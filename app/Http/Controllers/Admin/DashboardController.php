<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Antrian;
use App\Models\JenisLayanan;
use App\Models\Pengaduan;
use App\Models\Penilaian;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // ── Statistik ringkasan ──────────────────────────────
        $totalPetugas    = User::role('petugas')->count();
        $antrianHariIni  = Antrian::whereDate('tanggal', today())->count();
        $antrianSelesai  = Antrian::whereDate('tanggal', today())
                               ->where('status', 'selesai')->count();
        $pengaduanBaru   = Pengaduan::where('status', 'baru')->count();

        // ── Antrian aktif (menunggu + dipanggil + dilayani) ──
        $antrianAktif = Antrian::with(['jenisLayanan', 'petugas'])
            ->whereDate('tanggal', today())
            ->whereIn('status', ['menunggu', 'dipanggil', 'dilayani'])
            ->orderBy('nomor_urut')
            ->get();

        // ── Statistik per jenis layanan hari ini ─────────────
        $statistikLayanan = JenisLayanan::withCount([
            'antrian as total_antrian' => fn($q) => $q->whereDate('tanggal', today()),
            'antrian as selesai_count' => fn($q) => $q->whereDate('tanggal', today())
                                                        ->where('status', 'selesai'),
        ])->aktif()->get();

        // ── Rata-rata waktu layanan hari ini (menit) ─────────
        $rataWaktu = Antrian::whereDate('tanggal', today())
            ->where('status', 'selesai')
            ->whereNotNull('waktu_mulai_layanan')
            ->whereNotNull('waktu_selesai')
            ->get()
            ->avg(fn($a) => $a->waktu_mulai_layanan->diffInMinutes($a->waktu_selesai));

        return view('admin.dashboard', compact(
            'totalPetugas',
            'antrianHariIni',
            'antrianSelesai',
            'pengaduanBaru',
            'antrianAktif',
            'statistikLayanan',
            'rataWaktu',
        ));
    }
}