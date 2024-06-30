<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pesanan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class PembayaranController extends Controller
{
    // Method untuk menampilkan halaman pembayaran
    public function pembayaran(Request $request)
    {
        $totalHarga = session('totalHarga', 0);
        $ID_Tiket = session('ID_Tiket');

        return view('pengguna.pembayaran', compact('totalHarga'));
    }

    // Method untuk memproses pembayaran
    public function processPayment(Request $request)
    {
        // Validate input
        $validatedData = $request->validate([
            'metode_bayar' => 'required',
        ]);

        // Retrieve necessary session data or other data
        $totalHarga = session('totalHarga', 0);
        $ID_Tiket = session('ID_Tiket');

        // Create new Pesanan instance
        $pesanan = new Pesanan();
        $pesanan->ID_User = Auth::id();
        $pesanan->ID_Tiket = $ID_Tiket;
        $pesanan->Tanggal_Pesanan = Carbon::now()->format('Y-m-d');
        $pesanan->Waktu = Carbon::now()->format('H:i:s');
        $pesanan->Total_Harga = $totalHarga; // Assign total price here
        $pesanan->Metode_Bayar = $validatedData['metode_bayar'];
        $pesanan->save();

        // Redirect or show success message
        return redirect('/invoice')->with('success', 'Pembayaran berhasil diproses!');
    }
}
