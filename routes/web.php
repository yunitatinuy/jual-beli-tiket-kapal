<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RegistrasiController;
use App\Http\Controllers\InformasiController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\LoginController;
<<<<<<< Updated upstream
use App\Http\Controllers\KapalController;
use App\Http\Controllers\TransaksiController;
=======
use App\Http\Controllers\TransaksiController;
use App\Livewire\Kapal;
// use App\Http\Controllers\KapalController;
use App\Http\Controllers\MailerController;
>>>>>>> Stashed changes
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

//Route::get('/welcome', function () {
//return view('welcome');
//});

//Route::get('/user/{id}', function ($id) {
//return 'User dengan ID ' . $id;
//});

//Route::prefix('admin')->group(function () {
//    Route::get('/dashboard', function () {
//        return 'Admin Dashboard';
//    });

//    Route::get('/users', function () {
//        return 'Admin Users';
//    });
//});

// Route::get('/listbarang/{id}/{nama}', function($id, $nama){
//    return view('list_barang', compact('id', 'nama'));
// });

//Route::get('/listbarang/{id}/{nama}', [ListBarangController::class, 'tampilkan']);

Route::get('/', function () {
    return view('landing');
});


Route::get('/admin/dashboard', function () {
    return view('/admin/dashboard');
});

//Route::get('/admin/kapal', function () {
//    return view('/admin/kapal');
//});

Route::get('/admin/pelabuhan', function () {
    return view('/admin/pelabuhan');
});

Route::get('/admin/rute', function () {
    return view('/admin/rute');
});

Route::get('/admin/tiket', function () {
    return view('/admin/tiket');
});

Route::get('/admin/pengguna', function () {
    return view('/admin/pengguna');
});

Route::get('/admin/pesanan', function () {
    return view('/admin/pesanan');
});

Route::get('/admin/tambahkapal', function () {
    return view('/admin/formulir/tambahkapal');
});

Route::get('/admin/tambahrute', function () {
    return view('/admin/formulir/tambahrute');
});

Route::get('/admin/tambahtiket', function () {
    return view('/admin/formulir/tambahtiket');
});

Route::get('/admin/tambahpelabuhan', function () {
    return view('/admin/formulir/tambahpelabuhan');
});

Route::get('/admin/tambahpengguna', function () {
    return view('/admin/formulir/tambahpengguna');
});

<<<<<<< Updated upstream
Route::get('/list_barang', [ListBarangController::class, 'tampilkan']);

//Route::get('/', [HomeController::class, 'index']);
Route::get('/contact', [HomeController::class, 'contact']);
=======
// Route::get('/list_barang', [ListBarangController::class, 'tampilkan']);

//Route::get('/', [HomeController::class, 'index']);
// Route::get('/contact', [HomeController::class, 'contact']);
>>>>>>> Stashed changes


// pengguna---------
Route::get('/login', [LoginController::class, 'login']);
Route::post('/login', [LoginController::class, 'authenticate']);

Route::get('/registrasi', [RegistrasiController::class, 'registrasi']);
Route::post('/registrasi', [RegistrasiController::class, 'store']);

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
Route::get('/admin/kapal', [KapalController::class, 'kapal']);
Route::get('/admin/tambahkapal', [KapalController::class, 'tambah'])->name('tambah');
Route::post('admin/tambahkapal', [KapalController::class, 'simpan']);
Route::get('/delete/{id}', [ProductController::class, 'delete'])->name('delete');
Route::get('/edit/{id}', [ProductController::class, 'edit'])->name('edit');
Route::post('/edit/{id}', [ProductController::class, 'update']);