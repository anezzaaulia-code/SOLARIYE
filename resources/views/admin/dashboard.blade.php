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
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <small class="text-muted text-uppercase fw-bold">Pemasukan Hari Ini</small>
                        <div class="bg-success bg-opacity-10 text-success p-2 rounded-circle">
                            <i class="bi bi-wallet2"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold text-success mb-0">
                        Rp {{ number_format($pemasukanHari, 0, ',', '.') }}
                    </h3>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm h-100 border-start border-4 border-danger">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <small class="text-muted text-uppercase fw-bold">Pengeluaran Hari Ini</small>
                        <div class="bg-danger bg-opacity-10 text-danger p-2 rounded-circle">
                            <i class="bi bi-cash-stack"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold text-danger mb-0">
                        Rp {{ number_format($pengeluaranHari, 0, ',', '.') }}
                    </h3>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm h-100 border-start border-4 border-warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <small class="text-muted text-uppercase fw-bold">Stok Menipis</small>
                        <div class="bg-warning bg-opacity-10 text-warning p-2 rounded-circle">
                            <i class="bi bi-exclamation-triangle"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold text-dark mb-0">{{ $stokMenipis->count() }} <span class="fs-6 text-muted fw-normal">Item</span></h3>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm h-100 border-start border-4 border-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <small class="text-muted text-uppercase fw-bold">Terjual Minggu Ini</small>
                        <div class="bg-primary bg-opacity-10 text-primary p-2 rounded-circle">
                            <i class="bi bi-graph-up-arrow"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold text-primary mb-0">{{ $topMenus->sum('total_qty') }} <span class="fs-6 text-muted fw-normal">Porsi</span></h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="m-0 fw-bold text-dark"><i class="bi bi-bar-chart-line me-2"></i>Tren Pemasukan (7 Hari Terakhir)</h6>
                </div>
                <div class="card-body">
                    <canvas id="pemasukanChart" style="max-height: 300px;"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="m-0 fw-bold text-dark"><i class="bi bi-pie-chart me-2"></i>Top 5 Menu Minggu Ini</h6>
                </div>
                <div class="card-body">
                    <canvas id="topMenuChart" style="max-height: 300px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-secondary small text-uppercase">
                    <tr>
                        <th class="px-4 py-3" width="5%">No</th>
                        <th class="py-3">Nama Bahan</th>
                        <th class="py-3 text-center">Sisa Stok</th>
                        <th class="py-3 text-center">Status</th>
                        <th class="py-3 text-end px-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stokMenipis as $bahan)
                    <tr>
                        <td class="text-center text-muted">{{ $loop->iteration }}</td>
                        <td class="fw-bold text-dark">{{ $bahan->nama_bahan }}</td>
                        <td class="text-center fs-5 fw-bold text-danger">{{ $bahan->stok }} <span class="fs-6 text-muted fw-normal">{{ $bahan->satuan }}</span></td>
                        <td class="text-center">
                            @if($bahan->stok <= 5)
                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger rounded-pill px-3">HABIS</span>
                            @else
                                <span class="badge bg-warning bg-opacity-10 text-warning border border-warning rounded-pill px-3 text-dark">MENIPIS</span>
                            @endif
                        </td>
                        <td class="text-end px-4">
                            <a href="{{ route('pembelian.create') }}" class="btn btn-sm btn-primary">Update Stok</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="bi bi-check-circle fs-1 d-block mb-2 text-success opacity-50"></i>
                            Stok aman. Tidak ada bahan yang menipis.
                        </td>
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
    // CHART PEMASUKAN 7 HARI (Line Chart)
    // ================================
    const pemasukanCtx = document.getElementById('pemasukanChart').getContext('2d');
    
    // Siapkan data (hindari error jika data kosong)
    const labelTanggal = {!! json_encode($chart->pluck('tanggal')) !!} || [];
    const dataPemasukan = {!! json_encode($chart->pluck('pemasukan')) !!} || [];

    new Chart(pemasukanCtx, {
        type: 'line',
        data: {
            labels: labelTanggal,
            datasets: [{
                label: 'Total Pemasukan (Rp)',
                data: dataPemasukan,
                borderColor: '#198754', // Bootstrap Success Color
                backgroundColor: 'rgba(25, 135, 84, 0.1)',
                borderWidth: 2,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#198754',
                pointRadius: 4,
                tension: 0.4, // Membuat garis melengkung halus
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) { label += ': '; }
                            if (context.parsed.y !== null) {
                                label += new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(context.parsed.y);
                            }
                            return label;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { borderDash: [2, 4] },
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value / 1000 + 'k';
                        }
                    }
                },
                x: { grid: { display: false } }
            }
        }
    });

    // ================================
    // CHART MENU TERLARIS (Doughnut Chart)
    // ================================
    const topMenuCtx = document.getElementById('topMenuChart').getContext('2d');
    
    // Data Menu
    // Perbaikan: Ambil nama menu dari relasi, bukan langsung field (sesuaikan dgn model kamu)
    // Jika di PesananDetail relasinya bernama 'menu', dan kolom nama di tabel menu adalah 'nama'
    const labelMenu = {!! json_encode($topMenus->map(fn($m) => $m->menu->nama ?? 'Menu Terhapus')) !!};
    const dataQty = {!! json_encode($topMenus->pluck('total_qty')) !!};

    new Chart(topMenuCtx, {
        type: 'doughnut',
        data: {
            labels: labelMenu,
            datasets: [{
                data: dataQty,
                backgroundColor: [
                    '#0d6efd', '#6610f2', '#6f42c1', '#d63384', '#dc3545'
                ],
                borderWidth: 0,
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { boxWidth: 12, font: { size: 11 } }
                }
            },
            cutout: '70%' // Membuat lubang tengah lebih besar
        }
    });
</script>
@endsection