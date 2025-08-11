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
                                        <a class="dropdown-item" href="{{ route('admin.headers-footers.index') }}#headers">
                                            <i class="align-middle me-2" data-feather="edit-2"></i>Edit Data
                                        </a>
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
                                        <a class="dropdown-item" href="{{ route('admin.headers-footers.index') }}#footers">
                                            <i class="align-middle me-2" data-feather="edit-2"></i>Edit Data
                                        </a>
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
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
    if (typeof feather !== 'undefined') { feather.replace(); }
});
</script>
@endpush
