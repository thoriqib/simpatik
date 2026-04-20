<?php
namespace Database\Seeders;

use App\Models\Pengaduan;
use App\Models\User;
use Illuminate\Database\Seeder;

class PengaduanSeeder extends Seeder
{
    private array $data = [
        [
            'subjek'        => 'Waktu Tunggu Antrian Terlalu Lama',
            'isi_pengaduan' => 'Saya sudah menunggu lebih dari 2 jam namun belum dipanggil. Pengunjung lain yang datang lebih belakangan justru dilayani terlebih dahulu. Mohon sistem antrian diperbaiki.',
        ],
        [
            'subjek'        => 'Petugas Kurang Informatif',
            'isi_pengaduan' => 'Ketika saya menanyakan prosedur permintaan data, petugas memberikan jawaban yang tidak lengkap dan menyuruh saya kembali lain hari tanpa penjelasan yang memadai.',
        ],
        [
            'subjek'        => 'Informasi di Website Tidak Akurat',
            'isi_pengaduan' => 'Jam pelayanan di website tertulis sampai pukul 16.00, namun ketika saya datang pukul 15.00 loket sudah tutup. Mohon informasi di website diperbarui.',
        ],
        [
            'subjek'        => 'Ruang Tunggu Kurang Nyaman',
            'isi_pengaduan' => 'AC di ruang tunggu tidak berfungsi dengan baik. Pengunjung merasa sangat panas dan tidak nyaman saat menunggu dalam waktu lama. Mohon segera diperbaiki.',
        ],
        [
            'subjek'        => 'Proses Permintaan Data Terlalu Lama',
            'isi_pengaduan' => 'Saya mengajukan permintaan data pada dua minggu lalu namun hingga hari ini belum ada respons. Sudah menghubungi via telepon namun tidak ada yang mengangkat.',
        ],
        [
            'subjek'        => 'Nomor Antrian Tidak Sesuai Urutan',
            'isi_pengaduan' => 'Nomor antrian saya 015 namun nomor 020 yang dipanggil terlebih dahulu. Ketika saya protes, petugas mengatakan itu karena beda jenis layanan tapi tidak ada penjelasan sebelumnya.',
        ],
        [
            'subjek'        => 'Petugas Bermain HP saat Melayani',
            'isi_pengaduan' => 'Saya menyaksikan petugas di loket B menggunakan smartphone pribadi saat sedang melayani pengunjung. Hal ini sangat tidak profesional dan membuat proses layanan menjadi lambat.',
        ],
        [
            'subjek'        => 'Fasilitas Toilet Kurang Bersih',
            'isi_pengaduan' => 'Kondisi toilet di lantai satu sangat kurang bersih dan tidak ada sabun. Sebagai fasilitas publik yang sering dikunjungi, kebersihan toilet seharusnya menjadi prioritas.',
        ],
        [
            'subjek'        => 'Display Antrian Tidak Berfungsi',
            'isi_pengaduan' => 'Monitor display antrian di ruang tunggu mati sejak saya datang. Pengunjung tidak dapat mengetahui nomor antrian yang sedang dipanggil kecuali mendengar suara petugas yang tidak jelas.',
        ],
        [
            'subjek'        => 'Saran: Tambah Loket Pelayanan',
            'isi_pengaduan' => 'Pada hari Senin dan Selasa, jumlah pengunjung sangat banyak namun hanya ada 2 loket yang buka. Mohon dipertimbangkan untuk membuka loket tambahan di hari-hari ramai.',
        ],
        [
            'subjek'        => 'Kursi Ruang Tunggu Tidak Cukup',
            'isi_pengaduan' => 'Jumlah kursi di ruang tunggu sangat terbatas. Banyak pengunjung yang terpaksa berdiri selama menunggu, termasuk lansia dan ibu hamil. Mohon ditambah.',
        ],
        [
            'subjek'        => 'Petugas Tidak Ada di Loket',
            'isi_pengaduan' => 'Saya sudah menunggu 30 menit di depan loket C namun tidak ada petugas. Loket tertulis "Buka" namun kosong. Ketika bertanya ke petugas lain, tidak ada yang tahu keberadaannya.',
        ],
    ];

