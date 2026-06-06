<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Hasil Lelang</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #333; }

        .kop {
            text-align: center;
            border-bottom: 2px solid #2c3e50;
            padding-bottom: 10px;
            margin-bottom: 14px;
        }
        .kop h2 { font-size: 14px; font-weight: 700; text-transform: uppercase; }
        .kop p  { font-size: 11px; color: #555; margin-top: 3px; }

        .meta {
            display: flex;
            justify-content: space-between;
            font-size: 11px;
            margin-bottom: 14px;
            color: #555;
        }

        table { width: 100%; border-collapse: collapse; margin-top: 6px; }
        thead tr { background: #2c3e50; color: #fff; }
        thead th { padding: 8px 10px; text-align: left; font-size: 10.5px; font-weight: 600; }
        tbody td { padding: 7px 10px; border-bottom: 1px solid #e0e0e0; vertical-align: top; }
        tbody tr:nth-child(even) { background: #f7f7f7; }
        tfoot td { padding: 8px 10px; font-weight: 700; background: #ecf0f1;
                   border-top: 2px solid #bdc3c7; }

        .text-right  { text-align: right; }
        .text-center { text-align: center; }

        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 10px;
            font-weight: 700;
        }
        .badge-berhasil { background: #d4edda; color: #155724; }
        .badge-gagal    { background: #f8d7da; color: #721c24; }

        .footer {
            margin-top: 20px;
            font-size: 10px;
            color: #888;
            text-align: right;
        }

        .summary-box {
            display: table;
            width: 100%;
            margin-bottom: 14px;
        }
        .summary-item {
            display: table-cell;
            padding: 10px 14px;
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            font-size: 11px;
            text-align: center;
        }
        .summary-item .val { font-size: 14px; font-weight: 700; color: #2c3e50; }
        .summary-item .lbl { color: #6c757d; font-size: 10px; margin-top: 2px; }
    </style>
</head>
<body>

    {{-- KOP SURAT --}}
    <div class="kop">
        <h2>Rekap Hasil Lelang</h2>
        <p>Bank Syariah Indonesia — Sistem Informasi Lelang (SiLelang)</p>
        <p>Dicetak pada: {{ now()->format('d M Y, H:i') }} WIB</p>
    </div>

    {{-- SUMMARY --}}
    <div class="summary-box">
        <div class="summary-item">
            <div class="val">{{ $hasil->count() }}</div>
            <div class="lbl">Total Data</div>
        </div>
        <div class="summary-item">
            <div class="val" style="color:#27ae60;">
                {{ $hasil->where('status_hasil', 'berhasil')->count() }}
            </div>
            <div class="lbl">Berhasil</div>
        </div>
        <div class="summary-item">
            <div class="val" style="color:#e74c3c;">
                {{ $hasil->where('status_hasil', 'gagal')->count() }}
            </div>
            <div class="lbl">Gagal</div>
        </div>
        <div class="summary-item">
            <div class="val" style="color:#2980b9;">
                Rp {{ number_format($hasil->where('status_hasil','berhasil')->sum('harga_terjual'), 0, ',', '.') }}
            </div>
            <div class="lbl">Total Nilai Terjual</div>
        </div>
        <div class="summary-item">
            <div class="val" style="color:#e67e22;">
                Rp {{ number_format($hasil->sum('total_biaya_realisasi'), 0, ',', '.') }}
            </div>
            <div class="lbl">Total Biaya Realisasi</div>
        </div>
    </div>

    {{-- TABEL --}}
    <table>
        <thead>
            <tr>
                <th style="width:30px;">No</th>
                <th>No. Referensi TRR</th>
                <th>Nasabah</th>
                <th>Petugas</th>
                <th class="text-center">Tgl Lelang</th>
                <th class="text-center">Status</th>
                <th class="text-right">Harga Terjual (Rp)</th>
                <th>Nama Pemenang</th>
                <th class="text-right">Total Biaya (Rp)</th>
                <th class="text-right">Sisa Kembali (Rp)</th>
                <th>Catatan</th>
                <th>Diinput Oleh</th>
            </tr>
        </thead>
        <tbody>
            @forelse($hasil as $i => $item)
            <tr>
                <td class="text-center">{{ $i + 1 }}</td>
                <td style="font-weight:600;">
                    {{ $item->danaTrr->nomor_referensi ?? '-' }}
                </td>
                <td>{{ $item->nasabahNama }}</td>
                <td>{{ $item->petugasNama }}</td>
                <td class="text-center">
                    {{ $item->tanggal_lelang?->format('d/m/Y') ?? '-' }}
                </td>
                <td class="text-center">
                    @if($item->status_hasil === 'berhasil')
                        <span class="badge badge-berhasil">Berhasil</span>
                    @else
                        <span class="badge badge-gagal">Gagal</span>
                    @endif
                </td>
                <td class="text-right">
                    {{ $item->harga_terjual ? number_format($item->harga_terjual, 0, ',', '.') : '—' }}
                </td>
                <td>{{ $item->nama_pemenang ?? '—' }}</td>
                <td class="text-right">
                    {{ number_format($item->total_biaya_realisasi, 0, ',', '.') }}
                </td>
                <td class="text-right">
                    {{ number_format($item->sisa_dana_dikembalikan, 0, ',', '.') }}
                </td>
                <td style="font-size:10px; color:#555;">
                    {{ Str::limit($item->catatan ?? '—', 40) }}
                </td>
                <td>{{ $item->diinputOleh->name ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="12" class="text-center" style="padding:20px; color:#aaa;">
                    Tidak ada data hasil lelang.
                </td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="6" class="text-right">TOTAL</td>
                <td class="text-right">
                    Rp {{ number_format($hasil->where('status_hasil','berhasil')->sum('harga_terjual'), 0, ',', '.') }}
                </td>
                <td></td>
                <td class="text-right">
                    Rp {{ number_format($hasil->sum('total_biaya_realisasi'), 0, ',', '.') }}
                </td>
                <td class="text-right">
                    Rp {{ number_format($hasil->sum('sisa_dana_dikembalikan'), 0, ',', '.') }}
                </td>
                <td colspan="2"></td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        Dokumen ini digenerate otomatis oleh SiLelang — Bank Syariah Indonesia
    </div>

</body>
</html>