<?php
namespace Database\Factories;

use App\Models\Pengaduan;
use Illuminate\Database\Eloquent\Factories\Factory;

class PengaduanFactory extends Factory
{
    protected $model = Pengaduan::class;

    private static array $subjekList = [
        'Waktu Tunggu Antrian Terlalu Lama',
        'Petugas Kurang Informatif',
        'Informasi di Website Tidak Akurat',
        'Ruang Tunggu Kurang Nyaman',
        'Antrian Tidak Tertib',
        'Petugas Tidak Ada di Tempat',
        'Proses Permintaan Data Terlalu Lama',
        'Fasilitas Toilet Kurang Bersih',
        'AC di Ruang Tunggu Rusak',
        'Nomor Antrian Tidak Sesuai Urutan',
        'Petugas Menggunakan HP saat Melayani',
        'Informasi Jam Pelayanan Tidak Jelas',
    ];

    private static array $isiList = [
        'Saya sudah menunggu lebih dari 2 jam namun belum dilayani. Mohon ditingkatkan sistem antriannya.',
        'Ketika saya bertanya mengenai data yang dibutuhkan, petugas tidak dapat memberikan penjelasan yang memadai.',
        'Data yang tersedia di website tidak sesuai dengan data yang diberikan petugas secara langsung.',
        'Kursi di ruang tunggu sangat terbatas dan kondisi ruangan cukup panas. Mohon fasilitas ditingkatkan.',
        'Pengunjung yang baru datang langsung dilayani tanpa mengambil nomor antrian, ini tidak adil.',
        'Saat saya tiba, petugas tidak berada di meja pelayanan dan harus menunggu cukup lama.',
        'Permintaan data yang saya ajukan sudah lebih dari seminggu namun belum ada respons.',
        'Kondisi toilet di lantai satu perlu mendapat perhatian lebih dalam hal kebersihan.',
        'AC di ruang tunggu sudah tidak berfungsi sejak beberapa hari yang lalu. Pengunjung merasa tidak nyaman.',
        'Nomor antrian saya dipanggil tidak sesuai urutan, ada pengunjung dengan nomor lebih besar yang dilayani lebih dahulu.',
    ];

    public function definition(): array
    {
        $status = $this->faker->randomElement(['baru', 'baru', 'diproses', 'selesai']);

        return [
            'subjek'          => $this->faker->randomElement(self::$subjekList),
            'isi_pengaduan'   => $this->faker->randomElement(self::$isiList),
            'lampiran'        => null,
            'status'          => $status,
            'tanggapan'       => null,
            'ditangani_oleh'  => null,
            'ditanggapi_pada' => null,
            'created_at'      => $this->faker->dateTimeBetween('-60 days', 'now'),
        ];
    }

    public function baru(): static
    {
        return $this->state([
            'status'          => 'baru',
            'tanggapan'       => null,
            'ditangani_oleh'  => null,
            'ditanggapi_pada' => null,
        ]);
    }

    public function diproses(): static
    {
        return $this->state([
            'status'    => 'diproses',
            'tanggapan' => null,
        ]);
    }

    public function selesai(int $adminId): static
    {
        return $this->state([
            'status'          => 'selesai',
            'tanggapan'       => $this->faker->randomElement([
                'Terima kasih atas masukan Anda. Kami telah menindaklanjuti keluhan ini dan akan terus meningkatkan kualitas pelayanan.',
                'Keluhan Anda telah kami terima dan sudah ditindaklanjuti. Mohon maaf atas ketidaknyamanan yang terjadi.',
                'Kami mengapresiasi masukan Anda. Perbaikan telah kami lakukan sesuai dengan pengaduan yang disampaikan.',
            ]),
            'ditangani_oleh'  => $adminId,
            'ditanggapi_pada' => $this->faker->dateTimeBetween('-30 days', 'now'),
        ]);
    }
}