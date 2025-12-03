@extends('layouts.admin')

@section('content')
<div class="container mt-4">

    <h4>Tambah Pembelian Bahan</h4>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('pembelian.store') }}" method="POST">
        @csrf

        <div class="card shadow mt-3">
            <div class="card-body">

                <div class="mb-3">
                    <label>Tanggal Pembelian</label>
                    <input type="date" name="tanggal" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Pilih Supplier</label>
                    <select name="supplier_id" class="form-control">
                        <option value="">-- Pilih Supplier --</option>
                        @foreach($suppliers as $s)
                            <option value="{{ $s->id }}">{{ $s->nama_supplier }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label>Supplier Baru (Opsional)</label>
                    <input type="text" name="supplier_baru" class="form-control" placeholder="Masukkan supplier baru">
                </div>

                <hr>
                <h5>Daftar Bahan Dibeli</h5>

                <table class="table table-bordered mt-2" id="tableBahan">
                    <thead>
                        <tr>
                            <th>Bahan</th>
                            <th>Bahan Baru</th>
                            <th>Qty</th>
                            <th>Harga Satuan</th>
                            <th>Satuan</th>
                            <th></th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <td>
                                <select name="bahan_id[]" class="form-control">
                                    <option value="">-- Pilih Bahan --</option>
                                    @foreach ($bahan as $b)
                                        <option value="{{ $b->id }}">{{ $b->nama_bahan }}</option>
                                    @endforeach
                                </select>
                            </td>

                            <td>
                                <input type="text" name="bahan_baru[]" class="form-control"
                                       placeholder="Isi jika bahan baru">
                            </td>

                            <td>
                                <input type="number" name="qty[]" class="form-control" required min="1">
                            </td>

                            <td>
                                <input type="number" name="harga_satuan[]" class="form-control" required min="0">
                            </td>

                            <td>
                                <select name="satuan[]" class="form-control" required>
                                    <option value="pcs">pcs</option>
                                    <option value="kg">kg</option>
                                    <option value="gram">gram</option>
                                    <option value="liter">liter</option>
                                </select>
                            </td>

                            <td>
                                <button type="button" class="btn btn-danger btn-sm removeRow">X</button>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <button type="button" id="addRow" class="btn btn-secondary mt-2">
                    + Tambah Baris
                </button>

                <div class="mt-4 text-end">
                    <button class="btn btn-primary">Simpan Pembelian</button>
                </div>

            </div>
        </div>
    </form>
</div>

{{-- ========================================= --}}
{{-- JAVASCRIPT BARU YANG SUDAH DIFIX           --}}
{{-- ========================================= --}}
<script>
document.getElementById('addRow').addEventListener('click', function () {
    const row = `
    <tr>
        <td>
            <select name="bahan_id[]" class="form-control">
                <option value="">-- Pilih Bahan --</option>
                @foreach ($bahan as $b)
                    <option value="{{ $b->id }}">{{ $b->nama_bahan }}</option>
                @endforeach
            </select>
        </td>

        <td>
            <input type="text" name="bahan_baru[]" class="form-control" placeholder="Isi jika bahan baru">
        </td>

        <td>
            <input type="number" name="qty[]" class="form-control" min="1" required>
        </td>

        <td>
            <input type="number" name="harga_satuan[]" class="form-control" min="0" required>
        </td>

        <td>
            <select name="satuan[]" class="form-control" required>
                <option value="pcs">pcs</option>
                <option value="kg">kg</option>
                <option value="gram">gram</option>
                <option value="liter">liter</option>
            </select>
        </td>

        <td>
            <button type="button" class="btn btn-danger btn-sm removeRow">X</button>
        </td>
    </tr>
    `;

    document.querySelector("#tableBahan tbody").insertAdjacentHTML('beforeend', row);
});

// Hapus baris
document.addEventListener('click', function (e) {
    if (e.target.classList.contains('removeRow')) {
        e.target.closest('tr').remove();
    }
});
</script>

@endsection
