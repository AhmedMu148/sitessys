@extends('admin.layouts.master')

@section('title', 'Edit Content')

@section('css')
<link href="{{ asset('css/admin/headers-footers.css') }}" rel="stylesheet">
<style>
/* Edit Content Page Styles - Match Headers & Footers */
.template-card {
    transition: all 0.3s ease;
    border-radius: 12px;
    overflow: hidden;
    border: 1px solid #e3e6f0;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    height: 300px;
    position: relative;
}

.template-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.card-top-section {
    position: relative;
    height: 160px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    overflow: hidden;
}

.card-top-text {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100%;
    color: white;
    font-weight: 600;
    font-size: 1.1rem;
    text-align: center;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.card-icon {
    position: absolute;
    top: 20px;
    right: 20px;
    color: rgba(255,255,255,0.3);
    font-size: 2.5rem;
}

.card-bottom-section {
    padding: 15px;
    height: 140px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    background: white;
}

.section-template-card {
    margin-bottom: 1.5rem;
}

.section-template-card .template-card {
    cursor: pointer;
}

.section-template-card .template-card:hover {
    transform: translateY(-4px) scale(1.02);
}

.tab-content {
    border: 1px solid #e3e6f0;
    border-top: 0;
    padding: 25px;
    border-radius: 0 0 12px 12px;
    background: #ffffff;
}

.nav-tabs {
    border-bottom: 1px solid #e3e6f0;
}

.nav-tabs .nav-link {
    border-radius: 12px 12px 0 0;
    border: 1px solid transparent;
    color: #6c757d;
    font-weight: 500;
}

.nav-tabs .nav-link.active {
    color: #007bff;
    background-color: #ffffff;
    border-color: #e3e6f0 #e3e6f0 #ffffff;
}

/* Dropdown Actions Styles */
.card-actions {
    position: absolute;
    top: 12px;
    right: 12px;
    z-index: 15;
    opacity: 1;
    transition: all 0.3s ease;
    transform: translateY(0);
}

.actions-btn {
    background: rgba(34, 46, 60, 0.9);
    border: 1px solid rgba(34, 46, 60, 0.3);
    border-radius: 0.5rem;
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 12px rgba(34, 46, 60, 0.2);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    backdrop-filter: blur(10px);
    cursor: pointer;
    color: white;
    position: relative;
    overflow: hidden;
}

.actions-btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0.05) 100%);
    opacity: 0;
    transition: opacity 0.3s ease;
    border-radius: 0.5rem;
}

