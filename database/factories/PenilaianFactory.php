<?php
namespace Database\Factories;

use App\Models\Antrian;
use App\Models\Penilaian;
use Illuminate\Database\Eloquent\Factories\Factory;

class PenilaianFactory extends Factory
{
    protected $model = Penilaian::class;

    // Komentar realistis sesuai nilai bintang
    private static array $komentar = [
        1 => [
            'Pelayanan sangat lambat, antri sangat lama.',
            'Petugas kurang ramah dan tidak informatif.',
            'Informasi yang diberikan tidak akurat.',
        ],
        2 => [
            'Pelayanan cukup lambat, perlu ditingkatkan.',
            'Petugas kurang proaktif dalam membantu.',
            'Ruang tunggu kurang nyaman.',
        ],
        3 => [
            'Pelayanan cukup baik, namun masih bisa ditingkatkan.',
            'Petugas ramah, informasi cukup lengkap.',
            'Waktu tunggu masih cukup lama.',
        ],
        4 => [
            'Pelayanan baik dan petugas ramah.',
            'Informasi yang diberikan jelas dan membantu.',
            'Proses pelayanan cepat dan efisien.',
        ],
        5 => [
            'Pelayanan sangat memuaskan! Petugas sangat membantu.',
            'Luar biasa! Proses cepat dan petugas sangat profesional.',
            'Sangat puas dengan pelayanan BPS Kota Jambi.',
            'Petugas ramah, informatif, dan pelayanan sangat cepat.',
        ],
    ];

    public function definition(): array
    {
        // Distribusi nilai: mayoritas 4-5 bintang (realistis)
        $nilai = $this->faker->randomElement([3, 4, 4, 4, 5, 5, 5, 5, 2, 1]);

        return [
            'antrian_id' => null, // diisi seeder
            'petugas_id' => null, // diisi seeder
            'nilai'      => $nilai,
            'komentar'   => $this->faker->optional(0.5)
                                ->randomElement(self::$komentar[$nilai]),
        ];
    }

    public function bintang(int $nilai): static
    {
        return $this->state([
            'nilai'    => $nilai,
            'komentar' => $this->faker->optional(0.6)
                              ->randomElement(self::$komentar[$nilai]),
        ]);
    }

    public function tanpaKomentar(): static
    {
        return $this->state(['komentar' => null]);
    }
}