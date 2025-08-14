{{-- resources/views/admin/pages/edit.blade.php --}}
@extends('admin.layouts.master')

@section('title', 'Edit Page | تعديل الصفحة')

@section('css')
  {{-- SortableJS --}}
  <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
  {{-- CSS الخاص بالصفحة --}}
  <link rel="stylesheet" href="{{ asset('css/admin/edit-page-section.css') }}">
@endsection

@section('content')
  {{-- رأس الصفحة --}}
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

  {{-- الشبكة الرئيسية --}}
  <div class="container-fluid">
    <div class="row">
      {{-- سكاشن الصفحة --}}
      @if($page->sections && $page->sections->count() > 0)
        <div class="col-12 mb-3">
          <h5>{{ __('Page Sections') }}</h5>
        </div>

        <div id="sortable-sections" class="row">
          @foreach($page->sections->sortBy('sort_order') as $index => $section)
            <div class="col-lg-4 col-md-6 section-item"
                 data-section-id="{{ $section->id }}"
                 data-sort-order="{{ $section->sort_order ?? ($index + 1) }}">
              <div class="component-card section-card">
                <div class="card-top-section">
                  <div class="card-actions">
                    {{-- قائمة الإجراءات (بدون Edit) --}}
                    <div class="dropdown">
                      <button class="btn actions-btn dropdown-toggle" type="button"
                              data-bs-toggle="dropdown" aria-expanded="false">
                        <span style="font-size:14px;color:white;font-weight:bold;">⋯</span>
                        <span class="visually-hidden">Actions</span>
                      </button>
                      <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                          <a class="dropdown-item"
                             href="{{ route('admin.pages.sections.preview', ['page_id' => $page->id, 'section_id' => $section->id]) }}"
                             target="_blank">
                            <i class="fas fa-eye"></i>{{ __('Preview') }}
                          </a>
                        </li>

                        <li><hr class="dropdown-divider"></li>

                        <li>
                          <a class="dropdown-item" href="#"
                             onclick="toggleActive({{ $section->id }})">
                            <i class="fas fa-toggle-on"></i>
                            {{ ($section->status ?? true) ? __('Deactivate') : __('Activate') }}
                          </a>
                        </li>

                        <li><hr class="dropdown-divider"></li>

                        <li>
                          <a class="dropdown-item text-danger" href="#"
                             onclick="deleteSection({{ $section->id }})">
                            <i class="fas fa-minus-circle"></i>{{ __('Remove from Page') }}
                          </a>
                        </li>
                      </ul>
                    </div>
                  </div>

                  @php $layoutImage = $section->layout->preview_image ?? null; @endphp

                  @if($layoutImage)
                    <div class="card-top-image">
                      <img src="{{ $layoutImage }}"
                           alt="{{ $section->layout->name ?? $section->name }}"
                           onerror="this.style.display='none'; this.parentElement.querySelector('.card-top-fallback').style.display='flex';" />
                      <div class="image-overlay-text">
                        {{ __('Section') }} {{ $section->sort_order ?? ($index + 1) }}: {{ $section->name ?? __('Unnamed Section') }}
                      </div>
                      <div class="card-top-fallback" style="display:none;">
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
        </div>
      @else
        {{-- لا توجد سكاشن --}}
        <div class="col-12">
          <div class="no-sections-message">
            <div class="no-sections-content">
              <i class="fas fa-layer-group no-sections-icon"></i>
              <h4 class="no-sections-title">{{ __('No sections found for this page') }}</h4>
              <p class="no-sections-text">
                {{ __('Click "Add New Section" to get started and build your page content.') }}
              </p>
              <div class="no-sections-action">
                <button type="button" class="btn btn-primary btn-add-first-section" onclick="addSection()">
                  <i class="fas fa-plus me-1"></i>{{ __('Add Your First Section') }}
                </button>
              </div>
            </div>
          </div>
        </div>
      @endif

      {{-- كرت إضافة سكشن جديد دائـمًا --}}
      <div class="col-lg-4 col-md-6">
        <div class="component-card add-section-card" onclick="addSection()">
          <div class="card-top-section">
            <div class="card-top-text">{{ __('Add New Section') }}</div>
            <div class="card-icon"><i class="fas fa-plus"></i></div>
          </div>
          <div class="card-bottom-section">
            <div class="card-bottom-text text-center">
              <p class="mb-3">{{ __('Click to add a new section to your page') }}</p>
              <button type="button" class="btn btn-add-section">
                <i class="fas fa-plus me-1"></i>{{ __('Add Section') }}
              </button>
            </div>
          </div>
        </div>
      </div>
      {{-- /كرت إضافة --}}
    </div>

    {{-- أزرار الصفحة --}}
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

