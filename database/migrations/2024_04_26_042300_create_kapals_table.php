<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('kapal', function (Blueprint $table) {
            // Nama kolom disamakan dengan database yang dipakai aplikasi
            $table->id('ID_Kapal');
            $table->string('Nama_Kapal');
            $table->string('Kelas');
            $table->string('Jumlah_Kursi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kapal');
    }
};
