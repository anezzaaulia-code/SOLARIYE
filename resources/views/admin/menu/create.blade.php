@extends('layouts.admin')

@section('content')
<div class="container">
    <h3 class="mb-4">Tambah Menu</h3>

    <form action="{{ route('menu.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Nama Menu</label>
                <input type="text" name="nama" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Harga</label>
                <input type="number" name="harga" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Kategori</label>
                <select name="kategori_id" class="form-control" required>
                    @foreach($kategories as $k)
                        <option value="{{ $k->id }}">{{ $k->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Foto Menu</label>
                <input type="file" name="foto" class="form-control" accept="image/*">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-control">
                    <option value="tersedia">Tersedia</option>
                    <option value="habis">Habis</option>
                    <option value="nonaktif">Nonaktif</option>
                </select>
            </div>
        </div>
        <button class="btn btn-primary">Simpan</button>
        <a href="{{ route('menu.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
