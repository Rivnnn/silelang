@extends('layouts.petugas')

@section('title', 'Dana TRR')
@section('page-title', 'Dana TRR')

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
                            {{-- Tombol konfirmasi jika ada dana TRR yang menunggu --}}
                            @if($item->danaTrr && $item->danaTrr->status === 'menunggu_konfirmasi')
                                <form method="POST"
                                      action="{{ route('petugas.dana-trr.konfirmasi', $item->danaTrr->id) }}"
                                      onsubmit="return confirm('Konfirmasi bahwa dana sudah diterima?')">
                                    @csrf
                                    <button type="submit"
                                            style="padding:6px 12px; background:#27ae60; color:#fff;
                                                   border:none; border-radius:6px; cursor:pointer;
                                                   font-size:12px; font-weight:600;">
                                        ✓ Konfirmasi Terima Dana
                                    </button>
                                </form>
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
        📒 Dana TRR Aktif — Buku Kas
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

@endsection