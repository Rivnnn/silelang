@extends('layouts.petugas')

@section('title', 'Upload Dokumen')
@section('page-title', 'Upload Dokumen')

@section('content')
<style>
    /* Header Styling */
    .page-header {
        margin-bottom: 30px;
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
    }

    .header-text h3 {
        color: #39C6C9;
        font-size: 24px;
        font-weight: 700;
        margin-bottom: 5px;
    }

    .header-text p {
        color: #7f8c8d;
        font-size: 15px;
    }

    /* Filter/Search Bar Styling */
    .search-container {
        position: relative;
        width: 320px;
    }

    .search-container input {
        width: 100%;
        padding: 12px 15px 12px 45px;
        border-radius: 12px;
        border: 1.5px solid #e0e6ed;
        font-size: 14px;
        transition: all 0.3s ease;
        outline: none;
        background: #fff;
    }

    .search-container input:focus {
        border-color: #39C6C9;
        box-shadow: 0 0 0 4px rgba(57, 198, 201, 0.1);
    }

    .search-container i {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #a0aec0;
        font-size: 18px;
    }

    /* Table & Card Styling */
    .card {
        background: #fff;
        padding: 10px;
        border-radius: 16px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.03);
        border: 1px solid #f0f0f0;
    }

    .table-responsive {
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    th {
        background: #f8fbfa;
        color: #4a5568;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 12px;
        letter-spacing: 1px;
        padding: 18px 15px;
        border-bottom: 2px solid #edf2f7;
    }

    td {
        padding: 16px 15px;
        vertical-align: middle;
        color: #2d3748;
        font-size: 14px;
        border-bottom: 1px solid #edf2f7;
    }

    tbody tr {
        transition: all 0.2s ease;
    }

    tbody tr:hover {
        background-color: #f0fdfd;
        transform: scale(1.002);
    }

    .nasabah-info {
        display: flex;
        flex-direction: column;
    }

    .nasabah-name {
        font-weight: 600;
        color: #1a202c;
        font-size: 15px;
    }

    .nasabah-nik {
        font-size: 12px;
        color: #718096;
        margin-top: 2px;
    }

    /* Badge Style for NIK */
    .badge-nik {
        background: #f1f5f9;
        padding: 4px 8px;
        border-radius: 6px;
        font-family: 'Courier New', Courier, monospace;
        color: #475569;
    }

    /* Button Styling */
    .btn-upload {
        background: linear-gradient(135deg, #39C6C9, #2fb3b6);
        color: #fff !important;
        padding: 10px 20px;
        border-radius: 10px;
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        border: none;
    }

    .btn-upload:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(57, 198, 201, 0.4);
        filter: brightness(1.1);
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
    }

    .empty-icon {
        font-size: 64px;
        margin-bottom: 20px;
        display: block;
    }
</style>

<div class="page-header">
    <div class="header-text">
        <h3>Daftar Nasabah</h3>
        <p>Pilih nasabah untuk mengelola dokumen persyaratan lelang</p>
    </div>
    
    <div class="search-container">
        <i>🔍</i>
        <input type="text" id="searchNasabah" onkeyup="filterNasabah()" placeholder="Cari nama atau NIK nasabah...">
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table id="tableNasabah">
            <thead>
                <tr>
                    <th width="80">No</th>
                    <th>Informasi Nasabah</th>
                    <th>NIK</th>
                    <th width="200">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($nasabah as $n)
                <tr>
                    <td style="text-align: center; color: #a0aec0;">{{ $loop->iteration }}</td>
                    <td>
                        <div class="nasabah-info">
                            <span class="nasabah-name">{{ $n->nama_nasabah }}</span>
                            <span class="nasabah-nik">ID: {{ str_pad($n->id, 5, '0', STR_PAD_LEFT) }}</span>
                        </div>
                    </td>
                    <td>
                        <span class="badge-nik">{{ $n->nik }}</span>
                    </td>
                    <td style="text-align: center;">
                        <a href="{{ route('petugas.dokumen.form', $n->id) }}" class="btn-upload">
                            <span>📤</span> Upload Dokumen
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4">
                        <div class="empty-state">
                            <span class="empty-icon">📁</span>
                            <h4 style="color: #4a5568;">Data Nasabah Kosong</h4>
                            <p style="color: #a0aec0;">Silahkan tambahkan nasabah baru di menu Data Nasabah.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<script>
function filterNasabah() {
    const input = document.getElementById("searchNasabah").value.toLowerCase();
    const table = document.getElementById("tableNasabah");
    const rows = table.getElementsByTagName("tr");

    for (let i = 1; i < rows.length; i++) {
        const row = rows[i];
        if (row.querySelector('.empty-state')) continue;

        // Ambil teks dari kolom Nama dan NIK
        const nameText = row.querySelector('.nasabah-name')?.textContent.toLowerCase() || "";
        const nikText = row.querySelector('.badge-nik')?.textContent.toLowerCase() || "";
        
        if (nameText.includes(input) || nikText.includes(input)) {
            row.style.display = "";
        } else {
            row.style.display = "none";
        }
    }
}
</script>
@endpush
@endsection