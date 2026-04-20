<?php
namespace Database\Seeders;

use App\Models\JenisLayanan;
use Illuminate\Database\Seeder;

class JenisLayananSeeder extends Seeder
{
    public function run(): void
    {
        $layanan = [
            [
                'kode'         => 'A',
                'nama_layanan' => 'Konsultasi Data Statistik',
                'deskripsi'    => 'Konsultasi kebutuhan data BPS secara langsung dengan petugas ahli.',
                'is_aktif'     => true,
            ],
            [
                'kode'         => 'B',
                'nama_layanan' => 'Permintaan Data / Publikasi',
                'deskripsi'    => 'Permintaan data mentah, tabel statistik, atau publikasi resmi BPS.',
                'is_aktif'     => true,
            ],
            [
                'kode'         => 'C',
                'nama_layanan' => 'Rekomendasi Statistik',
                'deskripsi'    => 'Penerbitan surat rekomendasi untuk kegiatan statistik sektoral.',
                'is_aktif'     => true,
            ],
            [
                'kode'         => 'D',
                'nama_layanan' => 'Pengadaan Peta Wilayah',
                'deskripsi'    => 'Permintaan peta wilayah administrasi dan wilayah sensus.',
                'is_aktif'     => true,
            ],
            [
                'kode'         => 'E',
                'nama_layanan' => 'Layanan Perpustakaan Digital',
                'deskripsi'    => 'Akses referensi dan arsip publikasi statistik BPS secara digital.',
                'is_aktif'     => true,
            ],
        ];

        foreach ($layanan as $item) {
            JenisLayanan::firstOrCreate(['kode' => $item['kode']], $item);
        }

        $this->command->info('✅ Jenis Layanan berhasil dibuat (A–E).');
    }
}