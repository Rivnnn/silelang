<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SILELANG') | Petugas Panel</title>

    <link rel="stylesheet" href="{{ asset('css/common.css') }}">

    <style>
        /* ============================================================
           DESIGN TOKENS
        ============================================================ */
        :root {
            --brand:        #39C6C9;
            --brand-dark:   #2FB3B6;
            --brand-deeper: #1E9C9C;
            --brand-light:  #E6F9F9;
            --brand-mid:    #9FE1D8;

            --navy:   #1a2e3b;
            --slate:  #4A5568;
            --muted:  #8A94A6;
            --border: #E8ECF2;
            --bg:     #f4f8f7;
            --white:  #FFFFFF;

            /* Sidebar lama: lebar 280px */
            --sidebar-w:        280px;
            --sidebar-w-tablet: 240px;
            --header-h:         70px;

            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 16px;

            --shadow-sm: 0 1px 4px rgba(0,0,0,0.06);
            --shadow-md: 0 4px 20px rgba(0,0,0,0.1);

            --font: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* ============================================================
           RESET
        ============================================================ */
        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: var(--font);
            min-height: 100vh;
            background: var(--bg);
            color: var(--navy);
            -webkit-font-smoothing: antialiased;
        }

        /* ============================================================
           SIDEBAR — STYLE DARI LAYOUT PERTAMA (logo bulat besar)
        ============================================================ */
        .sidebar {
            width: var(--sidebar-w);
            background: linear-gradient(180deg, #39C6C9, #2FB3B6);
            color: #fff;
            display: flex;
            flex-direction: column;
            box-shadow: 4px 0 25px rgba(0,0,0,0.15);
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            overflow-y: auto;
            overflow-x: hidden;
            z-index: 1000;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sidebar::-webkit-scrollbar { width: 6px; }
        .sidebar::-webkit-scrollbar-track { background: rgba(0,0,0,0.1); }
        .sidebar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.3); border-radius: 3px; }
        .sidebar::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,0.5); }

        /* Logo box: bulat besar seperti layout pertama */
        .logo-box {
            padding: 40px 20px 50px;
            text-align: center;
            background: linear-gradient(160deg, #7FE3E6, #39C6C9);
            border-bottom-left-radius: 45px;
            border-bottom-right-radius: 45px;
            flex-shrink: 0;
        }

        .logo-box img {
            width: 130px;
            height: 130px;
            object-fit: cover;
            border-radius: 50%;
            background: #fff;
            padding: 10px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.2);
        }

        .logo-box h3 {
            margin-top: 15px;
            font-size: 18px;
            font-weight: 700;
            color: #fff;
        }

        .logo-box p {
            font-size: 13px;
            color: rgba(255,255,255,0.9);
            margin-top: 5px;
        }

        /* Menu: gaya layout pertama */
        .menu {
            padding: 25px 18px;
            flex: 1;
        }

        .menu-label {
            font-size: 11px;
            text-transform: uppercase;
            color: rgba(255,255,255,0.7);
            margin-bottom: 12px;
            margin-top: 20px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .menu-label:first-child { margin-top: 0; }

        .menu a {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 14px 20px;
            margin-bottom: 8px;
            border-radius: 12px;
            background: rgba(255,255,255,0.15);
            color: #fff;
            text-decoration: none;
            font-size: 15px;
            font-weight: 500;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .menu a::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 3px;
            background: #fff;
            transform: scaleY(0);
            transition: transform 0.3s ease;
            border-radius: 0 2px 2px 0;
        }

        .menu a:hover {
            background: rgba(255,255,255,0.25);
            transform: translateX(5px);
        }

        .menu a:hover::before { transform: scaleY(1); }

        .menu a.active {
            background: rgba(255,255,255,0.35);
            font-weight: 700;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }

        .menu a.active::before { transform: scaleY(1); }

        .menu a .nav-icon { font-size: 20px; flex-shrink: 0; }

        /* Footer sidebar */
        .sidebar-footer {
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: rgba(255,255,255,0.8);
            background: rgba(0,0,0,0.15);
            flex-shrink: 0;
            line-height: 1.6;
        }

        /* ============================================================
           MAIN AREA — DARI LAYOUT KEDUA (header rapi)
        ============================================================ */
        .main {
            margin-left: var(--sidebar-w);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .header {
            background: var(--white);
            height: var(--header-h);
            padding: 0 clamp(16px, 3vw, 35px);
            box-shadow: 0 2px 15px rgba(0,0,0,0.08);
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 14px;
            min-width: 0;
        }

        .menu-toggle {
            display: none;
            background: var(--brand-light);
            color: var(--brand-dark);
            border: 1.5px solid var(--brand-mid);
            width: 38px;
            height: 38px;
            border-radius: var(--radius-sm);
            font-size: 20px;
            cursor: pointer;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            transition: background 0.18s;
        }

        .menu-toggle:hover { background: var(--brand-mid); }

        .header h2 {
            color: var(--brand);
            font-size: 24px;
            font-weight: 700;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Breadcrumb */
        .breadcrumb {
            font-size: 12px;
            color: var(--muted);
            display: flex;
            align-items: center;
            gap: 5px;
            margin-top: 2px;
        }

        .breadcrumb a {
            color: var(--muted);
            text-decoration: none;
            transition: color 0.15s;
        }

        .breadcrumb a:hover { color: var(--brand); }

        .header-right {
            display: flex;
            align-items: center;
            gap: 15px;
            flex-shrink: 0;
        }

        /* Back button */
        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 10px 20px;
            background: #95a5a6;
            color: #fff;
            text-decoration: none;
            border-radius: var(--radius-sm);
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .back-btn:hover {
            background: #7f8c8d;
            transform: translateY(-2px);
            color: #fff;
        }

        /* User info chip */
        .user-info {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 8px 18px;
            background: #f8f9fa;
            border-radius: 25px;
            border: 1px solid var(--border);
        }

        .user-info .avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--brand), var(--brand-dark));
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 700;
            font-size: 16px;
            flex-shrink: 0;
        }

        .user-info .user-details {
            display: flex;
            flex-direction: column;
            line-height: 1.3;
        }

        .user-info .user-name {
            font-weight: 700;
            color: var(--navy);
            font-size: 14px;
        }

        .user-info .user-role {
            font-size: 12px;
            color: var(--muted);
        }

        /* Logout */
        .logout-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 10px 22px;
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            color: #fff;
            text-decoration: none;
            border-radius: var(--radius-sm);
            font-weight: 700;
            font-size: 14px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(231,76,60,0.2);
        }

        .logout-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(231,76,60,0.3);
            color: #fff;
        }

        /* Content */
        .content {
            padding: clamp(20px, 3vw, 35px);
            flex: 1;
        }

        /* ============================================================
           OVERLAY
        ============================================================ */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.5);
            backdrop-filter: blur(2px);
            z-index: 999;
        }

        .sidebar-overlay.visible { display: block; }

        /* ============================================================
           RESPONSIVE
        ============================================================ */
        @media (max-width: 1100px) {
            :root { --sidebar-w: var(--sidebar-w-tablet); }

            .logo-box img { width: 100px; height: 100px; }
            .logo-box { padding: 30px 16px 38px; }
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                box-shadow: none;
            }

            .sidebar.open {
                transform: translateX(0);
                box-shadow: 4px 0 25px rgba(0,0,0,0.2);
            }

            .main { margin-left: 0; }
            .menu-toggle { display: flex; }
            .user-info { display: none; }

            .logout-btn .logout-text { display: none; }
            .logout-btn { padding: 10px 13px; }

            .header h2 { font-size: 18px; }
        }

        @media (max-width: 480px) {
            .header h2 { font-size: 15px; }
        }
    </style>

    @stack('styles')
