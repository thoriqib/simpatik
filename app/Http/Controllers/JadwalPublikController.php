<?php
namespace App\Http\Controllers;

use App\Models\JadwalPiket;
use App\Models\ShiftPiket;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;

class JadwalPublikController extends Controller
{
    public function index(Request $request)
    {
        $bulan = $request->bulan ?? now()->month;
        $tahun = $request->tahun ?? now()->year;

        $awalBulan  = Carbon::create($tahun, $bulan, 1)->startOfMonth();
        $akhirBulan = $awalBulan->copy()->endOfMonth();

        // Ambil semua jadwal bulan ini beserta shift dan petugas
        $semuaJadwal = JadwalPiket::with(['petugas', 'shift'])
            ->whereBetween('tanggal', [$awalBulan, $akhirBulan])
            ->orderBy('tanggal')
            ->orderBy('shift_id')
            ->get()
            ->groupBy(fn($j) => $j->tanggal->toDateString());

        $shifts = ShiftPiket::where('is_aktif', true)->orderBy('jam_mulai')->get();

        // Bangun array hari kerja dalam bulan
        $hariKerja = [];
        $periode = CarbonPeriod::create($awalBulan, $akhirBulan);
        foreach ($periode as $tanggal) {
            if (!$tanggal->isWeekend()) {
                $hariKerja[] = $tanggal->copy();
            }
        }

        return view('publik.jadwal-petugas', compact(
            'semuaJadwal', 'shifts', 'hariKerja', 'bulan', 'tahun'
        ));
    }
}