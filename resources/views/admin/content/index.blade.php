@extends('admin.layouts.master')

@section('title', 'Edit Content')

@section('css')
    {{-- Include shared admin panel styles --}}
    <link rel="stylesheet" href="{{ asset('css/admin/contant.css') }}">
@endsection

@section('content')
<div class="container-fluid p-0">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h1 class="h3 mb-0">Edit Active Content</h1>
    </div>

    @php
        $headerCount = $activeHeader ? 1 : 0;
        $footerCount = $activeFooter ? 1 : 0;
        $sectionsCount = $pages->sum(function($p){ return $p->sections->count(); });
    @endphp

    {{-- Navigation Tabs --}}
    <ul class="nav nav-tabs" id="contentTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="headers-tab" data-bs-toggle="tab" data-bs-target="#headers" type="button" role="tab">
                <i class="align-middle me-2" data-feather="layout"></i>Header ({{ $headerCount }})
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="sections-tab" data-bs-toggle="tab" data-bs-target="#sections" type="button" role="tab">
                <i class="align-middle me-2" data-feather="grid"></i>Sections ({{ $sectionsCount }})
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="footers-tab" data-bs-toggle="tab" data-bs-target="#footers" type="button" role="tab">
                <i class="align-middle me-2" data-feather="layers"></i>Footer ({{ $footerCount }})
            </button>
        </li>
    </ul>

    <div class="tab-content mt-3" id="contentTabContent">
        {{-- Header Tab --}}
        <div class="tab-pane fade show active" id="headers" role="tabpanel">
            <div class="row">
                @if($activeHeader)
                    <div class="col-12 col-md-6 col-lg-4 mb-3">
                        <div class="template-card">
                            <div class="card-actions dropdown">
                                <button class="actions-btn" data-bs-toggle="dropdown" aria-expanded="false" title="Actions">
                                    <i data-feather="more-vertical"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <button class="dropdown-item border-0 bg-transparent" type="button" 
                                                onclick="openHeaderContentEditor({{ $activeHeader->id }})">
                                            <i class="align-middle me-2" data-feather="edit-3"></i>Edit Content
                                        </button>
                                    </li>
                                    <li>
                                        <button class="dropdown-item border-0 bg-transparent" type="button" data-bs-toggle="modal" data-bs-target="#headerNavModal">
                                            <i class="align-middle me-2" data-feather="menu"></i>Edit Navigation
                                        </button>
                                    </li>
                                </ul>
                            </div>
                            <div class="card-top-section">
                                <div class="card-top-text">
                                    <i class="align-middle me-2" data-feather="layout"></i>
                                    Active Header
                                </div>
                                <i class="card-icon" data-feather="star"></i>
                            </div>
                            <div class="card-bottom-section">
                                <div>
                                    <div class="fw-semibold">{{ $activeHeader->name }}</div>
                                    <div class="text-muted small">#{{ $activeHeader->id }} • Header</div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                @else
                    <div class="col-12">
                        <div class="alert alert-info">No active header selected.</div>
                    </div>
                @endif
            </div>
        </div>

        {{-- Sections Tab --}}
        <div class="tab-pane fade" id="sections" role="tabpanel">
            @php $pagesWithActive = $pages->filter(fn($p) => $p->sections->count() > 0); @endphp
            @forelse($pagesWithActive as $page)
                <div class="d-flex align-items-center justify-content-between mt-2 mb-2">
                    <h5 class="mb-0">{{ $page->name }}</h5>
                    <a href="{{ route('admin.pages.edit', $page->id) }}" class="btn btn-outline-secondary btn-sm">Manage Page</a>
                </div>
                <div class="row">
                    @foreach($page->sections as $section)
                        <div class="col-12 col-md-6 col-lg-4 mb-3">
                            <div class="template-card section-template-card">
                                <div class="card-actions dropdown">
                                    <button class="actions-btn" data-bs-toggle="dropdown" aria-expanded="false" title="Actions">
                                        <i data-feather="more-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <button class="dropdown-item border-0 bg-transparent" type="button" 
                                                    onclick="openSectionContentEditor({{ $section->id }}, {{ $page->id }})">
                                                <i class="align-middle me-2" data-feather="edit-3"></i>Edit Content
                                            </button>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('admin.pages.sections.preview', [$page->id, $section->id]) }}">
                                                <i class="align-middle me-2" data-feather="eye"></i>Preview
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="card-top-section" style="background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);">
                                    <div class="card-top-text">
                                        <i class="align-middle me-2" data-feather="grid"></i>
                                        {{ $section->name }}
                                    </div>
                                    <i class="card-icon" data-feather="activity"></i>
                                </div>
                                <div class="card-bottom-section">
                                    <div>
                                        <span class="badge bg-success badge-pill">Active</span>
                                        <span class="ms-2 text-muted small">Order: {{ $section->sort_order }}</span>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @empty
                <div class="alert alert-info mt-3">No active sections found.</div>
            @endforelse
        </div>

        {{-- Footer Tab --}}
        <div class="tab-pane fade" id="footers" role="tabpanel">
            <div class="row">
                @if($activeFooter)
                    <div class="col-12 col-md-6 col-lg-4 mb-3">
                        <div class="template-card">
                            <div class="card-actions dropdown">
                                <button class="actions-btn" data-bs-toggle="dropdown" aria-expanded="false" title="Actions">
                                    <i data-feather="more-vertical"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <button class="dropdown-item border-0 bg-transparent" type="button" 
                                                onclick="openFooterContentEditor({{ $activeFooter->id }})">
                                            <i class="align-middle me-2" data-feather="edit-3"></i>Edit Content
                                        </button>
                                    </li>
                                    <li>
                                        <button class="dropdown-item border-0 bg-transparent" type="button" data-bs-toggle="modal" data-bs-target="#footerNavModal">
                                            <i class="align-middle me-2" data-feather="menu"></i>Edit Navigation
                                        </button>
                                    </li>
                                    <li>
                                        <button class="dropdown-item border-0 bg-transparent" type="button" data-bs-toggle="modal" data-bs-target="#socialMediaModal">
                                            <i class="align-middle me-2" data-feather="share-2"></i>Edit Social Media
                                        </button>
                                    </li>
                                </ul>
                            </div>
                            <div class="card-top-section" style="background: linear-gradient(135deg, #06b6d4 0%, #0ea5e9 100%);">
                                <div class="card-top-text">
                                    <i class="align-middle me-2" data-feather="layers"></i>
                                    Active Footer
                                </div>
                                <i class="card-icon" data-feather="check-circle"></i>
                            </div>
                            <div class="card-bottom-section">
                                <div>
                                    <div class="fw-semibold">{{ $activeFooter->name }}</div>
                                    <div class="text-muted small">#{{ $activeFooter->id }} • Footer</div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                @else
                    <div class="col-12">
                        <div class="alert alert-info">No active footer selected.</div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Header Navigation Modal --}}
