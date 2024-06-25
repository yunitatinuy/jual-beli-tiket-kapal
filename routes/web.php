<?php

use App\Http\Controllers\API\RegisterController;
use Illuminate\Support\Facades\Route;

// Controllerr
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ListBarangController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RegistrasiController;
use App\Http\Controllers\InformasiController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\KapalController;
use App\Http\Controllers\MailerController;

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
Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
// Route::get('/login', function (){
//     return view('pengguna.login');
// })->name('login');

Route::get('/register', [RegisterController::class, 'registrasi']);
Route::post('/register', [RegisterController::class, 'register']);

// pengguna---------
Route::middleware(['auth', 'cekrole:user'])->group(function () {
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

// cek role admin
Route::prefix('admin')->middleware(['auth', 'cekrole:admin'])->group(function () {
    Route::get('/dashboard', AdminDashboard::class);
    Route::get('/kapal', Kapal::class);
    Route::get('/pelabuhan', Pelabuhan::class);
    Route::get('/tiket', Tiket::class);
    Route::get('/harga', Harga::class);
    Route::get('/rute', Rute::class);
    Route::get('/pengguna', Pengguna::class);
    Route::get('/pesanan', Pesanan::class);
});

// auth for logout - on progress
// Route::middleware(['auth', 'admin'])->group(function () {
//     Route::get('/dashboard', AdminDashboard::class);
//     Route::get('/kapal', Kapal::class);
//     Route::get('/pelabuhan', Pelabuhan::class);
//     Route::get('/tiket', Tiket::class);
//     Route::get('/rute', Rute::class);
//     Route::get('/pengguna', Pengguna::class);
//     Route::get('/pesanan', Pesanan::class);
//     // Tambahkan controller lain yang memerlukan otentikasi di sini
// });

Route::get('send-mail', [MailerController::class, 'index'])->name('send.mail');
Route::post('send-mail', [MailerController::class, 'store'])->name('send.email.post');
