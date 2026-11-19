<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * `php artisan db:seed` hanya membuat akun admin (aman untuk produksi).
     * Data contoh (pelabuhan, kapal, rute, harga, tiket) dibuat terpisah:
     * `php artisan db:seed --class=DemoDataSeeder` (hanya di luar produksi).
     */
    public function run(): void
    {
        $this->call(AdminSeeder::class);
    }
}
