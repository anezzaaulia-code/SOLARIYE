@extends('layouts.admin')

@section('content')
<div class="card shadow">
    <div class="card-header">
        <h4>Input Stok Harian</h4>
    </div>

    <div class="card-body">

        {{-- Notifikasi error --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Terjadi kesalahan:</strong>
                <ul class="mt-2 mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Form --}}
        <form action="{{ route('stokharian.store') }}" method="POST">
            @csrf

            {{-- Pilih Bahan --}}
            <div class="mb-3">
                <label class="form-label fw-bold">Pilih Bahan</label>
                <select name="bahan_id"
                        class="form-control @error('bahan_id') is-invalid @enderror"
                        required>
                    <option value="">-- Pilih Bahan --</option>
                    @foreach ($bahan as $b)
                        <option value="{{ $b->id }}"
                            data-stok="{{ $b->stok }}"
                            {{ old('bahan_id') == $b->id ? 'selected' : '' }}>
                            {{ $b->nama_bahan }} (Stok: {{ $b->stok }} {{ $b->satuan }})
                        </option>
                    @endforeach
                </select>
                @error('bahan_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Tanggal --}}
            <div class="mb-3">
                <label class="form-label fw-bold">Tanggal</label>
                <input type="date"
                       name="tanggal"
                       class="form-control @error('tanggal') is-invalid @enderror"
                       value="{{ old('tanggal') }}"
                       required>
                @error('tanggal')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Stok Awal --}}
            <div class="mb-3">
                <label class="form-label fw-bold">Stok Awal</label>
                <input type="number"
                       name="stok_awal"
                       class="form-control"
                       value="{{ old('stok_awal') }}"
                       placeholder="Otomatis diambil dari stok bahan"
                       readonly>
            </div>

            {{-- Stok Akhir --}}
            <div class="mb-3">
                <label class="form-label fw-bold">Stok Akhir</label>
                <input type="number"
                       name="stok_akhir"
                       class="form-control @error('stok_akhir') is-invalid @enderror"
                       value="{{ old('stok_akhir') }}"
                       min="0"
                       required>
                @error('stok_akhir')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button class="btn btn-primary mt-3">Simpan</button>
        </form>
    </div>
</div>

{{-- JS untuk auto isi stok awal --}}
<script>
document.querySelector('select[name=bahan_id]').addEventListener('change', function() {
    let stok = this.options[this.selectedIndex].getAttribute('data-stok');
    document.querySelector('input[name=stok_awal]').value = stok ? stok : '';
});
</script>
@endsection
