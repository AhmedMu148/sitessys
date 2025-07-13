@extends('admin.layouts.master')

@section('title', 'Users Management')

@section('content')
<div class="container-fluid p-0">
    <div class="mb-3">
        <h1 class="h3 d-inline align-middle">Users Management</h1>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary float-end">
            <i data-feather="plus"></i> Create New User
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">All Users</h5>
                </div>
                <div class="card-body">
                    @if($users->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Sites Count</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $user)
                                        <tr>
                                            <td>{{ $user->id }}</td>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>
                                                <span class="badge bg-primary">{{ $user->role }}</span>
                                            </td>
                                            <td>{{ $user->sites->count() }}</td>
                                            <td>{{ $user->created_at->format('M d, Y') }}</td>
                                            <td>
                                                <a href="{{ route('admin.users.show', $user) }}" class="btn btn-sm btn-outline-primary">
                                                    <i data-feather="eye"></i>
                                                </a>
                                                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-outline-secondary">
                                                    <i data-feather="edit"></i>
                                                </a>
                                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                            onclick="return confirm('Are you sure?')">
                                                        <i data-feather="trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-center">
                            {{ $users->links() }}
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i data-feather="users" class="text-muted" style="width: 48px; height: 48px;"></i>
                            <h5 class="mt-3">No users found</h5>
                            <p class="text-muted">Get started by creating your first user.</p>
                            <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                                <i data-feather="plus"></i> Create User
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
