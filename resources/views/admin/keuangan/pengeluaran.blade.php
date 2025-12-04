@extends('layouts.admin')

@section('content')
<div class="container-fluid p-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-dark m-0">Pengeluaran</h4>
            <p class="text-muted small m-0">Kelola dan pantau pengeluaran operasional toko.</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form action="" method="GET" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label for="from" class="form-label text-muted small fw-bold">Dari Tanggal</label>
                    <input type="date" name="from" id="from" class="form-control" value="{{ request('from') }}">
                </div>
                <div class="col-md-4">
                    <label for="to" class="form-label text-muted small fw-bold">Sampai Tanggal</label>
                    <input type="date" name="to" id="to" class="form-control" value="{{ request('to') }}">
                </div>
                <div class="col-md-4 d-flex gap-2">
                    <button type="button" class="btn btn-primary w-100 fw-bold" onclick="filterPengeluaran()">
                        <i class="bi bi-funnel me-1"></i> Terapkan Filter
                    </button>
                    @if(request('from') || request('to'))
                        <a href="{{ url()->current() }}" class="btn btn-secondary" title="Reset">
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 border-start border-4 border-danger bg-white">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase">Total Pengeluaran</small>
                        <h3 class="fw-bold text-danger mb-0 mt-1">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</h3>
                    </div>
                    <div class="bg-danger bg-opacity-10 text-danger p-3 rounded-circle">
                        <i class="bi bi-cash-stack fs-4"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card border-0 shadow-sm h-100 bg-light">
                <div class="card-body d-flex align-items-center justify-content-end gap-3">
                    <a href="{{ route('keuangan.create', ['jenis' => 'pengeluaran']) }}" class="btn btn-warning text-dark fw-bold px-4 py-2">
                        <i class="bi bi-plus-circle me-2"></i> Tambah Pengeluaran
                    </a>
                    <a href="{{ route('pengeluaran.export', request()->all()) }}" class="btn btn-outline-danger fw-bold px-4 py-2">
                        <i class="bi bi-file-earmark-pdf me-2"></i> Cetak Laporan
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 border-bottom">
            <h6 class="m-0 fw-bold text-danger"><i class="bi bi-table me-2"></i>Rincian Pengeluaran</h6>
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
                            <th class="py-3 px-4 text-end" width="15%">Nominal</th>
                            <th class="py-3 text-center" width="10%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pengeluaran as $row)
                        <tr>
                            <td class="text-center text-muted">{{ $loop->iteration }}</td>
                            <td>
                                <span class="fw-bold text-dark">{{ \Carbon\Carbon::parse($row->tanggal)->format('d M Y') }}</span>
                            </td>
                            <td>
                                <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary rounded-pill px-3">
                                    {{ ucfirst($row->sumber) }}
                                </span>
                            </td>
                            <td>
                                @if($row->keterangan)
                                    {{Str::limit($row->keterangan, 50) }}
                                @else
                                    <span class="text-muted fst-italic small">-</span>
                                @endif
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
                </table>
            </div>
        </div>
    </div>

</div>

<script>
    function filterPengeluaran() {
        let from = document.getElementById('from').value;
        let to = document.getElementById('to').value;

        // Memastikan parameter hanya ditambahkan jika ada nilainya
        let params = new URLSearchParams();
        if(from) params.append('from', from);
        if(to) params.append('to', to);

        window.location.search = params.toString();
    }
</script>

<style>
    /* Hover effect custom untuk tombol hapus */
    .hover-danger:hover {
        background-color: #dc3545 !important;
        color: white !important;
    }
</style>
@endsection
