@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Edit Pembelian #{{ $pembelian->id }}</h1>
    <a href="{{ route('pembelian.index') }}" class="btn btn-secondary mb-3">Kembali</a>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('pembelian.update', $pembelian->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="supplier_id" class="form-label">Supplier</label>
            <select name="supplier_id" id="supplier_id" class="form-control">
                @foreach($suppliers as $supplier)
                    <option value="{{ $supplier->id }}" {{ $pembelian->supplier_id == $supplier->id ? 'selected' : '' }}>
                        {{ $supplier->nama_supplier }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="tanggal" class="form-label">Tanggal</label>
            <input type="date" name="tanggal" id="tanggal" class="form-control" value="{{ $pembelian->tanggal }}">
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
@endsection
