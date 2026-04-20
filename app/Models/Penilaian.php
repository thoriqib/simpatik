<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penilaian extends Model
{
    protected $table = 'penilaian';
    protected $fillable = ['antrian_id', 'petugas_id', 'nilai', 'komentar'];

    public function antrian() { return $this->belongsTo(Antrian::class, 'antrian_id'); }
    public function petugas() { return $this->belongsTo(User::class, 'petugas_id'); }
}