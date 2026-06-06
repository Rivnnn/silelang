<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengajuanTrr extends Model
{
    protected $table = 'pengajuan_trr';

    protected $fillable = [
        'pengajuan_lelang_id',
        'user_id',
        'nominal_diajukan',
        'keterangan',
        'status',
        'nominal_disetujui',
        'catatan_admin',
        'processed_by',
        'processed_at',
    ];

    protected $casts = [
        'nominal_diajukan'  => 'decimal:2',
        'nominal_disetujui' => 'decimal:2',
        'processed_at'      => 'datetime',
    ];

    // Pengajuan TRR ini untuk lelang yang mana
    public function pengajuanLelang()
    {
        return $this->belongsTo(PengajuanLelang::class);
    }

    // Petugas yang mengajukan
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Admin yang memproses
    public function processedBy()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    // Dana TRR yang lahir dari pengajuan ini
    public function danaTrr()
    {
        return $this->hasOne(DanaTrr::class);
    }
}