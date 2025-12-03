@extends('layouts.admin')

@section('content')
<div class="container mt-4">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>Edit User: <span class="text-primary">{{ $user->nama }}</span></h4>
        <a href="{{ route('users.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        
        {{-- KIRI: EDIT DATA DIRI --}}
        <div class="col-md-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white fw-bold">
                    <i class="bi bi-person-lines-fill"></i> Data Profil
                </div>
                <div class="card-body">
                    <form action="{{ route('users.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control @error('nama') is-invalid @enderror" 
                                   name="nama" value="{{ old('nama', $user->nama) }}" required>
                            @error('nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                   value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Role</label>
                                <select name="role" class="form-select @error('role') is-invalid @enderror">
                                    <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="kasir" {{ $user->role == 'kasir' ? 'selected' : '' }}>Kasir</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Status Akun</label>
                                <select name="status" class="form-select">
                                    <option value="aktif" {{ $user->status == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="nonaktif" {{ $user->status == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                                </select>
                            </div>
                        </div>

                        <div class="text-end">
                            <button class="btn btn-primary">
                                <i class="bi bi-save"></i> Update Profil
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- KANAN: RESET PASSWORD --}}
        <div class="col-md-4">
            <div class="card shadow-sm border-danger">
                <div class="card-header bg-danger text-white fw-bold">
                    <i class="bi bi-key"></i> Reset Password
                </div>
                <div class="card-body">
                    <p class="text-muted small">
                        Gunakan form ini jika user lupa passwordnya. Password lama tidak diperlukan.
                    </p>

                    <form action="{{ route('users.resetPassword', $user->id) }}" method="POST">
                        @csrf
                        {{-- Method POST sudah benar sesuai route --}}

                        <div class="mb-3">
                            <label class="form-label">Password Baru</label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>

                        <button class="btn btn-danger w-100">
                            Reset Password
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection