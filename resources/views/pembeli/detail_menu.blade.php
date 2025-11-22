<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Menu</title>

    <style>
        body { margin: 0; background: #F2F2F2; font-family: Arial; }

        .header {
            background: #FF7A00; padding: 15px;
            color: white; font-weight: bold; font-size: 18px;
        }

        .container { padding: 15px; }

        .menu-img {
            width: 100%; height: 220px; object-fit: cover;
            border-radius: 12px; margin-bottom: 15px;
        }

        .name { font-size: 22px; font-weight: bold; }
        .price { color: #FF7A00; font-size: 20px; font-weight: bold; margin-top: 5px; }

        label { font-size: 14px; font-weight: bold; display: block; margin-top: 15px; }
        input, textarea, select {
            width: 100%; padding: 10px; border-radius: 8px;
            border: 1px solid #ccc; margin-top: 5px; font-size: 14px;
        }

        .btn-order {
            width: 100%; margin-top: 20px; background: #FF7A00;
            padding: 12px; border-radius: 10px; color: white;
            font-size: 16px; text-align: center; display: block; text-decoration: none;
        }
    </style>

</head>
<body>

<div class="header">Detail Pesanan</div>

<div class="container">

    <img src="{{ asset('storage/' . $menu->foto) }}" class="menu-img">

    <div class="name">{{ $menu->nama }}</div>
    <div class="price">Rp {{ number_format($menu->harga, 0, ',', '.') }}</div>

    <form action="{{ route('pesanan.store') }}" method="POST">
        @csrf

        <input type="hidden" name="menu_id" value="{{ $menu->menu_id }}">

        <label>Nama Pembeli</label>
        <input type="text" name="nama_pembeli" required>

        <label>Jumlah</label>
        <input type="number" name="jumlah" min="1" value="1" required>

        <label>Catatan</label>
        <textarea name="catatan" rows="3" placeholder="Contoh: pedas, jangan banyak minyak"></textarea>

        <label>Titik Antar</label>
        <input type="text" name="titik_antar" placeholder="Masukkan lokasi">

        <!-- MAPS DUMMY -->
        <iframe 
            src="https://www.google.com/maps/embed?pb=!1m18..."
            width="100%" height="200" style="border:0; margin-top:10px;"
            allowfullscreen loading="lazy">
        </iframe>

        <label>Metode Pembayaran</label>
        <select name="metode_bayar" required>
            <option value="tunai">Tunai</option>
            <option value="qris">QRIS</option>
        </select>

        <button class="btn-order">Buat Pesanan</button>

    </form>

</div>

</body>
</html>