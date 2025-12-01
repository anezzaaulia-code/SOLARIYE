@extends('layouts.admin')

@section('content')
<div class="card shadow">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4>Stok Harian</h4>
        <a href="{{ route('stokharian.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Input Stok Hari Ini
        </a>
    </div>

    <div class="card-body">
        <table class="table table-striped text-center">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Bahan</th>
                    <th>Stok Awal</th>
                    <th>Stok Akhir</th>
                    <th>Pemakaian</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($stokharian as $item)
                @php
                    $pemakaian = $item->stok_awal - $item->stok_akhir;
                    $warna = 'success';
                    if ($item->stok_akhir <= 3) $warna = 'danger';
                    else if ($item->stok_akhir <= 5) $warna = 'warning';
                @endphp

                <tr>
                    <td>{{ $item->tanggal }}</td>
                    <td>{{ $item->bahan->nama_bahan }}</td>
                    <td>{{ $item->stok_awal }} {{ $item->bahan->satuan }}</td>
                    <td>{{ $item->stok_akhir }} {{ $item->bahan->satuan }}</td>
                    <td>{{ $pemakaian }} {{ $item->bahan->satuan }}</td>
                    <td>
                        <span class="badge bg-{{ $warna }}">
                            {{ ucfirst($warna) }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('stokharian.edit', $item->id) }}" class="btn btn-warning btn-sm">
                            <i class="bi bi-pencil"></i>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>

        </table>
    </div>
</div>
@endsection
