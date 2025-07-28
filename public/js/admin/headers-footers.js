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
