@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Edit Pembelian #{{ $pembelian->id }}</h1>
    <a href="{{ route('pembelian.index') }}" class="btn btn-secondary mb-3">Kembali</a>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('pembelian.update', $pembelian->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="supplier_id" class="form-label">Supplier</label>
            <select name="supplier_id" id="supplier_id" class="form-control">
                @foreach($suppliers as $supplier)
                    <option value="{{ $supplier->id }}" {{ $pembelian->supplier_id == $supplier->id ? 'selected' : '' }}>
                        {{ $supplier->nama_supplier }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="tanggal" class="form-label">Tanggal</label>
            <input type="date" name="tanggal" id="tanggal" class="form-control" value="{{ $pembelian->tanggal }}">
        </div>

        <h4>Detail Barang</h4>
        <div id="detailBarangWrapper">
            @foreach($detail as $i => $det)
            <div class="row mb-2 detail-item">
                <div class="col-md-4">
                    <select name="bahan_id[]" class="form-control">
                        <option value="">-- Pilih Bahan --</option>
                        @foreach($bahan as $b)
                            <option value="{{ $b->id }}" {{ $det->bahan_id == $b->id ? 'selected' : '' }}>
                                {{ $b->nama_bahan }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="number" name="jumlah[]" class="form-control" placeholder="Jumlah" value="{{ $det->jumlah }}">
                </div>
                <div class="col-md-3">
                    <input type="number" name="harga_satuan[]" class="form-control" placeholder="Harga Satuan" value="{{ $det->harga_satuan }}">
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger remove-item">Hapus</button>
                </div>
            </div>
            @endforeach
        </div>

        <button type="button" class="btn btn-success mb-3" id="addItem">Tambah Barang</button>

        <button type="submit" class="btn btn-primary">Update Pembelian</button>
    </form>
</div>

<!-- Script untuk menambah / hapus input barang -->
<script>
    const bahanOptions = `@foreach($bahan as $b)<option value="{{ $b->id }}">{{ $b->nama_bahan }}</option>@endforeach`;

    document.getElementById('addItem').addEventListener('click', function() {
        const wrapper = document.getElementById('detailBarangWrapper');
        const div = document.createElement('div');
        div.classList.add('row','mb-2','detail-item');
        div.innerHTML = `
            <div class="col-md-4">
                <select name="bahan_id[]" class="form-control">
                    <option value="">-- Pilih Bahan --</option>
                    ${bahanOptions}
                </select>
            </div>
            <div class="col-md-2">
                <input type="number" name="jumlah[]" class="form-control" placeholder="Jumlah">
            </div>
            <div class="col-md-3">
                <input type="number" name="harga_satuan[]" class="form-control" placeholder="Harga Satuan">
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger remove-item">Hapus</button>
            </div>
        `;
        wrapper.appendChild(div);
    });

    document.addEventListener('click', function(e){
        if(e.target && e.target.classList.contains('remove-item')){
            e.target.closest('.detail-item').remove();
        }
    });
</script>
@endsection
