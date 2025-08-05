@extends('admin.layouts.master')

@section('title', 'Edit Page | تعديل الصفحة')

@section('css')
<!-- SortableJS CDN -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

{{-- Include Shared Admin Styles --}}
@include('admin.custom-css.shared-admin-styles')
@endsection

@section('content')
    <!-- Page Header -->
    <div class="page-edit-header">
        <div class="container-fluid">
            <div class="row align-items-center justify-content-center">
                <div class="col-md-10 col-lg-8">
                    <div class="text-center">
                        <h1 class="mb-2">
                            <i class="fas fa-edit mr-2"></i>
                            {{ __('Edit Page') }}: {{ $page->name ?? $page->title ?? __('Untitled Page') }}
                        </h1>
                        @if($page->slug)
                            <small class="text-light d-block">{{ __('Page URL') }}: /{{ $page->slug }}</small>
                        @endif
                        
                        <div class="mt-3">
                            @if($page->slug && $page->is_active)
                                <a href="{{ url('/' . $page->slug) }}" class="btn btn-light me-2" target="_blank">
                                    <i class="fas fa-eye me-1"></i> {{ __('View Page') }}
                                </a>
                            @else
                                <button class="btn btn-light me-2" disabled title="{{ __('Page is not active or has no URL') }}">
                                    <i class="fas fa-eye me-1"></i> {{ __('View Page') }}
                                </button>
                            @endif
                            <a href="{{ route('admin.pages.index') }}" class="btn btn-outline-light">
                                <i class="fas fa-arrow-left me-1"></i> {{ __('Back') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Component Cards Grid -->
    <div class="container-fluid">
        <div class="row">
            <!-- Sections Cards -->
            @if($page->sections && $page->sections->count() > 0)
                <div class="col-12 mb-3">
                    <h5>{{ __('Page Sections') }}</h5>
                </div>
                <div id="sortable-sections" class="row">
                @foreach($page->sections->sortBy('sort_order') as $index => $section)
                <div class="col-lg-4 col-md-6 section-item" data-section-id="{{ $section->id }}" data-sort-order="{{ $section->sort_order ?? ($index + 1) }}">
                    <div class="component-card section-card">
                        <!-- Order indicator -->
                        <div class="order-indicator" title="{{ __('Section Order') }}">
                            {{ $section->sort_order ?? ($index + 1) }}
                        </div>
                        
                        <div class="card-top-section">
                            <div class="card-actions">
                                <!-- Actions dropdown -->
                                <div class="dropdown">
                                    <button class="btn actions-btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Section Actions">
                                        <span style="font-size: 14px; color: white; font-weight: bold;">⋯</span>
                                        <span class="visually-hidden">Actions</span>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item" href="#" onclick="editSection({{ $section->id }})">
                                            <i class="fas fa-edit"></i>{{ __('Edit') }}
                                        </a></li>
                                        <li><a class="dropdown-item" href="{{ route('admin.pages.sections.preview', ['page_id' => $page->id, 'section_id' => $section->id]) }}" target="_blank">
                                            <i class="fas fa-eye"></i>{{ __('Preview') }}
                                        </a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item" href="#" onclick="toggleActive({{ $section->id }})">
                                            <i class="fas fa-toggle-on"></i>{{ ($section->status ?? true) ? __('Deactivate') : __('Activate') }}
                                        </a></li>
                                        <li><a class="dropdown-item" href="#" onclick="changeOrder({{ $section->id }})">
                                            <i class="fas fa-sort"></i>{{ __('Change Order') }}
                                        </a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item" href="#" onclick="addSection()">
                                            <i class="fas fa-plus"></i>{{ __('Add Section') }}
                                        </a></li>
                                        <li><a class="dropdown-item text-danger" href="#" onclick="deleteSection({{ $section->id }})">
                                            <i class="fas fa-minus-circle"></i>{{ __('Remove from Page') }}
                                        </a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item" href="#" onclick="duplicateCheck('section', {{ $section->id }})">
                                            <i class="fas fa-search"></i>{{ __('Duplicate Check') }}
                                        </a></li>
                                    </ul>
                                </div>
                            </div>
                            @php $layoutImage = $section->layout->preview_image ?? null; @endphp
                            @if($layoutImage)
                                <div class="card-top-image">
                                    <img src="{{ $layoutImage }}" alt="{{ $section->layout->name ?? $section->name }}" onerror="this.style.display='none'; this.parentElement.querySelector('.card-top-fallback').style.display='flex';" />
                                    <div class="image-overlay-text">
                                        {{ __('Section') }} {{ $section->sort_order ?? ($index + 1) }}: {{ $section->name ?? __('Unnamed Section') }}
                                    </div>
                                    <div class="card-top-fallback" style="display: none;">
                                        <div class="card-top-text">
                                            {{ __('Section') }} {{ $section->sort_order ?? ($index + 1) }}: {{ $section->name ?? __('Unnamed Section') }}
                                        </div>
                                        <div class="card-icon"><i class="fas fa-layer-group"></i></div>
                                    </div>
                                </div>
                            @else
                                <div class="card-top-text">
                                    {{ __('Section') }} {{ $section->sort_order ?? ($index + 1) }}: {{ $section->name ?? __('Unnamed Section') }}
                                </div>
                                <div class="card-icon"><i class="fas fa-layer-group"></i></div>
                            @endif
                        </div>
                        <div class="card-bottom-section">
                            <div class="card-bottom-text">
                                <strong>{{ __('Type') }}:</strong> {{ ucfirst($section->type ?? 'Custom') }}<br>
                                <strong>{{ __('Order') }}:</strong> {{ $section->sort_order ?? ($index + 1) }}<br>
                                <strong>{{ __('Status') }}:</strong>
                                <span class="status-badge {{ ($section->status ?? true) ? 'status-active' : 'status-inactive' }}">
                                    {{ ($section->status ?? true) ? __('Active') : __('Inactive') }}
                                </span>
                                @if($section->title_en || $section->title_ar)
                                    <br><strong>{{ __('Title') }}:</strong>
                                    @if(app()->getLocale() == 'ar' && $section->title_ar)
                                        {{ Str::limit($section->title_ar, 30) }}
                                    @else
                                        {{ Str::limit($section->title_en ?? $section->title_ar, 30) }}
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            @else
                <!-- No Sections Message -->
                <div class="col-12">
                    <div class="no-sections-message">
                        <div class="no-sections-content">
                            <i class="fas fa-layer-group no-sections-icon"></i>
                            <h4 class="no-sections-title">{{ __('No sections found for this page') }}</h4>
                            <p class="no-sections-text">{{ __('Click "Add New Section" to get started and build your page content.') }}</p>
                            <div class="no-sections-action">
                                <button type="button" class="btn btn-primary btn-add-first-section" onclick="addSection()">
                                    <i class="fas fa-plus me-1"></i>{{ __('Add Your First Section') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Add New Section Card -->
            <div class="col-lg-4 col-md-6">
                <div class="component-card add-section-card">
                    <div class="card-top-section">
                        <div class="card-top-text">{{ __('Add New Section') }}</div>
                        <div class="card-icon"><i class="fas fa-plus"></i></div>
                    </div>
                    <div class="card-bottom-section">
                        <div class="card-bottom-text text-center">
                            <p class="mb-3">{{ __('Click to add a new section to your page') }}</p>
                            <button type="button" class="btn btn-add-section" onclick="addSection()">
                                <i class="fas fa-plus me-1"></i>{{ __('Add Section') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="action-buttons">
            <button type="button" class="btn-save-page" onclick="savePage()">
                <i class="fas fa-save me-1"></i>{{ __('Save Changes') }}
            </button>
            <a href="{{ route('admin.pages.index') }}" class="btn-cancel">
                <i class="fas fa-times me-1"></i>{{ __('Cancel') }}
            </a>
        </div>
    </div>
@endsection

<!-- ===================== Modals ===================== -->
<!-- Edit Theme Modal -->
<div class="modal fade" id="editThemeModal" tabindex="-1" aria-labelledby="editThemeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editThemeModalLabel">{{ __('Edit Theme') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="themeForm">
                    <div class="mb-3">
                        <label for="componentType" class="form-label">{{ __('Component Type') }}</label>
                        <input type="text" id="componentType" class="form-control" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="themeSelect" class="form-label">{{ __('Available Themes') }}</label>
                        <select class="form-control" id="themeSelect"></select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="saveTheme()">{{ __('Save') }}</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
            </div>
        </div>
    </div>
</div>

<!-- Add Section Modal -->
<div class="modal fade" id="addSectionModal" tabindex="-1" aria-labelledby="addSectionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addSectionModalLabel">{{ __('Add New Section') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="sectionForm">
                    <div class="mb-3">
                        <label for="sectionType" class="form-label">{{ __('Section Type') }}</label>
                        <select class="form-control" id="sectionType" required>
                            <option value="">{{ __('Select Section Type') }}</option>
                            <option value="hero">{{ __('Hero Section') }}</option>
                            <option value="slider">{{ __('Slider Section') }}</option>
                            <option value="testimonial">{{ __('Testimonial Section') }}</option>
                            <option value="services">{{ __('Services Section') }}</option>
                            <option value="about">{{ __('About Section') }}</option>
                            <option value="contact">{{ __('Contact Section') }}</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="sectionName" class="form-label">{{ __('Section Name') }}</label>
                        <input type="text" class="form-control" id="sectionName" required placeholder="{{ __('Enter section name') }}">
                    </div>
                    <div class="mb-3">
                        <label for="sectionOrder" class="form-label">{{ __('Sort Order') }}</label>
                        <input type="number" class="form-control" id="sectionOrder" value="1" min="1">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" onclick="saveSection()">{{ __('Add Section') }}</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
            </div>
        </div>
    </div>
</div>

<!-- Advanced Edit Section Modal -->
<div class="modal fade" id="editSectionModal" tabindex="-1" aria-labelledby="editSectionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="editSectionModalLabel">
                    <i class="fas fa-edit me-2"></i>{{ __('Edit Section Content') }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div class="row g-0">
                    <!-- Left Side - Form -->
                    <div class="col-lg-8 p-4">
                        <form id="editSectionForm">
                            <input type="hidden" id="editSectionId">
                            <input type="hidden" id="editSectionLayoutId">

                            <!-- Loading Indicator -->
                            <div id="sectionLoadingIndicator" class="text-center py-4 d-none">
                                <div class="spinner-border text-primary" role="status"></div>
                                <p class="mt-2">{{ __('Loading section data...') }}</p>
                            </div>

                            <!-- Content Container -->
                            <div id="sectionContentContainer">
                                <!-- Content Fields -->
                                <div id="contentFields" class="mb-4">
                                    <!-- Dynamic fields will be generated here -->
                                </div>

                                <!-- Media Management -->
                                <div class="card mt-4">
                                    <div class="card-header">
                                        <h6 class="mb-0">
                                            <i class="fas fa-images me-2"></i>{{ __('Media Management') }}
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div id="mediaContainer">
                                            <!-- Media fields will be generated dynamically -->
                                        </div>
                                    </div>
                                </div>

                                <!-- Visual Settings -->
                                <div class="card mt-4">
                                    <div class="card-header">
                                        <h6 class="mb-0">
                                            <i class="fas fa-palette me-2"></i>{{ __('Visual Settings') }}
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="primaryColor" class="form-label">{{ __('Primary Color') }}</label>
                                                    <input type="color" class="form-control form-control-color" id="primaryColor" value="#007bff">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="bgColor" class="form-label">{{ __('Background Color') }}</label>
                                                    <input type="color" class="form-control form-control-color" id="bgColor" value="#ffffff">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="textColor" class="form-label">{{ __('Text Color') }}</label>
                                                    <input type="color" class="form-control form-control-color" id="textColor" value="#333333">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="paddingTop" class="form-label">{{ __('Top Padding') }}</label>
                                                    <div class="input-group">
                                                        <input type="range" class="form-range" id="paddingTop" min="0" max="200" value="50">
                                                        <span class="input-group-text" id="paddingTopValue">50px</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="paddingBottom" class="form-label">{{ __('Bottom Padding') }}</label>
                                                    <div class="input-group">
                                                        <input type="range" class="form-range" id="paddingBottom" min="0" max="200" value="50">
                                                        <span class="input-group-text" id="paddingBottomValue">50px</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    
                    <!-- Right Side - Preview -->
                    <div class="col-lg-4 border-start bg-light">
                        <div class="p-3">
                            <h6 class="mb-3">
                                <i class="fas fa-eye me-2"></i>{{ __('Live Preview') }}
                            </h6>
                            <div class="preview-container">
                                <div id="sectionPreview" class="border rounded p-3 bg-white">
                                    <div class="text-center text-muted">
                                        <i class="fas fa-spinner fa-spin fa-2x mb-3"></i>
                                        <p>{{ __('Select a section to see preview') }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Section Info -->
                            <div class="mt-3">
                                <div class="card">
                                    <div class="card-body p-3">
                                        <h6 class="card-title mb-2">{{ __('Section Information') }}</h6>
                                        <div id="sectionInfo">
                                            <!-- Section info will be populated here -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" onclick="saveAdvancedEditSection()">
                    <i class="fas fa-save me-1"></i>{{ __('Save Changes') }}
                </button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>{{ __('Cancel') }}
                </button>
            </div>
        </div>
    </div>
</div>

@section('js')
{{-- Include Shared Admin Scripts --}}
@include('admin.custom-scripts.shared-admin-scripts')

<script>
// ===================== PAGE SPECIFIC DATA =====================
window.pageData = {
    id: {{ $page->id }},
    name: '{{ $page->name }}',
    slug: '{{ $page->slug }}',
    sections: @json($page->sections ?? [])
};

// Set global variables for shared functions
pageData = window.pageData;
window.pageId = {{ $page->id }};
window.pagesIndexUrl = '{{ route("admin.pages.index") }}';

// Available section layouts
window.availableLayouts = @json($sectionLayouts ?? []);
availableLayouts = window.availableLayouts;
defaultLayoutId = availableLayouts.length > 0 ? availableLayouts[0].id : 1;

// Localized messages
window.messages = {
    fillRequired: '{{ __("Please fill all required fields") }}',
    creating: '{{ __("Creating section...") }}',
    sectionCreated: '{{ __("Section created successfully") }}',
    createFailed: '{{ __("Failed to create section") }}',
    createError: '{{ __("An error occurred while creating section") }}',
    loadFailed: '{{ __("Failed to load section data") }}',
    loadError: '{{ __("An error occurred while loading section data") }}',
    confirmDelete: '{{ __("Are you sure you want to remove this section from the page?") }}',
    removing: '{{ __("Removing section from page...") }}',
    sectionRemoved: '{{ __("Section removed from page successfully") }}',
    removeFailed: '{{ __("Failed to remove section from page") }}',
    removeError: '{{ __("An error occurred while removing section from page") }}',
    confirmToggle: '{{ __("Are you sure you want to toggle this section status?") }}',
    updatingStatus: '{{ __("Updating section status...") }}',
    active: '{{ __("active") }}',
    inactive: '{{ __("inactive") }}',
    sectionNow: '{{ __("Section is now") }}',
    statusFailed: '{{ __("Failed to update section status") }}',
    statusError: '{{ __("An error occurred while updating section status") }}',
    enterOrder: '{{ __("Enter new order (1-10):") }}',
    validOrder: '{{ __("Please enter a valid order number (1-10)") }}',
    sectionNotFound: '{{ __("Section not found") }}',
    updatingOrder: '{{ __("Updating section order...") }}',
    orderUpdated: '{{ __("Section order updated successfully") }}',
    orderFailed: '{{ __("Failed to update section order") }}',
    orderError: '{{ __("An error occurred while updating section order") }}',
    confirmSave: '{{ __("Are you sure you want to save all changes?") }}',
    changesSaved: '{{ __("Changes saved successfully") }}',
    sectionName: '{{ __("Section Name") }}',
    template: '{{ __("Template") }}',
    sortOrder: '{{ __("Sort Order") }}',
    status: '{{ __("Status") }}',
    title: '{{ __("Title") }}',
    subtitle: '{{ __("Subtitle") }}',
    description: '{{ __("Description") }}',
    buttonText: '{{ __("Button Text") }}',
    buttonUrl: '{{ __("Button URL") }}',
    enterTitle: '{{ __("Enter title") }}',
    enterSubtitle: '{{ __("Enter subtitle") }}',
    enterDescription: '{{ __("Enter description") }}',
    enterButtonText: '{{ __("Enter button text") }}',
    enterButtonUrl: '{{ __("Enter button URL") }}',
    mainImage: '{{ __("Main Image") }}',
    backgroundImage: '{{ __("Background Image") }}',
    uploadImage: '{{ __("Upload a new image or keep the existing one") }}',
    optionalBackground: '{{ __("Optional background image") }}',
    imageUrl: '{{ __("Or enter image URL") }}',
    videoUrl: '{{ __("Video URL (Optional)") }}',
    livePreview: '{{ __("Live preview will be updated as you edit") }}',
    previewAppear: '{{ __("Preview will appear here") }}',
    sectionIdNotFound: '{{ __("Section ID not found") }}',
    savingChanges: '{{ __("Saving section changes...") }}',
    sectionUpdated: '{{ __("Section updated successfully") }}',
    updateFailed: '{{ __("Failed to update section") }}',
    updateError: '{{ __("An error occurred while updating section") }}',
    noDuplicates: '{{ __("No duplicate sections found") }}'
};

// ===================== PAGE SPECIFIC INITIALIZATION =====================
document.addEventListener('DOMContentLoaded', function() {
    console.log('Page Edit loaded');
    
    // Remove any existing error alerts on page load
    document.querySelectorAll('.alert-danger').forEach(alert => alert.remove());

    // Initialize page edit functionality
    initializePageEdit();
});
</script>
@endsection
