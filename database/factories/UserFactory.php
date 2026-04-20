<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    public function definition(): array
    {
        // Nama depan khas Indonesia
        $namaDepan = $this->faker->randomElement([
            'Budi', 'Siti', 'Ahmad', 'Dewi', 'Eko', 'Fitri',
            'Hendra', 'Indah', 'Joko', 'Kartika', 'Lukman', 'Maya',
            'Nanda', 'Oki', 'Putri', 'Reza', 'Sari', 'Teguh',
            'Umar', 'Vina', 'Wahyu', 'Yuni', 'Zahra', 'Rizky',
        ]);
        $namaBelakang = $this->faker->randomElement([
            'Santoso', 'Rahayu', 'Pratama', 'Susanti', 'Wijaya',
            'Kurniawan', 'Handayani', 'Nugroho', 'Lestari', 'Purnomo',
            'Setiawan', 'Anggraini', 'Saputra', 'Wulandari', 'Hidayat',
        ]);

        return [
            'name'              => "{$namaDepan} {$namaBelakang}",
            'email'             => strtolower("{$namaDepan}.{$namaBelakang}@bps-jambi.go.id"),
            'email_verified_at' => now(),
            'password'          => Hash::make('password'),
            'remember_token'    => Str::random(10),
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn() => ['email_verified_at' => null]);
    }
}