@extends('layouts.admin')

@section('content')
<div class="card shadow">
    <div class="card-header">
        <h4>Edit Stok Harian</h4>
    </div>

    <div class="card-body">

        {{-- Error Validation --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Terjadi kesalahan:</strong>
                <ul class="mt-2 mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Success Alert --}}
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('stokharian.update', $stok->id) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- Nama Bahan --}}
            <div class="mb-3">
                <label class="form-label fw-bold">Nama Bahan</label>
                <input type="text"
                       class="form-control"
                       value="{{ $stok->bahan->nama_bahan ?? 'Tidak ditemukan' }}"
                       readonly>
            </div>

            {{-- Tanggal --}}
            <div class="mb-3">
                <label class="form-label fw-bold">Tanggal</label>
                <input type="text"
                       class="form-control"
                       value="{{ $stok->tanggal }}"
                       readonly>
            </div>

            {{-- Stok Awal --}}
            <div class="mb-3">
                <label class="form-label fw-bold">Stok Awal</label>
                <input type="number"
                       name="stok_awal"
                       class="form-control"
                       value="{{ $stok->stok_awal }}"
                       readonly>
            </div>

            {{-- Stok Akhir --}}
            <div class="mb-3">
                <label class="form-label fw-bold">Stok Akhir</label>
                <input type="number"
                       name="stok_akhir"
                       class="form-control @error('stok_akhir') is-invalid @enderror"
                       value="{{ old('stok_akhir', $stok->stok_akhir) }}"
                       min="0"
                       required>
                @error('stok_akhir')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button class="btn btn-primary mt-3">Update</button>
        </form>
    </div>
</div>
@endsection
