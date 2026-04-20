<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cache permission
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Buat role
        $admin   = Role::firstOrCreate(['name' => 'admin']);
        $petugas = Role::firstOrCreate(['name' => 'petugas']);

        // Buat permission
        $permissions = [
            'kelola-petugas',
            'kelola-jadwal',
            'kelola-shift',
            'kelola-jenis-layanan',
            'lihat-laporan',
            'kelola-antrian',
            'presensi',
            'tanggapi-pengaduan',
        ];

        foreach ($permissions as $p) {
            Permission::firstOrCreate(['name' => $p]);
        }

        // Assign permission ke role
        $admin->syncPermissions(Permission::all());
        $petugas->syncPermissions(['kelola-antrian', 'presensi', 'lihat-laporan']);

        // Buat akun admin
        $userAdmin = User::firstOrCreate(
            ['email' => 'admin@bps-jambi.go.id'],
            [
                'name'              => 'Administrator BPS',
                'password'          => Hash::make('Admin@BPS2024'),
                'email_verified_at' => now(),
            ]
        );
        $userAdmin->assignRole('admin');

        $this->command->info('✅ Role, Permission, dan akun Admin berhasil dibuat.');
    }
}