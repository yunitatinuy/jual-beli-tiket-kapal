<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class LoginController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ], [
            'email.required' => 'Email wajib diisi',
            'password.required' => 'Password wajib diisi',
        ]);

        $infologin = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        if (Auth::attempt($infologin)) {
            if (Auth::user()->role == 'admin') {
                return redirect('/admin/dashboard');
            } elseif (Auth::user()->role == 'user') {
                return redirect('/dashboard_pengguna');
            }
        } else {
            return redirect('/login')->with('loginError', 'Username dan password yang dimasukkan tidak sesuai')->withInput();
        }

        function logout()
        {
            Auth::logout();
            return redirect('');
        }
    }
    // public function postlogin(Request $request)
    // {
    //     // dd($request->all());
    //     // Autentikasi pengguna
    //     if (Auth::attempt($request->only('email', 'password'))) {
    //         // Dapatkan pengguna yang diautentikasi
    //         $user = Auth::user();

    //         // Periksa peran pengguna dan arahkan ke halaman yang sesuai
    //         if ($user->role === 'admin') {
    //             return redirect('/dashboard');
    //         } elseif ($user->role === 'user') {
    //             return redirect('/dashboard_pengguna');
    //         }
    //     }

    //     // Jika autentikasi gagal, kembalikan ke halaman login
    //     return redirect('/login')->with('error', 'Invalid credentials');
    // }

}
