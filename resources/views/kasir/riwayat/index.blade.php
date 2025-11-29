@extends('layouts.kasir')

@section('content')

<div class="container">

    <h2 class="mb-4 fw-bold">Riwayat Transaksi</h2>

    <div class="card shadow-sm p-3">

        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Kode</th>
                    <th>Pelanggan</th>
                    <th>Total</th>
                    <th>Metode</th>
                    <th>Tanggal</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($riwayat as $r)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $r->kode_transaksi }}</td>
                    <td>{{ $r->pelanggan ?? '-' }}</td>
                    <td>Rp {{ number_format($r->total) }}</td>
                    <td>{{ strtoupper($r->metode) }}</td>
                    <td>{{ $r->created_at->format('d-m-Y H:i') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted">
                        Belum ada transaksi.
                    </td>
                </tr>
                @endforelse
            </tbody>

        </table>

    </div>

</div>

@endsection
