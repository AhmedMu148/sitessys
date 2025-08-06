{{-- Shared Admin Panel JavaScript for Templates, Pages, and Components --}}
<script>
/* ===================== SHARED ADMIN PANEL JAVASCRIPT ===================== */

// Global variables
let currentLang = 'en';
let searchTimeout;

$(document).ready(function() {
    // Initialize everything
    initializeAdminPanel();
});

// Main initialization function
function initializeAdminPanel() {
    // Initialize Feather Icons
    feather.replace();
    
    // Language Management
    currentLang = $('html').attr('lang') || 'en';
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
    
    // Initialize animations
    initializeAnimations();
    
    // Initialize dropdown positioning
    initializeDropdowns();
    
    // Initialize search and filter functionality
    initializeSearchAndFilter();
    
    // Initialize keyboard shortcuts
    initializeKeyboardShortcuts();
    
    // Initialize tooltips and popovers
    initializeTooltips();
}

// ===================== Animation Functions =====================
function initializeAnimations() {
    // Animate cards on load
    $('.page-card, .template-card').css('opacity', '1');
    
    // Stagger animation for multiple cards
    $('.page-item, .template-item').each(function(index) {
        $(this).css('animation-delay', (index * 0.1) + 's');
    });
    
    // Add hover animations to buttons
    $('.btn').hover(
        function() {
            $(this).addClass('animate__animated animate__pulse');
        },
        function() {
            $(this).removeClass('animate__animated animate__pulse');
        }
    );
}

function animateFilteredResults() {
    $('.page-item:visible, .template-item:visible').each(function(index) {
        $(this).css({
            'animation': 'fadeInUp 0.3s ease-out forwards',
            'animation-delay': (index * 0.05) + 's'
        });
    });
}

// ===================== Dropdown Functions =====================
function initializeDropdowns() {
    // Enhanced dropdown positioning
    setTimeout(() => {
        document.querySelectorAll('[data-bs-toggle="dropdown"]').forEach(el => {
            new bootstrap.Dropdown(el, {
                popperConfig: {
                    strategy: 'fixed',
                    modifiers: [
                        { name: 'preventOverflow', options: { boundary: document.body } }
                    ]
                }
            });
        });
    }, 300);

    // Enhanced positioning classes toggle
    document.addEventListener('show.bs.dropdown', function(e) {
        const menu = e.target.querySelector('.dropdown-menu');
        if (!menu) return;
        
        menu.classList.remove('dropdown-menu-up', 'dropdown-menu-end');
        
        setTimeout(() => {
            const btnRect = e.target.querySelector('[data-bs-toggle="dropdown"]').getBoundingClientRect();
            const menuRect = menu.getBoundingClientRect();
            const cardRect = e.target.closest('.template-card, .page-card')?.getBoundingClientRect();
            
            if (btnRect.bottom + menuRect.height > window.innerHeight - 20) {
                menu.classList.add('dropdown-menu-up');
            }
            
            if (cardRect && (btnRect.left + menuRect.width > cardRect.right)) {
                menu.classList.add('dropdown-menu-end');
            }
            
            if (document.dir === 'rtl' || document.documentElement.dir === 'rtl') {
                if (cardRect && (btnRect.right - menuRect.width < cardRect.left)) {
                    menu.classList.remove('dropdown-menu-end');
                }
            }
        }, 10);
    });

    document.addEventListener('hide.bs.dropdown', e => {
        const menu = e.target.querySelector('.dropdown-menu');
        if (menu) {
            menu.classList.remove('dropdown-menu-up');
        }
    });
}

// ===================== Search and Filter Functions =====================
function initializeSearchAndFilter() {
    // Search functionality with debounce
    $(document).on('input', '#searchPages, #globalTemplateSearch', function() {
        clearTimeout(searchTimeout);
        const searchTerm = $(this).val().toLowerCase();
        
        // Add visual feedback
        $(this).css('border-color', '#ffc107');
        
        searchTimeout = setTimeout(() => {
            filterItems();
            $(this).css('border-color', '#ced4da');
        }, 300);
    });
    
    // Filter functionality
    $(document).on('change', '#filterTheme, #filterStatus, #filterNav, #globalTemplateFilter, #globalStatusFilter', function() {
        // Add visual feedback
        $(this).css('border-color', '#28a745');
        filterItems();
        animateFilteredResults();
        
        setTimeout(() => {
            $(this).css('border-color', '#ced4da');
        }, 500);
    });
}

function filterItems() {
    const isTemplatePage = $('#globalTemplateSearch').length > 0;
    
    if (isTemplatePage) {
        filterTemplates();
    } else {
        filterPages();
    }
}

function filterPages() {
    const searchTerm = $('#searchPages').val().toLowerCase();
    const filterTheme = $('#filterTheme').val();
    const filterStatus = $('#filterStatus').val();
    const filterNav = $('#filterNav').val();
    
    let visibleCount = 0;
    
    $('.page-item').each(function() {
        const $item = $(this);
        const name = ($item.data('name') || '').toString().toLowerCase();
        const theme = $item.data('theme') || '';
        const status = $item.data('status') || '';
        const nav = $item.data('nav') || '';
        
        let showItem = true;
        
        // Search filter
        if (searchTerm && !name.includes(searchTerm)) {
            showItem = false;
        }
        
        // Theme filter
        if (filterTheme && theme !== filterTheme) {
            showItem = false;
        }
        
        // Status filter  
        if (filterStatus && status !== filterStatus) {
            showItem = false;
        }
        
        // Navigation filter
        if (filterNav && nav !== filterNav) {
            showItem = false;
        }
        
        if (showItem) {
            $item.show().css('display', 'block');
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

function filterTemplates() {
    const searchTerm = $('#globalTemplateSearch').val().toLowerCase();
    const selectedType = $('#globalTemplateFilter').val();
    const selectedStatus = $('#globalStatusFilter').val();

    // Show loading state
    showFilteringState(true);

    // Filter each section
    filterTemplateSection('header-templates', searchTerm, selectedType, selectedStatus, 'header');
    filterTemplateSection('section-templates', searchTerm, selectedType, selectedStatus, 'section');
    filterTemplateSection('footer-templates', searchTerm, selectedType, selectedStatus, 'footer');

    // Update results count
    updateResultsCount();
    
    // Hide loading state
    setTimeout(() => showFilteringState(false), 200);
}

function filterTemplateSection(sectionId, searchTerm, selectedType, selectedStatus, cardType) {
    const section = document.getElementById(sectionId);
    if (!section) return;

    const cards = section.nextElementSibling ? section.nextElementSibling.querySelectorAll('.col-lg-4, .col-md-6') : [];
    
    cards.forEach(card => {
        const templateCard = card.querySelector('.template-card');
        const cardTitle = card.querySelector('.card-top-text, .image-overlay-text');
        const cardDesc = card.querySelector('.card-bottom-text');
        const activeBadge = card.querySelector('.badge.bg-success');
        
        const titleText = cardTitle ? cardTitle.textContent.toLowerCase() : '';
        const descText = cardDesc ? cardDesc.textContent.toLowerCase() : '';
        const isActive = activeBadge ? true : false;
        
        // Check filters
        const matchesSearch = !searchTerm || titleText.includes(searchTerm) || descText.includes(searchTerm);
        const matchesType = !selectedType || selectedType === cardType;
        const matchesStatus = !selectedStatus || 
            (selectedStatus === 'active' && isActive) || 
            (selectedStatus === 'inactive' && !isActive);
        
        const shouldShow = matchesSearch && matchesType && matchesStatus;
        
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

function showFilteringState(isFiltering) {
    const pageActions = document.querySelector('.page-actions, .search-filter-section');
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

function updateResultsCount() {
    // Implementation for updating results count
    const allCards = document.querySelectorAll('.col-lg-4, .col-md-6');
    const visibleCards = Array.from(allCards).filter(card => 
        card.style.display !== 'none' && window.getComputedStyle(card).display !== 'none'
    );
    
    let resultsIndicator = document.getElementById('results-indicator');
    if (!resultsIndicator) {
        resultsIndicator = document.createElement('div');
        resultsIndicator.id = 'results-indicator';
        resultsIndicator.className = 'alert alert-info mt-3 text-center';
        resultsIndicator.style.cssText = 'margin: 20px 0; padding: 10px; border-radius: 8px; font-weight: 500;';
        
        const container = document.querySelector('.container-fluid');
        const pageActions = container.querySelector('.page-actions, .search-filter-section');
        if (pageActions) {
            pageActions.parentNode.insertBefore(resultsIndicator, pageActions.nextSibling);
        }
    }

    const hasActiveFilters = $('#globalTemplateSearch').val() || $('#globalTemplateFilter').val() || $('#globalStatusFilter').val() ||
                            $('#searchPages').val() || $('#filterTheme').val() || $('#filterStatus').val() || $('#filterNav').val();

    if (hasActiveFilters) {
        resultsIndicator.innerHTML = `
            <i class="fas fa-search me-2"></i>
            Found ${visibleCards.length} items
        `;
        resultsIndicator.style.display = 'block';
    } else {
        resultsIndicator.style.display = 'none';
    }
}

function showNoResults() {
    if (!$('#noResultsMessage').length) {
        const noResultsHtml = `
            <div class="col-12" id="noResultsMessage">
                <div class="empty-state">
                    <i class="fas fa-search fs-1 mb-3" style="color: #bbdefb;"></i>
                    <h3 class="text-muted mb-3">
                        <span class="en">No Results Found</span>
                        <span class="ar" style="display: none;">لم يتم العثور على نتائج</span>
                    </h3>
                    <p class="text-muted">
                        <span class="en">Try adjusting your search terms or filters</span>
                        <span class="ar" style="display: none;">جرب تعديل مصطلحات البحث أو المرشحات</span>
                    </p>
                </div>
            </div>
        `;
        $('#pagesGrid').append(noResultsHtml);
        feather.replace();
        
        // Show appropriate language
        if (currentLang === 'ar') {
            $('#noResultsMessage .ar').show();
            $('#noResultsMessage .en').hide();
        }
    }
}

function hideNoResults() {
    $('#noResultsMessage').remove();
}

function clearAllFilters() {
    // Clear all filter inputs
    $('#searchPages, #globalTemplateSearch').val('');
    $('#filterTheme, #filterStatus, #filterNav, #globalTemplateFilter, #globalStatusFilter').val('');
    
    // Add visual feedback to clear button
    const clearBtn = event.target.closest('button');
    if (clearBtn) {
        const originalText = clearBtn.innerHTML;
        clearBtn.innerHTML = '<i class="fas fa-check"></i>';
        clearBtn.classList.add('btn-success');
        clearBtn.classList.remove('btn-outline-secondary');
        
        setTimeout(() => {
            clearBtn.innerHTML = originalText;
            clearBtn.classList.remove('btn-success');
            clearBtn.classList.add('btn-outline-secondary');
            feather.replace(); // Re-initialize feather icons
        }, 1000);
    }
    
    // Refilter items
    filterItems();
    
    // Show success message
    showAlert('success', currentLang === 'ar' ? 'تم مسح جميع المرشحات' : 'All filters cleared', 2000);
}

// ===================== Toggle Functions =====================
function toggleStatus(element, type = 'page') {
    const $btn = $(element);
    const itemId = $btn.data('id');
    const currentStatus = $btn.data('status');
    const $card = $btn.closest('.page-item, .template-item');
    const $statusIndicator = $card.find('.status-indicator');
    
    // Add loading animation
    $card.addClass('loading');
    $btn.prop('disabled', true);
    
    // Add button loading animation
    const originalContent = $btn.html();
    $btn.html(`
        <i class="align-middle me-2 fa fa-spinner fa-spin"></i>
        <span class="en">Processing...</span>
        <span class="ar" style="display: none;">جارٍ المعالجة...</span>
    `);
    
    // Show appropriate language in button
    if (currentLang === 'ar') {
        $btn.find('.ar').show();
        $btn.find('.en').hide();
    }
    
    $.ajax({
        url: `/admin/${type}s/${itemId}/toggle-status`,
        type: 'POST',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content')
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
                    $statusIndicator.removeClass('animate-pulse');
                    if (newStatus) {
                        $statusIndicator.removeClass('status-inactive').addClass('status-active');
                    } else {
                        $statusIndicator.removeClass('status-active').addClass('status-inactive');
                    }
                }, 200);
                
                // Show success notification
                showAlert('success', response.message);
            } else {
                showAlert('error', response.message || 'An error occurred');
            }
        },
        error: function(xhr) {
            const message = xhr.responseJSON?.message || 'An error occurred';
            showAlert('error', message);
        },
        complete: function() {
            setTimeout(() => {
                $card.removeClass('loading');
                $btn.prop('disabled', false).html(originalContent);
                feather.replace();
                
                // Show appropriate language
                if (currentLang === 'ar') {
                    $btn.find('.ar').show();
                    $btn.find('.en').hide();
                }
            }, 300);
        }
    });
}

function toggleNavigation(element) {
    const $btn = $(element);
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
            _token: $('meta[name="csrf-token"]').attr('content')
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
                        $card.find('.card-footer .d-flex').prepend('<span class="nav-indicator">In Navigation</span>');
                    }
                    $btn.find('.en').text('Remove from Nav');
                    $btn.find('.ar').text('إزالة من التنقل');
                } else {
                    $navIndicator.fadeOut(300, function() {
                        $(this).remove();
                    });
                    $btn.find('.en').text('Add to Nav');
                    $btn.find('.ar').text('إضافة للتنقل');
                }
                
                // Show success message
                showAlert('success', response.message);
            } else {
                showAlert('error', response.message || 'An error occurred');
            }
        },
        error: function(xhr) {
            const message = xhr.responseJSON?.message || 'An error occurred';
            showAlert('error', message);
        },
        complete: function() {
            $card.removeClass('loading');
            $btn.prop('disabled', false);
        }
    });
}

// ===================== Delete Functions =====================
function confirmDelete(itemId, itemType = 'page') {
    // Store item info globally
    window.itemToDelete = { id: itemId, type: itemType };
    
    // Update modal content based on type
    const itemName = itemType === 'page' ? (currentLang === 'ar' ? 'الصفحة' : 'page') : 
                     itemType === 'template' ? (currentLang === 'ar' ? 'القالب' : 'template') : 
                     (currentLang === 'ar' ? 'العنصر' : 'item');
    
    $('#deleteModal .modal-title').html(`
        <i class="fas fa-exclamation-triangle me-2"></i>
        <span class="en">Delete ${itemName.charAt(0).toUpperCase() + itemName.slice(1)}</span>
        <span class="ar" style="display: none;">حذف ${itemName}</span>
    `);
    
    $('#deleteModal .modal-body p').html(`
        <span class="en">Are you sure you want to delete this ${itemName}? This action cannot be undone.</span>
        <span class="ar" style="display: none;">هل أنت متأكد من حذف هذا ${itemName}؟ لا يمكن التراجع عن هذا الإجراء.</span>
    `);
    
    // Show appropriate language
    if (currentLang === 'ar') {
        $('#deleteModal .ar').show();
        $('#deleteModal .en').hide();
    }
    
    $('#deleteModal').modal('show');
}

function executeDelete() {
    if (!window.itemToDelete) return;
    
    const { id, type } = window.itemToDelete;
    const $btn = $('#confirmDelete');
    
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
        url: `/admin/${type}s/${id}`,
        type: 'DELETE',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                // Remove the card from view
                const $card = $(`.page-item[data-id="${id}"], .template-item[data-id="${id}"]`);
                $card.fadeOut(300, function() {
                    $(this).remove();
                    
                    // Update counts if they exist
                    const currentTotal = parseInt($('#totalPages, #totalTemplates').text()) - 1;
                    const currentVisible = parseInt($('#visiblePages, #visibleTemplates').text()) - 1;
                    $('#totalPages, #totalTemplates').text(currentTotal);
                    $('#visiblePages, #visibleTemplates').text(currentVisible);
                    
                    // Show empty state if no items left
                    if (currentTotal === 0) {
                        location.reload();
                    }
                });
                
                $('#deleteModal').modal('hide');
                showAlert('success', response.message);
            } else {
                showAlert('error', response.message || 'An error occurred');
            }
        },
        error: function(xhr) {
            const message = xhr.responseJSON?.message || 'An error occurred while deleting the item';
            showAlert('error', message);
        },
        complete: function() {
            $btn.prop('disabled', false).html(`
                <i class="align-middle me-2" data-feather="trash-2"></i>
                <span class="en">Delete</span>
                <span class="ar" style="display: none;">حذف</span>
            `);
            feather.replace();
            
            // Show appropriate language
            if (currentLang === 'ar') {
                $btn.find('.ar').show();
                $btn.find('.en').hide();
            }
            
            window.itemToDelete = null;
        }
    });
}

