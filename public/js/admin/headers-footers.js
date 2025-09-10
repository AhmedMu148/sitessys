/**
 * Headers & Footers Management JavaScript
 * Handles all client-side functionality for the admin headers/footers page
 */

// Wait for DOM to be ready
document.addEventListener('DOMContentLoaded', function() {
    // Initialize page
    initializeHeadersFooters();
});

/**
 * Reinitialize Feather icons (call after dynamic content changes)
 */
function reinitializeIcons() {
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
}

/**
 * Initialize the headers and footers management page
 */
function initializeHeadersFooters() {
    // Initialize tooltips
    initializeTooltips();
    
    // Initialize event listeners
    initializeEventListeners();
    
    // Auto-hide success alerts after 5 seconds
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert-success');
        alerts.forEach(alert => {
            if (alert) {
                alert.style.transition = 'opacity 0.5s ease';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            }
        });
    }, 5000);
}

/**
 * Initialize Bootstrap tooltips
 */
function initializeTooltips() {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
}

/**
 * Initialize event listeners
 */
function initializeEventListeners() {
    // Auth settings checkboxes
    const authHeaderCheckbox = document.getElementById('show_auth_header');
    const authFooterCheckbox = document.getElementById('show_auth_footer');
    
    if (authHeaderCheckbox) {
        authHeaderCheckbox.addEventListener('change', updateAuthSettings);
    }
    if (authFooterCheckbox) {
        authFooterCheckbox.addEventListener('change', updateAuthSettings);
    }
    
    // Page select change handler
    const pageSelect = document.getElementById('page-select');
    if (pageSelect) {
        pageSelect.addEventListener('change', function() {
            const selectedOption = this.selectedOptions[0];
            if (selectedOption && selectedOption.dataset.title) {
                document.getElementById('link-title').value = selectedOption.dataset.title;
            }
        });
    }
}

/**
 * Toggle between custom URL and page selection
 */
function toggleLinkSourceFields() {
    const source = document.getElementById('link-source').value;
    const customField = document.getElementById('custom-url-field');
    const pageField = document.getElementById('page-select-field');
    const externalCheckbox = document.getElementById('link-external');
    const urlInput = document.getElementById('link-url');
    
    if (source === 'page') {
        customField.style.display = 'none';
        pageField.style.display = 'block';
        externalCheckbox.checked = false;
        externalCheckbox.disabled = true;
        urlInput.required = false;
    } else {
        customField.style.display = 'block';
        pageField.style.display = 'none';
        externalCheckbox.disabled = false;
        urlInput.required = true;
    }
}

/**
 * Copy template function with loading state
 */
