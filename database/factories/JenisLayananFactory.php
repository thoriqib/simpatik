<?php
namespace Database\Factories;

use App\Models\JenisLayanan;
use Illuminate\Database\Eloquent\Factories\Factory;

class JenisLayananFactory extends Factory
{
    protected $model = JenisLayanan::class;

    // Data layanan PST BPS yang realistis
    private static array $layanan = [
        ['kode' => 'A', 'nama' => 'Konsultasi Data Statistik',    'desc' => 'Konsultasi kebutuhan data BPS secara langsung dengan petugas.'],
        ['kode' => 'B', 'nama' => 'Permintaan Data/Publikasi',    'desc' => 'Permintaan data mentah, tabel, atau publikasi resmi BPS.'],
        ['kode' => 'C', 'nama' => 'Rekomendasi Statistik',        'desc' => 'Penerbitan surat rekomendasi untuk kegiatan statistik sektoral.'],
        ['kode' => 'D', 'nama' => 'Pengadaan Peta Wilayah',       'desc' => 'Permintaan peta wilayah administrasi dan sensus.'],
        ['kode' => 'E', 'nama' => 'Layanan Perpustakaan Digital', 'desc' => 'Akses referensi dan arsip publikasi statistik BPS.'],
    ];

    private static int $index = 0;

    public function definition(): array
    {
        $item = self::$layanan[self::$index % count(self::$layanan)];
        self::$index++;

        return [
            'kode'         => $item['kode'],
            'nama_layanan' => $item['nama'],
            'deskripsi'    => $item['desc'],
            'is_aktif'     => true,
        ];
    }
}