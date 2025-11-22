<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $menu->nama }}</title>

    <style>
        body { font-family: Arial; margin: 0; padding: 0; }
        img { width: 100%; height: 260px; object-fit: cover; }

        .container { padding: 15px; }
        .name { font-size: 22px; font-weight: bold; }
        .price { font-size: 20px; color: #FF7A00; margin: 10px 0; }

        input, textarea {
            width: 100%; padding: 10px;
            border-radius: 8px;
            border: 1px solid #ccc;
            margin-top: 8px;
        }

        .btn {
            width: 100%;
            background: #FF7A00;
            color: white;
            text-align: center;
            padding: 12px;
            border-radius: 8px;
            margin-top: 15px;
            text-decoration: none;
            display: block;
        }
    </style>

</head>
<body>

<img src="{{ asset('storage/' . $menu->foto) }}">

<div class="container">

    <div class="name">{{ $menu->nama }}</div>
    <div class="price">Rp {{ number_format($menu->harga, 0, ',', '.') }}</div>

    <form action="{{ route('keranjang.tambah', $menu->id) }}" method="POST">
        @csrf

        <label>Jumlah</label>
        <input type="number" name="jumlah" min="1" required>

        <label>Catatan</label>
        <textarea name="catatan" placeholder="Pedas? Tambah telur?"></textarea>

        <button class="btn">Tambah ke Keranjang</button>
    </form>

</div>

</body>
</html>
