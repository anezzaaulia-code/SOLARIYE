@extends('layouts.app')

@section('content')
<div class="flex h-screen bg-gray-100">

    {{-- ===========================
        LOGOUT RIGHT TOP
    ============================ --}}
    <div class="absolute top-3 right-5 flex items-center gap-3">
        <span class="font-semibold text-gray-700">
            {{ Auth::user()->nama }} ({{ Auth::user()->role }})
        </span>

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button class="bg-red-500 text-white px-3 py-2 rounded-lg shadow hover:bg-red-600">
                Logout
            </button>
        </form>
    </div>


    {{-- ===========================
        SIDEBAR PRODUK (KIRI)
    ============================ --}}
    <div class="w-3/12 bg-white p-5 overflow-y-scroll border-r">

        {{-- Search --}}
        <input type="text" placeholder="Cari menu..." id="searchMenu"
               class="w-full border rounded-xl p-3 shadow-sm mb-4 focus:ring focus:ring-blue-200">

        <h2 class="font-bold text-lg mb-3">Daftar Produk</h2>

        {{-- LIST PRODUK VERTIKAL --}}
        <div id="productList">
            @foreach ($products as $p)
            <div onclick="addToCart({{ $p->id }})"
                 class="flex items-center gap-3 mb-3 p-2 rounded-xl border cursor-pointer hover:bg-blue-50 transition">

                <img src="{{ asset('storage/' . $p->gambar) }}"
                     class="w-14 h-14 object-cover rounded">

                <div>
                    <p class="font-semibold text-sm">{{ $p->nama }}</p>
                    <p class="text-blue-600 font-bold text-xs">Rp {{ number_format($p->harga) }}</p>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-4">
            {{ $products->links() }}
        </div>
    </div>


    {{-- ===========================
        TENGAH — INFORMASI
    ============================ --}}
    <div class="w-5/12 flex items-center justify-center text-gray-400">
        <div class="text-center">
            <img src="/img/pos.png" class="w-48 mx-auto opacity-50">
            <p class="text-lg mt-4 font-semibold">Pilih menu di sebelah kiri untuk menambahkan ke keranjang.</p>
        </div>
    </div>


    {{-- ===========================
        KERANJANG KANAN
    ============================ --}}
    <div class="w-4/12 bg-white shadow-xl p-6 overflow-y-scroll">

        <h2 class="font-bold text-xl mb-5 flex items-center gap-2">
            <i class="bi bi-person"></i> Umum
        </h2>

        {{-- Cart Items --}}
        <div id="cart-items">
            @foreach ($cart as $item)
            <div class="border-b pb-3 mb-4">

                <p class="font-semibold">{{ $item->product->nama }}</p>
                <p class="text-gray-600">
                    Rp {{ number_format($item->product->harga) }}
                    <span class="text-sm text-gray-500">Stok: {{ $item->product->stok }}</span>
                </p>

                <div class="flex items-center gap-2 mt-2">
                    <button onclick="updateQty({{ $item->id }}, 'minus')"
                            class="px-3 py-1 bg-gray-200 rounded-lg">-</button>

                    <p class="w-8 text-center font-semibold">{{ $item->qty }}</p>

                    <button onclick="updateQty({{ $item->id }}, 'plus')"
                            class="px-3 py-1 bg-purple-500 text-white rounded-lg">+</button>

                    <div class="ml-auto font-bold text-purple-600">
                        Rp {{ number_format($item->total) }}
                    </div>
                </div>
            </div>
            @endforeach

            @if(count($cart) == 0)
                <p class="text-gray-500">Belum ada item.</p>
            @endif
        </div>


        {{-- ===========================
            RINGKASAN
        ============================ --}}
        <div class="mt-5 border-t pt-4">
            <div class="flex justify-between mb-1">
                <span>Sub Total</span>
                <span>Rp {{ number_format($subtotal) }}</span>
            </div>
            <div class="flex justify-between mb-1">
                <span>Diskon (5%)</span>
                <span>Rp {{ number_format($diskon) }}</span>
            </div>
            <div class="flex justify-between mb-1">
                <span>Pajak (11%)</span>
                <span>Rp {{ number_format($pajak) }}</span>
            </div>

            <div class="flex justify-between text-xl font-bold mt-4 text-purple-600">
                <span>Total</span>
                <span>Rp {{ number_format($total) }}</span>
            </div>
        </div>

        <button class="w-full bg-green-500 text-white text-lg font-bold mt-6 py-4 rounded-xl hover:bg-green-600">
            Bayar Rp {{ number_format($total) }}
        </button>
    </div>

</div>


{{-- ===========================
    SCRIPT AJAX
=========================== --}}
<script>
    function addToCart(id) {
        fetch(`/kasir/add/${id}`).then(() => location.reload());
    }

    function updateQty(id, type) {
        fetch(`/kasir/qty/${id}/${type}`).then(() => location.reload());
    }
</script>
@endsection
