@extends('layouts.admin')

@section('content')
<div class="container-fluid p-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-dark m-0">Edit Bahan Baku</h4>
            <p class="text-muted small m-0">Perbarui informasi inventory dan batas peringatan.</p>
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

                    <form action="{{ route('bahanbaku.update', $bahan->id) }}" method="POST">
                        @csrf 
                        @method('PUT')

                        <div class="row g-3 mb-3">
                            <div class="col-md-8">
                                <label class="form-label small fw-bold text-uppercase">Nama Bahan <span class="text-danger">*</span></label>
                                <input type="text" name="nama_bahan" class="form-control" 
                                       value="{{ old('nama_bahan', $bahan->nama_bahan) }}" 
                                       placeholder="Contoh: Tepung Terigu" required>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-uppercase">Satuan <span class="text-danger">*</span></label>
                                <input type="text" name="satuan" class="form-control" 
                                       value="{{ old('satuan', $bahan->satuan) }}" 
                                       placeholder="Kg, Liter, Pcs" required>
                            </div>
                        </div>

                        <hr class="my-4 text-muted">
                        <h6 class="fw-bold text-muted mb-3"><i class="bi bi-gear me-1"></i> Pengaturan Batas Stok</h6>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-uppercase text-warning">
                                    <i class="bi bi-exclamation-triangle-fill me-1"></i> Batas Kuning (Menipis)
                                </label>
                                <div class="input-group">
                                    <input type="number" name="batas_kuning" class="form-control border-warning" 
                                           value="{{ old('batas_kuning', $bahan->batas_kuning) }}" required>
                                    <span class="input-group-text bg-warning bg-opacity-10 border-warning text-warning">Unit</span>
                                </div>
                                <div class="form-text small">Peringatan muncul saat stok menyentuh angka ini.</div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-uppercase text-danger">
                                    <i class="bi bi-x-circle-fill me-1"></i> Batas Merah (Kritis)
                                </label>
                                <div class="input-group">
                                    <input type="number" name="batas_merah" class="form-control border-danger" 
                                           value="{{ old('batas_merah', $bahan->batas_merah) }}" required>
                                    <span class="input-group-text bg-danger bg-opacity-10 border-danger text-danger">Unit</span>
                                </div>
                                <div class="form-text small">Status berubah bahaya saat stok di bawah angka ini.</div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('bahanbaku.index') }}" class="btn btn-light border px-4">Batal</a>
                            <button type="submit" class="btn btn-success fw-bold px-4">
                                <i class="bi bi-save me-1"></i> Simpan Perubahan
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection