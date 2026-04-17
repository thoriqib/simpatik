<?php
namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\JadwalPiket;
use App\Models\Presensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PresensiController extends Controller
{
    public function masuk(Request $request)
    {
        $request->validate([
            'jadwal_piket_id' => 'required|exists:jadwal_piket,id',
        ]);

        $jadwal = JadwalPiket::findOrFail($request->jadwal_piket_id);

        // Pastikan jadwal milik petugas yang login
        abort_if($jadwal->user_id !== Auth::id(), 403);

        Presensi::firstOrCreate(
            ['jadwal_piket_id' => $jadwal->id, 'user_id' => Auth::id()],
            ['waktu_masuk' => now()]
        );

        $jadwal->update(['status' => 'hadir']);

        return back()->with('success', 'Presensi masuk berhasil dicatat: ' . now()->format('H:i'));
    }

    public function keluar(Request $request)
    {
        $presensi = Presensi::where('user_id', Auth::id())
            ->whereDate('created_at', today())
            ->whereNull('waktu_keluar')
            ->latest()
            ->firstOrFail();

        $presensi->update(['waktu_keluar' => now()]);

        return back()->with('success', 'Presensi keluar berhasil: ' . now()->format('H:i'));
    }
}