<div class="modal fade" id="headerNavModal" tabindex="-1" aria-labelledby="headerNavModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="headerNavModalLabel">
                    <i class="align-middle me-2" data-feather="menu"></i>Header Navigation (Max 5 links)
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="header-links">
                    @if(isset($navigationConfig['header_links']) && count($navigationConfig['header_links']) > 0)
                        @foreach($navigationConfig['header_links'] as $index => $link)
                            <div class="nav-link-item mb-3" data-index="{{ $index }}">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h6 class="card-title mb-0">{{ $link['title'] ?? $link['name'] ?? 'Untitled Link' }}</h6>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" 
                                                       id="header-link-{{ $index }}-active" 
                                                       {{ ($link['active'] ?? true) ? 'checked' : '' }}
                                                       onchange="toggleLinkStatus('header', {{ $index }}, this.checked)">
                                                <label class="form-check-label text-muted small" for="header-link-{{ $index }}-active">
                                                    Active
                                                </label>
                                            </div>
                                        </div>
                                        <p class="card-text small text-muted">
                                            <i class="align-middle me-1" data-feather="link"></i>{{ $link['url'] ?? '#' }}
                                            @if($link['external'] ?? false)
                                                <span class="badge bg-info ms-2">External</span>
                                            @endif
                                        </p>
                                        <button class="btn btn-outline-danger btn-sm" onclick="removeLink('header', {{ $index }})">
                                            <i class="align-middle me-1" data-feather="trash-2"></i>Remove
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="alert alert-info">
                            <i class="align-middle me-2" data-feather="info"></i>
                            No header navigation links configured yet. Add your first link below.
                        </div>
                    @endif
                </div>
                
                @if(count($navigationConfig['header_links'] ?? []) < 5)
                    <div class="card border-dashed">
                        <div class="card-body">
                            <div id="add-header-link-form" style="display: none;">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="header-link-title" class="form-label">Link Title</label>
                                        <input type="text" class="form-control" id="header-link-title" placeholder="Enter title">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="header-link-url" class="form-label">URL</label>
                                        <input type="url" class="form-control" id="header-link-url" placeholder="https://example.com">
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="header-link-external">
                                            <label class="form-check-label" for="header-link-external">External Link</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="header-link-active" checked>
                                            <label class="form-check-label" for="header-link-active">Active</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <button type="button" class="btn btn-success btn-sm" onclick="addHeaderLink()">
                                        <i class="align-middle me-1" data-feather="plus"></i>Add Link
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary btn-sm ms-2" onclick="cancelAddHeaderLink()">
                                        Cancel
                                    </button>
                                </div>
                            </div>
                            <button id="show-add-header-form" class="btn btn-primary" onclick="showAddHeaderForm()">
                                <i class="align-middle me-2" data-feather="plus"></i>Add New Header Link
                            </button>
                        </div>
                    </div>
                @endif
                
                <div class="mt-4">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title"><i class="align-middle me-2" data-feather="settings"></i>Authentication Display Settings</h6>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="show_auth_header" 
                                       {{ $navigationConfig['show_auth_in_header'] ?? false ? 'checked' : '' }}>
                                <label class="form-check-label" for="show_auth_header">
                                    Show authentication links in header (Login/Register/Profile)
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="saveHeaderNavigation()">Save Changes</button>
            </div>
        </div>
    </div>
