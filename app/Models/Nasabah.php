<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nasabah extends Model
{
    protected $table = 'nasabah';

    protected $fillable = [
        'user_id',
        'nama_nasabah',
        'nik',
        'alamat',
        'no_hp',
        'lokasi_lelang',
        'jenis_lelang'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function dokumen()
    {
        return $this->hasMany(DokumenNasabah::class);
    }

    public function lpa()
    {
        return $this->hasMany(Lpa::class, 'nasabah_id');
    }

    public function pengajuanLelang()
    {
        return $this->hasMany(PengajuanLelang::class);
    }
}