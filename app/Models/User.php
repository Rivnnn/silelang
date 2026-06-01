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
}