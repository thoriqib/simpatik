<?php
namespace App\Http\Controllers;

use App\Models\Antrian;
use App\Models\Penilaian;
use Illuminate\Http\Request;

class PenilaianController extends Controller
{
    public function create(string $kode)
    {
        $antrian = Antrian::with('petugas')
            ->where('kode_antrian', $kode)
            ->where('status', 'selesai')
            ->whereNull('penilaian') // belum dinilai — relasi via has()
            ->firstOrFail();

        return view('publik.penilaian', compact('antrian'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'antrian_id' => 'required|exists:antrian,id',
            'nilai'      => 'required|integer|min:1|max:5',
            'komentar'   => 'nullable|string|max:500',
        ]);

        $antrian = Antrian::findOrFail($request->antrian_id);

        // Pastikan antrian sudah selesai & belum dinilai
        abort_if($antrian->status !== 'selesai', 422, 'Antrian belum selesai dilayani.');
        abort_if($antrian->penilaian()->exists(), 422, 'Antrian ini sudah dinilai.');

        Penilaian::create([
            'antrian_id' => $antrian->id,
            'petugas_id' => $antrian->petugas_id,
            'nilai'      => $request->nilai,
            'komentar'   => $request->komentar,
        ]);

        return redirect()->route('home')->with('success', 'Terima kasih atas penilaian Anda! 🌟');
    }
}