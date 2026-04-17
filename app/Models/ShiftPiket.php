<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShiftPiket extends Model
{
    protected $table = 'shift_piket';
    protected $fillable = ['nama_shift', 'jam_mulai', 'jam_selesai', 'is_aktif'];

    public function jadwalPiket()
    {
        return $this->hasMany(JadwalPiket::class, 'shift_id');
    }
}