<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>LPJ TRR — {{ $trr->nomor_referensi }}</title>
    <style>
        body { font-family: sans-serif; font-size: 13px; color: #333; }
        h2 { text-align: center; margin-bottom: 4px; }
        .sub { text-align: center; color: #666; margin-bottom: 20px; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th { background: #2c3e50; color: #fff; padding: 8px 10px; text-align: left; font-size: 12px; }
        td { padding: 8px 10px; border-bottom: 1px solid #eee; }
        tr:nth-child(even) { background: #f9f9f9; }
        .text-right { text-align: right; }
        .footer-row td { font-weight: bold; background: #ecf0f1; border-top: 2px solid #bdc3c7; }
        .info-box { border: 1px solid #ddd; border-radius: 6px; padding: 12px; margin-bottom: 16px; }
        .info-row { display: flex; gap: 20px; }
        .info-item { flex: 1; }
        .info-label { font-size: 11px; color: #888; }
        .info-value { font-weight: bold; font-size: 13px; }
    </style>
</head>
<body>

    <h2>LAPORAN PERTANGGUNGJAWABAN DANA TRR</h2>
    <p class="sub">Bank Syariah Indonesia — Sistem Informasi Lelang</p>

    <div class="info-box">
        <table style="border:none; margin:0;">
            <tr>
                <td style="border:none; padding:4px 8px; width:160px; color:#666;">No. Referensi</td>
                <td style="border:none; padding:4px 8px; font-weight:bold;">: {{ $trr->nomor_referensi }}</td>
                <td style="border:none; padding:4px 8px; width:160px; color:#666;">Nasabah</td>
                <td style="border:none; padding:4px 8px; font-weight:bold;">: {{ $trr->pengajuanLelang->nasabah->nama_nasabah ?? '-' }}</td>
            </tr>
            <tr>
                <td style="border:none; padding:4px 8px; color:#666;">Tanggal Cair</td>
                <td style="border:none; padding:4px 8px; font-weight:bold;">: {{ $trr->tanggal_cair->format('d M Y') }}</td>
                <td style="border:none; padding:4px 8px; color:#666;">Petugas</td>
                <td style="border:none; padding:4px 8px; font-weight:bold;">: {{ $trr->pengajuanLelang->user->name ?? '-' }}</td>
            </tr>
            <tr>
                <td style="border:none; padding:4px 8px; color:#666;">Dana Dicairkan</td>
                <td style="border:none; padding:4px 8px; font-weight:bold;">: Rp {{ number_format($trr->nominal_disetujui, 0, ',', '.') }}</td>
                <td style="border:none; padding:4px 8px; color:#666;">Status</td>
                <td style="border:none; padding:4px 8px; font-weight:bold;">: {{ strtoupper($trr->status) }}</td>
            </tr>
        </table>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width:40px;">No</th>
                <th>Keterangan</th>
                <th style="width:140px; text-align:right;">Kredit (Rp)</th>
                <th style="width:140px; text-align:right;">Debet (Rp)</th>
                <th style="width:150px; text-align:right;">Sisa Saldo (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($trr->ledger->sortBy('id') as $i => $baris)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $baris->keterangan }}</td>
                <td class="text-right">
                    {{ $baris->kredit > 0 ? number_format($baris->kredit, 0, ',', '.') : '—' }}
                </td>
                <td class="text-right">
                    {{ $baris->debet > 0 ? number_format($baris->debet, 0, ',', '.') : '—' }}
                </td>
                <td class="text-right">{{ number_format($baris->sisa_saldo, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="footer-row">
                <td colspan="2" class="text-right">TOTAL REALISASI</td>
                <td class="text-right">{{ number_format($ringkasan['total_kredit'], 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($ringkasan['total_debet'], 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($ringkasan['sisa_saldo_akhir'], 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <div style="margin-top:40px; display:flex; justify-content:flex-end;">
        <div style="text-align:center; width:200px;">
            <p style="margin-bottom:60px;">Petugas Lelang,</p>
            <p style="border-top:1px solid #333; padding-top:6px; font-weight:bold;">
                {{ $trr->pengajuanLelang->user->name ?? '________________' }}
            </p>
        </div>
    </div>

</body>
</html>