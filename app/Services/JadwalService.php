<?php

namespace App\Services;

use App\Models\Pelabuhan;
use App\Models\Rute;
use Illuminate\Support\Collection;

/** Data jadwal untuk halaman publik/pengguna: hanya jadwal yang belum lewat. */
class JadwalService
{
    /** Lintasan (asal -> tujuan) unik yang masih punya jadwal mendatang. */
    public function rutePopuler(int $limit = 9): Collection
    {
        return Rute::with(['pelabuhanAsal', 'pelabuhanTujuan'])
            ->mendatang()
            ->select('Pelabuhan_Asal', 'Pelabuhan_Tujuan')
            ->selectRaw('COUNT(*) as jumlah_jadwal')
            ->groupBy('Pelabuhan_Asal', 'Pelabuhan_Tujuan')
            ->orderByDesc('jumlah_jadwal')
            ->limit($limit)
            ->get()
            ->filter(fn ($r) => $r->pelabuhanAsal && $r->pelabuhanTujuan)
            ->values();
    }

    /** Jadwal mendatang (default 30 hari) dikelompokkan per lintasan. */
    public function jadwalMendatang(int $hari = 30): Collection
    {
        return Rute::with(['kapal', 'pelabuhanAsal', 'pelabuhanTujuan'])
            ->mendatang()
            ->where('Tanggal', '<=', now()->addDays($hari)->toDateString())
            ->orderBy('Tanggal')
            ->orderBy('Jam')
            ->get()
            ->reject(fn (Rute $r) => app(BookingService::class)->sudahBerangkat($r))
            ->filter(fn (Rute $r) => $r->pelabuhanAsal && $r->pelabuhanTujuan)
            ->groupBy(fn (Rute $r) => $r->pelabuhanAsal->Nama_Pelabuhan . ' - ' . $r->pelabuhanTujuan->Nama_Pelabuhan)
            ->sortKeys();
    }

    /** Nama pelabuhan untuk saran isian form pencarian. */
    public function namaPelabuhan(): Collection
    {
        return Pelabuhan::orderBy('Nama_Pelabuhan')->pluck('Nama_Pelabuhan');
    }
}
