@extends('admin.layouts.master')

@section('title', 'Template Management | إدارة القوالب')

@section('css')
@include('admin.custom-css.shared-admin-styles')

<!-- Template-specific CSS overrides -->
<style>
/* ===================== Template-Specific Overrides ===================== */

/* Template card height specific to this page */
.template-card {
    height: 300px;
}

/* Template-specific active badge positioning for RTL */
[dir="rtl"] .template-card.active::before {
    right: 12px;
    left: auto;
}

/* Template badge styling */
.badge.bg-success i {
    margin-right: 4px;
}

[dir="rtl"] .badge.bg-success i {
    margin-right: 0;
    margin-left: 4px;
}
</style>
@endsection

@section('content')
    <!-- Page Header -->
    <div class="template-header">
        <div class="container-fluid">
            <div class="row align-items-center justify-content-center">
                <div class="col-md-10 col-lg-8">
                    <div class="text-center">
                        <h1 class="mb-2">
                            <i class="fas fa-palette mr-2"></i>
                            {{ __('Template Management') }}
                        </h1>

                    </div>
                </div>
            </div>
        </div>
    </div>    
    
    <div class="container-fluid">
        <!-- Page Actions -->
        <div class="page-actions mb-4">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="d-flex align-items-center gap-3">
                        <div class="search-box">
                            <input type="text" class="form-control" placeholder="{{ __('Search templates...') }}" id="globalTemplateSearch" title="Use Ctrl+K to focus, Esc to clear">
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex justify-content-end align-items-center gap-3">
                        <div class="filter-dropdown-container">
                            <label class="filter-label">{{ __('Type') }}</label>
                            <select class="form-select filter-dropdown" id="globalTemplateFilter">
                                <option value="" data-icon="fas fa-th-large">{{ __('All Templates') }}</option>
                                <option value="header" data-icon="fas fa-heading">{{ __('Headers') }}</option>
                                <option value="section" data-icon="fas fa-layer-group">{{ __('Sections') }}</option>
                                <option value="footer" data-icon="fas fa-shoe-prints">{{ __('Footers') }}</option>
                            </select>
                        </div>
                        <div class="filter-dropdown-container">
                            <label class="filter-label">{{ __('Status') }}</label>
                            <select class="form-select filter-dropdown" id="globalStatusFilter">
                                <option value="" data-icon="fas fa-list">{{ __('All Status') }}</option>
                                <option value="active" data-icon="fas fa-check-circle">{{ __('Active') }}</option>
                                <option value="inactive" data-icon="fas fa-circle">{{ __('Inactive') }}</option>
                            </select>
                        </div>
                        <button class="btn btn-outline-secondary btn-sm" onclick="clearAllFilters()" title="{{ __('Clear all filters') }}">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Header Templates Section -->
        <div class="section-header" id="header-templates">
            <h3 class="section-title">
                <i class="fas fa-heading"></i>
                {{ __('Header Templates') }}
            </h3>
        </div>

        <div class="row" id="header-templates-row">
            @foreach($headerTemplates as $template)
                <div class="col-lg-4 col-md-6">
                    <div class="template-card {{ (isset($site->active_header_id) && $site->active_header_id == $template->id) ? 'active' : '' }}">
                        <div class="card-top-section">
                            <div class="card-actions">
                                <div class="dropdown">
                                    <button class="btn actions-btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="">
                                        <span style="font-size: 14px; color: white; font-weight: bold;">⋯</span>
                                        <span class="visually-hidden">Actions</span>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            @if(isset($site->active_header_id) && $site->active_header_id == $template->id)
                                                <a class="dropdown-item text-muted disabled" href="#">
                                                    <i class="fas fa-star"></i>{{ __('Currently Active') }}
                                                </a>
                                            @else
                                                <a class="dropdown-item" href="#" onclick="selectHeaderTemplate({{ $template->id }})">
                                                    <i class="fas fa-check"></i>{{ __('Set as Active') }}
                                                </a>
                                            @endif
                                        </li>
                                        <li><a class="dropdown-item" href="#" onclick="previewHeader({{ $template->id }})">
                                            <i class="fas fa-eye"></i>{{ __('Preview') }}
                                        </a></li>
                                    </ul>
                                </div>
                            </div>

                            @if($template->preview_image)
                                <div class="card-top-image">
                                    <img src="{{ $template->preview_image }}" alt="{{ $template->name }}" onerror="this.style.display='none'; this.parentElement.querySelector('.card-top-fallback').style.display='flex';" />
                                    <div class="image-overlay-text">
                                        {{ __('Header Template') }}: {{ $template->name }}
                                    </div>
                                    <div class="card-top-fallback" style="display: none;">
                                        <div class="card-top-text">{{ $template->name }}</div>
                                        <div class="card-icon"><i class="fas fa-heading"></i></div>
                                    </div>
                                </div>
                            @else
                                <div class="card-top-text">{{ $template->name }}</div>
                                <div class="card-icon"><i class="fas fa-heading"></i></div>
                            @endif
                        </div>
                        <div class="card-bottom-section">
                            @if($template->status && isset($site->active_header_id) && $site->active_header_id == $template->id)
                                <div class="mb-2">
                                    <span class="badge bg-success">
                                        <i class="fas fa-check me-1"></i>{{ __('Active Template') }}
                                    </span>
                                </div>
                            @endif
                            <div class="card-bottom-text">
                                {{ $template->description ?? __('No description available') }}
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>        <!-- Sections Templates Section -->
        <div class="section-header mt-5" id="section-templates">
            <h3 class="section-title">
                <i class="fas fa-layer-group"></i>
                {{ __('Section Templates') }}
            </h3>
        </div>

        <div class="row" id="section-templates-row">
            @foreach($sectionTemplates as $template)
                <div class="col-lg-4 col-md-6 section-template-card" data-category="{{ $template->layout_type }}" data-name="{{ strtolower($template->name) }}">
                    <div class="template-card">
                        <div class="card-top-section">
                            <div class="card-actions">
                                <div class="dropdown">
                                    <button class="btn actions-btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="">
                                        <span style="font-size: 14px; color: white; font-weight: bold;">⋯</span>
                                        <span class="visually-hidden">Actions</span>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item" href="#" onclick="addSectionToPage({{ $template->id }})">
                                            <i class="fas fa-plus"></i>{{ __('Add to Page') }}
                                        </a></li>
                                        <li><a class="dropdown-item" href="#" onclick="previewSection({{ $template->id }})">
                                            <i class="fas fa-eye"></i>{{ __('Preview') }}
                                        </a></li>
                                    </ul>
                                </div>
                            </div>

                            @if($template->preview_image)
                                <div class="card-top-image">
                                    <img src="{{ $template->preview_image }}" alt="{{ $template->name }}" onerror="this.style.display='none'; this.parentElement.querySelector('.card-top-fallback').style.display='flex';" />
                                    <div class="image-overlay-text">
                                        {{ __('Section Template') }}: {{ $template->name }}
                                    </div>
                                    <div class="card-top-fallback" style="display: none;">
                                        <div class="card-top-text">{{ $template->name }}</div>
                                        <div class="card-icon"><i class="fas fa-layer-group"></i></div>
                                    </div>
                                </div>
                            @else
                                <div class="card-top-text">{{ $template->name }}</div>
                                <div class="card-icon"><i class="fas fa-layer-group"></i></div>
                            @endif
                        </div>
                        <div class="card-bottom-section">
                            <div class="card-bottom-text">
                                <strong>{{ __('Type') }}:</strong> {{ ucfirst($template->layout_type) }}<br>
                                {{ $template->description ?? __('No description available') }}
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Footer Templates Section -->
        <div class="section-header mt-5" id="footer-templates">
            <h3 class="section-title">
                <i class="fas fa-grip-lines"></i>
                {{ __('Footer Templates') }}
            </h3>
           
        </div>

        <div class="row" id="footer-templates-row">
            @foreach($footerTemplates as $template)
                <div class="col-lg-4 col-md-6">
                    <div class="template-card {{ (isset($site->active_footer_id) && $site->active_footer_id == $template->id) ? 'active' : '' }}">
                        <div class="card-top-section">
                            <div class="card-actions">
                                <div class="dropdown">
                                    <button class="btn actions-btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="">
                                        <span style="font-size: 14px; color: white; font-weight: bold;">⋯</span>
                                        <span class="visually-hidden">Actions</span>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            @if(isset($site->active_footer_id) && $site->active_footer_id == $template->id)
                                                <a class="dropdown-item text-muted disabled" href="#">
                                                    <i class="fas fa-star"></i>{{ __('Currently Active') }}
                                                </a>
                                            @else
                                                <a class="dropdown-item" href="#" onclick="selectFooterTemplate({{ $template->id }})">
                                                    <i class="fas fa-check"></i>{{ __('Set as Active') }}
                                                </a>
                                            @endif
                                        </li>
                                        <li><a class="dropdown-item" href="#" onclick="previewFooter({{ $template->id }})">
                                            <i class="fas fa-eye"></i>{{ __('Preview') }}
                                        </a></li>
                                    </ul>
                                </div>
                            </div>

                            @if($template->preview_image)
                                <div class="card-top-image">
                                    <img src="{{ $template->preview_image }}" alt="{{ $template->name }}" onerror="this.style.display='none'; this.parentElement.querySelector('.card-top-fallback').style.display='flex';" />
                                    <div class="image-overlay-text">
                                        {{ __('Footer Template') }}: {{ $template->name }}
                                    </div>
                                    <div class="card-top-fallback" style="display: none;">
                                        <div class="card-top-text">{{ $template->name }}</div>
                                        <div class="card-icon"><i class="fas fa-grip-lines"></i></div>
                                    </div>
                                </div>
                            @else
                                <div class="card-top-text">{{ $template->name }}</div>
                                <div class="card-icon"><i class="fas fa-grip-lines"></i></div>
                            @endif
                        </div>
                        <div class="card-bottom-section">
                            @if($template->status && isset($site->active_footer_id) && $site->active_footer_id == $template->id)
                                <div class="mb-2">
                                    <span class="badge bg-success">
                                        <i class="fas fa-check me-1"></i>{{ __('Active Template') }}
                                    </span>
                                </div>
                            @endif
                            <div class="card-bottom-text">
                                {{ $template->description ?? __('No description available') }}
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
    </div>
