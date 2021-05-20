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

    /**
     * Get guru
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function guru() {
        return $this->belongsTo(\App\Models\Guru::class, 'guru_id');
    }

    /**
     * Get presensi
     *
     * @return Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function presensi() {
        return $this->hasMany(\App\Models\Presensi::class, 'pertemuan_id');
    }
    
}
