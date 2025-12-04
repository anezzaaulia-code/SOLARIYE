@extends('layouts.kasir')

@section('title', 'Riwayat Transaksi')

@section('content')

<div class="container-fluid py-4">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark m-0">Riwayat Transaksi</h3>
            <small class="text-muted">Pantau semua transaksi yang telah dilakukan oleh kasir.</small>
        </div>
    </div>

    {{-- KARTU INFO --}}
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100 border-start border-4 border-success">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted small mb-1 text-uppercase fw-bold">Total Pendapatan</p>
                        <h4 class="fw-bold text-success m-0">
                            Rp {{ number_format($riwayat->sum('total_harga'), 0, ',', '.') }}
                        </h4>
                    </div>
                    <div class="p-3 bg-success bg-opacity-10 text-success rounded-circle">
                        <i class="bi bi-cash-stack fs-3"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100 border-start border-4 border-primary">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted small mb-1 text-uppercase fw-bold">Jumlah Transaksi</p>
                        <h4 class="fw-bold text-primary m-0">{{ $riwayat->count() }} Transaksi</h4>
                    </div>
                    <div class="p-3 bg-primary bg-opacity-10 text-primary rounded-circle">
                        <i class="bi bi-receipt fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- TABLE --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white fw-bold text-secondary">
            <i class="bi bi-table me-2"></i>Daftar Transaksi
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-secondary small text-uppercase">
                    <tr>
                        <th class="text-center" width="5%">No</th>
                        <th width="18%">Tanggal</th>
                        <th width="18%">Nama Pembeli</th>
                        <th class="text-center" width="10%">Metode</th>
                        <th width="12%">Total Item</th>
                        <th width="15%">Total Harga</th>
                        <th width="10%" class="text-end pe-4">Detail</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($riwayat as $t)
                    <tr>
                        <td class="text-center text-muted fw-bold">{{ $loop->iteration }}</td>

                        <td>
                            <div class="fw-bold text-dark">{{ $t->created_at->format('d M Y') }}</div>
                            <small class="text-muted">
                                <i class="bi bi-clock me-1"></i>{{ $t->created_at->format('H:i') }} WIB
                            </small>
                        </td>

                        <td>
                            <div class="fw-bold">{{ $t->pelanggan ?? 'Umum' }}</div>
                            <small class="text-muted">
                                <i class="bi bi-person-badge me-1"></i>Kasir: {{ $t->kasir->name ?? '-' }}
                            </small>
                        </td>

                        <td class="text-center">
                            @if($t->metode_bayar === 'tunai')
                                <span class="badge bg-success bg-opacity-10 border border-success text-success px-3 py-2 rounded-pill">
                                    TUNAI
                                </span>
                            @else
                                <span class="badge bg-info bg-opacity-10 border border-info text-info px-3 py-2 rounded-pill">
                                    QRIS
                                </span>
                            @endif
                        </td>

                        <td class="fw-bold">
                            {{ $t->detail->sum('jumlah') }} Item
                        </td>

                        <td class="fw-bold text-primary">
                            Rp {{ number_format($t->total_harga, 0, ',', '.') }}
                        </td>

                        <td class="text-end pe-4">
                            <button class="btn btn-sm btn-outline-primary fw-bold"
                                data-bs-toggle="modal"
                                data-bs-target="#detail{{ $t->id }}">
                                Detail
                            </button>
                        </td>
                    </tr>

                    {{-- MODAL DETAIL TRANSAKSI --}}
                    <div class="modal fade" id="detail{{ $t->id }}" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">

                                <div class="modal-header bg-light">
                                    <h5 class="fw-bold">Detail Transaksi</h5>
                                    <button class="btn-close" data-bs-dismiss="modal"></button>
                                </div>

                                <div class="modal-body p-4">

                                    <div class="d-flex justify-content-between border-bottom pb-2 mb-3">
                                        <div>
                                            <small class="text-muted">Pembeli</small>
                                            <div class="fw-bold">{{ $t->pelanggan ?? 'Umum' }}</div>
                                        </div>
                                        <div class="text-end">
                                            <small class="text-muted">Waktu</small>
                                            <div class="fw-bold">{{ $t->created_at->format('d/m/Y H:i') }}</div>
                                        </div>
                                    </div>

                                    <div class="bg-light border rounded p-3 mb-3">
                                        <h6 class="text-muted small mb-2">Item Dibeli</h6>

                                        @foreach ($t->detail as $item)
                                        <div class="d-flex justify-content-between mb-2">
                                            <div>
                                                <div class="fw-bold">{{ $item->menu->nama ?? 'Item dihapus' }}</div>
                                                <small class="text-muted">
                                                    {{ $item->jumlah }} x Rp {{ number_format($item->harga, 0, ',', '.') }}
                                                </small>
                                            </div>
                                            <div class="fw-bold">
                                                Rp {{ number_format($item->harga * $item->jumlah, 0, ',', '.') }}
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>

                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="text-muted">Metode Pembayaran</span>
                                        <span class="fw-bold text-uppercase">{{ $t->metode_bayar }}</span>
                                    </div>

                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="text-muted">Jumlah Bayar</span>
                                        <span class="fw-bold">Rp {{ number_format($t->bayar, 0, ',', '.') }}</span>
                                    </div>

                                    <hr>

                                    <div class="d-flex justify-content-between">
                                        <span class="fw-bold fs-5">TOTAL</span>
                                        <span class="fw-bold fs-4 text-primary">
                                            Rp {{ number_format($t->total_harga, 0, ',', '.') }}
                                        </span>
                                    </div>

                                </div>

                                <div class="modal-footer">
                                    <button class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                </div>

                            </div>
                        </div>
                    </div>

                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                            Belum ada transaksi.
                        </td>
                    </tr>
                    @endforelse
                </tbody>

            </table>
        </div>
    </div>

</div>

@endsection