// ===================== Alert and Notification Functions =====================
function showAlert(type, message, duration = 5000) {
    const iconMap = {
        'success': '<i class="fas fa-check me-2"></i>',
        'error': '<i class="fas fa-times me-2"></i>',
        'warning': '<i class="fas fa-exclamation-triangle me-2"></i>',
        'info': '<i class="fas fa-info-circle me-2"></i>'
    };
    
    const alertClass = type === 'success' ? 'alert-success' : 
                      type === 'error' ? 'alert-danger' : 
                      type === 'warning' ? 'alert-warning' : 'alert-info';
    
    const icon = iconMap[type] || iconMap['info'];
    
    // Create alert with enhanced styling
    const borderColor = type === 'error' ? '#dc3545' : 
                       type === 'success' ? '#28a745' : 
                       type === 'warning' ? '#ffc107' : '#007bff';
    
    const alert = $(`
        <div class="alert ${alertClass} alert-dismissible fade show" 
             role="alert" 
             style="position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 300px; max-width: 500px; 
                    box-shadow: 0 4px 20px rgba(0,0,0,0.15); border-left: 4px solid ${borderColor}; 
                    border-radius: 0.75rem; animation: slideInRight 0.3s ease-out;">
            ${icon}
            <span style="font-weight: 500;">${message}</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    `);
    
    // Remove existing alerts of the same type
    $(`.alert-${alertClass.split('-')[1]}`).remove();
    
    // Add new alert
    $('body').append(alert);
    
    // Auto-remove after duration
    const actualDuration = type === 'error' ? duration * 2 : duration;
    setTimeout(() => {
        alert.fadeOut(300, function() {
            $(this).remove();
        });
    }, actualDuration);
    
    // Add CSS for slide animation if not exists
    if (!$('#slideAnimationCSS').length) {
        $('head').append(`
            <style id="slideAnimationCSS">
                @keyframes slideInRight {
                    from { transform: translateX(100%); opacity: 0; }
                    to { transform: translateX(0); opacity: 1; }
                }
            </style>
        `);
    }
}

function showAnimatedNotification(type, message) {
    showAlert(type, message);
}

// ===================== Keyboard Shortcuts =====================
function initializeKeyboardShortcuts() {
    $(document).on('keydown', function(e) {
        // Ctrl/Cmd + K to focus search
        if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
            e.preventDefault();
            const searchInput = $('#searchPages, #globalTemplateSearch').first();
            if (searchInput.length) {
                searchInput.focus().select();
            }
        }
        
        // Ctrl/Cmd + T to focus type filter
        if ((e.ctrlKey || e.metaKey) && e.key === 't') {
            e.preventDefault();
            const typeFilter = $('#filterTheme, #globalTemplateFilter').first();
            if (typeFilter.length) {
                typeFilter.focus();
            }
        }
        
        // Ctrl/Cmd + S to focus status filter
        if ((e.ctrlKey || e.metaKey) && e.key === 's') {
            e.preventDefault();
            const statusFilter = $('#filterStatus, #globalStatusFilter').first();
            if (statusFilter.length) {
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
            const activeElement = $(document.activeElement);
            if (activeElement.is('#searchPages, #globalTemplateSearch') && activeElement.val()) {
                activeElement.val('');
                filterItems();
                activeElement.blur();
            } else if (activeElement.is('#filterTheme, #filterStatus, #filterNav, #globalTemplateFilter, #globalStatusFilter') && activeElement.val()) {
                activeElement.val('');
                filterItems();
                activeElement.blur();
            }
        }
    });
}

