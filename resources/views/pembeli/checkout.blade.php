<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 15px;
            background: #F9F9F9;
        }

        .title {
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .summary-box {
            background: #fff;
            padding: 15px;
            border-radius: 15px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .item-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 16px;
        }

        .total-row {
            font-weight: bold;
            font-size: 18px;
            margin-top: 15px;
            border-top: 1px solid #eee;
            padding-top: 10px;
        }

        .payment-title {
            font-size: 18px;
            margin-bottom: 10px;
            font-weight: bold;
        }

        .payment-option {
            background: #fff;
            padding: 12px;
            border-radius: 10px;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            border: 2px solid transparent;
        }

        .payment-option input {
            transform: scale(1.3);
        }

        .payment-option.active {
            border-color: #FF7A00;
            background: #FFF4E9;
        }

        .checkout-btn {
            width: 100%;
            text-align: center;
            background: #FF7A00;
            color: white;
            padding: 12px;
            border-radius: 10px;
            display: block;
            text-decoration: none;
            font-size: 18px;
            font-weight: bold;
            margin-top: 20px;
        }
    </style>

</head>

<body>

    <div class="title">Checkout</div>

    <!-- Ringkasan Pesanan -->
    <div class="summary-box">
        <div class="item-row">
            <span>Total Item</span>
            <span>{{ $total_items }} item</span>
        </div>

        <div class="item-row">
            <span>Subtotal</span>
            <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
        </div>

        <div class="total-row">
            <span>Total Bayar</span>
            <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
        </div>
    </div>


    <!-- Opsi Pembayaran -->
    <div class="payment-title">Metode Pembayaran</div>

    <form action="{{ route('pesanan.storeCheckout') }}" method="POST">
        @csrf

        <label class="payment-option">
            <input type="radio" name="pembayaran" value="tunai" required>
            Tunai (Cash)
        </label>

        <label class="payment-option">
            <input type="radio" name="pembayaran" value="qris" required>
            QRIS
        </label>

        <button type="submit" class="checkout-btn">Buat Pesanan</button>
    </form>


    <script>
        // highlight kotak saat dipilih
        const options = document.querySelectorAll(".payment-option");

        options.forEach(opt => {
            opt.addEventListener("click", () => {
                options.forEach(o => o.classList.remove("active"));
                opt.classList.add("active");
            });
        });
    </script>

</body>

</html>
