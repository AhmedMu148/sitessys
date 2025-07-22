@extends('adminlte::page')

@section('title', 'Section Templates')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Section Templates</h1>
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createSectionModal">
            <i class="fas fa-plus"></i> Create Section Template
        </button>
    </div>
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Manage Section Templates</h3>
                <div class="card-tools">
                    <div class="input-group input-group-sm" style="width: 250px;">
                        <input type="text" id="searchSections" class="form-control float-right" placeholder="Search sections...">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-default">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap" id="sectionsTable">
                    <thead>
                        <tr>
                            <th width="50px">
                                <i class="fas fa-sort" title="Drag to reorder"></i>
                            </th>
                            <th>Preview</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th>Usage Count</th>
                            <th>Last Modified</th>
                            <th width="200px">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="sortableSections">
                        @forelse($sections as $section)
                        <tr data-section-id="{{ $section->id }}" data-sort-order="{{ $section->sort_order }}">
                            <td class="handle">
                                <i class="fas fa-grip-vertical text-muted"></i>
                            </td>
                            <td>
                                @if($section->preview_image)
                                    <img src="{{ $section->preview_image }}" alt="{{ $section->name }}" 
                                         class="img-thumbnail" style="width: 60px; height: 40px; object-fit: cover;">
                                @else
                                    <div class="bg-light d-flex align-items-center justify-content-center" 
                                         style="width: 60px; height: 40px; border-radius: 4px;">
                                        <i class="fas fa-image text-muted"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <strong>{{ $section->name }}</strong>
                                @if($section->description)
                                    <br><small class="text-muted">{{ Str::limit($section->description, 50) }}</small>
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-info">{{ $section->category ?? 'General' }}</span>
                            </td>
                            <td>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input section-status-toggle" 
                                           id="status-{{ $section->id }}" 
                                           data-section-id="{{ $section->id }}"
                                           {{ $section->is_active ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="status-{{ $section->id }}"></label>
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-secondary">{{ $section->usage_count ?? 0 }}</span>
                            </td>
                            <td>
                                <small class="text-muted">{{ $section->updated_at->diffForHumans() }}</small>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <button type="button" class="btn btn-info preview-section" 
                                            data-section-id="{{ $section->id }}"
                                            title="Preview">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button type="button" class="btn btn-primary edit-section" 
                                            data-section-id="{{ $section->id }}"
                                            title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-success duplicate-section" 
                                            data-section-id="{{ $section->id }}"
                                            title="Duplicate">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                    <button type="button" class="btn btn-danger delete-section" 
                                            data-section-id="{{ $section->id }}"
                                            title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <i class="fas fa-info-circle text-muted"></i>
                                No section templates found. Create your first section template!
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($sections->hasPages())
            <div class="card-footer">
                {{ $sections->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Create Section Modal -->
<div class="modal fade" id="createSectionModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <form id="createSectionForm" method="POST" action="{{ route('admin.section-templates.store') }}">
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title">Create Section Template</h4>
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="section_name">Template Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="section_name" name="name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="section_category">Category</label>
                                <select class="form-control" id="section_category" name="category">
                                    <option value="hero">Hero Sections</option>
                                    <option value="content">Content Blocks</option>
                                    <option value="gallery">Gallery</option>
                                    <option value="testimonials">Testimonials</option>
                                    <option value="contact">Contact Forms</option>
                                    <option value="footer">Footer</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="section_description">Description</label>
                        <textarea class="form-control" id="section_description" name="description" rows="3"></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="section_preview_image">Preview Image URL</label>
                                <input type="url" class="form-control" id="section_preview_image" name="preview_image">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="section_is_active" name="is_active" checked>
                                    <label class="custom-control-label" for="section_is_active">Active</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- HTML Content -->
                    <div class="form-group">
                        <label for="section_html">HTML Template <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="section_html" name="content[html]" rows="10" required></textarea>
                        <small class="form-text text-muted">
                            Use placeholders like {{title}}, {{description}}, {{image}} for dynamic content.
                        </small>
                    </div>
                    
                    <!-- CSS Content -->
                    <div class="form-group">
                        <label for="section_css">CSS Styles</label>
                        <textarea class="form-control" id="section_css" name="content[css]" rows="6"></textarea>
                        <small class="form-text text-muted">
                            Use CSS variables like var(--color-primary) for theme colors.
                        </small>
                    </div>
                    
                    <!-- JS Content -->
                    <div class="form-group">
                        <label for="section_js">JavaScript</label>
                        <textarea class="form-control" id="section_js" name="content[js]" rows="4"></textarea>
                    </div>
                    
                    <!-- Configurable Fields -->
                    <div class="form-group">
                        <label>Configurable Fields</label>
                        <div id="configurableFields">
                            <div class="configurable-field-template" style="display: none;">
                                <div class="card mb-2">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <input type="text" class="form-control" placeholder="Field Name" name="configurable_fields[][name]">
                                            </div>
                                            <div class="col-md-3">
                                                <select class="form-control" name="configurable_fields[][type]">
                                                    <option value="text">Text</option>
                                                    <option value="textarea">Textarea</option>
                                                    <option value="image">Image</option>
                                                    <option value="color">Color</option>
                                                    <option value="url">URL</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <input type="text" class="form-control" placeholder="Default Value" name="configurable_fields[][default_value]">
                                            </div>
                                            <div class="col-md-2">
                                                <button type="button" class="btn btn-danger btn-sm remove-field">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-secondary btn-sm" id="addConfigField">
                            <i class="fas fa-plus"></i> Add Field
                        </button>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Section Template</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Preview Modal -->
<div class="modal fade" id="previewSectionModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Section Preview</h4>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="sectionPreviewContent">
                    <div class="text-center py-4">
                        <i class="fas fa-spinner fa-spin"></i> Loading preview...
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
<style>
.handle {
    cursor: move;
}

.handle:hover {
    background-color: #f8f9fa;
}

.ui-sortable-helper {
    background-color: #fff;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.configurable-field-template .card {
    border: 1px solid #dee2e6;
}

#sectionPreviewContent {
    max-height: 70vh;
    overflow-y: auto;
}

.section-status-toggle {
    transform: scale(0.8);
}

@media (max-width: 768px) {
    .btn-group-sm .btn {
        margin-bottom: 2px;
    }
}
</style>
@stop

@section('js')
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
<script>
$(document).ready(function() {
    // Initialize sortable
    $("#sortableSections").sortable({
        handle: ".handle",
        update: function(event, ui) {
            let orders = [];
            $('#sortableSections tr').each(function(index) {
                let sectionId = $(this).data('section-id');
                if (sectionId) {
                    orders.push({
                        id: sectionId,
                        sort_order: index + 1
                    });
                }
            });
            
            // Update order via AJAX
            $.ajax({
                url: '{{ route("admin.section-templates.update-order") }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    orders: orders
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success('Section order updated successfully');
                    }
                },
                error: function() {
                    toastr.error('Failed to update section order');
                }
            });
        }
    });
    
    // Search functionality
    $('#searchSections').on('keyup', function() {
        let value = $(this).val().toLowerCase();
        $('#sectionsTable tbody tr').filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    });
    
    // Status toggle
    $('.section-status-toggle').on('change', function() {
        let sectionId = $(this).data('section-id');
        let isActive = $(this).is(':checked');
        
        $.ajax({
            url: '{{ route("admin.section-templates.toggle-status") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                section_id: sectionId,
                is_active: isActive
            },
            success: function(response) {
                if (response.success) {
                    toastr.success('Section status updated');
                }
            },
            error: function() {
                toastr.error('Failed to update section status');
                // Revert checkbox
                $(this).prop('checked', !isActive);
            }
        });
    });
    
    // Preview section
    $('.preview-section').on('click', function() {
        let sectionId = $(this).data('section-id');
        
        $('#previewSectionModal').modal('show');
        $('#sectionPreviewContent').html('<div class="text-center py-4"><i class="fas fa-spinner fa-spin"></i> Loading preview...</div>');
        
        $.ajax({
            url: '{{ route("admin.section-templates.preview") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                section_id: sectionId
            },
            success: function(response) {
                $('#sectionPreviewContent').html(response.html);
            },
            error: function() {
                $('#sectionPreviewContent').html('<div class="alert alert-danger">Failed to load preview</div>');
            }
        });
    });
    
    // Add configurable field
    $('#addConfigField').on('click', function() {
        let template = $('.configurable-field-template').clone();
        template.removeClass('configurable-field-template').show();
        $('#configurableFields').append(template);
    });
    
    // Remove configurable field
    $(document).on('click', '.remove-field', function() {
        $(this).closest('.card').remove();
    });
    
    // Delete section
    $('.delete-section').on('click', function() {
        let sectionId = $(this).data('section-id');
        
        if (confirm('Are you sure you want to delete this section template?')) {
            $.ajax({
                url: '{{ url("admin/section-templates") }}/' + sectionId,
                method: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    }
                },
                error: function() {
                    toastr.error('Failed to delete section template');
                }
            });
        }
    });
    
    // Form submission
    $('#createSectionForm').on('submit', function(e) {
        e.preventDefault();
        
        let formData = new FormData(this);
        
        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    $('#createSectionModal').modal('hide');
                    location.reload();
                }
            },
            error: function(xhr) {
                let errors = xhr.responseJSON.errors;
                if (errors) {
                    Object.keys(errors).forEach(function(key) {
                        toastr.error(errors[key][0]);
                    });
                }
            }
        });
    });
});
</script>
@stop
