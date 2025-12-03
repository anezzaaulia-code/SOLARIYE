@extends('layouts.admin')

@section('content')
<div class="container mt-4">

    <h4>Edit Pembelian Bahan</h4>

    {{-- Error --}}
    @if($errors->any())
        <div class="alert alert-danger">
            <strong>Terjadi kesalahan:</strong>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif


    <form action="{{ route('pembelian.update', $pembelian->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="card shadow mt-3">
            <div class="card-body">

                {{-- Tanggal --}}
                <div class="mb-3">
                    <label>Tanggal Pembelian</label>
                    <input type="date" name="tanggal" class="form-control"
                           value="{{ $pembelian->tanggal }}" required>
                </div>

                {{-- Supplier --}}
                <div class="mb-3">
                    <label>Supplier</label>
                    <select name="supplier_id" class="form-control">
                        @foreach ($suppliers as $s)
                            <option value="{{ $s->id }}"
                                {{ $s->id == $pembelian->supplier_id ? 'selected' : '' }}>
                                {{ $s->nama_supplier }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <hr>
                <h5>Detail Pembelian</h5>

                <table class="table table-bordered" id="tableEditPembelian">
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
                        @foreach ($detail as $i => $d)
                        <tr>
                            <td>
                                <select name="bahan_id[]" class="form-control">
                                    <option value="">-- Pilih Bahan --</option>
                                    @foreach ($bahan as $b)
                                        <option value="{{ $b->id }}"
                                            {{ $b->id == $d->bahan_id ? 'selected' : '' }}>
                                            {{ $b->nama_bahan }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>

                            <td>
                                <input type="text" name="bahan_baru[]" class="form-control"
                                       placeholder="Isi jika bahan baru">
                            </td>

                            <td>
                                <input type="number" name="qty[]" class="form-control"
                                       value="{{ $d->qty }}" min="1" required>
                            </td>

                            <td>
                                <input type="number" name="harga_satuan[]" class="form-control"
                                       value="{{ $d->harga_satuan }}" min="1" required>
                            </td>

                            <td>
                                <select name="satuan[]" class="form-control" required>
                                    <option value="pcs"   {{ $d->bahan->satuan == 'pcs' ? 'selected' : '' }}>pcs</option>
                                    <option value="kg"    {{ $d->bahan->satuan == 'kg' ? 'selected' : '' }}>kg</option>
                                    <option value="gram"  {{ $d->bahan->satuan == 'gram' ? 'selected' : '' }}>gram</option>
                                    <option value="liter" {{ $d->bahan->satuan == 'liter' ? 'selected' : '' }}>liter</option>
                                </select>
                            </td>

                            <td>
                                <button type="button" class="btn btn-danger btn-sm removeRow">X</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>

                </table>

                <button type="button" id="addEditRow" class="btn btn-secondary mt-2">
                    + Tambah Baris
                </button>

                <div class="mt-4 text-end">
                    <button class="btn btn-primary">Update Pembelian</button>
                </div>

            </div>
        </div>

    </form>
</div>


{{-- JAVASCRIPT UNTUK TAMBAH BARIS --}}
<script>
document.getElementById('addEditRow').addEventListener('click', function () {
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
            <input type="number" name="harga_satuan[]" class="form-control" min="1" required>
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

    document.querySelector("#tableEditPembelian tbody")
        .insertAdjacentHTML('beforeend', row);
});

document.addEventListener('click', function (e) {
    if (e.target.classList.contains('removeRow')) {
        e.target.closest('tr').remove();
    }
});
</script>

@endsection
