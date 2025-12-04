@extends('layouts.admin')

@section('content')
<div class="container-fluid p-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-dark m-0">Tambah Bahan Baku</h4>
            <p class="text-muted small m-0">Masukkan data inventory baru ke dalam sistem.</p>
        </div>
        <a href="{{ route('bahanbaku.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
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

                    <form action="{{ route('bahanbaku.store') }}" method="POST">
                        @csrf

                        <div class="row g-3 mb-3">
                            <div class="col-md-8">
                                <label class="form-label small fw-bold text-uppercase">Nama Bahan <span class="text-danger">*</span></label>
                                <input type="text" name="nama_bahan" class="form-control" placeholder="Contoh: Beras Premium" value="{{ old('nama_bahan') }}" required>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-uppercase">Satuan <span class="text-danger">*</span></label>
                                <input type="text" name="satuan" class="form-control" placeholder="Kg, Liter, Pcs..." value="{{ old('satuan') }}" required>
                            </div>
                        </div>

                        <hr class="my-4 text-muted">
                        <h6 class="fw-bold text-muted mb-3"><i class="bi bi-sliders me-1"></i> Aturan Stok (Peringatan)</h6>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-uppercase text-warning">
                                    <i class="bi bi-exclamation-triangle-fill me-1"></i> Batas Kuning
                                </label>
                                <div class="input-group">
                                    <input type="number" name="batas_kuning" class="form-control border-warning" placeholder="Contoh: 10" value="{{ old('batas_kuning') }}" required>
                                    <span class="input-group-text bg-warning bg-opacity-10 border-warning text-warning">Unit</span>
                                </div>
                                <div class="form-text small">Sistem akan memberi peringatan "Menipis" jika stok menyentuh angka ini.</div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-uppercase text-danger">
                                    <i class="bi bi-x-circle-fill me-1"></i> Batas Merah
                                </label>
                                <div class="input-group">
                                    <input type="number" name="batas_merah" class="form-control border-danger" placeholder="Contoh: 5" value="{{ old('batas_merah') }}" required>
                                    <span class="input-group-text bg-danger bg-opacity-10 border-danger text-danger">Unit</span>
                                </div>
                                <div class="form-text small">Sistem akan memberi peringatan "Kritis" jika stok di bawah angka ini.</div>
                            </div>
                        </div>

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