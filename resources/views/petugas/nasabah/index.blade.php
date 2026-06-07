@extends('layouts.petugas')

@section('title', 'Data Nasabah')
@section('page-title', 'Data Nasabah')
@section('breadcrumb')
<div class="breadcrumb">
    <a href="{{ route('petugas.dashboard') }}">Dashboard</a>
    <span>›</span>
    <span>Nasabah</span>
</div>
@endsection

@push('styles')
<style>
/* ============================================================
   VARIABLES (inherit dari layout)
   Tambahan lokal untuk halaman ini
============================================================ */

/* ============================================================
   ALERTS
============================================================ */
.alert {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 13px 16px;
    border-radius: var(--radius-md);
    margin-bottom: 20px;
    font-size: 13.5px;
    font-weight: 500;
    animation: slideDown 0.3s ease;
}

@keyframes slideDown {
    from { opacity:0; transform:translateY(-6px); }
    to   { opacity:1; transform:translateY(0); }
}

.alert-success { background:#EDFAF5; color:#0D6E48; border-left:4px solid #27AE60; }
.alert-error   { background:#FEF2F2; color:#991B1B; border-left:4px solid #E74C3C; }

.alert-icon {
    width: 22px; height: 22px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0; font-size: 11px; font-weight: 700;
}

.alert-success .alert-icon { background:#27AE60; color:#fff; }
.alert-error   .alert-icon { background:#E74C3C; color:#fff; }

/* ============================================================
   SUMMARY CARDS
============================================================ */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
    gap: 14px;
    margin-bottom: 24px;
}

.stat-card {
    background: var(--white);
    border-radius: var(--radius-lg);
    padding: 18px 20px;
    display: flex;
    align-items: center;
    gap: 14px;
    box-shadow: var(--shadow-sm);
    border: 1px solid var(--border);
    transition: transform 0.2s, box-shadow 0.2s;
}

.stat-card:hover { transform: translateY(-2px); box-shadow: var(--shadow-md); }

.stat-icon {
    width: 46px; height: 46px; border-radius: 13px;
    display: flex; align-items: center; justify-content: center;
    font-size: 22px; flex-shrink: 0;
}

.stat-value { font-size: 26px; font-weight: 800; color: var(--navy); line-height: 1; margin-bottom: 3px; }
.stat-label { font-size: 11px; color: var(--muted); font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }

/* ============================================================
   MAIN PANEL
============================================================ */
.panel {
    background: var(--white);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-sm);
    border: 1px solid var(--border);
    overflow: hidden;
    margin-bottom: 20px;
}

.panel-header {
    padding: 18px 22px;
    border-bottom: 1px solid var(--border);
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    flex-wrap: wrap;
}

.panel-header-left { display: flex; align-items: center; gap: 10px; }

.panel-header h3 { font-size: 16px; font-weight: 700; color: var(--navy); margin: 0; }

.count-badge {
    background: var(--brand-light);
    color: var(--brand-deeper);
    font-size: 12px;
    font-weight: 700;
    padding: 4px 12px;
    border-radius: 20px;
}

.btn-add {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 10px 20px;
    background: linear-gradient(135deg, var(--brand), var(--brand-dark));
    color: #fff; border: none; border-radius: var(--radius-sm);
    font-size: 14px; font-weight: 700; cursor: pointer;
    font-family: var(--font); text-decoration: none;
    transition: all 0.25s;
    box-shadow: 0 4px 14px rgba(57,198,201,0.35);
}

.btn-add:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(57,198,201,0.45); color: #fff; text-decoration: none; }

/* ============================================================
   TOOLBAR
============================================================ */
.toolbar {
    display: flex; align-items: center; gap: 8px;
    padding: 14px 18px; border-bottom: 1px solid var(--border);
    flex-wrap: wrap;
}

.search-wrap {
    position: relative; flex: 1;
    min-width: 200px; max-width: 320px;
}

.search-icon {
    position: absolute; left: 11px; top: 50%;
    transform: translateY(-50%); color: var(--muted);
    font-size: 14px; pointer-events: none;
}

.search-input {
    width: 100%; padding: 9px 10px 9px 34px;
    border: 1.5px solid var(--border); border-radius: var(--radius-sm);
    font-size: 13px; font-family: var(--font); color: var(--navy);
    background: #FAFBFC; outline: none; transition: border-color 0.2s;
}

.search-input:focus { border-color: var(--brand); background: #fff; }

.filter-select {
    padding: 9px 30px 9px 12px;
    border: 1.5px solid var(--border); border-radius: var(--radius-sm);
    font-size: 13px; font-family: var(--font); color: var(--slate);
    background: #FAFBFC; outline: none; cursor: pointer; appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%238A94A6' stroke-width='2'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
    background-repeat: no-repeat; background-position: right 9px center;
    transition: border-color 0.2s;
}

.filter-select:focus { border-color: var(--brand); }

.toolbar-btn {
    padding: 9px 16px; border-radius: var(--radius-sm);
    font-size: 13px; font-weight: 600; font-family: var(--font);
    cursor: pointer; transition: all 0.18s; white-space: nowrap;
}

.btn-search { background: var(--brand); color: #fff; border: none; }
.btn-search:hover { background: var(--brand-dark); }

.btn-reset {
    background: #fff; color: var(--muted);
    border: 1.5px solid var(--border);
    text-decoration: none; display: inline-flex; align-items: center; gap: 4px;
}

.btn-reset:hover { border-color: #b0b8c4; color: var(--slate); text-decoration: none; }

.toolbar-spacer { flex: 1; }

.result-badge {
    font-size: 12px; color: var(--muted);
    background: #F4F6F9; padding: 4px 12px;
    border-radius: 20px; white-space: nowrap;
}

/* ============================================================
   DESKTOP TABLE
============================================================ */
.table-view {}
.table-wrap { overflow-x: auto; -webkit-overflow-scrolling: touch; }

.nasabah-table {
    width: 100%; border-collapse: collapse; min-width: 700px;
}

.nasabah-table thead tr {
    background: #F8FAFC; border-bottom: 2px solid var(--border);
}

.nasabah-table th {
    padding: 12px 16px; text-align: left;
    font-size: 11px; font-weight: 700; color: var(--muted);
    text-transform: uppercase; letter-spacing: 0.7px; white-space: nowrap;
}

.nasabah-table th.center { text-align: center; }

.nasabah-table tbody tr {
    border-bottom: 1px solid #F0F3F7;
    transition: background 0.15s, transform 0.15s;
}

.nasabah-table tbody tr:hover { background: #F7FCFC; }
.nasabah-table tbody tr:last-child { border-bottom: none; }

.nasabah-table td { padding: 14px 16px; vertical-align: middle; font-size: 13px; }
.nasabah-table td.center { text-align: center; }

.row-num { color: #CBD2DC; font-size: 12px; font-weight: 700; text-align: center; }

.cell-nama strong { display: block; font-size: 14px; font-weight: 700; color: var(--navy); margin-bottom: 2px; }
.cell-nama .lokasi { display: flex; align-items: center; gap: 4px; font-size: 11.5px; color: var(--muted); }

.nik-pill {
    font-family: 'Courier New', monospace; font-size: 11.5px; letter-spacing: 0.5px;
    color: var(--slate); background: #F4F6F9; padding: 4px 9px;
    border-radius: 6px; white-space: nowrap; cursor: pointer;
    border: 1px solid var(--border); transition: background 0.15s;
    user-select: none;
}

.nik-pill:hover { background: #E8ECF2; }

/* Jenis lelang badges */
.jenis-badge {
    display: inline-block; padding: 4px 11px;
    border-radius: 20px; font-size: 11.5px; font-weight: 600; white-space: nowrap;
}

.jenis-tanah           { background: #EAF3DE; color: #3B6D11; }
.jenis-bangunan        { background: #E8F4FD; color: #185FA5; }
.jenis-tanah-bangunan  { background: #E6F9F9; color: #0F6E56; }
.jenis-eksekusi-ht     { background: #FAEEDA; color: #854F0B; }
.jenis-eksekusi-pn     { background: #FCEBEB; color: #A32D2D; }
.jenis-sukarela        { background: #EEEDFE; color: #534AB7; }

/* Dokumen progress */
.doc-cell { display: flex; flex-direction: column; align-items: center; gap: 3px; min-width: 72px; }
.doc-fraction { font-size: 12.5px; font-weight: 700; }
.doc-fraction .total { font-weight: 400; color: var(--muted); }
.doc-bar-wrap { width: 62px; height: 5px; background: #E8ECF2; border-radius: 3px; overflow: hidden; }
.doc-bar-fill { height: 100%; border-radius: 3px; transition: width 0.6s ease; }
.doc-pct { font-size: 10.5px; font-weight: 700; color: var(--muted); }

/* LPA badge */
.lpa-badge {
    display: inline-flex; align-items: center; justify-content: center;
    min-width: 32px; height: 32px; border-radius: var(--radius-sm);
    font-size: 13px; font-weight: 700;
}

.lpa-badge.has  { background: #EEEDFE; color: #534AB7; }
.lpa-badge.none { background: #F4F6F9; color: #CBD2DC; }

/* Action buttons */
.action-group { display: flex; gap: 5px; align-items: center; justify-content: center; }

.btn-tbl {
    display: inline-flex; align-items: center; gap: 4px;
    padding: 6px 12px; border-radius: 7px; font-size: 12px; font-weight: 600;
    text-decoration: none; transition: all 0.18s; white-space: nowrap;
    border: 1.5px solid transparent;
}

.btn-tbl-doc { background: #E8F9FA; color: #17A2B8; border-color: #B8EDF2; }
.btn-tbl-doc:hover { background: #17A2B8; color: #fff; border-color: #17A2B8; text-decoration: none; }

.btn-tbl-lpa { background: #EEEDFE; color: #6F42C1; border-color: #D3C8F5; }
.btn-tbl-lpa:hover { background: #6F42C1; color: #fff; border-color: #6F42C1; text-decoration: none; }

/* ============================================================
   MOBILE CARD LIST
============================================================ */
.card-list { display: none; }

.nasabah-card {
    background: var(--white); border-radius: var(--radius-lg);
    border: 1px solid var(--border); padding: 15px; margin-bottom: 10px;
    box-shadow: var(--shadow-sm); transition: box-shadow 0.2s;
    animation: fadeIn 0.2s ease;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(5px); }
    to   { opacity: 1; transform: translateY(0); }
}

.nasabah-card:hover { box-shadow: var(--shadow-md); }

.card-top {
    display: flex; align-items: flex-start;
    justify-content: space-between; gap: 10px; margin-bottom: 12px;
}

.card-avatar {
    width: 42px; height: 42px; border-radius: 12px;
    background: var(--brand-light); display: flex; align-items: center;
    justify-content: center; font-size: 14px; font-weight: 800;
    color: var(--brand-dark); flex-shrink: 0; letter-spacing: -1px;
}

.card-name { font-size: 15px; font-weight: 700; color: var(--navy); margin-bottom: 2px; }
.card-lokasi { font-size: 11.5px; color: var(--muted); display: flex; align-items: center; gap: 3px; }

.card-meta {
    display: grid; grid-template-columns: 1fr 1fr; gap: 8px;
    margin-bottom: 11px; padding: 11px;
    background: #F8FAFC; border-radius: var(--radius-sm);
}

.card-meta-label {
    font-size: 10px; font-weight: 700; color: var(--muted);
    text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 2px;
}

.card-meta-val { font-size: 12.5px; font-weight: 600; color: var(--slate); }

.card-doc-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 5px; }
.card-doc-label { font-size: 10.5px; font-weight: 700; color: var(--muted); text-transform: uppercase; letter-spacing: 0.5px; }
.card-doc-frac { font-size: 12px; font-weight: 700; }
.card-doc-bar-wrap { height: 7px; background: #E8ECF2; border-radius: 4px; overflow: hidden; margin-bottom: 11px; }
.card-doc-bar-fill { height: 100%; border-radius: 4px; transition: width 0.6s ease; }

.card-actions { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; }

.card-btn {
    display: flex; align-items: center; justify-content: center; gap: 5px;
    padding: 10px 12px; border-radius: var(--radius-sm);
    font-size: 13px; font-weight: 600; text-decoration: none; transition: all 0.18s;
}

.card-btn-doc { background: #E8F9FA; color: #17A2B8; border: 1.5px solid #B8EDF2; }
.card-btn-doc:hover { background: #17A2B8; color: #fff; text-decoration: none; }
.card-btn-lpa { background: #EEEDFE; color: #6F42C1; border: 1.5px solid #D3C8F5; }
.card-btn-lpa:hover { background: #6F42C1; color: #fff; text-decoration: none; }

/* ============================================================
   EMPTY STATE
============================================================ */
.empty-state { text-align: center; padding: 72px 24px; }
.empty-icon { font-size: 56px; margin-bottom: 14px; opacity: 0.3; }
.empty-title { font-size: 16px; font-weight: 700; color: var(--navy); margin-bottom: 7px; }
.empty-desc { font-size: 13px; color: var(--muted); line-height: 1.6; }

/* ============================================================
   NIK TOOLTIP
============================================================ */
[data-tooltip] { position: relative; }

[data-tooltip]::after {
    content: attr(data-tooltip);
    position: absolute; bottom: calc(100% + 6px); left: 50%;
    transform: translateX(-50%);
    background: var(--navy); color: #fff; padding: 4px 10px;
    border-radius: 6px; font-size: 11px; white-space: nowrap;
    opacity: 0; pointer-events: none; transition: opacity 0.15s;
    font-family: 'Courier New', monospace; letter-spacing: 1px; z-index: 10;
}

[data-tooltip]:hover::after { opacity: 1; }

/* ============================================================
   MODAL TAMBAH NASABAH
============================================================ */
.modal-overlay {
    display: none; position: fixed; inset: 0;
    background: rgba(15,31,46,0.5); backdrop-filter: blur(3px);
    z-index: 2000; align-items: center; justify-content: center;
    padding: 20px; animation: fadeOverlay 0.2s ease;
}

@keyframes fadeOverlay { from { opacity:0 } to { opacity:1 } }

.modal-overlay.active { display: flex; }

.modal-box {
    background: var(--white); border-radius: 18px;
    width: 100%; max-width: 520px; max-height: 90vh;
    overflow-y: auto; box-shadow: 0 20px 60px rgba(0,0,0,0.2);
    animation: slideUp 0.25s ease; position: relative;
}

@keyframes slideUp {
    from { opacity:0; transform:translateY(20px) scale(0.98); }
    to   { opacity:1; transform:translateY(0) scale(1); }
}

.modal-header {
    background: linear-gradient(135deg, var(--brand), var(--brand-dark));
    padding: 24px 28px 20px; position: relative;
    border-radius: 18px 18px 0 0;
}

.modal-header-title { font-size: 18px; font-weight: 800; color: #fff; margin: 0 0 3px; }
.modal-header-sub { font-size: 13px; color: rgba(255,255,255,0.75); }

.modal-close {
    position: absolute; top: 18px; right: 20px;
    width: 32px; height: 32px; border-radius: 50%;
    background: rgba(255,255,255,0.2); border: none; color: #fff;
    font-size: 16px; cursor: pointer; display: flex; align-items: center;
    justify-content: center; transition: background 0.15s; font-family: var(--font); line-height: 1;
}

.modal-close:hover { background: rgba(255,255,255,0.35); }

.modal-body { padding: 24px 28px 28px; display: flex; flex-direction: column; gap: 15px; }

.form-group { display: flex; flex-direction: column; gap: 5px; }

.form-label { font-size: 12.5px; font-weight: 700; color: var(--slate); letter-spacing: 0.2px; }
.form-label .req { color: #E74C3C; margin-left: 2px; }

.form-control {
    padding: 10px 14px; border: 1.5px solid var(--border); border-radius: var(--radius-sm);
    font-size: 13px; color: var(--navy); font-family: var(--font); outline: none;
    width: 100%; background: #FAFBFC; transition: border-color 0.2s, box-shadow 0.2s;
}

.form-control:focus {
    border-color: var(--brand);
    box-shadow: 0 0 0 3px rgba(57,198,201,0.12);
    background: #fff;
}

.form-control::placeholder { color: #C5CBD6; }

textarea.form-control { resize: vertical; min-height: 80px; line-height: 1.5; }

.form-error { font-size: 11.5px; color: #E74C3C; display: flex; align-items: center; gap: 4px; }

.form-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 13px; }

.btn-submit-modal {
    width: 100%; padding: 13px; margin-top: 4px;
    background: linear-gradient(135deg, var(--brand), var(--brand-dark));
    color: #fff; border: none; border-radius: var(--radius-md);
    font-size: 15px; font-weight: 700; cursor: pointer; font-family: var(--font);
    transition: all 0.2s; letter-spacing: 0.2px;
    box-shadow: 0 4px 16px rgba(57,198,201,0.35);
}

.btn-submit-modal:hover { transform: translateY(-1px); box-shadow: 0 6px 22px rgba(57,198,201,0.45); }

/* ============================================================
   FAB (mobile)
============================================================ */
.fab {
    display: none; position: fixed; bottom: 24px; right: 20px;
    width: 56px; height: 56px; border-radius: 50%;
    background: linear-gradient(135deg, var(--brand), var(--brand-dark));
    color: #fff; border: none; font-size: 28px; cursor: pointer;
    z-index: 500; box-shadow: 0 6px 22px rgba(57,198,201,0.5);
    align-items: center; justify-content: center;
    transition: transform 0.2s, box-shadow 0.2s;
}

.fab:hover { transform: scale(1.08); box-shadow: 0 8px 26px rgba(57,198,201,0.6); }

/* ============================================================
   RESPONSIVE
============================================================ */
@media (max-width: 768px) {
    .stats-grid { grid-template-columns: repeat(2, 1fr); gap: 10px; }
    .stat-card { padding: 13px 14px; gap: 10px; }
    .stat-icon { width: 38px; height: 38px; font-size: 18px; }
    .stat-value { font-size: 22px; }

    .table-view { display: none; }
    .card-list { display: block; padding: 12px; }

    .fab { display: flex; }
    .btn-add-desktop { display: none; }

    .toolbar { padding: 10px 12px; gap: 6px; }
    .search-wrap { min-width: 100%; max-width: 100%; order: 1; flex-basis: 100%; }
    .filter-select { flex: 1; order: 2; }
    .toolbar-btn.btn-search { order: 3; }
    .toolbar-btn.btn-reset { order: 4; }
    .toolbar-spacer { display: none; }
    .result-badge { order: 5; }

    .panel-header { flex-direction: column; align-items: flex-start; gap: 8px; }

    .modal-box { max-height: 95vh; border-radius: 16px; }
    .modal-body { padding: 16px 18px 20px; }
    .modal-header { padding: 18px 20px 16px; border-radius: 16px 16px 0 0; }
    .form-grid-2 { grid-template-columns: 1fr; }

    .pagination-wrap { flex-direction: column; align-items: flex-start; }
}
</style>
@endpush

@section('content')

{{-- ═══ ALERTS ═══ --}}
@if(session('success'))
<div class="alert alert-success" role="alert">
    <div class="alert-icon">✓</div>
    <span>{{ session('success') }}</span>
</div>
@endif
@if(session('error'))
<div class="alert alert-error" role="alert">
    <div class="alert-icon">✖</div>
    <span>{{ session('error') }}</span>
</div>
@endif

{{-- ═══ STATS ═══ --}}
@php
    $total   = $nasabah->total();
    $maxDok  = 19;
    $col     = $nasabah->getCollection();
    $lengkap = $col->filter(fn($n) => $n->jumlah_dokumen >= $maxDok)->count();
    $proses  = $col->filter(fn($n) => $n->jumlah_dokumen > 0 && $n->jumlah_dokumen < $maxDok)->count();
    $kosong  = $col->filter(fn($n) => $n->jumlah_dokumen == 0)->count();
@endphp

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon" style="background:#E6F9F9;">👥</div>
        <div>
            <div class="stat-value">{{ $total }}</div>
            <div class="stat-label">Total Nasabah</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#EAF3DE;">✅</div>
        <div>
            <div class="stat-value" style="color:#3B6D11;">{{ $lengkap }}</div>
            <div class="stat-label">Dokumen Lengkap</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#FAEEDA;">⏳</div>
        <div>
            <div class="stat-value" style="color:#854F0B;">{{ $proses }}</div>
            <div class="stat-label">Sedang Proses</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#FCEBEB;">⚠️</div>
        <div>
            <div class="stat-value" style="color:#A32D2D;">{{ $kosong }}</div>
            <div class="stat-label">Belum Ada Dokumen</div>
        </div>
    </div>
</div>

{{-- ═══ PANEL UTAMA ═══ --}}
<div class="panel">

    {{-- Panel header --}}
    <div class="panel-header">
        <div class="panel-header-left">
            <h3>Daftar Nasabah</h3>
            <span class="count-badge">{{ $total }} nasabah</span>
        </div>
        <button class="btn-add btn-add-desktop" onclick="openModal()">
            <span style="font-size:18px; line-height:1;">+</span> Tambah Nasabah
        </button>
    </div>

    {{-- Toolbar filter --}}
    <form method="GET" action="{{ route('petugas.nasabah.index') }}">
        <div class="toolbar">

            <div class="search-wrap">
                <span class="search-icon">🔍</span>
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Cari nama atau NIK…"
                       class="search-input"
                       autocomplete="off">
            </div>

            <select name="filter_jenis" class="filter-select">
                <option value="">Semua Jenis</option>
                @foreach($jenisLelang as $jenis)
                    <option value="{{ $jenis }}" {{ request('filter_jenis') === $jenis ? 'selected' : '' }}>
                        {{ $jenis }}
                    </option>
                @endforeach
            </select>

            <select name="filter_dokumen" class="filter-select">
                <option value="">Semua Status</option>
                <option value="lengkap" {{ request('filter_dokumen') === 'lengkap' ? 'selected' : '' }}>Dokumen Lengkap</option>
                <option value="proses"  {{ request('filter_dokumen') === 'proses'  ? 'selected' : '' }}>Sedang Proses</option>
                <option value="kosong"  {{ request('filter_dokumen') === 'kosong'  ? 'selected' : '' }}>Belum Ada Dokumen</option>
            </select>

            <button type="submit" class="toolbar-btn btn-search">Cari</button>

            @if(request()->hasAny(['search','filter_jenis','filter_dokumen']))
                <a href="{{ route('petugas.nasabah.index') }}" class="toolbar-btn btn-reset">✕ Reset</a>
            @endif

            <div class="toolbar-spacer"></div>

            @if(request()->hasAny(['search','filter_jenis','filter_dokumen']))
                <span class="result-badge">{{ $nasabah->total() }} ditemukan</span>
            @endif

        </div>
    </form>

    {{-- ─── DESKTOP TABLE ─── --}}
    <div class="table-view">
        @if($nasabah->isEmpty())
            <div class="empty-state">
                <div class="empty-icon">🔍</div>
                <div class="empty-title">Tidak ada data</div>
                <div class="empty-desc">
                    @if(request()->hasAny(['search','filter_jenis','filter_dokumen']))
                        Tidak ditemukan nasabah yang sesuai filter.<br>Coba ubah kata kunci atau reset filter.
                    @else
                        Belum ada nasabah terdaftar.<br>Klik "+ Tambah Nasabah" untuk mulai menambahkan data.
                    @endif
                </div>
            </div>
        @else
            <div class="table-wrap">
                <table class="nasabah-table">
                    <thead>
                        <tr>
                            <th class="center" style="width:48px;">No</th>
                            <th>Nama Nasabah</th>
                            <th>NIK</th>
                            <th>Jenis Lelang</th>
                            <th class="center">Dokumen</th>
                            <th class="center">LPA</th>
                            <th class="center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($nasabah as $i => $item)
                        @php
                            $maxDok   = 19;
                            $docPct   = min(100, round(($item->jumlah_dokumen / $maxDok) * 100));
                            $barColor = $docPct >= 100 ? '#27AE60' : ($docPct >= 50 ? '#F39C12' : '#E74C3C');
                            $nikMask  = substr($item->nik, 0, 4) . '••••••••' . substr($item->nik, -4);
                            $jenisCss = match(true) {
                                $item->jenis_lelang === 'Tanah'                      => 'jenis-tanah',
                                $item->jenis_lelang === 'Bangunan'                   => 'jenis-bangunan',
                                $item->jenis_lelang === 'Tanah Berikut Bangunan'     => 'jenis-tanah-bangunan',
                                $item->jenis_lelang === 'Lelang Eksekusi HT'         => 'jenis-eksekusi-ht',
                                $item->jenis_lelang === 'Lelang Eksekusi Pengadilan' => 'jenis-eksekusi-pn',
                                default                                               => 'jenis-sukarela',
                            };
                        @endphp
                        <tr>
                            <td class="row-num">{{ ($nasabah->currentPage() - 1) * $nasabah->perPage() + $i + 1 }}</td>

                            <td class="cell-nama">
                                <strong>{{ $item->nama_nasabah }}</strong>
                                <span class="lokasi">
                                    <span style="font-size:10px;">📍</span>
                                    {{ $item->lokasi_lelang }}
                                </span>
                            </td>

                            <td>
                                <span class="nik-pill"
                                      data-tooltip="{{ $item->nik }}"
                                      title="Klik untuk salin NIK">
                                    {{ $nikMask }}
                                </span>
                            </td>

                            <td>
                                <span class="jenis-badge {{ $jenisCss }}">{{ $item->jenis_lelang }}</span>
                            </td>

                            <td class="center">
                                <div class="doc-cell">
                                    <div class="doc-fraction" style="color:{{ $barColor }}">
                                        {{ $item->jumlah_dokumen }}<span class="total">/{{ $maxDok }}</span>
                                    </div>
                                    <div class="doc-bar-wrap">
                                        <div class="doc-bar-fill"
                                             style="width:{{ $docPct }}%; background:{{ $barColor }};"></div>
                                    </div>
                                    <div class="doc-pct">{{ $docPct }}%</div>
                                </div>
                            </td>

                            <td class="center">
                                <span class="lpa-badge {{ $item->jumlah_lpa > 0 ? 'has' : 'none' }}">
                                    {{ $item->jumlah_lpa }}
                                </span>
                            </td>

                            <td class="center">
                                <div class="action-group">
                                    <a href="{{ route('petugas.nasabah.dokumen', $item->id) }}" class="btn-tbl btn-tbl-doc">📄 Dokumen</a>
                                    <a href="{{ route('petugas.nasabah.lpa', $item->id) }}"     class="btn-tbl btn-tbl-lpa">📊 LPA</a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

           {{ $nasabah->withQueryString()->links() }}
        @endif
    </div>

    {{-- ─── MOBILE CARD LIST ─── --}}
    <div class="card-list">
        @if($nasabah->isEmpty())
            <div class="empty-state">
                <div class="empty-icon">👥</div>
                <div class="empty-title">Tidak ada data</div>
                <div class="empty-desc">Belum ada nasabah atau tidak ada yang sesuai filter.</div>
            </div>
        @else
            @foreach($nasabah as $item)
            @php
                $maxDok   = 19;
                $docPct   = min(100, round(($item->jumlah_dokumen / $maxDok) * 100));
                $barColor = $docPct >= 100 ? '#27AE60' : ($docPct >= 50 ? '#F39C12' : '#E74C3C');
                $nikMask  = substr($item->nik, 0, 4) . '••••••••' . substr($item->nik, -4);
                $initials = implode('', array_map(
                    fn($w) => strtoupper(substr($w, 0, 1)),
                    array_slice(explode(' ', $item->nama_nasabah), 0, 2)
                ));
                $jenisCss = match(true) {
                    $item->jenis_lelang === 'Tanah'                      => 'jenis-tanah',
                    $item->jenis_lelang === 'Bangunan'                   => 'jenis-bangunan',
                    $item->jenis_lelang === 'Tanah Berikut Bangunan'     => 'jenis-tanah-bangunan',
                    $item->jenis_lelang === 'Lelang Eksekusi HT'         => 'jenis-eksekusi-ht',
                    $item->jenis_lelang === 'Lelang Eksekusi Pengadilan' => 'jenis-eksekusi-pn',
                    default                                               => 'jenis-sukarela',
                };
            @endphp
            <div class="nasabah-card">
                <div class="card-top">
                    <div class="card-avatar">{{ $initials }}</div>
                    <div style="flex:1;">
                        <div class="card-name">{{ $item->nama_nasabah }}</div>
                        <div class="card-lokasi">
                            <span style="font-size:10px;">📍</span> {{ $item->lokasi_lelang }}
                        </div>
                    </div>
                    <span class="jenis-badge {{ $jenisCss }}" style="font-size:10.5px; padding:3px 9px;">
                        {{ $item->jenis_lelang }}
                    </span>
                </div>

                <div class="card-meta">
                    <div>
                        <div class="card-meta-label">NIK</div>
                        <div class="card-meta-val" style="font-family:'Courier New',monospace; font-size:11px;">
                            {{ $nikMask }}
                        </div>
                    </div>
                    <div>
                        <div class="card-meta-label">LPA</div>
                        <div class="card-meta-val" style="color:{{ $item->jumlah_lpa > 0 ? '#534AB7' : '#CBD2DC' }}">
                            {{ $item->jumlah_lpa > 0 ? $item->jumlah_lpa . ' LPA' : 'Belum ada' }}
                        </div>
                    </div>
                </div>

                <div>
                    <div class="card-doc-header">
                        <span class="card-doc-label">Kelengkapan Dokumen</span>
                        <span class="card-doc-frac" style="color:{{ $barColor }}">
                            {{ $item->jumlah_dokumen }}/{{ $maxDok }} ({{ $docPct }}%)
                        </span>
                    </div>
                    <div class="card-doc-bar-wrap">
                        <div class="card-doc-bar-fill"
                             style="width:{{ $docPct }}%; background:{{ $barColor }};"></div>
                    </div>
                </div>

                <div class="card-actions">
                    <a href="{{ route('petugas.nasabah.dokumen', $item->id) }}" class="card-btn card-btn-doc">📄 Dokumen</a>
                    <a href="{{ route('petugas.nasabah.lpa', $item->id) }}"     class="card-btn card-btn-lpa">📊 LPA</a>
                </div>
            </div>
            @endforeach

         {{ $nasabah->withQueryString()->links() }}
        @endif
    </div>

</div>{{-- .panel --}}

{{-- FAB mobile --}}
<button class="fab" onclick="openModal()" aria-label="Tambah Nasabah">+</button>

{{-- ═══ MODAL TAMBAH NASABAH ═══ --}}
<div class="modal-overlay" id="modalOverlay" onclick="handleOverlayClick(event)">
    <div class="modal-box" role="dialog" aria-modal="true" aria-labelledby="modalTitle">

        <div class="modal-header">
            <div class="modal-header-title" id="modalTitle">Tambah Nasabah Baru</div>
            <div class="modal-header-sub">Isi data dengan lengkap dan benar</div>
            <button class="modal-close" onclick="closeModal()" aria-label="Tutup modal">✕</button>
        </div>

        <form method="POST" action="{{ route('petugas.nasabah.store') }}" class="modal-body">
            @csrf

            <div class="form-group">
                <label class="form-label" for="nama_nasabah">
                    Nama Nasabah <span class="req">*</span>
                </label>
                <input type="text" id="nama_nasabah" name="nama_nasabah"
                       value="{{ old('nama_nasabah') }}"
                       placeholder="Nama lengkap sesuai KTP"
                       class="form-control" required autocomplete="off">
                @error('nama_nasabah')
                    <span class="form-error">⚠ {{ $message }}</span>
                @enderror
            </div>

            <div class="form-grid-2">
                <div class="form-group">
                    <label class="form-label" for="nik">NIK <span class="req">*</span></label>
                    <input type="text" id="nik" name="nik"
                           value="{{ old('nik') }}" placeholder="16 digit NIK"
                           maxlength="16" class="form-control" required inputmode="numeric"
                           oninput="this.value=this.value.replace(/[^0-9]/g,'')">
                    @error('nik')
                        <span class="form-error">⚠ {{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="no_hp">No. HP <span class="req">*</span></label>
                    <input type="text" id="no_hp" name="no_hp"
                           value="{{ old('no_hp') }}" placeholder="08xxxxxxxxxx"
                           class="form-control" required inputmode="tel">
                    @error('no_hp')
                        <span class="form-error">⚠ {{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="alamat">Alamat <span class="req">*</span></label>
                <textarea id="alamat" name="alamat" placeholder="Alamat lengkap nasabah"
                          class="form-control" required>{{ old('alamat') }}</textarea>
                @error('alamat')
                    <span class="form-error">⚠ {{ $message }}</span>
                @enderror
            </div>

            <div class="form-grid-2">
                <div class="form-group">
                    <label class="form-label" for="lokasi_lelang">Lokasi Lelang <span class="req">*</span></label>
                    <input type="text" id="lokasi_lelang" name="lokasi_lelang"
                           value="{{ old('lokasi_lelang') }}" placeholder="Contoh: Kota Bandung"
                           class="form-control" required>
                    @error('lokasi_lelang')
                        <span class="form-error">⚠ {{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="jenis_lelang">Jenis Lelang <span class="req">*</span></label>
                    <select id="jenis_lelang" name="jenis_lelang" class="form-control" required>
                        <option value="" disabled selected>— Pilih —</option>
                        @foreach($jenisLelang as $j)
                            <option value="{{ $j }}" {{ old('jenis_lelang') === $j ? 'selected' : '' }}>
                                {{ $j }}
                            </option>
                        @endforeach
                    </select>
                    @error('jenis_lelang')
                        <span class="form-error">⚠ {{ $message }}</span>
                    @enderror
                </div>
            </div>

            <button type="submit" class="btn-submit-modal">+ Tambah Nasabah</button>

        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
/* ── MODAL ── */
const overlay = document.getElementById('modalOverlay');

function openModal() {
    overlay.classList.add('active');
    document.body.style.overflow = 'hidden';
    setTimeout(() => {
        const first = overlay.querySelector('input:not([type=hidden])');
        if (first) first.focus();
    }, 260);
}

function closeModal() {
    overlay.classList.remove('active');
    document.body.style.overflow = '';
}

function handleOverlayClick(e) {
    if (e.target === overlay) closeModal();
}

document.addEventListener('keydown', e => {
    if (e.key === 'Escape') closeModal();
});

@if($errors->any())
    document.addEventListener('DOMContentLoaded', openModal);
@endif

/* ── NIK COPY ── */
document.querySelectorAll('.nik-pill').forEach(el => {
    el.addEventListener('click', function () {
        const fullNik = this.getAttribute('data-tooltip');
        navigator.clipboard?.writeText(fullNik).then(() => {
            const orig = this.textContent;
            this.textContent = '✓ Disalin';
            this.style.background = '#E6F9F9';
            this.style.color = '#0F6E56';
            setTimeout(() => {
                this.textContent = orig;
                this.style.background = '';
                this.style.color = '';
            }, 1500);
        });
    });
});

/* ── PROGRESS BAR ANIMATE ── */
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.doc-bar-fill, .card-doc-bar-fill').forEach(el => {
        const w = el.style.width;
        el.style.width = '0';
        requestAnimationFrame(() => requestAnimationFrame(() => { el.style.width = w; }));
    });
});
</script>
@endpush