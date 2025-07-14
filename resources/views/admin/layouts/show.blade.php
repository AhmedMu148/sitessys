@extends('admin.layouts.master')

@section('title', 'View Layout')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1 class="h3 mb-0"><strong>View</strong> Layout</h1>
                <a href="{{ route('admin.layouts.index') }}" class="btn btn-secondary">
                    <i class="align-middle" data-feather="arrow-left"></i> Back to Layouts
                </a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Layout Information</h5>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-4">ID</dt>
                        <dd class="col-sm-8">{{ $layout->id }}</dd>
                        <dt class="col-sm-4">Name</dt>
                        <dd class="col-sm-8 fw-bold">{{ $layout->name }}</dd>
                        <dt class="col-sm-4">Type</dt>
                        <dd class="col-sm-8">
                            <span class="badge bg-{{ $layout->type->name === 'nav' ? 'primary' : ($layout->type->name === 'section' ? 'success' : 'info') }}">
                                {{ ucfirst($layout->type->name) }}
                            </span>
                        </dd>
                        <dt class="col-sm-4">Status</dt>
                        <dd class="col-sm-8">
                            <span class="badge bg-{{ $layout->status ? 'success' : 'secondary' }}">
                                {{ $layout->status ? 'Active' : 'Inactive' }}
                            </span>
                        </dd>
                        <dt class="col-sm-4">Created At</dt>
                        <dd class="col-sm-8">{{ $layout->created_at->format('Y-m-d H:i') }}</dd>
                        <dt class="col-sm-4">Updated At</dt>
                        <dd class="col-sm-8">{{ $layout->updated_at->format('Y-m-d H:i') }}</dd>
                        <dt class="col-sm-4">Preview</dt>
                        <dd class="col-sm-8">
                            @if($layout->preview_image)
                                <img src="{{ asset('storage/'.$layout->preview_image) }}" class="img-thumbnail border-0 shadow-sm" style="max-width:140px;" alt="Preview">
                            @else
                                <span class="text-muted"><i data-feather="image"></i> No preview image</span>
                            @endif
                        </dd>
                        <dt class="col-sm-4">Description</dt>
                        <dd class="col-sm-8">{{ $layout->description ?: '-' }}</dd>
                        <dt class="col-sm-4">HTML Template</dt>
                        <dd class="col-sm-8">
                            <pre class="bg-light p-2 border rounded small text-break" style="white-space:pre-wrap;">{{ $layout->data }}</pre>
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Layout Types</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0">Navigation</h6>
                                <small class="text-muted">Site navigation menus</small>
                            </div>
                            <span class="badge bg-primary rounded-pill">nav</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0">Section</h6>
                                <small class="text-muted">Page content sections</small>
                            </div>
                            <span class="badge bg-success rounded-pill">section</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0">Footer</h6>
                                <small class="text-muted">Site footer content</small>
                            </div>
                            <span class="badge bg-info rounded-pill">footer</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    if(window.feather) feather.replace();
});
</script>
@endsection
