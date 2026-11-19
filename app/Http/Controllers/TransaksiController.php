<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Pesanan;

class TransaksiController extends Controller
{
    public function transaksi()
    {
        $user = Auth::user();

        // Menggunakan paginate untuk membatasi jumlah data yang ditampilkan per halaman
        $pesanans = Pesanan::with(['rute.pelabuhanAsal', 'rute.pelabuhanTujuan', 'rute.kapal'])
                    ->withCount('penumpangs')
                    ->where('ID_User', $user->id)
                    ->orderByDesc('ID_Pesanan')
                    ->paginate(10);

        return view('pengguna.transaksi', compact('pesanans'));
    }
}
