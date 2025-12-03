@extends('layouts.admin')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0 text-gray-800">Laporan Stok Harian</h3>
        <a href="{{ route('stokharian.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Input Stok Hari Ini
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
                <table class="table table-bordered table-striped table-hover align-middle">
                    <thead class="table-dark text-center">
                        <tr>
                            <th>Tanggal</th>
                            <th>Nama Bahan</th>
                            <th>Stok Awal</th>
                            <th>Stok Akhir</th>
                            <th>Pemakaian</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($stokharian as $item)
                        @php
                            // Hitung pemakaian manual
                            $pemakaian = $item->stok_awal - $item->stok_akhir;
                            
                            // Tentukan warna badge
                            $badgeClass = 'bg-secondary';
                            $statusLabel = ucfirst($item->status_warna);

                            if ($item->status_warna == 'aman') {
                                $badgeClass = 'bg-success';
                            } elseif ($item->status_warna == 'menipis') {
                                $badgeClass = 'bg-warning text-dark';
                            } elseif ($item->status_warna == 'habis') {
                                $badgeClass = 'bg-danger';
                            }
                        @endphp

                        <tr class="text-center">
                            <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</td>
                            <td class="text-start fw-bold">{{ $item->bahan->nama_bahan ?? '-' }}</td>
                            <td>{{ $item->stok_awal }} {{ $item->bahan->satuan ?? '' }}</td>
                            <td>{{ $item->stok_akhir }} {{ $item->bahan->satuan ?? '' }}</td>
                            
                            {{-- LOGIKAN PEMAKAIAN YANG SUDAH DIPERBAIKI --}}
                            <td>
                                @if($pemakaian > 0)
                                    {{-- Stok Berkurang --}}
                                    <span class="text-danger fw-bold">-{{ $pemakaian }}</span>
                                @elseif($pemakaian < 0)
                                    {{-- Stok Bertambah (Plus) --}}
                                    <span class="text-success fw-bold">+{{ abs($pemakaian) }}</span>
                                @else
                                    {{-- Tidak ada perubahan (0) --}}
                                    <span class="text-secondary fw-bold">0</span>
                                @endif
                            </td>

                            <td>
                                <span class="badge {{ $badgeClass }}">
                                    {{ $statusLabel }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('stokharian.edit', $item->id) }}" class="btn btn-warning btn-sm">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-3 text-muted">Belum ada data stok harian.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection