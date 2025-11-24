@extends('layouts.app')

@section('content')
<div class="flex h-screen bg-gray-100">

    {{-- ===========================
        SIDEBAR ATAS – LOGOUT
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
        PRODUK (KIRI)
    ============================ --}}
    <div class="w-2/3 p-6 overflow-y-scroll">
        
        {{-- Search --}}
        <div class="mb-4">
            <input type="text" placeholder="Cari..." 
                   class="w-full border rounded-xl p-3 shadow-sm focus:ring focus:ring-blue-200">
        </div>

        {{-- Grid Produk --}}
        <div class="grid grid-cols-4 gap-5">
            @foreach ($products as $p)
            <div class="border rounded-xl bg-white p-4 shadow hover:shadow-lg transition">
                <img src="{{ asset('storage/' . $p->gambar) }}" 
                     class="w-full h-28 object-contain mb-3">

                <p class="font-semibold text-sm">{{ $p->nama }}</p>
                <p class="text-blue-600 font-bold mt-1">Rp {{ number_format($p->harga) }}</p>

                <button onclick="addToCart({{ $p->id }})"
                        class="w-full bg-blue-500 text-white mt-3 py-2 rounded-lg hover:bg-blue-600">
                    + Tambah
                </button>
            </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-5">
            {{ $products->links() }}
        </div>
    </div>


    {{-- ===========================
        KERANJANG (KANAN)
    ============================ --}}
    <div class="w-1/3 bg-white shadow-xl p-6 overflow-y-scroll">

        {{-- Judul --}}
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
            RINGKASAN TOTAL
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

        {{-- Tombol Bayar --}}
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
        fetch(`/kasir/add/${id}`)
            .then(res => location.reload());
    }

    function updateQty(id, type) {
        fetch(`/kasir/qty/${id}/${type}`)
            .then(res => location.reload());
    }
</script>

@endsection
