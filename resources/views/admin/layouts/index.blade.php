@extends('admin.layouts.master')

@section('title', 'Layouts Management')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1 class="h3 mb-0"><strong>Layouts</strong> Management</h1>
                <a href="{{ route('admin.layouts.create') }}" class="btn btn-primary">
                    <i class="align-middle" data-feather="plus"></i> Create New Layout
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">All Layouts</h5>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    
                    @if($layouts->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Type</th>
                                        <th>Preview</th>
                                        <th>Status</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($layouts as $layout)
                                        <tr>
                                            <td>{{ $layout->id }}</td>
                                            <td>
                                                <strong>{{ $layout->name }}</strong>
                                                @if($layout->description)
                                                    <br><small class="text-muted">{{ Str::limit($layout->description, 50) }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ 
                                                    $layout->type->name == 'nav' ? 'primary' : 
                                                    ($layout->type->name == 'section' ? 'success' : 'info') 
                                                }}">
                                                    {{ ucfirst($layout->type->name) }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($layout->preview_image)
                                                    <img src="{{ asset('storage/' . $layout->preview_image) }}" 
                                                         alt="Preview" class="img-thumbnail" style="max-width: 80px;">
                                                @else
                                                    <div class="text-center" style="width: 80px; height: 50px; background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 4px; display: flex; align-items: center; justify-content: center;">
                                                        <i class="align-middle text-muted" data-feather="image"></i>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                @if($layout->status)
                                                    <span class="badge bg-success">Active</span>
                                                @else
                                                    <span class="badge bg-secondary">Inactive</span>
                                                @endif
                                            </td>
                                            <td>{{ $layout->created_at->format('M d, Y') }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('admin.layouts.show', $layout) }}" 
                                                       class="btn btn-sm btn-outline-info">
                                                        <i class="align-middle" data-feather="eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.layouts.edit', $layout) }}" 
                                                       class="btn btn-sm btn-outline-primary">
                                                        <i class="align-middle" data-feather="edit"></i>
                                                    </a>
                                                    <form action="{{ route('admin.layouts.destroy', $layout) }}" 
                                                          method="POST" style="display: inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                                class="btn btn-sm btn-outline-danger"
                                                                onclick="return confirm('Are you sure you want to delete this layout?')">
                                                            <i class="align-middle" data-feather="trash-2"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="d-flex justify-content-center">
                            {{ $layouts->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="align-middle" data-feather="layout" style="width: 64px; height: 64px; color: #6c757d;"></i>
                            <h4 class="mt-3">No Layouts Found</h4>
                            <p class="text-muted">Get started by creating your first layout.</p>
                            <a href="{{ route('admin.layouts.create') }}" class="btn btn-primary">
                                <i class="align-middle" data-feather="plus"></i> Create First Layout
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