{{-- ===================== المودالات ===================== --}}

{{-- مودال إضافة سكشن (موجود) --}}
<div class="modal fade" id="addSectionModal" tabindex="-1" aria-labelledby="addSectionModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
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
          {{ __('Choose a section template from the available templates below. Click on any template to add it to your page.') }}
        </div>

        {{-- بحث/فلترة --}}
        <div class="row mb-4">
          <div class="col-md-8">
            <div class="input-group">
              <span class="input-group-text bg-white border-end-0">
                <i class="fas fa-search text-muted"></i>
              </span>
              <input type="text" class="form-control border-start-0 ps-0" id="sectionTemplateSearch"
                     placeholder="{{ __('Search section templates...') }}" style="box-shadow:none;">
            </div>
          </div>
          <div class="col-md-4">
            <select class="form-select" id="sectionTemplateFilter" style="border-color:#e2e8f0;">
              <option value="">{{ __('All Categories') }}</option>
              <option value="hero">{{ __('Hero Sections') }}</option>
              <option value="about">{{ __('About Sections') }}</option>
              <option value="services">{{ __('Service Sections') }}</option>
              <option value="contact">{{ __('Contact Sections') }}</option>
              <option value="gallery">{{ __('Gallery Sections') }}</option>
              <option value="testimonial">{{ __('Testimonial Sections') }}</option>
            </select>
          </div>
        </div>

        {{-- الشبكة --}}
        <div class="section-templates-container" style="max-height:500px;overflow-y:auto;">
          <div class="row" id="sectionTemplatesGrid">
            @if(isset($sectionLayouts) && $sectionLayouts->count() > 0)
              @foreach($sectionLayouts as $template)
                <div class="col-lg-4 col-md-6 mb-4 section-template-item"
                     data-template-id="{{ $template->id }}"
                     data-template-name="{{ strtolower($template->name) }}"
                     data-template-type="{{ $template->tpl_id ?? '' }}">
                  <div class="template-selection-card"
                       onclick="selectSectionTemplate(event, {{ $template->id }}, '{{ addslashes($template->name) }}')">
                    <div class="template-card-header">
                      @if($template->preview_image)
                        <div class="template-preview-image">
                          <img src="{{ $template->preview_image }}" alt="{{ $template->name }}"
                               onerror="this.style.display='none'; this.parentElement.querySelector('.template-fallback').style.display='flex';">
                          <div class="template-fallback" style="display:none;">
                            <i class="fas fa-layer-group fa-3x"></i>
                            <div class="template-type-text">{{ $template->name }}</div>
                          </div>
                        </div>
                      @else
                        <div class="template-fallback">
                          <i class="fas fa-layer-group fa-3x"></i>
                          <div class="template-type-text">Section Template</div>
                        </div>
                      @endif
                      <div class="template-overlay">
                        <i class="fas fa-plus-circle fa-3x text-white"></i>
                      </div>
                    </div>
                    <div class="template-card-body">
                      <h6 class="template-name">{{ $template->name }}</h6>
                      <p class="template-description">{{ $template->description ?? __('Professional section template ready to use') }}</p>
                      <div class="template-meta">
                        <span class="badge bg-primary">{{ ucfirst($template->layout_type) }}</span>
                        @if($template->tpl_id)
                          <span class="badge bg-secondary">{{ Str::limit($template->tpl_id, 15) }}</span>
                        @endif
                      </div>
                    </div>
                  </div>
                </div>
              @endforeach
            @else
              <div class="col-12 text-center py-5">
                <i class="fas fa-layer-group fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">{{ __('No Section Templates Available') }}</h5>
                <p class="text-muted">{{ __('Please create section templates first in the Templates page.') }}</p>
                <a href="{{ route('admin.templates.index') }}" class="btn btn-primary">
                  <i class="fas fa-plus me-1"></i>{{ __('Create Templates') }}
                </a>
              </div>
            @endif
          </div>
        </div>

        {{-- اختيار القالب --}}
        <div id="selectedTemplateInfo" class="mt-4" style="display:none;">
          <div class="alert alert-success border-0"
               style="background:linear-gradient(135deg,#f0fdf4 0%,#dcfce7 100%);border-left:4px solid #10b981!important;">
            <div class="d-flex align-items-center mb-3">
              <div class="rounded-circle bg-success me-3 d-flex align-items-center justify-content-center" style="width:40px;height:40px;">
                <i class="fas fa-check text-white"></i>
              </div>
              <div>
                <h6 class="mb-0 text-success fw-bold">{{ __('Template Selected') }}</h6>
                <div id="selectedTemplateName" class="text-muted small"></div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-8">
                <label class="form-label fw-semibold">{{ __('Section Name') }}
                  <span class="text-muted">({{ __('Optional') }})</span></label>
                <input type="text" class="form-control border-success" id="customSectionName"
                       placeholder="{{ __('Enter custom name for this section') }}"
                       style="background-color:rgba(16,185,129,0.05);">
                <small class="form-text text-muted">{{ __('Leave empty to use template name') }}</small>
              </div>
              <div class="col-md-4 d-flex align-items-end">
                <button type="button" class="btn btn-outline-success btn-sm w-100" onclick="resetTemplateSelection()">
                  <i class="fas fa-undo me-1"></i>{{ __('Change Template') }}
                </button>
              </div>
            </div>
          </div>
        </div>

      </div>

      <div class="modal-footer bg-light">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
          <i class="fas fa-times me-1"></i>{{ __('Cancel') }}
        </button>
        <button type="button" class="btn btn-success" id="addSectionButton" onclick="addSelectedSection()" style="display:none;">
          <i class="fas fa-plus me-1"></i>{{ __('Add Section') }}
          <span class="spinner-border spinner-border-sm ms-2 d-none" id="addSectionSpinner"></span>
        </button>
      </div>
    </div>
  </div>
