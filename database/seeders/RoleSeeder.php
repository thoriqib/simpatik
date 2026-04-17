<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Buat role (hanya untuk pengguna sistem internal)
        $admin    = Role::create(['name' => 'admin']);
        $petugas  = Role::create(['name' => 'petugas']);
        // Catatan: pengunjung TIDAK memiliki role — mereka mengakses sebagai tamu (guest)

        // Buat permission
        $permissions = [
            'kelola-petugas', 'kelola-jadwal', 'kelola-shift',
            'lihat-laporan', 'kelola-antrian', 'presensi',
        ];
        foreach ($permissions as $p) {
            Permission::create(['name' => $p]);
        }

        // Assign permission ke role
        $admin->givePermissionTo(Permission::all());
        $petugas->givePermissionTo(['kelola-antrian', 'presensi', 'lihat-laporan']);

        // Buat akun admin default
        $userAdmin = User::create([
            'name'     => 'Administrator Pelayanan BPS Kota Jambi',
            'email'    => 'admin1571@bps.go.id',
            'password' => bcrypt('admin1571'),
        ]);
        $userAdmin->assignRole('admin');
    }
}