<?php
namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\JadwalPiket;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JadwalController extends Controller
{
    public function index(Request $request)
    {
        $bulan  = $request->bulan ?? now()->month;
        $tahun  = $request->tahun ?? now()->year;
        $userId = Auth::id();

        // Jadwal bulan yang dipilih, dengan relasi shift dan presensi
        $jadwal = JadwalPiket::with(['shift', 'presensi'])
            ->where('user_id', $userId)
            ->whereYear('tanggal', $tahun)
            ->whereMonth('tanggal', $bulan)
            ->orderBy('tanggal')
            ->get();

        // Rekap kehadiran bulan ini
        $rekap = [
            'hadir'     => $jadwal->where('status', 'hadir')->count(),
            'izin'      => $jadwal->where('status', 'izin')->count(),
            'sakit'     => $jadwal->where('status', 'sakit')->count(),
            'alpha'     => $jadwal->where('status', 'alpha')->count(),
            'terjadwal' => $jadwal->where('status', 'terjadwal')->count(),
            'total'     => $jadwal->count(),
        ];

        return view('petugas.jadwal', compact('jadwal', 'rekap', 'bulan', 'tahun'));
    }
}