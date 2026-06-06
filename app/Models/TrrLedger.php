<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrrLedger extends Model
{
    protected $table = 'trr_ledger';

    protected $fillable = [
        'dana_trr_id',
        'keterangan',
        'kredit',
        'debet',
        'sisa_saldo',
    ];

    protected $casts = [
        'kredit'      => 'decimal:2',
        'debet'       => 'decimal:2',
        'sisa_saldo'  => 'decimal:2',
    ];

    // Baris ledger ini milik dana TRR yang mana?
    public function danaTrr()
    {
        return $this->belongsTo(DanaTrr::class);
    }
}