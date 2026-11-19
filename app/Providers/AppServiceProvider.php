<?php

namespace App\Providers;

use App\Http\Middleware\CekRole;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Livewire menjalankan ulang middleware route asli pada setiap request
        // /livewire/update, tetapi hanya middleware yang terdaftar sebagai "persistent".
        // Tanpa ini, cek role admin tidak ikut dijalankan saat aksi Livewire dipanggil.
        Livewire::addPersistentMiddleware([
            CekRole::class,
        ]);

        // Di produksi semua URL (link verifikasi email, callback Midtrans, aset) harus HTTPS,
        // termasuk saat aplikasi berada di belakang reverse proxy yang meneruskan HTTP.
        if (config('app.force_https')) {
            URL::forceScheme('https');
        }
    }
}
