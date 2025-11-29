@extends('layouts.kasir')

@section('content')
<div class="p-5">

    <h1 class="text-2xl font-bold mb-4">Riwayat Transaksi</h1>

    <table class="w-full border">
        <thead class="bg-gray-200">
            <tr>
                <th class="p-2 border">Tanggal</th>
                <th class="p-2 border">Pelanggan</th>
                <th class="p-2 border">Total</th>
                <th class="p-2 border">Metode</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($riwayat as $t)
            <tr>
                <td class="p-2 border">{{ $t->created_at }}</td>
                <td class="p-2 border">{{ $t->pelanggan }}</td>
                <td class="p-2 border">Rp {{ number_format($t->total) }}</td>
                <td class="p-2 border">{{ strtoupper($t->metode) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</div>
@endsection
