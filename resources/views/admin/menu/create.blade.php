@extends('layouts.admin')

@section('content')
<div class="container">
    <h3 class="mb-4">Tambah Menu</h3>

    <form action="{{ route('menu.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row">

            {{-- Nama menu --}}
            <div class="col-md-6 mb-3">
                <label class="form-label">Nama Menu</label>
                <input type="text" name="nama" class="form-control" required>
            </div>

            {{-- Harga --}}
            <div class="col-md-6 mb-3">
                <label class="form-label">Harga</label>
                <input type="number" name="harga" class="form-control" required>
            </div>

            {{-- Kategori --}}
            <div class="col-md-6 mb-3">
                <label class="form-label">Kategori</label>
                <select name="kategori_id" class="form-control" required>
                    <option value="1">Makanan</option>
                    <option value="2">Minuman</option>
                </select>
            </div>

            {{-- Foto --}}
            <div class="col-md-6 mb-3">
                <label class="form-label">Foto Menu</label>
                <input type="file" name="foto" class="form-control" accept="image/*">
            </div>

            {{-- Status --}}
            <div class="col-md-6 mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-control">
                    <option value="aktif">Tersedia</option>
                    <option value="nonaktif">Habis</option>
                </select>
            </div>

        </div>

        <button class="btn btn-primary">Simpan</button>
        <a href="{{ route('menu.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
