<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DokumenNasabah extends Model
{
    protected $table = 'dokumen_nasabah';

    protected $fillable = [
        'nasabah_id',
        'nama_dokumen',
        'link_dokumen'
    ];
}
