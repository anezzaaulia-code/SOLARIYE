@extends('layouts.admin')

@section('content')
<div class="container mt-4">

    <div class="card shadow-sm" style="max-width: 600px; margin: 0 auto;">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Tambah Kategori Baru</h5>
        </div>
        
        <div class="card-body">
            <form action="{{ route('kategori-menu.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                    <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" 
                           value="{{ old('nama') }}" placeholder="Contoh: Makanan, Minuman, Snack" required>
                    @error('nama')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Nonaktif</option>
                    </select>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('kategori-menu.index') }}" class="btn btn-secondary">Kembali</a>
                    <button type="submit" class="btn btn-success"><i class="bi bi-save"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection