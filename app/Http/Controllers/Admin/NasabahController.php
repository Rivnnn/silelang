<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Nasabah;
use App\Models\DokumenNasabah;
use App\Models\Lpa;

class NasabahController extends Controller
{
    /**
     * Tampilkan dokumen nasabah
     */
    public function showDokumen($id)
    {
        $nasabah = Nasabah::with(['dokumen', 'user'])->findOrFail($id);
        $lpa = Lpa::where('nasabah_id', $id)->with('user')->latest()->first();
        
        $showBackButton = true;
        $backUrl = route('admin.monitoring.nasabah');
        
        return view('admin.nasabah_dokumen', compact('nasabah', 'lpa', 'showBackButton', 'backUrl'));
    }
}