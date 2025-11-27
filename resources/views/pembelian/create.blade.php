@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Tambah Pembelian</h1>
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

    <form action="{{ route('pembelian.store') }}" method="POST">
        @csrf

        {{-- Supplier --}}
        <div class="mb-3">
            <label for="supplier_id" class="form-label">Supplier</label>
            <select name="supplier_id" id="supplier_id" class="form-control">
                <option value="">-- Pilih Supplier --</option>
                @foreach($suppliers as $supplier)
                    <option value="{{ $supplier->id }}">{{ $supplier->nama_supplier }}</option>
                @endforeach
            </select>
            <small class="text-muted">Jika supplier baru, tulis di bawah:</small>
            <input type="text" name="supplier_baru" class="form-control mt-1" placeholder="Nama Supplier Baru">
        </div>

        {{-- Tanggal --}}
        <div class="mb-3">
            <label for="tanggal" class="form-label">Tanggal</label>
            <input type="date" name="tanggal" id="tanggal" class="form-control" value="{{ old('tanggal', date('Y-m-d')) }}">
        </div>

        {{-- Detail Bahan --}}
        <h4>Detail Bahan</h4>
        <table class="table table-bordered" id="bahanTable">
            <thead>
                <tr>
                    <th>Bahan</th>
                    <th>Qty</th>
                    <th>Harga Satuan</th>
                    <th>Subtotal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <select name="bahan_id[]" class="form-control bahan-select">
                            <option value="">-- Pilih Bahan --</option>
                            @foreach($bahan as $b)
                                <option value="{{ $b->id }}">{{ $b->nama_bahan }}</option>
                            @endforeach
                        </select>
                        <input type="text" name="bahan_baru[]" class="form-control mt-1" placeholder="Nama Bahan Baru">
                    </td>
                    <td><input type="number" name="qty[]" class="form-control qty" value="1"></td>
                    <td><input type="number" name="harga_satuan[]" class="form-control harga_satuan" value="0"></td>
                    <td><input type="text" class="form-control subtotal" value="0" readonly></td>
                    <td><button type="button" class="btn btn-danger remove-row">Hapus</button></td>
                </tr>
            </tbody>
        </table>

        <button type="button" class="btn btn-success mb-3" id="addRow">Tambah Bahan</button>
        <br>
        <button type="submit" class="btn btn-primary">Simpan Pembelian</button>
    </form>
</div>

{{-- JS untuk tambah baris & hitung subtotal --}}
<script>
document.addEventListener('DOMContentLoaded', function(){
    function hitungSubtotal(row){
        const qty = parseFloat(row.querySelector('.qty').value) || 0;
        const harga = parseFloat(row.querySelector('.harga_satuan').value) || 0;
        row.querySelector('.subtotal').value = qty * harga;
    }

    document.getElementById('addRow').addEventListener('click', function(){
        const tbody = document.querySelector('#bahanTable tbody');
        const newRow = tbody.rows[0].cloneNode(true);
        newRow.querySelectorAll('input, select').forEach(el => el.value = '');
        newRow.querySelector('.qty').value = 1;
        newRow.querySelector('.harga_satuan').value = 0;
        newRow.querySelector('.subtotal').value = 0;
        tbody.appendChild(newRow);
    });

    document.querySelector('#bahanTable').addEventListener('input', function(e){
        if(e.target.classList.contains('qty') || e.target.classList.contains('harga_satuan')){
            hitungSubtotal(e.target.closest('tr'));
        }
    });

    document.querySelector('#bahanTable').addEventListener('click', function(e){
        if(e.target.classList.contains('remove-row')){
            const row = e.target.closest('tr');
            if(document.querySelectorAll('#bahanTable tbody tr').length > 1){
                row.remove();
            }
        }
    });
});
</script>
@endsection
