@extends('admin.layouts.master')

@section('title', 'Template Management | إدارة القوالب')

@section('css')
<style>
/* ===================== Template Management Styles ===================== */
.template-header {
    background: linear-gradient(135deg, #222e3c 0%, #2b3947 100%);
    border: 1px solid rgba(255, 255, 255, 0.2);
    padding: 2.5rem 1.5rem;
    margin-bottom: 2rem;
    border-radius: 0.75rem;
    color: white;
    box-shadow: 0 4px 20px rgba(34, 46, 60, 0.15);
    position: relative;
    overflow: hidden;
}

.template-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Ccircle cx='30' cy='30' r='2'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E") repeat;
}

.template-header h1 {
    font-size: 1.75rem;
    font-weight: 600;
    margin-bottom: 0.25rem;
    color: #ffffff;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    position: relative;
    z-index: 1;
}

.template-header p {
    font-size: 0.95rem;
    color: rgba(255, 255, 255, 0.9);
    margin-bottom: 0;
    position: relative;
    z-index: 1;
}

/* Section Headers */
.section-header {
    background: linear-gradient(135deg, #f8faff 0%, #e3f2fd 100%);
    border: 1px solid rgba(34, 46, 60, 0.1);
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    border-radius: 0.75rem;
    position: relative;
}

.section-header::before {
    content: '';
    position: absolute;
    top: 0; 
    left: 0; 
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #222e3c 0%, #2b3947 50%, #222e3c 100%);
    border-radius: 0.75rem 0.75rem 0 0;
}

.section-title {
    color: #222e3c;
    font-size: 1.25rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
}

.section-title i {
    margin-right: 0.5rem;
    color: #222e3c;
}

.section-description {
    color: #64748b;
    font-size: 0.9rem;
    margin-bottom: 0;
    line-height: 1.5;
}

/* ===================== Component Cards ===================== */
.template-card {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border: 1px solid #e3f2fd;
    border-radius: 1rem; /* Fixed: unified border radius for perfect curves */
    box-shadow: 0 2px 12px rgba(34, 46, 60, 0.08);
    height: 280px;
    position: relative;
    overflow: visible; /* Allow dropdown to show outside card boundaries */
    background: linear-gradient(145deg, #ffffff 0%, #f8faff 100%);
    transform: translateY(0);
    margin-bottom: 1.5rem;
    backdrop-filter: blur(10px);
}

.template-card:hover {
    transform: translateY(-8px) scale(1.02);
    box-shadow: 0 12px 32px rgba(34, 46, 60, 0.15);
    border-color: #bbdefb;
    z-index: 10;
    background: linear-gradient(145deg, #ffffff 0%, #f3f8ff 100%);
}

.template-card.active {
    border: 2px solid #10b981;
    box-shadow: 0 4px 20px rgba(16, 185, 129, 0.25);
}

.template-card.active::before {
    content: '✓ {{ __("Active") }}';
    position: absolute;
    top: 12px;
    left: 12px;
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
    padding: 0.25rem 0.5rem;
    border-radius: 0.25rem;
    font-size: 0.7rem;
    font-weight: bold;
    z-index: 20;
    box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3);
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
    overflow: hidden; /* Hide content overflow while allowing positioned elements */
    border-radius: 1rem 1rem 0 0; /* Fixed: match parent border radius */
}

/* Layout Preview Image in Card */
.card-top-image {
    position: absolute;
    top: 0; left: 0; right: 0; bottom: 0;
    border-radius: 1rem 1rem 0 0; /* Fixed: match parent border radius */
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
    border-radius: 0.75rem 0.75rem 0 0;
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
    border-radius: 0 0 1rem 1rem; /* Fixed: match parent border radius for bottom corners */
}

.card-bottom-text { 
    font-size: 0.85rem; 
    color: #475569; 
    line-height: 1.6; 
    margin-bottom: 1rem;
}

/* Template Card Transitions for Filtering */
.col-lg-4, .col-md-6 {
    transition: all 0.3s ease;
}

.template-card {
    transition: all 0.3s ease;
}

/* Search and Filter Styling */
.page-actions {
    background: linear-gradient(135deg, #f8faff 0%, #e3f2fd 100%);
    border: 1px solid rgba(34, 46, 60, 0.1);
    border-radius: 1rem;
    padding: 1.5rem;
    box-shadow: 0 4px 15px rgba(34, 46, 60, 0.08);
    transition: all 0.3s ease;
}

.page-actions:hover {
    box-shadow: 0 6px 20px rgba(34, 46, 60, 0.12);
    transform: translateY(-1px);
}

.page-actions .btn-primary {
    background: linear-gradient(135deg, #222e3c 0%, #2b3947 100%);
    border: none;
    border-radius: 0.5rem;
    font-weight: 600;
    padding: 0.75rem 1.5rem;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(34, 46, 60, 0.3);
}

.page-actions .btn-primary:hover {
    background: linear-gradient(135deg, #2b3947 0%, #354553 100%);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(34, 46, 60, 0.4);
}

/* Enhanced Filter Dropdowns */
.filter-dropdown-container {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    min-width: 140px;
}

.filter-label {
    font-size: 0.75rem;
    font-weight: 600;
    color: #222e3c;
    margin-bottom: 0.25rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.filter-dropdown {
    border: 2px solid rgba(34, 46, 60, 0.15);
    border-radius: 0.5rem;
    background: rgba(255, 255, 255, 0.95);
    font-weight: 500;
    font-size: 0.9rem;
    padding: 0.5rem 0.75rem;
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
    box-shadow: 0 2px 8px rgba(34, 46, 60, 0.1);
    cursor: pointer;
    position: relative;
}

.filter-dropdown:hover {
    border-color: rgba(34, 46, 60, 0.3);
    background: rgba(255, 255, 255, 1);
    box-shadow: 0 4px 12px rgba(34, 46, 60, 0.15);
    transform: translateY(-1px);
}

.filter-dropdown:focus {
    border-color: #222e3c;
    background: rgba(255, 255, 255, 1);
    box-shadow: 0 0 0 0.2rem rgba(34, 46, 60, 0.25), 0 4px 12px rgba(34, 46, 60, 0.15);
    outline: none;
    transform: translateY(-1px);
}

.filter-dropdown option {
    padding: 0.5rem;
    background: white;
    color: #222e3c;
    font-weight: 500;
}

.filter-dropdown option:hover {
    background: linear-gradient(135deg, #222e3c 0%, #2b3947 100%);
    color: white;
}

/* Clear Filters Button */
.btn-outline-secondary {
    border: 2px solid rgba(34, 46, 60, 0.2);
    border-radius: 0.5rem;
    color: #222e3c;
    padding: 0.5rem 0.75rem;
    transition: all 0.3s ease;
    background: rgba(255, 255, 255, 0.8);
    backdrop-filter: blur(10px);
}

.btn-outline-secondary:hover {
    background: linear-gradient(135deg, #222e3c 0%, #2b3947 100%);
    border-color: #222e3c;
    color: white;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(34, 46, 60, 0.2);
}

/* Search Box Enhancements */
.search-box {
    position: relative;
    flex: 1;
    max-width: 300px;
}

.search-box input {
    border: 2px solid rgba(34, 46, 60, 0.15);
    border-radius: 0.5rem;
    background: rgba(255, 255, 255, 0.95);
    padding: 0.75rem 1rem 0.75rem 2.5rem;
    font-weight: 500;
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
    box-shadow: 0 2px 8px rgba(34, 46, 60, 0.1);
}

.search-box input:focus {
    border-color: #222e3c;
    background: rgba(255, 255, 255, 1);
    box-shadow: 0 0 0 0.2rem rgba(34, 46, 60, 0.25), 0 4px 12px rgba(34, 46, 60, 0.15);
    outline: none;
    transform: translateY(-1px);
}

.search-box::before {
    content: '\f002';
    font-family: 'Font Awesome 5 Free';
    font-weight: 900;
    position: absolute;
    left: 0.75rem;
    top: 50%;
    transform: translateY(-50%);
    color: rgba(34, 46, 60, 0.5);
    z-index: 1;
    pointer-events: none;
    transition: all 0.3s ease;
}

.search-box:focus-within::before {
    color: #222e3c;
}

.page-actions .form-control, .page-actions .form-select {
    border: 1px solid #ced4da;
    border-radius: 8px;
    padding: 8px 12px;
    transition: all 0.3s ease;
}

.page-actions .form-control:focus, .page-actions .form-select:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

/* Section Header Transitions */
.section-header {
    transition: all 0.3s ease;
}

/* Active Template Badge */
.badge.bg-success {
    background: linear-gradient(45deg, #10b981, #059669) !important;
    box-shadow: 0 2px 4px rgba(16, 185, 129, 0.3);
    border: none;
    font-weight: 600;
    letter-spacing: 0.025em;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { box-shadow: 0 2px 4px rgba(16, 185, 129, 0.3); }
    50% { box-shadow: 0 2px 8px rgba(16, 185, 129, 0.5); }
    100% { box-shadow: 0 2px 4px rgba(16, 185, 129, 0.3); }
}

.badge.bg-success i {
    margin-right: 4px;
}

/* Template Card States */
.template-card.active {
    border: 2px solid #10b981;
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.15);
    transform: translateY(-2px);
}

.template-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

/* Dropdown Menu Improvements */
.dropdown-menu {
    border: none;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    padding: 8px 0;
}

.dropdown-item {
    padding: 8px 16px;
    font-size: 0.875rem;
    color: #374151;
    transition: all 0.2s ease;
}

.dropdown-item:hover {
    background: #f3f4f6;
    color: #111827;
}

.dropdown-item.text-danger:hover {
    background: #fef2f2;
    color: #dc2626;
}

.dropdown-item i {
    width: 16px;
    margin-right: 8px;
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
.actions-btn i { transition: all 0.3s ease; color: #ffffff; font-size: 14px; font-weight: bold; }
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

.dropdown-item i { 
    width: 14px; 
    height: 14px; 
    margin-right: 6px; 
    font-size: 0.75rem; 
    flex-shrink: 0; 
}

[dir="rtl"] .dropdown-item i { margin-right: 0; margin-left: 6px; }
.dropdown-item.text-danger:hover { background: linear-gradient(135deg, #dc3545 0%, #c82333 100%); color: white; }

.card { position: relative; overflow: visible; }
.dropdown { position: relative; }

/* Position variations */
.dropdown-menu-up { top: auto !important; bottom: 100% !important; transform: translateY(-4px) !important; }
.dropdown-menu-end { right: 0 !important; left: auto !important; }
[dir="rtl"] .dropdown-menu-end { right: auto !important; left: 0 !important; }

/* RTL Support */
[dir="rtl"] .card-actions { right: auto; left: 12px; }
[dir="rtl"] .dropdown-item:hover { transform: translateX(-4px); }

/* ===================== Links Management Modals ===================== */
.modal-header {
    background: linear-gradient(135deg, #222e3c 0%, #2b3947 100%);
    color: white;
    border-bottom: none;
}

.modal-header .btn-close {
    filter: invert(1);
}

.modal-title i {
    color: #10b981;
}

.nav-tabs .nav-link {
    border: 1px solid rgba(34, 46, 60, 0.2);
    color: #222e3c;
    font-weight: 500;
}

.nav-tabs .nav-link.active {
    background: linear-gradient(135deg, #222e3c 0%, #2b3947 100%);
    border-color: #222e3c;
    color: white;
}

.tab-content {
    min-height: 300px;
    max-height: 400px;
    overflow-y: auto;
}

.link-item {
    background: #f8faff;
    border: 1px solid rgba(34, 46, 60, 0.1);
    border-radius: 0.5rem;
    padding: 1rem;
    margin-bottom: 0.75rem;
    transition: all 0.3s ease;
}

.link-item:hover {
    background: #f3f8ff;
    border-color: rgba(34, 46, 60, 0.2);
    transform: translateY(-1px);
}

.form-label {
    font-weight: 600;
    color: #222e3c;
    margin-bottom: 0.5rem;
}

.form-control {
    border: 1px solid rgba(34, 46, 60, 0.2);
    border-radius: 0.375rem;
    transition: all 0.3s ease;
}

.form-control:focus {
    border-color: #222e3c;
    box-shadow: 0 0 0 0.2rem rgba(34, 46, 60, 0.25);
}

.btn-outline-danger:hover {
    background-color: #dc3545;
    border-color: #dc3545;
    color: white;
}

.alert-info {
    background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
    border: 1px solid #64b5f6;
    color: #0d47a1;
}

/* RTL Support for Modals */
[dir="rtl"] .modal-body {
    text-align: right;
}

[dir="rtl"] .nav-tabs .nav-link {
    margin-left: 0;
    margin-right: 0.125rem;
}

[dir="rtl"] .btn i {
    margin-left: 0.25rem;
    margin-right: 0;
}

/* RTL Support for Filter Dropdowns */
[dir="rtl"] .page-actions .d-flex {
    flex-direction: row-reverse;
}

[dir="rtl"] .filter-dropdown-container {
    align-items: flex-end;
}

[dir="rtl"] .filter-label {
    text-align: right;
}

[dir="rtl"] .search-box::before {
    left: auto;
    right: 0.75rem;
}

[dir="rtl"] .search-box input {
    padding: 0.75rem 2.5rem 0.75rem 1rem;
}

[dir="rtl"] .btn-outline-secondary {
    order: -1;
}

@media (max-width: 768px) {
    [dir="rtl"] .page-actions .col-md-6:last-child .d-flex {
        flex-direction: row-reverse;
    }
    
    [dir="rtl"] .search-box {
        order: 1;
    }
    
    [dir="rtl"] .btn-outline-secondary {
        order: -1;
        margin-top: 1.5rem;
    }
}

/* Responsive */
@media (max-width: 768px) {
    .template-card { 
        height: 260px; 
        margin-bottom: 1rem; 
        border-radius: 0.875rem; /* Slightly smaller radius for mobile */
    }
    .card-top-section { 
        height: 100px; 
        border-radius: 0.875rem 0.875rem 0 0; /* Match mobile card radius */
    }
    .card-bottom-section { 
        height: 160px; 
        padding: 12px; 
        border-radius: 0 0 0.875rem 0.875rem; /* Match mobile card radius */
    }
    .card-top-image {
        border-radius: 0.875rem 0.875rem 0 0; /* Match mobile card radius */
    }
    .card-actions { top: 8px; right: 8px; }
    [dir="rtl"] .card-actions { right: auto; left: 8px; }
    .card-top-image img { object-fit: cover; object-position: center; }
    .card-top-image .image-overlay-text { font-size: 11px; bottom: 8px; left: 10px; right: 10px; }
    
    /* Mobile Filter Enhancements */
    .page-actions {
        padding: 1rem;
        border-radius: 0.75rem;
    }
    
    .page-actions .row {
        flex-direction: column;
        gap: 1rem;
    }
    
    .page-actions .col-md-6 {
        width: 100%;
        max-width: 100%;
    }
    
    .page-actions .d-flex {
        flex-direction: column;
        align-items: stretch !important;
        gap: 0.75rem;
    }
    
    .page-actions .col-md-6:last-child .d-flex {
        flex-direction: row;
        flex-wrap: wrap;
        justify-content: space-between;
    }
    
    .filter-dropdown-container {
        min-width: 120px;
        flex: 1;
    }
    
    .filter-dropdown {
        font-size: 0.85rem;
        padding: 0.6rem;
    }
    
    .filter-label {
        font-size: 0.7rem;
    }
    
    .search-box {
        max-width: 100%;
        order: -1;
    }
    
    .search-box input {
        padding: 0.7rem 1rem 0.7rem 2.5rem;
        font-size: 0.9rem;
    }
    
    .btn-outline-secondary {
        padding: 0.6rem;
        min-width: 45px;
        align-self: flex-end;
        margin-top: 1.5rem;
    }
    
    .modal-dialog {
        margin: 0.5rem;
    }
    
    .tab-content {
        max-height: 250px;
    }
}
</style>
@endsection

@section('content')
    <!-- Page Header -->
    <div class="template-header">
        <div class="container-fluid">
            <div class="row align-items-center justify-content-center">
                <div class="col-md-10 col-lg-8">
                    <div class="text-center">
                        <h1 class="mb-2">
                            <i class="fas fa-palette mr-2"></i>
                            {{ __('Template Management') }}
                        </h1>

                    </div>
                </div>
            </div>
        </div>
    </div>    
    
    <div class="container-fluid">
        <!-- Page Actions -->
        <div class="page-actions mb-4">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="d-flex align-items-center gap-3">
                        <button class="btn btn-primary" onclick="showCreateTemplateModal()">
                            <i class="fas fa-plus me-2"></i>{{ __('Add Custom Section') }}
                        </button>
                        <div class="search-box">
                            <input type="text" class="form-control" placeholder="{{ __('Search templates...') }}" id="globalTemplateSearch" title="Use Ctrl+K to focus, Esc to clear">
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex justify-content-end align-items-center gap-3">
                        <div class="filter-dropdown-container">
                            <label class="filter-label">{{ __('Type') }}</label>
                            <select class="form-select filter-dropdown" id="globalTemplateFilter">
                                <option value="" data-icon="fas fa-th-large">{{ __('All Templates') }}</option>
                                <option value="header" data-icon="fas fa-heading">{{ __('Headers') }}</option>
                                <option value="section" data-icon="fas fa-layer-group">{{ __('Sections') }}</option>
                                <option value="footer" data-icon="fas fa-shoe-prints">{{ __('Footers') }}</option>
                            </select>
                        </div>
                        <div class="filter-dropdown-container">
                            <label class="filter-label">{{ __('Status') }}</label>
                            <select class="form-select filter-dropdown" id="globalStatusFilter">
                                <option value="" data-icon="fas fa-list">{{ __('All Status') }}</option>
                                <option value="active" data-icon="fas fa-check-circle">{{ __('Active') }}</option>
                                <option value="inactive" data-icon="fas fa-circle">{{ __('Inactive') }}</option>
                            </select>
                        </div>
                        <button class="btn btn-outline-secondary btn-sm" onclick="clearAllFilters()" title="{{ __('Clear all filters') }}">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Header Templates Section -->
        <div class="section-header" id="header-templates">
            <h3 class="section-title">
                <i class="fas fa-heading"></i>
                {{ __('Header Templates') }}
            </h3>
        </div>

        <div class="row" id="header-templates-row">
            @foreach($headerTemplates as $template)
                <div class="col-lg-4 col-md-6">
                    <div class="template-card {{ (isset($site->active_header_id) && $site->active_header_id == $template->id) ? 'active' : '' }}">
                        <div class="card-top-section">
                            <div class="card-actions">
                                <div class="dropdown">
                                    <button class="btn actions-btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Template Actions">
                                        <span style="font-size: 14px; color: white; font-weight: bold;">⋯</span>
                                        <span class="visually-hidden">Actions</span>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            @if(isset($site->active_header_id) && $site->active_header_id == $template->id)
                                                <a class="dropdown-item text-muted disabled" href="#">
                                                    <i class="fas fa-star"></i>{{ __('Currently Active') }}
                                                </a>
                                            @else
                                                <a class="dropdown-item" href="#" onclick="selectHeaderTemplate({{ $template->id }})">
                                                    <i class="fas fa-check"></i>{{ __('Set as Active') }}
                                                </a>
                                            @endif
                                        </li>
                                        <li><a class="dropdown-item" href="#" onclick="editHeaderLinks({{ $template->id }})">
                                            <i class="fas fa-link"></i>{{ __('Edit Links') }}
                                        </a></li>
                                        <li><a class="dropdown-item" href="#" onclick="previewHeader({{ $template->id }})">
                                            <i class="fas fa-eye"></i>{{ __('Preview') }}
                                        </a></li>
                                    </ul>
                                </div>
                            </div>

                            @if($template->preview_image)
                                <div class="card-top-image">
                                    <img src="{{ $template->preview_image }}" alt="{{ $template->name }}" onerror="this.style.display='none'; this.parentElement.querySelector('.card-top-fallback').style.display='flex';" />
                                    <div class="image-overlay-text">
                                        {{ __('Header Template') }}: {{ $template->name }}
                                    </div>
                                    <div class="card-top-fallback" style="display: none;">
                                        <div class="card-top-text">{{ $template->name }}</div>
                                        <div class="card-icon"><i class="fas fa-heading"></i></div>
                                    </div>
                                </div>
                            @else
                                <div class="card-top-text">{{ $template->name }}</div>
                                <div class="card-icon"><i class="fas fa-heading"></i></div>
                            @endif
                        </div>
                        <div class="card-bottom-section">
                            @if($template->status && isset($site->active_header_id) && $site->active_header_id == $template->id)
                                <div class="mb-2">
                                    <span class="badge bg-success">
                                        <i class="fas fa-check me-1"></i>{{ __('Active Template') }}
                                    </span>
                                </div>
                            @endif
                            <div class="card-bottom-text">
                                {{ $template->description ?? __('No description available') }}
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>        <!-- Sections Templates Section -->
        <div class="section-header mt-5" id="section-templates">
            <h3 class="section-title">
                <i class="fas fa-layer-group"></i>
                {{ __('Section Templates') }}
            </h3>
        </div>

        <div class="row" id="section-templates-row">
            @foreach($sectionTemplates as $template)
                <div class="col-lg-4 col-md-6 section-template-card" data-category="{{ $template->layout_type }}" data-name="{{ strtolower($template->name) }}">
                    <div class="template-card">
                        <div class="card-top-section">
                            <div class="card-actions">
                                <div class="dropdown">
                                    <button class="btn actions-btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Template Actions">
                                        <span style="font-size: 14px; color: white; font-weight: bold;">⋯</span>
                                        <span class="visually-hidden">Actions</span>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item" href="#" onclick="addSectionToPage({{ $template->id }})">
                                            <i class="fas fa-plus"></i>{{ __('Add to Page') }}
                                        </a></li>
                                        <li><a class="dropdown-item" href="#" onclick="previewSection({{ $template->id }})">
                                            <i class="fas fa-eye"></i>{{ __('Preview') }}
                                        </a></li>
                                    </ul>
                                </div>
                            </div>

                            @if($template->preview_image)
                                <div class="card-top-image">
                                    <img src="{{ $template->preview_image }}" alt="{{ $template->name }}" onerror="this.style.display='none'; this.parentElement.querySelector('.card-top-fallback').style.display='flex';" />
                                    <div class="image-overlay-text">
                                        {{ __('Section Template') }}: {{ $template->name }}
                                    </div>
                                    <div class="card-top-fallback" style="display: none;">
                                        <div class="card-top-text">{{ $template->name }}</div>
                                        <div class="card-icon"><i class="fas fa-layer-group"></i></div>
                                    </div>
                                </div>
                            @else
                                <div class="card-top-text">{{ $template->name }}</div>
                                <div class="card-icon"><i class="fas fa-layer-group"></i></div>
                            @endif
                        </div>
                        <div class="card-bottom-section">
                            <div class="card-bottom-text">
                                <strong>{{ __('Type') }}:</strong> {{ ucfirst($template->layout_type) }}<br>
                                {{ $template->description ?? __('No description available') }}
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Footer Templates Section -->
        <div class="section-header mt-5" id="footer-templates">
            <h3 class="section-title">
                <i class="fas fa-grip-lines"></i>
                {{ __('Footer Templates') }}
            </h3>
           
        </div>

        <div class="row" id="footer-templates-row">
            @foreach($footerTemplates as $template)
                <div class="col-lg-4 col-md-6">
                    <div class="template-card {{ (isset($site->active_footer_id) && $site->active_footer_id == $template->id) ? 'active' : '' }}">
                        <div class="card-top-section">
                            <div class="card-actions">
                                <div class="dropdown">
                                    <button class="btn actions-btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Template Actions">
                                        <span style="font-size: 14px; color: white; font-weight: bold;">⋯</span>
                                        <span class="visually-hidden">Actions</span>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            @if(isset($site->active_footer_id) && $site->active_footer_id == $template->id)
                                                <a class="dropdown-item text-muted disabled" href="#">
                                                    <i class="fas fa-star"></i>{{ __('Currently Active') }}
                                                </a>
                                            @else
                                                <a class="dropdown-item" href="#" onclick="selectFooterTemplate({{ $template->id }})">
                                                    <i class="fas fa-check"></i>{{ __('Set as Active') }}
                                                </a>
                                            @endif
                                        </li>
                                        <li><a class="dropdown-item" href="#" onclick="editFooterLinks({{ $template->id }})">
                                            <i class="fas fa-link"></i>{{ __('Edit Links') }}
                                        </a></li>
                                        <li><a class="dropdown-item" href="#" onclick="previewFooter({{ $template->id }})">
                                            <i class="fas fa-eye"></i>{{ __('Preview') }}
                                        </a></li>
                                    </ul>
                                </div>
                            </div>

                            @if($template->preview_image)
                                <div class="card-top-image">
                                    <img src="{{ $template->preview_image }}" alt="{{ $template->name }}" onerror="this.style.display='none'; this.parentElement.querySelector('.card-top-fallback').style.display='flex';" />
                                    <div class="image-overlay-text">
                                        {{ __('Footer Template') }}: {{ $template->name }}
                                    </div>
                                    <div class="card-top-fallback" style="display: none;">
                                        <div class="card-top-text">{{ $template->name }}</div>
                                        <div class="card-icon"><i class="fas fa-grip-lines"></i></div>
                                    </div>
                                </div>
                            @else
                                <div class="card-top-text">{{ $template->name }}</div>
                                <div class="card-icon"><i class="fas fa-grip-lines"></i></div>
                            @endif
                        </div>
                        <div class="card-bottom-section">
                            @if($template->status && isset($site->active_footer_id) && $site->active_footer_id == $template->id)
                                <div class="mb-2">
                                    <span class="badge bg-success">
                                        <i class="fas fa-check me-1"></i>{{ __('Active Template') }}
                                    </span>
                                </div>
                            @endif
                            <div class="card-bottom-text">
                                {{ $template->description ?? __('No description available') }}
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
    </div>
</div>

<!-- Section Content Management Modal -->
<div class="modal fade" id="sectionContentModal" tabindex="-1" aria-labelledby="sectionContentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="sectionContentModalLabel">
                    <i class="fas fa-edit me-2"></i>{{ __('Edit Section Content') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    {{ __('Edit the content for this section in multiple languages. Changes will be saved automatically.') }}
                </div>
                
                <!-- Language Tabs -->
                <ul class="nav nav-tabs mb-3" id="sectionLangTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="section-en-tab" data-bs-toggle="tab" data-bs-target="#section-en" type="button" role="tab">
                            <i class="fas fa-flag-usa me-1"></i>English
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="section-ar-tab" data-bs-toggle="tab" data-bs-target="#section-ar" type="button" role="tab">
                            <i class="fas fa-flag me-1"></i>العربية
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="sectionLangContent">
                    <!-- English Tab -->
                    <div class="tab-pane fade show active" id="section-en" role="tabpanel">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label class="form-label">{{ __('Main Title') }}</label>
                                    <input type="text" class="form-control" id="sectionTitleEn" placeholder="Enter main title...">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">{{ __('Subtitle') }}</label>
                                    <input type="text" class="form-control" id="sectionSubtitleEn" placeholder="Enter subtitle...">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">{{ __('Description') }}</label>
                                    <textarea class="form-control" id="sectionDescriptionEn" rows="4" placeholder="Enter description..."></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">{{ __('Button Text') }} ({{ __('Optional') }})</label>
                                    <input type="text" class="form-control" id="sectionButtonTextEn" placeholder="e.g., Learn More">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">{{ __('Button URL') }} ({{ __('Optional') }})</label>
                                    <input type="url" class="form-control" id="sectionButtonUrlEn" placeholder="https://example.com">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">{{ __('Section Image') }} ({{ __('Optional') }})</label>
                                    <input type="file" class="form-control" id="sectionImageEn" accept="image/*">
                                    <div class="mt-2" id="sectionImagePreviewEn"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Arabic Tab -->
                    <div class="tab-pane fade" id="section-ar" role="tabpanel" dir="rtl">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label class="form-label">العنوان الرئيسي</label>
                                    <input type="text" class="form-control" id="sectionTitleAr" placeholder="أدخل العنوان الرئيسي...">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">العنوان الفرعي</label>
                                    <input type="text" class="form-control" id="sectionSubtitleAr" placeholder="أدخل العنوان الفرعي...">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">الوصف</label>
                                    <textarea class="form-control" id="sectionDescriptionAr" rows="4" placeholder="أدخل الوصف..."></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">نص الزر (اختياري)</label>
                                    <input type="text" class="form-control" id="sectionButtonTextAr" placeholder="مثال: اعرف أكثر">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">رابط الزر (اختياري)</label>
                                    <input type="url" class="form-control" id="sectionButtonUrlAr" placeholder="https://example.com">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">صورة القسم (اختياري)</label>
                                    <input type="file" class="form-control" id="sectionImageAr" accept="image/*">
                                    <div class="mt-2" id="sectionImagePreviewAr"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>{{ __('Cancel') }}
                </button>
                <button type="button" class="btn btn-primary" onclick="saveSectionContent()">
                    <i class="fas fa-save me-1"></i>{{ __('Save Content') }}
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Add Section to Page Modal -->
<div class="modal fade" id="addSectionModal" tabindex="-1" aria-labelledby="addSectionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addSectionModalLabel">
                    <i class="fas fa-plus me-2"></i>{{ __('Add Section to Page') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    {{ __('Select the page and position where you want to add this section.') }}
                </div>
                
                <div class="mb-3">
                    <label class="form-label">{{ __('Select Page') }}</label>
                    <select class="form-control" id="targetPage">
                        <option value="">{{ __('Loading pages...') }}</option>
                    </select>
                    <small class="form-text text-muted">{{ __('Select the page where you want to add this section') }}</small>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">{{ __('Section Position') }}</label>
                    <select class="form-control" id="sectionPosition">
                        <option value="1">1 - {{ __('Top of page') }}</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="-1">{{ __('Bottom of page') }}</option>
                    </select>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">{{ __('Current Page Sections') }}</label>
                    <div class="border rounded p-3 bg-light" id="currentSections">
                        <em class="text-muted">{{ __('Select a page to see current sections') }}</em>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>{{ __('Cancel') }}
                </button>
                <button type="button" class="btn btn-primary" onclick="saveAddSection()">
                    <i class="fas fa-plus me-1"></i>{{ __('Add Section') }}
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Custom Section Creation Modal -->
<div class="modal fade" id="customSectionModal" tabindex="-1" aria-labelledby="customSectionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="customSectionModalLabel">
                    <i class="fas fa-plus me-2"></i>{{ __('Create Custom Section') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    {{ __('Create a custom section with your own content, styling, and functionality.') }}
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">{{ __('Section Name') }}</label>
                            <input type="text" class="form-control" id="customSectionName" placeholder="e.g., Custom Hero Section">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">{{ __('Section Type') }}</label>
                            <select class="form-control" id="customSectionType">
                                <option value="hero">{{ __('Hero Section') }}</option>
                                <option value="content">{{ __('Content Section') }}</option>
                                <option value="gallery">{{ __('Gallery Section') }}</option>
                                <option value="testimonial">{{ __('Testimonial Section') }}</option>
                                <option value="call-to-action">{{ __('Call to Action') }}</option>
                                <option value="other">{{ __('Other') }}</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <!-- Language Tabs for Custom Content -->
                <ul class="nav nav-tabs mb-3" id="customLangTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="custom-en-tab" data-bs-toggle="tab" data-bs-target="#custom-en" type="button" role="tab">
                            <i class="fas fa-flag-usa me-1"></i>English
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="custom-ar-tab" data-bs-toggle="tab" data-bs-target="#custom-ar" type="button" role="tab">
                            <i class="fas fa-flag me-1"></i>العربية
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="custom-code-tab" data-bs-toggle="tab" data-bs-target="#custom-code" type="button" role="tab">
                            <i class="fas fa-code me-1"></i>{{ __('Custom Code') }}
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="customLangContent">
                    <!-- English Tab -->
                    <div class="tab-pane fade show active" id="custom-en" role="tabpanel">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label class="form-label">{{ __('Title') }}</label>
                                    <input type="text" class="form-control" id="customTitleEn" placeholder="Enter title...">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">{{ __('Content') }}</label>
                                    <textarea class="form-control" id="customContentEn" rows="5" placeholder="Enter content..."></textarea>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">{{ __('Image') }} ({{ __('Optional') }})</label>
                                    <input type="file" class="form-control" id="customImageEn" accept="image/*">
                                    <div class="mt-2" id="customImagePreviewEn"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Arabic Tab -->
                    <div class="tab-pane fade" id="custom-ar" role="tabpanel" dir="rtl">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label class="form-label">العنوان</label>
                                    <input type="text" class="form-control" id="customTitleAr" placeholder="أدخل العنوان...">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">المحتوى</label>
                                    <textarea class="form-control" id="customContentAr" rows="5" placeholder="أدخل المحتوى..."></textarea>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">الصورة (اختياري)</label>
                                    <input type="file" class="form-control" id="customImageAr" accept="image/*">
                                    <div class="mt-2" id="customImagePreviewAr"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Custom Code Tab -->
                    <div class="tab-pane fade" id="custom-code" role="tabpanel">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">{{ __('Custom CSS') }} ({{ __('Optional') }})</label>
                                    <textarea class="form-control font-monospace" id="customCSS" rows="6" placeholder=".custom-section { background: #f0f0f0; }"></textarea>
                                    <small class="text-muted">{{ __('Add custom styling for this section') }}</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">{{ __('Custom JavaScript') }} ({{ __('Optional') }})</label>
                                    <textarea class="form-control font-monospace" id="customJS" rows="6" placeholder="// Add custom functionality"></textarea>
                                    <small class="text-muted">{{ __('Add custom functionality for this section') }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>{{ __('Cancel') }}
                </button>
                <button type="button" class="btn btn-primary" onclick="saveCustomSection()">
                    <i class="fas fa-save me-1"></i>{{ __('Create Section') }}
                </button>
            </div>
        </div>
    </div>
</div>

@endsection<!-- Header Links Management Modal -->
<div class="modal fade" id="headerLinksModal" tabindex="-1" aria-labelledby="headerLinksModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="headerLinksModalLabel">
                    <i class="fas fa-link me-2"></i>{{ __('Edit Header Links') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    {{ __('You can add up to 5 navigation links. Links will appear in the header navigation bar.') }}
                </div>
                
                <!-- Language Tabs -->
                <ul class="nav nav-tabs mb-3" id="headerLangTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="header-en-tab" data-bs-toggle="tab" data-bs-target="#header-en" type="button" role="tab">
                            <i class="fas fa-flag-usa me-1"></i>English
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="header-ar-tab" data-bs-toggle="tab" data-bs-target="#header-ar" type="button" role="tab">
                            <i class="fas fa-flag me-1"></i>العربية
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="headerLangContent">
                    <!-- English Tab -->
                    <div class="tab-pane fade show active" id="header-en" role="tabpanel">
                        <div id="headerLinksEn">
                            <!-- Links will be dynamically added here -->
                        </div>
                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="addHeaderLink('en')" id="addHeaderLinkEn">
                            <i class="fas fa-plus me-1"></i>{{ __('Add Link') }}
                        </button>
                    </div>
                    
                    <!-- Arabic Tab -->
                    <div class="tab-pane fade" id="header-ar" role="tabpanel" dir="rtl">
                        <div id="headerLinksAr">
                            <!-- Links will be dynamically added here -->
                        </div>
                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="addHeaderLink('ar')" id="addHeaderLinkAr">
                            <i class="fas fa-plus me-1"></i>إضافة رابط
                        </button>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>{{ __('Cancel') }}
                </button>
                <button type="button" class="btn btn-primary" onclick="saveHeaderLinks()">
                    <i class="fas fa-save me-1"></i>{{ __('Save Links') }}
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Footer Links Management Modal -->
<div class="modal fade" id="footerLinksModal" tabindex="-1" aria-labelledby="footerLinksModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="footerLinksModalLabel">
                    <i class="fas fa-link me-2"></i>{{ __('Edit Footer Links') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    {{ __('You can add up to 10 footer links. Links will appear in the footer section of your website.') }}
                </div>
                
                <!-- Language Tabs -->
                <ul class="nav nav-tabs mb-3" id="footerLangTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="footer-en-tab" data-bs-toggle="tab" data-bs-target="#footer-en" type="button" role="tab">
                            <i class="fas fa-flag-usa me-1"></i>English
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="footer-ar-tab" data-bs-toggle="tab" data-bs-target="#footer-ar" type="button" role="tab">
                            <i class="fas fa-flag me-1"></i>العربية
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="footerLangContent">
                    <!-- English Tab -->
                    <div class="tab-pane fade show active" id="footer-en" role="tabpanel">
                        <div id="footerLinksEn">
                            <!-- Links will be dynamically added here -->
                        </div>
                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="addFooterLink('en')" id="addFooterLinkEn">
                            <i class="fas fa-plus me-1"></i>{{ __('Add Link') }}
                        </button>
                    </div>
                    
                    <!-- Arabic Tab -->
                    <div class="tab-pane fade" id="footer-ar" role="tabpanel" dir="rtl">
                        <div id="footerLinksAr">
                            <!-- Links will be dynamically added here -->
                        </div>
                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="addFooterLink('ar')" id="addFooterLinkAr">
                            <i class="fas fa-plus me-1"></i>إضافة رابط
                        </button>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>{{ __('Cancel') }}
                </button>
                <button type="button" class="btn btn-primary" onclick="saveFooterLinks()">
                    <i class="fas fa-save me-1"></i>{{ __('Save Links') }}
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Template Preview Modal -->
<div class="modal fade" id="templatePreviewModal" tabindex="-1" aria-labelledby="templatePreviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="templatePreviewModalLabel">{{ __('Template Preview') }}</h5>
                <div class="btn-group ms-auto me-2" role="group" aria-label="Language toggle">
                    <button type="button" class="btn btn-outline-secondary btn-sm" id="previewLangEn" onclick="switchPreviewLanguage('en')">English</button>
                    <button type="button" class="btn btn-outline-secondary btn-sm" id="previewLangAr" onclick="switchPreviewLanguage('ar')">العربية</button>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <iframe id="templatePreviewFrame" style="width: 100%; height: 600px; border: none;"></iframe>
            </div>
        </div>
    </div>
</div>

@section('js')
<script>
// ===================== Template Management JavaScript =====================

// Search and Filter Functions
document.addEventListener('DOMContentLoaded', function() {
    // Initialize dropdowns with better positioning
    setTimeout(() => {
        document.querySelectorAll('[data-bs-toggle="dropdown"]').forEach(el => {
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
            const cardRect = e.target.closest('.template-card')?.getBoundingClientRect();
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

    // Initialize search and filter functionality
    initializeSearchAndFilter();
});

// Initialize search and filter functions
function initializeSearchAndFilter() {
    const searchInput = document.getElementById('globalTemplateSearch');
    const typeFilter = document.getElementById('globalTemplateFilter');
    const statusFilter = document.getElementById('globalStatusFilter');

    if (searchInput) {
        searchInput.addEventListener('input', filterTemplates);
    }
    if (typeFilter) {
        typeFilter.addEventListener('change', filterTemplates);
    }
    if (statusFilter) {
        statusFilter.addEventListener('change', filterTemplates);
    }
}

// Filter templates based on search and filter criteria
function filterTemplates() {
    const searchTerm = document.getElementById('globalTemplateSearch').value.toLowerCase();
    const selectedType = document.getElementById('globalTemplateFilter').value;
    const selectedStatus = document.getElementById('globalStatusFilter').value;

    // Filter header templates
    filterTemplateSection('header-templates-row', searchTerm, selectedType, selectedStatus);
    
    // Filter section templates
    filterTemplateSection('section-templates-row', searchTerm, selectedType, selectedStatus);
    
    // Filter footer templates
    filterTemplateSection('footer-templates-row', searchTerm, selectedType, selectedStatus);
}

function filterTemplateSection(sectionId, searchTerm, selectedType, selectedStatus) {
    const section = document.getElementById(sectionId);
    if (!section) return;

    const cards = section.querySelectorAll('.col-lg-4, .col-md-6');
    
    cards.forEach(card => {
        const templateCard = card.querySelector('.template-card');
        const templateName = card.querySelector('.card-top-text')?.textContent.toLowerCase() || '';
        const templateDescription = card.querySelector('.card-bottom-text')?.textContent.toLowerCase() || '';
        const isActive = templateCard.classList.contains('active');
        
        let shouldShow = true;

        // Search filter
        if (searchTerm && !templateName.includes(searchTerm) && !templateDescription.includes(searchTerm)) {
            shouldShow = false;
        }

        // Type filter
        if (selectedType) {
            const sectionType = sectionId.includes('header') ? 'header' : 
                               sectionId.includes('footer') ? 'footer' : 'section';
            if (selectedType !== sectionType) {
                shouldShow = false;
            }
        }

        // Status filter
        if (selectedStatus) {
            if (selectedStatus === 'active' && !isActive) {
                shouldShow = false;
            } else if (selectedStatus === 'inactive' && isActive) {
                shouldShow = false;
            }
        }

        // Show/hide card with animation
        if (shouldShow) {
            card.style.display = 'block';
            setTimeout(() => {
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, 10);
        } else {
            card.style.opacity = '0';
            card.style.transform = 'translateY(-20px)';
            setTimeout(() => {
                card.style.display = 'none';
            }, 300);
        }
    });
}

// Clear all filters
function clearAllFilters() {
    document.getElementById('globalTemplateSearch').value = '';
    document.getElementById('globalTemplateFilter').value = '';
    document.getElementById('globalStatusFilter').value = '';
    filterTemplates();
}

// Show alert function
function showAlert(type, message) {
    // Create alert element
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type === 'error' ? 'danger' : type} alert-dismissible fade show`;
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    // Add to page
    const container = document.querySelector('.container-fluid');
    container.insertBefore(alertDiv, container.firstChild);
    
    // Auto dismiss after 5 seconds
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 5000);
}

// Header Template Functions
function selectHeaderTemplate(templateId) {
    showAlert('info', '{{ __("Selecting header template...") }}');
    
    // Make AJAX call to update header template
    fetch('/admin/templates/header', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ template_id: templateId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('success', '{{ __("Header template selected successfully") }}');
            // Update UI to show selected template
            updateHeaderSelection(templateId);
        } else {
            showAlert('error', data.message || '{{ __("Failed to select header template") }}');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('error', '{{ __("An error occurred while selecting header template") }}');
    });
}

function editHeaderLinks(templateId) {
    // Store current template ID
    window.currentHeaderTemplateId = templateId;
    
    // Load existing links
    loadHeaderLinks(templateId);
    
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('headerLinksModal'));
    modal.show();
}

function previewHeader(templateId) {
    // Open header preview in existing route pattern
    const previewUrl = `/admin/templates/header/${templateId}/preview`;
    window.open(previewUrl, '_blank');
}

// ===================== Header Links Management =====================
let headerLinksData = { en: [], ar: [] };

function loadHeaderLinks(templateId) {
    // Fetch existing links from server
    fetch(`/admin/templates/header/${templateId}/links`)
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            headerLinksData = data.links || { en: [], ar: [] };
            renderHeaderLinks();
        } else {
            // Initialize with empty data
            headerLinksData = { en: [], ar: [] };
            renderHeaderLinks();
        }
    })
    .catch(error => {
        console.error('Error loading header links:', error);
        headerLinksData = { en: [], ar: [] };
        renderHeaderLinks();
    });
}

function renderHeaderLinks() {
    // Render English links
    const enContainer = document.getElementById('headerLinksEn');
    enContainer.innerHTML = '';
    headerLinksData.en.forEach((link, index) => {
        enContainer.appendChild(createLinkElement(link, index, 'en', 'header'));
    });
    
    // Render Arabic links
    const arContainer = document.getElementById('headerLinksAr');
    arContainer.innerHTML = '';
    headerLinksData.ar.forEach((link, index) => {
        arContainer.appendChild(createLinkElement(link, index, 'ar', 'header'));
    });
    
    // Update add button states
    updateAddButtonState('header', 'en', headerLinksData.en.length);
    updateAddButtonState('header', 'ar', headerLinksData.ar.length);
}

function createLinkElement(link, index, lang, type) {
    const div = document.createElement('div');
    div.className = 'link-item';
    div.innerHTML = `
        <div class="row align-items-center">
            <div class="col-md-4">
                <label class="form-label">${lang === 'en' ? 'Link Text' : 'نص الرابط'}</label>
                <input type="text" class="form-control" value="${link.label || ''}" 
                       onchange="updateLinkData('${type}', '${lang}', ${index}, 'label', this.value)"
                       placeholder="${lang === 'en' ? 'e.g., Home' : 'مثال: الصفحة الرئيسية'}">
            </div>
            <div class="col-md-6">
                <label class="form-label">${lang === 'en' ? 'URL' : 'الرابط'}</label>
                <input type="url" class="form-control" value="${link.url || ''}" 
                       onchange="updateLinkData('${type}', '${lang}', ${index}, 'url', this.value)"
                       placeholder="${lang === 'en' ? 'https://example.com' : 'https://example.com'}">
            </div>
            <div class="col-md-2 text-end">
                <label class="form-label">&nbsp;</label>
                <div>
                    <button type="button" class="btn btn-outline-danger btn-sm" 
                            onclick="removeLinkData('${type}', '${lang}', ${index})" 
                            title="${lang === 'en' ? 'Remove Link' : 'حذف الرابط'}">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    `;
    return div;
}

function addHeaderLink(lang) {
    if (headerLinksData[lang].length >= 5) {
        showAlert('warning', lang === 'en' ? 'Maximum 5 header links allowed' : 'الحد الأقصى 5 روابط للناف');
        return;
    }
    
    headerLinksData[lang].push({ label: '', url: '' });
    renderHeaderLinks();
}

function updateLinkData(type, lang, index, field, value) {
    if (type === 'header') {
        headerLinksData[lang][index][field] = value;
    } else if (type === 'footer') {
        footerLinksData[lang][index][field] = value;
    }
}

function removeLinkData(type, lang, index) {
    if (type === 'header') {
        headerLinksData[lang].splice(index, 1);
        renderHeaderLinks();
    } else if (type === 'footer') {
        footerLinksData[lang].splice(index, 1);
        renderFooterLinks();
    }
}

function updateAddButtonState(type, lang, count) {
    const maxCount = type === 'header' ? 5 : 10;
    const buttonId = type === 'header' ? `addHeaderLink${lang.charAt(0).toUpperCase() + lang.slice(1)}` : `addFooterLink${lang.charAt(0).toUpperCase() + lang.slice(1)}`;
    const button = document.getElementById(buttonId);
    
    if (button) {
        if (count >= maxCount) {
            button.disabled = true;
            button.innerHTML = `<i class="fas fa-ban me-1"></i>${lang === 'en' ? 'Maximum reached' : 'الحد الأقصى'}`;
        } else {
            button.disabled = false;
            button.innerHTML = `<i class="fas fa-plus me-1"></i>${lang === 'en' ? 'Add Link' : 'إضافة رابط'}`;
        }
    }
}

function saveHeaderLinks() {
    // Validate links
    const allLinks = [...headerLinksData.en, ...headerLinksData.ar];
    const invalidLinks = allLinks.filter(link => !link.label.trim() || !link.url.trim());
    
    if (invalidLinks.length > 0) {
        showAlert('warning', '{{ __("Please fill in all link text and URL fields") }}');
        return;
    }
    
    showAlert('info', '{{ __("Saving header links...") }}');
    
    // Save to server
    fetch(`/admin/templates/header/${window.currentHeaderTemplateId}/links`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ links: headerLinksData })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('success', '{{ __("Header links saved successfully") }}');
            // Close modal
            bootstrap.Modal.getInstance(document.getElementById('headerLinksModal')).hide();
        } else {
            showAlert('error', data.message || '{{ __("Failed to save header links") }}');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('error', '{{ __("An error occurred while saving header links") }}');
    });
}

// ===================== Footer Links Management =====================
let footerLinksData = { en: [], ar: [] };

function loadFooterLinks(templateId) {
    // Fetch existing links from server
    fetch(`/admin/templates/footer/${templateId}/links`)
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            footerLinksData = data.links || { en: [], ar: [] };
            renderFooterLinks();
        } else {
            // Initialize with empty data
            footerLinksData = { en: [], ar: [] };
            renderFooterLinks();
        }
    })
    .catch(error => {
        console.error('Error loading footer links:', error);
        footerLinksData = { en: [], ar: [] };
        renderFooterLinks();
    });
}

function renderFooterLinks() {
    // Render English links
    const enContainer = document.getElementById('footerLinksEn');
    enContainer.innerHTML = '';
    footerLinksData.en.forEach((link, index) => {
        enContainer.appendChild(createLinkElement(link, index, 'en', 'footer'));
    });
    
    // Render Arabic links
    const arContainer = document.getElementById('footerLinksAr');
    arContainer.innerHTML = '';
    footerLinksData.ar.forEach((link, index) => {
        arContainer.appendChild(createLinkElement(link, index, 'ar', 'footer'));
    });
    
    // Update add button states
    updateAddButtonState('footer', 'en', footerLinksData.en.length);
    updateAddButtonState('footer', 'ar', footerLinksData.ar.length);
}

function addFooterLink(lang) {
    if (footerLinksData[lang].length >= 10) {
        showAlert('warning', lang === 'en' ? 'Maximum 10 footer links allowed' : 'الحد الأقصى 10 روابط للفوتر');
        return;
    }
    
    footerLinksData[lang].push({ label: '', url: '' });
    renderFooterLinks();
}

function saveFooterLinks() {
    // Validate links
    const allLinks = [...footerLinksData.en, ...footerLinksData.ar];
    const invalidLinks = allLinks.filter(link => !link.label.trim() || !link.url.trim());
    
    if (invalidLinks.length > 0) {
        showAlert('warning', '{{ __("Please fill in all link text and URL fields") }}');
        return;
    }
    
    showAlert('info', '{{ __("Saving footer links...") }}');
    
    // Save to server
    fetch(`/admin/templates/footer/${window.currentFooterTemplateId}/links`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ links: footerLinksData })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('success', '{{ __("Footer links saved successfully") }}');
            // Close modal
            bootstrap.Modal.getInstance(document.getElementById('footerLinksModal')).hide();
        } else {
            showAlert('error', data.message || '{{ __("Failed to save footer links") }}');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('error', '{{ __("An error occurred while saving footer links") }}');
    });
}

// Section Template Functions
function addSectionToPage(sectionId) {
    // Store current section ID
    window.currentSectionId = sectionId;
    
    // Load available pages from database
    loadAvailablePages();
    
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('addSectionModal'));
    modal.show();
    
    // Load pages when page dropdown changes
    document.getElementById('targetPage').addEventListener('change', function() {
        loadCurrentSections(this.value);
    });
}

function editSectionContent(sectionId) {
    // Store current section ID
    window.currentSectionId = sectionId;
    
    // Load existing content
    loadSectionContent(sectionId);
    
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('sectionContentModal'));
    modal.show();
}

    function previewSection(sectionId) {
        const url = `{{ route('admin.templates.section.preview.view', ':id') }}`.replace(':id', sectionId);
        window.open(url, '_blank');
    }function createCustomSection() {
    // Reset form
    resetCustomSectionForm();
    
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('customSectionModal'));
    modal.show();
}

// ===================== Section Content Management =====================
function loadSectionContent(sectionId) {
    // Load existing section content from server
    fetch(`/admin/templates/section/${sectionId}/content`)
    .then(response => response.json())
    .then(data => {
        if (data.success && data.content) {
            // Populate English fields
            document.getElementById('sectionTitleEn').value = data.content.en?.title || '';
            document.getElementById('sectionSubtitleEn').value = data.content.en?.subtitle || '';
            document.getElementById('sectionDescriptionEn').value = data.content.en?.description || '';
            document.getElementById('sectionButtonTextEn').value = data.content.en?.button_text || '';
            document.getElementById('sectionButtonUrlEn').value = data.content.en?.button_url || '';
            
            // Populate Arabic fields
            document.getElementById('sectionTitleAr').value = data.content.ar?.title || '';
            document.getElementById('sectionSubtitleAr').value = data.content.ar?.subtitle || '';
            document.getElementById('sectionDescriptionAr').value = data.content.ar?.description || '';
            document.getElementById('sectionButtonTextAr').value = data.content.ar?.button_text || '';
            document.getElementById('sectionButtonUrlAr').value = data.content.ar?.button_url || '';
            
            // Show existing images if any
            if (data.content.image) {
                showImagePreview('sectionImagePreviewEn', data.content.image);
                showImagePreview('sectionImagePreviewAr', data.content.image);
            }
        }
    })
    .catch(error => {
        console.error('Error loading section content:', error);
        showAlert('error', '{{ __("Failed to load section content") }}');
    });
}

function saveSectionContent() {
    const contentData = {
        en: {
            title: document.getElementById('sectionTitleEn').value,
            subtitle: document.getElementById('sectionSubtitleEn').value,
            description: document.getElementById('sectionDescriptionEn').value,
            button_text: document.getElementById('sectionButtonTextEn').value,
            button_url: document.getElementById('sectionButtonUrlEn').value
        },
        ar: {
            title: document.getElementById('sectionTitleAr').value,
            subtitle: document.getElementById('sectionSubtitleAr').value,
            description: document.getElementById('sectionDescriptionAr').value,
            button_text: document.getElementById('sectionButtonTextAr').value,
            button_url: document.getElementById('sectionButtonUrlAr').value
        }
    };

    // Handle image uploads
    const formData = new FormData();
    formData.append('content', JSON.stringify(contentData));
    
    const imageEn = document.getElementById('sectionImageEn').files[0];
    const imageAr = document.getElementById('sectionImageAr').files[0];
    
    if (imageEn) formData.append('image_en', imageEn);
    if (imageAr) formData.append('image_ar', imageAr);

    showAlert('info', '{{ __("Saving section content...") }}');

    fetch(`/admin/templates/section/${window.currentSectionId}/content`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('success', '{{ __("Section content saved successfully") }}');
            bootstrap.Modal.getInstance(document.getElementById('sectionContentModal')).hide();
        } else {
            showAlert('error', data.message || '{{ __("Failed to save section content") }}');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('error', '{{ __("An error occurred while saving section content") }}');
    });
}

// ===================== Add Section to Page =====================
function loadAvailablePages() {
    const pageSelect = document.getElementById('targetPage');
    
    // Show loading state
    pageSelect.innerHTML = '<option value="">{{ __("Loading pages...") }}</option>';
    pageSelect.disabled = true;
    
    console.log('Loading pages from API...');
    
    // Fetch pages from server
    fetch('/admin/pages/list', {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => {
        console.log('API Response Status:', response.status);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('API Response Data:', data);
        if (data.success && data.pages) {
            // Clear loading option
            pageSelect.innerHTML = '<option value="">{{ __("Choose a page...") }}</option>';
            
            // Add pages to dropdown
            data.pages.forEach(page => {
                const option = document.createElement('option');
                option.value = page.id;
                option.textContent = page.title || page.name;
                option.setAttribute('data-slug', page.slug);
                pageSelect.appendChild(option);
            });
            
            pageSelect.disabled = false;
            console.log(`Loaded ${data.pages.length} pages successfully`);
        } else {
            console.warn('API response success was false or no pages:', data);
            // Fallback to default pages if API fails
            pageSelect.innerHTML = `
                <option value="">{{ __("Choose a page...") }}</option>
                <option value="home">{{ __("Home Page") }}</option>
                <option value="about">{{ __("About Page") }}</option>
                <option value="services">{{ __("Services Page") }}</option>
                <option value="contact">{{ __("Contact Page") }}</option>
            `;
            pageSelect.disabled = false;
            console.warn('Using fallback pages. API response:', data);
        }
    })
    .catch(error => {
        console.error('Error loading pages:', error);
        
        // Fallback to default pages on error
        pageSelect.innerHTML = `
            <option value="">{{ __("Choose a page...") }}</option>
            <option value="home">{{ __("Home Page") }}</option>
            <option value="about">{{ __("About Page") }}</option>
            <option value="services">{{ __("Services Page") }}</option>
            <option value="contact">{{ __("Contact Page") }}</option>
        `;
        pageSelect.disabled = false;
        
        showAlert('warning', '{{ __("Could not load pages from database. Using default pages.") }}');
    });
}

function loadCurrentSections(pageId) {
    if (!pageId) {
        document.getElementById('currentSections').innerHTML = '<em class="text-muted">{{ __("Select a page to see current sections") }}</em>';
        return;
    }

    fetch(`/admin/templates/page/${pageId}/sections`)
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            let html = '<strong>{{ __("Current sections:") }}</strong><br>';
            if (data.sections && data.sections.length > 0) {
                data.sections.forEach((section, index) => {
                    html += `<small class="d-block">${index + 1}. ${section.name}</small>`;
                });
            } else {
                html += '<small class="text-muted">{{ __("No sections added yet") }}</small>';
            }
            document.getElementById('currentSections').innerHTML = html;
        }
    })
    .catch(error => {
        console.error('Error loading sections:', error);
        document.getElementById('currentSections').innerHTML = '<small class="text-danger">{{ __("Error loading sections") }}</small>';
    });
}

function saveAddSection() {
    const pageId = document.getElementById('targetPage').value;
    const position = document.getElementById('sectionPosition').value;
    
    if (!pageId) {
        showAlert('warning', '{{ __("Please select a page") }}');
        return;
    }

    showAlert('info', '{{ __("Adding section to page...") }}');

    fetch(`/admin/templates/section/${window.currentSectionId}/add-to-page`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            page_id: pageId,
            sort_order: position
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('success', '{{ __("Section added to page successfully") }}');
            bootstrap.Modal.getInstance(document.getElementById('addSectionModal')).hide();
        } else {
            showAlert('error', data.message || '{{ __("Failed to add section to page") }}');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('error', '{{ __("An error occurred while adding section to page") }}');
    });
}

// ===================== Custom Section Creation =====================
function resetCustomSectionForm() {
    document.getElementById('customSectionName').value = '';
    document.getElementById('customSectionType').value = 'hero';
    document.getElementById('customTitleEn').value = '';
    document.getElementById('customContentEn').value = '';
    document.getElementById('customTitleAr').value = '';
    document.getElementById('customContentAr').value = '';
    document.getElementById('customCSS').value = '';
    document.getElementById('customJS').value = '';
    
    // Clear image previews
    document.getElementById('customImagePreviewEn').innerHTML = '';
    document.getElementById('customImagePreviewAr').innerHTML = '';
    
    // Reset file inputs
    document.getElementById('customImageEn').value = '';
    document.getElementById('customImageAr').value = '';
}

function saveCustomSection() {
    const name = document.getElementById('customSectionName').value;
    const type = document.getElementById('customSectionType').value;
    
    if (!name.trim()) {
        showAlert('warning', '{{ __("Please enter a section name") }}');
        return;
    }

    const contentData = {
        name: name,
        type: type,
        content: {
            en: {
                title: document.getElementById('customTitleEn').value,
                content: document.getElementById('customContentEn').value
            },
            ar: {
                title: document.getElementById('customTitleAr').value,
                content: document.getElementById('customContentAr').value
            }
        },
        styles: document.getElementById('customCSS').value,
        scripts: document.getElementById('customJS').value
    };

    const formData = new FormData();
    formData.append('data', JSON.stringify(contentData));
    
    const imageEn = document.getElementById('customImageEn').files[0];
    const imageAr = document.getElementById('customImageAr').files[0];
    
    if (imageEn) formData.append('image_en', imageEn);
    if (imageAr) formData.append('image_ar', imageAr);

    showAlert('info', '{{ __("Creating custom section...") }}');

    fetch('/admin/templates/section/custom', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('success', '{{ __("Custom section created successfully") }}');
            bootstrap.Modal.getInstance(document.getElementById('customSectionModal')).hide();
            // Reload page to show new section
            setTimeout(() => location.reload(), 1500);
        } else {
            showAlert('error', data.message || '{{ __("Failed to create custom section") }}');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('error', '{{ __("An error occurred while creating custom section") }}');
    });
}

// ===================== Utility Functions =====================
function showImagePreview(containerId, imageUrl) {
    const container = document.getElementById(containerId);
    if (container && imageUrl) {
        container.innerHTML = `
            <div class="image-preview">
                <img src="${imageUrl}" alt="Preview" style="max-width: 100%; max-height: 150px; border-radius: 0.375rem; border: 1px solid #dee2e6;">
            </div>
        `;
    }
}

// Handle image preview for file inputs
document.addEventListener('DOMContentLoaded', function() {
    // Section image previews
    ['sectionImageEn', 'sectionImageAr', 'customImageEn', 'customImageAr'].forEach(inputId => {
        const input = document.getElementById(inputId);
        if (input) {
            input.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const previewId = inputId.replace('Image', 'ImagePreview');
                        showImagePreview(previewId, e.target.result);
                    };
                    reader.readAsDataURL(file);
                }
            });
        }
    });
});

// Footer Template Functions
function selectFooterTemplate(templateId) {
    showAlert('info', '{{ __("Selecting footer template...") }}');
    
    // Make AJAX call to update footer template
    fetch('/admin/templates/footer', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ template_id: templateId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('success', '{{ __("Footer template selected successfully") }}');
            // Update UI to show selected template
            updateFooterSelection(templateId);
        } else {
            showAlert('error', data.message || '{{ __("Failed to select footer template") }}');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('error', '{{ __("An error occurred while selecting footer template") }}');
    });
}

function editFooterLinks(templateId) {
    // Store current template ID
    window.currentFooterTemplateId = templateId;
    
    // Load existing links
    loadFooterLinks(templateId);
    
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('footerLinksModal'));
    modal.show();
}

function previewFooter(templateId) {
    // Open footer preview in existing route pattern
    const previewUrl = `/admin/templates/footer/${templateId}/preview`;
    window.open(previewUrl, '_blank');
}

function previewTemplate(type, templateId) {
    loadTemplatePreview(type, templateId);
}

// Template Preview Functions
function loadTemplatePreview(type, templateId) {
    const modal = new bootstrap.Modal(document.getElementById('templatePreviewModal'));
    const iframe = document.getElementById('templatePreviewFrame');
    const modalTitle = document.getElementById('templatePreviewModalLabel');
    
    // Set modal title
    let title = '';
    switch(type) {
        case 'header': title = '{{ __("Header Template Preview") }}'; break;
        case 'section': title = '{{ __("Section Template Preview") }}'; break;
        case 'footer': title = '{{ __("Footer Template Preview") }}'; break;
        default: title = '{{ __("Template Preview") }}';
    }
    modalTitle.textContent = title;
    
    // Load preview URL
    const previewUrl = `/admin/templates/${type}/${templateId}/preview`;
    iframe.src = previewUrl;
    
    // Show modal
    modal.show();
}

function switchPreviewLanguage(lang) {
    const iframe = document.getElementById('templatePreviewFrame');
    const currentUrl = iframe.src;
    
    // Update URL with language parameter
    const url = new URL(currentUrl);
    url.searchParams.set('lang', lang);
    iframe.src = url.toString();
    
    // Update button states
    document.getElementById('previewLangEn').classList.toggle('active', lang === 'en');
    document.getElementById('previewLangAr').classList.toggle('active', lang === 'ar');
}

// Utility Functions
function updateHeaderSelection(templateId) {
    // Remove active class from all header cards
    document.querySelectorAll('.template-card.active').forEach(card => {
        if (card.closest('.row').previousElementSibling.textContent.includes('Header')) {
            card.classList.remove('active');
        }
    });
    
    // Add active class to selected template
    // Implementation would depend on how you identify the cards
}

function updateFooterSelection(templateId) {
    // Similar to updateHeaderSelection but for footer
}

function deleteTemplate(templateId, type) {
    if (confirm('{{ __("Are you sure you want to delete this template? This action cannot be undone.") }}')) {
        fetch(`/admin/templates/${templateId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('success', '{{ __("Template deleted successfully") }}');
                // Remove the template card from the page
                const templateCard = document.querySelector(`[onclick*="${templateId}"]`).closest('.col-lg-4, .col-md-6');
                if (templateCard) {
                    templateCard.remove();
                }
                location.reload(); // Refresh to update the grid
            } else {
                showAlert('error', data.message || '{{ __("Failed to delete template") }}');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('error', '{{ __("An error occurred while deleting template") }}');
        });
    }
}

function showAlert(type, message, duration = 3000) {
    const iconMap = {
        'success': '<i class="fas fa-check me-1"></i>',
        'error': '<i class="fas fa-times me-1"></i>',
        'warning': '<i class="fas fa-exclamation-triangle me-1"></i>',
        'info': '<i class="fas fa-info-circle me-1"></i>'
    };
    const cls = type === 'success' ? 'alert-success' : type === 'error' ? 'alert-danger' : type === 'warning' ? 'alert-warning' : 'alert-info';
    const icon = iconMap[type] || iconMap['info'];
    const html = `<div class="alert ${cls} alert-dismissible fade show" role="alert">${icon} ${message}<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>`;
    
    document.querySelectorAll('.alert').forEach(a => a.remove());
    document.querySelector('.container-fluid').insertAdjacentHTML('afterbegin', html);
    
    setTimeout(() => {
        document.querySelectorAll('.alert').forEach(a => { 
            a.classList.remove('show'); 
            setTimeout(() => a.remove(), 150); 
        });
    }, duration);
}

// Create Template Modal Function
function showCreateTemplateModal() {
    $('#createTemplateModal').modal('show');
}

// Search and Filter Functionality
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('globalTemplateSearch');
    const typeFilter = document.getElementById('globalTemplateFilter');
    const statusFilter = document.getElementById('globalStatusFilter');

    // Add search feedback
    let searchTimeout;
    
    // Search functionality with debounce
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            
            // Add searching visual feedback
            searchInput.style.borderColor = '#ffc107';
            
            searchTimeout = setTimeout(() => {
                filterTemplates();
                searchInput.style.borderColor = '#ced4da';
            }, 300);
        });
    }

    // Filter functionality
    if (typeFilter) {
        typeFilter.addEventListener('change', function() {
            // Add visual feedback
            typeFilter.style.borderColor = '#28a745';
            filterTemplates();
            setTimeout(() => {
                typeFilter.style.borderColor = '#ced4da';
            }, 500);
        });
    }

    if (statusFilter) {
        statusFilter.addEventListener('change', function() {
            // Add visual feedback
            statusFilter.style.borderColor = '#28a745';
            filterTemplates();
            setTimeout(() => {
                statusFilter.style.borderColor = '#ced4da';
            }, 500);
        });
    }

    function filterTemplates() {
        const searchTerm = searchInput ? searchInput.value.toLowerCase() : '';
        const selectedType = typeFilter ? typeFilter.value : '';
        const selectedStatus = statusFilter ? statusFilter.value : '';

        console.log('Filter triggered:', {
            searchTerm,
            selectedType,
            selectedStatus
        });

        // Show loading state
        showFilteringState(true);

        // Filter header templates - find them by the parent section
        const headerSection = document.getElementById('header-templates');
        const headerCards = headerSection ? headerSection.nextElementSibling.querySelectorAll('.col-lg-4, .col-md-6') : [];
        console.log('Header section found:', headerSection, 'Cards:', headerCards.length);
        filterCardsBySection(headerCards, searchTerm, selectedType, selectedStatus, 'header');

        // Filter section templates
        const sectionSection = document.getElementById('section-templates');
        const sectionCards = sectionSection ? sectionSection.nextElementSibling.querySelectorAll('.col-lg-4, .col-md-6') : [];
        console.log('Section section found:', sectionSection, 'Cards:', sectionCards.length);
        filterCardsBySection(sectionCards, searchTerm, selectedType, selectedStatus, 'section');

        // Filter footer templates
        const footerSection = document.getElementById('footer-templates');
        const footerCards = footerSection ? footerSection.nextElementSibling.querySelectorAll('.col-lg-4, .col-md-6') : [];
        console.log('Footer section found:', footerSection, 'Cards:', footerCards.length);
        filterCardsBySection(footerCards, searchTerm, selectedType, selectedStatus, 'footer');

        // Show/hide sections based on visible cards
        toggleSectionVisibility('header-templates', headerCards);
        toggleSectionVisibility('section-templates', sectionCards);
        toggleSectionVisibility('footer-templates', footerCards);

        // Update results count
        updateResultsCount(headerCards, sectionCards, footerCards);
        
        // Hide loading state
        setTimeout(() => showFilteringState(false), 200);
    }

    function showFilteringState(isFiltering) {
        const pageActions = document.querySelector('.page-actions');
        if (pageActions) {
            if (isFiltering) {
                pageActions.style.opacity = '0.8';
                pageActions.style.transform = 'scale(0.99)';
            } else {
                pageActions.style.opacity = '1';
                pageActions.style.transform = 'scale(1)';
            }
        }
    }

    function updateResultsCount(headerCards, sectionCards, footerCards) {
        const headerCount = Array.from(headerCards).filter(card => card.style.display !== 'none').length;
        const sectionCount = Array.from(sectionCards).filter(card => card.style.display !== 'none').length;
        const footerCount = Array.from(footerCards).filter(card => card.style.display !== 'none').length;
        const totalCount = headerCount + sectionCount + footerCount;

        // Update or create results indicator
        let resultsIndicator = document.getElementById('results-indicator');
        if (!resultsIndicator) {
            resultsIndicator = document.createElement('div');
            resultsIndicator.id = 'results-indicator';
            resultsIndicator.className = 'alert alert-info mt-3 text-center';
            resultsIndicator.style.cssText = 'margin: 20px 0; padding: 10px; border-radius: 8px; font-weight: 500;';
            
            const container = document.querySelector('.container-fluid');
            const pageActions = container.querySelector('.page-actions');
            if (pageActions) {
                pageActions.parentNode.insertBefore(resultsIndicator, pageActions.nextSibling);
            }
        }

        if ((searchInput && searchInput.value) || (typeFilter && typeFilter.value) || (statusFilter && statusFilter.value)) {
            resultsIndicator.innerHTML = `
                <i class="fas fa-search me-2"></i>
                Found ${totalCount} templates 
                (${headerCount} headers, ${sectionCount} sections, ${footerCount} footers)
            `;
            resultsIndicator.style.display = 'block';
        } else {
            resultsIndicator.style.display = 'none';
        }
    }

    function filterCardsBySection(cards, searchTerm, selectedType, selectedStatus, cardType) {
        console.log(`Filtering ${cardType} templates:`, {
            cardsFound: cards.length,
            searchTerm,
            selectedType,
            selectedStatus
        });

        cards.forEach((card, index) => {
            const templateCard = card.querySelector('.template-card');
            const cardTitle = card.querySelector('.card-top-text, .image-overlay-text');
            const cardDesc = card.querySelector('.card-bottom-text');
            const activeBadge = card.querySelector('.badge.bg-success');
            
            const titleText = cardTitle ? cardTitle.textContent.toLowerCase() : '';
            const descText = cardDesc ? cardDesc.textContent.toLowerCase() : '';
            const isActive = activeBadge ? true : false;
            
            console.log(`Card ${index}:`, {
                titleText,
                descText,
                isActive,
                cardType
            });
            
            // Check search term
            const matchesSearch = !searchTerm || 
                titleText.includes(searchTerm) || 
                descText.includes(searchTerm);
            
            // Check type filter
            const matchesType = !selectedType || selectedType === cardType;
            
            // Check status filter
            const matchesStatus = !selectedStatus || 
                (selectedStatus === 'active' && isActive) || 
                (selectedStatus === 'inactive' && !isActive);
            
            const shouldShow = matchesSearch && matchesType && matchesStatus;
            
            console.log(`Card ${index} matches:`, {
                matchesSearch,
                matchesType,
                matchesStatus,
                shouldShow
            });
            
            // Show/hide card
            if (shouldShow) {
                card.style.display = 'block';
                card.style.opacity = '1';
                card.style.transform = 'scale(1)';
            } else {
                card.style.display = 'none';
                card.style.opacity = '0';
                card.style.transform = 'scale(0.95)';
            }
        });
    }

    function toggleSectionVisibility(sectionId, cards) {
        const section = document.getElementById(sectionId);
        if (!section) return;

        const visibleCards = Array.from(cards).some(card => 
            card.style.display !== 'none' && 
            window.getComputedStyle(card).display !== 'none'
        );

        if (visibleCards) {
            section.style.display = 'block';
            section.style.opacity = '1';
        } else {
            section.style.display = 'none';
            section.style.opacity = '0.5';
        }
    }

    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Ctrl/Cmd + K to focus search
        if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
            e.preventDefault();
            if (searchInput) {
                searchInput.focus();
                searchInput.select();
            }
        }
        
        // Ctrl/Cmd + T to focus type filter
        if ((e.ctrlKey || e.metaKey) && e.key === 't') {
            e.preventDefault();
            if (typeFilter) {
                typeFilter.focus();
            }
        }
        
        // Ctrl/Cmd + S to focus status filter
        if ((e.ctrlKey || e.metaKey) && e.key === 's') {
            e.preventDefault();
            if (statusFilter) {
                statusFilter.focus();
            }
        }
        
        // Ctrl/Cmd + R to clear all filters
        if ((e.ctrlKey || e.metaKey) && e.key === 'r') {
            e.preventDefault();
            clearAllFilters();
        }
        
        // Escape to clear search and filters
        if (e.key === 'Escape') {
            if (searchInput && searchInput.value) {
                searchInput.value = '';
                filterTemplates();
                searchInput.blur();
            } else if (typeFilter && typeFilter.value) {
                typeFilter.value = '';
                filterTemplates();
                typeFilter.blur();
            } else if (statusFilter && statusFilter.value) {
                statusFilter.value = '';
                filterTemplates();
                statusFilter.blur();
            }
        }
    });

    // Initialize with no filters
    filterTemplates();
});

// Clear All Filters Function
function clearAllFilters() {
    const searchInput = document.getElementById('globalTemplateSearch');
    const typeFilter = document.getElementById('globalTemplateFilter');
    const statusFilter = document.getElementById('globalStatusFilter');
    
    // Clear all filter values
    if (searchInput) searchInput.value = '';
    if (typeFilter) typeFilter.value = '';
    if (statusFilter) statusFilter.value = '';
    
    // Add visual feedback
    const clearBtn = event.target.closest('button');
    const originalText = clearBtn.innerHTML;
    clearBtn.innerHTML = '<i class="fas fa-check"></i>';
    clearBtn.classList.add('btn-success');
    clearBtn.classList.remove('btn-outline-secondary');
    
    // Reset button after animation
    setTimeout(() => {
        clearBtn.innerHTML = originalText;
        clearBtn.classList.remove('btn-success');
        clearBtn.classList.add('btn-outline-secondary');
    }, 1000);
    
    // Refilter templates
    filterTemplates();
    
    // Show success message
    showAlert('success', '{{ __("All filters cleared") }}', 2000);
}

// Enhanced Filter Dropdown Initialization
document.addEventListener('DOMContentLoaded', function() {
    // Add icons to filter options
    const addIconsToSelect = (selectElement) => {
        if (!selectElement) return;
        
        const options = selectElement.querySelectorAll('option');
        options.forEach(option => {
            const icon = option.getAttribute('data-icon');
            if (icon) {
                option.style.backgroundImage = `url("data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'><text y='18' font-size='18'>📁</text></svg>")`;
                option.style.backgroundRepeat = 'no-repeat';
                option.style.backgroundPosition = '8px center';
                option.style.paddingLeft = '32px';
            }
        });
    };
    
    // Apply icons to filter dropdowns
    addIconsToSelect(document.getElementById('globalTemplateFilter'));
    addIconsToSelect(document.getElementById('globalStatusFilter'));
    
    // Add filter change animations
    const filterDropdowns = document.querySelectorAll('.filter-dropdown');
    filterDropdowns.forEach(dropdown => {
        dropdown.addEventListener('change', function() {
            this.style.transform = 'scale(1.05)';
            this.style.borderColor = '#28a745';
            
            setTimeout(() => {
                this.style.transform = 'scale(1)';
                this.style.borderColor = 'rgba(34, 46, 60, 0.15)';
            }, 200);
        });
    });
});

// Handle template creation form
function createNewTemplate() {
    const form = document.getElementById('createTemplateForm');
    const formData = new FormData(form);
    
    // Show loading state
    const submitBtn = document.getElementById('createTemplateBtn');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Creating...';
    submitBtn.disabled = true;
    
    fetch('/admin/templates', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            $('#createTemplateModal').modal('hide');
            showAlert('success', data.message);
            location.reload(); // Refresh to show new template
        } else {
            showAlert('error', data.message || 'Error creating template');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('error', 'Error creating template');
    })
    .finally(() => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
}
</script>

<!-- Create Template Modal -->
<div class="modal fade" id="createTemplateModal" tabindex="-1" aria-labelledby="createTemplateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createTemplateModalLabel">
                    <i class="fas fa-plus me-2"></i>{{ __('Add Custom Section') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="createTemplateForm">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="templateName" class="form-label">{{ __('Template Name') }}</label>
                                <input type="text" class="form-control" id="templateName" name="name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="templateType" class="form-label">{{ __('Template Type') }}</label>
                                <select class="form-control" id="templateType" name="type" required>
                                    <option value="">{{ __('Select Type') }}</option>
                                    <option value="header">{{ __('Header') }}</option>
                                    <option value="section">{{ __('Section') }}</option>
                                    <option value="footer">{{ __('Footer') }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="templateDescription" class="form-label">{{ __('Description') }}</label>
                        <textarea class="form-control" id="templateDescription" name="description" rows="3"></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="templateLanguage" class="form-label">{{ __('Language') }}</label>
                                <select class="form-control" id="templateLanguage" name="language">
                                    <option value="en">{{ __('English') }}</option>
                                    <option value="ar">{{ __('Arabic') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="templateDirection" class="form-label">{{ __('Direction') }}</label>
                                <select class="form-control" id="templateDirection" name="direction">
                                    <option value="ltr">{{ __('LTR') }}</option>
                                    <option value="rtl">{{ __('RTL') }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="templateActive" name="is_active" value="1" checked>
                            <label class="form-check-label" for="templateActive">
                                {{ __('Set as Active') }}
                            </label>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                <button type="button" class="btn btn-primary" id="createTemplateBtn" onclick="createNewTemplate()">
                    <i class="fas fa-plus me-2"></i>{{ __('Create Template') }}
                </button>
            </div>
        </div>
    </div>
</div>

@endsection
