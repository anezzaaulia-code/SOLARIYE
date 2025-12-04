@extends('layouts.admin')

@section('content')
<div class="container-fluid p-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-dark m-0">Tambah Menu Baru</h4>
            <p class="text-muted small m-0">Isi formulir di bawah untuk menambahkan menu.</p>
        </div>
        <a href="{{ route('menu.index') }}" class="btn btn-outline-secondary btn-sm">
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

                    <form action="{{ route('menu.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-uppercase">Nama Menu <span class="text-danger">*</span></label>
                                <input type="text" name="nama" class="form-control" placeholder="Contoh: Nasi Goreng Spesial" value="{{ old('nama') }}" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-uppercase">Kategori <span class="text-danger">*</span></label>
                                <select name="kategori_id" class="form-select" required>
                                    <option value="" selected disabled>-- Pilih Kategori --</option>
                                    @foreach($kategories as $kategori)
                                        <option value="{{ $kategori->id }}" {{ old('kategori_id') == $kategori->id ? 'selected' : '' }}>
                                            {{ $kategori->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-uppercase">Harga <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">Rp</span>
                                    <input type="number" name="harga" class="form-control" placeholder="0" value="{{ old('harga') }}" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-uppercase">Status</label>
                                <select name="status" class="form-select">
                                    <option value="tersedia" {{ old('status') == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                                    <option value="habis" {{ old('status') == 'habis' ? 'selected' : '' }}>Habis</option>
                                    <option value="nonaktif" {{ old('status') == 'nonaktif' ? 'selected' : '' }}>Disembunyikan (Nonaktif)</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold small text-uppercase">Deskripsi</label>
                            <textarea name="deskripsi" class="form-control" rows="3" placeholder="Jelaskan detail menu ini (opsional)...">{{ old('deskripsi') }}</textarea>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold small text-uppercase">Foto Menu</label>
                            <input type="file" name="foto" class="form-control" accept="image/*" onchange="previewImage(event)">
                            <small class="text-muted d-block mt-1">Format: JPG, PNG, JPEG. Maks: 2MB.</small>
                            
                            <div class="mt-3">
                                <img id="preview" src="#" alt="Preview Foto" class="d-none rounded border shadow-sm" style="max-height: 200px; object-fit: cover;">
                            </div>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-end gap-2">
                            <button type="reset" class="btn btn-light border px-4">Reset</button>
                            <button type="submit" class="btn btn-primary fw-bold px-4">
                                <i class="bi bi-save me-1"></i> Simpan Menu
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
    function previewImage(event) {
        var reader = new FileReader();
        reader.onload = function(){
            var output = document.getElementById('preview');
            output.src = reader.result;
            output.classList.remove('d-none'); // Tampilkan gambar
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>

@endsection