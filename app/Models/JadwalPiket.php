<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalPiket extends Model
{
    protected $table = 'jadwal_piket';
    protected $fillable = ['user_id', 'shift_id', 'tanggal', 'status'];
    protected $casts = ['tanggal' => 'date'];

    public function petugas() { return $this->belongsTo(User::class, 'user_id'); }
    public function shift()   { return $this->belongsTo(ShiftPiket::class, 'shift_id'); }
    public function presensi() { return $this->hasOne(Presensi::class, 'jadwal_piket_id'); }
}