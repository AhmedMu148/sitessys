<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Site;
use App\Models\TplLayout;
use App\Models\TplPage;
use App\Models\TplPageSection;
use App\Models\TplSite;
use App\Services\ConfigurationService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class CleanSetupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        echo "🚀 Setting up clean SPS system...\n";
        
        // Step 1: Create default templates
        echo "📋 Creating default SEO templates...\n";
        $this->createDefaultTemplates();
        
        // Step 2: Create admin user with site and templates
        echo "👨‍💼 Creating admin user with default site...\n";
        $admin = $this->createAdminUser();
        $adminSite = $this->createAdminSite($admin);
        $this->assignTemplatesToSite($adminSite);
        
        // Step 3: Create regular user
        echo "👤 Creating regular user...\n";
        $this->createRegularUser();
        
        echo "✅ Clean setup completed successfully!\n\n";
        echo "📧 Admin Login: admin@seo.com / seopass123\n";
        echo "🔗 Admin Panel: http://localhost:8000/admin?site_id={$adminSite->id}\n";
        echo "📧 User Login: user@example.com / userpass123\n";
        echo "🌐 Site URL: http://localhost:8000\n";
    }

    /**
     * Create default SEO templates
     */
    private function createDefaultTemplates(): void
    {
        $templates = [
            // Header Template
            [
                'tpl_id' => 'default-seo-header',
                'layout_type' => 'header',
                'name' => 'Default SEO Header',
                'content' => $this->getHeaderTemplate(),
                'configurable_fields' => json_encode([
                    'site_name' => ['type' => 'text', 'default' => 'SEO Business'],
                    'logo_url' => ['type' => 'url', 'default' => '/img/logo.png'],
                    'show_search' => ['type' => 'boolean', 'default' => true],
                    'search_placeholder' => ['type' => 'text', 'default' => 'Search...'],
                    'menu_items' => [
                        'type' => 'array',
                        'default' => [
                            ['label' => 'Home', 'url' => '/'],
                            ['label' => 'About', 'url' => '/about'],
                            ['label' => 'Services', 'url' => '/services'],
                            ['label' => 'Portfolio', 'url' => '/portfolio'],
                            ['label' => 'Blog', 'url' => '/blog'],
                            ['label' => 'Contact', 'url' => '/contact']
                        ]
                    ],
                    'show_cta' => ['type' => 'boolean', 'default' => true],
                    'cta_text' => ['type' => 'text', 'default' => 'Get Free Audit'],
                    'cta_url' => ['type' => 'url', 'default' => '/contact'],
                    'login_text' => ['type' => 'text', 'default' => 'Login'],
                    'login_url' => ['type' => 'url', 'default' => '/login'],
                    'show_register' => ['type' => 'boolean', 'default' => true],
                    'register_text' => ['type' => 'text', 'default' => 'Sign Up'],
                    'register_url' => ['type' => 'url', 'default' => '/register'],
                    'profile_text' => ['type' => 'text', 'default' => 'My Profile'],
                    'profile_url' => ['type' => 'url', 'default' => '/profile'],
                    'dashboard_text' => ['type' => 'text', 'default' => 'Dashboard'],
                    'dashboard_url' => ['type' => 'url', 'default' => '/dashboard'],
                    'settings_text' => ['type' => 'text', 'default' => 'Settings'],
                    'settings_url' => ['type' => 'url', 'default' => '/settings'],
                    'logout_text' => ['type' => 'text', 'default' => 'Logout']
                ]),
                'default_config' => json_encode([
                    'site_name' => 'SEO Business',
                    'logo_url' => '/img/logo.png',
                    'show_search' => true,
                    'search_placeholder' => 'Search...',
                    'menu_items' => [
                        ['label' => 'Home', 'url' => '/'],
                        ['label' => 'About', 'url' => '/about'],
                        ['label' => 'Services', 'url' => '/services'],
                        ['label' => 'Portfolio', 'url' => '/portfolio'],
                        ['label' => 'Blog', 'url' => '/blog'],
                        ['label' => 'Contact', 'url' => '/contact']
                    ],
                    'show_cta' => true,
                    'cta_text' => 'Get Free Audit',
                    'cta_url' => '/contact',
                    'login_text' => 'Login',
                    'login_url' => '/login',
                    'show_register' => true,
                    'register_text' => 'Sign Up',
                    'register_url' => '/register',
                    'profile_text' => 'My Profile',
                    'profile_url' => '/profile',
                    'dashboard_text' => 'Dashboard',
                    'dashboard_url' => '/dashboard',
                    'settings_text' => 'Settings',
                    'settings_url' => '/settings',
                    'logout_text' => 'Logout'
                ])
            ],

            // Footer Template
            [
                'tpl_id' => 'default-seo-footer',
                'layout_type' => 'footer',
                'name' => 'Default SEO Footer',
                'content' => $this->getFooterTemplate(),
                'configurable_fields' => json_encode([
                    'site_name' => ['type' => 'text', 'default' => 'SEO Business'],
                    'logo_url' => ['type' => 'url', 'default' => '/img/logo.png'],
                    'description' => ['type' => 'textarea', 'default' => 'Professional SEO services to grow your business online. We help companies achieve top rankings and increase organic traffic with proven strategies.'],
                    'contact_info' => [
                        'type' => 'object',
                        'default' => [
                            'email' => 'contact@seobusiness.com',
                            'phone' => '+1 (555) 123-4567',
                            'address' => '123 SEO Street, Digital City, DC 12345'
                        ]
                    ],
                    'business_hours' => ['type' => 'text', 'default' => 'Mon-Fri: 9AM-6PM'],
                    'footer_links' => [
                        'type' => 'array',
                        'default' => [
                            ['label' => 'Home', 'url' => '/'],
                            ['label' => 'About Us', 'url' => '/about'],
                            ['label' => 'Services', 'url' => '/services'],
                            ['label' => 'Portfolio', 'url' => '/portfolio'],
                            ['label' => 'Blog', 'url' => '/blog'],
                            ['label' => 'Contact', 'url' => '/contact']
                        ]
                    ],
                    'services_links' => [
                        'type' => 'array',
                        'default' => [
                            ['label' => 'SEO Audit', 'url' => '/services/seo-audit'],
                            ['label' => 'Keyword Research', 'url' => '/services/keyword-research'],
                            ['label' => 'Link Building', 'url' => '/services/link-building'],
                            ['label' => 'Local SEO', 'url' => '/services/local-seo'],
                            ['label' => 'Content Marketing', 'url' => '/services/content-marketing'],
                            ['label' => 'Technical SEO', 'url' => '/services/technical-seo']
                        ]
                    ],
                    'additional_pages' => [
                        'type' => 'array',
                        'default' => [
                            ['label' => 'Case Studies', 'url' => '/case-studies'],
                            ['label' => 'Resources', 'url' => '/resources'],
                            ['label' => 'FAQ', 'url' => '/faq'],
                            ['label' => 'Careers', 'url' => '/careers'],
                            ['label' => 'Partners', 'url' => '/partners']
                        ]
                    ],
                    'additional_section_title' => ['type' => 'text', 'default' => 'More Information'],
                    'social_links' => [
                        'type' => 'array',
                        'default' => [
                            ['icon' => 'fab fa-facebook', 'url' => 'https://facebook.com/seobusiness'],
                            ['icon' => 'fab fa-twitter', 'url' => 'https://twitter.com/seobusiness'],
                            ['icon' => 'fab fa-linkedin', 'url' => 'https://linkedin.com/company/seobusiness'],
                            ['icon' => 'fab fa-instagram', 'url' => 'https://instagram.com/seobusiness'],
                            ['icon' => 'fab fa-youtube', 'url' => 'https://youtube.com/seobusiness'],
                            ['icon' => 'fab fa-tiktok', 'url' => 'https://tiktok.com/@seobusiness']
                        ]
                    ],
                    'show_newsletter' => ['type' => 'boolean', 'default' => true],
                    'newsletter_title' => ['type' => 'text', 'default' => 'Newsletter'],
                    'newsletter_description' => ['type' => 'text', 'default' => 'Get SEO tips and updates'],
                    'newsletter_placeholder' => ['type' => 'text', 'default' => 'Enter your email'],
                    'legal_links' => [
                        'type' => 'array',
                        'default' => [
                            ['label' => 'Privacy Policy', 'url' => '/privacy'],
                            ['label' => 'Terms of Service', 'url' => '/terms'],
                            ['label' => 'Cookie Policy', 'url' => '/cookies'],
                            ['label' => 'Sitemap', 'url' => '/sitemap']
                        ]
                    ],
                    'copyright_text' => ['type' => 'text', 'default' => 'All rights reserved.'],
                    'year' => ['type' => 'text', 'default' => '2025'],
                    'show_back_to_top' => ['type' => 'boolean', 'default' => true]
                ]),
                'default_config' => json_encode([
                    'site_name' => 'SEO Business',
                    'logo_url' => '/img/logo.png',
                    'description' => 'Professional SEO services to grow your business online. We help companies achieve top rankings and increase organic traffic with proven strategies.',
                    'contact_info' => [
                        'email' => 'contact@seobusiness.com',
                        'phone' => '+1 (555) 123-4567',
                        'address' => '123 SEO Street, Digital City, DC 12345'
                    ],
                    'business_hours' => 'Mon-Fri: 9AM-6PM',
                    'show_newsletter' => true,
                    'newsletter_title' => 'Newsletter',
                    'newsletter_description' => 'Get SEO tips and updates',
                    'newsletter_placeholder' => 'Enter your email',
                    'additional_section_title' => 'More Information',
                    'copyright_text' => 'All rights reserved.',
                    'year' => '2025',
                    'show_back_to_top' => true
                ])
            ],

            // Hero Section
            [
                'tpl_id' => 'default-seo-hero',
                'layout_type' => 'section',
                'name' => 'Default SEO Hero Section',
                'content' => $this->getHeroTemplate(),
                'configurable_fields' => json_encode([
                    'headline' => ['type' => 'text', 'default' => 'Dominate Search Results with Expert SEO'],
                    'subheadline' => ['type' => 'textarea', 'default' => 'Increase your organic traffic and grow your business with our proven SEO strategies that deliver real results.'],
                    'cta_text' => ['type' => 'text', 'default' => 'Get Free SEO Audit'],
                    'cta_url' => ['type' => 'url', 'default' => '/contact'],
                    'secondary_cta_text' => ['type' => 'text', 'default' => 'View Our Services'],
                    'secondary_cta_url' => ['type' => 'url', 'default' => '#services'],
                    'hero_image' => ['type' => 'url', 'default' => '/img/seo-hero-image.jpg'],
                    'clients_count' => ['type' => 'text', 'default' => '500+'],
                    'rankings_improved' => ['type' => 'text', 'default' => '95%'],
                    'traffic_increase' => ['type' => 'text', 'default' => '300%']
                ]),
                'default_config' => json_encode([
                    'headline' => 'Dominate Search Results with Expert SEO',
                    'subheadline' => 'Increase your organic traffic and grow your business with our proven SEO strategies that deliver real results.',
                    'cta_text' => 'Get Free SEO Audit',
                    'cta_url' => '/contact',
                    'secondary_cta_text' => 'View Our Services',
                    'secondary_cta_url' => '#services',
                    'hero_image' => '/img/seo-hero-image.jpg',
                    'clients_count' => '500+',
                    'rankings_improved' => '95%',
                    'traffic_increase' => '300%'
                ])
            ],

            // Services Section
            [
                'tpl_id' => 'default-seo-services',
                'layout_type' => 'section',
                'name' => 'Default SEO Services Section',
                'content' => $this->getServicesTemplate(),
                'configurable_fields' => json_encode([
                    'title' => ['type' => 'text', 'default' => 'Our Premium SEO Services'],
                    'subtitle' => ['type' => 'textarea', 'default' => 'Comprehensive SEO solutions designed to boost your rankings, increase traffic, and grow your business online.'],
                    'cta_title' => ['type' => 'text', 'default' => 'Ready to Dominate Search Results?'],
                    'cta_description' => ['type' => 'textarea', 'default' => 'Get a free SEO audit and discover how we can help your business grow.'],
                    'cta_text' => ['type' => 'text', 'default' => 'Get Free Audit Now'],
                    'cta_url' => ['type' => 'url', 'default' => '/contact'],
                    'services' => [
                        'type' => 'array',
                        'default' => [
                            [
                                'name' => 'Technical SEO',
                                'description' => 'Optimize your website\'s technical foundation for better search engine visibility.',
                                'icon' => 'fas fa-cogs',
                                'features' => ['Site Speed Optimization', 'Mobile Responsiveness', 'Schema Markup'],
                                'price' => 'Starting at $299/mo',
                                'link' => '/services/technical-seo'
                            ],
                            [
                                'name' => 'Content Optimization',
                                'description' => 'Create and optimize high-quality content that ranks and converts.',
                                'icon' => 'fas fa-edit',
                                'features' => ['Keyword Research', 'Content Strategy', 'On-Page SEO'],
                                'price' => 'Starting at $399/mo',
                                'link' => '/services/content-optimization'
                            ],
                            [
                                'name' => 'Link Building',
                                'description' => 'Build high-quality backlinks to improve your domain authority and rankings.',
                                'icon' => 'fas fa-link',
                                'features' => ['Guest Posting', 'Digital PR', 'Broken Link Building'],
                                'price' => 'Starting at $499/mo',
                                'link' => '/services/link-building'
                            ]
                        ]
                    ]
                ]),
                'default_config' => json_encode([
                    'title' => 'Our Premium SEO Services',
                    'subtitle' => 'Comprehensive SEO solutions designed to boost your rankings, increase traffic, and grow your business online.',
                    'cta_title' => 'Ready to Dominate Search Results?',
                    'cta_description' => 'Get a free SEO audit and discover how we can help your business grow.',
                    'cta_text' => 'Get Free Audit Now',
                    'cta_url' => '/contact'
                ])
            ],

            // About Section
            [
                'tpl_id' => 'default-seo-about',
                'layout_type' => 'section',
                'name' => 'Default SEO About Section',
                'content' => $this->getAboutTemplate(),
                'configurable_fields' => json_encode([
                    'badge_text' => ['type' => 'text', 'default' => 'About Us'],
                    'title' => ['type' => 'text', 'default' => 'Leading SEO Agency with Proven Results'],
                    'description' => ['type' => 'richtext', 'default' => 'We are a team of SEO experts dedicated to helping businesses grow their online presence and achieve their digital marketing goals through data-driven strategies.'],
                    'cta_text' => ['type' => 'text', 'default' => 'Start Your Project'],
                    'cta_url' => ['type' => 'url', 'default' => '/contact'],
                    'secondary_cta_text' => ['type' => 'text', 'default' => 'View Portfolio'],
                    'secondary_cta_url' => ['type' => 'url', 'default' => '/portfolio'],
                    'about_image' => ['type' => 'url', 'default' => '/img/about-us.jpg'],
                    'years_experience' => ['type' => 'text', 'default' => '10'],
                    'features' => [
                        'type' => 'array',
                        'default' => [
                            [
                                'title' => 'Data-Driven Approach',
                                'description' => 'Every strategy is backed by comprehensive data analysis',
                                'icon' => 'fas fa-chart-bar'
                            ],
                            [
                                'title' => 'Transparent Reporting',
                                'description' => 'Monthly detailed reports showing your progress',
                                'icon' => 'fas fa-file-chart-line'
                            ],
                            [
                                'title' => '24/7 Support',
                                'description' => 'Round-the-clock support for all your SEO needs',
                                'icon' => 'fas fa-headset'
                            ]
                        ]
                    ],
                    'stats' => [
                        'type' => 'array',
                        'default' => [
                            ['label' => 'Happy Clients', 'value' => '500', 'icon' => 'fas fa-users'],
                            ['label' => 'Projects Done', 'value' => '1200', 'icon' => 'fas fa-project-diagram'],
                            ['label' => 'Success Rate', 'value' => '95', 'icon' => 'fas fa-chart-line'],
                            ['label' => 'Team Members', 'value' => '25', 'icon' => 'fas fa-user-tie']
                        ]
                    ],
                    'team_title' => ['type' => 'text', 'default' => 'Meet Our Expert Team'],
                    'team_subtitle' => ['type' => 'textarea', 'default' => 'Our experienced professionals are dedicated to your success'],
                    'team_members' => [
                        'type' => 'array',
                        'default' => [
                            [
                                'name' => 'John Smith',
                                'position' => 'SEO Director',
                                'bio' => 'Over 8 years of SEO experience',
                                'image' => '/img/team/john.jpg',
                                'social' => [
                                    ['icon' => 'fab fa-linkedin', 'url' => '#'],
                                    ['icon' => 'fab fa-twitter', 'url' => '#']
                                ]
                            ],
                            [
                                'name' => 'Sarah Johnson',
                                'position' => 'Content Strategist',
                                'bio' => 'Expert in content marketing and SEO',
                                'image' => '/img/team/sarah.jpg',
                                'social' => [
                                    ['icon' => 'fab fa-linkedin', 'url' => '#'],
                                    ['icon' => 'fab fa-twitter', 'url' => '#']
                                ]
                            ]
                        ]
                    ]
                ]),
                'default_config' => json_encode([
                    'badge_text' => 'About Us',
                    'title' => 'Leading SEO Agency with Proven Results',
                    'description' => 'We are a team of SEO experts dedicated to helping businesses grow their online presence and achieve their digital marketing goals through data-driven strategies.',
                    'cta_text' => 'Start Your Project',
                    'cta_url' => '/contact',
                    'secondary_cta_text' => 'View Portfolio',
                    'secondary_cta_url' => '/portfolio',
                    'years_experience' => '10'
                ])
            ],

            // Contact Section
            [
                'tpl_id' => 'default-seo-contact',
                'layout_type' => 'section',
                'name' => 'Default SEO Contact Section',
                'content' => $this->getContactTemplate(),
                'configurable_fields' => json_encode([
                    'badge_text' => ['type' => 'text', 'default' => 'Contact Us'],
                    'title' => ['type' => 'text', 'default' => 'Get Your Free SEO Consultation'],
                    'subtitle' => ['type' => 'textarea', 'default' => 'Ready to boost your online presence? Contact us today for a comprehensive SEO audit and strategy consultation.'],
                    'form_title' => ['type' => 'text', 'default' => 'Send us a Message'],
                    'form_submit_text' => ['type' => 'text', 'default' => 'Send Message'],
                    'contact_info_title' => ['type' => 'text', 'default' => 'Get in Touch'],
                    'contact_info' => [
                        'type' => 'object',
                        'default' => [
                            'email' => 'contact@seobusiness.com',
                            'phone' => '+1 (555) 123-4567',
                            'address' => '123 SEO Street, Digital City, DC 12345'
                        ]
                    ],
                    'business_hours' => [
                        'type' => 'object',
                        'default' => [
                            'weekdays' => 'Mon - Fri: 9:00 AM - 6:00 PM',
                            'weekend' => 'Sat - Sun: 10:00 AM - 4:00 PM'
                        ]
                    ],
                    'social_links' => [
                        'type' => 'array',
                        'default' => [
                            ['icon' => 'fab fa-facebook', 'url' => '#'],
                            ['icon' => 'fab fa-twitter', 'url' => '#'],
                            ['icon' => 'fab fa-linkedin', 'url' => '#'],
                            ['icon' => 'fab fa-instagram', 'url' => '#']
                        ]
                    ],
                    'quick_cta_title' => ['type' => 'text', 'default' => 'Need Urgent Help?'],
                    'quick_cta_description' => ['type' => 'textarea', 'default' => 'Call us directly for immediate assistance with your SEO needs.'],
                    'quick_cta_text' => ['type' => 'text', 'default' => 'Call Now'],
                    'quick_cta_url' => ['type' => 'url', 'default' => 'tel:+15551234567'],
                    'show_map' => ['type' => 'boolean', 'default' => true],
                    'map_title' => ['type' => 'text', 'default' => 'Find Our Office'],
                    'map_embed_url' => ['type' => 'url', 'default' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3024.1!2d-74.0!3d40.7!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zNDDCsDQyJzAwLjAiTiA3NMKwMDAnMDAuMCJX!5e0!3m2!1sen!2sus!4v1234567890123!5m2!1sen!2sus']
                ]),
                'default_config' => json_encode([
                    'badge_text' => 'Contact Us',
                    'title' => 'Get Your Free SEO Consultation',
                    'subtitle' => 'Ready to boost your online presence? Contact us today for a comprehensive SEO audit and strategy consultation.',
                    'form_title' => 'Send us a Message',
                    'form_submit_text' => 'Send Message',
                    'contact_info_title' => 'Get in Touch',
                    'quick_cta_title' => 'Need Urgent Help?',
                    'quick_cta_description' => 'Call us directly for immediate assistance with your SEO needs.',
                    'quick_cta_text' => 'Call Now',
                    'quick_cta_url' => 'tel:+15551234567',
                    'show_map' => true,
                    'map_title' => 'Find Our Office'
                ])
            ]
        ];

        foreach ($templates as $template) {
            TplLayout::create([
                'tpl_id' => $template['tpl_id'],
                'layout_type' => $template['layout_type'],
                'name' => $template['name'],
                'content' => $template['content'],
                'configurable_fields' => $template['configurable_fields'],
                'default_config' => $template['default_config'],
                'path' => 'templates/' . $template['layout_type'] . 's/' . $template['tpl_id'],
                'status' => true,
                'sort_order' => 0
            ]);
            
            echo "  ✓ {$template['name']}\n";
        }
    }

    /**
     * Create admin user
     */
    private function createAdminUser(): User
    {
        return User::create([
            'name' => 'SEO Admin',
            'email' => 'admin@seo.com',
            'password' => Hash::make('seopass123'),
            'role' => 'admin',
            'status_id' => true,
            'preferred_language' => 'en'
        ]);
    }

    /**
     * Create admin site with development domains
     */
    private function createAdminSite(User $admin): Site
    {
        $site = Site::create([
            'user_id' => $admin->id,
            'site_name' => 'SEO Business',
            'url' => 'http://localhost:8000',
            'status_id' => true,
            'active_header_id' => TplLayout::where('tpl_id', 'default-seo-header')->first()->id,
            'active_footer_id' => TplLayout::where('tpl_id', 'default-seo-footer')->first()->id
        ]);

        // Set development domains
        $domains = [
            'localhost',
            '127.0.0.1:8000',
            'phplaravel-1399496-5687062.cloudwaysapps.com'
        ];
        $subdomains = ['seo', 'admin', 'dev'];
        
        $site->setDomainData($domains, $subdomains);

        // Initialize default configurations
        $configService = app(ConfigurationService::class);
        $configService->initializeDefaults($site->id);

        return $site;
    }

    /**
     * Create pages and assign templates to site
     */
    private function assignTemplatesToSite(Site $site): void
    {
        $pages = [
            [
                'name' => 'Home',
                'slug' => 'home',
                'link' => '/',
                'sections' => ['default-seo-hero', 'default-seo-services', 'default-seo-about']
            ],
            [
                'name' => 'About',
                'slug' => 'about',
                'link' => '/about',
                'sections' => ['default-seo-about']
            ],
            [
                'name' => 'Services',
                'slug' => 'services',
                'link' => '/services',
                'sections' => ['default-seo-services']
            ],
            [
                'name' => 'Portfolio',
                'slug' => 'portfolio',
                'link' => '/portfolio',
                'sections' => ['default-seo-about']
            ],
            [
                'name' => 'Blog',
                'slug' => 'blog',
                'link' => '/blog',
                'sections' => ['default-seo-services']
            ],
            [
                'name' => 'Contact',
                'slug' => 'contact',
                'link' => '/contact',
                'sections' => ['default-seo-contact']
            ],
            [
                'name' => 'Case Studies',
                'slug' => 'case-studies',
                'link' => '/case-studies',
                'sections' => ['default-seo-about']
            ],
            [
                'name' => 'Resources',
                'slug' => 'resources',
                'link' => '/resources',
                'sections' => ['default-seo-services']
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
                        'name' => $template->name . ' on ' . $page->name,
                        'content' => $template->default_config ?: '{}',
                        'status' => 1,
                        'sort_order' => $sortOrder++
                    ]);
                }
            }
            
            echo "  ✓ Created page: {$page->name} with " . count($pageData['sections']) . " sections\n";
        }

        // Create TplSite with navigation and footer data
        $headerLayout = TplLayout::where('tpl_id', 'default-seo-header')->first();
        $footerLayout = TplLayout::where('tpl_id', 'default-seo-footer')->first();
        
        $tplSite = TplSite::create([
            'site_id' => $site->id,
            'nav' => $headerLayout ? $headerLayout->id : null,
            'footer' => $footerLayout ? $footerLayout->id : null,
            'nav_data' => [
                'links' => [
                    ['name' => 'Home', 'url' => '/', 'active' => true],
                    ['name' => 'About', 'url' => '/about', 'active' => true],
                    ['name' => 'Services', 'url' => '/services', 'active' => true],
                    ['name' => 'Portfolio', 'url' => '/portfolio', 'active' => true],
                    ['name' => 'Blog', 'url' => '/blog', 'active' => true],
                    ['name' => 'Contact', 'url' => '/contact', 'active' => true]
                ]
            ],
            'footer_data' => [
                'links' => [
                    ['name' => 'Case Studies', 'url' => '/case-studies', 'active' => true],
                    ['name' => 'Resources', 'url' => '/resources', 'active' => true]
                ],
                'social_media' => [
                    'facebook' => '#',
                    'twitter' => '#',
                    'instagram' => '#',
                    'linkedin' => '#',
                    'youtube' => '#'
                ],
                'newsletter' => [
                    'enabled' => true,
                    'title' => 'Subscribe to Our Newsletter',
                    'description' => 'Get the latest updates and offers.'
                ]
            ]
        ]);

        echo "  ✓ Created TplSite configuration with navigation and footer data\n";
    }

    /**
     * Create regular user
     */
    private function createRegularUser(): User
    {
        return User::create([
            'name' => 'Regular User',
            'email' => 'user@example.com',
            'password' => Hash::make('userpass123'),
            'role' => 'user',
            'status_id' => true,
            'preferred_language' => 'en'
        ]);
    }

    // Template HTML methods
    private function getHeaderTemplate(): string
    {
        return '<header class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top" id="mainNavbar">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center fw-bold" href="/">
            <span class="text-primary brand-text">{{site_name}}</span>
        </a>
        
        <button class="navbar-toggler border-0 shadow-sm" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon-custom">
                <i class="fas fa-bars text-primary"></i>
            </span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto align-items-center">
                <li class="nav-item">
                    <a class="nav-link px-3 py-2 rounded-pill fw-medium nav-hover" href="/">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3 py-2 rounded-pill fw-medium nav-hover" href="/about">About</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3 py-2 rounded-pill fw-medium nav-hover" href="/services">Services</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3 py-2 rounded-pill fw-medium nav-hover" href="/portfolio">Portfolio</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3 py-2 rounded-pill fw-medium nav-hover" href="/blog">Blog</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3 py-2 rounded-pill fw-medium nav-hover" href="/contact">Contact</a>
                </li>
            </ul>
            
            <div class="d-flex align-items-center gap-3 navbar-actions">
                <!-- Authentication Section -->
                <div class="auth-section">
                    <div class="d-flex gap-2 auth-buttons">
                        <a href="{{login_url}}" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                            <i class="fas fa-sign-in-alt me-1"></i>{{login_text}}
                        </a>
                        <a href="{{register_url}}" class="btn btn-primary btn-sm rounded-pill px-3">
                            <i class="fas fa-user-plus me-1"></i>{{register_text}}
                        </a>
                    </div>
                </div>
                
                <a href="{{cta_url}}" class="btn btn-gradient btn-sm rounded-pill px-4 fw-medium ms-2 cta-button">
                    {{cta_text}}
                    <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>
</header>

<style>
    /* Enhanced Navigation Styles */
    #mainNavbar {
        backdrop-filter: blur(20px);
        background: rgba(255, 255, 255, 0.95) !important;
        border-bottom: 1px solid rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        z-index: 1050;
        padding: 1rem 0;
    }
    
    #mainNavbar.scrolled {
        background: rgba(255, 255, 255, 0.98) !important;
        box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
        padding: 0.5rem 0;
    }
    
    .brand-text {
        font-size: 1.5rem;
        font-weight: 700;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        transition: all 0.3s ease;
    }
    
    .navbar-brand:hover .brand-text {
        background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .nav-hover {
        position: relative;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border-radius: 25px;
        font-weight: 500;
    }
    
    .nav-hover:hover {
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1));
        color: #667eea !important;
        transform: translateY(-2px);
    }
    
    .nav-hover::before {
        content: "";
        position: absolute;
        bottom: -2px;
        left: 50%;
        transform: translateX(-50%);
        width: 0;
        height: 3px;
        background: linear-gradient(90deg, #667eea, #764ba2);
        border-radius: 2px;
        transition: width 0.3s ease;
    }
    
    .nav-hover:hover::before {
        width: 70%;
    }
    
    .btn-gradient {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        color: white;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .btn-gradient::before {
        content: "";
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s ease;
    }
    
    .btn-gradient:hover::before {
        left: 100%;
    }
    
    .btn-gradient:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 25px rgba(102, 126, 234, 0.4);
        color: white;
    }
    
    .cta-button {
        animation: pulse-glow 4s infinite;
    }
    
    @keyframes pulse-glow {
        0%, 100% { 
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
            transform: scale(1);
        }
        50% { 
            box-shadow: 0 6px 25px rgba(102, 126, 234, 0.5);
            transform: scale(1.02);
        }
    }
    
    .auth-section .btn {
        transition: all 0.3s ease;
    }
    
    .auth-section .btn:hover {
        transform: translateY(-1px);
    }
    
    .navbar-toggler {
        border: none;
        padding: 0.5rem;
        transition: all 0.3s ease;
    }
    
    .navbar-toggler:focus {
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.2);
    }
    
    .navbar-toggler-icon-custom {
        font-size: 1.2rem;
    }
    
    /* Mobile Responsive */
    @media (max-width: 991px) {
        .navbar-nav {
            margin: 1rem 0;
            text-align: center;
        }
        
        .nav-item {
            margin: 0.25rem 0;
        }
        
        .auth-section {
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid rgba(0, 0, 0, 0.1);
        }
        
        .navbar-actions {
            flex-direction: column;
            gap: 1rem !important;
            align-items: center;
        }
        
        .auth-buttons {
            flex-direction: row !important;
            justify-content: center;
        }
        
        .cta-button {
            width: fit-content;
        }
    }
    
    @media (max-width: 576px) {
        .brand-text {
            font-size: 1.25rem;
        }
    }
    
    /* Smooth scroll behavior */
    html {
        scroll-behavior: smooth;
    }
    
    /* Custom scrollbar for webkit browsers */
    ::-webkit-scrollbar {
        width: 8px;
    }
    
    ::-webkit-scrollbar-track {
        background: #f1f1f1;
    }
    
    ::-webkit-scrollbar-thumb {
        background: linear-gradient(135deg, #667eea, #764ba2);
        border-radius: 4px;
    }
    
    ::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(135deg, #764ba2, #667eea);
    }
</style>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // Add scroll effect to navbar
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
    
    // Mobile menu auto-close on link click
    document.querySelectorAll(".navbar-nav .nav-link").forEach(link => {
        link.addEventListener("click", function() {
            const navbarCollapse = document.getElementById("navbarNav");
            if (navbarCollapse && bootstrap && bootstrap.Collapse) {
                const bsCollapse = new bootstrap.Collapse(navbarCollapse, {
                    toggle: false
                });
                bsCollapse.hide();
            }
        });
    });
});
</script>';
    }

    private function getFooterTemplate(): string
    {
        return '<footer class="footer-enhanced bg-gradient-dark text-white position-relative">
    <div class="footer-background"></div>
    <div class="footer-main py-5">
        <div class="container position-relative">
            <div class="row g-4 mb-4">
                <!-- Company Info -->
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="footer-widget">
                        {{#if logo_url}}
                        <img src="{{logo_url}}" alt="{{site_name}}" height="40" class="mb-3 footer-logo">
                        {{/if}}
                        <h4 class="footer-title mb-3">{{site_name}}</h4>
                        <p class="footer-description mb-4">{{description}}</p>
                        
                        <!-- Contact Info -->
                        <div class="contact-info mb-4">
                            <div class="contact-item d-flex align-items-center mb-2">
                                <i class="fas fa-envelope me-3 text-primary"></i>
                                <a href="mailto:{{email}}" class="footer-link">{{email}}</a>
                            </div>
                            <div class="contact-item d-flex align-items-center mb-2">
                                <i class="fas fa-phone me-3 text-primary"></i>
                                <a href="tel:{{phone}}" class="footer-link">{{phone}}</a>
                            </div>
                            <div class="contact-item d-flex align-items-center">
                                <i class="fas fa-map-marker-alt me-3 text-primary"></i>
                                <span class="text-light opacity-75">{{address}}</span>
                            </div>
                        </div>
                        
                        <!-- Social Media -->
                        <div class="social-links">
                            <h6 class="text-white mb-3">Follow Us</h6>
                            <div class="d-flex gap-3 flex-wrap">
                                {{#each social_links}}
                                <a href="{{url}}" class="social-icon" target="_blank" rel="noopener noreferrer" aria-label="{{name}}">
                                    <i class="{{icon}}"></i>
                                </a>
                                {{/each}}
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Navigation Links -->
                <div class="col-lg-2 col-md-6 mb-4">
                    <div class="footer-widget">
                        <h5 class="footer-title mb-4">Navigation</h5>
                        <ul class="list-unstyled footer-links">
                            <li class="mb-2">
                                <a href="/" class="footer-link">
                                    <i class="fas fa-chevron-right me-2 small"></i>Home
                                </a>
                            </li>
                            <li class="mb-2">
                                <a href="/about" class="footer-link">
                                    <i class="fas fa-chevron-right me-2 small"></i>About Us
                                </a>
                            </li>
                            <li class="mb-2">
                                <a href="/services" class="footer-link">
                                    <i class="fas fa-chevron-right me-2 small"></i>Services
                                </a>
                            </li>
                            <li class="mb-2">
                                <a href="/portfolio" class="footer-link">
                                    <i class="fas fa-chevron-right me-2 small"></i>Portfolio
                                </a>
                            </li>
                            <li class="mb-2">
                                <a href="/blog" class="footer-link">
                                    <i class="fas fa-chevron-right me-2 small"></i>Blog
                                </a>
                            </li>
                            <li class="mb-2">
                                <a href="/contact" class="footer-link">
                                    <i class="fas fa-chevron-right me-2 small"></i>Contact
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                
                <!-- Services -->
                <div class="col-lg-2 col-md-6 mb-4">
                    <div class="footer-widget">
                        <h5 class="footer-title mb-4">Our Services</h5>
                        <ul class="list-unstyled footer-links">
                            <li class="mb-2">
                                <a href="/services" class="footer-link">
                                    <i class="fas fa-chevron-right me-2 small"></i>SEO Optimization
                                </a>
                            </li>
                            <li class="mb-2">
                                <a href="/services" class="footer-link">
                                    <i class="fas fa-chevron-right me-2 small"></i>Digital Marketing
                                </a>
                            </li>
                            <li class="mb-2">
                                <a href="/services" class="footer-link">
                                    <i class="fas fa-chevron-right me-2 small"></i>Content Strategy
                                </a>
                            </li>
                            <li class="mb-2">
                                <a href="/services" class="footer-link">
                                    <i class="fas fa-chevron-right me-2 small"></i>Social Media
                                </a>
                            </li>
                            <li class="mb-2">
                                <a href="/services" class="footer-link">
                                    <i class="fas fa-chevron-right me-2 small"></i>Web Analytics
                                </a>
                            </li>
                            <li class="mb-2">
                                <a href="/services" class="footer-link">
                                    <i class="fas fa-chevron-right me-2 small"></i>Consulting
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                
                <!-- Resources -->
                <div class="col-lg-2 col-md-6 mb-4">
                    <div class="footer-widget">
                        <h5 class="footer-title mb-4">Resources</h5>
                        <ul class="list-unstyled footer-links">
                            <li class="mb-2">
                                <a href="/blog" class="footer-link">
                                    <i class="fas fa-chevron-right me-2 small"></i>SEO Blog
                                </a>
                            </li>
                            <li class="mb-2">
                                <a href="/case-studies" class="footer-link">
                                    <i class="fas fa-chevron-right me-2 small"></i>Case Studies
                                </a>
                            </li>
                            <li class="mb-2">
                                <a href="/resources" class="footer-link">
                                    <i class="fas fa-chevron-right me-2 small"></i>Free Tools
                                </a>
                            </li>
                            <li class="mb-2">
                                <a href="/webinars" class="footer-link">
                                    <i class="fas fa-chevron-right me-2 small"></i>Webinars
                                </a>
                            </li>
                            <li class="mb-2">
                                <a href="/guides" class="footer-link">
                                    <i class="fas fa-chevron-right me-2 small"></i>SEO Guides
                                </a>
                            </li>
                            <li class="mb-2">
                                <a href="/faq" class="footer-link">
                                    <i class="fas fa-chevron-right me-2 small"></i>FAQ
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                
                <!-- Newsletter & Support -->
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="footer-widget">
                        <h5 class="footer-title mb-4">Stay Connected</h5>
                        <p class="footer-description mb-3">Subscribe to our newsletter for the latest SEO tips and industry insights.</p>
                        
                        <!-- Newsletter Form -->
                        <form class="newsletter-form mb-4">
                            <div class="input-group mb-3">
                                <input type="email" class="form-control newsletter-input" placeholder="Enter your email" required>
                                <button class="btn btn-primary newsletter-btn" type="submit">
                                    <i class="fas fa-paper-plane"></i>
                                </button>
                            </div>
                        </form>
                        
                        <!-- Support Info -->
                        <div class="support-info">
                            <h6 class="text-white mb-3">Support</h6>
                            <ul class="list-unstyled footer-links">
                                <li class="mb-2">
                                    <a href="/help" class="footer-link">
                                        <i class="fas fa-chevron-right me-2 small"></i>Help Center
                                    </a>
                                </li>
                                <li class="mb-2">
                                    <a href="/support" class="footer-link">
                                        <i class="fas fa-chevron-right me-2 small"></i>24/7 Support
                                    </a>
                                </li>
                                <li class="mb-2">
                                    <a href="/documentation" class="footer-link">
                                        <i class="fas fa-chevron-right me-2 small"></i>Documentation
                                    </a>
                                </li>
                                <li class="mb-2">
                                    <a href="/api" class="footer-link">
                                        <i class="fas fa-chevron-right me-2 small"></i>API Access
                                    </a>
                                </li>
                            </ul>
                        </div>
                        
                        <!-- Business Hours -->
                        <div class="business-hours mt-4">
                            <div class="feature-item d-flex align-items-center mb-2">
                                <i class="fas fa-clock me-2 text-primary"></i>
                                <small>Mon-Fri: 9AM-6PM</small>
                            </div>
                            <div class="feature-item d-flex align-items-center">
                                <i class="fas fa-shield-alt me-2 text-primary"></i>
                                <small>Secure & Private</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Footer Bottom -->
    <div class="footer-bottom py-4 border-top border-secondary">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                    <p class="mb-0 text-light">
                        © {{current_year}} {{site_name}}. All rights reserved.
                    </p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <div class="footer-bottom-links">
                        <a href="/privacy-policy" class="footer-bottom-link me-3">Privacy Policy</a>
                        <a href="/terms-of-service" class="footer-bottom-link me-3">Terms of Service</a>
                        <a href="/cookies" class="footer-bottom-link me-3">Cookie Policy</a>
                        <a href="/sitemap" class="footer-bottom-link">Sitemap</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Back to Top Button -->
    <button class="back-to-top" onclick="window.scrollTo({top: 0, behavior: \'smooth\'})" aria-label="Back to top">
        <i class="fas fa-chevron-up"></i>
    </button>
</footer>

<style>
    /* Enhanced Footer Styles */
    .footer-enhanced {
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 50%, #1e3c72 100%);
        position: relative;
        overflow: hidden;
    }
    
    .footer-background {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-image: 
            radial-gradient(circle at 20% 80%, rgba(120, 119, 198, 0.1) 0%, transparent 50%),
            radial-gradient(circle at 80% 20%, rgba(255, 255, 255, 0.05) 0%, transparent 50%);
        pointer-events: none;
    }
    
    .footer-main {
        position: relative;
        z-index: 2;
    }
    
    .footer-widget {
        height: 100%;
        padding: 1.5rem;
        background: rgba(255, 255, 255, 0.03);
        border-radius: 15px;
        border: 1px solid rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        transition: all 0.3s ease;
    }
    
    .footer-widget:hover {
        background: rgba(255, 255, 255, 0.05);
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    }
    
    .footer-title {
        font-weight: 700;
        color: #ffffff !important;
        position: relative;
        padding-bottom: 0.75rem;
        margin-bottom: 1.5rem !important;
    }
    
    .footer-title::after {
        content: "";
        position: absolute;
        bottom: 0;
        left: 0;
        width: 60px;
        height: 3px;
        background: linear-gradient(90deg, #667eea, #764ba2);
        border-radius: 2px;
    }
    
    .footer-description {
        color: rgba(255, 255, 255, 0.85);
        line-height: 1.7;
        font-size: 0.95rem;
        margin-bottom: 1.5rem;
    }
    
    .footer-links {
        margin: 0;
        padding: 0;
    }
    
    .footer-links li {
        margin-bottom: 0.75rem !important;
        transition: all 0.3s ease;
    }
    
    .footer-links li:hover {
        transform: translateX(8px);
    }
    
    .footer-link {
        color: rgba(255, 255, 255, 0.8);
        text-decoration: none;
        transition: all 0.3s ease;
        position: relative;
        padding-left: 0;
        font-size: 0.9rem;
        line-height: 1.6;
        display: flex;
        align-items: center;
    }
    
    .footer-link i.fa-chevron-right {
        opacity: 0.6;
        transition: all 0.3s ease;
        color: #667eea;
    }
    
    .footer-link:hover {
        color: #ffffff;
        padding-left: 10px;
    }
    
    .footer-link:hover i.fa-chevron-right {
        opacity: 1;
        transform: translateX(5px);
        color: #764ba2;
    }
    
    .social-icon {
        width: 45px;
        height: 45px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        color: #ffffff;
        text-decoration: none;
        transition: all 0.3s ease;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.15);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
    }
    
    .social-icon:hover {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: #ffffff;
        transform: translateY(-3px) scale(1.1);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        border-color: transparent;
    }
    
    .contact-item {
        color: rgba(255, 255, 255, 0.9);
        font-size: 0.9rem;
        margin-bottom: 0.75rem;
    }
    
    .contact-item i {
        color: #667eea !important;
        font-size: 1rem;
        width: 20px;
        flex-shrink: 0;
    }
    
    .newsletter-input {
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: #ffffff;
        border-radius: 25px 0 0 25px;
        padding: 0.75rem 1rem;
        transition: all 0.3s ease;
        font-size: 0.9rem;
    }
    
    .newsletter-input::placeholder {
        color: rgba(255, 255, 255, 0.6);
    }
    
    .newsletter-input:focus {
        background: rgba(255, 255, 255, 0.15);
        border-color: #667eea;
        color: #ffffff;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.2);
        outline: none;
    }
    
    .newsletter-btn {
        background: linear-gradient(135deg, #667eea, #764ba2);
        border: none;
        border-radius: 0 25px 25px 0;
        padding: 0.75rem 1.25rem;
        transition: all 0.3s ease;
        color: white;
    }
    
    .newsletter-btn:hover {
        background: linear-gradient(135deg, #764ba2, #667eea);
        transform: scale(1.05);
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        color: white;
    }
    
    .feature-item {
        color: rgba(255, 255, 255, 0.85);
        font-size: 0.9rem;
        line-height: 1.6;
    }
    
    .feature-item i {
        color: #667eea !important;
        font-size: 1rem;
    }
    
    .footer-bottom {
        background: rgba(0, 0, 0, 0.2);
        backdrop-filter: blur(10px);
        border-top: 1px solid rgba(255, 255, 255, 0.1) !important;
    }
    
    .footer-bottom-link {
        color: rgba(255, 255, 255, 0.7);
        text-decoration: none;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        position: relative;
        display: inline-block;
        margin: 0.25rem 0;
    }
    
    .footer-bottom-link::after {
        content: "";
        position: absolute;
        bottom: -2px;
        left: 0;
        width: 0;
        height: 2px;
        background: linear-gradient(90deg, #667eea, #764ba2);
        transition: width 0.3s ease;
    }
    
    .footer-bottom-link:hover {
        color: #ffffff;
    }
    
    .footer-bottom-link:hover::after {
        width: 100%;
    }
    
    .back-to-top {
        position: fixed;
        bottom: 30px;
        right: 30px;
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, #667eea, #764ba2);
        border: none;
        border-radius: 50%;
        color: white;
        font-size: 1.2rem;
        cursor: pointer;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        transition: all 0.3s ease;
        z-index: 1000;
        opacity: 0;
        visibility: hidden;
    }
    
    .back-to-top.show {
        opacity: 1;
        visibility: visible;
    }
    
    .back-to-top:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.5);
    }
    
    /* Responsive Design */
    @media (max-width: 1200px) {
        .footer-widget {
            margin-bottom: 2rem;
        }
    }
    
    @media (max-width: 768px) {
        .footer-widget {
            text-align: center;
            margin-bottom: 2.5rem;
            padding: 1.25rem;
        }
        
        .footer-title::after {
            left: 50%;
            transform: translateX(-50%);
        }
        
        .social-links .d-flex {
            justify-content: center !important;
        }
        
        .contact-item {
            justify-content: center;
        }
        
        .newsletter-form {
            max-width: 350px;
            margin: 0 auto;
        }
        
        .footer-bottom-links {
            margin-top: 1rem;
        }
        
        .footer-bottom-link {
            display: inline-block;
            margin: 0.25rem 0.75rem;
        }
        
        .back-to-top {
            bottom: 20px;
            right: 20px;
            width: 45px;
            height: 45px;
            font-size: 1rem;
        }
    }
    
    @media (max-width: 576px) {
        .footer-main {
            padding: 3rem 0 !important;
        }
        
        .footer-widget {
            margin-bottom: 2rem;
            padding: 1rem;
        }
        
        .footer-title {
            font-size: 1.1rem;
        }
        
        .social-icon {
            width: 40px;
            height: 40px;
            font-size: 1rem;
        }
        
        .newsletter-input, .newsletter-btn {
            padding: 0.6rem 1rem;
            font-size: 0.85rem;
        }
        
        .footer-link {
            font-size: 0.85rem;
        }
    }
    
    /* Scroll animations */
    .footer-widget {
        animation: fadeInUp 0.6s ease-out;
        animation-fill-mode: both;
    }
    
    .footer-widget:nth-child(1) { animation-delay: 0.1s; }
    .footer-widget:nth-child(2) { animation-delay: 0.2s; }
    .footer-widget:nth-child(3) { animation-delay: 0.3s; }
    .footer-widget:nth-child(4) { animation-delay: 0.4s; }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // Newsletter form submission
    const newsletterForm = document.querySelector(".newsletter-form");
    if (newsletterForm) {
        newsletterForm.addEventListener("submit", function(e) {
            e.preventDefault();
            const email = this.querySelector("input[type=\"email\"]").value;
            if (email) {
                // Show success message
                const btn = this.querySelector(".newsletter-btn");
                const originalHtml = btn.innerHTML;
                btn.innerHTML = "<i class=\"fas fa-check\"></i>";
                btn.style.background = "linear-gradient(135deg, #28a745, #20c997)";
                
                setTimeout(() => {
                    btn.innerHTML = originalHtml;
                    btn.style.background = "";
                    this.querySelector("input[type=\"email\"]").value = "";
                }, 2500);
            }
        });
    }
    
    // Back to top button functionality
    const backToTopBtn = document.querySelector(".back-to-top");
    if (backToTopBtn) {
        window.addEventListener("scroll", function() {
            if (window.scrollY > 300) {
                backToTopBtn.classList.add("show");
            } else {
                backToTopBtn.classList.remove("show");
            }
        });
    }
    
    // Smooth scroll for footer links
    document.querySelectorAll(".footer-link[href^=\"#\"]").forEach(link => {
        link.addEventListener("click", function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute("href"));
            if (target) {
                target.scrollIntoView({
                    behavior: "smooth",
                    block: "start"
                });
            }
        });
    });
});
</script>';
    }

    private function getHeroTemplate(): string
    {
        return '<!-- Hero Section -->
<section class="hero-section py-5" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 80vh;">
    <div class="container">
        <div class="row align-items-center min-vh-80">
            <div class="col-lg-6">
                <div class="hero-content text-white">
                    <h1 class="display-4 fw-bold mb-4">{{headline}}</h1>
                    <p class="lead mb-4">{{subheadline}}</p>
                    <div class="hero-stats row g-3 mb-4">
                        <div class="col-md-4">
                            <div class="stat-item text-center">
                                <h3 class="h2 fw-bold mb-1">{{clients_count}}</h3>
                                <p class="mb-0 small opacity-75">Happy Clients</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="stat-item text-center">
                                <h3 class="h2 fw-bold mb-1">{{rankings_improved}}</h3>
                                <p class="mb-0 small opacity-75">Rankings Improved</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="stat-item text-center">
                                <h3 class="h2 fw-bold mb-1">{{traffic_increase}}</h3>
                                <p class="mb-0 small opacity-75">Traffic Increase</p>
                            </div>
                        </div>
                    </div>
                    <div class="hero-buttons">
                        <a href="{{cta_url}}" class="btn btn-light btn-lg px-4 py-3 me-3 hero-btn-primary">
                            {{cta_text}}
                            <i class="fas fa-arrow-right ms-2"></i>
                        </a>
                        <a href="{{secondary_cta_url}}" class="btn btn-outline-light btn-lg px-4 py-3 hero-btn-secondary">
                            {{secondary_cta_text}}
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="hero-image">
                    <div class="position-relative">
                        <img src="{{hero_image}}" alt="SEO Services" class="img-fluid rounded-3 shadow-lg">
                        <div class="floating-card position-absolute" style="top: 20px; right: 20px;">
                            <div class="card bg-white shadow border-0 p-3">
                                <div class="d-flex align-items-center">
                                    <div class="icon-circle bg-success me-3">
                                        <i class="fas fa-chart-line text-white"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-bold">Traffic Growth</h6>
                                        <small class="text-muted">+250% this month</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="floating-card position-absolute" style="bottom: 20px; left: 20px;">
                            <div class="card bg-white shadow border-0 p-3">
                                <div class="d-flex align-items-center">
                                    <div class="icon-circle bg-primary me-3">
                                        <i class="fas fa-search text-white"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-bold">SEO Score</h6>
                                        <small class="text-muted">98/100</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Scroll indicator -->
    <div class="scroll-indicator text-center mt-4">
        <a href="#services" class="text-white opacity-75 text-decoration-none">
            <i class="fas fa-chevron-down fa-2x"></i>
        </a>
    </div>
</section>

<style>
.hero-section {
    position: relative;
    overflow: hidden;
}

.hero-section::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url("data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.1\'%3E%3Ccircle cx=\'30\' cy=\'30\' r=\'2\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E") repeat;
    opacity: 0.3;
}

.min-vh-80 {
    min-height: 80vh;
}

.hero-content {
    position: relative;
    z-index: 2;
}

.hero-image {
    position: relative;
    z-index: 2;
}

.hero-btn-primary {
    background: white;
    color: #667eea;
    border: 2px solid white;
    font-weight: 600;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.hero-btn-primary:hover {
    background: transparent;
    color: white;
    border-color: white;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
}

.hero-btn-secondary {
    font-weight: 600;
    border: 2px solid rgba(255, 255, 255, 0.7);
    transition: all 0.3s ease;
}

.hero-btn-secondary:hover {
    background: white;
    color: #667eea;
    border-color: white;
    transform: translateY(-2px);
}

.stat-item h3 {
    background: linear-gradient(45deg, rgba(255, 255, 255, 0.9), rgba(255, 255, 255, 0.7));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.floating-card {
    backdrop-filter: blur(10px);
    border-radius: 15px;
    /* Removed floating animation */
}

.icon-circle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.scroll-indicator i {
    /* Removed bounce animation */
    transition: all 0.3s ease;
}

.scroll-indicator:hover i {
    transform: translateY(5px);
}

@media (max-width: 768px) {
    .hero-section {
        min-height: 60vh !important;
        text-align: center;
    }
    
    .hero-stats {
        margin: 2rem 0;
    }
    
    .hero-buttons {
        display: flex;
        flex-direction: column;
        gap: 1rem;
        align-items: center;
    }
    
    .hero-btn-primary,
    .hero-btn-secondary {
        width: 100%;
        max-width: 300px;
    }
    
    .floating-card {
        position: static !important;
        margin: 1rem 0;
        display: none;
    }
}

@media (max-width: 576px) {
    .hero-section {
        padding: 2rem 0;
        min-height: 50vh !important;
    }
    
    .display-4 {
        font-size: 2rem;
    }
    
    .hero-stats {
        margin: 1.5rem 0;
    }
    
    .stat-item h3 {
        font-size: 1.5rem;
    }
}
</style>';
    }

    private function getServicesTemplate(): string
    {
        return '<!-- Services Section -->
<section class="services-section py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center mb-5">
                <h2 class="display-5 fw-bold text-dark">{{title}}</h2>
                <p class="lead text-muted">{{subtitle}}</p>
                <div class="divider mx-auto"></div>
            </div>
        </div>
        <div class="row g-4">
            {{#each services}}
            <div class="col-lg-4 col-md-6">
                <div class="service-card card h-100 border-0 shadow-sm hover-lift">
                    <div class="card-body text-center p-4">
                        <div class="service-icon mb-4">
                            <div class="icon-wrapper">
                                <i class="{{icon}} fa-3x"></i>
                            </div>
                        </div>
                        <h5 class="card-title fw-bold mb-3">{{name}}</h5>
                        <p class="card-text text-muted mb-3">{{description}}</p>
                        <div class="service-features">
                            {{#each features}}
                            <div class="feature-item d-flex align-items-center mb-2">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                <small class="text-muted">{{this}}</small>
                            </div>
                            {{/each}}
                        </div>
                        <a href="{{link}}" class="btn btn-outline-primary btn-sm mt-3 service-btn">
                            Learn More
                            <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                    <div class="card-footer bg-transparent border-0 text-center">
                        <div class="price-tag">
                            <span class="h6 fw-bold text-primary">{{price}}</span>
                        </div>
                    </div>
                </div>
            </div>
            {{/each}}
        </div>
        
        <!-- Call to Action -->
        <div class="row mt-5">
            <div class="col-lg-8 mx-auto text-center">
                <div class="cta-box bg-primary text-white rounded-3 p-4">
                    <h4 class="fw-bold mb-3">{{cta_title}}</h4>
                    <p class="mb-4">{{cta_description}}</p>
                    <a href="{{cta_url}}" class="btn btn-light btn-lg">
                        {{cta_text}}
                        <i class="fas fa-arrow-right ms-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.divider {
    width: 60px;
    height: 4px;
    background: linear-gradient(90deg, #667eea, #764ba2);
    border-radius: 2px;
    margin-top: 1rem;
}

.service-card {
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.service-card::before {
    content: "";
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 4px;
    background: linear-gradient(90deg, #667eea, #764ba2);
    transition: left 0.3s ease;
}

.service-card:hover::before {
    left: 0;
}

.hover-lift:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.1) !important;
}

.service-icon {
    position: relative;
}

.icon-wrapper {
    width: 80px;
    height: 80px;
    margin: 0 auto;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    transition: all 0.3s ease;
}

.service-card:hover .icon-wrapper {
    transform: scale(1.1);
    box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
}

.service-btn {
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.service-btn:hover {
    background-color: #667eea;
    border-color: #667eea;
    color: white;
    transform: translateY(-2px);
}

.feature-item {
    font-size: 0.9rem;
}

.cta-box {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    position: relative;
    overflow: hidden;
}

.cta-box::before {
    content: "";
    position: absolute;
    top: -50%;
    right: -50%;
    width: 200%;
    height: 200%;
    background: url("data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.05\'%3E%3Ccircle cx=\'30\' cy=\'30\' r=\'2\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E") repeat;
    animation: float 20s linear infinite;
}

@keyframes float {
    0% { transform: translate(-50%, -50%) rotate(0deg); }
    100% { transform: translate(-50%, -50%) rotate(360deg); }
}

.price-tag {
    font-size: 0.9rem;
}

@media (max-width: 768px) {
    .services-section .col-lg-4 {
        margin-bottom: 2rem;
    }
    
    .icon-wrapper {
        width: 60px;
        height: 60px;
    }
    
    .icon-wrapper i {
        font-size: 2rem !important;
    }
}
</style>';
    }

    private function getAboutTemplate(): string
    {
        return '<!-- About Section -->
<section class="about-section py-5 bg-light">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="about-content" data-aos="fade-right">
                    <span class="badge bg-primary mb-3">{{badge_text}}</span>
                    <h2 class="display-5 fw-bold mb-4">{{title}}</h2>
                    <p class="lead text-muted mb-4">{{description}}</p>
                    
                    <div class="about-features mb-4">
                        {{#each features}}
                        <div class="feature-item d-flex align-items-center mb-3">
                            <div class="feature-icon me-3">
                                <i class="{{icon}} text-primary"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1">{{title}}</h6>
                                <p class="text-muted mb-0 small">{{description}}</p>
                            </div>
                        </div>
                        {{/each}}
                    </div>
                    
                    <div class="about-cta">
                        <a href="{{cta_url}}" class="btn btn-primary btn-lg me-3">
                            {{cta_text}}
                            <i class="fas fa-arrow-right ms-2"></i>
                        </a>
                        <a href="{{secondary_cta_url}}" class="btn btn-outline-primary btn-lg">
                            {{secondary_cta_text}}
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="about-stats" data-aos="fade-left">
                    <div class="stats-grid">
                        {{#each stats}}
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="{{icon}} fa-2x"></i>
                            </div>
                            <div class="stat-content">
                                <h3 class="stat-number fw-bold" data-count="{{value}}">0</h3>
                                <p class="stat-label text-muted">{{label}}</p>
                            </div>
                        </div>
                        {{/each}}
                    </div>
                    
                    <!-- About Image -->
                    <div class="about-image mt-4">
                        <img src="{{about_image}}" alt="About Us" class="img-fluid rounded-3 shadow">
                        <div class="experience-badge">
                            <div class="badge-content text-center">
                                <h4 class="fw-bold text-white mb-0">{{years_experience}}+</h4>
                                <small class="text-white-50">Years Experience</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Team Section -->
        <div class="row mt-5">
            <div class="col-12 text-center mb-4">
                <h3 class="fw-bold">{{team_title}}</h3>
                <p class="text-muted">{{team_subtitle}}</p>
            </div>
            {{#each team_members}}
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="team-card text-center">
                    <div class="team-image">
                        <img src="{{image}}" alt="{{name}}" class="img-fluid rounded-circle">
                        <div class="team-overlay">
                            <div class="social-links">
                                {{#each social}}
                                <a href="{{url}}" class="social-link">
                                    <i class="{{icon}}"></i>
                                </a>
                                {{/each}}
                            </div>
                        </div>
                    </div>
                    <h5 class="fw-bold mt-3">{{name}}</h5>
                    <p class="text-primary">{{position}}</p>
                    <p class="text-muted small">{{bio}}</p>
                </div>
            </div>
            {{/each}}
        </div>
    </div>
</section>

<style>
.about-section {
    position: relative;
}

.feature-icon {
    width: 40px;
    height: 40px;
    background: rgba(102, 126, 234, 0.1);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 2rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: white;
    padding: 2rem;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    text-align: center;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.stat-card::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #667eea, #764ba2);
}

.stat-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.15);
}

.stat-icon {
    color: #667eea;
    margin-bottom: 1rem;
}

.stat-number {
    font-size: 2.5rem;
    color: #333;
    margin-bottom: 0.5rem;
}

.about-image {
    position: relative;
}

.experience-badge {
    position: absolute;
    bottom: 20px;
    right: 20px;
    width: 100px;
    height: 100px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
}

.team-card {
    transition: all 0.3s ease;
}

.team-image {
    position: relative;
    display: inline-block;
    margin-bottom: 1rem;
}

.team-image img {
    width: 120px;
    height: 120px;
    object-fit: cover;
    transition: all 0.3s ease;
}

.team-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(102, 126, 234, 0.9);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: all 0.3s ease;
}

.team-card:hover .team-overlay {
    opacity: 1;
}

.social-links {
    display: flex;
    gap: 0.5rem;
}

.social-link {
    width: 35px;
    height: 35px;
    background: rgba(255,255,255,0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    text-decoration: none;
    transition: all 0.3s ease;
}

.social-link:hover {
    background: rgba(255,255,255,0.3);
    transform: scale(1.1);
    color: white;
}

@media (max-width: 768px) {
    .stats-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .stat-card {
        padding: 1.5rem;
    }
    
    .experience-badge {
        width: 80px;
        height: 80px;
        bottom: 10px;
        right: 10px;
    }
    
    .experience-badge h4 {
        font-size: 1.2rem;
    }
}
</style>';
    }

    private function getContactTemplate(): string
    {
        return '<!-- Contact Section -->
<section class="contact-section py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center mb-5">
                <span class="badge bg-primary mb-3">{{badge_text}}</span>
                <h2 class="display-5 fw-bold">{{title}}</h2>
                <p class="lead text-muted">{{subtitle}}</p>
                <div class="divider mx-auto"></div>
            </div>
        </div>
        
        <div class="row g-5">
            <!-- Contact Form -->
            <div class="col-lg-8">
                <div class="contact-form-wrapper">
                    <h4 class="fw-bold mb-4">{{form_title}}</h4>
                    <form class="contact-form">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="firstName" placeholder="First Name" required>
                                    <label for="firstName">First Name *</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="lastName" placeholder="Last Name" required>
                                    <label for="lastName">Last Name *</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="email" class="form-control" id="email" placeholder="Email Address" required>
                                    <label for="email">Email Address *</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="tel" class="form-control" id="phone" placeholder="Phone Number">
                                    <label for="phone">Phone Number</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="website" placeholder="Website URL">
                                    <label for="website">Website URL</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating">
                                    <select class="form-select" id="service" required>
                                        <option value="">Select a Service</option>
                                        <option value="seo-audit">SEO Audit</option>
                                        <option value="keyword-research">Keyword Research</option>
                                        <option value="on-page-seo">On-Page SEO</option>
                                        <option value="link-building">Link Building</option>
                                        <option value="local-seo">Local SEO</option>
                                        <option value="technical-seo">Technical SEO</option>
                                    </select>
                                    <label for="service">Service Needed *</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating">
                                    <textarea class="form-control" id="message" placeholder="Message" style="height: 120px" required></textarea>
                                    <label for="message">Tell us about your project *</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary btn-lg px-5">
                                    {{form_submit_text}}
                                    <i class="fas fa-paper-plane ms-2"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Contact Information -->
            <div class="col-lg-4">
                <div class="contact-info">
                    <h4 class="fw-bold mb-4">{{contact_info_title}}</h4>
                    
                    <!-- Contact Items -->
                    <div class="contact-item mb-4">
                        <div class="contact-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="contact-details">
                            <h6 class="fw-bold">Office Address</h6>
                            <p class="text-muted mb-0">{{contact_info.address}}</p>
                        </div>
                    </div>
                    
                    <div class="contact-item mb-4">
                        <div class="contact-icon">
                            <i class="fas fa-phone"></i>
                        </div>
                        <div class="contact-details">
                            <h6 class="fw-bold">Phone Number</h6>
                            <p class="text-muted mb-0">
                                <a href="tel:{{contact_info.phone}}" class="text-decoration-none">{{contact_info.phone}}</a>
                            </p>
                        </div>
                    </div>
                    
                    <div class="contact-item mb-4">
                        <div class="contact-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="contact-details">
                            <h6 class="fw-bold">Email Address</h6>
                            <p class="text-muted mb-0">
                                <a href="mailto:{{contact_info.email}}" class="text-decoration-none">{{contact_info.email}}</a>
                            </p>
                        </div>
                    </div>
                    
                    <div class="contact-item mb-4">
                        <div class="contact-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="contact-details">
                            <h6 class="fw-bold">Business Hours</h6>
                            <p class="text-muted mb-1">{{business_hours.weekdays}}</p>
                            <p class="text-muted mb-0">{{business_hours.weekend}}</p>
                        </div>
                    </div>
                    
                    <!-- Social Links -->
                    <div class="social-section mt-4">
                        <h6 class="fw-bold mb-3">Follow Us</h6>
                        <div class="social-links d-flex gap-2">
                            {{#each social_links}}
                            <a href="{{url}}" class="social-link">
                                <i class="{{icon}}"></i>
                            </a>
                            {{/each}}
                        </div>
                    </div>
                    
                    <!-- Quick CTA -->
                    <div class="quick-cta mt-4 p-4 bg-light rounded-3">
                        <h6 class="fw-bold mb-2">{{quick_cta_title}}</h6>
                        <p class="text-muted small mb-3">{{quick_cta_description}}</p>
                        <a href="{{quick_cta_url}}" class="btn btn-outline-primary btn-sm">
                            {{quick_cta_text}}
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Map Section -->
        {{#if show_map}}
        <div class="row mt-5">
            <div class="col-12">
                <div class="map-container">
                    <h4 class="fw-bold mb-4 text-center">{{map_title}}</h4>
                    <div class="map-wrapper rounded-3 overflow-hidden shadow">
                        <iframe 
                            src="{{map_embed_url}}" 
                            width="100%" 
                            height="400" 
                            style="border:0;" 
                            allowfullscreen="" 
                            loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                </div>
            </div>
        </div>
        {{/if}}
    </div>
</section>

<style>
.contact-section {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

.divider {
    width: 60px;
    height: 4px;
    background: linear-gradient(90deg, #667eea, #764ba2);
    border-radius: 2px;
    margin-top: 1rem;
}

.contact-form-wrapper {
    background: white;
    padding: 3rem;
    border-radius: 20px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    position: relative;
    overflow: hidden;
}

.contact-form-wrapper::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 5px;
    background: linear-gradient(90deg, #667eea, #764ba2);
}

.form-floating > .form-control:focus,
.form-floating > .form-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.form-floating > label {
    color: #6c757d;
}

.contact-info {
    background: white;
    padding: 3rem;
    border-radius: 20px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    height: fit-content;
    position: sticky;
    top: 2rem;
}

.contact-item {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
}

.contact-icon {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.2rem;
    flex-shrink: 0;
}

.contact-details h6 {
    color: #333;
    margin-bottom: 0.5rem;
}

.contact-details a {
    color: #667eea;
    transition: all 0.3s ease;
}

.contact-details a:hover {
    color: #764ba2;
}

.social-link {
    width: 40px;
    height: 40px;
    background: #f8f9fa;
    border: 2px solid #e9ecef;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #6c757d;
    text-decoration: none;
    transition: all 0.3s ease;
}

.social-link:hover {
    background: linear-gradient(135deg, #667eea, #764ba2);
    border-color: transparent;
    color: white;
    transform: translateY(-2px);
}

.quick-cta {
    border: 2px solid #e9ecef;
    transition: all 0.3s ease;
}

.quick-cta:hover {
    border-color: #667eea;
    background: rgba(102, 126, 234, 0.05) !important;
}

.map-wrapper {
    position: relative;
    overflow: hidden;
}

.map-wrapper::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1));
    pointer-events: none;
    z-index: 1;
}

@media (max-width: 768px) {
    .contact-form-wrapper,
    .contact-info {
        padding: 2rem;
        margin-bottom: 2rem;
    }
    
    .contact-info {
        position: static;
    }
    
    .contact-item {
        margin-bottom: 2rem;
    }
    
    .social-links {
        justify-content: center;
    }
}
</style>';
    }
}
