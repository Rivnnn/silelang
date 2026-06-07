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

    .info-box .icon { font-size: 24px; flex-shrink: 0; }
    .info-box .content strong { display: block; margin-bottom: 6px; font-size: 15px; font-weight: 700; }
    .info-box .content p { margin: 0; font-size: 14px; line-height: 1.6; }

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

    .stat-card:hover { transform: translateY(-4px); box-shadow: 0 8px 25px rgba(0,0,0,0.12); }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0;
        width: 100%; height: 4px;
        background: var(--card-color);
    }

    .stat-icon { font-size: 36px; margin-bottom: 12px; }
    .stat-value { font-size: 32px; font-weight: 700; color: #2c3e50; margin-bottom: 5px; }
    .stat-label { font-size: 13px; color: #7f8c8d; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }

    .stat-card.pending  { --card-color: #f39c12; }
    .stat-card.approved { --card-color: #27ae60; }
    .stat-card.rejected { --card-color: #e74c3c; }

    /* ================= BOX ================= */
    .box {
        background: #fff;
        padding: 32px;
        border-radius: 14px;
        margin-bottom: 24px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.06);
        transition: all 0.3s ease;
    }

    .box:hover { box-shadow: 0 6px 20px rgba(0,0,0,0.08); }

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
        width: 4px; height: 24px;
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

    .btn-secondary:hover { background: #39C6C9; color: #fff; }

    /* ================= FORM ================= */
    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }

    .form-full { grid-column: 1 / -1; }

    .form-group { position: relative; }

    label {
        display: block;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 8px;
        font-size: 14px;
    }

    label .required { color: #dc3545; margin-left: 2px; }

    input[type="date"] {
        width: 100%;
        padding: 12px 16px;
        border-radius: 10px;
        border: 2px solid #e0e0e0;
        font-size: 14px;
        transition: all 0.3s ease;
        background: #fafafa;
    }

    input[type="date"]:focus {
        outline: none;
        border-color: #39C6C9;
        box-shadow: 0 0 0 4px rgba(57,198,201,0.1);
        background: #fff;
    }

    /* ================= SELECT2 CUSTOM STYLE ================= */
    .select2-container--custom .select2-selection--single {
        height: 48px !important;
        border: 2px solid #e0e0e0 !important;
        border-radius: 10px !important;
        background: #fafafa !important;
        display: flex !important;
        align-items: center !important;
        padding: 0 16px !important;
        transition: all 0.3s ease !important;
        outline: none !important;
    }

    .select2-container--custom.select2-container--open .select2-selection--single,
    .select2-container--custom .select2-selection--single:focus {
        border-color: #39C6C9 !important;
        box-shadow: 0 0 0 4px rgba(57,198,201,0.1) !important;
        background: #fff !important;
    }

    .select2-container--custom .select2-selection--single .select2-selection__rendered {
        color: #2c3e50 !important;
        font-size: 14px !important;
        line-height: 44px !important;
        padding: 0 !important;
    }

    .select2-container--custom .select2-selection--single .select2-selection__placeholder {
        color: #aaa !important;
    }

    .select2-container--custom .select2-selection--single .select2-selection__arrow {
        height: 46px !important;
        right: 12px !important;
    }

    .select2-container--custom .select2-selection--single .select2-selection__arrow b {
        border-color: #39C6C9 transparent transparent transparent !important;
    }

    .select2-container--custom.select2-container--open .select2-selection--single .select2-selection__arrow b {
        border-color: transparent transparent #39C6C9 transparent !important;
    }

    /* Dropdown */
    .select2-container--custom .select2-dropdown {
        border: 2px solid #39C6C9 !important;
        border-radius: 10px !important;
        box-shadow: 0 8px 24px rgba(57,198,201,0.15) !important;
        overflow: hidden !important;
        margin-top: 4px !important;
    }

    /* Search box di dalam dropdown */
    .select2-container--custom .select2-search--dropdown {
        padding: 10px 12px 8px !important;
        background: #f8fdfd !important;
        border-bottom: 1px solid #e6f9f9 !important;
    }

    .select2-container--custom .select2-search--dropdown .select2-search__field {
        border: 2px solid #e0e0e0 !important;
        border-radius: 8px !important;
        padding: 8px 12px !important;
        font-size: 13.5px !important;
        width: 100% !important;
        outline: none !important;
        background: #fff !important;
        transition: border-color 0.2s !important;
    }

    .select2-container--custom .select2-search--dropdown .select2-search__field:focus {
        border-color: #39C6C9 !important;
        box-shadow: 0 0 0 3px rgba(57,198,201,0.1) !important;
    }

    /* Hasil pencarian */
    .select2-container--custom .select2-results__option {
        padding: 10px 14px !important;
        font-size: 13.5px !important;
        color: #2c3e50 !important;
        transition: background 0.15s !important;
        cursor: pointer !important;
    }

    .select2-container--custom .select2-results__option--highlighted {
        background: #e6f9f9 !important;
        color: #1a7e80 !important;
    }

    .select2-container--custom .select2-results__option[aria-selected="true"] {
        background: #39C6C9 !important;
        color: #fff !important;
    }

    .select2-container--custom .select2-results__option--disabled {
        color: #aaa !important;
        background: #f9f9f9 !important;
    }

    /* Hint di bawah label */
    .search-hint {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 12px;
        color: #7f8c8d;
        margin-bottom: 8px;
        margin-top: -4px;
    }

    .search-hint span {
        background: #e6f9f9;
        color: #1a7e80;
        padding: 2px 8px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 11px;
    }

    /* ================= TABLE ================= */
    .table-container { overflow-x: auto; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.04); }

    table { width: 100%; border-collapse: collapse; background: #fff; }

    thead { background: linear-gradient(135deg, #39C6C9, #2FB3B6); }

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

    tbody tr { border-bottom: 1px solid #f0f0f0; transition: all 0.2s ease; }
    tbody tr:hover { background: linear-gradient(90deg, #f8fdfd 0%, #fff 100%); box-shadow: 0 2px 8px rgba(57,198,201,0.1); }
    tbody tr:last-child { border-bottom: none; }

    td { padding: 16px 14px; color: #2c3e50; font-size: 14px; }
    td strong { color: #1a1a1a; font-weight: 600; }

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

    .status-badge.pending   { background: linear-gradient(135deg, #fff3cd, #ffeaa7); color: #856404; }
    .status-badge.disetujui { background: linear-gradient(135deg, #d4edda, #c3e6cb); color: #155724; }
    .status-badge.ditolak   { background: linear-gradient(135deg, #f8d7da, #f5c6cb); color: #721c24; }

    /* ================= EMPTY STATE ================= */
    .empty-state { text-align: center; padding: 60px 20px; color: #95a5a6; }
    .empty-state .icon { font-size: 72px; display: block; margin-bottom: 16px; opacity: 0.5; }
    .empty-state h4 { font-size: 18px; margin-bottom: 8px; color: #7f8c8d; font-weight: 600; }
    .empty-state p { font-size: 14px; color: #95a5a6; }

    /* ================= ANIMATIONS ================= */
    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-20px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to   { opacity: 1; }
    }

    /* ================= RESPONSIVE ================= */
    @media (max-width: 768px) {
        .form-grid  { grid-template-columns: 1fr; }
        .stats-row  { grid-template-columns: 1fr; }
        .box        { padding: 20px; }
        .box h3     { font-size: 18px; }
    }
</style>

{{-- Load Select2 CSS --}}
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

@if(session('success'))
    <div class="alert success">
        <span style="font-size:20px;">✓</span>
        <span>{{ session('success') }}</span>
    </div>
@endif

@if(session('error'))
    <div class="alert error">
        <span style="font-size:20px;">✖</span>
        <span>{{ session('error') }}</span>
    </div>
@endif

{{-- Info Box --}}
<div class="info-box">
    <div class="icon">ℹ️</div>
    <div class="content">
        <strong>Informasi Pengajuan Lelang</strong>
        <p>Ajukan nasabah dengan status kredit macet untuk proses lelang. Admin akan mereview dan menyetujui/menolak pengajuan Anda.</p>
    </div>
</div>

{{-- Stats Cards --}}
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

{{-- Tombol Tambah --}}
<div class="box">
    <button class="btn-primary" onclick="toggleForm()">
        <span style="font-size:16px;">+</span> Ajukan Lelang Baru
    </button>
</div>

{{-- Form Pengajuan --}}
<div class="box" id="formPengajuan" style="display:none; animation: fadeIn 0.4s ease;">
    <div class="box-header">
        <h3>Form Pengajuan Lelang</h3>
        <button class="btn-secondary" onclick="toggleForm()">✖ Tutup</button>
    </div>

    <form method="POST" action="{{ route('petugas.pengajuan-lelang.store') }}">
        @csrf
        <div class="form-grid">

            {{-- SELECT NASABAH DENGAN SEARCH --}}
            <div class="form-group">
                <label for="nasabah_id">
                    Pilih Nasabah <span class="required">*</span>
                </label>
                <select
                    id="nasabah_id"
                    name="nasabah_id"
                    required
                    style="width:100%;">
                    <option value="">-- Cari atau pilih nasabah --</option>
                    @foreach($nasabah as $n)
                        <option value="{{ $n->id }}"
                            data-nik="{{ $n->nik }}"
                            data-lokasi="{{ $n->lokasi_lelang ?? '' }}">
                            {{ $n->nama_nasabah }} ({{ $n->nik }})
                        </option>
                    @endforeach
                </select>

                {{-- Preview nasabah terpilih --}}
                <div id="nasabahPreview" style="
                    display:none;
                    margin-top:10px;
                    padding:12px 16px;
                    background:#e6f9f9;
                    border-radius:10px;
                    border-left:3px solid #39C6C9;
                    font-size:13px;
                    color:#1a7e80;">
                    <strong id="previewNama"></strong><br>
                    <span style="color:#5a8a8b;">NIK: <span id="previewNik"></span></span>
                    <span id="previewLokasi" style="color:#5a8a8b; margin-left:12px;"></span>
                </div>
            </div>

            {{-- TANGGAL --}}
            <div class="form-group">
                <label for="tanggal_pengajuan">
                    Tanggal Pengajuan <span class="required">*</span>
                </label>
                <input type="date"
                       id="tanggal_pengajuan"
                       name="tanggal_pengajuan"
                       value="{{ date('Y-m-d') }}"
                       required>
            </div>

            <div class="form-full">
                <button type="submit" class="btn-primary" style="width:auto;">
                    📤 Kirim Pengajuan
                </button>
            </div>

        </div>
    </form>
</div>

{{-- Riwayat Pengajuan --}}
<div class="box">
    <div class="box-header">
        <h3>Riwayat Pengajuan Lelang</h3>
        <span style="color:#7f8c8d; font-size:13px; font-weight:500;">
            Total: {{ $pengajuan->count() }} pengajuan
        </span>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th style="width:60px;">No</th>
                    <th>Tanggal</th>
                    <th>Nama Nasabah</th>
                    <th>NIK</th>
                    <th style="text-align:center;">Status</th>
                    <th>Catatan Admin</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pengajuan as $item)
                <tr>
                    <td style="text-align:center; font-weight:600; color:#7f8c8d;">
                        {{ $loop->iteration }}
                    </td>
                    <td style="white-space:nowrap;">
                        {{ \Carbon\Carbon::parse($item->tanggal_pengajuan)->format('d M Y') }}
                    </td>
                    <td><strong>{{ $item->nasabah->nama_nasabah }}</strong></td>
                    <td style="font-family:monospace; color:#5a6c7d;">{{ $item->nasabah->nik }}</td>
                    <td style="text-align:center;">
                        <span class="status-badge {{ $item->status }}">
                            @if($item->status == 'pending')    ⏳ Pending
                            @elseif($item->status == 'disetujui') ✓ Disetujui
                            @else ✖ Ditolak
                            @endif
                        </span>
                    </td>
                    <td style="color:#5a6c7d;">
                        @if($item->catatan_admin)
                            {{ $item->catatan_admin }}
                        @else
                            <span style="color:#95a5a6; font-style:italic;">-</span>
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

@endsection

@push('scripts')
{{-- jQuery (wajib untuk Select2) --}}
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
{{-- Select2 JS --}}
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function () {

    /* ── Inisialisasi Select2 ── */
    $('#nasabah_id').select2({
        theme: 'custom',
        placeholder: '🔍 Ketik nama atau NIK nasabah...',
        allowClear: true,
        width: '100%',
        language: {
            noResults: function () {
                return 'Nasabah tidak ditemukan';
            },
            searching: function () {
                return 'Mencari...';
            }
        },
        /* Custom matcher: cari berdasarkan nama DAN NIK */
        matcher: function (params, data) {
            if (!params.term || params.term.trim() === '') return data;

            var keyword = params.term.toLowerCase();
            var nama    = (data.text || '').toLowerCase();
            var nik     = ($(data.element).data('nik') || '').toString();

            if (nama.indexOf(keyword) > -1 || nik.indexOf(keyword) > -1) {
                return data;
            }
            return null;
        }
    });

    /* ── Preview nasabah terpilih ── */
    $('#nasabah_id').on('select2:select', function (e) {
        var el     = e.params.data.element;
        var nama   = e.params.data.text.split('—')[0].trim();
        var nik    = $(el).data('nik') || '';
        var lokasi = $(el).data('lokasi') || '';

        $('#previewNama').text(nama);
        $('#previewNik').text(nik);
        $('#previewLokasi').text(lokasi ? '📍 ' + lokasi : '');
        $('#nasabahPreview').slideDown(200);
    });

    $('#nasabah_id').on('select2:clear select2:unselect', function () {
        $('#nasabahPreview').slideUp(200);
    });

});

/* ── Toggle form ── */
function toggleForm() {
    var form = document.getElementById('formPengajuan');
    if (form.style.display === 'none' || form.style.display === '') {
        form.style.display = 'block';
        form.scrollIntoView({ behavior: 'smooth', block: 'start' });
    } else {
        form.style.display = 'none';
    }
}
</script>
@endpush