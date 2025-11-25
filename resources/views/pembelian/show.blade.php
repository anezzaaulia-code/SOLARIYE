@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Detail Pembelian #{{ $pembelian->id }}</h1>
    <a href="{{ route('pembelian.index') }}" class="btn btn-secondary mb-3">Kembali</a>

    <table class="table table-bordered">
        <tr>
            <th>ID</th>
            <td>{{ $pembelian->id }}</td>
        </tr>
        <tr>
            <th>Supplier</th>
            <td>{{ $pembelian->supplier->nama_supplier ?? '-' }}</td>
        </tr>
        <tr>
            <th>Tanggal</th>
            <td>{{ $pembelian->tanggal }}</td>
        </tr>
        <tr>
            <th>Total Harga</th>
            <td>{{ number_format($pembelian->total_harga,0,',','.') }}</td>
        </tr>
        <tr>
            <th>Dibuat Oleh</th>
            <td>{{ $pembelian->user->name ?? '-' }}</td>
        </tr>
    </table>

    <h4>Detail Barang</h4>
    <table class="table table-bordered table-striped">
        <thead class="table-light">
            <tr>
                <th>#</th>
                <th>Bahan</th>
                <th>Jumlah</th>
                <th>Harga Satuan</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pembelian->detail as $idx => $det)
            <tr>
                <td>{{ $idx + 1 }}</td>
                <td>{{ $det->bahan->nama_bahan ?? '-' }}</td>
                <td>{{ $det->jumlah }}</td>
                <td>{{ number_format($det->harga_satuan,0,',','.') }}</td>
                <td>{{ number_format($det->subtotal,0,',','.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
