@extends('layouts.petugas')

@section('title', 'Pengajuan Lelang')
@section('page-title', 'Pengajuan Lelang')

@section('content')
<style>
    .alert {
        padding: 14px 18px;
        border-radius: 12px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .alert.success {
        background: #d4edda;
        color: #155724;
        border-left: 4px solid #28a745;
    }

    .alert.error {
        background: #f8d7da;
        color: #721c24;
        border-left: 4px solid #dc3545;
    }

    .box {
        background: #fff;
        padding: 28px;
        border-radius: 14px;
        margin-bottom: 24px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.06);
    }

    .box h3 {
        color: #39C6C9;
        margin-bottom: 20px;
        font-size: 20px;
        font-weight: 600;
    }

    .btn-primary {
        padding: 12px 26px;
        background: #39C6C9;
        border: none;
        color: #fff;
        border-radius: 10px;
        cursor: pointer;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.3s;
    }

    .btn-primary:hover {
        background: #2FB3B6;
        transform: translateY(-2px);
    }

    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 18px;
    }

    .form-full {
        grid-column: 1 / -1;
    }

    label {
        display: block;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 6px;
        font-size: 14px;
    }

    input, select {
        width: 100%;
        padding: 12px;
        margin-bottom: 12px;
        border-radius: 10px;
        border: 1px solid #ddd;
        font-size: 14px;
    }

    input:focus, select:focus {
        outline: none;
        border-color: #39C6C9;
        box-shadow: 0 0 0 3px rgba(57,198,201,0.1);
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 18px;
    }

    th, td {
        padding: 12px 10px;
        text-align: center;
        border-bottom: 1px solid #e0e0e0;
        font-size: 14px;
    }

    th {
        background: linear-gradient(135deg, #7FE3E6, #39C6C9);
        color: #0c5f61;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 13px;
        letter-spacing: 0.5px;
    }

    tbody tr {
        transition: all 0.2s;
    }

    tbody tr:hover {
        background: #f9fefe;
    }

    tr:nth-child(even) {
        background: #fafafa;
    }

    .status-badge {
        display: inline-block;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
    }

    .status-badge.pending {
        background: #fff3cd;
        color: #856404;
    }

    .status-badge.disetujui {
        background: #d4edda;
        color: #155724;
    }

    .status-badge.ditolak {
        background: #f8d7da;
        color: #721c24;
    }

    .info-box {
        background: linear-gradient(135deg, #e3f2fd, #bbdefb);
        padding: 16px 20px;
        border-radius: 10px;
        margin-bottom: 20px;
        color: #01579b;
        border-left: 4px solid #2196f3;
    }

    .info-box strong {
        display: block;
        margin-bottom: 5px;
    }

    @media (max-width: 768px) {
        .form-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

@if(session('success'))
    <div class="alert success">
        <span>✓</span> {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert error">
        <span>✖</span> {{ session('error') }}
    </div>
@endif

<div class="info-box">
    <strong>ℹ️ Informasi Pengajuan Lelang</strong>
    Ajukan nasabah dengan status kredit macet untuk proses lelang. 
    Admin akan mereview dan menyetujui/menolak pengajuan Anda.
</div>

<div class="box">
    <button class="btn-primary" onclick="toggleForm()">+ Ajukan Lelang</button>
</div>

<div class="box" id="formPengajuan" style="display:none;">
    <h3>Form Pengajuan Lelang</h3>

    <form method="POST" action="/petugas/pengajuan-lelang">
        @csrf
        <div class="form-grid">
            <div>
                <label>Pilih Nasabah</label>
                <select name="nasabah_id" required>
                    <option value="">-- Pilih Nasabah --</option>
                    @foreach($nasabah as $n)
                        <option value="{{ $n->id }}">
                            {{ $n->nama_nasabah }} - {{ $n->nik }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label>Tanggal Pengajuan</label>
                <input type="date" name="tanggal_pengajuan" value="{{ date('Y-m-d') }}" required>
            </div>

            <div class="form-full">
                <button type="submit" class="btn-primary">📤 Kirim Pengajuan</button>
            </div>
        </div>
    </form>
</div>

<div class="box">
    <h3>Status Pengajuan Lelang</h3>

    <div style="overflow-x: auto;">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Nama Nasabah</th>
                    <th>NIK</th>
                    <th>Status</th>
                    <th>Catatan Admin</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pengajuan as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal_pengajuan)->format('d M Y') }}</td>
                    <td><strong>{{ $item->nasabah->nama_nasabah }}</strong></td>
                    <td>{{ $item->nasabah->nik }}</td>
                    <td>
                        <span class="status-badge {{ $item->status }}">
                            @if($item->status == 'pending')
                                ⏳ Pending
                            @elseif($item->status == 'disetujui')
                                ✓ Disetujui
                            @else
                                ✖ Ditolak
                            @endif
                        </span>
                    </td>
                    <td>{{ $item->catatan_admin ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="padding: 40px; color: #95a5a6;">
                        <span style="font-size: 48px; display: block; margin-bottom: 10px;">⚖️</span>
                        Belum ada pengajuan lelang
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<script>
function toggleForm() {
    const form = document.getElementById('formPengajuan');
    form.style.display = form.style.display === 'none' ? 'block' : 'none';
}
</script>
@endpush
@endsection