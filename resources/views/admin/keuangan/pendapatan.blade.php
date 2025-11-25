@extends('layouts.admin')

@section('content')
<div class="container-fluid">

    <h4 class="mb-4">Pendapatan Harian & Bulanan</h4>

    {{-- FILTER --}}
    <form method="GET" class="row g-3 mb-4">
        <div class="col-md-3">
            <label class="form-label">Dari Tanggal</label>
            <input type="date" name="from" class="form-control" value="{{ request('from') }}">
        </div>
        <div class="col-md-3">
            <label class="form-label">Sampai Tanggal</label>
            <input type="date" name="to" class="form-control" value="{{ request('to') }}">
        </div>
        <div class="col-md-3">
            <label class="form-label d-block">&nbsp;</label>
            <button class="btn btn-primary w-100">
                <i class="bi bi-funnel"></i> Filter
            </button>
        </div>
    </form>


    {{-- CARD INFO --}}
    <div class="row mb-4">

        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6>Pendapatan Hari Ini</h6>
                    <h3 class="text-success">Rp {{ number_format($pendapatanHariIni, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6>Pendapatan Minggu Ini</h6>
                    <h3 class="text-primary">Rp {{ number_format($pendapatanMinggu, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6>Total Pendapatan (Rentang Tanggal)</h6>
                    <h3 class="text-dark">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>

    </div>


    {{-- TABEL --}}
    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h5 class="mb-0">Daftar Pendapatan</h5>
        </div>

        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Keterangan</th>
                        <th class="text-end">Nominal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $row)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ \Carbon\Carbon::parse($row->tanggal)->format('d M Y') }}</td>
                            <td>{{ ucfirst($row->sumber) }}</td>
                            <td>{{ $row->keterangan ?? '-' }}</td>
                            <td class="text-end">Rp {{ number_format($row->nominal, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="card-footer">
            {{ $data->links() }}
        </div>
    </div>

</div>
@endsection
