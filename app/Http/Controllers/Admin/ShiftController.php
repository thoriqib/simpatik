<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShiftPiket;
use Illuminate\Http\Request;

class ShiftController extends Controller
{
    public function index()
    {
        $shifts = ShiftPiket::orderBy('jam_mulai')->get();
        return view('admin.shift.index', compact('shifts'));
    }

    public function create()
    {
        return view('admin.shift.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_shift'  => 'required|string|max:50',
            'jam_mulai'   => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
        ]);

        ShiftPiket::create($request->only('nama_shift', 'jam_mulai', 'jam_selesai', 'is_aktif'));

        return redirect()->route('admin.shift.index')
            ->with('success', 'Shift berhasil ditambahkan.');
    }

    public function edit(ShiftPiket $shift)
    {
        return view('admin.shift.edit', compact('shift'));
    }

    public function update(Request $request, ShiftPiket $shift)
    {
        $request->validate([
            'nama_shift'  => 'required|string|max:50',
            'jam_mulai'   => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
        ]);

        $shift->update($request->only('nama_shift', 'jam_mulai', 'jam_selesai', 'is_aktif'));

        return redirect()->route('admin.shift.index')
            ->with('success', 'Shift berhasil diperbarui.');
    }

    public function destroy(ShiftPiket $shift)
    {
        // Cek apakah shift sedang digunakan di jadwal aktif
        if ($shift->jadwalPiket()->whereDate('tanggal', '>=', today())->exists()) {
            return back()->with('error', 'Shift tidak dapat dihapus karena masih digunakan di jadwal mendatang.');
        }

        $shift->delete();
        return redirect()->route('admin.shift.index')
            ->with('success', 'Shift berhasil dihapus.');
    }

    /**
     * Toggle aktif/nonaktif shift
     */
    public function toggleAktif(ShiftPiket $shift)
    {
        $shift->update(['is_aktif' => !$shift->is_aktif]);
        $status = $shift->is_aktif ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Shift {$shift->nama_shift} berhasil {$status}.");
    }
}