</head>
<body>

<!-- ═══ SIDEBAR ═══ -->
<aside class="sidebar" id="sidebar" aria-label="Navigasi utama">

    <div class="logo-box">
        <img src="{{ asset('images/SIL.png') }}" alt="Logo SiLelang">
        <h3>PETUGAS LELANG</h3>
        <p>Sistem Informasi Lelang</p>
    </div>

    <nav class="menu" aria-label="Menu petugas">

        <div class="menu-label">Menu Utama</div>
        <a href="{{ route('petugas.dashboard') }}"
           class="{{ Request::is('petugas/dashboard') ? 'active' : '' }}"
           aria-current="{{ Request::is('petugas/dashboard') ? 'page' : 'false' }}">
            <span class="nav-icon">🏠</span> Dashboard
        </a>

        <div class="menu-label">Data Nasabah</div>
        <a href="{{ route('petugas.nasabah.index') }}"
           class="{{ Request::is('petugas/data-nasabah*') || Request::is('petugas/nasabah/*') ? 'active' : '' }}"
           aria-current="{{ (Request::is('petugas/data-nasabah*') || Request::is('petugas/nasabah/*')) ? 'page' : 'false' }}">
            <span class="nav-icon">👥</span> Nasabah
        </a>

        <div class="menu-label">Surat Menyurat</div>
        <a href="{{ route('petugas.nomor-surat') }}"
           class="{{ Request::is('petugas/nomor-surat') || Request::is('petugas/surat-keluar') || Request::is('petugas/memo') || Request::is('petugas/nota') ? 'active' : '' }}">
            <span class="nav-icon">📄</span> Nomor Surat
        </a>

        <div class="menu-label">Pengajuan</div>
        <a href="{{ route('petugas.pengajuan-lelang.index') }}"
           class="{{ Request::is('petugas/pengajuan-lelang') ? 'active' : '' }}">
            <span class="nav-icon">⚖️</span> Pengajuan Lelang
        </a>

        <a href="{{ route('petugas.dana-trr.index') }}"
           class="{{ Request::is('petugas/dana-trr*') ? 'active' : '' }}">
            <span class="nav-icon">💰</span> Dana TRR
        </a>

    </nav>

    <div class="sidebar-footer">
        © {{ date('Y') }} SiLelang<br>
        Bank Syariah Indonesia
    </div>

