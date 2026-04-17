<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JadwalPiket;
use App\Models\ShiftPiket;
use App\Models\User;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    public function index(Request $request)
    {
        $bulan  = $request->bulan ?? now()->month;
        $tahun  = $request->tahun ?? now()->year;

        $jadwal = JadwalPiket::with(['petugas', 'shift'])
            ->whereYear('tanggal', $tahun)
            ->whereMonth('tanggal', $bulan)
            ->orderBy('tanggal')
            ->get()
            ->groupBy('tanggal');

        $petugas = User::role('petugas')->get();
        $shifts  = ShiftPiket::where('is_aktif', true)->get();

        return view('admin.jadwal.index', compact('jadwal', 'petugas', 'shifts', 'bulan', 'tahun'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id'  => 'required|exists:users,id',
            'shift_id' => 'required|exists:shift_piket,id',
            'tanggal'  => 'required|date|after_or_equal:today',
        ]);

        JadwalPiket::firstOrCreate(
            ['user_id' => $request->user_id, 'tanggal' => $request->tanggal, 'shift_id' => $request->shift_id],
            ['status'  => 'terjadwal']
        );

        return back()->with('success', 'Jadwal berhasil ditambahkan.');
    }

    public function destroy(JadwalPiket $jadwal)
    {
        $jadwal->delete();
        return back()->with('success', 'Jadwal berhasil dihapus.');
    }
}