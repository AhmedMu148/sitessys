@extends('admin.layouts.master')

@section('title', 'Designs Management')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1 class="h3 mb-0"><strong>Designs</strong> Management</h1>
                <a href="{{ route('admin.designs.create') }}" class="btn btn-primary">
                    <i class="align-middle" data-feather="plus"></i> Create New Design
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">All Designs</h5>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    
                    @if($designs->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Page</th>
                                        <th>Layout</th>
                                        <th>Type</th>
                                        <th>Language</th>
                                        <th>Sort Order</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($designs as $design)
                                        <tr>
                                            <td>{{ $design->id }}</td>
                                            <td>
                                                <strong>{{ $design->page->name }}</strong>
                                                <br><small class="text-muted">{{ $design->page->slug }}</small>
                                            </td>
                                            <td>
                                                <span class="fw-semibold">{{ $design->layout->name }}</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ 
                                                    $design->layoutType->name == 'nav' ? 'primary' : 
                                                    ($design->layoutType->name == 'section' ? 'success' : 'info') 
                                                }}">
                                                    {{ ucfirst($design->layoutType->name) }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary">{{ strtoupper($design->lang_code) }}</span>
                                            </td>
                                            <td>{{ $design->sort_order }}</td>
                                            <td>
                                                @if($design->status)
                                                    <span class="badge bg-success">Active</span>
                                                @else
                                                    <span class="badge bg-secondary">Inactive</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('admin.designs.show', $design) }}" 
                                                       class="btn btn-sm btn-outline-info">
                                                        <i class="align-middle" data-feather="eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.designs.edit', $design) }}" 
                                                       class="btn btn-sm btn-outline-primary">
                                                        <i class="align-middle" data-feather="edit"></i>
                                                    </a>
                                                    <form action="{{ route('admin.designs.destroy', $design) }}" 
                                                          method="POST" style="display: inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                                class="btn btn-sm btn-outline-danger"
                                                                onclick="return confirm('Are you sure you want to delete this design?')">
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
                            {{ $designs->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="align-middle" data-feather="grid" style="width: 64px; height: 64px; color: #6c757d;"></i>
                            <h4 class="mt-3">No Designs Found</h4>
                            <p class="text-muted">Get started by creating your first design.</p>
                            <a href="{{ route('admin.designs.create') }}" class="btn btn-primary">
                                <i class="align-middle" data-feather="plus"></i> Create First Design
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions Card -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card bg-light">
                <div class="card-header">
                    <h5 class="card-title mb-0">Page Structure Overview</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @php
                            $pageGroups = $designs->groupBy('page.name');
                        @endphp
                        @foreach($pageGroups as $pageName => $pageDesigns)
                            <div class="col-md-4 mb-3">
                                <div class="card h-100 border">
                                    <div class="card-header bg-white">
                                        <h6 class="mb-0 fw-bold">{{ $pageName }}</h6>
                                    </div>
                                    <div class="card-body p-3">
                                        @foreach($pageDesigns->sortBy('sort_order') as $design)
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <small class="text-muted">{{ $design->sort_order }}. {{ $design->layout->name }}</small>
                                                <span class="badge badge-sm bg-{{ 
                                                    $design->layoutType->name == 'nav' ? 'primary' : 
                                                    ($design->layoutType->name == 'section' ? 'success' : 'info') 
                                                }}">
                                                    {{ $design->layoutType->name }}
                                                </span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
