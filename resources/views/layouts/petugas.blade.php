<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SILELANG') | Petugas Panel</title>
    
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            min-height: 100vh;
            background: #f4f8f7;
        }

        /* ================= SIDEBAR ================= */
        .sidebar {
            width: 280px;
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
            z-index: 1000;
        }

        .logo-box {
            padding: 40px 20px 50px;
            text-align: center;
            background: linear-gradient(160deg, #7FE3E6, #39C6C9);
            border-bottom-left-radius: 45px;
            border-bottom-right-radius: 45px;
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
            font-weight: 600;
            color: #fff;
        }

        .logo-box p {
            font-size: 13px;
            color: rgba(255,255,255,0.9);
            margin-top: 5px;
        }

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
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .menu-label:first-child {
            margin-top: 0;
        }

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
        }

        .menu a:hover {
            background: rgba(255,255,255,0.25);
            transform: translateX(5px);
        }

        .menu a:hover::before {
            transform: scaleY(1);
        }

        .menu a.active {
            background: rgba(255,255,255,0.35);
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }

        .menu a.active::before {
            transform: scaleY(1);
        }

        .menu a span {
            font-size: 20px;
        }

        .sidebar-footer {
            margin-top: auto;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            opacity: 0.9;
            background: rgba(0,0,0,0.15);
        }

        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: rgba(0,0,0,0.1);
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.3);
            border-radius: 3px;
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: rgba(255,255,255,0.5);
        }

        .main {
            margin-left: 280px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .header {
            background: #fff;
            padding: 20px 35px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.08);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .header h2 {
            color: #39C6C9;
            font-size: 26px;
            font-weight: 600;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 8px 18px;
            background: #f8f9fa;
            border-radius: 25px;
        }

        .user-info .avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: linear-gradient(135deg, #39C6C9, #2FB3B6);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 600;
            font-size: 16px;
        }

        .user-info .user-details {
            display: flex;
            flex-direction: column;
        }

        .user-info .user-name {
            font-weight: 600;
            color: #2c3e50;
            font-size: 14px;
        }

        .user-info .user-role {
            font-size: 12px;
            color: #7f8c8d;
        }

        .logout-btn {
            padding: 10px 22px;
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            color: #fff;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(231,76,60,0.2);
        }

        .logout-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(231,76,60,0.3);
        }

        .back-btn {
            padding: 10px 22px;
            background: #95a5a6;
            color: #fff;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .back-btn:hover {
            background: #7f8c8d;
            transform: translateY(-2px);
        }

        .content {
            padding: 35px;
            flex: 1;
        }

        .menu-toggle {
            display: none;
            background: #39C6C9;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 20px;
            cursor: pointer;
            margin-right: 15px;
        }

        .header-left {
            display: flex;
            align-items: center;
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 999;
        }

        @media (max-width: 1024px) {
            .sidebar {
                width: 240px;
            }
            
            .main {
                margin-left: 240px;
            }
        }

        @media (max-width: 768px) {
            .menu-toggle {
                display: block;
            }
            
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            
            .sidebar.active {
                transform: translateX(0);
            }

            .sidebar.active + .sidebar-overlay {
                display: block;
            }
            
            .main {
                margin-left: 0;
            }
            
            .header {
                padding: 15px 20px;
            }
            
            .header h2 {
                font-size: 20px;
            }
            
            .user-info {
                display: none;
            }
        }
    </style>

    @stack('styles')
</head>
<body>
    <!-- SIDEBAR -->
    <div class="sidebar">
        <div class="logo-box">
            <img src="{{ asset('images/SIL.png') }}" alt="Logo SiLelang">
            <h3>PETUGAS LELANG</h3>
            <p>Sistem Informasi Lelang</p>
        </div>

        <div class="menu">
            <div class="menu-label">Menu Utama</div>
            <a href="{{ route('petugas.dashboard') }}" class="{{ Request::is('petugas/dashboard') ? 'active' : '' }}">
                <span>🏠</span> Dashboard
            </a>

            <div class="menu-label">Data & Dokumen</div>
            <a href="{{ route('petugas.nasabah.index') }}" class="{{ Request::is('petugas/data-nasabah*') || Request::is('petugas/nasabah/*') ? 'active' : '' }}">
                <span>👤</span> Data Nasabah
            </a>
            <a href="{{ route('petugas.dokumen.list') }}" class="{{ Request::is('petugas/upload-dokumen*') ? 'active' : '' }}">
                <span>📤</span> Upload Dokumen
            </a>
            <a href="{{ route('petugas.lpa.index') }}" class="{{ Request::is('petugas/lpa') ? 'active' : '' }}">
                <span>📊</span> Laporan Penilaian Agunan
            </a>

            <div class="menu-label">Surat Menyurat</div>
            <a href="{{ route('petugas.nomor-surat') }}" class="{{ Request::is('petugas/nomor-surat') || Request::is('petugas/surat-keluar') || Request::is('petugas/memo') || Request::is('petugas/nota') ? 'active' : '' }}">
                <span>📄</span> Nomor Surat
            </a>

            <div class="menu-label">Pengajuan</div>
            <a href="{{ route('petugas.pengajuan-lelang.index') }}" class="{{ Request::is('petugas/pengajuan-lelang') ? 'active' : '' }}">
                <span>⚖️</span> Pengajuan Lelang
            </a>
        </div>

        <div class="sidebar-footer">
            © {{ date('Y') }} SiLelang<br>
            Bank Syariah Indonesia
        </div>
    </div>

    <div class="sidebar-overlay" id="overlay"></div>

    <div class="main">
        <div class="header">
            <div class="header-left">
                <button class="menu-toggle" id="menuToggle">☰</button>
                <h2>@yield('page-title', 'Dashboard')</h2>
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
                <a href="{{ route('logout') }}" class="logout-btn">Logout</a>
            </div>
        </div>

        <div class="content">
            @yield('content')
        </div>
    </div>

    <script>
        const menuToggle = document.getElementById('menuToggle');
        const sidebar = document.querySelector('.sidebar');
        const overlay = document.getElementById('overlay');

        function toggleMenu() {
            sidebar.classList.toggle('active');
        }

        if (menuToggle) {
            menuToggle.addEventListener('click', toggleMenu);
        }

        if (overlay) {
            overlay.addEventListener('click', toggleMenu);
        }
    </script>

    @stack('scripts')
</body>
</html>