<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

/** Lupa password: kirim link reset ke email, lalu set password baru lewat link itu. */
class PasswordResetController extends Controller
{
    public function request()
    {
        return view('auth.lupa-password');
    }

    public function email(Request $request)
    {
        $request->validate(
            ['email' => 'required|email:rfc,filter'],
            ['email.required' => 'Email wajib diisi.', 'email.email' => 'Format email tidak valid.']
        );

        $status = Password::sendResetLink($request->only('email'));

        if ($status === Password::RESET_THROTTLED) {
            return back()->withInput()->withErrors(['email' => 'Tunggu sebentar sebelum meminta link reset lagi.']);
        }

        // Pesan sama untuk email terdaftar maupun tidak, supaya daftar email pengguna tidak bisa ditebak
        return back()->with('status', 'Jika email tersebut terdaftar, link untuk mengatur ulang password sudah kami kirim. Cek inbox atau folder spam.');
    }

    public function reset(Request $request, string $token)
    {
        return view('auth.reset-password', ['token' => $token, 'email' => $request->query('email')]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email:rfc,filter',
            'password' => User::passwordRules(),
        ], User::passwordMessages());

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => $password,
                    'remember_token' => Str::random(60),
                ])->save();

                // Link reset dari email sekaligus membuktikan kepemilikan email
                if (! $user->hasVerifiedEmail()) {
                    $user->markEmailAsVerified();
                }

                event(new PasswordReset($user));
            }
        );

        if ($status !== Password::PASSWORD_RESET) {
            return back()->withInput($request->only('email'))
                ->withErrors(['email' => 'Link reset tidak valid atau sudah kedaluwarsa. Silakan minta link baru.']);
        }

        return redirect()->route('login')->with('succes', 'Password berhasil diubah. Silakan login dengan password baru.');
    }
}
