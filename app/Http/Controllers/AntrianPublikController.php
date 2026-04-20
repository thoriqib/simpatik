<?php
namespace App\Http\Controllers;

use App\Models\Antrian;
use App\Models\JenisLayanan;
use Illuminate\Http\Request;

class AntrianPublikController extends Controller
{
    public function index()
    {
        $jenisLayanan = JenisLayanan::where('is_aktif', true)->get();
        return view('publik.antrian', compact('jenisLayanan'));
    }

    public function ambil(Request $request)
    {
        $request->validate([
            'jenis_layanan_id' => 'required|exists:jenis_layanan,id',
            'nama_pengunjung'  => 'required|string|max:100',
            'no_hp'            => 'nullable|string|max:15',
            'email'            => 'nullable|email|max:100',
        ]);

        $jenis   = JenisLayanan::findOrFail($request->jenis_layanan_id);
        $lastNo  = Antrian::where('jenis_layanan_id', $jenis->id)
            ->whereDate('tanggal', today())
            ->max('nomor_urut') ?? 0;

        $nomorBaru = $lastNo + 1;
        $kode      = $jenis->kode . str_pad($nomorBaru, 3, '0', STR_PAD_LEFT);

        $antrian = Antrian::create([
            'kode_antrian'     => $kode,
            'jenis_layanan_id' => $jenis->id,
            'nama_pengunjung'  => $request->nama_pengunjung,
            'no_hp'            => $request->no_hp,
            'email'            => $request->email,
            'tanggal'          => today(),
            'nomor_urut'       => $nomorBaru,
            'status'           => 'menunggu',
        ]);

        return redirect()->route('antrian.tiket', $antrian->kode_antrian);
    }

    public function tiket(string $kode)
    {
        $antrian = Antrian::with('jenisLayanan')
            ->where('kode_antrian', $kode)
            ->whereDate('tanggal', today())
            ->firstOrFail();

        $menunggu = Antrian::where('jenis_layanan_id', $antrian->jenis_layanan_id)
            ->whereDate('tanggal', today())
            ->where('status', 'menunggu')
            ->where('nomor_urut', '<', $antrian->nomor_urut)
            ->count();

        return view('publik.tiket', compact('antrian', 'menunggu'));
    }

    public function display()
    {
        $antrian = Antrian::with(['jenisLayanan', 'petugas'])
            ->whereDate('tanggal', today())
            ->whereIn('status', ['dipanggil', 'dilayani'])
            ->orderByDesc('waktu_panggil')
            ->take(10)
            ->get();

        $menunggu = Antrian::whereDate('tanggal', today())
            ->where('status', 'menunggu')
            ->count();
        $selesai = Antrian::whereDate('tanggal', today())
            ->where('status', 'selesai')
            ->count();

        return view('publik.display-antrian', compact('antrian', 'menunggu', 'selesai'));
    }
}