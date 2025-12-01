@extends('layouts.admin')

@section('content')
<div class="container mt-4">

    <h4>Edit Pembelian Bahan</h4>
    {{-- Pesan Error --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Terjadi kesalahan:</strong>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Pesan Error dari Controller (gagal menyimpan dll) --}}
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    {{-- Pesan sukses --}}
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif


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
                        @foreach ($detail as $i => $d)
                        <tr>
                            <td>
                                <select name="bahan_id[]" class="form-select" required>
                                    @foreach ($bahan as $b)
                                        <option value="{{ $b->id }}" {{ $b->id == $d->bahan_id ? 'selected' : '' }}>
                                            {{ $b->nama_bahan }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>

                            <td>
                                <input type="number" name="qty[]" class="form-control" value="{{ $d->qty }}" min="1" required>
                            </td>

                            <td>
                                <input type="number" name="harga_satuan[]" class="form-control" value="{{ $d->harga_satuan }}" min="1" required>
                            </td>

                            <td class="subtotal-col">
                                Rp {{ number_format($d->qty * $d->harga_satuan) }}
                            </td>
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
