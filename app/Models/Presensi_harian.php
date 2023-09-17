<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presensi_harian extends Model
{
    use HasFactory;
    protected $table = 'presensi_harian';
    protected $fillable = [
        'siswa_id',
        'kelas_id',
        'tanggal',
        'keterangan',
    ];
    
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }
}
