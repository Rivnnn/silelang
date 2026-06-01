<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Nasabah;
use App\Models\DokumenNasabah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class NasabahController extends Controller
{
    /**
     * Daftar dokumen wajib untuk lelang
     */
    private $dokumenWajib = [
        'Permohonan Lelang',
        'Pernyataan Lelang',
        'Surat Kuasa Lelang',
        'Keterangan Hutang',
        'Limit Lelang',
        'Penampungan Hasil Lelang',
        'SHM',
        'SHT',
        'APHT',
        'Akad',
        'Surat Peringatan 1',
        'Surat Peringatan 2',
        'Surat Peringatan 3',
        'Surat Pemberitahuan Lelang',
        'SKPT',
        'Pengumuman Pertama',
        'Pengumuman Kedua Koran',
        'Surat Penetapan',
        'BA Musyawarah'
    ];

    /**
     * Display listing of nasabah untuk petugas yang login
     */
    public function index(Request $request)
    {
        $keyword = $request->search;

        // Hanya tampilkan nasabah yang dibuat oleh petugas yang login
        $nasabah = Nasabah::where('user_id', auth()->id())
            ->when($keyword, function ($query) use ($keyword) {
                $query->where('nama_nasabah', 'like', "%$keyword%")
                      ->orWhere('nik', 'like', "%$keyword%")
                      ->orWhere('jenis_lelang', 'like', "%$keyword%");
            })
            ->latest()
            ->get();

        return view('petugas.nasabah.index', compact('nasabah'));
    }

    /**
     * Store nasabah baru
     */
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'nama_nasabah' => 'required|string|max:255',
            'nik' => 'required|string|size:16|unique:nasabah,nik',
            'alamat' => 'required|string|max:500',
            'no_hp' => 'required|string|max:20',
            'lokasi_lelang' => 'required|string|max:255',
            'jenis_lelang' => 'required|in:Tanah,Bangunan,Tanah Berikut Bangunan',
        ], [
            'nama_nasabah.required' => 'Nama nasabah wajib diisi',
            'nama_nasabah.max' => 'Nama nasabah maksimal 255 karakter',
            
            'nik.required' => 'NIK wajib diisi',
            'nik.size' => 'NIK harus 16 digit',
            'nik.unique' => 'NIK sudah terdaftar dalam sistem',
            
            'alamat.required' => 'Alamat wajib diisi',
            'alamat.max' => 'Alamat maksimal 500 karakter',
            
            'no_hp.required' => 'No HP wajib diisi',
            'no_hp.max' => 'No HP maksimal 20 karakter',
            
            'lokasi_lelang.required' => 'Lokasi lelang wajib diisi',
            'lokasi_lelang.max' => 'Lokasi lelang maksimal 255 karakter',
            
            'jenis_lelang.required' => 'Jenis lelang wajib dipilih',
            'jenis_lelang.in' => 'Jenis lelang tidak valid',
        ]);

        try {
            DB::beginTransaction();

            // Bersihkan dan format data
            $nasabah = Nasabah::create([
                'user_id' => auth()->id(),
                'nama_nasabah' => trim($request->nama_nasabah),
                'nik' => trim($request->nik),
                'alamat' => trim($request->alamat),
                'no_hp' => trim($request->no_hp),
                'lokasi_lelang' => trim($request->lokasi_lelang),
                'jenis_lelang' => $request->jenis_lelang,
            ]);

            DB::commit();

            Log::info('Nasabah baru berhasil ditambahkan', [
                'user_id' => auth()->id(),
                'nasabah_id' => $nasabah->id,
                'nama' => $nasabah->nama_nasabah
            ]);

            return redirect()->route('petugas.nasabah.index')
                ->with('success', "✓ Data nasabah '{$nasabah->nama_nasabah}' berhasil ditambahkan");

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Error saat menambahkan nasabah', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi.')
                ->withInput();
        }
    }

    /**
     * Show dokumen nasabah
     */
    public function showDokumen($id)
    {
        try {
            $nasabah = Nasabah::where('id', $id)
                ->where('user_id', auth()->id())
                ->firstOrFail();

            $dokumen = DokumenNasabah::where('nasabah_id', $id)
                ->get()
                ->keyBy('nama_dokumen');

            return view('petugas.nasabah.dokumen', [
                'nasabah' => $nasabah,
                'dokumenWajib' => $this->dokumenWajib,
                'dokumen' => $dokumen
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('petugas.nasabah.index')
                ->with('error', 'Nasabah tidak ditemukan atau Anda tidak memiliki akses');
        }
    }

    /**
     * Edit nasabah (opsional - untuk future enhancement)
     */
    public function edit($id)
    {
        $nasabah = Nasabah::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $allNasabah = Nasabah::where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('petugas.nasabah.index', [
            'nasabah' => $allNasabah,
            'editNasabah' => $nasabah
        ]);
    }

    /**
     * Update nasabah (opsional - untuk future enhancement)
     */
    public function update(Request $request, $id)
    {
        $nasabah = Nasabah::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $validated = $request->validate([
            'nama_nasabah' => 'required|string|max:255',
            'nik' => 'required|string|size:16|unique:nasabah,nik,' . $id,
            'alamat' => 'required|string|max:500',
            'no_hp' => 'required|string|max:20',
            'lokasi_lelang' => 'required|string|max:255',
            'jenis_lelang' => 'required|in:Tanah,Bangunan,Tanah Berikut Bangunan',
        ]);

        try {
            $nasabah->update([
                'nama_nasabah' => trim($request->nama_nasabah),
                'nik' => trim($request->nik),
                'alamat' => trim($request->alamat),
                'no_hp' => trim($request->no_hp),
                'lokasi_lelang' => trim($request->lokasi_lelang),
                'jenis_lelang' => $request->jenis_lelang,
            ]);

            return redirect()->route('petugas.nasabah.index')
                ->with('success', "✓ Data nasabah '{$nasabah->nama_nasabah}' berhasil diperbarui");

        } catch (\Exception $e) {
            Log::error('Error update nasabah: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal memperbarui data nasabah')
                ->withInput();
        }
    }

    /**
     * Delete nasabah (opsional - untuk future enhancement)
     */
    public function destroy($id)
    {
        try {
            $nasabah = Nasabah::where('id', $id)
                ->where('user_id', auth()->id())
                ->firstOrFail();

            $nama = $nasabah->nama_nasabah;
            
            // Hapus dokumen terkait dulu
            DokumenNasabah::where('nasabah_id', $id)->delete();
            
            // Hapus nasabah
            $nasabah->delete();

            return redirect()->route('petugas.nasabah.index')
                ->with('success', "✓ Data nasabah '{$nama}' berhasil dihapus");

        } catch (\Exception $e) {
            Log::error('Error delete nasabah: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal menghapus data nasabah');
        }
    }
}