@extends('layouts.admin')

@section('title', 'Tambah User')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Tambah User</h1>

    {{-- Error Validasi --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('users.store') }}" method="POST">
        @csrf

        {{-- NAMA --}}
        <div class="mb-3">
            <label class="form-label">Nama</label>
            <input type="text" name="nama"
                   class="form-control @error('nama') is-invalid @enderror"
                   value="{{ old('nama') }}" required>
            @error('nama')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        {{-- EMAIL --}}
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email"
                   class="form-control @error('email') is-invalid @enderror"
                   value="{{ old('email') }}" required>
            @error('email')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        {{-- PASSWORD --}}
        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password"
                   class="form-control @error('password') is-invalid @enderror"
                   required>
            @error('password')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        {{-- KONFIRMASI PASSWORD --}}
        <div class="mb-3">
            <label class="form-label">Konfirmasi Password</label>
            <input type="password" name="password_confirmation"
                   class="form-control" required>
        </div>

        {{-- ROLE --}}
        <div class="mb-3">
            <label class="form-label">Role</label>
            <select name="role"
                    class="form-select @error('role') is-invalid @enderror"
                    required>
                <option value="">-- Pilih Role --</option>
                <option value="admin" {{ old('role')=='admin' ? 'selected' : '' }}>Admin</option>
                <option value="kasir" {{ old('role')=='kasir' ? 'selected' : '' }}>Kasir</option>
            </select>
            @error('role')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('users.index') }}" class="btn btn-secondary">Kembali</a>

    </form>
</div>
@endsection
