@extends('layouts.admin')

@section('content')
<div class="container-fluid p-4">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-dark m-0">Laporan Pendapatan</h4>
            <p class="text-muted small m-0">Pantau arus kas masuk harian dan bulanan Anda.</p>
        </div>
        <div>
            {{-- 🔥 FIX: route sudah benar untuk export pendapatan --}}
            <a href="{{ route('pendapatan.export', request()->all()) }}" class="btn btn-outline-danger btn-sm">
                <i class="bi bi-file-earmark-pdf me-2"></i>Cetak Laporan
            </a>
        </div>
    </div>

    {{-- FILTER --}}
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

    {{-- STATISTIK --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 border-start border-4 border-success">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <small class="text-muted fw-bold text-uppercase">Pendapatan Hari Ini</small>
                        <div class="bg-success bg-opacity-10 text-success rounded-circle p-2">
                            <i class="bi bi-calendar-check"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold text-dark mb-0">
                        Rp {{ number_format($pendapatanHariIni, 0, ',', '.') }}
                    </h3>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 border-start border-4 border-primary">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <small class="text-muted fw-bold text-uppercase">Pendapatan Minggu Ini</small>
                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-2">
                            <i class="bi bi-graph-up-arrow"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold text-dark mb-0">
                        Rp {{ number_format($pendapatanMinggu, 0, ',', '.') }}
                    </h3>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 border-start border-4 border-warning">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <small class="text-muted fw-bold text-uppercase">Total (Sesuai Filter)</small>
                        <div class="bg-warning bg-opacity-10 text-warning rounded-circle p-2">
                            <i class="bi bi-wallet2"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold text-dark mb-0">
                        Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
                    </h3>
                </div>
            </div>
        </div>
    </div>

    {{-- TABEL --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 border-bottom">
            <h6 class="m-0 fw-bold text-primary">
                <i class="bi bi-table me-2"></i>Rincian Transaksi Masuk
            </h6>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-secondary small text-uppercase">
                        <tr>
                            <th class="px-4 py-3 text-center" width="5%">No</th>
                            <th width="15%">Tanggal</th>
                            <th width="15%">Sumber</th>
                            <th width="45%">Pelanggan / Keterangan</th>
                            <th class="px-4 text-end" width="20%">Nominal (Rp)</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($data as $row)
                        <tr>
                            <td class="text-center text-muted">
                                {{ $loop->iteration + $data->firstItem() - 1 }}
                            </td>

                            <td>
                                <span class="fw-bold text-dark">
                                    {{ \Carbon\Carbon::parse($row->tanggal)->format('d M Y') }}
                                </span>
                            </td>

                            <td>
                                <span class="badge bg-info bg-opacity-10 text-info border border-info rounded-pill px-3">
                                    {{ ucfirst($row->sumber) }}
                                </span>
                            </td>

                            <td>
                                @if($row->sumber == 'penjualan' && $row->pesanan)
                                    <div class="d-flex flex-column">
                                        <span class="fw-bold text-dark">
                                            {{ $row->pesanan->pelanggan ?? 'Pelanggan Umum' }}
                                        </span>
                                        <small class="text-muted fst-italic" style="font-size: 11px;">
                                            {{ $row->keterangan }}
                                        </small>
                                    </div>
                                @else
                                    <span class="text-dark">{{ $row->keterangan ?? '-' }}</span>
                                @endif
                            </td>

                            <td class="text-end px-4">
                                <span class="fw-bold text-success">
                                    Rp {{ number_format($row->nominal, 0, ',', '.') }}
                                </span>
                            </td>
                        </tr>

                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2 opacity-50"></i>
                                Belum ada data pendapatan pada rentang tanggal ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>

                    <tfoot class="bg-light fw-bold">
                        <tr>
                            <td colspan="4" class="text-end py-3 text-secondary text-uppercase small">
                                Total Halaman Ini
                            </td>
                            <td class="text-end px-4 py-3 text-primary fs-6">
                                Rp {{ number_format($data->sum('nominal'), 0, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>

                </table>
            </div>
        </div>

        <div class="card-footer bg-white py-3">
            <div class="d-flex justify-content-end">
                {{ $data->withQueryString()->links() }}
            </div>
        </div>
    </div>

</div>
@endsection
