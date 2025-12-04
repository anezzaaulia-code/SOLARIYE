@extends('layouts.admin')

@section('content')
<div class="container-fluid p-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-dark m-0">Stok Harian</h4>
            <p class="text-muted small m-0">Laporan pemakaian bahan baku harian.</p>
        </div>
        <a href="{{ route('stokharian.create') }}" class="btn btn-primary fw-bold shadow-sm">
            <i class="bi bi-plus-lg me-2"></i> Input Stok Hari Ini
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 border-bottom">
            <h6 class="m-0 fw-bold text-primary"><i class="bi bi-calendar-check me-2"></i>Riwayat Stok</h6>
        </div>
        
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-secondary small text-uppercase">
                        <tr>
                            <th class="px-4 py-3" width="15%">Tanggal</th>
                            <th class="py-3">Bahan Baku</th>
                            <th class="py-3 text-center">Stok Awal</th>
                            <th class="py-3 text-center">Stok Akhir</th>
                            <th class="py-3 text-center">Pemakaian</th>
                            <th class="py-3 text-center">Status</th>
                            <th class="py-3 text-end px-4" width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($stokharian as $item)
                        @php
                            $pemakaian = $item->stok_awal - $item->stok_akhir;
                            $badgeColor = match ($item->status_warna) {
                                'habis' => 'danger',
                                'menipis' => 'warning',
                                default => 'success'
                            };
                            $badgeText = ucfirst($item->status_warna);
                        @endphp
                        <tr>
                            <td class="px-4 fw-bold text-dark">
                                {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}
                            </td>

                            <td>
                                @if($item->bahan)
                                    <span class="fw-bold text-dark">{{ $item->bahan->nama_bahan }}</span>
                                    <small class="text-muted d-block">{{ $item->bahan->satuan }}</small>
                                @else
                                    <span class="text-muted fst-italic">- Data Bahan Terhapus -</span>
                                @endif
                            </td>

                            <td class="text-center">
                                <span class="badge bg-secondary bg-opacity-10 text-secondary border px-3">
                                    {{ $item->stok_awal }}
                                </span>
                            </td>

                            <td class="text-center">
                                <span class="fw-bold text-dark">{{ $item->stok_akhir }}</span>
                            </td>

                            <td class="text-center">
                                <span class="text-danger fw-bold">- {{ $pemakaian }}</span>
                            </td>

                            <td class="text-center">
                                <span class="badge bg-{{ $badgeColor }} bg-opacity-10 text-{{ $badgeColor }} border border-{{ $badgeColor }} rounded-pill px-3">
                                    {{ $badgeText }}
                                </span>
                            </td>

                            <td class="text-end px-4">
                                <div class="btn-group">
                                    <a href="{{ route('stokharian.edit', $item->id) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>
                                    <form action="{{ route('stokharian.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data stok ini?');">
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
                                Belum ada data stok harian. Silakan input stok hari ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer bg-white py-3">
            {{-- {{ $stokharian->links() }} --}} 
        </div>
    </div>

</div>
@endsection