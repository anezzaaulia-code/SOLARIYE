@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4 py-4">

    {{-- Header Section: Judul & Action --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h3 class="fw-bold text-dark m-0">Manajemen Menu</h3>
            <p class="text-muted small m-0">Kelola katalog makanan dan minuman resto Anda.</p>
        </div>
        
        <div class="d-flex gap-2">
            {{-- Optional: Search Form UI (Bisa diaktifkan nanti) --}}
            <form action="{{ route('menu.index') }}" method="GET" class="d-none d-md-flex">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 rounded-start-pill ps-3 text-muted">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" name="q" class="form-control border-start-0 rounded-end-pill ps-2" placeholder="Cari menu..." value="{{ request('q') }}">
                </div>
            </form>

            <a href="{{ route('menu.create') }}" class="btn btn-primary rounded-pill px-4 fw-semibold shadow-sm">
                <i class="bi bi-plus-lg me-1"></i> Tambah
            </a>
        </div>
    </div>

    {{-- Alert Section --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert">
            <div class="d-flex align-items-center">
                <i class="bi bi-check-circle-fill fs-4 me-2"></i>
                <div>
                    <strong>Berhasil!</strong> {{ session('success') }}
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Main Card --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        
        {{-- Table Container --}}
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="min-width: 800px;">
                <thead class="bg-light">
                    <tr class="text-secondary small text-uppercase fw-bold">
                        <th class="px-4 py-3 text-center" width="5%">#</th>
                        <th class="py-3" width="10%">Visual</th>
                        <th class="py-3" width="25%">Informasi Menu</th>
                        <th class="py-3" width="15%">Kategori</th>
                        <th class="py-3" width="15%">Harga</th>
                        <th class="py-3 text-center" width="15%">Status</th>
                        <th class="py-3 text-center" width="15%">Opsi</th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    @forelse($menus as $m)
                    <tr>
                        <td class="text-center text-muted fw-semibold">{{ $loop->iteration + $menus->firstItem() - 1 }}</td>
                        
                        {{-- Kolom Foto --}}
                        <td class="px-3">
                            <div class="position-relative d-inline-block">
                                @if($m->foto)
                                    <img src="{{ asset('storage/'.$m->foto) }}" 
                                         class="rounded-3 shadow-sm border" 
                                         width="60" height="60" 
                                         style="object-fit: cover;" 
                                         alt="{{ $m->nama }}">
                                @else
                                    <div class="bg-light rounded-3 border d-flex align-items-center justify-content-center text-secondary shadow-sm" 
                                         style="width: 60px; height: 60px;">
                                        <i class="bi bi-image fs-4"></i>
                                    </div>
                                @endif
                            </div>
                        </td>

                        {{-- Kolom Nama --}}
                        <td>
                            <h6 class="fw-bold text-dark mb-1">{{ $m->nama }}</h6>
                            <span class="text-muted small d-block text-truncate" style="max-width: 200px;">
                                {{ $m->deskripsi ?? 'Tidak ada deskripsi' }}
                            </span>
                        </td>

                        {{-- Kolom Kategori --}}
                        <td>
                            <div class="d-flex align-items-center">
                                <span class="badge bg-info bg-opacity-10 text-info border border-info px-2 py-1 fw-normal rounded-2">
                                    <i class="bi bi-tag-fill me-1"></i> {{ $m->kategori->nama ?? 'Umum' }}
                                </span>
                            </div>
                        </td>

                        {{-- Kolom Harga (Font Monospace agar rapi) --}}
                        <td class="fw-bold text-dark font-monospace">
                            Rp {{ number_format($m->harga, 0, ',', '.') }}
                        </td>

                        {{-- Kolom Status --}}
                        <td class="text-center">
                            @if($m->status == 'tersedia')
                                <span class="badge rounded-pill bg-success bg-opacity-10 text-success border border-success px-3 py-2">
                                    <i class="bi bi-check-circle me-1"></i> Tersedia
                                </span>
                            @elseif($m->status == 'habis')
                                <span class="badge rounded-pill bg-danger bg-opacity-10 text-danger border border-danger px-3 py-2">
                                    <i class="bi bi-x-circle me-1"></i> Habis
                                </span>
                            @else
                                <span class="badge rounded-pill bg-secondary bg-opacity-10 text-secondary border border-secondary px-3 py-2">
                                    {{ ucfirst($m->status) }}
                                </span>
                            @endif
                        </td>

                        {{-- Kolom Aksi --}}
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-1">
                                <a href="{{ route('menu.edit', $m->id) }}" class="btn btn-sm btn-light text-primary border shadow-sm" data-bs-toggle="tooltip" title="Edit Menu">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                
                                <form action="{{ route('menu.destroy', $m->id) }}" method="POST" onsubmit="return confirm('Hapus menu {{ $m->nama }}?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-light text-danger border shadow-sm" data-bs-toggle="tooltip" title="Hapus Menu">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <div class="d-flex flex-column align-items-center justify-content-center opacity-50">
                                <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" width="80" alt="Empty" class="mb-3 grayscale">
                                <h6 class="fw-bold mb-1">Data Menu Kosong</h6>
                                <p class="small text-muted">Belum ada menu yang ditambahkan.</p>
                                <a href="{{ route('menu.create') }}" class="btn btn-sm btn-outline-primary mt-2">
                                    <i class="bi bi-plus-lg"></i> Tambah Menu Sekarang
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Footer Pagination --}}
        @if($menus->hasPages())
        <div class="card-footer bg-white border-top py-3">
            <div class="d-flex justify-content-between align-items-center">
                <small class="text-muted">
                    Menampilkan {{ $menus->firstItem() }} - {{ $menus->lastItem() }} dari {{ $menus->total() }} data
                </small>
                <div class="d-flex justify-content-end mt-2">
                    {{ $menus->withQueryString()->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<style>
    /* Tambahan CSS kecil untuk mempercantik */
    .table-hover tbody tr:hover {
        background-color: #f8f9fa;
        transition: all 0.2s ease;
    }
    .btn-light:hover {
        background-color: #e2e6ea;
        border-color: #dae0e5;
    }
    .grayscale {
        filter: grayscale(100%);
        opacity: 0.6;
    }
</style>
@endsection