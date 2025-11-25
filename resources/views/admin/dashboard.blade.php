@extends('layouts.admin')

@section('content')

<div class="container-fluid">

    {{-- ROW CARDS --}}
    <div class="row mb-4">

        {{-- Pemasukan Hari Ini --}}
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card text-white bg-success h-100">
                <div class="card-body">
                    <h6 class="card-title">Pemasukan Hari Ini</h6>
                    <h3>Rp {{ number_format($pemasukanHari, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>

        {{-- Pengeluaran Hari Ini --}}
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card text-white bg-danger h-100">
                <div class="card-body">
                    <h6 class="card-title">Pengeluaran Hari Ini</h6>
                    <h3>Rp {{ number_format($pengeluaranHari, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>

        {{-- Stok Menipis --}}
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card text-white bg-warning h-100">
                <div class="card-body">
                    <h6 class="card-title">Stok Menipis</h6>
                    <h3>{{ $stokMenipis->count() }}</h3>
                </div>
            </div>
        </div>

        {{-- Menu Terlaris --}}
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card text-white bg-primary h-100">
                <div class="card-body">
                    <h6 class="card-title">Menu Terlaris Minggu Ini</h6>
                    <h3>{{ $topMenus->sum('total_qty') }}</h3>
                </div>
            </div>
        </div>
    </div>

    {{-- ROW CHARTS --}}
    <div class="row mb-4">

        {{-- Chart Pemasukan 7 Hari --}}
        <div class="col-md-6 mb-3">
            <div class="card h-100">
                <div class="card-header fw-bold">
                    Pemasukan 7 Hari Terakhir
                </div>
                <div class="card-body">
                    <canvas id="pemasukanChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Chart Top Menu --}}
        <div class="col-md-6 mb-3">
            <div class="card h-100">
                <div class="card-header fw-bold">
                    5 Menu Terlaris Minggu Ini
                </div>
                <div class="card-body">
                    <canvas id="topMenuChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabel Stok Menipis --}}
    <div class="card mb-4">
        <div class="card-header fw-bold">
            Stok Bahan Baku Menipis
        </div>
        <div class="card-body table-responsive">
            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Bahan</th>
                        <th>Stok</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($stokMenipis as $index => $bahan)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $bahan->nama_bahan }}</td>
                            <td>{{ $bahan->stok }}</td>
                            <td>
                                @php
                                    $color = 'success';
                                    if ($bahan->stok <= 5) $color = 'danger';
                                    elseif ($bahan->stok <= 10) $color = 'warning';
                                @endphp
                                <span class="badge bg-{{ $color }}">
                                    {{ $bahan->stok <= 5 ? 'Kritis' : ($bahan->stok <= 10 ? 'Hati-hati' : 'Aman') }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // =========================
    // Chart Pemasukan 7 Hari
    // =========================
    const pemasukanCtx = document.getElementById('pemasukanChart').getContext('2d');
    new Chart(pemasukanCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($chart->pluck('tanggal')) !!},
            datasets: [{
                label: 'Pemasukan',
                data: {!! json_encode($chart->pluck('pemasukan')) !!},
                borderColor: 'rgba(75, 192, 192, 1)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                tension: 0.3,
                fill: true
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: true },
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // =========================
    // Chart Top Menu 5 Terlaris
    // =========================
    const topMenuCtx = document.getElementById('topMenuChart').getContext('2d');
    new Chart(topMenuCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($topMenus->map(fn($m) => $m->menu->nama_menu)) !!},
            datasets: [{
                label: 'Jumlah Terjual',
                data: {!! json_encode($topMenus->pluck('total_qty')) !!},
                backgroundColor: 'rgba(54, 162, 235, 0.7)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
</script>
@endsection
