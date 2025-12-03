@extends('layouts.admin')

@section('content')
<div class="container mt-4">

    <div class="card shadow-sm" style="max-width: 800px; margin: 0 auto;">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Tambah Supplier Baru</h5>
        </div>
        
        <div class="card-body">
            <form action="{{ route('supplier.store') }}" method="POST">
                @csrf

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Nama Supplier <span class="text-danger">*</span></label>
                        <input type="text" name="nama_supplier" class="form-control @error('nama_supplier') is-invalid @enderror" 
                               value="{{ old('nama_supplier') }}" required placeholder="PT. Pangan Sejahtera">
                        @error('nama_supplier')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Kontak (HP/Telp)</label>
                        <input type="text" name="kontak" class="form-control @error('kontak') is-invalid @enderror" 
                               value="{{ old('kontak') }}" placeholder="0812...">
                        @error('kontak')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email (Opsional)</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                           value="{{ old('email') }}" placeholder="email@supplier.com">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Alamat Lengkap</label>
                    <textarea name="alamat" class="form-control" rows="3" placeholder="Jl. Raya... ">{{ old('alamat') }}</textarea>
                </div>

                <div class="mb-4">
                    <label class="form-label">Keterangan Tambahan</label>
                    <textarea name="keterangan" class="form-control" rows="2" placeholder="Catatan khusus supplier ini (misal: kirim tiap senin)">{{ old('keterangan') }}</textarea>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('supplier.index') }}" class="btn btn-secondary">Kembali</a>
                    <button type="submit" class="btn btn-success"><i class="bi bi-save"></i> Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection