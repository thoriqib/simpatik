<?php
namespace Database\Seeders;

use App\Models\Antrian;
use App\Models\JenisLayanan;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Database\Seeder;

class AntrianSeeder extends Seeder
{
    // Nama pengunjung Indonesia
    private array $namaPengunjung = [
        'Ahmad Fauzi', 'Siti Rahayu', 'Budi Santoso', 'Dewi Lestari',
        'Eko Prasetyo', 'Fitri Handayani', 'Hendra Gunawan', 'Indah Permata',
        'Joko Susilo', 'Kartika Sari', 'Lukman Hakim', 'Maya Anggraini',
        'Nanda Rizki', 'Oki Firmansyah', 'Putri Wulandari', 'Reza Pahlevi',
        'Sari Dewi', 'Teguh Prasetya', 'Umar Bakri', 'Vina Melati',
        'Wahyu Hidayat', 'Yuni Astuti', 'Zahra Nabila', 'Rizky Maulana',
        'Dian Novita', 'Fajar Setiawan', 'Gita Kusuma', 'Hari Wibowo',
        'Irma Suryani', 'Jefri Kurniawan', 'Krisna Bayu', 'Lilis Suryani',
        'Maman Suherman', 'Nina Kusuma', 'Opik Firdaus', 'Peni Rahayu',
    ];

    public function run(): void
    {
        $jenisLayanan = JenisLayanan::all();
        $petugas      = User::role('petugas')->get();

        // Generate antrian untuk 30 hari terakhir (hari kerja)
        $mulai   = now()->subDays(30);
        $selesai = now()->subDay(); // kemarin (hari ini dibuat terpisah)

        $periode = CarbonPeriod::create($mulai, $selesai);
        $totalDibuat = 0;

        foreach ($periode as $tanggal) {
            if ($tanggal->isWeekend()) continue;

            $totalDibuat += $this->buatAntrianUntukTanggal(
                $tanggal,
                $jenisLayanan,
                $petugas,
                isHariIni: false
            );
        }

        // Antrian hari ini (campuran status aktif)
        $totalDibuat += $this->buatAntrianHariIni($jenisLayanan, $petugas);

        $this->command->info("✅ Antrian berhasil dibuat: {$totalDibuat} entri.");
    }

    /**
     * Buat antrian historis (semua sudah selesai/batal)
     */
    private function buatAntrianUntukTanggal(
        $tanggal,
        $jenisLayanan,
        $petugas,
        bool $isHariIni
    ): int {
        // Jumlah pengunjung per hari: 10–25 orang
        $jumlahPengunjung = rand(10, 25);
        $created = 0;

        // Counter nomor urut per jenis layanan
        $counter = [];

        for ($i = 0; $i < $jumlahPengunjung; $i++) {
            $jenis = $jenisLayanan->random();

            if (!isset($counter[$jenis->id])) {
                $counter[$jenis->id] = 0;
            }
            $counter[$jenis->id]++;

            $nomor = $counter[$jenis->id];
            $kode  = $jenis->kode . str_pad($nomor, 3, '0', STR_PAD_LEFT);

            // Distribusi status historis: 90% selesai, 10% batal
            $status  = rand(1, 100) <= 90 ? 'selesai' : 'batal';
            $petugas1 = $petugas->random();

            // Waktu layanan realistis (jam 08:00–15:30)
            $jamMulai   = rand(8, 15);
            $menitMulai = rand(0, 59);
            $durasiMenit = rand(5, 30);

            $waktuMulai  = Carbon::parse($tanggal)->setTime($jamMulai, $menitMulai);
            $waktuSelesai = $waktuMulai->copy()->addMinutes($durasiMenit);

            Antrian::create([
                'kode_antrian'       => $kode,
                'jenis_layanan_id'   => $jenis->id,
                'petugas_id'         => $status === 'selesai' ? $petugas1->id : null,
                'nama_pengunjung'    => $this->namaAcak(),
                'no_hp'              => rand(0, 1) ? $this->noHpAcak() : null,
                'email'              => rand(0, 3) === 0 ? fake()->safeEmail() : null,
                'tanggal'            => $tanggal->toDateString(),
                'nomor_urut'         => $nomor,
                'status'             => $status,
                'waktu_panggil'      => $status === 'selesai' ? $waktuMulai->copy()->subMinutes(2) : null,
                'waktu_mulai_layanan'=> $status === 'selesai' ? $waktuMulai : null,
                'waktu_selesai'      => $status === 'selesai' ? $waktuSelesai : null,
            ]);

            $created++;
        }

        return $created;
    }