</div>

{{-- Footer Navigation Modal --}}
<div class="modal fade" id="footerNavModal" tabindex="-1" aria-labelledby="footerNavModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="footerNavModalLabel">
                    <i class="align-middle me-2" data-feather="layers"></i>Footer Navigation (Max 10 links)
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="footer-links">
                    @if(isset($navigationConfig['footer_links']) && count($navigationConfig['footer_links']) > 0)
                        @foreach($navigationConfig['footer_links'] as $index => $link)
                            <div class="nav-link-item mb-3" data-index="{{ $index }}">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h6 class="card-title mb-0">{{ $link['title'] ?? $link['name'] ?? 'Untitled Link' }}</h6>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" 
                                                       id="footer-link-{{ $index }}-active" 
                                                       {{ ($link['active'] ?? true) ? 'checked' : '' }}
                                                       onchange="toggleLinkStatus('footer', {{ $index }}, this.checked)">
                                                <label class="form-check-label text-muted small" for="footer-link-{{ $index }}-active">
                                                    Active
                                                </label>
                                            </div>
                                        </div>
                                        <p class="card-text small text-muted">
                                            <i class="align-middle me-1" data-feather="link"></i>{{ $link['url'] ?? '#' }}
                                            @if($link['external'] ?? false)
                                                <span class="badge bg-info ms-2">External</span>
                                            @endif
                                        </p>
                                        <button class="btn btn-outline-danger btn-sm" onclick="removeLink('footer', {{ $index }})">
                                            <i class="align-middle me-1" data-feather="trash-2"></i>Remove
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="alert alert-info">
                            <i class="align-middle me-2" data-feather="info"></i>
                            No footer navigation links configured yet. Add your first link below.
                        </div>
                    @endif
                </div>
                
                @if(count($navigationConfig['footer_links'] ?? []) < 10)
                    <div class="card border-dashed">
                        <div class="card-body">
                            <div id="add-footer-link-form" style="display: none;">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="footer-link-title" class="form-label">Link Title</label>
                                        <input type="text" class="form-control" id="footer-link-title" placeholder="Enter title">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="footer-link-url" class="form-label">URL</label>
                                        <input type="url" class="form-control" id="footer-link-url" placeholder="https://example.com">
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="footer-link-external">
                                            <label class="form-check-label" for="footer-link-external">External Link</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="footer-link-active" checked>
                                            <label class="form-check-label" for="footer-link-active">Active</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <button type="button" class="btn btn-success btn-sm" onclick="addFooterLink()">
                                        <i class="align-middle me-1" data-feather="plus"></i>Add Link
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary btn-sm ms-2" onclick="cancelAddFooterLink()">
                                        Cancel
                                    </button>
                                </div>
                            </div>
                            <button id="show-add-footer-form" class="btn btn-primary" onclick="showAddFooterForm()">
                                <i class="align-middle me-2" data-feather="plus"></i>Add New Footer Link
                            </button>
                        </div>
                    </div>
                @endif
                
                <div class="mt-4">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title"><i class="align-middle me-2" data-feather="settings"></i>Authentication Display Settings</h6>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="show_auth_footer" 
                                       {{ $navigationConfig['show_auth_in_footer'] ?? false ? 'checked' : '' }}>
                                <label class="form-check-label" for="show_auth_footer">
                                    Show authentication links in footer (Login/Register/Profile)
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="saveFooterNavigation()">Save Changes</button>
            </div>
        </div>
    </div>
