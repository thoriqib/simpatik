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
        Schema::create('antrian', function (Blueprint $table) {
            $table->id();
            $table->string('kode_antrian', 10);     // "A001", "B003"
            $table->foreignId('jenis_layanan_id')->constrained('jenis_layanan');
            $table->foreignId('petugas_id')->nullable()->constrained('users');
            $table->string('nama_pengunjung')->nullable();
            $table->string('no_hp')->nullable();
            $table->string('email')->nullable();
            $table->date('tanggal');
            $table->integer('nomor_urut');
            $table->enum('status', ['menunggu', 'dipanggil', 'dilayani', 'selesai', 'batal'])
                ->default('menunggu');
            $table->dateTime('waktu_panggil')->nullable();
            $table->dateTime('waktu_mulai_layanan')->nullable();
            $table->dateTime('waktu_selesai')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('antrian');
    }
};
