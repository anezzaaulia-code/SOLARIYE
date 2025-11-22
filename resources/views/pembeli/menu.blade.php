<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Menu</title>

    <style>
        body { background: #F2F2F2; margin: 0; font-family: Arial; }
        .header { background: #FF7A00; padding: 15px; color: white; font-size: 20px; font-weight: bold; }
        .container { padding: 15px; }

        .menu-card {
            background: white;
            border-radius: 15px;
            padding: 12px;
            margin-bottom: 15px;
            display: flex;
            gap: 15px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        img {
            width: 85px;
            height: 85px;
            object-fit: cover;
            border-radius: 12px;
        }

        .menu-name { font-size: 18px; font-weight: bold; }
        .price { color: #FF7A00; font-weight: bold; margin-top: 5px; }

        .btn-pesan {
            margin-left: auto;
            align-self: center;
            background: #FF7A00;
            padding: 7px 12px;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-size: 14px;
        }

        .bottom-nav {
            position: fixed; bottom: 0; left: 0; right: 0;
            background: white; padding: 10px 0; 
            display: flex; justify-content: space-around;
            border-top: 1px solid #ccc;
        }
        .nav-item { font-size: 13px; color: #444; }
        .active { color: #FF7A00; font-weight: bold; }
    </style>

</head>
<body>

    <div class="header">Semua Menu</div>

    <div class="container">
        @foreach($menus as $menu)
        <div class="menu-card">
            <img src="{{ asset('storage/' . $menu->foto) }}" alt="gambar">

            <div>
                <div class="menu-name">{{ $menu->nama }}</div>
                <div class="price">Rp {{ number_format($menu->harga, 0, ',', '.') }}</div>
            </div>

            <a href="{{ route('menu.show', $menu->menu_id) }}" class="btn-pesan">Pesan</a>
        </div>
        @endforeach
    </div>

    <div class="bottom-nav">
        <div class="nav-item">Home</div>
        <div class="nav-item active">Menu</div>
        <div class="nav-item">Keranjang</div>
        <div class="nav-item">Pesanan</div>
    </div>

</body>
</html>