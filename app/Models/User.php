<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 
        'email', 
        'password', 
        'role', 
        'is_active'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    // Relasi ke data yang dibuat user
    public function nasabah()
    {
        return $this->hasMany(Nasabah::class);
    }

    public function suratKeluar()
    {
        return $this->hasMany(SuratKeluar::class);
    }

    public function memo()
    {
        return $this->hasMany(Memo::class);
    }

    public function nota()
    {
        return $this->hasMany(Nota::class);
    }

    public function lpas()
    {
        return $this->hasMany(Lpa::class);
    }

    public function pengajuanLelang()
    {
        return $this->hasMany(PengajuanLelang::class);
    }

    public function danaTrrApproved()
    {
        return $this->hasMany(DanaTrr::class, 'approved_by');
    }

    // Dana TRR yang pernah dikonfirmasi oleh user ini (petugas)
    public function danaTrrConfirmed()
    {
        return $this->hasMany(DanaTrr::class, 'confirmed_by');
    }

    // Shortcut: ambil semua TRR aktif milik petugas ini
    // (via pengajuan_lelang -> dana_trr)
    public function activeTrr()
    {
        return DanaTrr::whereHas('pengajuanLelang', function ($q) {
                $q->where('user_id', $this->id);
            })
            ->where('status', 'aktif')
            ->get();
    }
}