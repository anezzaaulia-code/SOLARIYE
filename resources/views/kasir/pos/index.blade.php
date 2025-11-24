@extends('layouts.app')

@section('content')
<div class="flex h-screen">

    {{-- SIDEBAR KIRI: LIST MENU --}}
    <div class="w-8/12 p-4 overflow-y-scroll bg-gray-100">

        {{-- SEARCH BAR --}}
        <input type="text" id="searchMenu" class="w-full p-2 mb-4 border rounded"
            placeholder="Cari menu...">

        {{-- GRID MENU --}}
        <div class="grid grid-cols-4 gap-4" id="menuContainer">

            @foreach ($menus as $menu)
            <div class="bg-white shadow rounded p-3 cursor-pointer menu-item"
                data-id="{{ $menu->id }}"
                data-name="{{ $menu->nama }}"
                data-price="{{ $menu->harga }}">

                <img src="{{ asset('storage/'.$menu->gambar) }}"
                     class="w-full h-32 object-cover rounded">
                <div class="mt-2 font-semibold">{{ $menu->nama }}</div>
                <div class="text-blue-600 font-bold">Rp {{ number_format($menu->harga) }}</div>
            </div>
            @endforeach

        </div>
    </div>

    {{-- RIGHT PANEL: CART --}}
    <div class="w-4/12 bg-white shadow-lg flex flex-col">

        {{-- HEADER --}}
        <div class="p-4 border-b flex justify-between items-center">
            <h2 class="text-xl font-semibold">Kasir</h2>

            {{-- LOGOUT --}}
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="px-3 py-1 bg-red-500 text-white rounded text-sm">
                    Logout
                </button>
            </form>
        </div>

        {{-- LIST PESANAN --}}
        <div class="flex-1 overflow-y-scroll p-4" id="cartList"></div>

        {{-- TOTAL --}}
        <div class="border-t p-4">
            <div class="flex justify-between text-lg font-semibold">
                <span>Total</span>
                <span id="totalHarga">Rp 0</span>
            </div>

            {{-- METODE PEMBAYARAN --}}
            <label class="block mt-3">Metode Pembayaran:</label>
            <select id="metodeBayar" class="w-full border p-2 rounded">
                <option value="tunai">Tunai</option>
                <option value="qris">QRIS</option>
            </select>

            {{-- BUTTON BAYAR --}}
            <button id="btnBayar"
                class="w-full mt-4 bg-green-600 text-white p-3 rounded font-semibold">
                Bayar
            </button>
        </div>
    </div>

</div>

{{-- SCRIPT --}}
<script>
let cart = [];

function renderCart() {
    let html = "";
    let total = 0;

    cart.forEach((item, index) => {
        total += item.harga * item.qty;

        html += `
            <div class="border-b pb-3 mb-3">
                <div class="font-semibold">${item.nama}</div>
                <div class="flex items-center justify-between mt-1">
                    <div class="flex items-center gap-2">
                        <button onclick="updateQty(${index}, -1)"
                            class="px-2 bg-gray-200 rounded">-</button>

                        <span>${item.qty}</span>

                        <button onclick="updateQty(${index}, 1)"
                            class="px-2 bg-gray-200 rounded">+</button>
                    </div>

                    <div>Rp ${new Intl.NumberFormat().format(item.harga * item.qty)}</div>
                </div>
            </div>
        `;
    });

    document.getElementById('cartList').innerHTML = html;
    document.getElementById('totalHarga').innerHTML = "Rp " + new Intl.NumberFormat().format(total);
}

function updateQty(i, val) {
    cart[i].qty += val;
    if (cart[i].qty <= 0) cart.splice(i, 1);
    renderCart();
}

document.querySelectorAll('.menu-item').forEach(item => {
    item.addEventListener('click', () => {
        let id = item.dataset.id;
        let name = item.dataset.name;
        let price = parseInt(item.dataset.price);

        let exist = cart.find(c => c.id == id);

        if (exist) {
            exist.qty++;
        } else {
            cart.push({
                id: id,
                nama: name,
                harga: price,
                qty: 1
            });
        }
        renderCart();
    });
});

document.getElementById("btnBayar").addEventListener("click", () => {
    if (cart.length == 0) return alert("Keranjang kosong!");

    fetch("{{ route('pos.store') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({
            items: cart,
            metode: document.getElementById("metodeBayar").value
        })
    })
    .then(res => res.json())
    .then(res => {
        alert("Transaksi berhasil!");
        cart = [];
        renderCart();
    });
});
</script>

@endsection
