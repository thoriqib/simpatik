<?php
namespace Database\Factories;

use App\Models\JadwalPiket;
use App\Models\ShiftPiket;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class JadwalPiketFactory extends Factory
{
    protected $model = JadwalPiket::class;

    public function definition(): array
    {
        return [
            'user_id'  => User::role('petugas')->inRandomOrder()->first()?->id,
            'shift_id' => ShiftPiket::inRandomOrder()->first()?->id,
            'tanggal'  => $this->faker->dateTimeBetween('-1 month', '+1 month')->format('Y-m-d'),
            'status'   => 'terjadwal',
        ];
    }

    public function hadir(): static
    {
        return $this->state(['status' => 'hadir']);
    }

    public function izin(): static
    {
        return $this->state(['status' => 'izin']);
    }

    public function sakit(): static
    {
        return $this->state(['status' => 'sakit']);
    }

    public function alpha(): static
    {
        return $this->state(['status' => 'alpha']);
    }

    public function padaTanggal(string $tanggal): static
    {
        return $this->state(['tanggal' => $tanggal]);
    }
}