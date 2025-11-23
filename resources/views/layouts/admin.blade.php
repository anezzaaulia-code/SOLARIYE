<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>

    {{-- Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    {{-- Custom CSS --}}
    <style>
        body { background: #f4f6f9; }

        .sidebar {
            width: 250px;
            height: 100vh;
            background: #343a40;
            position: fixed;
            top: 0;
            left: 0;
            color: #fff;
        }

        .sidebar .menu a {
            color: #adb5bd;
            text-decoration: none;
            padding: 12px 20px;
            display: block;
        }
        .sidebar .menu a:hover, .sidebar .menu .active {
            background: #495057;
            color: #fff;
        }

        .content {
            margin-left: 250px;
            padding: 25px;
        }

        .topbar {
            background: #6f42c1;
            padding: 15px;
            color: #fff;
            margin-left: 250px;
        }

        .card-stats {
            padding: 20px;
            border-radius: 8px;
            color: #fff;
        }
    </style>
</head>

<body>

    {{-- SIDEBAR --}}
    <div class="sidebar">
        <div class="p-3 border-bottom">
            <h4 class="text-white">Admin Panel</h4>
        </div>

        <div class="menu mt-3">
            <a href="#" class="active"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
            <a href="#"><i class="bi bi-basket me-2"></i> Menu</a>
            <a href="#"><i class="bi bi-box-seam me-2"></i> Bahan Baku</a>
            <a href="#"><i class="bi bi-truck me-2"></i> Supplier</a>
            <a href="#"><i class="bi bi-receipt me-2"></i> Pembelian</a>
            <a href="#"><i class="bi bi-cash-coin me-2"></i> Keuangan</a>
            <a href="#"><i class="bi bi-people me-2"></i> Users</a>
        </div>
    </div>

    {{-- TOPBAR --}}
    <div class="topbar d-flex justify-content-between align-items-center">
        <span class="fw-bold fs-5">Dashboard</span>

        <div>
            <i class="bi bi-bell me-3 fs-5"></i>
            <span class="me-2">admin</span>
            <img src="https://i.pravatar.cc/40" class="rounded-circle" width="35">
        </div>
    </div>

    {{-- CONTENT --}}
    <div class="content">
        @yield('content')
    </div>

    {{-- JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    @yield('scripts')

</body>
</html>
