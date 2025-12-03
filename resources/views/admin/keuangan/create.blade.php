@extends('layouts.admin')

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

        {{-- Jika pengeluaran: pilih sumber --}}
        @if($jenis == 'pengeluaran')
            <div class="mb-3">
                <label>Sumber</label>
                <select name="sumber" class="form-control" required>
                    <option value="suppliers">Supplier</option>
                    <option value="lainnya">Lainnya</option>
                </select>
            </div>
        @endif

        <div class="mb-3">
            <label>Keterangan</label>
            <textarea name="keterangan" class="form-control"></textarea>
        </div>

        <button type="submit" class="btn btn-success">Simpan</button>

        <a href="{{ $jenis == 'pengeluaran' ? route('pengeluaran.index') : route('pendapatan.index') }}"
           class="btn btn-secondary">Kembali</a>

    </form>

</div>
@endsection
