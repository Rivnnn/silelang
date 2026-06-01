@extends('layouts.admin')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard Administrator')

@section('content')
<style>
    /* ================= WELCOME BOX ================= */
    .welcome-box {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 35px;
        border-radius: 16px;
        color: #fff;
        box-shadow: 0 8px 25px rgba(102,126,234,0.25);
        margin-bottom: 30px;
    }

    .welcome-box h3 {
        font-size: 26px;
        margin-bottom: 10px;
        font-weight: 600;
    }

    .welcome-box p {
        font-size: 15px;
        line-height: 1.6;
        opacity: 0.95;
    }

    /* ================= STATS GRID ================= */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: #fff;
        padding: 24px;
        border-radius: 14px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.06);
        display: flex;
        align-items: center;
        gap: 18px;
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
        height: 3px;
        background: linear-gradient(90deg, var(--card-color), transparent);
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        background: var(--card-bg);
        color: var(--card-color);
        flex-shrink: 0;
    }

    .stat-info {
        flex: 1;
    }

    .stat-label {
        font-size: 13px;
        color: #7f8c8d;
        margin-bottom: 6px;
        font-weight: 500;
    }

    .stat-value {
        font-size: 28px;
        font-weight: 700;
        color: #2c3e50;
        line-height: 1;
    }

    /* Stat Card Colors */
    .stat-card.blue {
        --card-color: #3498db;
        --card-bg: rgba(52,152,219,0.1);
    }

    .stat-card.green {
        --card-color: #27ae60;
        --card-bg: rgba(39,174,96,0.1);
    }

    .stat-card.orange {
        --card-color: #f39c12;
        --card-bg: rgba(243,156,18,0.1);
    }

    .stat-card.purple {
        --card-color: #9b59b6;
        --card-bg: rgba(155,89,182,0.1);
    }

    /* ================= QUICK ACTIONS ================= */
    .quick-actions {
        background: #fff;
        padding: 28px;
        border-radius: 14px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.06);
    }

    .quick-actions h3 {
        color: #2c3e50;
        margin-bottom: 20px;
        font-size: 19px;
        font-weight: 600;
    }

    .actions-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 16px;
    }

    .action-btn {
        padding: 20px 18px;
        background: #f8f9fa;
        border-radius: 12px;
        text-align: center;
        text-decoration: none;
        color: #2c3e50;
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }

    .action-btn:hover {
        background: #fff;
        border-color: #3498db;
        transform: translateY(-3px);
        box-shadow: 0 4px 12px rgba(52,152,219,0.15);
    }

    .action-btn span {
        font-size: 30px;
        display: block;
        margin-bottom: 10px;
    }

    .action-btn strong {
        display: block;
        font-size: 14px;
        font-weight: 600;
    }
</style>

<!-- Welcome Box -->
<div class="welcome-box">
    <h3>Selamat Datang, {{ session('name') }}! 👋</h3>
    <p>
        Anda memiliki akses penuh untuk monitoring seluruh data lelang, 
        mengelola petugas, dan memantau arsip surat-menyurat di sistem SILELANG.
    </p>
</div>

<!-- Stats Cards -->
<div class="stats-grid">
    <div class="stat-card blue">
        <div class="stat-icon">👥</div>
        <div class="stat-info">
            <div class="stat-label">Total Petugas</div>
            <div class="stat-value">{{ $stats['total_petugas'] ?? 0 }}</div>
        </div>
    </div>

    <div class="stat-card green">
        <div class="stat-icon">📋</div>
        <div class="stat-info">
            <div class="stat-label">Total Nasabah</div>
            <div class="stat-value">{{ $stats['total_nasabah'] ?? 0 }}</div>
        </div>
    </div>

    <div class="stat-card orange">
        <div class="stat-icon">⚠️</div>
        <div class="stat-info">
            <div class="stat-label">Pengajuan Pending</div>
            <div class="stat-value">{{ $stats['total_pengajuan'] ?? 0 }}</div>
        </div>
    </div>

    <div class="stat-card purple">
        <div class="stat-icon">📄</div>
        <div class="stat-info">
            <div class="stat-label">Total Surat</div>
            <div class="stat-value">{{ $stats['total_surat'] ?? 0 }}</div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="quick-actions">
    <h3>Akses Cepat</h3>
    <div class="actions-grid">
        <a href="/admin/monitoring-nasabah" class="action-btn">
            <span>👥</span>
            <strong>Lihat Semua Nasabah</strong>
        </a>
        <a href="/admin/manajemen-user" class="action-btn">
            <span>⚙️</span>
            <strong>Kelola Petugas</strong>
        </a>
        <a href="/admin/monitoring-lelang" class="action-btn">
            <span>⚖️</span>
            <strong>Review Pengajuan</strong>
        </a>
        <a href="/admin/monitoring-surat" class="action-btn">
            <span>📄</span>
            <strong>Arsip Surat</strong>
        </a>
    </div>
</div>
@endsection