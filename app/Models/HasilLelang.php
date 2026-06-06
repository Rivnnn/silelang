<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HasilLelang extends Model
{
    protected $table = 'hasil_lelang';

    protected $fillable = [
        'dana_trr_id',
        'harga_terjual',
        'nama_pemenang',
        'tanggal_lelang',
        'total_biaya_realisasi',
        'sisa_dana_dikembalikan',
        'status_hasil',
        'catatan',
        'diinput_oleh',
    ];

    protected $casts = [
        'harga_terjual'          => 'decimal:2',
        'total_biaya_realisasi'  => 'decimal:2',
        'sisa_dana_dikembalikan' => 'decimal:2',
        'tanggal_lelang'         => 'date',
    ];

    // ============================================================
    // RELASI
    // ============================================================

    public function danaTrr()
    {
        return $this->belongsTo(DanaTrr::class, 'dana_trr_id');
    }

    public function diinputOleh()
    {
        return $this->belongsTo(User::class, 'diinput_oleh');
    }

    // ============================================================
    // ACCESSOR SHORTCUT
    // ============================================================

    /**
     * Nama nasabah melalui rantai relasi hasil → dana_trr → nasabah
     */
    public function getNasabahNamaAttribute(): string
    {
        return $this->danaTrr?->nasabah?->nama_nasabah ?? '-';
    }

    /**
     * Nama petugas pengaju
     */
    public function getPetugasNamaAttribute(): string
    {
        return $this->danaTrr?->petugas?->name ?? '-';
    }
}