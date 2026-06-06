@extends('layouts.admin')

@section('title', 'Detail Pengajuan TRR')
@section('page-title', 'Detail Pengajuan TRR')

@section('content')

@php
    $nasabah = $pengajuan->pengajuanLelang->nasabah;
    $lelang  = $pengajuan->pengajuanLelang;
    $petugas = $pengajuan->user;
@endphp

{{-- Tombol kembali --}}
<div style="margin-bottom:20px;">
    <a href="{{ route('admin.monitoring.trr') }}"
       style="display:inline-flex; align-items:center; gap:6px; color:#6c757d;
              text-decoration:none; font-size:14px; font-weight:600;">
        ← Kembali ke Monitoring TRR
    </a>
</div>

@if(session('success'))
    <div style="background:#d4edda; color:#155724; padding:12px 16px;
                border-radius:8px; margin-bottom:20px; border-left:4px solid #28a745;">
        ✓ {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div style="background:#f8d7da; color:#721c24; padding:12px 16px;
                border-radius:8px; margin-bottom:20px; border-left:4px solid #dc3545;">
        ✖ {{ session('error') }}
    </div>
@endif

<div style="display:grid; grid-template-columns:1fr 380px; gap:20px; align-items:start;">

    {{-- KOLOM KIRI: Detail Informasi --}}
    <div style="display:flex; flex-direction:column; gap:20px;">

        {{-- Informasi Petugas --}}
        <div style="background:#fff; border-radius:12px; padding:24px;
                    box-shadow:0 2px 8px rgba(0,0,0,0.06);">
            <h3 style="margin-bottom:16px; font-size:15px; font-weight:600;
                        color:#2c3e50; border-bottom:2px solid #f0f0f0; padding-bottom:10px;">
                👤 Informasi Petugas Pengaju
            </h3>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
                <div>
                    <div style="font-size:12px; color:#7f8c8d; margin-bottom:4px;">Nama Petugas</div>
                    <div style="font-size:14px; font-weight:600; color:#2c3e50;">
                        {{ $petugas->name ?? '-' }}
                    </div>
                </div>
                <div>
                    <div style="font-size:12px; color:#7f8c8d; margin-bottom:4px;">Email</div>
                    <div style="font-size:14px; font-weight:600; color:#2c3e50;">
                        {{ $petugas->email ?? '-' }}
                    </div>
                </div>
                <div>
                    <div style="font-size:12px; color:#7f8c8d; margin-bottom:4px;">Tanggal Pengajuan TRR</div>
                    <div style="font-size:14px; font-weight:600; color:#2c3e50;">
                        {{ $pengajuan->created_at->format('d M Y H:i') }}
                    </div>
                </div>
                <div>
                    <div style="font-size:12px; color:#7f8c8d; margin-bottom:4px;">Status</div>
                    <div>
                        @if($pengajuan->status === 'pending')
                            <span style="background:#fff3cd; color:#856404; padding:4px 12px;
                                         border-radius:20px; font-size:12px; font-weight:600;">
                                ⏳ Menunggu Review
                            </span>
                        @elseif($pengajuan->status === 'disetujui')
                            <span style="background:#d4edda; color:#155724; padding:4px 12px;
                                         border-radius:20px; font-size:12px; font-weight:600;">
                                ✓ Disetujui
                            </span>
                        @else
                            <span style="background:#f8d7da; color:#721c24; padding:4px 12px;
                                         border-radius:20px; font-size:12px; font-weight:600;">
                                ✖ Ditolak
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Informasi Nasabah --}}
        <div style="background:#fff; border-radius:12px; padding:24px;
                    box-shadow:0 2px 8px rgba(0,0,0,0.06);">
            <h3 style="margin-bottom:16px; font-size:15px; font-weight:600;
                        color:#2c3e50; border-bottom:2px solid #f0f0f0; padding-bottom:10px;">
                🏦 Informasi Nasabah
            </h3>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
                <div>
                    <div style="font-size:12px; color:#7f8c8d; margin-bottom:4px;">Nama Nasabah</div>
                    <div style="font-size:14px; font-weight:600; color:#2c3e50;">
                        {{ $nasabah->nama_nasabah ?? '-' }}
                    </div>
                </div>
                <div>
                    <div style="font-size:12px; color:#7f8c8d; margin-bottom:4px;">NIK</div>
                    <div style="font-size:14px; font-weight:600; color:#2c3e50;">
                        {{ $nasabah->nik ?? '-' }}
                    </div>
                </div>
                <div>
                    <div style="font-size:12px; color:#7f8c8d; margin-bottom:4px;">No. HP</div>
                    <div style="font-size:14px; font-weight:600; color:#2c3e50;">
                        {{ $nasabah->no_hp ?? '-' }}
                    </div>
                </div>
                <div>
                    <div style="font-size:12px; color:#7f8c8d; margin-bottom:4px;">Jenis Lelang</div>
                    <div style="font-size:14px; font-weight:600; color:#2c3e50;">
                        {{ $nasabah->jenis_lelang ?? '-' }}
                    </div>
                </div>
                <div style="grid-column:1/-1;">
                    <div style="font-size:12px; color:#7f8c8d; margin-bottom:4px;">Alamat</div>
                    <div style="font-size:14px; font-weight:600; color:#2c3e50;">
                        {{ $nasabah->alamat ?? '-' }}
                    </div>
                </div>
                <div>
                    <div style="font-size:12px; color:#7f8c8d; margin-bottom:4px;">Lokasi Lelang</div>
                    <div style="font-size:14px; font-weight:600; color:#2c3e50;">
                        {{ $nasabah->lokasi_lelang ?? '-' }}
                    </div>
                </div>
            </div>
        </div>

        {{-- Informasi Pengajuan Lelang --}}
        <div style="background:#fff; border-radius:12px; padding:24px;
                    box-shadow:0 2px 8px rgba(0,0,0,0.06);">
            <h3 style="margin-bottom:16px; font-size:15px; font-weight:600;
                        color:#2c3e50; border-bottom:2px solid #f0f0f0; padding-bottom:10px;">
                ⚖️ Informasi Pengajuan Lelang
            </h3>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
                <div>
                    <div style="font-size:12px; color:#7f8c8d; margin-bottom:4px;">Tanggal Pengajuan Lelang</div>
                    <div style="font-size:14px; font-weight:600; color:#2c3e50;">
                        {{ \Carbon\Carbon::parse($lelang->tanggal_pengajuan)->format('d M Y') }}
                    </div>
                </div>
                <div>
                    <div style="font-size:12px; color:#7f8c8d; margin-bottom:4px;">Status Lelang</div>
                    <div>
                        <span style="background:#d4edda; color:#155724; padding:4px 12px;
                                     border-radius:20px; font-size:12px; font-weight:600;">
                            ✓ {{ ucfirst($lelang->status) }}
                        </span>
                    </div>
                </div>
                @if($lelang->estimasi_dana_trr)
                <div>
                    <div style="font-size:12px; color:#7f8c8d; margin-bottom:4px;">Estimasi TRR (saat ajukan lelang)</div>
                    <div style="font-size:14px; font-weight:600; color:#3498db;">
                        Rp {{ number_format($lelang->estimasi_dana_trr, 0, ',', '.') }}
                    </div>
                </div>
                @endif
            </div>
        </div>

        {{-- Keterangan Kebutuhan Dana dari Petugas --}}
        <div style="background:#fff; border-radius:12px; padding:24px;
                    box-shadow:0 2px 8px rgba(0,0,0,0.06);">
            <h3 style="margin-bottom:16px; font-size:15px; font-weight:600;
                        color:#2c3e50; border-bottom:2px solid #f0f0f0; padding-bottom:10px;">
                📋 Keterangan Kebutuhan Dana dari Petugas
            </h3>
            <div style="background:#f8f9fa; border-radius:8px; padding:16px;
                        border-left:3px solid #39C6C9; font-size:14px; color:#2c3e50;
                        line-height:1.7;">
                {{ $pengajuan->keterangan ?? 'Tidak ada keterangan' }}
            </div>
            <div style="margin-top:16px; padding:14px; background:#e8f4fd;
                        border-radius:8px; display:flex; justify-content:space-between;
                        align-items:center;">
                <div>
                    <div style="font-size:12px; color:#7f8c8d; margin-bottom:4px;">
                        Nominal yang Diajukan Petugas
                    </div>
                    <div style="font-size:20px; font-weight:700; color:#2980b9;">
                        Rp {{ number_format($pengajuan->nominal_diajukan, 0, ',', '.') }}
                    </div>
                </div>
                <div style="font-size:30px;">💰</div>
            </div>
        </div>

    </div>

    {{-- KOLOM KANAN: Form ACC / Tolak --}}
    @if($pengajuan->status === 'pending')
    <div style="position:sticky; top:80px; display:flex; flex-direction:column; gap:16px;">

        {{-- Form ACC --}}
        <div style="background:#fff; border-radius:12px; padding:24px;
                    box-shadow:0 2px 8px rgba(0,0,0,0.06);
                    border-top:4px solid #28a745;">
            <h3 style="margin-bottom:16px; font-size:15px; font-weight:600; color:#28a745;">
                ✓ ACC Dana TRR
            </h3>

            <form id="form-acc" method="POST"
                  action="{{ route('admin.monitoring.trr.approve', $pengajuan->id) }}">
                @csrf

                <div style="margin-bottom:14px;">
                    <label style="display:block; font-size:13px; font-weight:600;
                                   color:#555; margin-bottom:6px;">
                        Nominal Disetujui (Rp) <span style="color:#dc3545;">*</span>
                    </label>
                    <input type="number"
                           name="nominal_disetujui"
                           value="{{ $pengajuan->nominal_diajukan }}"
                           min="1000" step="1000" required
                           style="width:100%; padding:10px 12px; border:2px solid #e0e0e0;
                                  border-radius:8px; font-size:14px; box-sizing:border-box;"
                           onfocus="this.style.borderColor='#28a745'"
                           onblur="this.style.borderColor='#e0e0e0'"
                           oninput="previewNominal(this.value, 'previewAcc')">
                    <div id="previewAcc"
                         style="font-size:12px; color:#27ae60; font-weight:600;
                                margin-top:4px; min-height:18px;"></div>
                </div>

                <div style="margin-bottom:16px;">
                    <label style="display:block; font-size:13px; font-weight:600;
                                   color:#555; margin-bottom:6px;">
                        Catatan (opsional)
                    </label>
                    <input type="text" name="catatan_admin"
                           id="catatan_acc"
                           placeholder="Contoh: Disetujui sesuai kebutuhan lapangan"
                           style="width:100%; padding:10px 12px; border:2px solid #e0e0e0;
                                  border-radius:8px; font-size:14px; box-sizing:border-box;">
                </div>

                <button type="button"
                        onclick="openModalAcc()"
                        style="width:100%; padding:12px; background:#28a745; color:#fff;
                               border:none; border-radius:8px; font-size:14px;
                               font-weight:600; cursor:pointer; transition:all 0.3s ease;"
                        onmouseover="this.style.background='#218838'"
                        onmouseout="this.style.background='#28a745'">
                    ✓ ACC & Cairkan Dana
                </button>
            </form>
        </div>

        {{-- Form Tolak --}}
        <div style="background:#fff; border-radius:12px; padding:24px;
                    box-shadow:0 2px 8px rgba(0,0,0,0.06);
                    border-top:4px solid #dc3545;">
            <h3 style="margin-bottom:16px; font-size:15px; font-weight:600; color:#dc3545;">
                ✖ Tolak Pengajuan
            </h3>

            <form id="form-tolak" method="POST"
                  action="{{ route('admin.monitoring.trr.reject', $pengajuan->id) }}">
                @csrf

                <div style="margin-bottom:16px;">
                    <label style="display:block; font-size:13px; font-weight:600;
                                   color:#555; margin-bottom:6px;">
                        Alasan Penolakan <span style="color:#dc3545;">*</span>
                    </label>
                    <textarea name="catatan_admin" rows="4" required minlength="10"
                              id="alasan_tolak"
                              placeholder="Jelaskan alasan penolakan secara detail..."
                              style="width:100%; padding:10px 12px; border:2px solid #e0e0e0;
                                     border-radius:8px; font-size:14px; box-sizing:border-box;
                                     resize:vertical; font-family:inherit;"
                              onfocus="this.style.borderColor='#dc3545'"
                              onblur="this.style.borderColor='#e0e0e0'"
                              oninput="checkReject(this)"></textarea>
                    <div id="rejectHint"
                         style="font-size:12px; color:#aaa; text-align:right; margin-top:4px;">
                        0 / 10 karakter minimum
                    </div>
                </div>

                <button type="button" id="btnTolak" disabled
                        onclick="openModalTolak()"
                        style="width:100%; padding:12px; background:#dc3545; color:#fff;
                               border:none; border-radius:8px; font-size:14px;
                               font-weight:600; cursor:not-allowed; opacity:0.6;
                               transition:all 0.3s ease;">
                    ✖ Tolak Pengajuan
                </button>
            </form>
        </div>

    </div>

    {{-- Jika sudah diproses, tampilkan hasil --}}
    @else
    <div style="background:#fff; border-radius:12px; padding:24px;
                box-shadow:0 2px 8px rgba(0,0,0,0.06);">
        <h3 style="margin-bottom:16px; font-size:15px; font-weight:600; color:#2c3e50;">
            Hasil Review
        </h3>
        <div style="background:#f8f9fa; border-radius:8px; padding:16px;">
            <div style="margin-bottom:12px;">
                <div style="font-size:12px; color:#7f8c8d; margin-bottom:4px;">Status</div>
                @if($pengajuan->status === 'disetujui')
                    <span style="background:#d4edda; color:#155724; padding:4px 12px;
                                 border-radius:20px; font-size:12px; font-weight:600;">
                        ✓ Disetujui
                    </span>
                @else
                    <span style="background:#f8d7da; color:#721c24; padding:4px 12px;
                                 border-radius:20px; font-size:12px; font-weight:600;">
                        ✖ Ditolak
                    </span>
                @endif
            </div>
            @if($pengajuan->nominal_disetujui)
            <div style="margin-bottom:12px;">
                <div style="font-size:12px; color:#7f8c8d; margin-bottom:4px;">Nominal Disetujui</div>
                <div style="font-size:18px; font-weight:700; color:#27ae60;">
                    Rp {{ number_format($pengajuan->nominal_disetujui, 0, ',', '.') }}
                </div>
            </div>
            @endif
            <div>
                <div style="font-size:12px; color:#7f8c8d; margin-bottom:4px;">Catatan Admin</div>
                <div style="font-size:14px; color:#2c3e50;">
                    {{ $pengajuan->catatan_admin ?? '-' }}
                </div>
            </div>
            @if($pengajuan->processed_at)
            <div style="margin-top:12px; font-size:12px; color:#aaa;">
                Diproses pada {{ $pengajuan->processed_at->format('d M Y H:i') }}
            </div>
            @endif
        </div>
    </div>
    @endif

