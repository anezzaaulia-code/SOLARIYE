@extends('layouts.admin')

@section('content')
<div class="container mt-4">

    <h3>Edit Bahan Baku</h3>

    <form action="{{ route('bahanbaku.update', $bahan->id) }}" method="POST">
        @csrf @method('PUT')

        <div class="mb-3">
            <label>Nama Bahan</label>
            <input type="text" name="nama_bahan" class="form-control" value="{{ $bahan->nama_bahan }}" required>
        </div>

        <div class="mb-3">
            <label>Satuan</label>
            <input type="text" name="satuan" class="form-control" value="{{ $bahan->satuan }}" required>
        </div>

        <div class="mb-3">
            <label>Batas Kuning</label>
            <input type="number" name="batas_kuning" class="form-control" value="{{ $bahan->batas_kuning }}" required>
        </div>

        <div class="mb-3">
            <label>Batas Merah</label>
            <input type="number" name="batas_merah" class="form-control" value="{{ $bahan->batas_merah }}" required>
        </div>

        <button class="btn btn-success">Update</button>
        <a href="{{ route('bahanbaku.index') }}" class="btn btn-secondary">Kembali</a>
    </form>

</div>
@endsection
