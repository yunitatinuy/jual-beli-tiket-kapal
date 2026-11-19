<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function registrasi()
    {
        return view('/auth/registrasi', [
            'title' => 'Register',
            'active' => 'register'
        ]);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email:rfc,filter|max:255|unique:users,email',
            'password' => [
                'required',
                'string',
                \Illuminate\Validation\Rules\Password::min(8)->letters()->numbers(),
            ],
            'c_password' => 'required|same:password',
        ], [
            'name.required' => 'Nama harus diisi.',
            'email.required' => 'Email harus diisi.',
            'email.email' => 'Email harus berupa alamat email yang valid.',
            'email.unique' => 'Email ini sudah terdaftar.',
            'password.required' => 'Kata sandi harus diisi.',
            'password.min' => 'Kata sandi harus minimal 8 karakter.',
            'password.letters' => 'Kata sandi harus mengandung huruf.',
            'password.numbers' => 'Kata sandi harus mengandung angka.',
            'c_password.required' => 'Konfirmasi kata sandi harus diisi.',
            'c_password.same' => 'Konfirmasi kata sandi harus sama dengan kata sandi.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Hanya ambil field yang diizinkan; role selalu 'pengguna' (tidak bisa diisi dari form)
        $user = new User([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
        ]);
        $user->role = 'pengguna';
        $user->save();

        // Memicu pengiriman email verifikasi
        event(new Registered($user));

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('verification.notice')
            ->with('status', 'registered');
    }
}
