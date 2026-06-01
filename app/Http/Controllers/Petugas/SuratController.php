<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\SuratKeluar;
use App\Models\Memo;
use App\Models\Nota;
use Illuminate\Http\Request;

class SuratController extends Controller
{
    /**
     * Halaman utama Nomor Surat (pilih jenis surat)
     */
    public function index()
    {
        return view('petugas.nomor_surat.index');
    }

    // ========================================
    // SURAT KELUAR
    // ========================================
    
    /**
     * Halaman Surat Keluar
     */
    public function suratKeluar()
    {
        $data = SuratKeluar::where('user_id', auth()->id())
            ->orderBy('id', 'asc')
            ->get();

        return view('petugas.nomor_surat.surat_keluar', compact('data'));
    }

    /**
     * Store Surat Keluar
     */
    public function storeSuratKeluar(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'perihal' => 'required|string|max:255',
            'tujuan' => 'required|string|max:255',
            'pic' => 'required|string|max:255'
        ], [
            'tanggal.required' => 'Tanggal wajib diisi',
            'perihal.required' => 'Perihal wajib diisi',
            'tujuan.required' => 'Tujuan wajib diisi',
            'pic.required' => 'PIC wajib diisi',
        ]);

        // Generate nomor surat dengan format: ID_PETUGAS/URUTAN_GLOBAL-1/BSI-BDG KOTA
        $userId = auth()->id();
        $idPetugas = str_pad($userId, 2, '0', STR_PAD_LEFT);
        
        // Hitung total SEMUA surat keluar dari SEMUA petugas (urutan global)
        $totalGlobal = SuratKeluar::count();
        $urutanGlobal = $totalGlobal + 1;
        
        $urutanFormat = str_pad($urutanGlobal, 3, '0', STR_PAD_LEFT);
        $nomorSurat = "{$idPetugas}/{$urutanFormat}-1/BSI-BDG KOTA";

        SuratKeluar::create([
            'user_id' => $userId,
            'tanggal' => $request->tanggal,
            'perihal' => $request->perihal,
            'tujuan' => $request->tujuan,
            'pic' => $request->pic,
            'nomor_surat' => $nomorSurat
        ]);

        return redirect()->back()->with('success', 'Surat keluar berhasil dibuat dengan nomor: ' . $nomorSurat);
    }

    // ========================================
    // MEMO
    // ========================================
    
    /**
     * Halaman Memo
     */
    public function memo()
    {
        $data = Memo::where('user_id', auth()->id())
            ->orderBy('id', 'asc')
            ->get();

        return view('petugas.nomor_surat.memo', compact('data'));
    }

    /**
     * Store Memo
     */
    public function storeMemo(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'perihal' => 'required|string|max:255',
            'tujuan' => 'required|string|max:255',
            'pic' => 'required|string|max:255'
        ], [
            'tanggal.required' => 'Tanggal wajib diisi',
            'perihal.required' => 'Perihal wajib diisi',
            'tujuan.required' => 'Tujuan wajib diisi',
            'pic.required' => 'PIC wajib diisi',
        ]);

        // Generate nomor memo dengan format: ID_PETUGAS/URUTAN_GLOBAL-2/BSI-BDG KOTA
        $userId = auth()->id();
        $idPetugas = str_pad($userId, 2, '0', STR_PAD_LEFT);
        
        // Hitung total SEMUA memo dari SEMUA petugas (urutan global)
        $totalGlobal = Memo::count();
        $urutanGlobal = $totalGlobal + 1;
        
        $urutanFormat = str_pad($urutanGlobal, 3, '0', STR_PAD_LEFT);
        $nomorMemo = "{$idPetugas}/{$urutanFormat}-2/BSI-BDG KOTA";

        Memo::create([
            'user_id' => $userId,
            'tanggal' => $request->tanggal,
            'perihal' => $request->perihal,
            'tujuan' => $request->tujuan,
            'pic' => $request->pic,
            'nomor_memo' => $nomorMemo
        ]);

        return redirect()->back()->with('success', 'Memo berhasil dibuat dengan nomor: ' . $nomorMemo);
    }

    // ========================================
    // NOTA
    // ========================================
    
    /**
     * Halaman Nota
     */
    public function nota()
    {
        $data = Nota::where('user_id', auth()->id())
            ->orderBy('id', 'asc')
            ->get();

        return view('petugas.nomor_surat.nota', compact('data'));
    }

    /**
     * Store Nota
     */
    public function storeNota(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'perihal' => 'required|string|max:255',
            'tujuan' => 'required|string|max:255',
            'pic' => 'required|string|max:255'
        ], [
            'tanggal.required' => 'Tanggal wajib diisi',
            'perihal.required' => 'Perihal wajib diisi',
            'tujuan.required' => 'Tujuan wajib diisi',
            'pic.required' => 'PIC wajib diisi',
        ]);

        // Generate nomor nota dengan format: ID_PETUGAS/URUTAN_GLOBAL-3/BSI-BDG KOTA
        $userId = auth()->id();
        $idPetugas = str_pad($userId, 2, '0', STR_PAD_LEFT);
        
        // Hitung total SEMUA nota dari SEMUA petugas (urutan global)
        $totalGlobal = Nota::count();
        $urutanGlobal = $totalGlobal + 1;
        
        $urutanFormat = str_pad($urutanGlobal, 3, '0', STR_PAD_LEFT);
        $nomorNota = "{$idPetugas}/{$urutanFormat}-3/BSI-BDG KOTA";

        Nota::create([
            'user_id' => $userId,
            'tanggal' => $request->tanggal,
            'perihal' => $request->perihal,
            'tujuan' => $request->tujuan,
            'pic' => $request->pic,
            'nomor_nota' => $nomorNota
        ]);

        return redirect()->back()->with('success', 'Nota dinas berhasil dibuat dengan nomor: ' . $nomorNota);
    }
}