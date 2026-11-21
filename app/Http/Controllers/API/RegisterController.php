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

        // Tidak langsung login otomatis agar setelah verifikasi email user diarahkan login
        if (Auth::check()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        return redirect('/register')
            ->with('status', 'registered')
            ->with('show_verify_modal', true)
            ->with('registered_email', $user->email)
            ->with('registered_user_id', $user->id);
    }

    /**
     * Kirim ulang link verifikasi email dari pop-up di halaman register.
     */
    public function resendVerification(Request $request)
    {
        $userId = $request->input('user_id');
        $email = $request->input('email');

        $user = null;
        if ($userId) {
            $user = User::find($userId);
        }
        if (! $user && $email) {
            $user = User::where('email', $email)->first();
        }

        if (! $user) {
            return redirect('/register')
                ->with('status', 'error')
                ->with('show_verify_modal', true)
                ->with('verify_message', 'Data pengguna tidak ditemukan. Silakan lakukan registrasi kembali.');
        }

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('login')
                ->with('success', 'Email ini sudah diverifikasi sebelumnya. Silakan login untuk melanjutkan.');
        }

        $user->sendEmailVerificationNotification();

        return redirect('/register')
            ->with('status', 'verification-link-sent')
            ->with('show_verify_modal', true)
            ->with('registered_email', $user->email)
            ->with('registered_user_id', $user->id);
    }
}
