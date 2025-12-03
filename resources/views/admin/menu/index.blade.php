@extends('layouts.admin')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0 text-gray-800">Daftar Menu Restoran</h3>
        <a href="{{ route('menu.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Tambah Menu Baru
        </a>
    </div>

    {{-- Alert Success --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th width="5%">No</th>
                            <th width="10%">Foto</th>
                            <th>Nama Menu</th>
                            <th>Kategori</th>
                            <th>Harga</th>
                            <th>Status</th>
                            <th width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($menus as $m)
                        <tr>
                            {{-- Penomoran halaman --}}
                            <td>{{ $loop->iteration + ($menus->currentPage()-1) * $menus->perPage() }}</td>
                            
                            <td>
                                @if($m->foto)
                                    <img src="{{ asset('storage/'.$m->foto) }}" class="rounded shadow-sm" style="width: 60px; height: 60px; object-fit: cover;">
                                @else
                                    <span class="badge bg-secondary">No Image</span>
                                @endif
                            </td>
                            
                            <td class="fw-bold">{{ $m->nama }}</td>
                            <td>
                                <span class="badge bg-info text-dark">
                                    {{ $m->kategori->nama ?? 'Tanpa Kategori' }}
                                </span>
                            </td>
                            <td>Rp {{ number_format($m->harga, 0, ',', '.') }}</td>
                            
                            <td>
                                @if($m->status == 'tersedia')
                                    <span class="badge bg-success"><i class="bi bi-check-circle"></i> Tersedia</span>
                                @elseif($m->status == 'habis')
                                    <span class="badge bg-danger"><i class="bi bi-x-circle"></i> Habis</span>
                                @else
                                    <span class="badge bg-secondary"><i class="bi bi-eye-slash"></i> Nonaktif</span>
                                @endif
                            </td>
                            
                            <td>
                                <a href="{{ route('menu.edit', $m->id) }}" class="btn btn-warning btn-sm" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>

                                <form action="{{ route('menu.destroy', $m->id) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button onclick="return confirm('Yakin ingin menghapus menu {{ $m->nama }}?')"
                                            class="btn btn-danger btn-sm" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">Belum ada data menu.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-3">
                {{ $menus->links() }}
            </div>
        </div>
    </div>
</div>
@endsection