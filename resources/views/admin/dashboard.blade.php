@extends('layouts.admin')

@section('content')

<div class="container-fluid p-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-dark m-0">Dashboard Ringkasan</h4>
            <p class="text-muted small m-0">Pantau performa toko hari ini secara real-time.</p>
        </div>
        <div class="text-muted small">
            <i class="bi bi-calendar-event me-1"></i> {{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y') }}
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm h-100 border-start border-4 border-success">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted text-uppercase fw-bold">Pemasukan Hari Ini</small>
                        <h3 class="fw-bold text-success mb-0 mt-1">
                            Rp {{ number_format($pemasukanHari, 0, ',', '.') }}
                        </h3>
                    </div>
                    <i class="bi bi-wallet2 text-success opacity-50" style="font-size: 2.5rem;"></i>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm h-100 border-start border-4 border-danger">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted text-uppercase fw-bold">Pengeluaran Hari Ini</small>
                        <h3 class="fw-bold text-danger mb-0 mt-1">
                            Rp {{ number_format($pengeluaranHari, 0, ',', '.') }}
                        </h3>
                    </div>
                    <i class="bi bi-cash-stack text-danger opacity-50" style="font-size: 2.5rem;"></i>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm h-100 border-start border-4 border-warning">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted text-uppercase fw-bold">Stok Menipis</small>
                        <h3 class="fw-bold text-dark mb-0 mt-1">
                            {{ $stokMenipis->count() }} <span class="fs-6 text-muted fw-normal">Item</span>
                        </h3>
                    </div>
                    <i class="bi bi-exclamation-triangle text-warning opacity-50" style="font-size: 2.5rem;"></i>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm h-100 border-start border-4 border-primary">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted text-uppercase fw-bold">Terjual Minggu Ini</small>
                        <h3 class="fw-bold text-primary mb-0 mt-1">
                            {{ $topMenus->sum('total_qty') }} <span class="fs-6 text-muted fw-normal">Porsi</span>
                        </h3>
                    </div>
                    <i class="bi bi-graph-up-arrow text-primary opacity-50" style="font-size: 2.5rem;"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="m-0 fw-bold text-success"><i class="bi bi-graph-up-arrow me-2"></i>Tren Pemasukan (7 Hari)</h6>
                </div>
                <div class="card-body">
                    <canvas id="pemasukanChart" style="max-height: 250px;"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="m-0 fw-bold text-danger"><i class="bi bi-graph-down-arrow me-2"></i>Tren Pengeluaran (7 Hari)</h6>
                </div>
                <div class="card-body">
                    <canvas id="pengeluaranChart" style="max-height: 250px;"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="m-0 fw-bold text-primary"><i class="bi bi-pie-chart me-2"></i>Top 5 Menu Minggu Ini</h6>
                </div>
                <div class="card-body">
                    <canvas id="topMenuChart" style="max-height: 250px;"></canvas>
                </div>
            </div>
        </div>
    </div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3 border-bottom">
        <h6 class="m-0 fw-bold text-danger">
            <i class="bi bi-box-seam me-2"></i> Peringatan Stok Bahan Baku
        </h6>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light text-secondary small text-uppercase">
                <tr>
                    <th class="px-4 py-3" width="5%">No</th>
                    <th>Nama Bahan</th>
                    <th class="text-center">Sisa Stok</th>
                    <th class="text-center">Status</th>
                    <th class="text-end px-4">Aksi</th>
                </tr>
            </thead>

            <tbody>

            @php $no = 1; @endphp

            {{-- =========================== --}}
            {{--      STOK KRITIS (MERAH)    --}}
            {{-- =========================== --}}
            @foreach($stokKritis as $bahan)
            <tr>
                <td class="text-center text-muted">{{ $no++ }}</td>
                <td class="fw-bold text-dark">{{ $bahan->nama_bahan }}</td>
                <td class="text-center fs-5 fw-bold text-danger">
                    {{ $bahan->stok }} <span class="fs-6 text-muted">{{ $bahan->satuan }}</span>
                </td>
                <td class="text-center">
                    <span class="badge bg-danger bg-opacity-10 text-danger border border-danger px-3 rounded-pill">KRITIS</span>
                </td>
                <td class="text-end px-4">
                    <a href="{{ route('pembelian.create') }}" class="btn btn-sm btn-primary">Update Stok</a>
                </td>
            </tr>
            @endforeach


            {{-- =========================== --}}
            {{--     STOK MENIPIS (KUNING)   --}}
            {{-- =========================== --}}
            @foreach($stokMenipis as $bahan)
            <tr>
                <td class="text-center text-muted">{{ $no++ }}</td>
                <td class="fw-bold text-dark">{{ $bahan->nama_bahan }}</td>
                <td class="text-center fs-5 fw-bold text-warning">
                    {{ $bahan->stok }} <span class="fs-6 text-muted">{{ $bahan->satuan }}</span>
                </td>
                <td class="text-center">
                    <span class="badge bg-warning bg-opacity-10 text-warning border border-warning px-3 rounded-pill">MENIPIS</span>
                </td>
                <td class="text-end px-4">
                    <a href="{{ route('stokharian.create') }}" class="btn btn-sm btn-primary">Update Stok</a>
                </td>
            </tr>
            @endforeach


            {{-- =========================== --}}
            {{--       STOK AMAN (HIJAU)     --}}
            {{-- =========================== --}}
            @foreach($stokAman as $bahan)
            <tr>
                <td class="text-center text-muted">{{ $no++ }}</td>
                <td class="fw-bold text-dark">{{ $bahan->nama_bahan }}</td>
                <td class="text-center fs-5 fw-bold text-success">
                    {{ $bahan->stok }} <span class="fs-6 text-muted">{{ $bahan->satuan }}</span>
                </td>
                <td class="text-center">
                    <span class="badge bg-success bg-opacity-10 text-success border border-success px-3 rounded-pill">AMAN</span>
                </td>
                <td class="text-end px-4">
                    <a href="{{ route('stokharian.create') }}" class="btn btn-sm btn-outline-secondary">Update Stok</a>
                </td>
            </tr>
            @endforeach


            {{-- =========================== --}}
            {{--   JIKA TIDAK ADA PERINGATAN --}}
            {{-- =========================== --}}
            @if($stokKritis->isEmpty() && $stokMenipis->isEmpty())
            <tr>
                <td colspan="5" class="text-center py-5 text-muted">
                    <i class="bi bi-check-circle fs-1 text-success opacity-50"></i>
                    <p class="mt-2">Semua stok dalam kondisi aman.</p>
                </td>
            </tr>
            @endif

        </tbody>

        </table>
    </div>
</div>


@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // ================================
    // CHART PEMASUKAN
    // ================================
    const pemasukanCtx = document.getElementById('pemasukanChart').getContext('2d');
    const labelTanggal = {!! json_encode($chart->pluck('tanggal')) !!} || [];
    const dataPemasukan = {!! json_encode($chart->pluck('pemasukan')) !!} || [];

    new Chart(pemasukanCtx, {
        type: 'line',
        data: {
            labels: labelTanggal,
            datasets: [{
                label: 'Pemasukan (Rp)',
                data: dataPemasukan,
                borderColor: '#198754',
                backgroundColor: 'rgba(25, 135, 84, 0.1)',
                borderWidth: 2,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#198754',
                pointRadius: 3,
                tension: 0.3,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { borderDash: [2, 4] },
                    ticks: { callback: function(val) { return val/1000 + 'k'; } }
                },
                x: { grid: { display: false }, ticks: { font: { size: 10 } } }
            }
        }
    });

    // ================================
    // CHART PENGELUARAN
    // ================================
    const pengeluaranCtx = document.getElementById('pengeluaranChart').getContext('2d');
    const dataPengeluaran = {!! json_encode($chart->pluck('pengeluaran') ?? []) !!} || [];

    new Chart(pengeluaranCtx, {
        type: 'line',
        data: {
            labels: labelTanggal,
            datasets: [{
                label: 'Pengeluaran (Rp)',
                data: dataPengeluaran,
                borderColor: '#dc3545',
                backgroundColor: 'rgba(220, 53, 69, 0.1)',
                borderWidth: 2,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#dc3545',
                pointRadius: 3,
                tension: 0.3,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { borderDash: [2, 4] },
                    ticks: { callback: function(val) { return val/1000 + 'k'; } }
                },
                x: { grid: { display: false }, ticks: { font: { size: 10 } } }
            }
        }
    });

    // ================================
    // CHART MENU TERLARIS
    // ================================
    const topMenuCtx = document.getElementById('topMenuChart').getContext('2d');
    const labelMenu = {!! json_encode($topMenus->map(fn($m) => $m->menu->nama ?? 'Menu Terhapus')) !!};
    const dataQty = {!! json_encode($topMenus->pluck('total_qty')) !!};

    new Chart(topMenuCtx, {
        type: 'doughnut',
        data: {
            labels: labelMenu,
            datasets: [{
                data: dataQty,
                backgroundColor: ['#0d6efd', '#6610f2', '#6f42c1', '#d63384', '#dc3545'],
                borderWidth: 0,
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom', labels: { boxWidth: 10, font: { size: 10 } } }
            },
            cutout: '65%'
        }
    });
</script>
@endsection