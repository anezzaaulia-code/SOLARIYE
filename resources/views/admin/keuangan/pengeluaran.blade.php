@extends('layouts.admin')

@section('content')
<div class="container">

    <h4 class="mb-4 fw-bold">Pengeluaran</h4>

    {{-- CARD FILTER & TOTAL --}}
    <div class="row g-3 mb-4">

        {{-- FILTER TANGGAL --}}
        <div class="col-md-4">
            <label class="form-label">Dari Tanggal</label>
            <input type="date" name="from" id="from" class="form-control" value="{{ request('from') }}">
        </div>

        <div class="col-md-4">
            <label class="form-label">Sampai Tanggal</label>
            <input type="date" name="to" id="to" class="form-control" value="{{ request('to') }}">
        </div>

        <div class="col-md-4 d-flex align-items-end">
            <button class="btn btn-primary w-100" onclick="filterPengeluaran()">
                <i class="bi bi-filter"></i> Filter
            </button>
        </div>

    </div>

    {{-- KARTU TOTAL --}}
    <div class="row mb-4">

        <div class="col-md-4">
            <div class="p-3 bg-danger text-white rounded shadow-sm">
                <h6 class="mb-1">Total Pengeluaran</h6>
                <h3 class="fw-bold">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</h3>
            </div>
        </div>

        <div class="col-md-4">
            <a href="{{ route('keuangan.create', ['jenis' => 'pengeluaran']) }}" 
               class="btn btn-warning w-100 mt-md-4 mt-2">
                <i class="bi bi-plus-circle"></i> Tambah Pengeluaran
            </a>
        </div>

        <div class="col-md-4">
            <a href="{{ route('keuangan.exportPengeluaran', request()->all()) }}" 
               class="btn btn-danger w-100 mt-md-4 mt-2">
                <i class="bi bi-file-earmark-pdf"></i> Export PDF
            </a>
        </div>
    </div>

    {{-- TABEL PENGELUARAN --}}
    <div class="card shadow-sm">
        <div class="card-header fw-bold">Daftar Pengeluaran</div>

        <div class="table-responsive">
            <table class="table table-bordered mb-0">
                <thead class="table-light">
                    <tr class="text-center">
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Kategori</th>
                        <th>Keterangan</th>
                        <th>Nominal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($pengeluaran as $row)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ $row->tanggal }}</td>
                        <td>{{ ucfirst($row->sumber) }}</td>
                        <td>{{ $row->keterangan ?? '-' }}</td>
                        <td>Rp {{ number_format($row->nominal, 0, ',', '.') }}</td>
                        <td class="text-center">
                            <form action="{{ route('keuangan.destroy', $row->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">Belum ada data pengeluaran.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

</div>

<script>
    function filterPengeluaran() {
        let from = document.getElementById('from').value;
        let to = document.getElementById('to').value;
        window.location = `?from=${from}&to=${to}`;
    }
</script>

@endsection
