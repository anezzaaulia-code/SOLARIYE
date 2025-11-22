@extends('layouts.pembeli')

@section('content')
<div class="container mx-auto px-4 py-4">

    {{-- Kategori --}}
    <div class="flex gap-3 overflow-x-auto pb-3">
        <a href="{{ route('pembeli.menu') }}" 
           class="px-4 py-2 rounded-full bg-green-600 text-white text-sm whitespace-nowrap">
           Semua
        </a>

        @foreach($kategori as $kat)
            <a href="{{ route('pembeli.menu', ['kategori' => $kat]) }}" 
               class="px-4 py-2 rounded-full border border-gray-400 text-gray-700 text-sm whitespace-nowrap">
               {{ $kat }}
            </a>
        @endforeach
    </div>

    {{-- Grid Menu --}}
    <div class="grid grid-cols-2 gap-4 mt-3">

        @foreach($menu as $m)
        <div class="bg-white shadow-md rounded-lg p-3">

            {{-- Foto menu --}}
            <img src="{{ asset('img/menu.png') }}" 
                 class="w-full h-28 object-cover rounded-md mb-2">

            {{-- Nama --}}
            <h3 class="font-semibold text-gray-900 text-sm">
                {{ $m->nama_menu }}
            </h3>

            {{-- Harga --}}
            <p class="text-green-700 font-bold text-sm">
                Rp {{ number_format($m->harga, 0, ',', '.') }}
            </p>

            {{-- Tombol tambah --}}
            <form action="{{ route('pembeli.tambahKeranjang') }}" method="POST">
                @csrf
                <input type="hidden" name="menu_id" value="{{ $m->menu_id }}">

                <button class="w-full bg-green-600 text-white py-1 mt-2 rounded">
                    Tambah
                </button>
            </form>

        </div>
        @endforeach

    </div>

</div>
@endsection
