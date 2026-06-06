<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Nasabah;
use App\Models\SuratKeluar;
use App\Models\Memo;
use App\Models\Nota;
use App\Models\Lpa;
use App\Models\PengajuanLelang;
use App\Models\DanaTrr;
use App\Models\TrrLedger;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        // Stats lama — TIDAK DIUBAH
        $stats = [
            'total_nasabah'   => Nasabah::where('user_id', $userId)->count(),
            'total_surat'     => SuratKeluar::where('user_id', $userId)->count()
                               + Memo::where('user_id', $userId)->count()
                               + Nota::where('user_id', $userId)->count(),
            'total_lpa'       => Lpa::where('user_id', $userId)->count(),
            'total_pengajuan' => PengajuanLelang::where('user_id', $userId)->count(),
        ];

        // FIX: gunakan pengajuanTrr (relasi baru) bukan pengajuanLelang
        $trrIds = DanaTrr::whereHas(
            'pengajuanTrr',                          // ← diganti dari pengajuanLelang
            fn($q) => $q->where('user_id', $userId)  // user_id ada di tabel pengajuan_trr
        )->pluck('id');

        $trr = [
            'total_aktif' => DanaTrr::whereIn('id', $trrIds)
                ->where('status', 'aktif')
                ->sum('nominal_disetujui'),

            'total_selesai' => DanaTrr::whereIn('id', $trrIds)
                ->where('status', 'selesai')
                ->sum('nominal_disetujui'),

            'total_realisasi' => TrrLedger::whereIn('dana_trr_id', $trrIds)
                ->sum('debet'),

            'menunggu_konfirmasi' => DanaTrr::whereIn('id', $trrIds)
                ->where('status', 'menunggu_konfirmasi')
                ->count(),
        ];

        $trr['selisih'] = $trr['total_aktif'] - $trr['total_realisasi'];

        return view('petugas.dashboard', compact('stats', 'trr'));
    }
}