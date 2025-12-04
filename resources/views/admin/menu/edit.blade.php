@extends('layouts.admin')

@section('content')
<div class="container-fluid p-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-dark m-0">Edit Menu</h4>
            <p class="text-muted small m-0">Perbarui informasi menu #{{ $menu->id }}</p>
        </div>
        <a href="{{ route('menu.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('menu.update', $menu->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-uppercase">Nama Menu</label>
                                <input type="text" name="nama" class="form-control" value="{{ old('nama', $menu->nama) }}" required>
                                @error('nama') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-uppercase">Harga</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">Rp</span>
                                    <input type="number" name="harga" class="form-control" value="{{ old('harga', $menu->harga) }}" required>
                                </div>
                                @error('harga') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-uppercase">Kategori</label>
                                <select name="kategori_id" class="form-select" required>
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach($kategories as $kat)
                                        <option value="{{ $kat->id }}" {{ (old('kategori_id', $menu->kategori_id) == $kat->id) ? 'selected' : '' }}>
                                            {{ $kat->nama }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('kategori_id') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-uppercase">Status</label>
                                <select name="status" class="form-select">
                                    <option value="tersedia" {{ old('status', $menu->status) == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                                    <option value="habis" {{ old('status', $menu->status) == 'habis' ? 'selected' : '' }}>Habis</option>
                                    <option value="nonaktif" {{ old('status', $menu->status) == 'nonaktif' ? 'selected' : '' }}>Disembunyikan (Nonaktif)</option>
                                </select>
                                @error('status') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-uppercase">Deskripsi</label>
                            <textarea name="deskripsi" class="form-control" rows="3" placeholder="Deskripsi menu (opsional)">{{ old('deskripsi', $menu->deskripsi ?? '') }}</textarea>
                        </div>

                        <div class="mb-4">
                            <label class="form-label small fw-bold text-uppercase">Foto Menu</label>
                            <div class="d-flex align-items-start gap-3">
                                <div class="position-relative">
                                    <img id="preview" src="{{ $menu->foto ? asset('storage/'.$menu->foto) : asset('img/no-image.png') }}" 
                                         class="rounded border shadow-sm" 
                                         width="100" height="100" 
                                         style="object-fit: cover;">
                                </div>
                                
                                <div class="flex-grow-1">
                                    <input type="file" name="foto" class="form-control mb-1" accept="image/*" onchange="previewImage(event)">
                                    <small class="text-muted d-block">Biarkan kosong jika tidak ingin mengubah foto.</small>
                                    @error('foto') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('menu.index') }}" class="btn btn-light border px-4">Batal</a>
                            <button type="submit" class="btn btn-primary fw-bold px-4">
                                <i class="bi bi-save me-1"></i> Simpan Perubahan
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
        };
        if(event.target.files[0]){
            reader.readAsDataURL(event.target.files[0]);
        }
    }
</script>

@endsection