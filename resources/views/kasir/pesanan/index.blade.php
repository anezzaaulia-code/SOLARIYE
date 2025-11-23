@extends('layouts.app')
@section('title','Pesanan')
@section('content')
<div class="d-flex justify-content-between mb-3">
    <h3>Pesanan</h3>
    <a href="{{ route('pesanan.create') }}" class="btn btn-primary">Buat Pesanan</a>
</div>
<table class="table">
<thead><tr><th>#</th><th>Kode</th><th>Kasir</th><th>Total</th><th>Status</th><th>Aksi</th></tr></thead>
<tbody>
@forelse($pesanan as $p)
<tr>
<td>{{ $p->id }}</td>
<td>{{ $p->kode_pesanan }}</td>
<td>{{ $p->kasir->nama ?? '-' }}</td>
<td>{{ number_format($p->total_harga) }}</td>
<td>{{ $p->status }}</td>
<td>
    <a href="{{ route('pesanan.show',$p->id) }}" class="btn btn-sm btn-info">Detail</a>
    <form action="{{ route('pesanan.destroy',$p->id) }}" method="POST" style="display:inline">@csrf @method('DELETE')
    <button class="btn btn-sm btn-danger" onclick="return confirm('Hapus?')">Hapus</button></form>
</td>
</tr>
@empty
<tr><td colspan="6">Belum ada pesanan</td></tr>
@endforelse
</tbody>
</table>
@endsection
