@extends('layouts.admin')

@section('content')
<div class="container mt-4">

    <div class="d-flex justify-content-between mb-3">
        <h4>Pembelian Bahan</h4>
        <a href="{{ route('pembelian.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Tambah Pembelian
        </a>
    </div>

    <div class="card shadow">
        <div class="card-body">

            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Tanggal</th>
                        <th>Supplier</th>
                        <th>Total Harga</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($pembelian as $p)
                        <tr>
                            <td>{{ $p->tanggal }}</td>
                            <td>{{ $p->supplier->nama_supplier ?? '-' }}</td>
                            <td>Rp {{ number_format($p->total_harga) }}</td>
                            <td>
                                <a href="{{ route('pembelian.show', $p->id) }}" class="btn btn-info btn-sm">Detail</a>
                                <a href="{{ route('pembelian.edit', $p->id) }}" class="btn btn-warning btn-sm">Edit</a>

                                <form action="{{ route('pembelian.destroy', $p->id) }}"
                                      method="POST"
                                      class="d-inline">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-danger btn-sm"
                                        onclick="return confirm('Hapus pembelian ini?')">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>

            </table>

        </div>
    </div>
</div>
@endsection
