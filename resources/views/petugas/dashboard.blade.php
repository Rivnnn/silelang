@extends('layouts.petugas')

@section('title', 'Pengajuan Lelang')
@section('page-title', 'Pengajuan Lelang')

@section('content')
<style>
    /* ================= CONTENT STYLES ================= */
    .welcome-box {
        background: linear-gradient(135deg, #39C6C9, #2FB3B6);
        padding: 40px;
        border-radius: 20px;
        color: #fff;
        box-shadow: 0 10px 30px rgba(57,198,201,0.3);
        margin-bottom: 35px;
    }

    .welcome-box h3 {
        font-size: 28px;
        margin-bottom: 12px;
        font-weight: 600;
    }

    .welcome-box p {
        font-size: 16px;
        line-height: 1.7;
        opacity: 0.95;
    }

    /* ================= STATS GRID ================= */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 25px;
        margin-bottom: 35px;
    }

    .stat-card {
        background: #fff;
        padding: 28px;
        border-radius: 16px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        display: flex;
        align-items: center;
        gap: 20px;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(90deg, var(--card-color), transparent);
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.12);
    }

    .stat-icon {
        width: 65px;
        height: 65px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 30px;
        background: var(--card-bg);
        color: var(--card-color);
    }

    .stat-info {
        flex: 1;
    }

    .stat-label {
        font-size: 14px;
        color: #7f8c8d;
        margin-bottom: 8px;
        font-weight: 500;
    }

    .stat-value {
        font-size: 32px;
        font-weight: 700;
        color: #2c3e50;
        line-height: 1;
    }

    .stat-card.cyan   { --card-color: #39C6C9; --card-bg: rgba(57,198,201,0.1); }
    .stat-card.blue   { --card-color: #3498db; --card-bg: rgba(52,152,219,0.1); }
    .stat-card.green  { --card-color: #27ae60; --card-bg: rgba(39,174,96,0.1); }
    .stat-card.orange { --card-color: #f39c12; --card-bg: rgba(243,156,18,0.1); }

    /* ================= TRR WIDGET ================= */
    .trr-widget {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        padding: 28px;
        margin-bottom: 35px;
    }

    .trr-widget-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .trr-widget-header h3 {
        color: #2c3e50;
        font-size: 18px;
        font-weight: 600;
        margin: 0;
    }

    .trr-widget-header a {
        font-size: 13px;
        color: #39C6C9;
        text-decoration: none;
        font-weight: 600;
        padding: 6px 14px;
        border: 2px solid #39C6C9;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .trr-widget-header a:hover {
        background: #39C6C9;
        color: #fff;
    }

    .trr-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
        gap: 16px;
    }

    .trr-card {
        border-radius: 12px;
        padding: 18px;
        position: relative;
        overflow: hidden;
        transition: transform 0.3s ease;
    }

    .trr-card:hover {
        transform: translateY(-3px);
    }

    .trr-card-label {
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
    }

    .trr-card-value {
        font-size: 17px;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 4px;
    }

    .trr-card-desc {
        font-size: 11px;
        color: #7f8c8d;
    }

    .trr-card.aktif    { background: #e8f8f5; border-left: 3px solid #39C6C9; }
    .trr-card.pakai    { background: #fef9e7; border-left: 3px solid #e67e22; }
    .trr-card.sisa-ok  { background: #eafaf1; border-left: 3px solid #27ae60; }
    .trr-card.sisa-err { background: #fdedec; border-left: 3px solid #e74c3c; }
    .trr-card.selesai  { background: #f4f6f7; border-left: 3px solid #95a5a6; }

    .trr-card.aktif    .trr-card-label { color: #0e9c8e; }
    .trr-card.pakai    .trr-card-label { color: #e67e22; }
    .trr-card.sisa-ok  .trr-card-label { color: #27ae60; }
    .trr-card.sisa-err .trr-card-label { color: #e74c3c; }
    .trr-card.selesai  .trr-card-label { color: #7f8c8d; }

    /* ================= NOTIF BANNER ================= */
    .notif-banner {
        background: #fff8e1;
        border: 1px solid #ffe082;
        border-left: 4px solid #f39c12;
        border-radius: 10px;
        padding: 14px 18px;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .notif-banner-icon { font-size: 22px; }

    .notif-banner-text strong {
        display: block;
        color: #856404;
        font-size: 14px;
        font-weight: 600;
    }

    .notif-banner-text span {
        font-size: 12px;
        color: #7f8c8d;
    }

    .notif-banner-btn {
        margin-left: auto;
        padding: 8px 16px;
        background: #f39c12;
        color: #fff;
        border-radius: 8px;
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
        white-space: nowrap;
        transition: all 0.3s ease;
    }

    .notif-banner-btn:hover {
        background: #d68910;
        transform: translateY(-2px);
    }

    /* ================= QUICK MENU ================= */
    .quick-menu {
        background: #fff;
        padding: 30px;
        border-radius: 16px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    }

    .quick-menu h3 {
        color: #39C6C9;
        margin-bottom: 25px;
        font-size: 20px;
        font-weight: 600;
    }

    .menu-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 18px;
    }

    .menu-item {
        padding: 25px 20px;
        background: linear-gradient(135deg, #f8f9fa, #e9ecef);
        border-radius: 12px;
        text-align: center;
        text-decoration: none;
        color: #2c3e50;
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }

    .menu-item:hover {
        background: #fff;
        border-color: #39C6C9;
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(57,198,201,0.2);
    }

    .menu-item span {
        font-size: 36px;
        display: block;
        margin-bottom: 12px;
    }

    .menu-item strong {
        display: block;
        font-size: 15px;
        font-weight: 600;
    }

    .menu-item small {
        display: block;
        font-size: 12px;
        color: #7f8c8d;
        margin-top: 5px;
    }

    .menu-item.highlight {
        background: linear-gradient(135deg, rgba(57,198,201,0.1), rgba(47,179,182,0.15));
        border-color: rgba(57,198,201,0.3);
    }

    .menu-item.highlight:hover {
        border-color: #39C6C9;
        box-shadow: 0 8px 20px rgba(57,198,201,0.25);
    }
</style>

{{-- WELCOME BOX — TIDAK DIUBAH --}}
<div class="welcome-box">
    <h3>Selamat Datang, {{ auth()->user()->name ?? session('name') }}! 👋</h3>
    <p>
        Anda dapat mengelola data nasabah, mengupload dokumen, membuat laporan penilaian agunan,
        dan mengajukan lelang untuk nasabah dengan status kredit macet melalui sistem SILELANG.
    </p>
</div>

{{-- STATS GRID — TIDAK DIUBAH --}}
<div class="stats-grid">
    <div class="stat-card cyan">
        <div class="stat-icon">👥</div>
        <div class="stat-info">
            <div class="stat-label">Nasabah Terdaftar</div>
            <div class="stat-value">{{ $stats['total_nasabah'] ?? 0 }}</div>
        </div>
    </div>

    <div class="stat-card blue">
        <div class="stat-icon">📄</div>
        <div class="stat-info">
            <div class="stat-label">Total Surat</div>
            <div class="stat-value">{{ $stats['total_surat'] ?? 0 }}</div>
        </div>
    </div>

    <div class="stat-card green">
        <div class="stat-icon">📊</div>
        <div class="stat-info">
            <div class="stat-label">Laporan LPA</div>
            <div class="stat-value">{{ $stats['total_lpa'] ?? 0 }}</div>
        </div>
    </div>

    <div class="stat-card orange">
        <div class="stat-icon">⚖️</div>
        <div class="stat-info">
            <div class="stat-label">Pengajuan Lelang</div>
            <div class="stat-value">{{ $stats['total_pengajuan'] ?? 0 }}</div>
        </div>
    </div>
</div>

{{-- ===== BAGIAN BARU: NOTIFIKASI + WIDGET TRR ===== --}}

{{-- Banner notifikasi jika ada TRR menunggu konfirmasi --}}
@if(isset($trr) && $trr['menunggu_konfirmasi'] > 0)
<div class="notif-banner">
    <div class="notif-banner-icon">💰</div>
    <div class="notif-banner-text">
        <strong>
            Ada {{ $trr['menunggu_konfirmasi'] }} Dana TRR menunggu konfirmasi Anda!
        </strong>
        <span>Segera konfirmasi penerimaan dana agar buku kas bisa digunakan.</span>
    </div>
    <a href="{{ route('petugas.dana-trr.index') }}" class="notif-banner-btn">
        Lihat Sekarang →
    </a>
</div>
@endif

{{-- Widget ringkasan TRR --}}
@if(isset($trr))
<div class="trr-widget">
    <div class="trr-widget-header">
        <h3>💰 Ringkasan Dana TRR Saya</h3>
        <a href="{{ route('petugas.dana-trr.index') }}">Lihat Detail →</a>
    </div>

    <div class="trr-grid">

        <div class="trr-card aktif">
            <div class="trr-card-label">TRR Aktif</div>
            <div class="trr-card-value">
                Rp {{ number_format($trr['total_aktif'], 0, ',', '.') }}
            </div>
            <div class="trr-card-desc">Dana sedang berjalan</div>
        </div>

        <div class="trr-card pakai">
            <div class="trr-card-label">Total Dipakai</div>
            <div class="trr-card-value">
                Rp {{ number_format($trr['total_realisasi'], 0, ',', '.') }}
            </div>
            <div class="trr-card-desc">Total realisasi pengeluaran</div>
        </div>

        <div class="trr-card {{ $trr['selisih'] >= 0 ? 'sisa-ok' : 'sisa-err' }}">
            <div class="trr-card-label">Sisa Dana</div>
            <div class="trr-card-value"
                 style="color: {{ $trr['selisih'] >= 0 ? '#27ae60' : '#e74c3c' }}">
                Rp {{ number_format(abs($trr['selisih']), 0, ',', '.') }}
            </div>
            <div class="trr-card-desc">
                {{ $trr['selisih'] < 0 ? '⚠️ Melebihi dana cair' : 'Saldo yang masih dipegang' }}
            </div>
        </div>

        <div class="trr-card selesai">
            <div class="trr-card-label">Sudah Di-LPJ</div>
            <div class="trr-card-value">
                Rp {{ number_format($trr['total_selesai'], 0, ',', '.') }}
            </div>
            <div class="trr-card-desc">Dana yang sudah selesai</div>
        </div>

    </div>
</div>
@endif

{{-- ===== AKHIR BAGIAN BARU ===== --}}

{{-- QUICK MENU — ditambah 1 item Dana TRR --}}
<div class="quick-menu">
    <h3>Menu Cepat</h3>
    <div class="menu-grid">

        {{-- Item lama — TIDAK DIUBAH --}}
        <a href="/petugas/data-nasabah" class="menu-item">
            <span>👤</span>
            <strong>Data Nasabah</strong>
            <small>Kelola data nasabah</small>
        </a>

        <a href="/petugas/upload-dokumen" class="menu-item">
            <span>📤</span>
            <strong>Upload Dokumen</strong>
            <small>Upload dokumen persyaratan</small>
        </a>

        <a href="/petugas/lpa" class="menu-item">
            <span>📊</span>
            <strong>Buat LPA</strong>
            <small>Laporan Penilaian Agunan</small>
        </a>

        <a href="/petugas/nomor-surat" class="menu-item">
            <span>📄</span>
            <strong>Nomor Surat</strong>
            <small>Generate nomor surat</small>
        </a>

        <a href="/petugas/pengajuan-lelang" class="menu-item">
            <span>⚖️</span>
            <strong>Ajukan Lelang</strong>
            <small>Pengajuan lelang nasabah</small>
        </a>

        {{-- Item baru Dana TRR --}}
        <a href="{{ route('petugas.dana-trr.index') }}" class="menu-item highlight">
            <span>💰</span>
            <strong>Dana TRR</strong>
            <small>Kelola dana & buku kas</small>
        </a>

    </div>
</div>

@endsection

@push('scripts')
<script>
    {{-- Script lama — TIDAK DIUBAH --}}
    function toggleForm() {
        const form = document.getElementById('formPengajuan');
        if(form) {
            form.style.display = form.style.display === 'none' ? 'block' : 'none';
        }
    }
</script>
@endpush