@extends('layouts.admin')

@section('content')
<div class="container mt-4">

    <div class="card shadow-sm" style="max-width: 600px; margin: 0 auto;">
        <div class="card-header bg-warning text-dark">
            <h5 class="mb-0">Edit Stok Harian</h5>
        </div>

        <div class="card-body">
            <form action="{{ route('stokharian.update', $stok->id) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Info Bahan (Readonly) --}}
                <div class="mb-3">
                    <label class="form-label">Nama Bahan</label>
                    <input type="text" class="form-control bg-light" 
                           value="{{ $stok->bahan->nama_bahan ?? 'Bahan Terhapus' }}" readonly>
                </div>

                <div class="row">
                    {{-- Stok Awal --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Stok Awal</label>
                        <input type="number" name="stok_awal" class="form-control bg-light"
                               value="{{ $stok->stok_awal }}" readonly>
                        <small class="text-muted">Tidak dapat diubah.</small>
                    </div>

                    {{-- Stok Akhir --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Stok Akhir (Revisi)</label>
                        <input type="number" name="stok_akhir" class="form-control @error('stok_akhir') is-invalid @enderror"
                               value="{{ old('stok_akhir', $stok->stok_akhir) }}" required>
                        @error('stok_akhir')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('stokharian.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Update Data</button>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection