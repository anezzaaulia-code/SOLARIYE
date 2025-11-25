<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f7f9fc; }
        .sidebar {
            width: 230px;
            height: 100vh;
            background: #d9534f;
            color: white;
            position: fixed;
            padding: 20px;
        }
        .content {
            margin-left: 240px;
            padding: 25px;
        }
    </style>
</head>
<body>

<div class="sidebar">
    <h4>QUICKKASIR</h4>
    <hr>
    <a href="#" class="text-white d-block mt-3">Dashboard</a>
    <a href="#" class="text-white d-block mt-3">Kasir</a>
    <a href="#" class="text-white d-block mt-3">Transaksi</a>
    <a href="#" class="text-white d-block mt-3">Master Data</a>
    <a href="#" class="text-white d-block mt-3">Laporan</a>
</div>

<div class="content">
    @yield('content')
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
@yield('script')
</body>
</html>
