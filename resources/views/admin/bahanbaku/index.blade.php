@extends('layouts.admin')

@section('content')
<div class="container mt-4">

    <div class="d-flex justify-content-between mb-3">
        <h3>Data Bahan Baku</h3>
        <a href="{{ route('bahanbaku.create') }}" class="btn btn-primary">+ Tambah Bahan</a>
    </div>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Nama Bahan</th>
                <th>Satuan</th>
                <th>Stok</th>
                <th>Batas Kuning</th>
                <th>Batas Merah</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($bahan as $b)
            <tr>
                <td>{{ $b->nama_bahan }}</td>
                <td>{{ $b->satuan }}</td>
                <td>{{ $b->stok }}</td>
                <td>{{ $b->batas_kuning }}</td>
                <td>{{ $b->batas_merah }}</td>
                <td>
                    <a href="{{ route('bahanbaku.edit', $b->id) }}" class="btn btn-warning btn-sm">Edit</a>

                    <form action="{{ route('bahanbaku.destroy', $b->id) }}" method="POST" class="d-inline">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger btn-sm"
                            onclick="return confirm('Hapus bahan ini?')">Hapus</button>
                    </form>

                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

</div>
@endsection
