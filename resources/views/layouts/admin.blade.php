<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SILELANG') | Admin Panel</title>
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: #f5f7fa;
            overflow-x: hidden;
        }

        /* ================= FIXED SIDEBAR ================= */
        .sidebar {
            width: 260px;
            background: linear-gradient(180deg, #2c3e50, #34495e);
            color: #fff;
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            display: flex;
            flex-direction: column;
            box-shadow: 2px 0 15px rgba(0,0,0,0.1);
            z-index: 1001; /* Dinaikkan agar di atas overlay */
            transition: transform 0.3s ease; /* Animasi smooth */
        }

        /* Overlay untuk klik di luar sidebar saat mode HP */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1000;
        }

        /* Logo Section */
        .logo-box {
            padding: 25px 20px;
            text-align: center;
            background: linear-gradient(160deg, #34495e, #2c3e50);
            border-bottom: 1px solid rgba(255,255,255,0.1);
            flex-shrink: 0;
        }

        .logo-box img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 50%;
            background: #fff;
            padding: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }

        .logo-box h3 {
            margin-top: 12px;
            font-size: 16px;
            font-weight: 600;
            color: #ecf0f1;
        }

        .logo-box p {
            font-size: 11px;
            color: #bdc3c7;
            margin-top: 4px;
        }

        /* Menu Section */
        .menu {
            padding: 20px 14px;
            flex: 1;
            overflow-y: auto;
        }

        .menu::-webkit-scrollbar { width: 4px; }
        .menu::-webkit-scrollbar-track { background: rgba(0,0,0,0.1); }
        .menu::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.2); border-radius: 2px; }

        .menu-label {
            font-size: 10px;
            text-transform: uppercase;
            color: #95a5a6;
            margin-bottom: 8px;
            margin-top: 16px;
            font-weight: 600;
            letter-spacing: 0.5px;
            padding-left: 8px;
        }

        .menu-label:first-child { margin-top: 0; }

        .menu a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 16px;
            margin-bottom: 6px;
            border-radius: 10px;
            background: rgba(255,255,255,0.05);
            color: #ecf0f1;
            text-decoration: none;
            font-size: 14px;
            transition: all 0.2s ease;
            position: relative;
        }

        .menu a::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 3px;
            background: #3498db;
            transform: scaleY(0);
            transition: transform 0.2s ease;
            border-radius: 0 2px 2px 0;
        }

        .menu a:hover { background: rgba(255,255,255,0.12); transform: translateX(4px); }
        .menu a:hover::before { transform: scaleY(1); }

        .menu a.active {
            background: linear-gradient(135deg, #3498db, #2980b9);
            font-weight: 600;
            box-shadow: 0 3px 10px rgba(52,152,219,0.3);
        }

        .menu a.active::before { transform: scaleY(1); background: #fff; }
        .menu a span { font-size: 18px; width: 20px; text-align: center; }

        .sidebar-footer {
            padding: 16px;
            text-align: center;
            font-size: 11px;
            opacity: 0.7;
            background: rgba(0,0,0,0.1);
            border-top: 1px solid rgba(255,255,255,0.1);
            flex-shrink: 0;
        }

        /* ================= MAIN CONTENT AREA ================= */
        .main {
            margin-left: 260px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: margin-left 0.3s ease;
        }

        /* Header */
        .header {
            background: #fff;
            padding: 18px 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        /* TOMBOL HAMBURGER */
        .menu-toggle {
            display: none; /* Sembunyi di Desktop */
            background: #2c3e50;
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

        .header h2 {
            color: #2c3e50;
            font-size: 24px;
            font-weight: 600;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 16px;
            background: #f8f9fa;
            border-radius: 20px;
        }

        .user-info .avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, #3498db, #2980b9);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 600;
            font-size: 14px;
        }

        .user-info .user-details { display: flex; flex-direction: column; }
        .user-info .user-name { font-weight: 600; color: #2c3e50; font-size: 13px; }
        .user-info .user-role { font-size: 11px; color: #7f8c8d; }

        .logout-btn, .back-btn {
            padding: 9px 20px;
            color: #fff;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 13px;
            transition: all 0.2s ease;
            border: none;
            cursor: pointer;
        }

        .logout-btn {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            box-shadow: 0 3px 8px rgba(231,76,60,0.2);
        }

        .logout-btn:hover { transform: translateY(-2px); box-shadow: 0 5px 12px rgba(231,76,60,0.3); }
        .back-btn { background: #95a5a6; }
        .back-btn:hover { background: #7f8c8d; transform: translateY(-2px); }

        .content { padding: 30px; flex: 1; }

        /* ================= RESPONSIVE ================= */
        @media (max-width: 768px) {
            .menu-toggle { display: block; } /* Tampilkan tombol di HP */

            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.active {
                transform: translateX(0);
            }

            /* Overlay muncul saat sidebar aktif */
            .sidebar.active + .sidebar-overlay {
                display: block;
            }
            
            .main {
                margin-left: 0;
            }
            
            .header { padding: 15px 20px; }
            .header h2 { font-size: 18px; }
            .user-info { display: none; } /* Sembunyi info user di HP agar muat */
        }
    </style>

    @stack('styles')
</head>
<body>

    <div class="sidebar" id="sidebar">
        <div class="logo-box">
            <img src="{{ asset('images/SIL.png') }}" alt="Logo SiLelang">
            <h3>ADMIN PANEL</h3>
            <p>Sistem Informasi Lelang</p>
        </div>

        <div class="menu">
            <div class="menu-label">Menu Utama</div>
            <a href="{{ route('admin.dashboard') }}" class="{{ Request::is('admin/dashboard') ? 'active' : '' }}">
                <span>📊</span> Dashboard
            </a>

            <div class="menu-label">Monitoring</div>
            <a href="{{ route('admin.monitoring.nasabah') }}" class="{{ Request::is('admin/monitoring-nasabah*') ? 'active' : '' }}">
                <span>👥</span> Data Nasabah
            </a>
            <a href="{{ route('admin.monitoring.surat') }}" class="{{ Request::is('admin/monitoring-surat') ? 'active' : '' }}">
                <span>📄</span> Arsip Surat
            </a>
            <a href="{{ route('admin.monitoring.lelang') }}" class="{{ Request::is('admin/monitoring-lelang*') ? 'active' : '' }}">
                <span>⚖️</span> Pengajuan Lelang
            </a>

            <div class="menu-label">Manajemen</div>
            <a href="{{ route('admin.manajemen-user') }}" class="{{ Request::is('admin/manajemen-user') ? 'active' : '' }}">
                <span>👤</span> Kelola Petugas
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
                    <a href="{{ $backUrl ?? route('admin.dashboard') }}" class="back-btn">← Kembali</a>
                @endif
                <div class="user-info">
                    <div class="avatar">{{ strtoupper(substr(session('name', 'A'), 0, 1)) }}</div>
                    <div class="user-details">
                        <span class="user-name">{{ session('name', 'Admin') }}</span>
                        <span class="user-role">Administrator</span>
                    </div>
                </div>
                <a href="/logout" class="logout-btn">Logout</a>
            </div>
        </div>

        <div class="content">
            @yield('content')
        </div>
    </div>

    <script>
        // Logika JavaScript untuk membuka dan menutup sidebar
        const menuToggle = document.getElementById('menuToggle');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');

        function toggleMenu() {
            sidebar.classList.toggle('active');
        }

        menuToggle.addEventListener('click', toggleMenu);
        overlay.addEventListener('click', toggleMenu); // Klik overlay untuk tutup menu
    </script>

    @stack('scripts')
</body>
</html>