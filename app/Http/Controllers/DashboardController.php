<?php

namespace App\Http\Controllers;

use App\Services\JadwalService;

class DashboardController extends Controller
{
    public function dashboard(JadwalService $jadwal)
    {
        return view('pengguna.dashboard', [
            'uniqueRutes' => $jadwal->rutePopuler(),
            'namaPelabuhan' => $jadwal->namaPelabuhan(),
        ]);
    }
}
