<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model {
    
    use HasFactory;

    protected $table = 'siswa';

    protected $fillable = [
        'nis',
        'kode',
        'nama',
        'jenis_kelamin',
        'alamat',
        'tempat_lahir',
        'tanggal_lahir',
        'foto'
    ];

}
