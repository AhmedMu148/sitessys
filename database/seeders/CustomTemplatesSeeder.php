<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TplLayout;

class CustomTemplatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        echo "🎨 Creating custom templates...\n";
        
        $this->createNavigationTemplate();
        $this->createFooterTemplate();
        $this->createSectionTemplates();
        
        echo "✅ Custom templates created successfully!\n";
    }

    /**
     * Create navigation template
     */
    private function createNavigationTemplate()
    {
        TplLayout::updateOrCreate(
            ['tpl_id' => 'modern-navigation-2024'],
            [
                'tpl_id' => 'modern-navigation-2024',
                'layout_type' => 'header',
                'name' => 'Modern Navigation 2024',
                'description' => 'Modern responsive navigation with gradient background and smooth animations',
                'preview_image' => '/img/templates/modern-nav-preview.jpg',
                'path' => 'frontend.templates.headers.modern-navigation',
                'content' => $this->getNavigationTemplate(),
                'configurable_fields' => [
                    'brand_text' => ['type' => 'text', 'default' => 'Your Brand', 'label' => 'Brand Name'],
                    'logo_url' => ['type' => 'url', 'default' => '', 'label' => 'Logo URL'],
                    'gradient_start' => ['type' => 'color', 'default' => '#667eea', 'label' => 'Gradient Start Color'],
                    'gradient_end' => ['type' => 'color', 'default' => '#764ba2', 'label' => 'Gradient End Color'],
                    'text_color' => ['type' => 'color', 'default' => '#ffffff', 'label' => 'Text Color'],
                    'sticky_navbar' => ['type' => 'boolean', 'default' => true, 'label' => 'Sticky Navigation'],
                    'show_search' => ['type' => 'boolean', 'default' => true, 'label' => 'Show Search Box'],
                    'search_placeholder' => ['type' => 'text', 'default' => 'Search...', 'label' => 'Search Placeholder'],
                    'menu_items' => [
                        'type' => 'array',
                        'default' => [
                            ['label' => 'Home', 'url' => '/', 'target' => '_self'],
                            ['label' => 'About', 'url' => '/about', 'target' => '_self'],
                            ['label' => 'Services', 'url' => '/services', 'target' => '_self'],
                            ['label' => 'Portfolio', 'url' => '/portfolio', 'target' => '_self'],
                            ['label' => 'Blog', 'url' => '/blog', 'target' => '_self'],
                            ['label' => 'Contact', 'url' => '/contact', 'target' => '_self']
                        ],
                        'label' => 'Menu Items'
                    ],
                    'cta_button' => [
                        'type' => 'object',
                        'default' => [
                            'text' => 'Get Started',
                            'url' => '/contact',
                            'style' => 'btn-light',
                            'show' => true
                        ],
                        'label' => 'CTA Button Settings'
                    ]
                ],
                'default_config' => [
                    'brand_text' => 'Your Brand',
                    'gradient_start' => '#667eea',
                    'gradient_end' => '#764ba2',
                    'text_color' => '#ffffff',
                    'sticky_navbar' => true,
                    'show_search' => true
                ],
                'status' => true,
                'sort_order' => 1
            ]
        );
    }

    /**
     * Create footer template
     */
    private function createFooterTemplate()
    {
        TplLayout::updateOrCreate(
            ['tpl_id' => 'comprehensive-footer-2024'],
            [
                'tpl_id' => 'comprehensive-footer-2024',
                'layout_type' => 'footer',
                'name' => 'Comprehensive Footer 2024',
                'description' => 'Multi-column footer with company info, links, newsletter, and social media',
                'preview_image' => '/img/templates/comprehensive-footer-preview.jpg',
                'path' => 'frontend.templates.footers.comprehensive-footer',
                'content' => $this->getFooterTemplate(),
                'configurable_fields' => [
                    'company_name' => ['type' => 'text', 'default' => 'Your Company', 'label' => 'Company Name'],
                    'logo_url' => ['type' => 'url', 'default' => '', 'label' => 'Footer Logo URL'],
                    'company_description' => [
                        'type' => 'textarea',
                        'default' => 'We are a leading company providing innovative solutions to help your business grow and succeed in the digital world.',
                        'label' => 'Company Description'
                    ],
                    'contact_info' => [
                        'type' => 'object',
                        'default' => [
                            'email' => 'contact@yourcompany.com',
                            'phone' => '+1 (555) 123-4567',
                            'address' => '123 Business Street, City, State 12345',
                            'business_hours' => 'Mon-Fri: 9AM-6PM'
                        ],
                        'label' => 'Contact Information'
                    ],
                    'quick_links' => [
                        'type' => 'array',
                        'default' => [
                            ['label' => 'About Us', 'url' => '/about'],
                            ['label' => 'Services', 'url' => '/services'],
                            ['label' => 'Portfolio', 'url' => '/portfolio'],
                            ['label' => 'Blog', 'url' => '/blog'],
                            ['label' => 'Contact', 'url' => '/contact'],
                            ['label' => 'FAQ', 'url' => '/faq']
                        ],
                        'label' => 'Quick Links'
                    ],
                    'services_links' => [
                        'type' => 'array',
                        'default' => [
                            ['label' => 'Web Development', 'url' => '/services/web-development'],
                            ['label' => 'Mobile Apps', 'url' => '/services/mobile-apps'],
                            ['label' => 'Digital Marketing', 'url' => '/services/digital-marketing'],
                            ['label' => 'SEO Services', 'url' => '/services/seo'],
                            ['label' => 'Consulting', 'url' => '/services/consulting']
                        ],
                        'label' => 'Services Links'
                    ],
                    'social_links' => [
                        'type' => 'array',
                        'default' => [
                            ['platform' => 'Facebook', 'icon' => 'fab fa-facebook-f', 'url' => 'https://facebook.com/yourcompany'],
                            ['platform' => 'Twitter', 'icon' => 'fab fa-twitter', 'url' => 'https://twitter.com/yourcompany'],
                            ['platform' => 'LinkedIn', 'icon' => 'fab fa-linkedin-in', 'url' => 'https://linkedin.com/company/yourcompany'],
                            ['platform' => 'Instagram', 'icon' => 'fab fa-instagram', 'url' => 'https://instagram.com/yourcompany']
                        ],
                        'label' => 'Social Media Links'
                    ],
                    'newsletter' => [
                        'type' => 'object',
                        'default' => [
                            'enabled' => true,
                            'title' => 'Stay Updated',
                            'description' => 'Subscribe to our newsletter for the latest updates and offers.',
                            'placeholder' => 'Enter your email address',
                            'button_text' => 'Subscribe'
                        ],
                        'label' => 'Newsletter Settings'
                    ],
                    'copyright_text' => [
                        'type' => 'text',
                        'default' => '© 2024 Your Company. All rights reserved.',
                        'label' => 'Copyright Text'
                    ],
                    'background_color' => ['type' => 'color', 'default' => '#1f2937', 'label' => 'Background Color'],
                    'text_color' => ['type' => 'color', 'default' => '#ffffff', 'label' => 'Text Color']
                ],
                'default_config' => [
                    'company_name' => 'Your Company',
                    'background_color' => '#1f2937',
                    'text_color' => '#ffffff'
                ],
                'status' => true,
                'sort_order' => 1
            ]
        );
    }

    /**
     * Create section templates
     */
    private function createSectionTemplates()
    {
        $sections = [
            [
                'tpl_id' => 'hero-video-background',
                'name' => 'Hero Section with Video Background',
                'description' => 'Full-screen hero section with video background and call-to-action',
                'content' => $this->getHeroVideoSection()
            ],
            [
                'tpl_id' => 'features-grid-modern',
                'name' => 'Modern Features Grid',
                'description' => 'Grid layout showcasing features with icons and descriptions',
                'content' => $this->getFeaturesGridSection()
            ],
            [
                'tpl_id' => 'testimonials-carousel',
                'name' => 'Testimonials Carousel',
                'description' => 'Customer testimonials in a responsive carousel format',
                'content' => $this->getTestimonialsSection()
            ],
            [
                'tpl_id' => 'pricing-table-modern',
                'name' => 'Modern Pricing Table',
                'description' => 'Responsive pricing table with multiple plans and features',
                'content' => $this->getPricingSection()
            ],
            [
                'tpl_id' => 'cta-section-gradient',
                'name' => 'Call-to-Action with Gradient',
                'description' => 'Eye-catching CTA section with gradient background',
                'content' => $this->getCtaSection()
            ]
        ];

        foreach ($sections as $index => $section) {
            TplLayout::updateOrCreate(
                ['tpl_id' => $section['tpl_id']],
                [
                    'tpl_id' => $section['tpl_id'],
                    'layout_type' => 'section',
                    'name' => $section['name'],
                    'description' => $section['description'],
                    'preview_image' => "/img/templates/{$section['tpl_id']}-preview.jpg",
                    'path' => "frontend.templates.sections.{$section['tpl_id']}",
                    'content' => $section['content'],
                    'configurable_fields' => $this->getSectionConfigurableFields($section['tpl_id']),
                    'default_config' => $this->getSectionDefaultConfig($section['tpl_id']),
                    'status' => true,
                    'sort_order' => $index + 1
                ]
            );
        }
    }

    /**
     * Get navigation template content
     */
    private function getNavigationTemplate()
    {
        return [
            'html' => '<nav class="navbar navbar-expand-lg navbar-modern" style="background: linear-gradient(135deg, {{ $config[\'gradient_start\'] ?? \'#667eea\' }}, {{ $config[\'gradient_end\'] ?? \'#764ba2\' }});">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="/">
            @if(!empty($config[\'logo_url\']))
                <img src="{{ $config[\'logo_url\'] }}" alt="{{ $config[\'brand_text\'] ?? \'Logo\' }}" height="40" class="me-2">
            @endif
            <span class="fw-bold" style="color: {{ $config[\'text_color\'] ?? \'#ffffff\' }};">
                {{ $config[\'brand_text\'] ?? \'Your Brand\' }}
            </span>
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarContent">
            <ul class="navbar-nav me-auto">
                @foreach($config[\'menu_items\'] ?? [] as $item)
                    <li class="nav-item">
                        <a class="nav-link fw-medium" 
                           href="{{ $item[\'url\'] }}" 
                           target="{{ $item[\'target\'] ?? \'_self\' }}"
                           style="color: {{ $config[\'text_color\'] ?? \'#ffffff\' }};">
                            {{ $item[\'label\'] }}
                        </a>
                    </li>
                @endforeach
            </ul>
            
            <div class="d-flex align-items-center">
                @if($config[\'show_search\'] ?? true)
                    <form class="d-flex me-3" role="search">
                        <input class="form-control form-control-sm" type="search" 
                               placeholder="{{ $config[\'search_placeholder\'] ?? \'Search...\' }}" 
                               style="border-radius: 20px;">
                    </form>
                @endif
                
                @if(($config[\'cta_button\'][\'show\'] ?? true) && !empty($config[\'cta_button\'][\'text\']))
                    <a href="{{ $config[\'cta_button\'][\'url\'] ?? \'/contact\' }}" 
                       class="btn {{ $config[\'cta_button\'][\'style\'] ?? \'btn-light\' }} btn-sm rounded-pill px-3">
                        {{ $config[\'cta_button\'][\'text\'] ?? \'Get Started\' }}
                    </a>
                @endif
            </div>
        </div>
    </div>
</nav>',
            'css' => '.navbar-modern {
    backdrop-filter: blur(10px);
    transition: all 0.3s ease;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}
.navbar-modern.scrolled {
    background: rgba(255, 255, 255, 0.95) !important;
    backdrop-filter: blur(20px);
    box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
}
.navbar-modern.scrolled .nav-link,
.navbar-modern.scrolled .navbar-brand {
    color: #333 !important;
}
.navbar-nav .nav-link:hover {
    transform: translateY(-1px);
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}',
            'js' => 'document.addEventListener("DOMContentLoaded", function() {
    const navbar = document.querySelector(".navbar-modern");
    if (navbar) {
        window.addEventListener("scroll", function() {
            if (window.scrollY > 100) {
                navbar.classList.add("scrolled");
            } else {
                navbar.classList.remove("scrolled");
            }
        });
    }
});'
        ];
    }

    /**
     * Get footer template content
     */
    private function getFooterTemplate()
    {
        return [
            'html' => '<footer class="footer-comprehensive" style="background-color: {{ $config[\'background_color\'] ?? \'#1f2937\' }}; color: {{ $config[\'text_color\'] ?? \'#ffffff\' }};">
    <div class="container py-5">
        <div class="row g-4">
            <!-- Company Info Column -->
            <div class="col-lg-4 col-md-6">
                <div class="footer-brand mb-3">
                    @if(!empty($config[\'logo_url\']))
                        <img src="{{ $config[\'logo_url\'] }}" alt="{{ $config[\'company_name\'] ?? \'Company Logo\' }}" height="40" class="mb-3">
                    @else
                        <h5 class="fw-bold mb-3">{{ $config[\'company_name\'] ?? \'Your Company\' }}</h5>
                    @endif
                </div>
                <p class="mb-3">{{ $config[\'company_description\'] ?? \'Default company description\' }}</p>
                <div class="contact-info">
                    @if(!empty($config[\'contact_info\'][\'email\']))
                        <p class="mb-2"><i class="fas fa-envelope me-2"></i>{{ $config[\'contact_info\'][\'email\'] }}</p>
                    @endif
                    @if(!empty($config[\'contact_info\'][\'phone\']))
                        <p class="mb-2"><i class="fas fa-phone me-2"></i>{{ $config[\'contact_info\'][\'phone\'] }}</p>
                    @endif
                    @if(!empty($config[\'contact_info\'][\'address\']))
                        <p class="mb-2"><i class="fas fa-map-marker-alt me-2"></i>{{ $config[\'contact_info\'][\'address\'] }}</p>
                    @endif
                    @if(!empty($config[\'contact_info\'][\'business_hours\']))
                        <p class="mb-2"><i class="fas fa-clock me-2"></i>{{ $config[\'contact_info\'][\'business_hours\'] }}</p>
                    @endif
                </div>
            </div>
            
            <!-- Quick Links Column -->
            <div class="col-lg-2 col-md-6">
                <h6 class="fw-bold mb-3">Quick Links</h6>
                <ul class="list-unstyled">
                    @foreach($config[\'quick_links\'] ?? [] as $link)
                        <li class="mb-2">
                            <a href="{{ $link[\'url\'] }}" class="text-decoration-none" style="color: {{ $config[\'text_color\'] ?? \'#ffffff\' }}; opacity: 0.8;">
                                {{ $link[\'label\'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
            
            <!-- Services Column -->
            <div class="col-lg-2 col-md-6">
                <h6 class="fw-bold mb-3">Services</h6>
                <ul class="list-unstyled">
                    @foreach($config[\'services_links\'] ?? [] as $service)
                        <li class="mb-2">
                            <a href="{{ $service[\'url\'] }}" class="text-decoration-none" style="color: {{ $config[\'text_color\'] ?? \'#ffffff\' }}; opacity: 0.8;">
                                {{ $service[\'label\'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
            
            <!-- Newsletter Column -->
            <div class="col-lg-4 col-md-6">
                @if($config[\'newsletter\'][\'enabled\'] ?? true)
                    <h6 class="fw-bold mb-3">{{ $config[\'newsletter\'][\'title\'] ?? \'Stay Updated\' }}</h6>
                    <p class="mb-3">{{ $config[\'newsletter\'][\'description\'] ?? \'Subscribe to our newsletter\' }}</p>
                    <form class="newsletter-form">
                        <div class="input-group mb-3">
                            <input type="email" class="form-control" 
                                   placeholder="{{ $config[\'newsletter\'][\'placeholder\'] ?? \'Enter your email\' }}"
                                   required>
                            <button class="btn btn-primary" type="submit">
                                {{ $config[\'newsletter\'][\'button_text\'] ?? \'Subscribe\' }}
                            </button>
                        </div>
                    </form>
                @endif
                
                <!-- Social Links -->
                <div class="social-links mt-4">
                    <h6 class="fw-bold mb-3">Follow Us</h6>
                    <div class="d-flex gap-3">
                        @foreach($config[\'social_links\'] ?? [] as $social)
                            <a href="{{ $social[\'url\'] }}" target="_blank" 
                               class="social-link d-flex align-items-center justify-content-center"
                               style="width: 40px; height: 40px; background-color: rgba(255,255,255,0.1); border-radius: 50%; color: {{ $config[\'text_color\'] ?? \'#ffffff\' }}; text-decoration: none;">
                                <i class="{{ $social[\'icon\'] }}"></i>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Copyright -->
        <hr class="my-4" style="border-color: rgba(255,255,255,0.1);">
        <div class="row align-items-center">
            <div class="col-md-6">
                <p class="mb-0">{{ $config[\'copyright_text\'] ?? \'© 2024 Your Company. All rights reserved.\' }}</p>
            </div>
            <div class="col-md-6 text-md-end">
                <a href="/privacy" class="text-decoration-none me-3" style="color: {{ $config[\'text_color\'] ?? \'#ffffff\' }}; opacity: 0.8;">Privacy Policy</a>
                <a href="/terms" class="text-decoration-none" style="color: {{ $config[\'text_color\'] ?? \'#ffffff\' }}; opacity: 0.8;">Terms of Service</a>
            </div>
        </div>
    </div>
</footer>',
            'css' => '.footer-comprehensive {
    margin-top: auto;
}
.footer-comprehensive .social-link:hover {
    background-color: rgba(255,255,255,0.2) !important;
    transform: translateY(-2px);
    transition: all 0.3s ease;
}
.footer-comprehensive a:hover {
    opacity: 1 !important;
    transition: opacity 0.3s ease;
}
.newsletter-form .btn {
    border-radius: 0 0.375rem 0.375rem 0;
}',
            'js' => 'document.addEventListener("DOMContentLoaded", function() {
    const newsletterForm = document.querySelector(".newsletter-form");
    if (newsletterForm) {
        newsletterForm.addEventListener("submit", function(e) {
            e.preventDefault();
            const email = this.querySelector("input[type=email]").value;
            if (email) {
                alert("Thank you for subscribing with email: " + email);
                this.reset();
            }
        });
    }
});'
        ];
    }

    /**
     * Get hero video section
     */
    private function getHeroVideoSection()
    {
        return [
            'html' => '<section class="hero-video-bg position-relative overflow-hidden" style="min-height: 100vh;">
    @if(!empty($config[\'video_url\']))
        <video autoplay muted loop class="position-absolute w-100 h-100" style="object-fit: cover; z-index: 1;">
            <source src="{{ $config[\'video_url\'] }}" type="video/mp4">
        </video>
    @endif
    <div class="hero-overlay position-absolute w-100 h-100" style="background: rgba(0,0,0,{{ $config[\'overlay_opacity\'] ?? \'0.5\' }}); z-index: 2;"></div>
    
    <div class="container position-relative h-100 d-flex align-items-center" style="z-index: 3; min-height: 100vh;">
        <div class="row w-100">
            <div class="col-lg-8 mx-auto text-{{ $config[\'text_alignment\'] ?? \'center\' }}">
                <h1 class="display-2 fw-bold text-white mb-4 animate__animated animate__fadeInUp">
                    {{ $config[\'hero_title\'] ?? \'Welcome to Our Amazing Service\' }}
                </h1>
                <p class="lead text-white mb-5 animate__animated animate__fadeInUp animate__delay-1s">
                    {{ $config[\'hero_subtitle\'] ?? \'We provide innovative solutions to help your business grow and succeed in the digital world.\' }}
                </p>
                <div class="hero-buttons animate__animated animate__fadeInUp animate__delay-2s">
                    @if(!empty($config[\'primary_button\'][\'text\']))
                        <a href="{{ $config[\'primary_button\'][\'url\'] ?? \'#\' }}" 
                           class="btn btn-{{ $config[\'primary_button\'][\'style\'] ?? \'primary\' }} btn-lg me-3 px-5 py-3">
                            {{ $config[\'primary_button\'][\'text\'] }}
                        </a>
                    @endif
                    @if(!empty($config[\'secondary_button\'][\'text\']))
                        <a href="{{ $config[\'secondary_button\'][\'url\'] ?? \'#\' }}" 
                           class="btn btn-{{ $config[\'secondary_button\'][\'style\'] ?? \'outline-light\' }} btn-lg px-5 py-3">
                            {{ $config[\'secondary_button\'][\'text\'] }}
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    @if($config[\'show_scroll_indicator\'] ?? true)
        <div class="scroll-indicator position-absolute bottom-0 start-50 translate-middle-x pb-4" style="z-index: 3;">
            <div class="scroll-arrow animate__animated animate__bounce animate__infinite">
                <i class="fas fa-chevron-down text-white fs-3"></i>
            </div>
        </div>
    @endif
</section>',
            'css' => '.hero-video-bg {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
.scroll-indicator {
    animation: bounce 2s infinite;
}
@keyframes bounce {
    0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
    40% { transform: translateY(-10px); }
    60% { transform: translateY(-5px); }
}
.hero-buttons .btn:hover {
    transform: translateY(-2px);
    transition: all 0.3s ease;
}',
            'js' => 'document.addEventListener("DOMContentLoaded", function() {
    const scrollIndicator = document.querySelector(".scroll-indicator");
    if (scrollIndicator) {
        scrollIndicator.addEventListener("click", function() {
            window.scrollTo({
                top: window.innerHeight,
                behavior: "smooth"
            });
        });
    }
});'
        ];
    }

    /**
     * Get features grid section
     */
    private function getFeaturesGridSection()
    {
        return [
            'html' => '<section class="features-section py-5" style="background-color: {{ $config[\'background_color\'] ?? \'#f8f9fa\' }};">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col-lg-8 mx-auto">
                <h2 class="display-5 fw-bold mb-3">{{ $config[\'section_title\'] ?? \'Our Amazing Features\' }}</h2>
                <p class="lead text-muted">{{ $config[\'section_subtitle\'] ?? \'Discover what makes us different\' }}</p>
            </div>
        </div>
        
        <div class="row g-4">
            @foreach($config[\'features\'] ?? [] as $index => $feature)
                <div class="col-lg-{{ 12 / ($config[\'columns\'] ?? 3) }} col-md-6">
                    <div class="feature-card h-100 p-4 text-center bg-white rounded-3 shadow-sm hover-lift">
                        <div class="feature-icon mb-3">
                            @if(!empty($feature[\'icon\']))
                                <div class="icon-wrapper d-inline-flex align-items-center justify-content-center rounded-circle mb-3"
                                     style="width: 80px; height: 80px; background: {{ $config[\'icon_background\'] ?? \'linear-gradient(135deg, #667eea, #764ba2)\' }};">
                                    <i class="{{ $feature[\'icon\'] }} fs-2 text-white"></i>
                                </div>
                            @endif
                        </div>
                        <h4 class="fw-bold mb-3">{{ $feature[\'title\'] ?? \'Feature Title\' }}</h4>
                        <p class="text-muted">{{ $feature[\'description\'] ?? \'Feature description goes here\' }}</p>
                        @if(!empty($feature[\'link_url\']))
                            <a href="{{ $feature[\'link_url\'] }}" class="btn btn-outline-primary btn-sm mt-3">
                                {{ $feature[\'link_text\'] ?? \'Learn More\' }}
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>',
            'css' => '.feature-card {
    transition: all 0.3s ease;
    border: 1px solid rgba(0,0,0,0.05);
}
.hover-lift:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.1) !important;
}
.feature-icon .icon-wrapper {
    transition: transform 0.3s ease;
}
.feature-card:hover .icon-wrapper {
    transform: scale(1.1);
}',
            'js' => 'document.addEventListener("DOMContentLoaded", function() {
    const observerOptions = {
        threshold: 0.1,
        rootMargin: "0px 0px -50px 0px"
    };
    
    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = "1";
                entry.target.style.transform = "translateY(0)";
            }
        });
    }, observerOptions);
    
    document.querySelectorAll(".feature-card").forEach(card => {
        card.style.opacity = "0";
        card.style.transform = "translateY(30px)";
        card.style.transition = "all 0.6s ease";
        observer.observe(card);
    });
});'
        ];
    }

    /**
     * Get testimonials section
     */
    private function getTestimonialsSection()
    {
        return [
            'html' => '<section class="testimonials-section py-5" style="background: {{ $config[\'background_gradient\'] ?? \'linear-gradient(135deg, #667eea 0%, #764ba2 100%)\' }};">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col-lg-8 mx-auto">
                <h2 class="display-5 fw-bold text-white mb-3">{{ $config[\'section_title\'] ?? \'What Our Clients Say\' }}</h2>
                <p class="lead text-white opacity-75">{{ $config[\'section_subtitle\'] ?? \'Real feedback from real customers\' }}</p>
            </div>
        </div>
        
        <div id="testimonialsCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                @foreach($config[\'testimonials\'] ?? [] as $index => $testimonial)
                    <div class="carousel-item {{ $index === 0 ? \'active\' : \'\' }}">
                        <div class="row justify-content-center">
                            <div class="col-lg-8">
                                <div class="testimonial-card bg-white rounded-4 p-5 shadow-lg text-center">
                                    <div class="quote-icon mb-3">
                                        <i class="fas fa-quote-left fs-1 text-primary opacity-25"></i>
                                    </div>
                                    <blockquote class="mb-4 fs-5 text-muted">
                                        "{{ $testimonial[\'quote\'] ?? \'Amazing service and great results!\' }}"
                                    </blockquote>
                                    <div class="testimonial-author">
                                        @if(!empty($testimonial[\'avatar\']))
                                            <img src="{{ $testimonial[\'avatar\'] }}" alt="{{ $testimonial[\'name\'] }}" 
                                                 class="rounded-circle mb-3" width="80" height="80">
                                        @endif
                                        <h5 class="fw-bold mb-1">{{ $testimonial[\'name\'] ?? \'Client Name\' }}</h5>
                                        <p class="text-muted">{{ $testimonial[\'position\'] ?? \'Position\' }} at {{ $testimonial[\'company\'] ?? \'Company\' }}</p>
                                        @if(!empty($testimonial[\'rating\']))
                                            <div class="rating">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="fas fa-star {{ $i <= $testimonial[\'rating\'] ? \'text-warning\' : \'text-muted\' }}"></i>
                                                @endfor
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            @if(count($config[\'testimonials\'] ?? []) > 1)
                <button class="carousel-control-prev" type="button" data-bs-target="#testimonialsCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon bg-primary rounded-circle p-3"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#testimonialsCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon bg-primary rounded-circle p-3"></span>
                </button>
            @endif
        </div>
    </div>
</section>',
            'css' => '.testimonial-card {
    transition: transform 0.3s ease;
}
.carousel-control-prev-icon,
.carousel-control-next-icon {
    width: 40px;
    height: 40px;
}
.rating .fa-star {
    font-size: 1.2rem;
}',
            'js' => 'document.addEventListener("DOMContentLoaded", function() {
    const carousel = new bootstrap.Carousel(document.getElementById("testimonialsCarousel"), {
        interval: 5000,
        wrap: true
    });
});'
        ];
    }

    /**
     * Get pricing section
     */
    private function getPricingSection()
    {
        return [
            'html' => '<section class="pricing-section py-5" style="background-color: {{ $config[\'background_color\'] ?? \'#ffffff\' }};">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col-lg-8 mx-auto">
                <h2 class="display-5 fw-bold mb-3">{{ $config[\'section_title\'] ?? \'Choose Your Plan\' }}</h2>
                <p class="lead text-muted">{{ $config[\'section_subtitle\'] ?? \'Flexible pricing options for every need\' }}</p>
            </div>
        </div>
        
        <div class="row g-4 justify-content-center">
            @foreach($config[\'pricing_plans\'] ?? [] as $index => $plan)
                <div class="col-lg-4 col-md-6">
                    <div class="pricing-card h-100 {{ $plan[\'featured\'] ?? false ? \'featured\' : \'\' }}">
                        @if($plan[\'featured\'] ?? false)
                            <div class="featured-badge">
                                <span class="badge bg-primary rounded-pill">{{ $plan[\'badge_text\'] ?? \'Most Popular\' }}</span>
                            </div>
                        @endif
                        
                        <div class="card h-100 border-0 shadow-sm {{ $plan[\'featured\'] ?? false ? \'border-primary\' : \'\' }}">
                            <div class="card-header text-center py-4 {{ $plan[\'featured\'] ?? false ? \'bg-primary text-white\' : \'bg-light\' }}">
                                <h4 class="fw-bold mb-0">{{ $plan[\'name\'] ?? \'Plan Name\' }}</h4>
                            </div>
                            
                            <div class="card-body text-center p-4">
                                <div class="price-display mb-4">
                                    <span class="price fs-1 fw-bold text-primary">
                                        ${{ $plan[\'price\'] ?? \'0\' }}
                                    </span>
                                    <span class="period text-muted">{{ $plan[\'period\'] ?? \'/month\' }}</span>
                                </div>
                                
                                @if(!empty($plan[\'description\']))
                                    <p class="text-muted mb-4">{{ $plan[\'description\'] }}</p>
                                @endif
                                
                                <ul class="list-unstyled mb-4">
                                    @foreach($plan[\'features\'] ?? [] as $feature)
                                        <li class="mb-2">
                                            <i class="fas fa-check text-success me-2"></i>
                                            {{ $feature }}
                                        </li>
                                    @endforeach
                                </ul>
                                
                                <a href="{{ $plan[\'button_url\'] ?? \'#\' }}" 
                                   class="btn {{ $plan[\'featured\'] ?? false ? \'btn-primary\' : \'btn-outline-primary\' }} btn-lg w-100">
                                    {{ $plan[\'button_text\'] ?? \'Get Started\' }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>',
            'css' => '.pricing-card.featured {
    position: relative;
    transform: scale(1.05);
}
.featured-badge {
    position: absolute;
    top: -10px;
    left: 50%;
    transform: translateX(-50%);
    z-index: 10;
}
.pricing-card .card {
    transition: all 0.3s ease;
}
.pricing-card:hover .card {
    transform: translateY(-10px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.1) !important;
}
.price-display {
    border-bottom: 1px solid #eee;
    padding-bottom: 1rem;
}',
            'js' => 'document.addEventListener("DOMContentLoaded", function() {
    const pricingCards = document.querySelectorAll(".pricing-card");
    pricingCards.forEach(card => {
        card.addEventListener("mouseenter", function() {
            this.style.zIndex = "10";
        });
        card.addEventListener("mouseleave", function() {
            this.style.zIndex = "1";
        });
    });
});'
        ];
    }

    /**
     * Get CTA section
     */
    private function getCtaSection()
    {
        return [
            'html' => '<section class="cta-section py-5" style="background: {{ $config[\'background_gradient\'] ?? \'linear-gradient(135deg, #ff6b6b 0%, #ee5a52 50%, #ff4757 100%)\' }};">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h2 class="display-5 fw-bold text-white mb-3">
                    {{ $config[\'cta_title\'] ?? \'Ready to Get Started?\' }}
                </h2>
                <p class="lead text-white opacity-75 mb-lg-0">
                    {{ $config[\'cta_subtitle\'] ?? \'Join thousands of satisfied customers and transform your business today.\' }}
                </p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <div class="cta-buttons">
                    @if(!empty($config[\'primary_button\'][\'text\']))
                        <a href="{{ $config[\'primary_button\'][\'url\'] ?? \'#\' }}" 
                           class="btn btn-light btn-lg me-3 px-4 py-3 fw-bold">
                            {{ $config[\'primary_button\'][\'text\'] }}
                        </a>
                    @endif
                    @if(!empty($config[\'secondary_button\'][\'text\']))
                        <a href="{{ $config[\'secondary_button\'][\'url\'] ?? \'#\' }}" 
                           class="btn btn-outline-light btn-lg px-4 py-3 fw-bold">
                            {{ $config[\'secondary_button\'][\'text\'] }}
                        </a>
                    @endif
                </div>
            </div>
        </div>
        
        @if($config[\'show_features\'] ?? true)
            <div class="row mt-5 pt-5 border-top border-white-50">
                <div class="col-lg-12">
                    <div class="row text-center">
                        @foreach($config[\'cta_features\'] ?? [] as $feature)
                            <div class="col-md-4">
                                <div class="cta-feature text-white">
                                    <i class="{{ $feature[\'icon\'] ?? \'fas fa-check\' }} fs-3 mb-3 d-block"></i>
                                    <h5 class="fw-bold">{{ $feature[\'title\'] ?? \'Feature\' }}</h5>
                                    <p class="opacity-75">{{ $feature[\'description\'] ?? \'Feature description\' }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>
</section>',
            'css' => '.cta-section {
    position: relative;
    overflow: hidden;
}
.cta-section::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url("data:image/svg+xml,%3Csvg width="40" height="40" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="white" fill-opacity="0.05"%3E%3Ccircle cx="20" cy="20" r="2"/%3E%3C/g%3E%3C/svg%3E");
    z-index: 1;
}
.cta-section .container {
    position: relative;
    z-index: 2;
}
.cta-buttons .btn:hover {
    transform: translateY(-2px);
    transition: all 0.3s ease;
}
.cta-feature {
    animation: fadeInUp 0.6s ease;
}',
            'js' => 'document.addEventListener("DOMContentLoaded", function() {
    const ctaSection = document.querySelector(".cta-section");
    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add("animate__animated", "animate__fadeInUp");
            }
        });
    }, { threshold: 0.3 });
    
    if (ctaSection) {
        observer.observe(ctaSection);
    }
});'
        ];
    }

    /**
     * Get configurable fields for sections
     */
    private function getSectionConfigurableFields($sectionId)
    {
        $configs = [
            'hero-video-background' => [
                'video_url' => ['type' => 'url', 'default' => '', 'label' => 'Background Video URL'],
                'overlay_opacity' => ['type' => 'range', 'min' => 0, 'max' => 1, 'step' => 0.1, 'default' => 0.5, 'label' => 'Overlay Opacity'],
                'hero_title' => ['type' => 'text', 'default' => 'Welcome to Our Amazing Service', 'label' => 'Hero Title'],
                'hero_subtitle' => ['type' => 'textarea', 'default' => 'We provide innovative solutions to help your business grow.', 'label' => 'Hero Subtitle'],
                'text_alignment' => ['type' => 'select', 'options' => ['left', 'center', 'right'], 'default' => 'center', 'label' => 'Text Alignment'],
                'primary_button' => [
                    'type' => 'object',
                    'default' => ['text' => 'Get Started', 'url' => '/contact', 'style' => 'primary'],
                    'label' => 'Primary Button'
                ],
                'secondary_button' => [
                    'type' => 'object',
                    'default' => ['text' => 'Learn More', 'url' => '/about', 'style' => 'outline-light'],
                    'label' => 'Secondary Button'
                ],
                'show_scroll_indicator' => ['type' => 'boolean', 'default' => true, 'label' => 'Show Scroll Indicator']
            ],
            'features-grid-modern' => [
                'section_title' => ['type' => 'text', 'default' => 'Our Amazing Features', 'label' => 'Section Title'],
                'section_subtitle' => ['type' => 'text', 'default' => 'Discover what makes us different', 'label' => 'Section Subtitle'],
                'columns' => ['type' => 'select', 'options' => [2, 3, 4], 'default' => 3, 'label' => 'Number of Columns'],
                'background_color' => ['type' => 'color', 'default' => '#f8f9fa', 'label' => 'Background Color'],
                'icon_background' => ['type' => 'text', 'default' => 'linear-gradient(135deg, #667eea, #764ba2)', 'label' => 'Icon Background'],
                'features' => [
                    'type' => 'array',
                    'default' => [
                        ['icon' => 'fas fa-rocket', 'title' => 'Fast & Reliable', 'description' => 'Lightning fast performance with 99.9% uptime guarantee.'],
                        ['icon' => 'fas fa-shield-alt', 'title' => 'Secure', 'description' => 'Enterprise-level security to protect your data.'],
                        ['icon' => 'fas fa-headset', 'title' => '24/7 Support', 'description' => 'Round-the-clock customer support whenever you need help.']
                    ],
                    'label' => 'Features List'
                ]
            ]
        ];

        return $configs[$sectionId] ?? [];
    }

    /**
     * Get default config for sections
     */
    private function getSectionDefaultConfig($sectionId)
    {
        $configs = [
            'hero-video-background' => [
                'hero_title' => 'Welcome to Our Amazing Service',
                'text_alignment' => 'center',
                'overlay_opacity' => 0.5
            ],
            'features-grid-modern' => [
                'section_title' => 'Our Amazing Features',
                'columns' => 3,
                'background_color' => '#f8f9fa'
            ]
        ];

        return $configs[$sectionId] ?? [];
    }
}
