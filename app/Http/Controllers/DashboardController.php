<?php

namespace App\Http\Controllers;

use App\Models\Harga;
use App\Models\Rute;
use App\Models\Kapal;
use App\Models\Pelabuhan;
use App\Models\Tiket;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboard()
    {
        // Ambil data kapal, rute dan pelabuhan
        $kapals = Kapal::all();
        $rutes = Rute::with(['pelabuhanAsal', 'pelabuhanTujuan'])->get();
        $pelabuhans = Pelabuhan::all();
        $harga = Harga::all();
        $tiket = Tiket::with(['harga', 'rute'])->get();

        // Kirim data ke view
        return view('pengguna/dashboard', [
            'kapals' => $kapals,
            'rutes' => $rutes,
            'pelabuhans' => $pelabuhans,
            'hargas' => $harga,
            'tikets' => $tiket,
        ]);
    }
}
