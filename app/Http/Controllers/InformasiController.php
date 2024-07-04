<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rute;
use Carbon\Carbon;

class InformasiController extends Controller
{
    public function index()
    {
        $rutes = Rute::with(['kapal', 'pelabuhanAsal', 'pelabuhanTujuan'])
            ->orderBy('Pelabuhan_Asal')
            ->orderBy('Pelabuhan_Tujuan')
            ->orderBy('Tanggal')
            ->orderBy('Jam')
            ->get();

        $groupedRutes = $rutes->groupBy(function ($item, $key) {
            return $item->pelabuhanAsal->Nama_Pelabuhan . ' - ' . $item->pelabuhanTujuan->Nama_Pelabuhan;
        });

        return view('pengguna/informasi', compact('groupedRutes'));
    }
}
