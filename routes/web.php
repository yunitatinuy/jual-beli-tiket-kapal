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

// Livewire
use App\Livewire\Kapal;
use App\Livewire\Pelabuhan;
use App\Livewire\Rute;
use App\Livewire\Tiket;

Route::get('/', function () {
    return view('landing');
});

Route::prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    });
    Route::get('/pelabuhan', function () {
        return view('admin.pelabuhan');
    });
    Route::get('/kapal', function () {
        return view('admin.kapal');
    });
    Route::get('/rute', function () {
        return view('admin.rute');
    });
    Route::get('/tiket', function () {
        return view('admin.tiket');
    });
    Route::get('/pengguna', function () {
        return view('admin.pengguna');
    });
    Route::get('/pesanan', function () {
        return view('admin.pesanan');
    });
    Route::get('/tambahkapal', function () {
        return view('admin.formulir.tambahkapal');
    });
    Route::get('/tambahrute', function () {
        return view('admin.formulir.tambahrute');
    });
    Route::get('/tambahtiket', function () {
        return view('admin.formulir.tambahtiket');
    });
    Route::get('/tambahpelabuhan', function () {
        return view('admin.formulir.tambahpelabuhan');
    });
    Route::get('/tambahpengguna', function () {
        return view('admin.formulir.tambahpengguna');
    });
});

// pengguna---------
Route::get('/login', [LoginController::class, 'login']);
Route::post('/login', [LoginController::class, 'authenticate']);

Route::get('/register', [RegisterController::class, 'registrasi']);
Route::post('/register', [RegisterController::class, 'register']);

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

//test kapal
Route::get('/admin/kapal', Kapal::class);
Route::get('/admin/pelabuhan', Pelabuhan::class);
Route::get('/admin/tiket', Tiket::class);
Route::get('/admin/rute', Rute::class);
// Route::get('/admin/kapal', [KapalController::class, 'kapal']);
// Route::get('/admin/tambahkapal', [KapalController::class, 'tambah'])->name('tambah');
// Route::post('admin/tambahkapal', [KapalController::class, 'simpan']);
// Route::get('/delete/{id}', [ProductController::class, 'delete'])->name('delete');
// Route::get('/edit/{id}', [ProductController::class, 'edit'])->name('edit');
// Route::post('/edit/{id}', [ProductController::class, 'update']);

Route::get('send-mail', [MailerController::class, 'index'])->name('send.mail');
Route::post('send-mail', [MailerController::class, 'store'])->name('send.email.post');
