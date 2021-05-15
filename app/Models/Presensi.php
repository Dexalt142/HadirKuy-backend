<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presensi extends Model {
    
    use HasFactory;

    protected $table = 'presensi';

    protected $fillable = [
        'tanggal',
        'waktu',
        'pertemuan_id',
        'siswa_id'
    ];

}
