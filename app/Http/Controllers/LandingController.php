<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rute;

class LandingController extends Controller
{
    public function index()
    {
        // Ambil data rute dari database
        $rutes = Rute::with('pelabuhanAsal', 'pelabuhanTujuan')->get();

        // Kirim data ke view
        return view('/landing', ['rutes' => $rutes]);
    }
}
