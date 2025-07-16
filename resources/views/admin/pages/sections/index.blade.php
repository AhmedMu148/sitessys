@extends('admin.layouts.master')

@section('title', 'Manage Page Sections - ' . $page->name)

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h1 class="h3 mb-0"><strong>{{ $page->name }}</strong> Sections</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.pages.index') }}">Pages</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.pages.show', $page) }}">{{ $page->name }}</a></li>
                            <li class="breadcrumb-item active">Sections</li>
                        </ol>
                    </nav>
                </div>
                <div>
                    <a href="{{ route('admin.page-sections.create', ['page_id' => $page->id]) }}" class="btn btn-primary">
                        <i class="align-middle" data-feather="plus"></i> Add Section
                    </a>
                    <a href="{{ route('admin.pages.show', $page) }}" class="btn btn-secondary">
                        <i class="align-middle" data-feather="arrow-left"></i> Back to Page
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Page Sections</h5>
                    <div class="card-actions">
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="align-middle" data-feather="more-horizontal"></i> Actions
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#" onclick="activateAllSections()">
                                    <i class="align-middle" data-feather="eye"></i> Activate All
                                </a></li>
                                <li><a class="dropdown-item" href="#" onclick="deactivateAllSections()">
                                    <i class="align-middle" data-feather="eye-off"></i> Deactivate All
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="#" onclick="resetSectionOrder()">
                                    <i class="align-middle" data-feather="shuffle"></i> Reset Order
                                </a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    
                    @if($sections->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th width="80">Order</th>
                                        <th>Name & Description</th>
                                        <th>Layout</th>
                                        <th width="100">Status</th>
                                        <th width="150">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="sections-sortable" data-page-id="{{ $page->id }}">
                                    @foreach($sections as $section)
                                        <tr data-section-id="{{ $section->id }}" class="{{ !$section->is_active ? 'table-secondary' : '' }}">
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <span class="badge bg-secondary me-2">{{ $section->sort_order }}</span>
                                                    <i class="align-middle text-muted" data-feather="menu" style="cursor: move;" title="Drag to reorder"></i>
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <strong>{{ $section->name }}</strong>
                                                    @if(!$section->is_active)
                                                        <i class="align-middle ms-1 text-muted" data-feather="eye-off" style="width: 16px; height: 16px;"></i>
                                                    @endif
                                                </div>
                                                @if($section->description)
                                                    <small class="text-muted">{{ Str::limit($section->description, 80) }}</small>
                                                @endif
                                                <div class="mt-1">
                                                    <small class="text-muted">
                                                        <i class="align-middle" data-feather="calendar" style="width: 12px; height: 12px;"></i>
                                                        Created {{ $section->created_at->diffForHumans() }}
                                                    </small>
                                                </div>
                                            </td>
                                            <td>
                                                @if($section->layout)
                                                    <div>
                                                        <strong>{{ $section->layout->name }}</strong>
                                                        @if($section->layout->type)
                                                            <br><small class="text-muted">{{ $section->layout->type->name }}</small>
                                                        @endif
                                                    </div>
                                                @else
                                                    <span class="text-muted">No layout assigned</span>
                                                @endif
                                            </td>
                                            <td>
                                                <form action="{{ route('admin.page-sections.toggle-active', ['page_id' => $page->id, 'section_id' => $section->id]) }}" 
                                                      method="POST" style="display: inline;">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-sm {{ $section->is_active ? 'btn-success' : 'btn-outline-secondary' }}">
                                                        @if($section->is_active)
                                                            <i class="align-middle" data-feather="eye"></i>
                                                        @else
                                                            <i class="align-middle" data-feather="eye-off"></i>
                                                        @endif
                                                    </button>
                                                </form>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('admin.page-sections.edit', ['page_id' => $page->id, 'section_id' => $section->id]) }}" 
                                                       class="btn btn-sm btn-outline-primary" title="Edit">
                                                        <i class="align-middle" data-feather="edit"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-outline-info" 
                                                            onclick="duplicateSection({{ $section->id }})" title="Duplicate">
                                                        <i class="align-middle" data-feather="copy"></i>
                                                    </button>
                                                    <form action="{{ route('admin.page-sections.destroy', ['page_id' => $page->id, 'section_id' => $section->id]) }}" 
                                                          method="POST" style="display: inline;" 
                                                          onsubmit="return confirm('Are you sure you want to delete this section? This action cannot be undone.')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
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
                        
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div class="text-muted">
                                <small>
                                    Total: {{ $sections->count() }} sections 
                                    ({{ $sections->where('is_active', true)->count() }} active, {{ $sections->where('is_active', false)->count() }} inactive)
                                </small>
                            </div>
                            <div>
                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="saveCurrentOrder()">
                                    <i class="align-middle" data-feather="save"></i> Save Current Order
                                </button>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="align-middle" data-feather="layers" style="width: 64px; height: 64px; color: #6c757d;"></i>
                            <h4 class="mt-3">No Sections Found</h4>
                            <p class="text-muted">This page doesn't have any sections yet. Start building your page by adding sections.</p>
                            <a href="{{ route('admin.page-sections.create', ['page_id' => $page->id]) }}" class="btn btn-primary">
                                <i class="align-middle" data-feather="plus"></i> Add First Section
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize sortable for sections
    const sectionsTable = document.getElementById('sections-sortable');
    if (sectionsTable) {
        const sortable = Sortable.create(sectionsTable, {
            animation: 150,
            handle: '[data-feather="menu"]',
            ghostClass: 'sortable-ghost',
            chosenClass: 'sortable-chosen',
            dragClass: 'sortable-drag',
            onEnd: function(evt) {
                updateSectionOrder();
            }
        });
    }
});

function updateSectionOrder() {
    const sectionsTable = document.getElementById('sections-sortable');
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
            showNotification('Section order updated successfully!', 'success');
        } else {
            showNotification('Error updating section order', 'error');
        }
    })
    .catch(error => {
        console.error('Error updating section order:', error);
        showNotification('Error updating section order', 'error');
    });
}

function saveCurrentOrder() {
    updateSectionOrder();
}

function activateAllSections() {
    if (confirm('Are you sure you want to activate all sections?')) {
        // Implementation for bulk activation
        showNotification('Feature coming soon!', 'info');
    }
}

function deactivateAllSections() {
    if (confirm('Are you sure you want to deactivate all sections?')) {
        // Implementation for bulk deactivation
        showNotification('Feature coming soon!', 'info');
    }
}

function resetSectionOrder() {
    if (confirm('Are you sure you want to reset the section order to default?')) {
        // Implementation for resetting order
        showNotification('Feature coming soon!', 'info');
    }
}

function duplicateSection(sectionId) {
    if (confirm('Do you want to create a copy of this section?')) {
        // Implementation for section duplication
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

<style>
.sortable-ghost {
    opacity: 0.4;
}

.sortable-chosen {
    background-color: #f8f9fa;
}

.sortable-drag {
    opacity: 0.8;
}

[data-feather="menu"] {
    transition: color 0.2s ease;
}

[data-feather="menu"]:hover {
    color: #0d6efd !important;
}

.table-secondary {
    --bs-table-bg: #f8f9fa;
}
</style>
@endsection
