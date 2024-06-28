<?php

namespace App\Http\Controllers;

use App\Models\Penumpang;
use Illuminate\Http\Request;

class PesanTiketController extends Controller
{
    // Method untuk menampilkan form input data penumpang
    public function create()
    {
        return view('pengguna.pesantiket');
    }

    // Method untuk menyimpan data penumpang ke database
    public function store(Request $request)
    {
        // Validasi input form
        $request->validate([
            'Nama_Lengkap' => 'required|string|max:255',
            'Telepon' => 'required|numeric',
            'Alamat' => 'required|string',
        ]);

        // Menyimpan data penumpang ke database
        Penumpang::create([
            'id' => auth()->user()->id,
            'Nama_Lengkap' => $request->Nama_Lengkap,
            'Telepon' => $request->Telepon,
            'Alamat' => $request->Alamat,
        ]);

        // Redirect ke halaman pembayaran dengan pesan sukses
        return redirect()->route('pengguna.pembayaran')->with('success', 'Data penumpang berhasil disimpan');
    }

    public function create(Request $request)
    {
        $asal = $request->input('asal');
        $tujuan = $request->input('tujuan');
        $tanggal = $request->input('tanggal');
        $kapal = $request->input('kapal');
        $jam = $request->input('jam');

        return view('pengguna.pesantiket', compact('asal', 'tujuan', 'tanggal', 'kapal', 'jam'));
    }
}
