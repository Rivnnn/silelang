<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Nasabah;
use App\Models\SuratKeluar;
use App\Models\Memo;
use App\Models\Nota;
use App\Models\PengajuanLelang;
use Illuminate\Http\Request;

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
    public function approveLelang($id)
    {
        try {
            $pengajuan = PengajuanLelang::findOrFail($id);
            
            if ($pengajuan->status !== 'pending') {
                return back()->with('error', 'Pengajuan sudah diproses sebelumnya');
            }
            
            $pengajuan->status = 'disetujui';
            $pengajuan->catatan_admin = 'Disetujui oleh ' . session('name') . ' pada ' . now()->format('d M Y H:i');
            $pengajuan->save();

            return back()->with('success', 'Pengajuan lelang berhasil disetujui');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Reject Pengajuan Lelang
     */
    public function rejectLelang(Request $request, $id)
    {
        try {
            $request->validate([
                'catatan_admin' => 'required|string|min:10'
            ], [
                'catatan_admin.required' => 'Alasan penolakan wajib diisi',
                'catatan_admin.min' => 'Alasan penolakan minimal 10 karakter'
            ]);

            $pengajuan = PengajuanLelang::findOrFail($id);
            
            if ($pengajuan->status !== 'pending') {
                return back()->with('error', 'Pengajuan sudah diproses sebelumnya');
            }
            
            $pengajuan->status = 'ditolak';
            $pengajuan->catatan_admin = $request->catatan_admin . ' (Ditolak oleh: ' . session('name') . ' pada ' . now()->format('d M Y H:i') . ')';
            $pengajuan->save();

            return back()->with('success', 'Pengajuan lelang berhasil ditolak');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}