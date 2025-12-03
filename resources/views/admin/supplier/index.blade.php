@extends('layouts.admin')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0 text-gray-800">Data Supplier</h3>
        <a href="{{ route('supplier.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Tambah Supplier
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
                    <thead class="table-dark">
                        <tr>
                            <th width="5%">No</th>
                            <th>Nama Supplier</th>
                            <th>Kontak / HP</th>
                            <th>Email</th>
                            <th>Alamat</th>
                            <th width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($suppliers as $s)
                        <tr>
                            <td>{{ $loop->iteration + ($suppliers->currentPage()-1) * $suppliers->perPage() }}</td>
                            <td class="fw-bold">{{ $s->nama_supplier }}</td>
                            <td>{{ $s->kontak ?? '-' }}</td>
                            <td>{{ $s->email ?? '-' }}</td>
                            <td>{{ Str::limit($s->alamat, 50) ?? '-' }}</td>
                            <td>
                                <a href="{{ route('supplier.edit', $s->id) }}" class="btn btn-warning btn-sm">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('supplier.destroy', $s->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus supplier {{ $s->nama_supplier }}?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">Belum ada data supplier.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-3">
                {{ $suppliers->links() }}
            </div>
        </div>
    </div>
</div>
@endsection