<?php

namespace App\Services;

use App\Models\Pelabuhan;
use App\Models\Rute;
use Illuminate\Support\Collection;

/** Data jadwal untuk halaman publik/pengguna: hanya jadwal yang belum lewat. */
class JadwalService
{
    /** Lintasan (asal -> tujuan) unik yang masih punya jadwal mendatang, maksimal 6 rute diambil dari yang terlama. */
    public function rutePopuler(int $limit = 6): Collection
    {
        $ids = Rute::mendatang()
            ->select('Pelabuhan_Asal', 'Pelabuhan_Tujuan')
            ->selectRaw('MIN(ID_Rute) as min_id')
            ->groupBy('Pelabuhan_Asal', 'Pelabuhan_Tujuan')
            ->orderBy('min_id', 'asc')
            ->limit($limit)
            ->pluck('min_id');

        if ($ids->isEmpty()) {
            return collect();
        }

        return Rute::with(['kapal', 'pelabuhanAsal', 'pelabuhanTujuan', 'tiket', 'harga'])
            ->whereIn('ID_Rute', $ids)
            ->orderBy('ID_Rute', 'asc')
            ->get()
            ->filter(fn ($r) => $r->pelabuhanAsal && $r->pelabuhanTujuan)
            ->take($limit)
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
