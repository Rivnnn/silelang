@extends('layouts.petugas')

@section('title', 'Dokumen Nasabah')
@section('page-title', 'Dokumen Nasabah')

@php
    $showBackButton = true;
    $backUrl = route('petugas.nasabah.index');
@endphp

@section('content')
<style>
    /* ========================================
       PREMIUM DESIGN SYSTEM
    ======================================== */
    
    :root {
        --primary: #39C6C9;
        --primary-hover: #2FB3B6;
        --text-primary: #0F172A;
        --text-secondary: #64748B;
        --text-muted: #94A3B8;
        --bg-primary: #FFFFFF;
        --bg-secondary: #F8FAFC;
        --bg-tertiary: #F1F5F9;
        --border-light: #E2E8F0;
        --border-medium: #CBD5E1;
        --success-bg: #ECFDF5;
        --success-text: #065F46;
        --success-border: #6EE7B7;
        --warning-bg: #FEF3C7;
        --warning-text: #92400E;
        --warning-border: #FCD34D;
        --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
        --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1);
        --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1);
        --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1);
    }

    * {
        scrollbar-width: thin;
        scrollbar-color: var(--primary) var(--bg-tertiary);
    }

    *::-webkit-scrollbar {
        width: 6px;
        height: 6px;
    }

    *::-webkit-scrollbar-track {
        background: var(--bg-tertiary);
        border-radius: 10px;
    }

    *::-webkit-scrollbar-thumb {
        background: var(--primary);
        border-radius: 10px;
    }

    /* ========================================
       CONTAINER
    ======================================== */
    
    .container-fluid {
        max-width: 1400px;
        margin: 0 auto;
    }

    /* ========================================
       INFO HEADER - ELEGANT GRADIENT
    ======================================== */
    
    .info-header {
        background: linear-gradient(135deg, #39C6C9 0%, #2FB3B6 100%);
        padding: 32px 40px;
        border-radius: 16px;
        margin-bottom: 32px;
        color: white;
        box-shadow: var(--shadow-lg);
        position: relative;
        overflow: hidden;
    }

    .info-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 300px;
        height: 300px;
        background: rgba(255, 255, 255, 0.08);
        border-radius: 50%;
    }

    .info-header::after {
        content: '';
        position: absolute;
        bottom: -30%;
        left: -5%;
        width: 200px;
        height: 200px;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 50%;
    }

    .info-content {
        position: relative;
        z-index: 1;
    }

    .info-label {
        font-size: 13px;
        font-weight: 600;
        opacity: 0.9;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .info-value {
        font-size: 28px;
        font-weight: 700;
        margin-bottom: 6px;
        letter-spacing: -0.02em;
    }

    .info-subtitle {
        font-size: 14px;
        opacity: 0.85;
        font-weight: 500;
    }

    /* ========================================
       STATS CARDS - QUICK OVERVIEW
    ======================================== */
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 32px;
    }

    .stat-card {
        background: var(--bg-primary);
        border: 1px solid var(--border-light);
        border-radius: 12px;
        padding: 20px 24px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
        border-color: var(--primary);
    }

    .stat-label {
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--text-muted);
        margin-bottom: 8px;
    }

    .stat-value {
        font-size: 36px;
        font-weight: 700;
        color: var(--text-primary);
        line-height: 1;
        margin-bottom: 4px;
    }

    .stat-description {
        font-size: 13px;
        color: var(--text-secondary);
    }

    .stat-card.complete .stat-value {
        color: #059669;
    }

    .stat-card.incomplete .stat-value {
        color: #D97706;
    }

    /* ========================================
       MAIN CARD
    ======================================== */
    
    .card {
        background: var(--bg-primary);
        border-radius: 16px;
        border: 1px solid var(--border-light);
        box-shadow: var(--shadow-sm);
        margin-bottom: 24px;
        overflow: hidden;
    }

    .card-header {
        padding: 24px 28px;
        border-bottom: 1px solid var(--border-light);
        background: var(--bg-secondary);
    }

    .card-title {
        font-size: 20px;
        font-weight: 700;
        color: var(--text-primary);
        letter-spacing: -0.02em;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .card-body {
        padding: 0;
    }

    /* ========================================
       TABLE - PRECISION DATA DISPLAY
    ======================================== */
    
    .table-container {
        overflow-x: auto;
    }

    .table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .table thead {
        background: var(--bg-secondary);
    }

    .table th {
        padding: 16px 24px;
        text-align: left;
        font-size: 12px;
        font-weight: 700;
        color: var(--text-secondary);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 1px solid var(--border-light);
        white-space: nowrap;
    }

    .table th:first-child {
        text-align: center;
        width: 70px;
    }

    .table th:last-child {
        text-align: center;
        width: 140px;
    }

    .table td {
        padding: 20px 24px;
        font-size: 14px;
        color: var(--text-primary);
        border-bottom: 1px solid var(--border-light);
        vertical-align: middle;
    }

    .table tbody tr {
        transition: background-color 0.15s;
    }

    .table tbody tr:hover {
        background: var(--bg-secondary);
    }

    .table tbody tr:last-child td {
        border-bottom: none;
    }

    /* ========================================
       TABLE CELLS - SPECIALIZED
    ======================================== */
    
    .cell-number {
        text-align: center;
        color: var(--text-muted);
        font-weight: 600;
        font-size: 13px;
    }

    .cell-dokumen {
        font-weight: 600;
        color: var(--text-primary);
    }

    .cell-action {
        text-align: center;
    }

    .cell-status {
        text-align: center;
    }

    /* ========================================
       BADGES & STATUS INDICATORS
    ======================================== */
    
    .badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        letter-spacing: 0.02em;
        white-space: nowrap;
    }

    .badge-success {
        background: var(--success-bg);
        color: var(--success-text);
        border: 1px solid var(--success-border);
    }

    .badge-warning {
        background: var(--warning-bg);
        color: var(--warning-text);
        border: 1px solid var(--warning-border);
    }

    .badge-icon {
        font-size: 14px;
        line-height: 1;
    }

    /* ========================================
       BUTTONS - REFINED ACTIONS
    ======================================== */
    
    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 8px 16px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        text-decoration: none;
        white-space: nowrap;
    }

    .btn-primary {
        background: var(--primary);
        color: white;
        box-shadow: var(--shadow-sm);
    }

    .btn-primary:hover {
        background: var(--primary-hover);
        transform: translateY(-1px);
        box-shadow: var(--shadow-md);
    }

    .btn-outline {
        background: white;
        color: var(--text-primary);
        border: 1.5px solid var(--border-medium);
    }

    .btn-outline:hover {
        border-color: var(--primary);
        color: var(--primary);
        background: rgba(57, 198, 201, 0.05);
    }

    .btn-disabled {
        background: var(--bg-tertiary);
        color: var(--text-muted);
        cursor: not-allowed;
        border: 1px solid var(--border-light);
    }

    .btn-disabled:hover {
        transform: none;
        box-shadow: none;
    }

    .btn-icon {
        font-size: 16px;
        line-height: 1;
    }

    /* ========================================
       PROGRESS BAR
    ======================================== */
    
    .progress-section {
        background: var(--bg-primary);
        border: 1px solid var(--border-light);
        border-radius: 12px;
        padding: 20px 24px;
        margin-bottom: 32px;
    }

    .progress-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
    }

    .progress-label {
        font-size: 14px;
        font-weight: 600;
        color: var(--text-primary);
    }

    .progress-percentage {
        font-size: 20px;
        font-weight: 700;
        color: var(--primary);
    }

    .progress-bar-wrapper {
        background: var(--bg-tertiary);
        height: 10px;
        border-radius: 10px;
        overflow: hidden;
    }

    .progress-bar {
        height: 100%;
        background: linear-gradient(90deg, #39C6C9 0%, #2FB3B6 100%);
        border-radius: 10px;
        transition: width 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
    }

    .progress-bar::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
        animation: shimmer 2s infinite;
    }

    @keyframes shimmer {
        0% { transform: translateX(-100%); }
        100% { transform: translateX(100%); }
    }

    /* ========================================
       EMPTY STATE
    ======================================== */
    
    .empty-message {
        text-align: center;
        padding: 20px;
        color: var(--text-muted);
        font-size: 14px;
        font-style: italic;
    }

    /* ========================================
       RESPONSIVE DESIGN
    ======================================== */
    
    @media (max-width: 768px) {
        .info-header {
            padding: 24px 20px;
        }

        .info-value {
            font-size: 24px;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }

        .table th,
        .table td {
            padding: 12px 16px;
            font-size: 13px;
        }

        .stat-value {
            font-size: 28px;
        }
    }

    /* ========================================
       UTILITIES
    ======================================== */
    
    .text-center { text-align: center; }
    .font-semibold { font-weight: 600; }
    .font-bold { font-weight: 700; }
</style>

<div class="container-fluid">
    <!-- Info Header -->
    <div class="info-header">
        <div class="info-content">
            <div class="info-label">
                <span>👤</span>
                <span>Informasi Nasabah</span>
            </div>
            <div class="info-value">{{ $nasabah->nama_nasabah }}</div>
            <div class="info-subtitle">NIK: {{ $nasabah->nik }} • {{ $nasabah->jenis_lelang }}</div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid">
        @php
            $totalDokumen = count($dokumenWajib);
            $dokumenLengkap = $dokumen->filter(fn($d) => !empty($d->link_dokumen))->count();
            $dokumenKosong = $totalDokumen - $dokumenLengkap;
            $persentase = $totalDokumen > 0 ? round(($dokumenLengkap / $totalDokumen) * 100) : 0;
        @endphp

        <div class="stat-card complete">
            <div class="stat-label">Dokumen Lengkap</div>
            <div class="stat-value">{{ $dokumenLengkap }}</div>
            <div class="stat-description">dari {{ $totalDokumen }} dokumen</div>
        </div>

        <div class="stat-card incomplete">
            <div class="stat-label">Belum Lengkap</div>
            <div class="stat-value">{{ $dokumenKosong }}</div>
            <div class="stat-description">dokumen tersisa</div>
        </div>

        <div class="stat-card">
            <div class="stat-label">Progress</div>
            <div class="stat-value" style="color: var(--primary)">{{ $persentase }}%</div>
            <div class="stat-description">kelengkapan data</div>
        </div>
    </div>

    <!-- Progress Bar -->
    <div class="progress-section">
        <div class="progress-header">
            <span class="progress-label">Progress Kelengkapan Dokumen</span>
            <span class="progress-percentage">{{ $persentase }}%</span>
        </div>
        <div class="progress-bar-wrapper">
            <div class="progress-bar" style="width: {{ $persentase }}%"></div>
        </div>
    </div>

    <!-- Documents Table -->
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">
                <span>📄</span>
                <span>Daftar Dokumen Persyaratan</span>
            </h2>
        </div>
        
        <div class="card-body">
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Dokumen</th>
                            <th>Link Dokumen</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($dokumenWajib as $i => $nama)
                        <tr>
                            <td class="cell-number">{{ $i + 1 }}</td>
                            <td class="cell-dokumen">{{ $nama }}</td>
                            <td class="cell-action">
                                @if(isset($dokumen[$nama]) && $dokumen[$nama]->link_dokumen)
                                    <a href="{{ $dokumen[$nama]->link_dokumen }}" 
                                       target="_blank" 
                                       class="btn btn-primary"
                                       rel="noopener noreferrer">
                                        <span class="btn-icon">🔗</span>
                                        <span>Lihat Dokumen</span>
                                    </a>
                                @else
                                    <button class="btn btn-disabled" disabled>
                                        <span class="btn-icon">📭</span>
                                        <span>Belum Tersedia</span>
                                    </button>
                                @endif
                            </td>
                            <td class="cell-status">
                                @if(isset($dokumen[$nama]) && $dokumen[$nama]->link_dokumen)
                                    <span class="badge badge-success">
                                        <span class="badge-icon">✓</span>
                                        <span>Lengkap</span>
                                    </span>
                                @else
                                    <span class="badge badge-warning">
                                        <span class="badge-icon">○</span>
                                        <span>Belum Ada</span>
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4">
                                <div class="empty-message">
                                    Tidak ada data dokumen yang tersedia
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div style="display: flex; gap: 12px; justify-content: flex-end; margin-top: 24px;">
        <a href="{{ route('petugas.dokumen.form', $nasabah->id) }}" class="btn btn-primary">
            <span class="btn-icon">📤</span>
            <span>Upload Dokumen</span>
        </a>
        <a href="{{ route('petugas.nasabah.index') }}" class="btn btn-outline">
            <span class="btn-icon">←</span>
            <span>Kembali ke Daftar</span>
        </a>
    </div>
</div>

@push('scripts')
<script>
// Animate progress bar on load
document.addEventListener('DOMContentLoaded', function() {
    const progressBar = document.querySelector('.progress-bar');
    const targetWidth = progressBar.style.width;
    
    progressBar.style.width = '0%';
    
    setTimeout(() => {
        progressBar.style.width = targetWidth;
    }, 300);
});

// Add tooltip for long document names
document.querySelectorAll('.cell-dokumen').forEach(cell => {
    if (cell.textContent.length > 30) {
        cell.title = cell.textContent;
    }
});
</script>
@endpush
@endsection