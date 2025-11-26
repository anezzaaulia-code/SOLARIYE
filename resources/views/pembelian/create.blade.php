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
        <div class="mb-3">
            <label for="supplier_id" class="form-label">Supplier</label>
            <select name="supplier_id" id="supplier_id" class="form-control" required>
                <option value="">-- Pilih Supplier --</option>
                @foreach($suppliers as $supplier)
                    <option value="{{ $supplier->id }}">{{ $supplier->nama_supplier }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="tanggal" class="form-label">Tanggal</label>
            <input type="date" name="tanggal" id="tanggal" class="form-control" value="{{ old('tanggal', date('Y-m-d')) }}" required>
        </div>

        <h4>Detail Bahan</h4>
        <table class="table table-bordered" id="detailTable">
            <thead>
                <tr>
                    <th>Bahan</th>
                    <th>Jumlah</th>
                    <th>Harga Satuan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <select name="bahan_id[]" class="form-control" required>
                            <option value="">-- Pilih Bahan --</option>
                            @foreach($bahan as $item)
                                <option value="{{ $item->id }}">{{ $item->nama_bahan }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td><input type="number" name="jumlah[]" class="form-control" min="1" value="1" required></td>
                    <td><input type="number" name="harga_satuan[]" class="form-control" min="0" value="0" required></td>
                    <td><button type="button" class="btn btn-danger removeRow">-</button></td>
                </tr>
            </tbody>
        </table>

        <button type="button" class="btn btn-success mb-3" id="addRow">Tambah Bahan</button>
        <br>
        <button type="submit" class="btn btn-primary">Simpan Pembelian</button>
    </form>
</div>

<script>
document.getElementById('addRow').addEventListener('click', function() {
    let table = document.getElementById('detailTable').getElementsByTagName('tbody')[0];
    let newRow = table.rows[0].cloneNode(true);

    newRow.querySelectorAll('input').forEach(input => input.value = '');
    newRow.querySelector('select').selectedIndex = 0;

    table.appendChild(newRow);
});

document.addEventListener('click', function(e) {
    if(e.target && e.target.classList.contains('removeRow')) {
        let tbody = document.getElementById('detailTable').getElementsByTagName('tbody')[0];
        if(tbody.rows.length > 1) {
            e.target.closest('tr').remove();
        }
    }
});
</script>
@endsection
