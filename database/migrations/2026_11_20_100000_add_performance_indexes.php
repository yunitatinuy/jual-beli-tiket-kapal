<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Index untuk query yang paling sering dijalankan:
 * - pencarian jadwal per tanggal & lintasan (halaman cari, informasi, landing, generator)
 * - relasi harga/tiket ke rute (penawaran harga & kuota)
 * - penumpang per tiket (pengembalian kuota saat batal) dan per user
 * - ringkasan pendapatan di dashboard admin
 */
return new class extends Migration
{
    private array $indexes = [
        'rute' => [
            'rute_tanggal_jam_index' => ['Tanggal', 'Jam'],
            'rute_lintasan_tanggal_index' => ['Pelabuhan_Asal', 'Pelabuhan_Tujuan', 'Tanggal'],
            'rute_id_kapal_index' => ['ID_Kapal'],
        ],
        'harga' => [
            'harga_id_rute_index' => ['ID_Rute'],
        ],
        'tiket' => [
            'tiket_id_rute_index' => ['ID_Rute'],
            'tiket_id_harga_index' => ['ID_Harga'],
        ],
        'penumpang' => [
            'penumpang_id_tiket_index' => ['ID_Tiket'],
            'penumpang_id_index' => ['id'],
        ],
        'pesanan' => [
            'pesanan_status_dibayar_index' => ['Status', 'Dibayar_Pada'],
            'pesanan_tanggal_pesanan_index' => ['Tanggal_Pesanan'],
        ],
    ];

    public function up(): void
    {
        foreach ($this->indexes as $tabel => $daftar) {
            foreach ($daftar as $nama => $kolom) {
                if (! $this->adaIndex($tabel, $nama)) {
                    Schema::table($tabel, fn (Blueprint $t) => $t->index($kolom, $nama));
                }
            }
        }
    }

    public function down(): void
    {
        foreach ($this->indexes as $tabel => $daftar) {
            foreach (array_keys($daftar) as $nama) {
                if ($this->adaIndex($tabel, $nama)) {
                    Schema::table($tabel, fn (Blueprint $t) => $t->dropIndex($nama));
                }
            }
        }
    }

    /** Laravel 10 belum punya Schema::hasIndex(), jadi dicek manual. */
    private function adaIndex(string $tabel, string $nama): bool
    {
        return match (DB::getDriverName()) {
            'mysql', 'mariadb' => DB::select('SHOW INDEX FROM `' . $tabel . '` WHERE Key_name = ?', [$nama]) !== [],
            'sqlite' => collect(DB::select("PRAGMA index_list('" . $tabel . "')"))->contains(fn ($i) => $i->name === $nama),
            default => false,
        };
    }
};
