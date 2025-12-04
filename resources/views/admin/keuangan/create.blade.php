@extends('layouts.admin')

@section('content')
<div class="container-fluid p-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold m-0 {{ $jenis == 'pemasukan' ? 'text-success' : 'text-danger' }}">
                <i class="bi {{ $jenis == 'pemasukan' ? 'bi-graph-up-arrow' : 'bi-graph-down-arrow' }} me-2"></i>
                Tambah {{ ucfirst($jenis) }}
            </h4>
            <p class="text-muted small m-0">Catat transaksi keuangan baru.</p>
        </div>
        
        <a href="{{ $jenis == 'pengeluaran' ? route('pengeluaran.index') : route('pendapatan.index') }}" 
           class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header {{ $jenis == 'pemasukan' ? 'bg-success' : 'bg-danger' }} h-1"></div>
                
                <div class="card-body p-4">

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0 small">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('keuangan.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="jenis" value="{{ $jenis }}">

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-uppercase">Tanggal Transaksi</label>
                                <input type="date" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-uppercase">Nominal</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light fw-bold">Rp</span>
                                    <input type="number" name="nominal" class="form-control" placeholder="0" required>
                                </div>
                            </div>
                        </div>

                        @if($jenis == 'pengeluaran')
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-uppercase">Kategori Pengeluaran</label>
                            <select name="sumber" class="form-select" required>
                                <option value="" selected disabled>-- Pilih Kategori --</option>
                                <option value="suppliers">Pembayaran Supplier</option>
                                <option value="gaji">Gaji Karyawan</option>
                                <option value="operasional">Biaya Operasional (Listrik/Air/Internet)</option>
                                <option value="lainnya">Lain-lain</option>
                            </select>
                        </div>
                        @endif

                        <div class="mb-4">
                            <label class="form-label small fw-bold text-uppercase">Keterangan / Catatan</label>
                            <textarea name="keterangan" class="form-control" rows="3" placeholder="Contoh: Pembayaran listrik bulan Januari..."></textarea>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-end gap-2">
                            <button type="reset" class="btn btn-light border px-4">Reset</button>
                            <button type="submit" class="btn {{ $jenis == 'pemasukan' ? 'btn-success' : 'btn-danger' }} fw-bold px-4">
                                <i class="bi bi-save me-1"></i> Simpan Data
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection