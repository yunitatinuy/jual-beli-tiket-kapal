<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Alur pemesanan yang benar:
 * - pesanan menyimpan rute, kelas, jumlah penumpang, kode booking dan status
 * - setiap penumpang terikat ke satu pesanan dan satu tiket, beserta harga saat dibeli
 */
return new class extends Migration
{
    public function up(): void
    {
        // Di database lama (dari dump) ID_Penumpang masih NOT NULL; kolom ini tidak dipakai lagi
        if (DB::getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE pesanan MODIFY ID_Penumpang BIGINT UNSIGNED NULL');
        }

        Schema::table('pesanan', function (Blueprint $table) {
            $table->string('Kode_Booking', 20)->nullable()->unique()->after('ID_Pesanan');
            $table->string('Kelas', 50)->nullable()->after('ID_Rute');
            $table->unsignedSmallInteger('Jumlah_Dewasa')->default(0)->after('Kelas');
            $table->unsignedSmallInteger('Jumlah_Anak')->default(0)->after('Jumlah_Dewasa');
            $table->string('Status', 30)->default('menunggu_pembayaran')->after('Metode_Bayar');
            $table->timestamps();

            $table->index('ID_User');
            $table->index('ID_Rute');
        });

        Schema::table('penumpang', function (Blueprint $table) {
            $table->unsignedBigInteger('ID_Pesanan')->nullable()->after('ID_Penumpang');
            $table->unsignedBigInteger('ID_Tiket')->nullable()->after('ID_Pesanan');
            $table->string('Tipe_Penumpang', 20)->nullable()->after('Jenis_Kelamin');
            $table->integer('Harga')->nullable()->after('Tipe_Penumpang');

            $table->foreign('ID_Pesanan')->references('ID_Pesanan')->on('pesanan')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('penumpang', function (Blueprint $table) {
            $table->dropForeign(['ID_Pesanan']);
            $table->dropColumn(['ID_Pesanan', 'ID_Tiket', 'Tipe_Penumpang', 'Harga']);
        });

        Schema::table('pesanan', function (Blueprint $table) {
            $table->dropIndex(['ID_User']);
            $table->dropIndex(['ID_Rute']);
            $table->dropUnique(['Kode_Booking']);
            $table->dropColumn(['Kode_Booking', 'Kelas', 'Jumlah_Dewasa', 'Jumlah_Anak', 'Status', 'created_at', 'updated_at']);
        });
    }
};
