<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Site;
use App\Models\SiteConfig;
use App\Models\TplLayout;
use App\Models\TplPage;
use App\Models\TplPageSection;
use App\Models\TplSite;
use App\Models\ThemeCategory;
use App\Models\ThemePage;
use App\Models\TplLang;
use App\Services\ConfigurationService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DevelopmentSeeder extends Seeder
{
    /**
     * Run the database seeds for development environment
     */
    public function run(): void
    {
        echo "🚀 Setting up SPS Development Environment...\n";
        
        // Clear existing data
        $this->clearExistingData();
        
        // Step 1: Create languages
        echo "🌐 Creating languages...\n";
        $this->createLanguages();
        
        // Step 2: Create theme categories
        echo "🎨 Creating theme categories...\n";
        $this->createThemeCategories();
        
        // Step 3: Create 5 default templates for each type
        echo "📋 Creating default templates (5 headers, 5 footers, 5 sections)...\n";
        $this->createDefaultTemplates();
        
        // Step 4: Create admin user with site
        echo "👨‍💼 Creating admin user with development site...\n";
        $admin = $this->createAdminUser();
        $adminSite = $this->createDevelopmentSite($admin);
        $this->setupSiteConfiguration($adminSite);
        $this->createSitePages($adminSite);
        
        // Step 5: Create regular user for testing
        echo "👤 Creating test user...\n";
        $this->createTestUser();
        
        echo "✅ Development setup completed successfully!\n\n";
        echo "🌟 Development Environment Ready!\n";
        echo "📧 Admin Login: admin@localhost.dev / admin123\n";
        echo "🔗 Admin Panel: http://localhost:8000/admin\n";
        echo "📧 User Login: user@localhost.dev / user123\n";
        echo "🌐 Frontend: http://localhost:8000\n";
        echo "🔧 Headers & Footers Management: http://localhost:8000/admin/headers-footers\n\n";
    }

    /**
     * Clear existing data for fresh setup
     */
    private function clearExistingData(): void
    {
        echo "🧹 Clearing existing data...\n";
        
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        $tables = [
            'site_img_media',
            'tpl_page_sections', 
            'tpl_pages',
            'tpl_site',
            'site_config',
            'sites',
            'theme_pages',
            'theme_categories',
            'tpl_layouts',
            'tpl_langs',
            'users'
        ];
        
        foreach ($tables as $table) {
            DB::table($table)->truncate();
            echo "  ✓ Cleared {$table}\n";
        }
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    /**
     * Create default languages
     */
    private function createLanguages(): void
    {
        $languages = [
            ['code' => 'en', 'name' => 'English', 'dir' => 'ltr'],
            ['code' => 'ar', 'name' => 'العربية', 'dir' => 'rtl'],
            ['code' => 'es', 'name' => 'Español', 'dir' => 'ltr'],
            ['code' => 'fr', 'name' => 'Français', 'dir' => 'ltr'],
            ['code' => 'de', 'name' => 'Deutsch', 'dir' => 'ltr'],
        ];

        foreach ($languages as $lang) {
            TplLang::create($lang);
            echo "  ✓ {$lang['name']} ({$lang['code']})\n";
        }
    }

    /**
     * Create theme categories
     */
    private function createThemeCategories(): void
    {
        $categories = [
            ['name' => 'Business', 'description' => 'Professional business templates', 'icon' => 'fas fa-briefcase', 'sort_order' => 1],
            ['name' => 'Portfolio', 'description' => 'Creative portfolio showcases', 'icon' => 'fas fa-palette', 'sort_order' => 2],
            ['name' => 'Ecommerce', 'description' => 'Online store templates', 'icon' => 'fas fa-shopping-cart', 'sort_order' => 3],
            ['name' => 'Blog', 'description' => 'Content-focused blog layouts', 'icon' => 'fas fa-blog', 'sort_order' => 4],
            ['name' => 'Landing Page', 'description' => 'High-converting landing pages', 'icon' => 'fas fa-rocket', 'sort_order' => 5],
        ];

        foreach ($categories as $category) {
            ThemeCategory::create($category);
            echo "  ✓ {$category['name']}\n";
        }
    }

    /**
     * Create default templates (5 of each type)
     */
    private function createDefaultTemplates(): void
    {
        // Create 5 Header Templates
        $this->createHeaderTemplates();
        
        // Create 5 Footer Templates  
        $this->createFooterTemplates();
        
        // Create 5 Section Templates
        $this->createSectionTemplates();
    }

    /**
     * Create 5 header templates
     */
    private function createHeaderTemplates(): void
    {
        $headers = [
            [
                'tpl_id' => 'global-modern-header-1',
                'name' => 'Modern Business Header',
                'description' => 'Clean modern header with gradient background'
            ],
            [
                'tpl_id' => 'global-classic-header-2', 
                'name' => 'Classic Corporate Header',
                'description' => 'Traditional corporate header design'
            ],
            [
                'tpl_id' => 'global-creative-header-3',
                'name' => 'Creative Portfolio Header', 
                'description' => 'Creative header for portfolios and agencies'
            ],
            [
                'tpl_id' => 'global-minimal-header-4',
                'name' => 'Minimal Clean Header',
                'description' => 'Minimalist header with clean typography'
            ],
            [
                'tpl_id' => 'global-dynamic-header-5',
                'name' => 'Dynamic Interactive Header',
                'description' => 'Header with interactive elements and animations'
            ]
        ];

        foreach ($headers as $index => $header) {
            TplLayout::create([
                'tpl_id' => $header['tpl_id'],
                'layout_type' => 'header',
                'name' => $header['name'],
                'description' => $header['description'],
                'preview_image' => "/img/templates/headers/{$header['tpl_id']}.jpg",
                'path' => "frontend.templates.headers.{$header['tpl_id']}",
                'content' => $this->getHeaderContent($index + 1),
                'configurable_fields' => $this->getHeaderConfigFields(),
                'default_config' => $this->getHeaderDefaultConfig(),
                'status' => true,
                'sort_order' => $index + 1
            ]);
            echo "  ✓ {$header['name']}\n";
        }
    }

    /**
     * Create 5 footer templates
     */
    private function createFooterTemplates(): void
    {
        $footers = [
            [
                'tpl_id' => 'global-comprehensive-footer-1',
                'name' => 'Comprehensive Business Footer',
                'description' => 'Complete footer with all business information'
            ],
            [
                'tpl_id' => 'global-simple-footer-2',
                'name' => 'Simple Clean Footer',
                'description' => 'Minimal footer with essential links only'
            ],
            [
                'tpl_id' => 'global-newsletter-footer-3',
                'name' => 'Newsletter Focused Footer',
                'description' => 'Footer with prominent newsletter signup'
            ],
            [
                'tpl_id' => 'global-social-footer-4',
                'name' => 'Social Media Footer',
                'description' => 'Footer emphasizing social media presence'
            ],
            [
                'tpl_id' => 'global-corporate-footer-5',
                'name' => 'Corporate Legal Footer',
                'description' => 'Professional footer with legal compliance'
            ]
        ];

        foreach ($footers as $index => $footer) {
            TplLayout::create([
                'tpl_id' => $footer['tpl_id'],
                'layout_type' => 'footer',
                'name' => $footer['name'],
                'description' => $footer['description'],
                'preview_image' => "/img/templates/footers/{$footer['tpl_id']}.jpg",
                'path' => "frontend.templates.footers.{$footer['tpl_id']}",
                'content' => $this->getFooterContent($index + 1),
                'configurable_fields' => $this->getFooterConfigFields(),
                'default_config' => $this->getFooterDefaultConfig(),
                'status' => true,
                'sort_order' => $index + 1
            ]);
            echo "  ✓ {$footer['name']}\n";
        }
    }

    /**
     * Create 5 section templates
     */
    private function createSectionTemplates(): void
    {
        $sections = [
            [
                'tpl_id' => 'global-hero-video-1',
                'name' => 'Hero Section with Video Background',
                'description' => 'Full-screen hero with video background'
            ],
            [
                'tpl_id' => 'global-services-grid-2',
                'name' => 'Services Grid Layout',
                'description' => 'Grid layout for showcasing services'
            ],
            [
                'tpl_id' => 'global-testimonials-3',
                'name' => 'Customer Testimonials',
                'description' => 'Customer testimonials carousel'
            ],
            [
                'tpl_id' => 'global-about-team-4',
                'name' => 'About Us with Team',
                'description' => 'About section with team member showcase'
            ],
            [
                'tpl_id' => 'global-contact-form-5',
                'name' => 'Contact Form Section',
                'description' => 'Contact form with business information'
            ]
        ];

        foreach ($sections as $index => $section) {
            TplLayout::create([
                'tpl_id' => $section['tpl_id'],
                'layout_type' => 'section',
                'name' => $section['name'],
                'description' => $section['description'],
                'preview_image' => "/img/templates/sections/{$section['tpl_id']}.jpg",
                'path' => "frontend.templates.sections.{$section['tpl_id']}",
                'content' => $this->getSectionContent($index + 1),
                'configurable_fields' => $this->getSectionConfigFields($index + 1),
                'default_config' => $this->getSectionDefaultConfig($index + 1),
                'status' => true,
                'sort_order' => $index + 1
            ]);
            echo "  ✓ {$section['name']}\n";
        }
    }

    /**
     * Create admin user
     */
    private function createAdminUser(): User
    {
        return User::create([
            'name' => 'Development Admin',
            'email' => 'admin@localhost.dev',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'status_id' => true,
            'preferred_language' => 'en'
        ]);
    }

    /**
     * Create development site
     */
    private function createDevelopmentSite(User $admin): Site
    {
        $headerTemplate = TplLayout::where('layout_type', 'header')->first();
        $footerTemplate = TplLayout::where('layout_type', 'footer')->first();
        
        return Site::create([
            'user_id' => $admin->id,
            'site_name' => 'Development Site',
            'url' => 'http://localhost:8000',
            'status_id' => true,
            'active_header_id' => $headerTemplate->id,
            'active_footer_id' => $footerTemplate->id
        ]);
    }

    /**
     * Setup site configuration 
     */
    private function setupSiteConfiguration(Site $site): void
    {
        // Create site configuration
        SiteConfig::create([
            'site_id' => $site->id,
            'settings' => [
                'timezone' => 'UTC',
                'meta' => [
                    'description' => 'Development site for testing SPS system',
                    'keywords' => 'development, sps, testing'
                ]
            ],
            'data' => [
                'logo' => '/img/logo.png',
                'favicon' => '/img/favicon.ico'
            ],
            'language_code' => [
                'languages' => ['en', 'ar'],
                'primary' => 'en'
            ],
            'tpl_name' => 'business',
            'tpl_colors' => [
                'primary' => '#667eea',
                'secondary' => '#764ba2',
                'success' => '#28a745',
                'danger' => '#dc3545'
            ]
        ]);

        // Create TplSite with navigation and social data
        TplSite::create([
            'site_id' => $site->id,
            'nav' => $site->active_header_id,
            'footer' => $site->active_footer_id,
            'nav_data' => [
                'links' => [
                    ['name' => 'Home', 'url' => '/', 'active' => true, 'external' => false],
                    ['name' => 'About', 'url' => '/about', 'active' => true, 'external' => false],
                    ['name' => 'Services', 'url' => '/services', 'active' => true, 'external' => false],
                    ['name' => 'Portfolio', 'url' => '/portfolio', 'active' => true, 'external' => false],
                    ['name' => 'Contact', 'url' => '/contact', 'active' => true, 'external' => false]
                ],
                'show_auth' => true
            ],
            'footer_data' => [
                'links' => [
                    ['name' => 'Privacy Policy', 'url' => '/privacy', 'active' => true, 'external' => false],
                    ['name' => 'Terms of Service', 'url' => '/terms', 'active' => true, 'external' => false],
                    ['name' => 'Support', 'url' => '/support', 'active' => true, 'external' => false]
                ],
                'social_media' => [
                    'facebook' => 'https://facebook.com/yourcompany',
                    'twitter' => 'https://twitter.com/yourcompany', 
                    'linkedin' => 'https://linkedin.com/company/yourcompany',
                    'instagram' => 'https://instagram.com/yourcompany',
                    'youtube' => 'https://youtube.com/yourcompany'
                ],
                'newsletter' => [
                    'enabled' => true,
                    'title' => 'Stay Updated',
                    'description' => 'Subscribe to our newsletter for updates'
                ],
                'show_auth' => true
            ]
        ]);

        echo "  ✓ Site configuration created\n";
    }

    /**
     * Create site pages
     */
    private function createSitePages(Site $site): void
    {
        $pages = [
            [
                'name' => 'Home',
                'slug' => 'home', 
                'link' => '/',
                'sections' => ['global-hero-video-1', 'global-services-grid-2', 'global-testimonials-3']
            ],
            [
                'name' => 'About',
                'slug' => 'about',
                'link' => '/about', 
                'sections' => ['global-about-team-4']
            ],
            [
                'name' => 'Services',
                'slug' => 'services',
                'link' => '/services',
                'sections' => ['global-services-grid-2']
            ],
            [
                'name' => 'Portfolio',
                'slug' => 'portfolio',
                'link' => '/portfolio',
                'sections' => ['global-services-grid-2']
            ],
            [
                'name' => 'Contact',
                'slug' => 'contact',
                'link' => '/contact',
                'sections' => ['global-contact-form-5']
            ]
        ];

        foreach ($pages as $pageData) {
            $page = TplPage::create([
                'site_id' => $site->id,
                'name' => $pageData['name'],
                'slug' => $pageData['slug'],
                'link' => $pageData['link'],
                'data' => [
                    'en' => [
                        'title' => $pageData['name'],
                        'description' => $pageData['name'] . ' page for ' . $site->site_name
                    ]
                ],
                'show_in_nav' => true,
                'status' => true
            ]);

            // Add sections to page
            $sortOrder = 1;
            foreach ($pageData['sections'] as $templateId) {
                $template = TplLayout::where('tpl_id', $templateId)->first();
                if ($template) {
                    TplPageSection::create([
                        'page_id' => $page->id,
                        'tpl_layouts_id' => $template->id,
                        'site_id' => $site->id,
                        'name' => $template->name,
                        'content' => json_encode($template->default_config ?: []),
                        'status' => 1,
                        'sort_order' => $sortOrder++
                    ]);
                }
            }
            
            echo "  ✓ Created page: {$page->name}\n";
        }
    }

    /**
     * Create test user
     */
    private function createTestUser(): User
    {
        return User::create([
            'name' => 'Test User',
            'email' => 'user@localhost.dev',
            'password' => Hash::make('user123'),
            'role' => 'user',
            'status_id' => true,
            'preferred_language' => 'en'
        ]);
    }

    // Template content methods (simplified for demo)
    private function getHeaderContent($variant): array
    {
        return [
            'html' => $this->getHeaderHTML($variant),
            'css' => $this->getHeaderCSS($variant),
            'js' => $this->getHeaderJS($variant)
        ];
    }

    private function getFooterContent($variant): array
    {
        return [
            'html' => $this->getFooterHTML($variant),
            'css' => $this->getFooterCSS($variant),
            'js' => $this->getFooterJS($variant)
        ];
    }

    private function getSectionContent($variant): array
    {
        return [
            'html' => $this->getSectionHTML($variant),
            'css' => $this->getSectionCSS($variant),
            'js' => $this->getSectionJS($variant)
        ];
    }

    private function getHeaderHTML($variant): string
    {
        return '<nav class="navbar navbar-expand-lg navbar-modern navbar-variant-' . $variant . '" id="mainNavbar">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center fw-bold" href="/">
            @if(!empty($config[\'logo_url\']))
                <img src="{{ $config[\'logo_url\'] }}" alt="{{ $config[\'site_name\'] ?? \'Logo\' }}" height="40" class="me-2">
            @endif
            <span class="brand-text">{{ $config[\'site_name\'] ?? \'Your Site\' }}</span>
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                @foreach($config[\'menu_items\'] ?? [] as $item)
                    <li class="nav-item">
                        <a class="nav-link" href="{{ $item[\'url\'] }}" 
                           @if($item[\'external\'] ?? false) target="_blank" @endif>
                            {{ $item[\'label\'] ?? $item[\'name\'] }}
                        </a>
                    </li>
                @endforeach
            </ul>
            
            <div class="navbar-actions d-flex align-items-center gap-3">
                <!-- Auth Section - Always visible -->
                @guest
                    <a href="/login" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-sign-in-alt me-1"></i>Login
                    </a>
                    <a href="/register" class="btn btn-primary btn-sm">
                        <i class="fas fa-user-plus me-1"></i>Register
                    </a>
                @else
                    <div class="dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle me-1"></i>{{ auth()->user()->name }}
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="/profile"><i class="fas fa-user me-2"></i>Profile</a></li>
                            <li><a class="dropdown-item" href="/dashboard"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</a></li>
                            @if(auth()->user()->hasAnyRole([\'admin\', \'super-admin\']))
                                <li><a class="dropdown-item" href="/admin"><i class="fas fa-cog me-2"></i>Admin Panel</a></li>
                            @endif
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="/logout" class="d-inline w-100">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @endguest
                
                @if(!empty($config[\'cta_button\'][\'text\']))
                    <a href="{{ $config[\'cta_button\'][\'url\'] ?? \'#\' }}" 
                       class="btn btn-gradient btn-sm rounded-pill px-3">
                        {{ $config[\'cta_button\'][\'text\'] }}
                    </a>
                @endif
            </div>
        </div>
    </div>
</nav>';
    }

    private function getHeaderCSS($variant): string
    {
        $variants = [
            1 => 'background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);',
            2 => 'background: #ffffff; border-bottom: 2px solid #e9ecef;',
            3 => 'background: rgba(0,0,0,0.9); backdrop-filter: blur(10px);',
            4 => 'background: #f8f9fa; border-bottom: 1px solid #dee2e6;',
            5 => 'background: linear-gradient(45deg, #ff6b6b, #4ecdc4, #45b7d1);'
        ];

        return '.navbar-variant-' . $variant . ' {
    ' . ($variants[$variant] ?? $variants[1]) . '
    transition: all 0.3s ease;
    padding: 1rem 0;
}

.navbar-modern {
    z-index: 1050;
}

.brand-text {
    font-size: 1.5rem;
    font-weight: 700;
    color: ' . ($variant == 2 || $variant == 4 ? '#333' : '#fff') . ';
}

.nav-link {
    color: ' . ($variant == 2 || $variant == 4 ? '#333' : '#fff') . ' !important;
    font-weight: 500;
    padding: 0.75rem 1rem !important;
    border-radius: 25px;
    transition: all 0.3s ease;
}

.nav-link:hover {
    background: rgba(255,255,255,' . ($variant == 2 || $variant == 4 ? '0.1' : '0.2') . ');
    transform: translateY(-2px);
}

.btn-gradient {
    background: linear-gradient(135deg, #667eea, #764ba2);
    border: none;
    color: white;
    transition: all 0.3s ease;
}

.btn-gradient:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
    color: white;
}

.dropdown-menu {
    border: none;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    border-radius: 10px;
}

@media (max-width: 991px) {
    .navbar-nav {
        text-align: center;
        margin: 1rem 0;
    }
    
    .navbar-actions {
        justify-content: center;
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px solid rgba(255,255,255,0.2);
    }
}';
    }

    private function getHeaderJS($variant): string
    {
        return 'document.addEventListener("DOMContentLoaded", function() {
    const navbar = document.getElementById("mainNavbar");
    if (navbar) {
        window.addEventListener("scroll", function() {
            if (window.scrollY > 50) {
                navbar.classList.add("scrolled");
            } else {
                navbar.classList.remove("scrolled");
            }
        });
    }
    
    // Auto-close mobile menu on link click
    document.querySelectorAll(".navbar-nav .nav-link").forEach(link => {
        link.addEventListener("click", function() {
            const navbarCollapse = document.getElementById("navbarNav");
            if (navbarCollapse && window.bootstrap) {
                const bsCollapse = new bootstrap.Collapse(navbarCollapse, {toggle: false});
                bsCollapse.hide();
            }
        });
    });
});';
    }

    private function getFooterHTML($variant): string
    {
        return '<footer class="footer-modern footer-variant-' . $variant . '">
    <div class="container py-5">
        <div class="row g-4">
            <!-- Company Info -->
            <div class="col-lg-4 col-md-6">
                <div class="footer-brand mb-3">
                    @if(!empty($config[\'logo_url\']))
                        <img src="{{ $config[\'logo_url\'] }}" alt="{{ $config[\'company_name\'] ?? \'Company\' }}" height="40" class="mb-3">
                    @endif
                    <h5 class="fw-bold mb-3">{{ $config[\'company_name\'] ?? \'Your Company\' }}</h5>
                </div>
                <p class="mb-3">{{ $config[\'description\'] ?? \'Company description goes here\' }}</p>
                
                @if(!empty($config[\'contact_info\']))
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
                </div>
                @endif
            </div>
            
            <!-- Quick Links -->
            <div class="col-lg-2 col-md-6">
                <h6 class="fw-bold mb-3">Quick Links</h6>
                <ul class="list-unstyled">
                    @foreach($config[\'footer_links\'] ?? [] as $link)
                        <li class="mb-2">
                            <a href="{{ $link[\'url\'] }}" class="footer-link" 
                               @if($link[\'external\'] ?? false) target="_blank" @endif>
                                {{ $link[\'label\'] ?? $link[\'name\'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
            
            <!-- Services -->
            <div class="col-lg-2 col-md-6">
                <h6 class="fw-bold mb-3">Services</h6>
                <ul class="list-unstyled">
                    @foreach($config[\'services_links\'] ?? [] as $service)
                        <li class="mb-2">
                            <a href="{{ $service[\'url\'] }}" class="footer-link">{{ $service[\'label\'] }}</a>
                        </li>
                    @endforeach
                </ul>
            </div>
            
            <!-- Newsletter & Social -->
            <div class="col-lg-4 col-md-6">
                @if($config[\'show_newsletter\'] ?? true)
                    <h6 class="fw-bold mb-3">{{ $config[\'newsletter_title\'] ?? \'Newsletter\' }}</h6>
                    <p class="mb-3">{{ $config[\'newsletter_description\'] ?? \'Stay updated with our latest news\' }}</p>
                    <form class="newsletter-form mb-4">
                        <div class="input-group">
                            <input type="email" class="form-control" placeholder="{{ $config[\'newsletter_placeholder\'] ?? \'Enter email\' }}" required>
                            <button class="btn btn-primary" type="submit">Subscribe</button>
                        </div>
                    </form>
                @endif
                
                <!-- Social Links - Dynamic from admin -->
                @if(!empty($config[\'social_links\']))
                    <div class="social-links">
                        <h6 class="fw-bold mb-3">Follow Us</h6>
                        <div class="d-flex gap-3">
                            @foreach($config[\'social_links\'] as $social)
                                @if(!empty($social[\'url\']) && $social[\'url\'] !== \'#\')
                                    <a href="{{ $social[\'url\'] }}" target="_blank" class="social-icon">
                                        <i class="{{ $social[\'icon\'] ?? \'fas fa-link\' }}"></i>
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Footer Bottom -->
    <div class="footer-bottom py-3 border-top">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start">
                    <p class="mb-0">{{ $config[\'copyright_text\'] ?? \'© 2025 Your Company. All rights reserved.\' }}</p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <div class="footer-links">
                        <a href="/privacy" class="footer-link me-3">Privacy Policy</a>
                        <a href="/terms" class="footer-link">Terms of Service</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>';
    }

    private function getFooterCSS($variant): string
    {
        $variants = [
            1 => 'background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); color: white;',
            2 => 'background: #f8f9fa; color: #333; border-top: 1px solid #dee2e6;',
            3 => 'background: #343a40; color: white;',
            4 => 'background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;',
            5 => 'background: #000; color: white;'
        ];

        return '.footer-variant-' . $variant . ' {
    ' . ($variants[$variant] ?? $variants[1]) . '
}

.footer-modern {
    margin-top: auto;
}

.footer-link {
    color: inherit;
    text-decoration: none;
    opacity: 0.8;
    transition: all 0.3s ease;
}

.footer-link:hover {
    opacity: 1;
    color: inherit;
    transform: translateX(5px);
}

.social-icon {
    width: 40px;
    height: 40px;
    background: rgba(255,255,255,0.1);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: inherit;
    text-decoration: none;
    transition: all 0.3s ease;
}

.social-icon:hover {
    background: rgba(255,255,255,0.2);
    transform: translateY(-3px);
    color: inherit;
}

.newsletter-form .form-control {
    border-radius: 25px 0 0 25px;
}

.newsletter-form .btn {
    border-radius: 0 25px 25px 0;
}

.footer-bottom {
    background: rgba(0,0,0,0.1);
}

@media (max-width: 768px) {
    .footer-modern .col-lg-4,
    .footer-modern .col-lg-2 {
        text-align: center;
        margin-bottom: 2rem;
    }
}';
    }

    private function getFooterJS($variant): string
    {
        return 'document.addEventListener("DOMContentLoaded", function() {
    const newsletterForm = document.querySelector(".newsletter-form");
    if (newsletterForm) {
        newsletterForm.addEventListener("submit", function(e) {
            e.preventDefault();
            const email = this.querySelector("input[type=email]").value;
            if (email) {
                alert("Thank you for subscribing!");
                this.reset();
            }
        });
    }
});';
    }

    private function getSectionHTML($variant): string
    {
        $sections = [
            1 => 'hero-video',
            2 => 'services-grid', 
            3 => 'testimonials',
            4 => 'about-team',
            5 => 'contact-form'
        ];

        $type = $sections[$variant] ?? 'hero-video';
        
        return '<section class="section-' . $type . ' py-5" data-variant="' . $variant . '">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="display-5 fw-bold mb-3">{{ $config[\'title\'] ?? \'Section Title\' }}</h2>
                <p class="lead">{{ $config[\'subtitle\'] ?? \'Section subtitle goes here\' }}</p>
            </div>
        </div>
        
        @if($variant == 1)
            <!-- Hero Video Content -->
            <div class="hero-content text-center">
                <h1 class="display-4 fw-bold mb-4">{{ $config[\'hero_title\'] ?? \'Welcome to Our Site\' }}</h1>
                <p class="lead mb-4">{{ $config[\'hero_description\'] ?? \'Your success story starts here\' }}</p>
                <div class="hero-buttons">
                    <a href="{{ $config[\'cta_url\'] ?? \'#\' }}" class="btn btn-primary btn-lg me-3">
                        {{ $config[\'cta_text\'] ?? \'Get Started\' }}
                    </a>
                    <a href="{{ $config[\'secondary_url\'] ?? \'#\' }}" class="btn btn-outline-primary btn-lg">
                        {{ $config[\'secondary_text\'] ?? \'Learn More\' }}
                    </a>
                </div>
            </div>
        @endif
        
        @if($variant == 2)
            <!-- Services Grid -->
            <div class="row g-4">
                @foreach($config[\'services\'] ?? [] as $service)
                    <div class="col-lg-4 col-md-6">
                        <div class="service-card h-100 p-4 text-center">
                            <div class="service-icon mb-3">
                                <i class="{{ $service[\'icon\'] ?? \'fas fa-star\' }} fs-1"></i>
                            </div>
                            <h4>{{ $service[\'title\'] ?? \'Service Title\' }}</h4>
                            <p>{{ $service[\'description\'] ?? \'Service description\' }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
        
        @if($variant == 5)
            <!-- Contact Form -->
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <form class="contact-form">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <input type="text" class="form-control" placeholder="Your Name" required>
                            </div>
                            <div class="col-md-6">
                                <input type="email" class="form-control" placeholder="Your Email" required>
                            </div>
                            <div class="col-12">
                                <input type="text" class="form-control" placeholder="Subject" required>
                            </div>
                            <div class="col-12">
                                <textarea class="form-control" rows="5" placeholder="Your Message" required></textarea>
                            </div>
                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-primary btn-lg">Send Message</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    </div>
</section>';
    }

    private function getSectionCSS($variant): string
    {
        return '.section-variant-' . $variant . ' {
    background: #f8f9fa;
}

.service-card {
    background: white;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

.service-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 30px rgba(0,0,0,0.15);
}

.service-icon {
    color: #667eea;
}

.contact-form {
    background: white;
    padding: 3rem;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

.hero-content {
    padding: 5rem 0;
}';
    }

    private function getSectionJS($variant): string
    {
        return 'document.addEventListener("DOMContentLoaded", function() {
    const section = document.querySelector(".section-variant-' . $variant . '");
    if (section) {
        // Add animation on scroll
        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add("animate");
                }
            });
        }, {threshold: 0.1});
        
        observer.observe(section);
    }
    
    // Contact form submission
    const contactForm = document.querySelector(".contact-form");
    if (contactForm) {
        contactForm.addEventListener("submit", function(e) {
            e.preventDefault();
            alert("Message sent successfully!");
            this.reset();
        });
    }
});';
    }

    // Configuration methods
    private function getHeaderConfigFields(): array
    {
        return [
            'site_name' => ['type' => 'text', 'default' => 'Your Site', 'label' => 'Site Name'],
            'logo_url' => ['type' => 'url', 'default' => '', 'label' => 'Logo URL'],
            'menu_items' => [
                'type' => 'array',
                'default' => [
                    ['label' => 'Home', 'url' => '/', 'external' => false],
                    ['label' => 'About', 'url' => '/about', 'external' => false],
                    ['label' => 'Services', 'url' => '/services', 'external' => false],
                    ['label' => 'Contact', 'url' => '/contact', 'external' => false]
                ],
                'label' => 'Menu Items'
            ],
            'cta_button' => [
                'type' => 'object',
                'default' => ['text' => 'Get Started', 'url' => '/contact'],
                'label' => 'CTA Button'
            ]
        ];
    }

    private function getHeaderDefaultConfig(): array
    {
        return [
            'site_name' => 'Development Site',
            'logo_url' => '/img/logo.png',
            'menu_items' => [
                ['label' => 'Home', 'url' => '/', 'external' => false],
                ['label' => 'About', 'url' => '/about', 'external' => false],
                ['label' => 'Services', 'url' => '/services', 'external' => false],
                ['label' => 'Contact', 'url' => '/contact', 'external' => false]
            ],
            'cta_button' => ['text' => 'Get Started', 'url' => '/contact']
        ];
    }

    private function getFooterConfigFields(): array
    {
        return [
            'company_name' => ['type' => 'text', 'default' => 'Your Company', 'label' => 'Company Name'],
            'logo_url' => ['type' => 'url', 'default' => '', 'label' => 'Logo URL'],
            'description' => ['type' => 'textarea', 'default' => 'Company description', 'label' => 'Description'],
            'contact_info' => [
                'type' => 'object',
                'default' => [
                    'email' => 'contact@yourcompany.com',
                    'phone' => '+1 (555) 123-4567',
                    'address' => '123 Business St, City, State 12345'
                ],
                'label' => 'Contact Information'
            ],
            'footer_links' => [
                'type' => 'array',
                'default' => [
                    ['label' => 'About', 'url' => '/about'],
                    ['label' => 'Services', 'url' => '/services'],
                    ['label' => 'Contact', 'url' => '/contact']
                ],
                'label' => 'Footer Links'
            ],
            'social_links' => [
                'type' => 'array',
                'default' => [
                    ['icon' => 'fab fa-facebook', 'url' => ''],
                    ['icon' => 'fab fa-twitter', 'url' => ''],
                    ['icon' => 'fab fa-linkedin', 'url' => ''],
                    ['icon' => 'fab fa-instagram', 'url' => '']
                ],
                'label' => 'Social Media Links'
            ],
            'show_newsletter' => ['type' => 'boolean', 'default' => true, 'label' => 'Show Newsletter'],
            'newsletter_title' => ['type' => 'text', 'default' => 'Newsletter', 'label' => 'Newsletter Title'],
            'newsletter_description' => ['type' => 'text', 'default' => 'Stay updated', 'label' => 'Newsletter Description'],
            'copyright_text' => ['type' => 'text', 'default' => '© 2025 Your Company. All rights reserved.', 'label' => 'Copyright Text']
        ];
    }

    private function getFooterDefaultConfig(): array
    {
        return [
            'company_name' => 'Development Company',
            'description' => 'We provide amazing services to help your business grow.',
            'contact_info' => [
                'email' => 'contact@localhost.dev',
                'phone' => '+1 (555) 123-4567',
                'address' => '123 Development St, Local City, LC 12345'
            ],
            'show_newsletter' => true,
            'newsletter_title' => 'Stay Updated',
            'newsletter_description' => 'Get the latest updates from us',
            'copyright_text' => '© 2025 Development Company. All rights reserved.'
        ];
    }

    private function getSectionConfigFields($variant): array
    {
        $configs = [
            1 => [ // Hero
                'hero_title' => ['type' => 'text', 'default' => 'Welcome to Our Site', 'label' => 'Hero Title'],
                'hero_description' => ['type' => 'textarea', 'default' => 'Your success story starts here', 'label' => 'Hero Description'],
                'cta_text' => ['type' => 'text', 'default' => 'Get Started', 'label' => 'CTA Button Text'],
                'cta_url' => ['type' => 'url', 'default' => '/contact', 'label' => 'CTA Button URL']
            ],
            2 => [ // Services
                'title' => ['type' => 'text', 'default' => 'Our Services', 'label' => 'Section Title'],
                'subtitle' => ['type' => 'textarea', 'default' => 'What we offer', 'label' => 'Section Subtitle'],
                'services' => [
                    'type' => 'array',
                    'default' => [
                        ['title' => 'Service 1', 'description' => 'Description 1', 'icon' => 'fas fa-star'],
                        ['title' => 'Service 2', 'description' => 'Description 2', 'icon' => 'fas fa-heart'],
                        ['title' => 'Service 3', 'description' => 'Description 3', 'icon' => 'fas fa-cog']
                    ],
                    'label' => 'Services List'
                ]
            ]
        ];

        return $configs[$variant] ?? [
            'title' => ['type' => 'text', 'default' => 'Section Title', 'label' => 'Title'],
            'subtitle' => ['type' => 'textarea', 'default' => 'Section subtitle', 'label' => 'Subtitle']
        ];
    }

    private function getSectionDefaultConfig($variant): array
    {
        $configs = [
            1 => [
                'hero_title' => 'Welcome to Our Development Site',
                'hero_description' => 'Building amazing digital experiences',
                'cta_text' => 'Get Started',
                'cta_url' => '/contact'
            ],
            2 => [
                'title' => 'Our Services',
                'subtitle' => 'What we can do for you',
                'services' => [
                    ['title' => 'Web Development', 'description' => 'Modern web applications', 'icon' => 'fas fa-code'],
                    ['title' => 'Design', 'description' => 'Beautiful user interfaces', 'icon' => 'fas fa-palette'],
                    ['title' => 'SEO', 'description' => 'Search engine optimization', 'icon' => 'fas fa-search']
                ]
            ]
        ];

        return $configs[$variant] ?? [
            'title' => 'Section Title',
            'subtitle' => 'Section description'
        ];
    }
}
