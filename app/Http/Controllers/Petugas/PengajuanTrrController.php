<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\PengajuanTrr;
use App\Models\PengajuanLelang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PengajuanTrrController extends Controller
{
    /**
     * Petugas ajukan Dana TRR untuk lelang yang sudah disetujui
     */
    public function store(Request $request)
    {
        $request->validate([
            'pengajuan_lelang_id' => 'required|exists:pengajuan_lelang,id',
            'nominal_diajukan'    => 'required|numeric|min:1000',
            'keterangan'          => 'required|string|max:500',
        ], [
            'pengajuan_lelang_id.required' => 'Pilih pengajuan lelang terlebih dahulu',
            'nominal_diajukan.required'    => 'Nominal yang diajukan wajib diisi',
            'nominal_diajukan.min'         => 'Nominal minimal Rp 1.000',
            'keterangan.required'          => 'Keterangan kebutuhan dana wajib diisi',
        ]);

        // Pastikan pengajuan lelang ini milik petugas yang login
        $pengajuanLelang = PengajuanLelang::where('id', $request->pengajuan_lelang_id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        // Hanya bisa ajukan TRR jika lelang sudah disetujui
        if ($pengajuanLelang->status !== 'disetujui') {
            return back()->with('error',
                'Dana TRR hanya bisa diajukan untuk pengajuan lelang yang sudah disetujui'
            );
        }

        // Cek apakah sudah ada pengajuan TRR pending untuk lelang ini
        if ($pengajuanLelang->hasPengajuanTrrPending()) {
            return back()->with('error',
                'Sudah ada pengajuan TRR yang sedang menunggu review admin untuk lelang ini'
            );
        }

        // Cek apakah sudah ada TRR aktif untuk lelang ini
        $trrAktif = $pengajuanLelang->pengajuanTrr()
            ->whereHas('danaTrr', fn($q) => $q->whereIn('status', ['aktif', 'menunggu_konfirmasi']))
            ->exists();

        if ($trrAktif) {
            return back()->with('error',
                'Lelang ini sudah memiliki Dana TRR yang aktif'
            );
        }

        PengajuanTrr::create([
            'pengajuan_lelang_id' => $request->pengajuan_lelang_id,
            'user_id'             => auth()->id(),
            'nominal_diajukan'    => $request->nominal_diajukan,
            'keterangan'          => $request->keterangan,
            'status'              => 'pending',
        ]);

        return back()->with('success',
            'Pengajuan Dana TRR berhasil dikirim. Menunggu persetujuan admin.'
        );
    }
}