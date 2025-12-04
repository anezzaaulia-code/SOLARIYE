@extends('layouts.admin')

@section('content')
<div class="container-fluid p-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-dark m-0">Edit Pembelian Bahan</h4>
            <p class="text-muted small m-0">Perbarui data transaksi pembelian dari supplier.</p>
        </div>
        <a href="{{ route('pembelian.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <form action="{{ route('pembelian.update', $pembelian->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                
                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0 small">
                            @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <h6 class="fw-bold text-primary mb-3">Informasi Transaksi</h6>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-uppercase">Tanggal Pembelian</label>
                        <input type="date" name="tanggal" class="form-control" value="{{ $pembelian->tanggal }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-uppercase">Supplier</label>
                        <select name="supplier_id" class="form-select" required>
                            @foreach ($suppliers as $s)
                                <option value="{{ $s->id }}" {{ $s->id == $pembelian->supplier_id ? 'selected' : '' }}>
                                    {{ $s->nama_supplier }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-0">
                <div class="p-4 border-bottom d-flex justify-content-between align-items-center bg-light">
                    <h6 class="fw-bold text-primary m-0">Detail Item Belanja</h6>
                    <button type="button" class="btn btn-primary btn-sm" onclick="addItem()">
                        <i class="bi bi-plus-lg me-1"></i> Tambah Baris
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="table table-borderless align-middle mb-0" id="table-items">
                        <thead class="bg-white border-bottom text-secondary small text-uppercase">
                            <tr>
                                <th width="35%" class="ps-4">Nama Bahan</th>
                                <th width="15%">Jumlah</th>
                                <th width="10%">Satuan</th>
                                <th width="20%">Harga Satuan</th>
                                <th width="15%">Subtotal</th>
                                <th width="5%" class="text-center"><i class="bi bi-trash"></i></th>
                            </tr>
                        </thead>
                        <tbody id="items-container">
                            @foreach ($detail as $index => $item)
                            <tr class="item-row border-bottom">
                                <td class="ps-4 py-3">
                                    <select name="items[{{ $index }}][bahan_baku_id]" class="form-select select-bahan" required onchange="updateSatuan(this)">
                                        <option value="" disabled>-- Pilih Bahan --</option>
                                        @foreach ($bahan as $b)
                                            <option value="{{ $b->id }}" data-satuan="{{ $b->satuan }}" 
                                                {{ $b->id == $item->bahan_id ? 'selected' : '' }}>
                                                {{ $b->nama_bahan }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="py-3">
                                    <input type="number" name="items[{{ $index }}][jumlah]" class="form-control input-qty" 
                                           min="1" value="{{ $item->qty }}" required oninput="hitungTotal()">
                                </td>
                                <td class="py-3">
                                    <input type="text" class="form-control bg-light input-satuan" readonly 
                                           value="{{ $item->bahan->satuan ?? '-' }}">
                                </td>
                                <td class="py-3">
                                    <div class="input-group">
                                        <span class="input-group-text bg-white">Rp</span>
                                        <input type="number" name="items[{{ $index }}][harga_satuan]" class="form-control input-harga" 
                                               min="0" value="{{ $item->harga_satuan }}" required oninput="hitungTotal()">
                                    </div>
                                </td>
                                <td class="py-3">
                                    <input type="text" class="form-control bg-light text-end input-subtotal" readonly value="Rp 0">
                                </td>
                                <td class="text-center py-3">
                                    <button type="button" class="btn btn-outline-danger btn-sm border-0" onclick="removeRow(this)">
                                        <i class="bi bi-x-lg"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="border-top bg-light">
                            <tr>
                                <td colspan="4" class="text-end fw-bold py-3 text-uppercase">Grand Total</td>
                                <td class="py-3">
                                    <input type="text" id="display_total" class="form-control fw-bold text-end bg-white" value="Rp 0" readonly>
                                    <input type="hidden" name="total_harga" id="input_total">
                                </td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2">
            <a href="{{ route('pembelian.index') }}" class="btn btn-light border px-4">Batal</a>
            <button type="submit" class="btn btn-success fw-bold px-5">
                <i class="bi bi-save me-2"></i> Update Data
            </button>
        </div>

    </form>
</div>

{{-- SCRIPT JAVASCRIPT --}}
<script>
    const bahanList = @json($bahan);

    function addItem() {
        const container = document.getElementById('items-container');
        const index = new Date().getTime(); // Unique index

        let options = '<option value="" disabled selected>-- Pilih Bahan --</option>';
        bahanList.forEach(b => {
            options += `<option value="${b.id}" data-satuan="${b.satuan}">${b.nama_bahan}</option>`;
        });

        const row = `
            <tr class="item-row border-bottom">
                <td class="ps-4 py-3">
                    <select name="items[${index}][bahan_baku_id]" class="form-select select-bahan" required onchange="updateSatuan(this)">
                        ${options}
                    </select>
                </td>
                <td class="py-3">
                    <input type="number" name="items[${index}][jumlah]" class="form-control input-qty" min="1" value="1" required oninput="hitungTotal()">
                </td>
                <td class="py-3">
                    <input type="text" class="form-control bg-light input-satuan" readonly>
                </td>
                <td class="py-3">
                    <div class="input-group">
                        <span class="input-group-text bg-white">Rp</span>
                        <input type="number" name="items[${index}][harga_satuan]" class="form-control input-harga" min="0" required oninput="hitungTotal()">
                    </div>
                </td>
                <td class="py-3">
                    <input type="text" class="form-control bg-light text-end input-subtotal" readonly value="Rp 0">
                </td>
                <td class="text-center py-3">
                    <button type="button" class="btn btn-outline-danger btn-sm border-0" onclick="removeRow(this)">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </td>
            </tr>
        `;
        container.insertAdjacentHTML('beforeend', row);
    }

    function removeRow(btn) {
        btn.closest('tr').remove();
        hitungTotal();
    }

    function updateSatuan(select) {
        const option = select.options[select.selectedIndex];
        const satuan = option.getAttribute('data-satuan');
        const row = select.closest('tr');
        row.querySelector('.input-satuan').value = satuan || '-';
    }

    function hitungTotal() {
        let grandTotal = 0;
        const rows = document.querySelectorAll('.item-row');

        rows.forEach(row => {
            const qty = parseFloat(row.querySelector('.input-qty').value) || 0;
            const harga = parseFloat(row.querySelector('.input-harga').value) || 0;
            const subtotal = qty * harga;

            // Format subtotal
            row.querySelector('.input-subtotal').value = 'Rp ' + new Intl.NumberFormat('id-ID').format(subtotal);
            grandTotal += subtotal;
        });

        // Format Grand Total
        document.getElementById('display_total').value = 'Rp ' + new Intl.NumberFormat('id-ID').format(grandTotal);
        document.getElementById('input_total').value = grandTotal;
    }

    // Jalankan hitung total saat halaman dimuat (agar data lama terhitung)
    document.addEventListener('DOMContentLoaded', function() {
        hitungTotal();
    });
</script>

@endsection