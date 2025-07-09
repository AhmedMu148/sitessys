@extends('admin.layouts.master')

@section('title', 'Edit Design')

@section('content')
<div class="container-fluid p-0">
    <div class="row mb-2 mb-xl-3">
        <div class="col-auto d-none d-sm-block">
            <h3><strong>Edit</strong> Design</h3>
        </div>
        <div class="col-auto ms-auto text-end mt-n1">
            <a href="{{ route('admin.designs.index') }}" class="btn btn-light">
                <i class="align-middle" data-feather="arrow-left"></i>
                Back to Designs
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Edit Design Information</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.designs.update', $design) }}" method="POST" id="designForm">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="site_id" class="form-label">Site</label>
                                    <select name="site_id" id="site_id" class="form-select @error('site_id') is-invalid @enderror" required>
                                        <option value="">Select Site</option>
                                        @foreach($sites as $site)
                                            <option value="{{ $site->id }}" {{ (old('site_id') ?? $design->site_id) == $site->id ? 'selected' : '' }}>
                                                {{ $site->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('site_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="page_id" class="form-label">Page</label>
                                    <select name="page_id" id="page_id" class="form-select @error('page_id') is-invalid @enderror" required>
                                        <option value="">Select Page</option>
                                        @foreach($pages as $page)
                                            <option value="{{ $page->id }}" {{ (old('page_id') ?? $design->page_id) == $page->id ? 'selected' : '' }}>
                                                {{ $page->title }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('page_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="layout_id" class="form-label">Layout</label>
                                    <select name="layout_id" id="layout_id" class="form-select @error('layout_id') is-invalid @enderror" required>
                                        <option value="">Select Layout</option>
                                        @foreach($layouts as $layout)
                                            <option value="{{ $layout->id }}" data-type="{{ $layout->type->name }}" {{ (old('layout_id') ?? $design->layout_id) == $layout->id ? 'selected' : '' }}>
                                                {{ $layout->name }} ({{ $layout->type->name }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('layout_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="layout_type_id" class="form-label">Layout Type</label>
                                    <select name="layout_type_id" id="layout_type_id" class="form-select @error('layout_type_id') is-invalid @enderror" required>
                                        <option value="">Select Layout Type</option>
                                        @foreach($layoutTypes as $type)
                                            <option value="{{ $type->id }}" {{ (old('layout_type_id') ?? $design->layout_type_id) == $type->id ? 'selected' : '' }}>
                                                {{ $type->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('layout_type_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="lang_code" class="form-label">Language</label>
                                    <select name="lang_code" id="lang_code" class="form-select @error('lang_code') is-invalid @enderror" required>
                                        <option value="">Select Language</option>
                                        @foreach($languages as $lang)
                                            <option value="{{ $lang->code }}" {{ (old('lang_code') ?? $design->lang_code) == $lang->code ? 'selected' : '' }}>
                                                {{ $lang->name }} ({{ $lang->code }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('lang_code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="sort_order" class="form-label">Sort Order</label>
                                    <input type="number" name="sort_order" id="sort_order" class="form-control @error('sort_order') is-invalid @enderror" 
                                           value="{{ old('sort_order', $design->sort_order) }}" min="0">
                                    @error('sort_order')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="data" class="form-label">Section Data (JSON)</label>
                            <textarea name="data" id="data" class="form-control @error('data') is-invalid @enderror" 
                                      rows="15" placeholder="Enter section data in JSON format">{{ old('data', $design->data ? json_encode($design->data, JSON_PRETTY_PRINT) : '') }}</textarea>
                            @error('data')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                <strong>Common section data examples:</strong><br>
                                <button type="button" class="btn btn-sm btn-outline-primary me-2" onclick="loadHeroTemplate()">Hero Section</button>
                                <button type="button" class="btn btn-sm btn-outline-primary me-2" onclick="loadFeaturesTemplate()">Features Section</button>
                                <button type="button" class="btn btn-sm btn-outline-primary me-2" onclick="loadAboutTemplate()">About Section</button>
                                <button type="button" class="btn btn-sm btn-outline-primary me-2" onclick="loadContactTemplate()">Contact Section</button>
                                <button type="button" class="btn btn-sm btn-outline-success mt-2" onclick="validateJSON()">Validate JSON</button>
                                <button type="button" class="btn btn-sm btn-outline-warning mt-2" onclick="formatJSON()">Format JSON</button>
                            </div>
                            <div id="json-validation-message" class="mt-2"></div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="status" id="status" value="1" {{ (old('status') ?? $design->status) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="status">
                                            Active
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('admin.designs.index') }}" class="btn btn-light me-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">Update Design</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function loadHeroTemplate() {
    const heroTemplate = {
        "title": "Welcome to Our Company",
        "subtitle": "We create amazing solutions for your business",
        "description": "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.",
        "button": {
            "text": "Get Started",
            "link": "#contact"
        },
        "image": "hero-bg.jpg"
    };
    document.getElementById('data').value = JSON.stringify(heroTemplate, null, 2);
}

function loadFeaturesTemplate() {
    const featuresTemplate = {
        "title": "Our Features",
        "subtitle": "Why choose us",
        "features": [
            {
                "icon": "fas fa-rocket",
                "title": "Fast Performance",
                "description": "Lightning fast loading times and optimized performance."
            },
            {
                "icon": "fas fa-shield-alt",
                "title": "Secure",
                "description": "Top-notch security measures to protect your data."
            },
            {
                "icon": "fas fa-heart",
                "title": "User Friendly",
                "description": "Intuitive interface designed with user experience in mind."
            }
        ]
    };
    document.getElementById('data').value = JSON.stringify(featuresTemplate, null, 2);
}

function loadAboutTemplate() {
    const aboutTemplate = {
        "title": "About Us",
        "subtitle": "Our Story",
        "description": "We are a team of passionate professionals dedicated to delivering exceptional results. With years of experience in the industry, we understand what it takes to succeed.",
        "mission": "To provide innovative solutions that empower businesses to reach their full potential.",
        "vision": "To be the leading provider of cutting-edge technology solutions.",
        "image": "about-us.jpg"
    };
    document.getElementById('data').value = JSON.stringify(aboutTemplate, null, 2);
}

function loadContactTemplate() {
    const contactTemplate = {
        "title": "Contact Us",
        "subtitle": "Get in touch",
        "description": "We'd love to hear from you. Send us a message and we'll respond as soon as possible.",
        "contact_info": {
            "email": "info@company.com",
            "phone": "+1 (555) 123-4567",
            "address": "123 Business Street, City, State 12345"
        },
        "form_fields": [
            {
                "name": "name",
                "label": "Full Name",
                "type": "text",
                "required": true
            },
            {
                "name": "email",
                "label": "Email Address",
                "type": "email",
                "required": true
            },
            {
                "name": "message",
                "label": "Message",
                "type": "textarea",
                "required": true
            }
        ]
    };
    document.getElementById('data').value = JSON.stringify(contactTemplate, null, 2);
}

// Auto-update layout type when layout is selected
document.getElementById('layout_id').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const layoutTypeSelect = document.getElementById('layout_type_id');
    
    if (selectedOption.value) {
        const layoutTypeName = selectedOption.dataset.type;
        for (let option of layoutTypeSelect.options) {
            if (option.text === layoutTypeName) {
                option.selected = true;
                break;
            }
        }
    }
});

// JSON validation function
function validateJSON() {
    const textarea = document.getElementById('data');
    const messageDiv = document.getElementById('json-validation-message');
    
    if (!textarea.value.trim()) {
        messageDiv.innerHTML = '<div class="alert alert-info">No JSON data to validate</div>';
        return;
    }
    
    try {
        JSON.parse(textarea.value);
        messageDiv.innerHTML = '<div class="alert alert-success">✓ Valid JSON format</div>';
    } catch (e) {
        messageDiv.innerHTML = '<div class="alert alert-danger">✗ Invalid JSON: ' + e.message + '</div>';
    }
}

// JSON formatting function
function formatJSON() {
    const textarea = document.getElementById('data');
    const messageDiv = document.getElementById('json-validation-message');
    
    if (!textarea.value.trim()) {
        messageDiv.innerHTML = '<div class="alert alert-info">No JSON data to format</div>';
        return;
    }
    
    try {
        const parsed = JSON.parse(textarea.value);
        textarea.value = JSON.stringify(parsed, null, 2);
        messageDiv.innerHTML = '<div class="alert alert-success">✓ JSON formatted successfully</div>';
    } catch (e) {
        messageDiv.innerHTML = '<div class="alert alert-danger">✗ Cannot format invalid JSON: ' + e.message + '</div>';
    }
}

// Form submission validation
document.getElementById('designForm').addEventListener('submit', function(e) {
    const textarea = document.getElementById('data');
    
    if (textarea.value.trim()) {
        try {
            JSON.parse(textarea.value);
        } catch (e) {
            e.preventDefault();
            alert('Please fix the JSON format before submitting the form.');
            return false;
        }
    }
});
</script>
@endsection
