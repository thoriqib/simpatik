<?php
namespace Database\Seeders;

use App\Models\JadwalPiket;
use App\Models\ShiftPiket;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Database\Seeder;

class JadwalPiketSeeder extends Seeder
{
    public function run(): void
    {
        $petugas   = User::role('petugas')->get();
        $shifts    = ShiftPiket::all();
        $shiftPagi = $shifts->firstWhere('nama_shift', 'Pagi');
        $shiftSiang = $shifts->firstWhere('nama_shift', 'Siang');

        // Generate jadwal dari 3 bulan lalu s.d. akhir bulan depan
        $mulai  = now()->subMonths(3)->startOfMonth();
        $selesai = now()->addMonth()->endOfMonth();

        $periode = CarbonPeriod::create($mulai, $selesai);
        $created = 0;

        foreach ($periode as $tanggal) {
            // Skip hari Sabtu (6) dan Minggu (0)
            if ($tanggal->isWeekend()) {
                continue;
            }

            // Assign 3 petugas shift pagi, 3 petugas shift siang (dari 6 petugas)
            // Rotasi setiap hari supaya merata
            $petugasAcak = $petugas->shuffle();

            $grupPagi  = $petugasAcak->slice(0, 3);
            $grupSiang = $petugasAcak->slice(3, 3);

            // Tentukan status: hari yang sudah lewat → beri status realistis
            $sudahLewat = $tanggal->lt(today());

            foreach ($grupPagi as $p) {
                $status = $sudahLewat ? $this->randomStatusHistoris() : 'terjadwal';

                JadwalPiket::firstOrCreate(
                    ['user_id' => $p->id, 'tanggal' => $tanggal->toDateString(), 'shift_id' => $shiftPagi->id],
                    ['status' => $status]
                );
                $created++;
            }

            foreach ($grupSiang as $p) {
                $status = $sudahLewat ? $this->randomStatusHistoris() : 'terjadwal';

                JadwalPiket::firstOrCreate(
                    ['user_id' => $p->id, 'tanggal' => $tanggal->toDateString(), 'shift_id' => $shiftSiang->id],
                    ['status' => $status]
                );
                $created++;
            }
        }

        $this->command->info("✅ Jadwal Piket berhasil dibuat: {$created} entri.");
    }

    /**
     * Status historis dengan distribusi realistis:
     * 85% hadir, 7% izin, 5% sakit, 3% alpha
     */
    private function randomStatusHistoris(): string
    {
        $rand = rand(1, 100);

        return match(true) {
            $rand <= 85 => 'hadir',
            $rand <= 92 => 'izin',
            $rand <= 97 => 'sakit',
            default     => 'alpha',
        };
    }
}