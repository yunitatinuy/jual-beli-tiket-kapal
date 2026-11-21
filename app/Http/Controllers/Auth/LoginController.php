<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    // Maksimal percobaan login gagal per email + IP sebelum dikunci sementara
    private const MAX_ATTEMPTS = 5;
    private const DECAY_SECONDS = 60;

    public function index()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email:rfc,filter',
            'password' => 'required',
        ], [
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'password.required' => 'Password wajib diisi',
        ]);

        $throttleKey = Str::transliterate(Str::lower($request->input('email')) . '|' . $request->ip());

        if (RateLimiter::tooManyAttempts($throttleKey, self::MAX_ATTEMPTS)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            return redirect('/login')
                ->with('loginError', "Terlalu banyak percobaan login. Coba lagi dalam {$seconds} detik.")
                ->withInput($request->only('email'));
        }

        $credentials = $request->only('email', 'password');

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            RateLimiter::hit($throttleKey, self::DECAY_SECONDS);

            return redirect('/login')
                ->with('loginError', 'Email atau password yang dimasukkan tidak sesuai')
                ->withInput($request->only('email'));
        }

        $user = Auth::user();

        // Cek apakah email user sudah diverifikasi
        if (! $user->hasVerifiedEmail()) {
            $userEmail = $user->email;
            $userId = $user->id;

            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect('/register')
                ->with('status', 'unverified')
                ->with('show_verify_modal', true)
                ->with('registered_email', $userEmail)
                ->with('registered_user_id', $userId)
                ->with('verify_message', 'Akun Anda belum diverifikasi. Silakan cek email Anda untuk melakukan verifikasi terlebih dahulu sebelum login.');
        }

        RateLimiter::clear($throttleKey);
        $request->session()->regenerate();

        return redirect()->intended(
            Auth::user()->isAdmin() ? '/admin/dashboard' : '/dashboard_pengguna'
        );
    }
}
