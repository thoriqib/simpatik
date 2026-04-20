<?php
namespace Database\Factories;

use App\Models\Antrian;
use App\Models\JenisLayanan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class AntrianFactory extends Factory
{
    protected $model = Antrian::class;

    // Nama pengunjung Indonesia yang realistis
    private static array $namaList = [
        'Ahmad Fauzi', 'Siti Rahayu', 'Budi Santoso', 'Dewi Lestari',
        'Eko Prasetyo', 'Fitri Handayani', 'Hendra Gunawan', 'Indah Permata',
        'Joko Susilo', 'Kartika Sari', 'Lukman Hakim', 'Maya Anggraini',
        'Nanda Rizki', 'Oki Firmansyah', 'Putri Wulandari', 'Reza Pahlevi',
        'Sari Dewi', 'Teguh Prasetya', 'Umar Bakri', 'Vina Melati',
        'Wahyu Hidayat', 'Yuni Astuti', 'Zahra Nabila', 'Rizky Maulana',
        'Dian Novita', 'Fajar Setiawan', 'Gita Kusuma', 'Hari Wibowo',
        'Irma Suryani', 'Jefri Kurniawan',
    ];

    public function definition(): array
    {
        return [
            'kode_antrian'     => 'A001', // di-override oleh seeder
            'jenis_layanan_id' => JenisLayanan::inRandomOrder()->first()?->id,
            'petugas_id'       => null,
            'nama_pengunjung'  => $this->faker->randomElement(self::$namaList),
            'no_hp'            => $this->faker->optional(0.7)->numerify('08##########'),
            'email'            => $this->faker->optional(0.4)->safeEmail(),
            'tanggal'          => today()->toDateString(),
            'nomor_urut'       => 1,
            'status'           => 'menunggu',
            'waktu_panggil'    => null,
            'waktu_mulai_layanan' => null,
            'waktu_selesai'    => null,
        ];
    }

    public function selesai(): static
    {
        return $this->state(function () {
            $mulai   = now()->subMinutes($this->faker->numberBetween(5, 45));
            $selesai = $mulai->copy()->addMinutes($this->faker->numberBetween(5, 30));

            return [
                'status'               => 'selesai',
                'waktu_panggil'        => $mulai->copy()->subMinutes(2),
                'waktu_mulai_layanan'  => $mulai,
                'waktu_selesai'        => $selesai,
            ];
        });
    }

    public function menunggu(): static
    {
        return $this->state(['status' => 'menunggu']);
    }

    public function dipanggil(): static
    {
        return $this->state([
            'status'        => 'dipanggil',
            'waktu_panggil' => now()->subMinutes($this->faker->numberBetween(1, 5)),
        ]);
    }

    public function batal(): static
    {
        return $this->state(['status' => 'batal']);
    }
}