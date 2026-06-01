@extends('layouts.petugas')

@section('title', 'Upload Dokumen')
@section('page-title', 'Upload Dokumen Nasabah')

@php
    $showBackButton = true;
    $backUrl = route('petugas.dokumen.list');
@endphp

@section('content')
<style>
    * {
        scrollbar-width: thin;
        scrollbar-color: #39C6C9 #f0f0f0;
    }

    *::-webkit-scrollbar {
        width: 6px;
        height: 6px;
    }

    *::-webkit-scrollbar-track {
        background: #f0f0f0;
        border-radius: 10px;
    }

    *::-webkit-scrollbar-thumb {
        background: #39C6C9;
        border-radius: 10px;
    }

    .page-container {
        max-width: 1000px;
        margin: 0 auto;
    }

    /* Info Header - More Compact */
    .info-header {
        background: linear-gradient(135deg, #39C6C9 0%, #2FB3B6 100%);
        padding: 20px 24px;
        border-radius: 12px;
        margin-bottom: 20px;
        color: #fff;
        box-shadow: 0 4px 12px rgba(57,198,201,0.2);
    }

    .info-label {
        font-size: 11px;
        font-weight: 600;
        opacity: 0.85;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 4px;
    }

    .info-value {
        font-size: 18px;
        font-weight: 700;
        margin-bottom: 2px;
    }

    .info-subtitle {
        font-size: 12px;
        opacity: 0.8;
    }

    /* Alert - More Compact */
    .alert {
        padding: 12px 16px;
        border-radius: 8px;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 13px;
        animation: slideDown 0.3s ease;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .alert.success {
        background: #d4edda;
        color: #155724;
        border-left: 3px solid #28a745;
    }

    .alert.error {
        background: #f8d7da;
        color: #721c24;
        border-left: 3px solid #dc3545;
    }

    .alert-icon {
        font-size: 16px;
        font-weight: bold;
    }

    /* Document Cards - Compact Design */
    .documents-grid {
        display: grid;
        gap: 12px;
        margin-bottom: 20px;
    }

    .document-card {
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.04);
        transition: all 0.3s ease;
        border: 2px solid #e9ecef;
        overflow: hidden;
    }

    .document-card:hover {
        box-shadow: 0 4px 12px rgba(57,198,201,0.1);
        border-color: #39C6C9;
    }

    .document-card.filled {
        border-color: #27ae60;
        background: #f8fff9;
    }

    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 16px;
        background: #f8f9fa;
        border-bottom: 1px solid #e9ecef;
    }

    .document-card.filled .card-header {
        background: #e8f5e9;
    }

    .card-title {
        display: flex;
        align-items: center;
        gap: 10px;
        flex: 1;
    }

    .card-number {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 24px;
        height: 24px;
        background: linear-gradient(135deg, #39C6C9, #2FB3B6);
        color: #fff;
        border-radius: 6px;
        font-weight: 700;
        font-size: 12px;
        flex-shrink: 0;
    }

    .document-card.filled .card-number {
        background: linear-gradient(135deg, #27ae60, #229954);
    }

    .card-title h4 {
        color: #2c3e50;
        font-size: 13px;
        font-weight: 600;
        margin: 0;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .status-ok {
        background: #d4edda;
        color: #155724;
    }

    .status-empty {
        background: #fff3cd;
        color: #856404;
    }

    .card-body {
        padding: 12px 16px;
    }

    .input-wrapper {
        position: relative;
    }

    .input-icon {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 16px;
        color: #7f8c8d;
        transition: color 0.3s;
        pointer-events: none;
    }

    .document-input {
        width: 100%;
        padding: 10px 12px 10px 36px;
        border: 2px solid #e9ecef;
        border-radius: 8px;
        font-size: 13px;
        transition: all 0.3s ease;
        background: #f8f9fa;
        color: #2c3e50;
    }

    .document-input:focus {
        outline: none;
        border-color: #39C6C9;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(57,198,201,0.1);
    }

    .document-input:focus + .input-icon {
        color: #39C6C9;
    }

    .document-input.filled {
        border-color: #27ae60;
        background: #fff;
    }

    .document-input::placeholder {
        color: #95a5a6;
        font-size: 12px;
    }

    /* Save Section - Sticky Bottom */
    .save-section {
        position: sticky;
        bottom: 0;
        background: #fff;
        padding: 16px 20px;
        border-radius: 12px;
        box-shadow: 0 -4px 20px rgba(0,0,0,0.08);
        margin-top: 20px;
        border: 2px solid #e9ecef;
        z-index: 100;
    }

    /* Stats Grid - Compact */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 10px;
        margin-bottom: 14px;
    }

    .stat-item {
        text-align: center;
        padding: 10px;
        background: #f8f9fa;
        border-radius: 8px;
        border: 1px solid #e9ecef;
    }

    .stat-value {
        font-size: 20px;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 2px;
    }

    .stat-label {
        font-size: 10px;
        color: #7f8c8d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 600;
    }

    /* Progress Bar - Compact */
    .progress-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 8px;
    }

    .progress-text {
        font-size: 12px;
        font-weight: 600;
        color: #2c3e50;
    }

    .progress-percentage {
        font-size: 12px;
        color: #7f8c8d;
        font-weight: 600;
    }

    .progress-bar-container {
        background: #e9ecef;
        height: 8px;
        border-radius: 10px;
        overflow: hidden;
        margin-bottom: 14px;
        box-shadow: inset 0 1px 2px rgba(0,0,0,0.05);
    }

    .progress-bar {
        background: linear-gradient(90deg, #39C6C9 0%, #2FB3B6 100%);
        height: 100%;
        width: 0%;
        transition: width 0.4s ease;
        border-radius: 10px;
    }

    /* Save Button - Compact */
    .btn-save-all {
        width: 100%;
        background: linear-gradient(135deg, #39C6C9 0%, #2FB3B6 100%);
        border: none;
        padding: 12px 24px;
        border-radius: 8px;
        color: #fff;
        font-weight: 700;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 3px 10px rgba(57,198,201,0.25);
    }

    .btn-save-all:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(57,198,201,0.35);
    }

    .btn-save-all:active {
        transform: translateY(0);
    }

    .btn-save-all:disabled {
        background: #95a5a6;
        cursor: not-allowed;
        box-shadow: none;
    }

    .btn-content {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .btn-icon {
        font-size: 16px;
    }

    /* Loading Spinner */
    .spinner {
        display: inline-block;
        width: 14px;
        height: 14px;
        border: 2px solid rgba(255,255,255,0.3);
        border-top-color: #fff;
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    /* Helper Text */
    .helper-text {
        background: #e8f4f8;
        padding: 12px 16px;
        border-radius: 8px;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 12px;
        color: #2c3e50;
        border-left: 3px solid #39C6C9;
    }

    .helper-text strong {
        color: #39C6C9;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .page-container {
            padding: 0 12px;
        }

        .info-header {
            padding: 16px 18px;
        }

        .info-value {
            font-size: 16px;
        }

        .documents-grid {
            gap: 10px;
        }

        .card-header {
            padding: 10px 14px;
        }

        .card-body {
            padding: 10px 14px;
        }

        .card-title h4 {
            font-size: 12px;
        }

        .stats-grid {
            grid-template-columns: 1fr;
            gap: 8px;
        }

        .save-section {
            padding: 14px 16px;
        }

        .btn-save-all {
            padding: 11px 20px;
            font-size: 13px;
        }
    }

    /* Smooth Scroll */
    html {
        scroll-behavior: smooth;
    }

    /* Copy to Clipboard Feature */
    .copy-hint {
        font-size: 11px;
        color: #7f8c8d;
        margin-top: 4px;
        font-style: italic;
    }
</style>

@if(session('success'))
    <div class="alert success">
        <span class="alert-icon">✓</span>
        <div>{{ session('success') }}</div>
    </div>
@endif

@if(session('error'))
    <div class="alert error">
        <span class="alert-icon">✖</span>
        <div>{{ session('error') }}</div>
    </div>
@endif

<div class="page-container">
    <!-- Info Header -->
    <div class="info-header">
        <div class="info-label">👤 Nasabah</div>
        <div class="info-value">{{ $nasabah->nama_nasabah }}</div>
        <div class="info-subtitle">NIK: {{ $nasabah->nik }}</div>
    </div>

    <!-- Helper Text -->
    <div class="helper-text">
        <span>💡</span>
        <div>
            Paste link dokumen dari <strong>Google Drive</strong>, <strong>OneDrive</strong>, atau cloud storage lainnya.
            Pastikan link bisa diakses oleh admin.
        </div>
    </div>

    <form method="POST" action="{{ route('petugas.dokumen.store', $nasabah->id) }}" id="dokumenForm">
        @csrf
        
        <!-- Document Cards -->
        <div class="documents-grid">
            @foreach($dokumenWajib as $index => $nama)
            <div class="document-card" data-index="{{ $index }}">
                <div class="card-header">
                    <div class="card-title">
                        <span class="card-number">{{ $loop->iteration }}</span>
                        <h4>{{ $nama }}</h4>
                    </div>
                    @if(isset($dokumen[$nama]) && $dokumen[$nama]->link_dokumen)
                        <span class="status-badge status-ok">
                            <span>✓</span> OK
                        </span>
                    @else
                        <span class="status-badge status-empty">
                            <span>○</span> Kosong
                        </span>
                    @endif
                </div>

                <div class="card-body">
                    <div class="input-wrapper">
                        <input
                            type="url"
                            name="dokumen[{{ $index }}][link]"
                            class="document-input"
                            placeholder="https://drive.google.com/... atau https://onedrive.live.com/..."
                            value="{{ $dokumen[$nama]->link_dokumen ?? '' }}"
                            data-dokumen-name="{{ $nama }}"
                        >
                        <span class="input-icon">🔗</span>
                    </div>
                </div>
                <input type="hidden" name="dokumen[{{ $index }}][nama]" value="{{ $nama }}">
            </div>
            @endforeach
        </div>

        <!-- Save Section -->
        <div class="save-section">
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-value" id="filled-count">{{ $dokumen->count() }}</div>
                    <div class="stat-label">Terisi</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value" id="empty-count">{{ count($dokumenWajib) - $dokumen->count() }}</div>
                    <div class="stat-label">Kosong</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value">{{ count($dokumenWajib) }}</div>
                    <div class="stat-label">Total</div>
                </div>
            </div>

            <div class="progress-header">
                <div class="progress-text">Progress Upload</div>
                <div class="progress-percentage" id="progress-percentage">
                    {{ round(($dokumen->count() / count($dokumenWajib)) * 100) }}%
                </div>
            </div>
            
            <div class="progress-bar-container">
                <div class="progress-bar" id="progress-bar" style="width: {{ ($dokumen->count() / count($dokumenWajib)) * 100 }}%"></div>
            </div>

            <button type="submit" class="btn-save-all" id="saveButton">
                <span class="btn-content">
                    <span class="btn-icon">💾</span>
                    <span class="btn-text">Simpan Semua Dokumen</span>
                </span>
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('dokumenForm');
    const inputs = form.querySelectorAll('.document-input');
    const filledCount = document.getElementById('filled-count');
    const emptyCount = document.getElementById('empty-count');
    const progressBar = document.getElementById('progress-bar');
    const progressPercentage = document.getElementById('progress-percentage');
    const saveButton = document.getElementById('saveButton');
    const totalDocs = {{ count($dokumenWajib) }};

    // Validate URL
    function isValidUrl(string) {
        try {
            const url = new URL(string);
            return url.protocol === 'http:' || url.protocol === 'https:';
        } catch (_) {
            return false;
        }
    }

    function updateProgress() {
        let filled = 0;
        
        inputs.forEach((input, index) => {
            const card = input.closest('.document-card');
            const statusBadge = card.querySelector('.status-badge');
            const value = input.value.trim();
            
            if (value !== '' && isValidUrl(value)) {
                filled++;
                input.classList.add('filled');
                card.classList.add('filled');
                
                // Update status badge
                statusBadge.className = 'status-badge status-ok';
                statusBadge.innerHTML = '<span>✓</span> OK';
            } else if (value !== '' && !isValidUrl(value)) {
                // Invalid URL
                input.classList.remove('filled');
                card.classList.remove('filled');
                statusBadge.className = 'status-badge status-empty';
                statusBadge.innerHTML = '<span>⚠</span> Link Invalid';
                statusBadge.style.background = '#f8d7da';
                statusBadge.style.color = '#721c24';
            } else {
                input.classList.remove('filled');
                card.classList.remove('filled');
                
                // Update status badge
                statusBadge.className = 'status-badge status-empty';
                statusBadge.innerHTML = '<span>○</span> Kosong';
            }
        });
        
        const empty = totalDocs - filled;
        const percentage = Math.round((filled / totalDocs) * 100);
        
        // Update stats
        filledCount.textContent = filled;
        emptyCount.textContent = empty;
        
        // Update progress bar
        progressBar.style.width = percentage + '%';
        progressPercentage.textContent = percentage + '%';
    }

    // Add input event listeners with debounce
    let debounceTimer;
    inputs.forEach(input => {
        input.addEventListener('input', function() {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(updateProgress, 300);
        });
        
        input.addEventListener('paste', () => setTimeout(updateProgress, 100));
        
        // Show validation on blur
        input.addEventListener('blur', function() {
            const value = this.value.trim();
            if (value !== '' && !isValidUrl(value)) {
                this.style.borderColor = '#dc3545';
                setTimeout(() => {
                    this.style.borderColor = '';
                }, 2000);
            }
        });
    });

    // Form submit handler
    form.addEventListener('submit', function(e) {
        // Validate all URLs before submit
        let hasInvalidUrl = false;
        inputs.forEach(input => {
            const value = input.value.trim();
            if (value !== '' && !isValidUrl(value)) {
                hasInvalidUrl = true;
                input.style.borderColor = '#dc3545';
            }
        });

        if (hasInvalidUrl) {
            e.preventDefault();
            alert('Beberapa link tidak valid. Pastikan format link benar (harus diawali http:// atau https://)');
            return;
        }

        const btnContent = saveButton.querySelector('.btn-content');
        saveButton.disabled = true;
        btnContent.innerHTML = `
            <span class="spinner"></span>
            <span>Menyimpan...</span>
        `;
    });

    // Initial update
    updateProgress();

    // Auto-save to localStorage (optional)
    inputs.forEach(input => {
        const storageKey = 'dokumen_' + input.dataset.dokumenName;
        
        // Load from localStorage
        const savedValue = localStorage.getItem(storageKey);
        if (savedValue && !input.value) {
            input.value = savedValue;
        }
        
        // Save to localStorage on input
        input.addEventListener('input', function() {
            if (this.value.trim()) {
                localStorage.setItem(storageKey, this.value);
            } else {
                localStorage.removeItem(storageKey);
            }
        });
    });

    // Clear localStorage on successful submit
    form.addEventListener('submit', function() {
        if (!hasInvalidUrl) {
            inputs.forEach(input => {
                localStorage.removeItem('dokumen_' + input.dataset.dokumenName);
            });
        }
    });
});
</script>
@endpush
@endsection