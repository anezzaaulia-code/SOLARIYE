@extends('layouts.admin')

@section('content')
<div class="container mt-4">

    <div class="card shadow-sm" style="max-width: 800px; margin: 0 auto;">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Tambah User Baru</h5>
        </div>
        
        <div class="card-body">
            <form action="{{ route('users.store') }}" method="POST">
                @csrf

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        {{-- PERBAIKAN: name="nama" bukan "name" --}}
                        <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" 
                               value="{{ old('nama') }}" required>
                        @error('nama')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                               value="{{ old('email') }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Role / Jabatan <span class="text-danger">*</span></label>
                    <select name="role" class="form-select @error('role') is-invalid @enderror" required>
                        <option value="">-- Pilih Role --</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin (Akses Penuh)</option>
                        {{-- PERBAIKAN: value="kasir" bukan "user" --}}
                        <option value="kasir" {{ old('role') == 'kasir' ? 'selected' : '' }}>Kasir (POS Only)</option>
                    </select>
                    @error('role')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="alert alert-info py-2">
                    <small><i class="bi bi-info-circle"></i> Password minimal 6 karakter.</small>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label class="form-label">Password <span class="text-danger">*</span></label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                        {{-- WAJIB ADA: name="password_confirmation" --}}
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('users.index') }}" class="btn btn-secondary">Kembali</a>
                    <button type="submit" class="btn btn-success"><i class="bi bi-save"></i> Simpan User</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection