<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PengajuanTrr;
use App\Models\DanaTrr;
use App\Models\TrrLedger;
use App\Models\HasilLelang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MonitoringTrrController extends Controller
{
    // ============================================================
    // INDEX — halaman utama monitoring TRR
    // ============================================================
    public function index()
    {
        $pengajuanPending = PengajuanTrr::with([
                'pengajuanLelang.nasabah',
                'pengajuanLelang.user',
                'user',
            ])
            ->where('status', 'pending')
            ->latest()
            ->get();

        $trrAktif = DanaTrr::with([
                'pengajuanTrr.pengajuanLelang.nasabah',
                'pengajuanTrr.user',
                'ledger',
            ])
            ->whereIn('status', ['menunggu_konfirmasi', 'aktif'])
            ->latest()
            ->get()
            ->map(function ($trr) {
                $trr->saldo_terakhir = $trr->ledger->sortBy('id')->last()?->sisa_saldo
                                    ?? $trr->nominal_disetujui;
                return $trr;
            });

        $daftarTrr = $trrAktif;

        // TRR selesai + hasil lelang
        $trrSelesai = DanaTrr::with([
                'pengajuanTrr.pengajuanLelang.nasabah',
                'pengajuanTrr.user',
                'ledger',
                'confirmedBy',
                'hasilLelang.diinputOleh',
            ])
            ->where('status', 'selesai')
            ->latest()
            ->get()
            ->map(function ($trr) {
                $trr->total_pengeluaran = $trr->ledger->sum('debet');
                $trr->sisa_akhir        = $trr->nominal_disetujui - $trr->total_pengeluaran;
                return $trr;
            });

        $rekonsiliasi = [
            'total_aktif'     => DanaTrr::where('status', 'aktif')->sum('nominal_disetujui'),
            'total_selesai'   => DanaTrr::where('status', 'selesai')->sum('nominal_disetujui'),
            'total_realisasi' => TrrLedger::sum('debet'),
            'total_pending'   => PengajuanTrr::where('status', 'pending')->count(),
        ];
        $rekonsiliasi['selisih'] = $rekonsiliasi['total_aktif'] - $rekonsiliasi['total_realisasi'];

        $statsHasil = [
            'total_berhasil'        => HasilLelang::where('status_hasil', 'berhasil')->count(),
            'total_gagal'           => HasilLelang::where('status_hasil', 'gagal')->count(),
            'total_terjual'         => HasilLelang::where('status_hasil', 'berhasil')->sum('harga_terjual'),
            'selesai_belum_diinput' => DanaTrr::where('status', 'selesai')
                                            ->whereDoesntHave('hasilLelang')
                                            ->count(),
        ];

        return view('admin.monitoring_trr', compact(
            'pengajuanPending',
            'trrAktif',
            'daftarTrr',
            'trrSelesai',
            'rekonsiliasi',
            'statsHasil'
        ));
    }

    // ============================================================
    // DETAIL pengajuan TRR sebelum di-ACC
    // ============================================================
    public function detail($id)
    {
        $pengajuan = PengajuanTrr::with([
                'pengajuanLelang.nasabah',
                'pengajuanLelang.user',
                'user',
            ])
            ->findOrFail($id);

        return view('admin.monitoring_trr_detail', compact('pengajuan'));
    }

    // ============================================================
    // APPROVE pengajuan TRR
    // ============================================================
    public function approve(Request $request, $id)
    {
        $request->validate([
            'nominal_disetujui' => 'required|numeric|min:1000',
            'catatan_admin'     => 'nullable|string|max:500',
        ], [
            'nominal_disetujui.required' => 'Nominal yang disetujui wajib diisi',
            'nominal_disetujui.min'      => 'Nominal minimal Rp 1.000',
        ]);

        try {
            DB::beginTransaction();

            $pengajuan = PengajuanTrr::with('pengajuanLelang')->findOrFail($id);

            if ($pengajuan->status !== 'pending') {
                return back()->with('error', 'Pengajuan ini sudah diproses sebelumnya');
            }

            $pengajuan->update([
                'status'            => 'disetujui',
                'nominal_disetujui' => $request->nominal_disetujui,
                'catatan_admin'     => $request->catatan_admin ?? 'Disetujui',
                'processed_by'      => auth()->id(),
                'processed_at'      => now(),
            ]);

            $danaTrr = DanaTrr::create([
                'pengajuan_trr_id'  => $pengajuan->id,
                'approved_by'       => auth()->id(),
                'nomor_referensi'   => DanaTrr::generateNomorReferensi(),
                'nominal_disetujui' => $request->nominal_disetujui,
                'tanggal_cair'      => now()->toDateString(),
                'status'            => 'menunggu_konfirmasi',
            ]);

            $danaTrr->ledger()->create([
                'keterangan' => 'Dana TRR Cair — ' . $danaTrr->nomor_referensi,
                'kredit'     => $request->nominal_disetujui,
                'debet'      => 0,
                'sisa_saldo' => $request->nominal_disetujui,
            ]);

            DB::commit();

            return redirect()->route('admin.monitoring.trr')
                ->with('success', 'Pengajuan TRR disetujui. Dana ' . $danaTrr->nomor_referensi . ' siap dicairkan.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error approve TRR: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // ============================================================
    // REJECT pengajuan TRR
    // ============================================================
    public function reject(Request $request, $id)
    {
        $request->validate([
            'catatan_admin' => 'required|string|min:10',
        ], [
            'catatan_admin.required' => 'Alasan penolakan wajib diisi',
            'catatan_admin.min'      => 'Alasan penolakan minimal 10 karakter',
        ]);

        try {
            $pengajuan = PengajuanTrr::findOrFail($id);

            if ($pengajuan->status !== 'pending') {
                return back()->with('error', 'Pengajuan ini sudah diproses sebelumnya');
            }

            $pengajuan->update([
                'status'        => 'ditolak',
                'catatan_admin' => $request->catatan_admin,
                'processed_by'  => auth()->id(),
                'processed_at'  => now(),
            ]);

            return redirect()->route('admin.monitoring.trr')
                ->with('success', 'Pengajuan TRR berhasil ditolak');

        } catch (\Exception $e) {
            Log::error('Error reject TRR: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan');
        }
    }

    // ============================================================
    // EXPORT HASIL LELANG — PDF dengan filter
    // ============================================================
    public function exportHasil(Request $request)
    {
        $query = HasilLelang::with([
            'danaTrr.pengajuanTrr.pengajuanLelang.nasabah',
            'danaTrr.pengajuanTrr.user',
            'danaTrr.ledger',
            'diinputOleh',
        ]);

        if ($request->filled('status')) {
            $query->where('status_hasil', $request->status);
        }

        if ($request->filled('tahun')) {
            $query->whereYear('tanggal_lelang', $request->tahun);
        }

        // Filter keyword: nama nasabah atau nomor referensi TRR
        if ($request->filled('keyword')) {
            $kw = $request->keyword;
            $query->where(function ($q) use ($kw) {
                $q->whereHas('danaTrr', fn($q2) =>
                    $q2->where('nomor_referensi', 'like', "%{$kw}%")
                )->orWhereHas(
                    'danaTrr.pengajuanTrr.pengajuanLelang.nasabah',
                    fn($q2) => $q2->where('nama_nasabah', 'like', "%{$kw}%")
                );
            });
        }

        $hasil    = $query->latest()->get();
        $filename = 'Rekap-Hasil-Lelang-' . now()->format('Ymd-His') . '.pdf';

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView(
            'admin.export_hasil_lelang_pdf',
            compact('hasil')
        )->setPaper('a4', 'landscape');

        return $pdf->download($filename);
    }
}