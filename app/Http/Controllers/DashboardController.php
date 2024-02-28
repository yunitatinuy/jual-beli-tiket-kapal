<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    function tampilkan($paket, $harga)
    {
        return view('dashboard', ['paket' => $paket, 'harga' => $harga]);
    }
    // public function dashboard() {
    //     return view('dashboard');
    // }
}
