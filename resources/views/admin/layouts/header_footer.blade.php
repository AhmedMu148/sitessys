@extends('admin.layouts.master')

@section('title', 'Header & Footer Management')

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

/* Component Cards */
.component-card {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border: 1px solid #e3f2fd;
    border-radius: 0.75rem;
    box-shadow: 0 2px 12px rgba(34, 46, 60, 0.08);
    height: 280px;
    position: relative;
    overflow: visible !important;
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
    overflow: visible !important;
}

.card-top-image {
    width: 100%;
    height: 100%;
    position: absolute;
    top: 0;
    left: 0;
    z-index: 0;
}

.card-top-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
}

.image-overlay-text {
    position: absolute;
    bottom: 10px;
    left: 10px;
    color: white;
    background: rgba(0, 0, 0, 0.7);
    padding: 5px 10px;
    border-radius: 4px;
    font-size: 0.8rem;
    z-index: 2;
}

.card-top-fallback {
    width: 100%;
    height: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    z-index: 1;
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

.card-bottom-section {
    background: white;
    height: 160px;
    padding: 15px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.card-bottom-text { 
    font-size: 0.85rem; 
    color: #475569; 
    line-height: 1.6; 
}

.status-badge {
    font-size: 0.7rem;
    padding: 0.2rem 0.4rem;
    border-radius: 0.25rem;
    margin: 0.125rem;
    display: inline-block;
    font-weight: 500;
}

.status-displayed, .status-active { 
    background: linear-gradient(135deg, #222e3c 0%, #2b3947 100%); 
    color: #ffffff; 
    border: 1px solid rgba(34, 46, 60, 0.3); 
    box-shadow: 0 2px 8px rgba(34, 46, 60, 0.15); 
}

.status-hidden, .status-inactive { 
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); 
    color: #ffffff; 
    border: 1px solid rgba(245, 158, 11, 0.3); 
    box-shadow: 0 2px 8px rgba(245, 158, 11, 0.15); 
}

/* Dropdown */
.card-actions {
    position: absolute;
    top: 12px;
    right: 12px;
    z-index: 1055;
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

.actions-btn:hover {
    background: linear-gradient(135deg, #222e3c 0%, #2b3947 100%);
    border-color: #222e3c;
    box-shadow: 0 6px 20px rgba(34, 46, 60, 0.35);
    transform: scale(1.08);
    color: white;
}

.dropdown-menu {
    border: 1px solid rgba(34, 46, 60, 0.15);
    box-shadow: 0 8px 25px rgba(34, 46, 60, 0.15);
    border-radius: 0.5rem;
    padding: 0.25rem 0;
    min-width: 140px;
    background: rgba(255, 255, 255, 0.98);
    margin-top: 0.125rem;
    z-index: 2000;
}

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

.dropdown-item i { 
    width: 14px; 
    height: 14px; 
    margin-right: 6px; 
    font-size: 0.75rem; 
    flex-shrink: 0; 
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
                        <i class="fas fa-layout mr-2"></i>
                        {{ __('Header & Footer Management') }}
                    </h1>
                    <p>{{ __('Manage your website header and footer components') }}</p>
                </div>
                <div class="col-md-4 text-end">
                    @if(Route::has('admin.pages.index'))
                        <a href="{{ route('admin.pages.index') }}" class="btn btn-outline-light">
                            <i class="fas fa-arrow-left me-1"></i> {{ __('Back to Pages') }}
                        </a>
                    @else
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-light">
                            <i class="fas fa-arrow-left me-1"></i> {{ __('Back to Dashboard') }}
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Component Cards Grid -->
    <div class="container-fluid">
        <div class="row">
            <!-- Header Component Card -->
            <div class="col-lg-6 col-md-12">
                <div class="component-card">
                    <div class="card-top-section">
                        <!-- Card Actions Dropdown -->
                        <div class="card-actions">
                            <div class="dropdown">
                                <button class="btn actions-btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Header Actions">
                                    <i class="fas fa-ellipsis-v" style="width: 16px; height: 16px;"></i>
                                    <span class="visually-hidden">Actions</span>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="#" onclick="editTheme('header')">
                                        <i class="fas fa-edit"></i>{{ __('Edit Theme') }}
                                    </a></li>
                                    <li><a class="dropdown-item" href="#" onclick="addLink('header')">
                                        <i class="fas fa-plus"></i>{{ __('Add Link') }}
                                    </a></li>
                                    <li><a class="dropdown-item" href="#" onclick="removeLink('header')">
                                        <i class="fas fa-minus"></i>{{ __('Remove Link') }}
                                    </a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="#" onclick="toggleDisplay('header')">
                                        <i class="fas fa-eye"></i>{{ __('Toggle Display') }}
                                    </a></li>
                                </ul>
                            </div>
                        </div>
                        
                        @if($activeHeader->preview_image)
                            <div class="card-top-image">
                                <img src="{{ $activeHeader->preview_image }}" alt="{{ $activeHeader->name }}" onerror="this.style.display='none'; this.parentElement.querySelector('.card-top-fallback').style.display='flex';" />
                                <div class="image-overlay-text">
                                    {{ __('Header') }}: {{ $activeHeader->name }}
                                </div>
                                <div class="card-top-fallback" style="display: none;">
                                    <div class="card-top-text">{{ __('Header') }}</div>
                                    <div class="card-icon"><i class="fas fa-window-maximize"></i></div>
                                </div>
                            </div>
                        @else
                            <div class="card-top-text">{{ __('Header') }}</div>
                            <div class="card-icon"><i class="fas fa-window-maximize"></i></div>
                        @endif
                    </div>
                    <div class="card-bottom-section">
                        <div class="card-bottom-text">
                            <strong>{{ __('Name') }}:</strong> {{ $activeHeader->name ?? 'Default Header' }}<br>
                            <strong>{{ __('Layout ID') }}:</strong> {{ $activeHeader->id ?? 'N/A' }}<br>
                            <strong>{{ __('Status') }}:</strong>
                            <span class="status-badge {{ ($activeHeader->status ?? true) ? 'status-displayed' : 'status-hidden' }}" id="header-status">
                                {{ ($activeHeader->status ?? true) ? __('Active') : __('Inactive') }}
                            </span>
                            @if($headerLayouts->count() > 0)
                                <br><strong>{{ __('Available') }}:</strong> {{ $headerLayouts->count() }} {{ __('layouts') }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Component Card -->
            <div class="col-lg-6 col-md-12">
                <div class="component-card">
                    <div class="card-top-section">
                        <!-- Card Actions Dropdown -->
                        <div class="card-actions">
                            <div class="dropdown">
                                <button class="btn actions-btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Footer Actions">
                                    <i class="fas fa-ellipsis-v" style="width: 16px; height: 16px;"></i>
                                    <span class="visually-hidden">Actions</span>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="#" onclick="editTheme('footer')">
                                        <i class="fas fa-edit"></i>{{ __('Edit Theme') }}
                                    </a></li>
                                    <li><a class="dropdown-item" href="#" onclick="addLink('footer')">
                                        <i class="fas fa-plus"></i>{{ __('Add Link') }}
                                    </a></li>
                                    <li><a class="dropdown-item" href="#" onclick="removeLink('footer')">
                                        <i class="fas fa-minus"></i>{{ __('Remove Link') }}
                                    </a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="#" onclick="toggleDisplay('footer')">
                                        <i class="fas fa-eye"></i>{{ __('Toggle Display') }}
                                    </a></li>
                                </ul>
                            </div>
                        </div>
                        
                        @if($activeFooter->preview_image)
                            <div class="card-top-image">
                                <img src="{{ $activeFooter->preview_image }}" alt="{{ $activeFooter->name }}" onerror="this.style.display='none'; this.parentElement.querySelector('.card-top-fallback').style.display='flex';" />
                                <div class="image-overlay-text">
                                    {{ __('Footer') }}: {{ $activeFooter->name }}
                                </div>
                                <div class="card-top-fallback" style="display: none;">
                                    <div class="card-top-text">{{ __('Footer') }}</div>
                                    <div class="card-icon"><i class="fas fa-window-minimize"></i></div>
                                </div>
                            </div>
                        @else
                            <div class="card-top-text">{{ __('Footer') }}</div>
                            <div class="card-icon"><i class="fas fa-window-minimize"></i></div>
                        @endif
                    </div>
                    <div class="card-bottom-section">
                        <div class="card-bottom-text">
                            <strong>{{ __('Name') }}:</strong> {{ $activeFooter->name ?? 'Default Footer' }}<br>
                            <strong>{{ __('Layout ID') }}:</strong> {{ $activeFooter->id ?? 'N/A' }}<br>
                            <strong>{{ __('Status') }}:</strong>
                            <span class="status-badge {{ ($activeFooter->status ?? true) ? 'status-displayed' : 'status-hidden' }}" id="footer-status">
                                {{ ($activeFooter->status ?? true) ? __('Active') : __('Inactive') }}
                            </span>
                            @if($footerLayouts->count() > 0)
                                <br><strong>{{ __('Available') }}:</strong> {{ $footerLayouts->count() }} {{ __('layouts') }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
<script>
// Data for header and footer
const componentData = {
    header: {
        theme: 'default-header',
        links: 0,
        maxLinks: 5,
        displayed: true
    },
    footer: {
        theme: 'default-footer',
        links: 0,
        maxLinks: 10,
        displayed: true
    }
};

const themeOptions = {
    header: [
        { value: 'default-header', text: '{{ __("Default Header") }}' },
        { value: 'modern-header', text: '{{ __("Modern Header") }}' },
        { value: 'corporate-header', text: '{{ __("Corporate Header") }}' },
        { value: 'minimal-header', text: '{{ __("Minimal Header") }}' }
    ],
    footer: [
        { value: 'default-footer', text: '{{ __("Default Footer") }}' },
        { value: 'simple-footer', text: '{{ __("Simple Footer") }}' },
        { value: 'seo-footer', text: '{{ __("SEO Footer") }}' },
        { value: 'corporate-footer', text: '{{ __("Corporate Footer") }}' }
    ]
};

function editTheme(componentType) {
    const themes = themeOptions[componentType];
    const currentTheme = componentData[componentType].theme;
    
    let options = themes.map(theme => 
        `<option value="${theme.value}" ${theme.value === currentTheme ? 'selected' : ''}>${theme.text}</option>`
    ).join('');
    
    const modal = `
        <div class="modal fade" id="themeModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('Edit') }} ${componentType.charAt(0).toUpperCase() + componentType.slice(1)} {{ __('Theme') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <select class="form-control" id="themeSelect">${options}</select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" onclick="saveTheme('${componentType}')">{{ __('Save') }}</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    </div>
                </div>
            </div>
        </div>`;
    
    document.body.insertAdjacentHTML('beforeend', modal);
    new bootstrap.Modal(document.getElementById('themeModal')).show();
}

function saveTheme(componentType) {
    const newTheme = document.getElementById('themeSelect').value;
    componentData[componentType].theme = newTheme;
    bootstrap.Modal.getInstance(document.getElementById('themeModal')).hide();
    document.getElementById('themeModal').remove();
    showAlert('success', '{{ __("Theme updated successfully") }}');
}

function addLink(componentType) {
    const data = componentData[componentType];
    if (data.links >= data.maxLinks) {
        showAlert('warning', `{{ __("Maximum links limit reached") }} (${data.maxLinks})`);
        return;
    }
    data.links++;
    showAlert('success', '{{ __("Link added successfully") }}');
}

function removeLink(componentType) {
    const data = componentData[componentType];
    if (data.links <= 0) {
        showAlert('warning', '{{ __("No links to remove") }}');
        return;
    }
    data.links--;
    showAlert('success', '{{ __("Link removed successfully") }}');
}

function toggleDisplay(componentType) {
    const data = componentData[componentType];
    data.displayed = !data.displayed;
    
    const status = data.displayed ? '{{ __("displayed") }}' : '{{ __("hidden") }}';
    showAlert('success', `{{ __("Component is now") }} ${status}`);
}

function showAlert(type, message) {
    const cls = type === 'success' ? 'alert-success' : 
               type === 'error' ? 'alert-danger' : 
               type === 'warning' ? 'alert-warning' : 'alert-info';
    
    const html = `<div class="alert ${cls} alert-dismissible fade show" role="alert">
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>`;
    
    document.querySelectorAll('.alert').forEach(a => a.remove());
    document.querySelector('.container-fluid').insertAdjacentHTML('afterbegin', html);
    
    setTimeout(() => {
        document.querySelectorAll('.alert').forEach(a => {
            a.classList.remove('show');
            setTimeout(() => a.remove(), 150);
        });
    }, 3000);
}

// Initialize dropdowns
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Bootstrap dropdowns
    var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'));
    var dropdownList = dropdownElementList.map(function (dropdownToggleEl) {
        return new bootstrap.Dropdown(dropdownToggleEl);
    });
});
</script>
@endsection
