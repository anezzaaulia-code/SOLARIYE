@extends('layouts.app')

@section('content')
<div class="container">

    <h3 class="mb-4">
        Tambah {{ $jenis == 'pengeluaran' ? 'Pengeluaran' : 'Pendapatan' }}
    </h3>

    <form action="{{ route('keuangan.store') }}" method="POST">
        @csrf

        <input type="hidden" name="jenis" value="{{ $jenis }}">

        <div class="mb-3">
            <label>Tanggal</label>
            <input type="date" name="tanggal" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Nominal</label>
            <input type="number" name="nominal" class="form-control" required>
        </div>

        @if($jenis == 'pengeluaran')
            <div class="mb-3">
                <label>Sumber</label>
                <input type="text" name="sumber" class="form-control">
            </div>
        @endif

        <div class="mb-3">
            <label>Keterangan</label>
            <textarea name="keterangan" class="form-control"></textarea>
        </div>

        <button type="submit" class="btn btn-success">Simpan</button>
        <a href="{{ $jenis == 'pengeluaran' ? route('keuangan.pengeluaran') : route('keuangan.pendapatan') }}"
    class="btn btn-secondary">Kembali</a>
    </form>

</div>
@endsection
