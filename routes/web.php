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
Route::get('/register', [RegisterController::class, 'registrasi']);
Route::post('/register', [RegisterController::class, 'register']);

Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

// Menampilkan halaman verifikasi email
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware(['auth'])->name('verification.notice');

// verifikasi email
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    $user = Auth::user();

    switch ($user->role) {
        case 'admin':
            return redirect('/admin/dashboard');
        case 'user':
            return redirect('/dashboard_pengguna');
        default:
            return redirect('/');
    }
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();

    Session::flash('status', 'verification-link-sent');
    return back();
})->middleware(['auth', 'throttle:10,1'])->name('verification.send');

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

// Logout
Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');

// Pengguna
Route::middleware(['auth', 'verified', 'cekrole:user'])->group(function () {
    Route::get('/dashboard_pengguna', [DashboardController::class, 'dashboard']);
    Route::get('/informasi', [InformasiController::class, 'informasi']);
    Route::get('/transaksi', [TransaksiController::class, 'transaksi']);

    Route::get('/sekalipergi', [SekaliPergiController::class, 'index']);
    Route::get('/search', [SekaliPergiController::class, 'search'])->name('search');

    Route::get('/profil', [UserProfilController::class, 'edit'])->name('pengguna.profil');
    Route::post('/profil', [UserProfilController::class, 'update'])->name('profile.update');
    Route::get('/pesantiket', [PesanTiketController::class, 'create'])->name('pesantiket.create');
    Route::post('/pesantiket', [PesanTiketController::class, 'store'])->name('pesantiket.store');
    Route::get('/pesantiket', [PesanTiketController::class, 'index'])->name('pesantiket');
    Route::get('/invoices/{id}', [InvoiceController::class, 'show'])->name('invoices.show');
});

//     // Invoice
//     Route::post('/invoices/create/{pesananId}', [InvoiceController::class, 'createInvoice'])->name('invoices.create');
//     Route::get('/invoices/{id}', [InvoiceController::class, 'show'])->name('invoices.show');
//     Route::get('/invoice', function () {
//         return view('/invoices/show');
//     })->name('invoice');
//     Route::get('/pembayaran', [PembayaranController::class, 'pembayaran'])->name('pembayaran');
//     Route::post('/pembayaran', [PembayaranController::class, 'processPayment'])->name('pembayaran.process');
// });

// // Invoice routes
// Route::get('/invoices/{id}', [InvoiceController::class, 'show'])->name('invoices.show');
// // Invoice
// Route::post('/invoices/create/{pesananId}', [InvoiceController::class, 'createInvoice'])->name('invoices.create');
// Route::get('/invoices/{id}', [InvoiceController::class, 'show'])->name('invoices.show');
// Route::get('/invoice', function () {
//     return view('/invoices/show');
// });
