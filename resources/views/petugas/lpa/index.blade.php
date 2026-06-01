@extends('layouts.petugas')

@section('title', 'Laporan Penilaian Agunan')
@section('page-title', 'Laporan Penilaian Agunan')

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
        --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
        --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1);
        --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1);
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
        max-width: 1600px;
        margin: 0 auto;
    }

    /* ========================================
       ALERTS
    ======================================== */
    
    .alert {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        padding: 16px 20px;
        border-radius: 12px;
        margin-bottom: 24px;
        font-size: 14px;
        line-height: 1.6;
        animation: slideIn 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        border-left: 4px solid;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-12px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .alert.success {
        background: var(--success-bg);
        color: var(--success-text);
        border-color: var(--success-border);
    }

    .alert-icon {
        font-size: 20px;
        line-height: 1;
        flex-shrink: 0;
    }

    /* ========================================
       CARDS
    ======================================== */
    
    .card {
        background: var(--bg-primary);
        border-radius: 16px;
        border: 1px solid var(--border-light);
        box-shadow: var(--shadow-sm);
        margin-bottom: 24px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .card:hover {
        box-shadow: var(--shadow-md);
    }

    .card-header {
        padding: 24px 28px;
        border-bottom: 1px solid var(--border-light);
        display: flex;
        justify-content: space-between;
        align-items: center;
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
        padding: 28px;
    }

    /* ========================================
       BUTTONS
    ======================================== */
    
    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 11px 20px;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        outline: none;
        text-decoration: none;
        white-space: nowrap;
    }

    .btn-primary {
        background: var(--text-primary);
        color: white;
        box-shadow: var(--shadow-sm);
    }

    .btn-primary:hover {
        background: var(--primary);
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

    .btn-icon {
        font-size: 16px;
        line-height: 1;
    }

    /* ========================================
       FORMS
    ======================================== */
    
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

    .form-label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 8px;
        letter-spacing: -0.01em;
    }

    .form-label .required {
        color: #EF4444;
        margin-left: 2px;
    }

    .form-control {
        width: 100%;
        padding: 12px 16px;
        border: 1.5px solid var(--border-light);
        border-radius: 10px;
        font-size: 14px;
        color: var(--text-primary);
        background: var(--bg-primary);
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        outline: none;
    }

    .form-control::placeholder {
        color: var(--text-muted);
    }

    .form-control:hover {
        border-color: var(--border-medium);
    }

    .form-control:focus {
        border-color: var(--primary);
        background: white;
        box-shadow: 0 0 0 3px rgba(57, 198, 201, 0.1);
    }

    .form-control:read-only {
        background: var(--bg-tertiary);
        cursor: not-allowed;
    }

    textarea.form-control {
        resize: vertical;
        min-height: 90px;
        font-family: inherit;
    }

    select.form-control {
        cursor: pointer;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%2364748B' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 16px center;
        padding-right: 40px;
    }

    .form-footer {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        margin-top: 24px;
        padding-top: 20px;
        border-top: 1px solid var(--border-light);
    }

    .form-hint {
        font-size: 12px;
        color: var(--text-muted);
        margin-top: 4px;
        font-style: italic;
    }

    /* ========================================
       SEARCH
    ======================================== */
    
    .search-wrapper {
        position: relative;
        max-width: 400px;
        margin-bottom: 24px;
    }

    .search-icon {
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-muted);
        font-size: 16px;
        pointer-events: none;
    }

    .search-input {
        width: 100%;
        padding: 12px 16px 12px 44px;
        border: 1.5px solid var(--border-light);
        border-radius: 10px;
        font-size: 14px;
        background: white;
        transition: all 0.2s;
    }

    .search-input:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(57, 198, 201, 0.1);
    }

    /* ========================================
       TABLE
    ======================================== */
    
    .table-container {
        background: white;
        border-radius: 12px;
        border: 1px solid var(--border-light);
        overflow: hidden;
    }

    .table-responsive {
        overflow-x: auto;
    }

    .table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        min-width: 1200px;
    }

    .table thead {
        background: var(--bg-secondary);
    }

    .table th {
        padding: 14px 16px;
        text-align: center;
        font-size: 11px;
        font-weight: 700;
        color: var(--text-secondary);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 1px solid var(--border-light);
        white-space: nowrap;
    }

    .table td {
        padding: 16px;
        font-size: 13px;
        color: var(--text-primary);
        border-bottom: 1px solid var(--border-light);
        text-align: center;
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

    .table-number {
        color: var(--text-muted);
        font-weight: 600;
    }

    .table-name {
        font-weight: 600;
        color: var(--text-primary);
        text-align: left;
    }

    /* ========================================
       BADGES
    ======================================== */
    
    .badge {
        display: inline-flex;
        align-items: center;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 600;
        letter-spacing: 0.02em;
    }

    .badge-legalitas {
        background: rgba(59, 130, 246, 0.1);
        color: #1E40AF;
        text-transform: uppercase;
    }

    .badge-lelang {
        background: rgba(139, 92, 246, 0.1);
        color: #6B21A8;
    }

    /* ========================================
       EMPTY STATE
    ======================================== */
    
    .empty-state {
        text-align: center;
        padding: 64px 32px;
    }

    .empty-icon {
        font-size: 64px;
        margin-bottom: 16px;
        opacity: 0.4;
    }

    .empty-title {
        font-size: 16px;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 8px;
    }

    .empty-text {
        font-size: 14px;
        color: var(--text-muted);
    }

    /* ========================================
       MODAL OVERLAY
    ======================================== */
    
    .form-modal {
        animation: fadeInScale 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    @keyframes fadeInScale {
        from {
            opacity: 0;
            transform: scale(0.95);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    .close-btn {
        color: var(--text-muted);
        font-size: 24px;
        cursor: pointer;
        transition: all 0.2s;
        line-height: 1;
        padding: 4px;
    }

    .close-btn:hover {
        color: var(--text-primary);
        transform: rotate(90deg);
    }

    /* ========================================
       INFO BOXES
    ======================================== */
    
    .info-box {
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(147, 197, 253, 0.1));
        border: 1px solid rgba(59, 130, 246, 0.2);
        border-radius: 12px;
        padding: 16px 20px;
        margin-bottom: 20px;
    }

    .info-box-title {
        font-size: 12px;
        font-weight: 700;
        color: #1E40AF;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 4px;
    }

    .info-box-text {
        font-size: 13px;
        color: var(--text-secondary);
        line-height: 1.6;
    }

    /* ========================================
       RESPONSIVE
    ======================================== */
    
    @media (max-width: 1024px) {
        .form-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .card-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 16px;
        }

        .table th,
        .table td {
            padding: 12px 10px;
            font-size: 12px;
        }

        .search-wrapper {
            max-width: 100%;
        }
    }
</style>

<div class="container-fluid">
    <!-- Alerts -->
    @if(session('success'))
        <div class="alert success">
            <span class="alert-icon">✓</span>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    <!-- Header Card -->
    <div class="card">
        <div class="card-header">
            <h1 class="card-title">
                <span>📊</span>
                <span>Laporan Penilaian Agunan (LPA)</span>
            </h1>
            <button class="btn btn-primary" onclick="toggleForm()">
                <span class="btn-icon">+</span>
                <span>Buat LPA Baru</span>
            </button>
        </div>
    </div>

    <!-- Form Card -->
    <div class="card form-modal" id="formLPA" style="display:none;">
        <div class="card-header">
            <h2 class="card-title">Form Penilaian Agunan</h2>
            <span class="close-btn" onclick="toggleForm()">✕</span>
        </div>
        
        <div class="card-body">
            <div class="info-box">
                <div class="info-box-title">📋 Informasi</div>
                <div class="info-box-text">
                    Nilai Limit dan Uang Jaminan akan dihitung otomatis berdasarkan lelang ke berapa dan nilai yang diinput.
                </div>
            </div>

            <form action="{{ route('petugas.lpa.store') }}" method="POST" id="lpaForm">
                @csrf
                
                <div class="form-grid">
                    <!-- Nasabah -->
                    <div class="form-group form-full">
                        <label class="form-label">
                            Nama Nasabah <span class="required">*</span>
                        </label>
                        <select name="nasabah_id" class="form-control" required>
                            <option value="">Pilih Nasabah</option>
                            @foreach($nasabah as $n)
                                <option value="{{ $n->id }}">{{ $n->nama_nasabah }} - {{ $n->nik }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Jenis Legalitas -->
                    <div class="form-group">
                        <label class="form-label">
                            Jenis Legalitas <span class="required">*</span>
                        </label>
                        <select name="jenis_legalitas" class="form-control" required>
                            <option value="">Pilih Legalitas</option>
                            <option value="SHM">SHM (Sertifikat Hak Milik)</option>
                            <option value="SHGB">SHGB (Sertifikat Hak Guna Bangunan)</option>
                        </select>
                    </div>

                    <!-- Lelang Ke -->
                    <div class="form-group">
                        <label class="form-label">
                            Lelang Ke <span class="required">*</span>
                        </label>
                        <select name="lelang_ke" id="lelang_ke" class="form-control" required onchange="hitungLimitDanJaminan()">
                            <option value="">Pilih Lelang</option>
                            <option value="1">Lelang Pertama</option>
                            <option value="2">Lelang Kedua</option>
                            <option value="3">Lelang Ketiga</option>
                        </select>
                    </div>

                    <!-- Luas Tanah -->
                    <div class="form-group">
                        <label class="form-label">
                            Luas Tanah (m²) <span class="required">*</span>
                        </label>
                        <input 
                            type="number" 
                            name="luas_tanah" 
                            class="form-control"
                            placeholder="Contoh: 150"
                            step="0.01"
                            required
                        >
                    </div>

                    <!-- Luas Bangunan -->
                    <div class="form-group">
                        <label class="form-label">
                            Luas Bangunan (m²) <span class="required">*</span>
                        </label>
                        <input 
                            type="number" 
                            name="luas_bangunan" 
                            class="form-control"
                            placeholder="Contoh: 100"
                            step="0.01"
                            required
                        >
                    </div>

                    <!-- Spesifikasi Bangunan -->
                    <div class="form-group form-full">
                        <label class="form-label">
                            Spesifikasi Bangunan <span class="required">*</span>
                        </label>
                        <textarea 
                            name="spek_bangunan" 
                            class="form-control"
                            placeholder="Contoh: Bangunan permanen 2 lantai, struktur beton bertulang, atap genteng..."
                            required
                        ></textarea>
                    </div>

                    <!-- Nilai Pasar -->
                    <div class="form-group">
                        <label class="form-label">
                            Nilai Pasar (Rp) <span class="required">*</span>
                        </label>
                        <input 
                            type="number" 
                            name="nilai_pasar" 
                            id="nilai_pasar" 
                            class="form-control"
                            placeholder="Contoh: 500000000"
                            required 
                            onchange="hitungLimitDanJaminan()"
                        >
                        <div class="form-hint">Nilai pasar properti saat ini</div>
                    </div>

                    <!-- Nilai Likuidasi -->
                    <div class="form-group">
                        <label class="form-label">
                            Nilai Likuidasi (Rp) <span class="required">*</span>
                        </label>
                        <input 
                            type="number" 
                            name="nilai_likuidasi" 
                            id="nilai_likuidasi" 
                            class="form-control"
                            placeholder="Contoh: 400000000"
                            required 
                            onchange="hitungLimitDanJaminan()"
                        >
                        <div class="form-hint">Nilai jual cepat properti</div>
                    </div>

                    <!-- Nilai Limit (Auto) -->
                    <div class="form-group">
                        <label class="form-label">
                            Nilai Limit (Otomatis)
                        </label>
                        <input 
                            type="text" 
                            id="nilai_limit" 
                            class="form-control"
                            readonly
                            placeholder="Akan dihitung otomatis"
                        >
                        <input type="hidden" name="nilai_limit" id="nilai_limit_hidden">
                    </div>

                    <!-- Uang Jaminan (Auto) -->
                    <div class="form-group">
                        <label class="form-label">
                            Uang Jaminan 20% (Otomatis)
                        </label>
                        <input 
                            type="text" 
                            id="uang_jaminan" 
                            class="form-control"
                            readonly
                            placeholder="Akan dihitung otomatis"
                        >
                        <input type="hidden" name="uang_jaminan" id="uang_jaminan_hidden">
                    </div>
                </div>

                <div class="form-footer">
                    <button type="button" class="btn btn-outline" onclick="toggleForm()">
                        <span>Batal</span>
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <span class="btn-icon">💾</span>
                        <span>Simpan LPA</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Table Card -->
    <div class="card">
        <div class="card-body">
            <!-- Search -->
            <div class="search-wrapper">
                <span class="search-icon">🔍</span>
                <input 
                    type="text" 
                    id="searchNasabah" 
                    class="search-input"
                    placeholder="Cari nama nasabah..." 
                    onkeyup="filterLPA()"
                >
            </div>

            <!-- Table -->
            <div class="table-container">
                <div class="table-responsive">
                    <table class="table" id="tableLPA">
                        <thead>
                            <tr>
                                <th style="width: 50px;">No</th>
                                <th style="min-width: 150px;">Nasabah</th>
                                <th>Legalitas</th>
                                <th>Tanah (m²)</th>
                                <th>Bangunan (m²)</th>
                                <th>Nilai Pasar</th>
                                <th>Nilai Likuidasi</th>
                                <th>Lelang Ke</th>
                                <th>Nilai Limit</th>
                                <th>Uang Jaminan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($lpa as $item)
                            <tr>
                                <td>
                                    <span class="table-number">{{ $loop->iteration }}</span>
                                </td>
                                <td class="table-name">
                                    {{ $item->nasabah->nama_nasabah ?? '-' }}
                                </td>
                                <td>
                                    <span class="badge badge-legalitas">{{ $item->jenis_legalitas }}</span>
                                </td>
                                <td>{{ number_format($item->luas_tanah, 0, ',', '.') }}</td>
                                <td>{{ number_format($item->luas_bangunan, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($item->nilai_pasar, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($item->nilai_likuidasi, 0, ',', '.') }}</td>
                                <td>
                                    <span class="badge badge-lelang">Lelang {{ $item->lelang_ke }}</span>
                                </td>
                                <td><strong>Rp {{ number_format($item->nilai_limit, 0, ',', '.') }}</strong></td>
                                <td><strong>Rp {{ number_format($item->uang_jaminan, 0, ',', '.') }}</strong></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10">
                                    <div class="empty-state">
                                        <div class="empty-icon">📊</div>
                                        <div class="empty-title">Belum Ada Data LPA</div>
                                        <div class="empty-text">Klik tombol "Buat LPA Baru" untuk mulai membuat laporan penilaian agunan</div>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function toggleForm() {
    const form = document.getElementById('formLPA');
    const isHidden = form.style.display === 'none';
    
    form.style.display = isHidden ? 'block' : 'none';
    
    if (isHidden) {
        form.scrollIntoView({ behavior: 'smooth', block: 'start' });
        // Focus on first input
        setTimeout(() => {
            form.querySelector('select[name="nasabah_id"]').focus();
        }, 300);
    } else {
        // Reset form
        document.getElementById('lpaForm').reset();
        resetCalculation();
    }
}

function hitungLimitDanJaminan() {
    let pasar = parseFloat(document.getElementById('nilai_pasar').value) || 0;
    let likuid = parseFloat(document.getElementById('nilai_likuidasi').value) || 0;
    let lelang = document.getElementById('lelang_ke').value;

    if (!lelang || pasar === 0 || likuid === 0) {
        resetCalculation();
        return;
    }

    // Perhitungan nilai limit berdasarkan lelang ke berapa
    let limit = 0;
    if (lelang == 1) {
        limit = pasar; // Lelang 1: nilai pasar
    } else if (lelang == 2) {
        limit = (pasar + likuid) / 2; // Lelang 2: rata-rata
    } else if (lelang == 3) {
        limit = likuid; // Lelang 3: nilai likuidasi
    }

    let jaminan = limit * 0.2; // 20% dari nilai limit

    // Update display
    document.getElementById('nilai_limit').value = 'Rp ' + formatRupiah(limit);
    document.getElementById('uang_jaminan').value = 'Rp ' + formatRupiah(jaminan);
    
    // Update hidden inputs
    document.getElementById('nilai_limit_hidden').value = limit;
    document.getElementById('uang_jaminan_hidden').value = jaminan;
}

function resetCalculation() {
    document.getElementById('nilai_limit').value = '';
    document.getElementById('uang_jaminan').value = '';
    document.getElementById('nilai_limit_hidden').value = '';
    document.getElementById('uang_jaminan_hidden').value = '';
}

function formatRupiah(angka) {
    return new Intl.NumberFormat('id-ID').format(Math.round(angka));
}

function filterLPA() {
    const input = document.getElementById("searchNasabah").value.toLowerCase();
    const rows = document.querySelectorAll("#tableLPA tbody tr");

    rows.forEach((row) => {
        if (row.querySelector('.empty-state')) return;
        const nama = row.cells[1]?.innerText.toLowerCase() || '';
        row.style.display = nama.includes(input) ? "" : "none";
    });
}

// Format number inputs on input
document.addEventListener('DOMContentLoaded', function() {
    const numberInputs = document.querySelectorAll('input[type="number"]');
    numberInputs.forEach(input => {
        // Prevent negative values
        input.addEventListener('input', function() {
            if (this.value < 0) this.value = 0;
        });
    });
});
</script>
@endpush
@endsection