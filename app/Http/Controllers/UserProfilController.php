<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class UserProfilController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        return view('pengguna.profil', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $gantiPassword = $request->filled('password');
        $gantiEmail = strcasecmp((string) $request->input('email'), $user->email) !== 0;

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email:rfc,filter|max:255|unique:users,email,' . $user->id,
        ];
        if ($gantiPassword) {
            $rules['password'] = User::passwordRules();
        }
        // Ganti password atau email harus membuktikan pemilik akun dengan password saat ini,
        // supaya sesi yang tertinggal login di perangkat lain tidak bisa mengambil alih akun.
        if ($gantiPassword || $gantiEmail) {
            $rules['current_password'] = 'required|current_password';
        }

        $validator = Validator::make($request->all(), $rules, User::passwordMessages() + [
            'current_password.required' => 'Masukkan password saat ini untuk mengganti email atau password.',
            'current_password.current_password' => 'Password saat ini salah.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->except('password', 'password_confirmation', 'current_password'));
        }

        $user->name = $request->input('name');
        $user->email = $request->input('email');

        if ($gantiPassword) {
            $user->password = $request->input('password');
            $user->setRememberToken(Str::random(60));
        }

        // Email baru harus diverifikasi ulang
        $emailChanged = $user->isDirty('email');
        if ($emailChanged) {
            $user->email_verified_at = null;
        }

        $user->save();

        if ($emailChanged) {
            $user->sendEmailVerificationNotification();

            return redirect()->route('verification.notice')
                ->with('status', 'verification-link-sent');
        }

        return redirect()->route('pengguna.profil')->with('success', 'Data Profil berhasil diupdate.');
    }
}
