@extends('layouts.admin')

@section('content')
<div class="container mt-4">

    <h3>Tambah Bahan Baku</h3>

    <form action="{{ route('bahanbaku.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label>Nama Bahan</label>
            <input type="text" name="nama_bahan" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Satuan</label>
            <input type="text" name="satuan" class="form-control" placeholder="kg, pcs, liter..." required>
        </div>

        <div class="mb-3">
            <label>Batas Kuning</label>
            <input type="number" name="batas_kuning" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Batas Merah</label>
            <input type="number" name="batas_merah" class="form-control" required>
        </div>

        <button class="btn btn-success">Simpan</button>
        <a href="{{ route('bahanbaku.index') }}" class="btn btn-secondary">Kembali</a>
    </form>

</div>
@endsection
