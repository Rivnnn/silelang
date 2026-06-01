@extends('layouts.petugas')

@section('title', 'Surat Keluar')
@section('page-title', 'Surat Keluar')

@php
    $showBackButton = true;
    $backUrl = route('petugas.nomor-surat');
    
    // Generate nomor surat otomatis untuk preview
    $userId = auth()->id();
    $idPetugas = str_pad($userId, 2, '0', STR_PAD_LEFT);
    
    // Urutan global (dari semua petugas)
    $totalGlobal = \App\Models\SuratKeluar::count();
    $urutanBerikutnya = $totalGlobal + 1;
    
    $nomorBerikutnya = $idPetugas . "/" . str_pad($urutanBerikutnya, 3, '0', STR_PAD_LEFT) . "-1/BSI-BDG KOTA";
@endphp

@section('content')
<style>
    /* ================= ALERTS ================= */
    .alert {
        padding: 16px 20px;
        border-radius: 12px;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        gap: 12px;
        font-weight: 500;
        animation: slideDown 0.4s ease;
    }

    .alert.success {
        background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
        color: #155724;
        border-left: 4px solid #28a745;
        box-shadow: 0 4px 12px rgba(40, 167, 69, 0.15);
    }

    /* ================= BOX CONTAINER ================= */
    .box {
        background: #fff;
        padding: 32px;
        border-radius: 14px;
        margin-bottom: 24px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.06);
        transition: all 0.3s ease;
    }

    .box:hover {
        box-shadow: 0 6px 20px rgba(0,0,0,0.08);
    }

    .box-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        padding-bottom: 16px;
        border-bottom: 2px solid #f0f0f0;
    }

    .box h3 {
        color: #2c3e50;
        margin: 0;
        font-size: 22px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .box h3::before {
        content: '';
        width: 4px;
        height: 24px;
        background: linear-gradient(135deg, #39C6C9, #2FB3B6);
        border-radius: 2px;
    }

    /* ================= PREVIEW NOMOR ================= */
    .nomor-preview {
        background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
        padding: 18px 24px;
        border-radius: 10px;
        margin-bottom: 20px;
        border-left: 4px solid #2196f3;
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .nomor-preview .icon {
        font-size: 28px;
    }

    .nomor-preview .content {
        flex: 1;
    }

    .nomor-preview .content strong {
        display: block;
        color: #01579b;
        font-size: 13px;
        margin-bottom: 6px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .nomor-preview .content .nomor {
        font-size: 18px;
        font-weight: 700;
        color: #0d47a1;
        font-family: 'Courier New', monospace;
        letter-spacing: 0.8px;
        margin-bottom: 6px;
    }

    .nomor-preview .content .info {
        font-size: 12px;
        color: #0277bd;
        line-height: 1.5;
    }

    .nomor-preview .content .info-highlight {
        background: rgba(2, 119, 189, 0.1);
        padding: 2px 6px;
        border-radius: 4px;
        font-weight: 600;
    }

    /* ================= FORM ================= */
    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }

    .form-full {
        grid-column: 1 / -1;
    }

    .form-group {
        position: relative;
    }

    label {
        display: block;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 8px;
        font-size: 14px;
    }

    label .required {
        color: #dc3545;
        margin-left: 2px;
    }

    input {
        width: 100%;
        padding: 12px 16px;
        margin-bottom: 4px;
        border-radius: 10px;
        border: 2px solid #e0e0e0;
        font-size: 14px;
        transition: all 0.3s ease;
        background: #fafafa;
    }

    input:focus {
        outline: none;
        border-color: #39C6C9;
        box-shadow: 0 0 0 4px rgba(57,198,201,0.1);
        background: #fff;
    }

    /* ================= BUTTONS ================= */
    .btn-primary {
        padding: 12px 28px;
        background: linear-gradient(135deg, #39C6C9, #2FB3B6);
        border: none;
        color: #fff;
        border-radius: 10px;
        cursor: pointer;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 12px rgba(57, 198, 201, 0.3);
        letter-spacing: 0.3px;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #2FB3B6, #27a0a3);
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(57, 198, 201, 0.4);
    }

    .btn-primary:active {
        transform: translateY(0);
    }

    /* ================= TABLE ================= */
    .table-container {
        overflow-x: auto;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }

    table {
        width: 100%;
        border-collapse: collapse;
        background: #fff;
    }

    thead {
        background: linear-gradient(135deg, #39C6C9, #2FB3B6);
        box-shadow: 0 2px 4px rgba(57, 198, 201, 0.2);
    }

    th {
        padding: 16px 14px;
        text-align: left;
        color: #fff;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 12px;
        letter-spacing: 0.8px;
        white-space: nowrap;
    }

    tbody tr {
        border-bottom: 1px solid #f0f0f0;
        transition: all 0.2s ease;
    }

    tbody tr:hover {
        background: linear-gradient(90deg, #f8fdfd 0%, #fff 100%);
        transform: scale(1.01);
        box-shadow: 0 2px 8px rgba(57, 198, 201, 0.1);
    }

    tbody tr:last-child {
        border-bottom: none;
    }

    td {
        padding: 16px 14px;
        color: #2c3e50;
        font-size: 14px;
    }

    /* ================= NOMOR BADGE ================= */
    .nomor-badge {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: #fff;
        padding: 8px 16px;
        border-radius: 8px;
        font-weight: 700;
        font-size: 13px;
        display: inline-block;
        box-shadow: 0 3px 10px rgba(102, 126, 234, 0.3);
        letter-spacing: 0.5px;
        font-family: 'Courier New', monospace;
    }

    /* ================= EMPTY STATE ================= */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #95a5a6;
    }

    .empty-state .icon {
        font-size: 72px;
        display: block;
        margin-bottom: 16px;
        opacity: 0.5;
    }

    .empty-state h4 {
        font-size: 18px;
        margin-bottom: 8px;
        color: #7f8c8d;
        font-weight: 600;
    }

    .empty-state p {
        font-size: 14px;
        color: #95a5a6;
    }

    /* ================= ANIMATIONS ================= */
    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* ================= RESPONSIVE ================= */
    @media (max-width: 768px) {
        .form-grid {
            grid-template-columns: 1fr;
        }

        .box {
            padding: 20px;
        }

        .box h3 {
            font-size: 18px;
        }
    }
</style>

@if(session('success'))
    <div class="alert success">
        <span style="font-size: 20px;">✓</span>
        <span>{{ session('success') }}</span>
    </div>
@endif

<!-- Form Surat Keluar -->
<div class="box">
    <div class="box-header">
        <h3>Form Surat Keluar</h3>
    </div>

    <!-- Preview Nomor Surat Berikutnya -->
    <div class="nomor-preview">
        <div class="icon">🔢</div>
        <div class="content">
            <strong>Nomor Surat Berikutnya:</strong>
            <div class="nomor">{{ $nomorBerikutnya }}</div>
            <div class="info">
                <span class="info-highlight">{{ $idPetugas }}</span> = ID Petugas Anda &nbsp;|&nbsp; 
                <span class="info-highlight">{{ str_pad($urutanBerikutnya, 3, '0', STR_PAD_LEFT) }}</span> = Urutan Global (Unik) &nbsp;|&nbsp; 
                <span class="info-highlight">-1</span> = Kode Surat Keluar
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('petugas.surat.keluar.store') }}">
        @csrf
        <div class="form-grid">
            <div class="form-group">
                <label>Tanggal <span class="required">*</span></label>
                <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" required>
            </div>
            
            <div class="form-group">
                <label>PIC (Penanggung Jawab) <span class="required">*</span></label>
                <input type="text" name="pic" placeholder="Masukkan nama PIC" required>
            </div>

            <div class="form-group form-full">
                <label>Perihal <span class="required">*</span></label>
                <input type="text" name="perihal" placeholder="Masukkan perihal surat" required>
            </div>
            
            <div class="form-group form-full">
                <label>Tujuan <span class="required">*</span></label>
                <input type="text" name="tujuan" placeholder="Masukkan tujuan surat" required>
            </div>

            <div class="form-full">
                <button type="submit" class="btn-primary">💾 Simpan Surat</button>
            </div>
        </div>
    </form>
</div>

<!-- Data Surat Keluar -->
<div class="box">
    <div class="box-header">
        <h3>Data Surat Keluar</h3>
        <span style="color: #7f8c8d; font-size: 13px; font-weight: 500;">
            Total: {{ $data->count() }} surat
        </span>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th style="width: 60px;">No</th>
                    <th>Tanggal</th>
                    <th>Nomor Surat</th>
                    <th>Perihal</th>
                    <th>Tujuan</th>
                    <th>PIC</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $item)
                <tr>
                    <td style="text-align: center; font-weight: 600; color: #7f8c8d;">
                        {{ $loop->iteration }}
                    </td>
                    <td style="white-space: nowrap;">
                        {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}
                    </td>
                    <td><span class="nomor-badge">{{ $item->nomor_surat }}</span></td>
                    <td style="font-weight: 500;">{{ $item->perihal }}</td>
                    <td style="color: #5a6c7d;">{{ $item->tujuan }}</td>
                    <td style="color: #5a6c7d;">{{ $item->pic }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">
                        <div class="empty-state">
                            <span class="icon">📄</span>
                            <h4>Belum Ada Data Surat Keluar</h4>
                            <p>Silakan tambahkan surat baru menggunakan form di atas</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection