{{-- Shared Admin Panel Styles for Templates, Pages, and Components --}}
<style>
/* ===================== SHARED ADMIN PANEL STYLES ===================== */

/* Page Headers */
.page-header, .template-header {
    background: linear-gradient(135deg, #222e3c 0%, #2b3947 100%);
    border: 1px solid rgba(255, 255, 255, 0.2);
    padding: 1.5rem 0;
    margin-bottom: 1.5rem;
    border-radius: 0.75rem;
    animation: fadeInDown 0.6s ease-out;
    box-shadow: 0 4px 20px rgba(34, 46, 60, 0.15);
    position: relative;
    overflow: hidden;
    color: white;
}

.page-header::before, .template-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Ccircle cx='30' cy='30' r='2'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E") repeat;
}

.page-header h1, .template-header h1 {
    font-size: 1.75rem;
    font-weight: 600;
    margin-bottom: 0.25rem;
    color: #ffffff;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    position: relative;
    z-index: 1;
}

.page-header p, .template-header p {
    font-size: 0.95rem;
    color: rgba(255, 255, 255, 0.9);
    margin-bottom: 0;
    position: relative;
    z-index: 1;
}

/* Section Headers */
.section-header, .search-filter-section {
    background: linear-gradient(135deg, #f8faff 0%, #e3f2fd 100%);
    border: 1px solid rgba(34, 46, 60, 0.1);
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    border-radius: 0.75rem;
    position: relative;
    box-shadow: 0 4px 16px rgba(34, 46, 60, 0.08);
    animation: slideInUp 0.8s ease-out;
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
.template-card, .page-card {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border: 1px solid #e3f2fd;
    border-radius: 1rem;
    box-shadow: 0 2px 12px rgba(34, 46, 60, 0.08);
    position: relative;
    overflow: visible;
    background: linear-gradient(145deg, #ffffff 0%, #f8faff 100%);
    transform: translateY(0);
    margin-bottom: 1.5rem;
    backdrop-filter: blur(10px);
}

.template-card {
    height: 280px;
}

.page-card {
    height: 100%;
    animation: fadeInUp 0.6s ease-out forwards;
    animation-delay: calc(var(--animation-order) * 0.1s);
    opacity: 0;
}

.template-card:hover, .page-card:hover {
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
    content: '✓ Active';
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

/* Card Sections */
.card-top-section {
    background: linear-gradient(135deg, #222e3c 0%, #2b3947 100%);
    height: 120px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    position: relative;
    color: white;
    overflow: hidden;
    border-radius: 1rem 1rem 0 0;
}

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

.card-top-image:hover img { 
    transform: scale(1.05); 
}

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
    border-radius: 0 0 1rem 1rem;
}

.card-bottom-text { 
    font-size: 0.85rem; 
    color: #475569; 
    line-height: 1.6; 
    margin-bottom: 1rem;
}

/* ===================== Page-specific Styles ===================== */
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

.theme-business { background: #222e3c; color: #ffffff; }
.theme-portfolio { background: #34495e; color: #ffffff; }
.theme-ecommerce { background: #3b4556; color: #ffffff; }
.theme-seo-services { background: #424f63; color: #ffffff; }
.theme-default { background: #495a6b; color: #ffffff; }

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

.home-badge {
    background: #f59e0b;
    color: #ffffff;
    font-size: 0.7rem;
    padding: 0.25rem 0.5rem;
    border-radius: 0.375rem;
    margin-left: 0.5rem;
    font-weight: 600;
}

/* ===================== Dropdown Actions ===================== */
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

.dropdown-divider {
    margin: 0.5rem 0.5rem;
    border-color: rgba(34, 46, 60, 0.1);
    opacity: 0.7;
}

/* ===================== Search and Filter Section ===================== */
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

.btn-create {
    background: #222e3c;
    border: 1px solid #222e3c;
    color: white;
    font-weight: 500;
    padding: 0.75rem 1.5rem;
    border-radius: 0.375rem;
    font-size: 0.95rem;
    transition: all 0.2s ease;
}

.btn-create:hover {
    background: #2b3947;
    border-color: #2b3947;
    transform: translateY(-1px);
    color: white;
}

/* Filter Enhancements */
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

/* ===================== Empty State ===================== */
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

/* ===================== Loading States ===================== */
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

/* ===================== Animation Keyframes ===================== */
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

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
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

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

/* ===================== RTL Support ===================== */
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

[dir="rtl"] .dropdown-menu {
    right: auto;
    left: 0;
}

[dir="rtl"] .section-title i {
    margin-right: 0;
    margin-left: 0.5rem;
}

[dir="rtl"] .card-top-image .image-overlay-text {
    text-align: right;
}

[dir="rtl"] .dropdown-item:hover {
    transform: translateX(-4px);
}

/* ===================== Responsive Design ===================== */
@media (max-width: 1200px) {
    .page-card, .template-card {
        margin-bottom: 1rem;
    }
}

@media (max-width: 768px) {
    .page-header, .template-header {
        padding: 1rem 0;
        text-align: center;
    }
    
    .page-header h1, .template-header h1 {
        font-size: 1.5rem;
    }
    
    .search-filter-section, .page-actions {
        padding: 1rem;
    }
    
    .search-filter-section .row > div,
    .page-actions .row > div {
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
    
    .template-card {
        height: 260px;
        margin-bottom: 1rem;
        border-radius: 0.875rem;
    }
    
    .card-top-section {
        height: 100px;
        border-radius: 0.875rem 0.875rem 0 0;
    }
    
    .card-bottom-section {
        height: 160px;
        padding: 12px;
        border-radius: 0 0 0.875rem 0.875rem;
    }
    
    .card-top-image {
        border-radius: 0.875rem 0.875rem 0 0;
    }
    
    .page-card:hover, .template-card:hover {
        transform: translateY(-4px) scale(1.01);
    }
    
    .actions-btn {
        width: 32px;
        height: 32px;
    }
    
    .btn-create:hover {
        transform: translateY(-2px) scale(1.01);
    }
    
    /* RTL mobile adjustments */
    [dir="rtl"] .status-indicator {
        right: auto;
        left: 8px;
    }
    
    [dir="rtl"] .card-actions {
        right: auto;
        left: 8px;
    }
}

/* ===================== Form and Input Enhancements ===================== */
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

.card-footer {
    background: linear-gradient(135deg, #f8faff 0%, #e3f2fd 100%);
    border-top: 1px solid rgba(34, 46, 60, 0.1);
    padding: 0.75rem 1rem;
    transition: all 0.3s ease;
}

.page-card:hover .card-footer {
    background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
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

/* ===================== Modal Enhancements ===================== */
.modal-header {
    background: linear-gradient(135deg, #222e3c 0%, #2b3947 100%);
    color: white;
    border-bottom: none;
    position: relative;
    overflow: hidden;
}

.modal-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Ccircle cx='30' cy='30' r='2'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E") repeat;
}

.modal-title, .modal-header .btn-close {
    position: relative;
    z-index: 1;
}

.modal-header .btn-close {
    filter: invert(1);
}

.modal-title i {
    color: #10b981;
}

/* ===================== Alerts and Notifications ===================== */
.alert {
    border-radius: 0.75rem;
    border: none;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.alert-success {
    background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
    color: #065f46;
    border-left: 4px solid #10b981;
}

.alert-danger {
    background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
    color: #7f1d1d;
    border-left: 4px solid #dc2626;
}

.alert-warning {
    background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
    color: #78350f;
    border-left: 4px solid #f59e0b;
}

.alert-info {
    background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
    color: #1e3a8a;
    border-left: 4px solid #3b82f6;
}

/* ===================== PAGE EDIT SPECIFIC STYLES ===================== */

/* Page Edit Header */
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

.page-edit-header .btn i { 
    font-size: 0.9rem; 
}

.me-1 { 
    margin-right: 0.25rem !important; 
}

.me-2 { 
    margin-right: 0.5rem !important; 
}

/* Component Cards for Page Edit */
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

/* Specific Status Badges for Page Edit */
.status-badge {
    font-size: 0.7rem;
    padding: 0.2rem 0.4rem;
    border-radius: 0.25rem;
    margin: 0.125rem;
    display: inline-block;
    font-weight: 500;
}

.status-displayed { 
    background: linear-gradient(135deg, #222e3c 0%, #2b3947 100%); 
    color: #ffffff; 
    border: 1px solid rgba(34, 46, 60, 0.3); 
    box-shadow: 0 2px 8px rgba(34, 46, 60, 0.15); 
}

.status-hidden { 
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); 
    color: #ffffff; 
    border: 1px solid rgba(245, 158, 11, 0.3); 
    box-shadow: 0 2px 8px rgba(245, 158, 11, 0.15); 
}

/* Action Buttons for Page Edit */
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

.btn-cancel:hover { 
    background: #c1c7d0; 
    border-color: #c1c7d0; 
    transform: translateY(-1px); 
    color: #444; 
}

/* Add New Section Card */
.add-section-card { 
    border: 2px dashed rgba(34, 46, 60, 0.3) !important; 
    background: linear-gradient(145deg, #f8faff 0%, #e3f2fd 100%) !important; 
}

.add-section-card .card-top-section { 
    background: linear-gradient(135deg, #222e3c 0%, #2b3947 100%) !important; 
}

.add-section-card:hover { 
    border-color: rgba(34, 46, 60, 0.5) !important; 
    background: linear-gradient(145deg, #f3f8ff 0%, #ddeafa 100%) !important; 
}

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
    top: 0; 
    left: 0; 
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #222e3c 0%, #2b3947 50%, #222e3c 100%);
}

.no-sections-content { 
    position: relative; 
    z-index: 2; 
}

.no-sections-icon { 
    font-size: 3.5rem; 
    color: #222e3c; 
    margin-bottom: 1.5rem; 
    opacity: 0.8; 
    display: block; 
}

.no-sections-title { 
    color: #222e3c; 
    font-size: 1.5rem; 
    font-weight: 600; 
    margin-bottom: 1rem; 
    letter-spacing: -0.02em; 
}

.no-sections-text { 
    color: #64748b; 
    font-size: 1rem; 
    line-height: 1.6; 
    margin-bottom: 2rem; 
    max-width: 500px; 
    margin-left: auto; 
    margin-right: auto; 
}

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
    top: 0; 
    left: -100%;
    width: 100%; 
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    transition: left 0.5s;
}

.btn-add-first-section:hover {
    background: linear-gradient(135deg, #1a2530 0%, #222e3c 100%);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(34, 46, 60, 0.25);
    color: white;
}

.btn-add-first-section:hover::before { 
    left: 100%; 
}

.btn-add-first-section:active { 
    transform: translateY(0); 
}

/* Advanced Edit Modal Styles */
.modal-xl { 
    max-width: 90% !important; 
}

.modal-header.bg-primary {
    background: linear-gradient(135deg, #222e3c 0%, #2b3947 100%) !important;
    border-bottom: 1px solid rgba(255,255,255,0.1);
}

.modal-body .row.g-0 { 
    min-height: 600px; 
}

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

.form-control:focus, 
.form-range:focus {
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

/* Custom Card Styles for Edit Modal */
.modal .card {
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    border: none;
}

.modal .card-header {
    background: linear-gradient(135deg, #f8faff 0%, #e3f2fd 100%);
    border-bottom: 1px solid rgba(34, 46, 60, 0.1);
}

.modal .input-group-text {
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

/* Updated Dropdown Menu for Card Actions */
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
    display: flex; 
    align-items: center;
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

.dropdown-item.text-danger:hover { 
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%); 
    color: white; 
}

.dropdown { 
    position: relative; 
}

/* Position variations */
.dropdown-menu-up { 
    top: auto !important; 
    bottom: 100% !important; 
    transform: translateY(-4px) !important; 
}

.dropdown-menu-end { 
    right: 0 !important; 
    left: auto !important; 
}

.dropdown-toggle::after { 
    display: none !important; 
}

/* RTL Support for Page Edit */
[dir="rtl"] .me-1 { 
    margin-right: 0; 
    margin-left: 0.25rem !important; 
}

[dir="rtl"] .me-2 { 
    margin-right: 0; 
    margin-left: 0.5rem !important; 
}

[dir="rtl"] .order-indicator { 
    right: auto; 
    left: 12px; 
}

[dir="rtl"] .btn-add-section,
[dir="rtl"] .btn-save-page { 
    margin-right: 0; 
    margin-left: 10px; 
}

[dir="rtl"] .dropdown-item:hover { 
    transform: translateX(-4px); 
}

[dir="rtl"] .dropdown-item i { 
    margin-right: 0; 
    margin-left: 6px; 
}

[dir="rtl"] .no-sections-text { 
    text-align: right; 
}

[dir="rtl"] .btn-add-first-section { 
    font-family: 'Cairo', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
}

[dir="rtl"] .dropdown-menu { 
    right: auto; 
    left: 0; 
}

[dir="rtl"] .dropdown-menu-end { 
    right: auto !important; 
    left: 0 !important; 
}

/* Mobile Responsive for Page Edit */
@media (max-width: 768px) {
    .page-edit-header {
        padding: 1.5rem 1rem;
        margin-bottom: 1.5rem;
    }
    
    .page-edit-header h1 {
        font-size: 1.5rem;
        margin-bottom: 0.5rem;
    }
    
    .page-edit-header .btn {
        padding: 0.4rem 0.8rem;
        font-size: 0.85rem;
    }
    
    .component-card { 
        height: 260px; 
        margin-bottom: 1rem; 
    }
    
    .action-buttons { 
        text-align: center; 
        padding: 1rem; 
    }
    
    .btn-add-section, 
    .btn-save-page, 
    .btn-cancel { 
        width: 100%; 
        margin: 5px 0; 
    }
    
    .no-sections-message { 
        padding: 2rem 1rem; 
        margin: 1rem 0; 
    }
    
    .no-sections-icon { 
        font-size: 2.5rem; 
        margin-bottom: 1rem; 
    }
    
    .no-sections-title { 
        font-size: 1.25rem; 
        margin-bottom: 0.75rem; 
    }
    
    .no-sections-text { 
        font-size: 0.9rem; 
        margin-bottom: 1.5rem; 
    }
    
    .btn-add-first-section { 
        padding: 0.6rem 1.5rem; 
        font-size: 0.9rem; 
    }
    
    /* Modal adjustments for mobile */
    .modal-xl { 
        max-width: 95% !important; 
    }
    
    .modal-body .row.g-0 { 
        min-height: auto; 
    }
    
    .modal-body .col-lg-8, 
    .modal-body .col-lg-4 { 
        max-height: none; 
        overflow-y: visible;
    }
    
    /* RTL mobile adjustments */
    [dir="rtl"] .order-indicator { 
        right: auto; 
        left: 8px; 
    }
}
</style>
