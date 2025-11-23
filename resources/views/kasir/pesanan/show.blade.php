@extends('layouts.app')
@section('title','Detail Pesanan')
@section('content')
<h3>Pesanan {{ $pesanan->kode_pesanan }}</h3>
<p>Kasir: {{ $pesanan->kasir->nama ?? '-' }}</p>
<p>Total: {{ number_format($pesanan->total_harga) }}</p>
<p>Status: {{ $pesanan->status }}</p>

<table class="table">
<thead><tr><th>Menu</th><th>Jumlah</th><th>Harga</th><th>Subtotal</th></tr></thead>
<tbody>
@foreach($pesanan->detail as $d)
<tr>
<td>{{ $d->menu->nama ?? '-' }}</td>
<td>{{ $d->jumlah }}</td>
<td>{{ number_format($d->harga) }}</td>
<td>{{ number_format($d->subtotal) }}</td>
</tr>
@endforeach
</tbody>
</table>
@endsection
