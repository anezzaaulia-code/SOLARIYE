@extends('layouts.admin')

@section('content')
<div class="container">
    <h3 class="mb-3">Daftar Supplier</h3>

    <a href="{{ route('supplier.create') }}" class="btn btn-primary mb-3">
        <i class="bi bi-plus-circle"></i> Tambah Supplier
    </a>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nama Supplier</th>
                <th>Kontak</th>
                <th>Alamat</th>
                <th width="150">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($suppliers as $s)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $s->nama_supplier }}</td>
                <td>{{ $s->kontak ?? '-' }}</td>
                <td>{{ $s->alamat ?? '-' }}</td>
                <td>
                    <a href="{{ route('supplier.edit', $s->id) }}" class="btn btn-warning btn-sm">
                        <i class="bi bi-pencil-square"></i>
                    </a>
                    <form action="{{ route('supplier.destroy', $s->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus supplier?')">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center">Belum ada supplier</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Pagination --}}
    <div class="mt-3">
        {{ $suppliers->links() }}
    </div>
</div>
@endsection
