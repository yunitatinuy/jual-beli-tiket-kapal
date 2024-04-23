<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function login()
    {
        return view('/pengguna/login',[
            'title' => 'login',
            'active' => 'login'
        ]);
    }
}
