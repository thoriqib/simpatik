<?php
namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Presensi extends Model
{
    protected $table = 'presensi';
    protected $fillable = [
        'user_id',
        'jadwal_piket_id',
        'waktu_masuk',
        'waktu_keluar',
        'kekurangan_menit',
    ];

    protected $casts = [
        'waktu_masuk'  => 'datetime',
        'waktu_keluar' => 'datetime',
    ];

    // ── Relasi ──────────────────────────────────────────────
    public function jadwal()
    {
        return $this->belongsTo(JadwalPiket::class, 'jadwal_piket_id');
    }

    public function petugas()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // ── Accessor: durasi aktual (jam & menit) ────────────────
    public function getDurasiAttribute(): ?string
    {
        if (!$this->waktu_masuk || !$this->waktu_keluar) return null;

        $totalMenit = (int) $this->waktu_masuk->diffInMinutes($this->waktu_keluar);
        $jam        = intdiv($totalMenit, 60);
        $menit      = $totalMenit % 60;

        return trim(($jam > 0 ? "{$jam}j " : '') . ($menit > 0 ? "{$menit}m" : '0m'));
    }

    // ── Accessor: keterlambatan masuk (menit, 0 jika tepat/lebih awal) ──
    public function getTerlambatAttribute(): int
    {
        if (!$this->waktu_masuk || !$this->jadwal?->shift) return 0;

        $jamMulai = Carbon::parse(
            Carbon::parse($this->jadwal->tanggal)->toDateString()
            . ' ' . $this->jadwal->shift->jam_mulai
        );

        // Positif = masuk setelah jam mulai = terlambat
        return max(0, (int) $jamMulai->diffInMinutes($this->waktu_masuk, false));
    }

    // ── Accessor: pulang lebih awal (menit, 0 jika tepat/lebih lama) ──
    public function getPulangAwalAttribute(): int
    {
        if (!$this->waktu_keluar || !$this->jadwal?->shift) return 0;

        $jamSelesai = Carbon::parse(
            Carbon::parse($this->jadwal->tanggal)->toDateString()
            . ' ' . $this->jadwal->shift->jam_selesai
        );

        // Negatif = keluar sebelum jam selesai = pulang awal
        $selisih = (int) $jamSelesai->diffInMinutes($this->waktu_keluar, false);
        return max(0, -$selisih);
    }

    // ── Method: hitung kekurangan jam ───────────────────────
    public function hitungKekurangan(): int
    {
        if (!$this->waktu_masuk || !$this->waktu_keluar || !$this->jadwal?->shift) {
            return 0;
        }

        $tanggalStr = Carbon::parse($this->jadwal->tanggal)->toDateString();

        $jamMulai   = Carbon::parse($tanggalStr . ' ' . $this->jadwal->shift->jam_mulai);
        $jamSelesai = Carbon::parse($tanggalStr . ' ' . $this->jadwal->shift->jam_selesai);

        // Durasi shift yang seharusnya
        $durasiShift = (int) $jamMulai->diffInMinutes($jamSelesai);

        // Durasi aktual yang dijalani (dari masuk ke keluar, bukan dari jam mulai)
        $durasiAktual = (int) $this->waktu_masuk->diffInMinutes($this->waktu_keluar);

        // Kekurangan = durasi shift dikurangi durasi aktual
        return max(0, $durasiShift - $durasiAktual);
    }

    // ── Accessor: format kekurangan untuk tampilan ───────────
    public function getKekuranganFormatAttribute(): ?string
    {
        if (!$this->kekurangan_menit || $this->kekurangan_menit <= 0) return null;

        $jam   = intdiv($this->kekurangan_menit, 60);
        $menit = $this->kekurangan_menit % 60;

        return trim(($jam > 0 ? "{$jam}j " : '') . ($menit > 0 ? "{$menit}m" : ''));
    }
}