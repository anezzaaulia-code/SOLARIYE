@extends('layouts.admin')

@section('content')
<div class="container mt-4">

    <div class="card shadow-sm" style="max-width: 600px; margin: 0 auto;">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Input Stok Harian</h5>
        </div>

        <div class="card-body">
            
            <form action="{{ route('stokharian.store') }}" method="POST">
                @csrf

                {{-- Pilih Bahan --}}
                <div class="mb-3">
                    <label class="form-label">Pilih Bahan Baku <span class="text-danger">*</span></label>
                    <select name="bahan_id" id="selectBahan" class="form-select @error('bahan_id') is-invalid @enderror" required>
                        <option value="" data-stok="0">-- Pilih Bahan --</option>
                        @foreach ($bahan as $b)
                            {{-- Kita simpan stok saat ini di attribut data-stok agar mudah diambil JS --}}
                            <option value="{{ $b->id }}" 
                                    data-stok="{{ $b->stok }}" 
                                    {{ old('bahan_id') == $b->id ? 'selected' : '' }}>
                                {{ $b->nama_bahan }} (Sisa: {{ $b->stok }} {{ $b->satuan }})
                            </option>
                        @endforeach
                    </select>
                    @error('bahan_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Tanggal --}}
                <div class="mb-3">
                    <label class="form-label">Tanggal <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal" class="form-control @error('tanggal') is-invalid @enderror"
                           value="{{ old('tanggal', date('Y-m-d')) }}" required>
                    @error('tanggal')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    {{-- Stok Awal (Otomatis) --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Stok Awal (Saat Ini)</label>
                        <input type="number" name="stok_awal" id="stokAwal" class="form-control bg-light" 
                               value="{{ old('stok_awal') }}" readonly tabindex="-1">
                        <small class="text-muted">Terisi otomatis dari sistem.</small>
                    </div>

                    {{-- Stok Akhir (Input Manual) --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Stok Akhir (Opname) <span class="text-danger">*</span></label>
                        <input type="number" name="stok_akhir" class="form-control @error('stok_akhir') is-invalid @enderror"
                               value="{{ old('stok_akhir') }}" placeholder="0" required>
                        @error('stok_akhir')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('stokharian.index') }}" class="btn btn-secondary">Kembali</a>
                    <button type="submit" class="btn btn-success"><i class="bi bi-save"></i> Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Script otomatis isi Stok Awal saat bahan dipilih
    document.getElementById('selectBahan').addEventListener('change', function() {
        // Ambil data-stok dari option yang dipilih
        let selectedOption = this.options[this.selectedIndex];
        let stok = selectedOption.getAttribute('data-stok');
        
        // Isi ke input stok_awal
        document.getElementById('stokAwal').value = stok ? stok : 0;
    });
</script>
@endsection