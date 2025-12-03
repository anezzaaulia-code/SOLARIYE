@extends('layouts.admin')

@section('content')
<div class="container mt-4">

    <div class="card shadow-sm" style="max-width: 600px; margin: 0 auto;">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Tambah Bahan Baku Baru</h5>
        </div>
        <div class="card-body">
            
            <form action="{{ route('bahanbaku.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Nama Bahan</label>
                    <input type="text" name="nama_bahan" class="form-control @error('nama_bahan') is-invalid @enderror" 
                           value="{{ old('nama_bahan') }}" placeholder="Contoh: Beras, Telur, Minyak..." required>
                    @error('nama_bahan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Satuan</label>
                    <input type="text" name="satuan" class="form-control @error('satuan') is-invalid @enderror" 
                           value="{{ old('satuan') }}" placeholder="Contoh: kg, liter, butir, ikat" required>
                    @error('satuan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Batas Peringatan (Kuning)</label>
                        <input type="number" name="batas_kuning" class="form-control @error('batas_kuning') is-invalid @enderror" 
                               value="{{ old('batas_kuning') }}" placeholder="Cth: 10" required>
                        <small class="text-muted">Muncul peringatan jika stok di bawah angka ini.</small>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Batas Bahaya (Merah)</label>
                        <input type="number" name="batas_merah" class="form-control @error('batas_merah') is-invalid @enderror" 
                               value="{{ old('batas_merah') }}" placeholder="Cth: 5" required>
                        <small class="text-muted">Status kritis jika stok di bawah angka ini.</small>
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('bahanbaku.index') }}" class="btn btn-secondary">Kembali</a>
                    <button type="submit" class="btn btn-success">Simpan Data</button>
                </div>
            </form>

        </div>
    </div>

</div>
@endsection