    public function run(): void
    {
        $admin = User::role('admin')->first();

        // Distribusi status:
        // 5 baru, 3 diproses, 4 selesai dari data tertulis di atas
        // + 8 random dari faker

        $statusMap = [
            'baru', 'baru', 'baru', 'baru', 'baru',
            'diproses', 'diproses', 'diproses',
            'selesai', 'selesai', 'selesai', 'selesai',
        ];

        foreach ($this->data as $index => $item) {
            $status = $statusMap[$index] ?? 'baru';

            $tanggapan       = null;
            $ditanganiOleh   = null;
            $ditanggapiPada  = null;

            if ($status === 'selesai') {
                $tanggapan = $this->tanggapanUntuk($item['subjek']);
                $ditanganiOleh  = $admin->id;
                $ditanggapiPada = now()->subDays(rand(1, 10));
            }

            Pengaduan::create([
                'subjek'          => $item['subjek'],
                'isi_pengaduan'   => $item['isi_pengaduan'],
                'lampiran'        => null,
                'status'          => $status,
                'tanggapan'       => $tanggapan,
                'ditangani_oleh'  => $ditanganiOleh,
                'ditanggapi_pada' => $ditanggapiPada,
                'created_at'      => now()->subDays(rand(1, 60)),
            ]);
        }

        // Tambah 8 pengaduan acak menggunakan faker
        $faker = \Faker\Factory::create('id_ID');
        $subjekAcak = [
            'Masukan tentang Kualitas Pelayanan',
            'Petugas Tidak Hadir Tepat Waktu',
            'Saran Peningkatan Sistem Antrian Online',
            'Keluhan tentang Parkir yang Sempit',
            'Informasi Kontak BPS Sulit Ditemukan',
            'Saran Penambahan Jam Pelayanan',
            'Proses Administrasi Terlalu Berbelit',
            'Apresiasi untuk Petugas yang Ramah',
        ];

        foreach ($subjekAcak as $subjek) {
            Pengaduan::create([
                'subjek'          => $subjek,
                'isi_pengaduan'   => $faker->paragraph(3),
                'lampiran'        => null,
                'status'          => 'baru',
                'tanggapan'       => null,
                'ditangani_oleh'  => null,
                'ditanggapi_pada' => null,
                'created_at'      => now()->subDays(rand(1, 45)),
            ]);
        }

        $total = count($this->data) + count($subjekAcak);
        $this->command->info("✅ Pengaduan berhasil dibuat: {$total} entri.");
    }

    private function tanggapanUntuk(string $subjek): string
    {
        $tanggapanMap = [
            'Waktu Tunggu' => 'Terima kasih atas masukan Anda. Kami telah mengevaluasi sistem antrian dan menambah kapasitas loket pada jam-jam sibuk.',
            'Petugas'      => 'Kami mohon maaf atas ketidaknyamanan yang Anda alami. Kami telah memberikan pembinaan kepada petugas terkait.',
            'Website'      => 'Terima kasih telah menginformasikan hal ini. Informasi di website telah kami perbarui sesuai jam operasional terbaru.',
            'Ruang'        => 'Kami mengapresiasi masukan Anda. AC ruang tunggu telah diperbaiki dan kami akan terus meningkatkan kenyamanan fasilitas.',
            'Data'         => 'Mohon maaf atas keterlambatan. Tim kami telah menghubungi Anda dan permintaan data sedang diproses.',
        ];

        foreach ($tanggapanMap as $kata => $resp) {
            if (str_contains($subjek, $kata)) {
                return $resp;
            }
        }

        return 'Terima kasih atas pengaduan Anda. Kami telah menindaklanjuti dan akan terus berupaya meningkatkan kualitas pelayanan PST BPS Kota Jambi.';
    }
}