<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AlbumController;
use App\Http\Controllers\BookingController;

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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/landing', [LandingController::class, 'index']);
Route::get('/login.blade.php', [LoginController::class, 'login']);
// Route::get('/dashboard.blade.php/{paket}/{harga}', function($paket, $harga){
//     return view('dashboard', compact('paket', 'harga'));
// });
Route::get('/dashboard.blade.php/{paket}/{harga}', [DashboardController::class, 'tampilkan']);
// [DashboardController::class, 'dashboard']);
Route::get('album_view/{id}/{nama}', [AlbumController::class, 'tampilkan']);
Route::get('/booking_view', [BookingController::class, 'tampilkan']);