</div>


{{-- ===================== MODAL ACC ===================== --}}
@if($pengajuan->status === 'pending')
<div id="modal-acc"
     style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.55);
            z-index:1050; align-items:center; justify-content:center;"
     onclick="if(event.target===this) closeModal('acc')">
    <div style="background:#fff; border-radius:16px; padding:28px 28px 24px;
                width:440px; max-width:94vw;
                box-shadow:0 8px 40px rgba(0,0,0,0.2);
                animation:trrFadeUp .18s ease;">

        {{-- Header --}}
        <div style="display:flex; align-items:center; gap:12px; margin-bottom:20px;">
            <div style="width:44px; height:44px; border-radius:10px; background:#d4edda;
                        display:flex; align-items:center; justify-content:center;
                        font-size:22px; flex-shrink:0;">✓</div>
            <div>
                <div style="font-size:16px; font-weight:600; color:#2c3e50;">Konfirmasi ACC Dana TRR</div>
                <div style="font-size:13px; color:#7f8c8d; margin-top:2px;">Periksa kembali detail sebelum menyetujui</div>
            </div>
        </div>

        {{-- Info ringkas --}}
        <div style="background:#f8f9fa; border-radius:8px; padding:9px 12px;
                    display:flex; justify-content:space-between; margin-bottom:8px; font-size:13px;">
            <span style="color:#7f8c8d;">Petugas</span>
            <span style="font-weight:600; color:#2c3e50;">{{ $petugas->name ?? '-' }}</span>
        </div>
        <div style="background:#f8f9fa; border-radius:8px; padding:9px 12px;
                    display:flex; justify-content:space-between; margin-bottom:8px; font-size:13px;">
            <span style="color:#7f8c8d;">Nasabah</span>
            <span style="font-weight:600; color:#2c3e50;">{{ $nasabah->nama_nasabah ?? '-' }}</span>
        </div>

        <div style="height:1px; background:#f0f0f0; margin:12px 0;"></div>

        <div style="background:#f8f9fa; border-radius:8px; padding:9px 12px;
                    display:flex; justify-content:space-between; margin-bottom:8px; font-size:13px;">
            <span style="color:#7f8c8d;">Nominal diajukan</span>
            <span style="font-weight:600; color:#7f8c8d;">
                Rp {{ number_format($pengajuan->nominal_diajukan, 0, ',', '.') }}
            </span>
        </div>
        <div style="background:#e8f4fd; border-radius:8px; padding:12px 14px;
                    display:flex; justify-content:space-between; align-items:center; margin-bottom:8px;">
            <div>
                <div style="font-size:12px; color:#7f8c8d; margin-bottom:3px;">Nominal disetujui</div>
                <div style="font-size:20px; font-weight:700; color:#28a745;" id="konfirm-nominal">—</div>
            </div>
            <div style="font-size:26px; opacity:0.35;">✓</div>
        </div>
        <div id="konfirm-catatan-wrap"
             style="display:none; background:#f8f9fa; border-radius:8px; padding:9px 12px;
                    justify-content:space-between; gap:12px; margin-bottom:8px; font-size:13px;">
            <span style="color:#7f8c8d; flex-shrink:0;">Catatan</span>
            <span style="font-weight:500; color:#2c3e50; text-align:right;" id="konfirm-catatan"></span>
        </div>

        {{-- Tombol --}}
        <div style="display:flex; gap:10px; margin-top:20px;">
            <button type="button" onclick="closeModal('acc')"
                    style="flex:1; padding:11px; border:2px solid #dee2e6; border-radius:8px;
                           background:#fff; font-size:14px; font-weight:600;
                           cursor:pointer; color:#6c757d;">
                Batal, ubah lagi
            </button>
            <button type="button" onclick="document.getElementById('form-acc').submit()"
                    style="flex:1.5; padding:11px; background:#28a745; color:#fff; border:none;
                           border-radius:8px; font-size:14px; font-weight:600; cursor:pointer;"
                    onmouseover="this.style.background='#218838'"
                    onmouseout="this.style.background='#28a745'">
                ✓ Ya, ACC Sekarang
            </button>
        </div>
    </div>
