<?php
namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Antrian;
use Illuminate\Support\Facades\Auth;

class AntrianController extends Controller
{
    public function panggil(Antrian $antrian)
    {
        // Set petugas yang memanggil
        $antrian->update([
            'status'        => 'dipanggil',
            'petugas_id'    => Auth::id(),
            'waktu_panggil' => now(),
        ]);

        return back()->with('success', "Antrian {$antrian->kode_antrian} dipanggil.");
    }

    public function mulaiLayani(Antrian $antrian)
    {
        $antrian->update([
            'status'               => 'dilayani',
            'waktu_mulai_layanan'  => now(),
        ]);

        return back()->with('success', "Layanan {$antrian->kode_antrian} dimulai.");
    }

    public function selesai(Antrian $antrian)
    {
        $antrian->update([
            'status'         => 'selesai',
            'waktu_selesai'  => now(),
        ]);

        return back()->with('success', "Antrian {$antrian->kode_antrian} selesai dilayani.");
    }

    public function batal(Antrian $antrian)
    {
        $antrian->update(['status' => 'batal']);
        return back()->with('info', "Antrian {$antrian->kode_antrian} dibatalkan.");
    }
}