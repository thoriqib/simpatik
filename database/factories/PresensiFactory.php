<?php
namespace Database\Factories;

use App\Models\JadwalPiket;
use App\Models\Presensi;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class PresensiFactory extends Factory
{
    protected $model = Presensi::class;

    public function definition(): array
    {
        // Jam masuk sedikit variatif (telat 0–15 menit dari jam shift)
        $telatMenit  = $this->faker->numberBetween(0, 15);
        $jamMasuk    = Carbon::createFromTime(8, $telatMenit);

        // Jam keluar sedikit variatif (pulang 0–10 menit setelah jam shift)
        $lebihMenit  = $this->faker->numberBetween(0, 10);
        $jamKeluar   = Carbon::createFromTime(12, $lebihMenit);

        $tanggalAcak = $this->faker->dateTimeBetween('-30 days', '-1 day');

        return [
            'user_id'         => null, // diisi via seeder
            'jadwal_piket_id' => null, // diisi via seeder
            'waktu_masuk'     => Carbon::parse($tanggalAcak)->setTime($jamMasuk->hour, $jamMasuk->minute),
            'waktu_keluar'    => Carbon::parse($tanggalAcak)->setTime($jamKeluar->hour, $jamKeluar->minute),
        ];
    }

    /**
     * Presensi yang belum keluar (masih di kantor)
     */
    public function belumKeluar(): static
    {
        return $this->state(['waktu_keluar' => null]);
    }

    /**
     * Sesuaikan jam dengan shift tertentu
     */
    public function untukShift(string $namaShift): static
    {
        return $this->state(function () use ($namaShift) {
            $telatMenit = $this->faker->numberBetween(0, 15);
            $lebihMenit = $this->faker->numberBetween(0, 10);

            if ($namaShift === 'Pagi') {
                return [
                    'waktu_masuk'  => now()->setTime(8,  $telatMenit),
                    'waktu_keluar' => now()->setTime(12, $lebihMenit),
                ];
            }

            return [
                'waktu_masuk'  => now()->setTime(12, $telatMenit),
                'waktu_keluar' => now()->setTime(16, $lebihMenit),
            ];
        });
    }
}