</div>
{{-- /مودال إضافة سكشن --}}

@section('js')
  {{-- تمرير الداتا/الروابط/النصوص للملف الخارجي --}}
  <script>
    window.PAGE = {
      id: {{ $page->id }},
      name: @json($page->name),
      slug: @json($page->slug),
      sections: @json($page->sections ?? [])
    };

    window.AVAILABLE_LAYOUTS = @json($sectionLayouts ?? []);
    window.CSRF = document.querySelector('meta[name="csrf-token"]').content;

    window.URLS = {
      addSection: '/admin/pages/{{ $page->id }}/sections',
      getSection: id => `/admin/pages/{{ $page->id }}/sections/${id}/content`,
      updateSection: id => `/admin/pages/{{ $page->id }}/sections/${id}`,
      reorder: `/admin/pages/{{ $page->id }}/sections/reorder`,
      toggleStatus: id => `/admin/pages/{{ $page->id }}/sections/${id}/toggle-status`,
      deleteSection: id => `/admin/pages/{{ $page->id }}/sections/${id}`
    };

    window.I18N = {
      pleaseSelectTemplate: @json(__('Please select a template first')),
      addingSection: @json(__('Adding section to page...')),
      sectionAdded: @json(__('Section added successfully!')),
      failedAdd: @json(__('Failed to add section')),
      errorAdd: @json(__('An error occurred while adding section')),
      failedLoad: @json(__('Failed to load section data')),
      errorLoad: @json(__('An error occurred while loading section data')),
      sectionIdMissing: @json(__('Section ID not found')),
      savingChanges: @json(__('Saving section changes...')),
      updatedOk: @json(__('Section updated successfully')),
      failedUpdate: @json(__('Failed to update section')),
      errorUpdate: @json(__('An error occurred while updating section')),
      confirmRemove: @json(__('Are you sure you want to remove this section from the page?')),
      removing: @json(__('Removing section from page...')),
      removedOk: @json(__('Section removed from page successfully')),
      failedRemove: @json(__('Failed to remove section from page')),
      errorRemove: @json(__('An error occurred while removing section from page')),
      confirmToggle: @json(__('Are you sure you want to toggle this section status?')),
      updatingStatus: @json(__('Updating section status...')),
      active: @json(__('active')),
      inactive: @json(__('inactive')),
      statusNow: @json(__('Section is now')),
      orderUpdated: @json(__('Sections order updated successfully')),
      changesSaved: @json(__('Changes saved successfully'))
    };
  </script>

  {{-- ملف الجافاسكريبت الخارجي --}}
  <script src="{{ asset('js/admin/edit-page-section.js') }}"></script>
@endsection
