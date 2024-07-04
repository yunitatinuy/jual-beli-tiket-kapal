<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rute;

class LandingController extends Controller
{
    public function index()
    {
        $rutes = Rute::with(['pelabuhanAsal', 'pelabuhanTujuan'])
            ->get();

        // Grouping by route (Pelabuhan_Asal and Pelabuhan_Tujuan) and getting unique routes
        $uniqueRutes = $rutes->unique(function ($item) {
            return $item->Pelabuhan_Asal . ' - ' . $item->Pelabuhan_Tujuan;
        });

        return view('/landing', compact('uniqueRutes'));
    }
}
