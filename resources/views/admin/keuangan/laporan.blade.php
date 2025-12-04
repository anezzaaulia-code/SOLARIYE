@extends('layouts.admin')

@section('content')
<div class="container-fluid p-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-dark m-0">Laporan Laba Rugi</h4>
            <p class="text-muted small m-0">Ringkasan keuangan bulanan & laba bersih.</p>
        </div>
        <div>
            <button class="btn btn-outline-danger btn-sm" onclick="window.print()">
                <i class="bi bi-file-earmark-pdf me-2"></i>Cetak Laporan
            </button>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label text-muted small fw-bold">Pilih Bulan</label>
                    <input type="month" name="bulan" class="form-control" value="{{ request('bulan', date('Y-m')) }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label text-muted small fw-bold">Dari Tanggal</label>
                    <input type="date" name="from" class="form-control" value="{{ request('from') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label text-muted small fw-bold">Sampai Tanggal</label>
                    <input type="date" name="to" class="form-control" value="{{ request('to') }}">
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100 fw-bold">
                        <i class="bi bi-search me-1"></i> Tampilkan
                    </button>
                    @if(request('from') || request('to') || request('bulan'))
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
            <div class="card border-0 shadow-sm h-100 border-start border-4 border-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <small class="text-muted fw-bold text-uppercase">Total Pendapatan</small>
                        <div class="bg-success bg-opacity-10 text-success p-2 rounded-circle">
                            <i class="bi bi-graph-up-arrow"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold text-success mb-0">Rp {{ number_format($pendapatan, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 border-start border-4 border-danger">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <small class="text-muted fw-bold text-uppercase">Total Pengeluaran</small>
                        <div class="bg-danger bg-opacity-10 text-danger p-2 rounded-circle">
                            <i class="bi bi-graph-down-arrow"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold text-danger mb-0">Rp {{ number_format($pengeluaran, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            @php $laba = $pendapatan - $pengeluaran; @endphp
            <div class="card border-0 shadow-sm h-100 border-start border-4 {{ $laba >= 0 ? 'border-primary' : 'border-warning' }}">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <small class="text-muted fw-bold text-uppercase">Laba / Rugi Bersih</small>
                        <div class="{{ $laba >= 0 ? 'bg-primary text-primary' : 'bg-warning text-warning' }} bg-opacity-10 p-2 rounded-circle">
                            <i class="bi bi-wallet2"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold {{ $laba >= 0 ? 'text-primary' : 'text-warning' }} mb-0">
                        Rp {{ number_format($laba, 0, ',', '.') }}
                    </h3>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 border-bottom">
            <h6 class="m-0 fw-bold text-dark"><i class="bi bi-list-ul me-2"></i>Rincian Transaksi Keuangan</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-secondary small text-uppercase">
                        <tr>
                            <th class="px-4 py-3 text-center" width="5%">No</th>
                            <th class="py-3" width="15%">Tanggal</th>
                            <th class="py-3 text-center" width="10%">Jenis</th>
                            <th class="py-3" width="45%">Keterangan</th>
                            <th class="py-3 px-4 text-end" width="20%">Nominal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($list as $item)
                        <tr>
                            <td class="text-center text-muted">{{ $loop->iteration }}</td>
                            <td>
                                <span class="fw-bold text-dark">{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</span>
                            </td>
                            <td class="text-center">
                                @if($item->jenis == 'pemasukan')
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success rounded-pill px-3">Masuk</span>
                                @else
                                    <span class="badge bg-danger bg-opacity-10 text-danger border border-danger rounded-pill px-3">Keluar</span>
                                @endif
                            </td>
                            <td>
                                <span class="text-dark">{{ $item->keterangan ?? '-' }}</span>
                                <div class="small text-muted fst-italic">Sumber: {{ ucfirst($item->sumber) }}</div>
                            </td>
                            <td class="text-end px-4">
                                <span class="fw-bold {{ $item->jenis == 'pemasukan' ? 'text-success' : 'text-danger' }}">
                                    {{ $item->jenis == 'pemasukan' ? '+' : '-' }} Rp {{ number_format($item->nominal, 0, ',', '.') }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2 opacity-50"></i>
                                Tidak ada data transaksi pada periode ini.
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
