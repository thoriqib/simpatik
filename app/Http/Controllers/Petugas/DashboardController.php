<?php
namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Antrian;
use App\Models\JadwalPiket;
use App\Models\Presensi;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // ── Jadwal & presensi hari ini ───────────────────────
        $jadwalHariIni = JadwalPiket::with(['shift', 'presensi'])
            ->where('user_id', $userId)
            ->whereDate('tanggal', today())
            ->first();

        $presensiHariIni = $jadwalHariIni?->presensi;

        // ── Antrian aktif (semua yang menunggu/dipanggil/dilayani) ──
        $antrianAktif = Antrian::with('jenisLayanan')
            ->whereDate('tanggal', today())
            ->whereIn('status', ['menunggu', 'dipanggil', 'dilayani'])
            ->orderBy('nomor_urut')
            ->get();

        // ── Antrian yang saya tangani hari ini ───────────────
        $antrianSayaHariIni = Antrian::where('petugas_id', $userId)
            ->whereDate('tanggal', today())
            ->count();

        // ── Kalender jadwal bulan ini ─────────────────────────
        $kalender = $this->buildKalender(
            now()->year,
            now()->month,
            $userId
        );

        return view('petugas.dashboard', compact(
            'jadwalHariIni',
            'presensiHariIni',
            'antrianAktif',
            'antrianSayaHariIni',
            'kalender',
        ));
    }

    /**
     * Bangun array kalender bulanan.
     * Setiap elemen: ['tanggal' => int|null, 'jadwal' => bool, 'today' => bool]
     */
    private function buildKalender(int $tahun, int $bulan, int $userId): array
    {
        $awalBulan   = Carbon::create($tahun, $bulan, 1);
        $akhirBulan  = $awalBulan->copy()->endOfMonth();

        // Ambil semua tanggal jadwal petugas di bulan ini
        $tanggalJadwal = JadwalPiket::where('user_id', $userId)
            ->whereYear('tanggal', $tahun)
            ->whereMonth('tanggal', $bulan)
            ->pluck('tanggal')
            ->map(fn($t) => $t->day)
            ->toArray();

        $kalender = [];

        // Padding awal (hari sebelum tanggal 1, mulai dari Minggu = 0)
        $padding = $awalBulan->dayOfWeek; // 0 = Minggu
        for ($i = 0; $i < $padding; $i++) {
            $kalender[] = ['tanggal' => null, 'jadwal' => false, 'today' => false];
        }

        // Isi hari-hari dalam bulan
        for ($hari = 1; $hari <= $akhirBulan->day; $hari++) {
            $kalender[] = [
                'tanggal' => $hari,
                'jadwal'  => in_array($hari, $tanggalJadwal),
                'today'   => ($hari === today()->day && $bulan === today()->month && $tahun === today()->year),
            ];
        }

        return $kalender;
    }
}