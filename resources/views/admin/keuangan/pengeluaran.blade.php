@extends('layouts.admin')

@section('content')
<div class="container-fluid p-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-dark m-0">Laporan Pengeluaran</h4>
            <p class="text-muted small m-0">Kelola dan pantau biaya operasional toko.</p>
        </div>
        <div>
            <a href="{{ route('pengeluaran.export', request()->all()) }}" class="btn btn-outline-danger btn-sm">
                <i class="bi bi-file-earmark-pdf me-2"></i>Cetak Laporan
            </a>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label text-muted small fw-bold">Dari Tanggal</label>
                    <input type="date" name="from" class="form-control" value="{{ request('from') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label text-muted small fw-bold">Sampai Tanggal</label>
                    <input type="date" name="to" class="form-control" value="{{ request('to') }}">
                </div>
                <div class="col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100 fw-bold">
                        <i class="bi bi-funnel me-1"></i> Terapkan Filter
                    </button>
                    @if(request('from') || request('to'))
                        <a href="{{ url()->current() }}" class="btn btn-secondary" title="Reset Filter">
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 border-start border-4 border-danger">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <small class="text-muted fw-bold text-uppercase">Pengeluaran Hari Ini</small>
                        <div class="bg-danger bg-opacity-10 text-danger rounded-circle p-2">
                            <i class="bi bi-calendar-minus"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold text-dark mb-0">Rp {{ number_format($pengeluaranHariIni, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 border-start border-4 border-warning">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <small class="text-muted fw-bold text-uppercase">Pengeluaran Minggu Ini</small>
                        <div class="bg-warning bg-opacity-10 text-warning rounded-circle p-2">
                            <i class="bi bi-graph-down-arrow"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold text-dark mb-0">Rp {{ number_format($pengeluaranMingguIni, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 border-start border-4 border-secondary">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <small class="text-muted fw-bold text-uppercase">Total (Sesuai Filter)</small>
                        <div class="bg-secondary bg-opacity-10 text-secondary rounded-circle p-2">
                            <i class="bi bi-wallet2"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold text-dark mb-0">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('keuangan.create', ['jenis' => 'pengeluaran']) }}" class="btn btn-warning text-dark fw-bold px-4 shadow-sm">
            <i class="bi bi-plus-circle me-2"></i> Tambah Pengeluaran Baru
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 border-bottom">
            <h6 class="m-0 fw-bold text-danger"><i class="bi bi-table me-2"></i>Rincian Biaya Keluar</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-secondary small text-uppercase">
                        <tr>
                            <th class="px-4 py-3 text-center" width="5%">No</th>
                            <th class="py-3" width="15%">Tanggal</th>
                            <th class="py-3" width="15%">Kategori</th>
                            <th class="py-3" width="40%">Keterangan</th>
                            <th class="py-3 px-4 text-end" width="15%">Nominal (Rp)</th>
                            <th class="py-3 text-center" width="10%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pengeluaran as $row)
                        <tr>
                            <td class="text-center text-muted">{{ $loop->iteration + $pengeluaran->firstItem() - 1 }}</td>
                            
                            <td>
                                <span class="fw-bold text-dark">{{ \Carbon\Carbon::parse($row->tanggal)->format('d M Y') }}</span>
                            </td>

                            <td>
                                <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary rounded-pill px-3">
                                    {{ ucfirst($row->sumber) }}
                                </span>
                            </td>

                            <td>
                                <span class="text-dark">{{ $row->keterangan ?? '-' }}</span>
                            </td>

                            <td class="text-end px-4">
                                <span class="fw-bold text-danger">
                                    Rp {{ number_format($row->nominal, 0, ',', '.') }}
                                </span>
                            </td>

                            <td class="text-center">
                                <form action="{{ route('keuangan.destroy', $row->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-light text-danger border-0 hover-danger" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2 opacity-50"></i>
                                Belum ada data pengeluaran pada rentang tanggal ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                    
                    <tfoot class="bg-light fw-bold">
                        <tr>
                            <td colspan="4" class="text-end py-3 text-secondary text-uppercase small">Total Halaman Ini</td>
                            <td class="text-end px-4 py-3 text-danger fs-6">
                                Rp {{ number_format($pengeluaran->sum('nominal'), 0, ',', '.') }}
                            </td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        
        <div class="card-footer bg-white py-3">
            <div class="d-flex justify-content-end">
                {{ $pengeluaran->withQueryString()->links() }}
            </div>
        </div>
    </div>

</div>

<style>
    .hover-danger:hover {
        background-color: #dc3545 !important;
        color: white !important;
    }
</style>
@endsection