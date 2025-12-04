@extends('layouts.admin')

@section('content')
<div class="container-fluid p-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-dark m-0">Pembelian Bahan</h4>
            <p class="text-muted small m-0">Daftar riwayat stok masuk.</p>
        </div>
        <a href="{{ route('pembelian.create') }}" class="btn btn-primary fw-bold shadow-sm">
            <i class="bi bi-plus-lg me-1"></i> Tambah Pembelian
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-secondary small text-uppercase">
                        <tr>
                            <th class="px-4 py-3">Tanggal</th>
                            <th class="py-3">Supplier</th>
                            <th class="py-3">Total Harga</th>
                            <th class="py-3 text-end px-4" width="20%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pembelian as $p)
                        <tr>
                            <td class="px-4 fw-bold text-dark">{{ $p->tanggal }}</td>
                            <td>
                                <span class="badge bg-light text-dark border">
                                    {{ $p->supplier->nama_supplier ?? '-' }}
                                </span>
                            </td>
                            <td class="fw-bold text-success">
                                Rp {{ number_format($p->total_harga, 0, ',', '.') }}
                            </td>
                            <td class="text-end px-4">
                                <div class="btn-group" role="group">
                                    {{-- Detail --}}
                                    <a href="{{ route('pembelian.show', $p->id) }}" class="btn btn-sm btn-outline-info" title="Detail">
                                        <i class="bi bi-eye-fill"></i>
                                    </a>

                                    {{-- Edit --}}
                                    <a href="{{ route('pembelian.edit', $p->id) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>

                                    {{-- Hapus --}}
                                    <form action="{{ route('pembelian.destroy', $p->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus pembelian ini?');">
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
                            <td colspan="4" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2 opacity-50"></i>
                                Belum ada data pembelian.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection