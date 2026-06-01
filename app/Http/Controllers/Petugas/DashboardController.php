<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Nasabah;
use App\Models\SuratKeluar;
use App\Models\Memo;
use App\Models\Nota;
use App\Models\Lpa;
use App\Models\PengajuanLelang;

class DashboardController extends Controller
{
    /**
     * Display dashboard petugas dengan statistik data
     */
    public function index()
    {
        $userId = auth()->id();

        // Hitung statistik untuk dashboard
        $stats = [
            'total_nasabah' => Nasabah::where('user_id', $userId)->count(),
            
            'total_surat' => SuratKeluar::where('user_id', $userId)->count() 
                          + Memo::where('user_id', $userId)->count() 
                          + Nota::where('user_id', $userId)->count(),
            
            'total_lpa' => Lpa::where('user_id', $userId)->count(),
            
            'total_pengajuan' => PengajuanLelang::where('user_id', $userId)->count(),
        ];

        return view('petugas.dashboard', compact('stats'));
    }
}