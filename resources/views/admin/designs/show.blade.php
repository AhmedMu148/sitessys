@extends('admin.layouts.master')

@section('title', 'View Design')

@section('content')
<div class="container-fluid p-0">
    <div class="row mb-2 mb-xl-3">
        <div class="col-auto d-none d-sm-block">
            <h3><strong>View</strong> Design</h3>
        </div>
        <div class="col-auto ms-auto text-end mt-n1">
            <a href="{{ route('admin.designs.index') }}" class="btn btn-light me-2">
                <i class="align-middle" data-feather="arrow-left"></i>
                Back to Designs
            </a>
            <a href="{{ route('admin.designs.edit', $design) }}" class="btn btn-primary">
                <i class="align-middle" data-feather="edit"></i>
                Edit Design
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Design Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Site</label>
                                <p class="mb-0">{{ $design->site->name }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Page</label>
                                <p class="mb-0">{{ $design->page->title }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Layout</label>
                                <p class="mb-0">{{ $design->layout->name }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Layout Type</label>
                                <p class="mb-0">{{ $design->layoutType->name }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Language</label>
                                <p class="mb-0">{{ $design->lang_code }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Sort Order</label>
                                <p class="mb-0">{{ $design->sort_order }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Status</label>
                        <p class="mb-0">
                            @if($design->status)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-danger">Inactive</span>
                            @endif
                        </p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Created At</label>
                        <p class="mb-0">{{ $design->created_at->format('M d, Y H:i') }}</p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Updated At</label>
                        <p class="mb-0">{{ $design->updated_at->format('M d, Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.designs.edit', $design) }}" class="btn btn-primary">
                            <i class="align-middle" data-feather="edit"></i>
                            Edit Design
                        </a>
                        
                        <form action="{{ route('admin.designs.destroy', $design) }}" method="POST" 
                              onsubmit="return confirm('Are you sure you want to delete this design?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="align-middle" data-feather="trash-2"></i>
                                Delete Design
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Section Data</h5>
                </div>
                <div class="card-body">
                    @if($design->data)
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Raw JSON Data</h6>
                                <pre class="bg-light p-3 rounded" style="max-height: 500px; overflow-y: auto;"><code>{{ json_encode($design->data, JSON_PRETTY_PRINT) }}</code></pre>
                            </div>
                            <div class="col-md-6">
                                <h6>Data Preview</h6>
                                <div class="bg-light p-3 rounded" style="max-height: 500px; overflow-y: auto;">
                                    @if(is_array($design->data))
                                        @foreach($design->data as $key => $value)
                                            <div class="mb-2">
                                                <strong>{{ ucfirst($key) }}:</strong>
                                                @if(is_array($value))
                                                    <div class="ms-3">
                                                        @foreach($value as $subKey => $subValue)
                                                            <div>
                                                                <em>{{ ucfirst($subKey) }}:</em>
                                                                @if(is_array($subValue))
                                                                    <small class="text-muted">[Array]</small>
                                                                @else
                                                                    {{ $subValue }}
                                                                @endif
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    {{ $value }}
                                                @endif
                                            </div>
                                        @endforeach
                                    @else
                                        <p class="text-muted">No data available</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @else
                        <p class="text-muted">No section data available</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
