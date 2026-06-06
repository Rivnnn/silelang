<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Nasabah;
use App\Models\PengajuanLelang;
use App\Models\SuratKeluar;
use App\Models\Memo;
use App\Models\Nota;
use App\Models\DanaTrr;
use App\Models\TrrLedger;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // ── STATS CARDS ──────────────────────────────────────────────────────
        $stats = [
            'total_petugas'   => User::where('role', 'petugas')->count(),
            'total_nasabah'   => Nasabah::count(),
            'total_pengajuan' => PengajuanLelang::where('status', 'pending')->count(),
            'total_surat'     => SuratKeluar::count() + Memo::count() + Nota::count(),
        ];

        // ── REKONSILIASI TRR ─────────────────────────────────────────────────
        $trr = [
            'total_aktif'     => DanaTrr::where('status', 'aktif')->sum('nominal_disetujui'),
            'total_selesai'   => DanaTrr::where('status', 'selesai')->sum('nominal_disetujui'),
            'total_realisasi' => TrrLedger::sum('debet'),
        ];
        $trr['selisih'] = $trr['total_aktif'] - $trr['total_realisasi'];

        // ── CHART: Pengajuan Lelang 6 Bulan Terakhir ─────────────────────────
        // Mengambil jumlah pengajuan per bulan, dikelompokkan berdasarkan status
        $chartPengajuan = PengajuanLelang::select(
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as bulan"),
                DB::raw("DATE_FORMAT(created_at, '%b %Y') as label"),
                DB::raw("COUNT(*) as total"),
                DB::raw("SUM(CASE WHEN status = 'disetujui' THEN 1 ELSE 0 END) as disetujui"),
                DB::raw("SUM(CASE WHEN status = 'ditolak'   THEN 1 ELSE 0 END) as ditolak"),
                DB::raw("SUM(CASE WHEN status = 'pending'   THEN 1 ELSE 0 END) as pending")
            )
            ->where('created_at', '>=', now()->subMonths(6)->startOfMonth())
            ->groupBy('bulan', 'label')
            ->orderBy('bulan')
            ->get();

        // ── CHART: Realisasi Dana TRR 6 Bulan Terakhir ───────────────────────
        // Debet = pengeluaran / realisasi, Kredit = dana masuk
        $chartTrr = TrrLedger::select(
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as bulan"),
                DB::raw("DATE_FORMAT(created_at, '%b %Y') as label"),
                DB::raw("SUM(debet)  as total_debet"),
                DB::raw("SUM(kredit) as total_kredit")
            )
            ->where('created_at', '>=', now()->subMonths(6)->startOfMonth())
            ->groupBy('bulan', 'label')
            ->orderBy('bulan')
            ->get();

        // ── ACTIVITY FEED: Aktivitas Terkini (gabungan semua entitas) ─────────
        $activities = collect();

        // Pengajuan Lelang terbaru
        PengajuanLelang::with('nasabah', 'user')
            ->latest()->limit(5)->get()
            ->each(function ($p) use (&$activities) {
                $statusIcon = match($p->status) {
                    'disetujui' => ['icon' => '✅', 'color' => 'green',  'label' => 'Disetujui'],
                    'ditolak'   => ['icon' => '❌', 'color' => 'red',    'label' => 'Ditolak'],
                    default     => ['icon' => '⏳', 'color' => 'orange', 'label' => 'Pending'],
                };
                $activities->push([
                    'time'    => $p->created_at,
                    'icon'    => $statusIcon['icon'],
                    'color'   => $statusIcon['color'],
                    'title'   => 'Pengajuan Lelang ' . $statusIcon['label'],
                    'desc'    => 'Nasabah: ' . ($p->nasabah->nama_nasabah ?? '-')
                               . ' — oleh ' . ($p->user->name ?? '-'),
                    'type'    => 'pengajuan',
                ]);
            });

        // Surat Keluar terbaru
        SuratKeluar::with('user')
            ->latest()->limit(4)->get()
            ->each(function ($s) use (&$activities) {
                $activities->push([
                    'time'  => $s->created_at,
                    'icon'  => '📤',
                    'color' => 'blue',
                    'title' => 'Surat Keluar Dibuat',
                    'desc'  => 'No. ' . ($s->nomor_surat ?? '-')
                             . ' — oleh ' . ($s->user->name ?? '-'),
                    'type'  => 'surat',
                ]);
            });

        // Dana TRR terbaru
        DanaTrr::with('pengajuanLelang.nasabah')
            ->latest()->limit(4)->get()
            ->each(function ($d) use (&$activities) {
                $activities->push([
                    'time'  => $d->created_at,
                    'icon'  => '💰',
                    'color' => 'purple',
                    'title' => 'Dana TRR ' . ($d->nomor_referensi ?? '-'),
                    'desc'  => 'Nominal: Rp ' . number_format($d->nominal_disetujui, 0, ',', '.')
                             . ' — Status: ' . ucfirst(str_replace('_', ' ', $d->status)),
                    'type'  => 'trr',
                ]);
            });

        // Nasabah baru
        Nasabah::with('user')
            ->latest()->limit(3)->get()
            ->each(function ($n) use (&$activities) {
                $activities->push([
                    'time'  => $n->created_at,
                    'icon'  => '👤',
                    'color' => 'teal',
                    'title' => 'Nasabah Baru Terdaftar',
                    'desc'  => $n->nama_nasabah . ' — input oleh ' . ($n->user->name ?? '-'),
                    'type'  => 'nasabah',
                ]);
            });

        // Urutkan semua aktivitas dari yang paling baru, ambil 10 teratas
        $activities = $activities->sortByDesc('time')->take(10)->values();

        return view('admin.dashboard', compact('stats', 'trr', 'chartPengajuan', 'chartTrr', 'activities'));
    }
}