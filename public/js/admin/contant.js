// These variables should be set in a Blade template, not directly in a JS file.
// Remove these lines from this JS file and instead add the following to your Blade view before including this JS file:
document.addEventListener('DOMContentLoaded', function(){
    if (typeof feather !== 'undefined') { feather.replace(); }
    const csrfMeta = document.querySelector('meta[name="csrf-token"]');
if (!csrfMeta || !csrfMeta.content || csrfMeta.content.length < 10) {
  console.error('CSRF token is missing or invalid.');
  // ممكن تعرض Alert لطيف للمستخدم لو حابب
}

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
    
    // Add event listeners for modal opening to refresh data
    setupModalEventListeners();
});

function setupModalEventListeners() {
    // Header Navigation Modal
    const headerNavModal = document.getElementById('headerNavModal');
    if (headerNavModal) {
        headerNavModal.addEventListener('show.bs.modal', function () {
            refreshHeaderNavigationData();
        });
    }
    
    // Footer Navigation Modal
    const footerNavModal = document.getElementById('footerNavModal');
    if (footerNavModal) {
        footerNavModal.addEventListener('show.bs.modal', function () {
            refreshFooterNavigationData();
        });
    }
    
    // Social Media Modal
    const socialMediaModal = document.getElementById('socialMediaModal');
    if (socialMediaModal) {
        socialMediaModal.addEventListener('show.bs.modal', function () {
            refreshSocialMediaData();
        });
    }
}

function refreshHeaderNavigationData() {
    // Just update the checkbox with current data
    const showAuthHeaderCheckbox = document.getElementById('show_auth_header');
    if (showAuthHeaderCheckbox && window.navigationConfig) {
        showAuthHeaderCheckbox.checked = window.navigationConfig.show_auth_in_header || false;
    }
}

function refreshFooterNavigationData() {
    // Rebuild footer links with current data
    if (window.navigationConfig && window.navigationConfig.footer_links) {
        rebuildFooterLinksDisplay(window.navigationConfig.footer_links);
    } else {
        // Show empty state
        const footerLinksContainer = document.getElementById('footer-links');
        footerLinksContainer.innerHTML = `
            <div class="alert alert-info">
                <i class="align-middle me-2" data-feather="info"></i>
                No footer navigation links configured yet. Add your first link below.
            </div>
        `;
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    }
    
    // Update auth checkbox
    const showAuthFooterCheckbox = document.getElementById('show_auth_footer');
    if (showAuthFooterCheckbox && window.navigationConfig) {
        showAuthFooterCheckbox.checked = window.navigationConfig.show_auth_in_footer || false;
    }
}

function refreshSocialMediaData() {
    // Rebuild social media form with current data
    if (window.socialMediaConfig) {
        rebuildSocialMediaForm(window.socialMediaConfig);
    } else {
        // Show empty form
        rebuildSocialMediaForm({});
    }
}

function rebuildFooterLinksDisplay(footerLinks) {
    const modalBody = document.querySelector('#footerNavModal .modal-body');
    const footerLinksContainer = document.getElementById('footer-links');
    
    // Clear existing links
    footerLinksContainer.innerHTML = '';
    
    if (!footerLinks || footerLinks.length === 0) {
        footerLinksContainer.innerHTML = `
            <div class="alert alert-info">
                <i class="align-middle me-2" data-feather="info"></i>
                No footer navigation links configured yet. Add your first link below.
            </div>
        `;
        // Re-initialize feather icons
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
        return;
    }
    
    // Add each link
    footerLinks.forEach((link, index) => {
        const linkHtml = `
            <div class="nav-link-item mb-3" data-index="${index}">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h6 class="card-title mb-0">${link.title || link.name || 'Untitled Link'}</h6>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" 
                                       id="footer-link-${index}-active" 
                                       ${link.active !== undefined ? (link.active ? 'checked' : '') : 'checked'}
                                       onchange="toggleLinkStatus('footer', ${index}, this.checked)">
                                <label class="form-check-label text-muted small" for="footer-link-${index}-active">
                                    Active
                                </label>
                            </div>
                        </div>
                        <p class="card-text small text-muted">
                            <i class="align-middle me-1" data-feather="link"></i>${link.url || '#'}
                            ${link.external ? '<span class="badge bg-info ms-2">External</span>' : ''}
                        </p>
                        <button class="btn btn-outline-danger btn-sm" onclick="removeLink('footer', ${index})">
                            <i class="align-middle me-1" data-feather="trash-2"></i>Remove
                        </button>
                    </div>
                </div>
            </div>
        `;
        footerLinksContainer.insertAdjacentHTML('beforeend', linkHtml);
    });
    
    // Re-initialize feather icons
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
}

function rebuildSocialMediaForm(socialMediaConfig) {
    const modalBody = document.querySelector('#socialMediaModal .modal-body');
    
    const socialPlatforms = {
        'facebook': 'Facebook',
        'twitter': 'Twitter',
        'instagram': 'Instagram',
        'linkedin': 'LinkedIn',
        'youtube': 'YouTube',
        'tiktok': 'TikTok',
        'snapchat': 'Snapchat',
        'pinterest': 'Pinterest'
    };
    
    let formHtml = `
        <p class="text-muted mb-4">Configure your social media links. These will be displayed in your footer templates where the social media placeholder is used.</p>
        <form id="social-media-form">
            <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').getAttribute('content')}">
            <div class="social-media-grid">
    `;
    
    Object.entries(socialPlatforms).forEach(([platform, label]) => {
        const value = socialMediaConfig[platform] || '';
        formHtml += `
            <div class="card mb-3">
                <div class="card-body">
                    <label for="social-${platform}" class="form-label">
                        <i class="align-middle me-2" data-feather="share-2"></i>${label}
                    </label>
                    <input type="url" class="form-control" id="social-${platform}" 
                           name="social_media[${platform}]" 
                           value="${value}" 
                           placeholder="https://${platform}.com/yourprofile">
                </div>
            </div>
        `;
    });
    
    formHtml += `
            </div>
        </form>
    `;
    
    modalBody.innerHTML = formHtml;
    
    // Re-initialize feather icons
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
}

