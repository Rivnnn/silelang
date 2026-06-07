@extends('layouts.petugas')

@section('title', 'Dokumen | ' . $nasabah->nama_nasabah)
@section('page-title', 'Dokumen Nasabah')

@section('content')

{{-- Breadcrumb --}}
<div style="margin-bottom:16px; font-size:13px; color:#7f8c8d;">
    <a href="{{ route('petugas.nasabah.index') }}"
       style="color:#39C6C9; text-decoration:none; font-weight:600;">
        ← Kembali ke Daftar Nasabah
    </a>
</div>

{{-- Header info nasabah --}}
<div style="background:linear-gradient(135deg,#39C6C9,#2FB3B6); border-radius:12px;
            padding:20px 24px; margin-bottom:20px; color:#fff;
            display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px;">
    <div>
        <div style="font-size:18px; font-weight:700;">{{ $nasabah->nama_nasabah }}</div>
        <div style="font-size:13px; opacity:0.9; margin-top:4px;">
            NIK: {{ $nasabah->nik }} &nbsp;|&nbsp; {{ $nasabah->jenis_lelang }}
            &nbsp;|&nbsp; {{ $nasabah->lokasi_lelang }}
        </div>
    </div>
    <a href="{{ route('petugas.nasabah.lpa', $nasabah->id) }}"
       style="padding:8px 16px; background:rgba(255,255,255,0.2); color:#fff;
              border-radius:8px; text-decoration:none; font-size:13px; font-weight:600;
              border:1px solid rgba(255,255,255,0.4);">
        📊 Lihat LPA Nasabah Ini →
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

{{-- Statistik dokumen --}}
@php
    $terisi = $dokumen->filter(fn($d) => !empty($d->link_dokumen))->count();
    $total  = count($dokumenWajib);
    $persen = $total > 0 ? round(($terisi / $total) * 100) : 0;
@endphp
<div style="background:#fff; border-radius:12px; padding:16px 24px;
            margin-bottom:20px; box-shadow:0 2px 8px rgba(0,0,0,0.06);
            display:flex; align-items:center; gap:20px; flex-wrap:wrap;">
    <div>
        <div style="font-size:12px; color:#7f8c8d;">Kelengkapan Dokumen</div>
        <div style="font-size:22px; font-weight:700; color:#2c3e50;">
            {{ $terisi }} / {{ $total }}
            <span style="font-size:14px; font-weight:400; color:#7f8c8d;">dokumen</span>
        </div>
    </div>
    <div style="flex:1; min-width:150px;">
        <div style="background:#e9ecef; border-radius:10px; height:10px; overflow:hidden;">
            <div style="background:{{ $persen >= 100 ? '#27ae60' : ($persen >= 50 ? '#f39c12' : '#e74c3c') }};
                        width:{{ $persen }}%; height:100%; border-radius:10px;
                        transition:width 0.5s ease;"></div>
        </div>
        <div style="font-size:12px; color:#7f8c8d; margin-top:4px;">{{ $persen }}% lengkap</div>
    </div>
</div>

{{-- Form dokumen --}}
<div style="background:#fff; border-radius:12px; padding:24px;
            box-shadow:0 2px 8px rgba(0,0,0,0.06);">

    <h3 style="margin-bottom:18px; font-size:15px; font-weight:600; color:#2c3e50;">
        📄 Daftar Dokumen Persyaratan Lelang
    </h3>

    <form method="POST" action="{{ route('petugas.nasabah.dokumen.store', $nasabah->id) }}">
        @csrf

        <div style="overflow-x:auto;">
            <table style="width:100%; border-collapse:collapse; font-size:13px;">
                <thead>
                    <tr style="background:#f8f9fa; border-bottom:2px solid #e9ecef;">
                        <th style="padding:10px 12px; text-align:left; color:#6c757d; width:40px;">No</th>
                        <th style="padding:10px 12px; text-align:left; color:#6c757d;">Nama Dokumen</th>
                        <th style="padding:10px 12px; text-align:left; color:#6c757d;">Link Dokumen</th>
                        <th style="padding:10px 12px; text-align:center; color:#6c757d; width:120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($dokumenWajib as $i => $nama)
                    @php $doc = $dokumen[$nama] ?? null; @endphp
                    <tr style="border-bottom:1px solid #f0f0f0;
                               background:{{ $doc && $doc->link_dokumen ? '#f0fff4' : 'transparent' }}">
                        <td style="padding:10px 12px; color:#7f8c8d;">{{ $i + 1 }}</td>
                        <td style="padding:10px 12px; font-weight:500; color:#2c3e50;">
                            <input type="hidden" name="dokumen[{{ $i }}][nama]" value="{{ $nama }}">
                            {{ $nama }}
                            @if($doc && $doc->link_dokumen)
                                <span style="color:#27ae60; font-size:11px; margin-left:6px;">✓</span>
                            @endif
                        </td>
                        <td style="padding:10px 12px;">
                            <input type="url"
                                   name="dokumen[{{ $i }}][link]"
                                   value="{{ $doc->link_dokumen ?? '' }}"
                                   placeholder="https://drive.google.com/..."
                                   style="width:100%; padding:7px 10px; border:1px solid #ddd;
                                          border-radius:6px; font-size:12px; box-sizing:border-box;
                                          {{ $doc && $doc->link_dokumen ? 'border-color:#27ae60; background:#f0fff4;' : '' }}">
                        </td>
                        <td style="padding:10px 12px; text-align:center;">
                            @if($doc && $doc->link_dokumen)
                                <div style="display:flex; gap:4px; justify-content:center;">
                                    {{-- Buka link --}}
                                    <a href="{{ $doc->link_dokumen }}" target="_blank"
                                       title="Buka dokumen"
                                       style="padding:4px 8px; background:#17a2b8; color:#fff;
                                              border-radius:5px; text-decoration:none; font-size:11px;">
                                        🔗
                                    </a>
                                    {{-- Salin link --}}
                                    <button type="button"
                                            onclick="copyLink('{{ $doc->link_dokumen }}', this)"
                                            title="Salin link"
                                            style="padding:4px 8px; background:#6c757d; color:#fff;
                                                   border:none; border-radius:5px; cursor:pointer;
                                                   font-size:11px;">
                                        📋
                                    </button>
                                </div>
                            @else
                                <span style="color:#aaa; font-size:11px;">Belum ada</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div style="margin-top:16px; display:flex; justify-content:flex-end;">
            <button type="submit"
                    style="padding:10px 24px; background:#39C6C9; color:#fff;
                           border:none; border-radius:8px; font-size:14px;
                           font-weight:600; cursor:pointer;">
                💾 Simpan Semua Dokumen
            </button>
        </div>
    </form>
</div>

@endsection

@push('scripts')
<script>
function copyLink(url, btn) {
    navigator.clipboard.writeText(url).then(function() {
        const original = btn.textContent;
        btn.textContent = '✓';
        btn.style.background = '#27ae60';
        setTimeout(() => {
            btn.textContent = original;
            btn.style.background = '#6c757d';
        }, 1500);
    }).catch(function() {
        // Fallback untuk browser lama
        const el = document.createElement('textarea');
        el.value = url;
        document.body.appendChild(el);
        el.select();
        document.execCommand('copy');
        document.body.removeChild(el);
        btn.textContent = '✓';
        btn.style.background = '#27ae60';
        setTimeout(() => {
            btn.textContent = '📋';
            btn.style.background = '#6c757d';
        }, 1500);
    });
}
</script>
@endpush