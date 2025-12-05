<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - SOLARIYE</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <style>
        body {
            min-height: 100vh;
            display: flex;
            overflow-x: hidden;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f8f9fa;
        }

        /* SIDEBAR STYLE */
        #sidebar {
            min-width: 260px;
            max-width: 260px;
            background-color: #212529;
            color: #fff;
            transition: all 0.3s ease-in-out;
            display: flex;
            flex-direction: column;
            height: 100vh;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        #sidebar.collapsed {
            min-width: 80px;
            max-width: 80px;
        }

        /* HEADER */
        .sidebar-header {
            padding: 20px;
            border-bottom: 1px solid #343a40;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 70px;
        }
        .app-name { font-weight: bold; font-size: 1.2rem; margin-left: 10px; transition: 0.3s; white-space: nowrap;}
        #sidebar.collapsed .app-name { display: none; }

        /* MENU LINKS */
        #sidebar .nav-link {
            color: #adb5bd;
            padding: 12px 20px;
            display: flex;
            align-items: center;
            transition: 0.2s;
            border-left: 4px solid transparent;
            text-decoration: none;
            cursor: pointer;
        }
        #sidebar .nav-link:hover, #sidebar .nav-link.active {
            color: #fff;
            background-color: #343a40;
            border-left: 4px solid #0d6efd;
        }
        #sidebar .nav-link i { font-size: 1.2rem; min-width: 30px; text-align: center; }
        
        .link-text { margin-left: 10px; white-space: nowrap; transition: 0.3s; flex-grow: 1; }
        .dropdown-icon { transition: 0.3s; }
        
        #sidebar.collapsed .link-text, 
        #sidebar.collapsed .dropdown-icon { display: none; }

        /* SCROLLABLE AREA */
        .sidebar-menu {
            flex-grow: 1;
            overflow-y: auto;
            overflow-x: hidden;
            padding-top: 10px;
        }
        .sidebar-menu::-webkit-scrollbar { width: 5px; }
        .sidebar-menu::-webkit-scrollbar-track { background: #212529; }
        .sidebar-menu::-webkit-scrollbar-thumb { background: #495057; border-radius: 10px; }

        /* SUBMENU KEUANGAN (Manual Toggle) */
        .submenu-container {
            display: none; /* Default hidden */
            background-color: #2c3034;
        }
        .submenu-container.show {
            display: block;
        }
        .submenu-link {
            padding-left: 55px !important;
            font-size: 0.9rem;
            color: #adb5bd !important;
        }
        .submenu-link:hover, .submenu-link.active {
            color: white !important;
            background-color: #343a40;
        }

        /* FOOTER */
        .sidebar-footer {
            border-top: 1px solid #343a40;
            background: #1c1f23;
            padding: 15px;
        }
        .btn-logout {
            width: 100%;
            display: flex; align-items: center; justify-content: center; gap: 10px;
            padding: 10px; border-radius: 8px; transition: 0.2s;
            color: #ff6b6b; background: rgba(255, 107, 107, 0.1); border: 1px solid transparent;
            font-weight: 600;
        }
        .btn-logout:hover { background: #ff6b6b; color: white; }
        #sidebar.collapsed .btn-logout span { display: none; }

        .sidebar-toggle {
            width: 100%; background: transparent; border: none; color: #adb5bd;
            padding: 5px; margin-top: 10px; font-size: 1.2rem; cursor: pointer; transition: 0.2s;
            display: flex; justify-content: center;
        }
        .sidebar-toggle:hover { color: white; transform: scale(1.1); }

        /* CONTENT */
        #content {
            flex: 1; width: 100%; transition: 0.3s; overflow-y: auto;
        }
    </style>
</head>
<body>

    <nav id="sidebar">
        
        <div class="sidebar-header">
            <img src="{{ asset('storage/login/logo.jpg') }}" 
                alt="Logo"
                style="width: 40px; height: 40px; object-fit: contain;">
            <span class="app-name">SOLARIYE</span>
        </div>

        <div class="sidebar-menu">
            <ul class="nav nav-pills flex-column mb-auto">
                
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="bi bi-grid-fill"></i>
                        <span class="link-text">Dashboard</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('bahanbaku.index') }}" class="nav-link {{ request()->routeIs('bahanbaku.*') ? 'active' : '' }}">
                        <i class="bi bi-box-seam"></i>
                        <span class="link-text">Bahan Baku</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('menu.index') }}" class="nav-link {{ request()->routeIs('menu.*') ? 'active' : '' }}">
                        <i class="bi bi-cup-hot"></i>
                        <span class="link-text">Menu</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('supplier.index') }}" class="nav-link {{ request()->routeIs('supplier.*') ? 'active' : '' }}">
                        <i class="bi bi-truck"></i>
                        <span class="link-text">Supplier</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('stokharian.index') }}" class="nav-link {{ request()->routeIs('stokharian.*') ? 'active' : '' }}">
                        <i class="bi bi-calendar-check"></i>
                        <span class="link-text">Stok Harian</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('pembelian.index') }}" class="nav-link {{ request()->routeIs('pembelian.*') ? 'active' : '' }}">
                        <i class="bi bi-cart-plus"></i>
                        <span class="link-text">Pembelian Bahan</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link d-flex justify-content-between align-items-center" 
                       href="#" 
                       onclick="toggleKeuangan(event)">
                        
                        <div class="d-flex align-items-center">
                            <i class="bi bi-cash-coin"></i>
                            <span class="link-text">Keuangan</span>
                        </div>
                        <i class="bi bi-chevron-down dropdown-icon" id="iconKeuangan" style="font-size: 0.8rem;"></i>
                    </a>

                    <div class="submenu-container {{ request()->is('pendapatan*', 'pengeluaran*', 'keuangan*') ? 'show' : '' }}" id="submenuKeuangan">
                        <ul class="nav flex-column">
                            <li>
                                <a href="{{ route('pendapatan.index') }}" class="nav-link submenu-link {{ request()->routeIs('pendapatan.*') ? 'active' : '' }}">
                                    • Pendapatan
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('pengeluaran.index') }}" class="nav-link submenu-link {{ request()->routeIs('pengeluaran.*') ? 'active' : '' }}">
                                    • Pengeluaran
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('keuangan.laporan') }}" class="nav-link submenu-link {{ request()->routeIs('keuangan.laporan') ? 'active' : '' }}">
                                    • Laporan Keuangan
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li>
                    <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                        <i class="bi bi-people"></i>
                        <span class="link-text">Users</span>
                    </a>
                </li>

            </ul>
        </div>

        <div class="sidebar-footer">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="btn-logout" type="submit" title="Logout">
                    <i class="bi bi-box-arrow-right"></i>
                    <span>Logout</span>
                </button>
            </form>

            <button class="sidebar-toggle" id="sidebarCollapseBtn" title="Toggle Sidebar">
                <i class="bi bi-chevron-left"></i>
            </button>
        </div>

    </nav>

    <div id="content">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // Toggle Sidebar
        const sidebar = document.getElementById('sidebar');
        const toggleBtn = document.getElementById('sidebarCollapseBtn');
        const toggleIcon = toggleBtn.querySelector('i');

        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
            if (sidebar.classList.contains('collapsed')) {
                toggleIcon.classList.remove('bi-chevron-left');
                toggleIcon.classList.add('bi-chevron-right');
                // Tutup submenu jika sidebar di-collapse
                document.getElementById('submenuKeuangan').classList.remove('show');
            } else {
                toggleIcon.classList.remove('bi-chevron-right');
                toggleIcon.classList.add('bi-chevron-left');
            }
        });

        // Toggle Keuangan Manual (Anti-Flicker)
        function toggleKeuangan(e) {
            e.preventDefault(); // Mencegah refresh halaman
            const submenu = document.getElementById('submenuKeuangan');
            const icon = document.getElementById('iconKeuangan');
            
            // Jika sidebar collapsed, buka sidebar dulu agar submenu terlihat
            if (sidebar.classList.contains('collapsed')) {
                sidebar.classList.remove('collapsed');
                toggleIcon.classList.remove('bi-chevron-right');
                toggleIcon.classList.add('bi-chevron-left');
            }

            // Toggle class 'show'
            if (submenu.classList.contains('show')) {
                submenu.classList.remove('show');
                icon.classList.remove('bi-chevron-up');
                icon.classList.add('bi-chevron-down');
            } else {
                submenu.classList.add('show');
                icon.classList.remove('bi-chevron-down');
                icon.classList.add('bi-chevron-up');
            }
        }
    </script>

    @yield('scripts')

</body>
</html>