@extends('admin.layouts.master')

@section('title', 'Pages Management')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1 class="h3 mb-0"><strong>Pages</strong> Management</h1>
                <a href="{{ route('admin.pages.create') }}" class="btn btn-primary">
                    <i class="align-middle" data-feather="plus"></i> Create New Page
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">All Pages</h5>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    
                    @if($pages->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Slug</th>
                                        <th>Sections</th>
                                        <th>Sort Order</th>
                                        <th>Status</th>
                                        <th>In Nav</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pages as $page)
                                        <tr>
                                            <td>{{ $page->id }}</td>
                                            <td>
                                                <strong>{{ $page->name }}</strong>
                                                @if($page->slug == 'home')
                                                    <span class="badge bg-primary ms-2">Homepage</span>
                                                @endif
                                            </td>
                                            <td>
                                                <code>{{ $page->slug }}</code>
                                            </td>
                                            <td>
                                                @if($page->sections)
                                                    <span class="badge bg-primary">{{ $page->sections->count() }}</span>
                                                    @if($page->sections->where('is_active', true)->count() > 0)
                                                        <span class="badge bg-success">{{ $page->sections->where('is_active', true)->count() }} active</span>
                                                    @endif
                                                @else
                                                    <span class="text-muted">No sections</span>
                                                @endif
                                            </td>
                                            <td>{{ $page->sort_order }}</td>
                                            <td>
                                                @if($page->is_active)
                                                    <span class="badge bg-success">Active</span>
                                                @else
                                                    <span class="badge bg-secondary">Inactive</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($page->show_in_nav)
                                                    <span class="badge bg-info">Visible</span>
                                                @else
                                                    <span class="badge bg-warning">Hidden</span>
                                                @endif
                                            </td>
                                            <td>{{ $page->created_at->format('M d, Y') }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="/" target="_blank" class="btn btn-sm btn-outline-secondary" title="Preview">
                                                        <i class="align-middle" data-feather="external-link"></i>
                                                    </a>
                                                    <a href="{{ route('admin.pages.show', $page) }}" 
                                                       class="btn btn-sm btn-outline-info" title="View Details">
                                                        <i class="align-middle" data-feather="eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.page-sections.index', ['page_id' => $page->id]) }}" 
                                                       class="btn btn-sm btn-outline-primary" title="Manage Sections">
                                                        <i class="align-middle" data-feather="layers"></i>
                                                    </a>
                                                    <a href="{{ route('admin.pages.edit', $page) }}" 
                                                       class="btn btn-sm btn-outline-warning" title="Edit Page">
                                                        <i class="align-middle" data-feather="edit"></i>
                                                    </a>
                                                    @if($page->slug !== 'home')
                                                    <form action="{{ route('admin.pages.destroy', $page) }}" 
                                                          method="POST" style="display: inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                                class="btn btn-sm btn-outline-danger"
                                                                title="Delete Page"
                                                                onclick="return confirm('Are you sure you want to delete this page?')">
                                                            <i class="align-middle" data-feather="trash-2"></i>
                                                        </button>
                                                    </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="d-flex justify-content-center">
                            {{ $pages->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="align-middle" data-feather="file-text" style="width: 64px; height: 64px; color: #6c757d;"></i>
                            <h4 class="mt-3">No Pages Found</h4>
                            <p class="text-muted">Get started by creating your first page.</p>
                            <a href="{{ route('admin.pages.create') }}" class="btn btn-primary">
                                <i class="align-middle" data-feather="plus"></i> Create First Page
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
