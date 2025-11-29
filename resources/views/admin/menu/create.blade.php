@extends('layouts.admin')

@section('content')
<div class="container">

    <h3 class="mb-4">Tambah Menu</h3>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('menu.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row mb-3">
            <div class="col-md-6">
                <label>Nama Menu</label>
                <input type="text" name="nama" class="form-control" required>
            </div>

            <div class="col-md-6">
                <label>Harga</label>
                <input type="number" name="harga" class="form-control" required>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label>Kategori</label>
                <select name="kategori_id" class="form-control" required>
                    @foreach($kategories as $kategori)
                        <option value="{{ $kategori->id }}">{{ $kategori->nama }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label>Foto Menu</label>
                <input type="file" name="foto" class="form-control" accept="image/*">
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-6">
                <label>Status</label>
                <select name="status" class="form-control">
                    <option value="tersedia">Tersedia</option>
                    <option value="habis">Habis</option>
                    <option value="nonaktif">Nonaktif</option>
                </select>
            </div>

            <div class="col-md-6">
                <label>Deskripsi</label>
                <textarea name="deskripsi" class="form-control" rows="3"></textarea>
            </div>
        </div>

        <button type="submit" class="btn btn-primary mt-2">Simpan</button>
        <a href="{{ route('menu.index') }}" class="btn btn-secondary mt-2">Kembali</a>
    </form>

</div>
@endsection
