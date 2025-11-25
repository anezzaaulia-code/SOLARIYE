<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - SOLARIYE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            display: flex;
            overflow-x: hidden;
        }
        #sidebar {
            min-width: 250px;
            max-width: 250px;
            background: #343a40;
            color: #fff;
            transition: all 0.3s;
        }
        #sidebar.collapsed {
            min-width: 80px;
            max-width: 80px;
        }
        #sidebar .nav-link {
            color: #fff;
        }
        #sidebar .nav-link:hover {
            background: #495057;
        }
        #sidebar .nav-link i {
            margin-right: 10px;
        }
        #content {
            flex: 1;
            padding: 20px;
            background: #f8f9fa;
        }
        .sidebar-toggle {
            cursor: pointer;
        }
    </style>
</head>
<body>

    {{-- SIDEBAR --}}
    <div id="sidebar" class="d-flex flex-column p-3">
        <a href="{{ route('dashboard') }}" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
            <i class="bi bi-speedometer2 fs-3"></i>
            <span class="fs-5 fw-bold">SOLARIYE</span>
        </a>
        <hr>
        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item">
                <a href="{{ route('dashboard') }}" class="nav-link text-white">
                    <i class="bi bi-house-door"></i>
                    <span class="link-text">Dashboard</span>
                </a>
            </li>
            <li>
                <a href="{{ route('pembelian.index') }}" class="nav-link text-white">
                    <i class="bi bi-box-seam"></i>
                    <span class="link-text">Bahan Baku</span>
                </a>
            </li>
            <li>
                <a href="{{ route('menu.index') }}" class="nav-link text-white">
                    <i class="bi bi-list-ul"></i>
                    <span class="link-text">Menu</span>
                </a>
            </li>
            <li>
                <a href="{{ route('supplier.index') }}" class="nav-link text-white">
                    <i class="bi bi-truck"></i>
                    <span class="link-text">Supplier</span>
                </a>
            </li>
            <li>
                <a href="{{ route('pesanan.index') }}" class="nav-link text-white">
                    <i class="bi bi-cart-check"></i>
                    <span class="link-text">Pesanan</span>
                </a>
            </li>
            <li>
                <a href="{{ route('keuangan.index') }}" class="nav-link text-white">
                    <i class="bi bi-cash-stack"></i>
                    <span class="link-text">Keuangan</span>
                </a>
            </li>
            <li>
                <a href="{{ route('users.index') }}" class="nav-link text-white">
                    <i class="bi bi-people"></i>
                    <span class="link-text">Users</span>
                </a>
            </li>
        </ul>
        <hr>
        <div>
            <button class="btn btn-outline-light w-100 sidebar-toggle">
                <i class="bi bi-chevron-left"></i>
            </button>
        </div>
    </div>

    {{-- CONTENT --}}
    <div id="content">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const sidebar = document.getElementById('sidebar');
        const toggleBtn = document.querySelector('.sidebar-toggle');

        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
            // optional icon rotate
            toggleBtn.querySelector('i').classList.toggle('bi-chevron-right');
        });
    </script>

    @yield('scripts')
</body>
</html>
