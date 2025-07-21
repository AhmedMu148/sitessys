<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Site;
use App\Models\SiteConfig;
use App\Models\TplLang;
use App\Models\ThemeCategory;
use App\Models\ThemePage;
use App\Models\TplLayout;
use App\Models\TplPage;
use App\Models\TplSite;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('🚀 Starting database seeding...');
        
        try {
            // Step 1: Create languages
            $this->command->info('🌐 Creating languages...');
            $this->createLanguages();
            
            // Step 2: Create theme categories
            $this->command->info('📁 Creating theme categories...');
            $this->createThemeCategories();
            
            // Step 3: Create theme pages
            $this->command->info('📄 Creating theme pages...');
            $this->createThemePages();
            
            // Step 4: Create layouts
            $this->command->info('🎨 Creating layouts...');
            $this->createLayouts();
            
            // Step 5: Create template pages
            $this->command->info('📝 Creating template pages...');
            $this->createTemplatePages();
            
            // Step 6: Create template site
            $this->command->info('🏗️ Creating template site...');
            $this->createTemplateSite();
            
            // Step 7: Create admin user
            $this->command->info('👤 Creating admin user...');
            $this->createAdminUser();
            
            // Step 8: Create demo site
            $this->command->info('🏢 Creating demo site...');
            $this->createDemoSite();
            
            $this->command->info('✅ Database seeding completed successfully!');
            $this->command->info('');
            $this->command->info('🔐 Login Details:');
            $this->command->info('Email: admin@spsystem.com');
            $this->command->info('Password: admin123');
            $this->command->info('Admin URL: http://localhost:8000/admin');
            
        } catch (\Exception $e) {
            $this->command->error('❌ Seeding failed: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Create default languages
     */
    protected function createLanguages(): void
    {
        $languages = [
            ['code' => 'en', 'name' => 'English', 'dir' => 'ltr'],
            ['code' => 'ar', 'name' => 'Arabic', 'dir' => 'rtl'],
            ['code' => 'es', 'name' => 'Spanish', 'dir' => 'ltr'],
            ['code' => 'fr', 'name' => 'French', 'dir' => 'ltr'],
            ['code' => 'de', 'name' => 'German', 'dir' => 'ltr'],
        ];

        foreach ($languages as $lang) {
            TplLang::updateOrCreate(
                ['code' => $lang['code']],
                $lang
            );
        }
    }

    /**
     * Create theme categories
     */
    protected function createThemeCategories(): void
    {
        $categories = [
            ['name' => 'Business', 'description' => 'Professional business templates', 'icon' => 'briefcase', 'sort_order' => 1],
            ['name' => 'Portfolio', 'description' => 'Creative portfolio templates', 'icon' => 'folder', 'sort_order' => 2],
            ['name' => 'E-commerce', 'description' => 'Online store templates', 'icon' => 'shopping-cart', 'sort_order' => 3],
            ['name' => 'Blog', 'description' => 'Blog and content templates', 'icon' => 'edit', 'sort_order' => 4],
        ];

        foreach ($categories as $category) {
            ThemeCategory::updateOrCreate(
                ['name' => $category['name']],
                $category
            );
        }
    }

    /**
     * Create theme pages
     */
    protected function createThemePages(): void
    {
        $themePages = [
            ['theme_id' => 'home', 'name' => 'Home', 'description' => 'Homepage template', 'path' => '/templates/home', 'sort_order' => 1],
            ['theme_id' => 'about', 'name' => 'About', 'description' => 'About page template', 'path' => '/templates/about', 'sort_order' => 2],
            ['theme_id' => 'services', 'name' => 'Services', 'description' => 'Services page template', 'path' => '/templates/services', 'sort_order' => 3],
            ['theme_id' => 'contact', 'name' => 'Contact', 'description' => 'Contact page template', 'path' => '/templates/contact', 'sort_order' => 4],
            ['theme_id' => 'portfolio', 'name' => 'Portfolio', 'description' => 'Portfolio page template', 'path' => '/templates/portfolio', 'sort_order' => 5],
        ];

        $businessCategory = ThemeCategory::where('name', 'Business')->first();

        foreach ($themePages as $page) {
            $page['category_id'] = $businessCategory?->id ?? 1;
            ThemePage::updateOrCreate(
                ['theme_id' => $page['theme_id']],
                $page
            );
        }
    }

    /**
     * Create default layouts
     */
    protected function createLayouts(): void
    {
        $layouts = [
            [
                'tpl_id' => 'main_header',
                'layout_type' => 'header',
                'name' => 'Main Header',
                'description' => 'Main website header with navigation',
                'path' => '/layouts/header/main',
                'default_config' => ['logo' => '', 'nav_items' => []],
                'content' => ['html' => $this->getDefaultHeaderHtml()],
                'status' => true,
                'sort_order' => 1,
            ],
            [
                'tpl_id' => 'main_footer',
                'layout_type' => 'footer',
                'name' => 'Main Footer',
                'description' => 'Main website footer with links',
                'path' => '/layouts/footer/main',
                'default_config' => ['social_links' => []],
                'content' => ['html' => $this->getDefaultFooterHtml()],
                'status' => true,
                'sort_order' => 1,
            ],
        ];

        foreach ($layouts as $layout) {
            TplLayout::updateOrCreate(
                ['tpl_id' => $layout['tpl_id']],
                $layout
            );
        }
    }

    /**
     * Create template pages
     */
    protected function createTemplatePages(): void
    {
        // We'll create pages after we have a site, so this method will be called later
        // For now, we'll skip creating template pages since they need a site_id
        $this->command->info('Template pages will be created with the demo site...');
    }

    /**
     * Create template site
     */
    protected function createTemplateSite(): void
    {
        // Skip creating template site as it needs a site_id
        // We'll create it when we create the demo site
        $this->command->info('Template site will be created with the demo site...');
    }

    /**
     * Create admin user
     */
    protected function createAdminUser(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@spsystem.com'],
            [
                'name' => 'System Administrator',
                'email' => 'admin@spsystem.com',
                'password' => Hash::make('admin123'),
                'role' => 'super-admin',
                'status_id' => true, // Active
                'preferred_language' => 'en',
            ]
        );
    }

    /**
     * Create demo site
     */
    protected function createDemoSite(): void
    {
        $admin = User::where('email', 'admin@spsystem.com')->first();
        $headerLayout = TplLayout::where('layout_type', 'header')->first();
        $footerLayout = TplLayout::where('layout_type', 'footer')->first();

        $site = Site::updateOrCreate(
            ['url' => 'demo.localhost'],
            [
                'user_id' => $admin->id,
                'site_name' => 'Demo Site',
                'url' => 'demo.localhost',
                'status_id' => true, // Active
                'active_header_id' => $headerLayout?->id,
                'active_footer_id' => $footerLayout?->id,
            ]
        );

        // Create site config
        SiteConfig::updateOrCreate(
            ['site_id' => $site->id],
            [
                'site_id' => $site->id,
                'settings' => [
                    'timezone' => 'UTC',
                    'theme' => 'default'
                ],
                'data' => [
                    'site_title' => 'Demo Site',
                    'site_description' => 'A demo website',
                    'contact_email' => 'contact@demo.localhost',
                    'contact_phone' => '+1234567890',
                    'social_facebook' => 'https://facebook.com/demo',
                    'social_twitter' => 'https://twitter.com/demo',
                ],
                'language_code' => [
                    'languages' => ['en', 'ar'],
                    'primary' => 'en'
                ],
                'tpl_name' => 'business',
                'tpl_colors' => [
                    'primary' => '#007bff',
                    'secondary' => '#6c757d'
                ]
            ]
        );

        // Create demo pages for the site
        $this->createDemoPages($site->id);

        // Create template site data
        TplSite::updateOrCreate(
            ['site_id' => $site->id],
            [
                'site_id' => $site->id,
                'nav_data' => [
                    'links' => [
                        ['url' => '/', 'label' => 'Home'],
                        ['url' => '/about', 'label' => 'About'],
                        ['url' => '/services', 'label' => 'Services'],
                        ['url' => '/contact', 'label' => 'Contact'],
                    ]
                ],
                'footer_data' => [
                    'links' => [
                        ['url' => '/privacy', 'label' => 'Privacy Policy'],
                        ['url' => '/terms', 'label' => 'Terms of Service'],
                        ['url' => '/sitemap', 'label' => 'Sitemap'],
                    ]
                ]
            ]
        );
    }

    /**
     * Create demo pages for the site
     */
    protected function createDemoPages($siteId): void
    {
        $homeTheme = ThemePage::where('theme_id', 'home')->first();
        $aboutTheme = ThemePage::where('theme_id', 'about')->first();

        $pages = [
            [
                'site_id' => $siteId,
                'name' => 'Home',
                'link' => '/',
                'slug' => 'home',
                'data' => [
                    'en' => [
                        'title' => 'Welcome to Demo Site',
                        'meta_description' => 'Welcome to our amazing demo website'
                    ],
                    'ar' => [
                        'title' => 'مرحباً بكم في الموقع التجريبي',
                        'meta_description' => 'مرحباً بكم في موقعنا التجريبي الرائع'
                    ]
                ],
                'show_in_nav' => true,
                'status' => true,
                'page_theme_id' => $homeTheme?->id,
            ],
            [
                'site_id' => $siteId,
                'name' => 'About',
                'link' => '/about',
                'slug' => 'about',
                'data' => [
                    'en' => [
                        'title' => 'About Us',
                        'meta_description' => 'Learn more about our company'
                    ],
                    'ar' => [
                        'title' => 'معلومات عنا',
                        'meta_description' => 'تعرف أكثر على شركتنا'
                    ]
                ],
                'show_in_nav' => true,
                'status' => true,
                'page_theme_id' => $aboutTheme?->id,
            ],
        ];

        foreach ($pages as $page) {
            TplPage::updateOrCreate(
                ['site_id' => $page['site_id'], 'slug' => $page['slug']],
                $page
            );
        }
    }

    /**
     * Get default header HTML
     */
    protected function getDefaultHeaderHtml(): string
    {
        return '<header class="main-header">
    <nav class="navbar">
        <div class="container">
            <div class="navbar-brand">
                <a href="/" class="brand-link">
                    <span class="brand-text">{{site_name}}</span>
                </a>
            </div>
            <div class="navbar-menu">
                <ul class="navbar-nav">
                    <li class="nav-item"><a href="/" class="nav-link">Home</a></li>
                    <li class="nav-item"><a href="/about" class="nav-link">About</a></li>
                    <li class="nav-item"><a href="/services" class="nav-link">Services</a></li>
                    <li class="nav-item"><a href="/contact" class="nav-link">Contact</a></li>
                </ul>
            </div>
        </div>
    </nav>
</header>';
    }

    /**
     * Get default header CSS
     */
    protected function getDefaultHeaderCss(): string
    {
        return '.main-header {
    background: #fff;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    position: sticky;
    top: 0;
    z-index: 1000;
}

.navbar {
    padding: 1rem 0;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 1rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.brand-link {
    text-decoration: none;
    color: #333;
    font-size: 1.5rem;
    font-weight: bold;
}

.navbar-nav {
    display: flex;
    list-style: none;
    margin: 0;
    padding: 0;
    gap: 2rem;
}

.nav-link {
    text-decoration: none;
    color: #666;
    transition: color 0.3s;
}

.nav-link:hover {
    color: #007bff;
}';
    }

    /**
     * Get default footer HTML
     */
    protected function getDefaultFooterHtml(): string
    {
        return '<footer class="main-footer">
    <div class="container">
        <div class="footer-content">
            <div class="footer-section">
                <h3>{{site_name}}</h3>
                <p>{{site_description}}</p>
            </div>
            <div class="footer-section">
                <h4>Contact Info</h4>
                <p>Email: {{contact_email}}</p>
                <p>Phone: {{contact_phone}}</p>
                <p>Address: {{address}}</p>
            </div>
            <div class="footer-section">
                <h4>Follow Us</h4>
                <div class="social-links">
                    <a href="{{social_facebook}}" target="_blank">Facebook</a>
                    <a href="{{social_twitter}}" target="_blank">Twitter</a>
                    <a href="{{social_instagram}}" target="_blank">Instagram</a>
                    <a href="{{social_linkedin}}" target="_blank">LinkedIn</a>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>{{footer_text}}</p>
        </div>
    </div>
</footer>';
    }

    /**
     * Get default footer CSS
     */
    protected function getDefaultFooterCss(): string
    {
        return '.main-footer {
    background: #333;
    color: #fff;
    padding: 3rem 0 1rem;
    margin-top: auto;
}

.footer-content {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 2rem;
    margin-bottom: 2rem;
}

.footer-section h3,
.footer-section h4 {
    margin-bottom: 1rem;
    color: #fff;
}

.footer-section p {
    margin-bottom: 0.5rem;
    color: #ccc;
}

.social-links {
    display: flex;
    gap: 1rem;
}

.social-links a {
    color: #ccc;
    text-decoration: none;
    transition: color 0.3s;
}

.social-links a:hover {
    color: #007bff;
}

.footer-bottom {
    text-align: center;
    padding-top: 2rem;
    border-top: 1px solid #555;
    color: #ccc;
}';
    }

    /**
     * Get default page HTML
     */
    protected function getDefaultPageHtml(): string
    {
        return '<div class="hero-section">
    <div class="container">
        <div class="hero-content">
            <h1>Welcome to {{site_name}}</h1>
            <p class="hero-subtitle">{{site_description}}</p>
            <div class="hero-buttons">
                <a href="/about" class="btn btn-primary">Learn More</a>
                <a href="/contact" class="btn btn-secondary">Get Started</a>
            </div>
        </div>
    </div>
</div>

<div class="features-section">
    <div class="container">
        <h2>Our Features</h2>
        <div class="features-grid">
            <div class="feature-item">
                <h3>Professional Design</h3>
                <p>Beautiful, responsive designs that work on all devices.</p>
            </div>
            <div class="feature-item">
                <h3>Easy to Use</h3>
                <p>User-friendly interface for managing your website content.</p>
            </div>
            <div class="feature-item">
                <h3>SEO Optimized</h3>
                <p>Built with SEO best practices to help you rank better.</p>
            </div>
        </div>
    </div>
</div>';
    }

    /**
     * Get default page CSS
     */
    protected function getDefaultPageCss(): string
    {
        return '.hero-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 5rem 0;
    text-align: center;
}

.hero-content h1 {
    font-size: 3rem;
    margin-bottom: 1rem;
    font-weight: bold;
}

.hero-subtitle {
    font-size: 1.25rem;
    margin-bottom: 2rem;
    opacity: 0.9;
}

.hero-buttons {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
}

.btn {
    padding: 0.75rem 2rem;
    border-radius: 5px;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s;
}

.btn-primary {
    background: #007bff;
    color: white;
}

.btn-secondary {
    background: transparent;
    color: white;
    border: 2px solid white;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
}

.features-section {
    padding: 5rem 0;
    background: #f8f9fa;
}

.features-section h2 {
    text-align: center;
    margin-bottom: 3rem;
    font-size: 2.5rem;
    color: #333;
}

.features-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
}

.feature-item {
    background: white;
    padding: 2rem;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    text-align: center;
}

.feature-item h3 {
    margin-bottom: 1rem;
    color: #333;
}

.feature-item p {
    color: #666;
    line-height: 1.6;
}';
    }
}
