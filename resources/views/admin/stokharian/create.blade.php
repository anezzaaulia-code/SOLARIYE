@extends('layouts.admin')

@section('content')
<div class="container-fluid p-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-dark m-0">Input Stok Harian</h4>
            <p class="text-muted small m-0">Catat stok awal dan akhir bahan baku hari ini.</p>
        </div>
        <a href="{{ route('stokharian.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0 small">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('stokharian.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-uppercase">Pilih Bahan Baku <span class="text-danger">*</span></label>
                            <select name="bahan_id" class="form-select @error('bahan_id') is-invalid @enderror" required>
                                <option value="" selected disabled>-- Pilih Bahan --</option>
                                @foreach ($bahan as $b)
                                    <option value="{{ $b->id }}" {{ old('bahan_id') == $b->id ? 'selected' : '' }}>
                                        {{ $b->nama_bahan }} (Total di Gudang: {{ $b->stok }} {{ $b->satuan }})
                                    </option>
                                @endforeach
                            </select>
                            @error('bahan_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-uppercase">Tanggal <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal" class="form-control @error('tanggal') is-invalid @enderror" 
                                   value="{{ old('tanggal', date('Y-m-d')) }}" required>
                            @error('tanggal') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-uppercase text-success">Stok Awal <span class="text-danger">*</span></label>
                                <input type="number" name="stok_awal" class="form-control border-success @error('stok_awal') is-invalid @enderror" 
                                       value="{{ old('stok_awal') }}" min="0" placeholder="0" required>
                                <div class="form-text small">Jumlah fisik yang dibawa/tersedia hari ini.</div>
                                @error('stok_awal') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-uppercase text-primary">Stok Akhir <span class="text-danger">*</span></label>
                                <input type="number" name="stok_akhir" class="form-control border-primary @error('stok_akhir') is-invalid @enderror" 
                                       value="{{ old('stok_akhir') }}" min="0" placeholder="0" required>
                                <div class="form-text small">Sisa fisik saat tutup toko.</div>
                                @error('stok_akhir') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-end gap-2">
                            <button type="reset" class="btn btn-light border px-4">Reset</button>
                            <button type="submit" class="btn btn-primary fw-bold px-4">
                                <i class="bi bi-save me-1"></i> Simpan Data
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection