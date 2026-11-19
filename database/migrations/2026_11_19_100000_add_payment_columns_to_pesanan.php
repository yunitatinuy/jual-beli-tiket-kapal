<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pesanan', function (Blueprint $table) {
            $table->dateTime('Batas_Bayar')->nullable()->after('Status');
            $table->dateTime('Dibayar_Pada')->nullable()->after('Batas_Bayar');
            $table->string('Snap_Token', 100)->nullable()->after('Dibayar_Pada');
            $table->string('Midtrans_Transaction_Id', 100)->nullable()->after('Snap_Token');
            $table->string('Midtrans_Payment_Type', 50)->nullable()->after('Midtrans_Transaction_Id');

            $table->index(['Status', 'Batas_Bayar']);
        });
    }

    public function down(): void
    {
        Schema::table('pesanan', function (Blueprint $table) {
            $table->dropIndex(['Status', 'Batas_Bayar']);
            $table->dropColumn(['Batas_Bayar', 'Dibayar_Pada', 'Snap_Token', 'Midtrans_Transaction_Id', 'Midtrans_Payment_Type']);
        });
    }
};
