<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Antrian;
use App\Models\JadwalPiket;
use App\Models\Penilaian;
use App\Models\User;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    /**
     * Laporan Antrian
     */
    public function antrian(Request $request)
    {
        $dari    = $request->dari    ?? today()->startOfMonth()->toDateString();
        $sampai  = $request->sampai  ?? today()->toDateString();
        $statusFilter = $request->status ?? 'semua';

        $query = Antrian::with(['jenisLayanan', 'petugas'])
            ->whereBetween('tanggal', [$dari, $sampai]);

        if ($statusFilter !== 'semua') {
            $query->where('status', $statusFilter);
        }

        $antrian = $query->orderBy('tanggal')->orderBy('nomor_urut')->paginate(25)->withQueryString();

        // Ringkasan statistik
        $ringkasan = Antrian::whereBetween('tanggal', [$dari, $sampai])
            ->selectRaw("
                COUNT(*) as total,
                SUM(CASE WHEN status = 'selesai'  THEN 1 ELSE 0 END) as selesai,
                SUM(CASE WHEN status = 'batal'    THEN 1 ELSE 0 END) as batal,
                SUM(CASE WHEN status = 'menunggu' THEN 1 ELSE 0 END) as menunggu,
                AVG(CASE WHEN status = 'selesai' AND waktu_mulai_layanan IS NOT NULL
                    THEN TIMESTAMPDIFF(MINUTE, waktu_mulai_layanan, waktu_selesai)
                    END) as avg_durasi_menit
            ")->first();

        return view('admin.laporan.antrian', compact('antrian', 'ringkasan', 'dari', 'sampai', 'statusFilter'));
    }

    /**
     * Laporan Penilaian
     */
    public function penilaian(Request $request)
    {
        $dari   = $request->dari   ?? today()->startOfMonth()->toDateString();
        $sampai = $request->sampai ?? today()->toDateString();

        // Rata-rata nilai per petugas dalam rentang tanggal
        $nilaiPerPetugas = User::role('petugas')
            ->withAvg([
                'penilaianSebagaiPetugas as avg_nilai' =>
                    fn($q) => $q->whereHas('antrian', fn($a) => $a->whereBetween('tanggal', [$dari, $sampai]))
            ], 'nilai')
            ->withCount([
                'penilaianSebagaiPetugas as total_penilaian' =>
                    fn($q) => $q->whereHas('antrian', fn($a) => $a->whereBetween('tanggal', [$dari, $sampai]))
            ])
            ->orderByDesc('avg_nilai')
            ->get();

        // Distribusi nilai (1–5 bintang) dalam rentang tanggal
        $distribusi = Penilaian::whereHas('antrian', fn($q) => $q->whereBetween('tanggal', [$dari, $sampai]))
            ->selectRaw('nilai, COUNT(*) as jumlah')
            ->groupBy('nilai')
            ->orderBy('nilai')
            ->pluck('jumlah', 'nilai');

        // Penilaian terbaru dengan komentar
        $komentar = Penilaian::with(['petugas', 'antrian.jenisLayanan'])
            ->whereHas('antrian', fn($q) => $q->whereBetween('tanggal', [$dari, $sampai]))
            ->whereNotNull('komentar')
            ->latest()
            ->take(10)
            ->get();

        return view('admin.laporan.penilaian', compact(
            'nilaiPerPetugas', 'distribusi', 'komentar', 'dari', 'sampai'
        ));
    }

    /**
     * Laporan Presensi
     */
    public function presensi(Request $request)
    {
        $bulan   = $request->bulan  ?? now()->month;
        $tahun   = $request->tahun  ?? now()->year;

        $jadwal = JadwalPiket::with(['petugas', 'shift', 'presensi'])
            ->whereYear('tanggal', $tahun)
            ->whereMonth('tanggal', $bulan)
            ->orderBy('tanggal')
            ->get();

        // Rekap per petugas
        $rekapPerPetugas = $jadwal->groupBy('user_id')->map(function ($items) {
            $petugas = $items->first()->petugas;
            return [
                'nama'             => $petugas->name,
                'hadir'            => $items->where('status', 'hadir')->count(),
                'izin'             => $items->where('status', 'izin')->count(),
                'sakit'            => $items->where('status', 'sakit')->count(),
                'alpha'            => $items->where('status', 'alpha')->count(),
                'terjadwal'        => $items->where('status', 'terjadwal')->count(),
                'total'            => $items->count(),
                'total_kekurangan' => $items->sum(  // ← BARU
                    fn($j) => $j->presensi?->kekurangan_menit ?? 0
                ),
            ];
        })->values();

        return view('admin.laporan.presensi', compact('jadwal', 'rekapPerPetugas', 'bulan', 'tahun'));
    }
}