<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Nasi Goreng</title>

    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; }
        .container { padding: 15px; }
        .title { font-size: 22px; font-weight: bold; margin-bottom: 15px; }

        .menu-card {
            background: #fff;
            border-radius: 15px;
            padding: 12px;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 15px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
        img {
            width: 80px;
            height: 80px;
            border-radius: 15px;
            object-fit: cover;
        }
        .menu-info { flex: 1; }
        .menu-name { font-size: 18px; font-weight: bold; }
        .price { color: #FF7A00; font-weight: bold; margin-top: 5px; }

        .order-btn {
            background: #FF7A00;
            color: white;
            padding: 8px 12px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 14px;
        }
    </style>

</head>
<body>

<div class="container">
    <div class="title">Menu Nasi Goreng</div>

    @foreach($menus as $menu)
    <div class="menu-card">
        <img src="{{ asset('storage/' . $menu->foto) }}" alt="foto">
        <div class="menu-info">
            <div class="menu-name">{{ $menu->nama }}</div>
            <div class="price">Rp {{ number_format($menu->harga, 0, ',', '.') }}</div>
        </div>

        <a href="{{ route('menu.show', $menu->id) }}" class="order-btn">Pesan</a>
    </div>
    @endforeach
</div>

</body>
</html>
