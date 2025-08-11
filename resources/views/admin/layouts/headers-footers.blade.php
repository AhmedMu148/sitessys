@extends('admin.layouts.master')

@section('title', 'Headers & Footers Management')

@section('css')
<link href="{{ asset('css/admin/headers-footers.css') }}" rel="stylesheet">
<style>
/* Headers & Footers Management Styles */
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

.global-template {
    border-left: 4px solid #007bff;
    background: linear-gradient(135deg, #f8fbff 0%, #deecff 100%);
}

.user-template {
    border-left: 4px solid #28a745;
    background: linear-gradient(135deg, #f0fff4 0%, #d4edda 100%);
}

/* Template Card Structure - Same as templates page */
.card-top-section {
    position: relative;
    height: 160px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    overflow: hidden;
}

.card-top-image {
    position: relative;
    width: 100%;
    height: 100%;
    overflow: hidden;
}

.card-top-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.template-card:hover .card-top-image img {
    transform: scale(1.05);
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

.card-top-fallback {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.image-overlay-text {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: linear-gradient(transparent, rgba(0,0,0,0.8));
    color: white;
    padding: 20px 15px 15px;
    font-size: 0.9rem;
    font-weight: 500;
}

.card-bottom-section {
    padding: 15px;
    height: 140px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    background: white;
}

.card-bottom-text {
    font-size: 0.85rem;
    line-height: 1.4;
    color: #666;
    flex-grow: 1;
}

.card-actions {
    position: absolute;
    top: 10px;
    right: 10px;
    z-index: 10;
}

.actions-btn {
    background: rgba(0,0,0,0.5);
    border: none;
    border-radius: 50%;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    transition: all 0.3s ease;
}

.actions-btn:hover {
    background: rgba(0,0,0,0.8);
    transform: scale(1.1);
}

/* Section Template Card Specific Styles */
.section-template-card {
    margin-bottom: 1.5rem;
}

.section-template-card .template-card {
    cursor: pointer;
}

.section-template-card .template-card:hover {
    transform: translateY(-4px) scale(1.02);
}

/* Statistics Cards */
.card h4 {
    margin: 0;
    font-weight: 700;
}

.template-preview {
    height: 120px;
    background: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.9rem;
    color: #6c757d;
}

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
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
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

.badge-global {
    background: linear-gradient(45deg, #007bff, #0056b3);
    color: white;
}

.badge-user {
    background: linear-gradient(45deg, #28a745, #1e7e34);
    color: white;
}

.btn-activate {
    background: linear-gradient(45deg, #28a745, #20c997);
    border: none;
    transition: all 0.3s ease;
}

.btn-activate:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
}

.btn-copy {
    background: linear-gradient(45deg, #007bff, #0056b3);
    border: none;
    transition: all 0.3s ease;
}

.btn-copy:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3);
}

.current-template {
    border: 2px solid #28a745 !important;
    box-shadow: 0 4px 15px rgba(40, 167, 69, 0.2);
}

.card {
    border-radius: 12px;
    border: 1px solid #e3e6f0;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
}

.card:hover {
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.card-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-bottom: 1px solid #e3e6f0;
    border-radius: 12px 12px 0 0;
    font-weight: 600;
}

.card-body {
    padding: 1.5rem;
}

.alert {
    border-radius: 8px;
    border: none;
    margin-bottom: 1.5rem;
}

.alert-success {
    background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
    color: #155724;
}

.alert-danger {
    background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
    color: #721c24;
}

.alert-info {
    background: linear-gradient(135deg, #cce7ff 0%, #b3d9ff 100%);
    color: #0c5460;
}

.form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.form-check-input:checked {
    background-color: #007bff;
    border-color: #007bff;
}

.badge {
    font-size: 0.75rem;
    font-weight: 500;
    padding: 0.375rem 0.75rem;
    border-radius: 6px;
}

.feather {
    width: 16px;
    height: 16px;
    vertical-align: text-bottom;
}

@media (max-width: 768px) {
    .social-media-grid {
        grid-template-columns: 1fr;
    }
    
    .template-card {
        margin-bottom: 1rem;
    }
    
    .nav-link-item {
        padding: 8px;
    }
    
    .card-actions {
        top: 8px;
        right: 8px;
    }
    
    .actions-btn {
        width: 32px;
        height: 32px;
    }
}

/* ===================== DROPDOWN ACTIONS STYLES ===================== */
/* From shared admin panel styles - Card Actions Dropdown */
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

.actions-btn:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(34, 46, 60, 0.25);
    background: linear-gradient(135deg, #222e3c 0%, #2b3947 100%);
}

.actions-btn:active {
    transform: scale(0.95);
    background: linear-gradient(135deg, #1a2530 0%, #222e3c 100%);
}

.actions-btn i {
    transition: all 0.3s ease;
    color: #ffffff;
    stroke-width: 2;
    display: inline-block;
    vertical-align: middle;
}

.actions-btn:hover i,
.actions-btn:focus i {
    color: #ffffff;
    transform: rotate(90deg);
}

.actions-btn[aria-expanded="true"] i {
    color: #ffffff;
    transform: rotate(180deg);
}

.actions-btn svg {
    display: inline-block !important;
    vertical-align: middle;
    pointer-events: none;
}

/* Dropdown Menu */
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

.dropdown-item:focus {
    background: #f8faff;
    color: #222e3c;
    outline: none;
}

.dropdown-item.text-danger:hover {
    background: #fee2e2;
    color: #dc2626 !important;
}

/* Button styled as dropdown item */
.dropdown-item.border-0.bg-transparent {
    padding: 0.5rem 1rem;
    transition: all 0.2s ease;
    font-weight: 500;
    color: #475569;
    cursor: pointer;
}

.dropdown-item.border-0.bg-transparent:hover {
    background: #f8faff !important;
    color: #222e3c !important;
}

.dropdown-item.border-0.bg-transparent:focus {
    background: #f8faff !important;
    color: #222e3c !important;
    outline: none;
    box-shadow: none;
}

.dropdown-divider {
    margin: 0.5rem 0.5rem;
    border-color: rgba(34, 46, 60, 0.1);
    opacity: 0.7;
}

/* RTL Support for Dropdowns */
[dir="rtl"] .card-actions {
    right: auto;
    left: 12px;
}

[dir="rtl"] .dropdown-menu {
    right: auto;
    left: 0;
}

[dir="rtl"] .dropdown-item:hover {
    transform: translateX(-4px);
}

/* Dashed border for add forms */
.border-dashed {
    border: 2px dashed #dee2e6 !important;
    background: #f8f9fa;
}

.border-dashed:hover {
    border-color: #007bff !important;
    background: #f0f8ff;
}

/* Social Media Grid */
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
</style>
@endsection

@section('content')
<div class="container-fluid p-0">
    {{-- Page Header --}}
    <div class="mb-3">
        <h1 class="h3 d-inline align-middle">Headers & Footers Management</h1>
        <p class="text-muted mb-0">Manage your website's headers and footers with global templates and navigation</p>
    </div>

    {{-- Success/Error Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="align-middle me-2" data-feather="check-circle"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="align-middle me-2" data-feather="alert-circle"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Navigation Tabs --}}
    <ul class="nav nav-tabs" id="headerFooterTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="headers-tab" data-bs-toggle="tab" data-bs-target="#headers" type="button" role="tab">
                <i class="align-middle me-2" data-feather="layout"></i>Headers ({{ count($availableTemplates['global']) > 0 ? count(array_filter($availableTemplates['global'], fn($t) => $t['layout_type'] === 'header')) : 0 }})
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="sections-tab" data-bs-toggle="tab" data-bs-target="#sections" type="button" role="tab">
                <i class="align-middle me-2" data-feather="grid"></i>Sections ({{ count($availableTemplates['global']) > 0 ? count(array_filter($availableTemplates['global'], fn($t) => $t['layout_type'] === 'section')) : 0 }})
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="footers-tab" data-bs-toggle="tab" data-bs-target="#footers" type="button" role="tab">
                <i class="align-middle me-2" data-feather="layers"></i>Footers ({{ count($availableTemplates['global']) > 0 ? count(array_filter($availableTemplates['global'], fn($t) => $t['layout_type'] === 'footer')) : 0 }})
            </button>
        </li>
    </ul>

    {{-- Tab Content --}}
    <div class="tab-content" id="headerFooterTabContent">
        {{-- Headers Tab --}}
        <div class="tab-pane fade show active" id="headers" role="tabpanel">
            <div class="row">
                <div class="col-12">
                    <h5 class="mb-3"><i class="align-middle text-primary me-2" data-feather="star"></i>Global Header Templates</h5>
                    <p class="text-muted small mb-4">Choose from our professionally designed global templates. You can copy and customize any template for your site.</p>
                </div>
            </div>
            
            <div class="row">
                @if(isset($availableTemplates['global']) && count($availableTemplates['global']) > 0)
                    @foreach(array_filter($availableTemplates['global'], fn($template) => $template['layout_type'] === 'header') as $template)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card template-card global-template h-100 {{ $site->active_header_id == $template['id'] ? 'current-template' : '' }}"
                                 onmouseenter="showCardActions(this)" 
                                 onmouseleave="hideCardActions(this)">
                                
                                <!-- Card Actions Dropdown -->
                                <div class="card-actions">
                                    <div class="dropdown">
                                        <button class="btn actions-btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Actions">
                                            <i data-feather="more-vertical" style="width: 16px; height: 16px;"></i>
                                            <span class="visually-hidden">Actions</span>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            @if($site->active_header_id == $template['id'])
                                                <li>
                                                    <span class="dropdown-item text-success">
                                                        <i class="align-middle me-2" data-feather="check-circle"></i>
                                                        Currently Active
                                                    </span>
                                                </li>
                                            @else
                                                <li>
                                                    <form method="POST" action="{{ route('admin.headers-footers.activate', $template['id']) }}" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="dropdown-item border-0 bg-transparent text-start w-100">
                                                            <i class="align-middle me-2" data-feather="play"></i>
                                                            Activate Header
                                                        </button>
                                                    </form>
                                                </li>
                                            @endif
                                            <li>
                                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#headerNavModal">
                                                    <i class="align-middle me-2" data-feather="menu"></i>
                                                    Edit Navigation
                                                </a>
                                            </li>

                                        </ul>
                                    </div>
                                </div>
                                
                                <div class="template-preview">
                                    @if($template['preview_image'])
                                        <img src="{{ $template['preview_image'] }}" alt="Preview" class="img-fluid">
                                    @else
                                        <div class="text-center">
                                            <i class="align-middle" data-feather="layout" style="font-size: 2rem;"></i>
                                            <div>{{ $template['name'] }}</div>
                                        </div>
                                    @endif
                                </div>
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h6 class="card-title mb-0">{{ $template['name'] }}</h6>
                                        <span class="badge badge-global">Global</span>
                                    </div>
                                    <p class="card-text text-muted small">{{ $template['description'] ?? 'Professional header template' }}</p>
                                    
                                    <div class="d-flex gap-2">
                                        @if($site->active_header_id == $template['id'])
                                            <button class="btn btn-success btn-sm disabled">
                                                <i class="align-middle me-1" data-feather="check"></i>Active
                                            </button>
                                        @else
                                            <form method="POST" action="{{ route('admin.headers-footers.activate', $template['id']) }}" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-activate btn-sm text-white">
                                                    <i class="align-middle me-1" data-feather="play"></i>Activate
                                                </button>
                                            </form>
                                        @endif
                                        
                                        <button type="button" class="btn btn-copy btn-sm text-white" onclick="copyTemplate({{ $template['id'] }}, 'header')">
                                            <i class="align-middle me-1" data-feather="copy"></i>Edit Data
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="col-12">
                        <div class="alert alert-info">
                            <i class="align-middle me-2" data-feather="info"></i>No global header templates available.
                        </div>
                    </div>
                @endif
            </div>

            {{-- User Templates Section --}}
            @if(isset($availableTemplates['user']) && count(array_filter($availableTemplates['user'], fn($template) => $template['layout_type'] === 'header')) > 0)
                <div class="row mt-5">
                    <div class="col-12">
                        <h5 class="mb-3"><i class="align-middle text-success me-2" data-feather="user"></i>Your Custom Headers</h5>
                        <p class="text-muted small mb-4">Templates you've copied and customized for your site.</p>
                    </div>
                </div>
                
                <div class="row">
                    @foreach(array_filter($availableTemplates['user'], fn($template) => $template['layout_type'] === 'header') as $template)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card template-card user-template h-100 {{ $site->active_header_id == $template['id'] ? 'current-template' : '' }}"
                                 onmouseenter="showCardActions(this)" 
                                 onmouseleave="hideCardActions(this)">
                                
                                <!-- Card Actions Dropdown -->
                                <div class="card-actions">
                                    <div class="dropdown">
                                        <button class="btn actions-btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Actions">
                                            <i data-feather="more-vertical" style="width: 16px; height: 16px;"></i>
                                            <span class="visually-hidden">Actions</span>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            @if($site->active_header_id == $template['id'])
                                                <li>
                                                    <span class="dropdown-item text-success">
                                                        <i class="align-middle me-2" data-feather="check-circle"></i>
                                                        Currently Active
                                                    </span>
                                                </li>
                                            @else
                                                <li>
                                                    <form method="POST" action="{{ route('admin.headers-footers.activate', $template['id']) }}" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="dropdown-item border-0 bg-transparent text-start w-100">
                                                            <i class="align-middle me-2" data-feather="play"></i>
                                                            Activate Header
                                                        </button>
                                                    </form>
                                                </li>
                                            @endif
                                            <li>
                                                <a class="dropdown-item" href="#" onclick="editTemplate({{ $template['id'] }})">
                                                    <i class="align-middle me-2" data-feather="edit"></i>
                                                    Edit Template
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#headerNavModal">
                                                    <i class="align-middle me-2" data-feather="menu"></i>
                                                    Edit Navigation
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="#" onclick="previewTemplate({{ $template['id'] }})">
                                                    <i class="align-middle me-2" data-feather="eye"></i>
                                                    Preview Template
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="#" onclick="duplicateTemplate({{ $template['id'] }})">
                                                    <i class="align-middle me-2" data-feather="copy"></i>
                                                    Duplicate Template
                                                </a>
                                            </li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <a class="dropdown-item text-danger" href="#" onclick="confirmDeleteTemplate({{ $template['id'] }})">
                                                    <i class="align-middle me-2" data-feather="trash-2"></i>
                                                    Delete Template
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                
                                <div class="template-preview">
                                    @if($template['preview_image'])
                                        <img src="{{ $template['preview_image'] }}" alt="Preview" class="img-fluid">
                                    @else
                                        <div class="text-center">
                                            <i class="align-middle" data-feather="layout" style="font-size: 2rem;"></i>
                                            <div>{{ $template['name'] }}</div>
                                        </div>
                                    @endif
                                </div>
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h6 class="card-title mb-0">{{ $template['name'] }}</h6>
                                        <span class="badge badge-user">Custom</span>
                                    </div>
                                    <p class="card-text text-muted small">{{ $template['description'] ?? 'Your custom header template' }}</p>
                                    
                                    <div class="d-flex gap-2">
                                        @if($site->active_header_id == $template['id'])
                                            <button class="btn btn-success btn-sm disabled">
                                                <i class="align-middle me-1" data-feather="check"></i>Active
                                            </button>
                                        @else
                                            <form method="POST" action="{{ route('admin.headers-footers.activate', $template['id']) }}" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-activate btn-sm text-white">
                                                    <i class="align-middle me-1" data-feather="play"></i>Activate
                                                </button>
                                            </form>
                                        @endif
                                        
                                        <form method="POST" action="{{ route('admin.headers-footers.destroy', $template['id']) }}" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this template?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                                <i class="align-middle me-1" data-feather="trash-2"></i>Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Sections Tab --}}
        <div class="tab-pane fade" id="sections" role="tabpanel">
            <div class="row">
                <div class="col-12">
                    <h5 class="mb-3"><i class="align-middle text-primary me-2" data-feather="star"></i>Global Section Templates</h5>
                    <p class="text-muted small mb-4">Choose from our professionally designed global section templates. You can copy and customize any template for your site.</p>
                </div>
            </div>
            
            <div class="row">
                @if(isset($availableTemplates['global']) && count($availableTemplates['global']) > 0)
                    @foreach(array_filter($availableTemplates['global'], fn($template) => $template['layout_type'] === 'section') as $template)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card template-card global-template h-100"
                                 onmouseenter="showCardActions(this)" 
                                 onmouseleave="hideCardActions(this)">
                                
                                <!-- Card Actions Dropdown -->
                                <div class="card-actions">
                                    <div class="dropdown">
                                        <button class="btn actions-btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Actions">
                                            <i data-feather="more-vertical" style="width: 16px; height: 16px;"></i>
                                            <span class="visually-hidden">Actions</span>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <a class="dropdown-item" href="#" onclick="addSectionToPage({{ $template['id'] }})">
                                                    <i class="align-middle me-2" data-feather="plus"></i>
                                                    Add to Page
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="#" onclick="copyTemplate({{ $template['id'] }}, 'section')">
                                                    <i class="align-middle me-2" data-feather="copy"></i>
                                                    Edite data
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                
                                <div class="template-preview">
                                    @if($template['preview_image'])
                                        <img src="{{ $template['preview_image'] }}" alt="Preview" class="img-fluid">
                                    @else
                                        <div class="text-center">
                                            <i class="align-middle" data-feather="grid" style="font-size: 2rem;"></i>
                                            <div>{{ $template['name'] }}</div>
                                        </div>
                                    @endif
                                </div>
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h6 class="card-title mb-0">{{ $template['name'] }}</h6>
                                        <span class="badge badge-global">Global</span>
                                    </div>
                                    <p class="card-text text-muted small">{{ $template['description'] ?? 'Professional section template' }}</p>
                                    
                                    <div class="d-flex gap-2">
                                        <button type="button" class="btn btn-primary btn-sm" onclick="addSectionToPage({{ $template['id'] }})">
                                            <i class="align-middle me-1" data-feather="plus"></i>Add to Page
                                        </button>
                                        
                                        <button type="button" class="btn btn-copy btn-sm text-white" onclick="copyTemplate({{ $template['id'] }}, 'section')">
                                            <i class="align-middle me-1" data-feather="copy"></i>Edite Data
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="col-12">
                        <div class="alert alert-info">
                            <i class="align-middle me-2" data-feather="info"></i>No global section templates available.
                        </div>
                    </div>
                @endif
            </div>

            {{-- User Templates Section --}}
            @if(isset($availableTemplates['user']) && count(array_filter($availableTemplates['user'], fn($template) => $template['layout_type'] === 'section')) > 0)
                <div class="row mt-5">
                    <div class="col-12">
                        <h5 class="mb-3"><i class="align-middle text-success me-2" data-feather="user"></i>Your Custom Sections</h5>
                        <p class="text-muted small mb-4">Templates you've copied and customized for your site.</p>
                    </div>
                </div>
                
                <div class="row">
                    @foreach(array_filter($availableTemplates['user'], fn($template) => $template['layout_type'] === 'section') as $template)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card template-card user-template h-100"
                                 onmouseenter="showCardActions(this)" 
                                 onmouseleave="hideCardActions(this)">
                                
                                <!-- Card Actions Dropdown -->
                                <div class="card-actions">
                                    <div class="dropdown">
                                        <button class="btn actions-btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Actions">
                                            <i data-feather="more-vertical" style="width: 16px; height: 16px;"></i>
                                            <span class="visually-hidden">Actions</span>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <a class="dropdown-item" href="#" onclick="addSectionToPage({{ $template['id'] }})">
                                                    <i class="align-middle me-2" data-feather="plus"></i>
                                                    Add to Page
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="#" onclick="editTemplate({{ $template['id'] }})">
                                                    <i class="align-middle me-2" data-feather="edit"></i>
                                                    Edit Template
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="#" onclick="previewTemplate({{ $template['id'] }})">
                                                    <i class="align-middle me-2" data-feather="eye"></i>
                                                    Preview Template
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="#" onclick="duplicateTemplate({{ $template['id'] }})">
                                                    <i class="align-middle me-2" data-feather="copy"></i>
                                                    Duplicate Template
                                                </a>
                                            </li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <a class="dropdown-item text-danger" href="#" onclick="confirmDeleteTemplate({{ $template['id'] }})">
                                                    <i class="align-middle me-2" data-feather="trash-2"></i>
                                                    Delete Template
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                
                                <div class="template-preview">
                                    @if($template['preview_image'])
                                        <img src="{{ $template['preview_image'] }}" alt="Preview" class="img-fluid">
                                    @else
                                        <div class="text-center">
                                            <i class="align-middle" data-feather="grid" style="font-size: 2rem;"></i>
                                            <div>{{ $template['name'] }}</div>
                                        </div>
                                    @endif
                                </div>
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h6 class="card-title mb-0">{{ $template['name'] }}</h6>
                                        <span class="badge badge-user">Custom</span>
                                    </div>
                                    <p class="card-text text-muted small">{{ $template['description'] ?? 'Your custom section template' }}</p>
                                    
                                    <div class="d-flex gap-2">
                                        <button type="button" class="btn btn-primary btn-sm" onclick="addSectionToPage({{ $template['id'] }})">
                                            <i class="align-middle me-1" data-feather="plus"></i>Add to Page
                                        </button>
                                        
                                        <form method="POST" action="{{ route('admin.headers-footers.destroy', $template['id']) }}" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this template?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                                <i class="align-middle me-1" data-feather="trash-2"></i>Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Footers Tab --}}
        <div class="tab-pane fade" id="footers" role="tabpanel">
            <div class="row">
                <div class="col-12">
                    <h5 class="mb-3"><i class="align-middle text-primary me-2" data-feather="star"></i>Global Footer Templates</h5>
                    <p class="text-muted small mb-4">Choose from our professionally designed global templates. You can copy and customize any template for your site.</p>
                </div>
            </div>
            
            <div class="row">
                @if(isset($availableTemplates['global']) && count($availableTemplates['global']) > 0)
                    @foreach(array_filter($availableTemplates['global'], fn($template) => $template['layout_type'] === 'footer') as $template)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card template-card global-template h-100 {{ $site->active_footer_id == $template['id'] ? 'current-template' : '' }}"
                                 onmouseenter="showCardActions(this)" 
                                 onmouseleave="hideCardActions(this)">
                                
                                <!-- Card Actions Dropdown -->
                                <div class="card-actions">
                                    <div class="dropdown">
                                        <button class="btn actions-btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Actions">
                                            <i data-feather="more-vertical" style="width: 16px; height: 16px;"></i>
                                            <span class="visually-hidden">Actions</span>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            @if($site->active_footer_id == $template['id'])
                                                <li>
                                                    <span class="dropdown-item text-success">
                                                        <i class="align-middle me-2" data-feather="check-circle"></i>
                                                        Currently Active
                                                    </span>
                                                </li>
                                            @else
                                                <li>
                                                    <form method="POST" action="{{ route('admin.headers-footers.activate', $template['id']) }}" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="dropdown-item border-0 bg-transparent text-start w-100">
                                                            <i class="align-middle me-2" data-feather="play"></i>
                                                            Activate Footer
                                                        </button>
                                                    </form>
                                                </li>
                                            @endif
                                            <li>
                                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#footerNavModal">
                                                    <i class="align-middle me-2" data-feather="layers"></i>
                                                    Edit Navigation
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#socialMediaModal">
                                                    <i class="align-middle me-2" data-feather="share-2"></i>
                                                    Edit Social Media
                                                </a>
                                            </li>
                                           
                                           
                                        </ul>
                                    </div>
                                </div>
                                
                                <div class="template-preview">
                                    @if($template['preview_image'])
                                        <img src="{{ $template['preview_image'] }}" alt="Preview" class="img-fluid">
                                    @else
                                        <div class="text-center">
                                            <i class="align-middle" data-feather="layers" style="font-size: 2rem;"></i>
                                            <div>{{ $template['name'] }}</div>
                                        </div>
                                    @endif
                                </div>
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h6 class="card-title mb-0">{{ $template['name'] }}</h6>
                                        <span class="badge badge-global">Global</span>
                                    </div>
                                    <p class="card-text text-muted small">{{ $template['description'] ?? 'Professional footer template' }}</p>
                                    
                                    <div class="d-flex gap-2">
                                        @if($site->active_footer_id == $template['id'])
                                            <button class="btn btn-success btn-sm disabled">
                                                <i class="align-middle me-1" data-feather="check"></i>Active
                                            </button>
                                        @else
                                            <form method="POST" action="{{ route('admin.headers-footers.activate', $template['id']) }}" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-activate btn-sm text-white">
                                                    <i class="align-middle me-1" data-feather="play"></i>Activate
                                                </button>
                                            </form>
                                        @endif
                                        
                                        <button type="button" class="btn btn-copy btn-sm text-white" onclick="copyTemplate({{ $template['id'] }}, 'footer')">
                                            <i class="align-middle me-1" data-feather="copy"></i>Edite Data 
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="col-12">
                        <div class="alert alert-info">
                            <i class="align-middle me-2" data-feather="info"></i>No global footer templates available.
                        </div>
                    </div>
                @endif
            </div>

            {{-- User Templates Section --}}
            @if(isset($availableTemplates['user']) && count(array_filter($availableTemplates['user'], fn($template) => $template['layout_type'] === 'footer')) > 0)
                <div class="row mt-5">
                    <div class="col-12">
                        <h5 class="mb-3"><i class="align-middle text-success me-2" data-feather="user"></i>Your Custom Footers</h5>
                        <p class="text-muted small mb-4">Templates you've copied and customized for your site.</p>
                    </div>
                </div>
                
                <div class="row">
                    @foreach(array_filter($availableTemplates['user'], fn($template) => $template['layout_type'] === 'footer') as $template)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card template-card user-template h-100 {{ $site->active_footer_id == $template['id'] ? 'current-template' : '' }}"
                                 onmouseenter="showCardActions(this)" 
                                 onmouseleave="hideCardActions(this)">
                                
                                <!-- Card Actions Dropdown -->
                                <div class="card-actions">
                                    <div class="dropdown">
                                        <button class="btn actions-btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Actions">
                                            <i data-feather="more-vertical" style="width: 16px; height: 16px;"></i>
                                            <span class="visually-hidden">Actions</span>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            @if($site->active_footer_id == $template['id'])
                                                <li>
                                                    <span class="dropdown-item text-success">
                                                        <i class="align-middle me-2" data-feather="check-circle"></i>
                                                        Currently Active
                                                    </span>
                                                </li>
                                            @else
                                                <li>
                                                    <form method="POST" action="{{ route('admin.headers-footers.activate', $template['id']) }}" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="dropdown-item border-0 bg-transparent text-start w-100">
                                                            <i class="align-middle me-2" data-feather="play"></i>
                                                            Activate Footer
                                                        </button>
                                                    </form>
                                                </li>
                                            @endif
                                            <li>
                                                <a class="dropdown-item" href="#" onclick="editTemplate({{ $template['id'] }})">
                                                    <i class="align-middle me-2" data-feather="edit"></i>
                                                    Edit Template
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#footerNavModal">
                                                    <i class="align-middle me-2" data-feather="layers"></i>
                                                    Edit Navigation
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#socialMediaModal">
                                                    <i class="align-middle me-2" data-feather="share-2"></i>
                                                    Edit Social Media
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="#" onclick="previewTemplate({{ $template['id'] }})">
                                                    <i class="align-middle me-2" data-feather="eye"></i>
                                                    Preview Template
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="#" onclick="duplicateTemplate({{ $template['id'] }})">
                                                    <i class="align-middle me-2" data-feather="copy"></i>
                                                    Duplicate Template
                                                </a>
                                            </li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <a class="dropdown-item text-danger" href="#" onclick="confirmDeleteTemplate({{ $template['id'] }})">
                                                    <i class="align-middle me-2" data-feather="trash-2"></i>
                                                    Delete Template
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                
                                <div class="template-preview">
                                    @if($template['preview_image'])
                                        <img src="{{ $template['preview_image'] }}" alt="Preview" class="img-fluid">
                                    @else
                                        <div class="text-center">
                                            <i class="align-middle" data-feather="layers" style="font-size: 2rem;"></i>
                                            <div>{{ $template['name'] }}</div>
                                        </div>
                                    @endif
                                </div>
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h6 class="card-title mb-0">{{ $template['name'] }}</h6>
                                        <span class="badge badge-user">Custom</span>
                                    </div>
                                    <p class="card-text text-muted small">{{ $template['description'] ?? 'Your custom footer template' }}</p>
                                    
                                    <div class="d-flex gap-2">
                                        @if($site->active_footer_id == $template['id'])
                                            <button class="btn btn-success btn-sm disabled">
                                                <i class="align-middle me-1" data-feather="check"></i>Active
                                            </button>
                                        @else
                                            <form method="POST" action="{{ route('admin.headers-footers.activate', $template['id']) }}" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-activate btn-sm text-white">
                                                    <i class="align-middle me-1" data-feather="play"></i>Activate
                                                </button>
                                            </form>
                                        @endif
                                        
                                        <form method="POST" action="{{ route('admin.headers-footers.destroy', $template['id']) }}" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this template?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                                <i class="align-middle me-1" data-feather="trash-2"></i>Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div>
</div>

{{-- Add Section to Page Modal --}}
<div class="modal fade" id="addSectionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Section to Page</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="add-section-form">
                    @csrf
                    <input type="hidden" id="section-template-id" name="template_id">
                    
                    <div class="mb-3">
                        <label for="selected-page" class="form-label">Select Page</label>
                        <select class="form-control" id="selected-page" name="page_id" required>
                            <option value="">Choose a page...</option>
                            @foreach($availablePages ?? [] as $page)
                                <option value="{{ $page['id'] }}">{{ $page['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="section-position" class="form-label">Position</label>
                        <select class="form-control" id="section-position" name="position">
                            <option value="end">At the end</option>
                            <option value="start">At the beginning</option>
                            <option value="custom">Custom position</option>
                        </select>
                    </div>
                    
                    <div class="mb-3" id="custom-position-field" style="display: none;">
                        <label for="sort-order" class="form-label">Sort Order</label>
                        <input type="number" class="form-control" id="sort-order" name="sort_order" min="0" placeholder="0">
                        <small class="form-text text-muted">Higher numbers appear later in the page</small>
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="align-middle me-2" data-feather="info"></i>
                        <span id="section-info">Select a section template and page to add it to your website.</span>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="confirmAddSection()">Add Section</button>
            </div>
        </div>
    </div>
</div>

{{-- Add Link Modal --}}
<div class="modal fade" id="addLinkModal" tabindex="-1" style="z-index: 1060;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Navigation Link</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="add-link-form">
                    @csrf
                    <input type="hidden" id="link-type" name="type">
                    
                    <div class="mb-3">
                        <label for="link-title" class="form-label">Link Title</label>
                        <input type="text" class="form-control" id="link-title" name="title" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="link-source" class="form-label">Link Source</label>
                        <select class="form-control" id="link-source" onchange="toggleLinkSourceFields()">
                            <option value="custom">Custom URL</option>
                            <option value="page">Existing Page</option>
                        </select>
                    </div>
                    
                    <div class="mb-3" id="custom-url-field">
                        <label for="link-url" class="form-label">URL</label>
                        <input type="url" class="form-control" id="link-url" name="url" placeholder="https://example.com or /page">
                    </div>
                    
                    <div class="mb-3" id="page-select-field" style="display: none;">
                        <label for="page-select" class="form-label">Select Page</label>
                        <select class="form-control" id="page-select">
                            <option value="">Select a page...</option>
                            @foreach($availablePages as $page)
                                <option value="/{{ $page['slug'] }}" data-title="{{ $page['name'] }}">{{ $page['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="link-external" name="external">
                            <label class="form-check-label" for="link-external">
                                External Link (opens in new tab)
                            </label>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="link-active" name="active" checked>
                            <label class="form-check-label" for="link-active">
                                Active (visible in navigation)
                            </label>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="addNavigationLink()">Add Link</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// Pass navigation configuration to JavaScript
window.navigationConfig = @json($navigationConfig);
window.socialMediaConfig = @json($socialMediaConfig);
window.availablePages = @json($availablePages);

// Section Templates Functions
function addSectionToPage(templateId) {
    // Set the template ID in the modal
    document.getElementById('section-template-id').value = templateId;
    
    // Update the info text
    const sectionInfo = document.getElementById('section-info');
    sectionInfo.textContent = `Adding section template ID: ${templateId} to the selected page.`;
    
    // Show the modal
    const modal = new bootstrap.Modal(document.getElementById('addSectionModal'));
    modal.show();
}

function confirmAddSection() {
    const templateId = document.getElementById('section-template-id').value;
    const pageId = document.getElementById('selected-page').value;
    const position = document.getElementById('section-position').value;
    const sortOrder = document.getElementById('sort-order').value;
    
    if (!templateId || !pageId) {
        alert('Please select a page.');
        return;
    }
    
    // Show loading state
    const submitButton = document.querySelector('#addSectionModal .btn-primary');
    const originalText = submitButton.textContent;
    submitButton.textContent = 'Adding...';
    submitButton.disabled = true;
    
    // Get CSRF token from the form
    const csrfToken = document.querySelector('#add-section-form input[name="_token"]').value;
    
    // Prepare data for API call
    const data = {
        _token: csrfToken,
        template_id: parseInt(templateId),
        page_id: parseInt(pageId),
        position: position,
        sort_order: position === 'custom' && sortOrder ? parseInt(sortOrder) : null
    };
    
    console.log('Sending data:', data); // Debug log
    
    // Make API call to add section to page
    fetch('/admin/sections/add-to-page', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => {
        console.log('Response status:', response.status); // Debug log
        console.log('Response headers:', response.headers); // Debug log
        console.log('Response ok:', response.ok); // Debug log
        
        // Check if response is actually JSON
        const contentType = response.headers.get("content-type");
        if (contentType && contentType.indexOf("application/json") !== -1) {
            return response.json();
        } else {
            console.log('Response is not JSON, returning text'); // Debug log
            return response.text();
        }
    })
    .then(responseData => {
        console.log('Response data:', responseData); // Debug log
        console.log('Response data type:', typeof responseData); // Debug log
        
        // Handle different response types
        let data = responseData;
        if (typeof responseData === 'string') {
            try {
                data = JSON.parse(responseData);
            } catch (e) {
                console.error('Failed to parse JSON:', e);
                console.log('Raw response:', responseData);
                alert('Server returned invalid response: ' + responseData.substring(0, 200));
                return;
            }
        }
        if (data.success) {
            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('addSectionModal'));
            modal.hide();
            
            // Show success message
            alert(`Section "${data.data.template_name}" added to page "${data.data.page_name}" successfully!`);
            
            // Reset form
            document.getElementById('add-section-form').reset();
        } else {
            alert('Error: ' + (data.message || 'Failed to add section to page'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while adding the section to the page.');
    })
    .finally(() => {
        // Restore button state
        submitButton.textContent = originalText;
        submitButton.disabled = false;
    });
}

// Handle position selection change
document.addEventListener('DOMContentLoaded', function() {
    const positionSelect = document.getElementById('section-position');
    const customPositionField = document.getElementById('custom-position-field');
    
    if (positionSelect) {
        positionSelect.addEventListener('change', function() {
            if (this.value === 'custom') {
                customPositionField.style.display = 'block';
            } else {
                customPositionField.style.display = 'none';
            }
        });
    }
});

function createNewSection() {
    // Implementation for creating new section
    console.log('Creating new section template');
    
    // Redirect to create page
    window.location.href = '/admin/templates/create?type=section';
}

function refreshSections() {
    // Implementation for refreshing sections
    console.log('Refreshing sections');
    location.reload();
}

// Initialize section functionality
document.addEventListener('DOMContentLoaded', function() {
    // Initialize feather icons for any new icons
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
});

// ============ Dropdown Functions for Template Cards ============

// Show/Hide card actions on hover
function showCardActions(card) {
    const actions = card.querySelector('.card-actions');
    if (actions) {
        actions.style.opacity = '1';
        actions.style.transform = 'translateY(0)';
    }
}

function hideCardActions(card) {
    const actions = card.querySelector('.card-actions');
    if (actions) {
        actions.style.opacity = '1'; // Keep visible for accessibility
        actions.style.transform = 'translateY(0)';
    }
}

// Activate template function
function activateTemplate(templateId, type) {
    if (confirm(`Are you sure you want to activate this ${type} template?`)) {
        // Create a form and submit it
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/headers-footers/activate/${templateId}`;
        
        // Add CSRF token
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        form.appendChild(csrfInput);
        
        document.body.appendChild(form);
        form.submit();
    }
}

// Edit template function
function editTemplate(templateId) {
    window.location.href = `/admin/templates/${templateId}/edit`;
}

// Preview template function
function previewTemplate(templateId) {
    window.open(`/admin/templates/${templateId}/preview`, '_blank');
}

// Duplicate template function
function duplicateTemplate(templateId) {
    if (confirm('Do you want to create a copy of this template?')) {
        fetch(`/admin/templates/${templateId}/duplicate`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Template duplicated successfully!');
                location.reload();
            } else {
                alert('Error: ' + (data.message || 'Failed to duplicate template'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while duplicating the template.');
        });
    }
}

// Confirm delete template function
function confirmDeleteTemplate(templateId) {
    if (confirm('Are you sure you want to delete this template? This action cannot be undone.')) {
        deleteTemplate(templateId);
    }
}

// Delete template function
function deleteTemplate(templateId) {
    fetch(`/admin/headers-footers/${templateId}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Template deleted successfully!');
            location.reload();
        } else {
            alert('Error: ' + (data.message || 'Failed to delete template'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while deleting the template.');
    });
}

// View template details function
function viewTemplateDetails(templateId) {
    // You can implement a modal or redirect to details page
    window.open(`/admin/templates/${templateId}`, '_blank');
}

// Initialize dropdown positioning (similar to shared admin scripts)
document.addEventListener('DOMContentLoaded', function() {
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

    // Enhanced positioning classes toggle
    document.addEventListener('show.bs.dropdown', function(e) {
        const menu = e.target.querySelector('.dropdown-menu');
        if (!menu) return;
        
        menu.classList.remove('dropdown-menu-up', 'dropdown-menu-end');
        
        setTimeout(() => {
            const btnRect = e.target.querySelector('[data-bs-toggle="dropdown"]').getBoundingClientRect();
            const menuRect = menu.getBoundingClientRect();
            const cardRect = e.target.closest('.template-card')?.getBoundingClientRect();
            
            if (btnRect.bottom + menuRect.height > window.innerHeight - 20) {
                menu.classList.add('dropdown-menu-up');
            }
            
            if (cardRect && (btnRect.left + menuRect.width > cardRect.right)) {
                menu.classList.add('dropdown-menu-end');
            }
        }, 10);
    });

    document.addEventListener('hide.bs.dropdown', e => {
        const menu = e.target.querySelector('.dropdown-menu');
        if (menu) {
            menu.classList.remove('dropdown-menu-up');
        }
    });
});

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
            $('#headerNavModal').modal('hide');
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
            $('#footerNavModal').modal('hide');
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
        status: status === 'true'
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

function showAddLinkModal(type) {
    // Implementation for adding new link
    console.log(`Adding new ${type} link`);
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

<!-- Header Navigation Modal -->
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
                    @foreach($navigationConfig['header_links'] ?? [] as $index => $link)
                        <div class="nav-link-item mb-3" data-index="{{ $index }}">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="flex-grow-1">
                                            <strong>{{ $link['title'] ?? $link['name'] ?? 'Untitled' }}</strong>
                                            <div class="text-muted small">{{ $link['url'] ?? '#' }}</div>
                                            @if($link['external'] ?? false)
                                                <span class="badge bg-info badge-sm">External</span>
                                            @endif
                                            @if(!($link['active'] ?? true))
                                                <span class="badge bg-secondary badge-sm">Inactive</span>
                                            @endif
                                        </div>
                                        <div class="d-flex gap-2">
                                            <button class="btn btn-sm btn-outline-secondary" onclick="toggleLinkStatus('header', {{ $index }}, {{ ($link['active'] ?? true) ? 'false' : 'true' }})">
                                                @if($link['active'] ?? true)
                                                    <i class="align-middle" data-feather="eye"></i>
                                                @else
                                                    <i class="align-middle" data-feather="eye-off"></i>
                                                @endif
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger" onclick="removeLink('header', {{ $index }})">
                                                <i class="align-middle" data-feather="trash-2"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                @if(count($navigationConfig['header_links'] ?? []) < 5)
                    <div class="card border-dashed">
                        <div class="card-body">
                            <div id="add-header-link-form" style="display: none;">
                                <h6 class="mb-3"><i class="align-middle me-2" data-feather="plus"></i>Add New Header Link</h6>
                                <form>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="header-link-title" class="form-label">Link Title</label>
                                                <input type="text" class="form-control" id="header-link-title" placeholder="About Us" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="header-link-url" class="form-label">URL</label>
                                                <input type="text" class="form-control" id="header-link-url" placeholder="/about" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="header-link-external">
                                                <label class="form-check-label" for="header-link-external">
                                                    External Link (opens in new tab)
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="header-link-active" checked>
                                                <label class="form-check-label" for="header-link-active">
                                                    Active
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex gap-2 mt-3">
                                        <button type="button" class="btn btn-success btn-sm" onclick="addHeaderLink()">
                                            <i class="align-middle me-1" data-feather="check"></i>Add Link
                                        </button>
                                        <button type="button" class="btn btn-secondary btn-sm" onclick="cancelAddHeaderLink()">
                                            <i class="align-middle me-1" data-feather="x"></i>Cancel
                                        </button>
                                    </div>
                                </form>
                            </div>
                            <button id="show-add-header-form" class="btn btn-primary" onclick="showAddHeaderForm()">
                                <i class="align-middle me-1" data-feather="plus"></i>Add Header Link
                            </button>
                        </div>
                    </div>
                @endif
                
                <div class="mt-4">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title"><i class="align-middle me-2" data-feather="settings"></i>Authentication Display Settings</h6>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="show_auth_header" {{ ($navigationConfig['show_auth_in_header'] ?? true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="show_auth_header">
                                    Show Login/Register/Profile in Header
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

<!-- Footer Navigation Modal -->
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
                    @foreach($navigationConfig['footer_links'] ?? [] as $index => $link)
                        <div class="nav-link-item mb-3" data-index="{{ $index }}">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="flex-grow-1">
                                            <strong>{{ $link['title'] ?? $link['name'] ?? 'Untitled' }}</strong>
                                            <div class="text-muted small">{{ $link['url'] ?? '#' }}</div>
                                            @if($link['external'] ?? false)
                                                <span class="badge bg-info badge-sm">External</span>
                                            @endif
                                            @if(!($link['active'] ?? true))
                                                <span class="badge bg-secondary badge-sm">Inactive</span>
                                            @endif
                                        </div>
                                        <div class="d-flex gap-2">
                                            <button class="btn btn-sm btn-outline-secondary" onclick="toggleLinkStatus('footer', {{ $index }}, {{ ($link['active'] ?? true) ? 'false' : 'true' }})">
                                                @if($link['active'] ?? true)
                                                    <i class="align-middle" data-feather="eye"></i>
                                                @else
                                                    <i class="align-middle" data-feather="eye-off"></i>
                                                @endif
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger" onclick="removeLink('footer', {{ $index }})">
                                                <i class="align-middle" data-feather="trash-2"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                @if(count($navigationConfig['footer_links'] ?? []) < 10)
                    <div class="card border-dashed">
                        <div class="card-body">
                            <div id="add-footer-link-form" style="display: none;">
                                <h6 class="mb-3"><i class="align-middle me-2" data-feather="plus"></i>Add New Footer Link</h6>
                                <form>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="footer-link-title" class="form-label">Link Title</label>
                                                <input type="text" class="form-control" id="footer-link-title" placeholder="Privacy Policy" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="footer-link-url" class="form-label">URL</label>
                                                <input type="text" class="form-control" id="footer-link-url" placeholder="/privacy" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="footer-link-external">
                                                <label class="form-check-label" for="footer-link-external">
                                                    External Link (opens in new tab)
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="footer-link-active" checked>
                                                <label class="form-check-label" for="footer-link-active">
                                                    Active
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex gap-2 mt-3">
                                        <button type="button" class="btn btn-success btn-sm" onclick="addFooterLink()">
                                            <i class="align-middle me-1" data-feather="check"></i>Add Link
                                        </button>
                                        <button type="button" class="btn btn-secondary btn-sm" onclick="cancelAddFooterLink()">
                                            <i class="align-middle me-1" data-feather="x"></i>Cancel
                                        </button>
                                    </div>
                                </form>
                            </div>
                            <button id="show-add-footer-form" class="btn btn-primary" onclick="showAddFooterForm()">
                                <i class="align-middle me-1" data-feather="plus"></i>Add Footer Link
                            </button>
                        </div>
                    </div>
                @endif
                
                <div class="mt-4">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title"><i class="align-middle me-2" data-feather="settings"></i>Authentication Display Settings</h6>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="show_auth_footer" {{ ($navigationConfig['show_auth_in_footer'] ?? true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="show_auth_footer">
                                    Show Login/Register/Profile in Footer
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
                                'github' => 'GitHub',
                                'discord' => 'Discord',
                                'tiktok' => 'TikTok',
                                'pinterest' => 'Pinterest'
                            ];
                        @endphp
                        
                        @foreach($socialPlatforms as $platform => $label)
                            <div class="card mb-3">
                                <div class="card-body">
                                    <label for="{{ $platform }}" class="form-label">
                                        <i class="align-middle" data-feather="{{ $platform === 'discord' ? 'message-circle' : ($platform === 'tiktok' ? 'video' : ($platform === 'pinterest' ? 'image' : $platform)) }}" style="width: 16px; height: 16px;"></i>
                                        {{ $label }}
                                    </label>
                                    <input type="url" class="form-control" id="{{ $platform }}" name="social_media[{{ $platform }}]" 
                                           value="{{ $socialMediaConfig[$platform] ?? '' }}" 
                                           placeholder="https://{{ $platform }}.com/username">
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

<script src="{{ asset('js/admin/headers-footers.js') }}"></script>
@endpush
