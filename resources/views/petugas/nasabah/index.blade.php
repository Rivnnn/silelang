@extends('layouts.petugas')

@section('title', 'Data Nasabah')
@section('page-title', 'Data Nasabah')

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
        --error-bg: #FEF2F2;
        --error-text: #991B1B;
        --error-border: #FCA5A5;
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
       ALERTS - ELEGANT NOTIFICATIONS
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

    .alert.error {
        background: var(--error-bg);
        color: var(--error-text);
        border-color: var(--error-border);
    }

    .alert-icon {
        font-size: 20px;
        line-height: 1;
        flex-shrink: 0;
    }

    .alert-content {
        flex: 1;
    }

    .alert-content strong {
        display: block;
        font-weight: 600;
        margin-bottom: 4px;
    }

    .alert-content ul {
        margin: 8px 0 0 0;
        padding-left: 20px;
        list-style: disc;
    }

    .alert-content li {
        margin: 4px 0;
    }

    /* ========================================
       CONTAINERS - PRECISION CARDS
    ======================================== */
    
    .container-fluid {
        max-width: 1400px;
        margin: 0 auto;
    }

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
    }

    .card-title {
        font-size: 20px;
        font-weight: 700;
        color: var(--text-primary);
        letter-spacing: -0.02em;
        margin: 0;
    }

    .card-body {
        padding: 28px;
    }

    /* ========================================
       BUTTONS - SOPHISTICATED ACTIONS
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

    .btn-primary:active {
        transform: translateY(0);
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
       FORMS - REFINED INPUTS
    ======================================== */
    
    .form-grid {
        display: grid;
        gap: 20px;
    }

    .form-grid-2 {
        grid-template-columns: repeat(2, 1fr);
    }

    .form-grid-3 {
        grid-template-columns: repeat(3, 1fr);
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

    .form-error {
        color: var(--error-text);
        font-size: 12px;
        margin-top: 6px;
        display: block;
        font-weight: 500;
    }

    .form-footer {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        margin-top: 24px;
        padding-top: 20px;
        border-top: 1px solid var(--border-light);
    }

    /* ========================================
       SEARCH - MINIMAL & ELEGANT
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
       TABLE - PRECISION DATA DISPLAY
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
    }

    .table thead {
        background: var(--bg-secondary);
    }

    .table th {
        padding: 14px 20px;
        text-align: left;
        font-size: 12px;
        font-weight: 700;
        color: var(--text-secondary);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 1px solid var(--border-light);
        white-space: nowrap;
    }

    .table td {
        padding: 16px 20px;
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

    .table-number {
        color: var(--text-muted);
        font-weight: 600;
        font-size: 13px;
    }

    .table-name {
        font-weight: 600;
        color: var(--text-primary);
    }

    /* ========================================
       BADGES - REFINED STATUS INDICATORS
    ======================================== */
    
    .badge {
        display: inline-flex;
        align-items: center;
        padding: 5px 12px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        letter-spacing: 0.02em;
    }

    .badge-nik {
        background: var(--bg-tertiary);
        color: var(--text-secondary);
        font-family: 'Courier New', monospace;
    }

    .badge-jenis {
        background: rgba(57, 198, 201, 0.1);
        color: var(--primary);
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* ========================================
       EMPTY STATE - SOPHISTICATED
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
       MODAL OVERLAY - SMOOTH TRANSITION
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
       RESPONSIVE DESIGN
    ======================================== */
    
    @media (max-width: 1024px) {
        .form-grid-3 {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .card-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 16px;
        }

        .form-grid-2,
        .form-grid-3 {
            grid-template-columns: 1fr;
        }

        .table th,
        .table td {
            padding: 12px 14px;
            font-size: 13px;
        }

        .search-wrapper {
            max-width: 100%;
        }
    }

    /* ========================================
       UTILITY CLASSES
    ======================================== */
    
    .text-center { text-align: center; }
    .text-right { text-align: right; }
    .font-semibold { font-weight: 600; }
    .font-bold { font-weight: 700; }
    .truncate {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
</style>

<div class="container-fluid">
    <!-- Alerts -->
    @if(session('success'))
        <div class="alert success">
            <span class="alert-icon">✓</span>
            <div class="alert-content">{{ session('success') }}</div>
        </div>
    @endif

    @if(session('error'))
        <div class="alert error">
            <span class="alert-icon">✕</span>
            <div class="alert-content">{{ session('error') }}</div>
        </div>
    @endif

    @if($errors->any())
        <div class="alert error">
            <span class="alert-icon">⚠</span>
            <div class="alert-content">
                <strong>Terjadi Kesalahan Validasi</strong>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <!-- Header Card -->
    <div class="card">
        <div class="card-header">
            <h1 class="card-title">Data Nasabah</h1>
            <button class="btn btn-primary" onclick="toggleForm()">
                <span class="btn-icon">+</span>
                <span>Tambah Nasabah</span>
            </button>
        </div>
    </div>

    <!-- Form Card -->
    <div class="card form-modal" id="formNasabah" style="display:none;">
        <div class="card-header">
            <h2 class="card-title">Form Pendaftaran Nasabah</h2>
            <span class="close-btn" onclick="toggleForm()">✕</span>
        </div>
        
        <div class="card-body">
            <form method="POST" action="{{ route('petugas.nasabah.store') }}" id="nasabahForm">
                @csrf
                
                <div class="form-grid form-grid-2">
                    <div class="form-group">
                        <label class="form-label">
                            Nama Nasabah <span class="required">*</span>
                        </label>
                        <input 
                            type="text" 
                            name="nama_nasabah" 
                            class="form-control"
                            placeholder="Contoh: Ahmad Fauzi"
                            value="{{ old('nama_nasabah') }}"
                            required
                        >
                        @error('nama_nasabah')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">
                            NIK (16 Digit) <span class="required">*</span>
                        </label>
                        <input 
                            type="text" 
                            name="nik" 
                            class="form-control"
                            placeholder="Contoh: 3201234567891234"
                            value="{{ old('nik') }}"
                            maxlength="16"
                            pattern="[0-9]{16}"
                            required
                        >
                        @error('nik')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">
                        Alamat Lengkap <span class="required">*</span>
                    </label>
                    <textarea 
                        name="alamat" 
                        class="form-control"
                        placeholder="Masukkan alamat lengkap nasabah"
                        required
                    >{{ old('alamat') }}</textarea>
                    @error('alamat')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="form-grid form-grid-3">
                    <div class="form-group">
                        <label class="form-label">
                            No. Handphone <span class="required">*</span>
                        </label>
                        <input 
                            type="text" 
                            name="no_hp" 
                            class="form-control"
                            placeholder="Contoh: 081234567890"
                            value="{{ old('no_hp') }}"
                            required
                        >
                        @error('no_hp')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">
                            Lokasi Lelang <span class="required">*</span>
                        </label>
                        <input 
                            type="text" 
                            name="lokasi_lelang" 
                            class="form-control"
                            placeholder="Contoh: Bandung"
                            value="{{ old('lokasi_lelang') }}"
                            required
                        >
                        @error('lokasi_lelang')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">
                            Jenis Agunan <span class="required">*</span>
                        </label>
                        <select name="jenis_lelang" class="form-control" required>
                            <option value="">Pilih Jenis</option>
                            <option value="Tanah" {{ old('jenis_lelang') == 'Tanah' ? 'selected' : '' }}>Tanah</option>
                            <option value="Bangunan" {{ old('jenis_lelang') == 'Bangunan' ? 'selected' : '' }}>Bangunan</option>
                            <option value="Tanah Berikut Bangunan" {{ old('jenis_lelang') == 'Tanah Berikut Bangunan' ? 'selected' : '' }}>Tanah + Bangunan</option>
                        </select>
                        @error('jenis_lelang')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-footer">
                    <button type="button" class="btn btn-outline" onclick="toggleForm()">
                        <span>Batal</span>
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <span class="btn-icon">💾</span>
                        <span>Simpan Data</span>
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
                    placeholder="Cari nama, NIK, atau jenis agunan..." 
                    onkeyup="filterNasabah()"
                >
            </div>

            <!-- Table -->
            <div class="table-container">
                <div class="table-responsive">
                    <table class="table" id="tableNasabah">
                        <thead>
                            <tr>
                                <th style="width: 60px;" class="text-center">No</th>
                                <th>Nama Nasabah</th>
                                <th>NIK</th>
                                <th>Alamat</th>
                                <th>No. HP</th>
                                <th>Lokasi</th>
                                <th>Jenis</th>
                                <th class="text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($nasabah as $item)
                            <tr>
                                <td class="text-center">
                                    <span class="table-number">{{ $loop->iteration }}</span>
                                </td>
                                <td>
                                    <span class="table-name">{{ $item->nama_nasabah }}</span>
                                </td>
                                <td>
                                    <span class="badge badge-nik">{{ $item->nik }}</span>
                                </td>
                                <td>
                                    <span class="truncate" style="max-width: 200px; display: inline-block;" title="{{ $item->alamat }}">
                                        {{ Str::limit($item->alamat, 30) }}
                                    </span>
                                </td>
                                <td>{{ $item->no_hp }}</td>
                                <td>{{ $item->lokasi_lelang }}</td>
                                <td>
                                    <span class="badge badge-jenis">{{ $item->jenis_lelang }}</span>
                                </td>
                                <td class="text-right">
                                    <a href="{{ route('petugas.nasabah.dokumen', $item->id) }}" class="btn btn-outline">
                                        <span>📄</span>
                                        <span>Dokumen</span>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8">
                                    <div class="empty-state">
                                        <div class="empty-icon">📂</div>
                                        <div class="empty-title">Belum Ada Data Nasabah</div>
                                        <div class="empty-text">Klik tombol "Tambah Nasabah" untuk mulai menambahkan data</div>
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
    const form = document.getElementById('formNasabah');
    const isHidden = form.style.display === 'none';
    
    form.style.display = isHidden ? 'block' : 'none';
    
    if (isHidden) {
        form.scrollIntoView({ behavior: 'smooth', block: 'start' });
        // Focus on first input
        setTimeout(() => {
            form.querySelector('input[name="nama_nasabah"]').focus();
        }, 300);
    }
}

function filterNasabah() {
    const input = document.getElementById("searchNasabah").value.toLowerCase();
    const rows = document.querySelectorAll("#tableNasabah tbody tr");

    rows.forEach((row) => {
        if (row.querySelector('.empty-state')) return;
        const content = row.innerText.toLowerCase();
        row.style.display = content.includes(input) ? "" : "none";
    });
}

// Auto show form if there are validation errors
@if($errors->any() || old('nama_nasabah'))
    document.getElementById('formNasabah').style.display = 'block';
@endif

// NIK input validation - only numbers
const nikInput = document.querySelector('input[name="nik"]');
if (nikInput) {
    nikInput.addEventListener('input', function(e) {
        this.value = this.value.replace(/[^0-9]/g, '').substring(0, 16);
    });
}

// Phone number validation - only numbers and + at start
const phoneInput = document.querySelector('input[name="no_hp"]');
if (phoneInput) {
    phoneInput.addEventListener('input', function(e) {
        let value = this.value;
        // Allow + only at the start
        if (value.indexOf('+') > 0) {
            value = value.replace(/\+/g, '');
        }
        // Remove all non-digit characters except + at start
        this.value = value.replace(/[^\d+]/g, '');
    });
}
</script>
@endpush
@endsection