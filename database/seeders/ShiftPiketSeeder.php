<?php
namespace Database\Seeders;

use App\Models\ShiftPiket;
use Illuminate\Database\Seeder;

class ShiftPiketSeeder extends Seeder
{
    public function run(): void
    {
        $shifts = [
            ['nama_shift' => 'Pagi',  'jam_mulai' => '08:00', 'jam_selesai' => '12:00', 'is_aktif' => true],
            ['nama_shift' => 'Siang', 'jam_mulai' => '12:00', 'jam_selesai' => '15:30', 'is_aktif' => true],
        ];

        foreach ($shifts as $shift) {
            ShiftPiket::firstOrCreate(
                ['nama_shift' => $shift['nama_shift']],
                $shift
            );
        }

        $this->command->info('✅ Shift Piket berhasil dibuat: Pagi & Siang.');
    }
}