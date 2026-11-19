<?php

namespace App\Livewire;

use App\Models\Pesanan;
use App\Models\Rute;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

/** Ringkasan operasional untuk admin: penjualan, pembayaran, dan keberangkatan hari ini. */
class AdminDashboard extends Component
{
    public function render()
    {
        $hariIni = now()->toDateString();
        $awalBulan = now()->startOfMonth();

        $lunas = fn () => Pesanan::where('Status', Pesanan::STATUS_LUNAS);

        $ringkasan = [
            'pendapatanBulanIni' => (int) $lunas()->where('Dibayar_Pada', '>=', $awalBulan)->sum('Total_Harga'),
            'pendapatanTotal' => (int) $lunas()->sum('Total_Harga'),
            'pesananHariIni' => Pesanan::whereDate('Tanggal_Pesanan', $hariIni)->count(),
            'lunasHariIni' => $lunas()->whereDate('Dibayar_Pada', $hariIni)->count(),
            'menungguBayar' => Pesanan::where('Status', Pesanan::STATUS_MENUNGGU_PEMBAYARAN)->count(),
            'totalPengguna' => User::where('role', 'pengguna')->count(),
        ];

        // Keberangkatan hari ini: jumlah penumpang lunas dan sisa kursi per jadwal
        $keberangkatan = Rute::with(['kapal', 'pelabuhanAsal', 'pelabuhanTujuan'])
            ->select('rute.*')
            ->where('Tanggal', $hariIni)
            ->withSum('tiket as sisa_kursi', 'Jumlah_Tiket')
            ->addSelect(['penumpang_lunas' => DB::table('penumpang')
                ->join('pesanan', 'pesanan.ID_Pesanan', '=', 'penumpang.ID_Pesanan')
                ->whereColumn('pesanan.ID_Rute', 'rute.ID_Rute')
                ->where('pesanan.Status', Pesanan::STATUS_LUNAS)
                ->selectRaw('count(*)')])
            ->orderBy('Jam')
            ->get();

        $pesananTerbaru = Pesanan::with(['user', 'rute.pelabuhanAsal', 'rute.pelabuhanTujuan'])
            ->orderByDesc('ID_Pesanan')
            ->limit(5)
            ->get();

        return view('livewire.admin-dashboard', compact('ringkasan', 'keberangkatan', 'pesananTerbaru'))
            ->layout('components.adminDashboard');
    }
}
