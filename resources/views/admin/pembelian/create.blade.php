@extends('layouts.admin')

@section('content')
<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="text-primary fw-bold">Tambah Pembelian Bahan</h4>
        <a href="{{ route('pembelian.index') }}" class="btn btn-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    {{-- Alert Error Global --}}
    @if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Terjadi Kesalahan!</strong> Silakan periksa inputan Anda.
        <ul class="mb-0 mt-2">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <form action="{{ route('pembelian.store') }}" method="POST">
        @csrf

        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Tanggal Pembelian</label>
                        <input type="date" name="tanggal" class="form-control" value="{{ old('tanggal', date('Y-m-d')) }}" required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Pilih Supplier</label>
                        <select name="supplier_id" class="form-select">
                            <option value="">-- Pilih Supplier Lama --</option>
                            @foreach ($suppliers as $s)
                                <option value="{{ $s->id }}" {{ old('supplier_id') == $s->id ? 'selected' : '' }}>
                                    {{ $s->nama_supplier }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold text-success">Atau Buat Supplier Baru</label>
                        <input type="text" name="supplier_baru" class="form-control" placeholder="Nama Supplier Baru (Opsional)" value="{{ old('supplier_baru') }}">
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-header bg-light">
                <h5 class="mb-0 fw-bold">Daftar Item Belanja</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered mb-0" id="tableBahan">
                        <thead class="table-dark text-center">
                            <tr>
                                <th width="25%">Bahan Baku</th>
                                <th width="20%">Atau Bahan Baru</th>
                                <th width="15%">Qty</th>
                                <th width="15%">Satuan</th> {{-- Kolom Satuan --}}
                                <th width="20%">Harga Satuan (Rp)</th>
                                <th width="5%"></th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Baris Pertama Default --}}
                            <tr>
                                <td>
                                    <select name="bahan_id[]" class="form-select">
                                        <option value="">-- Pilih Bahan --</option>
                                        @foreach ($bahan as $b)
                                            <option value="{{ $b->id }}">{{ $b->nama_bahan }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="text" name="bahan_baru[]" class="form-control" placeholder="Nama Bahan Baru">
                                </td>
                                <td>
                                    <input type="number" name="qty[]" class="form-control" placeholder="0" min="1" required>
                                </td>
                                <td>
                                    <select name="satuan[]" class="form-select">
                                        <option value="pcs">Pcs</option>
                                        <option value="kg">Kg</option>
                                        <option value="liter">Liter</option>
                                        <option value="gram">Gram</option>
                                        <option value="ikat">Ikat</option>
                                        <option value="butir">Butir</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="number" name="harga_satuan[]" class="form-control" placeholder="0" min="0" required>
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-danger btn-sm removeRow"><i class="bi bi-trash"></i></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white d-flex justify-content-between">
                <button type="button" id="addRow" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-plus-lg"></i> Tambah Baris
                </button>
                <button type="submit" class="btn btn-success fw-bold">
                    <i class="bi bi-save"></i> Simpan Transaksi
                </button>
            </div>
        </div>

    </form>
</div>

{{-- SCRIPT JAVASCRIPT --}}
<script>
    document.getElementById('addRow').addEventListener('click', function () {
        const row = `
        <tr>
            <td>
                <select name="bahan_id[]" class="form-select">
                    <option value="">-- Pilih Bahan --</option>
                    @foreach ($bahan as $b)
                        <option value="{{ $b->id }}">{{ $b->nama_bahan }}</option>
                    @endforeach
                </select>
            </td>
            <td>
                <input type="text" name="bahan_baru[]" class="form-control" placeholder="Nama Bahan Baru">
            </td>
            <td>
                <input type="number" name="qty[]" class="form-control" placeholder="0" min="1" required>
            </td>
            <td>
                <select name="satuan[]" class="form-select">
                    <option value="pcs">Pcs</option>
                    <option value="kg">Kg</option>
                    <option value="liter">Liter</option>
                    <option value="gram">Gram</option>
                    <option value="ikat">Ikat</option>
                    <option value="butir">Butir</option>
                </select>
            </td>
            <td>
                <input type="number" name="harga_satuan[]" class="form-control" placeholder="0" min="0" required>
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-danger btn-sm removeRow"><i class="bi bi-trash"></i></button>
            </td>
        </tr>`;

        document.querySelector("#tableBahan tbody").insertAdjacentHTML('beforeend', row);
    });

    document.addEventListener('click', function (e) {
        if (e.target.closest('.removeRow')) {
            const rows = document.querySelectorAll("#tableBahan tbody tr");
            if (rows.length > 1) {
                e.target.closest('tr').remove();
            } else {
                alert("Minimal harus ada satu baris pembelian!");
            }
        }
    });
</script>
@endsection