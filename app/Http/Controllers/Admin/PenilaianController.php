<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Penilaian;
use Illuminate\Http\Request;

class PenilaianController extends Controller
{
    public function index(Request $request)
    {
        $dari   = $request->dari   ?? today()->startOfMonth()->toDateString();
        $sampai = $request->sampai ?? today()->toDateString();
        $nilai  = $request->nilai;

        $query = Penilaian::with(['petugas', 'antrian.jenisLayanan'])
            ->whereHas('antrian', fn($q) => $q->whereBetween('tanggal', [$dari, $sampai]));

        if ($nilai) {
            $query->where('nilai', $nilai);
        }

        $penilaian = $query->latest()->paginate(20)->withQueryString();

        // Rata-rata keseluruhan
        $rataRata = Penilaian::whereHas('antrian', fn($q) =>
            $q->whereBetween('tanggal', [$dari, $sampai])
        )->avg('nilai');

        return view('admin.penilaian.index', compact('penilaian', 'rataRata', 'dari', 'sampai', 'nilai'));
    }

    public function show(Penilaian $penilaian)
    {
        $penilaian->load(['petugas', 'antrian.jenisLayanan']);
        return view('admin.penilaian.show', compact('penilaian'));
    }

    public function destroy(Penilaian $penilaian)
    {
        $penilaian->delete();
        return back()->with('success', 'Penilaian berhasil dihapus.');
    }
}