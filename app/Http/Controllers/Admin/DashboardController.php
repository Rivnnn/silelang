<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Nasabah;
use App\Models\PengajuanLelang;
use App\Models\SuratKeluar;
use App\Models\Memo;
use App\Models\Nota;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_petugas' => User::where('role', 'petugas')->count(),
            'total_nasabah' => Nasabah::count(),
            'total_pengajuan' => PengajuanLelang::where('status', 'pending')->count(),
            'total_surat' => SuratKeluar::count() + Memo::count() + Nota::count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}