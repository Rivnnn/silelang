@extends('layouts.admin')

@section('title', 'Monitoring Pengajuan Lelang')
@section('page-title', 'Monitoring Pengajuan Lelang')

@section('content')
<style>
    .alert {
        padding: 16px 20px;
        border-radius: 12px;
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        gap: 12px;
        font-weight: 500;
    }

    .alert.success {
        background: #d4edda;
        color: #155724;
        border-left: 4px solid #28a745;
    }

    .alert.error {
        background: #f8d7da;
        color: #721c24;
        border-left: 4px solid #dc3545;
    }

    /* ================= STATS CARDS ================= */
    .stats-row {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: #fff;
        padding: 24px;
        border-radius: 14px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.06);
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
    }

    .stat-card.pending {
        --card-color: #f39c12;
    }

    .stat-card.approved {
        --card-color: #27ae60;
    }

    .stat-card.rejected {
        --card-color: #e74c3c;
    }

    /* ================= TABLE BOX ================= */
    .table-box {
        background: #fff;
        padding: 28px;
        border-radius: 14px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.06);
    }

    .table-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
    }

    .table-header h3 {
        color: #2c3e50;
        font-size: 20px;
        font-weight: 600;
    }

    .filter-select {
        padding: 10px 16px;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
    }

    /* ================= TABLE ================= */
    table {
        width: 100%;
        border-collapse: collapse;
    }

    thead {
        background: linear-gradient(135deg, #34495e, #2c3e50);
    }

    th {
        padding: 14px 12px;
        text-align: left;
        color: #fff;
        font-weight: 600;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    tbody tr {
        border-bottom: 1px solid #e0e0e0;
        transition: all 0.3s ease;
    }

    tbody tr:hover {
        background: #f8f9fa;
    }

    td {
        padding: 14px 12px;
        color: #2c3e50;
        font-size: 13px;
    }

    /* ================= STATUS BADGES ================= */
    .status-badge {
        display: inline-block;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
    }

    .status-badge.pending {
        background: #fff3cd;
        color: #856404;
    }

    .status-badge.disetujui {
        background: #d4edda;
        color: #155724;
    }

    .status-badge.ditolak {
        background: #f8d7da;
        color: #721c24;
    }

    /* ================= ACTION BUTTONS ================= */
    .action-buttons {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .btn {
        padding: 7px 14px;
        border-radius: 6px;
        border: none;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-approve {
        background: #28a745;
        color: #fff;
    }

    .btn-approve:hover {
        background: #218838;
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(40,167,69,0.3);
    }

    .btn-reject {
        background: #dc3545;
        color: #fff;
    }

    .btn-reject:hover {
        background: #c82333;
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(220,53,69,0.3);
    }

    /* ================= MODAL ================= */
    .modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.6);
        z-index: 9999;
        animation: fadeIn 0.3s ease;
    }

    .modal-overlay.active {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .modal-box {
        background: #fff;
        border-radius: 16px;
        width: 90%;
        max-width: 600px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.3);
        animation: slideUp 0.3s ease;
        overflow: hidden;
    }

    .modal-header {
        padding: 24px 28px;
        border-bottom: 1px solid #e0e0e0;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .modal-header.success {
        background: linear-gradient(135deg, #28a745, #20c997);
        color: #fff;
    }

    .modal-header.danger {
        background: linear-gradient(135deg, #dc3545, #c82333);
        color: #fff;
    }

    .modal-header .icon {
        font-size: 32px;
    }

    .modal-header h3 {
        font-size: 20px;
        font-weight: 600;
        margin: 0;
    }

    .modal-body {
        padding: 28px;
    }

    .modal-body .info-box {
        background: #f8f9fa;
        padding: 16px;
        border-radius: 8px;
        margin-bottom: 20px;
    }

    .modal-body .info-box p {
        margin: 8px 0;
        font-size: 14px;
        color: #495057;
    }

    .modal-body .info-box strong {
        color: #2c3e50;
        font-weight: 600;
    }

    .modal-body .info-row {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        border-bottom: 1px solid #e9ecef;
    }

    .modal-body .info-row:last-child {
        border-bottom: none;
    }

    .modal-body .info-label {
        color: #6c757d;
        font-size: 13px;
        font-weight: 500;
    }

    .modal-body .info-value {
        color: #2c3e50;
        font-size: 14px;
        font-weight: 600;
    }

    .modal-body textarea {
        width: 100%;
        padding: 14px;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        font-size: 14px;
        min-height: 120px;
        font-family: inherit;
        resize: vertical;
        transition: all 0.3s ease;
    }

    .modal-body textarea:focus {
        outline: none;
        border-color: #3498db;
        box-shadow: 0 0 0 3px rgba(52,152,219,0.1);
    }

    .char-counter {
        text-align: right;
        font-size: 12px;
        color: #7f8c8d;
        margin-top: 8px;
        font-weight: 500;
    }

    .char-counter.warning {
        color: #f39c12;
    }

    .char-counter.error {
        color: #e74c3c;
    }

    .modal-footer {
        padding: 20px 28px;
        background: #f8f9fa;
        display: flex;
        gap: 12px;
        justify-content: flex-end;
    }

    .modal-btn {
        padding: 12px 28px;
        border-radius: 8px;
        border: none;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .modal-btn-cancel {
        background: #6c757d;
        color: #fff;
    }

    .modal-btn-cancel:hover {
        background: #5a6268;
    }

    .modal-btn-confirm {
        background: #28a745;
        color: #fff;
    }

    .modal-btn-confirm:hover {
        background: #218838;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(40,167,69,0.3);
    }

    .modal-btn-confirm.danger {
        background: #dc3545;
    }

    .modal-btn-confirm.danger:hover {
        background: #c82333;
        box-shadow: 0 4px 12px rgba(220,53,69,0.3);
    }

    .modal-btn-confirm:disabled {
        background: #95a5a6;
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes slideUp {
        from { 
            opacity: 0;
            transform: translateY(30px);
        }
        to { 
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

@if(session('success'))
    <div class="alert success">
        <span>✓</span> {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert error">
        <span>✖</span> {{ session('error') }}
    </div>
@endif

@if($errors->any())
    <div class="alert error">
        <span>✖</span>
        <div>
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    </div>
@endif

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

<!-- Table -->
<div class="table-box">
    <div class="table-header">
        <h3>Daftar Pengajuan Lelang</h3>
        <select class="filter-select" onchange="filterByStatus(this.value)">
            <option value="">Semua Status</option>
            <option value="pending">Pending</option>
            <option value="disetujui">Disetujui</option>
            <option value="ditolak">Ditolak</option>
        </select>
    </div>

    <table id="pengajuanTable">
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Nasabah</th>
                <th>NIK</th>
                <th>Petugas</th>
                <th>Status</th>
                <th>Catatan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pengajuan as $item)
            <tr data-status="{{ $item->status }}">
                <td>{{ $loop->iteration }}</td>
                <td>{{ \Carbon\Carbon::parse($item->tanggal_pengajuan)->format('d M Y') }}</td>
                <td><strong>{{ $item->nasabah->nama_nasabah }}</strong></td>
                <td>{{ $item->nasabah->nik }}</td>
                <td>{{ $item->user->name ?? '-' }}</td>
                <td>
                    <span class="status-badge {{ $item->status }}">
                        {{ ucfirst($item->status) }}
                    </span>
                </td>
                <td>{{ $item->catatan_admin ?? '-' }}</td>
                <td>
                    @if($item->status == 'pending')
                    <div class="action-buttons">
                        <button type="button" class="btn btn-approve" 
                                onclick="openApproveModal({{ $item->id }}, '{{ $item->nasabah->nama_nasabah }}', '{{ $item->nasabah->nik }}', '{{ \Carbon\Carbon::parse($item->tanggal_pengajuan)->format('d M Y') }}')">
                            ✓ Setujui
                        </button>
                        <button type="button" class="btn btn-reject" 
                                onclick="openRejectModal({{ $item->id }}, '{{ $item->nasabah->nama_nasabah }}', '{{ $item->nasabah->nik }}', '{{ \Carbon\Carbon::parse($item->tanggal_pengajuan)->format('d M Y') }}')">
                            ✖ Tolak
                        </button>
                    </div>
                    @else
                        <span style="color: #95a5a6; font-style: italic;">Sudah diproses</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align: center; padding: 40px; color: #95a5a6;">
                    Belum ada pengajuan lelang
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Modal Approve -->
<div id="modalApprove" class="modal-overlay">
    <div class="modal-box">
        <div class="modal-header success">
            <span class="icon">✓</span>
            <h3>Terima & Setujui Pengajuan</h3>
        </div>
        <div class="modal-body">
            <p style="margin-bottom: 16px;">Anda akan <strong>menyetujui</strong> pengajuan lelang berikut:</p>
            <div class="info-box">
                <div class="info-row">
                    <span class="info-label">Nama Nasabah:</span>
                    <span class="info-value" id="approveNasabah"></span>
                </div>
                <div class="info-row">
                    <span class="info-label">NIK:</span>
                    <span class="info-value" id="approveNIK"></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Tanggal Pengajuan:</span>
                    <span class="info-value" id="approveTanggal"></span>
                </div>
            </div>
            <p style="color: #28a745; font-weight: 500;">
                ✓ Pengajuan yang disetujui akan diproses ke tahap lelang selanjutnya.
            </p>
        </div>
        <div class="modal-footer">
            <button type="button" class="modal-btn modal-btn-cancel" onclick="closeModal('modalApprove')">
                Batal
            </button>
            <button type="button" class="modal-btn modal-btn-confirm" onclick="submitApprove()">
                ✓ Ya, Setujui Pengajuan
            </button>
        </div>
    </div>
</div>

<!-- Modal Reject -->
<div id="modalReject" class="modal-overlay">
    <div class="modal-box">
        <div class="modal-header danger">
            <span class="icon">✖</span>
            <h3>Tolak Pengajuan Lelang</h3>
        </div>
        <form method="POST" id="rejectForm">
            @csrf
            <div class="modal-body">
                <p style="margin-bottom: 16px;">Anda akan <strong>menolak</strong> pengajuan lelang berikut:</p>
                <div class="info-box">
                    <div class="info-row">
                        <span class="info-label">Nama Nasabah:</span>
                        <span class="info-value" id="rejectNasabah"></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">NIK:</span>
                        <span class="info-value" id="rejectNIK"></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Tanggal Pengajuan:</span>
                        <span class="info-value" id="rejectTanggal"></span>
                    </div>
                </div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #2c3e50;">
                    Alasan Penolakan <span style="color: #dc3545;">*</span>
                </label>
                <textarea 
                    name="catatan_admin" 
                    id="catatanAdmin"
                    placeholder="Masukkan alasan penolakan dengan jelas dan detail (minimal 10 karakter)..." 
                    required
                    minlength="10"
                    oninput="updateCharCounter()"></textarea>
                <div class="char-counter" id="charCounter">0 / 10 karakter minimum</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="modal-btn modal-btn-cancel" onclick="closeModal('modalReject')">
                    Batal
                </button>
                <button type="submit" class="modal-btn modal-btn-confirm danger" id="submitBtn" disabled>
                    ✖ Ya, Tolak Pengajuan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Hidden Form for Approve -->
<form id="formApprove" method="POST" style="display: none;">
    @csrf
</form>

@endsection

@push('scripts')
<script>
function filterByStatus(status) {
    const rows = document.querySelectorAll('#pengajuanTable tbody tr');
    rows.forEach(row => {
        const rowStatus = row.getAttribute('data-status');
        if (!status || rowStatus === status) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

function openApproveModal(id, nasabah, nik, tanggal) {
    document.getElementById('approveNasabah').textContent = nasabah;
    document.getElementById('approveNIK').textContent = nik;
    document.getElementById('approveTanggal').textContent = tanggal;
    document.getElementById('formApprove').action = '{{ route("admin.monitoring.lelang.approve", ":id") }}'.replace(':id', id);
    document.getElementById('modalApprove').classList.add('active');
}

function submitApprove() {
    document.getElementById('formApprove').submit();
}

function openRejectModal(id, nasabah, nik, tanggal) {
    document.getElementById('rejectNasabah').textContent = nasabah;
    document.getElementById('rejectNIK').textContent = nik;
    document.getElementById('rejectTanggal').textContent = tanggal;
    
    const form = document.getElementById('rejectForm');
    const textarea = document.getElementById('catatanAdmin');
    const submitBtn = document.getElementById('submitBtn');
    
    // Set form action
    form.action = '{{ route("admin.monitoring.lelang.reject", ":id") }}'.replace(':id', id);
    
    // Reset form
    textarea.value = '';
    submitBtn.disabled = true;
    updateCharCounter();
    
    // Show modal
    document.getElementById('modalReject').classList.add('active');
    
    // Focus textarea
    setTimeout(() => textarea.focus(), 300);
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.remove('active');
    
    // Reset reject form if closing reject modal
    if (modalId === 'modalReject') {
        document.getElementById('rejectForm').reset();
        updateCharCounter();
    }
}

function updateCharCounter() {
    const textarea = document.getElementById('catatanAdmin');
    const counter = document.getElementById('charCounter');
    const submitBtn = document.getElementById('submitBtn');
    const length = textarea.value.length;
    
    counter.textContent = length + ' / 10 karakter minimum';
    
    if (length < 10) {
        counter.classList.remove('warning');
        counter.classList.add('error');
        submitBtn.disabled = true;
    } else if (length < 20) {
        counter.classList.remove('error');
        counter.classList.add('warning');
        submitBtn.disabled = false;
    } else {
        counter.classList.remove('error', 'warning');
        submitBtn.disabled = false;
    }
}

// Close modal when clicking outside
document.querySelectorAll('.modal-overlay').forEach(modal => {
    modal.addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.remove('active');
        }
    });
});

// Close modal with ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        document.querySelectorAll('.modal-overlay').forEach(modal => {
            modal.classList.remove('active');
        });
    }
});

// Prevent form submission if textarea is too short
document.getElementById('rejectForm').addEventListener('submit', function(e) {
    const textarea = document.getElementById('catatanAdmin');
    if (textarea.value.length < 10) {
        e.preventDefault();
        alert('Alasan penolakan minimal 10 karakter!');
        textarea.focus();
    }
});
</script>
@endpush