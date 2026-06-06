<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DanaTrr extends Model
{
    protected $table = 'dana_trr';

    protected $fillable = [
        'pengajuan_trr_id',
        'pengajuan_lelang_id',
        'approved_by',
        'nomor_referensi',
        'nominal_disetujui',
        'tanggal_cair',
        'status',
        'confirmed_at',
        'confirmed_by',
    ];

    protected $casts = [
        'nominal_disetujui' => 'decimal:2',
        'tanggal_cair'      => 'date',
        'confirmed_at'      => 'datetime',
    ];

    // ============================================================
    // RELASI
    // ============================================================

    // Relasi BARU — via pengajuan_trr
    public function pengajuanTrr()
    {
        return $this->belongsTo(PengajuanTrr::class, 'pengajuan_trr_id');
    }

    // Relasi LAMA — fallback via pengajuan_lelang_id
    public function pengajuanLelang()
    {
        return $this->belongsTo(PengajuanLelang::class, 'pengajuan_lelang_id');
    }

    // Admin yang menyetujui
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Petugas yang konfirmasi penerimaan
    public function confirmedBy()
    {
        return $this->belongsTo(User::class, 'confirmed_by');
    }

    // Baris-baris catatan pengeluaran
    public function ledger()
    {
        return $this->hasMany(TrrLedger::class);
    }

    // ============================================================
    // ACCESSOR — shortcut ke nasabah dan petugas
    // Cek relasi baru dulu, fallback ke relasi lama
    // ============================================================

    public function getNasabahAttribute()
    {
        if ($this->pengajuanTrr) {
            return $this->pengajuanTrr->pengajuanLelang?->nasabah;
        }
        return $this->pengajuanLelang?->nasabah;
    }

    public function getPetugasAttribute()
    {
        if ($this->pengajuanTrr) {
            return $this->pengajuanTrr->user;
        }
        return $this->pengajuanLelang?->user;
    }

    // ============================================================
    // HELPER
    // ============================================================

    public function getTotalPengeluaranAttribute(): float
    {
        return (float) $this->ledger()->sum('debet');
    }

    public function getSisaSaldoAkhirAttribute(): float
    {
        return (float) $this->nominal_disetujui - $this->total_pengeluaran;
    }

    public function sudahDikonfirmasi(): bool
    {
        return !is_null($this->confirmed_at);
    }

    public static function generateNomorReferensi(): string
    {
        $tahun  = now()->year;
        $urutan = static::whereYear('created_at', $tahun)->count() + 1;

        return 'TRR-' . $tahun . '-' . str_pad($urutan, 5, '0', STR_PAD_LEFT);
    }

        public function hasilLelang()
    {
        return $this->hasOne(\App\Models\HasilLelang::class, 'dana_trr_id');
    }
 
}