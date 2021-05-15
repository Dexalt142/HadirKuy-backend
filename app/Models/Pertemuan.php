<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pertemuan extends Model {

    use HasFactory;

    protected $table = 'pertemuan';

    protected $fillable = [
        'nama',
        'tanggal',
        'waktu',
        'kode_pertemuan',
        'guru_id'
    ];
    
}
