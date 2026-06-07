@extends('layouts.petugas')

@section('title', 'Dana TRR')
@section('page-title', 'Dana TRR')

@push('styles')
<style>
/* ===== MODAL ===== */
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
    border-radius: 16px;
    width: 94%;
    max-width: 480px;
    box-shadow: 0 12px 50px rgba(0,0,0,0.25);
    overflow: hidden;
    animation: fadeUp 0.2s ease;
}

@keyframes fadeUp {
    from { opacity: 0; transform: translateY(16px); }
    to   { opacity: 1; transform: translateY(0); }
}

.modal-head {
    background: linear-gradient(135deg, #27ae60, #1e8449);
    padding: 20px 24px;
    display: flex;
    align-items: center;
    gap: 14px;
}

.modal-head-icon {
    width: 46px;
    height: 46px;
    border-radius: 12px;
    background: rgba(255,255,255,0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    flex-shrink: 0;
}

.modal-head-text h3 {
    font-size: 16px;
    font-weight: 700;
    color: #fff;
    margin: 0 0 3px;
}

.modal-head-text p {
    font-size: 12px;
    color: rgba(255,255,255,0.8);
    margin: 0;
}

.modal-body { padding: 22px 24px; }

.info-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
    margin-bottom: 16px;
}

.info-card {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 12px 14px;
}

.info-card .lbl {
    font-size: 11px;
    color: #7f8c8d;
    margin-bottom: 4px;
    font-weight: 500;
}

.info-card .val {
    font-size: 14px;
    font-weight: 700;
    color: #2c3e50;
}

.info-card.highlight {
    background: #e8f8f0;
    border: 1px solid #b7e4c7;
    grid-column: 1 / -1;
}

.info-card.highlight .val {
    font-size: 20px;
    color: #27ae60;
}

.warning-box {
    background: #fff3cd;
    border: 1px solid #ffc107;
    border-radius: 8px;
    padding: 10px 14px;
    font-size: 13px;
    color: #856404;
    margin-bottom: 0;
    line-height: 1.5;
}

.modal-foot {
    padding: 16px 24px;
    background: #f8f9fa;
    border-top: 1px solid #e9ecef;
    display: flex;
    gap: 10px;
    justify-content: flex-end;
}

.btn-cancel {
    padding: 10px 22px;
    background: #fff;
    color: #6c757d;
    border: 2px solid #dee2e6;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    font-family: inherit;
    transition: all 0.2s;
}
.btn-cancel:hover { background: #f8f9fa; border-color: #adb5bd; }

.btn-konfirmasi {
    padding: 10px 24px;
    background: #27ae60;
    color: #fff;
    border: none;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 700;
    cursor: pointer;
    font-family: inherit;
    transition: all 0.2s;
}
.btn-konfirmasi:hover { background: #1e8449; transform: translateY(-1px); }
.btn-konfirmasi:active { transform: translateY(0); }
.btn-konfirmasi:disabled {
    background: #95a5a6;
    cursor: not-allowed;
    transform: none;
}
</style>
@endpush

@section('content')

@if(session('success'))
    <div style="background:#d4edda; color:#155724; padding:12px 16px;
                border-radius:8px; margin-bottom:20px; border-left:4px solid #28a745;">
        ✓ {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div style="background:#f8d7da; color:#721c24; padding:12px 16px;
                border-radius:8px; margin-bottom:20px; border-left:4px solid #dc3545;">
        ✖ {{ session('error') }}
    </div>
@endif

{{-- ===== SEKSI 1: FORM AJUKAN TRR BARU ===== --}}
@if($lelangDisetujui->isNotEmpty())
<div style="background:#fff; border-radius:12px; padding:24px;
            box-shadow:0 2px 8px rgba(0,0,0,0.06); margin-bottom:24px;
            border-left:4px solid #39C6C9;">

    <h3 style="margin-bottom:16px; color:#2c3e50; font-size:15px; font-weight:600;">
        💰 Ajukan Dana TRR Baru
    </h3>
    <p style="font-size:13px; color:#7f8c8d; margin-bottom:16px;">
        Pengajuan lelang berikut sudah disetujui dan bisa diajukan Dana TRR-nya.
    </p>

    <form method="POST" action="{{ route('petugas.pengajuan-trr.store') }}">
        @csrf
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:16px;">
            <div>
                <label style="display:block; font-size:13px; font-weight:600;
                               color:#555; margin-bottom:6px;">
                    Pilih Pengajuan Lelang <span style="color:#dc3545;">*</span>
                </label>
                <select name="pengajuan_lelang_id" required
                        style="width:100%; padding:10px 12px; border:1px solid #ddd;
                               border-radius:8px; font-size:14px; box-sizing:border-box;">
                    <option value="">— Pilih Nasabah —</option>
                    @foreach($lelangDisetujui as $lelang)
                        <option value="{{ $lelang->id }}">
                            {{ $lelang->nasabah->nama_nasabah }}
                            ({{ \Carbon\Carbon::parse($lelang->tanggal_pengajuan)->format('d M Y') }})
                        </option>
                    @endforeach
                </select>
                @error('pengajuan_lelang_id')
                    <span style="color:#e74c3c; font-size:12px;">{{ $message }}</span>
                @enderror
            </div>
            <div>
                <label style="display:block; font-size:13px; font-weight:600;
                               color:#555; margin-bottom:6px;">
                    Nominal Dana Diajukan (Rp) <span style="color:#dc3545;">*</span>
                </label>
                <input type="number" name="nominal_diajukan"
                       placeholder="Contoh: 5000000"
                       value="{{ old('nominal_diajukan') }}"
                       min="1000" step="1000" required
                       style="width:100%; padding:10px 12px; border:1px solid #ddd;
                              border-radius:8px; font-size:14px; box-sizing:border-box;">
                @error('nominal_diajukan')
                    <span style="color:#e74c3c; font-size:12px;">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div style="margin-bottom:16px;">
            <label style="display:block; font-size:13px; font-weight:600;
                           color:#555; margin-bottom:6px;">
                Keterangan Kebutuhan Dana <span style="color:#dc3545;">*</span>
            </label>
            <textarea name="keterangan" rows="3" required
                      placeholder="Contoh: Dana untuk biaya pengumuman koran, SKPT, dan biaya operasional lelang..."
                      style="width:100%; padding:10px 12px; border:1px solid #ddd;
                             border-radius:8px; font-size:14px; box-sizing:border-box;
                             resize:vertical;">{{ old('keterangan') }}</textarea>
            @error('keterangan')
                <span style="color:#e74c3c; font-size:12px;">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit"
                style="padding:10px 24px; background:#39C6C9; color:#fff;
                       border:none; border-radius:8px; font-size:14px;
                       font-weight:600; cursor:pointer;">
            Kirim Pengajuan Dana TRR →
        </button>
    </form>
</div>
@endif

{{-- ===== SEKSI 2: STATUS PENGAJUAN TRR ===== --}}
<div style="background:#fff; border-radius:12px; padding:24px;
            box-shadow:0 2px 8px rgba(0,0,0,0.06); margin-bottom:24px;">

    <h3 style="margin-bottom:16px; color:#2c3e50; font-size:15px; font-weight:600;">
        📋 Status Pengajuan TRR
    </h3>

    @if($pengajuanTrr->isEmpty())
        <p style="color:#7f8c8d; text-align:center; padding:30px 0; font-size:14px;">
            Belum ada pengajuan TRR.
        </p>
    @else
        <div style="overflow-x:auto;">
            <table style="width:100%; border-collapse:collapse; font-size:14px;">
                <thead>
                    <tr style="background:#f8f9fa; border-bottom:2px solid #e9ecef;">
                        <th style="padding:10px 14px; text-align:left; color:#6c757d;">Nasabah</th>
                        <th style="padding:10px 14px; text-align:left; color:#6c757d;">Nominal Diajukan</th>
                        <th style="padding:10px 14px; text-align:left; color:#6c757d;">Nominal Disetujui</th>
                        <th style="padding:10px 14px; text-align:left; color:#6c757d;">Status</th>
                        <th style="padding:10px 14px; text-align:left; color:#6c757d;">Catatan Admin</th>
                        <th style="padding:10px 14px; text-align:left; color:#6c757d;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pengajuanTrr as $item)
                    <tr style="border-bottom:1px solid #f0f0f0;">
                        <td style="padding:10px 14px; font-weight:600;">
                            {{ $item->pengajuanLelang->nasabah->nama_nasabah ?? '-' }}
                        </td>
                        <td style="padding:10px 14px;">
                            Rp {{ number_format($item->nominal_diajukan, 0, ',', '.') }}
                        </td>
                        <td style="padding:10px 14px; font-weight:600; color:#27ae60;">
                            @if($item->nominal_disetujui)
                                Rp {{ number_format($item->nominal_disetujui, 0, ',', '.') }}
                            @else
                                <span style="color:#aaa;">—</span>
                            @endif
                        </td>
                        <td style="padding:10px 14px;">
                            @if($item->status === 'pending')
                                <span style="background:#fff3cd; color:#856404; padding:4px 10px;
                                             border-radius:20px; font-size:12px; font-weight:600;">
                                    ⏳ Menunggu Review
                                </span>
                            @elseif($item->status === 'disetujui')
                                <span style="background:#d4edda; color:#155724; padding:4px 10px;
                                             border-radius:20px; font-size:12px; font-weight:600;">
                                    ✓ Disetujui
                                </span>
                            @elseif($item->status === 'ditolak')
                                <span style="background:#f8d7da; color:#721c24; padding:4px 10px;
                                             border-radius:20px; font-size:12px; font-weight:600;">
                                    ✖ Ditolak
                                </span>
                            @endif
                        </td>
                        <td style="padding:10px 14px; font-size:13px; color:#7f8c8d;">
                            {{ $item->catatan_admin ?? '—' }}
                        </td>
                        <td style="padding:10px 14px;">
                            {{-- Tombol buka modal konfirmasi --}}
                            @if($item->danaTrr && $item->danaTrr->status === 'menunggu_konfirmasi')
                                <button type="button"
                                        onclick="openKonfirmasiModal(
                                            {{ $item->danaTrr->id }},
                                            '{{ addslashes($item->pengajuanLelang->nasabah->nama_nasabah ?? '-') }}',
                                            '{{ number_format($item->danaTrr->nominal_disetujui, 0, ',', '.') }}',
                                            '{{ $item->danaTrr->nomor_referensi }}',
                                            '{{ $item->danaTrr->tanggal_cair->format('d M Y') }}'
                                        )"
                                        style="padding:6px 12px; background:#27ae60; color:#fff;
                                               border:none; border-radius:6px; cursor:pointer;
                                               font-size:12px; font-weight:600;">
                                    ✓ Konfirmasi Terima Dana
                                </button>

                            @elseif($item->danaTrr && in_array($item->danaTrr->status, ['aktif','selesai']))
                                <a href="{{ route('petugas.dana-trr.ledger', $item->danaTrr->id) }}"
                                   style="padding:6px 12px; background:#3498db; color:#fff;
                                          border-radius:6px; text-decoration:none;
                                          font-size:12px; font-weight:600;">
                                    📒 Buku Kas
                                </a>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

{{-- ===== SEKSI 3: BUKU KAS AKTIF ===== --}}
@if($daftarTrr->where('status', 'aktif')->isNotEmpty())
<div style="background:#fff; border-radius:12px; padding:24px;
            box-shadow:0 2px 8px rgba(0,0,0,0.06);">

    <h3 style="margin-bottom:16px; color:#2c3e50; font-size:15px; font-weight:600;">
        📒 Dana TRR Aktif Buku Kas
    </h3>

    <div style="overflow-x:auto;">
        <table style="width:100%; border-collapse:collapse; font-size:14px;">
            <thead>
                <tr style="background:#f8f9fa; border-bottom:2px solid #e9ecef;">
                    <th style="padding:10px 14px; text-align:left; color:#6c757d;">No. Referensi</th>
                    <th style="padding:10px 14px; text-align:left; color:#6c757d;">Nasabah</th>
                    <th style="padding:10px 14px; text-align:left; color:#6c757d;">Dana Cair</th>
                    <th style="padding:10px 14px; text-align:left; color:#6c757d;">Saldo Tersisa</th>
                    <th style="padding:10px 14px; text-align:left; color:#6c757d;">Status</th>
                    <th style="padding:10px 14px; text-align:left; color:#6c757d;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($daftarTrr as $trr)
                <tr style="border-bottom:1px solid #f0f0f0;">
                    <td style="padding:10px 14px; font-weight:600; color:#2c3e50;">
                        {{ $trr->nomor_referensi }}
                    </td>
                    <td style="padding:10px 14px;">
                        {{ $trr->nasabah->nama_nasabah ?? '-' }}
                    </td>
                    <td style="padding:10px 14px;">
                        Rp {{ number_format($trr->nominal_disetujui, 0, ',', '.') }}
                    </td>
                    <td style="padding:10px 14px; font-weight:600;
                               color:{{ $trr->saldo_terakhir < ($trr->nominal_disetujui * 0.2) ? '#e74c3c' : '#27ae60' }}">
                        Rp {{ number_format($trr->saldo_terakhir, 0, ',', '.') }}
                    </td>
                    <td style="padding:10px 14px;">
                        <span style="background:#d4edda; color:#155724; padding:4px 10px;
                                     border-radius:20px; font-size:12px; font-weight:600;">
                            Aktif
                        </span>
                    </td>
                    <td style="padding:10px 14px;">
                        <a href="{{ route('petugas.dana-trr.ledger', $trr->id) }}"
                           style="padding:6px 12px; background:#3498db; color:#fff;
                                  border-radius:6px; text-decoration:none;
                                  font-size:12px; font-weight:600;">
                            📒 Buku Kas
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

{{-- ================================================================ --}}
{{-- MODAL KONFIRMASI PENERIMAAN DANA --}}
{{-- ================================================================ --}}
<div class="modal-overlay" id="modalKonfirmasi">
    <div class="modal-box">

        {{-- Header --}}
        <div class="modal-head">
            <div class="modal-head-icon">💰</div>
            <div class="modal-head-text">
                <h3>Konfirmasi Penerimaan Dana</h3>
                <p>Pastikan Anda benar-benar sudah menerima dana ini</p>
            </div>
        </div>

        {{-- Body --}}
        <div class="modal-body">

            {{-- Info grid --}}
            <div class="info-grid">
                <div class="info-card">
                    <div class="lbl">No. Referensi</div>
                    <div class="val" id="modal_nomor_ref" style="color:#27ae60; font-size:13px;">—</div>
                </div>
                <div class="info-card">
                    <div class="lbl">Tanggal Cair</div>
                    <div class="val" id="modal_tgl_cair">—</div>
                </div>
                <div class="info-card">
                    <div class="lbl">Nasabah</div>
                    <div class="val" id="modal_nasabah">—</div>
                </div>
                <div class="info-card">
                    <div class="lbl">Petugas</div>
                    <div class="val">{{ auth()->user()->name }}</div>
                </div>

                {{-- Nominal highlight full width --}}
                <div class="info-card highlight">
                    <div class="lbl">Nominal Dana yang Diterima</div>
                    <div class="val" id="modal_nominal">—</div>
                </div>
            </div>

            {{-- Warning --}}
            <div class="warning-box">
                ⚠️ <strong>Perhatian:</strong> Dengan menekan tombol konfirmasi, Anda menyatakan
                bahwa dana sejumlah di atas <strong>sudah diterima secara fisik</strong>.
                Setelah konfirmasi, buku kas akan aktif dan Anda wajib mencatat
                setiap pengeluaran selama proses lelang.
            </div>
        </div>

        {{-- Footer --}}
        <div class="modal-foot">
            <button type="button"
                    class="btn-cancel"
                    onclick="closeKonfirmasiModal()">
                Batal, Cek Lagi
            </button>
            <form id="formKonfirmasi" method="POST" style="margin:0;">
                @csrf
                <button type="submit"
                        id="btnKonfirmasiSubmit"
                        class="btn-konfirmasi">
                    ✓ Ya, Dana Sudah Saya Terima
                </button>
            </form>
        </div>

    </div>
</div>

@endsection

@push('scripts')
<script>
function openKonfirmasiModal(trrId, nasabah, nominal, nomorRef, tanggalCair) {
    // Isi data ke modal
    document.getElementById('modal_nasabah').textContent   = nasabah;
    document.getElementById('modal_nominal').textContent   = 'Rp ' + nominal;
    document.getElementById('modal_nomor_ref').textContent = nomorRef;
    document.getElementById('modal_tgl_cair').textContent  = tanggalCair;

    // Set action form ke route konfirmasi yang benar
    document.getElementById('formKonfirmasi').action =
        '{{ route("petugas.dana-trr.konfirmasi", ":id") }}'.replace(':id', trrId);

    // Reset tombol jika sebelumnya disabled
    const btn = document.getElementById('btnKonfirmasiSubmit');
    btn.disabled    = false;
    btn.textContent = '✓ Ya, Dana Sudah Saya Terima';

    // Tampilkan modal
    document.getElementById('modalKonfirmasi').classList.add('show');
    document.body.style.overflow = 'hidden';
}

function closeKonfirmasiModal() {
    document.getElementById('modalKonfirmasi').classList.remove('show');
    document.body.style.overflow = '';
}

// Cegah double submit
document.getElementById('formKonfirmasi').addEventListener('submit', function() {
    const btn = document.getElementById('btnKonfirmasiSubmit');
    btn.disabled    = true;
    btn.textContent = '⏳ Memproses...';
});

// Tutup modal klik backdrop
document.getElementById('modalKonfirmasi').addEventListener('click', function(e) {
    if (e.target === this) closeKonfirmasiModal();
});

// Tutup modal ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeKonfirmasiModal();
});
</script>
@endpush