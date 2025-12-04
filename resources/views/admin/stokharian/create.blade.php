@extends('layouts.admin')

@section('content')
<div class="container-fluid p-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-dark m-0">Input Stok Harian</h4>
            <p class="text-muted small m-0">Catat stok shift: stok dibawa, stok akhir, dan hitung otomatis pemakaian.</p>
        </div>
        <a href="{{ route('stokharian.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">

                    {{-- Validasi Error --}}
                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show small">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    {{-- FORM --}}
                    <form action="{{ route('stokharian.store') }}" method="POST">
                        @csrf

                        {{-- PILIH BAHAN BAKU --}}
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-uppercase">Bahan Baku <span class="text-danger">*</span></label>
                            <select name="bahan_id" id="bahanSelect" class="form-select @error('bahan_id') is-invalid @enderror" required>
                                <option value="" disabled selected>-- Pilih Bahan --</option>
                                @foreach ($bahan as $b)
                                    <option value="{{ $b->id }}" data-stok="{{ $b->stok }}">
                                        {{ $b->nama_bahan }} — Stok Gudang: {{ $b->stok }} {{ $b->satuan }}
                                    </option>
                                @endforeach
                            </select>
                            @error('bahan_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- STOK GUDANG --}}
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Stok Gudang Saat Ini:</label>
                            <div id="stokGudangDisplay" class="fw-bold text-success">-</div>
                        </div>

                        {{-- TANGGAL --}}
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-uppercase">Tanggal <span class="text-danger">*</span></label>
                            <input type="date"
                                   name="tanggal"
                                   class="form-control @error('tanggal') is-invalid @enderror"
                                   value="{{ old('tanggal', date('Y-m-d')) }}"
                                   required>
                            @error('tanggal') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- INPUT STOK SHIFT --}}
                        <div class="row g-3 mb-3">

                            {{-- STOK MASUK --}}
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-uppercase text-success">
                                    Stok Awal <span class="text-danger">*</span>
                                </label>
                                <input type="number"
                                       name="stok_masuk"
                                       id="stokMasuk"
                                       class="form-control border-success"
                                       min="0"
                                       required>
                                <div class="form-text small">Jumlah yang keluar dari gudang.</div>
                            </div>

                            {{-- STOK AKHIR --}}
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-uppercase text-primary">
                                    Stok Akhir <span class="text-danger">*</span>
                                </label>
                                <input type="number"
                                       name="stok_akhir"
                                       id="stokAkhir"
                                       class="form-control border-primary"
                                       min="0"
                                       required>
                                <div class="form-text small">Sisa saat tutup toko.</div>
                            </div>

                        </div>

                        {{-- AUTO HIT PEMAKAIAN --}}
                        <div class="alert alert-info py-2 small">
                            <strong>Pemakaian Otomatis:</strong>
                            <span id="pemakaianDisplay" class="fw-bold">0</span>
                        </div>

                        {{-- WARNING IF SALAH INPUT --}}
                        <div id="warningBox" class="alert alert-warning small d-none">
                            <i class="bi bi-exclamation-triangle"></i>
                            Stok akhir tidak boleh lebih besar dari stok masuk.
                        </div>

                        <hr>

                        {{-- BUTTON --}}
                        <div class="d-flex justify-content-end gap-2">
                            <button type="reset" class="btn btn-light border px-4">Reset</button>
                            <button type="submit" id="submitBtn" class="btn btn-primary fw-bold px-4">
                                <i class="bi bi-save me-1"></i> Simpan Data
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>

</div>

{{-- SCRIPT --}}
<script>
document.addEventListener("DOMContentLoaded", function() {

    const bahanSelect = document.getElementById('bahanSelect');
    const stokGudangDisplay = document.getElementById('stokGudangDisplay');
    const stokMasuk = document.getElementById('stokMasuk');
    const stokAkhir = document.getElementById('stokAkhir');
    const pemakaianDisplay = document.getElementById('pemakaianDisplay');
    const warningBox = document.getElementById('warningBox');
    const submitBtn = document.getElementById('submitBtn');

    // Tampilkan stok gudang saat bahan dipilih
    bahanSelect.addEventListener('change', function() {
        let stok = this.options[this.selectedIndex].getAttribute('data-stok');
        stokGudangDisplay.textContent = stok + " unit";
    });

    function hitungPemakaian() {
        let masuk = parseFloat(stokMasuk.value) || 0;
        let akhir = parseFloat(stokAkhir.value) || 0;

        let pemakaian = masuk - akhir;
        if (pemakaian < 0) pemakaian = 0;

        pemakaianDisplay.textContent = pemakaian;

        // Validasi stok akhir lebih besar dari stok masuk
        if (akhir > masuk) {
            warningBox.classList.remove('d-none');
            submitBtn.disabled = true;
        } else {
            warningBox.classList.add('d-none');
            submitBtn.disabled = false;
        }
    }

    stokMasuk.addEventListener('input', hitungPemakaian);
    stokAkhir.addEventListener('input', hitungPemakaian);
});
</script>

@endsection
