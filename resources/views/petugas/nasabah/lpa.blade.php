@extends('layouts.petugas')

@section('title', 'LPA | ' . $nasabah->nama_nasabah)
@section('page-title', 'Laporan Penilaian Agunan')

@section('content')

{{-- Breadcrumb --}}
<div style="margin-bottom:16px; font-size:13px; color:#7f8c8d;">
    <a href="{{ route('petugas.nasabah.index') }}"
       style="color:#39C6C9; text-decoration:none; font-weight:600;">
        ← Kembali ke Daftar Nasabah
    </a>
</div>

{{-- Header info nasabah --}}
<div style="background:linear-gradient(135deg,#6f42c1,#563d7c); border-radius:12px;
            padding:20px 24px; margin-bottom:20px; color:#fff;
            display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px;">
    <div>
        <div style="font-size:18px; font-weight:700;">{{ $nasabah->nama_nasabah }}</div>
        <div style="font-size:13px; opacity:0.9; margin-top:4px;">
            NIK: {{ $nasabah->nik }} &nbsp;|&nbsp; {{ $nasabah->jenis_lelang }}
            &nbsp;|&nbsp; {{ $nasabah->lokasi_lelang }}
        </div>
    </div>
    <a href="{{ route('petugas.nasabah.dokumen', $nasabah->id) }}"
       style="padding:8px 16px; background:rgba(255,255,255,0.2); color:#fff;
              border-radius:8px; text-decoration:none; font-size:13px; font-weight:600;
              border:1px solid rgba(255,255,255,0.4);">
        📄 Lihat Dokumen Nasabah Ini →
    </a>
</div>

@if(session('success'))
    <div style="background:#d4edda; color:#155724; padding:12px 16px;
                border-radius:8px; margin-bottom:16px; border-left:4px solid #28a745;">
        ✓ {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div style="background:#f8d7da; color:#721c24; padding:12px 16px;
                border-radius:8px; margin-bottom:16px; border-left:4px solid #dc3545;">
        ✖ {{ session('error') }}
    </div>
@endif

<div style="display:grid; grid-template-columns:1fr 360px; gap:20px; align-items:start;">

    {{-- DAFTAR LPA --}}
    <div style="background:#fff; border-radius:12px; padding:24px;
                box-shadow:0 2px 8px rgba(0,0,0,0.06);">
        <h3 style="margin-bottom:18px; font-size:15px; font-weight:600; color:#2c3e50;">
            📊 Riwayat LPA
        </h3>

        @if($lpaList->isEmpty())
            <div style="text-align:center; padding:40px 0; color:#7f8c8d;">
                <div style="font-size:36px; margin-bottom:10px;">📊</div>
                <p>Belum ada data LPA untuk nasabah ini.</p>
            </div>
        @else
            @foreach($lpaList as $lpa)
            <div style="border:1px solid #e9ecef; border-radius:10px; padding:18px;
                        margin-bottom:14px; background:#fafafa;">
                <div style="display:flex; justify-content:space-between;
                            align-items:center; margin-bottom:12px;">
                    <span style="background:#6f42c1; color:#fff; padding:4px 12px;
                                 border-radius:20px; font-size:12px; font-weight:600;">
                        Lelang ke-{{ $lpa->lelang_ke }}
                    </span>
                    <span style="font-size:12px; color:#7f8c8d;">
                        {{ $lpa->created_at->format('d M Y') }}
                    </span>
                </div>
                <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:12px;">
                    <div>
                        <div style="font-size:11px; color:#7f8c8d; margin-bottom:3px;">Jenis Legalitas</div>
                        <div style="font-size:13px; font-weight:600;">{{ $lpa->jenis_legalitas }}</div>
                    </div>
                    <div>
                        <div style="font-size:11px; color:#7f8c8d; margin-bottom:3px;">Luas Tanah</div>
                        <div style="font-size:13px; font-weight:600;">{{ $lpa->luas_tanah }} m²</div>
                    </div>
                    <div>
                        <div style="font-size:11px; color:#7f8c8d; margin-bottom:3px;">Luas Bangunan</div>
                        <div style="font-size:13px; font-weight:600;">{{ $lpa->luas_bangunan }} m²</div>
                    </div>
                    <div>
                        <div style="font-size:11px; color:#7f8c8d; margin-bottom:3px;">Nilai Pasar</div>
                        <div style="font-size:13px; font-weight:600; color:#2980b9;">
                            Rp {{ number_format($lpa->nilai_pasar, 0, ',', '.') }}
                        </div>
                    </div>
                    <div>
                        <div style="font-size:11px; color:#7f8c8d; margin-bottom:3px;">Nilai Likuidasi</div>
                        <div style="font-size:13px; font-weight:600; color:#e67e22;">
                            Rp {{ number_format($lpa->nilai_likuidasi, 0, ',', '.') }}
                        </div>
                    </div>
                    <div>
                        <div style="font-size:11px; color:#7f8c8d; margin-bottom:3px;">Nilai Limit</div>
                        <div style="font-size:13px; font-weight:600; color:#27ae60;">
                            Rp {{ number_format($lpa->nilai_limit, 0, ',', '.') }}
                        </div>
                    </div>
                    <div style="grid-column:1/-1;">
                        <div style="font-size:11px; color:#7f8c8d; margin-bottom:3px;">Uang Jaminan (20%)</div>
                        <div style="font-size:14px; font-weight:700; color:#8e44ad;">
                            Rp {{ number_format($lpa->uang_jaminan, 0, ',', '.') }}
                        </div>
                    </div>
                    <div style="grid-column:1/-1;">
                        <div style="font-size:11px; color:#7f8c8d; margin-bottom:3px;">Spesifikasi Bangunan</div>
                        <div style="font-size:13px; color:#2c3e50; background:#f8f9fa;
                                    padding:8px 10px; border-radius:6px;">
                            {{ $lpa->spek_bangunan }}
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        @endif
    </div>

    {{-- FORM TAMBAH LPA --}}
    <div style="background:#fff; border-radius:12px; padding:24px;
                box-shadow:0 2px 8px rgba(0,0,0,0.06);
                position:sticky; top:80px;">

        <h3 style="margin-bottom:16px; font-size:15px; font-weight:600;
                    color:#2c3e50; border-bottom:2px solid #f0f0f0; padding-bottom:10px;">
            + Tambah Data LPA
        </h3>

        <form method="POST" action="{{ route('petugas.nasabah.lpa.store', $nasabah->id) }}">
            @csrf

            <div style="margin-bottom:12px;">
                <label style="display:block; font-size:12px; font-weight:600; color:#555; margin-bottom:4px;">
                    Jenis Legalitas <span style="color:#dc3545;">*</span>
                </label>
                <select name="jenis_legalitas" required
                        style="width:100%; padding:8px 10px; border:1px solid #ddd;
                               border-radius:7px; font-size:13px; box-sizing:border-box;">
                    <option value="">— Pilih —</option>
                    <option value="SHM" {{ old('jenis_legalitas') === 'SHM' ? 'selected' : '' }}>SHM</option>
                    <option value="SHGB" {{ old('jenis_legalitas') === 'SHGB' ? 'selected' : '' }}>SHGB</option>
                </select>
            </div>

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px; margin-bottom:12px;">
                <div>
                    <label style="display:block; font-size:12px; font-weight:600; color:#555; margin-bottom:4px;">
                        Luas Tanah (m²) <span style="color:#dc3545;">*</span>
                    </label>
                    <input type="number" name="luas_tanah" step="0.01" min="0" required
                           value="{{ old('luas_tanah') }}"
                           style="width:100%; padding:8px 10px; border:1px solid #ddd;
                                  border-radius:7px; font-size:13px; box-sizing:border-box;">
                </div>
                <div>
                    <label style="display:block; font-size:12px; font-weight:600; color:#555; margin-bottom:4px;">
                        Luas Bangunan (m²) <span style="color:#dc3545;">*</span>
                    </label>
                    <input type="number" name="luas_bangunan" step="0.01" min="0" required
                           value="{{ old('luas_bangunan') }}"
                           style="width:100%; padding:8px 10px; border:1px solid #ddd;
                                  border-radius:7px; font-size:13px; box-sizing:border-box;">
                </div>
            </div>

            <div style="margin-bottom:12px;">
                <label style="display:block; font-size:12px; font-weight:600; color:#555; margin-bottom:4px;">
                    Spesifikasi Bangunan <span style="color:#dc3545;">*</span>
                </label>
                <textarea name="spek_bangunan" rows="2" required
                          placeholder="Contoh: 2 lantai, beton, kondisi baik"
                          style="width:100%; padding:8px 10px; border:1px solid #ddd;
                                 border-radius:7px; font-size:13px; box-sizing:border-box;
                                 resize:vertical; font-family:inherit;">{{ old('spek_bangunan') }}</textarea>
            </div>

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px; margin-bottom:12px;">
                <div>
                    <label style="display:block; font-size:12px; font-weight:600; color:#555; margin-bottom:4px;">
                        Nilai Pasar (Rp) <span style="color:#dc3545;">*</span>
                    </label>
                    <input type="number" name="nilai_pasar" min="0" step="any" required
                           value="{{ old('nilai_pasar') }}"
                           style="width:100%; padding:8px 10px; border:1px solid #ddd;
                                  border-radius:7px; font-size:13px; box-sizing:border-box;">
                </div>
                <div>
                    <label style="display:block; font-size:12px; font-weight:600; color:#555; margin-bottom:4px;">
                        Nilai Likuidasi (Rp) <span style="color:#dc3545;">*</span>
                    </label>
                    <input type="number" name="nilai_likuidasi" min="0" step="any" required
                           value="{{ old('nilai_likuidasi') }}"
                           style="width:100%; padding:8px 10px; border:1px solid #ddd;
                                  border-radius:7px; font-size:13px; box-sizing:border-box;">
                </div>
            </div>

            <div style="margin-bottom:16px;">
                <label style="display:block; font-size:12px; font-weight:600; color:#555; margin-bottom:4px;">
                    Lelang Ke <span style="color:#dc3545;">*</span>
                </label>
                <select name="lelang_ke" required
                        style="width:100%; padding:8px 10px; border:1px solid #ddd;
                               border-radius:7px; font-size:13px; box-sizing:border-box;">
                    <option value="">— Pilih —</option>
                    <option value="1" {{ old('lelang_ke') == 1 ? 'selected' : '' }}>
                        Lelang 1 (Nilai Limit = Nilai Pasar)
                    </option>
                    <option value="2" {{ old('lelang_ke') == 2 ? 'selected' : '' }}>
                        Lelang 2 (Nilai Limit = Rata-rata)
                    </option>
                    <option value="3" {{ old('lelang_ke') == 3 ? 'selected' : '' }}>
                        Lelang 3 (Nilai Limit = Nilai Likuidasi)
                    </option>
                </select>
            </div>

            <button type="submit"
                    style="width:100%; padding:10px; background:#6f42c1; color:#fff;
                           border:none; border-radius:8px; font-size:14px;
                           font-weight:600; cursor:pointer;">
                + Simpan LPA
            </button>
        </form>
    </div>

</div>

@endsection