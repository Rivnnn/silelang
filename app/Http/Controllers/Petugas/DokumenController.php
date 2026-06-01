<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Nasabah;
use App\Models\DokumenNasabah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DokumenController extends Controller
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
     * List nasabah untuk dipilih upload dokumen
     */
    public function listNasabah()
    {
        // Ambil nasabah milik petugas yang login, urutkan berdasarkan nama
        $nasabah = Nasabah::where('user_id', auth()->id())
            ->orderBy('nama_nasabah', 'asc')
            ->get();

        return view('petugas.dokumen.list', compact('nasabah'));
    }

    /**
     * Form upload dokumen untuk nasabah tertentu
     */
    public function form($id)
    {
        // Validasi nasabah milik petugas yang login
        $nasabah = Nasabah::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        // Ambil semua dokumen yang sudah ada untuk nasabah ini
        $dokumen = DokumenNasabah::where('nasabah_id', $id)
            ->get()
            ->keyBy('nama_dokumen');

        return view('petugas.dokumen.form', [
            'nasabah' => $nasabah,
            'dokumenWajib' => $this->dokumenWajib,
            'dokumen' => $dokumen
        ]);
    }

    /**
     * Store/update multiple dokumen nasabah sekaligus
     */
    public function store(Request $request, $id)
    {
        // Validasi nasabah milik petugas yang login
        $nasabah = Nasabah::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        // Validasi input array dokumen
        $request->validate([
            'dokumen' => 'required|array|min:1',
            'dokumen.*.nama' => 'required|string',
            'dokumen.*.link' => 'nullable|url'
        ], [
            'dokumen.required' => 'Data dokumen wajib diisi',
            'dokumen.array' => 'Format data dokumen tidak valid',
            'dokumen.min' => 'Minimal harus ada 1 dokumen',
            'dokumen.*.nama.required' => 'Nama dokumen wajib diisi',
            'dokumen.*.link.url' => 'Link dokumen harus berupa URL yang valid (contoh: https://drive.google.com/...)',
        ]);

        try {
            // Mulai database transaction untuk memastikan data konsisten
            DB::beginTransaction();

            $savedCount = 0;      // Counter dokumen baru yang disimpan
            $updatedCount = 0;    // Counter dokumen yang diupdate
            $skippedCount = 0;    // Counter dokumen yang di-skip (link kosong)
            $errorCount = 0;      // Counter dokumen yang error

            // Loop setiap dokumen yang dikirim
            foreach ($request->dokumen as $doc) {
                // Validasi nama dokumen ada dalam daftar dokumen wajib
                if (!in_array($doc['nama'], $this->dokumenWajib)) {
                    $errorCount++;
                    Log::warning("Dokumen tidak valid: " . $doc['nama']);
                    continue;
                }

                // Skip jika link kosong atau hanya whitespace
                if (empty($doc['link']) || trim($doc['link']) === '') {
                    $skippedCount++;
                    continue;
                }

                // Validasi format URL lebih detail
                if (!filter_var($doc['link'], FILTER_VALIDATE_URL)) {
                    $errorCount++;
                    Log::warning("URL tidak valid untuk dokumen: " . $doc['nama']);
                    continue;
                }

                try {
                    // Cek apakah dokumen sudah ada
                    $existingDokumen = DokumenNasabah::where('nasabah_id', $id)
                        ->where('nama_dokumen', $doc['nama'])
                        ->first();

                    // Update or Create dokumen
                    $dokumen = DokumenNasabah::updateOrCreate(
                        [
                            'nasabah_id' => $id,
                            'nama_dokumen' => $doc['nama']
                        ],
                        [
                            'link_dokumen' => trim($doc['link'])
                        ]
                    );

                    // Hitung apakah ini dokumen baru atau update
                    if ($existingDokumen) {
                        // Cek apakah link berubah
                        if ($existingDokumen->link_dokumen !== trim($doc['link'])) {
                            $updatedCount++;
                        }
                    } else {
                        $savedCount++;
                    }

                } catch (\Exception $e) {
                    $errorCount++;
                    Log::error("Error menyimpan dokumen {$doc['nama']}: " . $e->getMessage());
                }
            }

            // Commit transaction jika semua berhasil
            DB::commit();

            // Buat pesan sukses yang informatif
            $messages = [];
            
            if ($savedCount > 0) {
                $messages[] = "{$savedCount} dokumen baru berhasil disimpan";
            }
            
            if ($updatedCount > 0) {
                $messages[] = "{$updatedCount} dokumen berhasil diperbarui";
            }
            
            if ($skippedCount > 0) {
                $messages[] = "{$skippedCount} dokumen dilewati (link kosong)";
            }

            if ($errorCount > 0) {
                $messages[] = "{$errorCount} dokumen gagal disimpan";
            }

            // Gabungkan semua pesan
            if (!empty($messages)) {
                $finalMessage = implode(', ', $messages);
                
                // Tentukan tipe alert
                if ($errorCount > 0 && ($savedCount === 0 && $updatedCount === 0)) {
                    return redirect()->back()->with('error', 'Gagal menyimpan dokumen. ' . $finalMessage);
                } elseif ($savedCount === 0 && $updatedCount === 0 && $skippedCount > 0) {
                    return redirect()->back()->with('error', 'Tidak ada dokumen yang disimpan. Pastikan minimal 1 link diisi dengan benar.');
                } else {
                    return redirect()->back()->with('success', '✓ Berhasil! ' . $finalMessage);
                }
            } else {
                return redirect()->back()->with('error', 'Tidak ada dokumen yang diproses');
            }

        } catch (\Exception $e) {
            // Rollback jika terjadi error
            DB::rollBack();
            
            // Log error untuk debugging
            Log::error('Error saat menyimpan dokumen nasabah: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menyimpan dokumen. Silakan coba lagi.')
                ->withInput();
        }
    }

    /**
     * Delete dokumen tertentu (opsional - jika diperlukan)
     */
    public function destroy($nasabahId, $dokumenId)
    {
        try {
            // Validasi nasabah milik petugas yang login
            $nasabah = Nasabah::where('id', $nasabahId)
                ->where('user_id', auth()->id())
                ->firstOrFail();

            // Hapus dokumen
            $dokumen = DokumenNasabah::where('id', $dokumenId)
                ->where('nasabah_id', $nasabahId)
                ->firstOrFail();

            $namaRokumen = $dokumen->nama_dokumen;
            $dokumen->delete();

            return redirect()->back()
                ->with('success', "Dokumen '{$namaDokumen}' berhasil dihapus");

        } catch (\Exception $e) {
            Log::error('Error menghapus dokumen: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal menghapus dokumen');
        }
    }

    /**
     * Get statistik dokumen nasabah (opsional - untuk dashboard)
     */
    public function getStatistik($nasabahId)
    {
        $nasabah = Nasabah::where('id', $nasabahId)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $totalDokumen = count($this->dokumenWajib);
        $dokumenTerisi = DokumenNasabah::where('nasabah_id', $nasabahId)
            ->whereNotNull('link_dokumen')
            ->count();

        $persentase = $totalDokumen > 0 ? round(($dokumenTerisi / $totalDokumen) * 100, 2) : 0;

        return [
            'total' => $totalDokumen,
            'terisi' => $dokumenTerisi,
            'kosong' => $totalDokumen - $dokumenTerisi,
            'persentase' => $persentase,
            'is_complete' => $dokumenTerisi === $totalDokumen
        ];
    }
}