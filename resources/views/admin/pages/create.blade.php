@extends('admin.layouts.master')

@section('title', 'Create Page')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1 class="h3 mb-0"><strong>Create</strong> Page</h1>
                <a href="{{ route('admin.pages.index') }}" class="btn btn-secondary">
                    <i class="align-middle" data-feather="arrow-left"></i> Back to Pages
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Page Information</h5>
                </div>
                <form action="{{ route('admin.pages.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="site_id" class="form-label">Site</label>
                                <select name="site_id" id="site_id" class="form-select @error('site_id') is-invalid @enderror">
                                    <option value="">Select Site</option>
                                    @foreach($sites as $site)
                                        <option value="{{ $site->id }}" {{ old('site_id') == $site->id ? 'selected' : '' }}>
                                            {{ $site->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('site_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Page Name</label>
                                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" 
                                       value="{{ old('name') }}" required placeholder="Enter page name">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="slug" class="form-label">Slug</label>
                                <input type="text" name="slug" id="slug" class="form-control @error('slug') is-invalid @enderror" 
                                       value="{{ old('slug') }}" required placeholder="page-slug">
                                @error('slug')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">URL-friendly version of the page name</div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="sort_order" class="form-label">Sort Order</label>
                                <input type="number" name="sort_order" id="sort_order" class="form-control @error('sort_order') is-invalid @enderror" 
                                       value="{{ old('sort_order', 0) }}" min="0" placeholder="0">
                                @error('sort_order')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Higher numbers appear first</div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input type="checkbox" name="status" class="form-check-input" 
                                       id="status" {{ old('status', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="status">Active Page</label>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="align-middle" data-feather="save"></i> Create Page
                        </button>
                        <a href="{{ route('admin.pages.index') }}" class="btn btn-secondary">
                            <i class="align-middle" data-feather="x"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="col-12 col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Page Tips</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="align-middle" data-feather="info"></i>
                        <strong>Slug Guidelines:</strong>
                        <ul class="mb-0 mt-2">
                            <li>Use lowercase letters</li>
                            <li>Replace spaces with hyphens</li>
                            <li>No special characters</li>
                            <li>Keep it short and descriptive</li>
                        </ul>
                    </div>
                    
                    <div class="alert alert-warning">
                        <i class="align-middle" data-feather="alert-triangle"></i>
                        <strong>Note:</strong> After creating a page, you can add layouts and designs to customize its appearance.
                    </div>
                </div>
            </div>
            
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">Common Page Types</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item">
                            <h6 class="mb-1">Homepage</h6>
                            <small class="text-muted">Main landing page (slug: home)</small>
                        </div>
                        <div class="list-group-item">
                            <h6 class="mb-1">About</h6>
                            <small class="text-muted">Company information (slug: about)</small>
                        </div>
                        <div class="list-group-item">
                            <h6 class="mb-1">Contact</h6>
                            <small class="text-muted">Contact information (slug: contact)</small>
                        </div>
                        <div class="list-group-item">
                            <h6 class="mb-1">Services</h6>
                            <small class="text-muted">Services offered (slug: services)</small>
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
    // Auto-generate slug from page name
    const nameInput = document.getElementById('name');
    const slugInput = document.getElementById('slug');
    
    nameInput.addEventListener('input', function() {
        const name = this.value;
        const slug = name.toLowerCase()
            .replace(/[^a-z0-9\s-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-')
            .trim('-');
        
        if (slugInput.value === '' || slugInput.dataset.manualEdit !== 'true') {
            slugInput.value = slug;
        }
    });
    
    slugInput.addEventListener('input', function() {
        this.dataset.manualEdit = 'true';
    });
});
</script>
@endsection