</div>


{{-- ===================== MODAL TOLAK ===================== --}}
<div id="modal-tolak"
     style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.55);
            z-index:1050; align-items:center; justify-content:center;"
     onclick="if(event.target===this) closeModal('tolak')">
    <div style="background:#fff; border-radius:16px; padding:28px 28px 24px;
                width:440px; max-width:94vw;
                box-shadow:0 8px 40px rgba(0,0,0,0.2);
                animation:trrFadeUp .18s ease;">

        {{-- Header --}}
        <div style="display:flex; align-items:center; gap:12px; margin-bottom:20px;">
            <div style="width:44px; height:44px; border-radius:10px; background:#f8d7da;
                        display:flex; align-items:center; justify-content:center;
                        font-size:22px; flex-shrink:0;">✖</div>
            <div>
                <div style="font-size:16px; font-weight:600; color:#2c3e50;">Konfirmasi Penolakan TRR</div>
                <div style="font-size:13px; color:#7f8c8d; margin-top:2px;">Tindakan ini tidak dapat dibatalkan</div>
            </div>
        </div>

        {{-- Info ringkas --}}
        <div style="background:#f8f9fa; border-radius:8px; padding:9px 12px;
                    display:flex; justify-content:space-between; margin-bottom:8px; font-size:13px;">
            <span style="color:#7f8c8d;">Petugas</span>
            <span style="font-weight:600; color:#2c3e50;">{{ $petugas->name ?? '-' }}</span>
        </div>
        <div style="background:#f8f9fa; border-radius:8px; padding:9px 12px;
                    display:flex; justify-content:space-between; margin-bottom:12px; font-size:13px;">
            <span style="color:#7f8c8d;">Nominal diajukan</span>
            <span style="font-weight:600; color:#2980b9;">
                Rp {{ number_format($pengajuan->nominal_diajukan, 0, ',', '.') }}
            </span>
        </div>

        <div style="background:#fdf0f0; border-radius:8px; padding:12px 14px; margin-bottom:12px;">
            <div style="font-size:12px; color:#7f8c8d; margin-bottom:4px;">Alasan penolakan</div>
            <div style="font-size:13px; font-weight:500; color:#c0392b; line-height:1.6;"
                 id="konfirm-alasan">—</div>
        </div>

        <div style="background:#fff3cd; border-radius:8px; padding:10px 12px;
                    font-size:13px; color:#856404;">
            ⚠️ Pengajuan yang ditolak tidak dapat diubah kembali ke status pending.
        </div>

        {{-- Tombol --}}
        <div style="display:flex; gap:10px; margin-top:20px;">
            <button type="button" onclick="closeModal('tolak')"
                    style="flex:1; padding:11px; border:2px solid #dee2e6; border-radius:8px;
                           background:#fff; font-size:14px; font-weight:600;
                           cursor:pointer; color:#6c757d;">
                Batal, ubah lagi
            </button>
            <button type="button" onclick="document.getElementById('form-tolak').submit()"
                    style="flex:1.5; padding:11px; background:#dc3545; color:#fff; border:none;
                           border-radius:8px; font-size:14px; font-weight:600; cursor:pointer;"
                    onmouseover="this.style.background='#b02a37'"
                    onmouseout="this.style.background='#dc3545'">
                ✖ Ya, Tolak Sekarang
            </button>
        </div>
    </div>
