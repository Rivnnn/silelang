@extends('layouts.admin')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard Administrator')

@section('content')
{{-- Chart.js --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>

<style>
/* ═══════════════════════════════════════════════
   DESIGN TOKENS
   ═══════════════════════════════════════════════ */
:root {
    --color-bg        : #f0f4f8;
    --color-surface   : #ffffff;
    --color-border    : #e2e8f0;
    --color-text      : #1e293b;
    --color-text-muted: #64748b;
    --color-blue      : #2563eb;
    --color-green     : #16a34a;
    --color-orange    : #ea580c;
    --color-purple    : #7c3aed;
    --color-red       : #dc2626;
    --color-teal      : #0891b2;
    --radius-card     : 14px;
    --shadow-card     : 0 1px 3px rgba(0,0,0,.06), 0 4px 16px rgba(0,0,0,.05);
    --shadow-hover    : 0 4px 6px rgba(0,0,0,.05), 0 10px 30px rgba(0,0,0,.1);
    --font-mono       : 'JetBrains Mono', 'Fira Code', monospace;
}

/* ═══════════════════════════════════════════════
   LAYOUT
   ═══════════════════════════════════════════════ */
.db-wrapper { display: flex; flex-direction: column; gap: 28px; }

/* ═══════════════════════════════════════════════
   WELCOME BANNER
   ═══════════════════════════════════════════════ */
.welcome-banner {
    background  : var(--color-surface);
    border      : 1px solid var(--color-border);
    border-radius: var(--radius-card);
    padding     : 28px 32px;
    display     : flex;
    align-items : center;
    justify-content: space-between;
    gap         : 20px;
    box-shadow  : var(--shadow-card);
    position    : relative;
    overflow    : hidden;
}
.welcome-banner::before {
    content : '';
    position: absolute;
    inset   : 0;
    background: linear-gradient(105deg, rgba(37,99,235,.04) 0%, rgba(124,58,237,.04) 100%);
    pointer-events: none;
}
.welcome-banner__text h2 {
    font-size  : 22px;
    font-weight: 700;
    color      : var(--color-text);
    margin     : 0 0 6px;
    letter-spacing: -.3px;
}
.welcome-banner__text p {
    font-size : 14px;
    color     : var(--color-text-muted);
    margin    : 0;
    line-height: 1.6;
    max-width : 520px;
}
.welcome-banner__meta {
    display    : flex;
    align-items: center;
    gap        : 12px;
    flex-shrink: 0;
}
.date-badge {
    background   : #f1f5f9;
    border       : 1px solid var(--color-border);
    border-radius: 10px;
    padding      : 10px 16px;
    text-align   : center;
    min-width    : 100px;
}
.date-badge__day  { font-size: 26px; font-weight: 700; color: var(--color-blue); line-height: 1; font-family: var(--font-mono); }
.date-badge__month{ font-size: 12px; color: var(--color-text-muted); margin-top: 2px; text-transform: uppercase; letter-spacing: .5px; }

/* ═══════════════════════════════════════════════
   STATS GRID
   ═══════════════════════════════════════════════ */
.stats-row {
    display              : grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap                  : 16px;
}

.stat-card {
    background   : var(--color-surface);
    border       : 1px solid var(--color-border);
    border-radius: var(--radius-card);
    padding      : 22px 24px;
    display      : flex;
    align-items  : center;
    gap          : 16px;
    box-shadow   : var(--shadow-card);
    transition   : transform .2s ease, box-shadow .2s ease;
    cursor       : default;
    position     : relative;
    overflow     : hidden;
}
.stat-card::after {
    content   : '';
    position  : absolute;
    bottom    : 0; left: 0; right: 0;
    height    : 3px;
    background: var(--accent);
    opacity   : .7;
    border-radius: 0 0 var(--radius-card) var(--radius-card);
}
.stat-card:hover { transform: translateY(-3px); box-shadow: var(--shadow-hover); }

.stat-icon {
    width        : 52px;
    height       : 52px;
    border-radius: 12px;
    background   : var(--accent-bg);
    display      : flex;
    align-items  : center;
    justify-content: center;
    font-size    : 22px;
    flex-shrink  : 0;
}
.stat-body { flex: 1; min-width: 0; }
.stat-label {
    font-size  : 12px;
    font-weight: 600;
    color      : var(--color-text-muted);
    text-transform: uppercase;
    letter-spacing: .6px;
    margin-bottom: 4px;
}
.stat-value {
    font-size  : 30px;
    font-weight: 800;
    color      : var(--color-text);
    line-height: 1;
    font-family: var(--font-mono);
}
.stat-value.currency { font-size: 20px; }

/* Accent variants */
.stat-card.blue   { --accent: var(--color-blue);   --accent-bg: rgba(37,99,235,.08); }
.stat-card.green  { --accent: var(--color-green);  --accent-bg: rgba(22,163,74,.08); }
.stat-card.orange { --accent: var(--color-orange); --accent-bg: rgba(234,88,12,.08); }
.stat-card.purple { --accent: var(--color-purple); --accent-bg: rgba(124,58,237,.08); }
.stat-card.red    { --accent: var(--color-red);    --accent-bg: rgba(220,38,38,.08); }
.stat-card.teal   { --accent: var(--color-teal);   --accent-bg: rgba(8,145,178,.08); }

/* ═══════════════════════════════════════════════
   SECTION HEADERS
   ═══════════════════════════════════════════════ */
.section-header {
    display    : flex;
    align-items: center;
    gap        : 10px;
    margin-bottom: 14px;
}
.section-header h3 {
    font-size  : 16px;
    font-weight: 700;
    color      : var(--color-text);
    margin     : 0;
    letter-spacing: -.2px;
}
.section-header .pill {
    background   : #f1f5f9;
    border       : 1px solid var(--color-border);
    border-radius: 99px;
    padding      : 2px 10px;
    font-size    : 12px;
    color        : var(--color-text-muted);
    font-weight  : 500;
}

/* ═══════════════════════════════════════════════
   MAIN CONTENT ROW (Charts + Activity)
   ═══════════════════════════════════════════════ */
.main-row {
    display              : grid;
    grid-template-columns: 1fr 380px;
    gap                  : 20px;
    align-items          : start;
}
@media (max-width: 1100px) {
    .main-row { grid-template-columns: 1fr; }
}

/* ═══════════════════════════════════════════════
   CHART CARDS
   ═══════════════════════════════════════════════ */
.charts-col { display: flex; flex-direction: column; gap: 20px; }

.chart-card {
    background   : var(--color-surface);
    border       : 1px solid var(--color-border);
    border-radius: var(--radius-card);
    padding      : 24px;
    box-shadow   : var(--shadow-card);
}
.chart-card canvas { max-height: 220px; }

/* ═══════════════════════════════════════════════
   ACTIVITY FEED
   ═══════════════════════════════════════════════ */
.activity-card {
    background   : var(--color-surface);
    border       : 1px solid var(--color-border);
    border-radius: var(--radius-card);
    padding      : 24px;
    box-shadow   : var(--shadow-card);
    height       : fit-content;
}

.activity-list { display: flex; flex-direction: column; gap: 0; }

.activity-item {
    display    : flex;
    gap        : 14px;
    padding    : 14px 0;
    border-bottom: 1px solid #f1f5f9;
    position   : relative;
}
.activity-item:last-child { border-bottom: none; padding-bottom: 0; }
.activity-item:first-child { padding-top: 0; }

.activity-dot {
    width        : 36px;
    height       : 36px;
    border-radius: 10px;
    display      : flex;
    align-items  : center;
    justify-content: center;
    font-size    : 16px;
    flex-shrink  : 0;
    margin-top   : 1px;
}
.activity-dot.green  { background: rgba(22,163,74,.1); }
.activity-dot.red    { background: rgba(220,38,38,.1); }
.activity-dot.orange { background: rgba(234,88,12,.1); }
.activity-dot.blue   { background: rgba(37,99,235,.1); }
.activity-dot.purple { background: rgba(124,58,237,.1); }
.activity-dot.teal   { background: rgba(8,145,178,.1); }

.activity-content { flex: 1; min-width: 0; }
.activity-title {
    font-size  : 13px;
    font-weight: 600;
    color      : var(--color-text);
    margin-bottom: 2px;
    white-space : nowrap;
    overflow    : hidden;
    text-overflow: ellipsis;
}
.activity-desc {
    font-size   : 12px;
    color       : var(--color-text-muted);
    line-height : 1.4;
    display            : -webkit-box;
    -webkit-line-clamp : 2;
    -webkit-box-orient : vertical;
    overflow           : hidden;
}
.activity-time {
    font-size  : 11px;
    color      : #94a3b8;
    flex-shrink: 0;
    align-self : flex-start;
    margin-top : 3px;
    /* hapus: font-family: var(--font-mono) */
    white-space: nowrap;
}

/* ═══════════════════════════════════════════════
   QUICK ACTIONS
   ═══════════════════════════════════════════════ */
.quick-actions-card {
    background   : var(--color-surface);
    border       : 1px solid var(--color-border);
    border-radius: var(--radius-card);
    padding      : 24px;
    box-shadow   : var(--shadow-card);
}
.actions-grid {
    display              : grid;
    grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
    gap                  : 12px;
    margin-top           : 6px;
}
.action-btn {
    padding      : 18px 14px;
    background   : #f8fafc;
    border       : 1px solid var(--color-border);
    border-radius: 12px;
    text-align   : center;
    text-decoration: none;
    color        : var(--color-text);
    transition   : all .2s ease;
    display      : block;
}
.action-btn:hover {
    background  : #fff;
    border-color: var(--color-blue);
    transform   : translateY(-2px);
    box-shadow  : 0 4px 12px rgba(37,99,235,.12);
    color       : var(--color-blue);
}
.action-btn .ab-icon { font-size: 26px; display: block; margin-bottom: 8px; }
.action-btn .ab-label { font-size: 13px; font-weight: 600; line-height: 1.3; display: block; }
</style>

{{-- ═══════════════════════════════════════
     MARKUP
     ═══════════════════════════════════════ --}}
<div class="db-wrapper">

    {{-- WELCOME BANNER --}}
    <div class="welcome-banner">
        <div class="welcome-banner__text">
            <h2>Selamat Datang, {{ session('name') }} 👋</h2>
            <p>Pantau seluruh aktivitas lelang, rekonsiliasi dana TRR, dan arsip surat-menyurat SILELANG dari satu tempat.</p>
        </div>
        <div class="welcome-banner__meta">
            <div class="date-badge">
                <div class="date-badge__day">{{ now()->format('d') }}</div>
                <div class="date-badge__month">{{ now()->translatedFormat('M Y') }}</div>
            </div>
        </div>
    </div>

    {{-- STATS ROW 1 — UMUM --}}
    <div>
        <div class="section-header">
            <h3>📊 Statistik Umum</h3>
        </div>
        <div class="stats-row">
            <div class="stat-card blue">
                <div class="stat-icon">👥</div>
                <div class="stat-body">
                    <div class="stat-label">Total Petugas</div>
                    <div class="stat-value">{{ $stats['total_petugas'] ?? 0 }}</div>
                </div>
            </div>
            <div class="stat-card green">
                <div class="stat-icon">📋</div>
                <div class="stat-body">
                    <div class="stat-label">Total Nasabah</div>
                    <div class="stat-value">{{ $stats['total_nasabah'] ?? 0 }}</div>
                </div>
            </div>
            <div class="stat-card orange">
                <div class="stat-icon">⏳</div>
                <div class="stat-body">
                    <div class="stat-label">Pengajuan Pending</div>
                    <div class="stat-value">{{ $stats['total_pengajuan'] ?? 0 }}</div>
                </div>
            </div>
            <div class="stat-card purple">
                <div class="stat-icon">📄</div>
                <div class="stat-body">
                    <div class="stat-label">Total Surat</div>
                    <div class="stat-value">{{ $stats['total_surat'] ?? 0 }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- STATS ROW 2 — TRR --}}
    <div>
        <div class="section-header">
            <h3>💰 Rekonsiliasi Dana TRR</h3>
        </div>
        <div class="stats-row">
            <div class="stat-card blue">
                <div class="stat-icon">💸</div>
                <div class="stat-body">
                    <div class="stat-label">TRR Aktif</div>
                    <div class="stat-value currency">Rp {{ number_format($trr['total_aktif'] ?? 0, 0, ',', '.') }}</div>
                </div>
            </div>
            <div class="stat-card green">
                <div class="stat-icon">✅</div>
                <div class="stat-body">
                    <div class="stat-label">TRR Selesai (LPJ)</div>
                    <div class="stat-value currency">Rp {{ number_format($trr['total_selesai'] ?? 0, 0, ',', '.') }}</div>
                </div>
            </div>
            <div class="stat-card orange">
                <div class="stat-icon">📈</div>
                <div class="stat-body">
                    <div class="stat-label">Total Realisasi</div>
                    <div class="stat-value currency">Rp {{ number_format($trr['total_realisasi'] ?? 0, 0, ',', '.') }}</div>
                </div>
            </div>
            <div class="stat-card {{ isset($trr['selisih']) && $trr['selisih'] >= 0 ? 'teal' : 'red' }}">
                <div class="stat-icon">⚖️</div>
                <div class="stat-body">
                    <div class="stat-label">Selisih Dana</div>
                    <div class="stat-value currency">
                        {{ isset($trr['selisih']) && $trr['selisih'] < 0 ? '−' : '' }}Rp {{ number_format(abs($trr['selisih'] ?? 0), 0, ',', '.') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MAIN ROW: CHARTS + ACTIVITY --}}
    <div class="main-row">

        {{-- CHARTS --}}
        <div class="charts-col">

            {{-- Chart 1: Pengajuan Lelang --}}
            <div class="chart-card">
                <div class="section-header">
                    <h3>📉 Tren Pengajuan Lelang</h3>
                    <span class="pill">6 Bulan Terakhir</span>
                </div>
                <canvas id="chartPengajuan"></canvas>
            </div>

            {{-- Chart 2: Realisasi TRR --}}
            <div class="chart-card">
                <div class="section-header">
                    <h3>💹 Arus Dana TRR</h3>
                    <span class="pill">6 Bulan Terakhir</span>
                </div>
                <canvas id="chartTrr"></canvas>
            </div>

        </div>

        {{-- ACTIVITY FEED --}}
        <div class="activity-card">
            <div class="section-header">
                <h3>🕐Aktivitas Terkini</h3>
                <span class="pill">{{ $activities->count() }} item</span>
            </div>
            <div class="activity-list">
                @forelse($activities as $act)
                <div class="activity-item">
                    <div class="activity-dot {{ $act['color'] }}">{{ $act['icon'] }}</div>
                    <div class="activity-content">
                        <div class="activity-title" title="{{ $act['title'] }}">{{ $act['title'] }}</div>
                        <div class="activity-desc" title="{{ $act['desc'] }}">{{ Str::before($act['desc'], ' —') ?: $act['desc'] }}</div>
                    </div>
                    <div class="activity-time">{{ $act['time']->diffForHumans() }}</div>
                </div>
                @empty
                <div style="text-align:center; padding: 24px 0; color: var(--color-text-muted); font-size: 13px;">
                    Belum ada aktivitas tercatat.
                </div>
                @endforelse
            </div>
        </div>

    </div>

    {{-- QUICK ACTIONS --}}
    <div class="quick-actions-card">
        <div class="section-header"><h3>⚡ Akses Cepat</h3></div>
        <div class="actions-grid">
            <a href="/admin/monitoring-nasabah" class="action-btn">
                <span class="ab-icon">👥</span>
                <span class="ab-label">Semua Nasabah</span>
            </a>
            <a href="/admin/manajemen-user" class="action-btn">
                <span class="ab-icon">⚙️</span>
                <span class="ab-label">Kelola Petugas</span>
            </a>
            <a href="/admin/monitoring-lelang" class="action-btn">
                <span class="ab-icon">⚖️</span>
                <span class="ab-label">Review Pengajuan</span>
            </a>
            <a href="/admin/monitoring-surat" class="action-btn">
                <span class="ab-icon">📄</span>
                <span class="ab-label">Arsip Surat</span>
            </a>
            <a href="/admin/monitoring-trr" class="action-btn">
                <span class="ab-icon">💰</span>
                <span class="ab-label">Monitor TRR</span>
            </a>
        </div>
    </div>

</div>

{{-- ═══════════════════════════════════════
     CHART.JS INIT
     ═══════════════════════════════════════ --}}
<script>
// Data dari Blade → JS
const dataPengajuan = @json($chartPengajuan);
const dataTrr       = @json($chartTrr);

// ── Helper: default font ──────────────────
Chart.defaults.font.family = "'Segoe UI', Tahoma, Geneva, Verdana, sans-serif";
Chart.defaults.font.size   = 12;
Chart.defaults.color       = '#64748b';

// ── Chart 1: Pengajuan Lelang (Grouped Bar) ──
const labelsPengajuan = dataPengajuan.map(r => r.label);
new Chart(document.getElementById('chartPengajuan'), {
    type: 'bar',
    data: {
        labels  : labelsPengajuan,
        datasets: [
            {
                label          : 'Disetujui',
                data           : dataPengajuan.map(r => r.disetujui),
                backgroundColor: 'rgba(22,163,74,.75)',
                borderRadius   : 6,
                borderSkipped  : false,
            },
            {
                label          : 'Ditolak',
                data           : dataPengajuan.map(r => r.ditolak),
                backgroundColor: 'rgba(220,38,38,.75)',
                borderRadius   : 6,
                borderSkipped  : false,
            },
            {
                label          : 'Pending',
                data           : dataPengajuan.map(r => r.pending),
                backgroundColor: 'rgba(234,88,12,.75)',
                borderRadius   : 6,
                borderSkipped  : false,
            },
        ],
    },
    options: {
        responsive         : true,
        maintainAspectRatio: true,
        plugins: {
            legend: { position: 'bottom', labels: { boxWidth: 12, padding: 16 } },
            tooltip: {
                callbacks: {
                    label: ctx => ` ${ctx.dataset.label}: ${ctx.parsed.y} pengajuan`
                }
            }
        },
        scales: {
            x: { grid: { display: false }, border: { display: false } },
            y: {
                beginAtZero: true,
                ticks: { stepSize: 1, precision: 0 },
                grid : { color: '#f1f5f9' },
                border: { display: false },
            },
        },
    },
});

// ── Chart 2: Arus Dana TRR (Line) ──
const labelsTrr = dataTrr.map(r => r.label);
new Chart(document.getElementById('chartTrr'), {
    type: 'line',
    data: {
        labels  : labelsTrr,
        datasets: [
            {
                label          : 'Dana Masuk (Kredit)',
                data           : dataTrr.map(r => r.total_kredit),
                borderColor    : '#2563eb',
                backgroundColor: 'rgba(37,99,235,.08)',
                borderWidth    : 2.5,
                pointRadius    : 5,
                pointHoverRadius: 7,
                fill           : true,
                tension        : 0.4,
            },
            {
                label          : 'Realisasi (Debet)',
                data           : dataTrr.map(r => r.total_debet),
                borderColor    : '#ea580c',
                backgroundColor: 'rgba(234,88,12,.06)',
                borderWidth    : 2.5,
                pointRadius    : 5,
                pointHoverRadius: 7,
                fill           : true,
                tension        : 0.4,
            },
        ],
    },
    options: {
        responsive         : true,
        maintainAspectRatio: true,
        plugins: {
            legend: { position: 'bottom', labels: { boxWidth: 12, padding: 16 } },
            tooltip: {
                callbacks: {
                    label: ctx => {
                        const val = ctx.parsed.y ?? 0;
                        return ` ${ctx.dataset.label}: Rp ${val.toLocaleString('id-ID')}`;
                    }
                }
            }
        },
        scales: {
            x: { grid: { display: false }, border: { display: false } },
            y: {
                beginAtZero: true,
                grid       : { color: '#f1f5f9' },
                border     : { display: false },
                ticks      : {
                    callback: val => 'Rp ' + (val >= 1_000_000
                        ? (val / 1_000_000).toFixed(1) + 'jt'
                        : val.toLocaleString('id-ID'))
                },
            },
        },
    },
});
</script>
@endsection