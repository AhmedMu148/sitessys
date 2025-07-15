@extends('admin.layouts.master')

@section('title', 'Create Footer')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1 class="h3 mb-0"><strong>Create</strong> Footer</h1>
                <a href="{{ route('admin.headers-footers.index') }}" class="btn btn-secondary">
                    <i class="align-middle" data-feather="arrow-left"></i> Back to Headers & Footers
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Footer Information</h5>
                </div>
                <form action="{{ route('admin.headers-footers.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="type" value="footer">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Footer Name</label>
                                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" 
                                       value="{{ old('name') }}" required placeholder="Enter footer name">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="sort_order" class="form-label">Sort Order</label>
                                <input type="number" name="sort_order" id="sort_order" class="form-control @error('sort_order') is-invalid @enderror" 
                                       value="{{ old('sort_order', 0) }}" min="0" placeholder="0">
                                @error('sort_order')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Higher numbers appear first in the list</div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea name="description" id="description" rows="3" class="form-control @error('description') is-invalid @enderror" 
                                      placeholder="Brief description of this footer">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="html_content" class="form-label">Footer HTML Content</label>
                            <textarea name="html_content" id="html_content" rows="15" 
                                      class="form-control @error('html_content') is-invalid @enderror font-monospace"
                                      placeholder="Enter your footer HTML content here...">{{ old('html_content', $defaultFooterHtml ?? '') }}</textarea>
                            @error('html_content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">You can use Bootstrap 5 classes and custom CSS</div>
                        </div>
                        
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input type="checkbox" name="is_active" class="form-check-input" 
                                       id="is_active" {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">Active Footer</label>
                                <div class="form-text">Only active footers can be selected for use</div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="align-middle" data-feather="save"></i> Create Footer
                        </button>
                        <a href="{{ route('admin.headers-footers.index') }}" class="btn btn-secondary">
                            <i class="align-middle" data-feather="x"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="col-12 col-lg-4">
            <!-- Footer Templates -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Footer Templates</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <button type="button" class="list-group-item list-group-item-action" onclick="loadTemplate('simple')">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">Simple Footer</h6>
                                <small>Basic</small>
                            </div>
                            <p class="mb-1">Clean footer with copyright and links</p>
                        </button>
                        
                        <button type="button" class="list-group-item list-group-item-action" onclick="loadTemplate('multi-column')">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">Multi-Column</h6>
                                <small>Advanced</small>
                            </div>
                            <p class="mb-1">4-column footer with links and contact info</p>
                        </button>
                        
                        <button type="button" class="list-group-item list-group-item-action" onclick="loadTemplate('social')">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">Social Footer</h6>
                                <small>Modern</small>
                            </div>
                            <p class="mb-1">Footer with social media icons</p>
                        </button>

                        <button type="button" class="list-group-item list-group-item-action" onclick="loadTemplate('corporate')">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">Corporate</h6>
                                <small>Professional</small>
                            </div>
                            <p class="mb-1">Professional footer for business sites</p>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Footer Tips -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">Footer Design Tips</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="align-middle" data-feather="info"></i>
                        <strong>Best Practices:</strong>
                        <ul class="mb-0 mt-2">
                            <li>Include important links</li>
                            <li>Add contact information</li>
                            <li>Keep it organized and clean</li>
                            <li>Use consistent styling</li>
                        </ul>
                    </div>
                    
                    <div class="alert alert-warning">
                        <i class="align-middle" data-feather="alert-triangle"></i>
                        <strong>Note:</strong> Footer content should be responsive and work well on all devices.
                    </div>
                </div>
            </div>

            <!-- Available Classes -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">Available CSS Classes</h5>
                </div>
                <div class="card-body">
                    <small class="text-muted">
                        <strong>Bootstrap 5 Classes:</strong><br>
                        • Container: <code>.container</code>, <code>.container-fluid</code><br>
                        • Grid: <code>.row</code>, <code>.col-*</code><br>
                        • Text: <code>.text-center</code>, <code>.text-muted</code><br>
                        • Background: <code>.bg-dark</code>, <code>.bg-light</code><br>
                        • Spacing: <code>.p-*</code>, <code>.m-*</code>, <code>.py-*</code>
                    </small>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
<script>
const footerTemplates = {
    simple: `<footer class="bg-dark text-light py-4">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <p>&copy; 2024 Your Company Name. All rights reserved.</p>
            </div>
            <div class="col-md-6 text-md-end">
                <a href="/privacy" class="text-light me-3">Privacy Policy</a>
                <a href="/terms" class="text-light">Terms of Service</a>
            </div>
        </div>
    </div>
</footer>`,

    'multi-column': `<footer class="bg-dark text-light py-5">
    <div class="container">
        <div class="row">
            <div class="col-md-3 mb-4">
                <h5>Company</h5>
                <ul class="list-unstyled">
                    <li><a href="/about" class="text-light">About Us</a></li>
                    <li><a href="/team" class="text-light">Our Team</a></li>
                    <li><a href="/careers" class="text-light">Careers</a></li>
                </ul>
            </div>
            <div class="col-md-3 mb-4">
                <h5>Services</h5>
                <ul class="list-unstyled">
                    <li><a href="/service1" class="text-light">Service 1</a></li>
                    <li><a href="/service2" class="text-light">Service 2</a></li>
                    <li><a href="/service3" class="text-light">Service 3</a></li>
                </ul>
            </div>
            <div class="col-md-3 mb-4">
                <h5>Support</h5>
                <ul class="list-unstyled">
                    <li><a href="/help" class="text-light">Help Center</a></li>
                    <li><a href="/contact" class="text-light">Contact Us</a></li>
                    <li><a href="/faq" class="text-light">FAQ</a></li>
                </ul>
            </div>
            <div class="col-md-3 mb-4">
                <h5>Contact Info</h5>
                <p class="mb-1">123 Business St.</p>
                <p class="mb-1">City, State 12345</p>
                <p class="mb-1">Phone: (555) 123-4567</p>
                <p>Email: info@company.com</p>
            </div>
        </div>
        <hr class="my-4">
        <div class="row">
            <div class="col-md-6">
                <p>&copy; 2024 Your Company Name. All rights reserved.</p>
            </div>
            <div class="col-md-6 text-md-end">
                <a href="/privacy" class="text-light me-3">Privacy Policy</a>
                <a href="/terms" class="text-light">Terms of Service</a>
            </div>
        </div>
    </div>
</footer>`,

    social: `<footer class="bg-primary text-white py-4">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <p class="mb-0">&copy; 2024 Your Company Name. All rights reserved.</p>
            </div>
            <div class="col-md-6 text-md-end">
                <div class="social-links">
                    <a href="#" class="text-white me-3"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="text-white me-3"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="text-white me-3"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="text-white me-3"><i class="fab fa-linkedin-in"></i></a>
                    <a href="#" class="text-white"><i class="fab fa-youtube"></i></a>
                </div>
            </div>
        </div>
    </div>
</footer>`,

    corporate: `<footer class="bg-light border-top py-5">
    <div class="container">
        <div class="row">
            <div class="col-md-4 mb-4">
                <h5 class="text-primary">Your Company</h5>
                <p class="text-muted">Professional services for businesses worldwide. We deliver excellence in everything we do.</p>
                <div class="d-flex">
                    <a href="#" class="btn btn-outline-primary btn-sm me-2"><i class="fab fa-linkedin-in"></i></a>
                    <a href="#" class="btn btn-outline-primary btn-sm me-2"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="btn btn-outline-primary btn-sm"><i class="fab fa-facebook-f"></i></a>
                </div>
            </div>
            <div class="col-md-2 mb-4">
                <h6>Company</h6>
                <ul class="list-unstyled">
                    <li><a href="/about" class="text-muted">About</a></li>
                    <li><a href="/team" class="text-muted">Team</a></li>
                    <li><a href="/careers" class="text-muted">Careers</a></li>
                    <li><a href="/news" class="text-muted">News</a></li>
                </ul>
            </div>
            <div class="col-md-2 mb-4">
                <h6>Services</h6>
                <ul class="list-unstyled">
                    <li><a href="/consulting" class="text-muted">Consulting</a></li>
                    <li><a href="/development" class="text-muted">Development</a></li>
                    <li><a href="/support" class="text-muted">Support</a></li>
                    <li><a href="/training" class="text-muted">Training</a></li>
                </ul>
            </div>
            <div class="col-md-2 mb-4">
                <h6>Resources</h6>
                <ul class="list-unstyled">
                    <li><a href="/blog" class="text-muted">Blog</a></li>
                    <li><a href="/docs" class="text-muted">Documentation</a></li>
                    <li><a href="/help" class="text-muted">Help Center</a></li>
                    <li><a href="/api" class="text-muted">API</a></li>
                </ul>
            </div>
            <div class="col-md-2 mb-4">
                <h6>Legal</h6>
                <ul class="list-unstyled">
                    <li><a href="/privacy" class="text-muted">Privacy</a></li>
                    <li><a href="/terms" class="text-muted">Terms</a></li>
                    <li><a href="/security" class="text-muted">Security</a></li>
                    <li><a href="/cookies" class="text-muted">Cookies</a></li>
                </ul>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-6">
                <p class="text-muted mb-0">&copy; 2024 Your Company Name. All rights reserved.</p>
            </div>
            <div class="col-md-6 text-md-end">
                <p class="text-muted mb-0">Made with ❤️ for our customers</p>
            </div>
        </div>
    </div>
</footer>`
};

function loadTemplate(templateName) {
    const textarea = document.getElementById('html_content');
    if (footerTemplates[templateName]) {
        textarea.value = footerTemplates[templateName];
        
        // Auto-generate name if empty
        const nameInput = document.getElementById('name');
        if (!nameInput.value) {
            const templateNames = {
                'simple': 'Simple Footer',
                'multi-column': 'Multi-Column Footer',
                'social': 'Social Media Footer',
                'corporate': 'Corporate Footer'
            };
            nameInput.value = templateNames[templateName] || 'Footer Template';
        }
        
        showNotification(`${templateName.charAt(0).toUpperCase() + templateName.slice(1)} footer template loaded!`, 'success');
    }
}

function showNotification(message, type) {
    // Create a simple notification
    const notification = document.createElement('div');
    notification.className = `alert alert-${type === 'success' ? 'success' : 'info'} alert-dismissible fade show position-fixed`;
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    notification.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(notification);
    
    // Auto-remove after 3 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.parentNode.removeChild(notification);
        }
    }, 3000);
}

document.addEventListener('DOMContentLoaded', function() {
    // Auto-generate slug from footer name
    const nameInput = document.getElementById('name');
    
    nameInput.addEventListener('input', function() {
        // You can add auto-slug generation if needed
    });
});
</script>
@endsection
