@extends('admin.layouts.master')

@section('title', 'Page Management | إدارة الصفحات')

@section('css')
<style>
/* Page Management Styles */
.page-header {
    background: linear-gradient(135deg, #222e3c 0%, #2b3947 100%);
    border: 1px solid rgba(255, 255, 255, 0.2);
    padding: 1.5rem 0;
    margin-bottom: 1.5rem;
    border-radius: 0.75rem;
    animation: fadeInDown 0.6s ease-out;
    box-shadow: 0 4px 20px rgba(34, 46, 60, 0.15);
    position: relative;
    overflow: hidden;
}

.page-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Ccircle cx='30' cy='30' r='2'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E") repeat;
}

.page-header h1 {
    font-size: 1.75rem;
    font-weight: 600;
    margin-bottom: 0.25rem;
    color: #ffffff;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    position: relative;
    z-index: 1;
}

.page-header p {
    font-size: 0.95rem;
    color: rgba(255, 255, 255, 0.9);
    margin-bottom: 0;
    position: relative;
    z-index: 1;
}

.page-card {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border: 1px solid #e3f2fd;
    border-radius: 0.75rem;
    box-shadow: 0 2px 12px rgba(34, 46, 60, 0.08);
    height: 100%;
    position: relative;
    overflow: hidden;
    background: linear-gradient(145deg, #ffffff 0%, #f8faff 100%);
    transform: translateY(0);
    animation: fadeInUp 0.6s ease-out forwards;
    animation-delay: calc(var(--animation-order) * 0.1s);
    opacity: 0;
}

.page-card:hover {
    transform: translateY(-8px) scale(1.02);
    box-shadow: 0 12px 32px rgba(34, 46, 60, 0.15);
    border-color: #bbdefb;
    z-index: 10;
    background: linear-gradient(145deg, #ffffff 0%, #f3f8ff 100%);
}

.page-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: #222e3c;
    margin-bottom: 0.75rem;
    line-height: 1.4;
    min-height: 2.25rem;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.theme-badge {
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
    border-radius: 0.25rem;
    font-weight: 500;
    margin-bottom: 0.75rem;
    display: inline-block;
    text-transform: capitalize;
}

.theme-business { 
    background: #222e3c; 
    color: #ffffff; 
}
.theme-portfolio { 
    background: #34495e; 
    color: #ffffff; 
}
.theme-ecommerce { 
    background: #3b4556; 
    color: #ffffff; 
}
.theme-seo-services { 
    background: #424f63; 
    color: #ffffff; 
}
.theme-default { 
    background: #495a6b; 
    color: #ffffff; 
}

.status-indicator {
    position: absolute;
    top: 12px;
    right: 12px;
    width: 10px;
    height: 10px;
    border-radius: 50%;
    border: 2px solid white;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12);
    z-index: 5;
}

.status-active { 
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    animation: pulse 2s infinite;
    box-shadow: 0 0 12px rgba(16, 185, 129, 0.4);
}

.status-inactive { 
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    box-shadow: 0 0 8px rgba(245, 158, 11, 0.3);
}

.nav-indicator, .footer-indicator {
    font-size: 0.7rem;
    padding: 0.2rem 0.4rem;
    border-radius: 0.25rem;
    margin: 0.125rem;
    display: inline-block;
    font-weight: 500;
}

.nav-indicator {
    background: #222e3c;
    color: #ffffff;
}

.footer-indicator {
    background: #2b3947;
    color: #ffffff;
}

.card-actions {
    position: absolute;
    top: 12px;
    right: 12px;
    z-index: 15;
    opacity: 1;
    transition: all 0.3s ease;
    transform: translateY(0);
}


}

.dropdown-menu.show {
    display: block !important;
}

