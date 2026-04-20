<?php
namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $petugas = [
            ['name' => 'Budi Santoso',      'email' => 'budi.santoso@bps.go.id'],
            ['name' => 'Siti Rahayu',        'email' => 'siti.rahayu@bps.go.id'],
            ['name' => 'Ahmad Kurniawan',    'email' => 'ahmad.kurniawan@bps.go.id'],
            ['name' => 'Dewi Anggraini',     'email' => 'dewi.anggraini@bps.go.id'],
            ['name' => 'Eko Prasetyo',       'email' => 'eko.prasetyo@bps.go.id'],
            ['name' => 'Fitri Handayani',    'email' => 'fitri.handayani@bps.go.id'],
        ];

        foreach ($petugas as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name'              => $data['name'],
                    'password'          => Hash::make('password'),
                    'email_verified_at' => now(),
                ]
            );
            $user->assignRole('petugas');
        }

        $this->command->info('✅ 6 akun Petugas berhasil dibuat.');
        $this->command->line('   Password semua petugas: <comment>password</comment>');
    }
}