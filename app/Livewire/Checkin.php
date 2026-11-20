<?php

namespace App\Livewire;

use App\Models\Pesanan;
use App\Models\Rute;
use App\Services\BookingException;
use App\Services\CheckinService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Url;
use Livewire\Component;

/** Admin: pindai QR / ketik kode booking, lalu catat penumpang yang naik kapal. */
class Checkin extends Component
{
    #[Url]
    public string $kode = '';

    public string $input = '';

    public ?string $pesan = null;

    public string $jenisPesan = 'info';

    public function mount(CheckinService $checkin)
    {
        $this->kode = $checkin->ambilKode($this->kode);
    }

    /** Dipanggil dari form ketik manual dan dari scanner kamera. */
    public function cari(CheckinService $checkin, ?string $hasilScan = null)
    {
        $this->kode = $checkin->ambilKode($hasilScan ?? $this->input);
        $this->input = '';
        $this->pesan = null;
    }

    public function checkinSemua(CheckinService $checkin)
    {
        $this->jalankan($checkin, null);
    }

    public function checkinSatu(CheckinService $checkin, int $penumpangId)
    {
        $this->jalankan($checkin, $penumpangId);
    }

    public function batalCheckin(CheckinService $checkin, int $penumpangId)
    {
        $pesanan = $this->pesanan();
        if ($pesanan && $checkin->batal($pesanan, $penumpangId)) {
            $this->beriPesan('Check-in penumpang dibatalkan.', 'info');
        }
    }

    private function jalankan(CheckinService $checkin, ?int $penumpangId): void
    {
        $pesanan = $this->pesanan();
        if (! $pesanan) {
            return;
        }

        try {
            $jumlah = $checkin->checkin($pesanan, Auth::user(), $penumpangId);
            $this->beriPesan($jumlah ? "{$jumlah} penumpang berhasil check-in." : 'Penumpang sudah check-in sebelumnya.', $jumlah ? 'sukses' : 'info');
        } catch (BookingException $e) {
            $this->beriPesan($e->getMessage(), 'gagal');
        }
    }

    private function beriPesan(string $pesan, string $jenis): void
    {
        $this->pesan = $pesan;
        $this->jenisPesan = $jenis;
    }

    private function pesanan(): ?Pesanan
    {
        return $this->kode === ''
            ? null
            : Pesanan::with(['user', 'penumpangs', 'rute.kapal', 'rute.pelabuhanAsal', 'rute.pelabuhanTujuan'])
                ->where('Kode_Booking', $this->kode)
                ->first();
    }

    public function render(CheckinService $checkin)
    {
        $pesanan = $this->pesanan();

        // Rekap keberangkatan hari ini: penumpang lunas vs sudah check-in
        $hariIni = Rute::with(['pelabuhanAsal', 'pelabuhanTujuan', 'kapal'])
            ->select('rute.*')
            ->where('Tanggal', now()->toDateString())
            ->addSelect([
                'total_lunas' => DB::table('penumpang')->join('pesanan', 'pesanan.ID_Pesanan', '=', 'penumpang.ID_Pesanan')
                    ->whereColumn('pesanan.ID_Rute', 'rute.ID_Rute')->where('pesanan.Status', Pesanan::STATUS_LUNAS)->selectRaw('count(*)'),
                'total_checkin' => DB::table('penumpang')->join('pesanan', 'pesanan.ID_Pesanan', '=', 'penumpang.ID_Pesanan')
                    ->whereColumn('pesanan.ID_Rute', 'rute.ID_Rute')->where('pesanan.Status', Pesanan::STATUS_LUNAS)
                    ->whereNotNull('penumpang.Checkin_Pada')->selectRaw('count(*)'),
            ])
            ->orderBy('Jam')
            ->get();

        return view('livewire.checkin', [
            'pesanan' => $pesanan,
            'alasan' => $pesanan ? $checkin->alasanDitolak($pesanan) : null,
            'berangkat' => $pesanan ? $checkin->waktuBerangkat($pesanan) : null,
            'hariIni' => $hariIni,
        ])->layout('components.adminLayout', ['title' => 'Check-in']);
    }
}
