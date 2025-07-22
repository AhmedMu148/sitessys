<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CompleteDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        echo "🚀 Completing remaining database data...\n";
        
        // Get all sites
        $sites = DB::table('sites')->get();
        
        foreach ($sites as $site) {
            echo "Processing site: {$site->site_name}\n";
            
            // 1. Create site_config for each site
            $this->createSiteConfig($site);
            
            // 2. Create pages for each site
            $this->createSitePages($site);
            
            // 3. Create site template data
            $this->createSiteTemplateData($site);
            
            // 4. Create media configuration (not actual media files)
            $this->createMediaConfig($site);
        }
        
        echo "✅ All data completed successfully!\n";
    }
    
    private function createSiteConfig($site)
    {
        $configData = $this->getSiteConfigData($site->site_name);
        
        DB::table('site_config')->updateOrInsert(
            ['site_id' => $site->id],
            [
                'site_id' => $site->id,
                'settings' => json_encode($configData['settings']),
                'data' => json_encode($configData['data']),
                'language_code' => json_encode($configData['language_code']),
                'tpl_name' => $configData['tpl_name'],
                'tpl_colors' => json_encode($configData['tpl_colors']),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        );
    }
    
    private function createSitePages($site)
    {
        $pagesData = $this->getSitePagesData($site->site_name);
        
        foreach ($pagesData as $pageData) {
            $pageId = DB::table('tpl_pages')->insertGetId([
                'site_id' => $site->id,
                'name' => $pageData['name'],
                'link' => $pageData['link'],
                'slug' => $pageData['slug'],
                'data' => json_encode($pageData['data']),
                'show_in_nav' => $pageData['show_in_nav'],
                'status' => $pageData['status'],
                'page_theme_id' => $pageData['page_theme_id'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
            
            // Create sections for this page
            $this->createPageSections($pageId, $pageData['sections']);
        }
    }
    
    private function createPageSections($pageId, $sections)
    {
        // Get site_id from the page
        $page = DB::table('tpl_pages')->where('id', $pageId)->first();
        $siteId = $page->site_id;
        
        foreach ($sections as $section) {
            DB::table('tpl_page_sections')->insert([
                'page_id' => $pageId,
                'site_id' => $siteId,
                'tpl_layouts_id' => null, // Can be set later if needed
                'name' => $section['section_name'],
                'content' => json_encode($section['section_data']),
                'custom_styles' => null,
                'custom_scripts' => null,
                'status' => true,
                'sort_order' => $section['sort_order'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
    
    private function createSiteTemplateData($site)
    {
        $templateData = $this->getSiteTemplateData($site->site_name);
        
        DB::table('tpl_site')->updateOrInsert(
            ['site_id' => $site->id],
            [
                'site_id' => $site->id,
                'nav_data' => json_encode($templateData['nav_data']),
                'footer_data' => json_encode($templateData['footer_data']),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        );
    }
    
    private function createMediaConfig($site)
    {
        // Create media configuration for the site
        DB::table('site_img_media')->updateOrInsert(
            ['site_id' => $site->id],
            [
                'site_id' => $site->id,
                'section_id' => null,
                'max_files' => 50,
                'allowed_types' => json_encode(['image/jpeg', 'image/png', 'image/gif', 'image/webp']),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        );
    }
    
    private function getSiteConfigData($siteName)
    {
        $configs = [
            'SEOeStore Digital Agency' => [
                'settings' => ['timezone' => 'Asia/Dubai', 'theme' => 'business'],
                'data' => [
                    'site_title' => 'SEOeStore Digital Agency - خدمات التسويق الرقمي',
                    'site_description' => 'وكالة تسويق رقمي متخصصة في SEO وتطوير المواقع',
                    'contact_email' => 'info@seoestore.com',
                    'contact_phone' => '+971-4-123-4567',
                    'address' => 'Dubai Internet City, UAE',
                    'logo' => 'https://images.unsplash.com/photo-1560472354-b33ff0c44a43?w=200&h=80&fit=crop',
                ],
                'language_code' => ['languages' => ['ar', 'en'], 'primary' => 'ar'],
                'tpl_name' => 'business',
                'tpl_colors' => ['primary' => '#1e3a8a', 'secondary' => '#f59e0b'],
            ],
            'TechCorp Solutions' => [
                'settings' => ['timezone' => 'America/New_York', 'theme' => 'business'],
                'data' => [
                    'site_title' => 'TechCorp Solutions - Enterprise Technology',
                    'site_description' => 'Leading technology consulting and software development',
                    'contact_email' => 'contact@techcorp-solutions.com',
                    'contact_phone' => '+1-555-123-4567',
                    'address' => 'New York, NY, USA',
                    'logo' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=200&h=80&fit=crop',
                ],
                'language_code' => ['languages' => ['en'], 'primary' => 'en'],
                'tpl_name' => 'business',
                'tpl_colors' => ['primary' => '#2563eb', 'secondary' => '#64748b'],
            ],
            // Add more configs for other sites...
        ];
        
        return $configs[$siteName] ?? $configs['SEOeStore Digital Agency'];
    }
    
    private function getSitePagesData($siteName)
    {
        $homeTheme = DB::table('theme_pages')->where('theme_id', 'home')->first();
        $aboutTheme = DB::table('theme_pages')->where('theme_id', 'about')->first();
        $servicesTheme = DB::table('theme_pages')->where('theme_id', 'services')->first();
        $contactTheme = DB::table('theme_pages')->where('theme_id', 'contact')->first();
        
        if ($siteName === 'SEOeStore Digital Agency') {
            return [
                [
                    'name' => 'الرئيسية',
                    'link' => '/',
                    'slug' => 'home',
                    'data' => [
                        'ar' => [
                            'title' => 'SEOeStore - وكالة التسويق الرقمي',
                            'meta_description' => 'وكالة تسويق رقمي متخصصة في تحسين محركات البحث وتطوير المواقع',
                        ],
                        'en' => [
                            'title' => 'SEOeStore Digital Marketing Agency',
                            'meta_description' => 'Digital marketing agency specialized in SEO and web development',
                        ]
                    ],
                    'show_in_nav' => true,
                    'status' => true,
                    'page_theme_id' => $homeTheme->id ?? 1,
                    'sections' => [
                        [
                            'section_name' => 'hero',
                            'section_type' => 'hero',
                            'section_data' => [
                                'ar' => [
                                    'title' => 'وكالة التسويق الرقمي الرائدة',
                                    'subtitle' => 'نساعدك على تحقيق النجاح الرقمي',
                                    'cta_text' => 'ابدأ مشروعك',
                                ],
                                'en' => [
                                    'title' => 'Leading Digital Marketing Agency',
                                    'subtitle' => 'We help you achieve digital success',
                                    'cta_text' => 'Start Your Project',
                                ]
                            ],
                            'sort_order' => 1,
                        ]
                    ]
                ],
                [
                    'name' => 'من نحن',
                    'link' => '/about',
                    'slug' => 'about',
                    'data' => [
                        'ar' => [
                            'title' => 'من نحن - SEOeStore',
                            'meta_description' => 'تعرف على فريق SEOeStore وخبراتنا في التسويق الرقمي',
                        ],
                        'en' => [
                            'title' => 'About Us - SEOeStore',
                            'meta_description' => 'Learn about SEOeStore team and our digital marketing expertise',
                        ]
                    ],
                    'show_in_nav' => true,
                    'status' => true,
                    'page_theme_id' => $aboutTheme->id ?? 2,
                    'sections' => [
                        [
                            'section_name' => 'about_content',
                            'section_type' => 'content',
                            'section_data' => [
                                'ar' => [
                                    'title' => 'نحن SEOeStore',
                                    'content' => 'وكالة تسويق رقمي متخصصة في تحسين محركات البحث وتطوير المواقع الإلكترونية.',
                                ],
                                'en' => [
                                    'title' => 'We are SEOeStore',
                                    'content' => 'A digital marketing agency specialized in SEO and web development.',
                                ]
                            ],
                            'sort_order' => 1,
                        ]
                    ]
                ]
            ];
        }
        
        // Default pages for other sites
        return [
            [
                'name' => 'Home',
                'link' => '/',
                'slug' => 'home',
                'data' => [
                    'en' => [
                        'title' => "Welcome to {$siteName}",
                        'meta_description' => "Welcome to {$siteName} website",
                    ]
                ],
                'show_in_nav' => true,
                'status' => true,
                'page_theme_id' => $homeTheme->id ?? 1,
                'sections' => [
                    [
                        'section_name' => 'hero',
                        'section_type' => 'hero',
                        'section_data' => [
                            'en' => [
                                'title' => "Welcome to {$siteName}",
                                'subtitle' => 'Your success is our mission',
                                'cta_text' => 'Get Started',
                            ]
                        ],
                        'sort_order' => 1,
                    ]
                ]
            ]
        ];
    }
    
    private function getSiteTemplateData($siteName)
    {
        if ($siteName === 'SEOeStore Digital Agency') {
            return [
                'nav_data' => [
                    'links' => [
                        ['url' => '/', 'label' => ['ar' => 'الرئيسية', 'en' => 'Home']],
                        ['url' => '/about', 'label' => ['ar' => 'من نحن', 'en' => 'About']],
                        ['url' => '/services', 'label' => ['ar' => 'خدماتنا', 'en' => 'Services']],
                        ['url' => '/contact', 'label' => ['ar' => 'اتصل بنا', 'en' => 'Contact']],
                    ]
                ],
                'footer_data' => [
                    'links' => [
                        ['url' => '/privacy', 'label' => ['ar' => 'سياسة الخصوصية', 'en' => 'Privacy Policy']],
                        ['url' => '/terms', 'label' => ['ar' => 'الشروط والأحكام', 'en' => 'Terms & Conditions']],
                    ]
                ]
            ];
        }
        
        return [
            'nav_data' => [
                'links' => [
                    ['url' => '/', 'label' => ['en' => 'Home']],
                    ['url' => '/about', 'label' => ['en' => 'About']],
                    ['url' => '/services', 'label' => ['en' => 'Services']],
                    ['url' => '/contact', 'label' => ['en' => 'Contact']],
                ]
            ],
            'footer_data' => [
                'links' => [
                    ['url' => '/privacy', 'label' => ['en' => 'Privacy Policy']],
                    ['url' => '/terms', 'label' => ['en' => 'Terms of Service']],
                ]
            ]
        ];
    }
}
