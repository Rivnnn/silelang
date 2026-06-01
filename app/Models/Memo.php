<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Memo extends Model
{
    protected $table = 'memo';

protected $fillable = [
    'user_id',  
    'tanggal',
    'perihal',
    'tujuan',
    'pic',
    'nomor_memo'
];
         public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function dokumen()
    {
        return $this->hasMany(DokumenNasabah::class);
    }

    public function pengajuanLelang()
    {
        return $this->hasMany(PengajuanLelang::class);
    }
}