</div>
@endif

@endsection

@push('scripts')
<style>
@keyframes trrFadeUp {
    from { opacity:0; transform:translateY(14px); }
    to   { opacity:1; transform:translateY(0); }
}
</style>
<script>
function previewNominal(value, targetId) {
    const el = document.getElementById(targetId);
    el.textContent = value >= 1000
        ? '= Rp ' + parseInt(value).toLocaleString('id-ID')
        : '';
}

function checkReject(textarea) {
    const len  = textarea.value.length;
    const btn  = document.getElementById('btnTolak');
    const hint = document.getElementById('rejectHint');
    hint.textContent   = len + ' / 10 karakter minimum';
    hint.style.color   = len >= 10 ? '#27ae60' : '#aaa';
    btn.disabled       = len < 10;
    btn.style.opacity  = len >= 10 ? '1' : '0.6';
    btn.style.cursor   = len >= 10 ? 'pointer' : 'not-allowed';
}

function openModalAcc() {
    const nominal = document.querySelector('input[name="nominal_disetujui"]').value;
    const catatan = document.getElementById('catatan_acc').value.trim();

    document.getElementById('konfirm-nominal').textContent =
        'Rp ' + parseInt(nominal).toLocaleString('id-ID');

    const cWrap = document.getElementById('konfirm-catatan-wrap');
    if (catatan) {
        document.getElementById('konfirm-catatan').textContent = catatan;
        cWrap.style.display = 'flex';
    } else {
        cWrap.style.display = 'none';
    }

    openModal('acc');
}

function openModalTolak() {
    const alasan = document.getElementById('alasan_tolak').value.trim();
    document.getElementById('konfirm-alasan').textContent = alasan;
    openModal('tolak');
}

function openModal(id) {
    document.getElementById('modal-' + id).style.display = 'flex';
}

function closeModal(id) {
    document.getElementById('modal-' + id).style.display = 'none';
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') { closeModal('acc'); closeModal('tolak'); }
});

document.addEventListener('DOMContentLoaded', function() {
    const inputNominal = document.querySelector('input[name="nominal_disetujui"]');
    if (inputNominal) previewNominal(inputNominal.value, 'previewAcc');
});
</script>
@endpush