@extends('layouts.admin')

@section('content')
<div class="card shadow">
    <div class="card-header">
        <h4>Input Stok Harian</h4>
    </div>

    <div class="card-body">
        <form action="{{ route('stok_harian.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label">Pilih Bahan</label>
                <select name="bahan_baku_id" class="form-control" required>
                    <option value="">-- Pilih Bahan --</option>
                    @foreach ($bahanBaku as $bahan)
                        <option value="{{ $bahan->id }}">
                            {{ $bahan->nama }} (Stok: {{ $bahan->stok }} {{ $bahan->satuan }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Stok Awal</label>
                <input type="number" name="stok_awal" class="form-control" 
                       placeholder="Otomatis diambil dari stok bahan baku"
                       readonly>
            </div>

            <div class="mb-3">
                <label class="form-label">Stok Akhir</label>
                <input type="number" name="stok_akhir" class="form-control" required>
            </div>

            <button class="btn btn-primary mt-3">Simpan</button>
        </form>
    </div>
</div>

<script>
document.querySelector('select[name=bahan_baku_id]').addEventListener('change', function() {
    let stokAwal = this.options[this.selectedIndex].text.match(/Stok: (\d+)/)[1];
    document.querySelector('input[name=stok_awal]').value = stokAwal;
});
</script>

@endsection
