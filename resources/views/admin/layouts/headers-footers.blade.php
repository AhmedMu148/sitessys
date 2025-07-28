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
            <button class="nav-link" id="footers-tab" data-bs-toggle="tab" data-bs-target="#footers" type="button" role="tab">
                <i class="align-middle me-2" data-feather="layers"></i>Footers ({{ count($availableTemplates['global']) > 0 ? count(array_filter($availableTemplates['global'], fn($t) => $t['layout_type'] === 'footer')) : 0 }})
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="navigation-tab" data-bs-toggle="tab" data-bs-target="#navigation" type="button" role="tab">
                <i class="align-middle me-2" data-feather="menu"></i>Navigation & Links
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="social-tab" data-bs-toggle="tab" data-bs-target="#social" type="button" role="tab">
                <i class="align-middle me-2" data-feather="share-2"></i>Social Media
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
                            <div class="card template-card global-template h-100 {{ $site->active_header_id == $template['id'] ? 'current-template' : '' }}">
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
                                            <i class="align-middle me-1" data-feather="copy"></i>Copy & Customize
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
                            <div class="card template-card user-template h-100 {{ $site->active_header_id == $template['id'] ? 'current-template' : '' }}">
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
                            <div class="card template-card global-template h-100 {{ $site->active_footer_id == $template['id'] ? 'current-template' : '' }}">
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
                                            <i class="align-middle me-1" data-feather="copy"></i>Copy & Customize
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
                            <div class="card template-card user-template h-100 {{ $site->active_footer_id == $template['id'] ? 'current-template' : '' }}">
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

        {{-- Navigation Tab --}}
        <div class="tab-pane fade" id="navigation" role="tabpanel">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="mb-3"><i class="align-middle" data-feather="menu text-primary me-2"></i>Header Navigation (Max 5 links)</h5>
                    <div id="header-links">
                        @foreach($navigationConfig['header_links'] ?? [] as $index => $link)
                            <div class="nav-link-item" data-index="{{ $index }}">
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
                        @endforeach
                    </div>
                    
                    @if(count($navigationConfig['header_links'] ?? []) < 5)
                        <button class="btn btn-outline-primary btn-sm" onclick="showAddLinkModal('header')">
                            <i class="align-middle" data-feather="plus me-1"></i>Add Header Link
                        </button>
                    @endif
                </div>
                
                <div class="col-md-6">
                    <h5 class="mb-3"><i class="align-middle" data-feather="layers text-primary me-2"></i>Footer Navigation (Max 10 links)</h5>
                    <div id="footer-links">
                        @foreach($navigationConfig['footer_links'] ?? [] as $index => $link)
                            <div class="nav-link-item" data-index="{{ $index }}">
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
                        @endforeach
                    </div>
                    
                    @if(count($navigationConfig['footer_links'] ?? []) < 10)
                        <button class="btn btn-outline-primary btn-sm" onclick="showAddLinkModal('footer')">
                            <i class="align-middle" data-feather="plus me-1"></i>Add Footer Link
                        </button>
                    @endif
                </div>
            </div>
            
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title"><i class="align-middle" data-feather="settings me-2"></i>Authentication Display Settings</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="show_auth_header" {{ ($navigationConfig['show_auth_in_header'] ?? true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="show_auth_header">
                                            Show Login/Register/Profile in Header
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
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
                </div>
            </div>
        </div>

        {{-- Social Media Tab --}}
        <div class="tab-pane fade" id="social" role="tabpanel">
            <div class="row">
                <div class="col-12">
                    <h5 class="mb-3"><i class="align-middle" data-feather="share-2 text-primary me-2"></i>Social Media Links</h5>
                    <p class="text-muted small mb-4">Configure your social media links. These will be displayed in your templates where the social media placeholder is used.</p>
                </div>
            </div>
            
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
                        <div class="card">
                            <div class="card-body">
                                <label for="{{ $platform }}" class="form-label">
                                    <i class="align-middle" data-feather="{{ $platform === 'discord' ? 'message-circle' : ($platform === 'tiktok' ? 'video' : ($platform === 'pinterest' ? 'image' : $platform)) }} me-2"></i>{{ $label }}
                                </label>
                                <input type="url" class="form-control" id="{{ $platform }}" name="social_media[{{ $platform }}]" 
                                       value="{{ $socialMediaConfig[$platform] ?? '' }}" 
                                       placeholder="https://{{ $platform }}.com/username">
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <div class="text-center mt-4">
                    <button type="button" class="btn btn-primary" onclick="updateSocialMedia()">
                        <i class="align-middle" data-feather="save me-2"></i>Save Social Media Links
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Add Link Modal --}}
<div class="modal fade" id="addLinkModal" tabindex="-1">
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
</script>
<script src="{{ asset('js/admin/headers-footers.js') }}"></script>
@endpush
