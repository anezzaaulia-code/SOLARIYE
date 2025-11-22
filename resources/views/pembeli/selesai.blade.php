<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Terkirim</title>

    <style>
        body { font-family: Arial; padding: 15px; text-align: center; }

        .box {
            background: #fff;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            margin-top: 40px;
        }

        .btn {
            display: block;
            background: #FF7A00;
            padding: 12px;
            color: #fff;
            border-radius: 8px;
            margin-top: 15px;
            text-decoration: none;
        }
    </style>
</head>

<body>

<div class="box">
    <h2>Pesanan Berhasil Dibuat 🎉</h2>

    <p>Nomor Pesanan: <b>#{{ $pesanan->id }}</b></p>

    <p>Status: <b>{{ ucfirst($pesanan->status) }}</b></p>

    <a href="/" class="btn">Kembali ke Menu</a>
</div>

</body>
</html>
