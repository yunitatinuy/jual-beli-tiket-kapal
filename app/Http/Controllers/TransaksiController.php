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
        $pesanans = Pesanan::with(['user', 'penumpang'])
                    ->where('ID_User', $user->id)
                    ->orderByDesc('Tanggal_Pesanan')
                    ->paginate(10); // Ubah angka 10 sesuai dengan jumlah data yang ingin ditampilkan per halaman

        return view('pengguna.transaksi', compact('pesanans'));
    }
}
