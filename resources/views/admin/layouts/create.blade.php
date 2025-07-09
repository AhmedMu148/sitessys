@extends('admin.layouts.master')

@section('title', 'Create Layout')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1 class="h3 mb-0"><strong>Create</strong> Layout</h1>
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
                <form action="{{ route('admin.layouts.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="type_id" class="form-label">Layout Type</label>
                                <select name="type_id" id="type_id" class="form-select @error('type_id') is-invalid @enderror">
                                    <option value="">Select Layout Type</option>
                                    @foreach($types as $type)
                                        <option value="{{ $type->id }}" {{ old('type_id') == $type->id ? 'selected' : '' }}>
                                            {{ ucfirst($type->name) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('type_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" 
                                       value="{{ old('name') }}" required placeholder="Enter layout name">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="preview_image" class="form-label">Preview Image</label>
                            <input type="file" name="preview_image" id="preview_image" 
                                   class="form-control @error('preview_image') is-invalid @enderror" 
                                   accept="image/*">
                            @error('preview_image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Upload a preview image for this layout (optional)</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="html_template" class="form-label">HTML Template</label>
                            <textarea name="html_template" id="html_template" rows="15" 
                                      class="form-control @error('html_template') is-invalid @enderror" 
                                      required placeholder="Enter HTML template code...">{{ old('html_template') }}</textarea>
                            @error('html_template')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                Use <code>{{ '{{ $data["key"] }}' }}</code> for dynamic content placeholders.
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input type="checkbox" name="status" class="form-check-input" 
                                       id="status" {{ old('status', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="status">Active Layout</label>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="align-middle" data-feather="save"></i> Create Layout
                        </button>
                        <a href="{{ route('admin.layouts.index') }}" class="btn btn-secondary">
                            <i class="align-middle" data-feather="x"></i> Cancel
                        </a>
                    </div>
                </form>
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
            
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">Template Variables</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6>Common Variables:</h6>
                        <ul class="list-unstyled small">
                            <li><code>{{ '{{ $data["title"] }}' }}</code> - Title text</li>
                            <li><code>{{ '{{ $data["content"] }}' }}</code> - Main content</li>
                            <li><code>{{ '{{ $data["image"] }}' }}</code> - Image URL</li>
                            <li><code>{{ '{{ $data["url"] }}' }}</code> - Link URL</li>
                        </ul>
                    </div>
                    <div class="alert alert-info">
                        <i class="align-middle" data-feather="info"></i>
                        <strong>Tip:</strong> Use descriptive variable names for better organization.
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add monospace font for code textarea
    const textarea = document.getElementById('html_template');
    textarea.style.fontFamily = 'Consolas, Monaco, "Courier New", monospace';
    textarea.style.fontSize = '14px';
    
    // Auto-resize textarea
    textarea.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = this.scrollHeight + 'px';
    });
});
</script>
@endsection
