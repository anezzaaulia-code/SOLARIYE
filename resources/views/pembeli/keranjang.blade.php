<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang</title>

    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background: #f5f5f5; }
        .container { padding: 15px; }
        .title { font-size: 22px; font-weight: bold; margin-bottom: 15px; }

        .cart-card {
            background: #fff;
            border-radius: 15px;
            padding: 12px;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 15px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        img {
            width: 70px;
            height: 70px;
            border-radius: 12px;
            object-fit: cover;
        }

        .cart-info { flex: 1; }
        .cart-name { font-size: 17px; font-weight: bold; }
        .cart-note { font-size: 13px; color: #666; margin-top: 3px; }

        .price { color: #FF7A00; font-weight: bold; margin-top: 5px; }

        .qty-box {
            display: flex;
            flex-direction: column;
            text-align: right;
        }

        .total-box {
            margin-top: 20px;
            background: #fff;
            padding: 15px;
            border-radius: 15px;
            font-size: 16px;
            font-weight: bold;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        .checkout-btn {
            margin-top: 15px;
            display: block;
            text-align: center;
            background: #FF7A00;
            color: white;
            padding: 12px;
            border-radius: 10px;
            text-decoration: none;
            font-size: 17px;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="container">

    <div class="title">Keranjang</div>

    @forelse($items as $item)
    <div class="cart-card">
        <img src="{{ asset('storage/' . $item->menu->foto) }}">

        <div class="cart-info">
            <div class="cart-name">{{ $item->menu->nama }}</div>
            <div class="cart-note">Catatan: {{ $item->catatan ?? '-' }}</div>
            <div class="price">Rp {{ number_format($item->menu->harga, 0, ',', '.') }}</div>
        </div>

        <div class="qty-box">
            <div>{{ $item->qty }}x</div>
        </div>
    </div>
    @empty
    <p style="text-align:center; margin-top:20px;">Keranjang masih kosong.</p>
    @endforelse


    @if(count($items) > 0)
    <div class="total-box">
        Total: Rp {{ number_format($total, 0, ',', '.') }}
    </div>

    <a href="{{ route('pesanan.checkout') }}" class="checkout-btn">
        Lanjut Pembayaran
    </a>
    @endif

</div>
</body>
</html>
