@extends('layouts.admin')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between mb-3">
        <h4>Data Users</h4>
        <a href="{{ route('users.create') }}" class="btn btn-primary">
            + Add Users
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">

            {{-- Search --}}
            <div class="d-flex justify-content-end mb-2">
                <form action="{{ route('users.index') }}" method="GET">
                    <input type="text" name="search" class="form-control" 
                           placeholder="Search..." value="{{ request('search') }}">
                </form>
            </div>

            <table class="table table-bordered text-center align-middle">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th width="150px">Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($users as $u)
                    <tr>
                        <td>{{ $loop->iteration + ($users->currentPage()-1)*$users->perPage() }}</td>
                        <td>{{ $u->nama }}</td>
                        <td>{{ $u->email }}</td>
                        <td>{{ ucfirst($u->role) }}</td>

                        <td>
                            <a href="{{ route('users.edit', $u->id) }}" 
                               class="btn btn-warning btn-sm">
                                <i class="bi bi-pencil-square"></i>
                            </a>

                            <form action="{{ route('users.destroy', $u->id) }}" 
                                  method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button onclick="return confirm('Delete user?')" 
                                        class="btn btn-danger btn-sm">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5">Tidak ada data.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="d-flex justify-content-end">
                {{ $users->links() }}
            </div>

        </div>
    </div>

</div>
@endsection
