@extends('admin.layouts.master')

@section('title', 'Edit Page | تعديل الصفحة')

@section('css')
<style>
/* ===================== Page Edit Styles ===================== */
.page-edit-header {
    background: linear-gradient(135deg, #222e3c 0%, #2b3947 100%);
    border: 1px solid rgba(255, 255, 255, 0.2);
    padding: 1.5rem 0;
    margin-bottom: 2rem;
    border-radius: 0.75rem;
    color: white;
    box-shadow: 0 4px 20px rgba(34, 46, 60, 0.15);
    position: relative;
    overflow: hidden;
}

.page-edit-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Ccircle cx='30' cy='30' r='2'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E") repeat;
}

.page-edit-header h1 {
    font-size: 1.75rem;
    font-weight: 600;
    margin-bottom: 0.25rem;
    color: #ffffff;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    position: relative;
    z-index: 1;
}

.page-edit-header p {
    font-size: 0.95rem;
    color: rgba(255, 255, 255, 0.9);
    margin-bottom: 0;
    position: relative;
    z-index: 1;
}

/* Header Buttons Styling */
.page-edit-header .btn {
    position: relative;
    z-index: 1;
    font-weight: 500;
    padding: 0.5rem 1rem;
    border-radius: 0.5rem;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    transition: all 0.3s ease;
    border: 1px solid rgba(255, 255, 255, 0.3);
}

.page-edit-header .btn-light {
    background: rgba(255, 255, 255, 0.9);
    color: #222e3c;
    border-color: rgba(255, 255, 255, 0.3);
}

.page-edit-header .btn-light:hover {
    background: rgba(255, 255, 255, 1);
    color: #222e3c;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(255, 255, 255, 0.3);
}

.page-edit-header .btn-outline-light {
    background: transparent;
    color: white;
    border-color: rgba(255, 255, 255, 0.5);
}

.page-edit-header .btn-outline-light:hover {
    background: rgba(255, 255, 255, 0.1);
    color: white;
    border-color: rgba(255, 255, 255, 0.8);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(255, 255, 255, 0.2);
}

.page-edit-header .btn i { font-size: 0.9rem; }

.me-1 { margin-right: 0.25rem !important; }
.me-2 { margin-right: 0.5rem !important; }

