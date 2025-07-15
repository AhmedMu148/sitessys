@extends('admin.layouts.master')

@section('title', 'Edit Page')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1 class="h3 mb-0"><strong>Edit</strong> Page</h1>
                <div>
                    <a href="{{ route('admin.pages.show', $page) }}" class="btn btn-info">
                        <i class="align-middle" data-feather="eye"></i> View Page
                    </a>
                    <a href="{{ route('admin.pages.index') }}" class="btn btn-secondary">
                        <i class="align-middle" data-feather="arrow-left"></i> Back to Pages
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Page Information</h5>
                </div>
                <form action="{{ route('admin.pages.update', $page) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Page Name</label>
                                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" 
                                       value="{{ old('name', $page->name) }}" required placeholder="Enter page name">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="slug" class="form-label">Slug</label>
                                <input type="text" name="slug" id="slug" class="form-control @error('slug') is-invalid @enderror" 
                                       value="{{ old('slug', $page->slug) }}" required placeholder="page-slug"
                                       {{ $page->slug === 'home' ? 'readonly' : '' }}>
                                @error('slug')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">URL-friendly version of the page name
                                    @if($page->slug === 'home')
                                        <br><strong>Note:</strong> Home page slug cannot be changed.
                                    @endif
                                </div>
                            </div>
                        </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="sort_order" class="form-label">Sort Order</label>
                                <input type="number" name="sort_order" id="sort_order" class="form-control @error('sort_order') is-invalid @enderror" 
                                       value="{{ old('sort_order', $page->sort_order) }}" min="0" placeholder="0">
                                @error('sort_order')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Higher numbers appear first</div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea name="description" id="description" rows="3" class="form-control @error('description') is-invalid @enderror" 
                                      placeholder="Optional page description">{{ old('description', $page->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input type="checkbox" name="is_active" class="form-check-input" 
                                       id="is_active" {{ old('is_active', $page->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">Active Page</label>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input type="checkbox" name="show_in_nav" class="form-check-input" 
                                       id="show_in_nav" {{ old('show_in_nav', $page->show_in_nav) ? 'checked' : '' }}>
                                <label class="form-check-label" for="show_in_nav">Show in Navigation</label>
                                <div class="form-text">Uncheck to hide this page from the main navigation menu</div>
                            </div>
                        </div>

                        <!-- Meta Data Section -->
                        <div class="mb-3">
                            <label for="meta_title" class="form-label">Meta Title</label>
                            <input type="text" name="meta_data[title]" id="meta_title" class="form-control" 
                                   value="{{ old('meta_data.title', $page->meta_data['title'] ?? '') }}" 
                                   placeholder="SEO page title">
                            <div class="form-text">Recommended: 50-60 characters</div>
                        </div>

                        <div class="mb-3">
                            <label for="meta_description" class="form-label">Meta Description</label>
                            <textarea name="meta_data[description]" id="meta_description" rows="2" class="form-control" 
                                      placeholder="SEO page description">{{ old('meta_data.description', $page->meta_data['description'] ?? '') }}</textarea>
                            <div class="form-text">Recommended: 150-160 characters</div>
                        </div>

                        <div class="mb-3">
                            <label for="meta_keywords" class="form-label">Meta Keywords</label>
                            <input type="text" name="meta_data[keywords]" id="meta_keywords" class="form-control" 
                                   value="{{ old('meta_data.keywords', $page->meta_data['keywords'] ?? '') }}" 
                                   placeholder="keyword1, keyword2, keyword3">
                            <div class="form-text">Comma-separated keywords</div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="align-middle" data-feather="save"></i> Update Page
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
                    <h5 class="card-title mb-0">Page Sections</h5>
                </div>
                <div class="card-body">
                    @if($page->sections && $page->sections->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($page->sections->sortBy('sort_order') as $section)
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">{{ $section->name }}</h6>
                                        <small class="text-muted">Order: {{ $section->sort_order }}</small>
                                    </div>
                                    <div>
                                        @if($section->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-secondary">Inactive</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-3">
                            <a href="{{ route('admin.page-sections.index', ['page_id' => $page->id]) }}" class="btn btn-outline-primary btn-sm">
                                <i class="align-middle" data-feather="edit"></i> Manage Sections
                            </a>
                        </div>
                    @else
                        <div class="text-center">
                            <p class="text-muted">No sections added yet.</p>
                            <a href="{{ route('admin.page-sections.create', ['page_id' => $page->id]) }}" class="btn btn-outline-primary btn-sm">
                                <i class="align-middle" data-feather="plus"></i> Add First Section
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">Page Statistics</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="metric">
                                <span class="metric-value h3">{{ $page->sections ? $page->sections->count() : 0 }}</span>
                                <span class="metric-label text-muted d-block">Sections</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="metric">
                                <span class="metric-value h3">{{ $page->sections ? $page->sections->where('is_active', true)->count() : 0 }}</span>
                                <span class="metric-label text-muted d-block">Active</span>
                            </div>
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
    // Auto-generate slug from page name (only if slug is empty)
    const nameInput = document.getElementById('name');
    const slugInput = document.getElementById('slug');
    const originalSlug = slugInput.value;
    
    nameInput.addEventListener('input', function() {
        const name = this.value;
        const slug = name.toLowerCase()
            .replace(/[^a-z0-9\s-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-')
            .trim('-');
        
        // Only auto-update if user hasn't manually changed the slug
        if (slugInput.dataset.manualEdit !== 'true' && originalSlug === slugInput.value) {
            slugInput.value = slug;
        }
    });
    
    slugInput.addEventListener('input', function() {
        this.dataset.manualEdit = 'true';
    });
});
</script>
@endsection
