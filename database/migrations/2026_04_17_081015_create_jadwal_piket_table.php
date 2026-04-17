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
        Schema::create('jadwal_piket', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('shift_id')->constrained('shift_piket');
            $table->date('tanggal');
            $table->enum('status', ['terjadwal', 'hadir', 'izin', 'sakit', 'alpha'])
                ->default('terjadwal');
            $table->timestamps();

            $table->unique(['user_id', 'tanggal', 'shift_id']); // cegah duplikasi
                });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_piket');
    }
};
