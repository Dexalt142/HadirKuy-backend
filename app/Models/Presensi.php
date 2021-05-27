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
        'siswa_id',
        'foto'
    ];

    /**
     * Get pertemuan
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pertemuan() {
        return $this->belongsTo(\App\Models\Pertemuan::class, 'pertemuan_id');
    }

    /**
     * Get siswa
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function siswa() {
        return $this->belongsTo(\App\Models\Siswa::class, 'siswa_id');
    }

}
