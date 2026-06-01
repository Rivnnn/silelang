<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Nasabah;
use App\Models\PengajuanLelang;
use Illuminate\Http\Request;

class PengajuanLelangController extends Controller
{
    /**
     * Display pengajuan lelang form dan list status
     */
    public function index()
    {
        // Ambil nasabah yang dibuat oleh petugas yang sedang login
        $nasabah = Nasabah::where('user_id', auth()->id())->get();
        
        // Ambil pengajuan lelang yang dibuat oleh petugas yang sedang login
        $pengajuan = PengajuanLelang::with(['nasabah'])
            ->where('user_id', auth()->id())
            ->latest()
            ->get();
        
        return view('petugas.pengajuan_lelang.index', compact('nasabah', 'pengajuan'));
    }

    /**
     * Store pengajuan lelang baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'nasabah_id' => 'required|exists:nasabah,id',
            'tanggal_pengajuan' => 'required|date'
        ], [
            'nasabah_id.required' => 'Nasabah wajib dipilih',
            'nasabah_id.exists' => 'Nasabah tidak ditemukan',
            'tanggal_pengajuan.required' => 'Tanggal pengajuan wajib diisi',
            'tanggal_pengajuan.date' => 'Format tanggal tidak valid',
        ]);

        // Validasi nasabah milik petugas yang login
        $nasabah = Nasabah::where('id', $request->nasabah_id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        // Cek apakah nasabah sudah pernah diajukan dan masih pending
        $existingPending = PengajuanLelang::where('nasabah_id', $request->nasabah_id)
            ->where('status', 'pending')
            ->first();

        if ($existingPending) {
            return back()->with('error', 'Nasabah ini sudah diajukan dan masih menunggu review admin');
        }

        // Cek apakah nasabah sudah pernah disetujui
        $existingApproved = PengajuanLelang::where('nasabah_id', $request->nasabah_id)
            ->where('status', 'disetujui')
            ->first();

        if ($existingApproved) {
            return back()->with('error', 'Nasabah ini sudah pernah disetujui untuk lelang');
        }

        // Simpan pengajuan lelang
        PengajuanLelang::create([
            'nasabah_id' => $request->nasabah_id,
            'user_id' => auth()->id(),
            'tanggal_pengajuan' => $request->tanggal_pengajuan,
            'status' => 'pending',
            'catatan_admin' => null
        ]);

        return back()->with('success', 'Pengajuan lelang berhasil dikirim. Menunggu review dari admin.');
    }
}