@extends('layouts.petugas')

@section('title', 'Pengajuan Lelang')
@section('page-title', 'Pengajuan Lelang')

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

    .alert.error {
        background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
        color: #721c24;
        border-left: 4px solid #dc3545;
        box-shadow: 0 4px 12px rgba(220, 53, 69, 0.15);
    }

    /* ================= INFO BOX ================= */
    .info-box {
        background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
        padding: 18px 24px;
        border-radius: 12px;
        margin-bottom: 24px;
        color: #01579b;
        border-left: 4px solid #2196f3;
        box-shadow: 0 4px 12px rgba(33, 150, 243, 0.1);
        display: flex;
        align-items: flex-start;
        gap: 12px;
    }

    .info-box .icon {
        font-size: 24px;
        flex-shrink: 0;
    }

    .info-box .content strong {
        display: block;
        margin-bottom: 6px;
        font-size: 15px;
        font-weight: 700;
    }

    .info-box .content p {
        margin: 0;
        font-size: 14px;
        line-height: 1.6;
    }

    /* ================= STATS CARDS ================= */
    .stats-row {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
        margin-bottom: 28px;
    }

    .stat-card {
        background: #fff;
        padding: 24px;
        border-radius: 14px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.06);
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.12);
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: var(--card-color);
    }

    .stat-icon {
        font-size: 36px;
        margin-bottom: 12px;
    }

    .stat-value {
        font-size: 32px;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 5px;
    }

    .stat-label {
        font-size: 13px;
        color: #7f8c8d;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .stat-card.pending { --card-color: #f39c12; }
    .stat-card.approved { --card-color: #27ae60; }
    .stat-card.rejected { --card-color: #e74c3c; }

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

    .btn-secondary {
        padding: 10px 20px;
        background: #fff;
        border: 2px solid #39C6C9;
        color: #39C6C9;
        border-radius: 10px;
        cursor: pointer;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .btn-secondary:hover {
        background: #39C6C9;
        color: #fff;
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

    input, select {
        width: 100%;
        padding: 12px 16px;
        margin-bottom: 4px;
        border-radius: 10px;
        border: 2px solid #e0e0e0;
        font-size: 14px;
        transition: all 0.3s ease;
        background: #fafafa;
    }

    input:focus, select:focus {
        outline: none;
        border-color: #39C6C9;
        box-shadow: 0 0 0 4px rgba(57,198,201,0.1);
        background: #fff;
    }

    select {
        cursor: pointer;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%2339C6C9' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 12px center;
        padding-right: 40px;
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

    td strong {
        color: #1a1a1a;
        font-weight: 600;
    }

    /* ================= STATUS BADGES ================= */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 16px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }

    .status-badge.pending {
        background: linear-gradient(135deg, #fff3cd, #ffeaa7);
        color: #856404;
    }

    .status-badge.disetujui {
        background: linear-gradient(135deg, #d4edda, #c3e6cb);
        color: #155724;
    }

    .status-badge.ditolak {
        background: linear-gradient(135deg, #f8d7da, #f5c6cb);
        color: #721c24;
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

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    /* ================= RESPONSIVE ================= */
    @media (max-width: 768px) {
        .form-grid {
            grid-template-columns: 1fr;
        }

        .stats-row {
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

@if(session('error'))
    <div class="alert error">
        <span style="font-size: 20px;">✖</span>
        <span>{{ session('error') }}</span>
    </div>
@endif

<!-- Info Box -->
<div class="info-box">
    <div class="icon">ℹ️</div>
    <div class="content">
        <strong>Informasi Pengajuan Lelang</strong>
        <p>Ajukan nasabah dengan status kredit macet untuk proses lelang. Admin akan mereview dan menyetujui/menolak pengajuan Anda.</p>
    </div>
</div>

<!-- Stats Cards -->
<div class="stats-row">
    <div class="stat-card pending">
        <div class="stat-icon">⏳</div>
        <div class="stat-value">{{ $pengajuan->where('status', 'pending')->count() }}</div>
        <div class="stat-label">Menunggu Review</div>
    </div>

    <div class="stat-card approved">
        <div class="stat-icon">✓</div>
        <div class="stat-value">{{ $pengajuan->where('status', 'disetujui')->count() }}</div>
        <div class="stat-label">Disetujui</div>
    </div>

    <div class="stat-card rejected">
        <div class="stat-icon">✖</div>
        <div class="stat-value">{{ $pengajuan->where('status', 'ditolak')->count() }}</div>
        <div class="stat-label">Ditolak</div>
    </div>
</div>

<!-- Action Box -->
<div class="box">
    <button class="btn-primary" onclick="toggleForm()">
        <span style="font-size: 16px;">+</span> Ajukan Lelang Baru
    </button>
</div>

<!-- Form Pengajuan -->
<div class="box" id="formPengajuan" style="display:none; animation: fadeIn 0.4s ease;">
    <div class="box-header">
        <h3>Form Pengajuan Lelang</h3>
        <button class="btn-secondary" onclick="toggleForm()">✖ Tutup</button>
    </div>

    <form method="POST" action="{{ route('petugas.pengajuan-lelang.store') }}">
        @csrf
        <div class="form-grid">
            <div class="form-group">
                <label>Pilih Nasabah <span class="required">*</span></label>
                <select name="nasabah_id" required>
                    <option value="">-- Pilih Nasabah --</option>
                    @foreach($nasabah as $n)
                        <option value="{{ $n->id }}">
                            {{ $n->nama_nasabah }} (NIK: {{ $n->nik }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Tanggal Pengajuan <span class="required">*</span></label>
                <input type="date" name="tanggal_pengajuan" value="{{ date('Y-m-d') }}" required>
            </div>

            <div class="form-full">
                <button type="submit" class="btn-primary" style="width: auto;">
                    📤 Kirim Pengajuan
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Status Pengajuan -->
<div class="box">
    <div class="box-header">
        <h3>Riwayat Pengajuan Lelang</h3>
        <span style="color: #7f8c8d; font-size: 13px; font-weight: 500;">
            Total: {{ $pengajuan->count() }} pengajuan
        </span>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th style="width: 60px;">No</th>
                    <th>Tanggal</th>
                    <th>Nama Nasabah</th>
                    <th>NIK</th>
                    <th style="text-align: center;">Status</th>
                    <th>Catatan Admin</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pengajuan as $item)
                <tr>
                    <td style="text-align: center; font-weight: 600; color: #7f8c8d;">
                        {{ $loop->iteration }}
                    </td>
                    <td style="white-space: nowrap;">
                        {{ \Carbon\Carbon::parse($item->tanggal_pengajuan)->format('d M Y') }}
                    </td>
                    <td><strong>{{ $item->nasabah->nama_nasabah }}</strong></td>
                    <td style="font-family: monospace; color: #5a6c7d;">{{ $item->nasabah->nik }}</td>
                    <td style="text-align: center;">
                        <span class="status-badge {{ $item->status }}">
                            @if($item->status == 'pending')
                                ⏳ Pending
                            @elseif($item->status == 'disetujui')
                                ✓ Disetujui
                            @else
                                ✖ Ditolak
                            @endif
                        </span>
                    </td>
                    <td style="color: #5a6c7d;">
                        @if($item->catatan_admin)
                            {{ $item->catatan_admin }}
                        @else
                            <span style="color: #95a5a6; font-style: italic;">-</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">
                        <div class="empty-state">
                            <span class="icon">⚖️</span>
                            <h4>Belum Ada Pengajuan Lelang</h4>
                            <p>Klik tombol "Ajukan Lelang Baru" untuk membuat pengajuan</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<script>
function toggleForm() {
    const form = document.getElementById('formPengajuan');
    if (form.style.display === 'none' || form.style.display === '') {
        form.style.display = 'block';
        form.scrollIntoView({ behavior: 'smooth', block: 'start' });
    } else {
        form.style.display = 'none';
    }
}
</script>
@endpush
@endsection