<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengaduan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengaduanController extends Controller
{
    public function index()
    {
        $pengaduan = Pengaduan::latest()->paginate(15);
        return view('admin.pengaduan.index', compact('pengaduan'));
    }

    public function tanggapi(Request $request, Pengaduan $pengaduan)
    {
        $request->validate(['tanggapan' => 'required|string']);

        $pengaduan->update([
            'tanggapan'       => $request->tanggapan,
            'status'          => 'selesai',
            'ditangani_oleh'  => Auth::id(),
            'ditanggapi_pada' => now(),
        ]);

        return back()->with('success', 'Tanggapan berhasil disimpan.');
    }
}