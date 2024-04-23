<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegistrasiController extends Controller
{
    public function registrasi()
    {
        return view('/pengguna/registrasi', [
            'title' => 'Register',
            'active' => 'register'
        ]);
    }
}