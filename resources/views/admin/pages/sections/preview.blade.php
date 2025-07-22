<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('Section Preview') }} - {{ $section->name }}</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- RTL Support -->
    @if(app()->getLocale() == 'ar')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    @endif
    
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: #f8f9fa;
        }
        
        .preview-container {
            background: white;
            min-height: 100vh;
            position: relative;
        }
        
        .preview-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1rem;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .preview-header h1 {
            margin: 0;
            font-size: 1.25rem;
            font-weight: 600;
        }
        
        .preview-info {
            font-size: 0.875rem;
            opacity: 0.9;
            margin-top: 0.25rem;
        }
        
        .section-content {
            padding: 0;
            position: relative;
        }
        
        .preview-tools {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: white;
            border-radius: 50px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
            padding: 10px;
            display: flex;
            gap: 10px;
            z-index: 1001;
        }
        
        .preview-tool {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
            color: #666;
        }
        
        .preview-tool:hover {
            background: #f0f0f0;
            transform: scale(1.1);
        }
        
        .preview-tool.active {
            background: #667eea;
            color: white;
        }
        
        .responsive-view {
            margin: 0 auto;
            transition: all 0.3s ease;
            background: white;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        
        .responsive-view.mobile {
            max-width: 375px;
        }
        
        .responsive-view.tablet {
            max-width: 768px;
        }
        
        .responsive-view.desktop {
            max-width: 100%;
        }
        
        /* RTL Support */
        [dir="rtl"] .preview-tools {
            right: auto;
            left: 20px;
        }
        
        /* Section-specific styles */
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 100px 0;
            text-align: center;
        }
        
        .services-section {
            padding: 80px 0;
            background: #f8f9fa;
        }
        
        .about-section {
            padding: 80px 0;
            background: white;
        }
        
        .contact-section {
            padding: 80px 0;
            background: #343a40;
            color: white;
        }
        
        .footer-section {
            background: #212529;
            color: white;
            padding: 40px 0 20px;
        }
    </style>
    
    <!-- Custom Section Styles -->
    @if($section->custom_styles)
    <style>
        {!! $section->custom_styles !!}
    </style>
    @endif