</div>

{{-- Social Media Configuration Modal --}}
<div class="modal fade" id="socialMediaModal" tabindex="-1" aria-labelledby="socialMediaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="socialMediaModalLabel">
                    <i class="align-middle me-2" data-feather="share-2"></i>Social Media Configuration
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted mb-4">Configure your social media links. These will be displayed in your footer templates where the social media placeholder is used.</p>
                
                <form id="social-media-form">
                    @csrf
                    <div class="social-media-grid">
                        @php
                            $socialPlatforms = [
                                'facebook' => 'Facebook',
                                'twitter' => 'Twitter',
                                'instagram' => 'Instagram',
                                'linkedin' => 'LinkedIn',
                                'youtube' => 'YouTube',
                                'tiktok' => 'TikTok',
                                'snapchat' => 'Snapchat',
                                'pinterest' => 'Pinterest'
                            ];
                        @endphp
                        
                        @foreach($socialPlatforms as $platform => $label)
                            <div class="card mb-3">
                                <div class="card-body">
                                    <label for="social-{{ $platform }}" class="form-label">
                                        <i class="align-middle me-2" data-feather="share-2"></i>{{ $label }}
                                    </label>
                                    <input type="url" class="form-control" id="social-{{ $platform }}" 
                                           name="social_media[{{ $platform }}]" 
                                           value="{{ $socialMediaConfig[$platform] ?? '' }}" 
                                           placeholder="https://{{ $platform }}.com/yourprofile">
                                </div>
                            </div>
                        @endforeach
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="updateSocialMedia()">
                    <i class="align-middle me-1" data-feather="save"></i>Save Social Media
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Section Content Editor Modal --}}
<div class="modal fade" id="sectionContentModal" tabindex="-1" aria-labelledby="sectionContentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="sectionContentModalLabel">
                    <i class="align-middle me-2" data-feather="edit-3"></i>Edit Section Content
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="sectionContentForm">
                    @csrf
                    <input type="hidden" id="section_id" name="section_id" value="">
                    <input type="hidden" id="page_id" name="page_id" value="">
                    
                    <!-- Loading State -->
                    <div id="sectionLoadingState" class="text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <div class="mt-3">Loading section data...</div>
                    </div>
                    
                    <!-- Section Info -->
                    <div id="sectionInfo" class="mb-4" style="display: none;">
                        <div class="alert alert-info">
                            <div class="d-flex align-items-center">
                                <i class="align-middle me-2" data-feather="info"></i>
                                <div>
                                    <strong id="sectionName"></strong>
                                    <div class="small text-muted" id="sectionDescription"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Dynamic Form Fields -->
                    <div id="sectionFormFields" style="display: none;">
                        <!-- Fields will be populated dynamically -->
                    </div>
                    
                    <!-- Error Display -->
                    <div id="sectionErrorAlert" class="alert alert-danger" style="display: none;" role="alert">
                        <i class="align-middle me-2" data-feather="alert-circle"></i>
                        <span id="sectionErrorMessage"></span>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="align-middle me-1" data-feather="x"></i>Cancel
                </button>
                <button type="button" class="btn btn-primary" id="saveSectionContent">
                    <i class="align-middle me-1" data-feather="save"></i>Save Changes
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
  <script>
      window.navigationConfig = {!! json_encode($navigationConfig ?? []) !!};
      window.socialMediaConfig = {!! json_encode($socialMediaConfig ?? []) !!};
      window.availablePages = {!! json_encode($availablePages ?? []) !!};
  </script>
  <script src="{{ asset('js/admin-content-debug.js') }}"></script>
  <script src="{{ asset('js/admin/contant.js') }}"></script>
@endsection