@extends('layouts.petugas')

@section('title', 'Buku Kas | ' . $trr->nomor_referensi)
@section('page-title', 'Buku Kas TRR')

@push('styles')
<style>
    /* ==================== MODAL ==================== */
    .modal-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.55);
        z-index: 9999;
        align-items: center;
        justify-content: center;
    }

    .modal-overlay.show {
        display: flex;
    }

    .modal-box {
        background: #fff;
        border-radius: 14px;
        width: 94%;
        max-width: 580px;
        max-height: 92vh;
        overflow-y: auto;
        box-shadow: 0 12px 50px rgba(0, 0, 0, 0.3);
    }

    .modal-head {
        padding: 20px 24px;
        border-bottom: 1px solid #e9ecef;
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: sticky;
        top: 0;
        background: #fff;
        z-index: 1;
        border-radius: 14px 14px 0 0;
    }

    .modal-head h3 {
        font-size: 17px;
        font-weight: 700;
        color: #2c3e50;
        margin: 0;
    }

    .modal-close {
        background: none;
        border: none;
        font-size: 24px;
        cursor: pointer;
        color: #7f8c8d;
        line-height: 1;
        padding: 0 4px;
    }

    .modal-close:hover {
        color: #2c3e50;
    }

    .modal-body {
        padding: 24px;
    }

    .modal-foot {
        padding: 16px 24px;
        background: #f8f9fa;
        border-top: 1px solid #e9ecef;
        display: flex;
        gap: 10px;
        justify-content: flex-end;
        border-radius: 0 0 14px 14px;
    }

    /* ==================== FORM FIELDS ==================== */
    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px;
        margin-bottom: 14px;
    }

    .form-row.single {
        grid-template-columns: 1fr;
    }

    .form-group label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: #555;
        margin-bottom: 5px;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 9px 12px;
        border: 1.5px solid #ddd;
        border-radius: 8px;
        font-size: 13px;
        box-sizing: border-box;
        font-family: inherit;
        transition: border-color 0.2s;
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: #39C6C9;
    }

    .req {
        color: #dc3545;
    }

    /* ==================== BADGE ==================== */
    .badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .badge-aktif {
        background: #d4edda;
        color: #155724;
    }

    .badge-selesai {
        background: #cce5ff;
        color: #004085;
    }

    .badge-berhasil {
        background: #d4edda;
        color: #155724;
    }

    .badge-gagal {
        background: #f8d7da;
        color: #721c24;
    }

    @media (max-width: 768px) {
        .form-row {
            grid-template-columns: 1fr;
        }

        .ledger-grid {
            grid-template-columns: 1fr !important;
        }
    }

    @media print {

        .sidebar,
        .header,
        .no-print,
        button,
        a[href] {
            display: none !important;
        }

        .main {
            margin-left: 0 !important;
        }

        .content {
            padding: 0 !important;
        }
    }
</style>
@endpush

@section('content')

{{-- ===== HEADER BAR ===== --}}
<div style="background:#fff; border-radius:12px; padding:20px 24px;
            box-shadow:0 2px 8px rgba(0,0,0,0.06); margin-bottom:20px;
            display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px;">
    <div>
        <div style="font-size:12px; color:#7f8c8d; margin-bottom:2px;">Nomor Referensi</div>
        <div style="font-size:20px; font-weight:700; color:#2c3e50;">{{ $trr->nomor_referensi }}</div>
        <div style="font-size:13px; color:#7f8c8d; margin-top:4px;">
            Nasabah: <strong>{{ $trr->nasabah->nama_nasabah ?? '-' }}</strong>
            &nbsp;|&nbsp;
            @if($trr->status === 'aktif')
            <span class="badge badge-aktif">🔥 Aktif</span>
            @else
            <span class="badge badge-selesai">✅ Selesai</span>
            @if($trr->hasilLelang)
            &nbsp;
            @if($trr->hasilLelang->status_hasil === 'berhasil')
            <span class="badge badge-berhasil">Lelang Berhasil</span>
            @else
            <span class="badge badge-gagal">Lelang Gagal</span>
            @endif
            @endif
            @endif
        </div>
    </div>

    <div style="display:flex; gap:10px; flex-wrap:wrap;" class="no-print">
        <button onclick="window.print()"
            style="padding:9px 16px; background:#6c757d; color:#fff;
                       border:none; border-radius:8px; cursor:pointer; font-size:13px; font-weight:600;">
            🖨️ Cetak
        </button>
        <a href="{{ route('petugas.dana-trr.export-pdf', $trr->id) }}"
            style="padding:9px 16px; background:#e74c3c; color:#fff;
                  border-radius:8px; text-decoration:none; font-size:13px; font-weight:600;">
            📄 Export PDF
        </a>
        @if($trr->status === 'aktif')
        <button type="button" onclick="openSelesaiModal()"
            style="padding:9px 16px; background:#8e44ad; color:#fff;
                           border:none; border-radius:8px; cursor:pointer;
                           font-size:13px; font-weight:600;">
            ✅ Selesaikan Lelang
        </button>
        @endif
    </div>
