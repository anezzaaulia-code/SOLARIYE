@extends('layouts.admin')

@section('content')
<div class="container mt-4">

    <h4>Edit Pembelian Bahan</h4>

    <form action="{{ route('pembelian.update', $pembelian->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="card shadow mt-3">
            <div class="card-body">

                {{-- Tanggal --}}
                <div class="mb-3">
                    <label>Tanggal Pembelian</label>
                    <input type="date" name="tanggal" class="form-control"
                           value="{{ $pembelian->tanggal }}" required>
                </div>

                {{-- Supplier --}}
                <div class="mb-3">
                    <label>Supplier</label>
                    <select name="supplier_id" class="form-control">
                        @foreach ($suppliers as $s)
                            <option value="{{ $s->id }}"
                                {{ $s->id == $pembelian->supplier_id ? 'selected' : '' }}>
                                {{ $s->nama_supplier }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <hr>
                <h5>Detail Pembelian</h5>

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Bahan</th>
                            <th>Qty</th>
                            <th>Harga Satuan</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($detail as $d)
                        <tr>
                            <td>{{ $d->bahan->nama_bahan }}</td>
                            <td>{{ $d->qty }}</td>
                            <td>Rp {{ number_format($d->harga_satuan) }}</td>
                            <td>Rp {{ number_format($d->subtotal) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="mt-4 text-end">
                    <button class="btn btn-primary">Update Pembelian</button>
                </div>

            </div>
        </div>

    </form>
</div>
@endsection
