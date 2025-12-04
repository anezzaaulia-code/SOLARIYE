@extends('layouts.admin')

@section('content')
<div class="container-fluid p-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-dark m-0">Manajemen Menu</h4>
            <p class="text-muted small m-0">Kelola daftar menu makanan dan minuman.</p>
        </div>
        <a href="{{ route('menu.create') }}" class="btn btn-primary fw-bold shadow-sm">
            <i class="bi bi-plus-lg me-2"></i> Tambah Menu Baru
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 border-bottom">
            <h6 class="m-0 fw-bold text-primary"><i class="bi bi-list-ul me-2"></i>Daftar Menu Tersedia</h6>
        </div>
        
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-secondary small text-uppercase">
                        <tr>
                            <th class="px-4 py-3 text-center" width="5%">No</th>
                            <th class="py-3" width="10%">Foto</th>
                            <th class="py-3" width="25%">Nama Menu</th>
                            <th class="py-3" width="15%">Kategori</th>
                            <th class="py-3" width="15%">Harga</th>
                            <th class="py-3 text-center" width="10%">Status</th>
                            <th class="py-3 text-center" width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($menus as $m)
                        <tr>
                            <td class="text-center text-muted">{{ $loop->iteration + $menus->firstItem() - 1 }}</td>
                            
                            <td>
                                @if($m->foto)
                                    <img src="{{ asset('storage/'.$m->foto) }}" 
                                         class="rounded border" 
                                         width="60" height="60" 
                                         style="object-fit: cover;" 
                                         alt="{{ $m->nama }}">
                                @else
                                    <div class="bg-light rounded border d-flex align-items-center justify-content-center text-muted" 
                                         style="width: 60px; height: 60px;">
                                        <i class="bi bi-image"></i>
                                    </div>
                                @endif
                            </td>

                            <td>
                                <span class="fw-bold text-dark">{{ $m->nama }}</span>
                            </td>

                            <td>
                                <span class="badge bg-light text-dark border fw-normal">
                                    {{ $m->kategori->nama ?? 'Tanpa Kategori' }}
                                </span>
                            </td>

                            <td class="fw-bold text-dark">
                                Rp {{ number_format($m->harga, 0, ',', '.') }}
                            </td>

                            <td class="text-center">
                                @if($m->status == 'tersedia')
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success rounded-pill px-3">Tersedia</span>
                                @elseif($m->status == 'habis')
                                    <span class="badge bg-danger bg-opacity-10 text-danger border border-danger rounded-pill px-3">Habis</span>
                                @else
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary rounded-pill px-3">{{ ucfirst($m->status) }}</span>
                                @endif
                            </td>

                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('menu.edit', $m->id) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>
                                    <form action="{{ route('menu.destroy', $m->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus menu ini? Data tidak bisa dikembalikan.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2 opacity-50"></i>
                                Belum ada data menu. Silakan tambah menu baru.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer bg-white py-3">
            <div class="d-flex justify-content-end">
                {{ $menus->withQueryString()->links() }}
            </div>
        </div>
    </div>

</div>
@endsection