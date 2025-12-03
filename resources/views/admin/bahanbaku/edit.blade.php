@extends('layouts.admin')

@section('content')
<div class="container mt-4">

    <div class="card shadow-sm" style="max-width: 600px; margin: 0 auto;">
        <div class="card-header bg-warning text-dark">
            <h5 class="mb-0">Edit Bahan Baku</h5>
        </div>
        <div class="card-body">
            
            <form action="{{ route('bahanbaku.update', $bahan->id) }}" method="POST">
                @csrf @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Nama Bahan</label>
                    <input type="text" name="nama_bahan" class="form-control @error('nama_bahan') is-invalid @enderror" 
                           value="{{ old('nama_bahan', $bahan->nama_bahan) }}" required>
                    @error('nama_bahan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Satuan</label>
                    <input type="text" name="satuan" class="form-control @error('satuan') is-invalid @enderror" 
                           value="{{ old('satuan', $bahan->satuan) }}" required>
                    @error('satuan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Batas Kuning</label>
                        <input type="number" name="batas_kuning" class="form-control @error('batas_kuning') is-invalid @enderror" 
                               value="{{ old('batas_kuning', $bahan->batas_kuning) }}" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Batas Merah</label>
                        <input type="number" name="batas_merah" class="form-control @error('batas_merah') is-invalid @enderror" 
                               value="{{ old('batas_merah', $bahan->batas_merah) }}" required>
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('bahanbaku.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Update Data</button>
                </div>
            </form>

        </div>
    </div>

</div>
@endsection