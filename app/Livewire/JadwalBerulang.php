<?php

namespace App\Livewire;

use App\Models\Kapal;
use App\Models\Pelabuhan;
use App\Services\BookingService;
use App\Services\JadwalGenerator;
use Carbon\Carbon;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

/** Admin: buat banyak jadwal sekaligus (rute + harga + kuota tiket). */
class JadwalBerulang extends Component
{
    use LivewireAlert;

    public const HARI = [1 => 'Sen', 2 => 'Sel', 3 => 'Rab', 4 => 'Kam', 5 => 'Jum', 6 => 'Sab', 7 => 'Min'];

    public $kapal = '';
    public $asal = '';
    public $tujuan = '';
    public $mulai = '';
    public $selesai = '';
    public array $hari = [1, 2, 3, 4, 5, 6, 7];
    public array $jam = ['07:00'];

    /** @var array<string, array{aktif: bool, dewasa: array, anak: array}> */
    public array $tarif = [];

    public ?array $hasil = null;

    public function mount()
    {
        $this->mulai = now()->addDay()->toDateString();
        $this->selesai = now()->addDays(30)->toDateString();

        foreach (BookingService::KELAS as $kelas) {
            $this->tarif[$kelas] = [
                'aktif' => true,
                'dewasa' => ['harga' => '', 'kuota' => ''],
                'anak' => ['harga' => '', 'kuota' => ''],
            ];
        }
    }

    public function tambahJam()
    {
        if (count($this->jam) < 12) {
            $this->jam[] = '';
        }
    }

    public function hapusJam(int $i)
    {
        unset($this->jam[$i]);
        $this->jam = array_values($this->jam) ?: [''];
    }

    protected function rules(): array
    {
        $rules = [
            'kapal' => ['required', 'integer', 'exists:kapal,ID_Kapal'],
            'asal' => ['required', 'integer', 'exists:pelabuhan,ID_Pelabuhan'],
            'tujuan' => ['required', 'integer', 'exists:pelabuhan,ID_Pelabuhan', 'different:asal'],
            'mulai' => ['required', 'date_format:Y-m-d', 'after_or_equal:today'],
            'selesai' => ['required', 'date_format:Y-m-d', 'after_or_equal:mulai', 'before_or_equal:' . now()->addYear()->toDateString()],
            'hari' => ['required', 'array', 'min:1'],
            'hari.*' => ['integer', 'between:1,7'],
            'jam' => ['required', 'array', 'min:1'],
            'jam.*' => ['required', 'date_format:H:i', 'distinct'],
        ];

        foreach (BookingService::KELAS as $kelas) {
            if (! empty($this->tarif[$kelas]['aktif'])) {
                foreach (BookingService::TIPE as $tipe) {
                    $rules["tarif.$kelas.$tipe.harga"] = ['required', 'integer', 'min:1000', 'max:100000000'];
                    $rules["tarif.$kelas.$tipe.kuota"] = ['required', 'integer', 'min:0', 'max:10000'];
                }
            }
        }

        return $rules;
    }

    protected function messages(): array
    {
        return [
            'kapal.required' => 'Kapal harus dipilih.',
            'asal.required' => 'Pelabuhan asal harus dipilih.',
            'tujuan.required' => 'Pelabuhan tujuan harus dipilih.',
            'tujuan.different' => 'Pelabuhan tujuan harus berbeda dengan asal.',
            'mulai.after_or_equal' => 'Tanggal mulai tidak boleh sebelum hari ini.',
            'selesai.after_or_equal' => 'Tanggal selesai harus setelah tanggal mulai.',
            'selesai.before_or_equal' => 'Rentang maksimal 1 tahun ke depan.',
            'hari.required' => 'Pilih minimal satu hari.',
            'hari.min' => 'Pilih minimal satu hari.',
            'jam.*.required' => 'Jam harus diisi.',
            'jam.*.date_format' => 'Format jam JJ:MM.',
            'jam.*.distinct' => 'Jam tidak boleh sama.',
            'tarif.*.*.harga.required' => 'Harga wajib diisi.',
            'tarif.*.*.harga.integer' => 'Harga harus angka bulat.',
            'tarif.*.*.harga.min' => 'Minimal Rp1.000.',
            'tarif.*.*.kuota.required' => 'Kuota wajib diisi.',
            'tarif.*.*.kuota.integer' => 'Kuota harus angka bulat.',
            'tarif.*.*.kuota.min' => 'Tidak boleh negatif.',
        ];
    }

    private function tarifAktif(): array
    {
        $hasil = [];
        foreach (BookingService::KELAS as $kelas) {
            if (! empty($this->tarif[$kelas]['aktif'])) {
                foreach (BookingService::TIPE as $tipe) {
                    $hasil[$kelas][$tipe] = [
                        'harga' => (int) $this->tarif[$kelas][$tipe]['harga'],
                        'kuota' => (int) $this->tarif[$kelas][$tipe]['kuota'],
                    ];
                }
            }
        }

        return $hasil;
    }

    private function rencana(JadwalGenerator $generator): array
    {
        $jam = collect($this->jam)->filter(fn ($j) => preg_match('/^\d{2}:\d{2}$/', (string) $j))->unique()->sort()->values()->all();
        if (! $this->mulai || ! $this->selesai || $this->selesai < $this->mulai || ! $jam || ! $this->hari) {
            return [];
        }
        // Batasi pratinjau agar tidak berat jika rentang tanggal belum valid
        if (Carbon::parse($this->mulai)->diffInDays(Carbon::parse($this->selesai)) > 366) {
            return [];
        }

        return $generator->rencana($this->mulai, $this->selesai, array_map('intval', $this->hari), $jam);
    }

    public function simpan(JadwalGenerator $generator)
    {
        $this->hasil = null;
        $this->validate();

        $tarif = $this->tarifAktif();
        if (! $tarif) {
            $this->addError('tarif', 'Aktifkan minimal satu kelas.');

            return;
        }

        $rencana = $this->rencana($generator);
        if (! $rencana) {
            $this->addError('jam', 'Tidak ada jadwal yang bisa dibuat (semua tanggal/jam sudah lewat atau tidak cocok dengan hari yang dipilih).');

            return;
        }
        if (count($rencana) > JadwalGenerator::MAKS_JADWAL) {
            $this->addError('selesai', 'Terlalu banyak jadwal sekaligus (' . count($rencana) . '). Maksimal ' . JadwalGenerator::MAKS_JADWAL . ', persempit rentang tanggal.');

            return;
        }

        $this->hasil = $generator->buat((int) $this->kapal, (int) $this->asal, (int) $this->tujuan, $rencana, $tarif);

        $this->alert('success', $this->hasil['dibuat'] . ' jadwal berhasil dibuat.');
    }

    public function render(JadwalGenerator $generator)
    {
        $rencana = $this->rencana($generator);

        return view('livewire.jadwal-berulang', [
            'kapalList' => Kapal::orderBy('Nama_Kapal')->get(),
            'pelabuhanList' => Pelabuhan::orderBy('Nama_Pelabuhan')->get(),
            'jumlahRencana' => count($rencana),
            'contohRencana' => array_slice($rencana, 0, 6),
            'namaHari' => self::HARI,
            'kelasList' => BookingService::KELAS,
        ])->layout('components.adminLayout', ['title' => 'Jadwal Berulang']);
    }
}