</head>
<body>
    <div class="preview-container">
        <!-- Preview Header -->
        <div class="preview-header">
            <h1>{{ __('Section Preview') }}: {{ $section->name }}</h1>
            <div class="preview-info">
                {{ __('Page') }}: {{ $page->name }} | {{ __('Layout') }}: {{ $section->layout->name ?? __('Default') }}
            </div>
        </div>
        
        <!-- Section Content -->
        <div class="section-content">
            <div class="responsive-view desktop" id="previewContent">
                <div id="sectionContainer" data-section-id="{{ $section->id }}">
                    <!-- Section will be rendered here -->
                </div>
            </div>
        </div>
        
        <!-- Preview Tools -->
        <div class="preview-tools">
            <button class="preview-tool active" data-view="desktop" title="{{ __('Desktop View') }}">
                <i class="fas fa-desktop"></i>
            </button>
            <button class="preview-tool" data-view="tablet" title="{{ __('Tablet View') }}">
                <i class="fas fa-tablet-alt"></i>
            </button>
            <button class="preview-tool" data-view="mobile" title="{{ __('Mobile View') }}">
                <i class="fas fa-mobile-alt"></i>
            </button>
            <button class="preview-tool" onclick="window.location.reload()" title="{{ __('Refresh') }}">
                <i class="fas fa-sync-alt"></i>
            </button>
            <button class="preview-tool" onclick="window.close()" title="{{ __('Close') }}">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    
    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Section Data -->
    <script>
        const sectionData = @json($section);
        const currentLanguage = '{{ app()->getLocale() }}';
        
        // Responsive view switching
        document.querySelectorAll('[data-view]').forEach(button => {
            button.addEventListener('click', function() {
                const view = this.dataset.view;
                const container = document.getElementById('previewContent');
                
                // Update active button
                document.querySelectorAll('[data-view]').forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
                
                // Update container class
                container.className = `responsive-view ${view}`;
            });
        });
        
        // Render section content
        function renderSection() {
            const container = document.getElementById('sectionContainer');
            const content = sectionData.content || {};
            const images = sectionData.images || {};
            const colors = sectionData.colors || {};
            
            let html = '';
            
            // Get content for current language
            const sectionContent = content[currentLanguage] || content['en'] || {};
            const title = sectionContent.title || 'Section Title';
            const description = sectionContent.description || 'Section description';
            const text = sectionContent.text || '';
            
            // Determine section type from name or content
            const sectionName = sectionData.name.toLowerCase();
            
            if (sectionName.includes('hero') || sectionName.includes('banner')) {
                html = renderHeroSection(title, description, text, images, colors);
            } else if (sectionName.includes('service') || sectionName.includes('feature')) {
                html = renderServicesSection(title, description, text, images, colors);
            } else if (sectionName.includes('about') || sectionName.includes('info')) {
                html = renderAboutSection(title, description, text, images, colors);
            } else if (sectionName.includes('contact') || sectionName.includes('form')) {
                html = renderContactSection(title, description, text, images, colors);
            } else if (sectionName.includes('footer')) {
                html = renderFooterSection(title, description, text, images, colors);
            } else {
                html = renderGenericSection(title, description, text, images, colors);
            }
            
            container.innerHTML = html;
            
            // Apply custom colors if available
            if (colors.background) {
                container.style.backgroundColor = colors.background;
            }
            if (colors.text) {
                container.style.color = colors.text;
            }
        }
        
        function renderHeroSection(title, description, text, images, colors) {
            const bgImage = images.background || '/images/sections/hero-bg.svg';
            return `
                <section class="hero-section" style="background-image: url('${bgImage}'); background-size: cover; background-position: center;">
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-8 text-center">
                                <h1 class="display-4 fw-bold mb-4">${title}</h1>
                                <p class="lead mb-4">${description}</p>
                                ${text ? `<div class="mb-4">${text}</div>` : ''}
                                <a href="#" class="btn btn-light btn-lg px-5">{{ __('Get Started') }}</a>
                            </div>
                        </div>
                    </div>
                </section>
            `;
        }
        
        function renderServicesSection(title, description, text, images, colors) {
            return `
                <section class="services-section">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-12 text-center mb-5">
                                <h2 class="display-5 fw-bold">${title}</h2>
                                <p class="lead">${description}</p>
                                ${text ? `<div class="mt-3">${text}</div>` : ''}
                            </div>
                        </div>
                        <div class="row g-4">
                            <div class="col-md-4">
                                <div class="card h-100 border-0 shadow-sm">
                                    <div class="card-body text-center p-4">
                                        <i class="fas fa-cog fa-3x text-primary mb-3"></i>
                                        <h5>{{ __('Service 1') }}</h5>
                                        <p>{{ __('Description of service 1') }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card h-100 border-0 shadow-sm">
                                    <div class="card-body text-center p-4">
                                        <i class="fas fa-chart-line fa-3x text-success mb-3"></i>
                                        <h5>{{ __('Service 2') }}</h5>
                                        <p>{{ __('Description of service 2') }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card h-100 border-0 shadow-sm">
                                    <div class="card-body text-center p-4">
                                        <i class="fas fa-users fa-3x text-info mb-3"></i>
                                        <h5>{{ __('Service 3') }}</h5>
                                        <p>{{ __('Description of service 3') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            `;
        }
        
        function renderAboutSection(title, description, text, images, colors) {
            return `
                <section class="about-section">
                    <div class="container">
                        <div class="row align-items-center">
                            <div class="col-lg-6">
                                <h2 class="display-5 fw-bold mb-4">${title}</h2>
                                <p class="lead mb-4">${description}</p>
                                ${text ? `<div class="mb-4">${text}</div>` : ''}
                                <a href="#" class="btn btn-primary btn-lg">{{ __('Learn More') }}</a>
                            </div>
                            <div class="col-lg-6">
                                <img src="${images.main || '/images/sections/about-image.jpg'}" alt="${title}" class="img-fluid rounded shadow">
                            </div>
                        </div>
                    </div>
                </section>
            `;
        }
        
        function renderContactSection(title, description, text, images, colors) {
            return `
                <section class="contact-section">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-6">
                                <h2 class="display-5 fw-bold mb-4">${title}</h2>
                                <p class="lead mb-4">${description}</p>
                                ${text ? `<div class="mb-4">${text}</div>` : ''}
                                <div class="contact-info">
                                    <div class="d-flex align-items-center mb-3">
                                        <i class="fas fa-envelope fa-2x me-3"></i>
                                        <div>
                                            <h6 class="mb-0">{{ __('Email') }}</h6>
                                            <span>info@example.com</span>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center mb-3">
                                        <i class="fas fa-phone fa-2x me-3"></i>
                                        <div>
                                            <h6 class="mb-0">{{ __('Phone') }}</h6>
                                            <span>+1 234 567 8900</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <form class="bg-white p-4 rounded shadow">
                                    <div class="mb-3">
                                        <input type="text" class="form-control" placeholder="{{ __('Your Name') }}">
                                    </div>
                                    <div class="mb-3">
                                        <input type="email" class="form-control" placeholder="{{ __('Your Email') }}">
                                    </div>
                                    <div class="mb-3">
                                        <textarea class="form-control" rows="5" placeholder="{{ __('Your Message') }}"></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-lg w-100">{{ __('Send Message') }}</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </section>
            `;
        }
        
        function renderFooterSection(title, description, text, images, colors) {
            return `
                <footer class="footer-section">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-4">
                                <h5>${title}</h5>
                                <p>${description}</p>
                                ${text ? `<div>${text}</div>` : ''}
                            </div>
                            <div class="col-lg-2">
                                <h6>{{ __('Links') }}</h6>
                                <ul class="list-unstyled">
                                    <li><a href="#" class="text-light">{{ __('Home') }}</a></li>
                                    <li><a href="#" class="text-light">{{ __('About') }}</a></li>
                                    <li><a href="#" class="text-light">{{ __('Services') }}</a></li>
                                    <li><a href="#" class="text-light">{{ __('Contact') }}</a></li>
                                </ul>
                            </div>
                            <div class="col-lg-2">
                                <h6>{{ __('Support') }}</h6>
                                <ul class="list-unstyled">
                                    <li><a href="#" class="text-light">{{ __('FAQ') }}</a></li>
                                    <li><a href="#" class="text-light">{{ __('Help') }}</a></li>
                                    <li><a href="#" class="text-light">{{ __('Terms') }}</a></li>
                                    <li><a href="#" class="text-light">{{ __('Privacy') }}</a></li>
                                </ul>
                            </div>
                            <div class="col-lg-4">
                                <h6>{{ __('Contact Info') }}</h6>
                                <p><i class="fas fa-envelope me-2"></i> info@example.com</p>
                                <p><i class="fas fa-phone me-2"></i> +1 234 567 8900</p>
                                <div class="social-links">
                                    <a href="#" class="text-light me-3"><i class="fab fa-facebook-f"></i></a>
                                    <a href="#" class="text-light me-3"><i class="fab fa-twitter"></i></a>
                                    <a href="#" class="text-light me-3"><i class="fab fa-linkedin-in"></i></a>
                                    <a href="#" class="text-light"><i class="fab fa-instagram"></i></a>
                                </div>
                            </div>
                        </div>
                        <hr class="my-4">
                        <div class="row">
                            <div class="col-12 text-center">
                                <p>&copy; 2025 {{ __('All rights reserved') }}.</p>
                            </div>
                        </div>
                    </div>
                </footer>
            `;
        }
        
        function renderGenericSection(title, description, text, images, colors) {
            return `
                <section class="py-5">
                    <div class="container">
                        <div class="row">
                            <div class="col-12">
                                <h2 class="display-5 fw-bold mb-4">${title}</h2>
                                <p class="lead mb-4">${description}</p>
                                ${text ? `<div>${text}</div>` : ''}
                            </div>
                        </div>
                    </div>
                </section>
            `;
        }
        
        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            renderSection();
        });
    </script>
    
    <!-- Custom Section Scripts -->
    @if($section->custom_scripts)
    <script>
        {!! $section->custom_scripts !!}
    </script>
    @endif
</body>
</html>
