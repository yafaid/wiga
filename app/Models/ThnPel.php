<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThnPel extends Model
{
    use HasFactory;
    protected $table = 'tahun';
    protected $fillable = [
        'tahun',
        'semester'
    ];
}
