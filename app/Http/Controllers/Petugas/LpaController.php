<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Lpa;
use App\Models\Nasabah;
use Illuminate\Http\Request;

class LpaController extends Controller
{
    /**
     * Display LPA form dan list data LPA
     */
    public function index()
    {
        // Hanya tampilkan nasabah milik petugas yang login
        $nasabah = Nasabah::where('user_id', auth()->id())->get();
        
        // Hanya tampilkan LPA yang dibuat oleh petugas yang login
        $lpa = Lpa::with('nasabah')
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('petugas.lpa.index', compact('nasabah', 'lpa'));
    }

    /**
     * Store LPA baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'nasabah_id' => 'required|exists:nasabah,id',
            'jenis_legalitas' => 'required|string|in:SHM,SHGB',
            'luas_tanah' => 'required|numeric|min:0',
            'luas_bangunan' => 'required|numeric|min:0',
            'spek_bangunan' => 'required|string',
            'nilai_pasar' => 'required|numeric|min:0',
            'nilai_likuidasi' => 'required|numeric|min:0',
            'lelang_ke' => 'required|integer|in:1,2,3',
        ], [
            'nasabah_id.required' => 'Nasabah wajib dipilih',
            'nasabah_id.exists' => 'Nasabah tidak ditemukan',
            'jenis_legalitas.required' => 'Jenis legalitas wajib dipilih',
            'jenis_legalitas.in' => 'Jenis legalitas harus SHM atau SHGB',
            'luas_tanah.required' => 'Luas tanah wajib diisi',
            'luas_tanah.numeric' => 'Luas tanah harus berupa angka',
            'luas_bangunan.required' => 'Luas bangunan wajib diisi',
            'luas_bangunan.numeric' => 'Luas bangunan harus berupa angka',
            'spek_bangunan.required' => 'Spesifikasi bangunan wajib diisi',
            'nilai_pasar.required' => 'Nilai pasar wajib diisi',
            'nilai_likuidasi.required' => 'Nilai likuidasi wajib diisi',
            'lelang_ke.required' => 'Lelang ke berapa wajib dipilih',
            'lelang_ke.in' => 'Lelang ke harus antara 1, 2, atau 3',
        ]);

        // Validasi nasabah milik petugas yang login
        $nasabah = Nasabah::where('id', $request->nasabah_id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        // Hitung nilai limit berdasarkan lelang ke berapa
        if ($request->lelang_ke == 1) {
            // Lelang pertama: menggunakan nilai pasar
            $nilai_limit = $request->nilai_pasar;
        } elseif ($request->lelang_ke == 2) {
            // Lelang kedua: rata-rata nilai pasar dan likuidasi
            $nilai_limit = ($request->nilai_pasar + $request->nilai_likuidasi) / 2;
        } else {
            // Lelang ketiga: menggunakan nilai likuidasi
            $nilai_limit = $request->nilai_likuidasi;
        }

        // Hitung uang jaminan (20% dari nilai limit)
        $uang_jaminan = 0.2 * $nilai_limit;

        // Simpan LPA
        Lpa::create([
            'user_id' => auth()->id(),
            'nasabah_id' => $request->nasabah_id,
            'jenis_legalitas' => $request->jenis_legalitas,
            'luas_tanah' => $request->luas_tanah,
            'luas_bangunan' => $request->luas_bangunan,
            'spek_bangunan' => $request->spek_bangunan,
            'nilai_pasar' => $request->nilai_pasar,
            'nilai_likuidasi' => $request->nilai_likuidasi,
            'lelang_ke' => $request->lelang_ke,
            'nilai_limit' => $nilai_limit,
            'uang_jaminan' => $uang_jaminan,
        ]);

        return redirect()->back()->with('success', 'Data LPA berhasil disimpan');
    }
}