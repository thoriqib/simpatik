<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JenisLayanan;
use Illuminate\Http\Request;

class JenisLayananController extends Controller
{
    public function index()
    {
        $jenisLayanan = JenisLayanan::withCount([
            'antrian as total_antrian',
            'antrian as antrian_hari_ini' => fn($q) => $q->whereDate('tanggal', today()),
        ])->orderBy('kode')->get();

        return view('admin.jenis-layanan.index', compact('jenisLayanan'));
    }

    public function create()
    {
        return view('admin.jenis-layanan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode'         => 'required|string|max:5|uppercase|unique:jenis_layanan,kode',
            'nama_layanan' => 'required|string|max:100',
            'deskripsi'    => 'nullable|string|max:500',
        ]);

        JenisLayanan::create([
            'kode'         => strtoupper($request->kode),
            'nama_layanan' => $request->nama_layanan,
            'deskripsi'    => $request->deskripsi,
            'is_aktif'     => true,
        ]);

        return redirect()->route('admin.jenis-layanan.index')
            ->with('success', 'Jenis layanan berhasil ditambahkan.');
    }

    public function edit(JenisLayanan $jenisLayanan)
    {
        return view('admin.jenis-layanan.edit', compact('jenisLayanan'));
    }

    public function update(Request $request, JenisLayanan $jenisLayanan)
    {
        $request->validate([
            'kode'         => 'required|string|max:5|unique:jenis_layanan,kode,' . $jenisLayanan->id,
            'nama_layanan' => 'required|string|max:100',
            'deskripsi'    => 'nullable|string|max:500',
        ]);

        $jenisLayanan->update([
            'kode'         => strtoupper($request->kode),
            'nama_layanan' => $request->nama_layanan,
            'deskripsi'    => $request->deskripsi,
            'is_aktif'     => $request->boolean('is_aktif'),
        ]);

        return redirect()->route('admin.jenis-layanan.index')
            ->with('success', 'Jenis layanan berhasil diperbarui.');
    }

    public function destroy(JenisLayanan $jenisLayanan)
    {
        // Jangan hapus jika sudah ada antrian
        if ($jenisLayanan->antrian()->exists()) {
            return back()->with('error', 'Jenis layanan tidak dapat dihapus karena sudah memiliki data antrian.');
        }

        $nama = $jenisLayanan->nama_layanan;
        $jenisLayanan->delete();

        return redirect()->route('admin.jenis-layanan.index')
            ->with('success', "Jenis layanan {$nama} berhasil dihapus.");
    }
}