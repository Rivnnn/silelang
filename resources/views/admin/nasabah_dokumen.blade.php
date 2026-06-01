@extends('layouts.admin')

@section('title', 'Dokumen Nasabah')
@section('page-title', 'Detail Dokumen Nasabah')

@section('content')
<style>
    /* ================= INFO BOX ================= */
    .info-box {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 30px;
        border-radius: 14px;
        color: #fff;
        margin-bottom: 30px;
        box-shadow: 0 8px 25px rgba(102,126,234,0.25);
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-top: 20px;
    }

    .info-item {
        background: rgba(255,255,255,0.15);
        padding: 16px;
        border-radius: 10px;
        backdrop-filter: blur(10px);
    }

    .info-label {
        font-size: 12px;
        opacity: 0.9;
        margin-bottom: 6px;
        font-weight: 500;
    }

    .info-value {
        font-size: 16px;
        font-weight: 600;
    }

    /* ================= SECTION BOX ================= */
    .section-box {
        background: #fff;
        padding: 28px;
        border-radius: 14px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.06);
        margin-bottom: 24px;
    }

    .section-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 2px solid #e0e0e0;
    }

    .section-header h3 {
        color: #2c3e50;
        font-size: 18px;
        font-weight: 600;
    }

    .section-icon {
        font-size: 24px;
    }

    /* ================= DOKUMEN LIST ================= */
    .dokumen-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 16px;
    }

    .dokumen-card {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 12px;
        border: 2px solid #e0e0e0;
        transition: all 0.3s ease;
    }

    .dokumen-card:hover {
        border-color: #3498db;
        transform: translateY(-3px);
        box-shadow: 0 4px 12px rgba(52,152,219,0.15);
    }

    .dokumen-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 15px;
    }

    .dokumen-icon {
        width: 45px;
        height: 45px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        flex-shrink: 0;
    }

    .dokumen-icon.gdrive {
        background: linear-gradient(135deg, #4285f4, #34a853);
    }

    .dokumen-icon.onedrive {
        background: linear-gradient(135deg, #0078d4, #50e6ff);
    }

    .dokumen-icon.dropbox {
        background: linear-gradient(135deg, #0061ff, #00d4ff);
    }

    .dokumen-icon.other {
        background: linear-gradient(135deg, #7f8c8d, #95a5a6);
    }

    .dokumen-info {
        flex: 1;
        min-width: 0;
    }

    .dokumen-name {
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 5px;
        font-size: 14px;
        word-wrap: break-word;
    }

    .dokumen-type {
        font-size: 11px;
        color: #7f8c8d;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .dokumen-link {
        font-size: 11px;
        color: #3498db;
        text-decoration: none;
        word-break: break-all;
        display: block;
        margin-top: 8px;
        padding: 8px;
        background: #fff;
        border-radius: 6px;
        border: 1px solid #e0e0e0;
    }

    .dokumen-link:hover {
        background: #ecf0f1;
    }

    .dokumen-actions {
        display: flex;
        gap: 8px;
        margin-top: 12px;
    }

    .btn-open, .btn-copy {
        flex: 1;
        padding: 10px 16px;
        text-decoration: none;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        border: none;
        cursor: pointer;
    }

    .btn-open {
        background: linear-gradient(135deg, #3498db, #2980b9);
        color: #fff;
    }

    .btn-open:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(52,152,219,0.3);
    }

    .btn-copy {
        background: #ecf0f1;
        color: #2c3e50;
    }

    .btn-copy:hover {
        background: #bdc3c7;
    }

    .btn-copy.copied {
        background: #27ae60;
        color: #fff;
    }

    /* ================= LPA INFO ================= */
    .lpa-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
    }

    .lpa-item {
        padding: 16px;
        background: #f8f9fa;
        border-radius: 10px;
        border-left: 4px solid #3498db;
    }

    .lpa-item-label {
        font-size: 12px;
        color: #7f8c8d;
        margin-bottom: 6px;
        font-weight: 600;
    }

    .lpa-item-value {
        font-size: 16px;
        font-weight: 600;
        color: #2c3e50;
    }

    /* ================= EMPTY STATE ================= */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #95a5a6;
    }

    .empty-state span {
        font-size: 64px;
        display: block;
        margin-bottom: 20px;
    }

    .empty-state h4 {
        font-size: 20px;
        margin-bottom: 10px;
        color: #7f8c8d;
    }

    .empty-state p {
        font-size: 14px;
    }

    /* ================= BADGE ================= */
    .badge {
        display: inline-block;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .badge.tanah {
        background: #fff3cd;
        color: #856404;
    }

    .badge.bangunan {
        background: #cce5ff;
        color: #004085;
    }

    .badge.tanah-bangunan {
        background: #d4edda;
        color: #155724;
    }

    /* ================= TOAST NOTIFICATION ================= */
    .toast {
        position: fixed;
        bottom: 30px;
        right: 30px;
        background: #27ae60;
        color: #fff;
        padding: 15px 25px;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        display: none;
        align-items: center;
        gap: 10px;
        z-index: 1000;
        animation: slideIn 0.3s ease;
    }

    .toast.show {
        display: flex;
    }

    @keyframes slideIn {
        from {
            transform: translateX(400px);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
</style>

<!-- Info Box Nasabah -->
<div class="info-box">
    <h3 style="margin-bottom: 20px;">📋 {{ $nasabah->nama_nasabah }}</h3>
    
    <div class="info-grid">
        <div class="info-item">
            <div class="info-label">NIK</div>
            <div class="info-value">{{ $nasabah->nik }}</div>
        </div>
        
        <div class="info-item">
            <div class="info-label">No. HP</div>
            <div class="info-value">{{ $nasabah->no_hp }}</div>
        </div>
        
        <div class="info-item">
            <div class="info-label">Lokasi Lelang</div>
            <div class="info-value">{{ $nasabah->lokasi_lelang }}</div>
        </div>
        
        <div class="info-item">
            <div class="info-label">Jenis Lelang</div>
            <div class="info-value">
                @if($nasabah->jenis_lelang == 'Tanah')
                    <span class="badge tanah">Tanah</span>
                @elseif($nasabah->jenis_lelang == 'Bangunan')
                    <span class="badge bangunan">Bangunan</span>
                @else
                    <span class="badge tanah-bangunan">Tanah + Bangunan</span>
                @endif
            </div>
        </div>
    </div>

    <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid rgba(255,255,255,0.2);">
        <div class="info-label">Alamat Lengkap</div>
        <div class="info-value">{{ $nasabah->alamat }}</div>
    </div>

    @if($nasabah->user)
    <div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid rgba(255,255,255,0.2);">
        <div class="info-label">Petugas Input</div>
        <div class="info-value">{{ $nasabah->user->name }}</div>
    </div>
    @endif
</div>

<!-- LPA Section -->
@if($lpa)
<div class="section-box">
    <div class="section-header">
        <span class="section-icon">📊</span>
        <h3>Laporan Penilaian Agunan (LPA)</h3>
    </div>

    <div class="lpa-grid">
        <div class="lpa-item">
            <div class="lpa-item-label">Jenis Legalitas</div>
            <div class="lpa-item-value">{{ $lpa->jenis_legalitas }}</div>
        </div>

        @if($lpa->luas_tanah)
        <div class="lpa-item">
            <div class="lpa-item-label">Luas Tanah</div>
            <div class="lpa-item-value">{{ number_format($lpa->luas_tanah, 2) }} m²</div>
        </div>
        @endif

        @if($lpa->luas_bangunan)
        <div class="lpa-item">
            <div class="lpa-item-label">Luas Bangunan</div>
            <div class="lpa-item-value">{{ number_format($lpa->luas_bangunan, 2) }} m²</div>
        </div>
        @endif

        @if($lpa->spek_bangunan)
        <div class="lpa-item">
            <div class="lpa-item-label">Spesifikasi Bangunan</div>
            <div class="lpa-item-value">{{ $lpa->spek_bangunan }}</div>
        </div>
        @endif

        <div class="lpa-item" style="border-left-color: #27ae60;">
            <div class="lpa-item-label">Nilai Pasar</div>
            <div class="lpa-item-value">Rp {{ number_format($lpa->nilai_pasar, 0, ',', '.') }}</div>
        </div>

        <div class="lpa-item" style="border-left-color: #f39c12;">
            <div class="lpa-item-label">Nilai Likuidasi</div>
            <div class="lpa-item-value">Rp {{ number_format($lpa->nilai_likuidasi, 0, ',', '.') }}</div>
        </div>

        <div class="lpa-item" style="border-left-color: #e74c3c;">
            <div class="lpa-item-label">Nilai Limit</div>
            <div class="lpa-item-value">Rp {{ number_format($lpa->nilai_limit, 0, ',', '.') }}</div>
        </div>

        <div class="lpa-item">
            <div class="lpa-item-label">Lelang Ke-</div>
            <div class="lpa-item-value">{{ $lpa->lelang_ke }}</div>
        </div>

        <div class="lpa-item" style="border-left-color: #9b59b6;">
            <div class="lpa-item-label">Uang Jaminan</div>
            <div class="lpa-item-value">Rp {{ number_format($lpa->uang_jaminan, 0, ',', '.') }}</div>
        </div>
    </div>

    @if($lpa->user)
    <div style="margin-top: 20px; padding-top: 20px; border-top: 2px solid #e0e0e0; color: #7f8c8d; font-size: 13px;">
        <strong>Dibuat oleh:</strong> {{ $lpa->user->name }} • 
        <strong>Tanggal:</strong> {{ $lpa->created_at->format('d M Y H:i') }}
    </div>
    @endif
</div>
@endif

<!-- Dokumen Section -->
<div class="section-box">
    <div class="section-header">
        <span class="section-icon">📁</span>
        <h3>Dokumen Nasabah ({{ $nasabah->dokumen->count() }})</h3>
    </div>

    @if($nasabah->dokumen->count() > 0)
        <div class="dokumen-grid">
            @foreach($nasabah->dokumen as $dokumen)
            @php
                $url = $dokumen->link_dokumen;
                $platform = 'other';
                $platformIcon = '📄';
                
                if (str_contains($url, 'drive.google.com')) {
                    $platform = 'gdrive';
                    $platformIcon = '📁';
                } elseif (str_contains($url, 'onedrive.live.com') || str_contains($url, 'sharepoint.com')) {
                    $platform = 'onedrive';
                    $platformIcon = '☁️';
                } elseif (str_contains($url, 'dropbox.com')) {
                    $platform = 'dropbox';
                    $platformIcon = '📦';
                }
            @endphp
            
            <div class="dokumen-card">
                <div class="dokumen-header">
                    <div class="dokumen-icon {{ $platform }}">
                        {{ $platformIcon }}
                    </div>
                    <div class="dokumen-info">
                        <div class="dokumen-name">{{ $dokumen->nama_dokumen }}</div>
                        <div class="dokumen-type">
                            <span>🔗</span>
                            <span>
                                @if($platform == 'gdrive')
                                    Google Drive
                                @elseif($platform == 'onedrive')
                                    OneDrive
                                @elseif($platform == 'dropbox')
                                    Dropbox
                                @else
                                    Link Eksternal
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
                
                <div class="dokumen-actions">
                    <a href="{{ $url }}" 
                       class="btn-open" 
                       target="_blank"
                       rel="noopener noreferrer">
                        <span>🔗</span> Buka Dokumen
                    </a>
                    <button class="btn-copy" 
                            onclick="copyLink('{{ $url }}', this)"
                            title="Salin link">
                        <span>📋</span> Salin Link
                    </button>
                </div>

                <small class="dokumen-link" title="{{ $url }}">
                    {{ Str::limit($url, 60) }}
                </small>
            </div>
            @endforeach
        </div>
    @else
        <div class="empty-state">
            <span>📂</span>
            <h4>Belum Ada Dokumen</h4>
            <p>Dokumen untuk nasabah ini belum diupload</p>
        </div>
    @endif
</div>

<!-- Toast Notification -->
<div class="toast" id="toast">
    <span>✓</span>
    <span id="toastMessage">Link berhasil disalin!</span>
</div>

@endsection

@push('scripts')
<script>
function copyLink(url, button) {
    // Copy to clipboard
    navigator.clipboard.writeText(url).then(function() {
        // Show toast notification
        showToast('Link berhasil disalin!');
        
        // Change button state
        const originalHTML = button.innerHTML;
        button.innerHTML = '<span>✓</span> Tersalin';
        button.classList.add('copied');
        
        // Reset button after 2 seconds
        setTimeout(function() {
            button.innerHTML = originalHTML;
            button.classList.remove('copied');
        }, 2000);
    }).catch(function(err) {
        showToast('Gagal menyalin link', 'error');
        console.error('Error copying text: ', err);
    });
}

function showToast(message, type = 'success') {
    const toast = document.getElementById('toast');
    const toastMessage = document.getElementById('toastMessage');
    
    toastMessage.textContent = message;
    
    if (type === 'error') {
        toast.style.background = '#e74c3c';
    } else {
        toast.style.background = '#27ae60';
    }
    
    toast.classList.add('show');
    
    setTimeout(function() {
        toast.classList.remove('show');
    }, 3000);
}
</script>
@endpush