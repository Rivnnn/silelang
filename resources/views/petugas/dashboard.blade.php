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

    .stat-card.cyan { --card-color: #39C6C9; --card-bg: rgba(57,198,201,0.1); }
    .stat-card.blue { --card-color: #3498db; --card-bg: rgba(52,152,219,0.1); }
    .stat-card.green { --card-color: #27ae60; --card-bg: rgba(39,174,96,0.1); }
    .stat-card.orange { --card-color: #f39c12; --card-bg: rgba(243,156,18,0.1); }

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
</style>

<div class="welcome-box">
    <h3>Selamat Datang, {{ session('name') }}! 👋</h3>
    <p>
        Anda dapat mengelola data nasabah, mengupload dokumen, membuat laporan penilaian agunan, 
        dan mengajukan lelang untuk nasabah dengan status kredit macet melalui sistem SILELANG.
    </p>
</div>

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

<div class="quick-menu">
    <h3>Menu Cepat</h3>
    <div class="menu-grid">
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
    </div>
</div>
@endsection

@push('scripts')
<script>
function toggleForm() {
    const form = document.getElementById('formPengajuan');
    if(form) {
        form.style.display = form.style.display === 'none' ? 'block' : 'none';
    }
}
</script>
@endpush