@extends('layouts.admin')

@section('content')
<div class="container-fluid p-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-dark m-0">Data Bahan Baku</h4>
            <p class="text-muted small m-0">Kelola stok inventory dan batas peringatan.</p>
        </div>
        <a href="{{ route('bahanbaku.create') }}" class="btn btn-primary fw-bold shadow-sm">
            <i class="bi bi-plus-lg me-2"></i> Tambah Bahan
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 border-bottom">
            <h6 class="m-0 fw-bold text-primary"><i class="bi bi-box-seam me-2"></i>Daftar Inventory</h6>
        </div>
        
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-secondary small text-uppercase">
                        <tr>
                            <th class="px-4 py-3 text-center" width="5%">No</th>
                            <th class="py-3">Nama Bahan</th>
                            <th class="py-3 text-center">Sisa Stok</th>
                            <th class="py-3 text-center">Status</th>
                            <th class="py-3 text-center">Setting Batas</th>
                            <th class="py-3 text-end px-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($bahan as $b)
                        <tr>
                            <td class="text-center text-muted">{{ $loop->iteration }}</td>
                            
                            <td>
                                <span class="fw-bold text-dark d-block">{{ $b->nama_bahan }}</span>
                                <small class="text-muted">Satuan: {{ $b->satuan }}</small>
                            </td>

                            <td class="text-center">
                                <span class="fs-5 fw-bold {{ $b->stok <= $b->batas_merah ? 'text-danger' : 'text-dark' }}">
                                    {{ $b->stok }}
                                </span>
                            </td>

                            <td class="text-center">
                                @if($b->stok <= $b->batas_merah)
                                    <span class="badge bg-danger bg-opacity-10 text-danger border border-danger px-3 rounded-pill">KRITIS</span>
                                @elseif($b->stok <= $b->batas_kuning)
                                    <span class="badge bg-warning bg-opacity-10 text-warning border border-warning px-3 rounded-pill text-dark">MENIPIS</span>
                                @else
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success px-3 rounded-pill">AMAN</span>
                                @endif
                            </td>

                            <td class="text-center small">
                                <div class="d-inline-flex gap-2">
                                    <span class="badge bg-light text-warning border" title="Batas Kuning">
                                        <i class="bi bi-exclamation-triangle me-1"></i> {{ $b->batas_kuning }}
                                    </span>
                                    <span class="badge bg-light text-danger border" title="Batas Merah">
                                        <i class="bi bi-x-circle me-1"></i> {{ $b->batas_merah }}
                                    </span>
                                </div>
                            </td>

                            <td class="text-end px-4">
                                <div class="btn-group">
                                    <a href="{{ route('bahanbaku.edit', $b->id) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>
                                    <form action="{{ route('bahanbaku.destroy', $b->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus bahan ini?');">
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
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2 opacity-50"></i>
                                Belum ada data bahan baku.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer bg-white py-3">
            {{-- Hapus komentar di bawah jika menggunakan pagination di controller --}}
            {{-- {{ $bahan->links() }} --}}
        </div>
    </div>

</div>
@endsection