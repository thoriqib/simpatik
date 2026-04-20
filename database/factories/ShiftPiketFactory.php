<?php
namespace Database\Factories;

use App\Models\ShiftPiket;
use Illuminate\Database\Eloquent\Factories\Factory;

class ShiftPiketFactory extends Factory
{
    protected $model = ShiftPiket::class;

    public function definition(): array
    {
        return [
            'nama_shift'  => 'Pagi',
            'jam_mulai'   => '08:00',
            'jam_selesai' => '12:00',
            'is_aktif'    => true,
        ];
    }

    public function pagi(): static
    {
        return $this->state([
            'nama_shift'  => 'Pagi',
            'jam_mulai'   => '08:00',
            'jam_selesai' => '12:00',
        ]);
    }

    public function siang(): static
    {
        return $this->state([
            'nama_shift'  => 'Siang',
            'jam_mulai'   => '12:00',
            'jam_selesai' => '15:30',
        ]);
    }

    public function nonaktif(): static
    {
        return $this->state(['is_aktif' => false]);
    }
}