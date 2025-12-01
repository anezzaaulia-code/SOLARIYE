@extends('layouts.admin')

@section('content')
<div class="container">

    <h2 class="mb-4">Detail Pembelian Bahan</h2>

    {{-- Info Pembelian --}}
    <div class="card mb-4">
        <div class="card-body">
            <p><strong>Tanggal:</strong> {{ $pembelian->tanggal }}</p>
            <p><strong>Supplier:</strong> {{ $pembelian->supplier->nama_supplier ?? '-' }}</p>
            <p><strong>Keterangan:</strong> {{ $pembelian->keterangan ?? '-' }}</p>
            <p><strong>Dibuat Oleh:</strong> {{ $pembelian->creator->name ?? '-' }}</p>
            <hr>
            <p><strong>Total Harga:</strong> Rp {{ number_format($pembelian->total_harga, 0, ',', '.') }}</p>
        </div>
    </div>

    {{-- Detail Pembelian Bahan --}}
    <div class="card">
        <div class="card-header">
            <strong>Rincian Pembelian</strong>
        </div>
        <div class="card-body p-0">
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Bahan</th>
                        <th>Qty</th>
                        <th>Harga Satuan</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pembelian->detailPembelian as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->bahan->nama_bahan ?? '-' }}</td>
                            <td>{{ $item->qty }}</td>
                            <td>Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted p-3">Tidak ada detail pembelian</td>
                        </tr>
                    @endforelse
                </tbody>
                @if($pembelian->detailPembelian->count() > 0)
                    <tfoot>
                        <tr>
                            <th colspan="4" class="text-end">Total</th>
                            <th>Rp {{ number_format($pembelian->total_harga, 0, ',', '.') }}</th>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>

    <a href="{{ route('pembelian.index') }}" class="btn btn-secondary mt-4">Kembali</a>

</div>
@endsection
