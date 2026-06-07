<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>LPJ TRR | {{ $trr->nomor_referensi }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 0;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #2c3e50;
            background: #fff;
        }

        table.page-wrap {
            width: 100%;
            border-collapse: collapse;
        }
        table.page-wrap > tbody > tr > td.page-pad {
            padding: 25.4mm 25.4mm 25.4mm 25.4mm;
        }

        /* ===== KOP — pakai table HTML biasa ===== */
        .kop {
            border-bottom: 2px solid #2c3e50;
            padding-bottom: 8px;
            margin-bottom: 10px;
        }
        table.kop-tbl { width: 100%; border-collapse: collapse; }
        table.kop-tbl td { padding: 0; vertical-align: middle; }
        .kop-right-td { text-align: right; white-space: nowrap; }
        .kop-title  { font-size: 12px; font-weight: 700; text-transform: uppercase; color: #1a252f; }
        .kop-sub    { font-size: 9px; color: #555; margin-top: 1px; }
        .doc-title  { font-size: 11px; font-weight: 700; text-transform: uppercase; color: #2c3e50; }
        .doc-nomor  { font-size: 9px; color: #27ae60; font-weight: 700; margin-top: 2px; }
        .doc-date   { font-size: 8.5px; color: #888; margin-top: 1px; }

        /* ===== INFO BOX ===== */
        table.info-tbl {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #ddd;
            margin-bottom: 8px;
        }
        table.info-tbl td { vertical-align: top; padding: 8px 10px; font-size: 9.5px; }
        table.info-tbl td.info-left  { width: 50%; border-right: 1px solid #eee; }
        table.info-tbl td.info-right { width: 50%; }
        .info-col-title {
            font-size: 8px; font-weight: 700; color: #888;
            text-transform: uppercase; letter-spacing: 0.3px;
            border-bottom: 1px solid #eee;
            padding-bottom: 3px; margin-bottom: 5px;
        }
        table.info-inner { width: 100%; border-collapse: collapse; }
        table.info-inner td { padding: 2px 0; vertical-align: top; font-size: 9.5px; }
        table.info-inner td.lbl { color: #888; width: 100px; }
        table.info-inner td.val { font-weight: 600; color: #2c3e50; }

        /* ===== BADGE ===== */
        .badge {
            display: inline; padding: 1px 6px;
            border-radius: 8px; font-size: 8px; font-weight: 700;
        }
        .badge-aktif   { background: #d4edda; color: #155724; }
        .badge-selesai { background: #cce5ff; color: #004085; }

        /* ===== HIGHLIGHT DANA ===== */
        table.dana-tbl {
            width: 100%; border-collapse: collapse;
            border: 1px solid #bee3f8;
            background: #f0f9ff;
            margin-bottom: 8px;
        }
        table.dana-tbl td {
            text-align: center; padding: 7px 4px;
            border-right: 1px solid #bee3f8;
            width: 25%;
        }
        table.dana-tbl td:last-child { border-right: none; }
        .dana-lbl { font-size: 8px; color: #7f8c8d; margin-bottom: 2px; }
        .dana-val { font-size: 11px; font-weight: 700; }

        /* ===== TABEL LEDGER ===== */
        table.ledger-tbl {
            width: 100%; border-collapse: collapse;
            margin-bottom: 8px;
        }
        table.ledger-tbl thead tr { background: #2c3e50; }
        table.ledger-tbl thead th {
            padding: 6px 5px; text-align: left;
            color: #fff; font-size: 9px; font-weight: 700;
        }
        table.ledger-tbl thead th.r { text-align: right; }
        table.ledger-tbl tbody tr { border-bottom: 1px solid #e9ecef; }
        table.ledger-tbl tbody tr:nth-child(even) { background: #f8f9fa; }
        table.ledger-tbl tbody tr.saldo-awal { background: #f0fff4; }
        table.ledger-tbl tbody td {
            padding: 5px 5px; font-size: 9px; color: #2c3e50;
        }
        table.ledger-tbl tbody td.r { text-align: right; }
        table.ledger-tbl tbody td.c { text-align: center; }
        table.ledger-tbl tfoot tr { background: #ecf0f1; border-top: 2px solid #bdc3c7; }
        table.ledger-tbl tfoot td {
            padding: 6px 5px; font-weight: 700; font-size: 9.5px;
        }
        table.ledger-tbl tfoot td.r { text-align: right; }

        /* ===== RINGKASAN ===== */
        .summary-wrap {
            border: 1.5px solid #2c3e50;
            margin-bottom: 8px;
        }
        .summary-head {
            background: #2c3e50; color: #fff;
            padding: 5px 10px; font-size: 9.5px; font-weight: 700;
            text-transform: uppercase;
        }
        table.summary-tbl { width: 100%; border-collapse: collapse; }
        table.summary-tbl tr { border-bottom: 1px solid #ecf0f1; }
        table.summary-tbl tr:last-child { border-bottom: none; }
        table.summary-tbl td { padding: 5px 10px; font-size: 9.5px; }
        table.summary-tbl td.s-lbl { color: #555; width: 60%; }
        table.summary-tbl td.s-val { text-align: right; font-weight: 700; color: #2c3e50; }
        table.summary-tbl tr.s-total td { background: #f8f9fa; font-weight: 700; font-size: 11px; }

        /* ===== HASIL LELANG ===== */
        .hasil-wrap {
            border: 1px solid #27ae60;
            margin-bottom: 8px;
        }
        .hasil-head {
            background: #27ae60; color: #fff;
            padding: 5px 10px; font-size: 9.5px; font-weight: 700;
        }
        .hasil-head.gagal { background: #e74c3c; }
        table.hasil-tbl { width: 100%; border-collapse: collapse; padding: 0; }
        table.hasil-tbl td { padding: 3px 10px; font-size: 9.5px; }
        table.hasil-tbl td.hl { color: #888; width: 25%; }
        table.hasil-tbl td.hv { font-weight: 600; width: 25%; }

        /* ===== TANDA TANGAN ===== */
        table.ttd-tbl { width: 100%; border-collapse: collapse; margin-top: 18px; }
        table.ttd-tbl td { text-align: center; vertical-align: top; width: 33.33%; padding: 0 5px; }
        .ttd-title { font-size: 9px; color: #555; margin-bottom: 2px; }
        .ttd-role  { font-size: 9px; font-weight: 700; color: #2c3e50; margin-bottom: 44px; }
        .ttd-line  { border-top: 1px solid #2c3e50; padding-top: 4px; font-size: 9.5px; font-weight: 700; }
        .ttd-nip   { font-size: 8.5px; color: #888; margin-top: 1px; }

        /* ===== FOOTER ===== */
        table.footer-tbl {
            width: 100%; border-collapse: collapse;
            margin-top: 12px; border-top: 1px solid #eee;
            padding-top: 5px;
        }
        table.footer-tbl td { font-size: 8px; color: #aaa; padding-top: 5px; }
        table.footer-tbl td.fl { text-align: left; }
        table.footer-tbl td.fr { text-align: right; }

        /* ===== UTILS ===== */
        .green { color: #27ae60; }
        .red   { color: #e74c3c; }
        .blue  { color: #2980b9; }
        .bold  { font-weight: 700; }
    </style>
</head>
<body>

@php
    $nasabahNama   = $trr->pengajuanTrr->pengajuanLelang->nasabah->nama_nasabah
                  ?? $trr->pengajuanLelang->nasabah->nama_nasabah ?? '-';
    $nasabahNik    = $trr->pengajuanTrr->pengajuanLelang->nasabah->nik
                  ?? $trr->pengajuanLelang->nasabah->nik ?? '-';
    $nasabahAlamat = $trr->pengajuanTrr->pengajuanLelang->nasabah->alamat
                  ?? $trr->pengajuanLelang->nasabah->alamat ?? '-';
    $nasabahLokasi = $trr->pengajuanTrr->pengajuanLelang->nasabah->lokasi_lelang
                  ?? $trr->pengajuanLelang->nasabah->lokasi_lelang ?? '-';
    $nasabahJenis  = $trr->pengajuanTrr->pengajuanLelang->nasabah->jenis_lelang
                  ?? $trr->pengajuanLelang->nasabah->jenis_lelang ?? '-';
    $petugasNama   = $trr->pengajuanTrr->user->name
                  ?? $trr->pengajuanLelang->user->name ?? '-';
    $petugasEmail  = $trr->pengajuanTrr->user->email
                  ?? $trr->pengajuanLelang->user->email ?? '-';
    $approvedByNama = $trr->approvedBy->name ?? '-';
    $tanggalCetak  = now()->format('d M Y, H:i') . ' WIB';
    $hasilLelang   = $trr->hasilLelang ?? null;
@endphp

<table class="page-wrap"><tbody><tr><td class="page-pad">
{{-- ===== KOP ===== --}}
<div class="kop">
    <table class="kop-tbl">
        <tr>
            <td>
                <div class="kop-title">Bank Syariah Indonesia</div>
                <div class="kop-sub">Sistem Informasi Lelang (SiLelang)</div>
                <div class="kop-sub">Laporan Pertanggungjawaban Dana Operasional Lelang</div>
            </td>
            <td class="kop-right-td">
                <div class="doc-title">LPJ Dana TRR</div>
                <div class="doc-nomor">{{ $trr->nomor_referensi }}</div>
                <div class="doc-date">Dicetak: {{ $tanggalCetak }}</div>
            </td>
        </tr>
    </table>
</div>

{{-- ===== INFO NASABAH & PETUGAS ===== --}}
<table class="info-tbl">
    <tr>
        <td class="info-left">
            <div class="info-col-title">Data Nasabah</div>
            <table class="info-inner">
                <tr><td class="lbl">Nama Nasabah</td><td class="val">{{ $nasabahNama }}</td></tr>
                <tr><td class="lbl">NIK</td><td class="val">{{ $nasabahNik }}</td></tr>
                <tr><td class="lbl">Lokasi Lelang</td><td class="val">{{ $nasabahLokasi }}</td></tr>
                <tr><td class="lbl">Jenis Lelang</td><td class="val">{{ $nasabahJenis }}</td></tr>
                <tr><td class="lbl">Alamat</td><td class="val">{{ $nasabahAlamat }}</td></tr>
            </table>
        </td>
        <td class="info-right">
            <div class="info-col-title">Data Petugas &amp; TRR</div>
            <table class="info-inner">
                <tr><td class="lbl">Petugas Lelang</td><td class="val">{{ $petugasNama }}</td></tr>
                <tr><td class="lbl">Disetujui Oleh</td><td class="val">{{ $approvedByNama }}</td></tr>
                <tr>
                    <td class="lbl">No. Referensi</td>
                    <td class="val green">{{ $trr->nomor_referensi }}</td>
                </tr>
                <tr><td class="lbl">Tanggal Cair</td><td class="val">{{ $trr->tanggal_cair->format('d M Y') }}</td></tr>
                <tr>
                    <td class="lbl">Status</td>
                    <td class="val">
                        @if($trr->status === 'aktif')
                            <span class="badge badge-aktif">AKTIF</span>
                        @else
                            <span class="badge badge-selesai">SELESAI</span>
                        @endif
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

{{-- ===== HIGHLIGHT DANA ===== --}}
<table class="dana-tbl">
    <tr>
        <td>
            <div class="dana-lbl">Dana Dicairkan</div>
            <div class="dana-val blue">Rp {{ number_format($trr->nominal_disetujui, 0, ',', '.') }}</div>
        </td>
        <td>
            <div class="dana-lbl">Total Pengeluaran</div>
            <div class="dana-val red">Rp {{ number_format($ringkasan['total_debet'], 0, ',', '.') }}</div>
        </td>
        <td>
            <div class="dana-lbl">Sisa Saldo Akhir</div>
            <div class="dana-val {{ $ringkasan['sisa_saldo_akhir'] >= 0 ? 'green' : 'red' }}">
                Rp {{ number_format(abs($ringkasan['sisa_saldo_akhir']), 0, ',', '.') }}
                {{ $ringkasan['sisa_saldo_akhir'] < 0 ? '(Minus)' : '' }}
            </div>
        </td>
        <td>
            <div class="dana-lbl">Jumlah Transaksi</div>
            <div class="dana-val">{{ $trr->ledger->count() }} baris</div>
        </td>
    </tr>
</table>

{{-- ===== TABEL LEDGER ===== --}}
<table class="ledger-tbl">
    <colgroup>
        <col style="width:4%">
        <col style="width:18%">
        <col style="width:36%">
        <col style="width:14%">
        <col style="width:14%">
        <col style="width:14%">
    </colgroup>
    <thead>
        <tr>
            <th class="c">No</th>
            <th>Tanggal</th>
            <th>Keterangan</th>
            <th class="r">Kredit (Rp)</th>
            <th class="r">Debet (Rp)</th>
            <th class="r">Sisa Saldo (Rp)</th>
        </tr>
    </thead>
    <tbody>
        @foreach($trr->ledger->sortBy('id') as $i => $baris)
        <tr class="{{ $i === 0 ? 'saldo-awal' : '' }}">
            <td class="c" style="color:#888;">{{ $i + 1 }}</td>
            <td style="color:#888;">{{ $baris->created_at->format('d M Y, H:i') }}</td>
            <td>
                {{ $baris->keterangan }}
                @if($i === 0)<span class="green bold" style="font-size:8px;"> &larr; Saldo Awal</span>@endif
            </td>
            <td class="r green">{{ $baris->kredit > 0 ? number_format($baris->kredit, 0, ',', '.') : '—' }}</td>
            <td class="r red">{{ $baris->debet > 0 ? number_format($baris->debet, 0, ',', '.') : '—' }}</td>
            <td class="r bold">{{ number_format($baris->sisa_saldo, 0, ',', '.') }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="3" class="r">TOTAL REALISASI</td>
            <td class="r green">{{ number_format($ringkasan['total_kredit'], 0, ',', '.') }}</td>
            <td class="r red">{{ number_format($ringkasan['total_debet'], 0, ',', '.') }}</td>
            <td class="r {{ $ringkasan['sisa_saldo_akhir'] >= 0 ? 'green' : 'red' }}">
                {{ number_format($ringkasan['sisa_saldo_akhir'], 0, ',', '.') }}
            </td>
        </tr>
    </tfoot>
</table>

{{-- ===== RINGKASAN AKHIR ===== --}}
<div class="summary-wrap">
    <div class="summary-head">Ringkasan Perhitungan Akhir</div>
    <table class="summary-tbl">
        <tr>
            <td class="s-lbl">Dana TRR yang Dicairkan</td>
            <td class="s-val blue">Rp {{ number_format($trr->nominal_disetujui, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="s-lbl">
                Total Realisasi Pengeluaran
                <span style="font-size:8px; color:#888;">({{ $trr->ledger->where('debet', '>', 0)->count() }} transaksi)</span>
            </td>
            <td class="s-val red">(Rp {{ number_format($ringkasan['total_debet'], 0, ',', '.') }})</td>
        </tr>
        <tr class="s-total">
            <td class="s-lbl">Sisa Dana = Dana Cair &minus; Total Realisasi</td>
            <td class="s-val {{ $ringkasan['sisa_saldo_akhir'] >= 0 ? 'green' : 'red' }}">
                Rp {{ number_format(abs($ringkasan['sisa_saldo_akhir']), 0, ',', '.') }}
                {{ $ringkasan['sisa_saldo_akhir'] < 0 ? '(MINUS)' : '' }}
            </td>
        </tr>
    </table>
</div>

{{-- ===== HASIL LELANG ===== --}}
@if($hasilLelang)
<div class="hasil-wrap">
    <div class="hasil-head {{ $hasilLelang->status_hasil === 'gagal' ? 'gagal' : '' }}">
        {{ $hasilLelang->status_hasil === 'berhasil' ? 'Hasil Lelang: BERHASIL / TERJUAL' : 'Hasil Lelang: GAGAL / TIDAK ADA PEMENANG' }}
    </div>
    <table class="hasil-tbl">
        <tr>
            <td class="hl">Tanggal Lelang</td>
            <td class="hv">{{ $hasilLelang->tanggal_lelang?->format('d M Y') ?? '-' }}</td>
            @if($hasilLelang->status_hasil === 'berhasil')
            <td class="hl">Harga Terjual</td>
            <td class="hv green">Rp {{ number_format($hasilLelang->harga_terjual ?? 0, 0, ',', '.') }}</td>
            @else
            <td colspan="2"></td>
            @endif
        </tr>
        @if($hasilLelang->status_hasil === 'berhasil')
        <tr>
            <td class="hl">Nama Pemenang</td>
            <td class="hv" colspan="3">{{ $hasilLelang->nama_pemenang ?? '-' }}</td>
        </tr>
        @endif
        <tr>
            <td class="hl">Sisa Dana Kembali</td>
            <td class="hv blue">Rp {{ number_format($hasilLelang->sisa_dana_dikembalikan ?? 0, 0, ',', '.') }}</td>
            <td class="hl">Diinput Oleh</td>
            <td class="hv">{{ $hasilLelang->diinputOleh->name ?? '-' }}</td>
        </tr>
        @if($hasilLelang->catatan)
        <tr>
            <td class="hl">Catatan</td>
            <td colspan="3" style="padding:3px 10px; font-size:9.5px;">{{ $hasilLelang->catatan }}</td>
        </tr>
        @endif
    </table>
</div>
@endif

{{-- ===== TANDA TANGAN ===== --}}
<table class="ttd-tbl">
    <tr>
        <td>
            <div class="ttd-title">Mengetahui,</div>
            <div class="ttd-role">Admin / Pejabat Berwenang</div>
            <div class="ttd-line">{{ $approvedByNama }}</div>
            <div class="ttd-nip">Pemroses TRR</div>
        </td>
        <td>
            <div class="ttd-title">Dibuat oleh,</div>
            <div class="ttd-role">Petugas Lelang</div>
            <div class="ttd-line">{{ $petugasNama }}</div>
            <div class="ttd-nip">{{ $petugasEmail }}</div>
        </td>
        <td>
            <div class="ttd-title">Dicetak pada,</div>
            <div class="ttd-role">{{ now()->format('d M Y') }}</div>
            <div class="ttd-line">{{ now()->format('H:i') }} WIB</div>
            <div class="ttd-nip">Dokumen otomatis SiLelang</div>
        </td>
    </tr>
</table>

{{-- ===== FOOTER ===== --}}
<table class="footer-tbl">
    <tr>
        <td class="fl">SiLelang &mdash; Bank Syariah Indonesia &nbsp;|&nbsp; Dokumen ini digenerate otomatis oleh sistem</td>
        <td class="fr">{{ $trr->nomor_referensi }} &nbsp;|&nbsp; Halaman 1</td>
    </tr>
</table>

</td></tr></tbody></table>
</td></tr></tbody></table>
</body>
</html>