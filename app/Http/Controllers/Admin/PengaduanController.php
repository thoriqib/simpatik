<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengaduan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengaduanController extends Controller
{
    /**
     * Daftar semua pengaduan
     */
    public function index(Request $request)
    {
        $status = $request->status;

        $query = Pengaduan::with('penanganan')->latest();

        if ($status) {
            $query->where('status', $status);
        }

        $pengaduan = $query->paginate(15)->withQueryString();

        // Hitung per status untuk badge di filter
        $jumlahBaru     = Pengaduan::where('status', 'baru')->count();
        $jumlahDiproses = Pengaduan::where('status', 'diproses')->count();
        $jumlahSelesai  = Pengaduan::where('status', 'selesai')->count();

        return view('admin.pengaduan.index', compact(
            'pengaduan', 'status', 'jumlahBaru', 'jumlahDiproses', 'jumlahSelesai'
        ));
    }

    /**
     * Detail satu pengaduan
     * Route: GET /admin/pengaduan/{pengaduan}
     * Name:  admin.pengaduan.show
     */
    public function show(Pengaduan $pengaduan)
    {
        $pengaduan->load('penanganan');

        return view('admin.pengaduan.show', compact('pengaduan'));
    }

    /**
     * Kirim tanggapan & update status
     * Route: POST /admin/pengaduan/{pengaduan}/tanggapi
     * Name:  admin.pengaduan.tanggapi
     */
    public function tanggapi(Request $request, Pengaduan $pengaduan)
    {
        $request->validate([
            'tanggapan' => 'required|string|min:10',
            'status'    => 'required|in:diproses,selesai',
        ]);

        $pengaduan->update([
            'tanggapan'       => $request->tanggapan,
            'status'          => $request->status,
            'ditangani_oleh'  => Auth::id(),
            'ditanggapi_pada' => $request->status === 'selesai'
                                    ? now()
                                    : $pengaduan->ditanggapi_pada,
        ]);

        $pesan = $request->status === 'selesai'
            ? 'Tanggapan berhasil dikirim. Pengaduan ditandai Selesai.'
            : 'Status pengaduan diperbarui menjadi Diproses.';

        return back()->with('success', $pesan);
    }

    /**
     * Update status saja (tanpa tanggapan)
     * Route: PATCH /admin/pengaduan/{pengaduan}/status
     * Name:  admin.pengaduan.status
     */
    public function updateStatus(Request $request, Pengaduan $pengaduan)
    {
        $request->validate([
            'status' => 'required|in:baru,diproses,selesai',
        ]);

        $pengaduan->update([
            'status'         => $request->status,
            'ditangani_oleh' => Auth::id(),
        ]);

        return back()->with('success', 'Status pengaduan berhasil diperbarui.');
    }
}