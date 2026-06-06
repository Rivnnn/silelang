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
        'tanggal_pengajuan',
        'estimasi_dana_trr',
    ];

    protected $casts = [
        'tanggal_pengajuan' => 'date',
        'estimasi_dana_trr' => 'decimal:2', 
    ];

    public function nasabah()
    {
        return $this->belongsTo(Nasabah::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function danaTrr()
    {
        return $this->hasOne(DanaTrr::class);
    }

    public function pengajuanTrr()
    {
        return $this->hasMany(PengajuanTrr::class);
    }

    // Cek apakah lelang ini sudah punya pengajuan TRR aktif
    public function hasPengajuanTrrPending(): bool
    {
        return $this->pengajuanTrr()
                    ->where('status', 'pending')
                    ->exists();
    }
    
}