.actions-btn:hover {
    background: linear-gradient(135deg, #222e3c 0%, #2b3947 100%);
    border-color: #222e3c;
    box-shadow: 0 6px 20px rgba(34, 46, 60, 0.35);
    transform: scale(1.08);
    color: white;
}

.actions-btn:hover::before {
    opacity: 1;
}

.dropdown-menu {
    border: 1px solid rgba(34, 46, 60, 0.15);
    box-shadow: 0 4px 12px rgba(34, 46, 60, 0.15);
    border-radius: 0.5rem;
    padding: 0.5rem 0;
    min-width: 180px;
}

.dropdown-item {
    padding: 0.5rem 1rem;
    transition: all 0.2s ease;
    font-weight: 500;
    color: #475569;
}

.dropdown-item:hover {
    background: #f8faff;
    color: #222e3c;
}

.alert {
    border-radius: 8px;
    border: none;
    margin-bottom: 1.5rem;
}

.alert-info {
    background: linear-gradient(135deg, #cce7ff 0%, #b3d9ff 100%);
    color: #0c5460;
}

@media (max-width: 768px) {
    .template-card {
        margin-bottom: 1rem;
    }
}

/* Navigation & Social Media Styles */
.nav-link-item {
    background: #ffffff;
    border: 1px solid #e3e6f0;
    border-radius: 8px;
    padding: 12px;
    margin-bottom: 10px;
    transition: all 0.2s ease;
}

.nav-link-item:hover {
    border-color: #007bff;
    box-shadow: 0 2px 8px rgba(0,123,255,0.1);
}

.social-media-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 1rem;
}

.social-media-grid .card {
    margin-bottom: 0;
}

.social-media-grid .form-label {
    display: flex;
    align-items: center;
    font-weight: 500;
    color: #495057;
    margin-bottom: 0.5rem;
}

.social-media-grid .form-label i {
    margin-right: 0.5rem;
    color: #007bff;
}

.border-dashed {
    border: 2px dashed #dee2e6 !important;
    background: #f8f9fa;
}

.border-dashed:hover {
    border-color: #007bff !important;
    background: #f0f8ff;
}
</style>
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
                                            <a class="dropdown-item" href="{{ route('admin.pages.sections.edit', [$page->id, $section->id]) }}">
                                                <i class="align-middle me-2" data-feather="edit-3"></i>Edit Content
                                            </a>
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
@endsection

@push('scripts')
<script>
// Pass navigation configuration to JavaScript
window.navigationConfig = @json($navigationConfig ?? []);
window.socialMediaConfig = @json($socialMediaConfig ?? []);
window.availablePages = @json($availablePages ?? []);

document.addEventListener('DOMContentLoaded', function(){
    if (typeof feather !== 'undefined') { feather.replace(); }
    
    // Enhanced dropdown positioning
    setTimeout(() => {
        document.querySelectorAll('[data-bs-toggle="dropdown"]').forEach(el => {
            new bootstrap.Dropdown(el, {
                popperConfig: {
                    strategy: 'fixed',
                    modifiers: [
                        { name: 'preventOverflow', options: { boundary: document.body } }
                    ]
                }
            });
        });
    }, 300);
    
    // Add event listeners for modal opening to refresh data
    setupModalEventListeners();
});

function setupModalEventListeners() {
    // Header Navigation Modal
    const headerNavModal = document.getElementById('headerNavModal');
    if (headerNavModal) {
        headerNavModal.addEventListener('show.bs.modal', function () {
            refreshHeaderNavigationData();
        });
    }
    
    // Footer Navigation Modal
    const footerNavModal = document.getElementById('footerNavModal');
    if (footerNavModal) {
        footerNavModal.addEventListener('show.bs.modal', function () {
            refreshFooterNavigationData();
        });
    }
    
    // Social Media Modal
    const socialMediaModal = document.getElementById('socialMediaModal');
    if (socialMediaModal) {
        socialMediaModal.addEventListener('show.bs.modal', function () {
            refreshSocialMediaData();
        });
    }
}

function refreshHeaderNavigationData() {
    // Just update the checkbox with current data
    const showAuthHeaderCheckbox = document.getElementById('show_auth_header');
    if (showAuthHeaderCheckbox && window.navigationConfig) {
        showAuthHeaderCheckbox.checked = window.navigationConfig.show_auth_in_header || false;
    }
}

function refreshFooterNavigationData() {
    // Rebuild footer links with current data
    if (window.navigationConfig && window.navigationConfig.footer_links) {
        rebuildFooterLinksDisplay(window.navigationConfig.footer_links);
    } else {
        // Show empty state
        const footerLinksContainer = document.getElementById('footer-links');
        footerLinksContainer.innerHTML = `
            <div class="alert alert-info">
                <i class="align-middle me-2" data-feather="info"></i>
                No footer navigation links configured yet. Add your first link below.
            </div>
        `;
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    }
    
    // Update auth checkbox
    const showAuthFooterCheckbox = document.getElementById('show_auth_footer');
    if (showAuthFooterCheckbox && window.navigationConfig) {
        showAuthFooterCheckbox.checked = window.navigationConfig.show_auth_in_footer || false;
    }
}

function refreshSocialMediaData() {
    // Rebuild social media form with current data
    if (window.socialMediaConfig) {
        rebuildSocialMediaForm(window.socialMediaConfig);
    } else {
        // Show empty form
        rebuildSocialMediaForm({});
    }
}

function rebuildFooterLinksDisplay(footerLinks) {
    const modalBody = document.querySelector('#footerNavModal .modal-body');
    const footerLinksContainer = document.getElementById('footer-links');
    
    // Clear existing links
    footerLinksContainer.innerHTML = '';
    
    if (!footerLinks || footerLinks.length === 0) {
        footerLinksContainer.innerHTML = `
            <div class="alert alert-info">
                <i class="align-middle me-2" data-feather="info"></i>
                No footer navigation links configured yet. Add your first link below.
            </div>
        `;
        // Re-initialize feather icons
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
        return;
    }
    
    // Add each link
    footerLinks.forEach((link, index) => {
        const linkHtml = `
            <div class="nav-link-item mb-3" data-index="${index}">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h6 class="card-title mb-0">${link.title || link.name || 'Untitled Link'}</h6>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" 
                                       id="footer-link-${index}-active" 
                                       ${link.active !== undefined ? (link.active ? 'checked' : '') : 'checked'}
                                       onchange="toggleLinkStatus('footer', ${index}, this.checked)">
                                <label class="form-check-label text-muted small" for="footer-link-${index}-active">
                                    Active
                                </label>
                            </div>
                        </div>
                        <p class="card-text small text-muted">
                            <i class="align-middle me-1" data-feather="link"></i>${link.url || '#'}
                            ${link.external ? '<span class="badge bg-info ms-2">External</span>' : ''}
                        </p>
                        <button class="btn btn-outline-danger btn-sm" onclick="removeLink('footer', ${index})">
                            <i class="align-middle me-1" data-feather="trash-2"></i>Remove
                        </button>
                    </div>
                </div>
            </div>
        `;
        footerLinksContainer.insertAdjacentHTML('beforeend', linkHtml);
    });
    
    // Re-initialize feather icons
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
}

function rebuildSocialMediaForm(socialMediaConfig) {
    const modalBody = document.querySelector('#socialMediaModal .modal-body');
    
    const socialPlatforms = {
        'facebook': 'Facebook',
        'twitter': 'Twitter',
        'instagram': 'Instagram',
        'linkedin': 'LinkedIn',
        'youtube': 'YouTube',
        'tiktok': 'TikTok',
        'snapchat': 'Snapchat',
        'pinterest': 'Pinterest'
    };
    
    let formHtml = `
        <p class="text-muted mb-4">Configure your social media links. These will be displayed in your footer templates where the social media placeholder is used.</p>
        <form id="social-media-form">
            <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').getAttribute('content')}">
            <div class="social-media-grid">
    `;
    
    Object.entries(socialPlatforms).forEach(([platform, label]) => {
        const value = socialMediaConfig[platform] || '';
        formHtml += `
            <div class="card mb-3">
                <div class="card-body">
                    <label for="social-${platform}" class="form-label">
                        <i class="align-middle me-2" data-feather="share-2"></i>${label}
                    </label>
                    <input type="url" class="form-control" id="social-${platform}" 
                           name="social_media[${platform}]" 
                           value="${value}" 
                           placeholder="https://${platform}.com/yourprofile">
                </div>
            </div>
        `;
    });
    
    formHtml += `
            </div>
        </form>
    `;
    
    modalBody.innerHTML = formHtml;
    
    // Re-initialize feather icons
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
}

// Header Navigation Functions
function saveHeaderNavigation() {
    // Get authentication setting
    const showAuthHeader = document.getElementById('show_auth_header').checked;
    
    // Get CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    // Prepare data for API call
    const data = {
        _token: csrfToken,
        type: 'header',
        show_auth_in_header: showAuthHeader
    };
    
    // Make API call to save header navigation settings
    fetch('/admin/headers-footers/update-navigation', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Header navigation settings saved successfully!');
            const modal = bootstrap.Modal.getInstance(document.getElementById('headerNavModal'));
            if (modal) modal.hide();
        } else {
            alert('Error: ' + (data.message || 'Failed to save header navigation settings'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while saving header navigation settings.');
    });
}

// Footer Navigation Functions  
function saveFooterNavigation() {
    // Get authentication setting
    const showAuthFooter = document.getElementById('show_auth_footer').checked;
    
    // Get CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    // Prepare data for API call
    const data = {
        _token: csrfToken,
        type: 'footer',
        show_auth_in_footer: showAuthFooter
    };
    
    // Make API call to save footer navigation settings
    fetch('/admin/headers-footers/update-navigation', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Footer navigation settings saved successfully!');
            const modal = bootstrap.Modal.getInstance(document.getElementById('footerNavModal'));
            if (modal) modal.hide();
        } else {
            alert('Error: ' + (data.message || 'Failed to save footer navigation settings'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while saving footer navigation settings.');
    });
}

function toggleLinkStatus(type, index, status) {
    // Get CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    // Prepare data for API call
    const data = {
        _token: csrfToken,
        type: type,
        index: index,
        active: status === 'true' || status === true  // Changed from 'status' to 'active'
    };
    
    // Make API call to toggle link status
    fetch('/admin/headers-footers/toggle-navigation-link', {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Refresh the page to show updated status
            location.reload();
        } else {
            alert('Error: ' + (data.message || 'Failed to toggle link status'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while toggling the link status.');
    });
}

function removeLink(type, index) {
    if(confirm('Are you sure you want to remove this link?')) {
        // Get CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        // Prepare data for API call
        const data = {
            _token: csrfToken,
            type: type,
            index: index
        };
        
        // Make API call to remove link
        fetch('/admin/headers-footers/remove-navigation-link', {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Link removed successfully!');
                // Refresh the page to show updated links
                location.reload();
            } else {
                alert('Error: ' + (data.message || 'Failed to remove link'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while removing the link.');
        });
    }
}

// New inline form functions for Header
function showAddHeaderForm() {
    document.getElementById('add-header-link-form').style.display = 'block';
    document.getElementById('show-add-header-form').style.display = 'none';
    // Focus on first input
    document.getElementById('header-link-title').focus();
}

function cancelAddHeaderLink() {
    document.getElementById('add-header-link-form').style.display = 'none';
    document.getElementById('show-add-header-form').style.display = 'block';
    // Clear form
    document.getElementById('header-link-title').value = '';
    document.getElementById('header-link-url').value = '';
    document.getElementById('header-link-external').checked = false;
    document.getElementById('header-link-active').checked = true;
}

function addHeaderLink() {
    const title = document.getElementById('header-link-title').value.trim();
    const url = document.getElementById('header-link-url').value.trim();
    const external = document.getElementById('header-link-external').checked;
    const active = document.getElementById('header-link-active').checked;
    
    if (!title || !url) {
        alert('Please fill in both title and URL');
        return;
    }
    
    // Show loading state
    const submitButton = document.querySelector('#add-header-link-form .btn-success');
    const originalText = submitButton.textContent;
    submitButton.textContent = 'Adding...';
    submitButton.disabled = true;
    
    // Get CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    // Prepare data for API call
    const data = {
        _token: csrfToken,
        type: 'header',
        title: title,
        url: url,
        external: external,
        active: active
    };
    
    // Make API call to save the link
    fetch('/admin/headers-footers/add-navigation-link', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Header link added successfully!');
            cancelAddHeaderLink();
            
            // Refresh the page to show new link
            location.reload();
        } else {
            alert('Error: ' + (data.message || 'Failed to add header link'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while adding the header link.');
    })
    .finally(() => {
        // Restore button state
        submitButton.textContent = originalText;
        submitButton.disabled = false;
    });
}

// New inline form functions for Footer
function showAddFooterForm() {
    document.getElementById('add-footer-link-form').style.display = 'block';
    document.getElementById('show-add-footer-form').style.display = 'none';
    // Focus on first input
    document.getElementById('footer-link-title').focus();
}

function cancelAddFooterLink() {
    document.getElementById('add-footer-link-form').style.display = 'none';
    document.getElementById('show-add-footer-form').style.display = 'block';
    // Clear form
    document.getElementById('footer-link-title').value = '';
    document.getElementById('footer-link-url').value = '';
    document.getElementById('footer-link-external').checked = false;
    document.getElementById('footer-link-active').checked = true;
}

function addFooterLink() {
    const title = document.getElementById('footer-link-title').value.trim();
    const url = document.getElementById('footer-link-url').value.trim();
    const external = document.getElementById('footer-link-external').checked;
    const active = document.getElementById('footer-link-active').checked;
    
    if (!title || !url) {
        alert('Please fill in both title and URL');
        return;
    }
    
    // Show loading state
    const submitButton = document.querySelector('#add-footer-link-form .btn-success');
    const originalText = submitButton.textContent;
    submitButton.textContent = 'Adding...';
    submitButton.disabled = true;
    
    // Get CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    // Prepare data for API call
    const data = {
        _token: csrfToken,
        type: 'footer',
        title: title,
        url: url,
        external: external,
        active: active
    };
    
    // Make API call to save the link
    fetch('/admin/headers-footers/add-navigation-link', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Footer link added successfully!');
            cancelAddFooterLink();
            
            // Refresh the page to show new link
            location.reload();
        } else {
            alert('Error: ' + (data.message || 'Failed to add footer link'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while adding the footer link.');
    })
    .finally(() => {
        // Restore button state
        submitButton.textContent = originalText;
        submitButton.disabled = false;
    });
}

// Social Media Functions
function updateSocialMedia() {
    const form = document.getElementById('social-media-form');
    const formData = new FormData(form);
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    // Get the save button for loading state
    const saveButton = document.querySelector('#socialMediaModal .btn-primary');
    const originalText = saveButton.textContent;
    saveButton.textContent = 'Saving...';
    saveButton.disabled = true;
    
    // Convert FormData to JSON
    const data = {};
    for (let [key, value] of formData.entries()) {
        if (key.startsWith('social_media[') && key.endsWith(']')) {
            // Extract platform name from social_media[platform] format
            const platform = key.match(/social_media\[([^\]]+)\]/)[1];
            data[platform] = value;
        }
    }
    
    fetch('/admin/headers-footers/social-media', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ social_media: data })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Social media links updated successfully!');
            // Use Bootstrap 5 API to hide modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('socialMediaModal'));
            if (modal) {
                modal.hide();
            }
        } else {
            alert('Error: ' + (data.message || 'Failed to update social media links'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while updating social media links.');
    })
    .finally(() => {
        // Restore button state
        saveButton.textContent = originalText;
        saveButton.disabled = false;
    });
}
</script>
@endpush
