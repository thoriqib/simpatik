<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('');
        $this->command->info('🚀 Memulai seeding database PST BPS Kota Jambi...');
        $this->command->info('');

        $this->call([
            // 1. Role & Permission + Admin
            RoleSeeder::class,

            // 2. Master data
            ShiftPiketSeeder::class,
            JenisLayananSeeder::class,

            // 3. Petugas (user dengan role petugas)
            UserSeeder::class,

            // 4. Jadwal piket (membutuhkan petugas & shift)
            JadwalPiketSeeder::class,

            // 5. Presensi (membutuhkan jadwal yang sudah ada)
            PresensiSeeder::class,

            // 6. Antrian (membutuhkan jenis layanan & petugas)
            AntrianSeeder::class,

            // 7. Penilaian (membutuhkan antrian selesai)
            PenilaianSeeder::class,

            // 8. Pengaduan
            PengaduanSeeder::class,
        ]);

        $this->command->info('');
        $this->command->info('🎉 Seeding selesai! Berikut akun yang tersedia:');
        $this->command->table(
            ['Role', 'Email', 'Password'],
            [
                ['Admin',   'admin1571@bps.go.id',          'admin1571'],
                ['Petugas', 'budi.santoso@bps.go.id',   'password'],
                ['Petugas', 'siti.rahayu@bps.go.id',    'password'],
                ['Petugas', 'ahmad.kurniawan@bps.go.id','password'],
                ['Petugas', 'dewi.anggraini@bps.go.id', 'password'],
                ['Petugas', 'eko.prasetyo@bps.go.id',   'password'],
                ['Petugas', 'fitri.handayani@bps.go.id','password'],
            ]
        );
    }
}