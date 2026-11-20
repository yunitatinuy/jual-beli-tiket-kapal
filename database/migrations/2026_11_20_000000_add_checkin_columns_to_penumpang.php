<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/** Waktu check-in (naik kapal) per penumpang, dicatat saat QR e-tiket dipindai. */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('penumpang', function (Blueprint $table) {
            if (! Schema::hasColumn('penumpang', 'Checkin_Pada')) {
                $table->timestamp('Checkin_Pada')->nullable();
            }
            if (! Schema::hasColumn('penumpang', 'Checkin_Oleh')) {
                $table->unsignedBigInteger('Checkin_Oleh')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('penumpang', function (Blueprint $table) {
            $table->dropColumn(['Checkin_Pada', 'Checkin_Oleh']);
        });
    }
};
