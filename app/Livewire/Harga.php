<?php

namespace App\Livewire;

use App\Models\Harga as ModelsHarga;
use App\Models\Rute as ModelsRute;
use App\Services\BookingService;
use Illuminate\Validation\Rule;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithPagination;

class Harga extends Component
{
    use WithPagination;
    use LivewireAlert;

    public $ID_Rute;
    public $Tipe_Penumpang;
    public $Kelas;
    public $Harga;

    public $search;
    public $updateData = false;
    public $hargaId;

    /** Tanggal untuk menyaring pilihan rute (dropdown hanya berisi jadwal pada tanggal ini). */
    public $tanggalRute;

    public function mount()
    {
        $this->tanggalRute = now()->toDateString();
    }

    public function updatedTanggalRute()
    {
        $this->ID_Rute = null;
    }

    protected function rules(): array
    {
        return [
            'ID_Rute' => ['required', 'integer', 'exists:rute,ID_Rute'],
            'Tipe_Penumpang' => ['required', Rule::in(BookingService::TIPE)],
            'Kelas' => [
                'required',
                Rule::in(BookingService::KELAS),
                // Satu rute hanya boleh punya satu harga untuk tiap kombinasi tipe + kelas
                Rule::unique('harga', 'Kelas')
                    ->where('ID_Rute', $this->ID_Rute)
                    ->where('Tipe_Penumpang', $this->Tipe_Penumpang)
                    ->ignore($this->hargaId, 'ID_Harga'),
            ],
            'Harga' => ['required', 'integer', 'min:1000', 'max:100000000'],
        ];
    }

    protected function messages(): array
    {
        return [
            'ID_Rute.required' => 'Rute harus dipilih.',
            'ID_Rute.exists' => 'Rute tidak ditemukan.',
            'Tipe_Penumpang.required' => 'Tipe Penumpang harus dipilih.',
            'Tipe_Penumpang.in' => 'Tipe Penumpang harus dewasa atau anak.',
            'Kelas.required' => 'Kelas harus dipilih.',
            'Kelas.in' => 'Kelas harus VIP atau Ekonomi.',
            'Kelas.unique' => 'Rute ini sudah punya harga untuk tipe penumpang dan kelas tersebut.',
            'Harga.required' => 'Harga harus diisi.',
            'Harga.integer' => 'Harga harus berupa angka bulat (tanpa titik/koma).',
            'Harga.min' => 'Harga minimal Rp1.000.',
            'Harga.max' => 'Harga terlalu besar.',
        ];
    }

    private function data(): array
    {
        return [
            'ID_Rute' => (int) $this->ID_Rute,
            'Tipe_Penumpang' => $this->Tipe_Penumpang,
            'Kelas' => $this->Kelas,
            'Harga' => (int) $this->Harga,
        ];
    }

    public function store()
    {
        $this->validate();

        ModelsHarga::create($this->data());

        $this->alert('success', 'Data Berhasil Ditambahkan!');
        $this->clear();
    }

    public function edit($ID_Harga)
    {
        $harga = ModelsHarga::find($ID_Harga);
        if (! $harga) {
            $this->alert('error', 'Data Tidak Ditemukan!');

            return;
        }

        $this->hargaId = $harga->ID_Harga;
        $this->ID_Rute = $harga->ID_Rute;
        $this->tanggalRute = $harga->rute ? \Carbon\Carbon::parse($harga->rute->Tanggal)->toDateString() : $this->tanggalRute;
        $this->Tipe_Penumpang = strtolower((string) $harga->Tipe_Penumpang);
        $this->Kelas = $harga->Kelas;
        $this->Harga = $harga->Harga;
        $this->updateData = true;
    }

    public function update()
    {
        $this->validate();

        $harga = ModelsHarga::find($this->hargaId);
        if (! $harga) {
            $this->alert('error', 'Data Tidak Ditemukan!');
            $this->clear();

            return;
        }

        // Rute harga tidak boleh dipindah jika sudah dipakai tiket (tiket menunjuk ke rute lama)
        if ((int) $harga->ID_Rute !== (int) $this->ID_Rute && $harga->tikets()->exists()) {
            $this->addError('ID_Rute', 'Harga ini sudah dipakai tiket, rutenya tidak bisa diganti. Buat harga baru untuk rute lain.');

            return;
        }

        // Harga yang sudah dibayar pembeli tersimpan di data penumpang, jadi aman diubah
        $harga->update($this->data());

        $this->alert('success', 'Data Berhasil Diupdate!');
        $this->clear();
    }

    public function delete($ID_Harga)
    {
        $harga = ModelsHarga::find($ID_Harga);
        if (! $harga) {
            $this->alert('error', 'Data Tidak Ditemukan!');

            return;
        }

        if ($harga->tikets()->exists()) {
            $this->alert('error', 'Harga masih dipakai tiket. Hapus tiketnya terlebih dahulu.');

            return;
        }

        $harga->delete();
        $this->alert('success', 'Data Berhasil Dihapus!');
    }

    public function cancel()
    {
        $this->clear();
    }

    public function clear()
    {
        $this->reset('ID_Rute', 'Tipe_Penumpang', 'Kelas', 'Harga', 'hargaId', 'updateData');
        $this->tanggalRute ??= now()->toDateString();
        $this->resetValidation();
        $this->resetPage();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $search = '%' . $this->search . '%';
        $data = ModelsHarga::with(['rute.pelabuhanAsal', 'rute.pelabuhanTujuan'])
            ->where(function ($q) use ($search) {
                $q->where('Tipe_Penumpang', 'like', $search)
                    ->orWhere('Kelas', 'like', $search)
                    ->orWhere('Harga', 'like', $search)
                    ->orWhereHas('rute.pelabuhanAsal', fn ($p) => $p->where('Nama_Pelabuhan', 'like', $search))
                    ->orWhereHas('rute.pelabuhanTujuan', fn ($p) => $p->where('Nama_Pelabuhan', 'like', $search));
            })
            ->orderBy('ID_Harga', 'desc')
            ->paginate(5);

        // Pilihan rute: jadwal pada tanggal yang dipilih, ditambah rute yang sedang diedit.
        // Disaring per tanggal supaya dropdown tidak berisi ribuan jadwal hasil generator.
        $dataRute = ModelsRute::with(['pelabuhanAsal', 'pelabuhanTujuan'])
            ->where(fn ($q) => $q->where('Tanggal', $this->tanggalRute ?: now()->toDateString())
                ->when($this->ID_Rute, fn ($w) => $w->orWhere('ID_Rute', $this->ID_Rute)))
            ->orderBy('Tanggal')->orderBy('Jam')
            ->get();

        return view('livewire.harga', [
            'dataHarga' => $data,
            'dataRute' => $dataRute,
            'kelasOptions' => BookingService::KELAS,
        ])->layout('components.adminHarga');
    }
}
