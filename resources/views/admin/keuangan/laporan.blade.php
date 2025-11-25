@extends('layouts.admin') 
{{-- layout yg kamu pakai untuk sidebar --}}

@section('content')
<div class="container-fluid">

    <h4 class="mb-3">Laporan Keuangan</h4>

    {{-- FILTER --}}
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Pilih Bulan</label>
                    <input type="month" name="bulan" class="form-control" 
                           value="{{ request('bulan') }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Dari Tanggal</label>
                    <input type="date" name="from" class="form-control"
                           value="{{ request('from') }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Sampai Tanggal</label>
                    <input type="date" name="to" class="form-control"
                           value="{{ request('to') }}">
                </div>

                <div class="col-12 text-end">
                    <button class="btn btn-primary mt-3">
                        <i class="bi bi-search"></i> Tampilkan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- CARD RINGKASAN --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6>Total Pendapatan</h6>
                    <h4 class="text-success">Rp {{ number_format($pendapatan) }}</h4>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6>Total Pengeluaran</h6>
                    <h4 class="text-danger">Rp {{ number_format($pengeluaran) }}</h4>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6>Laba / Rugi</h6>
                    <h4 class="{{ $pendapatan - $pengeluaran >= 0 ? 'text-success' : 'text-danger' }}">
                        Rp {{ number_format($pendapatan - $pengeluaran) }}
                    </h4>
                </div>
            </div>
        </div>
    </div>

    {{-- TOMBOL PDF --}}
    <div class="text-end mb-3">
        <a href="#" class="btn btn-danger">
            <i class="bi bi-file-earmark-pdf"></i> Export PDF
        </a>
    </div>

    {{-- TABLE --}}
    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="mb-3">Detail Transaksi</h5>

            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Jenis</th>
                            <th>Deskripsi</th>
                            <th>Nominal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($list as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->tanggal }}</td>
                            <td>{{ ucfirst($item->jenis) }}</td>
                            <td>{{ $item->keterangan }}</td>
                            <td>Rp {{ number_format($item->nominal) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-3">
                                Tidak ada data.
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
