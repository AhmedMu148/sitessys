<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Site;
use App\Models\TplLayout;
use App\Models\TplPage;
use App\Models\TplPageSection;
use App\Models\TplSite;
use App\Models\ThemeCategory;
use App\Models\SiteImgMedia;
use App\Services\ConfigurationService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class EnhancedDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        echo "🚀 Setting up Enhanced SPS system with comprehensive dummy data...\n";
        
        // Step 1: Create theme categories
        echo "🎨 Creating theme categories...\n";
        $this->createThemeCategories();
        
        // Step 2: Create comprehensive templates
        echo "📋 Creating comprehensive templates...\n";
        $this->createEnhancedTemplates();
        
        // Step 3: Create users with different roles
        echo "👨‍💼 Creating users...\n";
        $admin = $this->createAdminUser();
        $editor = $this->createEditorUser();
        $regularUser = $this->createRegularUser();
        
        // Step 4: Create multiple sites
        echo "🏢 Creating multiple sites...\n";
        $adminSite = $this->createAdminSite($admin);
        $businessSite = $this->createBusinessSite($admin);
        $portfolioSite = $this->createPortfolioSite($editor);
        
        // Step 5: Assign templates to sites
        echo "🔗 Assigning templates to sites...\n";
        $this->assignTemplatesToSite($adminSite, 'corporate');
        $this->assignTemplatesToSite($businessSite, 'business');
        $this->assignTemplatesToSite($portfolioSite, 'portfolio');
        
        // Step 6: Create media files
        echo "📁 Creating media library...\n";
        $this->createMediaFiles($adminSite, $admin);
        
        // Step 7: Create comprehensive configurations
        echo "⚙️ Setting up comprehensive configurations...\n";
        $this->createEnhancedConfigurations($adminSite);
        $this->createEnhancedConfigurations($businessSite);
        $this->createEnhancedConfigurations($portfolioSite);
        
        echo "✅ Enhanced setup completed successfully!\n\n";
        echo "📧 Admin Login: admin@seo.com / seopass123\n";
        echo "📧 Editor Login: editor@seo.com / editorpass123\n";
        echo "📧 User Login: user@example.com / userpass123\n";
        echo "🔗 Admin Panel: http://localhost:8000/admin?site_id={$adminSite->id}\n";
        echo "🌐 Corporate Site: http://localhost:8000\n";
        echo "🌐 Business Site: http://business.localhost:8000\n";
        echo "🌐 Portfolio Site: http://portfolio.localhost:8000\n";
    }

    /**
     * Create theme categories
     */
    private function createThemeCategories(): void
    {
        $categories = [
            [
                'name' => 'Business',
                'description' => 'Professional business and corporate website themes',
                'icon' => 'fas fa-briefcase',
                'sort_order' => 1,
                'status' => true
            ],
            [
                'name' => 'Portfolio',
                'description' => 'Creative portfolio and showcase themes',
                'icon' => 'fas fa-paint-brush',
                'sort_order' => 2,
                'status' => true
            ],
            [
                'name' => 'E-commerce',
                'description' => 'Online store and e-commerce themes',
                'icon' => 'fas fa-shopping-cart',
                'sort_order' => 3,
                'status' => true
            ],
            [
                'name' => 'Blog',
                'description' => 'Blog and content-focused themes',
                'icon' => 'fas fa-blog',
                'sort_order' => 4,
                'status' => true
            ],
            [
                'name' => 'Agency',
                'description' => 'Digital agency and marketing themes',
                'icon' => 'fas fa-rocket',
                'sort_order' => 5,
                'status' => true
            ],
            [
                'name' => 'Restaurant',
                'description' => 'Restaurant and food service themes',
                'icon' => 'fas fa-utensils',
                'sort_order' => 6,
                'status' => true
            ],
            [
                'name' => 'Technology',
                'description' => 'Tech startup and SaaS themes',
                'icon' => 'fas fa-microchip',
                'sort_order' => 7,
                'status' => true
            ],
            [
                'name' => 'Healthcare',
                'description' => 'Medical and healthcare themes',
                'icon' => 'fas fa-heartbeat',
                'sort_order' => 8,
                'status' => true
            ]
        ];

        foreach ($categories as $category) {
            ThemeCategory::updateOrCreate(
                ['name' => $category['name']],
                $category
            );
            echo "  ✓ {$category['name']} category\n";
        }
    }

    /**
     * Create enhanced templates with more variety
     */
    private function createEnhancedTemplates(): void
    {
        $templates = [
            // Modern Header Templates
            [
                'tpl_id' => 'modern-header-v1',
                'layout_type' => 'header',
                'name' => 'Modern Header V1',
                'content' => $this->getModernHeaderTemplate(),
                'configurable_fields' => $this->getHeaderConfigurableFields(),
                'default_config' => $this->getModernHeaderConfig()
            ],
            [
                'tpl_id' => 'creative-header-v1',
                'layout_type' => 'header',
                'name' => 'Creative Header V1',
                'content' => $this->getCreativeHeaderTemplate(),
                'configurable_fields' => $this->getHeaderConfigurableFields(),
                'default_config' => $this->getCreativeHeaderConfig()
            ],

            // Footer Templates
            [
                'tpl_id' => 'modern-footer-v1',
                'layout_type' => 'footer',
                'name' => 'Modern Footer V1',
                'content' => $this->getModernFooterTemplate(),
                'configurable_fields' => $this->getFooterConfigurableFields(),
                'default_config' => $this->getModernFooterConfig()
            ],
            [
                'tpl_id' => 'minimal-footer-v1',
                'layout_type' => 'footer',
                'name' => 'Minimal Footer V1',
                'content' => $this->getMinimalFooterTemplate(),
                'configurable_fields' => $this->getFooterConfigurableFields(),
                'default_config' => $this->getMinimalFooterConfig()
            ],

            // Hero Section Templates
            [
                'tpl_id' => 'hero-business-v1',
                'layout_type' => 'section',
                'name' => 'Business Hero Section',
                'content' => $this->getBusinessHeroTemplate(),
                'configurable_fields' => $this->getHeroConfigurableFields(),
                'default_config' => $this->getBusinessHeroConfig()
            ],
            [
                'tpl_id' => 'hero-portfolio-v1',
                'layout_type' => 'section',
                'name' => 'Portfolio Hero Section',
                'content' => $this->getPortfolioHeroTemplate(),
                'configurable_fields' => $this->getHeroConfigurableFields(),
                'default_config' => $this->getPortfolioHeroConfig()
            ],
            [
                'tpl_id' => 'hero-agency-v1',
                'layout_type' => 'section',
                'name' => 'Agency Hero Section',
                'content' => $this->getAgencyHeroTemplate(),
                'configurable_fields' => $this->getHeroConfigurableFields(),
                'default_config' => $this->getAgencyHeroConfig()
            ],

            // Services Section Templates
            [
                'tpl_id' => 'services-grid-v1',
                'layout_type' => 'section',
                'name' => 'Services Grid Section',
                'content' => $this->getServicesGridTemplate(),
                'configurable_fields' => $this->getServicesConfigurableFields(),
                'default_config' => $this->getServicesGridConfig()
            ],
            [
                'tpl_id' => 'services-carousel-v1',
                'layout_type' => 'section',
                'name' => 'Services Carousel Section',
                'content' => $this->getServicesCarouselTemplate(),
                'configurable_fields' => $this->getServicesConfigurableFields(),
                'default_config' => $this->getServicesCarouselConfig()
            ],

            // About Section Templates
            [
                'tpl_id' => 'about-corporate-v1',
                'layout_type' => 'section',
                'name' => 'Corporate About Section',
                'content' => $this->getCorporateAboutTemplate(),
                'configurable_fields' => $this->getAboutConfigurableFields(),
                'default_config' => $this->getCorporateAboutConfig()
            ],
            [
                'tpl_id' => 'about-creative-v1',
                'layout_type' => 'section',
                'name' => 'Creative About Section',
                'content' => $this->getCreativeAboutTemplate(),
                'configurable_fields' => $this->getAboutConfigurableFields(),
                'default_config' => $this->getCreativeAboutConfig()
            ],

            // Portfolio Section Templates
            [
                'tpl_id' => 'portfolio-grid-v1',
                'layout_type' => 'section',
                'name' => 'Portfolio Grid Section',
                'content' => $this->getPortfolioGridTemplate(),
                'configurable_fields' => $this->getPortfolioConfigurableFields(),
                'default_config' => $this->getPortfolioGridConfig()
            ],
            [
                'tpl_id' => 'portfolio-masonry-v1',
                'layout_type' => 'section',
                'name' => 'Portfolio Masonry Section',
                'content' => $this->getPortfolioMasonryTemplate(),
                'configurable_fields' => $this->getPortfolioConfigurableFields(),
                'default_config' => $this->getPortfolioMasonryConfig()
            ],

            // Contact Section Templates
            [
                'tpl_id' => 'contact-modern-v1',
                'layout_type' => 'section',
                'name' => 'Modern Contact Section',
                'content' => $this->getModernContactTemplate(),
                'configurable_fields' => $this->getContactConfigurableFields(),
                'default_config' => $this->getModernContactConfig()
            ],
            [
                'tpl_id' => 'contact-creative-v1',
                'layout_type' => 'section',
                'name' => 'Creative Contact Section',
                'content' => $this->getCreativeContactTemplate(),
                'configurable_fields' => $this->getContactConfigurableFields(),
                'default_config' => $this->getCreativeContactConfig()
            ],

            // Testimonials Section Templates
            [
                'tpl_id' => 'testimonials-slider-v1',
                'layout_type' => 'section',
                'name' => 'Testimonials Slider Section',
                'content' => $this->getTestimonialsSliderTemplate(),
                'configurable_fields' => $this->getTestimonialsConfigurableFields(),
                'default_config' => $this->getTestimonialsSliderConfig()
            ],
            [
                'tpl_id' => 'testimonials-grid-v1',
                'layout_type' => 'section',
                'name' => 'Testimonials Grid Section',
                'content' => $this->getTestimonialsGridTemplate(),
                'configurable_fields' => $this->getTestimonialsConfigurableFields(),
                'default_config' => $this->getTestimonialsGridConfig()
            ],

            // CTA Section Templates
            [
                'tpl_id' => 'cta-banner-v1',
                'layout_type' => 'section',
                'name' => 'CTA Banner Section',
                'content' => $this->getCTABannerTemplate(),
                'configurable_fields' => $this->getCTAConfigurableFields(),
                'default_config' => $this->getCTABannerConfig()
            ],
            [
                'tpl_id' => 'cta-split-v1',
                'layout_type' => 'section',
                'name' => 'CTA Split Section',
                'content' => $this->getCTASplitTemplate(),
                'configurable_fields' => $this->getCTAConfigurableFields(),
                'default_config' => $this->getCTASplitConfig()
            ]
        ];

        foreach ($templates as $template) {
            TplLayout::updateOrCreate(
                ['tpl_id' => $template['tpl_id']],
                [
                    'tpl_id' => $template['tpl_id'],
                    'layout_type' => $template['layout_type'],
                    'name' => $template['name'],
                    'content' => json_encode($template['content']),
                    'configurable_fields' => json_encode($template['configurable_fields']),
                    'default_config' => json_encode($template['default_config']),
                    'path' => 'templates/' . $template['layout_type'] . 's/' . $template['tpl_id'],
                    'status' => true,
                    'sort_order' => 0
                ]
            );
            
            echo "  ✓ {$template['name']}\n";
        }
    }

    /**
     * Create admin user
     */
    private function createAdminUser(): User
    {
        return User::updateOrCreate(
            ['email' => 'admin@seo.com'],
            [
                'name' => 'Super Admin',
                'email' => 'admin@seo.com',
                'password' => Hash::make('seopass123'),
                'role' => 'super-admin',
                'status_id' => true,
                'preferred_language' => 'en',
                'created_at' => now()->subDays(30),
                'updated_at' => now()
            ]
        );
    }

    /**
     * Create editor user
     */
    private function createEditorUser(): User
    {
        return User::updateOrCreate(
            ['email' => 'editor@seo.com'],
            [
                'name' => 'Content Editor',
                'email' => 'editor@seo.com',
                'password' => Hash::make('editorpass123'),
                'role' => 'admin',
                'status_id' => true,
                'preferred_language' => 'en',
                'created_at' => now()->subDays(15),
                'updated_at' => now()
            ]
        );
    }

    /**
     * Create regular user
     */
    private function createRegularUser(): User
    {
        return User::updateOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'Regular User',
                'email' => 'user@example.com',
                'password' => Hash::make('userpass123'),
                'role' => 'user',
                'status_id' => true,
                'preferred_language' => 'en',
                'created_at' => now()->subDays(7),
                'updated_at' => now()
            ]
        );
    }

    /**
     * Create admin site (main corporate site)
     */
    private function createAdminSite(User $admin): Site
    {
        $headerTemplate = TplLayout::where('tpl_id', 'modern-header-v1')->first();
        $footerTemplate = TplLayout::where('tpl_id', 'modern-footer-v1')->first();

        $site = Site::updateOrCreate(
            ['url' => 'http://localhost:8000'],
            [
                'user_id' => $admin->id,
                'site_name' => 'SEO Business Pro',
                'url' => 'http://localhost:8000',
                'status_id' => true,
                'active_header_id' => $headerTemplate?->id,
                'active_footer_id' => $footerTemplate?->id,
                'created_at' => now()->subDays(30),
                'updated_at' => now()
            ]
        );

        return $site;
    }

    /**
     * Create business site
     */
    private function createBusinessSite(User $admin): Site
    {
        $headerTemplate = TplLayout::where('tpl_id', 'modern-header-v1')->first();
        $footerTemplate = TplLayout::where('tpl_id', 'modern-footer-v1')->first();

        $site = Site::updateOrCreate(
            ['url' => 'http://business.localhost:8000'],
            [
                'user_id' => $admin->id,
                'site_name' => 'Business Solutions Hub',
                'url' => 'http://business.localhost:8000',
                'status_id' => true,
                'active_header_id' => $headerTemplate?->id,
                'active_footer_id' => $footerTemplate?->id,
                'created_at' => now()->subDays(20),
                'updated_at' => now()
            ]
        );

        return $site;
    }

    /**
     * Create portfolio site
     */
    private function createPortfolioSite(User $editor): Site
    {
        $headerTemplate = TplLayout::where('tpl_id', 'creative-header-v1')->first();
        $footerTemplate = TplLayout::where('tpl_id', 'minimal-footer-v1')->first();

        $site = Site::updateOrCreate(
            ['url' => 'http://portfolio.localhost:8000'],
            [
                'user_id' => $editor->id,
                'site_name' => 'Creative Portfolio Studio',
                'url' => 'http://portfolio.localhost:8000',
                'status_id' => true,
                'active_header_id' => $headerTemplate?->id,
                'active_footer_id' => $footerTemplate?->id,
                'created_at' => now()->subDays(10),
                'updated_at' => now()
            ]
        );

        return $site;
    }

    /**
     * Assign templates to site and create pages
     */
    private function assignTemplatesToSite(Site $site, string $theme): void
    {
        $pageConfigurations = $this->getPageConfigurationsByTheme($theme);

        foreach ($pageConfigurations as $pageData) {
            $page = TplPage::updateOrCreate(
                [
                    'site_id' => $site->id,
                    'slug' => $pageData['slug']
                ],
                [
                    'site_id' => $site->id,
                    'name' => $pageData['name'],
                    'slug' => $pageData['slug'],
                    'link' => $pageData['link'],
                    'data' => [
                        'en' => [
                            'title' => $pageData['name'] . ' - ' . $site->site_name,
                            'description' => $pageData['description'] ?? $pageData['name'] . ' page for ' . $site->site_name,
                            'keywords' => $pageData['keywords'] ?? ''
                        ],
                        'ar' => [
                            'title' => $pageData['name'] . ' - ' . $site->site_name,
                            'description' => 'صفحة ' . $pageData['name'] . ' لموقع ' . $site->site_name,
                            'keywords' => ''
                        ]
                    ],
                    'show_in_nav' => $pageData['show_in_nav'] ?? true,
                    'status' => true,
                    'created_at' => now()->subDays(rand(1, 25)),
                    'updated_at' => now()
                ]
            );

            // Add sections to page
            $sortOrder = 1;
            foreach ($pageData['sections'] as $templateId) {
                $template = TplLayout::where('tpl_id', $templateId)->first();
                if ($template) {
                    TplPageSection::updateOrCreate(
                        [
                            'page_id' => $page->id,
                            'tpl_layouts_id' => $template->id
                        ],
                        [
                            'page_id' => $page->id,
                            'tpl_layouts_id' => $template->id,
                            'site_id' => $site->id,
                            'name' => $template->name . ' on ' . $page->name,
                            'content' => $template->default_config ?: '{}',
                            'status' => 1,
                            'sort_order' => $sortOrder++,
                            'created_at' => now()->subDays(rand(1, 20)),
                            'updated_at' => now()
                        ]
                    );
                }
            }
            
            echo "  ✓ Created page: {$page->name} with " . count($pageData['sections']) . " sections\n";
        }

        // Create TplSite with navigation and footer data
        $headerLayout = TplLayout::where('layout_type', 'header')->first();
        $footerLayout = TplLayout::where('layout_type', 'footer')->first();
        
        TplSite::updateOrCreate(
            ['site_id' => $site->id],
            [
                'site_id' => $site->id,
                'nav' => $headerLayout ? $headerLayout->id : null,
                'footer' => $footerLayout ? $footerLayout->id : null,
                'nav_data' => $this->getNavigationDataByTheme($theme),
                'footer_data' => $this->getFooterDataByTheme($theme),
                'created_at' => now()->subDays(rand(1, 15)),
                'updated_at' => now()
            ]
        );

        echo "  ✓ Created TplSite configuration for {$site->site_name}\n";
    }

    /**
     * Create media files
     */
    private function createMediaFiles(Site $site, User $user): void
    {
        // Create media configuration entries for different sections
        $mediaConfigs = [
            [
                'site_id' => $site->id,
                'section_id' => null, // Global media config
                'max_files' => 50,
                'allowed_types' => json_encode(['image/jpeg', 'image/png', 'image/gif', 'image/svg+xml', 'image/webp'])
            ],
            [
                'site_id' => $site->id,
                'section_id' => null, // Another global config for documents
                'max_files' => 20,
                'allowed_types' => json_encode(['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])
            ]
        ];

        foreach ($mediaConfigs as $config) {
            SiteImgMedia::updateOrCreate(
                [
                    'site_id' => $config['site_id'],
                    'section_id' => $config['section_id']
                ],
                $config
            );
        }
        
        echo "  ✓ Created media configurations for {$site->site_name}\n";
    }

    /**
     * Create enhanced configurations
     */
    private function createEnhancedConfigurations(Site $site): void
    {
        $configService = app(ConfigurationService::class);
        
        // Initialize base configurations
        $configService->initializeDefaults($site->id);
        
        // Enhanced theme configuration
        $themeConfig = [
            'theme' => $this->getThemeBysite($site),
            'header_theme' => 'modern-header-v1',
            'footer_theme' => 'modern-footer-v1',
            'color_scheme' => $this->getColorSchemeByTheme($this->getThemeBysite($site)),
            'layout_style' => 'modern',
            'animation_style' => 'smooth',
            'responsive_breakpoints' => [
                'mobile' => 768,
                'tablet' => 1024,
                'desktop' => 1200
            ]
        ];
        
        // Enhanced language configuration
        $languageConfig = [
            'languages' => ['en', 'ar'],
            'primary_language' => 'en',
            'rtl_languages' => ['ar'],
            'fallback_language' => 'en',
            'auto_detect' => true,
            'show_language_switcher' => true,
            'date_format' => [
                'en' => 'M d, Y',
                'ar' => 'd/m/Y'
            ],
            'currency' => [
                'en' => 'USD',
                'ar' => 'SAR'
            ]
        ];
        
        // Enhanced navigation configuration
        $navigationConfig = [
            'header' => [
                'theme' => 'modern-header-v1',
                'style' => 'horizontal',
                'position' => 'top',
                'sticky' => true,
                'links' => $this->getNavigationLinksByTheme($this->getThemeBysite($site))
            ],
            'footer' => [
                'theme' => 'modern-footer-v1',
                'style' => 'multi-column',
                'show_social' => true,
                'show_newsletter' => true,
                'links' => $this->getFooterLinksByTheme($this->getThemeBysite($site))
            ],
            'breadcrumbs' => [
                'enabled' => true,
                'show_home' => true,
                'separator' => '/'
            ]
        ];
        
        // Enhanced colors configuration
        $colorsConfig = [
            'primary' => $this->getPrimaryColorByTheme($this->getThemeBysite($site)),
            'secondary' => $this->getSecondaryColorByTheme($this->getThemeBysite($site)),
            'accent' => $this->getAccentColorByTheme($this->getThemeBysite($site)),
            'nav' => [
                'background' => '#ffffff',
                'text' => '#333333',
                'hover' => $this->getPrimaryColorByTheme($this->getThemeBysite($site))
            ],
            'footer' => [
                'background' => '#1a1a1a',
                'text' => '#ffffff',
                'links' => '#cccccc'
            ],
            'buttons' => [
                'primary_bg' => $this->getPrimaryColorByTheme($this->getThemeBysite($site)),
                'primary_text' => '#ffffff',
                'secondary_bg' => 'transparent',
                'secondary_text' => $this->getPrimaryColorByTheme($this->getThemeBysite($site))
            ]
        ];
        
        // Enhanced media configuration
        $mediaConfig = [
            'max_upload_size' => 10485760, // 10MB
            'allowed_types' => ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp', 'pdf'],
            'image_quality' => 85,
            'create_thumbnails' => true,
            'thumbnail_sizes' => [
                'small' => ['width' => 150, 'height' => 150],
                'medium' => ['width' => 300, 'height' => 300],
                'large' => ['width' => 600, 'height' => 600]
            ],
            'optimization' => [
                'enabled' => true,
                'webp_conversion' => true,
                'lazy_loading' => true
            ]
        ];
        
        // Save configurations
        try {
            $configService->set($site->id, 'theme', $themeConfig);
            $configService->set($site->id, 'language', $languageConfig);
            $configService->set($site->id, 'navigation', $navigationConfig);
            $configService->set($site->id, 'colors', $colorsConfig);
            $configService->set($site->id, 'media', $mediaConfig);
            
            echo "  ✓ Enhanced configurations for {$site->site_name}\n";
        } catch (\Exception $e) {
            echo "  ⚠️ Error creating configurations for {$site->site_name}: {$e->getMessage()}\n";
        }
    }

    // Helper methods for template configurations
    private function getPageConfigurationsByTheme(string $theme): array
    {
        switch ($theme) {
            case 'corporate':
                return [
                    [
                        'name' => 'Home',
                        'slug' => 'home',
                        'link' => '/',
                        'description' => 'Welcome to our homepage showcasing our services and expertise',
                        'keywords' => 'home, welcome, services, company, business',
                        'show_in_nav' => true,
                        'sections' => ['hero-business-v1', 'services-grid-v1', 'about-corporate-v1', 'testimonials-slider-v1', 'cta-banner-v1']
                    ],
                    [
                        'name' => 'About',
                        'slug' => 'about',
                        'link' => '/about',
                        'description' => 'Learn more about our company, team, and mission',
                        'keywords' => 'about, company, team, mission, history',
                        'show_in_nav' => true,
                        'sections' => ['about-corporate-v1', 'testimonials-grid-v1']
                    ],
                    [
                        'name' => 'Services',
                        'slug' => 'services',
                        'link' => '/services',
                        'description' => 'Comprehensive overview of our professional services',
                        'keywords' => 'services, solutions, offerings, professional',
                        'show_in_nav' => true,
                        'sections' => ['services-grid-v1', 'cta-split-v1']
                    ],
                    [
                        'name' => 'Contact',
                        'slug' => 'contact',
                        'link' => '/contact',
                        'description' => 'Get in touch with our team for inquiries and support',
                        'keywords' => 'contact, support, inquiries, communication',
                        'show_in_nav' => true,
                        'sections' => ['contact-modern-v1']
                    ]
                ];

            case 'business':
                return [
                    [
                        'name' => 'Home',
                        'slug' => 'home',
                        'link' => '/',
                        'description' => 'Welcome to our homepage showcasing our services and expertise',
                        'keywords' => 'home, welcome, services, company, business',
                        'show_in_nav' => true,
                        'sections' => ['hero-business-v1', 'services-carousel-v1', 'about-corporate-v1', 'testimonials-slider-v1']
                    ],
                    [
                        'name' => 'About',
                        'slug' => 'about',
                        'link' => '/about',
                        'description' => 'Learn more about our company, team, and mission',
                        'keywords' => 'about, company, team, mission, history',
                        'show_in_nav' => true,
                        'sections' => ['about-corporate-v1']
                    ],
                    [
                        'name' => 'Services',
                        'slug' => 'services',
                        'link' => '/services',
                        'description' => 'Comprehensive overview of our professional services',
                        'keywords' => 'services, solutions, offerings, professional',
                        'show_in_nav' => true,
                        'sections' => ['services-grid-v1']
                    ],
                    [
                        'name' => 'Contact',
                        'slug' => 'contact',
                        'link' => '/contact',
                        'description' => 'Get in touch with our team for inquiries and support',
                        'keywords' => 'contact, support, inquiries, communication',
                        'show_in_nav' => true,
                        'sections' => ['contact-modern-v1']
                    ]
                ];

            case 'portfolio':
                return [
                    [
                        'name' => 'Home',
                        'slug' => 'home',
                        'link' => '/',
                        'description' => 'Welcome to our homepage showcasing our services and expertise',
                        'keywords' => 'home, welcome, services, company, business',
                        'show_in_nav' => true,
                        'sections' => ['hero-portfolio-v1', 'portfolio-grid-v1', 'about-creative-v1']
                    ],
                    [
                        'name' => 'About',
                        'slug' => 'about',
                        'link' => '/about',
                        'description' => 'Learn more about our company, team, and mission',
                        'keywords' => 'about, company, team, mission, history',
                        'show_in_nav' => true,
                        'sections' => ['about-creative-v1']
                    ],
                    [
                        'name' => 'Portfolio',
                        'slug' => 'portfolio',
                        'link' => '/portfolio',
                        'description' => 'Showcase of our creative work and projects',
                        'keywords' => 'portfolio, work, projects, creative, showcase',
                        'show_in_nav' => true,
                        'sections' => ['portfolio-grid-v1', 'portfolio-masonry-v1']
                    ],
                    [
                        'name' => 'Services',
                        'slug' => 'services',
                        'link' => '/services',
                        'description' => 'Comprehensive overview of our professional services',
                        'keywords' => 'services, solutions, offerings, professional',
                        'show_in_nav' => true,
                        'sections' => ['services-grid-v1']
                    ],
                    [
                        'name' => 'Contact',
                        'slug' => 'contact',
                        'link' => '/contact',
                        'description' => 'Get in touch with our team for inquiries and support',
                        'keywords' => 'contact, support, inquiries, communication',
                        'show_in_nav' => true,
                        'sections' => ['contact-creative-v1']
                    ]
                ];

            default:
                return [
                    [
                        'name' => 'Home',
                        'slug' => 'home',
                        'link' => '/',
                        'description' => 'Welcome to our homepage showcasing our services and expertise',
                        'keywords' => 'home, welcome, services, company, business',
                        'show_in_nav' => true,
                        'sections' => ['hero-business-v1', 'services-grid-v1', 'about-corporate-v1']
                    ],
                    [
                        'name' => 'About',
                        'slug' => 'about',
                        'link' => '/about',
                        'description' => 'Learn more about our company, team, and mission',
                        'keywords' => 'about, company, team, mission, history',
                        'show_in_nav' => true,
                        'sections' => ['about-corporate-v1']
                    ],
                    [
                        'name' => 'Services',
                        'slug' => 'services',
                        'link' => '/services',
                        'description' => 'Comprehensive overview of our professional services',
                        'keywords' => 'services, solutions, offerings, professional',
                        'show_in_nav' => true,
                        'sections' => ['services-grid-v1']
                    ],
                    [
                        'name' => 'Contact',
                        'slug' => 'contact',
                        'link' => '/contact',
                        'description' => 'Get in touch with our team for inquiries and support',
                        'keywords' => 'contact, support, inquiries, communication',
                        'show_in_nav' => true,
                        'sections' => ['contact-modern-v1']
                    ]
                ];
        }
    }

    private function getThemeBysite(Site $site): string
    {
        if (str_contains($site->url, 'business')) {
            return 'business';
        } elseif (str_contains($site->url, 'portfolio')) {
            return 'portfolio';
        }
        return 'corporate';
    }

    private function getPrimaryColorByTheme(string $theme): string
    {
        $colors = [
            'corporate' => '#667eea',
            'business' => '#4facfe',
            'portfolio' => '#764ba2',
            'agency' => '#43e97b'
        ];
        return $colors[$theme] ?? '#667eea';
    }

    private function getSecondaryColorByTheme(string $theme): string
    {
        $colors = [
            'corporate' => '#764ba2',
            'business' => '#00f2fe',
            'portfolio' => '#f093fb',
            'agency' => '#38f9d7'
        ];
        return $colors[$theme] ?? '#764ba2';
    }

    private function getAccentColorByTheme(string $theme): string
    {
        $colors = [
            'corporate' => '#f093fb',
            'business' => '#667eea',
            'portfolio' => '#4facfe',
            'agency' => '#764ba2'
        ];
        return $colors[$theme] ?? '#f093fb';
    }

    private function getColorSchemeByTheme(string $theme): string
    {
        $schemes = [
            'corporate' => 'professional',
            'business' => 'business',
            'portfolio' => 'creative',
            'agency' => 'modern'
        ];
        return $schemes[$theme] ?? 'professional';
    }

    private function getNavigationLinksByTheme(string $theme): array
    {
        $baseLinks = [
            ['label' => 'Home', 'url' => '/', 'target' => '_self', 'active' => true],
            ['label' => 'About', 'url' => '/about', 'target' => '_self', 'active' => true],
            ['label' => 'Services', 'url' => '/services', 'target' => '_self', 'active' => true],
            ['label' => 'Contact', 'url' => '/contact', 'target' => '_self', 'active' => true]
        ];

        if ($theme === 'portfolio') {
            array_splice($baseLinks, 3, 0, [
                ['label' => 'Portfolio', 'url' => '/portfolio', 'target' => '_self', 'active' => true]
            ]);
        }

        return $baseLinks;
    }

    private function getNavigationDataByTheme(string $theme): array
    {
        return [
            'links' => $this->getNavigationLinksByTheme($theme),
            'style' => $theme === 'portfolio' ? 'creative' : 'modern',
            'show_search' => true,
            'show_cta' => true,
            'cta_text' => $theme === 'portfolio' ? 'View Work' : 'Get Started',
            'cta_url' => $theme === 'portfolio' ? '/portfolio' : '/contact'
        ];
    }

    private function getFooterLinksByTheme(string $theme): array
    {
        return [
            ['label' => 'Privacy Policy', 'url' => '/privacy', 'target' => '_self', 'active' => true],
            ['label' => 'Terms of Service', 'url' => '/terms', 'target' => '_self', 'active' => true],
            ['label' => 'Cookie Policy', 'url' => '/cookies', 'target' => '_self', 'active' => true],
            ['label' => 'Sitemap', 'url' => '/sitemap', 'target' => '_self', 'active' => true]
        ];
    }

    private function getFooterDataByTheme(string $theme): array
    {
        return [
            'links' => $this->getFooterLinksByTheme($theme),
            'social_media' => [
                'facebook' => 'https://facebook.com/company',
                'twitter' => 'https://twitter.com/company',
                'instagram' => 'https://instagram.com/company',
                'linkedin' => 'https://linkedin.com/company/company',
                'youtube' => 'https://youtube.com/company'
            ],
            'newsletter' => [
                'enabled' => true,
                'title' => $theme === 'portfolio' ? 'Stay Inspired' : 'Stay Updated',
                'description' => $theme === 'portfolio' ? 'Get creative updates and inspiration.' : 'Get the latest updates and offers.'
            ],
            'contact_info' => [
                'email' => 'contact@company.com',
                'phone' => '+1 (555) 123-4567',
                'address' => '123 Business Street, City, State 12345'
            ]
        ];
    }

    // Placeholder methods for template content (would contain actual HTML templates)
    private function getModernHeaderTemplate(): string { return '<header>Modern Header Template</header>'; }
    private function getCreativeHeaderTemplate(): string { return '<header>Creative Header Template</header>'; }
    private function getModernFooterTemplate(): string { return '<footer>Modern Footer Template</footer>'; }
    private function getMinimalFooterTemplate(): string { return '<footer>Minimal Footer Template</footer>'; }
    private function getBusinessHeroTemplate(): string { return '<section>Business Hero Template</section>'; }
    private function getPortfolioHeroTemplate(): string { return '<section>Portfolio Hero Template</section>'; }
    private function getAgencyHeroTemplate(): string { return '<section>Agency Hero Template</section>'; }
    private function getServicesGridTemplate(): string { return '<section>Services Grid Template</section>'; }
    private function getServicesCarouselTemplate(): string { return '<section>Services Carousel Template</section>'; }
    private function getCorporateAboutTemplate(): string { return '<section>Corporate About Template</section>'; }
    private function getCreativeAboutTemplate(): string { return '<section>Creative About Template</section>'; }
    private function getPortfolioGridTemplate(): string { return '<section>Portfolio Grid Template</section>'; }
    private function getPortfolioMasonryTemplate(): string { return '<section>Portfolio Masonry Template</section>'; }
    private function getModernContactTemplate(): string { return '<section>Modern Contact Template</section>'; }
    private function getCreativeContactTemplate(): string { return '<section>Creative Contact Template</section>'; }
    private function getTestimonialsSliderTemplate(): string { return '<section>Testimonials Slider Template</section>'; }
    private function getTestimonialsGridTemplate(): string { return '<section>Testimonials Grid Template</section>'; }
    private function getCTABannerTemplate(): string { return '<section>CTA Banner Template</section>'; }
    private function getCTASplitTemplate(): string { return '<section>CTA Split Template</section>'; }

    // Placeholder methods for configurable fields
    private function getHeaderConfigurableFields(): array { return ['site_name' => ['type' => 'text', 'default' => 'Company Name']]; }
    private function getFooterConfigurableFields(): array { return ['copyright' => ['type' => 'text', 'default' => '© 2025 Company Name']]; }
    private function getHeroConfigurableFields(): array { return ['headline' => ['type' => 'text', 'default' => 'Welcome']]; }
    private function getServicesConfigurableFields(): array { return ['title' => ['type' => 'text', 'default' => 'Our Services']]; }
    private function getAboutConfigurableFields(): array { return ['title' => ['type' => 'text', 'default' => 'About Us']]; }
    private function getPortfolioConfigurableFields(): array { return ['title' => ['type' => 'text', 'default' => 'Our Work']]; }
    private function getContactConfigurableFields(): array { return ['title' => ['type' => 'text', 'default' => 'Contact Us']]; }
    private function getTestimonialsConfigurableFields(): array { return ['title' => ['type' => 'text', 'default' => 'Testimonials']]; }
    private function getCTAConfigurableFields(): array { return ['title' => ['type' => 'text', 'default' => 'Get Started']]; }

    // Placeholder methods for default configs
    private function getModernHeaderConfig(): array { return ['site_name' => 'Modern Company']; }
    private function getCreativeHeaderConfig(): array { return ['site_name' => 'Creative Studio']; }
    private function getModernFooterConfig(): array { return ['copyright' => '© 2025 Modern Company']; }
    private function getMinimalFooterConfig(): array { return ['copyright' => '© 2025 Creative Studio']; }
    private function getBusinessHeroConfig(): array { return ['headline' => 'Business Success Starts Here']; }
    private function getPortfolioHeroConfig(): array { return ['headline' => 'Creative Solutions & Design']; }
    private function getAgencyHeroConfig(): array { return ['headline' => 'Digital Agency Excellence']; }
    private function getServicesGridConfig(): array { return ['title' => 'Our Premium Services']; }
    private function getServicesCarouselConfig(): array { return ['title' => 'What We Offer']; }
    private function getCorporateAboutConfig(): array { return ['title' => 'Leading Business Solutions']; }
    private function getCreativeAboutConfig(): array { return ['title' => 'Creative Excellence']; }
    private function getPortfolioGridConfig(): array { return ['title' => 'Featured Projects']; }
    private function getPortfolioMasonryConfig(): array { return ['title' => 'Our Creative Work']; }
    private function getModernContactConfig(): array { return ['title' => 'Get In Touch']; }
    private function getCreativeContactConfig(): array { return ['title' => 'Start Your Project']; }
    private function getTestimonialsSliderConfig(): array { return ['title' => 'What Clients Say']; }
    private function getTestimonialsGridConfig(): array { return ['title' => 'Client Success Stories']; }
    private function getCTABannerConfig(): array { return ['title' => 'Ready to Get Started?']; }
    private function getCTASplitConfig(): array { return ['title' => 'Transform Your Business']; }
}
