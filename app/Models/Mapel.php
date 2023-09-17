<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Kelas;

class Mapel extends Model
{
    use HasFactory;
    protected $table = 'mapels';
    protected $fillable = [
        'kodemapel',
        'mapel',
        'kelas_id'
    ];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }
}