function copyTemplate(templateId, type) {
    if (!confirm('Create a custom copy of this template that you can modify?')) {
        return;
    }
    
    // Add loading state
    const button = event.target.closest('button');
    const originalText = button.innerHTML;
    button.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Copying...';
    button.disabled = true;
    
    fetch(getRoute('admin.headers-footers.create-user-copy'), {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': getCSRFToken()
        },
        body: JSON.stringify({
            template_id: templateId,
            type: type
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showSuccess(data.message);
            setTimeout(() => {
                location.reload();
            }, 1000);
        } else {
            showError(data.error || 'Failed to copy template');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showError('An error occurred while copying the template');
    })
    .finally(() => {
        // Restore button state
        button.innerHTML = originalText;
        button.disabled = false;
    });
}

/**
 * Show add link modal
 */
function showAddLinkModal(type) {
    const modal = document.getElementById('addLinkModal');
    const modalTitle = modal.querySelector('.modal-title');
    const form = document.getElementById('add-link-form');
    const linkTypeInput = document.getElementById('link-type');
    
    // Set modal title and form data
    modalTitle.textContent = `Add ${type.charAt(0).toUpperCase() + type.slice(1)} Link`;
    linkTypeInput.value = type;
    
    // Reset form
    form.reset();
    linkTypeInput.value = type;
    document.getElementById('link-active').checked = true;
    document.getElementById('link-source').value = 'custom';
    toggleLinkSourceFields();
    
    // Show modal
    const modalInstance = new bootstrap.Modal(modal);
    modalInstance.show();
}

/**
 * Add navigation link
 */
function addNavigationLink() {
    const form = document.getElementById('add-link-form');
    const formData = new FormData(form);
    
    let title = formData.get('title');
    let url = formData.get('url');
    
    // If page is selected, use page URL and title
    const source = document.getElementById('link-source').value;
    if (source === 'page') {
        const pageSelect = document.getElementById('page-select');
        url = pageSelect.value;
        if (!title && pageSelect.selectedOptions[0]) {
            title = pageSelect.selectedOptions[0].dataset.title;
            document.getElementById('link-title').value = title;
        }
    }
    
    // Validation
    if (!title || !url) {
        showError('Please provide both title and URL/page');
        return;
    }
    
    const data = {
        type: formData.get('type'),
        title: title,
        url: url,
        external: formData.has('external') && source !== 'page',
        active: formData.has('active')
    };
    
    // Add loading state to button
    const submitButton = event.target;
    const originalText = submitButton.innerHTML;
    submitButton.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Adding...';
    submitButton.disabled = true;
    
    fetch(getRoute('admin.headers-footers.add-navigation-link'), {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': getCSRFToken()
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const modal = bootstrap.Modal.getInstance(document.getElementById('addLinkModal'));
            modal.hide();
            showSuccess('Navigation link added successfully');
            setTimeout(() => location.reload(), 1000);
        } else {
            showError(data.error || 'Failed to add link');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showError('An error occurred while adding the link');
    })
    .finally(() => {
        // Restore button state
        submitButton.innerHTML = originalText;
        submitButton.disabled = false;
    });
}

/**
 * Remove navigation link
 */
function removeLink(type, index) {
    if (!confirm('Remove this navigation link?')) {
        return;
    }
    
    fetch(getRoute('admin.headers-footers.remove-navigation-link'), {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': getCSRFToken()
        },
        body: JSON.stringify({
            type: type,
            index: index
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showSuccess('Navigation link removed');
            setTimeout(() => location.reload(), 1000);
        } else {
            showError(data.error || 'Failed to remove link');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showError('An error occurred while removing the link');
    });
}

/**
 * Toggle navigation link status
 */
function toggleLinkStatus(type, index, active) {
    fetch(getRoute('admin.headers-footers.toggle-navigation-link'), {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': getCSRFToken()
        },
        body: JSON.stringify({
            type: type,
            index: index,
            active: active
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showSuccess('Link status updated');
            setTimeout(() => location.reload(), 1000);
        } else {
            showError(data.error || 'Failed to update link status');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showError('An error occurred while updating link status');
    });
}

/**
 * Update social media links
 */
function updateSocialMedia() {
    const form = document.getElementById('social-media-form');
    const formData = new FormData(form);
    
    const socialMedia = {};
    for (let [key, value] of formData.entries()) {
        if (key.startsWith('social_media[') && value.trim()) {
            const platform = key.match(/social_media\[(.+)\]/)[1];
            socialMedia[platform] = value.trim();
        }
    }
    
    // Add loading state to button
    const submitButton = event.target;
    const originalText = submitButton.innerHTML;
    submitButton.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Saving...';
    submitButton.disabled = true;
    
    fetch(getRoute('admin.headers-footers.update-social-media'), {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': getCSRFToken()
        },
        body: JSON.stringify({
            social_media: socialMedia
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showSuccess(data.message);
        } else {
            showError(data.error || 'Failed to update social media links');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showError('An error occurred while updating social media links');
    })
    .finally(() => {
        // Restore button state
        submitButton.innerHTML = originalText;
        submitButton.disabled = false;
    });
}

/**
 * Update authentication settings
 */
function updateAuthSettings() {
    const showAuthHeader = document.getElementById('show_auth_header').checked;
    const showAuthFooter = document.getElementById('show_auth_footer').checked;
    
    fetch(getRoute('admin.headers-footers.update-navigation'), {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': getCSRFToken()
        },
        body: JSON.stringify({
            show_auth_in_header: showAuthHeader,
            show_auth_in_footer: showAuthFooter,
            header_links: window.navigationConfig?.header_links || [],
            footer_links: window.navigationConfig?.footer_links || []
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showSuccess('Authentication settings updated');
        } else {
            showError(data.error || 'Failed to update auth settings');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showError('An error occurred while updating auth settings');
    });
}

/**
 * Utility Functions
 */

/**
 * Get CSRF token from meta tag
 */
function getCSRFToken() {
    const csrfMeta = document.querySelector('meta[name="csrf-token"]');
    return csrfMeta ? csrfMeta.getAttribute('content') : '';
}

/**
 * Get route URL (simplified version - you may need to implement Laravel route helper equivalent)
 */
function getRoute(routeName) {
    const routes = {
        'admin.headers-footers.create-user-copy': '/admin/headers-footers/create-user-copy',
        'admin.headers-footers.add-navigation-link': '/admin/headers-footers/add-navigation-link', 
        'admin.headers-footers.remove-navigation-link': '/admin/headers-footers/remove-navigation-link',
        'admin.headers-footers.toggle-navigation-link': '/admin/headers-footers/toggle-navigation-link',
        'admin.headers-footers.update-social-media': '/admin/headers-footers/update-social-media',
        'admin.headers-footers.update-navigation': '/admin/headers-footers/update-navigation'
    };
    return routes[routeName] || '#';
}

/**
 * Show success message
 */
function showSuccess(message) {
    createAlert('success', message);
}

/**
 * Show error message
 */
function showError(message) {
    createAlert('danger', message);
}

/**
 * Create alert message
 */
function createAlert(type, message) {
    // Remove existing alerts
    const existingAlerts = document.querySelectorAll('.alert.dynamic-alert');
    existingAlerts.forEach(alert => alert.remove());
    
    // Create new alert
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show dynamic-alert fade-in-up`;
    alertDiv.innerHTML = `
        <i class="feather-${type === 'success' ? 'check-circle' : 'alert-circle'} me-2"></i>${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    // Insert at the top of the content
    const container = document.querySelector('.container-fluid');
    const firstChild = container.querySelector('.mb-3');
    container.insertBefore(alertDiv, firstChild.nextSibling);
    
    // Reinitialize Feather icons
    reinitializeIcons();
    
    // Auto-remove after 5 seconds
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.style.transition = 'opacity 0.5s ease';
            alertDiv.style.opacity = '0';
            setTimeout(() => {
                if (alertDiv.parentNode) {
                    alertDiv.remove();
                }
            }, 500);
        }
    }, 5000);
}

/**
 * Validate URL format
 */
function isValidUrl(string) {
    try {
        new URL(string);
        return true;
    } catch (_) {
        // Check if it's a relative path
        return string.startsWith('/') || string.startsWith('#');
    }
}

/**
 * Debounce function for performance
 */
function debounce(func, wait, immediate) {
    let timeout;
    return function executedFunction() {
        const context = this;
        const args = arguments;
        const later = function() {
            timeout = null;
            if (!immediate) func.apply(context, args);
        };
        const callNow = immediate && !timeout;
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
        if (callNow) func.apply(context, args);
    };
}

/**
 * Handle form submission with loading states
 */
function handleFormSubmission(form, button, url, data, successCallback) {
    // Add loading state
    const originalText = button.innerHTML;
    button.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Processing...';
    button.disabled = true;
    
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': getCSRFToken()
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (successCallback) {
                successCallback(data);
            } else {
                showSuccess(data.message || 'Operation completed successfully');
                setTimeout(() => location.reload(), 1000);
            }
        } else {
            showError(data.error || 'Operation failed');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showError('An error occurred during the operation');
    })
    .finally(() => {
        // Restore button state
        button.innerHTML = originalText;
        button.disabled = false;
    });
}
// Pass available pages to JavaScript
// window.availablePages should be set in your Blade template, for example:
// <script>window.availablePages = @json($availablePages);</script>
// Remove this line from the JS file to avoid syntax errors.

// Section Templates Functions
function addSectionToPage(templateId) {
    // Set the template ID in the modal
    document.getElementById('section-template-id').value = templateId;
    
    // Update the info text
    const sectionInfo = document.getElementById('section-info');
    sectionInfo.textContent = `Adding section template ID: ${templateId} to the selected page.`;
    
    // Show the modal
    const modal = new bootstrap.Modal(document.getElementById('addSectionModal'));
    modal.show();
}

function confirmAddSection() {
    const templateId = document.getElementById('section-template-id').value;
    const pageId = document.getElementById('selected-page').value;
    const position = document.getElementById('section-position').value;
    const sortOrder = document.getElementById('sort-order').value;
    
    if (!templateId || !pageId) {
        alert('Please select a page.');
        return;
    }
    
    // Show loading state
    const submitButton = document.querySelector('#addSectionModal .btn-primary');
    const originalText = submitButton.textContent;
    submitButton.textContent = 'Adding...';
    submitButton.disabled = true;
    
    // Get CSRF token from the form
    const csrfToken = document.querySelector('#add-section-form input[name="_token"]').value;
    
    // Prepare data for API call
    const data = {
        _token: csrfToken,
        template_id: parseInt(templateId),
        page_id: parseInt(pageId),
        position: position,
        sort_order: position === 'custom' && sortOrder ? parseInt(sortOrder) : null
    };
    
    console.log('Sending data:', data); // Debug log
    
    // Make API call to add section to page
    fetch('/admin/sections/add-to-page', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => {
        console.log('Response status:', response.status); // Debug log
        console.log('Response headers:', response.headers); // Debug log
        console.log('Response ok:', response.ok); // Debug log
        
        // Check if response is actually JSON
        const contentType = response.headers.get("content-type");
        if (contentType && contentType.indexOf("application/json") !== -1) {
            return response.json();
        } else {
            console.log('Response is not JSON, returning text'); // Debug log
            return response.text();
        }
    })
    .then(responseData => {
        console.log('Response data:', responseData); // Debug log
        console.log('Response data type:', typeof responseData); // Debug log
        
        // Handle different response types
        let data = responseData;
        if (typeof responseData === 'string') {
            try {
                data = JSON.parse(responseData);
            } catch (e) {
                console.error('Failed to parse JSON:', e);
                console.log('Raw response:', responseData);
                alert('Server returned invalid response: ' + responseData.substring(0, 200));
                return;
            }
        }
        if (data.success) {
            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('addSectionModal'));
            modal.hide();
            
            // Show success message
            alert(`Section "${data.data.template_name}" added to page "${data.data.page_name}" successfully!`);
            
            // Reset form
            document.getElementById('add-section-form').reset();
        } else {
            alert('Error: ' + (data.message || 'Failed to add section to page'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while adding the section to the page.');
    })
    .finally(() => {
        // Restore button state
        submitButton.textContent = originalText;
        submitButton.disabled = false;
    });
}

// Handle position selection change
document.addEventListener('DOMContentLoaded', function() {
    const positionSelect = document.getElementById('section-position');
    const customPositionField = document.getElementById('custom-position-field');
    
    if (positionSelect) {
        positionSelect.addEventListener('change', function() {
            if (this.value === 'custom') {
                customPositionField.style.display = 'block';
            } else {
                customPositionField.style.display = 'none';
            }
        });
    }
});

function createNewSection() {
    // Implementation for creating new section
    console.log('Creating new section template');
    
    // Redirect to create page
    window.location.href = '/admin/templates/create?type=section';
}

function refreshSections() {
    // Implementation for refreshing sections
    console.log('Refreshing sections');
    location.reload();
}

// Initialize section functionality
document.addEventListener('DOMContentLoaded', function() {
    // Initialize feather icons for any new icons
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
});

// ============ Dropdown Functions for Template Cards ============

// Show/Hide card actions on hover
function showCardActions(card) {
    const actions = card.querySelector('.card-actions');
    if (actions) {
        actions.style.opacity = '1';
        actions.style.transform = 'translateY(0)';
    }
}

function hideCardActions(card) {
    const actions = card.querySelector('.card-actions');
    if (actions) {
        actions.style.opacity = '1'; // Keep visible for accessibility
        actions.style.transform = 'translateY(0)';
    }
}

// Activate template function
function activateTemplate(templateId, type) {
    if (confirm(`Are you sure you want to activate this ${type} template?`)) {
        // Create a form and submit it
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/headers-footers/activate/${templateId}`;
        
        // Add CSRF token
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        form.appendChild(csrfInput);
        
        document.body.appendChild(form);
        form.submit();
    }
}

// Edit template function
function editTemplate(templateId) {
    window.location.href = `/admin/templates/${templateId}/edit`;
}

// Preview template function
function previewTemplate(templateId) {
    window.open(`/admin/templates/${templateId}/preview`, '_blank');
}

// Duplicate template function
function duplicateTemplate(templateId) {
    if (confirm('Do you want to create a copy of this template?')) {
        fetch(`/admin/templates/${templateId}/duplicate`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Template duplicated successfully!');
                location.reload();
            } else {
                alert('Error: ' + (data.message || 'Failed to duplicate template'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while duplicating the template.');
        });
    }
}

// Confirm delete template function
function confirmDeleteTemplate(templateId) {
    if (confirm('Are you sure you want to delete this template? This action cannot be undone.')) {
        deleteTemplate(templateId);
    }
}

// Delete template function
function deleteTemplate(templateId) {
    fetch(`/admin/headers-footers/${templateId}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Template deleted successfully!');
            location.reload();
        } else {
            alert('Error: ' + (data.message || 'Failed to delete template'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while deleting the template.');
    });
}

// View template details function
function viewTemplateDetails(templateId) {
    // You can implement a modal or redirect to details page
    window.open(`/admin/templates/${templateId}`, '_blank');
}

// Initialize dropdown positioning (similar to shared admin scripts)
document.addEventListener('DOMContentLoaded', function() {
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
            const cardRect = e.target.closest('.template-card')?.getBoundingClientRect();
            
            if (btnRect.bottom + menuRect.height > window.innerHeight - 20) {
                menu.classList.add('dropdown-menu-up');
            }
            
            if (cardRect && (btnRect.left + menuRect.width > cardRect.right)) {
                menu.classList.add('dropdown-menu-end');
            }
        }, 10);
    });

    document.addEventListener('hide.bs.dropdown', e => {
        const menu = e.target.querySelector('.dropdown-menu');
        if (menu) {
            menu.classList.remove('dropdown-menu-up');
        }
    });
});
