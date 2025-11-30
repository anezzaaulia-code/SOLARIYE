@extends('layouts.kasir')

@section('content')
<div class="p-6">

    <h1 class="text-2xl font-bold mb-5">Riwayat Transaksi</h1>

    {{-- Kartu Info --}}
    <div class="grid grid-cols-3 gap-4 mb-6">
        <div class="bg-white shadow rounded p-4">
            <p class="text-gray-600">Total Pendapatan</p>
            <h2 class="text-xl font-bold text-green-600">
                Rp {{ number_format($riwayat->sum('total_harga')) }}
            </h2>
        </div>

        <div class="bg-white shadow rounded p-4">
            <p class="text-gray-600">Jumlah Transaksi</p>
            <h2 class="text-xl font-bold">{{ $riwayat->count() }}</h2>
        </div>

        <div class="bg-white shadow rounded p-4 flex justify-center items-center">
            <a href="#" class="bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded shadow">
                Export PDF
            </a>
        </div>
    </div>

    {{-- Tabel Riwayat --}}
    <div class="bg-white shadow rounded overflow-auto">
        <table class="min-w-full border">
            <thead class="bg-gray-200 font-semibold">
                <tr>
                    <th class="border p-2 text-center">No</th>
                    <th class="border p-2">Tanggal</th>
                    <th class="border p-2">Nama Pelanggan</th>
                    <th class="border p-2">Nama Kasir</th>
                    <th class="border p-2">Item</th>
                    <th class="border p-2 text-center">Total Pesanan</th>
                    <th class="border p-2 text-center">Metode Bayar</th>
                    <th class="border p-2 text-center">Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($riwayat as $t)
                <tr>
                    <td class="border p-2 text-center">{{ $loop->iteration }}</td>
                    <td class="border p-2">{{ $t->created_at->format('d M Y H:i') }}</td>
                    <td class="border p-2">{{ $t->pelanggan ?? '-' }}</td>
                    <td class="border p-2">{{ $t->kasir->name ?? '-' }}</td>

                    {{-- daftar item --}}
                    <td class="border p-2">
                        @foreach ($t->detail as $item)
                            {{ $item->menu->nama }} (x{{ $item->jumlah }}) – Rp {{ number_format($item->harga) }} <br>
                        @endforeach
                    </td>

                    {{-- total pesanan --}}
                    <td class="border p-2 text-center">
                        {{ $t->detail->sum('jumlah') }}
                    </td>

                    {{-- metode --}}
                    <td class="border p-2 text-center">
                        {{ strtoupper($t->metode_bayar) }}
                    </td>

                    {{-- total harga --}}
                    <td class="border p-2 text-right font-bold">
                        Rp {{ number_format($t->total_harga) }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center p-4 text-gray-500">
                        Belum ada transaksi
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection
