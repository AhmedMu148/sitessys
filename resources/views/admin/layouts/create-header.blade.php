@extends('admin.layouts.master')

@section('title', 'Create Header')

@section('content')
<div class="container-fluid p-0">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3 mb-0"><strong>Create</strong> Header</h1>
                <a href="{{ route('admin.headers-footers.index') }}" class="btn btn-secondary">
                    <i class="align-middle" data-feather="arrow-left"></i>
                    Back to Headers & Footers
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Header Details</h5>
                </div>
                <div class="card-body">                <form action="{{ route('admin.headers-footers.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="type" value="header">
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Header Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" 
                                   placeholder="e.g., Modern Header, Classic Navigation" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3" 
                                      placeholder="Brief description of this header design">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="data" class="form-label">HTML Content</label>
                            <textarea class="form-control @error('data') is-invalid @enderror" 
                                      id="data" name="data" rows="15" required>{{ old('data', $defaultContent ?? '') }}</textarea>
                            @error('data')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                Enter the HTML code for your header. You can use Bootstrap 5 classes.
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.headers-footers.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="align-middle" data-feather="save"></i>
                                Create Header
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="align-middle" data-feather="info"></i>
                        Tips
                    </h5>
                </div>
                <div class="card-body">
                    <h6>Header Design Tips:</h6>
                    <ul class="small">
                        <li>Use Bootstrap 5 classes for styling</li>
                        <li>Include responsive navigation toggle</li>
                        <li>Add your site logo or brand name</li>
                        <li>Keep navigation links simple and clear</li>
                        <li>Consider mobile-first design</li>
                    </ul>
                    
                    <h6 class="mt-4">Available Variables:</h6>
                    <ul class="small">
                        <li><code>{{ '{' }}{{ '{ $site->site_name }' }}{{ '}' }}</code> - Site name</li>
                        <li><code>{{ '{' }}{{ '{ asset("path") }' }}{{ '}' }}</code> - Asset URLs</li>
                        <li><code>{{ '{' }}{{ '{ route("name") }' }}{{ '}' }}</code> - Route URLs</li>
                    </ul>
                </div>
            </div>
            
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="align-middle" data-feather="eye"></i>
                        Preview
                    </h5>
                </div>
                <div class="card-body">
                    <div id="preview-container" class="border rounded p-2" style="min-height: 100px; background: #f8f9fa;">
                        <small class="text-muted">Preview will appear here as you type...</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Initialize CodeMirror for better HTML editing
if (typeof CodeMirror !== 'undefined') {
    var editor = CodeMirror.fromTextArea(document.getElementById('data'), {
        mode: 'text/html',
        theme: 'default',
        lineNumbers: true,
        autoCloseTags: true,
        matchBrackets: true,
        indentUnit: 2,
        tabSize: 2
    });
    
    // Update preview on change
    editor.on('change', function() {
        updatePreview();
    });
} else {
    // Fallback to regular textarea with preview
    document.getElementById('data').addEventListener('input', updatePreview);
}

function updatePreview() {
    var content = document.getElementById('data').value;
    var preview = document.getElementById('preview-container');
    
    // Simple preview (just show the HTML)
    if (content.trim()) {
        preview.innerHTML = content;
    } else {
        preview.innerHTML = '<small class="text-muted">Preview will appear here as you type...</small>';
    }
}

// Set default content if none provided
document.addEventListener('DOMContentLoaded', function() {
    var dataField = document.getElementById('data');
    if (!dataField.value.trim()) {
        dataField.value = `<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="/">
            {{ $site->site_name }}
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarMain">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="/">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="/about">About</a></li>
                <li class="nav-item"><a class="nav-link" href="/services">Services</a></li>
                <li class="nav-item"><a class="nav-link" href="/contact">Contact</a></li>
            </ul>
        </div>
    </div>
</nav>`;
        updatePreview();
    }
});
</script>
@endpush
