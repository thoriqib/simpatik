<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // database/migrations/xxxx_add_kekurangan_menit_to_presensi_table.php
    public function up(): void
    {
        Schema::table('presensi', function (Blueprint $table) {
            $table->integer('kekurangan_menit')->default(0)->after('waktu_keluar');
            // Positif = kurang jam, 0 = tepat/lebih
        });
    }

    public function down(): void
    {
        Schema::table('presensi', function (Blueprint $table) {
            $table->dropColumn('kekurangan_menit');
        });
    }
};