</div>

<!-- Section Content Management Modal -->
<div class="modal fade" id="sectionContentModal" tabindex="-1" aria-labelledby="sectionContentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="sectionContentModalLabel">
                    <i class="fas fa-edit me-2"></i>{{ __('Edit Section Content') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    {{ __('Edit the content for this section in multiple languages. Changes will be saved automatically.') }}
                </div>
                
                <!-- Language Tabs -->
                <ul class="nav nav-tabs mb-3" id="sectionLangTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="section-en-tab" data-bs-toggle="tab" data-bs-target="#section-en" type="button" role="tab">
                            <i class="fas fa-flag-usa me-1"></i>English
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="section-ar-tab" data-bs-toggle="tab" data-bs-target="#section-ar" type="button" role="tab">
                            <i class="fas fa-flag me-1"></i>العربية
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="sectionLangContent">
                    <!-- English Tab -->
                    <div class="tab-pane fade show active" id="section-en" role="tabpanel">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label class="form-label">{{ __('Main Title') }}</label>
                                    <input type="text" class="form-control" id="sectionTitleEn" placeholder="Enter main title...">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">{{ __('Subtitle') }}</label>
                                    <input type="text" class="form-control" id="sectionSubtitleEn" placeholder="Enter subtitle...">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">{{ __('Description') }}</label>
                                    <textarea class="form-control" id="sectionDescriptionEn" rows="4" placeholder="Enter description..."></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">{{ __('Button Text') }} ({{ __('Optional') }})</label>
                                    <input type="text" class="form-control" id="sectionButtonTextEn" placeholder="e.g., Learn More">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">{{ __('Button URL') }} ({{ __('Optional') }})</label>
                                    <input type="url" class="form-control" id="sectionButtonUrlEn" placeholder="https://example.com">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">{{ __('Section Image') }} ({{ __('Optional') }})</label>
                                    <input type="file" class="form-control" id="sectionImageEn" accept="image/*">
                                    <div class="mt-2" id="sectionImagePreviewEn"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Arabic Tab -->
                    <div class="tab-pane fade" id="section-ar" role="tabpanel" dir="rtl">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label class="form-label">العنوان الرئيسي</label>
                                    <input type="text" class="form-control" id="sectionTitleAr" placeholder="أدخل العنوان الرئيسي...">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">العنوان الفرعي</label>
                                    <input type="text" class="form-control" id="sectionSubtitleAr" placeholder="أدخل العنوان الفرعي...">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">الوصف</label>
                                    <textarea class="form-control" id="sectionDescriptionAr" rows="4" placeholder="أدخل الوصف..."></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">نص الزر (اختياري)</label>
                                    <input type="text" class="form-control" id="sectionButtonTextAr" placeholder="مثال: اعرف أكثر">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">رابط الزر (اختياري)</label>
                                    <input type="url" class="form-control" id="sectionButtonUrlAr" placeholder="https://example.com">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">صورة القسم (اختياري)</label>
                                    <input type="file" class="form-control" id="sectionImageAr" accept="image/*">
                                    <div class="mt-2" id="sectionImagePreviewAr"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>{{ __('Cancel') }}
                </button>
                <button type="button" class="btn btn-primary" onclick="saveSectionContent()">
                    <i class="fas fa-save me-1"></i>{{ __('Save Content') }}
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Add Section to Page Modal -->
<div class="modal fade" id="addSectionModal" tabindex="-1" aria-labelledby="addSectionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addSectionModalLabel">
                    <i class="fas fa-plus me-2"></i>{{ __('Add Section to Page') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    {{ __('Select the page and position where you want to add this section.') }}
                </div>
                
                <div class="mb-3">
                    <label class="form-label">{{ __('Select Page') }}</label>
                    <select class="form-control" id="targetPage">
                        <option value="">{{ __('Loading pages...') }}</option>
                    </select>
                    <small class="form-text text-muted">{{ __('Select the page where you want to add this section') }}</small>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">{{ __('Section Position') }}</label>
                    <select class="form-control" id="sectionPosition">
                        <option value="1">1 - {{ __('Top of page') }}</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="-1">{{ __('Bottom of page') }}</option>
                    </select>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">{{ __('Current Page Sections') }}</label>
                    <div class="border rounded p-3 bg-light" id="currentSections">
                        <em class="text-muted">{{ __('Select a page to see current sections') }}</em>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>{{ __('Cancel') }}
                </button>
                <button type="button" class="btn btn-primary" onclick="saveAddSection()">
                    <i class="fas fa-plus me-1"></i>{{ __('Add Section') }}
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

<!-- Template Preview Modal -->
<div class="modal fade" id="templatePreviewModal" tabindex="-1" aria-labelledby="templatePreviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="templatePreviewModalLabel">{{ __('Template Preview') }}</h5>
                <div class="btn-group ms-auto me-2" role="group" aria-label="Language toggle">
                    <button type="button" class="btn btn-outline-secondary btn-sm" id="previewLangEn" onclick="switchPreviewLanguage('en')">English</button>
                    <button type="button" class="btn btn-outline-secondary btn-sm" id="previewLangAr" onclick="switchPreviewLanguage('ar')">العربية</button>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <iframe id="templatePreviewFrame" style="width: 100%; height: 600px; border: none;"></iframe>
            </div>
        </div>
    </div>
</div>

@section('js')
@include('admin.custom-scripts.shared-admin-scripts')

<!-- Template-specific scripts -->
<script>
$(document).ready(function() {
    // ===================== Template-Specific Functionality =====================
    
    // Template-specific global functions for template management
    window.selectHeaderTemplate = function(templateId) {
        console.log('Selecting header template:', templateId);
        // Add your header template selection logic here
    };

    window.selectFooterTemplate = function(templateId) {
        console.log('Selecting footer template:', templateId);
        // Add your footer template selection logic here
    };

    window.addSectionToPage = function(sectionId) {
        console.log('Adding section to page:', sectionId);
        // Add your section addition logic here
    };

    window.editSectionContent = function(sectionId) {
        console.log('Editing section content:', sectionId);
        // Add your section editing logic here
    };

    window.previewSection = function(sectionId) {
        console.log('Previewing section:', sectionId);
        // Add your section preview logic here
    };

    // Template-specific filter function
    window.filterTemplates = function() {
        const searchTerm = document.getElementById('globalTemplateSearch')?.value.toLowerCase() || '';
        const selectedType = document.getElementById('globalTemplateFilter')?.value || '';
        const selectedStatus = document.getElementById('globalStatusFilter')?.value || '';
        
        // Filter all template sections
        filterTemplateSection('header-templates-row', searchTerm, selectedType, selectedStatus);
        filterTemplateSection('section-templates-row', searchTerm, selectedType, selectedStatus);
        filterTemplateSection('footer-templates-row', searchTerm, selectedType, selectedStatus);
    };

    function filterTemplateSection(sectionId, searchTerm, selectedType, selectedStatus) {
        const section = document.getElementById(sectionId);
        if (!section) return;

        const cards = section.querySelectorAll('.col-lg-4, .col-md-6');
        
        cards.forEach(card => {
            const templateCard = card.querySelector('.template-card');
            const templateName = card.querySelector('.card-top-text')?.textContent.toLowerCase() || '';
            const templateDescription = card.querySelector('.card-bottom-text')?.textContent.toLowerCase() || '';
            const isActive = templateCard.classList.contains('active');
            
            let shouldShow = true;

            // Search filter
            if (searchTerm && !templateName.includes(searchTerm) && !templateDescription.includes(searchTerm)) {
                shouldShow = false;
            }

            // Type filter
            if (selectedType) {
                const sectionType = sectionId.includes('header') ? 'header' : 
                                   sectionId.includes('footer') ? 'footer' : 'section';
                if (selectedType !== sectionType) {
                    shouldShow = false;
                }
            }

            // Status filter
            if (selectedStatus) {
                if (selectedStatus === 'active' && !isActive) {
                    shouldShow = false;
                } else if (selectedStatus === 'inactive' && isActive) {
                    shouldShow = false;
                }
            }

            // Show/hide card with animation
            if (shouldShow) {
                card.style.display = 'block';
                setTimeout(() => {
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, 10);
            } else {
                card.style.opacity = '0';
                card.style.transform = 'translateY(-20px)';
                setTimeout(() => {
                    card.style.display = 'none';
                }, 300);
            }
        });
    }

    // Clear all filters
    window.clearAllFilters = function() {
        const searchInput = document.getElementById('globalTemplateSearch');
        const typeFilter = document.getElementById('globalTemplateFilter');
        const statusFilter = document.getElementById('globalStatusFilter');
        
        if (searchInput) searchInput.value = '';
        if (typeFilter) typeFilter.value = '';
        if (statusFilter) statusFilter.value = '';
        
        filterTemplates();
    };

    // Initialize template search and filter functionality
    const searchInput = document.getElementById('globalTemplateSearch');
    const typeFilter = document.getElementById('globalTemplateFilter');
    const statusFilter = document.getElementById('globalStatusFilter');

    if (searchInput) {
        searchInput.addEventListener('input', filterTemplates);
    }
    if (typeFilter) {
        typeFilter.addEventListener('change', filterTemplates);
    }
    if (statusFilter) {
        statusFilter.addEventListener('change', filterTemplates);
    }
});
</script>
@endsection
