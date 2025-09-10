@extends('admin.layouts.master')

@section('title', 'Headers & Footers Management')

@section('css')
<link href="{{ asset('css/admin/headers-footers.css') }}" rel="stylesheet">

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
                                        </ul>
                                    </div>
                                </div>
                                
                                <div class="card-top-section {{ !empty($template['preview_image']) ? 'with-media' : '' }}">
                                    @if(!empty($template['preview_image']))
                                        <div class="card-top-media">
                                            <img src="{{ $template['preview_image'] }}" alt="Preview" class="img-fluid">
                                        </div>
                                    @else
                                        <div class="card-top-fallback text-center">
                                            <i class="align-middle" data-feather="layout" style="font-size: 2rem;"></i>
                                            <div>{{ $template['name'] }}</div>
                                        </div>
                                    @endif
                                    <i class="card-icon" data-feather="layout"></i>
                                </div>
                                <div class="card-bottom-section">
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
                                                    Edit data
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                
                                <div class="card-top-section {{ !empty($template['preview_image']) ? 'with-media' : '' }}">
                                    @if(!empty($template['preview_image']))
                                        <div class="card-top-media">
                                            <img src="{{ $template['preview_image'] }}" alt="Preview" class="img-fluid">
                                        </div>
                                    @else
                                        <div class="card-top-fallback text-center">
                                            <i class="align-middle" data-feather="grid" style="font-size: 2rem;"></i>
                                            <div>{{ $template['name'] }}</div>
                                        </div>
                                    @endif
                                    <i class="card-icon" data-feather="grid"></i>
                                </div>
                                <div class="card-bottom-section">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h6 class="card-title mb-0">{{ $template['name'] }}</h6>
                                        <span class="badge badge-global">Global</span>
                                    </div>
                                    <p class="card-text text-muted small">{{ $template['description'] ?? 'Professional section template' }}</p>
                                    <div class="d-flex gap-2">

                                    
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
                                
                                <div class="card-top-section {{ !empty($template['preview_image']) ? 'with-media' : '' }}">
                                    @if(!empty($template['preview_image']))
                                        <div class="card-top-media">
                                            <img src="{{ $template['preview_image'] }}" alt="Preview" class="img-fluid">
                                        </div>
                                    @else
                                        <div class="card-top-fallback text-center">
                                            <i class="align-middle" data-feather="grid" style="font-size: 2rem;"></i>
                                            <div>{{ $template['name'] }}</div>
                                        </div>
                                    @endif
                                    <i class="card-icon" data-feather="grid"></i>
                                </div>
                                <div class="card-bottom-section">
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

@endsection

@push('scripts')
<script src="{{ asset('js/admin/headers-footers.js') }}"></script>
@endpush
