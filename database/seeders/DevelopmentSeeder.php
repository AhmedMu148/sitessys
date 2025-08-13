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
                'name' => 'Elevated Business Header',
                'description' => 'Professional header with floating navigation and dynamic shadows'
            ],
            [
                'tpl_id' => 'global-classic-header-2', 
                'name' => 'Corporate Elite Header',
                'description' => 'Sophisticated corporate header with premium styling'
            ],
            [
                'tpl_id' => 'global-creative-header-3',
                'name' => 'Creative Studio Header', 
                'description' => 'Artistic header with bold typography and creative layouts'
            ],
            [
                'tpl_id' => 'global-minimal-header-4',
                'name' => 'Ultra-Clean Header',
                'description' => 'Minimalist approach with perfect spacing and typography'
            ],
            [
                'tpl_id' => 'global-dynamic-header-5',
                'name' => 'Interactive Pro Header',
                'description' => 'Advanced header with micro-interactions and modern effects'
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
        $htmlVariants = [
            1 => '<!-- Elevated Business Header -->
<nav class="navbar navbar-expand-lg navbar-elevated navbar-variant-' . $variant . '" id="mainNavbar">
    <div class="container-fluid px-4">
        <div class="navbar-brand-container">
            <a class="navbar-brand elevated-brand" href="/">
                @if(!empty($config[\'logo_url\']))
                    <div class="logo-wrapper">
                        <img src="{{ $config[\'logo_url\'] }}" alt="{{ $config[\'site_name\'] ?? \'Logo\' }}" class="brand-logo">
                    </div>
                @endif
                <span class="brand-title">{{ $config[\'site_name\'] ?? \'Your Business\' }}</span>
            </a>
        </div>
        
        <button class="navbar-toggler custom-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span></span><span></span><span></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav navigation-menu mx-auto">
                @foreach($config[\'menu_items\'] ?? [] as $item)
                    <li class="nav-item dropdown-hover">
                        <a class="nav-link nav-button" href="{{ $item[\'url\'] }}" 
                           @if($item[\'external\'] ?? false) target="_blank" @endif>
                            <span class="link-text">{{ $item[\'label\'] ?? $item[\'name\'] }}</span>
                            <span class="link-underline"></span>
                        </a>
                    </li>
                @endforeach
            </ul>
            
            <div class="navbar-actions action-panel">
                @guest
                    <a href="/login" class="btn btn-outline-light btn-elevated">
                        <i class="fas fa-sign-in-alt"></i>
                        <span>Sign In</span>
                    </a>
                    <a href="/register" class="btn btn-primary btn-elevated">
                        <i class="fas fa-rocket"></i>
                        <span>Get Started</span>
                    </a>
                @else
                    <div class="user-dropdown">
                        <a class="user-profile-link" href="#" data-bs-toggle="dropdown">
                            <div class="user-avatar">
                                <i class="fas fa-user-circle"></i>
                            </div>
                            <span class="user-name">{{ auth()->user()->name }}</span>
                            <i class="fas fa-chevron-down dropdown-arrow"></i>
                        </a>
                        <ul class="dropdown-menu user-menu">
                            <li><a class="dropdown-item" href="/profile"><i class="fas fa-user"></i>My Profile</a></li>
                            <li><a class="dropdown-item" href="/dashboard"><i class="fas fa-chart-bar"></i>Dashboard</a></li>
                            @if(auth()->user()->hasAnyRole([\'admin\', \'super-admin\']))
                                <li><a class="dropdown-item" href="/admin"><i class="fas fa-shield-alt"></i>Admin Center</a></li>
                            @endif
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="/logout">
                                    @csrf
                                    <button type="submit" class="dropdown-item logout-btn">
                                        <i class="fas fa-power-off"></i>Sign Out
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @endguest
                
                @if(!empty($config[\'cta_button\'][\'text\']))
                    <a href="{{ $config[\'cta_button\'][\'url\'] ?? \'#\' }}" class="btn btn-cta-elevated">
                        <span>{{ $config[\'cta_button\'][\'text\'] }}</span>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                @endif
            </div>
        </div>
    </div>
</nav>',

            2 => '<!-- Corporate Elite Header -->
<header class="corporate-header header-variant-' . $variant . '" id="mainNavbar">
    <div class="header-top-bar">
        <div class="container">
            <div class="top-bar-content">
                <div class="contact-info">
                    <span><i class="fas fa-envelope"></i> info@company.com</span>
                    <span><i class="fas fa-phone"></i> +1 (555) 123-4567</span>
                </div>
                <div class="header-social">
                    <a href="#"><i class="fab fa-linkedin-in"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                </div>
            </div>
        </div>
    </div>
    
    <nav class="navbar navbar-expand-lg main-navigation">
        <div class="container">
            <a class="navbar-brand corporate-brand" href="/">
                @if(!empty($config[\'logo_url\']))
                    <img src="{{ $config[\'logo_url\'] }}" alt="{{ $config[\'site_name\'] ?? \'Logo\' }}" class="corporate-logo">
                @endif
                <div class="brand-info">
                    <h1 class="company-name">{{ $config[\'site_name\'] ?? \'Corporation\' }}</h1>
                    <p class="company-tagline">Excellence in Business</p>
                </div>
            </a>
            
            <button class="navbar-toggler corporate-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="toggler-line"></span>
                <span class="toggler-line"></span>
                <span class="toggler-line"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav corporate-nav ms-auto">
                    @foreach($config[\'menu_items\'] ?? [] as $item)
                        <li class="nav-item">
                            <a class="nav-link corporate-link" href="{{ $item[\'url\'] }}" 
                               @if($item[\'external\'] ?? false) target="_blank" @endif>
                                {{ $item[\'label\'] ?? $item[\'name\'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
                
                <div class="corporate-actions">
                    @guest
                        <a href="/login" class="corporate-btn btn-secondary">
                            <i class="fas fa-user"></i> Client Portal
                        </a>
                        <a href="/register" class="corporate-btn btn-primary">
                            <i class="fas fa-briefcase"></i> Partnership
                        </a>
                    @else
                        <div class="corporate-user-menu">
                            <a class="corporate-user-btn" href="#" data-bs-toggle="dropdown">
                                <span class="user-initial">{{ substr(auth()->user()->name, 0, 1) }}</span>
                                <span>{{ auth()->user()->name }}</span>
                            </a>
                            <ul class="dropdown-menu corporate-dropdown">
                                <li><a class="dropdown-item" href="/profile">Account Settings</a></li>
                                <li><a class="dropdown-item" href="/dashboard">Control Panel</a></li>
                                @if(auth()->user()->hasAnyRole([\'admin\', \'super-admin\']))
                                    <li><a class="dropdown-item" href="/admin">Management Suite</a></li>
                                @endif
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="/logout">
                                        @csrf
                                        <button type="submit" class="dropdown-item">Sign Out</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @endguest
                    
                    @if(!empty($config[\'cta_button\'][\'text\']))
                        <a href="{{ $config[\'cta_button\'][\'url\'] ?? \'#\' }}" class="corporate-cta">
                            {{ $config[\'cta_button\'][\'text\'] }}
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </nav>
</header>',

            3 => '<!-- Creative Studio Header -->
<div class="creative-header header-variant-' . $variant . '" id="mainNavbar">
    <div class="creative-background">
        <div class="background-shapes">
            <div class="shape shape-1"></div>
            <div class="shape shape-2"></div>
            <div class="shape shape-3"></div>
        </div>
    </div>
    
    <nav class="navbar navbar-expand-lg creative-nav">
        <div class="container-fluid">
            <a class="navbar-brand creative-brand" href="/">
                @if(!empty($config[\'logo_url\']))
                    <div class="creative-logo-container">
                        <img src="{{ $config[\'logo_url\'] }}" alt="{{ $config[\'site_name\'] ?? \'Logo\' }}" class="creative-logo">
                        <div class="logo-glow"></div>
                    </div>
                @endif
                <div class="brand-text-creative">
                    <span class="brand-main">{{ $config[\'site_name\'] ?? \'Creative Studio\' }}</span>
                    <span class="brand-sub">Design & Innovation</span>
                </div>
            </a>
            
            <button class="navbar-toggler creative-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <div class="burger-lines">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav creative-menu">
                    @foreach($config[\'menu_items\'] ?? [] as $item)
                        <li class="nav-item creative-item">
                            <a class="nav-link creative-link" href="{{ $item[\'url\'] }}" 
                               @if($item[\'external\'] ?? false) target="_blank" @endif>
                                <span class="link-content">{{ $item[\'label\'] ?? $item[\'name\'] }}</span>
                                <span class="link-hover-effect"></span>
                            </a>
                        </li>
                    @endforeach
                </ul>
                
                <div class="creative-user-section">
                    @guest
                        <div class="auth-buttons-creative">
                            <a href="/login" class="btn-creative btn-login">
                                <span>Enter</span>
                                <div class="btn-bg"></div>
                            </a>
                            <a href="/register" class="btn-creative btn-signup">
                                <span>Join Us</span>
                                <div class="btn-bg"></div>
                            </a>
                        </div>
                    @else
                        <div class="creative-profile">
                            <a class="profile-trigger" href="#" data-bs-toggle="dropdown">
                                <div class="profile-avatar">
                                    <span>{{ substr(auth()->user()->name, 0, 2) }}</span>
                                </div>
                                <span class="profile-name">{{ auth()->user()->name }}</span>
                            </a>
                            <ul class="dropdown-menu creative-dropdown">
                                <li><a class="dropdown-item" href="/profile"><i class="fas fa-palette"></i> Creative Profile</a></li>
                                <li><a class="dropdown-item" href="/dashboard"><i class="fas fa-layer-group"></i> Projects</a></li>
                                @if(auth()->user()->hasAnyRole([\'admin\', \'super-admin\']))
                                    <li><a class="dropdown-item" href="/admin"><i class="fas fa-magic"></i> Studio Admin</a></li>
                                @endif
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="/logout">
                                        @csrf
                                        <button type="submit" class="dropdown-item"><i class="fas fa-sign-out-alt"></i> Exit</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @endguest
                    
                    @if(!empty($config[\'cta_button\'][\'text\']))
                        <a href="{{ $config[\'cta_button\'][\'url\'] ?? \'#\' }}" class="creative-cta">
                            <span>{{ $config[\'cta_button\'][\'text\'] }}</span>
                            <div class="cta-particles"></div>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </nav>
</div>',

            4 => '<!-- Ultra-Clean Header -->
<header class="minimal-header header-variant-' . $variant . '" id="mainNavbar">
    <nav class="navbar navbar-expand-lg minimal-nav">
        <div class="container">
            <div class="navbar-content">
                <a class="navbar-brand minimal-brand" href="/">
                    @if(!empty($config[\'logo_url\']))
                        <img src="{{ $config[\'logo_url\'] }}" alt="{{ $config[\'site_name\'] ?? \'Logo\' }}" class="minimal-logo">
                    @endif
                    <span class="brand-text-minimal">{{ $config[\'site_name\'] ?? \'Minimal\' }}</span>
                </a>
                
                <button class="navbar-toggler minimal-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="line"></span>
                    <span class="line"></span>
                </button>
                
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav minimal-menu">
                        @foreach($config[\'menu_items\'] ?? [] as $item)
                            <li class="nav-item">
                                <a class="nav-link minimal-link" href="{{ $item[\'url\'] }}" 
                                   @if($item[\'external\'] ?? false) target="_blank" @endif>
                                    {{ $item[\'label\'] ?? $item[\'name\'] }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                    
                    <div class="minimal-actions">
                        @guest
                            <a href="/login" class="minimal-btn">Sign In</a>
                            <a href="/register" class="minimal-btn minimal-btn-primary">Start</a>
                        @else
                            <div class="minimal-user">
                                <a class="minimal-user-link" href="#" data-bs-toggle="dropdown">
                                    {{ auth()->user()->name }}
                                    <span class="user-dot"></span>
                                </a>
                                <ul class="dropdown-menu minimal-dropdown">
                                    <li><a class="dropdown-item" href="/profile">Profile</a></li>
                                    <li><a class="dropdown-item" href="/dashboard">Dashboard</a></li>
                                    @if(auth()->user()->hasAnyRole([\'admin\', \'super-admin\']))
                                        <li><a class="dropdown-item" href="/admin">Admin</a></li>
                                    @endif
                                    <li>
                                        <form method="POST" action="/logout">
                                            @csrf
                                            <button type="submit" class="dropdown-item">Logout</button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        @endguest
                        
                        @if(!empty($config[\'cta_button\'][\'text\']))
                            <a href="{{ $config[\'cta_button\'][\'url\'] ?? \'#\' }}" class="minimal-cta">
                                {{ $config[\'cta_button\'][\'text\'] }}
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </nav>
</header>',

            5 => '<!-- Interactive Pro Header -->
<div class="interactive-header header-variant-' . $variant . '" id="mainNavbar">
    <div class="header-effects">
        <div class="particle-system" id="headerParticles"></div>
        <div class="gradient-orbs">
            <div class="orb orb-1"></div>
            <div class="orb orb-2"></div>
            <div class="orb orb-3"></div>
        </div>
    </div>
    
    <nav class="navbar navbar-expand-lg interactive-nav">
        <div class="container-fluid">
            <a class="navbar-brand interactive-brand" href="/">
                @if(!empty($config[\'logo_url\']))
                    <div class="logo-interactive">
                        <img src="{{ $config[\'logo_url\'] }}" alt="{{ $config[\'site_name\'] ?? \'Logo\' }}" class="brand-logo-interactive">
                        <div class="logo-pulse"></div>
                    </div>
                @endif
                <div class="brand-text-interactive">
                    <span class="brand-primary">{{ $config[\'site_name\'] ?? \'Interactive\' }}</span>
                    <span class="brand-secondary">Experience</span>
                </div>
            </a>
            
            <button class="navbar-toggler interactive-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <div class="toggler-icon">
                    <span class="bar bar-1"></span>
                    <span class="bar bar-2"></span>
                    <span class="bar bar-3"></span>
                </div>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav interactive-menu mx-auto">
                    @foreach($config[\'menu_items\'] ?? [] as $item)
                        <li class="nav-item interactive-item">
                            <a class="nav-link interactive-link" href="{{ $item[\'url\'] }}" 
                               @if($item[\'external\'] ?? false) target="_blank" @endif data-text="{{ $item[\'label\'] ?? $item[\'name\'] }}">
                                <span class="link-text">{{ $item[\'label\'] ?? $item[\'name\'] }}</span>
                                <span class="link-background"></span>
                                <span class="link-particles"></span>
                            </a>
                        </li>
                    @endforeach
                </ul>
                
                <div class="interactive-user-zone">
                    @guest
                        <div class="auth-interactive">
                            <a href="/login" class="interactive-btn btn-ghost">
                                <span class="btn-text">Login</span>
                                <div class="btn-ripple"></div>
                            </a>
                            <a href="/register" class="interactive-btn btn-solid">
                                <span class="btn-text">Join</span>
                                <div class="btn-glow"></div>
                            </a>
                        </div>
                    @else
                        <div class="user-interactive">
                            <a class="user-trigger" href="#" data-bs-toggle="dropdown">
                                <div class="user-avatar-interactive">
                                    <span class="avatar-text">{{ substr(auth()->user()->name, 0, 1) }}</span>
                                    <div class="avatar-ring"></div>
                                </div>
                                <span class="user-name-interactive">{{ auth()->user()->name }}</span>
                                <div class="dropdown-indicator"></div>
                            </a>
                            <ul class="dropdown-menu interactive-dropdown">
                                <li><a class="dropdown-item" href="/profile"><i class="fas fa-user-astronaut"></i> Profile</a></li>
                                <li><a class="dropdown-item" href="/dashboard"><i class="fas fa-rocket"></i> Control Center</a></li>
                                @if(auth()->user()->hasAnyRole([\'admin\', \'super-admin\']))
                                    <li><a class="dropdown-item" href="/admin"><i class="fas fa-satellite"></i> Command Center</a></li>
                                @endif
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="/logout">
                                        @csrf
                                        <button type="submit" class="dropdown-item"><i class="fas fa-power-off"></i> Power Down</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @endguest
                    
                    @if(!empty($config[\'cta_button\'][\'text\']))
                        <a href="{{ $config[\'cta_button\'][\'url\'] ?? \'#\' }}" class="cta-interactive">
                            <span class="cta-text">{{ $config[\'cta_button\'][\'text\'] }}</span>
                            <div class="cta-energy"></div>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </nav>
</div>'
        ];

        return $htmlVariants[$variant] ?? $htmlVariants[1];
    }

    private function getHeaderCSS($variant): string
    {
        $cssVariants = [
            1 => '/* Elevated Business Header */
.navbar-elevated {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-bottom: 1px solid rgba(0, 0, 0, 0.1);
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
    transition: all 0.4s ease;
    padding: 1rem 0;
}

.navbar-elevated.scrolled {
    padding: 0.5rem 0;
    box-shadow: 0 5px 30px rgba(0, 0, 0, 0.15);
}

.elevated-brand {
    display: flex;
    align-items: center;
    text-decoration: none;
}

.logo-wrapper {
    margin-right: 1rem;
    position: relative;
}

.brand-logo {
    height: 45px;
    transition: transform 0.3s ease;
}

.brand-logo:hover {
    transform: scale(1.05);
}

.brand-title {
    font-size: 1.8rem;
    font-weight: 700;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    letter-spacing: -0.5px;
}

.custom-toggler {
    border: none;
    background: none;
    padding: 0.5rem;
}

.custom-toggler span {
    display: block;
    width: 25px;
    height: 3px;
    background: #333;
    margin: 5px 0;
    transition: 0.3s;
}

.navigation-menu {
    gap: 2rem;
}

.nav-button {
    position: relative;
    color: #333 !important;
    font-weight: 500;
    padding: 0.75rem 1.5rem;
    text-decoration: none;
    border-radius: 50px;
    transition: all 0.3s ease;
}

.link-text {
    position: relative;
    z-index: 2;
}

.link-underline {
    position: absolute;
    bottom: 0;
    left: 50%;
    width: 0;
    height: 2px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    transition: all 0.3s ease;
    transform: translateX(-50%);
}

.nav-button:hover .link-underline {
    width: 100%;
}

.action-panel {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.btn-elevated {
    border-radius: 50px;
    padding: 0.75rem 2rem;
    font-weight: 500;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.btn-elevated:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.btn-cta-elevated {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    border-radius: 50px;
    padding: 0.75rem 2rem;
    text-decoration: none;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.3s ease;
}

.btn-cta-elevated:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
    color: white;
}

.user-dropdown .user-profile-link {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    color: #333;
    text-decoration: none;
    padding: 0.5rem 1rem;
    border-radius: 50px;
    transition: all 0.3s ease;
}

.user-avatar {
    font-size: 1.5rem;
    color: #667eea;
}

.user-menu {
    border: none;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
    border-radius: 15px;
    padding: 1rem 0;
    margin-top: 0.5rem;
}

@media (max-width: 991px) {
    .navigation-menu {
        margin: 2rem 0;
    }
    
    .action-panel {
        justify-content: center;
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px solid rgba(0, 0, 0, 0.1);
    }
}',

            2 => '/* Corporate Elite Header */
.corporate-header {
    background: #ffffff;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.header-top-bar {
    background: #2c3e50;
    color: #ecf0f1;
    padding: 0.5rem 0;
    font-size: 0.875rem;
}

.top-bar-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.contact-info span {
    margin-right: 2rem;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.header-social a {
    color: #ecf0f1;
    margin-left: 1rem;
    font-size: 1.1rem;
    transition: color 0.3s ease;
}

.header-social a:hover {
    color: #3498db;
}

.main-navigation {
    background: #ffffff;
    padding: 1.5rem 0;
}

.corporate-brand {
    display: flex;
    align-items: center;
    text-decoration: none;
    gap: 1.5rem;
}

.corporate-logo {
    height: 60px;
}

.brand-info {
    display: flex;
    flex-direction: column;
}

.company-name {
    font-size: 2rem;
    font-weight: 700;
    color: #2c3e50;
    margin: 0;
    line-height: 1.2;
    letter-spacing: -1px;
}

.company-tagline {
    font-size: 0.875rem;
    color: #7f8c8d;
    margin: 0;
    font-style: italic;
}

.corporate-toggler {
    border: none;
    background: none;
    display: flex;
    flex-direction: column;
    gap: 4px;
    padding: 0.5rem;
}

.toggler-line {
    width: 30px;
    height: 3px;
    background: #2c3e50;
    transition: 0.3s;
}

.corporate-nav {
    gap: 2rem;
}

.corporate-link {
    color: #2c3e50 !important;
    font-weight: 600;
    font-size: 1.1rem;
    padding: 1rem 1.5rem;
    border-radius: 5px;
    transition: all 0.3s ease;
    position: relative;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.corporate-link::before {
    content: "";
    position: absolute;
    bottom: 0;
    left: 0;
    width: 0;
    height: 3px;
    background: #3498db;
    transition: width 0.3s ease;
}

.corporate-link:hover::before {
    width: 100%;
}

.corporate-actions {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.corporate-btn {
    padding: 0.75rem 2rem;
    border-radius: 5px;
    font-weight: 600;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.3s ease;
}

.corporate-btn.btn-secondary {
    background: transparent;
    border: 2px solid #7f8c8d;
    color: #7f8c8d;
}

.corporate-btn.btn-secondary:hover {
    background: #7f8c8d;
    color: white;
}

.corporate-btn.btn-primary {
    background: #3498db;
    border: 2px solid #3498db;
    color: white;
}

.corporate-btn.btn-primary:hover {
    background: #2980b9;
    border-color: #2980b9;
}

.corporate-cta {
    background: linear-gradient(135deg, #e74c3c, #c0392b);
    color: white;
    padding: 1rem 2.5rem;
    border-radius: 5px;
    text-decoration: none;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
    transition: all 0.3s ease;
    margin-left: 1rem;
}

.corporate-cta:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(231, 76, 60, 0.3);
    color: white;
}

.corporate-user-menu .corporate-user-btn {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    color: #2c3e50;
    text-decoration: none;
    padding: 0.75rem 1.5rem;
    border: 2px solid #bdc3c7;
    border-radius: 5px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.user-initial {
    width: 35px;
    height: 35px;
    background: #3498db;
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
}

.corporate-dropdown {
    border: none;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
    border-radius: 10px;
    margin-top: 0.5rem;
    padding: 1rem 0;
}

@media (max-width: 991px) {
    .header-top-bar {
        display: none;
    }
    
    .company-name {
        font-size: 1.5rem;
    }
    
    .corporate-nav {
        margin: 2rem 0;
    }
    
    .corporate-actions {
        justify-content: center;
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 2px solid #ecf0f1;
    }
}',

            3 => '/* Creative Studio Header */
.creative-header {
    position: relative;
    background: radial-gradient(circle at 20% 80%, #ff6b6b 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, #4ecdc4 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, #45b7d1 0%, transparent 50%),
                linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    overflow: hidden;
    min-height: 80px;
}

.creative-background {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    pointer-events: none;
}

.background-shapes {
    position: relative;
    width: 100%;
    height: 100%;
}

.shape {
    position: absolute;
    border-radius: 50%;
    opacity: 0.1;
    animation: float 6s ease-in-out infinite;
}

.shape-1 {
    width: 100px;
    height: 100px;
    background: #ff6b6b;
    top: 20%;
    left: 10%;
    animation-delay: 0s;
}

.shape-2 {
    width: 150px;
    height: 150px;
    background: #4ecdc4;
    top: 60%;
    right: 20%;
    animation-delay: 2s;
}

.shape-3 {
    width: 80px;
    height: 80px;
    background: #45b7d1;
    bottom: 30%;
    left: 70%;
    animation-delay: 4s;
}

@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-20px); }
}

.creative-nav {
    position: relative;
    z-index: 10;
    padding: 1.5rem 0;
}

.creative-brand {
    display: flex;
    align-items: center;
    text-decoration: none;
    gap: 1.5rem;
}

.creative-logo-container {
    position: relative;
}

.creative-logo {
    height: 50px;
    filter: brightness(0) invert(1);
    transition: all 0.3s ease;
}

.logo-glow {
    position: absolute;
    top: -5px;
    left: -5px;
    right: -5px;
    bottom: -5px;
    background: radial-gradient(circle, rgba(255, 255, 255, 0.3) 0%, transparent 70%);
    border-radius: 50%;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.creative-brand:hover .logo-glow {
    opacity: 1;
}

.brand-text-creative {
    display: flex;
    flex-direction: column;
}

.brand-main {
    font-size: 2.2rem;
    font-weight: 800;
    color: white;
    line-height: 1;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
}

.brand-sub {
    font-size: 0.9rem;
    color: rgba(255, 255, 255, 0.8);
    font-weight: 300;
    font-style: italic;
}

.creative-toggler {
    border: none;
    background: rgba(255, 255, 255, 0.1);
    padding: 1rem;
    border-radius: 10px;
    backdrop-filter: blur(10px);
}

.burger-lines span {
    display: block;
    width: 25px;
    height: 2px;
    background: white;
    margin: 5px 0;
    transition: 0.3s;
}

.creative-menu {
    display: flex;
    gap: 0.5rem;
}

.creative-item {
    position: relative;
}

.creative-link {
    position: relative;
    color: white !important;
    font-weight: 600;
    padding: 1rem 2rem;
    text-decoration: none;
    border-radius: 50px;
    transition: all 0.3s ease;
    overflow: hidden;
    text-transform: capitalize;
    letter-spacing: 0.5px;
}

.link-content {
    position: relative;
    z-index: 2;
}

.link-hover-effect {
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: left 0.5s ease;
}

.creative-link:hover .link-hover-effect {
    left: 100%;
}

.creative-user-section {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.auth-buttons-creative {
    display: flex;
    gap: 1rem;
}

.btn-creative {
    position: relative;
    padding: 0.75rem 2rem;
    border: 2px solid rgba(255, 255, 255, 0.3);
    border-radius: 50px;
    color: white;
    text-decoration: none;
    font-weight: 600;
    overflow: hidden;
    transition: all 0.3s ease;
}

.btn-bg {
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.2);
    transition: left 0.3s ease;
}

.btn-creative:hover .btn-bg {
    left: 0;
}

.btn-creative:hover {
    color: white;
    border-color: rgba(255, 255, 255, 0.8);
}

.creative-profile .profile-trigger {
    display: flex;
    align-items: center;
    gap: 1rem;
    color: white;
    text-decoration: none;
    padding: 0.75rem 1.5rem;
    border: 2px solid rgba(255, 255, 255, 0.3);
    border-radius: 50px;
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
}

.profile-avatar {
    width: 40px;
    height: 40px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    border: 2px solid rgba(255, 255, 255, 0.3);
}

.creative-cta {
    background: linear-gradient(135deg, #ff6b6b, #ee5a24);
    color: white;
    padding: 1rem 2.5rem;
    border-radius: 50px;
    text-decoration: none;
    font-weight: 700;
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
}

.creative-cta:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 30px rgba(255, 107, 107, 0.4);
    color: white;
}

.cta-particles {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: url("data:image/svg+xml,<svg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 100 100\'><circle cx=\'10\' cy=\'20\' r=\'1\' fill=\'white\' opacity=\'0.3\'><animate attributeName=\'opacity\' values=\'0.3;1;0.3\' dur=\'2s\' repeatCount=\'indefinite\'/></circle><circle cx=\'80\' cy=\'80\' r=\'1.5\' fill=\'white\' opacity=\'0.5\'><animate attributeName=\'opacity\' values=\'0.5;1;0.5\' dur=\'1.5s\' repeatCount=\'indefinite\'/></circle></svg>") repeat;
    pointer-events: none;
}

@media (max-width: 991px) {
    .creative-header {
        min-height: auto;
    }
    
    .creative-menu {
        flex-direction: column;
        margin: 2rem 0;
        text-align: center;
    }
    
    .creative-user-section {
        justify-content: center;
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 1px solid rgba(255, 255, 255, 0.2);
    }
}',

            4 => '/* Ultra-Clean Header */
.minimal-header {
    background: #ffffff;
    border-bottom: 1px solid #f0f0f0;
    transition: all 0.3s ease;
}

.minimal-nav {
    padding: 2rem 0;
}

.minimal-header.scrolled {
    border-bottom-color: #e0e0e0;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
}

.navbar-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 100%;
}

.minimal-brand {
    display: flex;
    align-items: center;
    text-decoration: none;
    gap: 1rem;
}

.minimal-logo {
    height: 32px;
    opacity: 0.9;
    transition: opacity 0.3s ease;
}

.minimal-logo:hover {
    opacity: 1;
}

.brand-text-minimal {
    font-size: 1.5rem;
    font-weight: 300;
    color: #333;
    letter-spacing: 2px;
}

.minimal-toggler {
    border: none;
    background: none;
    padding: 0.5rem;
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.line {
    width: 20px;
    height: 1px;
    background: #333;
    transition: 0.3s;
}

.minimal-menu {
    display: flex;
    gap: 3rem;
    margin: 0;
    padding: 0;
}

.minimal-link {
    color: #666 !important;
    font-weight: 400;
    font-size: 0.95rem;
    text-decoration: none;
    padding: 0.5rem 0;
    position: relative;
    transition: color 0.3s ease;
    letter-spacing: 0.5px;
}

.minimal-link::after {
    content: "";
    position: absolute;
    bottom: -2px;
    left: 0;
    width: 0;
    height: 1px;
    background: #333;
    transition: width 0.3s ease;
}

.minimal-link:hover {
    color: #333 !important;
}

.minimal-link:hover::after {
    width: 100%;
}

.minimal-actions {
    display: flex;
    align-items: center;
    gap: 2rem;
}

.minimal-btn {
    color: #666;
    text-decoration: none;
    font-weight: 400;
    padding: 0.5rem 1.5rem;
    border-radius: 2px;
    transition: all 0.3s ease;
    font-size: 0.9rem;
    letter-spacing: 0.5px;
}

.minimal-btn:hover {
    color: #333;
}

.minimal-btn-primary {
    background: #333;
    color: white !important;
}

.minimal-btn-primary:hover {
    background: #000;
    color: white !important;
}

.minimal-user {
    position: relative;
}

.minimal-user-link {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #666;
    text-decoration: none;
    font-weight: 400;
    padding: 0.5rem 1rem;
    transition: color 0.3s ease;
    letter-spacing: 0.5px;
}

.user-dot {
    width: 6px;
    height: 6px;
    background: #333;
    border-radius: 50%;
}

.minimal-dropdown {
    border: 1px solid #f0f0f0;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    border-radius: 4px;
    margin-top: 0.5rem;
    padding: 0.5rem 0;
    min-width: 160px;
}

.minimal-dropdown .dropdown-item {
    color: #666;
    font-weight: 400;
    padding: 0.5rem 1.5rem;
    font-size: 0.9rem;
    transition: all 0.3s ease;
    letter-spacing: 0.25px;
}

.minimal-dropdown .dropdown-item:hover {
    background: #f8f8f8;
    color: #333;
}

.minimal-cta {
    background: #333;
    color: white;
    padding: 0.75rem 2rem;
    text-decoration: none;
    font-weight: 400;
    transition: background 0.3s ease;
    letter-spacing: 1px;
}

.minimal-cta:hover {
    background: #000;
    color: white;
}

@media (max-width: 991px) {
    .minimal-nav {
        padding: 1.5rem 0;
    }
    
    .minimal-menu {
        flex-direction: column;
        gap: 1rem;
        margin: 2rem 0;
        text-align: center;
    }
    
    .minimal-actions {
        justify-content: center;
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 1px solid #f0f0f0;
    }
    
    .navbar-content {
        flex-direction: column;
    }
}',

            5 => '/* Interactive Pro Header */
.interactive-header {
    position: relative;
    background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
    overflow: hidden;
    min-height: 90px;
}

.header-effects {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    pointer-events: none;
}

.particle-system {
    position: absolute;
    width: 100%;
    height: 100%;
}

.gradient-orbs {
    position: absolute;
    width: 100%;
    height: 100%;
}

.orb {
    position: absolute;
    border-radius: 50%;
    opacity: 0.6;
    animation: orbit 20s linear infinite;
    filter: blur(1px);
}

.orb-1 {
    width: 60px;
    height: 60px;
    background: radial-gradient(circle, #ff6b6b, #ee5a24);
    top: 20%;
    left: 10%;
    animation-duration: 15s;
}

.orb-2 {
    width: 40px;
    height: 40px;
    background: radial-gradient(circle, #4ecdc4, #44bd87);
    top: 70%;
    right: 15%;
    animation-duration: 25s;
    animation-direction: reverse;
}

.orb-3 {
    width: 80px;
    height: 80px;
    background: radial-gradient(circle, #a8e6cf, #88d8a3);
    bottom: 40%;
    left: 60%;
    animation-duration: 18s;
}

@keyframes orbit {
    from { transform: rotate(0deg) translateX(50px) rotate(0deg); }
    to { transform: rotate(360deg) translateX(50px) rotate(-360deg); }
}

.interactive-nav {
    position: relative;
    z-index: 10;
    padding: 1.5rem 0;
}

.interactive-brand {
    display: flex;
    align-items: center;
    text-decoration: none;
    gap: 1.5rem;
}

.logo-interactive {
    position: relative;
}

.brand-logo-interactive {
    height: 50px;
    filter: brightness(0) invert(1);
    transition: all 0.3s ease;
}

.logo-pulse {
    position: absolute;
    top: 50%;
    left: 50%;
    width: 60px;
    height: 60px;
    border: 2px solid rgba(255, 255, 255, 0.3);
    border-radius: 50%;
    transform: translate(-50%, -50%);
    animation: pulse 2s ease-in-out infinite;
}

@keyframes pulse {
    0% { transform: translate(-50%, -50%) scale(1); opacity: 0.8; }
    50% { transform: translate(-50%, -50%) scale(1.2); opacity: 0.4; }
    100% { transform: translate(-50%, -50%) scale(1); opacity: 0.8; }
}

.brand-text-interactive {
    display: flex;
    flex-direction: column;
}

.brand-primary {
    font-size: 2rem;
    font-weight: 700;
    color: white;
    line-height: 1;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
}

.brand-secondary {
    font-size: 0.9rem;
    color: rgba(255, 255, 255, 0.7);
    font-weight: 300;
    margin-top: 0.2rem;
}

.interactive-toggler {
    border: none;
    background: rgba(255, 255, 255, 0.1);
    padding: 1rem;
    border-radius: 8px;
    backdrop-filter: blur(10px);
}

.toggler-icon {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.bar {
    width: 25px;
    height: 2px;
    background: white;
    transition: 0.3s;
}

.interactive-menu {
    display: flex;
    gap: 0.5rem;
}

.interactive-link {
    position: relative;
    color: white !important;
    font-weight: 500;
    padding: 1rem 2rem;
    text-decoration: none;
    border-radius: 8px;
    transition: all 0.3s ease;
    overflow: hidden;
}

.link-text {
    position: relative;
    z-index: 3;
}

.link-background {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.1);
    transform: translateX(-100%);
    transition: transform 0.3s ease;
}

.interactive-link:hover .link-background {
    transform: translateX(0);
}

.link-particles {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    pointer-events: none;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.interactive-link:hover .link-particles {
    opacity: 1;
}

.interactive-user-zone {
    display: flex;
    align-items: center;
    gap: 1.5rem;
}

.auth-interactive {
    display: flex;
    gap: 1rem;
}

.interactive-btn {
    position: relative;
    padding: 0.75rem 2rem;
    border-radius: 8px;
    color: white;
    text-decoration: none;
    font-weight: 600;
    overflow: hidden;
    transition: all 0.3s ease;
}

.btn-ghost {
    border: 2px solid rgba(255, 255, 255, 0.3);
    background: transparent;
}

.btn-solid {
    background: rgba(255, 255, 255, 0.2);
    border: 2px solid rgba(255, 255, 255, 0.2);
}

.btn-ripple, .btn-glow {
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    border-radius: 50%;
    transform: translate(-50%, -50%);
    transition: all 0.3s ease;
}

.btn-ripple {
    background: rgba(255, 255, 255, 0.2);
}

.btn-glow {
    background: radial-gradient(circle, rgba(255, 255, 255, 0.3), transparent);
}

.interactive-btn:hover .btn-ripple,
.interactive-btn:hover .btn-glow {
    width: 300px;
    height: 300px;
}

.user-interactive .user-trigger {
    display: flex;
    align-items: center;
    gap: 1rem;
    color: white;
    text-decoration: none;
    padding: 0.75rem 1.5rem;
    border: 2px solid rgba(255, 255, 255, 0.3);
    border-radius: 8px;
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
}

.user-avatar-interactive {
    position: relative;
    width: 40px;
    height: 40px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
}

.avatar-ring {
    position: absolute;
    top: -2px;
    left: -2px;
    right: -2px;
    bottom: -2px;
    border: 1px solid rgba(255, 255, 255, 0.3);
    border-radius: 10px;
    animation: rotate 3s linear infinite;
}

@keyframes rotate {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

.dropdown-indicator {
    width: 8px;
    height: 8px;
    border: 2px solid rgba(255, 255, 255, 0.5);
    border-left: transparent;
    border-top: transparent;
    transform: rotate(45deg);
    transition: transform 0.3s ease;
}

.cta-interactive {
    background: linear-gradient(135deg, #ff6b6b, #ee5a24);
    color: white;
    padding: 1rem 2.5rem;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 700;
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
}

.cta-interactive:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(255, 107, 107, 0.4);
    color: white;
}

.cta-energy {
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
    transition: left 0.5s ease;
}

.cta-interactive:hover .cta-energy {
    left: 100%;
}

@media (max-width: 991px) {
    .interactive-header {
        min-height: auto;
    }
    
    .interactive-menu {
        flex-direction: column;
        margin: 2rem 0;
        text-align: center;
        gap: 0.5rem;
    }
    
    .interactive-user-zone {
        justify-content: center;
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 1px solid rgba(255, 255, 255, 0.2);
        flex-wrap: wrap;
    }
    
    .auth-interactive {
        order: 2;
        margin-top: 1rem;
    }
}'
        ];

        return $cssVariants[$variant] ?? $cssVariants[1];
    }

    private function getHeaderJS($variant): string
    {
        $jsVariants = [
            1 => '// Elevated Business Header JS
document.addEventListener("DOMContentLoaded", function() {
    const navbar = document.getElementById("mainNavbar");
    if (navbar) {
        // Scroll effect
        window.addEventListener("scroll", function() {
            if (window.scrollY > 50) {
                navbar.classList.add("scrolled");
            } else {
                navbar.classList.remove("scrolled");
            }
        });

        // Smooth hover effects for navigation
        const navLinks = document.querySelectorAll(".nav-button");
        navLinks.forEach(link => {
            link.addEventListener("mouseenter", function() {
                this.style.transform = "translateY(-2px)";
            });
            link.addEventListener("mouseleave", function() {
                this.style.transform = "translateY(0)";
            });
        });

        // Interactive CTA button
        const ctaBtn = document.querySelector(".btn-cta-elevated");
        if (ctaBtn) {
            ctaBtn.addEventListener("mouseenter", function() {
                this.style.transform = "translateY(-2px) scale(1.05)";
            });
            ctaBtn.addEventListener("mouseleave", function() {
                this.style.transform = "translateY(0) scale(1)";
            });
        }
    }
    
    // Auto-close mobile menu
    document.querySelectorAll(".navbar-nav .nav-link").forEach(link => {
        link.addEventListener("click", function() {
            const navbarCollapse = document.getElementById("navbarNav");
            if (navbarCollapse && window.bootstrap) {
                const bsCollapse = new bootstrap.Collapse(navbarCollapse, {toggle: false});
                bsCollapse.hide();
            }
        });
    });
});',

            2 => '// Corporate Elite Header JS
document.addEventListener("DOMContentLoaded", function() {
    const navbar = document.getElementById("mainNavbar");
    if (navbar) {
        // Professional scroll behavior
        window.addEventListener("scroll", function() {
            const header = document.querySelector(".corporate-header");
            if (window.scrollY > 100) {
                header.style.boxShadow = "0 4px 20px rgba(0, 0, 0, 0.15)";
            } else {
                header.style.boxShadow = "0 2px 10px rgba(0, 0, 0, 0.1)";
            }
        });

        // Corporate link animations
        const corporateLinks = document.querySelectorAll(".corporate-link");
        corporateLinks.forEach(link => {
            link.addEventListener("mouseenter", function() {
                this.style.color = "#3498db";
                this.style.transform = "translateY(-1px)";
            });
            link.addEventListener("mouseleave", function() {
                this.style.color = "#2c3e50";
                this.style.transform = "translateY(0)";
            });
        });

        // Professional button effects
        const corporateButtons = document.querySelectorAll(".corporate-btn");
        corporateButtons.forEach(btn => {
            btn.addEventListener("mouseenter", function() {
                this.style.transform = "translateY(-2px)";
                this.style.boxShadow = "0 8px 20px rgba(52, 152, 219, 0.3)";
            });
            btn.addEventListener("mouseleave", function() {
                this.style.transform = "translateY(0)";
                this.style.boxShadow = "none";
            });
        });
    }

    // Mobile menu behavior
    document.querySelectorAll(".navbar-nav .nav-link").forEach(link => {
        link.addEventListener("click", function() {
            const navbarCollapse = document.getElementById("navbarNav");
            if (navbarCollapse && window.bootstrap) {
                const bsCollapse = new bootstrap.Collapse(navbarCollapse, {toggle: false});
                bsCollapse.hide();
            }
        });
    });
});',

            3 => '// Creative Studio Header JS
document.addEventListener("DOMContentLoaded", function() {
    const navbar = document.getElementById("mainNavbar");
    if (navbar) {
        // Creative scroll effects
        window.addEventListener("scroll", function() {
            const header = document.querySelector(".creative-header");
            const scrolled = window.scrollY;
            
            // Parallax effect for background shapes
            const shapes = document.querySelectorAll(".shape");
            shapes.forEach((shape, index) => {
                const speed = 0.5 + (index * 0.2);
                const yPos = -(scrolled * speed);
                shape.style.transform = `translateY(${yPos}px)`;
            });
            
            if (scrolled > 50) {
                header.style.transform = "translateY(-5px)";
                header.style.boxShadow = "0 10px 40px rgba(0, 0, 0, 0.3)";
            } else {
                header.style.transform = "translateY(0)";
                header.style.boxShadow = "none";
            }
        });

        // Creative link hover effects
        const creativeLinks = document.querySelectorAll(".creative-link");
        creativeLinks.forEach(link => {
            link.addEventListener("mouseenter", function() {
                this.style.background = "rgba(255, 255, 255, 0.1)";
                this.style.backdropFilter = "blur(10px)";
                this.style.transform = "scale(1.05)";
            });
            link.addEventListener("mouseleave", function() {
                this.style.background = "transparent";
                this.style.backdropFilter = "none";
                this.style.transform = "scale(1)";
            });
        });

        // Animated particles effect
        function createParticle() {
            const particle = document.createElement("div");
            particle.style.cssText = `
                position: absolute;
                width: 2px;
                height: 2px;
                background: rgba(255, 255, 255, 0.6);
                border-radius: 50%;
                pointer-events: none;
                animation: float 3s ease-out forwards;
            `;
            
            particle.style.left = Math.random() * 100 + "%";
            particle.style.top = "100%";
            
            const headerEffects = document.querySelector(".header-effects");
            if (headerEffects) {
                headerEffects.appendChild(particle);
                
                setTimeout(() => {
                    particle.remove();
                }, 3000);
            }
        }

        // Create particles periodically
        setInterval(createParticle, 500);
    }

    // Mobile menu
    document.querySelectorAll(".navbar-nav .nav-link").forEach(link => {
        link.addEventListener("click", function() {
            const navbarCollapse = document.getElementById("navbarNav");
            if (navbarCollapse && window.bootstrap) {
                const bsCollapse = new bootstrap.Collapse(navbarCollapse, {toggle: false});
                bsCollapse.hide();
            }
        });
    });
});

// Add floating animation keyframes
const style = document.createElement("style");
style.textContent = `
    @keyframes float {
        0% { transform: translateY(0px) scale(0); opacity: 0; }
        50% { opacity: 1; }
        100% { transform: translateY(-100px) scale(1); opacity: 0; }
    }
`;
document.head.appendChild(style);',

            4 => '// Ultra-Clean Header JS
document.addEventListener("DOMContentLoaded", function() {
    const navbar = document.getElementById("mainNavbar");
    if (navbar) {
        // Minimal scroll behavior
        window.addEventListener("scroll", function() {
            const header = document.querySelector(".minimal-header");
            if (window.scrollY > 50) {
                header.classList.add("scrolled");
            } else {
                header.classList.remove("scrolled");
            }
        });

        // Subtle hover effects
        const minimalLinks = document.querySelectorAll(".minimal-link");
        minimalLinks.forEach(link => {
            link.addEventListener("mouseenter", function() {
                this.style.transform = "translateY(-1px)";
            });
            link.addEventListener("mouseleave", function() {
                this.style.transform = "translateY(0)";
            });
        });

        // Clean button interactions
        const minimalButtons = document.querySelectorAll(".minimal-btn");
        minimalButtons.forEach(btn => {
            btn.addEventListener("mouseenter", function() {
                if (this.classList.contains("minimal-btn-primary")) {
                    this.style.transform = "scale(1.02)";
                }
            });
            btn.addEventListener("mouseleave", function() {
                this.style.transform = "scale(1)";
            });
        });

        // Minimal dropdown behavior
        const userLink = document.querySelector(".minimal-user-link");
        if (userLink) {
            userLink.addEventListener("click", function(e) {
                e.preventDefault();
                const dropdown = this.nextElementSibling;
                if (dropdown) {
                    dropdown.style.opacity = dropdown.style.display === "block" ? "0" : "1";
                    dropdown.style.display = dropdown.style.display === "block" ? "none" : "block";
                }
            });
        }
    }

    // Mobile menu
    document.querySelectorAll(".navbar-nav .nav-link").forEach(link => {
        link.addEventListener("click", function() {
            const navbarCollapse = document.getElementById("navbarNav");
            if (navbarCollapse && window.bootstrap) {
                const bsCollapse = new bootstrap.Collapse(navbarCollapse, {toggle: false});
                bsCollapse.hide();
            }
        });
    });
});',

            5 => '// Interactive Pro Header JS
document.addEventListener("DOMContentLoaded", function() {
    const navbar = document.getElementById("mainNavbar");
    if (navbar) {
        // Advanced scroll effects
        window.addEventListener("scroll", function() {
            const header = document.querySelector(".interactive-header");
            const scrolled = window.scrollY;
            
            // Dynamic background gradient based on scroll
            const opacity = Math.min(scrolled / 300, 0.9);
            header.style.background = `linear-gradient(135deg, 
                rgba(30, 60, 114, ${0.8 + opacity}) 0%, 
                rgba(42, 82, 152, ${0.8 + opacity}) 100%)`;
            
            // Orb movements
            const orbs = document.querySelectorAll(".orb");
            orbs.forEach((orb, index) => {
                const speed = 0.3 + (index * 0.1);
                const yPos = -(scrolled * speed);
                orb.style.transform = `translateY(${yPos}px) rotate(${scrolled * 0.5}deg)`;
            });
            
            if (scrolled > 50) {
                header.style.boxShadow = "0 10px 50px rgba(0, 0, 0, 0.3)";
            } else {
                header.style.boxShadow = "none";
            }
        });

        // Interactive link effects
        const interactiveLinks = document.querySelectorAll(".interactive-link");
        interactiveLinks.forEach(link => {
            link.addEventListener("mouseenter", function() {
                // Create ripple effect
                const ripple = document.createElement("div");
                ripple.style.cssText = `
                    position: absolute;
                    top: 50%;
                    left: 50%;
                    width: 0;
                    height: 0;
                    background: rgba(255, 255, 255, 0.3);
                    border-radius: 50%;
                    transform: translate(-50%, -50%);
                    animation: ripple 0.6s ease-out;
                    pointer-events: none;
                `;
                this.appendChild(ripple);
                
                setTimeout(() => ripple.remove(), 600);
                
                this.style.transform = "scale(1.05)";
                this.style.zIndex = "5";
            });
            
            link.addEventListener("mouseleave", function() {
                this.style.transform = "scale(1)";
                this.style.zIndex = "auto";
            });
        });

        // Particle system
        function createInteractiveParticle(x, y) {
            const particle = document.createElement("div");
            particle.style.cssText = `
                position: absolute;
                width: 4px;
                height: 4px;
                background: radial-gradient(circle, #fff, transparent);
                border-radius: 50%;
                pointer-events: none;
                left: ${x}px;
                top: ${y}px;
                animation: particleFloat 2s ease-out forwards;
            `;
            
            const particleSystem = document.getElementById("headerParticles");
            if (particleSystem) {
                particleSystem.appendChild(particle);
                setTimeout(() => particle.remove(), 2000);
            }
        }

        // Mouse move particle generation
        navbar.addEventListener("mousemove", function(e) {
            if (Math.random() > 0.9) {
                const rect = this.getBoundingClientRect();
                createInteractiveParticle(
                    e.clientX - rect.left,
                    e.clientY - rect.top
                );
            }
        });

        // Interactive buttons
        const interactiveButtons = document.querySelectorAll(".interactive-btn, .cta-interactive");
        interactiveButtons.forEach(btn => {
            btn.addEventListener("mouseenter", function() {
                this.style.transform = "translateY(-3px) scale(1.05)";
                this.style.filter = "brightness(1.1)";
            });
            btn.addEventListener("mouseleave", function() {
                this.style.transform = "translateY(0) scale(1)";
                this.style.filter = "brightness(1)";
            });
        });
    }

    // Mobile menu
    document.querySelectorAll(".navbar-nav .nav-link").forEach(link => {
        link.addEventListener("click", function() {
            const navbarCollapse = document.getElementById("navbarNav");
            if (navbarCollapse && window.bootstrap) {
                const bsCollapse = new bootstrap.Collapse(navbarCollapse, {toggle: false});
                bsCollapse.hide();
            }
        });
    });
});

// Add animation keyframes
const interactiveStyle = document.createElement("style");
interactiveStyle.textContent = `
    @keyframes ripple {
        to { width: 300px; height: 300px; opacity: 0; }
    }
    @keyframes particleFloat {
        0% { transform: translateY(0) scale(0); opacity: 1; }
        50% { opacity: 0.8; }
        100% { transform: translateY(-50px) scale(1); opacity: 0; }
    }
`;
document.head.appendChild(interactiveStyle);'
        ];

        return $jsVariants[$variant] ?? $jsVariants[1];
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
