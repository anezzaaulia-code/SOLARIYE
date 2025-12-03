@extends('layouts.admin')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0 text-gray-800">Manajemen Users</h3>
        <a href="{{ route('users.create') }}" class="btn btn-primary">
            <i class="bi bi-person-plus"></i> Tambah User Baru
        </a>
    </div>

    {{-- Alert Success --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Alert Error (misal hapus akun sendiri) --}}
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-body">
            
            {{-- Search Form --}}
            <div class="row justify-content-end mb-3">
                <div class="col-md-4">
                    <form action="{{ route('users.index') }}" method="GET">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" 
                                   placeholder="Cari nama atau email..." value="{{ request('search') }}">
                            <button class="btn btn-secondary" type="submit">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover align-middle">
                    <thead class="table-dark text-center">
                        <tr>
                            <th width="5%">No</th>
                            <th>Nama Lengkap</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th width="20%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $u)
                        <tr>
                            <td class="text-center">{{ $loop->iteration + ($users->currentPage()-1)*$users->perPage() }}</td>
                            <td class="fw-bold">{{ $u->nama }}</td>
                            <td>{{ $u->email }}</td>
                            <td class="text-center">
                                @if($u->role == 'admin')
                                    <span class="badge bg-primary">ADMIN</span>
                                @else
                                    <span class="badge bg-info text-dark">KASIR</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($u->status == 'aktif')
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-secondary">Nonaktif</span>
                                @endif
                            </td>
                            <td class="text-center">
                                {{-- Tombol Toggle Status --}}
                                @if(auth()->id() != $u->id)
                                    <a href="{{ route('users.toggleStatus', $u->id) }}" 
                                       class="btn btn-sm {{ $u->status == 'aktif' ? 'btn-outline-secondary' : 'btn-outline-success' }}"
                                       title="{{ $u->status == 'aktif' ? 'Nonaktifkan' : 'Aktifkan' }}">
                                        <i class="bi {{ $u->status == 'aktif' ? 'bi-toggle-on' : 'bi-toggle-off' }}"></i>
                                    </a>
                                @endif

                                {{-- Edit --}}
                                <a href="{{ route('users.edit', $u->id) }}" class="btn btn-warning btn-sm" title="Edit & Reset Password">
                                    <i class="bi bi-pencil-square"></i>
                                </a>

                                {{-- Delete --}}
                                @if(auth()->id() != $u->id)
                                    <form action="{{ route('users.destroy', $u->id) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button onclick="return confirm('Yakin ingin menghapus user {{ $u->nama }}?')" 
                                                class="btn btn-danger btn-sm" title="Hapus User">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Tidak ada data user.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-3">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</div>
@endsection