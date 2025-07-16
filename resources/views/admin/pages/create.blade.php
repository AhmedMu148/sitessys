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
                                <label for="name" class="form-label">Page Name</label>
                                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" 
                                       value="{{ old('name') }}" required placeholder="Enter page name">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="slug" class="form-label">Slug</label>
                                <input type="text" name="slug" id="slug" class="form-control @error('slug') is-invalid @enderror" 
                                       value="{{ old('slug') }}" required placeholder="page-slug">
                                @error('slug')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">URL-friendly version of the page name</div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="sort_order" class="form-label">Sort Order</label>
                                <input type="number" name="sort_order" id="sort_order" class="form-control @error('sort_order') is-invalid @enderror" 
                                       value="{{ old('sort_order', 0) }}" min="0" placeholder="0">
                                @error('sort_order')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Higher numbers appear first</div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="description" class="form-label">Description <small class="text-muted">(Optional)</small></label>
                                <input type="text" name="description" id="description" class="form-control @error('description') is-invalid @enderror" 
                                       value="{{ old('description') }}" placeholder="Brief page description">
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Meta Data Section -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="meta_title" class="form-label">Meta Title <small class="text-muted">(SEO)</small></label>
                                <input type="text" name="meta_data[title]" id="meta_title" class="form-control" 
                                       value="{{ old('meta_data.title') }}" placeholder="SEO page title">
                                <div class="form-text">Recommended: 50-60 characters</div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="meta_keywords" class="form-label">Meta Keywords <small class="text-muted">(SEO)</small></label>
                                <input type="text" name="meta_data[keywords]" id="meta_keywords" class="form-control" 
                                       value="{{ old('meta_data.keywords') }}" placeholder="keyword1, keyword2, keyword3">
                                <div class="form-text">Comma-separated keywords</div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="meta_description" class="form-label">Meta Description <small class="text-muted">(SEO)</small></label>
                            <textarea name="meta_data[description]" id="meta_description" rows="2" class="form-control" 
                                      placeholder="SEO page description">{{ old('meta_data.description') }}</textarea>
                            <div class="form-text">Recommended: 150-160 characters</div>
                        </div>
                        
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input type="checkbox" name="is_active" class="form-check-input" 
                                       id="is_active" {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">Active Page</label>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input type="checkbox" name="show_in_nav" class="form-check-input" 
                                       id="show_in_nav" {{ old('show_in_nav', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="show_in_nav">Show in Navigation</label>
                                <div class="form-text">Uncheck to hide this page from the main navigation menu</div>
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
                    <h5 class="card-title mb-0">Current Site</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar me-3">
                            <div class="avatar-title bg-primary rounded">
                                {{ strtoupper(substr($site->name, 0, 2)) }}
                            </div>
                        </div>
                        <div>
                            <h6 class="mb-0">{{ $site->name }}</h6>
                            <small class="text-muted">{{ $site->domain ?? 'No domain set' }}</small>
                        </div>
                    </div>
                    <div class="mt-2">
                        <span class="badge bg-success">Active Site</span>
                    </div>
                </div>
            </div>
            
            <div class="card mt-3">
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
