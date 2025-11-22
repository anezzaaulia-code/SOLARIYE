<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>

    <style>
        body { font-family: Arial; padding: 15px; }

        input, select, textarea {
            width: 100%;
            padding: 12px;
            margin-top: 8px;
            border-radius: 8px;
            border: 1px solid #bbb;
        }

        .btn {
            width: 100%;
            background: #FF7A00;
            color: white;
            padding: 15px;
            border-radius: 10px;
            margin-top: 15px;
            border: none;
            font-size: 17px;
        }
    </style>
</head>

<body>

<h2>Checkout</h2>

<form action="{{ route('pesanan.simpan') }}" method="POST">
@csrf

<label>Nama Pembeli</label>
<input type="text" name="nama_pembeli" required>

<label>Alamat / Titik Jemput</label>
<textarea name="alamat" required></textarea>

<label>Metode Pembayaran</label>
<select name="metode_pembayaran" required>
    <option value="tunai">Tunai</option>
    <option value="qris">QRIS</option>
</select>

<label>Ongkir (opsional)</label>
<input type="number" name="ongkir" min="0" value="0">

<button class="btn">Buat Pesanan</button>

</form>

</body>
</html>
