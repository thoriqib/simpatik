<?php
namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\JadwalPiket;
use App\Models\Presensi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PresensiController extends Controller
{
    public function index(Request $request)
    {
        $bulan  = $request->bulan  ?? now()->month;
        $tahun  = $request->tahun  ?? now()->year;
        $userId = Auth::id();

        $jadwalHariIni   = JadwalPiket::with(['shift', 'presensi'])
            ->where('user_id', $userId)
            ->whereDate('tanggal', today())
            ->first();
        $presensiHariIni = $jadwalHariIni?->presensi;

        $jadwal = JadwalPiket::with(['shift', 'presensi'])
            ->where('user_id', $userId)
            ->whereYear('tanggal', $tahun)
            ->whereMonth('tanggal', $bulan)
            ->orderBy('tanggal')
            ->get();

        $rekap = [
            'hadir'            => $jadwal->where('status', 'hadir')->count(),
            'izin'             => $jadwal->where('status', 'izin')->count(),
            'sakit'            => $jadwal->where('status', 'sakit')->count(),
            'alpha'            => $jadwal->where('status', 'alpha')->count(),
            'terjadwal'        => $jadwal->where('status', 'terjadwal')->count(),
            'total_kekurangan' => $jadwal->sum(
                fn($j) => $j->presensi?->kekurangan_menit ?? 0
            ),
        ];

        return view('petugas.presensi', compact(
            'jadwalHariIni', 'presensiHariIni', 'jadwal', 'rekap', 'bulan', 'tahun'
        ));
    }

    public function masuk(Request $request)
    {
        $request->validate([
            'jadwal_piket_id' => 'required|exists:jadwal_piket,id',
        ]);

        $jadwal = JadwalPiket::with('shift')->findOrFail($request->jadwal_piket_id);

        // Pastikan jadwal milik petugas yang login
        abort_if($jadwal->user_id !== Auth::id(), 403);

        // Cegah presensi masuk ganda
        if (Presensi::where('jadwal_piket_id', $jadwal->id)
                ->whereNotNull('waktu_masuk')->exists()) {
            return back()->with('info', 'Anda sudah melakukan presensi masuk hari ini.');
        }

        // now() otomatis WIB karena timezone sudah diset di config/app.php
        $waktuMasuk = now();

        Presensi::create([
            'user_id'         => Auth::id(),
            'jadwal_piket_id' => $jadwal->id,
            'waktu_masuk'     => $waktuMasuk,
        ]);

        $jadwal->update(['status' => 'hadir']);

        // Hitung keterlambatan berdasarkan jam shift
        $jamMulaiShift = Carbon::parse(
            today()->toDateString() . ' ' . $jadwal->shift->jam_mulai
        );

        // diffInMinutes: positif = waktu masuk SETELAH jam mulai (terlambat)
        $terlambatMenit = (int) $jamMulaiShift->diffInMinutes($waktuMasuk, false);

        if ($terlambatMenit > 0) {
            return back()->with(
                'warning',
                "Presensi masuk tercatat pukul {$waktuMasuk->format('H:i')} WIB. "
                . "Anda terlambat {$terlambatMenit} menit."
            );
        }

        return back()->with(
            'success',
            "Presensi masuk berhasil dicatat: {$waktuMasuk->format('H:i')} WIB."
        );
    }

    public function keluar(Request $request)
    {
        // Ambil presensi hari ini yang belum ada waktu keluar
        $presensi = Presensi::with(['jadwal.shift'])
            ->where('user_id', Auth::id())
            ->whereHas('jadwal', fn($q) => $q->whereDate('tanggal', today()))
            ->whereNull('waktu_keluar')
            ->latest()
            ->firstOrFail();

        $waktuKeluar = now(); // WIB otomatis

        $presensi->update(['waktu_keluar' => $waktuKeluar]);

        // Hitung & simpan kekurangan jam
        $kekuranganMenit = $presensi->hitungKekurangan();
        $presensi->update(['kekurangan_menit' => $kekuranganMenit]);

        // Hitung pulang lebih awal
        $jamSelesaiShift = Carbon::parse(
            today()->toDateString() . ' ' . $presensi->jadwal->shift->jam_selesai
        );

        // diffInMinutes: negatif = waktu keluar SEBELUM jam selesai (pulang awal)
        $pulangAwalMenit = (int) $jamSelesaiShift->diffInMinutes($waktuKeluar, false);

        if ($kekuranganMenit > 0) {
            $jam   = floor($kekuranganMenit / 60);
            $menit = $kekuranganMenit % 60;
            $formatKurang = trim(($jam > 0 ? "{$jam} jam " : '') . ($menit > 0 ? "{$menit} menit" : ''));

            return back()->with(
                'warning',
                "Presensi keluar tercatat pukul {$waktuKeluar->format('H:i')} WIB. "
                . "Kekurangan jam: {$formatKurang}."
            );
        }

        return back()->with(
            'success',
            "Presensi keluar berhasil dicatat: {$waktuKeluar->format('H:i')} WIB."
        );
    }
}