/* RTL dropdown positioning */
[dir="rtl"] .dropdown-menu {
    right: auto;
    left: 0;
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

/* Dropdown Button Icon Styling */
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

/* Ensure SVG icon visibility */
.actions-btn svg {
    display: inline-block !important;
    vertical-align: middle;
    pointer-events: none;
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

.search-filter-section {
    background: linear-gradient(135deg, #f8faff 0%, #e3f2fd 100%);
    border: 1px solid rgba(34, 46, 60, 0.1);
    border-radius: 0.75rem;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 4px 16px rgba(34, 46, 60, 0.08);
    animation: slideInUp 0.8s ease-out;
    position: relative;
    overflow: hidden;
}

.search-filter-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url("data:image/svg+xml,%3Csvg width='40' height='40' viewBox='0 0 40 40' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23222e3c' fill-opacity='0.03'%3E%3Cpath d='M20 20c0-11.046-8.954-20-20-20s-20 8.954-20 20 8.954 20 20 20 20-8.954 20-20z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E") repeat;
}

.btn-create {
    background: #6c757d;
    border: 1px solid #6c757d;
    color: white;
    font-weight: 500;
    padding: 0.75rem 1.5rem;
    border-radius: 0.375rem;
    font-size: 0.95rem;
    transition: all 0.2s ease;
}

.btn-create:hover {
    background: #5a6268;
    border-color: #5a6268;
    transform: translateY(-1px);
    color: white;
}

.page-info {
    font-size: 0.85rem;
    color: #475569;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.page-url {
    background: #f1f5f9;
    padding: 0.375rem 0.75rem;
    border-radius: 0.5rem;
    font-family: monospace;
    font-size: 0.75rem;
    color: #1e293b;
    border: 1px solid rgba(34, 46, 60, 0.1);
    word-break: break-all;
}

.sections-count {
    background: #ede9fe;
    color: #222e3c;
    padding: 0.25rem 0.75rem;
    border-radius: 0.5rem;
    font-size: 0.75rem;
    font-weight: 600;
}

.empty-state {
    text-align: center;
    padding: 3rem 2rem;
    color: #475569;
    background: #f8faff;
    border-radius: 0.75rem;
    border: 2px dashed rgba(34, 46, 60, 0.3);
}

.empty-state i {
    font-size: 3rem;
    margin-bottom: 1rem;
    opacity: 0.6;
    color: #222e3c;
}

.home-badge {
    background: #f59e0b;
    color: #ffffff;
    font-size: 0.7rem;
    padding: 0.25rem 0.5rem;
    border-radius: 0.375rem;
    margin-left: 0.5rem;
    font-weight: 600;
}

/* Search and Filter Styles */
.search-input {
    border-radius: 0.75rem;
    border: 1px solid rgba(34, 46, 60, 0.2);
    transition: all 0.3s ease;
    background: rgba(255, 255, 255, 0.9);
}

.search-input:focus {
    border-color: #222e3c;
    box-shadow: 0 0 0 0.2rem rgba(34, 46, 60, 0.25);
    transform: translateY(-2px);
    background: white;
}

.filter-select {
    border-radius: 0.75rem;
    border: 1px solid rgba(34, 46, 60, 0.2);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    transform: translateY(0);
    background: rgba(255, 255, 255, 0.9);
}

.filter-select:focus {
    border-color: #222e3c;
    box-shadow: 0 0 0 0.2rem rgba(34, 46, 60, 0.25);
    transform: translateY(-2px);
    background: white;
}

/* RTL Support */
[dir="rtl"] .page-card {
    text-align: right;
}

[dir="rtl"] .status-indicator {
    right: auto;
    left: 12px;
}

[dir="rtl"] .card-actions {
    right: auto;
    left: 12px;
}

[dir="rtl"] .nav-indicator, 
[dir="rtl"] .footer-indicator {
    margin: 0.125rem 0.125rem 0.125rem 0;
}

[dir="rtl"] .home-badge {
    margin-left: 0;
    margin-right: 0.5rem;
}

/* Responsive Design */
@media (max-width: 1200px) {
    .page-card {
        margin-bottom: 1rem;
    }
}

@media (max-width: 768px) {
    .page-header {
        padding: 1rem 0;
        text-align: center;
    }
    
    .page-header h1 {
        font-size: 1.5rem;
    }
    
    .search-filter-section {
        padding: 1rem;
    }
    
    .search-filter-section .row > div {
        margin-bottom: 0.75rem;
    }
    
    .btn-create {
        width: 100%;
        margin-top: 0.75rem;
    }
    
    .card-actions {
        bottom: 8px;
        right: 8px;
    }
    
    .status-indicator {
        top: 8px;
        right: 8px;
        width: 8px;
        height: 8px;
    }
    
    [dir="rtl"] .status-indicator {
        right: auto;
        left: 8px;
    }
    
    [dir="rtl"] .card-actions {
        right: auto;
        left: 8px;
    }
}

/* Loading States */
/* Loading States */
.loading {
    opacity: 0.7;
    pointer-events: none;
    position: relative;
}

.loading::after {
    content: "";
    position: absolute;
    top: 50%;
    left: 50%;
    width: 20px;
    height: 20px;
    margin: -10px 0 0 -10px;
    border: 2px solid #f3f3f3;
    border-top: 2px solid #222e3c;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    z-index: 1000;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
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
    box-shadow: 0 0 0 2px rgba(220, 38, 38, 0.3);
}

.dropdown-divider {
    margin: 0.5rem 0.5rem;
    border-color: rgba(34, 46, 60, 0.1);
    opacity: 0.7;
}

/* Animation Keyframes */
@keyframes fadeInDown {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px) scale(0.95);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes pulse {
    0%, 100% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.05);
    }
}

@keyframes bounceIn {
    0% {
        opacity: 0;
        transform: scale(0.3);
    }
    50% {
        opacity: 1;
        transform: scale(1.05);
    }
    70% {
        transform: scale(0.9);
    }
    100% {
        opacity: 1;
        transform: scale(1);
    }
}

/* Status Indicator Animations */
.status-indicator {
    position: absolute;
    top: 12px;
    right: 12px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 2px solid white;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    z-index: 5;
    transition: all 0.3s ease;
}

/* Badge Animations */
.theme-badge {
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
    border-radius: 0.375rem;
    font-weight: 500;
    text-transform: capitalize;
}





@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

/* Button Hover Enhancements */
.btn-create {
    background: #222e3c;
    border: 1px solid #222e3c;
    color: white;
    font-weight: 500;
    padding: 0.75rem 1.5rem;
    border-radius: 0.5rem;
    transition: all 0.2s ease;
}

.btn-create:hover {
    background: #2b3947;
    border-color: #2b3947;
    transform: translateY(-1px);
    color: white;
}

/* Input Group Enhancements */
.input-group-text {
    border-radius: 0.5rem 0 0 0.5rem;
    background: #f8faff;
    border-color: rgba(34, 46, 60, 0.2);
    color: #222e3c;
}

.input-group:focus-within .input-group-text {
    background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
    border-color: #222e3c;
    color: #1a2530;
}

/* Card Footer Enhancements */
.card-footer {
    background: linear-gradient(135deg, #f8faff 0%, #e3f2fd 100%);
    border-top: 1px solid rgba(34, 46, 60, 0.1);
    padding: 0.75rem 1rem;
    transition: all 0.3s ease;
}

.page-card:hover .card-footer {
    background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
}

/* Responsive Animation Adjustments */
@media (max-width: 768px) {
    .page-card:hover {
        transform: translateY(-4px) scale(1.01);
    }
    
    .actions-btn {
        width: 32px;
        height: 32px;
    }
    
    .card-actions {
        opacity: 1 !important;
        transform: translateY(0) !important;
        top: 8px;
        right: 8px;
    }
    
    .btn-create:hover {
        transform: translateY(-2px) scale(1.01);
    }
}
</style>
@endsection

@section('content')
<!-- Page Header -->
<div class="page-header text-center">
    <div class="container-fluid">
        <h1 class="mb-2">
            <i class="align-middle me-2" data-feather="file-text" style="width: 24px; height: 24px;"></i>
            <span class="en">Page Management</span>
            <span class="ar" style="display: none;">إدارة الصفحات</span>
        </h1>
        <p class="mb-0">
            <span class="en">Manage your website pages with multi-language support</span>
            <span class="ar" style="display: none;">إدارة صفحات موقعك مع الدعم متعدد اللغات</span>
        </p>
    </div>
</div>

<!-- Search and Filter Section -->
<div class="search-filter-section">
    <div class="row align-items-end">
        <div class="col-lg-3 col-md-6 col-12">
            <label class="form-label fw-semibold">
                <i class="align-middle me-2" data-feather="search"></i>
                <span class="en">Search Pages</span>
                <span class="ar" style="display: none;">البحث في الصفحات</span>
            </label>
            <div class="input-group">
                <span class="input-group-text bg-white">
                    <i data-feather="search"></i>
                </span>
                <input type="text" class="form-control search-input" id="searchPages" 
                       placeholder="Enter page name..." autocomplete="off">
            </div>
        </div>
        <div class="col-lg-2 col-md-6 col-12">
            <label class="form-label fw-semibold">
                <i class="align-middle me-2" data-feather="palette"></i>
                <span class="en">Theme</span>
                <span class="ar" style="display: none;">الثيم</span>
            </label>
            <select class="form-select filter-select" id="filterTheme">
                <option value="">All Themes</option>
                <option value="business">Business</option>
                <option value="portfolio">Portfolio</option>
                <option value="ecommerce">Ecommerce</option>
                <option value="seo-services">SEO Services</option>
            </select>
        </div>
        <div class="col-lg-2 col-md-6 col-12">
            <label class="form-label fw-semibold">
                <i class="align-middle me-2" data-feather="activity"></i>
                <span class="en">Status</span>
                <span class="ar" style="display: none;">الحالة</span>
            </label>
            <select class="form-select filter-select" id="filterStatus">
                <option value="">All Status</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>
        <div class="col-lg-2 col-md-6 col-12">
            <label class="form-label fw-semibold">
                <i class="align-middle me-2" data-feather="navigation"></i>
                <span class="en">Navigation</span>
                <span class="ar" style="display: none;">التنقل</span>
            </label>
            <select class="form-select filter-select" id="filterNav">
                <option value="">All Pages</option>
                <option value="in-nav">In Navigation</option>
                <option value="not-in-nav">Not in Navigation</option>
            </select>
        </div>
        <div class="col-lg-3 col-md-12 col-12">
            <div class="d-flex justify-content-end">
                <a href="{{ route('admin.pages.create') }}" class="btn btn-create">
                    <i class="align-middle me-2" data-feather="plus" style="width: 16px; height: 16px;"></i>
                    <span class="en">Create New Page</span>
                    <span class="ar" style="display: none;">إنشاء صفحة جديدة</span>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Success/Error Messages -->
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="align-middle me-2" data-feather="check-circle"></i>
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="align-middle me-2" data-feather="alert-circle"></i>
    {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<!-- Page Count Info -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <div class="text-muted">
        <i class="align-middle me-2" data-feather="info"></i>
        <span class="en">Total: <strong id="totalPages">{{ $pages->total() }}</strong> pages</span>
        <span class="ar" style="display: none;">المجموع: <strong id="totalPages">{{ $pages->total() }}</strong> صفحة</span>
        
        <span class="mx-2">|</span>
        <span class="en">Showing: <strong id="visiblePages">{{ $pages->count() }}</strong></span>
        <span class="ar" style="display: none;">المعروض: <strong id="visiblePages">{{ $pages->count() }}</strong></span>
    </div>
    <div class="text-muted">
        <small>
            <span class="en">Last updated: {{ now()->format('M d, Y H:i') }}</span>
            <span class="ar" style="display: none;">آخر تحديث: {{ now()->format('M d, Y H:i') }}</span>
        </small>
    </div>
</div>

<!-- Pages Grid -->
<div class="row" id="pagesGrid">
    @forelse($pages as $index => $page)
    <div class="col-xl-3 col-lg-4 col-md-6 col-12 mb-4 page-item" 
         data-theme="{{ $page->themePage->name ?? 'default' }}" 
         data-status="{{ $page->status ? 'active' : 'inactive' }}"
         data-name="{{ strtolower($page->name) }}"
         data-nav="{{ $page->show_in_nav ? 'in-nav' : 'not-in-nav' }}"
         data-id="{{ $page->id }}"
         style="--animation-order: {{ $index }}">
        
        <div class="card page-card h-100"
             onmouseenter="showCardActions(this)" 
             onmouseleave="hideCardActions(this)">
            <!-- Status Indicator -->
            <div class="status-indicator {{ $page->status ? 'status-active' : 'status-inactive' }}" 
                 title="{{ $page->status ? 'Active Page' : 'Inactive Page' }}"></div>
            
            <!-- Card Actions Dropdown -->
            <div class="card-actions">
                <div class="dropdown">
                    <button class="btn actions-btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="إجراءات الصفحة / Page Actions">
                        <i data-feather="more-vertical" style="width: 16px; height: 16px;"></i>
                        <span class="visually-hidden">Actions</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="{{ route('admin.pages.edit', $page->id) }}">
                                <i class="align-middle me-2" data-feather="edit"></i>
                                <span class="en">Edit Page</span>
                                <span class="ar" style="display: none;">تعديل الصفحة</span>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ url($page->link) }}" target="_blank">
                                <i class="align-middle me-2" data-feather="eye"></i>
                                <span class="en">View Page</span>
                                <span class="ar" style="display: none;">عرض الصفحة</span>
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item toggle-status" href="#" data-id="{{ $page->id }}" 
                               data-status="{{ $page->status }}">
                                <i class="align-middle me-2" data-feather="{{ $page->status ? 'pause' : 'play' }}"></i>
                                <span class="en">{{ $page->status ? 'Deactivate' : 'Activate' }}</span>
                                <span class="ar" style="display: none;">{{ $page->status ? 'إلغاء التفعيل' : 'تفعيل' }}</span>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item toggle-nav" href="#" data-id="{{ $page->id }}" 
                               data-in-nav="{{ $page->show_in_nav }}">
                                <i class="align-middle me-2" data-feather="navigation"></i>
                                <span class="en">{{ $page->show_in_nav ? 'Remove from Nav' : 'Add to Nav' }}</span>
                                <span class="ar" style="display: none;">{{ $page->show_in_nav ? 'إزالة من التنقل' : 'إضافة للتنقل' }}</span>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item toggle-footer" href="#" data-id="{{ $page->id }}">
                                <i class="align-middle me-2" data-feather="anchor"></i>
                                <span class="en">Toggle Footer</span>
                                <span class="ar" style="display: none;">تبديل الفوتر</span>
                            </a>
                        </li>
                        @if($page->slug !== 'home')
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item text-danger delete-page" href="#" data-id="{{ $page->id }}">
                                <i class="align-middle me-2" data-feather="trash-2"></i>
                                <span class="en">Delete Page</span>
                                <span class="ar" style="display: none;">حذف الصفحة</span>
                            </a>
                        </li>
                        @endif
                    </ul>
                </div>
            </div>

            <div class="card-body d-flex flex-column">
                <!-- Page Title -->
                <div class="page-title">
                    {{ $page->name }}
                    @if($page->slug === 'home')
                        <span class="home-badge">
                            <i class="align-middle me-1" data-feather="home" style="width: 12px; height: 12px;"></i>
                            <span class="en">Home</span>
                            <span class="ar" style="display: none;">الرئيسية</span>
                        </span>
                    @endif
                </div>

                <!-- Multi-language Titles -->
                @if($page->data && isset($page->data['title']))
                <div class="mb-3">
                    <small class="text-muted d-block">
                        <strong>EN:</strong> {{ $page->data['title']['en'] ?? $page->name }}
                    </small>
                    <small class="text-muted d-block">
                        <strong>AR:</strong> {{ $page->data['title']['ar'] ?? $page->name }}
                    </small>
                </div>
                @endif

                <!-- Theme Badge -->
                <div class="mb-3">
                    <span class="theme-badge theme-{{ $page->themePage->name ?? 'business' }}">
                        <i class="align-middle me-1" data-feather="palette" style="width: 14px; height: 14px;"></i>
                        {{ ucfirst(str_replace('-', ' ', $page->themePage->name ?? 'Business')) }}
                    </span>
                </div>

                <!-- Page URL -->
                <div class="page-info mb-2">
                    <i class="align-middle" data-feather="link" style="width: 16px; height: 16px;"></i>
                    <span class="page-url">{{ $page->link }}</span>
                </div>

                <!-- Sections Count -->
                <div class="page-info mb-3">
                    <i class="align-middle" data-feather="layers" style="width: 16px; height: 16px;"></i>
                    <span class="sections-count">
                        {{ $page->sections->count() }} 
                        <span class="en">sections</span>
                        <span class="ar" style="display: none;">أقسام</span>
                        @if($page->sections->where('status', true)->count() > 0)
                            | {{ $page->sections->where('status', true)->count() }} 
                            <span class="en">active</span>
                            <span class="ar" style="display: none;">نشط</span>
                        @endif
                    </span>
                </div>

                <!-- Navigation and Footer Indicators -->
                <div class="mt-auto">
                    @if($page->show_in_nav)
                        <span class="nav-indicator">
                            <i class="align-middle me-1" data-feather="navigation" style="width: 12px; height: 12px;"></i>
                            <span class="en">In Navigation</span>
                            <span class="ar" style="display: none;">في التنقل</span>
                        </span>
                    @endif
                    
                    {{-- Footer indicator placeholder --}}
                    <span class="footer-indicator d-none" id="footer-indicator-{{ $page->id }}">
                        <i class="align-middle me-1" data-feather="anchor" style="width: 12px; height: 12px;"></i>
                        <span class="en">In Footer</span>
                        <span class="ar" style="display: none;">في الفوتر</span>
                    </span>
                </div>
            </div>

            <!-- Card Footer -->
            <div class="card-footer bg-light border-0">
                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-muted">
                        <i class="align-middle me-1" data-feather="calendar" style="width: 14px; height: 14px;"></i>
                        {{ $page->created_at->format('M d, Y') }}
                    </small>
                    <small class="text-muted">
                        <i class="align-middle me-1" data-feather="globe" style="width: 14px; height: 14px;"></i>
                        {{ $page->site->site_name ?? 'Unknown Site' }}
                    </small>
                </div>
            </div>
        </div>
    </div>
    @empty
    <!-- Empty State -->
    <div class="col-12">
        <div class="empty-state">
            <div class="mb-4">
                <i class="align-middle" data-feather="file-text"></i>
            </div>
            <h3 class="text-muted mb-3">
                <span class="en">No Pages Found</span>
                <span class="ar" style="display: none;">لا توجد صفحات</span>
            </h3>
            <p class="text-muted mb-4">
                <span class="en">Get started by creating your first page for this site. You can choose from various themes and customize it according to your needs.</span>
                <span class="ar" style="display: none;">ابدأ بإنشاء أول صفحة لهذا الموقع. يمكنك الاختيار من ثيمات مختلفة وتخصيصها حسب احتياجاتك.</span>
            </p>
            <a href="{{ route('admin.pages.create') }}" class="btn btn-create btn-lg">
                <i class="align-middle me-2" data-feather="plus" style="width: 20px; height: 20px;"></i>
                <span class="en">Create First Page</span>
                <span class="ar" style="display: none;">إنشاء أول صفحة</span>
            </a>
        </div>
    </div>
    @endforelse
