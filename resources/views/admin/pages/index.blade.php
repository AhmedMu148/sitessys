@extends('admin.layouts.master')

@section('title', 'Page Management | إدارة الصفحات')

@section('css')
{{-- Include shared admin panel styles --}}
@include('admin.custom-css.shared-admin-styles')
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
                <option value="default">Default</option>
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
            <div class="d-flex justify-content-end gap-2">
                <button type="button" class="btn btn-outline-secondary" onclick="clearAllFilters()" title="Clear all filters">
                    <i class="align-middle me-2" data-feather="x-circle" style="width: 16px; height: 16px;"></i>
                    <span class="en">Clear</span>
                    <span class="ar" style="display: none;">مسح</span>
                </button>
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
         data-theme="{{ strtolower(str_replace(' ', '-', $page->themePage->name ?? 'business')) }}" 
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
                    <button class="btn actions-btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="">
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
{{-- Include shared admin panel scripts --}}
@include('admin.custom-scripts.shared-admin-scripts')

<script>
// Page-specific enhancements
$(document).ready(function() {
    // Additional page-specific functionality can be added here
    console.log('Pages management loaded with shared admin utilities');
});
</script>
@endsection
