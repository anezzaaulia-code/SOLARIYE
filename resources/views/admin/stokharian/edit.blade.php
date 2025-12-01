@extends('layouts.admin')

@section('content')
<div class="card shadow">
    <div class="card-header">
        <h4>Edit Stok Harian</h4>
    </div>

    <div class="card-body">

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('stokharian.update', $stok->id) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- Bahan --}}
            <div class="mb-3">
                <label class="form-label">Bahan</label>
                <input type="text" 
                       class="form-control" 
                       value="{{ $stok->bahan->nama_bahan ?? 'Tidak ditemukan' }}" 
                       readonly>
            </div>

            {{-- Stok Awal --}}
            <div class="mb-3">
                <label class="form-label">Stok Awal</label>
                <input type="number" 
                       name="stok_awal" 
                       class="form-control"
                       value="{{ $stok->stok_awal }}" 
                       readonly>
            </div>

            {{-- Stok Akhir --}}
            <div class="mb-3">
                <label class="form-label">Stok Akhir</label>
                <input type="number" 
                       name="stok_akhir" 
                       class="form-control"
                       value="{{ $stok->stok_akhir }}" 
                       required>
            </div>

            <button class="btn btn-primary mt-3">Update</button>
        </form>

    </div>
</div>
@endsection