    /**
     * Buat antrian hari ini dengan berbagai status aktif
     */
    private function buatAntrianHariIni($jenisLayanan, $petugas): int
    {
        $created = 0;
        $counter = [];

        // 5 antrian sudah selesai
        for ($i = 0; $i < 5; $i++) {
            $jenis = $jenisLayanan->random();
            $counter[$jenis->id] = ($counter[$jenis->id] ?? 0) + 1;
            $nomor = $counter[$jenis->id];

            $mulai   = now()->subMinutes(rand(30, 120));
            $selesai = $mulai->copy()->addMinutes(rand(5, 20));

            Antrian::create([
                'kode_antrian'        => $jenis->kode . str_pad($nomor, 3, '0', STR_PAD_LEFT),
                'jenis_layanan_id'    => $jenis->id,
                'petugas_id'          => $petugas->random()->id,
                'nama_pengunjung'     => $this->namaAcak(),
                'no_hp'               => $this->noHpAcak(),
                'email'               => null,
                'tanggal'             => today()->toDateString(),
                'nomor_urut'          => $nomor,
                'status'              => 'selesai',
                'waktu_panggil'       => $mulai->copy()->subMinutes(2),
                'waktu_mulai_layanan' => $mulai,
                'waktu_selesai'       => $selesai,
            ]);
            $created++;
        }

        // 2 antrian sedang dilayani / dipanggil
        foreach (['dilayani', 'dipanggil'] as $status) {
            $jenis = $jenisLayanan->random();
            $counter[$jenis->id] = ($counter[$jenis->id] ?? 0) + 1;
            $nomor = $counter[$jenis->id];

            Antrian::create([
                'kode_antrian'        => $jenis->kode . str_pad($nomor, 3, '0', STR_PAD_LEFT),
                'jenis_layanan_id'    => $jenis->id,
                'petugas_id'          => $petugas->random()->id,
                'nama_pengunjung'     => $this->namaAcak(),
                'no_hp'               => $this->noHpAcak(),
                'email'               => null,
                'tanggal'             => today()->toDateString(),
                'nomor_urut'          => $nomor,
                'status'              => $status,
                'waktu_panggil'       => now()->subMinutes(rand(1, 5)),
                'waktu_mulai_layanan' => $status === 'dilayani' ? now()->subMinutes(rand(1, 3)) : null,
                'waktu_selesai'       => null,
            ]);
            $created++;
        }

        // 8 antrian masih menunggu
        for ($i = 0; $i < 8; $i++) {
            $jenis = $jenisLayanan->random();
            $counter[$jenis->id] = ($counter[$jenis->id] ?? 0) + 1;
            $nomor = $counter[$jenis->id];

            Antrian::create([
                'kode_antrian'     => $jenis->kode . str_pad($nomor, 3, '0', STR_PAD_LEFT),
                'jenis_layanan_id' => $jenis->id,
                'petugas_id'       => null,
                'nama_pengunjung'  => $this->namaAcak(),
                'no_hp'            => rand(0, 1) ? $this->noHpAcak() : null,
                'email'            => null,
                'tanggal'          => today()->toDateString(),
                'nomor_urut'       => $nomor,
                'status'           => 'menunggu',
            ]);
            $created++;
        }

        return $created;
    }

    private function namaAcak(): string
    {
        return $this->namaPengunjung[array_rand($this->namaPengunjung)];
    }

    private function noHpAcak(): string
    {
        $prefix = ['0812', '0813', '0821', '0822', '0851', '0852', '0853'];
        return $prefix[array_rand($prefix)] . rand(10000000, 99999999);
    }
}