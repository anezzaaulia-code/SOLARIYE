<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - SOLARIYE</title>

    <!-- BOOTSTRAP -->
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
                <a href="{{ route('bahanbaku.index') }}" class="nav-link text-white">
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
                <a href="{{ route('stokharian.index') }}" class="nav-link text-white">
                    <i class="bi bi-calendar-check"></i>
                    <span class="link-text">Stok Harian</span>
                </a>
            </li>

            <!-- 📌 MENU BARU: Pembelian Bahan -->
            <li>
                <a href="{{ route('pembelian.index') }}" class="nav-link text-white">
                    <i class="bi bi-cart-plus"></i>
                    <span class="link-text">Pembelian Bahan</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white d-flex justify-content-between align-items-center" 
                data-bs-toggle="collapse" 
                href="#submenuKeuangan" 
                role="button" 
                aria-expanded="false" 
                aria-controls="submenuKeuangan">
                    <div>
                        <i class="bi bi-cash-stack"></i>
                        <span class="link-text">Keuangan</span>
                    </div>
                    <i class="bi bi-chevron-down"></i>
                </a>

                <div class="collapse ms-4" id="submenuKeuangan">
                    <ul class="nav flex-column">

                        <li>
                            <a href="{{ route('pendapatan') }}" class="nav-link text-white">
                                <i class="bi bi-coin"></i>
                                Pendapatan
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('pengeluaran') }}" class="nav-link text-white">
                                <i class="bi bi-receipt-cutoff"></i>
                                Pengeluaran
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('keuangan.laporan') }}" class="nav-link text-white">
                                <i class="bi bi-bar-chart"></i>
                                Laporan Keuangan
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li>
                <a href="{{ route('users.index') }}" class="nav-link text-white">
                    <i class="bi bi-people"></i>
                    <span class="link-text">Users</span>
                </a>
            </li>
        </ul>

        <hr>

        {{-- LOGOUT BUTTON --}}
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button class="btn btn-danger w-100 mb-2">
                <i class="bi bi-box-arrow-right"></i> Logout
            </button>
        </form>

        {{-- COLLAPSE BUTTON --}}
        <button class="btn btn-outline-light w-100 sidebar-toggle">
            <i class="bi bi-chevron-left"></i>
        </button>
    </div>

    {{-- CONTENT AREA --}}
    <div id="content">
        @yield('content')
    </div>

    <!-- SCRIPTS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const sidebar = document.getElementById('sidebar');
        const toggleBtn = document.querySelector('.sidebar-toggle');

        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
            toggleBtn.querySelector('i').classList.toggle('bi-chevron-right');
        });
    </script>

    @yield('scripts')

</body>
</html>
