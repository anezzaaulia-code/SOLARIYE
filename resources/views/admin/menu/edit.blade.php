@extends('layouts.admin')

@section('content')
<div class="container">
    <h3 class="mb-4">Edit Menu #{{ $menu->id }}</h3>

    {{-- Flash Message --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('menu.update', $menu->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row">

            {{-- Nama Menu --}}
            <div class="col-md-6 mb-3">
                <label class="form-label">Nama Menu</label>
                <input type="text" name="nama" class="form-control" value="{{ old('nama', $menu->nama) }}" required>
                @error('nama')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            {{-- Harga --}}
            <div class="col-md-6 mb-3">
                <label class="form-label">Harga</label>
                <input type="number" name="harga" class="form-control" value="{{ old('harga', $menu->harga) }}" required>
                @error('harga')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            {{-- Kategori --}}
            <div class="col-md-6 mb-3">
                <label class="form-label">Kategori</label>
                <select name="kategori_id" class="form-control" required>
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($kategories as $kat)
                        <option value="{{ $kat->id }}"
                            {{ (isset($menu) && $menu->kategori_id == $kat->id) || old('kategori_id') == $kat->id ? 'selected' : '' }}>
                            {{ $kat->nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Foto Menu --}}
            <div class="col-md-6 mb-3">
                <label class="form-label">Foto Menu</label>
                    @if($menu->foto)
                        <img src="{{ asset('storage/'.$menu->foto) }}" alt="Foto" width="80">
                    @endif
                <input type="file" name="foto" class="form-control" accept="image/*">
                @error('foto')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            {{-- Status --}}
            <div class="col-md-6 mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-control">
                    <option value="tersedia" {{ old('status', $menu->status) == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                    <option value="habis" {{ old('status', $menu->status) == 'habis' ? 'selected' : '' }}>Habis</option>
                    <option value="nonaktif" {{ old('status', $menu->status) == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                </select>
                @error('status')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

        </div>

        <button class="btn btn-primary">Update</button>
        <a href="{{ route('menu.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
