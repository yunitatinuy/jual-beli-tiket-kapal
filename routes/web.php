<?php

use App\Livewire\Rute;
use App\Livewire\Harga;
use App\Livewire\Kapal;

// Livewire Controller
use App\Livewire\Tiket;
use App\Livewire\Pesanan;
use App\Livewire\Pengguna;
use App\Livewire\Pelabuhan;
use Illuminate\Http\Request;
use App\Livewire\AdminDashboard;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

// Controller
use App\Http\Controllers\InformasiController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\PesanTiketController;
use App\Http\Controllers\UserProfilController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\SekaliPergiController;
use App\Http\Controllers\API\RegisterController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\InvoiceController;

// Dashboard / Halaman Utama (Langsung ke dashboard pengguna, dapat diakses tamu)
Route::get('/', [DashboardController::class, 'dashboard'])->name('home');
Route::get('/dashboard_pengguna', [DashboardController::class, 'dashboard'])->name('pengguna.dashboard');

// Pencarian dan Informasi Jadwal (Dapat diakses tamu tanpa harus login)
Route::get('/informasi', [InformasiController::class, 'index'])->name('informasi');
Route::get('/search', [InformasiController::class, 'index'])->name('search');
Route::get('/sekalipergi', [InformasiController::class, 'index'])->name('sekalipergi');

// Registrasi dan Login
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'registrasi']);
    Route::post('/register', [RegisterController::class, 'register'])->middleware('throttle:10,1');

    Route::get('/login', [LoginController::class, 'index'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);

    // Lupa password
    Route::get('/lupa-password', [PasswordResetController::class, 'request'])->name('password.request');
    Route::post('/lupa-password', [PasswordResetController::class, 'email'])->middleware('throttle:5,1')->name('password.email');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'reset'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'update'])->middleware('throttle:10,1')->name('password.update');
});

// Verifikasi email & Notifikasi
Route::get('/email/verify', function () {
    if (Auth::check() && ! Auth::user()->hasVerifiedEmail()) {
        $email = Auth::user()->email;
        $id = Auth::user()->id;
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect('/register')
            ->with('show_verify_modal', true)
            ->with('registered_email', $email)
            ->with('registered_user_id', $id);
    }

    return redirect('/register');
})->name('verification.notice');

// Eksekusi tautan verifikasi dari email: setelah diverifikasi, wajib login terlebih dahulu
Route::get('/email/verify/{id}/{hash}', function (Request $request, $id, $hash) {
    $user = \App\Models\User::find($id);

    if (! $user) {
        return redirect()->route('login')->with('loginError', 'Pengguna tidak ditemukan.');
    }

    if (! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
        return redirect()->route('login')->with('loginError', 'Tautan verifikasi tidak valid atau telah kedaluwarsa.');
    }

    if (! $user->hasVerifiedEmail()) {
        $user->markEmailAsVerified();
        event(new \Illuminate\Auth\Events\Verified($user));
    }

    if (Auth::check()) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }

    return redirect()->route('login')->with('success', 'Email berhasil diverifikasi! Silakan login untuk melanjutkan ke akun Anda.');
})->middleware(['signed'])->name('verification.verify');

Route::post('/email/resend-verification', [RegisterController::class, 'resendVerification'])
    ->middleware('throttle:6,1')
    ->name('verification.resend');

Route::post('/email/verification-notification', function (Request $request) {
    if ($request->user()) {
        $request->user()->sendEmailVerificationNotification();
    }

    return back()->with('status', 'verification-link-sent');
})->middleware(['throttle:6,1'])->name('verification.send');

// Admin Auth
Route::prefix('admin')->middleware(['auth', 'verified', 'cekrole:admin'])->group(function () {
    Route::get('/dashboard', AdminDashboard::class);
    Route::get('/kapal', Kapal::class);
    Route::get('/pelabuhan', Pelabuhan::class);
    Route::get('/tiket', Tiket::class);
    Route::get('/harga', Harga::class);
    Route::get('/rute', Rute::class);
    Route::get('/jadwal-berulang', \App\Livewire\JadwalBerulang::class)->name('admin.jadwal-berulang');
    Route::get('/checkin', \App\Livewire\Checkin::class)->name('admin.checkin');
    Route::get('/pengguna', Pengguna::class);
    Route::get('/pesanan', Pesanan::class);
});

// Webhook Midtrans (tanpa login; diverifikasi lewat signature). Daftarkan URL ini di
// dashboard Midtrans > Settings > Payment > Notification URL: https://domain-anda/midtrans/notification
Route::post('/midtrans/notification', [PembayaranController::class, 'notification'])->middleware('throttle:120,1');

// Logout
Route::post('/logout', [LogoutController::class, 'logout'])->middleware('auth')->name('logout');

// Pengguna (Hanya untuk fitur transaksi & pemesanan tiket yang mewajibkan login)
Route::middleware(['auth', 'cekrole:pengguna'])->group(function () {
    Route::get('/transaksi', [TransaksiController::class, 'transaksi'])->name('transaksi');

    Route::get('/profil', [UserProfilController::class, 'edit'])->name('pengguna.profil');
    Route::post('/profil', [UserProfilController::class, 'update'])->name('profile.update');
    Route::get('/pesantiket', [PesanTiketController::class, 'index'])->name('pesantiket');
    Route::post('/pesantiket', [PesanTiketController::class, 'store'])->middleware('throttle:20,1')->name('pesantiket.store');
    Route::get('/pesanan/{id}/detail', [InvoiceController::class, 'detail'])->name('pesanan.detail');
    Route::get('/invoices/{id}', [InvoiceController::class, 'show'])->name('invoices.show');
    Route::get('/invoices/{id}/eticket', [InvoiceController::class, 'eticket'])->name('invoices.eticket');
    Route::get('/invoice/pdf/{id}', [InvoiceController::class, 'generatePDF'])->name('invoice.pdf');

    // Pembayaran Midtrans
    Route::post('/pesanan/{id}/bayar', [PembayaranController::class, 'bayar'])->middleware('throttle:20,1')->name('pesanan.bayar');
    Route::post('/pesanan/{id}/cek-status', [PembayaranController::class, 'cekStatus'])->middleware('throttle:20,1')->name('pesanan.cek-status');
    Route::post('/pesanan/{id}/batal', [PembayaranController::class, 'batal'])->name('pesanan.batal');
    Route::post('/pesanan/{id}/simulasi-bayar', [PembayaranController::class, 'simulasiSandbox'])->name('pesanan.simulasi-bayar');
});

