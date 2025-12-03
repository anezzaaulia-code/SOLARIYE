@extends('layouts.admin')

@section('content')
<div class="container-fluid">

    <h4 class="mb-4 text-gray-800 fw-bold">Laporan Laba & Rugi</h4>

    {{-- Filter Tanggal --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label">Dari Tanggal</label>
                    <input type="date" name="from" class="form-control" value="{{ $from }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Sampai Tanggal</label>
                    <input type="date" name="to" class="form-control" value="{{ $to }}">
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-filter"></i> Tampilkan Laporan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Ringkasan Laba Rugi --}}
    <div class="row g-3">
        
        <div class="col-md-4">
            <div class="card border-left-success shadow h-100 py-2 border-0 border-start border-5 border-success">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Pemasukan</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Rp {{ number_format($pemasukan, 0, ',', '.') }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-arrow-up-circle fs-2 text-gray-300 text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-left-danger shadow h-100 py-2 border-0 border-start border-5 border-danger">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Total Pengeluaran</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Rp {{ number_format($pengeluaran, 0, ',', '.') }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-arrow-down-circle fs-2 text-gray-300 text-danger"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-left-primary shadow h-100 py-2 border-0 border-start border-5 {{ $labaRugi >= 0 ? 'border-primary' : 'border-warning' }}">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1 {{ $labaRugi >= 0 ? 'text-primary' : 'text-warning' }}">
                                {{ $labaRugi >= 0 ? 'Keuntungan Bersih (Laba)' : 'Kerugian (Rugi)' }}
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Rp {{ number_format($labaRugi, 0, ',', '.') }}
                            </div>
                        </div>
                        <div class="col-auto">
                            @if($labaRugi >= 0)
                                <i class="bi bi-emoji-smile fs-2 text-primary"></i>
                            @else
                                <i class="bi bi-emoji-frown fs-2 text-warning"></i>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- Tombol Cetak (Opsional) --}}
    <div class="mt-4 text-end">
        <button onclick="window.print()" class="btn btn-secondary">
            <i class="bi bi-printer"></i> Cetak Halaman
        </button>
    </div>

</div>
@endsection