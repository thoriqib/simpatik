<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Presensi extends Model
{
    protected $fillable = [
        'user_id', 'jadwal_piket_id', 'waktu_masuk', 'waktu_keluar'
    ];
    protected $casts = [
        'waktu_masuk'  => 'datetime',
        'waktu_keluar' => 'datetime',
    ];

    public function jadwal()  { return $this->belongsTo(JadwalPiket::class, 'jadwal_piket_id'); }
    public function petugas() { return $this->belongsTo(User::class, 'user_id'); }

    public function getDurasiAttribute(): ?string
    {
        if ($this->waktu_masuk && $this->waktu_keluar) {
            $menit = $this->waktu_masuk->diffInMinutes($this->waktu_keluar);
            return floor($menit / 60) . 'j ' . ($menit % 60) . 'm';
        }
        return null;
    }
}