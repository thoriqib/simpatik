<?php
namespace Database\Seeders;

use App\Models\Antrian;
use App\Models\Penilaian;
use Illuminate\Database\Seeder;

class PenilaianSeeder extends Seeder
{
    // Komentar per nilai bintang
    private array $komentar = [
        1 => [
            'Pelayanan sangat lambat dan petugas kurang ramah.',
            'Saya kecewa dengan pelayanan hari ini. Antri sangat lama.',
            'Informasi yang diberikan tidak akurat, harus bertanya berulang kali.',
        ],
        2 => [
            'Waktu tunggu cukup lama. Perlu ditingkatkan lagi.',
            'Petugas kurang proaktif dalam memberikan bantuan.',
            'Fasilitas ruang tunggu perlu diperbaiki.',
        ],
        3 => [
            'Pelayanan cukup baik, namun masih ada ruang untuk perbaikan.',
            'Petugas ramah, tapi proses agak lama.',
            'Informasi cukup lengkap, terima kasih.',
        ],
        4 => [
            'Pelayanan baik dan petugas sangat membantu.',
            'Proses cepat dan informasi yang diberikan jelas.',
            'Saya puas dengan pelayanan yang diberikan.',
            'Petugas ramah dan profesional.',
        ],
        5 => [
            'Pelayanan luar biasa! Sangat puas dengan respons petugas.',
            'Proses sangat cepat dan petugas sangat profesional. Terima kasih!',
            'Sangat puas! Petugas sabar dan informatif.',
            'Pelayanan terbaik yang pernah saya rasakan di instansi pemerintah.',
            'Petugas ramah, tepat waktu, dan sangat membantu. Terus pertahankan!',
        ],
    ];

    public function run(): void
    {
        // Ambil antrian selesai yang belum dinilai
        $antrianSelesai = Antrian::where('status', 'selesai')
            ->whereNotNull('petugas_id')
            ->doesntHave('penilaian')
            ->with('petugas')
            ->get();

        $created = 0;

        foreach ($antrianSelesai as $antrian) {
            // 70% pengunjung memberikan penilaian (tidak semua menilai)
            if (rand(1, 100) > 70) {
                continue;
            }

            // Distribusi nilai: mayoritas 4-5 (realistis untuk layanan publik)
            $nilai = $this->generateNilai();

            // 50% memberikan komentar
            $komentar = rand(0, 1)
                ? $this->komentar[$nilai][array_rand($this->komentar[$nilai])]
                : null;

            Penilaian::create([
                'antrian_id' => $antrian->id,
                'petugas_id' => $antrian->petugas_id,
                'nilai'      => $nilai,
                'komentar'   => $komentar,
                'created_at' => $antrian->waktu_selesai ?? $antrian->created_at,
                'updated_at' => $antrian->waktu_selesai ?? $antrian->updated_at,
            ]);

            $created++;
        }

        $this->command->info("✅ Penilaian berhasil dibuat: {$created} entri.");
    }

    /**
     * Distribusi nilai realistis:
     * 5★ = 45%, 4★ = 30%, 3★ = 15%, 2★ = 7%, 1★ = 3%
     */
    private function generateNilai(): int
    {
        $rand = rand(1, 100);

        return match(true) {
            $rand <= 3  => 1,
            $rand <= 10 => 2,
            $rand <= 25 => 3,
            $rand <= 55 => 4,
            default     => 5,
        };
    }
}