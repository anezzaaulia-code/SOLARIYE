<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beranda Pembeli</title>

    <style>
        body { 
            margin: 0; 
            padding: 0; 
            font-family: Arial, sans-serif; 
            background: #F2F2F2;
        }

        .header {
            background: #FF7A00;
            color: white;
            padding: 15px;
            font-size: 20px;
            font-weight: bold;
        }

        .container {
            padding: 15px;
        }

        .menu-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 12px;
        }

        .menu-card {
            background: white;
            padding: 12px;
            border-radius: 15px;
            display: flex;
            gap: 15px;
            margin-bottom: 15px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 12px;
        }

        .menu-name {
            font-size: 17px;
            font-weight: bold;
        }

        .menu-price {
            color: #FF7A00;
            margin-top: 5px;
            font-weight: bold;
        }

        .btn-pesan {
            margin-left: auto;
            align-self: center;
            background: #FF7A00;
            color: white;
            padding: 7px 12px;
            border-radius: 8px;
            font-size: 14px;
            text-decoration: none;
        }

        /* Bottom Navigation */
        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: white;
            padding: 10px 0;
            display: flex;
            justify-content: space-around;
            border-top: 1px solid #ddd;
        }

        .nav-item {
            text-align: center;
            font-size: 13px;
            color: #555;
        }

        .nav-item.active {
            color: #FF7A00;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <div class="header">Warung Nasi Goreng</div>

    <div class="container">

        <div class="menu-title">Rekomendasi Menu</div>

        @foreach($menus as $menu)
        <div class="menu-card">
            <img src="{{ asset('storage/' . $menu->foto) }}" alt="Foto Menu">

            <div>
                <div class="menu-name">{{ $menu->nama }}</div>
                <div class="menu-price">Rp {{ number_format($menu->harga, 0, ',', '.') }}</div>
            </div>

            <a href="{{ route('menu.show', $menu->menu_id) }}" class="btn-pesan">Pesan</a>
        </div>
        @endforeach

    </div>

    <!-- Bottom Navigation -->
    <div class="bottom-nav">
        <div class="nav-item active">Home</div>
        <div class="nav-item">Menu</div>
        <div class="nav-item">Keranjang</div>
        <div class="nav-item">Pesanan</div>
    </div>

</body>
</html>
