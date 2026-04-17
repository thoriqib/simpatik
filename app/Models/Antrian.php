<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Antrian extends Model
{
    protected $table = 'antrian';
    protected $fillable = [
        'kode_antrian', 'jenis_layanan_id', 'petugas_id',
        'nama_pengunjung', 'no_hp', 'email', 'tanggal',
        'nomor_urut', 'status', 'waktu_panggil',
        'waktu_mulai_layanan', 'waktu_selesai',
    ];

    public function jenisLayanan() { return $this->belongsTo(JenisLayanan::class, 'jenis_layanan_id'); }
    public function petugas()      { return $this->belongsTo(User::class, 'petugas_id'); }
    public function penilaian()    { return $this->hasOne(Penilaian::class, 'antrian_id'); }

    /**
     * Generate kode antrian otomatis, contoh: A001, B015
     */
    public static function generateKode(int $jenisLayananId): string
    {
        $jenis = JenisLayanan::findOrFail($jenisLayananId);
        $today = today();
        $lastNo = self::where('jenis_layanan_id', $jenisLayananId)
            ->whereDate('tanggal', $today)
            ->max('nomor_urut') ?? 0;

        $nomorBaru = $lastNo + 1;
        return $jenis->kode . str_pad($nomorBaru, 3, '0', STR_PAD_LEFT);
    }
}