</aside>

<div class="sidebar-overlay" id="sidebarOverlay" aria-hidden="true"></div>

<!-- ═══ MAIN ═══ -->
<div class="main" id="mainContent">

    <!-- Header -->
    <header class="header">
        <div class="header-left">
            <button class="menu-toggle"
                    id="menuToggle"
                    aria-label="Buka/tutup menu"
                    aria-expanded="false"
                    aria-controls="sidebar">☰</button>
            <div>
                <div>
                    <h2>@yield('page-title', 'Dashboard')</h2>
                    @yield('breadcrumb')
                </div>
            </div>
        </div>

        <div class="header-right">
            @if(isset($showBackButton) && $showBackButton)
                <a href="{{ $backUrl ?? route('petugas.dashboard') }}" class="back-btn">← Kembali</a>
            @endif

            <div class="user-info">
                <div class="avatar">{{ strtoupper(substr(auth()->user()->name ?? 'P', 0, 1)) }}</div>
                <div class="user-details">
                    <span class="user-name">{{ auth()->user()->name ?? 'Petugas' }}</span>
                    <span class="user-role">Petugas Lelang</span>
                </div>
            </div>

            <a href="{{ route('logout') }}"
               class="logout-btn"
               onclick="return confirm('Yakin ingin keluar?')"
               title="Logout">
                ⏻ <span class="logout-text">Logout</span>
            </a>
        </div>
    </header>

    <!-- Content -->
    <main class="content" id="mainArea">
        @yield('content')
    </main>

</div>

<script>
(function () {
    const sidebar  = document.getElementById('sidebar');
    const overlay  = document.getElementById('sidebarOverlay');
    const toggle   = document.getElementById('menuToggle');
    let   isOpen   = false;

    function openSidebar() {
        isOpen = true;
        sidebar.classList.add('open');
        overlay.classList.add('visible');
        toggle.setAttribute('aria-expanded', 'true');
        document.body.style.overflow = 'hidden';
    }

    function closeSidebar() {
        isOpen = false;
        sidebar.classList.remove('open');
        overlay.classList.remove('visible');
        toggle.setAttribute('aria-expanded', 'false');
        document.body.style.overflow = '';
    }

    function toggleSidebar() { isOpen ? closeSidebar() : openSidebar(); }

    if (toggle)  toggle.addEventListener('click', toggleSidebar);
    if (overlay) overlay.addEventListener('click', closeSidebar);

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && isOpen) closeSidebar();
    });

    window.matchMedia('(min-width: 769px)').addEventListener('change', function (e) {
        if (e.matches) closeSidebar();
    });
})();
</script>

@stack('scripts')
</body>
</html>