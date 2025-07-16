@extends('admin.layouts.master')

@section('title', 'Add New Section - ' . $page->name)

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h1 class="h3 mb-0"><strong>Add New Section</strong> to {{ $page->name }}</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.pages.index') }}">Pages</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.pages.show', $page) }}">{{ $page->name }}</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.page-sections.index', ['page_id' => $page->id]) }}">Sections</a></li>
                            <li class="breadcrumb-item active">Add Section</li>
                        </ol>
                    </nav>
                </div>
                <div>
                    <a href="{{ route('admin.page-sections.index', ['page_id' => $page->id]) }}" class="btn btn-secondary">
                        <i class="align-middle" data-feather="arrow-left"></i> Back to Sections
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Section Information</h5>
                </div>
                <form action="{{ route('admin.page-sections.store', ['page_id' => $page->id]) }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Section Name</label>
                                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" 
                                       value="{{ old('name') }}" required placeholder="Enter section name">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">A descriptive name for this section</div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="layout_id" class="form-label">Section Layout</label>
                                <select name="layout_id" id="layout_id" class="form-select @error('layout_id') is-invalid @enderror" required>
                                    <option value="">Select a layout</option>
                                    @foreach($availableLayouts as $layout)
                                        <option value="{{ $layout->id }}" 
                                                {{ old('layout_id') == $layout->id ? 'selected' : '' }}
                                                data-description="{{ $layout->description }}">
                                            {{ $layout->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('layout_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Choose the layout template for this section</div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description <small class="text-muted">(Optional)</small></label>
                            <textarea name="description" id="description" rows="2" class="form-control @error('description') is-invalid @enderror" 
                                      placeholder="Brief description of this section">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Content Data Section -->
                        <div class="mb-3">
                            <label for="content_data" class="form-label">
                                Content Data <small class="text-muted">(JSON Format - Optional)</small>
                            </label>
                            <textarea name="content_data" id="content_data" rows="6" 
                                      class="form-control @error('content_data') is-invalid @enderror font-monospace"
                                      placeholder='{"title": "Section Title", "content": "Section content goes here"}'>{{ old('content_data') }}</textarea>
                            @error('content_data')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                JSON data for dynamic content. This will be passed to the layout template.
                            </div>
                        </div>

                        <!-- Settings Section -->
                        <div class="mb-3">
                            <label for="settings" class="form-label">
                                Section Settings <small class="text-muted">(JSON Format - Optional)</small>
                            </label>
                            <textarea name="settings" id="settings" rows="4" 
                                      class="form-control @error('settings') is-invalid @enderror font-monospace"
                                      placeholder='{"background": "#ffffff", "padding": "50px", "animation": "fade-in"}'>{{ old('settings') }}</textarea>
                            @error('settings')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                Configuration settings for styling and behavior.
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input type="checkbox" name="is_active" class="form-check-input" 
                                       id="is_active" {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">Active Section</label>
                                <div class="form-text">Inactive sections won't be displayed on the page</div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="align-middle" data-feather="save"></i> Create Section
                        </button>
                        <a href="{{ route('admin.page-sections.index', ['page_id' => $page->id]) }}" class="btn btn-secondary">
                            <i class="align-middle" data-feather="x"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="col-12 col-lg-4">
            <!-- Layout Preview -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Layout Preview</h5>
                </div>
                <div class="card-body">
                    <div id="layout-preview">
                        <div class="text-center text-muted">
                            <i class="align-middle" data-feather="layout" style="width: 48px; height: 48px;"></i>
                            <p class="mt-2">Select a layout to see preview</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content Data Examples -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">Content Data Examples</h5>
                </div>
                <div class="card-body">
                    <div class="accordion" id="examplesAccordion">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#hero-example">
                                    Hero Section
                                </button>
                            </h2>
                            <div id="hero-example" class="accordion-collapse collapse" data-bs-parent="#examplesAccordion">
                                <div class="accordion-body">
                                    <pre class="bg-light p-2 rounded small"><code>{
  "title": "Welcome to Our Site",
  "subtitle": "Amazing services await",
  "description": "Lorem ipsum dolor...",
  "button_text": "Get Started",
  "button_link": "/contact",
  "background_image": "/images/hero-bg.jpg"
}</code></pre>
                                    <button class="btn btn-sm btn-outline-primary" onclick="fillExample('hero')">Use This Example</button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#features-example">
                                    Features Section
                                </button>
                            </h2>
                            <div id="features-example" class="accordion-collapse collapse" data-bs-parent="#examplesAccordion">
                                <div class="accordion-body">
                                    <pre class="bg-light p-2 rounded small"><code>{
  "title": "Our Features",
  "features": [
    {
      "icon": "fa-rocket",
      "title": "Fast Performance",
      "description": "Lightning fast load times"
    },
    {
      "icon": "fa-shield",
      "title": "Secure",
      "description": "Enterprise-level security"
    }
  ]
}</code></pre>
                                    <button class="btn btn-sm btn-outline-primary" onclick="fillExample('features')">Use This Example</button>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#about-example">
                                    About Section
                                </button>
                            </h2>
                            <div id="about-example" class="accordion-collapse collapse" data-bs-parent="#examplesAccordion">
                                <div class="accordion-body">
                                    <pre class="bg-light p-2 rounded small"><code>{
  "title": "About Us",
  "content": "We are a leading company...",
  "image": "/images/about.jpg",
  "stats": [
    {"label": "Years Experience", "value": "10+"},
    {"label": "Happy Clients", "value": "500+"}
  ]
}</code></pre>
                                    <button class="btn btn-sm btn-outline-primary" onclick="fillExample('about')">Use This Example</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tips -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">Tips</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="align-middle" data-feather="info"></i>
                        <strong>JSON Format:</strong>
                        <ul class="mb-0 mt-2">
                            <li>Use double quotes for strings</li>
                            <li>No trailing commas</li>
                            <li>Validate JSON before saving</li>
                        </ul>
                    </div>
                    
                    <div class="alert alert-warning">
                        <i class="align-middle" data-feather="alert-triangle"></i>
                        <strong>Note:</strong> Content data structure should match your selected layout's requirements.
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const layoutSelect = document.getElementById('layout_id');
    const layoutPreview = document.getElementById('layout-preview');
    
    // Handle layout selection change
    layoutSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (selectedOption.value) {
            const description = selectedOption.dataset.description;
            layoutPreview.innerHTML = `
                <div class="border rounded p-3">
                    <h6>${selectedOption.text}</h6>
                    <p class="text-muted small mb-0">${description || 'No description available'}</p>
                </div>
            `;
        } else {
            layoutPreview.innerHTML = `
                <div class="text-center text-muted">
                    <i class="align-middle" data-feather="layout" style="width: 48px; height: 48px;"></i>
                    <p class="mt-2">Select a layout to see preview</p>
                </div>
            `;
        }
        
        // Re-initialize feather icons
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    });

    // JSON validation
    const jsonTextareas = document.querySelectorAll('#content_data, #settings');
    jsonTextareas.forEach(textarea => {
        textarea.addEventListener('blur', function() {
            validateJSON(this);
        });
    });
});

