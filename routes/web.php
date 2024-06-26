<?php

use App\Http\Controllers\API\RegisterController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// Controllerr
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InformasiController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\TransaksiController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

// Livewire Controller
use App\Livewire\AdminDashboard;
use App\Livewire\Kapal;
use App\Livewire\Pelabuhan;
use App\Livewire\Pengguna;
use App\Livewire\Rute;
use App\Livewire\Harga;
use App\Livewire\Tiket;
use App\Livewire\Pesanan;

Route::get('/', function () {
    return view('landing');
});

// Login
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

// Logout
Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');

// pengguna---------
Route::middleware(['auth', 'verified', 'cekrole:user'])->group(function () {
    Route::get('/dashboard_pengguna', [DashboardController::class, 'dashboard']);
    Route::get('/informasi', [InformasiController::class, 'informasi']);
    Route::get('/pembayaran', [PembayaranController::class, 'pembayaran']);
    Route::get('/transaksi', [TransaksiController::class, 'transaksi']);

    Route::get('/profil', function () {
        return view('/pengguna/profil');
    });

    Route::get('/sekalipergi', function () {
        return view('/pengguna/sekalipergi');
    });

    Route::get('/pergipulang', function () {
        return view('/pengguna/pergipulang');
    });

    Route::get('/pesantiket', function () {
        return view('/pengguna/pesantiket');
    });

    Route::get('/pesantiket2', function () {
        return view('/pengguna/pesantiket2orang');
    });
});

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
