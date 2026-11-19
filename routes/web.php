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

// Landing Page
Route::get('/', [LandingController::class, 'index']);

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

// Menampilkan halaman verifikasi email
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware(['auth'])->name('verification.notice');

// verifikasi email
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    return redirect($request->user()->isAdmin() ? '/admin/dashboard' : '/dashboard_pengguna');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return back()->with('status', 'verification-link-sent');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

// Admin Auth
Route::prefix('admin')->middleware(['auth', 'verified', 'cekrole:admin'])->group(function () {
    Route::get('/dashboard', AdminDashboard::class);
    Route::get('/kapal', Kapal::class);
    Route::get('/pelabuhan', Pelabuhan::class);
    Route::get('/tiket', Tiket::class);
    Route::get('/harga', Harga::class);
    Route::get('/rute', Rute::class);
    Route::get('/pengguna', Pengguna::class);
    Route::get('/pesanan', Pesanan::class);
});

// Webhook Midtrans (tanpa login; diverifikasi lewat signature). Daftarkan URL ini di
// dashboard Midtrans > Settings > Payment > Notification URL: https://domain-anda/midtrans/notification
Route::post('/midtrans/notification', [PembayaranController::class, 'notification'])->middleware('throttle:120,1');

// Logout
Route::post('/logout', [LogoutController::class, 'logout'])->middleware('auth')->name('logout');

// Pengguna
Route::middleware(['auth', 'verified', 'cekrole:pengguna'])->group(function () {
    Route::get('/dashboard_pengguna', [DashboardController::class, 'dashboard']);
    Route::get('/informasi', [InformasiController::class, 'index']);
    Route::get('/transaksi', [TransaksiController::class, 'transaksi']);

    Route::get('/sekalipergi', [SekaliPergiController::class, 'index']);
    Route::get('/search', [SekaliPergiController::class, 'search'])->name('search');

    Route::get('/profil', [UserProfilController::class, 'edit'])->name('pengguna.profil');
    Route::post('/profil', [UserProfilController::class, 'update'])->name('profile.update');
    Route::get('/pesantiket', [PesanTiketController::class, 'index'])->name('pesantiket');
    Route::post('/pesantiket', [PesanTiketController::class, 'store'])->middleware('throttle:20,1')->name('pesantiket.store');
    Route::get('/invoices/{id}', [InvoiceController::class, 'show'])->name('invoices.show');
    Route::get('/invoice/pdf/{id}', [InvoiceController::class, 'generatePDF'])->name('invoice.pdf');

    // Pembayaran Midtrans
    Route::post('/pesanan/{id}/bayar', [PembayaranController::class, 'bayar'])->middleware('throttle:20,1')->name('pesanan.bayar');
    Route::post('/pesanan/{id}/cek-status', [PembayaranController::class, 'cekStatus'])->middleware('throttle:20,1')->name('pesanan.cek-status');
    Route::post('/pesanan/{id}/batal', [PembayaranController::class, 'batal'])->name('pesanan.batal');
});

