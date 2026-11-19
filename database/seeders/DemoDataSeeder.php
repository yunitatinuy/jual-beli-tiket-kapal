<?php

namespace Database\Seeders;

use App\Models\Harga;
use App\Models\Kapal;
use App\Models\Pelabuhan;
use App\Models\Rute;
use App\Models\Tiket;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Data contoh untuk mencoba alur pemesanan: 4 pelabuhan, 2 kapal, dan jadwal
 * pulang-pergi untuk 14 hari ke depan, masing-masing dengan harga dewasa/anak
 * untuk kelas VIP dan Ekonomi.
 *
 *   php artisan db:seed --class=DemoDataSeeder
 *
 * Tidak dijalankan di APP_ENV=production.
 */
class DemoDataSeeder extends Seeder
{
    private const HARGA = [
        // [Kelas, Tipe, Harga, Kuota]
        ['VIP', 'dewasa', 150000, 20],
        ['VIP', 'anak', 100000, 10],
        ['Ekonomi', 'dewasa', 75000, 80],
        ['Ekonomi', 'anak', 50000, 40],
    ];

    public function run(): void
    {
        if (app()->environment('production')) {
            $this->command?->error('DemoDataSeeder tidak boleh dijalankan di produksi.');

            return;
        }

        DB::transaction(function () {
            $pelabuhan = collect([
                ['Pelabuhan Merak', 'Cilegon'],
                ['Pelabuhan Bakauheni', 'Lampung Selatan'],
                ['Pelabuhan Ketapang', 'Banyuwangi'],
                ['Pelabuhan Gilimanuk', 'Jembrana'],
            ])->map(fn ($p) => Pelabuhan::firstOrCreate(['Nama_Pelabuhan' => $p[0]], ['Nama_Kota' => $p[1]]));

            $kapal = collect([
                ['KMP Nusantara Jaya', 'VIP', 200],
                ['KMP Samudra Indah', 'Ekonomi', 300],
            ])->map(fn ($k) => Kapal::firstOrCreate(['Nama_Kapal' => $k[0]], ['Kelas' => $k[1], 'Jumlah_Kursi' => $k[2]]));

            $lintasan = [
                [$pelabuhan[0], $pelabuhan[1], $kapal[0], ['08:00', '20:00']],
                [$pelabuhan[1], $pelabuhan[0], $kapal[0], ['14:00']],
                [$pelabuhan[2], $pelabuhan[3], $kapal[1], ['07:00', '19:00']],
                [$pelabuhan[3], $pelabuhan[2], $kapal[1], ['13:00']],
            ];

            $jumlah = 0;
            for ($hari = 1; $hari <= 14; $hari++) {
                $tanggal = now()->addDays($hari)->toDateString();

                foreach ($lintasan as [$asal, $tujuan, $kpl, $jamList]) {
                    foreach ($jamList as $jam) {
                        $rute = Rute::firstOrCreate([
                            'ID_Kapal' => $kpl->ID_Kapal,
                            'Pelabuhan_Asal' => $asal->ID_Pelabuhan,
                            'Pelabuhan_Tujuan' => $tujuan->ID_Pelabuhan,
                            'Tanggal' => $tanggal,
                            'Jam' => $jam . ':00',
                        ]);

                        if (! $rute->wasRecentlyCreated) {
                            continue;
                        }

                        foreach (self::HARGA as [$kelas, $tipe, $nominal, $kuota]) {
                            $harga = Harga::forceCreate([
                                'ID_Rute' => $rute->ID_Rute,
                                'Tipe_Penumpang' => $tipe,
                                'Kelas' => $kelas,
                                'Harga' => $nominal,
                            ]);

                            Tiket::create([
                                'ID_Rute' => $rute->ID_Rute,
                                'ID_Harga' => $harga->ID_Harga,
                                'Jumlah_Tiket' => $kuota,
                            ]);
                        }
                        $jumlah++;
                    }
                }
            }

            $this->command?->info("Data demo dibuat: {$jumlah} jadwal baru untuk 14 hari ke depan.");
        });
    }
}
