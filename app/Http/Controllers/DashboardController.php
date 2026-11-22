<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Services\JadwalService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function dashboard(JadwalService $jadwal)
    {
        $perjalananMendatang = null;
        if (Auth::check()) {
            $pesanan = Pesanan::with(['rute.kapal', 'rute.pelabuhanAsal', 'rute.pelabuhanTujuan', 'penumpangs'])
                ->where('ID_User', Auth::id())
                ->where('Status', Pesanan::STATUS_LUNAS)
                ->whereHas('rute', function ($q) {
                    $q->where('Tanggal', '>=', now()->toDateString());
                })
                ->get()
                ->sortBy(function ($p) {
                    return $p->rute ? Carbon::parse($p->rute->Tanggal . ' ' . $p->rute->Jam)->timestamp : PHP_INT_MAX;
                })
                ->first();

            if ($pesanan && $pesanan->rute) {
                $r = $pesanan->rute;
                $tglBerangkat = Carbon::parse($r->Tanggal)->setTimeFrom(Carbon::parse($r->Jam))->locale('id');
                $jamTiba = !empty($r->Jam_Tiba) ? Carbon::parse($r->Jam_Tiba)->format('H.i') . ' WIB' : '-';
                $tglTiba = !empty($r->Tanggal_Tiba) ? Carbon::parse($r->Tanggal_Tiba)->locale('id')->translatedFormat('d M Y') : '';

                $perjalananMendatang = (object) [
                    'id_pesanan' => $pesanan->ID_Pesanan,
                    'nama_kapal' => $r->kapal->Nama_Kapal ?? '-',
                    'kelas' => $pesanan->Kelas ?? '-',
                    'jumlah' => $pesanan->penumpangs->count() ?: ($pesanan->Jumlah_Dewasa + $pesanan->Jumlah_Anak),
                    'kode' => $pesanan->Kode_Booking ?? $pesanan->ID_Pesanan,
                    'jam_berangkat' => $tglBerangkat->format('H.i') . ' WIB',
                    'asal' => $r->pelabuhanAsal->Nama_Pelabuhan ?? '-',
                    'tgl_berangkat' => $tglBerangkat->translatedFormat('d M Y'),
                    'durasi' => $r->Durasi ?? '',
                    'jam_tiba' => $jamTiba,
                    'tujuan' => $r->pelabuhanTujuan->Nama_Pelabuhan ?? '-',
                    'tgl_tiba' => $tglTiba,
                ];
            }
        }

        return view('pengguna.dashboard', [
            'uniqueRutes' => $jadwal->rutePopuler(),
            'namaPelabuhan' => $jadwal->namaPelabuhan(),
            'perjalananMendatang' => $perjalananMendatang,
        ]);
    }
}
