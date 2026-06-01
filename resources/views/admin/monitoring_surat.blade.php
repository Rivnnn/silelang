@extends('layouts.admin')

@section('title', 'Monitoring Arsip Surat')
@section('page-title', 'Monitoring Arsip Surat')

@section('content')
<style>
    /* ================= STATS ROW ================= */
    .stats-row {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
        margin-bottom: 24px;
    }

    .stat-mini {
        background: #fff;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.06);
        text-align: center;
        transition: all 0.3s ease;
    }

    .stat-mini:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(0,0,0,0.1);
    }

    .stat-mini-value {
        font-size: 32px;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 5px;
        background: linear-gradient(135deg, #667eea, #764ba2);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .stat-mini-label {
        font-size: 13px;
        color: #7f8c8d;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .stat-mini-icon {
        font-size: 24px;
        margin-bottom: 8px;
    }

    /* ================= TABS ================= */
    .tabs {
        display: flex;
        gap: 10px;
        margin-bottom: 24px;
        background: #fff;
        padding: 10px;
        border-radius: 12px;
        box-shadow: 0 3px 15px rgba(0,0,0,0.06);
    }

    .tab-btn {
        flex: 1;
        padding: 12px;
        background: transparent;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        font-size: 14px;
        color: #7f8c8d;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .tab-btn:hover {
        background: #f8f9fa;
    }

    .tab-btn.active {
        background: linear-gradient(135deg, #3498db, #2980b9);
        color: #fff;
        box-shadow: 0 4px 10px rgba(52,152,219,0.3);
    }

    /* ================= TABLE BOX ================= */
    .table-box {
        background: #fff;
        padding: 28px;
        border-radius: 14px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.06);
        display: none;
        animation: fadeIn 0.4s ease;
    }

    .table-box.active {
        display: block;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .table-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        padding-bottom: 16px;
        border-bottom: 2px solid #f0f0f0;
    }

    .table-header h3 {
        color: #2c3e50;
        font-size: 20px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .table-header h3::before {
        content: '';
        width: 4px;
        height: 24px;
        background: linear-gradient(135deg, #3498db, #2980b9);
        border-radius: 2px;
    }

    .search-box {
        position: relative;
    }

    .search-box input {
        padding: 10px 16px 10px 40px;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        width: 300px;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .search-box input:focus {
        outline: none;
        border-color: #3498db;
        box-shadow: 0 0 0 3px rgba(52,152,219,0.1);
    }

    .search-box::before {
        content: '🔍';
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 14px;
    }

    /* ================= TABLE ================= */
    .table-container {
        overflow-x: auto;
        border-radius: 10px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    thead {
        background: linear-gradient(135deg, #34495e, #2c3e50);
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    th {
        padding: 16px 14px;
        text-align: left;
        color: #fff;
        font-weight: 700;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        white-space: nowrap;
    }

    tbody tr {
        border-bottom: 1px solid #e0e0e0;
        transition: all 0.2s ease;
    }

    tbody tr:hover {
        background: linear-gradient(90deg, #f8f9fa 0%, #fff 100%);
        transform: scale(1.005);
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }

    tbody tr:last-child {
        border-bottom: none;
    }

    td {
        padding: 16px 14px;
        color: #2c3e50;
        font-size: 14px;
    }

    /* ================= NOMOR BADGE ================= */
    .nomor-badge {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: #fff;
        padding: 8px 14px;
        border-radius: 8px;
        font-weight: 700;
        font-size: 13px;
        display: inline-block;
        box-shadow: 0 3px 10px rgba(102, 126, 234, 0.3);
        letter-spacing: 0.5px;
        font-family: 'Courier New', monospace;
    }

    /* ================= PETUGAS INFO ================= */
    .petugas-info {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .petugas-avatar {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea, #764ba2);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-weight: 700;
        font-size: 12px;
        box-shadow: 0 3px 8px rgba(102, 126, 234, 0.3);
    }

    .petugas-name {
        font-weight: 500;
        color: #2c3e50;
    }

    /* ================= EMPTY STATE ================= */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #95a5a6;
    }

    .empty-state .icon {
        font-size: 72px;
        display: block;
        margin-bottom: 16px;
        opacity: 0.5;
    }

    .empty-state h4 {
        font-size: 18px;
        margin-bottom: 8px;
        color: #7f8c8d;
        font-weight: 600;
    }

    .empty-state p {
        font-size: 14px;
        color: #95a5a6;
    }

    /* ================= RESPONSIVE ================= */
    @media (max-width: 768px) {
        .stats-row {
            grid-template-columns: 1fr;
        }

        .tabs {
            flex-direction: column;
        }

        .search-box input {
            width: 100%;
        }

        .table-header {
            flex-direction: column;
            gap: 16px;
            align-items: flex-start;
        }
    }
</style>

<!-- Stats Mini -->
<div class="stats-row">
    <div class="stat-mini">
        <div class="stat-mini-icon">📄</div>
        <div class="stat-mini-value">{{ $suratKeluar->count() }}</div>
        <div class="stat-mini-label">Surat Keluar</div>
    </div>
    <div class="stat-mini">
        <div class="stat-mini-icon">📝</div>
        <div class="stat-mini-value">{{ $memo->count() }}</div>
        <div class="stat-mini-label">Memo</div>
    </div>
    <div class="stat-mini">
        <div class="stat-mini-icon">🧾</div>
        <div class="stat-mini-value">{{ $nota->count() }}</div>
        <div class="stat-mini-label">Nota</div>
    </div>
</div>

<!-- Tabs -->
<div class="tabs">
    <button class="tab-btn active" onclick="switchTab('surat-keluar')">
        📄 Surat Keluar
    </button>
    <button class="tab-btn" onclick="switchTab('memo')">
        📝 Memo
    </button>
    <button class="tab-btn" onclick="switchTab('nota')">
        🧾 Nota
    </button>
</div>

<!-- Tab Content: Surat Keluar -->
<div class="table-box active" id="tab-surat-keluar">
    <div class="table-header">
        <h3>Arsip Surat Keluar</h3>
        <div class="search-box">
            <input type="text" placeholder="Cari perihal atau tujuan..." onkeyup="filterTable('surat-keluar')">
        </div>
    </div>

    <div class="table-container">
        <table id="table-surat-keluar">
            <thead>
                <tr>
                    <th style="width: 60px;">No</th>
                    <th>Tanggal</th>
                    <th>Nomor Surat</th>
                    <th>Perihal</th>
                    <th>Tujuan</th>
                    <th>PIC</th>
                    <th>Dibuat Oleh</th>
                </tr>
            </thead>
            <tbody>
                @forelse($suratKeluar as $item)
                <tr>
                    <td style="text-align: center; font-weight: 600; color: #7f8c8d;">
                        {{ $loop->iteration }}
                    </td>
                    <td style="white-space: nowrap;">
                        {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}
                    </td>
                    <td><span class="nomor-badge">{{ $item->nomor_surat }}</span></td>
                    <td style="font-weight: 500;">{{ $item->perihal }}</td>
                    <td style="color: #5a6c7d;">{{ $item->tujuan }}</td>
                    <td style="color: #5a6c7d;">{{ $item->pic }}</td>
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
                </tr>
                @empty
                <tr>
                    <td colspan="7">
                        <div class="empty-state">
                            <span class="icon">📄</span>
                            <h4>Belum Ada Data Surat Keluar</h4>
                            <p>Belum ada surat keluar yang dibuat oleh petugas</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Tab Content: Memo -->
<div class="table-box" id="tab-memo">
    <div class="table-header">
        <h3>Arsip Memo</h3>
        <div class="search-box">
            <input type="text" placeholder="Cari perihal atau tujuan..." onkeyup="filterTable('memo')">
        </div>
    </div>

    <div class="table-container">
        <table id="table-memo">
            <thead>
                <tr>
                    <th style="width: 60px;">No</th>
                    <th>Tanggal</th>
                    <th>Nomor Memo</th>
                    <th>Perihal</th>
                    <th>Tujuan</th>
                    <th>PIC</th>
                    <th>Dibuat Oleh</th>
                </tr>
            </thead>
            <tbody>
                @forelse($memo as $item)
                <tr>
                    <td style="text-align: center; font-weight: 600; color: #7f8c8d;">
                        {{ $loop->iteration }}
                    </td>
                    <td style="white-space: nowrap;">
                        {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}
                    </td>
                    <td><span class="nomor-badge">{{ $item->nomor_memo }}</span></td>
                    <td style="font-weight: 500;">{{ $item->perihal }}</td>
                    <td style="color: #5a6c7d;">{{ $item->tujuan }}</td>
                    <td style="color: #5a6c7d;">{{ $item->pic }}</td>
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
                </tr>
                @empty
                <tr>
                    <td colspan="7">
                        <div class="empty-state">
                            <span class="icon">📝</span>
                            <h4>Belum Ada Data Memo</h4>
                            <p>Belum ada memo yang dibuat oleh petugas</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Tab Content: Nota -->
<div class="table-box" id="tab-nota">
    <div class="table-header">
        <h3>Arsip Nota</h3>
        <div class="search-box">
            <input type="text" placeholder="Cari perihal atau tujuan..." onkeyup="filterTable('nota')">
        </div>
    </div>

    <div class="table-container">
        <table id="table-nota">
            <thead>
                <tr>
                    <th style="width: 60px;">No</th>
                    <th>Tanggal</th>
                    <th>Nomor Nota</th>
                    <th>Perihal</th>
                    <th>Tujuan</th>
                    <th>PIC</th>
                    <th>Dibuat Oleh</th>
                </tr>
            </thead>
            <tbody>
                @forelse($nota as $item)
                <tr>
                    <td style="text-align: center; font-weight: 600; color: #7f8c8d;">
                        {{ $loop->iteration }}
                    </td>
                    <td style="white-space: nowrap;">
                        {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}
                    </td>
                    <td><span class="nomor-badge">{{ $item->nomor_nota }}</span></td>
                    <td style="font-weight: 500;">{{ $item->perihal }}</td>
                    <td style="color: #5a6c7d;">{{ $item->tujuan }}</td>
                    <td style="color: #5a6c7d;">{{ $item->pic }}</td>
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
                </tr>
                @empty
                <tr>
                    <td colspan="7">
                        <div class="empty-state">
                            <span class="icon">🧾</span>
                            <h4>Belum Ada Data Nota</h4>
                            <p>Belum ada nota yang dibuat oleh petugas</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
function switchTab(tabName) {
    // Remove active class from all tabs and buttons
    const allTabs = document.querySelectorAll('.table-box');
    allTabs.forEach(tab => tab.classList.remove('active'));
    
    const allButtons = document.querySelectorAll('.tab-btn');
    allButtons.forEach(btn => btn.classList.remove('active'));
    
    // Add active class to selected tab and button
    document.getElementById('tab-' + tabName).classList.add('active');
    event.target.classList.add('active');
}

function filterTable(type) {
    const input = event.target.value.toLowerCase();
    const table = document.getElementById('table-' + type);
    const rows = table.querySelectorAll('tbody tr');
    
    rows.forEach(row => {
        // Skip if this is the empty state row
        if (row.querySelector('.empty-state')) {
            return;
        }
        
        const perihal = row.cells[3].textContent.toLowerCase();
        const tujuan = row.cells[4].textContent.toLowerCase();
        const pic = row.cells[5].textContent.toLowerCase();
        const petugas = row.cells[6].textContent.toLowerCase();
        
        if (perihal.includes(input) || tujuan.includes(input) || pic.includes(input) || petugas.includes(input)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}
</script>
@endpush