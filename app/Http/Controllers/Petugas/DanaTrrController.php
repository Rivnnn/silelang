<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\DanaTrr;
use App\Models\TrrLedger;
use App\Models\PengajuanLelang;
use App\Models\PengajuanTrr;
use App\Models\HasilLelang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DanaTrrController extends Controller
{
    // ============================================================
    // HELPER: cek apakah TRR ini milik petugas yang login
    // ============================================================
    private function isOwner(DanaTrr $trr): bool
    {
        $userId = auth()->id();

        if ($trr->pengajuanTrr) {
            return $trr->pengajuanTrr->user_id === $userId;
        }

        if ($trr->pengajuan_lelang_id) {
            $lelang = PengajuanLelang::find($trr->pengajuan_lelang_id);
            return $lelang && $lelang->user_id === $userId;
        }

        return false;
    }

    // ============================================================
    // HELPER: ambil TRR milik petugas yang login + eager load hasilLelang
    // ============================================================
    private function getTrrMilikPetugas($id): DanaTrr
    {
        $trr = DanaTrr::with([
            'pengajuanTrr.pengajuanLelang.nasabah',
            'pengajuanTrr.user',
            'pengajuanLelang.nasabah',
            'pengajuanLelang.user',
            'ledger',
            'hasilLelang',          // ← tambahan
        ])->findOrFail($id);

        if (!$this->isOwner($trr)) {
            abort(403, 'Anda tidak memiliki akses ke data ini');
        }

        return $trr;
    }

    // ============================================================
    // INDEX
    // ============================================================
    public function index()
    {
        $userId = auth()->id();

        $pengajuanTrr = PengajuanTrr::with([
                'pengajuanLelang.nasabah',
                'danaTrr.ledger',
            ])
            ->where('user_id', $userId)
            ->latest()
            ->get();

        $daftarTrr = DanaTrr::with([
                'pengajuanTrr.pengajuanLelang.nasabah',
                'pengajuanLelang.nasabah',
                'ledger',
            ])
            ->where(function ($query) use ($userId) {
                $query->whereHas('pengajuanTrr', fn($q) => $q->where('user_id', $userId))
                      ->orWhereHas('pengajuanLelang', fn($q) => $q->where('user_id', $userId));
            })
            ->latest()
            ->get()
            ->map(function ($trr) {
                $trr->saldo_terakhir = $trr->ledger->sortBy('id')->last()?->sisa_saldo
                                     ?? $trr->nominal_disetujui;
                return $trr;
            });

        $lelangDisetujui = PengajuanLelang::with('nasabah')
            ->where('user_id', $userId)
            ->where('status', 'disetujui')
            ->whereDoesntHave('pengajuanTrr', fn($q) =>
                $q->whereIn('status', ['pending', 'disetujui'])
            )
            ->get();

        return view('petugas.dana_trr.index', compact(
            'pengajuanTrr',
            'daftarTrr',
            'lelangDisetujui'
        ));
    }

    // ============================================================
    // KONFIRMASI penerimaan dana
    // ============================================================
    public function konfirmasi($id)
    {
        try {
            $trr = DanaTrr::with(['pengajuanTrr', 'pengajuanLelang'])->findOrFail($id);

            if (!$this->isOwner($trr)) {
                return back()->with('error', 'Anda tidak memiliki akses ke dana TRR ini');
            }

            if ($trr->status !== 'menunggu_konfirmasi') {
                return back()->with('error', 'Dana ini sudah pernah dikonfirmasi sebelumnya');
            }

            $trr->update([
                'status'       => 'aktif',
                'confirmed_at' => now(),
                'confirmed_by' => auth()->id(),
            ]);

            return back()->with('success',
                'Penerimaan dana ' . $trr->nomor_referensi . ' berhasil dikonfirmasi. Buku kas siap digunakan.'
            );

        } catch (\Exception $e) {
            Log::error('Error konfirmasi TRR: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // ============================================================
    // LEDGER — halaman buku kas
    // ============================================================
    public function ledger($id)
    {
        try {
            $trr = $this->getTrrMilikPetugas($id);

            $ringkasan = [
                'total_kredit'     => $trr->ledger->sum('kredit'),
                'total_debet'      => $trr->ledger->sum('debet'),
                'sisa_saldo_akhir' => $trr->nominal_disetujui - $trr->ledger->sum('debet'),
            ];

            return view('petugas.dana_trr.ledger', compact('trr', 'ringkasan'));

        } catch (\Symfony\Component\HttpKernel\Exception\HttpException $e) {
            return redirect()->route('petugas.dana-trr.index')
                ->with('error', 'Anda tidak memiliki akses ke buku kas ini');
        } catch (\Exception $e) {
            Log::error('Error ledger TRR: ' . $e->getMessage());
            return redirect()->route('petugas.dana-trr.index')
                ->with('error', 'Data tidak ditemukan');
        }
    }

    // ============================================================
    // STORE LEDGER — catat pengeluaran baru
    // ============================================================
    public function storeLedger(Request $request, $id)
    {
        $request->validate([
            'keterangan' => 'required|string|max:255',
            'debet'      => 'required|numeric|min:100',
        ], [
            'keterangan.required' => 'Keterangan pengeluaran wajib diisi',
            'debet.required'      => 'Nominal pengeluaran wajib diisi',
            'debet.min'           => 'Nominal minimal Rp 100',
        ]);

        try {
            DB::beginTransaction();

            $trr = $this->getTrrMilikPetugas($id);

            if ($trr->status !== 'aktif') {
                return back()->with('error', 'Buku kas hanya bisa diisi setelah dana dikonfirmasi');
            }

            $saldoSebelumnya = $trr->ledger()->orderBy('id', 'desc')->value('sisa_saldo')
                             ?? $trr->nominal_disetujui;

            if ($saldoSebelumnya <= 0) {
                return back()->with('error',
                    'Tidak dapat mencatat pengeluaran. Sisa saldo sudah 0.'
                );
            }
                
            $sisaSaldoBaru = $saldoSebelumnya - $request->debet;

            if ($sisaSaldoBaru < 0) {
                return back()->with('error',
                    'Pengeluaran melebihi sisa saldo. Saldo tersedia: Rp '
                    . number_format($saldoSebelumnya, 0, ',', '.')
                );
            }

            $trr->ledger()->create([
                'keterangan' => $request->keterangan,
                'kredit'     => 0,
                'debet'      => $request->debet,
                'sisa_saldo' => $sisaSaldoBaru,
            ]);

            DB::commit();

            return back()->with('success', 'Pengeluaran berhasil dicatat');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error store ledger: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menyimpan');
        }
    }

    // ============================================================
    // SELESAI — tutup buku kas + input hasil lelang (dari petugas)
    // ============================================================
    public function selesai(Request $request, $id)
    {
        $request->validate([
            'status_hasil'           => 'required|in:berhasil,gagal',
            'tanggal_lelang'         => 'required|date',
            'harga_terjual'          => 'nullable|numeric|min:0',
            'nama_pemenang'          => 'nullable|string|max:255',
            'sisa_dana_dikembalikan' => 'required|numeric|min:0',
            'catatan'                => 'nullable|string|max:1000',
        ], [
            'status_hasil.required'           => 'Status hasil lelang wajib dipilih',
            'tanggal_lelang.required'         => 'Tanggal lelang wajib diisi',
            'sisa_dana_dikembalikan.required' => 'Sisa dana yang dikembalikan wajib diisi',
        ]);

        try {
            DB::beginTransaction();

            $trr = $this->getTrrMilikPetugas($id);

            if ($trr->status !== 'aktif') {
                return back()->with('error', 'Hanya TRR berstatus aktif yang bisa diselesaikan');
            }

            if ($trr->hasilLelang) {
                return back()->with('error', 'Hasil lelang untuk TRR ini sudah pernah diinput');
            }

            $trr->update(['status' => 'selesai']);

            $totalBiaya = (float) $trr->ledger->sum('debet');

            HasilLelang::create([
                'dana_trr_id'            => $trr->id,
                'status_hasil'           => $request->status_hasil,
                'harga_terjual'          => $request->status_hasil === 'berhasil'
                                                ? $request->harga_terjual
                                                : null,
                'nama_pemenang'          => $request->status_hasil === 'berhasil'
                                                ? $request->nama_pemenang
                                                : null,
                'tanggal_lelang'         => $request->tanggal_lelang,
                'total_biaya_realisasi'  => $totalBiaya,
                'sisa_dana_dikembalikan' => $request->sisa_dana_dikembalikan,
                'catatan'                => $request->catatan,
                'diinput_oleh'           => auth()->id(),
            ]);

            DB::commit();

            return redirect()
                ->route('petugas.dana-trr.ledger', $trr->id)
                ->with('success',
                    'Buku kas ' . $trr->nomor_referensi . ' berhasil ditutup dan hasil lelang tersimpan. Silakan export PDF untuk LPJ.'
                );

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error selesai TRR: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // ============================================================
    // EXPORT PDF LPJ
    // ============================================================
    public function exportPdf($id)
    {
        try {
            $trr = $this->getTrrMilikPetugas($id);

            $ringkasan = [
                'total_kredit'     => $trr->ledger->sum('kredit'),
                'total_debet'      => $trr->ledger->sum('debet'),
                'sisa_saldo_akhir' => $trr->nominal_disetujui - $trr->ledger->sum('debet'),
            ];

            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView(
                'petugas.dana_trr.pdf_ledger',
                compact('trr', 'ringkasan')
            );

            return $pdf->download('LPJ-TRR-' . $trr->nomor_referensi . '.pdf');

        } catch (\Exception $e) {
            Log::error('Error export PDF TRR: ' . $e->getMessage());
            return redirect()->route('petugas.dana-trr.index')
                ->with('error', 'Gagal export PDF');
        }
    }
}