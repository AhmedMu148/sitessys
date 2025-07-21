@extends('admin.layouts.master')

@section('title', 'Templates')

@section('content')
    <div class="container-fluid p-0">
        <div class="mb-3">
            <h1 class="h3 d-inline align-middle">Templates</h1>
            <a href="{{ route('admin.templates.create') }}" class="btn btn-primary float-end">
                <i data-feather="plus"></i> Create Template
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Template Layouts</h5>
                    </div>
                    <div class="card-body">
                        @if($layouts->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Type</th>
                                            <th>Description</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($layouts as $layout)
                                            <tr>
                                                <td>{{ $layout->name }}</td>
                                                <td>
                                                    <span class="badge bg-primary">{{ ucfirst($layout->layout_type) }}</span>
                                                </td>
                                                <td>{{ $layout->description ?: '-' }}</td>
                                                <td>
                                                    @if($layout->status)
                                                        <span class="badge bg-success">Active</span>
                                                    @else
                                                        <span class="badge bg-secondary">Inactive</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a href="{{ route('admin.templates.show', $layout) }}" class="btn btn-sm btn-outline-primary">
                                                            <i data-feather="eye"></i>
                                                        </a>
                                                        <a href="{{ route('admin.templates.edit', $layout) }}" class="btn btn-sm btn-outline-primary">
                                                            <i data-feather="edit"></i>
                                                        </a>
                                                        <form method="POST" action="{{ route('admin.templates.destroy', $layout) }}" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?')">
                                                                <i data-feather="trash-2"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                            {{ $layouts->links() }}
                        @else
                            <div class="text-center py-4">
                                <i data-feather="layers" class="feather-xl text-muted mb-3"></i>
                                <p class="text-muted">No templates found. <a href="{{ route('admin.templates.create') }}">Create your first template</a>.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
