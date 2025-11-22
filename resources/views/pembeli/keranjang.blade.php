<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang</title>

    <style>
        body { font-family: Arial; padding: 15px; }

        .item {
            background: white;
            padding: 12px;
            border-radius: 10px;
            margin-bottom: 12px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.08);
        }

        .row {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .hapus {
            color: red;
            font-size: 13px;
        }

        .checkout-btn {
            position: fixed;
            bottom: 15px;
            left: 15px;
            right: 15px;
            background: #FF7A00;
            color: white;
            padding: 14px;
            border-radius: 10px;
            text-align: center;
            font-size: 18px;
            text-decoration: none;
        }
    </style>
</head>

<body>

<h2>Keranjang</h2>

@foreach($items as $i)
<div class="item">
    <div class="row">
        <div>
            <b>{{ $i->menu->nama }}</b> (x{{ $i->jumlah }})
            <br>
            <small>{{ $i->catatan }}</small>
        </div>

        <form action="{{ route('keranjang.hapus', $i->id) }}" method="POST">
            @csrf
            @method('DELETE')
            <button class="hapus">Hapus</button>
        </form>
    </div>
</div>
@endforeach

<a href="{{ route('pesanan.checkout') }}" class="checkout-btn">Checkout</a>

</body>
</html>
