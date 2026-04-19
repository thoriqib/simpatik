<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JenisLayanan extends Model
{
    use HasFactory;

    protected $table = 'jenis_layanan';

    protected $fillable = [
        'kode',
        'nama_layanan',
        'deskripsi',
        'is_aktif',
    ];

    protected $casts = [
        'is_aktif' => 'boolean',
    ];

    /**
     * Relasi ke antrian
     */
    public function antrian()
    {
        return $this->hasMany(Antrian::class, 'jenis_layanan_id');
    }

    /**
     * Scope: hanya yang aktif
     */
    public function scopeAktif($query)
    {
        return $query->where('is_aktif', true);
    }

    /**
     * Hitung antrian hari ini untuk jenis layanan ini
     */
    public function getAntrianHariIniCountAttribute(): int
    {
        return $this->antrian()
            ->whereDate('tanggal', today())
            ->count();
    }

    /**
     * Nomor antrian terakhir hari ini
     */
    public function getNomorTerakhirAttribute(): int
    {
        return $this->antrian()
            ->whereDate('tanggal', today())
            ->max('nomor_urut') ?? 0;
    }
}