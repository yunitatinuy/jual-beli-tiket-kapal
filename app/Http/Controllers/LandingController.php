<?php

namespace App\Http\Controllers;

use App\Services\JadwalService;

class LandingController extends Controller
{
    public function index(JadwalService $jadwal)
    {
        return view('landing', ['uniqueRutes' => $jadwal->rutePopuler()]);
    }
}
