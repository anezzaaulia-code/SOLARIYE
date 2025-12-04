@extends('layouts.admin')

@section('content')
<div class="container-fluid p-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-dark m-0">Detail Pembelian</h4>
            <p class="text-muted small m-0">Rincian transaksi bahan baku masuk.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('pembelian.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
            <a href="{{ route('pembelian.edit', $pembelian->id) }}" class="btn btn-warning btn-sm fw-bold">
                <i class="bi bi-pencil me-1"></i> Edit
            </a>
            <button onclick="window.print()" class="btn btn-primary btn-sm">
                <i class="bi bi-printer me-1"></i> Cetak
            </button>
        </div>
    </div>

    <div class="row g-4">
        
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h6 class="fw-bold text-primary mb-3">Informasi Transaksi</h6>
                    
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span class="text-muted small text-uppercase fw-bold">Tanggal</span>
                            <span class="fw-bold text-dark">{{ \Carbon\Carbon::parse($pembelian->tanggal)->format('d M Y') }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span class="text-muted small text-uppercase fw-bold">Supplier</span>
                            <span class="fw-bold text-dark">{{ $pembelian->supplier->nama_supplier ?? '-' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span class="text-muted small text-uppercase fw-bold">Dibuat Oleh</span>
                            <span class="text-dark">{{ $pembelian->creator->name ?? 'Admin' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span class="text-muted small text-uppercase fw-bold">Total Item</span>
                            {{-- PERBAIKAN DI SINI: Menggunakan detailPembelian --}}
                            <span class="badge bg-secondary">{{ $pembelian->detailPembelian->count() }} Jenis</span>
                        </li>
                    </ul>

                    <div class="mt-4 p-3 bg-light rounded text-center border border-dashed">
                        <small class="text-muted text-uppercase fw-bold">Grand Total</small>
                        <h2 class="text-success fw-bold m-0">
                            Rp {{ number_format($pembelian->total_harga, 0, ',', '.') }}
                        </h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="m-0 fw-bold text-dark"><i class="bi bi-box-seam me-2"></i>Daftar Barang Dibeli</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-secondary small text-uppercase">
                                <tr>
                                    <th class="px-4 py-3" width="5%">No</th>
                                    <th class="py-3">Nama Bahan</th>
                                    <th class="py-3 text-center">Qty</th>
                                    <th class="py-3 text-end">Harga Satuan</th>
                                    <th class="py-3 text-end px-4">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- PERBAIKAN DI SINI: Menggunakan detailPembelian --}}
                                @forelse ($pembelian->detailPembelian as $item)
                                <tr>
                                    <td class="text-center text-muted">{{ $loop->iteration }}</td>
                                    <td>
                                        <span class="fw-bold text-dark">{{ $item->bahan->nama_bahan ?? 'Bahan Terhapus' }}</span>
                                        <div class="small text-muted">{{ $item->bahan->satuan ?? '-' }}</div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-info text-dark bg-opacity-10 border border-info px-3">
                                            {{ $item->jumlah ?? $item->qty }} 
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}
                                    </td>
                                    <td class="text-end px-4 fw-bold text-dark">
                                        Rp {{ number_format($item->subtotal ?? ($item->qty * $item->harga_satuan), 0, ',', '.') }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">
                                        <i class="bi bi-inbox fs-1 d-block mb-2 opacity-50"></i>
                                        Tidak ada detail barang.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                            <tfoot class="bg-light fw-bold">
                                <tr>
                                    <td colspan="4" class="text-end py-3 text-uppercase text-muted small">Total Pembelian</td>
                                    <td class="text-end px-4 py-3 text-success fs-6">
                                        Rp {{ number_format($pembelian->total_harga, 0, ',', '.') }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection