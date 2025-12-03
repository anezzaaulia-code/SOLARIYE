@extends('layouts.admin')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0 text-gray-800 fw-bold">Dashboard Overview</h3>
        <span class="text-muted">{{ now()->translatedFormat('l, d F Y') }}</span>
    </div>

    {{-- ROW CARDS --}}
    <div class="row mb-4">

        {{-- Pemasukan Hari Ini --}}
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card text-white bg-success h-100 shadow">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-1">Pemasukan Hari Ini</h6>
                            <h4 class="fw-bold mb-0">Rp {{ number_format($pemasukanHari, 0, ',', '.') }}</h4>
                        </div>
                        <i class="bi bi-graph-up-arrow fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Pengeluaran Hari Ini --}}
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card text-white bg-danger h-100 shadow">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-1">Pengeluaran Hari Ini</h6>
                            <h4 class="fw-bold mb-0">Rp {{ number_format($pengeluaranHari, 0, ',', '.') }}</h4>
                        </div>
                        <i class="bi bi-graph-down-arrow fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Stok Menipis --}}
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card text-white bg-warning h-100 shadow">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-1">Stok Menipis</h6>
                            <h4 class="fw-bold mb-0">{{ $stokMenipis->count() }} <span class="fs-6 fw-normal">Item</span></h4>
                        </div>
                        <i class="bi bi-exclamation-triangle fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Menu Terlaris --}}
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card text-white bg-primary h-100 shadow">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-1">Terjual Minggu Ini</h6>
                            {{-- Mengambil total_qty dari semua item di collection --}}
                            <h4 class="fw-bold mb-0">{{ $topMenus->sum('total_qty') }} <span class="fs-6 fw-normal">Porsi</span></h4>
                        </div>
                        <i class="bi bi-trophy fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ROW CHARTS --}}
    <div class="row mb-4">

        {{-- Chart Pemasukan & Pengeluaran 7 Hari --}}
        <div class="col-md-8 mb-3">
            <div class="card h-100 shadow-sm">
                <div class="card-header bg-white fw-bold py-3">
                    <i class="bi bi-bar-chart-line"></i> Grafik Keuangan 7 Hari Terakhir
                </div>
                <div class="card-body">
                    <canvas id="keuanganChart" height="120"></canvas>
                </div>
            </div>
        </div>

        {{-- Chart Top Menu --}}
        <div class="col-md-4 mb-3">
            <div class="card h-100 shadow-sm">
                <div class="card-header bg-white fw-bold py-3">
                    <i class="bi bi-pie-chart"></i> 5 Menu Terlaris
                </div>
                <div class="card-body">
                    <canvas id="topMenuChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabel Stok Menipis --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-white fw-bold py-3 text-danger">
            <i class="bi bi-box-seam"></i> Peringatan Stok Bahan Baku
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-hover table-striped mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">No</th>
                        <th>Nama Bahan</th>
                        <th>Stok Saat Ini</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stokMenipis as $index => $bahan)
                        <tr>
                            <td class="ps-4">{{ $index + 1 }}</td>
                            <td class="fw-bold">{{ $bahan->nama_bahan }}</td>
                            <td>
                                <span class="fw-bold {{ $bahan->stok <= 0 ? 'text-danger' : 'text-dark' }}">
                                    {{ $bahan->stok }} {{ $bahan->satuan }}
                                </span>
                            </td>
                            <td>
                                @php
                                    // Logika status sederhana (fallback jika status_warna tidak ada)
                                    $color = 'success';
                                    $text = 'Aman';
                                    
                                    if ($bahan->stok <= 0) {
                                        $color = 'danger'; $text = 'Habis';
                                    } elseif ($bahan->stok <= $bahan->batas_merah) {
                                        $color = 'danger'; $text = 'Kritis';
                                    } elseif ($bahan->stok <= $bahan->batas_kuning) {
                                        $color = 'warning text-dark'; $text = 'Menipis';
                                    }
                                @endphp
                                <span class="badge bg-{{ $color }}">
                                    {{ $text }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('pembelian.create') }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-cart-plus"></i> Restock
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">
                                <i class="bi bi-check-circle text-success fs-4 d-block mb-2"></i>
                                Stok aman! Tidak ada bahan yang menipis.
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
    // ============================================
    // 1. CHART KEUANGAN (PEMASUKAN vs PENGELUARAN)
    // ============================================
    // Data diambil dari Controller (aliases: tgl, total_masuk, total_keluar)
    const keuanganCtx = document.getElementById('keuanganChart').getContext('2d');
    
    // Siapkan array data dari PHP
    const labels = {!! json_encode($chart->pluck('tgl')) !!};
    const dataPemasukan = {!! json_encode($chart->pluck('total_masuk')) !!};
    const dataPengeluaran = {!! json_encode($chart->pluck('total_keluar')) !!};

    new Chart(keuanganCtx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Pemasukan',
                    data: dataPemasukan,
                    borderColor: '#1cc88a', // Hijau
                    backgroundColor: 'rgba(28, 200, 138, 0.1)',
                    tension: 0.3,
                    fill: true
                },
                {
                    label: 'Pengeluaran',
                    data: dataPengeluaran,
                    borderColor: '#e74a3b', // Merah
                    backgroundColor: 'rgba(231, 74, 59, 0.1)',
                    tension: 0.3,
                    fill: true
                }
            ]
        },
        options: {
            responsive: true,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            plugins: {
                legend: { position: 'top' },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) {
                                label += ': ';
                            }
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
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                        }
                    }
                }
            }
        }
    });

    // =========================
    // 2. CHART TOP MENU
    // =========================
    // Menggunakan kolom 'nama' dari relasi menu
    const topMenuCtx = document.getElementById('topMenuChart').getContext('2d');
    
    // Handle jika menu terhapus (optional chaining)
    const menuLabels = {!! json_encode($topMenus->map(fn($m) => $m->menu->nama ?? 'Menu Terhapus')) !!};
    const menuData = {!! json_encode($topMenus->pluck('total_qty')) !!};

    new Chart(topMenuCtx, {
        type: 'doughnut', // Ganti jadi Doughnut biar variatif
        data: {
            labels: menuLabels,
            datasets: [{
                data: menuData,
                backgroundColor: [
                    '#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b'
                ],
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom' },
            }
        }
    });
</script>
@endsection