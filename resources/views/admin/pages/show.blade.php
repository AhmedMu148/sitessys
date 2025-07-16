@extends('admin.layouts.master')

@section('title', 'View Page - ' . $page->name)

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1 class="h3 mb-0"><strong>{{ $page->name }}</strong> 
                    @if($page->slug == 'home')
                        <span class="badge bg-primary ms-2">Homepage</span>
                    @endif
                </h1>
                <div>
                    <a href="{{ route('admin.pages.edit', $page) }}" class="btn btn-primary">
                        <i class="align-middle" data-feather="edit"></i> Edit Page
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
            <!-- Page Information -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Page Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <dl class="row">
                                <dt class="col-sm-4">Name:</dt>
                                <dd class="col-sm-8">{{ $page->name }}</dd>
                                
                                <dt class="col-sm-4">Slug:</dt>
                                <dd class="col-sm-8"><code>{{ $page->slug }}</code></dd>
                                
                                <dt class="col-sm-4">Site:</dt>
                                <dd class="col-sm-8">{{ $page->site->name ?? 'N/A' }}</dd>
                                
                                <dt class="col-sm-4">Sort Order:</dt>
                                <dd class="col-sm-8">{{ $page->sort_order }}</dd>
                            </dl>
                        </div>
                        <div class="col-md-6">
                            <dl class="row">
                                <dt class="col-sm-4">Status:</dt>
                                <dd class="col-sm-8">
                                    @if($page->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </dd>
                                
                                <dt class="col-sm-4">In Navigation:</dt>
                                <dd class="col-sm-8">
                                    @if($page->show_in_nav)
                                        <span class="badge bg-info">Visible</span>
                                    @else
                                        <span class="badge bg-warning">Hidden</span>
                                    @endif
                                </dd>
                                
                                <dt class="col-sm-4">Created:</dt>
                                <dd class="col-sm-8">{{ $page->created_at->format('M d, Y H:i') }}</dd>
                                
                                <dt class="col-sm-4">Updated:</dt>
                                <dd class="col-sm-8">{{ $page->updated_at->format('M d, Y H:i') }}</dd>
                            </dl>
                        </div>
                    </div>
                    
                    @if($page->description)
                    <div class="row mt-3">
                        <div class="col-12">
                            <h6>Description:</h6>
                            <p class="text-muted">{{ $page->description }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- SEO Information -->
            @if($page->meta_data && (isset($page->meta_data['title']) || isset($page->meta_data['description']) || isset($page->meta_data['keywords'])))
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">SEO Information</h5>
                </div>
                <div class="card-body">
                    @if(isset($page->meta_data['title']))
                        <div class="mb-3">
                            <strong>Meta Title:</strong>
                            <p class="text-muted mb-0">{{ $page->meta_data['title'] }}</p>
                        </div>
                    @endif
                    
                    @if(isset($page->meta_data['description']))
                        <div class="mb-3">
                            <strong>Meta Description:</strong>
                            <p class="text-muted mb-0">{{ $page->meta_data['description'] }}</p>
                        </div>
                    @endif
                    
                    @if(isset($page->meta_data['keywords']))
                        <div class="mb-3">
                            <strong>Meta Keywords:</strong>
                            <p class="text-muted mb-0">{{ $page->meta_data['keywords'] }}</p>
                        </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Page Sections -->
            <div class="card mt-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Page Sections</h5>
                    <a href="{{ route('admin.page-sections.create', ['page_id' => $page->id]) }}" class="btn btn-primary btn-sm">
                        <i class="align-middle" data-feather="plus"></i> Add Section
                    </a>
                </div>
                <div class="card-body">
                    @if($page->sections && $page->sections->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Order</th>
                                        <th>Name</th>
                                        <th>Layout</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="sections-table" data-page-id="{{ $page->id }}">
                                    @foreach($page->sections->sortBy('sort_order') as $section)
                                        <tr data-section-id="{{ $section->id }}">
                                            <td>
                                                <span class="badge bg-secondary">{{ $section->sort_order }}</span>
                                                <i class="align-middle ms-2 text-muted" data-feather="menu" style="cursor: move;"></i>
                                            </td>
                                            <td>
                                                <strong>{{ $section->name }}</strong>
                                                @if($section->description)
                                                    <br><small class="text-muted">{{ $section->description }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                {{ $section->layout->name ?? 'N/A' }}
                                                @if($section->layout && $section->layout->type)
                                                    <br><small class="text-muted">{{ $section->layout->type->name }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                @if($section->is_active)
                                                    <span class="badge bg-success">Active</span>
                                                @else
                                                    <span class="badge bg-secondary">Inactive</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('admin.page-sections.edit', ['page_id' => $page->id, 'section_id' => $section->id]) }}" 
                                                       class="btn btn-sm btn-outline-primary">
                                                        <i class="align-middle" data-feather="edit"></i>
                                                    </a>
                                                    <form action="{{ route('admin.page-sections.toggle-active', ['page_id' => $page->id, 'section_id' => $section->id]) }}" 
                                                          method="POST" style="display: inline;">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="btn btn-sm btn-outline-{{ $section->is_active ? 'warning' : 'success' }}">
                                                            <i class="align-middle" data-feather="{{ $section->is_active ? 'eye-off' : 'eye' }}"></i>
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('admin.page-sections.destroy', ['page_id' => $page->id, 'section_id' => $section->id]) }}" 
                                                          method="POST" style="display: inline;" 
                                                          onsubmit="return confirm('Are you sure you want to delete this section?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger">
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
                        
                        <div class="mt-3">
                            <a href="{{ route('admin.page-sections.index', ['page_id' => $page->id]) }}" class="btn btn-outline-primary">
                                <i class="align-middle" data-feather="settings"></i> Manage All Sections
                            </a>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="align-middle" data-feather="layers" style="width: 48px; height: 48px; color: #6c757d;"></i>
                            <h5 class="mt-3">No Sections Added</h5>
                            <p class="text-muted">Start building your page by adding sections.</p>
                            <a href="{{ route('admin.page-sections.create', ['page_id' => $page->id]) }}" class="btn btn-primary">
                                <i class="align-middle" data-feather="plus"></i> Add First Section
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-12 col-lg-4">
            <!-- Quick Actions -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="/" target="_blank" class="btn btn-outline-secondary">
                            <i class="align-middle" data-feather="external-link"></i> Preview Page
                        </a>
                        <a href="{{ route('admin.page-sections.create', ['page_id' => $page->id]) }}" class="btn btn-outline-primary">
                            <i class="align-middle" data-feather="plus"></i> Add Section
                        </a>
                        <a href="{{ route('admin.page-sections.index', ['page_id' => $page->id]) }}" class="btn btn-outline-info">
                            <i class="align-middle" data-feather="layers"></i> Manage Sections
                        </a>
                        <a href="{{ route('admin.pages.edit', $page) }}" class="btn btn-outline-warning">
                            <i class="align-middle" data-feather="edit"></i> Edit Page Settings
                        </a>
                    </div>
                </div>
            </div>

            <!-- Page Statistics -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">Page Statistics</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="metric">
                                <span class="metric-value h3">{{ $page->sections ? $page->sections->count() : 0 }}</span>
                                <span class="metric-label text-muted d-block">Total Sections</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="metric">
                                <span class="metric-value h3">{{ $page->sections ? $page->sections->where('is_active', true)->count() : 0 }}</span>
                                <span class="metric-label text-muted d-block">Active Sections</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Page URL -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">Page URL</h5>
                </div>
                <div class="card-body">
                    <div class="input-group">
                        <span class="input-group-text">{{ url('/') }}/</span>
                        <input type="text" class="form-control" value="{{ $page->slug }}" readonly>
                        <button class="btn btn-outline-secondary" type="button" onclick="copyToClipboard('{{ url('/' . $page->slug) }}')">
                            <i class="align-middle" data-feather="copy"></i>
                        </button>
                    </div>
                    <div class="form-text">Public URL for this page</div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize sortable for sections table
    const sectionsTable = document.getElementById('sections-table');
    if (sectionsTable) {
        const sortable = Sortable.create(sectionsTable, {
            animation: 150,
            handle: '[data-feather="menu"]',
            onEnd: function(evt) {
                const pageId = sectionsTable.dataset.pageId;
                const sections = [];
                
                Array.from(sectionsTable.children).forEach((row, index) => {
                    const sectionId = row.dataset.sectionId;
                    if (sectionId) {
                        sections.push({
                            id: parseInt(sectionId),
                            sort_order: index + 1
                        });
                        
                        // Update the displayed sort order
                        const badge = row.querySelector('.badge');
                        if (badge) {
                            badge.textContent = index + 1;
                        }
                    }
                });
                
                // Send update to server
                fetch(`/admin/page-sections/${pageId}/update-order`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ sections: sections })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success message
                        showNotification('Section order updated successfully!', 'success');
                    }
                })
                .catch(error => {
                    console.error('Error updating section order:', error);
                    showNotification('Error updating section order', 'error');
                });
            }
        });
    }
});

function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        showNotification('URL copied to clipboard!', 'success');
    }, function(err) {
        console.error('Could not copy text: ', err);
        showNotification('Failed to copy URL', 'error');
    });
}

function showNotification(message, type) {
    // Create a simple notification
    const notification = document.createElement('div');
    notification.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show position-fixed`;
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
</script>
@endsection
