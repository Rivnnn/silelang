@extends('layouts.admin')

@section('title', 'Monitoring Nasabah')
@section('page-title', 'Monitoring Data Nasabah')

@section('content')
<style>
    /* ================= FILTER BOX ================= */
    .filter-box {
        background: #fff;
        padding: 24px;
        border-radius: 14px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.06);
        margin-bottom: 24px;
    }

    .filter-grid {
        display: grid;
        grid-template-columns: 2fr 1fr 1fr auto;
        gap: 14px;
        align-items: end;
    }

    .filter-group label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 8px;
    }

    .filter-group input,
    .filter-group select {
        width: 100%;
        padding: 10px 14px;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .filter-group input:focus,
    .filter-group select:focus {
        outline: none;
        border-color: #3498db;
        box-shadow: 0 0 0 3px rgba(52,152,219,0.1);
    }

    .btn-reset {
        padding: 10px 20px;
        background: #e74c3c;
        color: #fff;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        white-space: nowrap;
    }

    .btn-reset:hover {
        background: #c0392b;
        transform: translateY(-2px);
    }

    /* ================= INFO BADGE ================= */
    .info-badge {
        background: #e8f4f8;
        color: #2c3e50;
        padding: 12px 18px;
        border-radius: 10px;
        font-size: 13px;
        margin-bottom: 20px;
        display: inline-flex;
        align-items: center;
        gap: 10px;
    }

    .info-badge strong {
        color: #3498db;
    }

    /* ================= TABLE BOX ================= */
    .table-box {
        background: #fff;
        padding: 28px;
        border-radius: 14px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.06);
    }

    .table-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
    }

    .table-header h3 {
        color: #2c3e50;
        font-size: 20px;
        font-weight: 600;
    }

    .stats-badge {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: #fff;
        padding: 10px 20px;
        border-radius: 25px;
        font-weight: 600;
        font-size: 14px;
    }

    /* ================= TABLE ================= */
    .table-wrapper {
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    thead {
        background: linear-gradient(135deg, #34495e, #2c3e50);
    }

    th {
        padding: 14px 12px;
        text-align: left;
        color: #fff;
        font-weight: 600;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        white-space: nowrap;
    }

    tbody tr {
        border-bottom: 1px solid #e0e0e0;
        transition: all 0.3s ease;
    }

    tbody tr:hover {
        background: #f8f9fa;
    }

    tbody tr.hidden {
        display: none;
    }

    td {
        padding: 14px 12px;
        color: #2c3e50;
        font-size: 13px;
    }

    /* ================= BADGES ================= */
    .badge {
        display: inline-block;
        padding: 5px 12px;
        border-radius: 15px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .badge.tanah {
        background: #fff3cd;
        color: #856404;
    }

    .badge.bangunan {
        background: #cce5ff;
        color: #004085;
    }

    .badge.tanah-bangunan {
        background: #d4edda;
        color: #155724;
    }

    /* ================= ACTION BUTTON ================= */
    .btn-view {
        padding: 7px 14px;
        background: linear-gradient(135deg, #3498db, #2980b9);
        color: #fff;
        text-decoration: none;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        transition: all 0.3s ease;
        display: inline-block;
    }

    .btn-view:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(52,152,219,0.3);
    }

    /* ================= PETUGAS INFO ================= */
    .petugas-info {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .petugas-avatar {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea, #764ba2);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-weight: 600;
        font-size: 11px;
        flex-shrink: 0;
    }

    .petugas-name {
        font-weight: 600;
        color: #2c3e50;
        font-size: 13px;
    }

    /* ================= EMPTY STATE ================= */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #95a5a6;
    }

    .empty-state span {
        font-size: 64px;
        display: block;
        margin-bottom: 20px;
    }

    .empty-state h4 {
        font-size: 20px;
        margin-bottom: 10px;
        color: #7f8c8d;
    }

    /* ================= NO RESULTS ================= */
    .no-results {
        text-align: center;
        padding: 40px 20px;
        color: #95a5a6;
        display: none;
    }

    .no-results.show {
        display: table-row;
    }

    .no-results span {
        font-size: 48px;
        display: block;
        margin-bottom: 15px;
    }

    .no-results h4 {
        font-size: 18px;
        margin-bottom: 8px;
        color: #7f8c8d;
    }

    .no-results p {
        font-size: 13px;
    }

    /* ================= RESPONSIVE ================= */
    @media (max-width: 768px) {
        .filter-grid {
            grid-template-columns: 1fr;
        }

        .btn-reset {
            width: 100%;
        }

        .table-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }
    }
</style>

<!-- Filter Box -->
<div class="filter-box">
    <div class="filter-grid">
        <div class="filter-group">
            <label>🔍 Cari Nama/NIK/No HP</label>
            <input 
                type="text" 
                id="searchNasabah" 
                placeholder="Ketik nama, NIK, atau nomor HP..." 
                oninput="filterTable()">
        </div>

        <div class="filter-group">
            <label>📍 Lokasi Lelang</label>
            <select id="filterLokasi" onchange="filterTable()">
                <option value="">Semua Lokasi</option>
                @php
                    $uniqueLokasi = $nasabah->unique('lokasi_lelang')->pluck('lokasi_lelang')->sort()->filter();
                @endphp
                @foreach($uniqueLokasi as $lokasi)
                    <option value="{{ $lokasi }}">{{ $lokasi }}</option>
                @endforeach
            </select>
        </div>

        <div class="filter-group">
            <label>🏷️ Jenis Lelang</label>
            <select id="filterJenis" onchange="filterTable()">
                <option value="">Semua Jenis</option>
                <option value="Tanah">Tanah</option>
                <option value="Bangunan">Bangunan</option>
                <option value="Tanah Berikut Bangunan">Tanah + Bangunan</option>
            </select>
        </div>

        <button class="btn-reset" onclick="resetFilter()">↻ Reset</button>
    </div>
</div>

<!-- Info Badge -->
<div class="info-badge" id="filterInfo" style="display: none;">
    <span>🔎</span>
    <span id="filterInfoText">Menampilkan hasil filter</span>
</div>

<!-- Table Box -->
<div class="table-box">
    <div class="table-header">
        <h3>Daftar Nasabah</h3>
        <div class="stats-badge">
            <span id="totalCount">{{ $nasabah->count() }}</span> / {{ $nasabah->count() }} Nasabah
        </div>
    </div>

    <div class="table-wrapper">
        <table id="nasabahTable">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Nasabah</th>
                    <th>NIK</th>
                    <th>Alamat</th>
                    <th>No HP</th>
                    <th>Lokasi Lelang</th>
                    <th>Jenis Lelang</th>
                    <th>Petugas Input</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($nasabah as $item)
                <tr data-lokasi="{{ $item->lokasi_lelang }}" data-jenis="{{ $item->jenis_lelang }}">
                    <td class="row-number">{{ $loop->iteration }}</td>
                    <td><strong class="searchable-name">{{ $item->nama_nasabah }}</strong></td>
                    <td class="searchable-nik">{{ $item->nik }}</td>
                    <td>{{ Str::limit($item->alamat, 40) }}</td>
                    <td class="searchable-hp">{{ $item->no_hp }}</td>
                    <td>{{ $item->lokasi_lelang }}</td>
                    <td>
                        @if($item->jenis_lelang == 'Tanah')
                            <span class="badge tanah">Tanah</span>
                        @elseif($item->jenis_lelang == 'Bangunan')
                            <span class="badge bangunan">Bangunan</span>
                        @else
                            <span class="badge tanah-bangunan">Tanah + Bangunan</span>
                        @endif
                    </td>
                    <td>
                        @if($item->user)
                        <div class="petugas-info">
                            <div class="petugas-avatar">
                                {{ strtoupper(substr($item->user->name, 0, 1)) }}
                            </div>
                            <span class="petugas-name">{{ $item->user->name }}</span>
                        </div>
                        @else
                            <span style="color: #95a5a6;">-</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.nasabah.dokumen', $item->id) }}" class="btn-view">
                            📄 Lihat Dokumen
                        </a>
                    </td>
                </tr>
                @empty
                <tr class="empty-row">
                    <td colspan="9">
                        <div class="empty-state">
                            <span>📋</span>
                            <h4>Belum Ada Data Nasabah</h4>
                        </div>
                    </td>
                </tr>
                @endforelse
                
                <!-- No Results Row -->
                <tr class="no-results" id="noResults">
                    <td colspan="9">
                        <span>🔍</span>
                        <h4>Tidak Ada Hasil</h4>
                        <p>Tidak ditemukan nasabah yang sesuai dengan filter. Coba ubah kriteria pencarian.</p>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Normalisasi teks untuk pencarian (hapus spasi ekstra, lowercase)
function normalizeText(text) {
    return text.toLowerCase().trim().replace(/\s+/g, ' ');
}

// Fungsi filter utama
function filterTable() {
    const searchValue = normalizeText(document.getElementById('searchNasabah').value);
    const lokasiValue = document.getElementById('filterLokasi').value;
    const jenisValue = document.getElementById('filterJenis').value;
    
    const table = document.getElementById('nasabahTable');
    const rows = table.querySelectorAll('tbody tr:not(.empty-row):not(.no-results)');
    const noResults = document.getElementById('noResults');
    const filterInfo = document.getElementById('filterInfo');
    const filterInfoText = document.getElementById('filterInfoText');
    
    let visibleCount = 0;
    let filterActive = searchValue !== '' || lokasiValue !== '' || jenisValue !== '';

    rows.forEach((row, index) => {
        // Ambil data dari row
        const nama = normalizeText(row.querySelector('.searchable-name')?.textContent || '');
        const nik = normalizeText(row.querySelector('.searchable-nik')?.textContent || '');
        const hp = normalizeText(row.querySelector('.searchable-hp')?.textContent || '');
        const lokasi = row.getAttribute('data-lokasi') || '';
        const jenis = row.getAttribute('data-jenis') || '';
        
        // Logika matching
        const matchSearch = searchValue === '' || 
                          nama.includes(searchValue) || 
                          nik.includes(searchValue) || 
                          hp.includes(searchValue);
        
        const matchLokasi = lokasiValue === '' || lokasi === lokasiValue;
        const matchJenis = jenisValue === '' || jenis === jenisValue;
        
        // Tampilkan atau sembunyikan row
        if (matchSearch && matchLokasi && matchJenis) {
            row.classList.remove('hidden');
            row.style.display = '';
            visibleCount++;
            
            // Update nomor urut
            const numberCell = row.querySelector('.row-number');
            if (numberCell) {
                numberCell.textContent = visibleCount;
            }
        } else {
            row.classList.add('hidden');
            row.style.display = 'none';
        }
    });
    
    // Update counter
    const totalCount = {{ $nasabah->count() }};
    document.getElementById('totalCount').textContent = visibleCount;
    
    // Tampilkan/sembunyikan "No Results"
    if (visibleCount === 0 && rows.length > 0) {
        noResults.classList.add('show');
    } else {
        noResults.classList.remove('show');
    }
    
    // Update filter info badge
    if (filterActive) {
        let infoText = 'Filter aktif: ';
        let filters = [];
        
        if (searchValue !== '') {
            filters.push(`Pencarian "${document.getElementById('searchNasabah').value}"`);
        }
        if (lokasiValue !== '') {
            filters.push(`Lokasi "${lokasiValue}"`);
        }
        if (jenisValue !== '') {
            filters.push(`Jenis "${jenisValue}"`);
        }
        
        filterInfoText.textContent = infoText + filters.join(', ');
        filterInfo.style.display = 'inline-flex';
    } else {
        filterInfo.style.display = 'none';
    }
}

// Reset filter
function resetFilter() {
    document.getElementById('searchNasabah').value = '';
    document.getElementById('filterLokasi').value = '';
    document.getElementById('filterJenis').value = '';
    filterTable();
    
    // Focus ke search box setelah reset
    document.getElementById('searchNasabah').focus();
}

// Debounce untuk search input (opsional, untuk performa lebih baik)
let searchTimeout;
document.getElementById('searchNasabah').addEventListener('input', function() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(filterTable, 300);
});

// Keyboard shortcut: Ctrl/Cmd + K untuk focus ke search
document.addEventListener('keydown', function(e) {
    if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
        e.preventDefault();
        document.getElementById('searchNasabah').focus();
    }
    
    // ESC untuk reset filter
    if (e.key === 'Escape') {
        resetFilter();
    }
});

// Auto-focus ke search saat halaman load
window.addEventListener('load', function() {
    const searchInput = document.getElementById('searchNasabah');
    if (searchInput) {
        searchInput.focus();
    }
});
</script>
@endpush