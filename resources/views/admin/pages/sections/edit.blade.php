@extends('admin.layouts.master')

@section('title', 'Edit Section - ' . $section->name)

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h1 class="h3 mb-0"><strong>Edit Section:</strong> {{ $section->name }}</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.pages.index') }}">Pages</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.pages.show', $page) }}">{{ $page->name }}</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.page-sections.index', ['page_id' => $page->id]) }}">Sections</a></li>
                            <li class="breadcrumb-item active">Edit Section</li>
                        </ol>
                    </nav>
                </div>
                <div>
                    <a href="{{ route('admin.page-sections.index', ['page_id' => $page->id]) }}" class="btn btn-secondary">
                        <i class="align-middle" data-feather="arrow-left"></i> Back to Sections
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Section Information</h5>
                </div>
                <form action="{{ route('admin.page-sections.update', ['page_id' => $page->id, 'section_id' => $section->id]) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Section Name</label>
                                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" 
                                       value="{{ old('name', $section->name) }}" required placeholder="Enter section name">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">A descriptive name for this section</div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="layout_id" class="form-label">Section Layout</label>
                                <select name="layout_id" id="layout_id" class="form-select @error('layout_id') is-invalid @enderror" required>
                                    <option value="">Select a layout</option>
                                    @foreach($availableLayouts as $layout)
                                        <option value="{{ $layout->id }}" 
                                                {{ old('layout_id', $section->layout_id) == $layout->id ? 'selected' : '' }}
                                                data-description="{{ $layout->description }}">
                                            {{ $layout->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('layout_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Choose the layout template for this section</div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description <small class="text-muted">(Optional)</small></label>
                            <textarea name="description" id="description" rows="2" class="form-control @error('description') is-invalid @enderror" 
                                      placeholder="Brief description of this section">{{ old('description', $section->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Content Data Section -->
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <label for="content_data" class="form-label">
                                    Content Data <small class="text-muted">(JSON Format - Optional)</small>
                                </label>
                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="formatJSON('content_data')">
                                    <i class="align-middle" data-feather="code"></i> Format JSON
                                </button>
                            </div>
                            <textarea name="content_data" id="content_data" rows="8" 
                                      class="form-control @error('content_data') is-invalid @enderror font-monospace"
                                      placeholder='{"title": "Section Title", "content": "Section content goes here"}'>{{ old('content_data', $section->content_data ? json_encode($section->content_data, JSON_PRETTY_PRINT) : '') }}</textarea>
                            @error('content_data')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                JSON data for dynamic content. This will be passed to the layout template.
                            </div>
                        </div>

                        <!-- Settings Section -->
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <label for="settings" class="form-label">
                                    Section Settings <small class="text-muted">(JSON Format - Optional)</small>
                                </label>
                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="formatJSON('settings')">
                                    <i class="align-middle" data-feather="code"></i> Format JSON
                                </button>
                            </div>
                            <textarea name="settings" id="settings" rows="6" 
                                      class="form-control @error('settings') is-invalid @enderror font-monospace"
                                      placeholder='{"background": "#ffffff", "padding": "50px", "animation": "fade-in"}'>{{ old('settings', $section->settings ? json_encode($section->settings, JSON_PRETTY_PRINT) : '') }}</textarea>
                            @error('settings')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                Configuration settings for styling and behavior.
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input type="checkbox" name="is_active" class="form-check-input" 
                                       id="is_active" {{ old('is_active', $section->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">Active Section</label>
                                <div class="form-text">Inactive sections won't be displayed on the page</div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="align-middle" data-feather="save"></i> Update Section
                        </button>
                        <a href="{{ route('admin.page-sections.index', ['page_id' => $page->id]) }}" class="btn btn-secondary">
                            <i class="align-middle" data-feather="x"></i> Cancel
                        </a>
                        <button type="button" class="btn btn-outline-danger ms-auto" onclick="confirmDelete()">
                            <i class="align-middle" data-feather="trash-2"></i> Delete Section
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="col-12 col-lg-4">
            <!-- Section Info -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Section Information</h5>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-5">Page:</dt>
                        <dd class="col-sm-7">{{ $page->name }}</dd>
                        
                        <dt class="col-sm-5">Sort Order:</dt>
                        <dd class="col-sm-7">{{ $section->sort_order }}</dd>
                        
                        <dt class="col-sm-5">Status:</dt>
                        <dd class="col-sm-7">
                            @if($section->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </dd>
                        
                        <dt class="col-sm-5">Created:</dt>
                        <dd class="col-sm-7">{{ $section->created_at->format('M d, Y H:i') }}</dd>
                        
                        <dt class="col-sm-5">Updated:</dt>
                        <dd class="col-sm-7">{{ $section->updated_at->format('M d, Y H:i') }}</dd>
                    </dl>
                </div>
            </div>

            <!-- Current Layout -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">Current Layout</h5>
                </div>
                <div class="card-body">
                    @if($section->layout)
                        <div class="border rounded p-3">
                            <h6>{{ $section->layout->name }}</h6>
                            <p class="text-muted small mb-2">{{ $section->layout->description ?? 'No description available' }}</p>
                            @if($section->layout->type)
                                <span class="badge bg-info">{{ $section->layout->type->name }}</span>
                            @endif
                        </div>
                    @else
                        <div class="text-center text-muted">
                            <i class="align-middle" data-feather="layout" style="width: 48px; height: 48px;"></i>
                            <p class="mt-2">No layout assigned</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <form action="{{ route('admin.page-sections.toggle-active', ['page_id' => $page->id, 'section_id' => $section->id]) }}" 
                              method="POST" style="display: inline;">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn {{ $section->is_active ? 'btn-warning' : 'btn-success' }} w-100">
                                <i class="align-middle" data-feather="{{ $section->is_active ? 'eye-off' : 'eye' }}"></i>
                                {{ $section->is_active ? 'Deactivate' : 'Activate' }} Section
                            </button>
                        </form>
                        
                        <button type="button" class="btn btn-outline-info" onclick="duplicateSection()">
                            <i class="align-middle" data-feather="copy"></i> Duplicate Section
                        </button>
                        
                        <a href="{{ route('admin.page-sections.create', ['page_id' => $page->id]) }}" class="btn btn-outline-primary">
                            <i class="align-middle" data-feather="plus"></i> Add New Section
                        </a>
                        
                        <button type="button" class="btn btn-outline-secondary" onclick="moveUp()">
                            <i class="align-middle" data-feather="arrow-up"></i> Move Up
                        </button>
                        
                        <button type="button" class="btn btn-outline-secondary" onclick="moveDown()">
                            <i class="align-middle" data-feather="arrow-down"></i> Move Down
                        </button>
                    </div>
                </div>
            </div>

            <!-- Content Data Preview -->
            @if($section->content_data)
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">Content Data Preview</h5>
                </div>
                <div class="card-body">
                    <pre class="bg-light p-2 rounded small" style="max-height: 200px; overflow-y: auto;"><code>{{ json_encode($section->content_data, JSON_PRETTY_PRINT) }}</code></pre>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete the section "<strong>{{ $section->name }}</strong>"?</p>
                    <p class="text-danger small">This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form action="{{ route('admin.page-sections.destroy', ['page_id' => $page->id, 'section_id' => $section->id]) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete Section</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const layoutSelect = document.getElementById('layout_id');
    
    // Handle layout selection change
    layoutSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        // Could add layout preview functionality here
    });

    // JSON validation
    const jsonTextareas = document.querySelectorAll('#content_data, #settings');
    jsonTextareas.forEach(textarea => {
        textarea.addEventListener('blur', function() {
            validateJSON(this);
        });
    });
});

function validateJSON(textarea) {
    const value = textarea.value.trim();
    if (value === '') return; // Empty is valid
    
    try {
        JSON.parse(value);
        textarea.classList.remove('is-invalid');
        // Remove any existing error message
        const errorDiv = textarea.parentElement.querySelector('.json-error');
        if (errorDiv) {
            errorDiv.remove();
        }
    } catch (e) {
        textarea.classList.add('is-invalid');
        // Add error message if not exists
        let errorDiv = textarea.parentElement.querySelector('.json-error');
        if (!errorDiv) {
            errorDiv = document.createElement('div');
            errorDiv.className = 'invalid-feedback json-error';
            textarea.parentElement.appendChild(errorDiv);
        }
        errorDiv.textContent = 'Invalid JSON format: ' + e.message;
    }
}

function formatJSON(textareaId) {
    const textarea = document.getElementById(textareaId);
    const value = textarea.value.trim();
    
    if (value === '') return;
    
    try {
        const parsed = JSON.parse(value);
        textarea.value = JSON.stringify(parsed, null, 2);
        validateJSON(textarea);
        showNotification('JSON formatted successfully!', 'success');
    } catch (e) {
        showNotification('Invalid JSON format. Please fix the errors first.', 'error');
    }
}

function confirmDelete() {
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    deleteModal.show();
}

function duplicateSection() {
    if (confirm('Do you want to create a copy of this section?')) {
        // Implementation for section duplication
        showNotification('Feature coming soon!', 'info');
    }
}

function moveUp() {
    if (confirm('Move this section up in the order?')) {
        // Implementation for moving section up
        showNotification('Feature coming soon!', 'info');
    }
}

function moveDown() {
    if (confirm('Move this section down in the order?')) {
        // Implementation for moving section down
        showNotification('Feature coming soon!', 'info');
    }
}

function showNotification(message, type) {
    // Create a simple notification
    const notification = document.createElement('div');
    notification.className = `alert alert-${getBootstrapClass(type)} alert-dismissible fade show position-fixed`;
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    notification.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(notification);
    
    // Auto-remove after 3 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.parentNode.removeChild(notification);
        }
    }, 3000);
}

function getBootstrapClass(type) {
    switch(type) {
        case 'success': return 'success';
        case 'error': return 'danger';
        case 'info': return 'info';
        case 'warning': return 'warning';
        default: return 'secondary';
    }
}
</script>
@endsection