// Header Navigation Functions
function saveHeaderNavigation() {
    // Get authentication setting
    const showAuthHeader = document.getElementById('show_auth_header').checked;
    
    // Get CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    // Prepare data for API call
    const data = {
        _token: csrfToken,
        type: 'header',
        show_auth_in_header: showAuthHeader
    };
    
    // Make API call to save header navigation settings
    fetch('/admin/headers-footers/update-navigation', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Header navigation settings saved successfully!');
            const modal = bootstrap.Modal.getInstance(document.getElementById('headerNavModal'));
            if (modal) modal.hide();
        } else {
            alert('Error: ' + (data.message || 'Failed to save header navigation settings'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while saving header navigation settings.');
    });
}

// Footer Navigation Functions  
function saveFooterNavigation() {
    // Get authentication setting
    const showAuthFooter = document.getElementById('show_auth_footer').checked;
    
    // Get CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    // Prepare data for API call
    const data = {
        _token: csrfToken,
        type: 'footer',
        show_auth_in_footer: showAuthFooter
    };
    
    // Make API call to save footer navigation settings
    fetch('/admin/headers-footers/update-navigation', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Footer navigation settings saved successfully!');
            const modal = bootstrap.Modal.getInstance(document.getElementById('footerNavModal'));
            if (modal) modal.hide();
        } else {
            alert('Error: ' + (data.message || 'Failed to save footer navigation settings'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while saving footer navigation settings.');
    });
}

function toggleLinkStatus(type, index, status) {
    // Get CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    // Prepare data for API call
    const data = {
        _token: csrfToken,
        type: type,
        index: index,
        active: status === 'true' || status === true  // Changed from 'status' to 'active'
    };
    
    // Make API call to toggle link status
    fetch('/admin/headers-footers/toggle-navigation-link', {
        method: 'PATCH',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Refresh the page to show updated status
            location.reload();
        } else {
            alert('Error: ' + (data.message || 'Failed to toggle link status'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while toggling the link status.');
    });
}

function removeLink(type, index) {
    if(confirm('Are you sure you want to remove this link?')) {
        // Get CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        // Prepare data for API call
        const data = {
            _token: csrfToken,
            type: type,
            index: index
        };
        
        // Make API call to remove link
        fetch('/admin/headers-footers/remove-navigation-link', {
            method: 'DELETE',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Link removed successfully!');
                // Refresh the page to show updated links
                location.reload();
            } else {
                alert('Error: ' + (data.message || 'Failed to remove link'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while removing the link.');
        });
    }
}

// New inline form functions for Header
function showAddHeaderForm() {
    document.getElementById('add-header-link-form').style.display = 'block';
    document.getElementById('show-add-header-form').style.display = 'none';
    // Focus on first input
    document.getElementById('header-link-title').focus();
}

function cancelAddHeaderLink() {
    document.getElementById('add-header-link-form').style.display = 'none';
    document.getElementById('show-add-header-form').style.display = 'block';
    // Clear form
    document.getElementById('header-link-title').value = '';
    document.getElementById('header-link-url').value = '';
    document.getElementById('header-link-external').checked = false;
    document.getElementById('header-link-active').checked = true;
}

function addHeaderLink() {
    const title = document.getElementById('header-link-title').value.trim();
    const url = document.getElementById('header-link-url').value.trim();
    const external = document.getElementById('header-link-external').checked;
    const active = document.getElementById('header-link-active').checked;
    
    if (!title || !url) {
        alert('Please fill in both title and URL');
        return;
    }
    
    // Show loading state
    const submitButton = document.querySelector('#add-header-link-form .btn-success');
    const originalText = submitButton.textContent;
    submitButton.textContent = 'Adding...';
    submitButton.disabled = true;
    
    // Get CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    // Prepare data for API call
    const data = {
        _token: csrfToken,
        type: 'header',
        title: title,
        url: url,
        external: external,
        active: active
    };
    
    // Make API call to save the link
    fetch('/admin/headers-footers/add-navigation-link', {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Header link added successfully!');
            cancelAddHeaderLink();
            
            // Refresh the page to show new link
            location.reload();
        } else {
            alert('Error: ' + (data.message || 'Failed to add header link'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while adding the header link.');
    })
    .finally(() => {
        // Restore button state
        submitButton.textContent = originalText;
        submitButton.disabled = false;
    });
}

// New inline form functions for Footer
function showAddFooterForm() {
    document.getElementById('add-footer-link-form').style.display = 'block';
    document.getElementById('show-add-footer-form').style.display = 'none';
    // Focus on first input
    document.getElementById('footer-link-title').focus();
}

function cancelAddFooterLink() {
    document.getElementById('add-footer-link-form').style.display = 'none';
    document.getElementById('show-add-footer-form').style.display = 'block';
    // Clear form
    document.getElementById('footer-link-title').value = '';
    document.getElementById('footer-link-url').value = '';
    document.getElementById('footer-link-external').checked = false;
    document.getElementById('footer-link-active').checked = true;
}

function addFooterLink() {
    const title = document.getElementById('footer-link-title').value.trim();
    const url = document.getElementById('footer-link-url').value.trim();
    const external = document.getElementById('footer-link-external').checked;
    const active = document.getElementById('footer-link-active').checked;
    
    if (!title || !url) {
        alert('Please fill in both title and URL');
        return;
    }
    
    // Show loading state
    const submitButton = document.querySelector('#add-footer-link-form .btn-success');
    const originalText = submitButton.textContent;
    submitButton.textContent = 'Adding...';
    submitButton.disabled = true;
    
    // Get CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    // Prepare data for API call
    const data = {
        _token: csrfToken,
        type: 'footer',
        title: title,
        url: url,
        external: external,
        active: active
    };
    
    // Make API call to save the link
    fetch('/admin/headers-footers/add-navigation-link', {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Footer link added successfully!');
            cancelAddFooterLink();
            
            // Refresh the page to show new link
            location.reload();
        } else {
            alert('Error: ' + (data.message || 'Failed to add footer link'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while adding the footer link.');
    })
    .finally(() => {
        // Restore button state
        submitButton.textContent = originalText;
        submitButton.disabled = false;
    });
}

// Social Media Functions
function updateSocialMedia() {
    const form = document.getElementById('social-media-form');
    const formData = new FormData(form);
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    // Get the save button for loading state
    const saveButton = document.querySelector('#socialMediaModal .btn-primary');
    const originalText = saveButton.textContent;
    saveButton.textContent = 'Saving...';
    saveButton.disabled = true;
    
    // Convert FormData to JSON
    const data = {};
    for (let [key, value] of formData.entries()) {
        if (key.startsWith('social_media[') && key.endsWith(']')) {
            // Extract platform name from social_media[platform] format
            const platform = key.match(/social_media\[([^\]]+)\]/)[1];
            data[platform] = value;
        }
    }
    
    fetch('/admin/headers-footers/social-media', {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ social_media: data })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Social media links updated successfully!');
            // Use Bootstrap 5 API to hide modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('socialMediaModal'));
            if (modal) {
                modal.hide();
            }
        } else {
            alert('Error: ' + (data.message || 'Failed to update social media links'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while updating social media links.');
    })
    .finally(() => {
        // Restore button state
        saveButton.textContent = originalText;
        saveButton.disabled = false;
    });
}

// Section Content Editor Functions
function openSectionContentEditor(sectionId, pageId) {
    // Set section and page IDs
    document.getElementById('section_id').value = sectionId;
    document.getElementById('page_id').value = pageId;
    
    // Reset modal state
    resetSectionModal();
    
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('sectionContentModal'));
    modal.show();
    
    // Load section data
    loadSectionData(sectionId, pageId);
}

function resetSectionModal() {
    // Hide all states
    document.getElementById('sectionLoadingState').style.display = 'block';
    document.getElementById('sectionInfo').style.display = 'none';
    document.getElementById('sectionFormFields').style.display = 'none';
    document.getElementById('sectionErrorAlert').style.display = 'none';
    
    // Clear form fields
    document.getElementById('sectionFormFields').innerHTML = '';
    
    // Reset save button
    const saveButton = document.getElementById('saveSectionContent');
    saveButton.disabled = false;
    saveButton.innerHTML = '<i class="align-middle me-1" data-feather="save"></i>Save Changes';
}

function loadSectionData(sectionId, pageId) {
    console.log('Loading section data for:', { sectionId, pageId });
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    fetch(`/admin/content/sections/${pageId}/${sectionId}`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        }
    })
    .then(response => {
        console.log('LoadSectionData response status:', response.status);
        if (!response.ok) {
            throw new Error('Failed to fetch section data');
        }
        return response.json();
    })
    .then(data => {
        console.log('LoadSectionData response data:', data);
        if (data.success) {
            populateSectionForm(data.section);
        } else {
            showSectionError(data.message || 'Failed to load section data');
        }
    })
    .catch(error => {
        console.error('LoadSectionData error:', error);
        showSectionError('Failed to load section data. Please try again.');
    });
}

        function populateSectionForm(sectionData) {
            console.log('Populating section form with data:', sectionData);
            
            // Hide loading state
            document.getElementById('sectionLoadingState').style.display = 'none';
            
            // Show section info
            const sectionInfo = document.getElementById('sectionInfo');
            document.getElementById('sectionName').textContent = sectionData.name || 'Section';
            document.getElementById('sectionDescription').textContent = sectionData.layout?.description || 'Edit section content';
            sectionInfo.style.display = 'block';
            
            // Build form fields based on template configuration
            const formContainer = document.getElementById('sectionFormFields');
            let formHtml = '';
            
            if (sectionData.layout?.configurable_fields) {
                const configurableFields = sectionData.layout.configurable_fields;
                let currentContent = sectionData.content_data || sectionData.content || {};
                const defaultConfig = sectionData.layout.default_config || {};
                
                // Enhanced content parsing with debugging
                console.log('Raw current content:', currentContent);
                console.log('Default config:', defaultConfig);
                console.log('Configurable fields:', configurableFields);
                
                // If content is a string (JSON), try to parse it
                if (typeof currentContent === 'string') {
                    try {
                        currentContent = JSON.parse(currentContent);
                        console.log('Parsed content from string:', currentContent);
                    } catch (e) {
                        console.warn('Failed to parse content string:', e);
                        currentContent = {};
                    }
                }
                
                // Handle nested language structure (en, ar, etc.)
                if (currentContent.en && typeof currentContent.en === 'object') {
                    currentContent = { ...currentContent, ...currentContent.en };
                    console.log('Merged language content:', currentContent);
                }
                
                // Handle nested content structure (if content is wrapped in another object)
                if (currentContent.content && typeof currentContent.content === 'object') {
                    currentContent = { ...currentContent, ...currentContent.content };
                    console.log('Merged nested content:', currentContent);
                }
                
                Object.entries(configurableFields).forEach(([fieldName, fieldConfig]) => {
                    // Try multiple fallback values
                    let currentValue = currentContent[fieldName] || 
                                     defaultConfig[fieldName] || 
                                     fieldConfig.default || 
                                     (fieldConfig.type === 'array' ? [] : 
                                      fieldConfig.type === 'object' ? {} : '');
                    
                    // Special handling for common field mappings
                    if (!currentValue || currentValue === '') {
                        const aliases = {
                            'title': ['hero_title', 'section_title', 'main_title'],
                            'hero_title': ['title', 'section_title', 'main_title'],
                            'subtitle': ['hero_subtitle', 'description', 'content'],
                            'hero_subtitle': ['subtitle', 'description', 'content'],
                            'button_text': ['cta_text', 'btn_text'],
                            'button_url': ['cta_url', 'btn_url'],
                            'features': ['items', 'list'],
                            'stats': ['items', 'statistics', 'numbers'],
                            'testimonials': ['reviews', 'feedback']
                        };
                        
                        if (aliases[fieldName]) {
                            for (const alias of aliases[fieldName]) {
                                if (currentContent[alias] && currentContent[alias] !== '') {
                                    currentValue = currentContent[alias];
                                    break;
                                }
                            }
                        }
                    }
                    
                    console.log(`Field ${fieldName}:`, currentValue, typeof currentValue);
                    formHtml += generateFormField(fieldName, fieldConfig, currentValue);
                });
            }    if (!formHtml) {
        formHtml = '<div class="alert alert-warning"><i class="align-middle me-2" data-feather="alert-triangle"></i>No editable fields found for this section template.</div>';
    }
    
    formContainer.innerHTML = formHtml;
    formContainer.style.display = 'block';
    
    // Re-initialize feather icons
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
}

function generateFormField(fieldName, fieldConfig, currentValue) {
    const label = fieldConfig.label || fieldName;
    const type = fieldConfig.type || 'text';
    const required = fieldConfig.required ? 'required' : '';
    const lowerFieldName = (fieldName || '').toLowerCase();
    const lowerLabel = (label || '').toLowerCase();
    let fieldHtml = `
        <div class="mb-3">
            <label for="field_${fieldName}" class="form-label">
                ${label}
                ${fieldConfig.required ? '<span class="text-danger">*</span>' : ''}
            </label>
    `;
    
    switch (type) {
        case 'text':
            fieldHtml += `<input type="text" class="form-control" id="field_${fieldName}" name="${fieldName}" value="${currentValue}" ${required}>`;
            break;
            
        case 'textarea':
            fieldHtml += `<textarea class="form-control" id="field_${fieldName}" name="${fieldName}" rows="4" ${required}>${currentValue}</textarea>`;
            break;
            
        case 'url':
            fieldHtml += `<input type="url" class="form-control" id="field_${fieldName}" name="${fieldName}" value="${currentValue}" placeholder="https://example.com" ${required}>`;
            break;
            
        case 'email':
            fieldHtml += `<input type="email" class="form-control" id="field_${fieldName}" name="${fieldName}" value="${currentValue}" ${required}>`;
            break;
            
        case 'color':
            fieldHtml += `<input type="color" class="form-control form-control-color" id="field_${fieldName}" name="${fieldName}" value="${currentValue || '#000000'}" ${required}>`;
            break;
            
    // Removed color picker for end-user simplicity (request)
            
        case 'select':
            fieldHtml += `<select class="form-select" id="field_${fieldName}" name="${fieldName}" ${required}>`;
            if (fieldConfig.options) {
                fieldConfig.options.forEach(option => {
                    const selected = (currentValue == option) ? 'selected' : '';
                    fieldHtml += `<option value="${option}" ${selected}>${option}</option>`;
                });
            }
            fieldHtml += `</select>`;
            break;
            
        case 'boolean':
            const checked = currentValue ? 'checked' : '';
            fieldHtml += `
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="field_${fieldName}" name="${fieldName}" value="1" ${checked}>
                    <label class="form-check-label" for="field_${fieldName}">${label}</label>
                </div>`;
            break;
            
        case 'range':
            const min = fieldConfig.min || 0;
            const max = fieldConfig.max || 100;
            const step = fieldConfig.step || 1;
            fieldHtml += `
                <div class="d-flex align-items-center gap-3">
                    <input type="range" class="form-range flex-grow-1" id="field_${fieldName}" name="${fieldName}" 
                           value="${currentValue}" min="${min}" max="${max}" step="${step}" 
                           oninput="document.getElementById('field_${fieldName}_display').value = this.value">
                    <input type="number" class="form-control" id="field_${fieldName}_display" 
                           value="${currentValue}" min="${min}" max="${max}" step="${step}" style="width: 80px;"
                           oninput="document.getElementById('field_${fieldName}').value = this.value">
                </div>`;
            break;
            
        case 'array':
            // Ensure currentValue is properly parsed
            let arrayValue = currentValue;
            if (typeof arrayValue === 'string') {
                try {
                    arrayValue = JSON.parse(arrayValue);
                } catch (e) {
                    arrayValue = [];
                }
            }
            if (!Array.isArray(arrayValue)) {
                arrayValue = [];
            }
            
            // Add proper field header for array fields
            fieldHtml += `
                <div class="array-field-header mb-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="mb-1 text-primary">
                                <i class="align-middle me-2" data-feather="list"></i>${label}
                                ${fieldConfig.required ? '<span class="text-danger ms-1">*</span>' : ''}
                            </h6>
                            ${fieldConfig.description ? `<small class="text-muted">${fieldConfig.description}</small>` : ''}
                        </div>
                        <span class="badge bg-light text-dark">${arrayValue.length} ${arrayValue.length === 1 ? 'item' : 'items'}</span>
                    </div>
                </div>`;
            
            if (lowerLabel.includes('feature') || lowerFieldName === 'features') {
                fieldHtml += generateArrayField(fieldName, arrayValue, 'features');
            } else if (lowerLabel.includes('menu') || lowerFieldName.includes('menu')) {
                fieldHtml += generateArrayField(fieldName, arrayValue, 'menu');
            } else if (lowerLabel.includes('stat') || lowerFieldName.includes('stat')) {
                fieldHtml += generateArrayField(fieldName, arrayValue, 'stats');
            } else if (lowerLabel.includes('testimonial') || lowerFieldName.includes('testimonial')) {
                fieldHtml += generateArrayField(fieldName, arrayValue, 'testimonials');
            } else if (lowerLabel.includes('social') || lowerFieldName.includes('social')) {
                fieldHtml += generateArrayField(fieldName, arrayValue, 'social');
            } else if (lowerLabel.includes('plan') || lowerFieldName === 'plans') {
                fieldHtml += generateArrayField(fieldName, arrayValue, 'plans');
            } else if (lowerFieldName === 'services' || lowerLabel.includes('service')) {
                fieldHtml += generateArrayField(fieldName, arrayValue, 'services');
            } else if (lowerFieldName === 'cards' || lowerLabel.includes('card')) {
                fieldHtml += generateArrayField(fieldName, arrayValue, 'cards');
            } else if (lowerFieldName.includes('link') || lowerLabel.includes('link')) {
                fieldHtml += generateArrayField(fieldName, arrayValue, 'links');
            } else if (lowerLabel.includes('image') || lowerFieldName.includes('image') || lowerFieldName === 'images') {
                fieldHtml += generateArrayField(fieldName, arrayValue, 'images');
            } else if (lowerLabel.includes('gallery') || lowerFieldName.includes('gallery')) {
                fieldHtml += generateArrayField(fieldName, arrayValue, 'images');
            } else if (lowerLabel.includes('faq') || lowerFieldName.includes('faq') || lowerFieldName === 'items') {
                // Check if this looks like FAQ items
                if (arrayValue.length > 0 && arrayValue[0] && ('q' in arrayValue[0] || 'question' in arrayValue[0])) {
                    fieldHtml += generateArrayField(fieldName, arrayValue, 'faq');
                } else {
                    // Continue with normal detection
                    if (arrayValue.length > 0) {
                        const firstItem = arrayValue[0];
                        if (firstItem && typeof firstItem === 'object') {
                            if ('title' in firstItem && 'text' in firstItem) {
                                fieldHtml += generateArrayField(fieldName, arrayValue, 'zigzag');
                            } else if ('icon' in firstItem && 'title' in firstItem && 'description' in firstItem) {
                                fieldHtml += generateArrayField(fieldName, arrayValue, 'features');
                            } else if ('value' in firstItem && 'label' in firstItem) {
                                fieldHtml += generateArrayField(fieldName, arrayValue, 'stats');
                            } else if ('number' in firstItem && 'label' in firstItem) {
                                fieldHtml += generateArrayField(fieldName, arrayValue, 'stats');
                            } else if ('quote' in firstItem && 'name' in firstItem) {
                                fieldHtml += generateArrayField(fieldName, arrayValue, 'testimonials');
                            } else if ('icon' in firstItem && 'url' in firstItem) {
                                fieldHtml += generateArrayField(fieldName, arrayValue, 'social');
                            } else if ('label' in firstItem && 'url' in firstItem) {
                                fieldHtml += generateArrayField(fieldName, arrayValue, 'links');
                            } else if ('text' in firstItem || 'content' in firstItem) {
                                fieldHtml += generateArrayField(fieldName, arrayValue, 'generic');
                            } else {
                                fieldHtml += generateArrayField(fieldName, arrayValue, 'generic');
                            }
                        } else {
                            fieldHtml += generateArrayField(fieldName, arrayValue, 'simple');
                        }
                    } else {
                        // Default to simple array
                        fieldHtml += generateArrayField(fieldName, arrayValue, 'simple');
                    }
                }
            } else {
                // Auto-detect from array content
                if (arrayValue.length > 0) {
                    const firstItem = arrayValue[0];
                    if (firstItem && typeof firstItem === 'object') {
                        if ('title' in firstItem && 'text' in firstItem) {
                            fieldHtml += generateArrayField(fieldName, arrayValue, 'zigzag');
                        } else if ('q' in firstItem || 'question' in firstItem) {
                            fieldHtml += generateArrayField(fieldName, arrayValue, 'faq');
                        } else if ('icon' in firstItem && 'title' in firstItem && 'description' in firstItem) {
                            fieldHtml += generateArrayField(fieldName, arrayValue, 'features');
                        } else if ('value' in firstItem && 'label' in firstItem) {
                            fieldHtml += generateArrayField(fieldName, arrayValue, 'stats');
                        } else if ('number' in firstItem && 'label' in firstItem) {
                            fieldHtml += generateArrayField(fieldName, arrayValue, 'stats');
                        } else if ('quote' in firstItem && 'name' in firstItem) {
                            fieldHtml += generateArrayField(fieldName, arrayValue, 'testimonials');
                        } else if ('icon' in firstItem && 'url' in firstItem) {
                            fieldHtml += generateArrayField(fieldName, arrayValue, 'social');
                        } else if ('label' in firstItem && 'url' in firstItem) {
                            fieldHtml += generateArrayField(fieldName, arrayValue, 'links');
                        } else if ('text' in firstItem || 'content' in firstItem) {
                            fieldHtml += generateArrayField(fieldName, arrayValue, 'generic');
                        } else {
                            fieldHtml += generateArrayField(fieldName, arrayValue, 'generic');
                        }
                    } else {
                        fieldHtml += generateArrayField(fieldName, arrayValue, 'simple');
                    }
                } else {
                    // Default to simple array
                    fieldHtml += generateArrayField(fieldName, arrayValue, 'simple');
                }
            }
            break;
            
        case 'object':
            // Handle object fields like buttons, complex configurations
            let objectValue = currentValue;
            if (typeof objectValue === 'string') {
                try {
                    objectValue = JSON.parse(objectValue);
                } catch (e) {
                    objectValue = {};
                }
            }
            if (typeof objectValue !== 'object' || Array.isArray(objectValue)) {
                objectValue = {};
            }
            
            fieldHtml += `<div class="object-field border rounded p-3" id="${fieldName}_object">`;
            
            // Check if this looks like a button object
            if (fieldName.includes('button') || lowerFieldName.includes('btn') || 
                ('text' in objectValue && 'url' in objectValue)) {
                fieldHtml += `
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label">Button Text</label>
                            <input type="text" class="form-control" name="${fieldName}_text" 
                                   value="${objectValue.text || ''}" placeholder="Button text">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Button URL</label>
                            <input type="url" class="form-control" name="${fieldName}_url" 
                                   value="${objectValue.url || ''}" placeholder="https://...">
                        </div>
                    </div>`;
            } else {
                // Generic object handler - create fields for all object keys
                const defaultKeys = fieldConfig.default ? Object.keys(fieldConfig.default) : [];
                const currentKeys = Object.keys(objectValue);
                const allKeys = [...new Set([...defaultKeys, ...currentKeys])];
                
                if (allKeys.length === 0) {
                    allKeys.push('value'); // Default key
                }
                
                allKeys.forEach(key => {
                    fieldHtml += `
                        <div class="mb-2">
                            <label class="form-label">${key.charAt(0).toUpperCase() + key.slice(1)}</label>
                            <input type="text" class="form-control" name="${fieldName}_${key}" 
                                   value="${objectValue[key] || ''}" placeholder="${key}">
                        </div>`;
                });
            }
            
            fieldHtml += `</div>`;
            break;
            
        default:
            fieldHtml += `<input type="text" class="form-control" id="field_${fieldName}" name="${fieldName}" value="${currentValue}" ${required}>`;
    }
    
    if (fieldConfig.description) {
        fieldHtml += `<div class="form-text">${fieldConfig.description}</div>`;
    }
    
    fieldHtml += `</div>`;
    return fieldHtml;
}

function generateArrayField(fieldName, arrayValue, arrayType) {
    let html = `<div id="${fieldName}_container">`;
    
    // Initialize empty array if needed
    if (!arrayValue || !Array.isArray(arrayValue) || arrayValue.length === 0) {
        switch (arrayType) {
            case 'zigzag':
                arrayValue = [{ title: '', text: '' }];
                break;
            case 'features':
                arrayValue = [{ icon: 'fas fa-star', title: '', description: '' }];
                break;
            case 'menu':
                arrayValue = [{ label: 'Home', url: '/', external: false }];
                break;
            case 'stats':
                arrayValue = [{ value: '', label: '', icon: 'fas fa-star' }];
                break;
            case 'testimonials':
                arrayValue = [{ quote: '', name: '', company: '' }];
                break;
            case 'faq':
                arrayValue = [{ q: '', a: '' }];
                break;
            case 'images':
                arrayValue = [''];
                break;
            case 'social':
                arrayValue = [{ icon: 'fab fa-facebook', url: '' }];
                break;
            case 'plans':
                arrayValue = [{ name: '', price: '', features: [] }];
                break;
            case 'services':
                arrayValue = [{ icon: 'fas fa-cog', title: '', description: '' }];
                break;
            case 'cards':
                arrayValue = [{ name: '', text: '' }];
                break;
            case 'links':
                arrayValue = [{ label: '', url: '' }];
                break;
            case 'generic':
                arrayValue = [{ name: '', value: '' }];
                break;
            case 'simple':
                arrayValue = [''];
                break;
            default:
                arrayValue = [{}];
        }
    }
    
    arrayValue.forEach((item, index) => {
        html += `<div class="array-item border rounded p-3 mb-3" data-index="${index}">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h6 class="mb-0">${getItemLabel(arrayType)} ${index + 1}</h6>
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeArrayItem('${fieldName}', ${index}, '${arrayType}')">
                    <i class="align-middle" data-feather="x"></i>
                </button>
            </div>`;
            
        switch (arrayType) {
            case 'features':
            case 'services':
                html += `
                    <div class="row">
                        <div class="col-md-4">
                            <label class="form-label">Icon</label>
                            <input type="text" class="form-control" name="${fieldName}[${index}][icon]" 
                                   value="${item.icon || 'fas fa-star'}" placeholder="fas fa-star">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Title</label>
                            <input type="text" class="form-control" name="${fieldName}[${index}][title]" 
                                   value="${item.title || ''}" placeholder="Title">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="${fieldName}[${index}][description]" 
                                    placeholder="Description">${item.description || ''}</textarea>
                        </div>
                    </div>`;
                break;
                
            case 'menu':
                html += `
                    <div class="row">
                        <div class="col-md-4">
                            <label class="form-label">Label</label>
                            <input type="text" class="form-control" name="${fieldName}[${index}][label]" 
                                   value="${item.label || ''}" placeholder="Menu label">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">URL</label>
                            <input type="text" class="form-control" name="${fieldName}[${index}][url]" 
                                   value="${item.url || ''}" placeholder="/page or https://...">
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="${fieldName}[${index}][external]" 
                                       value="1" ${item.external ? 'checked' : ''}>
                                <label class="form-check-label">External Link</label>
                            </div>
                        </div>
                    </div>`;
                break;
                
            case 'stats':
                html += `
                    <div class="row">
                        <div class="col-md-4">
                            <label class="form-label">Value</label>
                            <input type="text" class="form-control" name="${fieldName}[${index}][value]" 
                                   value="${item.value || item.number || ''}" placeholder="12K+">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Label</label>
                            <input type="text" class="form-control" name="${fieldName}[${index}][label]" 
                                   value="${item.label || ''}" placeholder="Users">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Icon (optional)</label>
                            <input type="text" class="form-control" name="${fieldName}[${index}][icon]" 
                                   value="${item.icon || 'fas fa-star'}" placeholder="fas fa-users">
                        </div>
                    </div>`;
                break;
                
            case 'faq':
                html += `
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label">Question</label>
                            <input type="text" class="form-control" name="${fieldName}[${index}][q]" 
                                   value="${item.q || item.question || ''}" placeholder="Enter question">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Answer</label>
                            <textarea class="form-control" name="${fieldName}[${index}][a]" rows="3"
                                    placeholder="Enter answer">${item.a || item.answer || ''}</textarea>
                        </div>
                    </div>`;
                break;
                
            case 'testimonials':
                html += `
                    <div class="row">
                        <div class="col-md-12">
                            <label class="form-label">Quote</label>
                            <textarea class="form-control mb-2" name="${fieldName}[${index}][quote]" 
                                    placeholder="Customer testimonial">${item.quote || ''}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Name</label>
                            <input type="text" class="form-control" name="${fieldName}[${index}][name]" 
                                   value="${item.name || ''}" placeholder="Customer name">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Company</label>
                            <input type="text" class="form-control" name="${fieldName}[${index}][company]" 
                                   value="${item.company || ''}" placeholder="Company name">
                        </div>
                    </div>`;
                break;
                
            case 'social':
                html += `
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label">Icon</label>
                            <input type="text" class="form-control" name="${fieldName}[${index}][icon]" 
                                   value="${item.icon || 'fab fa-facebook'}" placeholder="fab fa-facebook">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">URL</label>
                            <input type="url" class="form-control" name="${fieldName}[${index}][url]" 
                                   value="${item.url || ''}" placeholder="https://facebook.com/yourpage">
                        </div>
                    </div>`;
                break;
                
            case 'plans':
                const features = Array.isArray(item.features) ? item.features.join('\n') : (item.features || '');
                html += `
                    <div class="row">
                        <div class="col-md-4">
                            <label class="form-label">Plan Name</label>
                            <input type="text" class="form-control" name="${fieldName}[${index}][name]" 
                                   value="${item.name || ''}" placeholder="Starter">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Price</label>
                            <input type="text" class="form-control" name="${fieldName}[${index}][price]" 
                                   value="${item.price || ''}" placeholder="29">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Featured</label>
                            <div class="form-check mt-2">
                                <input class="form-check-input" type="checkbox" name="${fieldName}[${index}][featured]" 
                                       value="1" ${item.featured ? 'checked' : ''}>
                                <label class="form-check-label">Highlight this plan</label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Features (one per line)</label>
                            <textarea class="form-control" name="${fieldName}[${index}][features]" rows="3"
                                    placeholder="Feature 1&#10;Feature 2&#10;Feature 3">${features}</textarea>
                        </div>
                    </div>`;
                break;
                
            case 'cards':
                html += `
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label">Name</label>
                            <input type="text" class="form-control" name="${fieldName}[${index}][name]" 
                                   value="${item.name || ''}" placeholder="Card title">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Text</label>
                            <textarea class="form-control" name="${fieldName}[${index}][text]" 
                                    placeholder="Card content">${item.text || ''}</textarea>
                        </div>
                    </div>`;
                break;
                
            case 'links':
                html += `
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label">Label</label>
                            <input type="text" class="form-control" name="${fieldName}[${index}][label]" 
                                   value="${item.label || ''}" placeholder="Link text">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">URL</label>
                            <input type="url" class="form-control" name="${fieldName}[${index}][url]" 
                                   value="${item.url || ''}" placeholder="/page or https://...">
                        </div>
                    </div>`;
                break;
                
            case 'images':
                html += `
                    <div class="mb-2">
                        <label class="form-label">Image URL</label>
                        <input type="text" class="form-control" name="${fieldName}[${index}]" 
                               value="${typeof item === 'string' ? item : ''}" 
                               placeholder="https://example.com/image.jpg or http://example.com/image.jpg">
                        <div class="form-text">Enter the full URL to the image (HTTP or HTTPS)</div>
                    </div>`;
                break;
                
            case 'simple':
                html += `<input type="text" class="form-control" name="${fieldName}[${index}]" 
                               value="${typeof item === 'string' ? item : ''}" placeholder="Item value">`;
                break;
                
            default:
                // Generic object handler
                if (typeof item === 'object' && item !== null) {
                    html += `<div class="row">`;
                    Object.keys(item).forEach(key => {
                        html += `
                            <div class="col-md-6">
                                <label class="form-label">${key}</label>
                                <input type="text" class="form-control" name="${fieldName}[${index}][${key}]" 
                                       value="${item[key] || ''}" placeholder="${key}">
                            </div>`;
                    });
                    html += `</div>`;
                } else {
                    html += `<input type="text" class="form-control" name="${fieldName}[${index}]" 
                                   value="${item || ''}" placeholder="Value">`;
                }
        }
        
        html += `</div>`;
    });
    
    html += `
        </div>
        <button type="button" class="btn btn-outline-primary btn-sm" onclick="addArrayItem('${fieldName}', '${arrayType}')">
            <i class="align-middle me-1" data-feather="plus"></i>Add ${getItemLabel(arrayType)}
        </button>
    `;
    
    return html;
}

function getItemLabel(arrayType) {
    switch (arrayType) {
        case 'features': return 'Feature';
        case 'menu': return 'Menu Item';
        case 'stats': return 'Stat';
        case 'testimonials': return 'Testimonial';
        case 'faq': return 'FAQ Item';
        case 'images': return 'Image';
        case 'social': return 'Social Link';
        case 'plans': return 'Plan';
        case 'services': return 'Service';
        case 'cards': return 'Card';
        case 'links': return 'Link';
        case 'generic': return 'Item';
        case 'simple': return 'Item';
        default: return 'Item';
    }
}

function addArrayItem(fieldName, arrayType) {
    const container = document.getElementById(fieldName + '_container');
    if (!container) return;
    
    const index = container.querySelectorAll('.array-item').length;
    let newItemHtml = `<div class="array-item border rounded p-3 mb-3" data-index="${index}">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h6 class="mb-0">${getItemLabel(arrayType)} ${index + 1}</h6>
            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeArrayItem('${fieldName}', ${index}, '${arrayType}')">
                <i class="align-middle" data-feather="x"></i>
            </button>
        </div>`;
        
    switch (arrayType) {
        case 'features':
        case 'services':
            newItemHtml += `
                <div class="row">
                    <div class="col-md-4">
                        <label class="form-label">Icon</label>
                        <input type="text" class="form-control" name="${fieldName}[${index}][icon]" 
                               value="fas fa-star" placeholder="fas fa-star">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Title</label>
                        <input type="text" class="form-control" name="${fieldName}[${index}][title]" 
                               placeholder="Title">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="${fieldName}[${index}][description]" 
                                placeholder="Description"></textarea>
                    </div>
                </div>`;
            break;
        case 'menu':
            newItemHtml += `
                <div class="row">
                    <div class="col-md-4">
                        <label class="form-label">Label</label>
                        <input type="text" class="form-control" name="${fieldName}[${index}][label]" placeholder="Menu label">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">URL</label>
                        <input type="text" class="form-control" name="${fieldName}[${index}][url]" placeholder="/page">
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="${fieldName}[${index}][external]" value="1">
                            <label class="form-check-label">External Link</label>
                        </div>
                    </div>
                </div>`;
            break;
        case 'stats':
            newItemHtml += `
                <div class="row">
                    <div class="col-md-4">
                        <label class="form-label">Value</label>
                        <input type="text" class="form-control" name="${fieldName}[${index}][value]" placeholder="12K+">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Label</label>
                        <input type="text" class="form-control" name="${fieldName}[${index}][label]" placeholder="Users">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Icon (optional)</label>
                        <input type="text" class="form-control" name="${fieldName}[${index}][icon]" 
                               value="fas fa-star" placeholder="fas fa-users">
                    </div>
                </div>`;
            break;
        case 'testimonials':
            newItemHtml += `
                <div class="row">
                    <div class="col-md-12">
                        <label class="form-label">Quote</label>
                        <textarea class="form-control mb-2" name="${fieldName}[${index}][quote]" 
                                placeholder="Customer testimonial"></textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Name</label>
                        <input type="text" class="form-control" name="${fieldName}[${index}][name]" 
                               placeholder="Customer name">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Company</label>
                        <input type="text" class="form-control" name="${fieldName}[${index}][company]" 
                               placeholder="Company name">
                    </div>
                </div>`;
            break;
            
        case 'faq':
            newItemHtml += `
                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label">Question</label>
                        <input type="text" class="form-control" name="${fieldName}[${index}][q]" 
                               placeholder="Enter question">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Answer</label>
                        <textarea class="form-control" name="${fieldName}[${index}][a]" rows="3"
                                placeholder="Enter answer"></textarea>
                    </div>
                </div>`;
            break;
            
        case 'images':
            newItemHtml += `
                <div class="mb-2">
                    <label class="form-label">Image URL</label>
                    <input type="text" class="form-control" name="${fieldName}[${index}]" 
                           placeholder="https://example.com/image.jpg or http://example.com/image.jpg">
                    <div class="form-text">Enter the full URL to the image (HTTP or HTTPS)</div>
                </div>`;
            break;
            
        case 'social':
            newItemHtml += `
                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label">Icon</label>
                        <input type="text" class="form-control" name="${fieldName}[${index}][icon]" 
                               value="fab fa-facebook" placeholder="fab fa-facebook">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">URL</label>
                        <input type="url" class="form-control" name="${fieldName}[${index}][url]" 
                               placeholder="https://facebook.com/yourpage">
                    </div>
                </div>`;
            break;
        case 'plans':
            newItemHtml += `
                <div class="row">
                    <div class="col-md-4">
                        <label class="form-label">Plan Name</label>
                        <input type="text" class="form-control" name="${fieldName}[${index}][name]" 
                               placeholder="Basic Plan">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Price</label>
                        <input type="text" class="form-control" name="${fieldName}[${index}][price]" 
                               placeholder="$29/month">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Features (one per line)</label>
                        <textarea class="form-control" name="${fieldName}[${index}][features]" 
                                placeholder="Feature 1&#10;Feature 2&#10;Feature 3"></textarea>
                    </div>
                </div>`;
            break;
        case 'cards':
            newItemHtml += `
                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label">Name/Title</label>
                        <input type="text" class="form-control" name="${fieldName}[${index}][name]" 
                               placeholder="Card title">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Text/Description</label>
                        <textarea class="form-control" name="${fieldName}[${index}][text]" 
                                placeholder="Card description"></textarea>
                    </div>
                </div>`;
            break;
        case 'links':
            newItemHtml += `
                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label">Label</label>
                        <input type="text" class="form-control" name="${fieldName}[${index}][label]" 
                               placeholder="Link text">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">URL</label>
                        <input type="url" class="form-control" name="${fieldName}[${index}][url]" 
                               placeholder="/page or https://...">
                    </div>
                </div>`;
            break;
        case 'generic':
            newItemHtml += `
                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label">Name</label>
                        <input type="text" class="form-control" name="${fieldName}[${index}][name]" 
                               placeholder="Name">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Value</label>
                        <input type="text" class="form-control" name="${fieldName}[${index}][value]" 
                               placeholder="Value">
                    </div>
                </div>`;
            break;
        case 'simple':
            newItemHtml += `<input type="text" class="form-control" name="${fieldName}[${index}]" placeholder="Item value">`;
            break;
        // Add other cases as needed...
        default:
            newItemHtml += `<input type="text" class="form-control" name="${fieldName}[${index}]" placeholder="Value">`;
    }
    
    newItemHtml += `</div>`;
    
    container.insertAdjacentHTML('beforeend', newItemHtml);
    if (typeof feather !== 'undefined') feather.replace();
}

function removeArrayItem(fieldName, index, arrayType) {
    const element = document.querySelector(`#${fieldName}_container .array-item[data-index="${index}"]`);
    if (element) {
        element.remove();
        reindexArrayItems(fieldName, arrayType);
    }
}

function reindexArrayItems(fieldName, arrayType) {
    const container = document.getElementById(fieldName + '_container');
    if (!container) return;
    
    container.querySelectorAll('.array-item').forEach((element, newIndex) => {
        element.setAttribute('data-index', newIndex);
        element.querySelector('h6').textContent = `${getItemLabel(arrayType)} ${newIndex + 1}`;
        element.querySelector('button').setAttribute('onclick', `removeArrayItem('${fieldName}', ${newIndex}, '${arrayType}')`);
        
        // Update all input names
        element.querySelectorAll('input, textarea').forEach(input => {
            if (input.name.includes('[')) {
                const namePattern = input.name.match(/^([^\[]+)\[\d+\](.*)$/);
                if (namePattern) {
                    input.name = `${namePattern[1]}[${newIndex}]${namePattern[2]}`;
                }
            }
        });
    });
}

function addMenuItem() {
    const container = document.getElementById('menu_items_container');
    const menuItems = container.querySelectorAll('.menu-item');
    const newIndex = menuItems.length;
    
    const newItemHtml = `
        <div class="menu-item border rounded p-3 mb-3" data-index="${newIndex}">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h6 class="mb-0">Menu Item ${newIndex + 1}</h6>
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeMenuItem(${newIndex})">
                    <i class="align-middle" data-feather="x"></i>
                </button>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <label class="form-label">Label</label>
                    <input type="text" class="form-control" name="menu_items[${newIndex}][label]" value="" placeholder="Menu label">
                </div>
                <div class="col-md-4">
                    <label class="form-label">URL</label>
                    <input type="text" class="form-control" name="menu_items[${newIndex}][url]" value="/" placeholder="/page or https://...">
                </div>
                <div class="col-md-4">
                    <div class="form-check mt-4">
                        <input class="form-check-input" type="checkbox" name="menu_items[${newIndex}][external]" value="1">
                        <label class="form-check-label">External Link</label>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    container.insertAdjacentHTML('beforeend', newItemHtml);
    
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
}

function removeMenuItem(index) {
    const menuItem = document.querySelector(`.menu-item[data-index="${index}"]`);
    if (menuItem) {
        menuItem.remove();
        reindexMenuItems();
    }
}

function reindexMenuItems() {
    const menuItems = document.querySelectorAll('.menu-item');
    menuItems.forEach((item, index) => {
        item.setAttribute('data-index', index);
        item.querySelector('h6').textContent = `Menu Item ${index + 1}`;
        item.querySelector('button').setAttribute('onclick', `removeMenuItem(${index})`);
        
        // Update input names
        const inputs = item.querySelectorAll('input[name^="menu_items["]');
        inputs.forEach(input => {
            const fieldName = input.name.match(/\[([^\]]+)\]$/)[1];
            input.name = `menu_items[${index}][${fieldName}]`;
        });
    });
}

function showSectionError(message) {
    document.getElementById('sectionLoadingState').style.display = 'none';
    document.getElementById('sectionInfo').style.display = 'none';
    document.getElementById('sectionFormFields').style.display = 'none';
    
    document.getElementById('sectionErrorMessage').textContent = message;
    document.getElementById('sectionErrorAlert').style.display = 'block';
    
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
}

// Save section content
document.addEventListener('DOMContentLoaded', function() {
    const saveSectionButton = document.getElementById('saveSectionContent');
    if (saveSectionButton) {
        saveSectionButton.addEventListener('click', function() {
            saveSectionContent();
        });
    }
});

function saveSectionContent() {
    console.log('=== Starting Save Section Content ===');
    
    const form = document.getElementById('sectionContentForm');
    const formData = new FormData(form);
    
    // Get section and page IDs
    const sectionId = document.getElementById('section_id').value;
    const pageId = document.getElementById('page_id').value;
    
    console.log('Save params:', { sectionId, pageId });
    
    if (!sectionId || !pageId) {
        showSectionError('Missing section or page ID');
        return;
    }
    
    // Show loading state on save button
    const saveButton = document.getElementById('saveSectionContent');
    const originalText = saveButton.innerHTML;
   saveButton.innerHTML = '<i class="align-middle me-1" data-feather="refresh-cw"></i>Saving...';
    saveButton.disabled = true;
    
    // Convert FormData to JSON, handling special cases
    const contentData = {};
    
    // Define fields to skip (system/form fields)
    const skipFields = ['_token', 'section_id', 'page_id'];
    
    // Handle regular fields
    for (let [key, value] of formData.entries()) {
        // Skip system fields
        if (skipFields.includes(key)) {
            continue;
        }
        
        if (key.includes('[')) {
            // Skip array items, we'll handle them separately
            continue;
        }
        
        // Check if this is part of an object field
        if (key.includes('_')) {
            const parts = key.split('_');
            if (parts.length >= 2) {
                const potentialObjectField = parts[0];
                const potentialObjectKey = parts.slice(1).join('_');
                
                // Check if there's an object field in the DOM with this name
                const objectContainer = document.getElementById(potentialObjectField + '_object');
                if (objectContainer) {
                    if (!contentData[potentialObjectField]) {
                        contentData[potentialObjectField] = {};
                    }
                    contentData[potentialObjectField][potentialObjectKey] = value;
                    continue; // Skip adding this as a regular field
                }
            }
        }
        
        // Handle boolean checkboxes
        if (document.querySelector(`input[name="${key}"][type="checkbox"]`)) {
            contentData[key] = value === '1' || value === 'on';
        } else {
            contentData[key] = value;
        }
    }
    
    // Handle all array fields using the new unified structure
    document.querySelectorAll('[id$="_container"]').forEach(container => {
        const fieldName = container.id.replace('_container', '');
        const arrayData = [];
        
        container.querySelectorAll('.array-item').forEach((item, index) => {
            const itemData = {};
            let simpleValue = null;
            
            // Collect all inputs for this array item
            item.querySelectorAll('input, textarea, select').forEach(input => {
                if (input.name && input.name.includes(`[${index}]`)) {
                    // Check if this is a simple array item (like images[0]) or object array item (like features[0][title])
                    const namePattern = input.name;
                    const simpleArrayMatch = namePattern.match(new RegExp(`\\[${index}\\]$`));
                    const objectArrayMatch = namePattern.match(new RegExp(`\\[${index}\\]\\[([\\w]+)\\]$`));
                    
                    if (objectArrayMatch) {
                        // This is an object array item (has property name like features[0][title])
                        const fieldKey = objectArrayMatch[1];
                        
                        if (input.type === 'checkbox') {
                            itemData[fieldKey] = input.checked;
                        } else if (input.type === 'number' || input.type === 'range') {
                            itemData[fieldKey] = parseFloat(input.value) || 0;
                        } else if (fieldKey === 'features' && input.value) {
                            // Handle features as array (for plans)
                            itemData[fieldKey] = input.value.split('\n').filter(f => f.trim());
                        } else {
                            itemData[fieldKey] = input.value || '';
                        }
                    } else if (simpleArrayMatch) {
                        // This is a simple array item (like images[0])
                        simpleValue = input.value || '';
                    }
                }
            });
            
            // Add to array based on type
            if (simpleValue !== null) {
                // Simple array item (like image URLs)
                if (simpleValue !== '') {
                    arrayData.push(simpleValue);
                }
            } else if (Object.keys(itemData).length > 0 && Object.values(itemData).some(v => v !== '' && v !== false)) {
                // Object array item (like FAQ, features, etc.)
                arrayData.push(itemData);
            }
        });
        
        if (arrayData.length > 0) {
            contentData[fieldName] = arrayData;
        }
    });
    
    // Final cleanup - remove unwanted duplicate/alias fields (but do NOT remove generic description if it's the only meaningful field)
    const duplicateFields = ['hero_title', 'hero_description'];
    duplicateFields.forEach(field => {
        if (contentData.hasOwnProperty(field)) {
            delete contentData[field];
        }
    });

    // Safety: prevent sending empty object (which would wipe existing stored content)
    if (Object.keys(contentData).length === 0) {
        console.warn('Content data empty after processing – aborting save to avoid wiping existing header/footer');
        alert('لا يوجد أي تغييرات للحفظ');
        saveButton.innerHTML = originalText;
        saveButton.disabled = false;
        return;
    }
    
    // Get CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    // Check if CSRF token exists and is valid
    if (!csrfToken || csrfToken === 'undefined' || csrfToken.length < 10) {
        showSectionError('CSRF token is missing or invalid. Please refresh the page.');
        console.error('Invalid CSRF token:', csrfToken);
        return;
    }
    
    // Debug: Log the data being sent
    console.log('Form Data entries:');
    for (let [key, value] of formData.entries()) {
        console.log(`  ${key}: ${value}`);
    }
    console.log('Final content data:', contentData);
    console.log('Section ID:', sectionId);
    console.log('Page ID:', pageId);
    
    // Determine the correct endpoint based on page type
    let endpoint;
    if (pageId === 'header') {
        endpoint = `/admin/headers-footers/headers/${sectionId}`;
    } else if (pageId === 'footer') {
        endpoint = `/admin/headers-footers/footers/${sectionId}`;
    } else {
        endpoint = `/admin/content/sections/${pageId}/${sectionId}`;
    }
    
    console.log('=== SAVE DEBUG INFO ===');
    console.log('Page ID:', pageId);
    console.log('Section ID:', sectionId);
    console.log('Using endpoint:', endpoint);
    console.log('Content data being sent:', contentData);
    console.log('CSRF Token:', csrfToken);
    console.log('========================');
    
    // Send update request
   // (الجديدة) Send update request باستخدام FormData + _method=PUT
const payload = new FormData();
payload.append('_token', csrfToken);
payload.append('_method', 'PUT');
payload.append('content_data', JSON.stringify(contentData));
if (pageId === 'header' || pageId === 'footer') {
  payload.append('force_override', '1');
}

fetch(endpoint, {
  method: 'POST',
  headers: {
    'Accept': 'application/json',
    'X-Requested-With': 'XMLHttpRequest'
    // مهم: ما نحددش Content-Type هنا — المتصفح هيظبط multipart boundary
  },
  credentials: 'same-origin',
  cache: 'no-store',
  body: payload
})
.then(async response => {
  console.log('Response received:', response);
  console.log('Response status:', response.status);
  console.log('Response headers:', response.headers);

  const contentType = response.headers.get('content-type') || '';
  let rawText = '';
  let jsonData = null;
  try {
    rawText = await response.text();
    console.log('Raw response text:', rawText);
    if (contentType.includes('application/json')) {
      jsonData = JSON.parse(rawText);
    } else {
      // بعض الكنترولرز بترجع JSON مع content-type غلط
      try { jsonData = JSON.parse(rawText); } catch (e) {}
    }
  } catch (e) {
    console.warn('Failed to parse JSON:', e.message);
  }

  if (!response.ok) {
    throw new Error(`HTTP ${response.status}: ${rawText}`);
  }
  if (!jsonData) {
    throw new Error('Unexpected non-JSON response');
  }
  return jsonData;
})

    .then(data => {
        if (data.success) {
            // Show success message
            alert('Section content updated successfully!');
            
            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('sectionContentModal'));
            if (modal) {
                modal.hide();
            }
            
            // Reload to reflect updated section card content / preview
            setTimeout(()=>window.location.reload(), 400);
            
        } else {
            showSectionError(data.message || 'Failed to save section content');
        }
    })
    .catch(error => {
        console.error('Error saving section content:', error);
        let msg = error.message || '';
        if (msg.includes('not active')) {
            msg = 'هذا التيمبلت غير مُفعل للموقع الحالي. فعّل الهيدر/الفوتر أولاً ثم أعد المحاولة.';
        } else if (msg.startsWith('HTTP 419')) {
            msg = 'انتهت الجلسة أو فشل التحقق من CSRF. حدّث الصفحة ثم أعد المحاولة.';
        } else if (msg.startsWith('HTTP 401')) {
            msg = 'غير مسموح. قد تحتاج لتسجيل الدخول مجدداً.';
        }
        alert('فشل في حفظ المحتوى: ' + msg);
        showSectionError('Failed to save section content: ' + msg);
    })
    .finally(() => {
        // Restore save button
        saveButton.innerHTML = originalText;
        saveButton.disabled = false;
        
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    });
}

// Header Content Editor Function
function openHeaderContentEditor(headerId) {
    console.log('=== OPENING HEADER EDITOR ===');
    console.log('Header ID:', headerId);
    
    // Set section and page IDs for header editing
    document.getElementById('section_id').value = headerId;
    document.getElementById('page_id').value = 'header';
    
    console.log('Set section_id to:', document.getElementById('section_id').value);
    console.log('Set page_id to:', document.getElementById('page_id').value);
    
    // Reset modal state
    resetSectionModal();
    
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('sectionContentModal'));
    modal.show();
    
    console.log('Modal shown, loading header data...');
    
    // Load header data
    loadHeaderData(headerId);
}

// Footer Content Editor Function
function openFooterContentEditor(footerId) {
    console.log('=== OPENING FOOTER EDITOR ===');
    console.log('Footer ID:', footerId);
    
    // Set section and page IDs for footer editing
    document.getElementById('section_id').value = footerId;
    document.getElementById('page_id').value = 'footer';
    
    console.log('Set section_id to:', document.getElementById('section_id').value);
    console.log('Set page_id to:', document.getElementById('page_id').value);
    
    // Reset modal state
    resetSectionModal();
    
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('sectionContentModal'));
    modal.show();
    
    console.log('Modal shown, loading footer data...');
    
    // Load footer data
    loadFooterData(footerId);
}

function loadHeaderData(headerId) {
    console.log('=== LOADING HEADER DATA ===');
    console.log('Loading header data for:', headerId);
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    console.log('CSRF Token:', csrfToken);
    
    const url = `/admin/headers-footers/headers/${headerId}`;
    console.log('Fetch URL:', url);
    
    fetch(url, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        credentials: 'same-origin',
        cache: 'no-store'
    })
    .then(response => {
        console.log('LoadHeaderData response status:', response.status);
        console.log('LoadHeaderData response:', response);
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: Failed to fetch header data`);
        }
        return response.json();
    })
    .then(data => {
        console.log('LoadHeaderData response data:', data);
        if (data.success) {
            populateHeaderForm(data.header);
        } else {
            showSectionError(data.message || 'Failed to load header data');
        }
    })
    .catch(error => {
        console.error('LoadHeaderData error:', error);
        showSectionError('Failed to load header data. Please try again.');
    });
}

function populateHeaderForm(headerData) {
    console.log('Populating header form with data:', headerData);
    
    // Hide loading state
    document.getElementById('sectionLoadingState').style.display = 'none';
    
    // Show section info
    const sectionInfo = document.getElementById('sectionInfo');
    document.getElementById('sectionName').textContent = headerData.name || 'Header';
    document.getElementById('sectionDescription').textContent = 'Edit header template content (Navigation links managed separately)';
    sectionInfo.style.display = 'block';
    
    // Build form fields based on header content
    const formContainer = document.getElementById('sectionFormFields');
    let formHtml = '';
    
    // Get the content data - handle both structured data and HTML content
    let contentData = headerData.content_data || headerData.content || {};
    
    // If content is HTML-based, extract variables and show default fields
    if (typeof contentData === 'object' && contentData.html) {
        console.log('HTML-based header detected, showing default editable fields');
        
        // Extract existing values from HTML if possible
        const htmlContent = contentData.html || '';
        const extractedData = extractVariablesFromHTML(htmlContent);
        
        // Show default header fields with extracted or default values
        const defaultHeaderFields = {
            'site_name': { 
                label: 'Site Name', 
                type: 'text', 
                required: true,
                value: extractedData.site_name || contentData.site_name || 'Development Site'
            },
            'logo_url': { 
                label: 'Logo URL', 
                type: 'url', 
                required: false,
                value: extractedData.logo_url || contentData.logo_url || '/img/logo.png'
            },
            'cta_button_text': { 
                label: 'CTA Button Text', 
                type: 'text', 
                required: false,
                value: extractedData.cta_button_text || contentData.cta_button_text || 'Get Started'
            },
            'cta_button_url': { 
                label: 'CTA Button URL', 
                type: 'url', 
                required: false,
                value: extractedData.cta_button_url || contentData.cta_button_url || '#'
            },
            'tagline': { 
                label: 'Tagline/Subtitle', 
                type: 'textarea', 
                required: false,
                value: extractedData.tagline || contentData.tagline || ''
            }
        };
        
        Object.entries(defaultHeaderFields).forEach(([fieldName, fieldConfig]) => {
            formHtml += generateFormField(fieldName, fieldConfig, fieldConfig.value);
        });
    } else {
        // Handle structured data normally
        const editableFields = getHeaderEditableFields(contentData);
        Object.entries(editableFields).forEach(([fieldName, fieldConfig]) => {
            formHtml += generateFormField(fieldName, fieldConfig.config, fieldConfig.value);
        });
    }
    
    if (!formHtml) {
        formHtml = '<div class="alert alert-info">No editable fields found for this header template.</div>';
    }
    
    formContainer.innerHTML = formHtml;
    formContainer.style.display = 'block';
    
    // Re-initialize feather icons
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
}

function loadFooterData(footerId) {
    console.log('Loading footer data for:', footerId);
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    fetch(`/admin/headers-footers/footers/${footerId}`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        credentials: 'same-origin',
        cache: 'no-store'
    })
    .then(response => {
        console.log('LoadFooterData response status:', response.status);
        if (!response.ok) {
            throw new Error('Failed to fetch footer data');
        }
        return response.json();
    })
    .then(data => {
        console.log('LoadFooterData response data:', data);
        if (data.success) {
            populateFooterForm(data.footer);
        } else {
            showSectionError(data.message || 'Failed to load footer data');
        }
    })
    .catch(error => {
        console.error('LoadFooterData error:', error);
        showSectionError('Failed to load footer data. Please try again.');
    });
}

function populateFooterForm(footerData) {
    console.log('Populating footer form with data:', footerData);
    
    // Hide loading state
    document.getElementById('sectionLoadingState').style.display = 'none';
    
    // Show section info
    const sectionInfo = document.getElementById('sectionInfo');
    document.getElementById('sectionName').textContent = footerData.name || 'Footer';
    document.getElementById('sectionDescription').textContent = 'Edit footer template content (Navigation links and social media managed separately)';
    sectionInfo.style.display = 'block';
    
    // Build form fields based on footer content
    const formContainer = document.getElementById('sectionFormFields');
    let formHtml = '';
    
    // Get the content data - handle both structured data and HTML content
    let contentData = footerData.content_data || footerData.content || {};
    
    // If content is HTML-based, extract variables and show default fields
    if (typeof contentData === 'object' && contentData.html) {
        console.log('HTML-based footer detected, showing default editable fields');
        
        // Extract existing values from HTML if possible
        const htmlContent = contentData.html || '';
        const extractedData = extractVariablesFromHTML(htmlContent);
        
        // Show default footer fields with extracted or default values
        const defaultFooterFields = {
            'company_name': { 
                label: 'Company Name', 
                type: 'text', 
                required: true,
                value: extractedData.company_name || contentData.company_name || 'Your Company'
            },
            'copyright': { 
                label: 'Copyright Text', 
                type: 'text', 
                required: false,
                value: extractedData.copyright || contentData.copyright || '© 2025 Your Company. All rights reserved.'
            },
            'contact_email': { 
                label: 'Contact Email', 
                type: 'email', 
                required: false,
                value: extractedData.contact_email || contentData.contact_email || ''
            },
            'contact_phone': { 
                label: 'Contact Phone', 
                type: 'text', 
                required: false,
                value: extractedData.contact_phone || contentData.contact_phone || ''
            },
            'address': { 
                label: 'Address', 
                type: 'textarea', 
                required: false,
                value: extractedData.address || contentData.address || ''
            },
            'description': { 
                label: 'Company Description', 
                type: 'textarea', 
                required: false,
                value: extractedData.description || contentData.description || ''
            }
        };
        
        Object.entries(defaultFooterFields).forEach(([fieldName, fieldConfig]) => {
            formHtml += generateFormField(fieldName, fieldConfig, fieldConfig.value);
        });
    } else {
        // Handle structured data normally
        const editableFields = getFooterEditableFields(contentData);
        Object.entries(editableFields).forEach(([fieldName, fieldConfig]) => {
            formHtml += generateFormField(fieldName, fieldConfig.config, fieldConfig.value);
        });
    }
    
    if (!formHtml) {
        formHtml = '<div class="alert alert-info">No editable fields found for this footer template.</div>';
    }
    
    formContainer.innerHTML = formHtml;
    formContainer.style.display = 'block';
    
    // Re-initialize feather icons
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
}

// Helper functions to get editable fields for headers/footers
function getHeaderEditableFields(contentData) {
    const editableFields = {};
    
    // Define common header fields with better field detection
    const headerFieldMappings = {
        'site_name': { label: 'Site Name', type: 'text', required: true },
        'title': { label: 'Header Title', type: 'text', required: false },
        'logo_url': { label: 'Logo URL', type: 'url', required: false },
        'logo': { label: 'Logo URL', type: 'url', required: false },
        'logo_text': { label: 'Logo Text', type: 'text', required: false },
        'tagline': { label: 'Tagline', type: 'text', required: false },
        'subtitle': { label: 'Subtitle', type: 'text', required: false },
        'description': { label: 'Description', type: 'textarea', required: false },
        'cta_button_text': { label: 'CTA Button Text', type: 'text', required: false },
        'cta_button_url': { label: 'CTA Button URL', type: 'url', required: false },
        'button_text': { label: 'Button Text', type: 'text', required: false },
        'button_url': { label: 'Button URL', type: 'url', required: false }
    };
    
    // Fields to exclude (navigation-related)
    // const excludeFields = [
    //     'menu', 'menu_items', 'navigation', 'nav_links', 'links',
    //     'home_link', 'about_link', 'services_link', 'contact_link', 'html'
    // ];
    
    // First, add predefined fields that exist in content
    Object.entries(headerFieldMappings).forEach(([fieldName, config]) => {
        if (contentData.hasOwnProperty(fieldName)) {
            editableFields[fieldName] = {
                config: config,
                value: contentData[fieldName] || ''
            };
        }
    });
    
    // Then process other existing fields
    Object.entries(contentData).forEach(([fieldName, value]) => {
        const lowerFieldName = fieldName.toLowerCase();
        
        // Skip if already processed or excluded
        if (editableFields[fieldName] ) {
            return;
        }
        
        // Skip complex objects and arrays
        if (typeof value === 'object' && value !== null) {
            return;
        }
        
        // Determine field type based on field name and value
        let fieldType = 'text';
        if (lowerFieldName.includes('email')) fieldType = 'email';
        else if (lowerFieldName.includes('url') || lowerFieldName.includes('link')) fieldType = 'url';
        else if (lowerFieldName.includes('description') || lowerFieldName.includes('tagline') || lowerFieldName.includes('text')) fieldType = 'textarea';
        else if (lowerFieldName.includes('color')) fieldType = 'color';
        else if (typeof value === 'boolean') fieldType = 'boolean';
        
        editableFields[fieldName] = {
            config: {
                label: formatFieldLabel(fieldName),
                type: fieldType,
                required: false
            },
            value: value || ''
        };
    });
    
    // Add default site_name if not present
    if (!editableFields.site_name && !editableFields.title) {
        editableFields.site_name = {
            config: { label: 'Site Name', type: 'text', required: true },
            value: contentData.site_name || contentData.title || 'Your Site Name'
        };
    }
    
    return editableFields;
}

function getFooterEditableFields(contentData) {
    const editableFields = {};
    
    // Define common footer fields with better field detection
    const footerFieldMappings = {
        'company_name': { label: 'Company Name', type: 'text', required: true },
        'copyright': { label: 'Copyright Text', type: 'text', required: false },
        'copyright_text': { label: 'Copyright Text', type: 'text', required: false },
        'contact_email': { label: 'Contact Email', type: 'email', required: false },
        'email': { label: 'Contact Email', type: 'email', required: false },
        'contact_phone': { label: 'Contact Phone', type: 'text', required: false },
        'phone': { label: 'Contact Phone', type: 'text', required: false },
        'address': { label: 'Address', type: 'textarea', required: false },
        'contact_address': { label: 'Address', type: 'textarea', required: false },
        'description': { label: 'Company Description', type: 'textarea', required: false },
        'about_text': { label: 'About Text', type: 'textarea', required: false }
    };
    
    // Fields to exclude (navigation and social media related)
    // const excludeFields = [
    //     'menu', 'menu_items', 'navigation', 'nav_links', 'links', 'footer_links',
    //     'social', 'social_links', 'social_media', 'facebook', 'twitter', 'instagram',
    //     'linkedin', 'youtube', 'tiktok', 'snapchat', 'pinterest', 'html', 'services_links',
    //     'quick_links', 'services'
    // ];
    
    // First, add predefined fields that exist in content
    Object.entries(footerFieldMappings).forEach(([fieldName, config]) => {
        if (contentData.hasOwnProperty(fieldName)) {
            editableFields[fieldName] = {
                config: config,
                value: contentData[fieldName] || ''
            };
        }
    });
    
    // Then process other existing fields
    Object.entries(contentData).forEach(([fieldName, value]) => {
        const lowerFieldName = fieldName.toLowerCase();
        
        // Skip if already processed or excluded
        if (editableFields[fieldName] ) {
            return;
        }
        
        // Skip complex objects and arrays
        if (typeof value === 'object' && value !== null) {
            return;
        }
        
        // Determine field type based on field name and value
        let fieldType = 'text';
        if (lowerFieldName.includes('email')) fieldType = 'email';
        else if (lowerFieldName.includes('url') || lowerFieldName.includes('link')) fieldType = 'url';
        else if (lowerFieldName.includes('address') || lowerFieldName.includes('description') || lowerFieldName.includes('about')) fieldType = 'textarea';
        else if (lowerFieldName.includes('color')) fieldType = 'color';
        else if (typeof value === 'boolean') fieldType = 'boolean';
        
        editableFields[fieldName] = {
            config: {
                label: formatFieldLabel(fieldName),
                type: fieldType,
                required: false
            },
            value: value || ''
        };
    });
    
    // Add default fields if not present
    if (!editableFields.company_name) {
        editableFields.company_name = {
            config: { label: 'Company Name', type: 'text', required: true },
            value: contentData.company_name || 'Your Company'
        };
    }
    if (!editableFields.copyright && !editableFields.copyright_text) {
        editableFields.copyright = {
            config: { label: 'Copyright Text', type: 'text', required: false },
            value: contentData.copyright || contentData.copyright_text || '© 2025 Your Company. All rights reserved.'
        };
    }
    
    return editableFields;
}

function formatFieldLabel(fieldName) {
    return fieldName
        .replace(/_/g, ' ')
        .replace(/([A-Z])/g, ' $1')
        .replace(/^./, str => str.toUpperCase())
        .trim();
}

// Extract variables from HTML template content
function extractVariablesFromHTML(htmlContent) {
    const extractedData = {};
    
    if (!htmlContent || typeof htmlContent !== 'string') {
        return extractedData;
    }
    
    // Common patterns to extract from HTML
    const patterns = {
        'site_name': [/\{\{\s*\$config\['site_name'\]\s*\}\}/g, /site_name['"]\s*:\s*['"]([^'"]*)['"]/g],
        'company_name': [/\{\{\s*\$config\['company_name'\]\s*\}\}/g, /company_name['"]\s*:\s*['"]([^'"]*)['"]/g],
        'logo_url': [/\{\{\s*\$config\['logo_url'\]\s*\}\}/g, /logo_url['"]\s*:\s*['"]([^'"]*)['"]/g],
        'copyright': [/\{\{\s*\$config\['copyright'\]\s*\}\}/g, /copyright['"]\s*:\s*['"]([^'"]*)['"]/g],
        'contact_email': [/\{\{\s*\$config\['contact_email'\]\s*\}\}/g, /contact_email['"]\s*:\s*['"]([^'"]*)['"]/g],
        'contact_phone': [/\{\{\s*\$config\['contact_phone'\]\s*\}\}/g, /contact_phone['"]\s*:\s*['"]([^'"]*)['"]/g]
    };
    
    // Try to extract values from the HTML content
    Object.entries(patterns).forEach(([fieldName, regexes]) => {
        for (const regex of regexes) {
            const matches = htmlContent.match(regex);
            if (matches && matches.length > 0) {
                // If it's a capture group, get the captured value
                const match = regex.exec(htmlContent);
                if (match && match[1]) {
                    extractedData[fieldName] = match[1];
                    break;
                }
            }
        }
    });
    
    console.log('Extracted data from HTML:', extractedData);
    return extractedData;
    // لازم دول لو عندك onclick/ onchange في الـ HTML
window.openSectionContentEditor = openSectionContentEditor;
window.openHeaderContentEditor  = openHeaderContentEditor;
window.openFooterContentEditor  = openFooterContentEditor;
window.toggleLinkStatus         = toggleLinkStatus;
window.removeLink               = removeLink;
window.addArrayItem             = addArrayItem;
window.removeArrayItem          = removeArrayItem;

}