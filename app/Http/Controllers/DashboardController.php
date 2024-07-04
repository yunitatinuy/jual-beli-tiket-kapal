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
        $kapals = Kapal::all();
        $rutes = Rute::with(['pelabuhanAsal', 'pelabuhanTujuan'])->get();
        $pelabuhans = Pelabuhan::all();
        $harga = Harga::all();
        $tiket = Tiket::with(['harga', 'rute'])->get();

        $uniqueRutes = $rutes->unique(function ($item) {
            return $item->pelabuhanAsal->Nama_Pelabuhan . ' - ' . $item->pelabuhanTujuan->Nama_Pelabuhan;
        });

        // Return view with data
        return view('pengguna.dashboard', [
            'kapals' => $kapals,
            'rutes' => $rutes,
            'pelabuhans' => $pelabuhans,
            'hargas' => $harga,
            'tikets' => $tiket,
            'uniqueRutes' => $uniqueRutes,
        ]);
    }
}

?>
