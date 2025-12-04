@extends('layouts.admin')

@section('content')
<div class="container-fluid p-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-dark m-0">Tambah Pembelian Bahan</h4>
            <p class="text-muted small m-0">Input stok masuk dari supplier.</p>
        </div>
        <a href="{{ route('pembelian.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <form action="{{ route('pembelian.store') }}" method="POST">
        @csrf

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <h6 class="fw-bold text-primary mb-3">Informasi Transaksi</h6>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-uppercase">Tanggal Pembelian</label>
                        <input type="date" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-uppercase">Supplier</label>
                        <select name="supplier_id" class="form-select">
                            <option value="" selected disabled>-- Pilih Supplier --</option>
                            @foreach($suppliers as $s)
                                <option value="{{ $s->id }}">{{ $s->nama_supplier }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-uppercase">Supplier Baru (Opsional)</label>
                        <input type="text" name="supplier_baru" class="form-control" placeholder="Isi jika supplier belum terdaftar">
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-0">
                <div class="p-4 border-bottom d-flex justify-content-between align-items-center bg-light">
                    <h6 class="fw-bold text-primary m-0">Daftar Bahan Baku</h6>
                    <button type="button" class="btn btn-primary btn-sm" id="addRow">
                        <i class="bi bi-plus-lg me-1"></i> Tambah Baris
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="table table-borderless align-middle mb-0" id="tableBahan">
                        <thead class="bg-white border-bottom text-secondary small text-uppercase">
                            <tr>
                                <th width="30%" class="ps-4">Nama Bahan</th>
                                <th width="20%">Bahan Baru (Opsional)</th>
                                <th width="10%">Jumlah</th>
                                <th width="15%">Harga Satuan</th>
                                <th width="10%">Satuan</th>
                                <th width="10%">Subtotal</th>
                                <th width="5%" class="text-center"><i class="bi bi-trash"></i></th>
                            </tr>
                        </thead>
                        <tbody id="items-container">
                            <tr class="item-row border-bottom">
                                <td class="ps-4 py-3">
                                    <select name="bahan_id[]" class="form-select select-bahan" onchange="updateSatuan(this)">
                                        <option value="">-- Pilih --</option>
                                        @foreach ($bahan as $b)
                                            <option value="{{ $b->id }}" data-satuan="{{ $b->satuan }}">{{ $b->nama_bahan }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="py-3">
                                    <input type="text" name="bahan_baru[]" class="form-control" placeholder="Nama bahan baru">
                                </td>
                                <td class="py-3">
                                    <input type="number" name="qty[]" class="form-control input-qty" min="1" value="1" required oninput="hitungTotal()">
                                </td>
                                <td class="py-3">
                                    <div class="input-group">
                                        <span class="input-group-text bg-white">Rp</span>
                                        <input type="number" name="harga_satuan[]" class="form-control input-harga" min="0" required oninput="hitungTotal()">
                                    </div>
                                </td>
                                <td class="py-3">
                                    <select name="satuan[]" class="form-select input-satuan" required>
                                        <option value="pcs">pcs</option>
                                        <option value="kg">kg</option>
                                        <option value="gram">gram</option>
                                        <option value="liter">liter</option>
                                    </select>
                                </td>
                                <td class="py-3">
                                    <input type="text" class="form-control bg-light text-end input-subtotal" readonly value="Rp 0">
                                </td>
                                <td class="text-center py-3">
                                    <button type="button" class="btn btn-outline-danger btn-sm border-0 removeRow">
                                        <i class="bi bi-x-lg"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                        <tfoot class="border-top bg-light">
                            <tr>
                                <td colspan="5" class="text-end fw-bold py-3 text-uppercase">Grand Total</td>
                                <td class="py-3">
                                    <input type="text" id="display_total" class="form-control fw-bold text-end bg-white" value="Rp 0" readonly>
                                </td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2">
            <button type="reset" class="btn btn-light border px-4">Reset</button>
            <button type="submit" class="btn btn-success fw-bold px-5">
                <i class="bi bi-save me-2"></i> Simpan Transaksi
            </button>
        </div>

    </form>
</div>

{{-- SCRIPT JAVASCRIPT --}}
<script>
    // Data Bahan Baku (untuk dropdown baris baru)
    const bahanOptions = `
        <option value="">-- Pilih --</option>
        @foreach ($bahan as $b)
            <option value="{{ $b->id }}" data-satuan="{{ $b->satuan }}">{{ $b->nama_bahan }}</option>
        @endforeach
    `;

    // Tambah Baris
    document.getElementById('addRow').addEventListener('click', function () {
        const row = `
        <tr class="item-row border-bottom">
            <td class="ps-4 py-3">
                <select name="bahan_id[]" class="form-select select-bahan" onchange="updateSatuan(this)">
                    ${bahanOptions}
                </select>
            </td>
            <td class="py-3">
                <input type="text" name="bahan_baru[]" class="form-control" placeholder="Nama bahan baru">
            </td>
            <td class="py-3">
                <input type="number" name="qty[]" class="form-control input-qty" min="1" value="1" required oninput="hitungTotal()">
            </td>
            <td class="py-3">
                <div class="input-group">
                    <span class="input-group-text bg-white">Rp</span>
                    <input type="number" name="harga_satuan[]" class="form-control input-harga" min="0" required oninput="hitungTotal()">
                </div>
            </td>
            <td class="py-3">
                <select name="satuan[]" class="form-select input-satuan" required>
                    <option value="pcs">pcs</option>
                    <option value="kg">kg</option>
                    <option value="gram">gram</option>
                    <option value="liter">liter</option>
                </select>
            </td>
            <td class="py-3">
                <input type="text" class="form-control bg-light text-end input-subtotal" readonly value="Rp 0">
            </td>
            <td class="text-center py-3">
                <button type="button" class="btn btn-outline-danger btn-sm border-0 removeRow">
                    <i class="bi bi-x-lg"></i>
                </button>
            </td>
        </tr>
        `;
        document.getElementById('items-container').insertAdjacentHTML('beforeend', row);
    });

    // Hapus Baris & Hitung Ulang
    document.addEventListener('click', function (e) {
        if (e.target.closest('.removeRow')) {
            e.target.closest('tr').remove();
            hitungTotal();
        }
    });

    // Update Satuan Otomatis
    window.updateSatuan = function(select) {
        const option = select.options[select.selectedIndex];
        const satuan = option.getAttribute('data-satuan');
        if (satuan) {
            const row = select.closest('tr');
            row.querySelector('.input-satuan').value = satuan;
        }
    }

    // Hitung Grand Total
    window.hitungTotal = function() {
        let grandTotal = 0;
        const rows = document.querySelectorAll('.item-row');

        rows.forEach(row => {
            const qty = parseFloat(row.querySelector('.input-qty').value) || 0;
            const harga = parseFloat(row.querySelector('.input-harga').value) || 0;
            const subtotal = qty * harga;

            // Update subtotal per baris
            row.querySelector('.input-subtotal').value = 'Rp ' + new Intl.NumberFormat('id-ID').format(subtotal);
            grandTotal += subtotal;
        });

        // Update Grand Total
        document.getElementById('display_total').value = 'Rp ' + new Intl.NumberFormat('id-ID').format(grandTotal);
    }
</script>

@endsection