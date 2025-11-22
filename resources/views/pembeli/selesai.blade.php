<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Berhasil</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background: #f5f5f5;
        }

        .box {
            background: #fff;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            text-align: center;
            margin-top: 40px;
        }

        h2 {
            margin-bottom: 10px;
            color: #333;
        }

        p {
            color: #555;
            margin: 8px 0;
        }

        .btn {
            display: block;
            text-align: center;
            background: #FF7A00;
            padding: 12px;
            color: #fff;
            border-radius: 8px;
            margin-top: 20px;
            text-decoration: none;
            font-weight: bold;
        }
    </style>
</head>

<body>

<div class="box">
    <h2>Pesanan Berhasil Dibuat 🎉</h2>

    <p>Nomor Pesanan: <b>#{{ $pesanan->id }}</b></p>
    <p>Status: <b>{{ ucfirst($pesanan->status) }}</b></p>

    <a href="{{ route('pembeli.home') }}" class="btn">
        Kembali ke Menu
    </a>
</div>

</body>
</html>