</div>

{{-- ALERT --}}
@if(session('success'))
<div style="background:#d4edda; color:#155724; padding:12px 16px;
                border-radius:8px; margin-bottom:16px; border-left:4px solid #28a745;">
    ✓ {{ session('success') }}
</div>
@endif
@if(session('error'))
<div style="background:#f8d7da; color:#721c24; padding:12px 16px;
                border-radius:8px; margin-bottom:16px; border-left:4px solid #dc3545;">
    ✖ {{ session('error') }}
</div>
@endif

{{-- ===== GRID: TABEL LEDGER + FORM SAMPING ===== --}}
<div class="ledger-grid" style="display:grid; grid-template-columns:1fr 340px; gap:20px; align-items:start;">

    {{-- TABEL LEDGER --}}
    <div style="background:#fff; border-radius:12px; padding:24px;
                box-shadow:0 2px 8px rgba(0,0,0,0.06);">
        <h3 style="margin-bottom:16px; color:#2c3e50; font-size:15px; font-weight:600;">
            📒 Rincian Pengeluaran
        </h3>
        <div style="overflow-x:auto;">
            <table style="width:100%; border-collapse:collapse; font-size:14px;">
                <thead>
                    <tr style="background:#f8f9fa; border-bottom:2px solid #e9ecef;">
                        <th style="padding:10px 12px; text-align:left; color:#6c757d; font-size:12px;">No</th>
                        <th style="padding:10px 12px; text-align:left; color:#6c757d; font-size:12px;">Keterangan</th>
                        <th style="padding:10px 12px; text-align:right; color:#6c757d; font-size:12px;">Kredit (Rp)</th>
                        <th style="padding:10px 12px; text-align:right; color:#6c757d; font-size:12px;">Debet (Rp)</th>
                        <th style="padding:10px 12px; text-align:right; color:#6c757d; font-size:12px;">Sisa Saldo (Rp)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($trr->ledger->sortBy('id') as $i => $baris)
                    <tr style="border-bottom:1px solid #f0f0f0; background:{{ $i === 0 ? '#f0fff4' : 'transparent' }}">
                        <td style="padding:10px 12px; color:#7f8c8d;">{{ $i + 1 }}</td>
                        <td style="padding:10px 12px;">{{ $baris->keterangan }}</td>
                        <td style="padding:10px 12px; text-align:right; color:#27ae60;">
                            {{ $baris->kredit > 0 ? number_format($baris->kredit, 0, ',', '.') : '—' }}
                        </td>
                        <td style="padding:10px 12px; text-align:right; color:#e74c3c;">
                            {{ $baris->debet > 0 ? number_format($baris->debet, 0, ',', '.') : '—' }}
                        </td>
                        <td style="padding:10px 12px; text-align:right; font-weight:600; color:#2c3e50;">
                            {{ number_format($baris->sisa_saldo, 0, ',', '.') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr style="background:#f8f9fa; border-top:2px solid #dee2e6; font-weight:700;">
                        <td colspan="2" style="padding:12px; text-align:right; color:#2c3e50;">TOTAL</td>
                        <td style="padding:12px; text-align:right; color:#27ae60;">
                            {{ number_format($ringkasan['total_kredit'], 0, ',', '.') }}
                        </td>
                        <td style="padding:12px; text-align:right; color:#e74c3c;">
                            {{ number_format($ringkasan['total_debet'], 0, ',', '.') }}
                        </td>
                        <td style="padding:12px; text-align:right; font-size:15px;
                                   color:{{ $ringkasan['sisa_saldo_akhir'] >= 0 ? '#27ae60' : '#e74c3c' }}">
                            {{ number_format($ringkasan['sisa_saldo_akhir'], 0, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        {{-- INFO HASIL LELANG jika sudah selesai --}}
        @if($trr->hasilLelang)
        <div style="margin-top:20px; background:#f0f9ff; border:1px solid #bee3f8;
                    border-radius:10px; padding:16px;">
            <div style="font-size:13px; font-weight:700; color:#2c3e50; margin-bottom:12px;">
                📋 Ringkasan Hasil Lelang
            </div>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:8px; font-size:13px;">
                <div>
                    <span style="color:#7f8c8d;">Status Hasil</span><br>
                    @if($trr->hasilLelang->status_hasil === 'berhasil')
                    <strong style="color:#27ae60;">✓ Berhasil / Terjual</strong>
                    @else
                    <strong style="color:#e74c3c;">✖ Gagal / Tidak Ada Pemenang</strong>
                    @endif
                </div>
                <div>
                    <span style="color:#7f8c8d;">Tanggal Lelang</span><br>
                    <strong>{{ $trr->hasilLelang->tanggal_lelang?->format('d M Y') ?? '-' }}</strong>
                </div>
                @if($trr->hasilLelang->status_hasil === 'berhasil')
                <div>
                    <span style="color:#7f8c8d;">Harga Terjual</span><br>
                    <strong>Rp {{ number_format($trr->hasilLelang->harga_terjual ?? 0, 0, ',', '.') }}</strong>
                </div>
                <div>
                    <span style="color:#7f8c8d;">Nama Pemenang</span><br>
                    <strong>{{ $trr->hasilLelang->nama_pemenang ?? '-' }}</strong>
                </div>
                @endif
                <div>
                    <span style="color:#7f8c8d;">Sisa Dana Dikembalikan</span><br>
                    <strong style="color:#27ae60;">Rp {{ number_format($trr->hasilLelang->sisa_dana_dikembalikan, 0, ',', '.') }}</strong>
                </div>
                @if($trr->hasilLelang->catatan)
                <div style="grid-column:span 2;">
                    <span style="color:#7f8c8d;">Catatan</span><br>
                    <strong>{{ $trr->hasilLelang->catatan }}</strong>
                </div>
                @endif
            </div>
        </div>
        @endif
    </div>

    {{-- SIDEBAR KANAN: FORM INPUT --}}
    <div style="display:flex; flex-direction:column; gap:16px;">

        {{-- FORM CATAT PENGELUARAN --}}
        @if($trr->status === 'aktif')
        <div class="no-print" style="background:#fff; border-radius:12px; padding:24px;
                    box-shadow:0 2px 8px rgba(0,0,0,0.06);">
            <h3 style="margin-bottom:16px; color:#2c3e50; font-size:15px; font-weight:600;">
                ✏️ Catat Pengeluaran Baru
            </h3>

            @php $saldoHabis = $ringkasan['sisa_saldo_akhir'] <= 0; @endphp

                @if($saldoHabis)
                {{-- PERINGATAN SALDO HABIS --}}
                <div style="background:#fff3cd; border:1px solid #ffc107; border-radius:10px;
                        padding:16px; text-align:center; margin-bottom:16px;">
                <div style="font-size:32px; margin-bottom:8px;">⚠️</div>
                <div style="font-size:14px; font-weight:700; color:#856404; margin-bottom:4px;">
                    Saldo Dana Habis
                </div>
                <div style="font-size:12px; color:#856404;">
                    Sisa saldo Rp 0. Tidak dapat mencatat pengeluaran baru.
                </div>
        </div>
        @endif

        <form method="POST" action="{{ route('petugas.dana-trr.ledger.store', $trr->id) }}"
            id="formPengeluaran">
            @csrf
            <div style="margin-bottom:14px;" class="form-group">
                <label>Keterangan <span class="req">*</span></label>
                <input type="text" name="keterangan"
                    placeholder="Contoh: Biaya pengumuman koran"
                    value="{{ old('keterangan') }}"
                    {{ $saldoHabis ? 'disabled' : '' }} required>
                @error('keterangan')
                <span style="color:#e74c3c; font-size:12px;">{{ $message }}</span>
                @enderror
            </div>
            <div style="margin-bottom:14px;" class="form-group">
                <label>Nominal Pengeluaran (Rp) <span class="req">*</span></label>
                <input type="number" name="debet"
                    placeholder="Contoh: 500000"
                    value="{{ old('debet') }}"
                    min="100" step="100"
                    {{ $saldoHabis ? 'disabled' : '' }} required>
                @error('debet')
                <span style="color:#e74c3c; font-size:12px;">{{ $message }}</span>
                @enderror
            </div>
            <div style="background:#f0f9ff; border:1px solid #bee3f8; border-radius:8px;
                            padding:10px 14px; margin-bottom:14px; font-size:13px;">
                Saldo tersedia:
                <strong style="color:{{ $ringkasan['sisa_saldo_akhir'] > 0 ? '#2980b9' : '#e74c3c' }};">
                    Rp {{ number_format($ringkasan['sisa_saldo_akhir'], 0, ',', '.') }}
                </strong>
            </div>
            <button type="{{ $saldoHabis ? 'button' : 'submit' }}"
                onclick="{{ $saldoHabis ? 'openSaldoHabisModal()' : '' }}"
                style="width:100%; padding:11px;
                            background:{{ $saldoHabis ? '#adb5bd' : '#39C6C9' }};
                            color:#fff; border:none; border-radius:8px;
                            font-size:14px; font-weight:600;
                            cursor:{{ $saldoHabis ? 'not-allowed' : 'pointer' }};">
                {{ $saldoHabis ? '🚫 Saldo Tidak Mencukupi' : '+ Catat Pengeluaran' }}
            </button>
        </form>
    </div>
    @else
    <div style="background:#fff; border-radius:12px; padding:24px;
                    box-shadow:0 2px 8px rgba(0,0,0,0.06); color:#7f8c8d; text-align:center;">
        <div style="font-size:32px; margin-bottom:8px;">🔒</div>
        <p style="font-size:13px;">Buku kas sudah ditutup.<br>Tidak bisa menambah pengeluaran baru.</p>
    </div>
    @endif

</div>
</div>

{{-- ================================================================ --}}
{{-- MODAL: FORM SELESAIKAN LELANG (input hasil) --}}
{{-- ================================================================ --}}
@if($trr->status === 'aktif')
<div class="modal-overlay no-print" id="modalSelesai">
    <div class="modal-box">
        <div class="modal-head" style="background:linear-gradient(135deg,#8e44ad,#7d3c98);">
            <h3 style="color:#fff;">✅ Selesaikan Lelang & Input Hasil</h3>
            <button class="modal-close" style="color:#fff;" onclick="closeModal()">×</button>
        </div>

        <form method="POST" action="{{ route('petugas.dana-trr.selesai', $trr->id) }}"
            id="formSelesai">
            @csrf
            <div class="modal-body">

                {{-- Info TRR --}}
                <div style="background:#f8f0ff; border:1px solid #d7aefb; border-radius:10px;
                            padding:14px; margin-bottom:18px; font-size:13px;
                            display:grid; grid-template-columns:1fr 1fr; gap:8px;">
                    <div>
                        <span style="color:#7f8c8d;">No. Referensi</span><br>
                        <strong>{{ $trr->nomor_referensi }}</strong>
                    </div>
                    <div>
                        <span style="color:#7f8c8d;">Nasabah</span><br>
                        <strong>{{ $trr->nasabah->nama_nasabah ?? '-' }}</strong>
                    </div>
                    <div>
                        <span style="color:#7f8c8d;">Total Pengeluaran</span><br>
                        <strong style="color:#e74c3c;">
                            Rp {{ number_format($ringkasan['total_debet'], 0, ',', '.') }}
                        </strong>
                    </div>
                    <div>
                        <span style="color:#7f8c8d;">Sisa Saldo (estimasi kembali)</span><br>
                        <strong style="color:#27ae60;">
                            Rp {{ number_format($ringkasan['sisa_saldo_akhir'], 0, ',', '.') }}
                        </strong>
                    </div>
                </div>

                {{-- PERINGATAN --}}
                <div style="background:#fff3cd; border:1px solid #ffc107; border-radius:8px;
                            padding:10px 14px; margin-bottom:18px; font-size:13px; color:#856404;">
                    ⚠️ <strong>Perhatian:</strong> Setelah form ini dikirim, buku kas akan ditutup dan
                    tidak bisa ditambah pengeluaran baru. Pastikan semua biaya sudah dicatat.
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Status Hasil <span class="req">*</span></label>
                        <select name="status_hasil" id="sel_status"
                            onchange="toggleBerhasilFields(this.value)" required>
                            <option value="">— Pilih —</option>
                            <option value="berhasil">✓ Berhasil (Terjual)</option>
                            <option value="gagal">✖ Gagal (Tidak Ada Pemenang)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Tanggal Lelang <span class="req">*</span></label>
                        <input type="date" name="tanggal_lelang"
                            max="{{ date('Y-m-d') }}" required>
                    </div>
                </div>

                {{-- Fields khusus jika berhasil --}}
                <div id="div_berhasil" style="display:none;">
                    <div class="form-row">
                        <div class="form-group">
                            <label>Harga Terjual (Rp)</label>
                            <input type="number" name="harga_terjual"
                                min="0" step="1000"
                                placeholder="Contoh: 500000000">
                        </div>
                        <div class="form-group">
                            <label>Nama Pemenang / Pembeli</label>
                            <input type="text" name="nama_pemenang"
                                placeholder="Nama perorangan / badan usaha">
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Sisa Dana Dikembalikan (Rp) <span class="req">*</span></label>
                        <input type="number" name="sisa_dana_dikembalikan"
                            id="inp_sisa_kembali"
                            min="0" step="100" required
                            value="{{ max(0, $ringkasan['sisa_saldo_akhir']) }}">
                    </div>
                </div>

                <div class="form-group">
                    <label>Catatan Tambahan</label>
                    <textarea name="catatan" rows="3"
                        placeholder="Catatan tentang jalannya lelang, kendala, dsb..."></textarea>
                </div>
            </div>

            <div class="modal-foot">
                <button type="button" onclick="closeModal()"
                    style="padding:10px 20px; background:#6c757d; color:#fff;
                               border:none; border-radius:8px; cursor:pointer; font-size:14px;">
                    Batal
                </button>
                <button type="submit" id="btnSelesaiSubmit"
                    style="padding:10px 24px; background:#8e44ad; color:#fff;
                               border:none; border-radius:8px; cursor:pointer;
                               font-size:14px; font-weight:600;">
                    💾 Tutup Buku Kas & Simpan Hasil
                </button>
            </div>
        </form>
    </div>
</div>
@endif

{{-- MODAL KONFIRMASI SIMPAN --}}
<div class="modal-overlay no-print" id="modalKonfirmasi">
    <div class="modal-box" style="max-width:420px;">
        <div class="modal-head" style="background:linear-gradient(135deg,#27ae60,#1e8449);">
            <h3 style="color:#fff;">⚠️ Konfirmasi Penyimpanan</h3>
            <button class="modal-close" style="color:#fff;" onclick="closeKonfirmasiModal()">×</button>
        </div>
        <div class="modal-body" style="text-align:center; padding:32px 24px;">
            <div style="font-size:48px; margin-bottom:16px;">🔒</div>
            <div style="font-size:15px; font-weight:700; color:#2c3e50; margin-bottom:8px;">
                Tutup Buku Kas?
            </div>
            <div style="font-size:13px; color:#7f8c8d; margin-bottom:6px;">
                Status Hasil: <strong id="konfirmasi_status_label" style="color:#8e44ad;">—</strong>
            </div>
            <div style="background:#fff3cd; border:1px solid #ffc107; border-radius:8px;
                        padding:10px 14px; margin-top:16px; font-size:13px; color:#856404; text-align:left;">
                ⚠️ Tindakan ini <strong>tidak dapat dibatalkan</strong>.
                Semua pengeluaran akan dikunci dan hasil lelang tersimpan permanen.
            </div>
        </div>
        <div class="modal-foot" style="justify-content:center; gap:12px;">
            <button type="button" onclick="closeKonfirmasiModal()"
                style="padding:10px 24px; background:#6c757d; color:#fff;
                           border:none; border-radius:8px; cursor:pointer; font-size:14px;">
                Batal
            </button>
            <button type="button" id="btnKonfirmasiYa" onclick="submitSelesai()"
                style="padding:10px 28px; background:#27ae60; color:#fff;
                           border:none; border-radius:8px; cursor:pointer;
                           font-size:14px; font-weight:700;">
                ✅ Ya, Simpan & Tutup
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    (function() {
        // ── Modal Selesai ──────────────────────────────
        function openSelesaiModal() {
            document.getElementById('modalSelesai').classList.add('show');
            document.body.style.overflow = 'hidden';
        }

        function closeSelesaiModal() {
            document.getElementById('modalSelesai').classList.remove('show');
            document.body.style.overflow = '';
        }

        // ── Modal Konfirmasi ───────────────────────────
        function openKonfirmasiModal() {
            const status = document.getElementById('sel_status').value;

            if (!status) {
                alert('Pilih status hasil lelang terlebih dahulu.');
                return false;
            }

            const tanggal = document.querySelector('[name="tanggal_lelang"]').value;
            if (!tanggal) {
                alert('Tanggal lelang wajib diisi.');
                return false;
            }

            const sisa = document.getElementById('inp_sisa_kembali').value;
            if (sisa === '' || sisa < 0) {
                alert('Sisa dana dikembalikan wajib diisi.');
                return false;
            }

            const label = status === 'berhasil' ?
                '✓ BERHASIL (Terjual)' :
                '✖ GAGAL (Tidak Ada Pemenang)';

            document.getElementById('konfirmasi_status_label').textContent = label;
            document.getElementById('modalKonfirmasi').classList.add('show');
        }

        function closeKonfirmasiModal() {
            document.getElementById('modalKonfirmasi').classList.remove('show');
            // Jangan kembalikan overflow, modal selesai masih terbuka
        }

        function submitSelesai() {
            document.getElementById('btnKonfirmasiYa').disabled = true;
            document.getElementById('btnKonfirmasiYa').textContent = '⏳ Menyimpan...';
            document.getElementById('formSelesai').submit();
        }

        // ── Toggle field berhasil ──────────────────────
        function toggleBerhasilFields(status) {
            const div = document.getElementById('div_berhasil');
            if (div) div.style.display = status === 'berhasil' ? 'block' : 'none';
        }

        // ── Backdrop klik tutup ────────────────────────
        const modalSelesaiEl = document.getElementById('modalSelesai');
        if (modalSelesaiEl) {
            modalSelesaiEl.addEventListener('click', function(e) {
                if (e.target === this) closeSelesaiModal();
            });
        }

        const modalKonfirmasiEl = document.getElementById('modalKonfirmasi');
        if (modalKonfirmasiEl) {
            modalKonfirmasiEl.addEventListener('click', function(e) {
                if (e.target === this) closeKonfirmasiModal();
            });
        }

        // ── Escape tutup modal ─────────────────────────
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeKonfirmasiModal();
                closeSelesaiModal();
            }
        });

        // ── Intercept submit → buka modal konfirmasi ──
        const formSelesai = document.getElementById('formSelesai');
        if (formSelesai) {
            formSelesai.addEventListener('submit', function(e) {
                e.preventDefault(); // selalu cegah submit langsung
                openKonfirmasiModal(); // buka modal konfirmasi dulu
            });
        }

        // ── Expose ke global (dipanggil dari onclick HTML) ──
        window.openSelesaiModal = openSelesaiModal;
        window.closeModal = closeSelesaiModal; // alias lama tetap jalan
        window.closeKonfirmasiModal = closeKonfirmasiModal;
        window.submitSelesai = submitSelesai;
        window.toggleBerhasilFields = toggleBerhasilFields;

    })();
</script>
@endpush