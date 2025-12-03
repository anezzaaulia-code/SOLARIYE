@extends('layouts.admin')

@section('content')

<div class="container-fluid mt-3">

    {{-- ========================== --}}
    {{-- ROW: STATISTIC CARDS       --}}
    {{-- ========================== --}}
    <div class="row g-3">

        {{-- Pemasukan --}}
        <div class="col-md-3 col-sm-6">
            <div class="card shadow-sm border-start-success">
                <div class="card-body">
                    <h6 class="text-muted">Pemasukan Hari Ini</h6>
                    <h3 class="text-success fw-bold">
                        Rp {{ number_format($pemasukanHari, 0, ',', '.') }}
                    </h3>
                </div>
            </div>
        </div>

        {{-- Pengeluaran --}}
        <div class="col-md-3 col-sm-6">
            <div class="card shadow-sm border-start-danger">
                <div class="card-body">
                    <h6 class="text-muted">Pengeluaran Hari Ini</h6>
                    <h3 class="text-danger fw-bold">
                        Rp {{ number_format($pengeluaranHari, 0, ',', '.') }}
                    </h3>
                </div>
            </div>
        </div>

        {{-- Stok Menipis --}}
        <div class="col-md-3 col-sm-6">
            <div class="card shadow-sm border-start-warning">
                <div class="card-body">
                    <h6 class="text-muted">Stok Menipis</h6>
                    <h3 class="text-warning fw-bold">{{ $stokMenipis->count() }}</h3>
                </div>
            </div>
        </div>

        {{-- Menu Terlaris --}}
        <div class="col-md-3 col-sm-6">
            <div class="card shadow-sm border-start-primary">
                <div class="card-body">
                    <h6 class="text-muted">Menu Terlaris Minggu Ini</h6>
                    <h3 class="text-primary fw-bold">{{ $topMenus->sum('total_qty') }}</h3>
                </div>
            </div>
        </div>

    </div>

    {{-- SPACING --}}
    <div class="my-4"></div>

    {{-- ========================== --}}
    {{-- ROW: CHARTS                --}}
    {{-- ========================== --}}
    <div class="row g-3">

        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white fw-bold">
                    Pemasukan 7 Hari Terakhir
                </div>
                <div class="card-body">
                    <canvas id="pemasukanChart" style="height:260px"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white fw-bold">
                    5 Menu Terlaris Minggu Ini
                </div>
                <div class="card-body">
                    <canvas id="topMenuChart" style="height:260px"></canvas>
                </div>
            </div>
        </div>

    </div>

    {{-- SPACING --}}
    <div class="my-4"></div>

    {{-- ========================== --}}
    {{-- TABEL: STOK MENIPIS        --}}
    {{-- ========================== --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white fw-bold">
            Stok Bahan Baku Menipis
        </div>

        <div class="card-body table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Nama Bahan</th>
                        <th>Stok</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($stokMenipis as $index => $bahan)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $bahan->nama_bahan }}</td>
                            <td>{{ $bahan->stok }}</td>
                            <td>
                                @php
                                    $color = $bahan->stok <= 5 ? 'danger' : 'warning';
                                    $text = $bahan->stok <= 5 ? 'Kritis' : 'Hati-hati';
                                @endphp
                                <span class="badge bg-{{ $color }}">{{ $text }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">Tidak ada bahan menipis.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

@endsection



@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // ================================
    // CHART PEMASUKAN 7 HARI
    // ================================
    const pemasukanCtx = document.getElementById('pemasukanChart').getContext('2d');
    new Chart(pemasukanCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($chart->pluck('tanggal')) !!},
            datasets: [{
                label: 'Pemasukan',
                data: {!! json_encode($chart->pluck('pemasukan')) !!},
                borderColor: '#28a745',
                backgroundColor: 'rgba(40,167,69,0.2)',
                borderWidth: 2,
                tension: 0.3,
                fill: true
            }]
        },
        options: {
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    // ================================
    // CHART 5 MENU TERLARIS
    // ================================
    const topMenuCtx = document.getElementById('topMenuChart').getContext('2d');
    new Chart(topMenuCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($topMenus->map(fn($m) => $m->menu->nama_menu)) !!},
            datasets: [{
                data: {!! json_encode($topMenus->pluck('total_qty')) !!},
                backgroundColor: '#007bff',
            }]
        },
        options: {
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
</script>

@endsection
