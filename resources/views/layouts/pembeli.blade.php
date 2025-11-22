<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Pembeli' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #fafafa; }
        .menu-card { border-radius: 15px; overflow: hidden; }
        .menu-img { height: 150px; object-fit: cover; }
        .bottom-nav {
            position: fixed;
            bottom: 0; left: 0; right: 0;
            background: white; padding: 10px 0;
            display: flex; justify-content: space-around;
            border-top: 1px solid #ddd;
        }
        .bottom-nav a { text-align: center; color: #555; font-size: 14px; }
    </style>
</head>
<body>

    <div class="container py-3">
        @yield('content')
    </div>

    <div class="bottom-nav">
        <a href="/"><i class="bi bi-house"></i><br>Home</a>
        <a href="/menu"><i class="bi bi-list"></i><br>Menu</a>
        <a href="/keranjang"><i class="bi bi-cart"></i><br>Keranjang</a>
        <a href="/pesanan"><i class="bi bi-receipt"></i><br>Pesanan</a>
    </div>

</body>
</html>