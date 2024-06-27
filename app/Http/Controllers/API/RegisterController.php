<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
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
            'name' => 'required',
            'email' => 'required|email',
            'password' => [
                'required',
                'string',
                'min:5', // minimal 8 karakter
            ],
            'c_password' => 'required|same:password',
        ], [
            'name.required' => 'Nama harus diisi.',
            'email.required' => 'Email harus diisi.',
            'email.email' => 'Email harus berupa alamat email yang valid.',
            'email.unique' => 'Email ini sudah terdaftar.',
            'password.required' => 'Kata sandi harus diisi.',
            'password.min' => 'Kata sandi harus minimal 8 karakter.',
            'password.regex' => 'Kata sandi harus mengandung huruf kecil, huruf besar, angka, dan karakter spesial.',
            'c_password.required' => 'Konfirmasi kata sandi harus diisi.',
            'c_password.same' => 'Konfirmasi kata sandi harus sama dengan kata sandi.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);

        Auth::login($user);

        return redirect('/login')->with('success', 'Username dan password yang dimasukkan tidak sesuai');
    }
}
