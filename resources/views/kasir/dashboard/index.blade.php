@extends('layouts.kasir')

@section('content')
<div class="flex h-screen w-full overflow-hidden"> {{-- Container utama --}}

    {{-- 🔹 MENU KIRI --}}
    <div class="w-8/12 p-4 overflow-y-auto bg-gray-100">

        <input type="text" id="searchMenu" class="w-full p-2 mb-4 border rounded"
            placeholder="Cari menu...">

        <div class="grid grid-cols-4 gap-4" id="menuContainer">
            @foreach ($menus as $menu)
            <div class="bg-white shadow rounded p-3 cursor-pointer menu-item"
                data-id="{{ $menu->id }}"
                data-name="{{ $menu->nama }}"
                data-price="{{ $menu->harga }}">

                <img src="{{ asset('storage/'.$menu->gambar) }}"
                    class="w-full h-32 object-cover rounded">

                <div class="mt-2 font-semibold">{{ $menu->nama }}</div>
                <div class="text-blue-600 font-bold">
                    Rp {{ number_format($menu->harga) }}
                </div>
            </div>
            @endforeach
        </div>
    </div>


    {{-- 🔹 PANEL KERANJANG --}}
    <div class="w-4/12 bg-white shadow-lg flex flex-col">

        <div class="p-4 border-b flex justify-between items-center">
            <h2 class="text-xl font-semibold">Kasir</h2>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="px-3 py-1 bg-red-500 text-white rounded text-sm">
                    Logout
                </button>
            </form>
        </div>

        <div class="flex-1 overflow-y-auto p-4" id="cartList"></div>

        <div class="border-t p-4">
            <div class="flex justify-between text-lg font-semibold">
                <span>Total</span>
                <span id="totalHarga">Rp 0</span>
            </div>

            <button id="btnBayar"
                class="w-full mt-4 bg-green-600 text-white p-3 rounded font-semibold">
                Proses Pembayaran
            </button>
        </div>
    </div>

</div> {{-- <-- FIX penting: penutup container utama --}}
@endsection


{{-- 🔽 MODAL PEMBAYARAN --}}
<div id="paymentModal"
     class="fixed inset-0 bg-black/40 flex items-center justify-center hidden">

    <div class="bg-white w-96 p-5 rounded shadow-lg">

        <h2 class="text-xl font-bold mb-3">Pembayaran</h2>

        <label class="block text-sm">Nama Pelanggan</label>
        <input type="text" id="namaPelanggan" class="w-full p-2 border rounded mb-3"
               placeholder="Opsional">

        <label class="block text-sm">Nama Kasir</label>
        <input type="text" class="w-full p-2 border rounded mb-3"
               value="{{ auth()->user()->name }}" readonly>

        <label class="block text-sm">Total</label>
        <input type="text" id="totalTagihan" class="w-full p-2 border rounded mb-3 bg-gray-100" readonly>

        <label class="block text-sm">Pembayaran</label>
        <select id="metodePembayaran" class="w-full p-2 border rounded mb-3">
            <option value="tunai">Tunai</option>
            <option value="qris">QRIS</option>
        </select>

        <label class="block text-sm">Jumlah Bayar</label>
        <input type="number" id="jumlahBayar"
               class="w-full p-2 border rounded mb-3">

        <label class="block text-sm">Kembalian</label>
        <input type="text" id="kembalian"
               class="w-full p-2 border rounded bg-gray-100 mb-4" readonly>

        <div class="flex justify-between">
            <button class="px-4 py-2 bg-gray-400 text-white rounded"
                    onclick="toggleModal(false)">
                Batal
            </button>

            <button id="btnSimpanPembayaran"
                    class="px-4 py-2 bg-green-600 text-white rounded">
                Simpan & Cetak
            </button>
        </div>

    </div>

</div>


{{-- 🔽 SCRIPT --}}
<script>
let cart = [];

/* Render Cart */
function renderCart() {
    let html = "";
    let total = 0;

    cart.forEach((item, index) => {
        total += item.harga * item.qty;

        html += `
            <div class="border-b pb-3 mb-3">
                <div class="font-semibold">${item.nama}</div>
                <div class="flex justify-between items-center mt-1">
                    <div class="flex items-center gap-1">
                        <button onclick="updateQty(${index}, -1)" class="px-2 bg-gray-200 rounded">-</button>
                        <span>${item.qty}</span>
                        <button onclick="updateQty(${index}, 1)" class="px-2 bg-gray-200 rounded">+</button>
                    </div>
                    <div>Rp ${new Intl.NumberFormat().format(item.qty * item.harga)}</div>
                </div>
            </div>
        `;
    });

    document.getElementById("cartList").innerHTML = html;
    document.getElementById("totalHarga").innerText = "Rp " + new Intl.NumberFormat().format(total);
}

/* Update qty */
function updateQty(i, val) {
    cart[i].qty += val;
    if (cart[i].qty <= 0) cart.splice(i, 1);
    renderCart();
}

/* Add menu to cart */
document.querySelectorAll('.menu-item').forEach(item => {
    item.addEventListener('click', () => {
        let data = {
            id: item.dataset.id,
            nama: item.dataset.name,
            harga: parseInt(item.dataset.price),
            qty: 1
        };
        let exist = cart.find(c => c.id == data.id);
        exist ? exist.qty++ : cart.push(data);
        renderCart();
    });
});

/* Modal pembayaran */
function toggleModal(show) {
    document.getElementById("paymentModal").classList.toggle("hidden", !show);
    if (show) {
        let total = cart.reduce((a, b) => a + b.harga * b.qty, 0);
        document.getElementById("totalTagihan").value =
            new Intl.NumberFormat().format(total);
    }
}

document.getElementById("btnBayar").addEventListener("click", () => {
    if (cart.length === 0) return alert("Keranjang masih kosong!");
    toggleModal(true);
});

/* Hitung kembalian */
document.getElementById("jumlahBayar").addEventListener("input", () => {
    let total = cart.reduce((a, b) => a + b.harga * b.qty, 0);
    let bayar = parseInt(document.getElementById("jumlahBayar").value || 0);

    document.getElementById("kembalian").value =
        bayar >= total ? "Rp " + new Intl.NumberFormat().format(bayar - total) : "Belum cukup";
});

/* Simpan transaksi */
document.getElementById("btnSimpanPembayaran").addEventListener("click", () => {
    fetch("{{ route('kasir.pos.store') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({
            pelanggan: document.getElementById("namaPelanggan").value,
            items: cart,
            metode: document.getElementById("metodePembayaran").value,
            bayar: document.getElementById("jumlahBayar").value
        })
    })
    .then(res => res.json())
    .then(res => {
        alert("Transaksi berhasil disimpan!");
        cart = [];
        renderCart();
        toggleModal(false);
        location.reload(); // refresh agar keranjang & UI reset
    });
});
</script>