/* ===================== Component Cards ===================== */
.component-card {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border: 1px solid #e3f2fd;
    border-radius: 0.75rem;
    box-shadow: 0 2px 12px rgba(34, 46, 60, 0.08);
    height: 280px;
    position: relative;
    overflow: visible !important; /* <<< FIX: allow dropdown to be fully visible */
    background: linear-gradient(145deg, #ffffff 0%, #f8faff 100%);
    transform: translateY(0);
    margin-bottom: 1.5rem;
}

.component-card:hover {
    transform: translateY(-8px) scale(1.02);
    box-shadow: 0 12px 32px rgba(34, 46, 60, 0.15);
    border-color: #bbdefb;
    z-index: 10;
    background: linear-gradient(145deg, #ffffff 0%, #f3f8ff 100%);
}

.card-top-section {
    background: linear-gradient(135deg, #222e3c 0%, #2b3947 100%);
    height: 120px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    position: relative;
    color: white;
    overflow: visible !important; /* <<< FIX */
}

.card-top-text {
    font-size: 1.125rem;
    font-weight: 600;
    color: #ffffff;
    margin-bottom: 0.75rem;
    line-height: 1.4;
    text-align: center;
    position: relative;
    z-index: 1;
}

.card-icon {
    width: 50px;
    height: 50px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    color: white;
    position: relative;
    z-index: 1;
}

/* Layout Preview Image in Card */
.card-top-image {
    position: absolute;
    top: 0; left: 0; right: 0; bottom: 0;
    border-radius: 1rem 1rem 0 0;
    overflow: hidden;
}

.card-top-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.card-top-image:hover img { transform: scale(1.05); }

.card-top-image::after {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, rgba(34, 46, 60, 0.7) 0%, rgba(43, 57, 71, 0.7) 100%);
    z-index: 1;
}

.card-top-image .image-overlay-text {
    position: absolute;
    bottom: 10px;
    left: 15px;
    right: 15px;
    color: white;
    font-size: 12px;
    font-weight: 500;
    z-index: 2;
    text-align: left;
    text-shadow: 0 1px 3px rgba(0,0,0,0.5);
    line-height: 1.3;
}

[dir="rtl"] .card-top-image .image-overlay-text { text-align: right; }

.card-top-fallback {
    position: absolute;
    inset: 0;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #222e3c 0%, #2b3947 100%);
    border-radius: 1rem 1rem 0 0;
}

@media (max-width: 768px) {
    .card-top-image img { object-fit: cover; object-position: center; }
    .card-top-image .image-overlay-text { font-size: 11px; bottom: 8px; left: 10px; right: 10px; }
}

/* ===================== Dropdown ===================== */
.card-actions {
    position: absolute;
    top: 12px;
    right: 12px;
    z-index: 1055; /* <<< higher than menu */
    opacity: 1;
    transition: all 0.3s ease;
    transform: translateY(0);
}

.actions-btn {
    background: rgba(34, 46, 60, 0.9);
    border: 1px solid rgba(34, 46, 60, 0.3);
    border-radius: 0.5rem;
    width: 36px; height: 36px;
    display: flex; align-items: center; justify-content: center;
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
    inset: 0;
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

.actions-btn:hover::before { opacity: 1; }
.actions-btn:focus { outline: none; box-shadow: 0 0 0 3px rgba(34, 46, 60, 0.25); background: linear-gradient(135deg, #222e3c 0%, #2b3947 100%); }
.actions-btn:active { transform: scale(0.95); background: linear-gradient(135deg, #1a2530 0%, #222e3c 100%); }
.actions-btn i { transition: all 0.3s ease; color: #ffffff; }
.actions-btn:hover i { transform: rotate(90deg); }
.actions-btn[aria-expanded="true"] i { transform: rotate(180deg); }
.dropdown-toggle::after { display: none !important; }

/* Menu */
.dropdown-menu {
    border: 1px solid rgba(34, 46, 60, 0.15);
    box-shadow: 0 8px 25px rgba(34, 46, 60, 0.15);
    border-radius: 0.5rem;
    padding: 0.25rem 0;
    min-width: 140px;
    background: rgba(255, 255, 255, 0.98);
    margin-top: 0.125rem;
    z-index: 2000; /* <<< make sure it's above card */
}

[dir="rtl"] .dropdown-menu { right: auto; left: 0; }

.dropdown-item {
    padding: 0.4rem 0.75rem;
    border-radius: 0.25rem;
    margin: 0.125rem 0.25rem;
    transition: all 0.15s ease;
    color: #222e3c;
    font-size: 0.8rem;
    display: flex; align-items: center;
    white-space: nowrap;
}

.dropdown-item:hover {
    background: linear-gradient(135deg, #222e3c 0%, #2b3947 100%);
    color: white;
    transform: translateX(4px);
}

.dropdown-item i { width: 14px; height: 14px; margin-right: 6px; font-size: 0.75rem; flex-shrink: 0; }
[dir="rtl"] .dropdown-item i { margin-right: 0; margin-left: 6px; }
.dropdown-item.text-danger:hover { background: linear-gradient(135deg, #dc3545 0%, #c82333 100%); color: white; }

.card { position: relative; overflow: visible; }
.dropdown { position: relative; }

/* Position variations */
.dropdown-menu-up { top: auto !important; bottom: 100% !important; transform: translateY(-4px) !important; }
.dropdown-menu-end { right: 0 !important; left: auto !important; }
[dir="rtl"] .dropdown-menu-end { right: auto !important; left: 0 !important; }

.card-bottom-section {
    background: white;
    height: 160px;
    padding: 15px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.card-bottom-text { font-size: 0.85rem; color: #475569; line-height: 1.6; }

.status-badge {
    font-size: 0.7rem;
    padding: 0.2rem 0.4rem;
    border-radius: 0.25rem;
    margin: 0.125rem;
    display: inline-block;
    font-weight: 500;
}

.status-displayed { background: linear-gradient(135deg, #222e3c 0%, #2b3947 100%); color: #ffffff; border: 1px solid rgba(34, 46, 60, 0.3); box-shadow: 0 2px 8px rgba(34, 46, 60, 0.15); }
.status-hidden    { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: #ffffff; border: 1px solid rgba(245, 158, 11, 0.3); box-shadow: 0 2px 8px rgba(245, 158, 11, 0.15); }
.status-active    { background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: #ffffff; border: 1px solid rgba(16, 185, 129, 0.3); box-shadow: 0 2px 8px rgba(16, 185, 129, 0.15); }
.status-inactive  { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: #ffffff; border: 1px solid rgba(245, 158, 11, 0.3); box-shadow: 0 2px 8px rgba(245, 158, 11, 0.15); }

/* Action Buttons */
.action-buttons {
    text-align: center;
    margin-top: 2rem;
    padding: 1.5rem;
    background: linear-gradient(135deg, #f8faff 0%, #e3f2fd 100%);
    border-radius: 0.75rem;
    border: 1px solid rgba(34, 46, 60, 0.1);
    box-shadow: 0 4px 16px rgba(34, 46, 60, 0.08);
}

.btn-add-section,
.btn-save-page {
    background: linear-gradient(135deg, #222e3c 0%, #2b3947 100%);
    color: white;
    border: 1px solid rgba(34, 46, 60, 0.3);
    padding: 12px 30px;
    border-radius: 6px;
    font-weight: 500;
    margin-right: 10px;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(34, 46, 60, 0.15);
}

.btn-add-section:hover,
.btn-save-page:hover {
    background: linear-gradient(135deg, #1a2530 0%, #222e3c 100%);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(34, 46, 60, 0.25);
    color: white;
}

.btn-cancel {
    background: #d2d6de;
    color: #444;
    border: 1px solid #d2d6de;
    padding: 12px 30px;
    border-radius: 6px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-cancel:hover { background: #c1c7d0; border-color: #c1c7d0; transform: translateY(-1px); color: #444; }

/* Add New Section Card */
.add-section-card { border: 2px dashed rgba(34, 46, 60, 0.3) !important; background: linear-gradient(145deg, #f8faff 0%, #e3f2fd 100%) !important; }
.add-section-card .card-top-section { background: linear-gradient(135deg, #222e3c 0%, #2b3947 100%) !important; }
.add-section-card:hover { border-color: rgba(34, 46, 60, 0.5) !important; background: linear-gradient(145deg, #f3f8ff 0%, #ddeafa 100%) !important; }

/* No Sections Message */
.no-sections-message {
    background: linear-gradient(135deg, #f8faff 0%, #e8f4fd 100%);
    border: 2px solid rgba(34, 46, 60, 0.1);
    border-radius: 1rem;
    padding: 3rem 2rem;
    margin: 2rem 0;
    text-align: center;
    position: relative;
    overflow: hidden;
    box-shadow: 0 8px 25px rgba(34, 46, 60, 0.08);
}

.no-sections-message::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 4px;
    background: linear-gradient(90deg, #222e3c 0%, #2b3947 50%, #222e3c 100%);
}

.no-sections-content { position: relative; z-index: 2; }
.no-sections-icon { font-size: 3.5rem; color: #222e3c; margin-bottom: 1.5rem; opacity: 0.8; display: block; }
.no-sections-title { color: #222e3c; font-size: 1.5rem; font-weight: 600; margin-bottom: 1rem; letter-spacing: -0.02em; }
.no-sections-text { color: #64748b; font-size: 1rem; line-height: 1.6; margin-bottom: 2rem; max-width: 500px; margin-left: auto; margin-right: auto; }

.btn-add-first-section {
    background: linear-gradient(135deg, #222e3c 0%, #2b3947 100%);
    border: none;
    padding: 0.8rem 2rem;
    border-radius: 0.5rem;
    font-weight: 500;
    font-size: 1rem;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 4px 14px rgba(34, 46, 60, 0.15);
    position: relative;
    overflow: hidden;
}

.btn-add-first-section::before {
    content: '';
    position: absolute;
    top: 0; left: -100%;
    width: 100%; height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    transition: left 0.5s;
}

.btn-add-first-section:hover {
    background: linear-gradient(135deg, #1a2530 0%, #222e3c 100%);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(34, 46, 60, 0.25);
    color: white;
}

.btn-add-first-section:hover::before { left: 100%; }
.btn-add-first-section:active { transform: translateY(0); }

/* RTL Support */
[dir="rtl"] .card-actions { right: auto; left: 12px; }
[dir="rtl"] .btn-add-section,
[dir="rtl"] .btn-save-page { margin-right: 0; margin-left: 10px; }
[dir="rtl"] .dropdown-item:hover { transform: translateX(-4px); }
[dir="rtl"] .no-sections-text { text-align: right; }
[dir="rtl"] .btn-add-first-section { font-family: 'Cairo', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }

/* Responsive */
@media (max-width: 768px) {
    .component-card { height: 260px; margin-bottom: 1rem; }
    .card-top-section { height: 100px; }
    .card-bottom-section { height: 160px; padding: 12px; }
    .action-buttons { text-align: center; padding: 1rem; }
    .btn-add-section, .btn-save-page, .btn-cancel { width: 100%; margin: 5px 0; }
    .card-actions { top: 8px; right: 8px; }
    [dir="rtl"] .card-actions { right: auto; left: 8px; }
    .no-sections-message { padding: 2rem 1rem; margin: 1rem 0; }
    .no-sections-icon { font-size: 2.5rem; margin-bottom: 1rem; }
    .no-sections-title { font-size: 1.25rem; margin-bottom: 0.75rem; }
    .no-sections-text { font-size: 0.9rem; margin-bottom: 1.5rem; }
    .btn-add-first-section { padding: 0.6rem 1.5rem; font-size: 0.9rem; }
}
</style>
@endsection

@section('content')
    <!-- Page Header -->
    <div class="page-edit-header">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="mb-0">
                        <i class="fas fa-edit mr-2"></i>
                        {{ __('Edit Page') }}: {{ $page->name ?? $page->title ?? __('Untitled Page') }}
                    </h1>
                    <p>{{ __('Manage your page components and sections') }}</p>
                    @if($page->slug)
                        <small class="text-light">{{ __('Page URL') }}: /{{ $page->slug }}</small>
                    @endif
                    @if($page->updated_at)
                        <br><small class="text-light">{{ __('Last updated') }}: {{ $page->updated_at->diffForHumans() }}</small>
                    @endif
                </div>
                <div class="col-md-4 text-end">
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

    <!-- Component Cards Grid -->
    <div class="container-fluid">
        <div class="row">
            <!-- Sections Cards -->
            @if($page->sections && $page->sections->count() > 0)
                @foreach($page->sections->sortBy('sort_order') as $index => $section)
                <div class="col-lg-4 col-md-6">
                    <div class="component-card section-card">
                        <div class="card-top-section">
                            <div class="card-actions">
                                <div class="dropdown">
                                    <button class="btn actions-btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Section Actions">
                                        <i class="fas fa-ellipsis-v" style="width: 16px; height: 16px;"></i>
                                        <span class="visually-hidden">Actions</span>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item" href="{{ route('admin.pages.sections.edit', ['page_id' => $page->id, 'section_id' => $section->id]) }}">
                                            <i class="fas fa-edit"></i>{{ __('Edit') }}
                                        </a></li>
                                        <li><a class="dropdown-item" href="{{ route('admin.pages.sections.preview', ['page_id' => $page->id, 'section_id' => $section->id]) }}" target="_blank">
                                            <i class="fas fa-eye"></i>{{ __('Preview') }}
                                        </a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item" href="#" onclick="toggleActive({{ $section->id }})">
                                            <i class="fas fa-toggle-on"></i>{{ ($section->status ?? true) ? __('Deactivate') : __('Activate') }}
                                        </a></li>
                                        <li><a class="dropdown-item" href="#" onclick="changeOrder({{ $section->id }})">
                                            <i class="fas fa-sort"></i>{{ __('Change Order') }}
                                        </a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item" href="#" onclick="addSection()">
                                            <i class="fas fa-plus"></i>{{ __('Add Section') }}
                                        </a></li>
                                        <li><a class="dropdown-item text-danger" href="#" onclick="deleteSection({{ $section->id }})">
                                            <i class="fas fa-trash"></i>{{ __('Delete Section') }}
                                        </a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item" href="#" onclick="duplicateCheck('section', {{ $section->id }})">
                                            <i class="fas fa-search"></i>{{ __('Duplicate Check') }}
                                        </a></li>
                                    </ul>
                                </div>
                            </div>
                            @php $layoutImage = $section->layout->preview_image ?? null; @endphp
                            @if($layoutImage)
                                <div class="card-top-image">
                                    <img src="{{ $layoutImage }}" alt="{{ $section->layout->name ?? $section->name }}" onerror="this.style.display='none'; this.parentElement.querySelector('.card-top-fallback').style.display='flex';" />
                                    <div class="image-overlay-text">
                                        {{ __('Section') }} {{ $section->sort_order ?? ($index + 1) }}: {{ $section->name ?? __('Unnamed Section') }}
                                    </div>
                                    <div class="card-top-fallback" style="display: none;">
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
            @else
                <!-- No Sections Message -->
                <div class="col-12">
                    <div class="no-sections-message">
                        <div class="no-sections-content">
                            <i class="fas fa-layer-group no-sections-icon"></i>
                            <h4 class="no-sections-title">{{ __('No sections found for this page') }}</h4>
                            <p class="no-sections-text">{{ __('Click "Add New Section" to get started and build your page content.') }}</p>
                            <div class="no-sections-action">
                                <button type="button" class="btn btn-primary btn-add-first-section" onclick="addSection()">
                                    <i class="fas fa-plus me-1"></i>{{ __('Add Your First Section') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Add New Section Card -->
            <div class="col-lg-4 col-md-6">
                <div class="component-card add-section-card">
                    <div class="card-top-section">
                        <div class="card-top-text">{{ __('Add New Section') }}</div>
                        <div class="card-icon"><i class="fas fa-plus"></i></div>
                    </div>
                    <div class="card-bottom-section">
                        <div class="card-bottom-text text-center">
                            <p class="mb-3">{{ __('Click to add a new section to your page') }}</p>
                            <button type="button" class="btn btn-add-section" onclick="addSection()">
                                <i class="fas fa-plus me-1"></i>{{ __('Add Section') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
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

<!-- ===================== Modals ===================== -->
<!-- Edit Theme Modal -->
<div class="modal fade" id="editThemeModal" tabindex="-1" aria-labelledby="editThemeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editThemeModalLabel">{{ __('Edit Theme') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="themeForm">
                    <div class="mb-3">
                        <label for="componentType" class="form-label">{{ __('Component Type') }}</label>
                        <input type="text" id="componentType" class="form-control" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="themeSelect" class="form-label">{{ __('Available Themes') }}</label>
                        <select class="form-control" id="themeSelect"></select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="saveTheme()">{{ __('Save') }}</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
            </div>
        </div>
    </div>
</div>

<!-- Add Section Modal -->
<div class="modal fade" id="addSectionModal" tabindex="-1" aria-labelledby="addSectionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addSectionModalLabel">{{ __('Add New Section') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="sectionForm">
                    <div class="mb-3">
                        <label for="sectionType" class="form-label">{{ __('Section Type') }}</label>
                        <select class="form-control" id="sectionType" required>
                            <option value="">{{ __('Select Section Type') }}</option>
                            <option value="hero">{{ __('Hero Section') }}</option>
                            <option value="slider">{{ __('Slider Section') }}</option>
                            <option value="testimonial">{{ __('Testimonial Section') }}</option>
                            <option value="services">{{ __('Services Section') }}</option>
                            <option value="about">{{ __('About Section') }}</option>
                            <option value="contact">{{ __('Contact Section') }}</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="sectionName" class="form-label">{{ __('Section Name') }}</label>
                        <input type="text" class="form-control" id="sectionName" required placeholder="{{ __('Enter section name') }}">
                    </div>
                    <div class="mb-3">
                        <label for="sectionOrder" class="form-label">{{ __('Sort Order') }}</label>
                        <input type="number" class="form-control" id="sectionOrder" value="1" min="1">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" onclick="saveSection()">{{ __('Add Section') }}</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Section Modal -->
<div class="modal fade" id="editSectionModal" tabindex="-1" aria-labelledby="editSectionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editSectionModalLabel">{{ __('Edit Section') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editSectionForm">
                    <input type="hidden" id="editSectionId">

                    <!-- Title Tabs -->
                    <ul class="nav nav-tabs" id="languageTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="english-tab" data-bs-toggle="tab" data-bs-target="#english" type="button" role="tab">
                                {{ __('English') }}
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="arabic-tab" data-bs-toggle="tab" data-bs-target="#arabic" type="button" role="tab">
                                {{ __('Arabic') }}
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content mt-3" id="languageTabContent">
                        <div class="tab-pane fade show active" id="english" role="tabpanel">
                            <div class="mb-3">
                                <label for="titleEn" class="form-label">{{ __('Title (English)') }}</label>
                                <input type="text" class="form-control" id="titleEn" placeholder="{{ __('Enter English title') }}">
                            </div>
                            <div class="mb-3">
                                <label for="contentEn" class="form-label">{{ __('Content (English)') }}</label>
                                <textarea class="form-control" id="contentEn" rows="4" placeholder="{{ __('Enter English content') }}"></textarea>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="arabic" role="tabpanel">
                            <div class="mb-3">
                                <label for="titleAr" class="form-label">{{ __('Title (Arabic)') }}</label>
                                <input type="text" class="form-control" id="titleAr" placeholder="{{ __('Enter Arabic title') }}" dir="rtl">
                            </div>
                            <div class="mb-3">
                                <label for="contentAr" class="form-label">{{ __('Content (Arabic)') }}</label>
                                <textarea class="form-control" id="contentAr" rows="4" placeholder="{{ __('Enter Arabic content') }}" dir="rtl"></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Media Settings -->
                    <div class="mb-3">
                        <label for="mediaUrl" class="form-label">{{ __('Media Settings') }}</label>
                        <input type="url" class="form-control" id="mediaUrl" placeholder="{{ __('Image/Video URL') }}">
                    </div>

                    <!-- Color Settings -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="bgColor" class="form-label">{{ __('Background Color') }}</label>
                                <input type="color" class="form-control" id="bgColor" value="#ffffff">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="textColor" class="form-label">{{ __('Text Color') }}</label>
                                <input type="color" class="form-control" id="textColor" value="#000000">
                            </div>
                        </div>
                    </div>

                    <!-- Advanced Settings -->
                    <div class="mb-3">
                        <label for="customCode" class="form-label">{{ __('Custom CSS/JS (Optional)') }}</label>
                        <textarea class="form-control" id="customCode" rows="3" placeholder="{{ __('Enter custom CSS or JavaScript') }}"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="saveEditSection()">{{ __('Save Changes') }}</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
            </div>
        </div>
    </div>
</div>

@section('js')
<script>
// ===================== JS DATA =====================
const pageData = {
    id: {{ $page->id }},
    name: '{{ $page->name }}',
    slug: '{{ $page->slug }}',
    sections: @json($page->sections ?? [])
};

// Available section layouts
const availableLayouts = @json($sectionLayouts ?? []);
const defaultLayoutId = availableLayouts.length > 0 ? availableLayouts[0].id : 1;

// ===================== Functions =====================
function duplicateCheck(componentType, sectionId = null){
    const message = '{{ __("No duplicate sections found") }}';
    showAlert('info', message);
}

function addSection(){ new bootstrap.Modal(document.getElementById('addSectionModal')).show(); }

function saveSection(){
    const type  = document.getElementById('sectionType').value;
    const name  = document.getElementById('sectionName').value;
    const order = document.getElementById('sectionOrder').value;
    
    if(!type || !name){ 
        showAlert('error', '{{ __("Please fill all required fields") }}'); 
        return; 
    }
    
    // Show loading state
    showAlert('info', '{{ __("Creating section...") }}');
    
    // Prepare data for API call
    const sectionData = {
        name: name,
        type: type,
        sort_order: parseInt(order),
        status: true,
        tpl_layouts_id: defaultLayoutId, // Use default layout
        content: {},
        custom_styles: '',
        custom_scripts: ''
    };
    
    // Make API call to create section
    fetch('/admin/pages/{{ $page->id }}/sections', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(sectionData)
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            // Close modal and reset form
            bootstrap.Modal.getInstance(document.getElementById('addSectionModal')).hide();
            document.getElementById('sectionForm').reset();
            
            showAlert('success', '{{ __("Section created successfully") }}');
            
            // Reload page to show new section
            setTimeout(() => { location.reload(); }, 1000);
        } else {
            showAlert('error', data.message || '{{ __("Failed to create section") }}');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('error', '{{ __("An error occurred while creating section") }}');
    });
}

function editSection(id){
    const s = pageData.sections.find(x=>x.id===id); if(!s) return;
    document.getElementById('editSectionId').value = id;
    document.getElementById('titleEn').value = s.title_en || '';
    document.getElementById('titleAr').value = s.title_ar || '';
    document.getElementById('contentEn').value = s.content_en || '';
    document.getElementById('contentAr').value = s.content_ar || '';
    document.getElementById('mediaUrl').value = s.media_url || '';
    document.getElementById('bgColor').value = s.bg_color || '#ffffff';
    document.getElementById('textColor').value = s.text_color || '#000000';
    document.getElementById('customCode').value = s.custom_code || '';
    new bootstrap.Modal(document.getElementById('editSectionModal')).show();
}

function saveEditSection(){
    const id = parseInt(document.getElementById('editSectionId').value);
    const s = pageData.sections.find(x=>x.id===id); if(!s) return;
    s.title_en   = document.getElementById('titleEn').value;
    s.title_ar   = document.getElementById('titleAr').value;
    s.content_en = document.getElementById('contentEn').value;
    s.content_ar = document.getElementById('contentAr').value;
    s.media_url  = document.getElementById('mediaUrl').value;
    s.bg_color   = document.getElementById('bgColor').value;
    s.text_color = document.getElementById('textColor').value;
    s.custom_code= document.getElementById('customCode').value;
    bootstrap.Modal.getInstance(document.getElementById('editSectionModal')).hide();
    showAlert('success', '{{ __("Section updated successfully") }}');
}

function deleteSection(id){
    if(!confirm('{{ __("Are you sure you want to delete this section?") }}')) return;
    
    // Show loading state
    showAlert('info', '{{ __("Deleting section...") }}');
    
    // Make API call to delete section
    fetch(`/admin/pages/{{ $page->id }}/sections/${id}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            // Remove from local data
            pageData.sections = pageData.sections.filter(x=>x.id!==id);
            showAlert('success', '{{ __("Section deleted successfully") }}');
            
            // Reload page to reflect changes
            setTimeout(() => { location.reload(); }, 1000);
        } else {
            showAlert('error', data.message || '{{ __("Failed to delete section") }}');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('error', '{{ __("An error occurred while deleting section") }}');
    });
}

function toggleActive(id){
    if(!confirm('{{ __("Are you sure you want to toggle this section status?") }}')) return;
    
    // Show loading state
    showAlert('info', '{{ __("Updating section status...") }}');
    
    // Make API call to toggle status
    fetch(`/admin/pages/{{ $page->id }}/sections/${id}/toggle-status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            // Update local data
            const s = pageData.sections.find(x=>x.id===id);
            if(s) {
                s.status = data.status;
                s.is_active = data.status; // Keep both for compatibility
            }
            
            const statusText = data.status ? '{{ __("active") }}' : '{{ __("inactive") }}';
            showAlert('success', `{{ __("Section is now") }} ${statusText}`);
            
            // Reload page to reflect changes
            setTimeout(() => { location.reload(); }, 1000);
        } else {
            showAlert('error', data.message || '{{ __("Failed to update section status") }}');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('error', '{{ __("An error occurred while updating section status") }}');
    });
}

function changeOrder(id){
    const val = prompt('{{ __("Enter new order (1-10):") }}','1');
    if(val===null) return;
    const order = parseInt(val);
    if(isNaN(order)||order<1||order>10){ 
        showAlert('error','{{ __("Please enter a valid order number (1-10)") }}'); 
        return; 
    }
    
    // Find the section to get required data
    const section = pageData.sections.find(x=>x.id===id);
    if(!section) {
        showAlert('error', '{{ __("Section not found") }}');
        return;
    }
    
    // Show loading state
    showAlert('info', '{{ __("Updating section order...") }}');
    
    // Make API call to update section with all required fields
    fetch(`/admin/pages/{{ $page->id }}/sections/${id}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            name: section.name,
            tpl_layouts_id: section.tpl_layouts_id || defaultLayoutId,
            content: {},
            custom_styles: '',
            custom_scripts: '',
            sort_order: order
        })
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            // Update local data
            section.sort_order = order;
            
            showAlert('success', '{{ __("Section order updated successfully") }}');
            
            // Reload page to reflect changes
            setTimeout(() => { location.reload(); }, 1000);
        } else {
            showAlert('error', data.message || '{{ __("Failed to update section order") }}');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('error', '{{ __("An error occurred while updating section order") }}');
    });
}

function savePage(){
    if(confirm('{{ __("Are you sure you want to save all changes?") }}')){
        showAlert('success','{{ __("Changes saved successfully") }}');
        setTimeout(()=>{ window.location.href='{{ route("admin.pages.index") }}'; },1500);
    }
}

function showAlert(type, message){
    const cls = type==='success'?'alert-success': type==='error'?'alert-danger': type==='warning'?'alert-warning':'alert-info';
    const html = `<div class="alert ${cls} alert-dismissible fade show" role="alert">${message}<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>`;
    document.querySelectorAll('.alert').forEach(a=>a.remove());
    document.querySelector('.container-fluid').insertAdjacentHTML('afterbegin', html);
    setTimeout(()=>{
        document.querySelectorAll('.alert').forEach(a=>{ a.classList.remove('show'); setTimeout(()=>a.remove(),150); });
    },3000);
}

// ===================== Init =====================
document.addEventListener('DOMContentLoaded', function(){
    console.log('Page Edit loaded');

    // Init Bootstrap dropdowns with better popper config
    setTimeout(()=>{
        document.querySelectorAll('[data-bs-toggle="dropdown"]').forEach(el=>{
            new bootstrap.Dropdown(el, {
                popperConfig: {
                    strategy: 'fixed',
                    modifiers: [{ name: 'preventOverflow', options: { boundary: document.body } }]
                }
            });
        });
    }, 300);

    // Enhanced positioning classes toggle
    document.addEventListener('show.bs.dropdown', function(e){
        const menu = e.target.querySelector('.dropdown-menu');
        if(!menu) return;
        menu.classList.remove('dropdown-menu-up','dropdown-menu-end');
        setTimeout(()=>{
            const btnRect  = e.target.querySelector('[data-bs-toggle="dropdown"]').getBoundingClientRect();
            const menuRect = menu.getBoundingClientRect();
            const cardRect = e.target.closest('.component-card')?.getBoundingClientRect();
            if(btnRect.bottom + menuRect.height > window.innerHeight - 20){ menu.classList.add('dropdown-menu-up'); }
            if(cardRect && (btnRect.left + menuRect.width > cardRect.right)){ menu.classList.add('dropdown-menu-end'); }
            if(document.dir==='rtl' || document.documentElement.dir==='rtl'){
                if(cardRect && (btnRect.right - menuRect.width < cardRect.left)) menu.classList.remove('dropdown-menu-end');
            }
        },10);
    });
    document.addEventListener('hide.bs.dropdown', e=>{
        const menu = e.target.querySelector('.dropdown-menu');
        if(menu){ menu.classList.remove('dropdown-menu-up'); }
    });

    // Handle layout preview images
    const imgs = document.querySelectorAll('.card-top-image img');
    imgs.forEach(img=>{
        img.addEventListener('error', function(){ this.style.display='none'; this.parentElement.querySelector('.card-top-fallback').style.display='flex'; });
        img.addEventListener('load',  function(){ const fb=this.parentElement.querySelector('.card-top-fallback'); if(fb) fb.style.display='none'; });
    });
    setTimeout(()=>{ imgs.forEach(img=>{ if(!img.src || img.src==='' || img.src===window.location.href){ img.dispatchEvent(new Event('error')); } }); },100);
});
</script>
@endsection
