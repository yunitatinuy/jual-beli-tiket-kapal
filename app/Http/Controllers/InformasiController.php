<?php

namespace App\Http\Controllers;

use App\Services\JadwalService;

class InformasiController extends Controller
{
    public function index(JadwalService $jadwal)
    {
        return view('pengguna.informasi', ['groupedRutes' => $jadwal->jadwalMendatang(30)]);
    }
}
