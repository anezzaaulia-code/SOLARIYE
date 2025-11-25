@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Pembelian Bahan</h1>
    <a href="{{ route('pembelian.create') }}" class="btn btn-primary mb-3">Tambah Pembelian</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Supplier</th>
                <th>Tanggal</th>
                <th>Total Harga</th>
                <th>Dibuat Oleh</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pembelian as $item)
            <tr>
                <td>{{ $item->id }}</td>
                <td>{{ $item->supplier->nama_supplier ?? '-' }}</td>
                <td>{{ $item->tanggal }}</td>
                <td>{{ $item->total_harga }}</td>
                <td>{{ $item->user->name ?? '-' }}</td>
                <td>
                    <a href="{{ route('pembelian.show', $item->id) }}" class="btn btn-info btn-sm">Detail</a>
                    <a href="{{ route('pembelian.edit', $item->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('pembelian.destroy', $item->id) }}" method="POST" style="display:inline-block">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus?')">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