</div>

<!-- Pagination -->
@if($pages->hasPages())
<div class="d-flex justify-content-center mt-4">
    <nav aria-label="Pages pagination">
        {{ $pages->links() }}
    </nav>
</div>
@endif

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteModalLabel">
                    <i class="align-middle me-2" data-feather="alert-triangle"></i>
                    <span class="en">Confirm Delete</span>
                    <span class="ar" style="display: none;">تأكيد الحذف</span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <i class="align-middle text-danger" data-feather="trash-2" style="width: 48px; height: 48px;"></i>
                </div>
                <p class="en text-center">Are you sure you want to delete this page? This action cannot be undone.</p>
                <p class="ar text-center" style="display: none;">هل أنت متأكد من حذف هذه الصفحة؟ لا يمكن التراجع عن هذا الإجراء.</p>
                <div class="alert alert-warning">
                    <i class="align-middle me-2" data-feather="info"></i>
                    <span class="en">All sections and content associated with this page will also be deleted.</span>
                    <span class="ar" style="display: none;">سيتم حذف جميع الأقسام والمحتوى المرتبط بهذه الصفحة أيضاً.</span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="align-middle me-2" data-feather="x"></i>
                    <span class="en">Cancel</span>
                    <span class="ar" style="display: none;">إلغاء</span>
                </button>
                <button type="button" class="btn btn-danger" id="confirmDelete">
                    <i class="align-middle me-2" data-feather="trash-2"></i>
                    <span class="en">Delete Page</span>
                    <span class="ar" style="display: none;">حذف الصفحة</span>
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
$(document).ready(function() {
    // Initialize Feather Icons
    feather.replace();
    
    // Language Management
    const currentLang = $('html').attr('lang') || 'en';
    const langDirection = currentLang === 'ar' ? 'rtl' : 'ltr';
    
    // Show appropriate language elements
    if (currentLang === 'ar') {
        $('.ar').show();
        $('.en').hide();
        $('body').attr('dir', 'rtl');
    } else {
        $('.en').show();
        $('.ar').hide();
        $('body').attr('dir', 'ltr');
    }
    
    // Initialize page animations
    initializeAnimations();
    
    // Search functionality with debounce
    let searchTimeout;
    $('#searchPages').on('input', function() {
        clearTimeout(searchTimeout);
        const searchTerm = $(this).val().toLowerCase();
        
        searchTimeout = setTimeout(function() {
            filterPages();
        }, 300);
    });
    
    // Enhanced filter functionality
    $('#filterTheme, #filterStatus, #filterNav').on('change', function() {
        filterPages();
        animateFilteredResults();
    });
    
    // Enhanced filter pages function
    function filterPages() {
        const searchTerm = $('#searchPages').val().toLowerCase();
        const filterTheme = $('#filterTheme').val();
        const filterStatus = $('#filterStatus').val();
        const filterNav = $('#filterNav').val();
        
        let visibleCount = 0;
        
        $('.page-item').each(function() {
            const $item = $(this);
            const name = $item.data('name');
            const theme = $item.data('theme');
            const status = $item.data('status');
            const nav = $item.data('nav');
            
            let showItem = true;
            
            if (searchTerm && !name.includes(searchTerm)) showItem = false;
            if (filterTheme && theme !== filterTheme) showItem = false;
            if (filterStatus && status !== filterStatus) showItem = false;
            if (filterNav && nav !== filterNav) showItem = false;
            
            if (showItem) {
                $item.show();
                visibleCount++;
            } else {
                $item.hide();
            }
        });
        
        $('#visiblePages').text(visibleCount);
        
        if (visibleCount === 0) {
            showNoResults();
        } else {
            hideNoResults();
        }
    }
    
    // Initialize page animations
    function initializeAnimations() {
        $('.page-card').css('opacity', '1');
    }
    

    
    // Show no results message
    function showNoResults() {
        if (!$('#noResultsMessage').length) {
            const noResultsHtml = `
                <div class="col-12" id="noResultsMessage">
                    <div class="empty-state">
                        <i class="align-middle" data-feather="search"></i>
                        <h3 class="text-muted mb-3">No Results Found</h3>
                        <p class="text-muted">Try adjusting your search criteria or filters.</p>
                    </div>
                </div>
            `;
            $('#pagesGrid').append(noResultsHtml);
            feather.replace();
        }
    }
    
    // Hide no results message
    function hideNoResults() {
        $('#noResultsMessage').remove();
    }
    
    // Enhanced Toggle Status functionality with animations
    $(document).on('click', '.toggle-status', function(e) {
        e.preventDefault();
        const $btn = $(this);
        const pageId = $btn.data('id');
        const currentStatus = $btn.data('status');
        const $card = $btn.closest('.page-item');
        const $statusIndicator = $card.find('.status-indicator');
        
        // Add loading animation
        $card.addClass('loading');
        $btn.prop('disabled', true);
        
        // Add button loading animation
        const originalContent = $btn.html();
        $btn.html('<i class="align-middle me-2 fa fa-spinner fa-spin"></i><span class="en">Processing...</span><span class="ar" style="display: none;">جارٍ المعالجة...</span>');
        
        if (currentLang === 'ar') {
            $btn.find('.ar').show();
            $btn.find('.en').hide();
        }
        
        $.ajax({
            url: `/admin/pages/${pageId}/toggle-status`,
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    // Update status with animation
                    const newStatus = !currentStatus;
                    $btn.data('status', newStatus);
                    $card.data('status', newStatus ? 'active' : 'inactive');
                    
                    // Animate status indicator change
                    $statusIndicator.addClass('animate-pulse');
                    setTimeout(() => {
                        if (newStatus) {
                            $statusIndicator.removeClass('status-inactive').addClass('status-active');
                            $btn.find('i').attr('data-feather', 'pause');
                            $btn.find('.en').text('Deactivate');
                            $btn.find('.ar').text('إلغاء التفعيل');
                        } else {
                            $statusIndicator.removeClass('status-active').addClass('status-inactive');
                            $btn.find('i').attr('data-feather', 'play');
                            $btn.find('.en').text('Activate');
                            $btn.find('.ar').text('تفعيل');
                        }
                        
                        $statusIndicator.removeClass('animate-pulse');
                        feather.replace();
                    }, 200);
                    
                    // Show success notification with animation
                    showAnimatedNotification('success', response.message);
                } else {
                    showAnimatedNotification('error', response.message || 'An error occurred');
                }
            },
            error: function(xhr) {
                const message = xhr.responseJSON?.message || 'An error occurred';
                showAnimatedNotification('error', message);
            },
            complete: function() {
                setTimeout(() => {
                    $card.removeClass('loading');
                    $btn.prop('disabled', false).html(originalContent);
                    feather.replace();
                    
                    if (currentLang === 'ar') {
                        $btn.find('.ar').show();
                        $btn.find('.en').hide();
                    }
                }, 300);
            }
        });
    });
    
    // Enhanced Toggle Navigation functionality
    $(document).on('click', '.toggle-nav', function(e) {
        e.preventDefault();
        const $btn = $(this);
        const pageId = $btn.data('id');
        const inNav = $btn.data('in-nav');
        const $card = $btn.closest('.page-item');
        
        // Add loading animation
        $card.addClass('loading');
        $btn.prop('disabled', true);
        
        $.ajax({
            url: `/admin/pages/${pageId}/toggle-nav`,
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    // Update navigation status with animation
                    const newNavStatus = !inNav;
                    $btn.data('in-nav', newNavStatus);
                    $card.data('nav', newNavStatus ? 'in-nav' : 'not-in-nav');
                    
                    // Animate UI update
                    const $navIndicator = $card.find('.nav-indicator');
                    if (newNavStatus) {
                        if (!$navIndicator.length) {
                            const indicatorHtml = `
                                <span class="nav-indicator" style="opacity: 0; transform: scale(0.5);">
                                    <i class="align-middle me-1" data-feather="navigation" style="width: 12px; height: 12px;"></i>
                                    <span class="en">In Navigation</span>
                                    <span class="ar" style="display: none;">في التنقل</span>
                                </span>
                            `;
                            $card.find('.mt-auto').prepend(indicatorHtml);
                            feather.replace();
                            
                            // Animate in
                            setTimeout(() => {
                                $card.find('.nav-indicator').css({
                                    'opacity': '1',
                                    'transform': 'scale(1)',
                                    'transition': 'all 0.3s cubic-bezier(0.4, 0, 0.2, 1)'
                                });
                            }, 50);
                            
                            // Show appropriate language
                            if (currentLang === 'ar') {
                                $card.find('.nav-indicator .ar').show();
                                $card.find('.nav-indicator .en').hide();
                            }
                        }
                        $btn.find('.en').text('Remove from Nav');
                        $btn.find('.ar').text('إزالة من التنقل');
                    } else {
                        $navIndicator.css({
                            'opacity': '0',
                            'transform': 'scale(0.5)',
                            'transition': 'all 0.3s cubic-bezier(0.4, 0, 0.2, 1)'
                        });
                        setTimeout(() => {
                            $navIndicator.remove();
                        }, 300);
                        $btn.find('.en').text('Add to Nav');
                        $btn.find('.ar').text('إضافة للتنقل');
                    }
                    
                    // Show success message
                    showAnimatedNotification('success', response.message);
                } else {
                    showAnimatedNotification('error', response.message || 'An error occurred');
                }
            },
            error: function(xhr) {
                const message = xhr.responseJSON?.message || 'An error occurred';
                showAnimatedNotification('error', message);
            },
            complete: function() {
                $card.removeClass('loading');
                $btn.prop('disabled', false);
            }
        });
    });
    
    // Toggle Footer functionality
    $(document).on('click', '.toggle-footer', function(e) {
        e.preventDefault();
        const $btn = $(this);
        const pageId = $btn.data('id');
        const $card = $btn.closest('.page-item');
        
        // Add loading state
        $card.addClass('loading');
        $btn.prop('disabled', true);
        
        $.ajax({
            url: `/admin/pages/${pageId}/toggle-footer`,
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    // Update footer indicator
                    const $footerIndicator = $card.find('.footer-indicator');
                    if (response.in_footer) {
                        $footerIndicator.removeClass('d-none');
                    } else {
                        $footerIndicator.addClass('d-none');
                    }
                    
                    // Show success message
                    showNotification('success', response.message);
                } else {
                    showNotification('error', response.message || 'An error occurred');
                }
            },
            error: function(xhr) {
                const message = xhr.responseJSON?.message || 'An error occurred';
                showNotification('error', message);
            },
            complete: function() {
                $card.removeClass('loading');
                $btn.prop('disabled', false);
            }
        });
    });
    
    // Delete page functionality
    let pageToDelete = null;
    
    $(document).on('click', '.delete-page', function(e) {
        e.preventDefault();
        pageToDelete = $(this).data('id');
        $('#deleteModal').modal('show');
    });
    
    $('#confirmDelete').on('click', function() {
        if (!pageToDelete) return;
        
        const $btn = $(this);
        $btn.prop('disabled', true).html(`
            <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
            <span class="en">Deleting...</span>
            <span class="ar" style="display: none;">جارٍ الحذف...</span>
        `);
        
        // Show appropriate language in button
        if (currentLang === 'ar') {
            $btn.find('.ar').show();
            $btn.find('.en').hide();
        }
        
        $.ajax({
            url: `/admin/pages/${pageToDelete}`,
            type: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    // Remove the card from view
                    const $card = $(`.page-item[data-id="${pageToDelete}"]`);
                    $card.fadeOut(300, function() {
                        $(this).remove();
                        
                        // Update counts
                        const currentTotal = parseInt($('#totalPages').text()) - 1;
                        const currentVisible = parseInt($('#visiblePages').text()) - 1;
                        $('#totalPages').text(currentTotal);
                        $('#visiblePages').text(currentVisible);
                        
                        // Show empty state if no pages left
                        if (currentTotal === 0) {
                            location.reload();
                        }
                    });
                    
                    $('#deleteModal').modal('hide');
                    showNotification('success', response.message);
                } else {
                    showNotification('error', response.message || 'An error occurred');
                }
            },
            error: function(xhr) {
                const message = xhr.responseJSON?.message || 'An error occurred while deleting the page';
                showNotification('error', message);
            },
            complete: function() {
                $btn.prop('disabled', false).html(`
                    <i class="align-middle me-2" data-feather="trash-2"></i>
                    <span class="en">Delete Page</span>
                    <span class="ar" style="display: none;">حذف الصفحة</span>
                `);
                feather.replace();
                
                // Show appropriate language
                if (currentLang === 'ar') {
                    $btn.find('.ar').show();
                    $btn.find('.en').hide();
                }
                
                pageToDelete = null;
            }
        });
    });
    
    // Reset delete modal when closed
    $('#deleteModal').on('hidden.bs.modal', function() {
        pageToDelete = null;
    });
    
    // Notification function
    function showAnimatedNotification(type, message) {
        const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
        const icon = type === 'success' ? 'check-circle' : 'alert-circle';
        
        const notification = $(`
            <div class="alert ${alertClass} alert-dismissible fade show" role="alert" 
                 style="position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 300px;">
                <i class="align-middle me-2" data-feather="${icon}"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `);
        
        $('body').append(notification);
        feather.replace();
        
        setTimeout(() => notification.remove(), 4000);
    }
    

    

    

    
    // Initialize everything
    initializeAnimations();
    filterPages();
    
    // Initialize Bootstrap dropdowns
    feather.replace();
});
</script>
@endsection
