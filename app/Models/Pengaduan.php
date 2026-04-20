<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengaduan extends Model
{
    protected $table = 'pengaduan';
    protected $fillable = [
        'subjek', 'isi_pengaduan', 'lampiran', 'status',
        'tanggapan', 'ditangani_oleh', 'ditanggapi_pada'
    ];

    public function penanganan() { return $this->belongsTo(User::class, 'ditangani_oleh'); }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'baru'      => '<span class="bg-red-100 text-red-700 px-2 py-1 rounded text-xs">Baru</span>',
            'diproses'  => '<span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded text-xs">Diproses</span>',
            'selesai'   => '<span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs">Selesai</span>',
            default     => $this->status,
        };
    }
}