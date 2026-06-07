<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lpa extends Model
{
    protected $table = 'lpas';

    protected $fillable = [
        'user_id',
        'nasabah_id',
        'jenis_legalitas',
        'luas_tanah',
        'luas_bangunan',
        'spek_bangunan',
        'nilai_pasar',
        'nilai_likuidasi',
        'lelang_ke',
        'nilai_limit',
        'uang_jaminan'
    ];

    protected $casts = [
        'luas_tanah' => 'decimal:2',
        'luas_bangunan' => 'decimal:2',
        'nilai_pasar' => 'decimal:2',
        'nilai_likuidasi' => 'decimal:2',
        'nilai_limit' => 'decimal:2',
        'uang_jaminan' => 'decimal:2',
    ];

    /**
     * LPA dibuat oleh user (petugas)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    

    /**
     * LPA untuk nasabah tertentu
     */
    public function nasabah()
    {
        return $this->belongsTo(Nasabah::class, 'nasabah_id');
    }
}