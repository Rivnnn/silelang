<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengajuanLelang extends Model
{
    protected $table = 'pengajuan_lelang';

    protected $fillable = [
        'nasabah_id',
        'user_id',
        'status',
        'catatan_admin',
        'tanggal_pengajuan'
    ];

    protected $casts = [
        'tanggal_pengajuan' => 'date'
    ];

    public function nasabah()
    {
        return $this->belongsTo(Nasabah::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}