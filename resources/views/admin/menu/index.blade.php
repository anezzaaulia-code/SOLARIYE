@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between mb-4">
        <h3>Data Menu</h3>
        <a href="{{ route('menu.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Tambah Menu
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card p-3">
        <table class="table table-bordered align-middle">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Foto</th>
                    <th>Nama</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th>Status</th>
                    <th width="180px">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($menus as $m)
                <tr>
                    <td>{{ $loop->iteration + ($menus->currentPage()-1)*$menus->perPage() }}</td>
                    <td>
                        @if($m->foto)
                            <img src="{{ asset('storage/'.$m->foto) }}" width="60" class="rounded">
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>{{ $m->nama }}</td>
                    <td>{{ $m->kategori->nama ?? '-' }}</td>
                    <td>Rp {{ number_format($m->harga, 0, ',', '.') }}</td>
                    <td>
                        <span class="badge
                            bg-{{ $m->status == 'tersedia' ? 'success' : ($m->status == 'habis' ? 'danger' : 'secondary') }}">
                            {{ ucfirst($m->status) }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('menu.edit', $m->id) }}" class="btn btn-warning btn-sm">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('menu.destroy', $m->id) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button onclick="return confirm('Hapus menu ini?')"
                                    class="btn btn-danger btn-sm">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{ $menus->links() }}
    </div>
</div>
@endsection
