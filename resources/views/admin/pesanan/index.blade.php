@extends('layouts.admin')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0 text-gray-800">Riwayat Pesanan</h3>
        {{-- Tombol Tambah Pesanan Manual (Opsional, biasanya lewat POS) --}}
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover align-middle">
                    <thead class="table-dark text-center">
                        <tr>
                            <th>No</th>
                            <th>Invoice</th>
                            <th>Tanggal</th>
                            <th>Pelanggan</th>
                            <th>Kasir</th>
                            <th>Total</th>
                            <th>Metode</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pesanan as $p)
                        <tr>
                            <td class="text-center">{{ $loop->iteration + ($pesanan->currentPage()-1)*$pesanan->perPage() }}</td>
                            <td class="fw-bold text-primary">{{ $p->invoice }}</td>
                            <td>{{ $p->created_at->format('d M Y H:i') }}</td>
                            <td>{{ $p->pelanggan ?? 'Umum' }}</td>
                            <td>{{ $p->kasir->nama ?? '-' }}</td>
                            <td class="text-end fw-bold">Rp {{ number_format($p->total, 0, ',', '.') }}</td>
                            <td class="text-center">
                                <span class="badge bg-secondary">{{ strtoupper($p->metode) }}</span>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('pesanan.show', $p->id) }}" class="btn btn-info btn-sm text-white" title="Lihat Detail">
                                    <i class="bi bi-eye"></i>
                                </a>

                                {{-- Hanya Admin yang boleh hapus history --}}
                                @if(auth()->user()->role == 'admin')
                                <form action="{{ route('pesanan.destroy', $p->id) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-danger btn-sm" onclick="return confirm('Hapus riwayat transaksi ini? Data keuangan terkait juga akan dihapus.')" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">Belum ada transaksi.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $pesanan->links() }}
            </div>
        </div>
    </div>
</div>
@endsection