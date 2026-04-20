<?php
namespace Database\Seeders;

use App\Models\JadwalPiket;
use App\Models\Presensi;
use App\Models\ShiftPiket;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PresensiSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil semua jadwal yang statusnya 'hadir' dan tanggalnya sudah lewat
        $jadwalHadir = JadwalPiket::with('shift')
            ->where('status', 'hadir')
            ->where('tanggal', '<', today())
            ->get();

        $created = 0;

        foreach ($jadwalHadir as $jadwal) {
            // Jangan buat duplikat
            if (Presensi::where('jadwal_piket_id', $jadwal->id)->exists()) {
                continue;
            }

            // Tentukan jam berdasarkan shift
            [$jamMasukBase, $jamKeluarBase] = $jadwal->shift->nama_shift === 'Pagi'
                ? [8, 12]
                : [12, 16];

            // Variasi: telat 0–20 menit masuk, pulang 0–15 menit lebih
            $telatMasuk  = rand(0, 20);
            $lebihKeluar = rand(0, 15);

            $tanggal    = Carbon::parse($jadwal->tanggal);
            $waktuMasuk = $tanggal->copy()->setTime($jamMasukBase, $telatMasuk);
            $waktuKeluar = $tanggal->copy()->setTime($jamKeluarBase, $lebihKeluar);

            Presensi::create([
                'user_id'         => $jadwal->user_id,
                'jadwal_piket_id' => $jadwal->id,
                'waktu_masuk'     => $waktuMasuk,
                'waktu_keluar'    => $waktuKeluar,
            ]);

            $created++;
        }

        $this->command->info("✅ Presensi berhasil dibuat: {$created} entri.");
    }
}