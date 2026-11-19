<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tabel-tabel inti yang sebelumnya hanya ada di dump SQL.
 *
 * Setiap tabel dibungkus Schema::hasTable() supaya migration ini aman dijalankan
 * di database lama yang sudah di-import dari dump (tabel yang sudah ada dilewati),
 * sekaligus bisa membangun database baru dari nol dengan `php artisan migrate`.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('pelabuhan')) {
            Schema::create('pelabuhan', function (Blueprint $table) {
                $table->id('ID_Pelabuhan');
                $table->string('Nama_Pelabuhan');
                $table->string('Nama_Kota');
            });
        }

        if (! Schema::hasTable('rute')) {
            Schema::create('rute', function (Blueprint $table) {
                $table->id('ID_Rute');
                $table->unsignedBigInteger('ID_Kapal');
                $table->unsignedBigInteger('Pelabuhan_Asal');
                $table->unsignedBigInteger('Pelabuhan_Tujuan');
                $table->date('Tanggal');
                $table->time('Jam');
            });
        }

        if (! Schema::hasTable('harga')) {
            Schema::create('harga', function (Blueprint $table) {
                $table->id('ID_Harga');
                $table->unsignedBigInteger('ID_Rute');
                $table->string('Tipe_Penumpang');
                $table->string('Kelas');
                $table->integer('Harga');
            });
        }

        if (! Schema::hasTable('tiket')) {
            Schema::create('tiket', function (Blueprint $table) {
                $table->id('ID_Tiket');
                $table->unsignedBigInteger('ID_Rute');
                $table->unsignedBigInteger('ID_Harga');
                $table->integer('Jumlah_Tiket');
            });
        }

        if (! Schema::hasTable('penumpang')) {
            Schema::create('penumpang', function (Blueprint $table) {
                $table->id('ID_Penumpang');
                $table->unsignedBigInteger('id');
                $table->string('Nama_Lengkap');
                $table->string('Telepon');
                $table->text('Alamat');
                $table->string('Jenis_Kelamin');
            });
        }

        if (! Schema::hasTable('pesanan')) {
            Schema::create('pesanan', function (Blueprint $table) {
                $table->id('ID_Pesanan');
                $table->unsignedBigInteger('ID_User');
                $table->unsignedBigInteger('ID_Penumpang')->nullable();
                $table->unsignedBigInteger('ID_Tiket')->nullable();
                $table->unsignedBigInteger('ID_Rute')->nullable();
                $table->date('Tanggal_Pesanan');
                $table->time('Waktu');
                $table->integer('Total_Harga');
                $table->string('Metode_Bayar');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('pesanan');
        Schema::dropIfExists('penumpang');
        Schema::dropIfExists('tiket');
        Schema::dropIfExists('harga');
        Schema::dropIfExists('rute');
        Schema::dropIfExists('pelabuhan');
    }
};