function validateJSON(textarea) {
    const value = textarea.value.trim();
    if (value === '') return; // Empty is valid
    
    try {
        JSON.parse(value);
        textarea.classList.remove('is-invalid');
        // Remove any existing error message
        const errorDiv = textarea.nextElementSibling;
        if (errorDiv && errorDiv.classList.contains('invalid-feedback')) {
            errorDiv.style.display = 'none';
        }
    } catch (e) {
        textarea.classList.add('is-invalid');
        // Add error message if not exists
        let errorDiv = textarea.nextElementSibling;
        if (!errorDiv || !errorDiv.classList.contains('invalid-feedback')) {
            errorDiv = document.createElement('div');
            errorDiv.className = 'invalid-feedback';
            textarea.parentNode.insertBefore(errorDiv, textarea.nextSibling);
        }
        errorDiv.textContent = 'Invalid JSON format: ' + e.message;
        errorDiv.style.display = 'block';
    }
}

function fillExample(type) {
    const contentDataTextarea = document.getElementById('content_data');
    const examples = {
        hero: `{
  "title": "Welcome to Our Site",
  "subtitle": "Amazing services await",
  "description": "Lorem ipsum dolor sit amet, consectetur adipiscing elit.",
  "button_text": "Get Started",
  "button_link": "/contact",
  "background_image": "/images/hero-bg.jpg"
}`,
        features: `{
  "title": "Our Features",
  "features": [
    {
      "icon": "fa-rocket",
      "title": "Fast Performance",
      "description": "Lightning fast load times"
    },
    {
      "icon": "fa-shield",
      "title": "Secure",
      "description": "Enterprise-level security"
    },
    {
      "icon": "fa-users",
      "title": "User Friendly",
      "description": "Intuitive and easy to use"
    }
  ]
}`,
        about: `{
  "title": "About Us",
  "content": "We are a leading company in our industry with years of experience and dedication to excellence.",
  "image": "/images/about.jpg",
  "stats": [
    {"label": "Years Experience", "value": "10+"},
    {"label": "Happy Clients", "value": "500+"},
    {"label": "Projects Completed", "value": "1000+"}
  ]
}`
    };
    
    if (examples[type]) {
        contentDataTextarea.value = examples[type];
        validateJSON(contentDataTextarea);
    }
}

// Format JSON
function formatJSON(textareaId) {
    const textarea = document.getElementById(textareaId);
    const value = textarea.value.trim();
    
    if (value === '') return;
    
    try {
        const parsed = JSON.parse(value);
        textarea.value = JSON.stringify(parsed, null, 2);
        validateJSON(textarea);
    } catch (e) {
        alert('Invalid JSON format. Please fix the errors first.');
    }
}
</script>
@endsection
