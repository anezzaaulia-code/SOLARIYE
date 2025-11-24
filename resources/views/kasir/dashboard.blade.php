@extends('layouts.app')

@section('content')
<div class="flex h-screen bg-gray-100">

    {{-- BAGIAN PRODUK --}}
    <div class="w-2/3 p-5 overflow-y-scroll">
        <div class="mb-4">
            <input type="text" placeholder="Cari..." class="w-full border rounded-lg p-3">
        </div>

        <div class="grid grid-cols-4 gap-4">
            @foreach ($products as $p)
            <div class="border rounded-lg bg-white p-3 shadow">
                <img src="{{ asset('storage/'.$p->gambar) }}" class="w-full h-32 object-contain mb-2">

                <p class="font-semibold text-sm">{{ $p->nama }}</p>
                <p class="text-blue-600 font-bold text-sm">Rp {{ number_format($p->harga) }}</p>

                <button
                    onclick="addToCart({{ $p->id }})"
                    class="w-full bg-blue-500 text-white mt-2 py-1 rounded">
                    + Tambah
                </button>
            </div>
            @endforeach
        </div>

        <div class="mt-5">
            {{ $products->links() }}
        </div>

    </div>


    {{-- BAGIAN KERANJANG --}}
    <div class="w-1/3 bg-white shadow-xl p-5 overflow-y-scroll">
        <h2 class="font-bold text-xl mb-4">🛒 Umum</h2>

        <div id="cart-items">
            @forelse ($cart as $item)
            <div class="border-b pb-3 mb-3">
                <p class="font-semibold">{{ $item->product->nama }}</p>
                <p class="text-gray-600">
                    Rp {{ number_format($item->product->harga) }}
                    <span class="text-sm text-gray-500">Stok: {{ $item->product->stok }}</span>
                </p>

                <div class="flex items-center gap-2 mt-2">
                    <button onclick="updateQty({{ $item->id }}, 'minus')"
                        class="px-2 bg-gray-200 rounded">-</button>

                    <p class="w-8 text-center">{{ $item->qty }}</p>

                    <button onclick="updateQty({{ $item->id }}, 'plus')"
                        class="px-2 bg-purple-400 text-white rounded">+</button>

                    <div class="ml-auto font-bold">
                        Rp {{ number_format($item->total) }}
                    </div>
                </div>
            </div>
            @empty
            <p class="text-gray-500">Belum ada item.</p>
            @endforelse
        </div>

        {{-- RINGKASAN --}}
        <div class="mt-5 border-t pt-3">
            <div class="flex justify-between">
                <span>Sub Total</span>
                <span>Rp {{ number_format($subtotal) }}</span>
            </div>
            <div class="flex justify-between">
                <span>Diskon (5%)</span>
                <span>Rp {{ number_format($diskon) }}</span>
            </div>
            <div class="flex justify-between">
                <span>Pajak (11%)</span>
                <span>Rp {{ number_format($pajak) }}</span>
            </div>

            <div class="flex justify-between text-lg font-bold mt-3 text-purple-600">
                <span>Total</span>
                <span>Rp {{ number_format($total) }}</span>
            </div>
        </div>

        <button class="w-full bg-green-500 text-white text-lg font-bold mt-5 py-3 rounded">
            Bayar Rp {{ number_format($total) }}
        </button>
    </div>

</div>

{{-- SCRIPT --}}
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
