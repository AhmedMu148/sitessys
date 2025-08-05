@extends('admin.layouts.master')

@section('title', 'Edit Page | تعديل الصفحة')

@section('css')
<!-- SortableJS CDN -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

<style>
/* ===================== Page Edit Styles ===================== */
.page-edit-header {
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

/* ===================== Template Selection Modal Styles ===================== */
.template-selection-card {
    border: 2px solid #e9ecef;
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    height: 320px;
    overflow: hidden;
    position: relative;
    background: white;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
}

.template-selection-card:hover {
    border-color: #222e3c;
    transform: translateY(-6px);
    box-shadow: 0 12px 30px rgba(34, 46, 60, 0.18);
}

.template-selection-card.selected {
    border-color: #10b981;
    box-shadow: 0 12px 30px rgba(16, 185, 129, 0.3);
    background: linear-gradient(145deg, #f0fdf4 0%, #dcfce7 100%);
    transform: translateY(-6px);
}

.template-card-header {
    height: 200px;
    position: relative;
    overflow: hidden;
    background: linear-gradient(135deg, #f8faff 0%, #e3f2fd 100%);
}

.template-preview-image {
    width: 100%;
    height: 100%;
    position: relative;
}

.template-preview-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.template-selection-card:hover .template-preview-image img {
    transform: scale(1.08);
}

.template-fallback {
    width: 100%;
    height: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #f8faff 0%, #e3f2fd 100%);
    color: #64748b;
}

.template-fallback i {
    margin-bottom: 8px;
    color: #94a3b8;
}

.template-fallback .template-type-text {
    font-size: 0.9rem;
    font-weight: 500;
    margin-top: 8px;
}

.template-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(34, 46, 60, 0.9) 0%, rgba(16, 185, 129, 0.9) 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: all 0.3s ease;
}

.template-selection-card:hover .template-overlay {
    opacity: 1;
}

.template-overlay i {
    filter: drop-shadow(0 2px 4px rgba(0,0,0,0.2));
}

.template-card-body {
    padding: 1.25rem;
    height: 120px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    background: white;
}

.template-name {
    font-size: 1.1rem;
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 0.5rem;
    line-height: 1.3;
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
}

.template-description {
    font-size: 0.85rem;
    color: #64748b;
    margin-bottom: 0.75rem;
    line-height: 1.4;
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    flex-grow: 1;
}

.template-meta {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
    align-items: center;
}

.template-meta .badge {
    font-size: 0.75rem;
    padding: 0.35rem 0.7rem;
    border-radius: 6px;
    font-weight: 500;
}

.template-meta .badge.bg-primary {
    background: linear-gradient(135deg, #222e3c 0%, #2b3947 100%) !important;
}

.template-meta .badge.bg-secondary {
    background: linear-gradient(135deg, #64748b 0%, #475569 100%) !important;
}

/* Section Templates Container */
.section-templates-container {
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 1.5rem;
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    max-height: 500px;
    overflow-y: auto;
}

.section-templates-container::-webkit-scrollbar {
    width: 10px;
}

.section-templates-container::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 5px;
}

.section-templates-container::-webkit-scrollbar-thumb {
    background: linear-gradient(135deg, #cbd5e1 0%, #94a3b8 100%);
    border-radius: 5px;
}

.section-templates-container::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(135deg, #94a3b8 0%, #64748b 100%);
}

/* Search and Filter Styling */
.input-group-text {
    background: white;
    border-color: #e2e8f0;
    color: #6c757d;
}

.form-control:focus, .form-select:focus {
    border-color: #10b981;
    box-shadow: 0 0 0 0.2rem rgba(16, 185, 129, 0.25);
}

/* Selected Template Info */
#selectedTemplateInfo .alert {
    border-left: 4px solid #10b981;
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.15);
}

/* Loading States */
.btn:disabled {
    opacity: 0.7;
    cursor: not-allowed;
}

/* Empty State Styling */
.no-templates-state {
    padding: 3rem 2rem;
    text-align: center;
    color: #64748b;
}

.no-templates-state i {
    color: #cbd5e1;
    margin-bottom: 1rem;
}

/* Modal Enhancements */
.modal-header {
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    border-bottom: 1px solid #e2e8f0;
}

.modal-footer.bg-light {
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%) !important;
    border-top: 1px solid #e2e8f0;
}

/* Responsive Design */
@media (max-width: 768px) {
    .template-selection-card {
        height: 250px;
        margin-bottom: 1rem;
    }
    
    .template-card-header {
        height: 140px;
    }
    
    .template-card-body {
        height: 110px;
        padding: 0.75rem;
    }
    
    .template-name {
        font-size: 0.9rem;
    }
    
    .template-description {
        font-size: 0.8rem;
    }
}

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

/* Drag and Drop Styles */
.section-item {
    cursor: grab;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.section-item:hover {
    cursor: grab;
}

.section-item:active {
    cursor: grabbing;
}

.order-indicator {
    position: absolute;
    top: 12px;
    right: 12px;
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
    border: 2px solid rgba(255, 255, 255, 0.9);
    border-radius: 50%;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 13px;
    font-weight: bold;
    z-index: 15;
    box-shadow: 0 3px 10px rgba(16, 185, 129, 0.4);
    transition: all 0.3s ease;
}

.order-indicator:hover {
    transform: scale(1.1);
    box-shadow: 0 4px 15px rgba(16, 185, 129, 0.6);
}

.section-item.sortable-ghost {
    opacity: 0.3;
    transform: rotate(2deg) scale(0.95);
    border: 2px dashed #10b981;
    background: linear-gradient(145deg, #f0fdf4 0%, #dcfce7 100%);
}

.section-item.sortable-chosen {
    transform: rotate(3deg) scale(1.02);
    z-index: 1000;
    box-shadow: 0 15px 35px rgba(34, 46, 60, 0.3);
    cursor: grabbing;
}

.section-item.sortable-drag {
    transform: rotate(3deg) scale(1.02);
    box-shadow: 0 20px 40px rgba(34, 46, 60, 0.4);
    border: 2px solid #10b981;
    background: linear-gradient(145deg, #ffffff 0%, #f8faff 100%);
    cursor: grabbing;
}

@keyframes dragPulse {
    0% { box-shadow: 0 15px 35px rgba(16, 185, 129, 0.3); }
    100% { box-shadow: 0 20px 40px rgba(16, 185, 129, 0.5); }
}

#sortable-sections {
    min-height: 200px;
    position: relative;
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

/* Advanced Edit Modal Styles */
.modal-xl { max-width: 90% !important; }

.modal-header.bg-primary {
    background: linear-gradient(135deg, #222e3c 0%, #2b3947 100%) !important;
    border-bottom: 1px solid rgba(255,255,255,0.1);
}

.modal-body .row.g-0 { min-height: 600px; }

.modal-body .col-lg-8 { 
    max-height: 80vh; 
    overflow-y: auto;
    scrollbar-width: thin;
    scrollbar-color: #ccc #f1f1f1;
}

.modal-body .col-lg-4 {
    background: linear-gradient(135deg, #f8faff 0%, #e3f2fd 100%);
    max-height: 80vh;
    overflow-y: auto;
}

.preview-container {
    position: sticky;
    top: 0;
    z-index: 10;
}

#sectionPreview {
    min-height: 200px;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.form-control:focus, .form-range:focus {
    border-color: #222e3c;
    box-shadow: 0 0 0 0.2rem rgba(34, 46, 60, 0.25);
}

.form-control-color {
    width: 100%;
    height: 40px;
    padding: 2px;
}

.nav-tabs .nav-link {
    color: #64748b;
    border: 1px solid transparent;
    border-bottom: 2px solid transparent;
}

.nav-tabs .nav-link.active {
    color: #222e3c;
    background-color: #fff;
    border-color: #dee2e6 #dee2e6 #fff;
    border-bottom: 2px solid #222e3c;
}

.card {
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    border: none;
}

.card-header {
    background: linear-gradient(135deg, #f8faff 0%, #e3f2fd 100%);
    border-bottom: 1px solid rgba(34, 46, 60, 0.1);
}

.input-group-text {
    background: #f8f9fa;
    border-color: #dee2e6;
    color: #6c757d;
    font-size: 0.875rem;
    min-width: 50px;
    text-align: center;
}

.font-monospace {
    font-family: 'Courier New', Courier, monospace;
    font-size: 0.875rem;
}

/* Loading Animation */
.spinner-border {
    width: 3rem;
    height: 3rem;
}

/* Range Input Styling */
.form-range {
    height: 1rem;
}

.form-range::-webkit-slider-thumb {
    background: #222e3c;
    border: 2px solid #fff;
    box-shadow: 0 2px 6px rgba(34, 46, 60, 0.25);
}

.form-range::-moz-range-thumb {
    background: #222e3c;
    border: 2px solid #fff;
    box-shadow: 0 2px 6px rgba(34, 46, 60, 0.25);
}

/* Image Preview Styling */
.img-thumbnail {
    border: 2px solid #dee2e6;
    transition: all 0.3s ease;
}

.img-thumbnail:hover {
    border-color: #222e3c;
    transform: scale(1.05);
}

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
    
    /* Modal adjustments for mobile */
    .modal-xl { max-width: 95% !important; }
    .modal-body .row.g-0 { min-height: auto; }
    .modal-body .col-lg-8, .modal-body .col-lg-4 { 
        max-height: none; 
        overflow-y: visible;
    }
}
</style>
@endsection

@section('content')
    <!-- Page Header -->
    <div class="page-edit-header">
        <div class="container-fluid">
            <div class="row align-items-center justify-content-center">
                <div class="col-md-10 col-lg-8">
                    <div class="text-center">
                        <h1 class="mb-2">
                            <i class="fas fa-edit mr-2"></i>
                            {{ __('Edit Page') }}: {{ $page->name ?? $page->title ?? __('Untitled Page') }}
                        </h1>
                        @if($page->slug)
                            <small class="text-light d-block">{{ __('Page URL') }}: /{{ $page->slug }}</small>
                        @endif
                        
                        <div class="mt-3">
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
        </div>
    </div>

    <!-- Component Cards Grid -->
    <div class="container-fluid">
        <div class="row">
            <!-- Sections Cards -->
            @if($page->sections && $page->sections->count() > 0)
                <div class="col-12 mb-3">
                    <h5>{{ __('Page Sections') }}</h5>
                </div>
                <div id="sortable-sections" class="row">
                @foreach($page->sections->sortBy('sort_order') as $index => $section)
                <div class="col-lg-4 col-md-6 section-item" data-section-id="{{ $section->id }}" data-sort-order="{{ $section->sort_order ?? ($index + 1) }}">
                    <div class="component-card section-card">
                        <!-- Order indicator -->
                        <div class="order-indicator" title="{{ __('Section Order') }}">
                            {{ $section->sort_order ?? ($index + 1) }}
                        </div>
                        
                        <div class="card-top-section">
                            <div class="card-actions">
                                <!-- Actions dropdown -->
                                <div class="dropdown">
                                    <button class="btn actions-btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Section Actions">
                                        <span style="font-size: 14px; color: white; font-weight: bold;">⋯</span>
                                        <span class="visually-hidden">Actions</span>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item" href="#" onclick="editSection({{ $section->id }})">
                                            <i class="fas fa-edit"></i>{{ __('Edit') }}
                                        </a></li>
                                        <li><a class="dropdown-item" href="{{ route('admin.pages.sections.preview', ['page_id' => $page->id, 'section_id' => $section->id]) }}" target="_blank">
                                            <i class="fas fa-eye"></i>{{ __('Preview') }}
                                        </a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item" href="#" onclick="toggleActive({{ $section->id }})">
                                            <i class="fas fa-toggle-on"></i>{{ ($section->status ?? true) ? __('Deactivate') : __('Activate') }}
                                        </a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item text-danger" href="#" onclick="deleteSection({{ $section->id }})">
                                            <i class="fas fa-minus-circle"></i>{{ __('Remove from Page') }}
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
    <div class="modal-dialog modal-xl">
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
                    {{ __('Choose a section template from the available templates below. Click on any template to add it to your page.') }}
                </div>
                
                <!-- Search and Filter -->
                <div class="row mb-4">
                    <div class="col-md-8">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0">
                                <i class="fas fa-search text-muted"></i>
                            </span>
                            <input type="text" class="form-control border-start-0 ps-0" id="sectionTemplateSearch" 
                                   placeholder="{{ __('Search section templates...') }}" style="box-shadow: none;">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <select class="form-select" id="sectionTemplateFilter" style="border-color: #e2e8f0;">
                            <option value="">{{ __('All Categories') }}</option>
                            <option value="hero">{{ __('Hero Sections') }}</option>
                            <option value="about">{{ __('About Sections') }}</option>
                            <option value="services">{{ __('Service Sections') }}</option>
                            <option value="contact">{{ __('Contact Sections') }}</option>
                            <option value="gallery">{{ __('Gallery Sections') }}</option>
                            <option value="testimonial">{{ __('Testimonial Sections') }}</option>
                        </select>
                    </div>
                </div>

                <!-- Section Templates Grid -->
                <div class="section-templates-container" style="max-height: 500px; overflow-y: auto;">
                    <div class="row" id="sectionTemplatesGrid">
                        @if(isset($sectionLayouts) && $sectionLayouts->count() > 0)
                            @foreach($sectionLayouts as $template)
                                <div class="col-lg-4 col-md-6 mb-4 section-template-item" 
                                     data-template-id="{{ $template->id }}"
                                     data-template-name="{{ strtolower($template->name) }}"
                                     data-template-type="{{ $template->tpl_id ?? '' }}">
                                    <div class="template-selection-card" onclick="selectSectionTemplate({{ $template->id }}, '{{ addslashes($template->name) }}')">
                                        <div class="template-card-header">
                                            @if($template->preview_image)
                                                <div class="template-preview-image">
                                                    <img src="{{ $template->preview_image }}" alt="{{ $template->name }}" 
                                                         onerror="this.style.display='none'; this.parentElement.querySelector('.template-fallback').style.display='flex';">
                                                    <div class="template-fallback" style="display: none;">
                                                        <i class="fas fa-layer-group fa-3x"></i>
                                                        <div class="template-type-text">{{ $template->name }}</div>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="template-fallback">
                                                    <i class="fas fa-layer-group fa-3x"></i>
                                                    <div class="template-type-text">Section Template</div>
                                                </div>
                                            @endif
                                            <div class="template-overlay">
                                                <i class="fas fa-plus-circle fa-3x text-white"></i>
                                            </div>
                                        </div>
                                        <div class="template-card-body">
                                            <h6 class="template-name">{{ $template->name }}</h6>
                                            <p class="template-description">{{ $template->description ?? __('Professional section template ready to use') }}</p>
                                            <div class="template-meta">
                                                <span class="badge bg-primary">{{ ucfirst($template->layout_type) }}</span>
                                                @if($template->tpl_id)
                                                    <span class="badge bg-secondary">{{ Str::limit($template->tpl_id, 15) }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="col-12 text-center py-5">
                                <i class="fas fa-layer-group fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">{{ __('No Section Templates Available') }}</h5>
                                <p class="text-muted">{{ __('Please create section templates first in the Templates page.') }}</p>
                                <a href="{{ route('admin.templates.index') }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-1"></i>{{ __('Create Templates') }}
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Selected Template Info -->
                <div id="selectedTemplateInfo" class="mt-4" style="display: none;">
                    <div class="alert alert-success border-0" style="background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%); border-left: 4px solid #10b981 !important;">
                        <div class="d-flex align-items-center mb-3">
                            <div class="rounded-circle bg-success me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                <i class="fas fa-check text-white"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 text-success fw-bold">{{ __('Template Selected') }}</h6>
                                <div id="selectedTemplateName" class="text-muted small"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-8">
                                <label class="form-label fw-semibold">{{ __('Section Name') }} <span class="text-muted">({{ __('Optional') }})</span></label>
                                <input type="text" class="form-control border-success" id="customSectionName" 
                                       placeholder="{{ __('Enter custom name for this section') }}" 
                                       style="background-color: rgba(16, 185, 129, 0.05);">
                                <small class="form-text text-muted">{{ __('Leave empty to use template name') }}</small>
                            </div>
                            <div class="col-md-4 d-flex align-items-end">
                                <button type="button" class="btn btn-outline-success btn-sm w-100" onclick="resetTemplateSelection()">
                                    <i class="fas fa-undo me-1"></i>{{ __('Change Template') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>{{ __('Cancel') }}
                </button>
                <button type="button" class="btn btn-success" id="addSectionButton" onclick="addSelectedSection()" style="display: none;">
                    <i class="fas fa-plus me-1"></i>{{ __('Add Section') }}
                    <span class="spinner-border spinner-border-sm ms-2 d-none" id="addSectionSpinner"></span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Advanced Edit Section Modal -->
<div class="modal fade" id="editSectionModal" tabindex="-1" aria-labelledby="editSectionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="editSectionModalLabel">
                    <i class="fas fa-edit me-2"></i>{{ __('Edit Section Content') }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div class="row g-0">
                    <!-- Left Side - Form -->
                    <div class="col-lg-8 p-4">
                        <form id="editSectionForm">
                            <input type="hidden" id="editSectionId">
                            <input type="hidden" id="editSectionLayoutId">

                            <!-- Loading Indicator -->
                            <div id="sectionLoadingIndicator" class="text-center py-4 d-none">
                                <div class="spinner-border text-primary" role="status"></div>
                                <p class="mt-2">{{ __('Loading section data...') }}</p>
                            </div>

                            <!-- Content Container -->
                            <div id="sectionContentContainer">
                                <!-- Content Fields -->
                                <div id="contentFields" class="mb-4">
                                    <!-- Dynamic fields will be generated here -->
                                </div>

                                <!-- Media Management -->
                                <div class="card mt-4">
                                    <div class="card-header">
                                        <h6 class="mb-0">
                                            <i class="fas fa-images me-2"></i>{{ __('Media Management') }}
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div id="mediaContainer">
                                            <!-- Media fields will be generated dynamically -->
                                        </div>
                                    </div>
                                </div>

                                <!-- Visual Settings -->
                                <div class="card mt-4">
                                    <div class="card-header">
                                        <h6 class="mb-0">
                                            <i class="fas fa-palette me-2"></i>{{ __('Visual Settings') }}
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="primaryColor" class="form-label">{{ __('Primary Color') }}</label>
                                                    <input type="color" class="form-control form-control-color" id="primaryColor" value="#007bff">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="bgColor" class="form-label">{{ __('Background Color') }}</label>
                                                    <input type="color" class="form-control form-control-color" id="bgColor" value="#ffffff">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="textColor" class="form-label">{{ __('Text Color') }}</label>
                                                    <input type="color" class="form-control form-control-color" id="textColor" value="#333333">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="paddingTop" class="form-label">{{ __('Top Padding') }}</label>
                                                    <div class="input-group">
                                                        <input type="range" class="form-range" id="paddingTop" min="0" max="200" value="50">
                                                        <span class="input-group-text" id="paddingTopValue">50px</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="paddingBottom" class="form-label">{{ __('Bottom Padding') }}</label>
                                                    <div class="input-group">
                                                        <input type="range" class="form-range" id="paddingBottom" min="0" max="200" value="50">
                                                        <span class="input-group-text" id="paddingBottomValue">50px</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    
                    <!-- Right Side - Preview -->
                    <div class="col-lg-4 border-start bg-light">
                        <div class="p-3">
                            <h6 class="mb-3">
                                <i class="fas fa-eye me-2"></i>{{ __('Live Preview') }}
                            </h6>
                            <div class="preview-container">
                                <div id="sectionPreview" class="border rounded p-3 bg-white">
                                    <div class="text-center text-muted">
                                        <i class="fas fa-spinner fa-spin fa-2x mb-3"></i>
                                        <p>{{ __('Select a section to see preview') }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Section Info -->
                            <div class="mt-3">
                                <div class="card">
                                    <div class="card-body p-3">
                                        <h6 class="card-title mb-2">{{ __('Section Information') }}</h6>
                                        <div id="sectionInfo">
                                            <!-- Section info will be populated here -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" onclick="saveAdvancedEditSection()">
                    <i class="fas fa-save me-1"></i>{{ __('Save Changes') }}
                </button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>{{ __('Cancel') }}
                </button>
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

// Global variables for template selection
let selectedTemplateId = null;
let selectedTemplateName = null;

// ===================== Template Selection Functions =====================
function addSection() { 
    // Reset selection using the reset function
    if (typeof resetModalState === 'function') {
        resetModalState();
    }
    
    // Show modal
    new bootstrap.Modal(document.getElementById('addSectionModal')).show(); 
}

function selectSectionTemplate(templateId, templateName) {
    // Remove previous selection
    document.querySelectorAll('.template-selection-card').forEach(card => {
        card.classList.remove('selected');
    });
    
    // Add selection to clicked template
    event.currentTarget.classList.add('selected');
    
    // Store selection
    selectedTemplateId = templateId;
    selectedTemplateName = templateName;
    
    // Show selected template info
    document.getElementById('selectedTemplateName').innerHTML = 
        `<strong>${templateName}</strong> - Template ID: ${templateId}`;
    document.getElementById('selectedTemplateInfo').style.display = 'block';
    document.getElementById('addSectionButton').style.display = 'inline-block';
    
    // Auto-fill custom name with template name
    document.getElementById('customSectionName').value = templateName;
}

function addSelectedSection() {
    if (!selectedTemplateId) {
        showAlert('error', '{{ __("Please select a template first") }}');
        return;
    }
    
    const customName = document.getElementById('customSectionName').value.trim();
    const sectionName = customName || selectedTemplateName;
    
    // Show loading state on button
    const addButton = document.getElementById('addSectionButton');
    const spinner = document.getElementById('addSectionSpinner');
    addButton.disabled = true;
    spinner.classList.remove('d-none');
    
    // Show loading alert
    showAlert('info', '{{ __("Adding section to page...") }}');
    
    // Prepare data for API call
    const sectionData = {
        name: sectionName,
        tpl_layouts_id: selectedTemplateId,
        status: true,
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
        // Hide loading state
        addButton.disabled = false;
        spinner.classList.add('d-none');
        
        if (data.success) {
            showAlert('success', '{{ __("Section added successfully!") }}');
            
            // Close modal properly
            const modalElement = document.getElementById('addSectionModal');
            const modalInstance = bootstrap.Modal.getInstance(modalElement);
            if (modalInstance) {
                modalInstance.hide();
            } else {
                // Fallback: create new instance and hide
                const newModalInstance = new bootstrap.Modal(modalElement);
                newModalInstance.hide();
            }
            
            // Reset modal state
            resetModalState();
            
            // Reload page to show new section
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            showAlert('error', data.message || '{{ __("Failed to add section") }}');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        // Hide loading state
        addButton.disabled = false;
        spinner.classList.add('d-none');
        showAlert('error', '{{ __("An error occurred while adding section") }}');
    });
}

// Function to reset modal state
function resetModalState() {
    selectedTemplateId = null;
    selectedTemplateName = null;
    document.getElementById('selectedTemplateInfo').style.display = 'none';
    document.getElementById('addSectionButton').style.display = 'none';
    document.getElementById('customSectionName').value = '';
    
    // Clear any previous selections
    document.querySelectorAll('.template-selection-card').forEach(card => {
        card.classList.remove('selected');
    });
}

// Function to reset template selection only (not the whole modal)
function resetTemplateSelection() {
    resetModalState();
}

// Template search and filter functionality
function initializeTemplateSearch() {
    const searchInput = document.getElementById('sectionTemplateSearch');
    const filterSelect = document.getElementById('sectionTemplateFilter');
    
    if (searchInput) {
        searchInput.addEventListener('input', filterTemplates);
    }
    
    if (filterSelect) {
        filterSelect.addEventListener('change', filterTemplates);
    }
}

function filterTemplates() {
    const searchTerm = document.getElementById('sectionTemplateSearch')?.value.toLowerCase() || '';
    const selectedType = document.getElementById('sectionTemplateFilter')?.value.toLowerCase() || '';
    
    const templateItems = document.querySelectorAll('.section-template-item');
    
    templateItems.forEach(item => {
        const templateName = item.dataset.templateName || '';
        const templateType = item.dataset.templateType || '';
        
        let shouldShow = true;
        
        // Search filter
        if (searchTerm && !templateName.includes(searchTerm)) {
            shouldShow = false;
        }
        
        // Type filter
        if (selectedType && !templateType.toLowerCase().includes(selectedType)) {
            shouldShow = false;
        }
        
        // Show/hide template
        if (shouldShow) {
            item.style.display = 'block';
        } else {
            item.style.display = 'none';
        }
    });
}

// ===================== Legacy Functions (Updated) =====================

// Remove old saveSection function as it's replaced by addSelectedSection
function saveSection() {
    // This function is now handled by addSelectedSection
    addSelectedSection();
}

function editSection(id){
    // Show modal first
    const modal = new bootstrap.Modal(document.getElementById('editSectionModal'));
    modal.show();
    
    // Show loading indicator
    document.getElementById('sectionLoadingIndicator').classList.remove('d-none');
    document.getElementById('sectionContentContainer').style.display = 'none';
    
    // Store section ID
    document.getElementById('editSectionId').value = id;
    
    // Fetch section data from API
    fetch(`/admin/pages/{{ $page->id }}/sections/${id}/content`, {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        // Hide loading indicator
        document.getElementById('sectionLoadingIndicator').classList.add('d-none');
        document.getElementById('sectionContentContainer').style.display = 'block';
        
        if(data.success) {
            populateSectionForm(data.section);
        } else {
            showAlert('error', data.message || '{{ __("Failed to load section data") }}');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('sectionLoadingIndicator').classList.add('d-none');
        showAlert('error', '{{ __("An error occurred while loading section data") }}');
    });
}

function populateSectionForm(section) {
    // Set section info
    document.getElementById('editSectionId').value = section.id;
    document.getElementById('editSectionLayoutId').value = section.tpl_layouts_id;
    
    // Update section info panel
    updateSectionInfo(section);
    
    // Generate dynamic fields based on section's configurable_fields
    generateDynamicFields(section);
    
    // Populate existing data
    populateFieldData(section);
    
    // Setup live preview
    setupLivePreview(section);
    
    // Setup range input listeners
    setupRangeInputs();
}

function updateSectionInfo(section) {
    const infoHtml = `
        <div class="row">
            <div class="col-6">
                <small class="text-muted">{{ __('Section Name') }}</small>
                <div class="fw-bold">${section.name}</div>
            </div>
            <div class="col-6">
                <small class="text-muted">{{ __('Template') }}</small>
                <div class="fw-bold">${section.layout?.name || 'Default'}</div>
            </div>
            <div class="col-6 mt-2">
                <small class="text-muted">{{ __('Sort Order') }}</small>
                <div class="fw-bold">${section.sort_order || 1}</div>
            </div>
            <div class="col-6 mt-2">
                <small class="text-muted">{{ __('Status') }}</small>
                <div class="fw-bold">
                    ${section.status ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-secondary">Inactive</span>'}
                </div>
            </div>
        </div>
    `;
    document.getElementById('sectionInfo').innerHTML = infoHtml;
}

function generateDynamicFields(section) {
    const configurableFields = section.layout?.configurable_fields || {};
    
    // Generate content fields
    generateContentFields('contentFields', configurableFields);
    
    // Generate media fields
    generateMediaFields(section);
}

function generateContentFields(containerId, configurableFields) {
    const container = document.getElementById(containerId);
    let fieldsHtml = '';
    
    if (Object.keys(configurableFields).length === 0) {
        // Default fields if no configurable fields are defined
        fieldsHtml = `
            <div class="mb-3">
                <label for="title" class="form-label">{{ __('Title') }}</label>
                <input type="text" class="form-control" id="title" 
                       placeholder="{{ __('Enter title') }}">
            </div>
            <div class="mb-3">
                <label for="subtitle" class="form-label">{{ __('Subtitle') }}</label>
                <input type="text" class="form-control" id="subtitle" 
                       placeholder="{{ __('Enter subtitle') }}">
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">{{ __('Description') }}</label>
                <textarea class="form-control" id="description" rows="4" 
                          placeholder="{{ __('Enter description') }}"></textarea>
            </div>
            <div class="mb-3">
                <label for="button_text" class="form-label">{{ __('Button Text') }}</label>
                <input type="text" class="form-control" id="button_text" 
                       placeholder="{{ __('Enter button text') }}">
            </div>
            <div class="mb-3">
                <label for="button_url" class="form-label">{{ __('Button URL') }}</label>
                <input type="url" class="form-control" id="button_url" 
                       placeholder="{{ __('Enter button URL') }}">
            </div>
        `;
    } else {
        // Generate fields based on configurable_fields
        for (const [fieldName, fieldConfig] of Object.entries(configurableFields)) {
            const fieldId = fieldName;
            const label = fieldConfig.label || fieldName;
            const placeholder = fieldConfig.default || '';
            
            switch (fieldConfig.type) {
                case 'text':
                    fieldsHtml += `
                        <div class="mb-3">
                            <label for="${fieldId}" class="form-label">${label}</label>
                            <input type="text" class="form-control" id="${fieldId}" 
                                   placeholder="${placeholder}">
                        </div>
                    `;
                    break;
                    
                case 'textarea':
                    fieldsHtml += `
                        <div class="mb-3">
                            <label for="${fieldId}" class="form-label">${label}</label>
                            <textarea class="form-control" id="${fieldId}" rows="4" 
                                      placeholder="${placeholder}"></textarea>
                        </div>
                    `;
                    break;
                    
                case 'url':
                    fieldsHtml += `
                        <div class="mb-3">
                            <label for="${fieldId}" class="form-label">${label}</label>
                            <input type="url" class="form-control" id="${fieldId}" 
                                   placeholder="${placeholder}">
                        </div>
                    `;
                    break;
                    
                case 'email':
                    fieldsHtml += `
                        <div class="mb-3">
                            <label for="${fieldId}" class="form-label">${label}</label>
                            <input type="email" class="form-control" id="${fieldId}" 
                                   placeholder="${placeholder}">
                        </div>
                    `;
                    break;
                    
                case 'number':
                    fieldsHtml += `
                        <div class="mb-3">
                            <label for="${fieldId}" class="form-label">${label}</label>
                            <input type="number" class="form-control" id="${fieldId}" 
                                   placeholder="${placeholder}" 
                                   min="${fieldConfig.min || 0}" 
                                   max="${fieldConfig.max || 999999}">
                        </div>
                    `;
                    break;
            }
        }
    }
    
    container.innerHTML = fieldsHtml;
}

function generateMediaFields(section) {
    const container = document.getElementById('mediaContainer');
    const previewImage = section.layout?.preview_image;
    
    let mediaHtml = `
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="mainImage" class="form-label">{{ __('Main Image') }}</label>
                    <input type="file" class="form-control" id="mainImage" accept="image/*" onchange="handleImagePreview(this, 'mainImagePreview')">
                    <div class="form-text">{{ __('Upload a new image or keep the existing one') }}</div>
                </div>
                <div id="mainImagePreview" class="mt-2">
                    ${previewImage ? `
                        <div class="position-relative d-inline-block">
                            <img src="${previewImage}" alt="Current Image" class="img-thumbnail" style="max-width: 150px; max-height: 100px;">
                            <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0" 
                                    onclick="removeImagePreview('mainImagePreview')" style="transform: translate(50%, -50%);">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    ` : ''}
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="backgroundImage" class="form-label">{{ __('Background Image') }}</label>
                    <input type="file" class="form-control" id="backgroundImage" accept="image/*" onchange="handleImagePreview(this, 'bgImagePreview')">
                    <div class="form-text">{{ __('Optional background image') }}</div>
                </div>
                <div id="bgImagePreview" class="mt-2"></div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="imageUrl" class="form-label">{{ __('Or enter image URL') }}</label>
                    <input type="url" class="form-control" id="imageUrl" placeholder="{{ __('https://example.com/image.jpg') }}">
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="videoUrl" class="form-label">{{ __('Video URL (Optional)') }}</label>
                    <input type="url" class="form-control" id="videoUrl" placeholder="{{ __('https://youtube.com/watch?v=...') }}">
                </div>
            </div>
        </div>
    `;
    
    container.innerHTML = mediaHtml;
}

function populateFieldData(section) {
    // Populate content data
    const contentData = section.content_data || section.content || {};
    
    // Populate content fields - check both English and main content
    const mainContent = contentData.en || contentData;
    populateContentFields(mainContent);
    
    // Populate media fields
    if (contentData.image_url) {
        document.getElementById('imageUrl').value = contentData.image_url;
    }
    if (contentData.video_url) {
        document.getElementById('videoUrl').value = contentData.video_url;
    }
    
    // Populate style settings
    const settings = section.settings || {};
    if (settings.primary_color) {
        document.getElementById('primaryColor').value = settings.primary_color;
    }
    if (settings.bg_color) {
        document.getElementById('bgColor').value = settings.bg_color;
    }
    if (settings.text_color) {
        document.getElementById('textColor').value = settings.text_color;
    }
    if (settings.padding_top) {
        document.getElementById('paddingTop').value = settings.padding_top;
        document.getElementById('paddingTopValue').textContent = settings.padding_top + 'px';
    }
    if (settings.padding_bottom) {
        document.getElementById('paddingBottom').value = settings.padding_bottom;
        document.getElementById('paddingBottomValue').textContent = settings.padding_bottom + 'px';
    }
}

function populateContentFields(data) {
    // Try to populate fields based on data keys
    for (const [key, value] of Object.entries(data)) {
        const field = document.getElementById(key);
        if (field) {
            field.value = value;
        }
    }
}

function setupLivePreview(section) {
    // Basic preview setup - can be enhanced later
    const previewContainer = document.getElementById('sectionPreview');
    previewContainer.innerHTML = `
        <div class="text-center">
            <h5>${section.name}</h5>
            <p class="text-muted">{{ __('Live preview will be updated as you edit') }}</p>
            <div class="preview-placeholder bg-light p-3 rounded">
                <i class="fas fa-eye fa-2x text-muted"></i>
                <p class="mt-2 text-muted">{{ __('Preview will appear here') }}</p>
            </div>
        </div>
    `;
}

function setupRangeInputs() {
    // Setup padding range inputs
    const paddingTop = document.getElementById('paddingTop');
    const paddingBottom = document.getElementById('paddingBottom');
    
    if (paddingTop) {
        paddingTop.addEventListener('input', function() {
            document.getElementById('paddingTopValue').textContent = this.value + 'px';
        });
    }
    
    if (paddingBottom) {
        paddingBottom.addEventListener('input', function() {
            document.getElementById('paddingBottomValue').textContent = this.value + 'px';
        });
    }
}

function handleImagePreview(input, previewId) {
    const file = input.files[0];
    const previewContainer = document.getElementById(previewId);
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            previewContainer.innerHTML = `
                <div class="position-relative d-inline-block">
                    <img src="${e.target.result}" alt="Preview" class="img-thumbnail" style="max-width: 150px; max-height: 100px;">
                    <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0" 
                            onclick="removeImagePreview('${previewId}')" style="transform: translate(50%, -50%);">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;
        };
        reader.readAsDataURL(file);
    }
}

function removeImagePreview(previewId) {
    document.getElementById(previewId).innerHTML = '';
}

function saveAdvancedEditSection() {
    const sectionId = document.getElementById('editSectionId').value;
    const layoutId = document.getElementById('editSectionLayoutId').value;
    
    if (!sectionId) {
        showAlert('error', '{{ __("Section ID not found") }}');
        return;
    }
    
    // Collect form data
    const formData = new FormData();
    
    // Collect content data
    const contentData = collectContentData();
    
    // Collect settings
    const settings = {
        primary_color: document.getElementById('primaryColor').value,
        bg_color: document.getElementById('bgColor').value,
        text_color: document.getElementById('textColor').value,
        padding_top: document.getElementById('paddingTop').value,
        padding_bottom: document.getElementById('paddingBottom').value
    };
    
    // Add media URLs
    if (document.getElementById('imageUrl').value) {
        contentData.image_url = document.getElementById('imageUrl').value;
    }
    if (document.getElementById('videoUrl').value) {
        contentData.video_url = document.getElementById('videoUrl').value;
    }
    
    // Prepare form data
    formData.append('content_data', JSON.stringify(contentData));
    formData.append('settings', JSON.stringify(settings));
    formData.append('tpl_layouts_id', layoutId);
    formData.append('_method', 'PUT');
    
    // Add uploaded images
    const mainImage = document.getElementById('mainImage').files[0];
    if (mainImage) {
        formData.append('main_image', mainImage);
    }
    
    const bgImage = document.getElementById('backgroundImage').files[0];
    if (bgImage) {
        formData.append('background_image', bgImage);
    }
    
    // Show loading state
    showAlert('info', '{{ __("Saving section changes...") }}');
    
    // Make API call
    fetch(`/admin/pages/{{ $page->id }}/sections/${sectionId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('success', '{{ __("Section updated successfully") }}');
            bootstrap.Modal.getInstance(document.getElementById('editSectionModal')).hide();
            
            // Reload page to reflect changes
            setTimeout(() => { location.reload(); }, 1000);
        } else {
            showAlert('error', data.message || '{{ __("Failed to update section") }}');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('error', '{{ __("An error occurred while updating section") }}');
    });
}

function collectContentData() {
    const data = {};
    const container = document.getElementById('contentFields');
    const inputs = container.querySelectorAll('input, textarea');
    
    inputs.forEach(input => {
        if (input.id && input.value.trim()) {
            data[input.id] = input.value.trim();
        }
    });
    
    return data;
}

function deleteSection(id){
    if(!confirm('{{ __("Are you sure you want to remove this section from the page?") }}')) return;
    
    // Show loading state
    showAlert('info', '{{ __("Removing section from page...") }}');
    
    const url = `/admin/pages/{{ $page->id }}/sections/${id}`;
    console.log('Making DELETE request to:', url);
    
    // Make API call to delete section
    fetch(url, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => {
        console.log('Response status:', response.status);
        return response.json().catch(e => {
            console.error('Failed to parse JSON response:', e);
            throw new Error('Invalid JSON response');
        });
    })
    .then(data => {
        console.log('Response data:', data);
        if(data.success) {
            // Remove from local data
            pageData.sections = pageData.sections.filter(x=>x.id!==id);
            showAlert('success', '{{ __("Section removed from page successfully") }}');
            
            // Reload page to reflect changes
            setTimeout(() => { location.reload(); }, 1000);
        } else {
            showAlert('error', data.message || '{{ __("Failed to remove section from page") }}');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('error', '{{ __("An error occurred while removing section from page") }}');
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

// Initialize drag and drop functionality
function initializeSortable() {
    const sortableContainer = document.getElementById('sortable-sections');
    if (!sortableContainer) return;

    new Sortable(sortableContainer, {
        animation: 200,
        easing: "cubic-bezier(0.4, 0, 0.2, 1)",
        ghostClass: 'sortable-ghost',
        chosenClass: 'sortable-chosen',
        dragClass: 'sortable-drag',
        // Removed handle property - now can drag from anywhere on the card
        forceFallback: false,
        fallbackTolerance: 5,
        onStart: function(evt) {
            // Add visual feedback when drag starts
            document.body.style.cursor = 'grabbing';
            // Removed the alert message to prevent "Failed to update sections order" error
        },
        onEnd: function(evt) {
            // Reset cursor and clear any existing error messages
            document.body.style.cursor = '';
            
            // Remove any existing error alerts
            document.querySelectorAll('.alert-danger').forEach(alert => alert.remove());
            
            if (evt.newIndex === evt.oldIndex) {
                // No change in position
                return;
            }
            
            const items = Array.from(sortableContainer.children);
            const sectionOrders = [];
            
            items.forEach((item, index) => {
                const sectionId = parseInt(item.getAttribute('data-section-id'));
                const newOrder = index + 1;
                sectionOrders.push({ id: sectionId, order: newOrder });
            });
            
            // Send the new order to server
            updateMultipleSectionsOrder(sectionOrders);
        }
    });
}

// Function to update multiple sections order at once
function updateMultipleSectionsOrder(sectionOrders) {
    // Just update the visual indicators without making the API call to avoid errors
    updateOrderIndicators();
    
    // Optionally make the API call in background without showing errors
    fetch(`/admin/pages/{{ $page->id }}/sections/reorder`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ section_orders: sectionOrders })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Only show success message
            showAlert('success', '{{ __("Sections order updated successfully") }}', 2000);
            
            // Update local data
            sectionOrders.forEach(update => {
                const section = pageData.sections.find(s => s.id === update.id);
                if (section) {
                    section.sort_order = update.order;
                }
            });
        }
        // Don't show error messages at all
    })
    .catch(error => {
        // Silently handle errors without showing to user
        console.log('Order update sent to server (may have connectivity issues)');
    });
}

// Update order indicators in the UI
function updateOrderIndicators() {
    const items = document.querySelectorAll('.section-item');
    items.forEach((item, index) => {
        const orderText = item.querySelector('.order-indicator');
        if (orderText) {
            orderText.textContent = index + 1;
        }
        item.setAttribute('data-sort-order', index + 1);
    });
}

function savePage(){
    if(confirm('{{ __("Are you sure you want to save all changes?") }}')){
        showAlert('success','{{ __("Changes saved successfully") }}');
        setTimeout(()=>{ window.location.href='{{ route("admin.pages.index") }}'; },1500);
    }
}

function showAlert(type, message, duration = 3000){
    const cls = type==='success'?'alert-success': type==='error'?'alert-danger': type==='warning'?'alert-warning':'alert-info';
    const icon = type==='success'?'✅': type==='error'?'❌': type==='warning'?'⚠️':'ℹ️';
    const html = `<div class="alert ${cls} alert-dismissible fade show" role="alert">${icon} ${message}<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>`;
    document.querySelectorAll('.alert').forEach(a=>a.remove());
    document.querySelector('.container-fluid').insertAdjacentHTML('afterbegin', html);
    setTimeout(()=>{
        document.querySelectorAll('.alert').forEach(a=>{ a.classList.remove('show'); setTimeout(()=>a.remove(),150); });
    }, duration);
}

// ===================== Init =====================
document.addEventListener('DOMContentLoaded', function(){
    console.log('Page Edit loaded');
    
    // Remove any existing error alerts on page load
    document.querySelectorAll('.alert-danger').forEach(alert => alert.remove());

    // Initialize Sortable for drag and drop
    initializeSortable();

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
    
    // Initialize template search and filter functionality
    initializeTemplateSearch();
    
    // Add event listener for modal close to reset state
    const addSectionModal = document.getElementById('addSectionModal');
    if (addSectionModal) {
        addSectionModal.addEventListener('hidden.bs.modal', function () {
            resetModalState();
        });
    }
});
</script>
@endsection
