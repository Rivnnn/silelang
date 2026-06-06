<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Nasabah;
use App\Models\SuratKeluar;
use App\Models\Memo;
use App\Models\Nota;
use App\Models\PengajuanLelang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
class MonitoringController extends Controller
{
    /**
     * Monitoring Data Nasabah
     */
    public function nasabah()
    {
        $nasabah = Nasabah::with('user')
            ->orderBy('id', 'asc')
            ->get();
        
        return view('admin.monitoring_nasabah', compact('nasabah'));
    }

    /**
     * Monitoring Arsip Surat
     */
    public function surat()
    {
        $suratKeluar = SuratKeluar::with('user')
            ->orderBy('id', 'asc')
            ->get();
            
        $memo = Memo::with('user')
            ->orderBy('id', 'asc')
            ->get();
            
        $nota = Nota::with('user')
            ->orderBy('id', 'asc')
            ->get();
        
        return view('admin.monitoring_surat', compact('suratKeluar', 'memo', 'nota'));
    }

    /**
     * Monitoring Pengajuan Lelang
     */
    public function lelang()
    {
        $pengajuan = PengajuanLelang::with(['nasabah.user', 'user'])
            ->orderBy('id', 'asc')
            ->get();
        
        return view('admin.monitoring_lelang', compact('pengajuan'));
    }

    /**
     * Approve Pengajuan Lelang
     */
   public function approveLelang(Request $request, $id)
    {
        try {
            $pengajuan = PengajuanLelang::findOrFail($id);

            if ($pengajuan->status !== 'pending') {
                return back()->with('error', 'Pengajuan sudah diproses sebelumnya');
            }

            $pengajuan->status        = 'disetujui';
            $pengajuan->catatan_admin = 'Disetujui oleh ' . auth()->user()->name
                                    . ' pada ' . now()->format('d M Y H:i');
            $pengajuan->save();

            return back()->with('success', 'Pengajuan lelang berhasil disetujui. Petugas dapat mengajukan Dana TRR.');

        } catch (\Exception $e) {
            Log::error('Error approve lelang: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * REJECT — tidak banyak berubah, hanya pastikan validasi tetap ada
     */
    public function rejectLelang(Request $request, $id)
    {
        $request->validate([
            'catatan_admin' => 'required|string|min:10',
        ], [
            'catatan_admin.required' => 'Alasan penolakan wajib diisi',
            'catatan_admin.min'      => 'Alasan penolakan minimal 10 karakter',
        ]);

        try {
            $pengajuan = PengajuanLelang::findOrFail($id);

            if ($pengajuan->status !== 'pending') {
                return back()->with('error', 'Pengajuan sudah diproses sebelumnya');
            }

            $pengajuan->status        = 'ditolak';
            $pengajuan->catatan_admin = $request->catatan_admin
                                      . ' — (Ditolak oleh: ' . auth()->user()->name
                                      . ', ' . now()->format('d M Y H:i') . ')';
            $pengajuan->save();

            return back()->with('success', 'Pengajuan lelang berhasil ditolak');

        } catch (\Exception $e) {
            Log::error('Error reject lelang: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function monitoringTrr()
    {
        // Ambil semua TRR yang aktif atau menunggu konfirmasi,
        // beserta nama petugas dan sisa saldo terakhir di ledger-nya
        $daftarTrr = DanaTrr::with([
                'pengajuanLelang.nasabah',
                'pengajuanLelang.user',  // petugas pengaju
                'ledger',
            ])
            ->whereIn('status', ['menunggu_konfirmasi', 'aktif'])
            ->latest()
            ->get()
            ->map(function ($trr) {
                // Ambil sisa saldo dari baris terakhir ledger
                $trr->saldo_terakhir = $trr->ledger->sortBy('id')->last()?->sisa_saldo ?? $trr->nominal_disetujui;
                return $trr;
            });

        // Data untuk widget dashboard rekonsiliasi
        $rekonsiliasi = [
            'total_aktif'    => DanaTrr::where('status', 'aktif')->sum('nominal_disetujui'),
            'total_selesai'  => DanaTrr::where('status', 'selesai')->sum('nominal_disetujui'),
            'total_realisasi'=> \App\Models\TrrLedger::sum('debet'),
        ];
        $rekonsiliasi['selisih'] = $rekonsiliasi['total_aktif'] - $rekonsiliasi['total_realisasi'];

        return view('admin.monitoring_trr', compact('daftarTrr', 'rekonsiliasi'));
    }
}