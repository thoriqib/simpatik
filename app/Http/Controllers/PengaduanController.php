<?php
namespace App\Http\Controllers;

use App\Models\Pengaduan;
use Illuminate\Http\Request;

class PengaduanController extends Controller
{
    public function create()
    {
        return view('publik.pengaduan');
    }
    public function store(Request $request)
    {
        $request->validate([
            'subjek'        => 'required|string|max:150',
            'isi_pengaduan' => 'required|string',
            'lampiran'      => 'nullable|file|mimes:jpg,png,pdf|max:2048',
        ]);

        $data = $request->except('lampiran');

        if ($request->hasFile('lampiran')) {
            $data['lampiran'] = $request->file('lampiran')->store('pengaduan', 'public');
        }

        Pengaduan::create($data);

        return back()->with('success', 'Pengaduan Anda berhasil dikirim. Kami akan segera menindaklanjuti.');
    }
}