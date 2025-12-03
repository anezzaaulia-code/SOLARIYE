@extends('layouts.admin')

@section('content')
<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Detail Transaksi: <span class="text-primary">{{ $pesanan->invoice }}</span></h4>
        <a href="{{ route('pesanan.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
    </div>

    <div class="row">
        {{-- Info Header --}}
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-light fw-bold">Informasi Pesanan</div>
                <div class="card-body">
                    <table class="table table-borderless table-sm">
                        <tr>
                            <td class="text-muted">Tanggal</td>
                            <td class="fw-bold text-end">{{ $pesanan->created_at->format('d M Y H:i') }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Kasir</td>
                            <td class="fw-bold text-end">{{ $pesanan->kasir->nama ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Pelanggan</td>
                            <td class="fw-bold text-end">{{ $pesanan->pelanggan }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Metode Bayar</td>
                            <td class="fw-bold text-end">{{ strtoupper($pesanan->metode) }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Status</td>
                            <td class="text-end">
                                <span class="badge bg-success">SELESAI</span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        {{-- Detail Menu --}}
        <div class="col-md-8 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-primary text-white">Rincian Menu</div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Menu</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-end">Harga</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pesanan->detail as $item)
                                <tr>
                                    <td>
                                        <strong>{{ $item->nama_menu }}</strong><br>
                                        <small class="text-muted">{{ $item->menu->kategori->nama ?? '' }}</small>
                                    </td>
                                    <td class="text-center">{{ $item->jumlah }}</td>
                                    <td class="text-end">Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                                    <td class="text-end fw-bold">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-dark">
                                <tr>
                                    <td colspan="3" class="text-end">TOTAL TAGIHAN</td>
                                    <td class="text-end fs-5">Rp {{ number_format($pesanan->total, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end text-muted small">DIBAYAR</td>
                                    <td class="text-end text-muted small">Rp {{ number_format($pesanan->bayar, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end text-muted small">KEMBALI</td>
                                    <td class="text-end text-muted small">Rp {{ number_format($pesanan->kembali, 0, ',', '.') }}</td>
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