// ===================== Tooltip and Popover Functions =====================
function initializeTooltips() {
    // Initialize Bootstrap tooltips
    $('[data-bs-toggle="tooltip"]').tooltip();
    
    // Initialize Bootstrap popovers
    $('[data-bs-toggle="popover"]').popover();
    
    // Add tooltips to action buttons
    $('.actions-btn').attr('title', currentLang === 'ar' ? 'المزيد من الخيارات' : 'More options');
    $('.btn-create').attr('title', currentLang === 'ar' ? 'إنشاء عنصر جديد' : 'Create new item');
    
    // Refresh tooltips
    $('[title]').tooltip();
}

// ===================== Utility Functions =====================
function previewItem(id, type = 'page') {
    const url = type === 'page' ? `/admin/pages/${id}/preview` : `/admin/templates/${id}/preview`;
    window.open(url, '_blank');
}

function editItem(id, type = 'page') {
    const url = type === 'page' ? `/admin/pages/${id}/edit` : `/admin/templates/${id}/edit`;
    window.location.href = url;
}

function duplicateItem(id, type = 'page') {
    showAlert('info', currentLang === 'ar' ? 'جارٍ تكرار العنصر...' : 'Duplicating item...');
    
    $.ajax({
        url: `/admin/${type}s/${id}/duplicate`,
        type: 'POST',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                showAlert('success', response.message);
                setTimeout(() => location.reload(), 1500);
            } else {
                showAlert('error', response.message || 'An error occurred');
            }
        },
        error: function(xhr) {
            const message = xhr.responseJSON?.message || 'An error occurred while duplicating the item';
            showAlert('error', message);
        }
    });
}

// ===================== Event Handlers =====================
$(document).ready(function() {
    // Toggle status handlers
    $(document).on('click', '.toggle-status', function(e) {
        e.preventDefault();
        toggleStatus(this);
    });
    
    // Toggle navigation handlers
    $(document).on('click', '.toggle-nav', function(e) {
        e.preventDefault();
        toggleNavigation(this);
    });
    
    // Delete confirmation handlers
    $(document).on('click', '.delete-page, .delete-template', function(e) {
        e.preventDefault();
        const itemId = $(this).data('id');
        const itemType = $(this).hasClass('delete-page') ? 'page' : 'template';
        confirmDelete(itemId, itemType);
    });
    
    // Execute delete handler
    $(document).on('click', '#confirmDelete', function() {
        executeDelete();
    });
    
    // Clear filters handler
    $(document).on('click', '.btn-outline-secondary', function(e) {
        if ($(this).text().includes('Clear') || $(this).text().includes('مسح')) {
            e.preventDefault();
            clearAllFilters();
        }
    });
    
    // Reset delete modal when closed
    $('#deleteModal').on('hidden.bs.modal', function() {
        window.itemToDelete = null;
    });
    
    // Handle image previews
    $(document).on('change', 'input[type="file"]', function(e) {
        const file = e.target.files[0];
        const previewContainer = $(this).siblings('.image-preview');
        
        if (file && previewContainer.length) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewContainer.html(`
                    <img src="${e.target.result}" alt="Preview" 
                         style="max-width: 100%; max-height: 150px; border-radius: 0.375rem; border: 1px solid #dee2e6;">
                `);
            };
            reader.readAsDataURL(file);
        }
    });
    
    // Handle layout preview images
    $('.card-top-image img').on('error', function() {
        $(this).hide();
        $(this).siblings('.card-top-fallback').show();
    }).on('load', function() {
        $(this).siblings('.card-top-fallback').hide();
    });
    
    // Initialize everything
    setTimeout(() => {
        $('.card-top-image img').each(function() {
            if (!this.src || this.src === '' || this.src === window.location.href) {
                $(this).trigger('error');
            }
        });
    }, 100);
});

// ===================== Export Functions for Global Use =====================
window.AdminPanelUtils = {
    showAlert,
    showAnimatedNotification,
    filterItems,
    clearAllFilters,
    toggleStatus,
    toggleNavigation,
    confirmDelete,
    executeDelete,
    previewItem,
    editItem,
    duplicateItem,
    initializeAdminPanel
};
</script>
