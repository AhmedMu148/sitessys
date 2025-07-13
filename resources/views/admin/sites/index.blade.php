@extends('admin.layouts.master')

@section('title', 'Sites Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Sites Management</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">All Sites</h5>
                        <a href="{{ route('admin.sites.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Create New Site
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Site Name</th>
                                    <th>Domain</th>
                                    <th>Owner</th>
                                    <th>Status</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($sites as $site)
                                <tr>
                                    <td>{{ $site->id }}</td>
                                    <td>{{ $site->site_name }}</td>
                                    <td>{{ $site->domain ?? '-' }}</td>
                                    <td>{{ $site->user->name ?? '-' }}</td>
                                    <td>
                                        <span class="badge badge-{{ $site->status ? 'success' : 'danger' }}">
                                            {{ $site->status ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>{{ $site->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('admin.sites.show', $site) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.sites.edit', $site) }}" class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.sites.destroy', $site) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">No sites found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{ $sites->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
