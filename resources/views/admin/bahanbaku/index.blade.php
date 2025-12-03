@extends('layouts.admin')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0 text-gray-800">Manajemen Bahan Baku</h3>
        <a href="{{ route('bahanbaku.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Tambah Bahan
        </a>
    </div>

    {{-- Alert Success --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Nama Bahan</th>
                            <th>Satuan</th>
                            <th>Stok Saat Ini</th>
                            <th>Batas Aman (Kuning)</th>
                            <th>Batas Bahaya (Merah)</th>
                            <th style="width: 15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($bahan as $index => $b)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $b->nama_bahan }}</td>
                            <td>{{ $b->satuan }}</td>
                            
                            {{-- Warna Stok jika menipis --}}
                            <td class="{{ $b->stok <= $b->batas_merah ? 'bg-danger text-white' : ($b->stok <= $b->batas_kuning ? 'bg-warning' : '') }}">
                                <strong>{{ $b->stok }}</strong>
                            </td>
                            
                            <td>{{ $b->batas_kuning }}</td>
                            <td>{{ $b->batas_merah }}</td>
                            <td>
                                <a href="{{ route('bahanbaku.edit', $b->id) }}" class="btn btn-warning btn-sm">
                                    <i class="bi bi-pencil"></i>
                                </a>

                                <form action="{{ route('bahanbaku.destroy', $b->id) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-danger btn-sm"
                                        onclick="return confirm('Yakin ingin menghapus bahan {{ $b->nama_bahan }}?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">Belum ada data bahan baku.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection