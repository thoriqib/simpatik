<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PetugasController extends Controller
{
    /**
     * Daftar semua petugas
     */
    public function index()
    {
        $petugas = User::role('petugas')
            ->withCount('antrian as antrian_count')  // jumlah antrian yang pernah ditangani
            ->withAvg('penilaianSebagaiPetugas as avg_nilai', 'nilai')
            ->paginate(15);

        return view('admin.petugas.index', compact('petugas'));
    }

    /**
     * Form tambah petugas
     */
    public function create()
    {
        return view('admin.petugas.create');
    }

    /**
     * Simpan petugas baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email',
            'password' => ['required', Password::min(8)->mixedCase()->numbers()],
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole('petugas');

        return redirect()->route('admin.petugas.index')
            ->with('success', "Petugas {$user->name} berhasil ditambahkan.");
    }

    /**
     * Form edit petugas
     */
    public function edit(User $petugas)
    {
        // Pastikan yang diedit adalah petugas (bukan admin)
        abort_if(!$petugas->hasRole('petugas'), 404);

        return view('admin.petugas.edit', compact('petugas'));
    }

    /**
     * Update data petugas
     */
    public function update(Request $request, User $petugas)
    {
        abort_if(!$petugas->hasRole('petugas'), 404);

        $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email,' . $petugas->id,
            'password' => ['nullable', Password::min(8)->mixedCase()->numbers()],
        ]);

        $petugas->update([
            'name'  => $request->name,
            'email' => $request->email,
        ]);

        if ($request->filled('password')) {
            $petugas->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->route('admin.petugas.index')
            ->with('success', "Data petugas {$petugas->name} berhasil diperbarui.");
    }

    /**
     * Hapus petugas
     */
    public function destroy(User $petugas)
    {
        abort_if(!$petugas->hasRole('petugas'), 404);

        $nama = $petugas->name;
        $petugas->delete();

        return redirect()->route('admin.petugas.index')
            ->with('success', "Petugas {$nama} berhasil dihapus.");
    }
}