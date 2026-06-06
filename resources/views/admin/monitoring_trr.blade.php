@extends('layouts.admin')

@section('title', 'Monitoring TRR')
@section('page-title', 'Monitoring TRR')

@push('styles')
<style>
/* ==================== TAB NAVIGATION ==================== */
.tab-nav {
    display: flex;
    gap: 6px;
    margin-bottom: 24px;
    border-bottom: 2px solid #e9ecef;
    padding-bottom: 0;
    flex-wrap: wrap;
}
.tab-btn {
    padding: 10px 22px;
    border: none;
    background: none;
    font-size: 14px;
    font-weight: 600;
    color: #7f8c8d;
    cursor: pointer;
    border-bottom: 3px solid transparent;
    margin-bottom: -2px;
    border-radius: 6px 6px 0 0;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    gap: 6px;
}
.tab-btn:hover { color: #2c3e50; background: #f8f9fa; }
.tab-btn.active { color: #2c3e50; border-bottom-color: #3498db; background: #f0f7ff; }

.tab-badge {
    background: #e74c3c;
    color: #fff;
    font-size: 11px;
    padding: 1px 7px;
    border-radius: 10px;
    font-weight: 700;
}
.tab-badge.warning { background: #f39c12; }
.tab-badge.success { background: #27ae60; }

/* ==================== TAB PANELS ==================== */
.tab-panel { display: none; }
.tab-panel.active { display: block; }

/* ==================== CARD ==================== */
.card {
    background: #fff;
    border-radius: 12px;
    padding: 24px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    margin-bottom: 20px;
}
.card-title {
    font-size: 16px;
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 18px;
    display: flex;
    align-items: center;
    gap: 8px;
}

/* ==================== TABEL ==================== */
.tbl { width: 100%; border-collapse: collapse; font-size: 14px; }
.tbl th {
    padding: 11px 14px;
    text-align: left;
    color: #6c757d;
    font-size: 12px;
    font-weight: 600;
    background: #f8f9fa;
    border-bottom: 2px solid #e9ecef;
}
.tbl td { padding: 11px 14px; border-bottom: 1px solid #f0f0f0; color: #2c3e50; }
.tbl tbody tr:hover { background: #fafafa; }
.tbl-wrap { overflow-x: auto; }

/* ==================== BADGE STATUS ==================== */
.badge {
    display: inline-block;
    padding: 4px 11px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    white-space: nowrap;
}
.badge-pending  { background: #fff3cd; color: #856404; }
.badge-aktif    { background: #d4edda; color: #155724; }
.badge-menunggu { background: #fff3cd; color: #856404; }
.badge-selesai  { background: #cce5ff; color: #004085; }
.badge-berhasil { background: #d4edda; color: #155724; }
.badge-gagal    { background: #f8d7da; color: #721c24; }

/* ==================== EXPORT BAR ==================== */
.export-bar {
    background: #fff;
    border-radius: 12px;
    padding: 14px 18px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    margin-bottom: 16px;
}
.export-bar-inner {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
}
.export-bar-controls {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
}

.export-bar label { font-size: 13px; font-weight: 600; color: #555; }
.export-bar select, .export-bar input[type=number], .export-bar input[type=text] {
    padding: 8px 12px;
    border: 1.5px solid #ddd;
    border-radius: 7px;
    font-size: 13px;
    box-sizing: border-box;
}
.btn-export {
    padding: 9px 20px;
    background: #27ae60;
    color: #fff;
    border: none;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: background 0.2s;
}
.btn-export:hover { background: #219a52; }

/* ==================== ALERT ==================== */
.alert {
    padding: 12px 16px;
    border-radius: 8px;
    margin-bottom: 20px;
    font-weight: 500;
    border-left: 4px solid;
}
.alert-success { background: #d4edda; color: #155724; border-color: #28a745; }
.alert-error   { background: #f8d7da; color: #721c24; border-color: #dc3545; }

/* ==================== STATS MINI ==================== */
.stats-mini {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
    gap: 14px;
    margin-bottom: 24px;
}
.stat-mini {
    background: #fff;
    border-radius: 10px;
    padding: 18px 20px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    border-left: 4px solid;
}
.stat-mini .val { font-size: 22px; font-weight: 700; color: #2c3e50; margin-bottom: 4px; }
.stat-mini .lbl { font-size: 12px; color: #7f8c8d; font-weight: 500; }

/* ==================== MODAL DETAIL HASIL ==================== */
.modal-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.55);
    z-index: 9999;
    align-items: center;
    justify-content: center;
}
.modal-overlay.show { display: flex; }
.modal-box {
    background: #fff;
    border-radius: 14px;
    width: 92%;
    max-width: 560px;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: 0 10px 40px rgba(0,0,0,0.25);
}
.modal-head {
    padding: 20px 24px;
    border-bottom: 1px solid #e9ecef;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.modal-head h3 { font-size: 17px; font-weight: 700; color: #2c3e50; margin: 0; }
.modal-close {
    background: none;
    border: none;
    font-size: 22px;
    cursor: pointer;
    color: #7f8c8d;
    line-height: 1;
}
.modal-close:hover { color: #2c3e50; }
.modal-body { padding: 24px; }
.modal-foot {
    padding: 16px 24px;
    background: #f8f9fa;
    border-top: 1px solid #e9ecef;
    display: flex;
    gap: 10px;
    justify-content: flex-end;
}

@media (max-width: 768px) {
    .stats-mini { grid-template-columns: repeat(2, 1fr); }
    .export-bar { flex-direction: column; align-items: flex-start; }
}
</style>
@endpush

@section('content')

{{-- ALERT --}}
@if(session('success'))
    <div class="alert alert-success">✓ {{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-error">✖ {{ session('error') }}</div>
@endif

{{-- STATS MINI --}}
<div class="stats-mini">
    @if($rekonsiliasi['total_pending'] > 0)
    <div class="stat-mini" style="border-color:#f39c12;">
        <div class="val" style="color:#f39c12;">{{ $rekonsiliasi['total_pending'] }}</div>
        <div class="lbl">Pengajuan Menunggu Review</div>
    </div>
    @endif
    <div class="stat-mini" style="border-color:#3498db;">
        <div class="val">Rp {{ number_format($rekonsiliasi['total_aktif'], 0, ',', '.') }}</div>
        <div class="lbl">Total TRR Aktif</div>
    </div>
    <div class="stat-mini" style="border-color:#27ae60;">
        <div class="val" style="color:#27ae60;">{{ $statsHasil['total_berhasil'] }}</div>
        <div class="lbl">Lelang Berhasil</div>
    </div>
    <div class="stat-mini" style="border-color:#e74c3c;">
        <div class="val" style="color:#e74c3c;">{{ $statsHasil['total_gagal'] }}</div>
        <div class="lbl">Lelang Gagal</div>
    </div>
    @if($statsHasil['selesai_belum_diinput'] > 0)
    <div class="stat-mini" style="border-color:#e67e22;">
        <div class="val" style="color:#e67e22;">{{ $statsHasil['selesai_belum_diinput'] }}</div>
        <div class="lbl">Selesai, Menunggu Hasil dari Petugas</div>
    </div>
    @endif
    <div class="stat-mini" style="border-color:#8e44ad;">
        <div class="val" style="color:#8e44ad;">
            Rp {{ number_format($statsHasil['total_terjual'], 0, ',', '.') }}
        </div>
        <div class="lbl">Total Nilai Terjual</div>
    </div>
</div>

{{-- REKONSILIASI --}}
<div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(180px,1fr));
            gap:16px; margin-bottom:28px;">
    <div style="background:#fff; border-radius:12px; padding:20px;
                box-shadow:0 2px 8px rgba(0,0,0,0.06); border-left:4px solid #27ae60;">
        <div style="font-size:12px; color:#7f8c8d; margin-bottom:6px;">Total TRR Selesai</div>
        <div style="font-size:20px; font-weight:700; color:#2c3e50;">
            Rp {{ number_format($rekonsiliasi['total_selesai'], 0, ',', '.') }}
        </div>
    </div>
    <div style="background:#fff; border-radius:12px; padding:20px;
                box-shadow:0 2px 8px rgba(0,0,0,0.06); border-left:4px solid #e67e22;">
        <div style="font-size:12px; color:#7f8c8d; margin-bottom:6px;">Total Realisasi</div>
        <div style="font-size:20px; font-weight:700; color:#2c3e50;">
            Rp {{ number_format($rekonsiliasi['total_realisasi'], 0, ',', '.') }}
        </div>
    </div>
    <div style="background:#fff; border-radius:12px; padding:20px;
                box-shadow:0 2px 8px rgba(0,0,0,0.06);
                border-left:4px solid {{ $rekonsiliasi['selisih'] >= 0 ? '#27ae60' : '#e74c3c' }};">
        <div style="font-size:12px; color:#7f8c8d; margin-bottom:6px;">Selisih</div>
        <div style="font-size:20px; font-weight:700;
                    color:{{ $rekonsiliasi['selisih'] >= 0 ? '#27ae60' : '#e74c3c' }};">
            Rp {{ number_format(abs($rekonsiliasi['selisih']), 0, ',', '.') }}
            {{ $rekonsiliasi['selisih'] < 0 ? '⚠️' : '' }}
        </div>
    </div>
</div>

{{-- TAB NAVIGATION --}}
<div class="tab-nav">
    <button class="tab-btn active" onclick="switchTab('pending', this)">
        ⏳ Menunggu Review
        @if($rekonsiliasi['total_pending'] > 0)
            <span class="tab-badge warning">{{ $rekonsiliasi['total_pending'] }}</span>
        @endif
    </button>
    <button class="tab-btn" onclick="switchTab('aktif', this)">
        🔥 TRR Aktif
        <span class="tab-badge" style="background:#3498db;">{{ $trrAktif->count() }}</span>
    </button>
    <button class="tab-btn" onclick="switchTab('selesai', this)">
        ✅ Selesai &amp; Hasil
        <span class="tab-badge success">{{ $trrSelesai->count() }}</span>
        @if($statsHasil['selesai_belum_diinput'] > 0)
            <span class="tab-badge" style="background:#e67e22;">
                {{ $statsHasil['selesai_belum_diinput'] }} menunggu
            </span>
        @endif
    </button>
</div>

{{-- ================================================================ --}}
{{-- TAB 1: PENGAJUAN PENDING --}}
{{-- ================================================================ --}}
<div id="tab-pending" class="tab-panel active">
    <div class="card">
        <div class="card-title">⏳ Pengajuan TRR Menunggu Review</div>
        @if($pengajuanPending->isEmpty())
            <p style="color:#7f8c8d; text-align:center; padding:30px 0;">
                Tidak ada pengajuan TRR yang menunggu review.
            </p>
        @else
        <div class="tbl-wrap">
            <table class="tbl">
                <thead>
                    <tr>
                        <th>Petugas</th>
                        <th>Nasabah</th>
                        <th>Nominal Diajukan</th>
                        <th>Keterangan</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pengajuanPending as $item)
                    <tr>
                        <td style="font-weight:600;">{{ $item->user->name ?? '-' }}</td>
                        <td>{{ $item->pengajuanLelang->nasabah->nama_nasabah ?? '-' }}</td>
                        <td style="font-weight:600;">
                            Rp {{ number_format($item->nominal_diajukan, 0, ',', '.') }}
                        </td>
                        <td style="color:#7f8c8d; font-size:13px; max-width:200px;">
                            {{ Str::limit($item->keterangan, 60) ?? '-' }}
                        </td>
                        <td style="font-size:13px;">{{ $item->created_at->format('d M Y') }}</td>
                        <td>
                            <div style="display:flex; gap:8px; flex-wrap:wrap;">
                                <a href="{{ route('admin.monitoring.trr.detail', $item->id) }}"
                                   style="padding:6px 12px; background:#3498db; color:#fff;
                                          border-radius:6px; text-decoration:none;
                                          font-size:12px; font-weight:600;">
                                    🔍 Review
                                </a>
                                <button type="button"
                                        onclick="openRejectModal({{ $item->id }}, '{{ addslashes($item->user->name ?? '-') }}', '{{ addslashes($item->pengajuanLelang->nasabah->nama_nasabah ?? '-') }}')"
                                        style="padding:6px 12px; background:#dc3545; color:#fff;
                                               border:none; border-radius:6px; cursor:pointer;
                                               font-size:12px; font-weight:600;">
                                    ✖ Tolak
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>

{{-- ================================================================ --}}
{{-- TAB 2: TRR AKTIF --}}
{{-- ================================================================ --}}
<div id="tab-aktif" class="tab-panel">
    <div class="card">
        <div class="card-title">📋 TRR Aktif &amp; Menunggu Konfirmasi Petugas</div>
        @if($daftarTrr->isEmpty())
            <p style="color:#7f8c8d; text-align:center; padding:30px 0;">
                Belum ada TRR yang aktif saat ini.
            </p>
        @else
        <div class="tbl-wrap">
            <table class="tbl">
                <thead>
                    <tr>
                        <th>No. Referensi</th>
                        <th>Nasabah</th>
                        <th>Petugas</th>
                        <th>Dana Cair</th>
                        <th>Saldo Tersisa</th>
                        <th>Tgl Cair</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($daftarTrr as $trr)
                    <tr>
                        <td style="font-weight:600;">{{ $trr->nomor_referensi }}</td>
                        <td>{{ $trr->nasabah->nama_nasabah ?? '-' }}</td>
                        <td>{{ $trr->petugas->name ?? '-' }}</td>
                        <td>Rp {{ number_format($trr->nominal_disetujui, 0, ',', '.') }}</td>
                        <td style="font-weight:600;
                                   color:{{ $trr->saldo_terakhir < ($trr->nominal_disetujui * 0.2) ? '#e74c3c' : '#27ae60' }}">
                            Rp {{ number_format($trr->saldo_terakhir, 0, ',', '.') }}
                        </td>
                        <td>{{ $trr->tanggal_cair->format('d M Y') }}</td>
                        <td>
                            @if($trr->status === 'menunggu_konfirmasi')
                                <span class="badge badge-menunggu">Menunggu Konfirmasi</span>
                            @else
                                <span class="badge badge-aktif">Aktif</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>

{{-- ================================================================ --}}
{{-- TAB 3: TRR SELESAI + HASIL LELANG (admin hanya lihat) --}}
{{-- ================================================================ --}}
<div id="tab-selesai" class="tab-panel">

    {{-- EXPORT BAR (dengan filter keyword, status, tahun) --}}
    <form method="GET" action="{{ route('admin.monitoring.trr.export') }}" class="export-bar">
        <div class="export-bar-inner">
            <span style="font-size:13px; font-weight:600; color:#555; display:flex; align-items:center; gap:6px;">
                📥 Export hasil lelang (PDF)
            </span>
            <div class="export-bar-controls">
                <select name="status" style="font-size:13px; padding:7px 10px; border:1.5px solid #ddd; border-radius:7px;">
                    <option value="">Semua status</option>
                    <option value="berhasil" {{ request('status') === 'berhasil' ? 'selected' : '' }}>Berhasil</option>
                    <option value="gagal"    {{ request('status') === 'gagal'    ? 'selected' : '' }}>Gagal</option>
                </select>
                <input type="number" name="tahun" placeholder="{{ date('Y') }}"
                    value="{{ request('tahun') }}" min="2020" max="{{ date('Y') }}"
                    style="width:82px; font-size:13px; padding:7px 10px; border:1.5px solid #ddd; border-radius:7px;">
                <input type="text" name="keyword" placeholder="Cari nasabah / no. ref..."
                    value="{{ request('keyword') }}"
                    style="width:200px; font-size:13px; padding:7px 10px; border:1.5px solid #ddd; border-radius:7px;">
                <button type="submit" class="btn-export">
                    ⬇ Export PDF
                </button>
            </div>
        </div>
    </form>

    {{-- INFO: admin hanya bisa melihat, input dari petugas --}}
    @if($statsHasil['selesai_belum_diinput'] > 0)
    <div style="background:#fff3cd; border:1px solid #ffc107; border-radius:8px;
                padding:12px 16px; margin-bottom:16px; font-size:13px; color:#856404;">
        ℹ️ Terdapat <strong>{{ $statsHasil['selesai_belum_diinput'] }}</strong> TRR selesai yang
        <strong>belum ada hasil lelangnya</strong>. Petugas terkait perlu menginput hasil
        melalui halaman Buku Kas masing-masing.
    </div>
    @endif

    <div class="card">
        <div class="card-title">✅ TRR Selesai Rekap Hasil Lelang</div>
        @if($trrSelesai->isEmpty())
            <p style="color:#7f8c8d; text-align:center; padding:30px 0;">
                Belum ada TRR yang selesai.
            </p>
        @else
        <div class="tbl-wrap">
            <table class="tbl">
                <thead>
                    <tr>
                        <th>No. Referensi</th>
                        <th>Nasabah</th>
                        <th>Petugas</th>
                        <th>Dana Cair</th>
                        <th>Total Biaya</th>
                        <th>Sisa Akhir</th>
                        <th>Tgl Selesai</th>
                        <th>Hasil Lelang</th>
                        <th>Detail</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($trrSelesai as $trr)
                    <tr>
                        <td style="font-weight:600;">{{ $trr->nomor_referensi }}</td>
                        <td>{{ $trr->nasabah->nama_nasabah ?? '-' }}</td>
                        <td>{{ $trr->petugas->name ?? '-' }}</td>
                        <td>Rp {{ number_format($trr->nominal_disetujui, 0, ',', '.') }}</td>
                        <td style="color:#e74c3c; font-weight:600;">
                            Rp {{ number_format($trr->total_pengeluaran, 0, ',', '.') }}
                        </td>
                        <td style="color:#27ae60; font-weight:600;">
                            Rp {{ number_format($trr->sisa_akhir, 0, ',', '.') }}
                        </td>
                        <td>{{ $trr->updated_at->format('d M Y') }}</td>
                        <td>
                            @if($trr->hasilLelang)
                                @if($trr->hasilLelang->status_hasil === 'berhasil')
                                    <span class="badge badge-berhasil">✓ Berhasil</span>
                                @else
                                    <span class="badge badge-gagal">✖ Gagal</span>
                                @endif
                            @else
                                <span class="badge"
                                      style="background:#fff3cd; color:#856404;">
                                    ⏳ Menunggu Input Petugas
                                </span>
                            @endif
                        </td>
                        <td>
                            @if($trr->hasilLelang)
                                <button type="button"
                                        onclick="openDetailModal({{ json_encode([
                                            'nomor'         => $trr->nomor_referensi,
                                            'nasabah'       => $trr->nasabah->nama_nasabah ?? '-',
                                            'petugas'       => $trr->petugas->name ?? '-',
                                            'status'        => $trr->hasilLelang->status_hasil,
                                            'tgl_lelang'    => $trr->hasilLelang->tanggal_lelang?->format('d M Y') ?? '-',
                                            'harga_terjual' => $trr->hasilLelang->harga_terjual,
                                            'pemenang'      => $trr->hasilLelang->nama_pemenang ?? '-',
                                            'biaya'         => $trr->hasilLelang->total_biaya_realisasi,
                                            'sisa_kembali'  => $trr->hasilLelang->sisa_dana_dikembalikan,
                                            'catatan'       => $trr->hasilLelang->catatan ?? '-',
                                            'diinput_oleh'  => $trr->hasilLelang->diinputOleh->name ?? '-',
                                            'diinput_tgl'   => $trr->hasilLelang->created_at->format('d M Y H:i'),
                                        ]) }})"
                                        style="padding:6px 12px; background:#6c757d; color:#fff;
                                               border:none; border-radius:6px; cursor:pointer;
                                               font-size:12px; font-weight:600;">
                                    🔍 Detail
                                </button>
                            @else
                                <span style="font-size:12px; color:#aaa;">—</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>

{{-- ================================================================ --}}
{{-- MODAL: DETAIL HASIL LELANG (read-only) --}}
{{-- ================================================================ --}}
<div class="modal-overlay" id="modalDetail">
    <div class="modal-box">
        <div class="modal-head">
            <h3>🔍 Detail Hasil Lelang</h3>
            <button class="modal-close" onclick="closeModal('modalDetail')">×</button>
        </div>
        <div class="modal-body" id="detailBody"></div>
        <div class="modal-foot">
            <button onclick="closeModal('modalDetail')"
                    style="padding:10px 20px; background:#6c757d; color:#fff;
                           border:none; border-radius:8px; cursor:pointer; font-size:14px;">
                Tutup
            </button>
        </div>
    </div>
</div>

{{-- ================================================================ --}}
{{-- MODAL: TOLAK PENGAJUAN TRR --}}
{{-- ================================================================ --}}
<div class="modal-overlay" id="modalReject">
    <div class="modal-box">
        <div class="modal-head" style="background:linear-gradient(135deg,#dc3545,#c82333);">
            <h3 style="color:#fff;">✖ Tolak Pengajuan TRR</h3>
            <button class="modal-close" style="color:#fff;" onclick="closeModal('modalReject')">×</button>
        </div>
        <form id="formReject" method="POST">
            @csrf
            <div class="modal-body">
                <div style="background:#f8f9fa; border-radius:8px; padding:14px; margin-bottom:16px; font-size:13px;">
                    <div style="display:flex; justify-content:space-between; margin-bottom:6px;">
                        <span style="color:#6c757d;">Petugas</span>
                        <strong id="rejectPetugas"></strong>
                    </div>
                    <div style="display:flex; justify-content:space-between;">
                        <span style="color:#6c757d;">Nasabah</span>
                        <strong id="rejectNasabah"></strong>
                    </div>
                </div>
                <div style="margin-bottom:4px;">
                    <label style="display:block; font-size:13px; font-weight:600; color:#555; margin-bottom:5px;">
                        Alasan Penolakan <span style="color:#dc3545;">*</span>
                    </label>
                    <textarea name="catatan_admin" id="textareaAlasan" rows="3" required minlength="10"
                              placeholder="Jelaskan alasan penolakan (minimal 10 karakter)..."
                              oninput="checkRejectBtn()"
                              style="width:100%; padding:9px 12px; border:1.5px solid #ddd;
                                     border-radius:7px; font-size:13px; box-sizing:border-box;
                                     font-family:inherit; resize:vertical;"></textarea>
                    <div id="rejectCounter" style="font-size:12px; color:#aaa; text-align:right; margin-top:4px;">
                        0 / 10 minimum
                    </div>
                </div>
            </div>
            <div class="modal-foot">
                <button type="button" onclick="closeModal('modalReject')"
                        style="padding:10px 20px; background:#6c757d; color:#fff;
                               border:none; border-radius:8px; cursor:pointer; font-size:14px;">
                    Batal
                </button>
                <button type="submit" id="btnRejectSubmit" disabled
                        style="padding:10px 20px; background:#dc3545; color:#fff;
                               border:none; border-radius:8px; cursor:not-allowed;
                               font-size:14px; font-weight:600; opacity:0.6;">
                    ✖ Tolak
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
// ============================================================
// TAB SWITCHING
// ============================================================
function switchTab(name, btn) {
    document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    document.getElementById('tab-' + name).classList.add('active');
    btn.classList.add('active');
}

if (window.location.hash === '#selesai') {
    const btn = document.querySelectorAll('.tab-btn')[2];
    if (btn) switchTab('selesai', btn);
}

// ============================================================
// MODAL HELPER
// ============================================================
function openModal(id) {
    document.getElementById(id).classList.add('show');
    document.body.style.overflow = 'hidden';
}
function closeModal(id) {
    document.getElementById(id).classList.remove('show');
    document.body.style.overflow = '';
}
document.querySelectorAll('.modal-overlay').forEach(el => {
    el.addEventListener('click', function(e) {
        if (e.target === this) closeModal(this.id);
    });
});
document.addEventListener('keydown', e => {
    if (e.key === 'Escape')
        document.querySelectorAll('.modal-overlay.show').forEach(el => closeModal(el.id));
});

// ============================================================
// MODAL DETAIL HASIL LELANG (read-only)
// ============================================================
function openDetailModal(data) {
    const badge = data.status === 'berhasil'
        ? '<span class="badge badge-berhasil">✓ Berhasil</span>'
        : '<span class="badge badge-gagal">✖ Gagal</span>';

    const rows = [
        ['No. Referensi',          data.nomor],
        ['Nasabah',                data.nasabah],
        ['Petugas (Penginput)',     data.petugas],
        ['Status Hasil',           badge],
        ['Tanggal Lelang',         data.tgl_lelang],
        data.status === 'berhasil'
            ? ['Harga Terjual', 'Rp ' + Number(data.harga_terjual || 0).toLocaleString('id-ID')]
            : null,
        data.status === 'berhasil'
            ? ['Nama Pemenang', data.pemenang]
            : null,
        ['Total Biaya Realisasi',  'Rp ' + Number(data.biaya || 0).toLocaleString('id-ID')],
        ['Sisa Dana Dikembalikan', 'Rp ' + Number(data.sisa_kembali || 0).toLocaleString('id-ID')],
        ['Catatan',                data.catatan || '—'],
        ['Diinput Oleh',           data.diinput_oleh],
        ['Tanggal Input',          data.diinput_tgl],
    ].filter(Boolean);

    const html = '<div style="background:#f8f9fa; border-radius:8px; padding:16px;">'
        + rows.map(([k, v]) => `
            <div style="display:flex; justify-content:space-between; align-items:flex-start;
                        padding:8px 0; border-bottom:1px solid #e9ecef; font-size:13px; gap:12px;">
                <span style="color:#6c757d; white-space:nowrap; min-width:170px;">${k}</span>
                <strong style="text-align:right;">${v}</strong>
            </div>`).join('')
        + '</div>';

    document.getElementById('detailBody').innerHTML = html;
    openModal('modalDetail');
}

// ============================================================
// MODAL TOLAK TRR PENDING
// ============================================================
function openRejectModal(id, petugas, nasabah) {
    document.getElementById('rejectPetugas').textContent  = petugas;
    document.getElementById('rejectNasabah').textContent  = nasabah;
    document.getElementById('textareaAlasan').value       = '';
    checkRejectBtn();
    document.getElementById('formReject').action =
        '{{ route("admin.monitoring.trr.reject", ":id") }}'.replace(':id', id);
    openModal('modalReject');
    setTimeout(() => document.getElementById('textareaAlasan').focus(), 300);
}

function checkRejectBtn() {
    const val     = document.getElementById('textareaAlasan').value;
    const btn     = document.getElementById('btnRejectSubmit');
    const counter = document.getElementById('rejectCounter');
    counter.textContent  = val.length + ' / 10 minimum';
    counter.style.color  = val.length >= 10 ? '#27ae60' : '#aaa';
    btn.disabled         = val.length < 10;
    btn.style.opacity    = val.length >= 10 ? '1' : '0.6';
    btn.style.cursor     = val.length >= 10 ? 'pointer' : 'not-allowed';
}
</script>
@endpush