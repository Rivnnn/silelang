<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Nasabah;
use App\Models\DokumenNasabah;
use App\Models\Lpa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class NasabahController extends Controller
{
    /**
     * Daftar jenis lelang yang valid — satu-satunya sumber kebenaran.
     * Dipakai oleh validasi store() dan opsi <select> di view.
     */
    const JENIS_LELANG = [
        'Tanah',
        'Bangunan',
        'Tanah Berikut Bangunan',
        'Lelang Eksekusi HT',
        'Lelang Eksekusi Pengadilan',
        'Lelang Sukarela',
    ];

    private array $dokumenWajib = [
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
        'BA Musyawarah',
    ];

    /**
     * Halaman utama — daftar nasabah dengan filter & paginasi.
     *
     * Bug yang diperbaiki:
     *  1. filter_jenis dan filter_dokumen sebelumnya diabaikan.
     *  2. Hitung jumlah_dokumen dan jumlah_lpa pakai subquery (efisien, kompatibel paginate).
     *  3. $nasabah dikembalikan sebagai LengthAwarePaginator agar ->total(),
     *     ->firstItem(), dsb. bekerja di view (sebelumnya di-get() lalu di-map()).
     */
    public function index(Request $request)
    {
        $query = Nasabah::query()
            ->where('user_id', auth()->id())
            // Hitung dokumen & LPA via subquery — efisien, tidak N+1
            ->withCount([
                'dokumen as jumlah_dokumen' => fn ($q) => $q->whereNotNull('link_dokumen'),
                'lpa as jumlah_lpa',
            ])
            // Filter: kata kunci (nama / NIK / jenis)
            ->when($request->filled('search'), function ($q) use ($request) {
                $kw = '%' . trim($request->search) . '%';
                $q->where(function ($q2) use ($kw) {
                    $q2->where('nama_nasabah', 'like', $kw)
                       ->orWhere('nik', 'like', $kw)
                       ->orWhere('jenis_lelang', 'like', $kw);
                });
            })
            // Filter: jenis lelang
            ->when($request->filled('filter_jenis'), function ($q) use ($request) {
                $q->where('jenis_lelang', $request->filter_jenis);
            })
            // Filter: status dokumen (butuh HAVING karena pakai withCount)
            ->when($request->filled('filter_dokumen'), function ($q) use ($request) {
                match ($request->filter_dokumen) {
                    'lengkap' => $q->having('jumlah_dokumen', '>=', count($this->dokumenWajib)),
                    'proses'  => $q->having('jumlah_dokumen', '>', 0)
                                   ->having('jumlah_dokumen', '<', count($this->dokumenWajib)),
                    'kosong'  => $q->having('jumlah_dokumen', 0),
                    default   => null,
                };
            })
            ->latest();

        $nasabah = $query->paginate(15)->withQueryString();

        return view('petugas.nasabah.index', [
            'nasabah'     => $nasabah,
            'jenisLelang' => self::JENIS_LELANG,
        ]);
    }

    /**
     * Simpan nasabah baru.
     *
     * Bug yang diperbaiki:
     *  — Validasi in: sekarang menggunakan self::JENIS_LELANG,
     *    sehingga semua 6 opsi di form diterima.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_nasabah'  => 'required|string|max:255',
            'nik'           => 'required|string|size:16|unique:nasabah,nik',
            'alamat'        => 'required|string|max:500',
            'no_hp'         => 'required|string|max:20',
            'lokasi_lelang' => 'required|string|max:255',
            'jenis_lelang'  => ['required', 'string', \Illuminate\Validation\Rule::in(self::JENIS_LELANG)],
        ], [
            'nama_nasabah.required'  => 'Nama nasabah wajib diisi.',
            'nik.required'           => 'NIK wajib diisi.',
            'nik.size'               => 'NIK harus tepat 16 digit.',
            'nik.unique'             => 'NIK sudah terdaftar dalam sistem.',
            'alamat.required'        => 'Alamat wajib diisi.',
            'no_hp.required'         => 'No. HP wajib diisi.',
            'lokasi_lelang.required' => 'Lokasi lelang wajib diisi.',
            'jenis_lelang.required'  => 'Jenis lelang wajib dipilih.',
            'jenis_lelang.in'        => 'Jenis lelang tidak valid.',
        ]);

        try {
            DB::beginTransaction();

            $nasabah = Nasabah::create([
                'user_id'       => auth()->id(),
                'nama_nasabah'  => trim($validated['nama_nasabah']),
                'nik'           => trim($validated['nik']),
                'alamat'        => trim($validated['alamat']),
                'no_hp'         => trim($validated['no_hp']),
                'lokasi_lelang' => trim($validated['lokasi_lelang']),
                'jenis_lelang'  => $validated['jenis_lelang'],
            ]);

            DB::commit();

            return redirect()->route('petugas.nasabah.index')
                ->with('success', "Data nasabah '{$nasabah->nama_nasabah}' berhasil ditambahkan.");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error tambah nasabah: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi.')
                ->withInput();
        }
    }

    /**
     * Halaman dokumen nasabah.
     */
    public function showDokumen(int $id)
    {
        $nasabah = Nasabah::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $dokumen = DokumenNasabah::where('nasabah_id', $id)
            ->get()
            ->keyBy('nama_dokumen');

        return view('petugas.nasabah.dokumen', [
            'nasabah'      => $nasabah,
            'dokumenWajib' => $this->dokumenWajib,
            'dokumen'      => $dokumen,
        ]);
    }

    /**
     * Simpan / update dokumen nasabah.
     */
    public function storeDokumen(Request $request, int $id)
    {
        $nasabah = Nasabah::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $request->validate([
            'dokumen'         => 'required|array|min:1',
            'dokumen.*.nama'  => 'required|string',
            'dokumen.*.link'  => 'nullable|url',
        ], [
            'dokumen.*.link.url' => 'Link dokumen harus berupa URL yang valid.',
        ]);

        try {
            DB::beginTransaction();

            $savedCount = $updatedCount = $skippedCount = 0;

            foreach ($request->dokumen as $doc) {
                if (! in_array($doc['nama'], $this->dokumenWajib)) {
                    continue;
                }

                if (empty(trim($doc['link'] ?? ''))) {
                    $skippedCount++;
                    continue;
                }

                if (! filter_var($doc['link'], FILTER_VALIDATE_URL)) {
                    continue;
                }

                $existing = DokumenNasabah::where('nasabah_id', $id)
                    ->where('nama_dokumen', $doc['nama'])
                    ->exists();

                DokumenNasabah::updateOrCreate(
                    ['nasabah_id' => $id, 'nama_dokumen' => $doc['nama']],
                    ['link_dokumen' => trim($doc['link'])]
                );

                $existing ? $updatedCount++ : $savedCount++;
            }

            DB::commit();

            $parts = [];
            if ($savedCount > 0)   $parts[] = "{$savedCount} dokumen baru disimpan";
            if ($updatedCount > 0) $parts[] = "{$updatedCount} dokumen diperbarui";
            if ($skippedCount > 0) $parts[] = "{$skippedCount} dokumen dilewati (link kosong)";

            return back()->with('success', implode(', ', $parts) . '.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error simpan dokumen: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menyimpan dokumen.');
        }
    }

    /**
     * Halaman LPA nasabah.
     */
    public function showLpa(int $id)
    {
        $nasabah = Nasabah::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $lpaList = Lpa::where('nasabah_id', $id)->latest()->get();

        return view('petugas.nasabah.lpa', compact('nasabah', 'lpaList'));
    }

    /**
     * Simpan LPA baru.
     */
    public function storeLpa(Request $request, int $id)
    {
        $nasabah = Nasabah::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $validated = $request->validate([
            'jenis_legalitas' => 'required|string|in:SHM,SHGB',
            'luas_tanah'      => 'required|numeric|min:0',
            'luas_bangunan'   => 'required|numeric|min:0',
            'spek_bangunan'   => 'required|string',
            'nilai_pasar'     => 'required|numeric|min:0',
            'nilai_likuidasi' => 'required|numeric|min:0',
            'lelang_ke'       => 'required|integer|in:1,2,3',
        ]);

        $nilaiPasar     = (float) $validated['nilai_pasar'];
        $nilaiLikuidasi = (float) $validated['nilai_likuidasi'];

        $nilaiLimit = match ((int) $validated['lelang_ke']) {
            1       => $nilaiPasar,
            2       => ($nilaiPasar + $nilaiLikuidasi) / 2,
            default => $nilaiLikuidasi,
        };

        $uangJaminan = 0.2 * $nilaiLimit;

        Lpa::create([
            'user_id'         => auth()->id(),
            'nasabah_id'      => $id,
            'jenis_legalitas' => $validated['jenis_legalitas'],
            'luas_tanah'      => $validated['luas_tanah'],
            'luas_bangunan'   => $validated['luas_bangunan'],
            'spek_bangunan'   => $validated['spek_bangunan'],
            'nilai_pasar'     => $nilaiPasar,
            'nilai_likuidasi' => $nilaiLikuidasi,
            'lelang_ke'       => $validated['lelang_ke'],
            'nilai_limit'     => $nilaiLimit,
            'uang_jaminan'    => $uangJaminan,
        ]);

        return back()->with('success', 'Data LPA berhasil disimpan.');
    }
}