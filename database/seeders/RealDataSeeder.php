<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class RealDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing data
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Truncate all tables
        DB::table('site_img_media')->truncate();
        DB::table('tpl_page_sections')->truncate();
        DB::table('tpl_site')->truncate();
        DB::table('tpl_pages')->truncate();
        DB::table('site_config')->truncate();
        DB::table('sites')->truncate();
        DB::table('theme_pages')->truncate();
        DB::table('theme_categories')->truncate();
        DB::table('tpl_layouts')->truncate();
        DB::table('tpl_langs')->truncate();
        DB::table('site_status')->truncate();
        DB::table('users')->truncate();
        DB::table('user_status')->truncate();
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 1. User Status
        DB::table('user_status')->insert([
            ['id' => 1, 'name' => 'Active'],
            ['id' => 2, 'name' => 'Inactive'],
            ['id' => 3, 'name' => 'Suspended'],
        ]);

        // 2. Site Status
        DB::table('site_status')->insert([
            ['id' => 1, 'status' => 'Active'],
            ['id' => 2, 'status' => 'Inactive'],
            ['id' => 3, 'status' => 'Under Construction'],
        ]);

        // 3. Languages
        DB::table('tpl_langs')->insert([
            ['id' => 1, 'code' => 'en', 'name' => 'English', 'dir' => 'ltr', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'code' => 'ar', 'name' => 'العربية', 'dir' => 'rtl', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'code' => 'fr', 'name' => 'Français', 'dir' => 'ltr', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'code' => 'es', 'name' => 'Español', 'dir' => 'ltr', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // 4. Users - Real people data
        $users = [
            [
                'id' => 1,
                'name' => 'Ahmed Mohamed',
                'email' => 'ahmed.mohamed@seoestore.com',
                'password' => Hash::make('12345678'),
                'role' => 'super-admin',
                'status_id' => 1,
                'preferred_language' => 'ar',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'name' => 'Sarah Johnson',
                'email' => 'sarah.johnson@techcorp.com',
                'password' => Hash::make('12345678'),
                'role' => 'admin',
                'status_id' => 1,
                'preferred_language' => 'en',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'name' => 'Omar Hassan',
                'email' => 'omar.hassan@digitalmedia.ae',
                'password' => Hash::make('12345678'),
                'role' => 'user',
                'status_id' => 1,
                'preferred_language' => 'ar',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'name' => 'Emily Rodriguez',
                'email' => 'emily.rodriguez@creativestudio.com',
                'password' => Hash::make('12345678'),
                'role' => 'user',
                'status_id' => 1,
                'preferred_language' => 'en',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 5,
                'name' => 'Khalid Al-Rashid',
                'email' => 'khalid.alrashid@sauditech.sa',
                'password' => Hash::make('12345678'),
                'role' => 'user',
                'status_id' => 1,
                'preferred_language' => 'ar',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('users')->insert($users);

        // 5. Theme Categories - Real business categories
        $themeCategories = [
            [
                'id' => 1,
                'name' => 'business',
                'description' => 'Professional business websites for companies, corporations, and enterprises',
                'icon' => 'briefcase',
                'sort_order' => 1,
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'name' => 'portfolio',
                'description' => 'Creative portfolios for designers, photographers, artists, and freelancers',
                'icon' => 'folder',
                'sort_order' => 2,
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'name' => 'ecommerce',
                'description' => 'Online stores and e-commerce platforms for selling products and services',
                'icon' => 'shopping-cart',
                'sort_order' => 3,
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'name' => 'seo-services',
                'description' => 'SEO agencies and digital marketing service providers',
                'icon' => 'trending-up',
                'sort_order' => 4,
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 5,
                'name' => 'restaurant',
                'description' => 'Restaurants, cafes, food delivery, and culinary businesses',
                'icon' => 'coffee',
                'sort_order' => 5,
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('theme_categories')->insert($themeCategories);

        // 6. Template Layouts - Real components
        $layouts = [
            // Headers
            [
                'id' => 1,
                'tpl_id' => 'header_modern_nav',
                'layout_type' => 'header',
                'name' => 'Modern Navigation Header',
                'description' => 'Clean and modern navigation header with logo and menu',
                'preview_image' => 'https://images.unsplash.com/photo-1551650975-87deedd944c3?w=800&h=400&fit=crop',
                'path' => 'headers.modern-nav',
                'default_config' => json_encode([
                    'logo_size' => 'medium',
                    'navigation_style' => 'horizontal',
                    'show_search' => true,
                    'sticky_header' => true
                ]),
                'content' => json_encode([
                    'en' => ['title' => 'Modern Navigation', 'subtitle' => 'Professional Header'],
                    'ar' => ['title' => 'تنقل حديث', 'subtitle' => 'رأس احترافي']
                ]),
                'configurable_fields' => json_encode(['logo', 'menu_items', 'colors', 'typography']),
                'status' => true,
                'sort_order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'tpl_id' => 'header_minimal_clean',
                'layout_type' => 'header',
                'name' => 'Minimal Clean Header',
                'description' => 'Minimalist header design with clean typography',
                'preview_image' => 'https://images.unsplash.com/photo-1522542550221-31fd19575a2d?w=800&h=400&fit=crop',
                'path' => 'headers.minimal-clean',
                'default_config' => json_encode([
                    'logo_position' => 'center',
                    'menu_style' => 'minimal',
                    'background' => 'transparent'
                ]),
                'content' => json_encode([
                    'en' => ['title' => 'Minimal Header', 'subtitle' => 'Clean Design'],
                    'ar' => ['title' => 'رأس بسيط', 'subtitle' => 'تصميم نظيف']
                ]),
                'configurable_fields' => json_encode(['logo', 'navigation', 'spacing']),
                'status' => true,
                'sort_order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Sections
            [
                'id' => 3,
                'tpl_id' => 'section_hero_banner',
                'layout_type' => 'section',
                'name' => 'Hero Banner Section',
                'description' => 'Eye-catching hero section with call-to-action',
                'preview_image' => 'https://images.unsplash.com/photo-1542744173-8e7e53415bb0?w=800&h=600&fit=crop',
                'path' => 'sections.hero-banner',
                'default_config' => json_encode([
                    'background_type' => 'image',
                    'overlay_opacity' => 0.5,
                    'text_alignment' => 'center',
                    'button_style' => 'primary'
                ]),
                'content' => json_encode([
                    'en' => [
                        'title' => 'Welcome to Our Business',
                        'subtitle' => 'We provide exceptional services that drive your success',
                        'cta_text' => 'Get Started Today'
                    ],
                    'ar' => [
                        'title' => 'مرحباً بكم في أعمالنا',
                        'subtitle' => 'نقدم خدمات استثنائية تقود نجاحكم',
                        'cta_text' => 'ابدأ اليوم'
                    ]
                ]),
                'configurable_fields' => json_encode(['background_image', 'title', 'subtitle', 'button']),
                'status' => true,
                'sort_order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'tpl_id' => 'section_about_us',
                'layout_type' => 'section',
                'name' => 'About Us Section',
                'description' => 'Professional about us section with team information',
                'preview_image' => 'https://images.unsplash.com/photo-1552664730-d307ca884978?w=800&h=600&fit=crop',
                'path' => 'sections.about-us',
                'default_config' => json_encode([
                    'layout' => 'two-column',
                    'image_position' => 'left',
                    'show_stats' => true
                ]),
                'content' => json_encode([
                    'en' => [
                        'title' => 'About Our Company',
                        'description' => 'We are a leading company with over 10 years of experience in delivering innovative solutions.',
                        'stats' => [
                            ['number' => '500+', 'label' => 'Happy Clients'],
                            ['number' => '50+', 'label' => 'Team Members'],
                            ['number' => '1000+', 'label' => 'Projects Completed']
                        ]
                    ],
                    'ar' => [
                        'title' => 'عن شركتنا',
                        'description' => 'نحن شركة رائدة بخبرة تزيد عن 10 سنوات في تقديم حلول مبتكرة.',
                        'stats' => [
                            ['number' => '500+', 'label' => 'عميل سعيد'],
                            ['number' => '50+', 'label' => 'عضو فريق'],
                            ['number' => '1000+', 'label' => 'مشروع مكتمل']
                        ]
                    ]
                ]),
                'configurable_fields' => json_encode(['image', 'title', 'description', 'statistics']),
                'status' => true,
                'sort_order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 5,
                'tpl_id' => 'section_services',
                'layout_type' => 'section',
                'name' => 'Services Section',
                'description' => 'Showcase your services with icons and descriptions',
                'preview_image' => 'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?w=800&h=600&fit=crop',
                'path' => 'sections.services',
                'default_config' => json_encode([
                    'columns' => 3,
                    'icon_style' => 'modern',
                    'card_style' => 'shadow'
                ]),
                'content' => json_encode([
                    'en' => [
                        'title' => 'Our Services',
                        'subtitle' => 'We offer comprehensive solutions for your business needs',
                        'services' => [
                            [
                                'icon' => 'code',
                                'title' => 'Web Development',
                                'description' => 'Custom web applications built with modern technologies'
                            ],
                            [
                                'icon' => 'smartphone',
                                'title' => 'Mobile Apps',
                                'description' => 'Native and cross-platform mobile applications'
                            ],
                            [
                                'icon' => 'trending-up',
                                'title' => 'Digital Marketing',
                                'description' => 'SEO, social media, and online advertising services'
                            ]
                        ]
                    ],
                    'ar' => [
                        'title' => 'خدماتنا',
                        'subtitle' => 'نقدم حلولاً شاملة لاحتياجات أعمالكم',
                        'services' => [
                            [
                                'icon' => 'code',
                                'title' => 'تطوير المواقع',
                                'description' => 'تطبيقات ويب مخصصة بتقنيات حديثة'
                            ],
                            [
                                'icon' => 'smartphone',
                                'title' => 'تطبيقات الجوال',
                                'description' => 'تطبيقات جوال أصلية ومتعددة المنصات'
                            ],
                            [
                                'icon' => 'trending-up',
                                'title' => 'التسويق الرقمي',
                                'description' => 'خدمات SEO ووسائل التواصل والإعلان الرقمي'
                            ]
                        ]
                    ]
                ]),
                'configurable_fields' => json_encode(['services', 'layout', 'icons', 'colors']),
                'status' => true,
                'sort_order' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 6,
                'tpl_id' => 'section_portfolio',
                'layout_type' => 'section',
                'name' => 'Portfolio Gallery',
                'description' => 'Display your work and projects in an elegant gallery',
                'preview_image' => 'https://images.unsplash.com/photo-1561070791-2526d30994b5?w=800&h=600&fit=crop',
                'path' => 'sections.portfolio',
                'default_config' => json_encode([
                    'gallery_type' => 'masonry',
                    'items_per_row' => 3,
                    'filter_enabled' => true
                ]),
                'content' => json_encode([
                    'en' => [
                        'title' => 'Our Portfolio',
                        'subtitle' => 'Explore our latest projects and achievements',
                        'projects' => [
                            [
                                'title' => 'E-commerce Platform',
                                'category' => 'Web Development',
                                'image' => 'https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?w=600&h=400&fit=crop',
                                'description' => 'Modern e-commerce solution with advanced features'
                            ],
                            [
                                'title' => 'Mobile Banking App',
                                'category' => 'Mobile Development',
                                'image' => 'https://images.unsplash.com/photo-1512941937669-90a1b58e7e9c?w=600&h=400&fit=crop',
                                'description' => 'Secure mobile banking application'
                            ],
                            [
                                'title' => 'Corporate Website',
                                'category' => 'Web Design',
                                'image' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=600&h=400&fit=crop',
                                'description' => 'Professional corporate website redesign'
                            ]
                        ]
                    ],
                    'ar' => [
                        'title' => 'معرض أعمالنا',
                        'subtitle' => 'استكشف أحدث مشاريعنا وإنجازاتنا',
                        'projects' => [
                            [
                                'title' => 'منصة التجارة الإلكترونية',
                                'category' => 'تطوير الويب',
                                'image' => 'https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?w=600&h=400&fit=crop',
                                'description' => 'حل تجارة إلكترونية حديث بميزات متقدمة'
                            ],
                            [
                                'title' => 'تطبيق البنك المحمول',
                                'category' => 'تطوير الجوال',
                                'image' => 'https://images.unsplash.com/photo-1512941937669-90a1b58e7e9c?w=600&h=400&fit=crop',
                                'description' => 'تطبيق بنكي محمول آمن'
                            ],
                            [
                                'title' => 'موقع الشركة',
                                'category' => 'تصميم الويب',
                                'image' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=600&h=400&fit=crop',
                                'description' => 'إعادة تصميم موقع الشركة احترافي'
                            ]
                        ]
                    ]
                ]),
                'configurable_fields' => json_encode(['projects', 'gallery_settings', 'filters']),
                'status' => true,
                'sort_order' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 7,
                'tpl_id' => 'section_contact',
                'layout_type' => 'section',
                'name' => 'Contact Us Section',
                'description' => 'Contact form with map and business information',
                'preview_image' => 'https://images.unsplash.com/photo-1423666639041-f56000c27a9a?w=800&h=600&fit=crop',
                'path' => 'sections.contact',
                'default_config' => json_encode([
                    'show_map' => true,
                    'form_fields' => ['name', 'email', 'phone', 'message'],
                    'contact_info' => true
                ]),
                'content' => json_encode([
                    'en' => [
                        'title' => 'Get In Touch',
                        'subtitle' => 'We would love to hear from you',
                        'address' => '123 Business Street, Dubai, UAE',
                        'phone' => '+971 4 123 4567',
                        'email' => 'info@company.com',
                        'hours' => 'Mon - Fri: 9:00 AM - 6:00 PM'
                    ],
                    'ar' => [
                        'title' => 'تواصل معنا',
                        'subtitle' => 'نود أن نسمع منكم',
                        'address' => '123 شارع الأعمال، دبي، الإمارات',
                        'phone' => '+971 4 123 4567',
                        'email' => 'info@company.com',
                        'hours' => 'الإثنين - الجمعة: 9:00 ص - 6:00 م'
                    ]
                ]),
                'configurable_fields' => json_encode(['contact_info', 'form_settings', 'map_location']),
                'status' => true,
                'sort_order' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Footers
            [
                'id' => 8,
                'tpl_id' => 'footer_modern_links',
                'layout_type' => 'footer',
                'name' => 'Modern Footer with Links',
                'description' => 'Comprehensive footer with multiple columns and social media',
                'preview_image' => 'https://images.unsplash.com/photo-1586953208448-b95a79798f07?w=800&h=400&fit=crop',
                'path' => 'footers.modern-links',
                'default_config' => json_encode([
                    'columns' => 4,
                    'show_social' => true,
                    'show_newsletter' => true,
                    'copyright_text' => 'All rights reserved'
                ]),
                'content' => json_encode([
                    'en' => [
                        'company_info' => 'Leading technology company providing innovative solutions since 2015.',
                        'quick_links' => ['About Us', 'Services', 'Portfolio', 'Contact'],
                        'services' => ['Web Development', 'Mobile Apps', 'Digital Marketing', 'SEO Services'],
                        'contact' => [
                            'address' => '123 Business Street, Dubai, UAE',
                            'phone' => '+971 4 123 4567',
                            'email' => 'info@company.com'
                        ]
                    ],
                    'ar' => [
                        'company_info' => 'شركة تقنية رائدة تقدم حلولاً مبتكرة منذ 2015.',
                        'quick_links' => ['عنا', 'خدماتنا', 'معرض الأعمال', 'اتصل بنا'],
                        'services' => ['تطوير الويب', 'تطبيقات الجوال', 'التسويق الرقمي', 'خدمات SEO'],
                        'contact' => [
                            'address' => '123 شارع الأعمال، دبي، الإمارات',
                            'phone' => '+971 4 123 4567',
                            'email' => 'info@company.com'
                        ]
                    ]
                ]),
                'configurable_fields' => json_encode(['company_info', 'links', 'social_media', 'newsletter']),
                'status' => true,
                'sort_order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 9,
                'tpl_id' => 'footer_minimal_simple',
                'layout_type' => 'footer',
                'name' => 'Minimal Simple Footer',
                'description' => 'Clean and simple footer with essential information only',
                'preview_image' => 'https://images.unsplash.com/photo-1434030216411-0b793f4b4173?w=800&h=400&fit=crop',
                'path' => 'footers.minimal-simple',
                'default_config' => json_encode([
                    'style' => 'centered',
                    'show_logo' => true,
                    'show_social' => false
                ]),
                'content' => json_encode([
                    'en' => [
                        'copyright' => '© 2025 Company Name. All rights reserved.',
                        'links' => ['Privacy Policy', 'Terms of Service', 'Contact']
                    ],
                    'ar' => [
                        'copyright' => '© 2025 اسم الشركة. جميع الحقوق محفوظة.',
                        'links' => ['سياسة الخصوصية', 'شروط الخدمة', 'اتصل بنا']
                    ]
                ]),
                'configurable_fields' => json_encode(['copyright', 'links', 'colors']),
                'status' => true,
                'sort_order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('tpl_layouts')->insert($layouts);

        // 7. Theme Pages - Real business themes
        $themePages = [
            [
                'id' => 1,
                'category_id' => 1, // business
                'theme_id' => 'corporate_modern',
                'name' => 'Corporate Modern',
                'description' => 'Professional corporate theme with modern design elements',
                'preview_image' => 'https://images.unsplash.com/photo-1497366216548-37526070297c?w=1200&h=800&fit=crop',
                'path' => 'themes.business.corporate-modern',
                'css_variables' => json_encode([
                    '--primary-color' => '#2563eb',
                    '--secondary-color' => '#64748b',
                    '--accent-color' => '#0ea5e9',
                    '--text-color' => '#1e293b',
                    '--bg-color' => '#ffffff',
                    '--font-family' => 'Inter, sans-serif'
                ]),
                'status' => true,
                'sort_order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'category_id' => 1, // business
                'theme_id' => 'business_professional',
                'name' => 'Business Professional',
                'description' => 'Classic professional business theme for established companies',
                'preview_image' => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=1200&h=800&fit=crop',
                'path' => 'themes.business.professional',
                'css_variables' => json_encode([
                    '--primary-color' => '#1e40af',
                    '--secondary-color' => '#475569',
                    '--accent-color' => '#3b82f6',
                    '--text-color' => '#0f172a',
                    '--bg-color' => '#f8fafc'
                ]),
                'status' => true,
                'sort_order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'category_id' => 2, // portfolio
                'theme_id' => 'creative_portfolio',
                'name' => 'Creative Portfolio',
                'description' => 'Modern portfolio theme for designers and creatives',
                'preview_image' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=1200&h=800&fit=crop',
                'path' => 'themes.portfolio.creative',
                'css_variables' => json_encode([
                    '--primary-color' => '#7c3aed',
                    '--secondary-color' => '#6b7280',
                    '--accent-color' => '#a855f7',
                    '--text-color' => '#111827',
                    '--bg-color' => '#ffffff'
                ]),
                'status' => true,
                'sort_order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'category_id' => 3, // ecommerce
                'theme_id' => 'modern_shop',
                'name' => 'Modern Shop',
                'description' => 'Clean and modern e-commerce theme with advanced features',
                'preview_image' => 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=1200&h=800&fit=crop',
                'path' => 'themes.ecommerce.modern-shop',
                'css_variables' => json_encode([
                    '--primary-color' => '#059669',
                    '--secondary-color' => '#6b7280',
                    '--accent-color' => '#10b981',
                    '--text-color' => '#374151',
                    '--bg-color' => '#f9fafb'
                ]),
                'status' => true,
                'sort_order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 5,
                'category_id' => 4, // seo-services
                'theme_id' => 'seo_agency',
                'name' => 'SEO Agency',
                'description' => 'Professional theme for SEO agencies and digital marketing companies',
                'preview_image' => 'https://images.unsplash.com/photo-1432888622747-4eb9a8efeb07?w=1200&h=800&fit=crop',
                'path' => 'themes.seo.agency',
                'css_variables' => json_encode([
                    '--primary-color' => '#dc2626',
                    '--secondary-color' => '#64748b',
                    '--accent-color' => '#ef4444',
                    '--text-color' => '#1f2937',
                    '--bg-color' => '#ffffff'
                ]),
                'status' => true,
                'sort_order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 6,
                'category_id' => 5, // restaurant
                'theme_id' => 'restaurant_elegant',
                'name' => 'Restaurant Elegant',
                'description' => 'Elegant restaurant theme with menu showcase and booking features',
                'preview_image' => 'https://images.unsplash.com/photo-1414235077428-338989a2e8c0?w=1200&h=800&fit=crop',
                'path' => 'themes.restaurant.elegant',
                'css_variables' => json_encode([
                    '--primary-color' => '#92400e',
                    '--secondary-color' => '#78716c',
                    '--accent-color' => '#d97706',
                    '--text-color' => '#292524',
                    '--bg-color' => '#fef7ed'
                ]),
                'status' => true,
                'sort_order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('theme_pages')->insert($themePages);

        // 8. Sites - Real business websites
        $sites = [
            [
                'id' => 1,
                'user_id' => 1,
                'site_name' => 'SEOeStore Digital Agency',
                'url' => 'https://seoestore.com',
                'status_id' => 1,
                'active_header_id' => 1,
                'active_footer_id' => 8,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'user_id' => 2,
                'site_name' => 'TechCorp Solutions',
                'url' => 'https://techcorp-solutions.com',
                'status_id' => 1,
                'active_header_id' => 2,
                'active_footer_id' => 9,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'user_id' => 3,
                'site_name' => 'Digital Media UAE',
                'url' => 'https://digitalmedia.ae',
                'status_id' => 1,
                'active_header_id' => 1,
                'active_footer_id' => 8,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'user_id' => 4,
                'site_name' => 'Creative Studio Portfolio',
                'url' => 'https://creativestudio.com',
                'status_id' => 1,
                'active_header_id' => 2,
                'active_footer_id' => 9,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 5,
                'user_id' => 5,
                'site_name' => 'Saudi Tech Hub',
                'url' => 'https://sauditech.sa',
                'status_id' => 1,
                'active_header_id' => 1,
                'active_footer_id' => 8,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('sites')->insert($sites);

        // 9. Site Configuration
        $siteConfigs = [
            [
                'id' => 1,
                'site_id' => 1,
                'settings' => json_encode([
                    'timezone' => 'Asia/Dubai',
                    'maintenance_mode' => false,
                    'analytics_id' => 'GA-123456789',
                    'contact_email' => 'info@seoestore.com'
                ]),
                'data' => json_encode([
                    'logo' => 'https://images.unsplash.com/photo-1599305445671-ac291c95aaa9?w=200&h=80&fit=crop',
                    'favicon' => 'https://images.unsplash.com/photo-1599305445671-ac291c95aaa9?w=32&h=32&fit=crop',
                    'meta' => [
                        'title' => 'SEOeStore - Digital Marketing Agency',
                        'description' => 'Leading digital marketing agency in Dubai providing SEO, web development, and online marketing services.',
                        'keywords' => 'SEO, digital marketing, web development, Dubai, UAE'
                    ]
                ]),
                'language_code' => json_encode([
                    'languages' => ['en', 'ar'],
                    'primary' => 'en'
                ]),
                'tpl_name' => 'business',
                'tpl_colors' => json_encode([
                    'primary' => '#2563eb',
                    'secondary' => '#64748b',
                    'accent' => '#0ea5e9'
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'site_id' => 2,
                'settings' => json_encode([
                    'timezone' => 'America/New_York',
                    'maintenance_mode' => false,
                    'analytics_id' => 'GA-987654321'
                ]),
                'data' => json_encode([
                    'logo' => 'https://images.unsplash.com/photo-1560472354-b33ff0c44a43?w=200&h=80&fit=crop',
                    'favicon' => 'https://images.unsplash.com/photo-1560472354-b33ff0c44a43?w=32&h=32&fit=crop',
                    'meta' => [
                        'title' => 'TechCorp Solutions - Technology Consulting',
                        'description' => 'Professional technology consulting and software development services.',
                        'keywords' => 'technology, consulting, software development, IT services'
                    ]
                ]),
                'language_code' => json_encode([
                    'languages' => ['en'],
                    'primary' => 'en'
                ]),
                'tpl_name' => 'corporate',
                'tpl_colors' => json_encode([
                    'primary' => '#1e40af',
                    'secondary' => '#475569',
                    'accent' => '#3b82f6'
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'site_id' => 3,
                'settings' => json_encode([
                    'timezone' => 'Asia/Dubai',
                    'maintenance_mode' => false
                ]),
                'data' => json_encode([
                    'logo' => 'https://images.unsplash.com/photo-1611224923853-80b023f02d71?w=200&h=80&fit=crop',
                    'meta' => [
                        'title' => 'Digital Media UAE - Creative Agency',
                        'description' => 'Creative digital media agency specializing in branding and marketing.',
                        'keywords' => 'digital media, branding, creative agency, UAE'
                    ]
                ]),
                'language_code' => json_encode([
                    'languages' => ['ar', 'en'],
                    'primary' => 'ar'
                ]),
                'tpl_name' => 'creative',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'site_id' => 4,
                'settings' => json_encode([
                    'timezone' => 'America/Los_Angeles',
                    'maintenance_mode' => false
                ]),
                'data' => json_encode([
                    'logo' => 'https://images.unsplash.com/photo-1626785774573-4b799315345d?w=200&h=80&fit=crop',
                    'meta' => [
                        'title' => 'Creative Studio - Design Portfolio',
                        'description' => 'Award-winning design studio creating exceptional digital experiences.',
                        'keywords' => 'design, portfolio, creative, studio, web design'
                    ]
                ]),
                'language_code' => json_encode([
                    'languages' => ['en'],
                    'primary' => 'en'
                ]),
                'tpl_name' => 'portfolio',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 5,
                'site_id' => 5,
                'settings' => json_encode([
                    'timezone' => 'Asia/Riyadh',
                    'maintenance_mode' => false
                ]),
                'data' => json_encode([
                    'logo' => 'https://images.unsplash.com/photo-1531973576160-7125cd663d86?w=200&h=80&fit=crop',
                    'meta' => [
                        'title' => 'Saudi Tech Hub - Technology Innovation',
                        'description' => 'Leading technology hub in Saudi Arabia driving digital transformation.',
                        'keywords' => 'technology, innovation, Saudi Arabia, digital transformation'
                    ]
                ]),
                'language_code' => json_encode([
                    'languages' => ['ar', 'en'],
                    'primary' => 'ar'
                ]),
                'tpl_name' => 'technology',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('site_config')->insert($siteConfigs);

        // 10. Template Pages - Real website pages
        $tplPages = [
            // SEOeStore Digital Agency Pages
            [
                'id' => 1,
                'site_id' => 1,
                'name' => 'Home',
                'link' => '/',
                'slug' => 'home',
                'data' => json_encode([
                    'en' => [
                        'title' => 'SEOeStore - Leading Digital Marketing Agency',
                        'meta_description' => 'Professional SEO and digital marketing services in Dubai, UAE. Boost your online presence with our expert team.',
                        'meta_keywords' => 'SEO Dubai, digital marketing UAE, web development'
                    ],
                    'ar' => [
                        'title' => 'SEOeStore - وكالة التسويق الرقمي الرائدة',
                        'meta_description' => 'خدمات SEO والتسويق الرقمي المهنية في دبي، الإمارات. عزز حضورك الرقمي مع فريقنا المتخصص.',
                        'meta_keywords' => 'SEO دبي، التسويق الرقمي الإمارات، تطوير المواقع'
                    ]
                ]),
                'show_in_nav' => true,
                'status' => true,
                'page_theme_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'site_id' => 1,
                'name' => 'About Us',
                'link' => '/about',
                'slug' => 'about',
                'data' => json_encode([
                    'en' => [
                        'title' => 'About SEOeStore - Our Story and Mission',
                        'meta_description' => 'Learn about SEOeStore\'s journey, mission, and the expert team behind our digital marketing success.',
                        'meta_keywords' => 'about SEOeStore, digital marketing team, company history'
                    ],
                    'ar' => [
                        'title' => 'عن SEOeStore - قصتنا ورسالتنا',
                        'meta_description' => 'تعرف على رحلة SEOeStore ورسالتنا والفريق المتخصص وراء نجاحنا في التسويق الرقمي.',
                        'meta_keywords' => 'عن SEOeStore، فريق التسويق الرقمي، تاريخ الشركة'
                    ]
                ]),
                'show_in_nav' => true,
                'status' => true,
                'page_theme_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'site_id' => 1,
                'name' => 'Services',
                'link' => '/services',
                'slug' => 'services',
                'data' => json_encode([
                    'en' => [
                        'title' => 'Digital Marketing Services - SEO, PPC, Social Media',
                        'meta_description' => 'Comprehensive digital marketing services including SEO, PPC, social media marketing, and web development.',
                        'meta_keywords' => 'SEO services, PPC advertising, social media marketing, web development'
                    ],
                    'ar' => [
                        'title' => 'خدمات التسويق الرقمي - SEO، PPC، وسائل التواصل',
                        'meta_description' => 'خدمات التسويق الرقمي الشاملة تشمل SEO، PPC، تسويق وسائل التواصل، وتطوير المواقع.',
                        'meta_keywords' => 'خدمات SEO، إعلانات PPC، تسويق وسائل التواصل، تطوير المواقع'
                    ]
                ]),
                'show_in_nav' => true,
                'status' => true,
                'page_theme_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'site_id' => 1,
                'name' => 'Portfolio',
                'link' => '/portfolio',
                'slug' => 'portfolio',
                'data' => json_encode([
                    'en' => [
                        'title' => 'Our Portfolio - Successful Digital Marketing Projects',
                        'meta_description' => 'Explore our portfolio of successful digital marketing campaigns and web development projects.',
                        'meta_keywords' => 'portfolio, digital marketing projects, case studies, success stories'
                    ],
                    'ar' => [
                        'title' => 'معرض أعمالنا - مشاريع التسويق الرقمي الناجحة',
                        'meta_description' => 'استكشف معرض أعمالنا من حملات التسويق الرقمي الناجحة ومشاريع تطوير المواقع.',
                        'meta_keywords' => 'معرض الأعمال، مشاريع التسويق الرقمي، دراسات الحالة، قصص النجاح'
                    ]
                ]),
                'show_in_nav' => true,
                'status' => true,
                'page_theme_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 5,
                'site_id' => 1,
                'name' => 'Contact',
                'link' => '/contact',
                'slug' => 'contact',
                'data' => json_encode([
                    'en' => [
                        'title' => 'Contact SEOeStore - Get Your Free Consultation',
                        'meta_description' => 'Contact SEOeStore for a free digital marketing consultation. Located in Dubai, UAE.',
                        'meta_keywords' => 'contact SEOeStore, free consultation, Dubai digital marketing'
                    ],
                    'ar' => [
                        'title' => 'اتصل بـ SEOeStore - احصل على استشارة مجانية',
                        'meta_description' => 'اتصل بـ SEOeStore للحصول على استشارة مجانية في التسويق الرقمي. موجودون في دبي، الإمارات.',
                        'meta_keywords' => 'اتصل SEOeStore، استشارة مجانية، التسويق الرقمي دبي'
                    ]
                ]),
                'show_in_nav' => true,
                'status' => true,
                'page_theme_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // TechCorp Solutions Pages
            [
                'id' => 6,
                'site_id' => 2,
                'name' => 'Home',
                'link' => '/',
                'slug' => 'home',
                'data' => json_encode([
                    'en' => [
                        'title' => 'TechCorp Solutions - Enterprise Technology Consulting',
                        'meta_description' => 'Leading technology consulting firm providing enterprise solutions, software development, and IT consulting services.',
                        'meta_keywords' => 'technology consulting, enterprise solutions, software development, IT consulting'
                    ]
                ]),
                'show_in_nav' => true,
                'status' => true,
                'page_theme_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 7,
                'site_id' => 2,
                'name' => 'Solutions',
                'link' => '/solutions',
                'slug' => 'solutions',
                'data' => json_encode([
                    'en' => [
                        'title' => 'Enterprise Technology Solutions',
                        'meta_description' => 'Comprehensive technology solutions for modern enterprises including cloud migration, digital transformation, and custom software.',
                        'meta_keywords' => 'enterprise solutions, cloud migration, digital transformation, custom software'
                    ]
                ]),
                'show_in_nav' => true,
                'status' => true,
                'page_theme_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 8,
                'site_id' => 2,
                'name' => 'Case Studies',
                'link' => '/case-studies',
                'slug' => 'case-studies',
                'data' => json_encode([
                    'en' => [
                        'title' => 'Success Stories and Case Studies',
                        'meta_description' => 'Real-world case studies showcasing our successful technology implementations and business transformations.',
                        'meta_keywords' => 'case studies, success stories, technology implementations, business transformation'
                    ]
                ]),
                'show_in_nav' => true,
                'status' => true,
                'page_theme_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // Digital Media UAE Pages
            [
                'id' => 9,
                'site_id' => 3,
                'name' => 'الرئيسية',
                'link' => '/',
                'slug' => 'home',
                'data' => json_encode([
                    'ar' => [
                        'title' => 'Digital Media UAE - وكالة الإعلام الرقمي الإبداعية',
                        'meta_description' => 'وكالة إعلام رقمي إبداعية متخصصة في العلامات التجارية والتسويق في دولة الإمارات.',
                        'meta_keywords' => 'الإعلام الرقمي، العلامات التجارية، وكالة إبداعية، الإمارات'
                    ],
                    'en' => [
                        'title' => 'Digital Media UAE - Creative Digital Agency',
                        'meta_description' => 'Creative digital media agency specializing in branding and marketing in the UAE.',
                        'meta_keywords' => 'digital media, branding, creative agency, UAE'
                    ]
                ]),
                'show_in_nav' => true,
                'status' => true,
                'page_theme_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 10,
                'site_id' => 3,
                'name' => 'خدماتنا',
                'link' => '/services',
                'slug' => 'services',
                'data' => json_encode([
                    'ar' => [
                        'title' => 'خدماتنا - تصميم العلامات التجارية والتسويق الرقمي',
                        'meta_description' => 'خدمات شاملة في تصميم العلامات التجارية، التسويق الرقمي، وإنتاج المحتوى الإبداعي.',
                        'meta_keywords' => 'تصميم العلامات التجارية، التسويق الرقمي، المحتوى الإبداعي'
                    ],
                    'en' => [
                        'title' => 'Our Services - Branding and Digital Marketing',
                        'meta_description' => 'Comprehensive services in brand design, digital marketing, and creative content production.',
                        'meta_keywords' => 'brand design, digital marketing, creative content'
                    ]
                ]),
                'show_in_nav' => true,
                'status' => true,
                'page_theme_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // Creative Studio Portfolio Pages
            [
                'id' => 11,
                'site_id' => 4,
                'name' => 'Home',
                'link' => '/',
                'slug' => 'home',
                'data' => json_encode([
                    'en' => [
                        'title' => 'Creative Studio - Award-Winning Design Portfolio',
                        'meta_description' => 'Award-winning design studio creating exceptional digital experiences, websites, and brand identities.',
                        'meta_keywords' => 'design studio, portfolio, web design, brand identity, digital experiences'
                    ]
                ]),
                'show_in_nav' => true,
                'status' => true,
                'page_theme_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 12,
                'site_id' => 4,
                'name' => 'Portfolio',
                'link' => '/portfolio',
                'slug' => 'portfolio',
                'data' => json_encode([
                    'en' => [
                        'title' => 'Design Portfolio - Our Creative Work',
                        'meta_description' => 'Explore our creative portfolio featuring web design, brand identity, and digital experience projects.',
                        'meta_keywords' => 'design portfolio, creative work, web design projects, brand identity'
                    ]
                ]),
                'show_in_nav' => true,
                'status' => true,
                'page_theme_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // Saudi Tech Hub Pages
            [
                'id' => 13,
                'site_id' => 5,
                'name' => 'الرئيسية',
                'link' => '/',
                'slug' => 'home',
                'data' => json_encode([
                    'ar' => [
                        'title' => 'Saudi Tech Hub - مركز التقنية السعودي للابتكار',
                        'meta_description' => 'مركز تقني رائد في المملكة العربية السعودية يقود التحول الرقمي والابتكار التقني.',
                        'meta_keywords' => 'التقنية، الابتكار، السعودية، التحول الرقمي'
                    ],
                    'en' => [
                        'title' => 'Saudi Tech Hub - Technology Innovation Center',
                        'meta_description' => 'Leading technology hub in Saudi Arabia driving digital transformation and technological innovation.',
                        'meta_keywords' => 'technology, innovation, Saudi Arabia, digital transformation'
                    ]
                ]),
                'show_in_nav' => true,
                'status' => true,
                'page_theme_id' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 14,
                'site_id' => 5,
                'name' => 'الابتكار',
                'link' => '/innovation',
                'slug' => 'innovation',
                'data' => json_encode([
                    'ar' => [
                        'title' => 'مشاريع الابتكار التقني',
                        'meta_description' => 'استكشف مشاريعنا الرائدة في مجال الابتكار التقني والتحول الرقمي في المملكة.',
                        'meta_keywords' => 'مشاريع الابتكار، التحول الرقمي، التقنية السعودية'
                    ],
                    'en' => [
                        'title' => 'Technology Innovation Projects',
                        'meta_description' => 'Explore our leading technology innovation and digital transformation projects in the Kingdom.',
                        'meta_keywords' => 'innovation projects, digital transformation, Saudi technology'
                    ]
                ]),
                'show_in_nav' => true,
                'status' => true,
                'page_theme_id' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('tpl_pages')->insert($tplPages);

        // 11. Page Sections - Real content sections
        $pageSections = [
            // SEOeStore Home Page Sections
            [
                'id' => 1,
                'page_id' => 1,
                'tpl_layouts_id' => 3, // Hero Banner
                'site_id' => 1,
                'name' => 'Hero Section',
                'content' => json_encode([
                    'en' => [
                        'title' => 'Boost Your Online Presence with Expert SEO',
                        'subtitle' => 'Leading digital marketing agency in Dubai helping businesses grow online through proven SEO strategies and digital marketing solutions.',
                        'cta_text' => 'Get Free SEO Audit',
                        'cta_link' => '/contact',
                        'background_image' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=1920&h=1080&fit=crop'
                    ],
                    'ar' => [
                        'title' => 'عزز حضورك الرقمي مع خبراء SEO',
                        'subtitle' => 'وكالة التسويق الرقمي الرائدة في دبي تساعد الشركات على النمو عبر الإنترنت من خلال استراتيجيات SEO المثبتة وحلول التسويق الرقمي.',
                        'cta_text' => 'احصل على تدقيق SEO مجاني',
                        'cta_link' => '/contact',
                        'background_image' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=1920&h=1080&fit=crop'
                    ]
                ]),
                'custom_styles' => '.hero-section { background: linear-gradient(rgba(37, 99, 235, 0.8), rgba(37, 99, 235, 0.6)); }',
                'status' => true,
                'sort_order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'page_id' => 1,
                'tpl_layouts_id' => 5, // Services
                'site_id' => 1,
                'name' => 'Our Services',
                'content' => json_encode([
                    'en' => [
                        'title' => 'Our Digital Marketing Services',
                        'subtitle' => 'Comprehensive digital solutions to grow your business',
                        'services' => [
                            [
                                'icon' => 'search',
                                'title' => 'Search Engine Optimization',
                                'description' => 'Improve your website ranking on Google with our proven SEO strategies and techniques.',
                                'image' => 'https://images.unsplash.com/photo-1432888622747-4eb9a8efeb07?w=400&h=300&fit=crop'
                            ],
                            [
                                'icon' => 'target',
                                'title' => 'Pay-Per-Click Advertising',
                                'description' => 'Drive immediate traffic and conversions with strategic PPC campaigns on Google and social media.',
                                'image' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=400&h=300&fit=crop'
                            ],
                            [
                                'icon' => 'users',
                                'title' => 'Social Media Marketing',
                                'description' => 'Build your brand presence and engage with customers on all major social media platforms.',
                                'image' => 'https://images.unsplash.com/photo-1611224923853-80b023f02d71?w=400&h=300&fit=crop'
                            ],
                            [
                                'icon' => 'globe',
                                'title' => 'Web Development',
                                'description' => 'Custom website development with modern technologies and SEO-friendly architecture.',
                                'image' => 'https://images.unsplash.com/photo-1467232004584-a241de8bcf5d?w=400&h=300&fit=crop'
                            ],
                            [
                                'icon' => 'mail',
                                'title' => 'Email Marketing',
                                'description' => 'Nurture leads and retain customers with personalized email marketing campaigns.',
                                'image' => 'https://images.unsplash.com/photo-1563013544-824ae1b704d3?w=400&h=300&fit=crop'
                            ],
                            [
                                'icon' => 'bar-chart',
                                'title' => 'Analytics & Reporting',
                                'description' => 'Track your marketing performance with detailed analytics and monthly reports.',
                                'image' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=400&h=300&fit=crop'
                            ]
                        ]
                    ],
                    'ar' => [
                        'title' => 'خدمات التسويق الرقمي',
                        'subtitle' => 'حلول رقمية شاملة لنمو أعمالكم',
                        'services' => [
                            [
                                'icon' => 'search',
                                'title' => 'تحسين محركات البحث',
                                'description' => 'حسن ترتيب موقعك في Google باستراتيجيات SEO المثبتة والتقنيات المتقدمة.',
                                'image' => 'https://images.unsplash.com/photo-1432888622747-4eb9a8efeb07?w=400&h=300&fit=crop'
                            ],
                            [
                                'icon' => 'target',
                                'title' => 'إعلانات الدفع مقابل النقرة',
                                'description' => 'جذب زيارات فورية وتحويلات بحملات PPC استراتيجية على Google ووسائل التواصل.',
                                'image' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=400&h=300&fit=crop'
                            ],
                            [
                                'icon' => 'users',
                                'title' => 'تسويق وسائل التواصل',
                                'description' => 'ابن حضور علامتك التجارية وتفاعل مع العملاء على جميع منصات التواصل الرئيسية.',
                                'image' => 'https://images.unsplash.com/photo-1611224923853-80b023f02d71?w=400&h=300&fit=crop'
                            ],
                            [
                                'icon' => 'globe',
                                'title' => 'تطوير المواقع',
                                'description' => 'تطوير مواقع مخصصة بتقنيات حديثة وبنية محسنة لمحركات البحث.',
                                'image' => 'https://images.unsplash.com/photo-1467232004584-a241de8bcf5d?w=400&h=300&fit=crop'
                            ],
                            [
                                'icon' => 'mail',
                                'title' => 'التسويق بالبريد الإلكتروني',
                                'description' => 'رعاية العملاء المحتملين والاحتفاظ بالعملاء بحملات بريد إلكتروني مخصصة.',
                                'image' => 'https://images.unsplash.com/photo-1563013544-824ae1b704d3?w=400&h=300&fit=crop'
                            ],
                            [
                                'icon' => 'bar-chart',
                                'title' => 'التحليلات والتقارير',
                                'description' => 'تتبع أداء التسويق بتحليلات مفصلة وتقارير شهرية.',
                                'image' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=400&h=300&fit=crop'
                            ]
                        ]
                    ]
                ]),
                'status' => true,
                'sort_order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'page_id' => 1,
                'tpl_layouts_id' => 4, // About Us
                'site_id' => 1,
                'name' => 'About SEOeStore',
                'content' => json_encode([
                    'en' => [
                        'title' => 'Why Choose SEOeStore?',
                        'description' => 'With over 8 years of experience in digital marketing, SEOeStore has helped hundreds of businesses in the UAE and Middle East achieve their online marketing goals. Our team of certified digital marketing experts uses data-driven strategies to deliver measurable results.',
                        'image' => 'https://images.unsplash.com/photo-1552664730-d307ca884978?w=800&h=600&fit=crop',
                        'stats' => [
                            ['number' => '500+', 'label' => 'Happy Clients'],
                            ['number' => '8+', 'label' => 'Years Experience'],
                            ['number' => '1200+', 'label' => 'Projects Completed'],
                            ['number' => '15+', 'label' => 'Expert Team Members']
                        ]
                    ],
                    'ar' => [
                        'title' => 'لماذا تختار SEOeStore؟',
                        'description' => 'بخبرة تزيد عن 8 سنوات في التسويق الرقمي، ساعدت SEOeStore مئات الشركات في الإمارات والشرق الأوسط لتحقيق أهدافها التسويقية عبر الإنترنت. فريقنا من خبراء التسويق الرقمي المعتمدين يستخدم استراتيجيات قائمة على البيانات لتحقيق نتائج قابلة للقياس.',
                        'image' => 'https://images.unsplash.com/photo-1552664730-d307ca884978?w=800&h=600&fit=crop',
                        'stats' => [
                            ['number' => '500+', 'label' => 'عميل سعيد'],
                            ['number' => '8+', 'label' => 'سنوات خبرة'],
                            ['number' => '1200+', 'label' => 'مشروع مكتمل'],
                            ['number' => '15+', 'label' => 'عضو فريق خبير']
                        ]
                    ]
                ]),
                'status' => true,
                'sort_order' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'page_id' => 1,
                'tpl_layouts_id' => 7, // Contact
                'site_id' => 1,
                'name' => 'Contact Us',
                'content' => json_encode([
                    'en' => [
                        'title' => 'Ready to Grow Your Business?',
                        'subtitle' => 'Contact us today for a free consultation and discover how we can help you achieve your digital marketing goals.',
                        'address' => 'Office 1204, Al Manara Tower, Business Bay, Dubai, UAE',
                        'phone' => '+971 4 567 8901',
                        'email' => 'info@seoestore.com',
                        'hours' => 'Sunday - Thursday: 9:00 AM - 6:00 PM',
                        'map_location' => '25.1972,55.2744' // Business Bay coordinates
                    ],
                    'ar' => [
                        'title' => 'مستعد لتنمية أعمالك؟',
                        'subtitle' => 'اتصل بنا اليوم للحصول على استشارة مجانية واكتشف كيف يمكننا مساعدتك في تحقيق أهدافك في التسويق الرقمي.',
                        'address' => 'مكتب 1204، برج المنارة، الخليج التجاري، دبي، الإمارات',
                        'phone' => '+971 4 567 8901',
                        'email' => 'info@seoestore.com',
                        'hours' => 'الأحد - الخميس: 9:00 ص - 6:00 م',
                        'map_location' => '25.1972,55.2744'
                    ]
                ]),
                'status' => true,
                'sort_order' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // TechCorp Solutions Home Page Sections
            [
                'id' => 5,
                'page_id' => 6,
                'tpl_layouts_id' => 3,
                'site_id' => 2,
                'name' => 'Hero Section',
                'content' => json_encode([
                    'en' => [
                        'title' => 'Enterprise Technology Solutions That Drive Success',
                        'subtitle' => 'Transform your business with cutting-edge technology consulting, custom software development, and digital transformation strategies.',
                        'cta_text' => 'Schedule Consultation',
                        'cta_link' => '/contact',
                        'background_image' => 'https://images.unsplash.com/photo-1497366216548-37526070297c?w=1920&h=1080&fit=crop'
                    ]
                ]),
                'status' => true,
                'sort_order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 6,
                'page_id' => 6,
                'tpl_layouts_id' => 5,
                'site_id' => 2,
                'name' => 'Technology Solutions',
                'content' => json_encode([
                    'en' => [
                        'title' => 'Comprehensive Technology Services',
                        'subtitle' => 'End-to-end technology solutions for modern enterprises',
                        'services' => [
                            [
                                'icon' => 'cloud',
                                'title' => 'Cloud Migration',
                                'description' => 'Seamless migration to cloud platforms with zero downtime and enhanced security.',
                                'image' => 'https://images.unsplash.com/photo-1451187580459-43490279c0fa?w=400&h=300&fit=crop'
                            ],
                            [
                                'icon' => 'code',
                                'title' => 'Custom Software Development',
                                'description' => 'Tailored software solutions built to meet your specific business requirements.',
                                'image' => 'https://images.unsplash.com/photo-1555066931-4365d14bab8c?w=400&h=300&fit=crop'
                            ],
                            [
                                'icon' => 'shield',
                                'title' => 'Cybersecurity Solutions',
                                'description' => 'Comprehensive security strategies to protect your digital assets and data.',
                                'image' => 'https://images.unsplash.com/photo-1563013544-824ae1b704d3?w=400&h=300&fit=crop'
                            ],
                            [
                                'icon' => 'database',
                                'title' => 'Data Analytics',
                                'description' => 'Transform raw data into actionable insights for better business decisions.',
                                'image' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=400&h=300&fit=crop'
                            ]
                        ]
                    ]
                ]),
                'status' => true,
                'sort_order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // Digital Media UAE Home Page (Arabic)
            [
                'id' => 7,
                'page_id' => 9,
                'tpl_layouts_id' => 3,
                'site_id' => 3,
                'name' => 'القسم الرئيسي',
                'content' => json_encode([
                    'ar' => [
                        'title' => 'نبدع في الإعلام الرقمي لنصنع الفرق',
                        'subtitle' => 'وكالة إعلام رقمي متخصصة في تصميم العلامات التجارية والتسويق الإبداعي في دولة الإمارات العربية المتحدة.',
                        'cta_text' => 'اطلب عرض سعر',
                        'cta_link' => '/contact',
                        'background_image' => 'https://images.unsplash.com/photo-1611224923853-80b023f02d71?w=1920&h=1080&fit=crop'
                    ],
                    'en' => [
                        'title' => 'We Create Digital Media That Makes a Difference',
                        'subtitle' => 'Creative digital media agency specializing in brand design and innovative marketing in the United Arab Emirates.',
                        'cta_text' => 'Get Quote',
                        'cta_link' => '/contact',
                        'background_image' => 'https://images.unsplash.com/photo-1611224923853-80b023f02d71?w=1920&h=1080&fit=crop'
                    ]
                ]),
                'status' => true,
                'sort_order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // Creative Studio Portfolio Home
            [
                'id' => 8,
                'page_id' => 11,
                'tpl_layouts_id' => 6, // Portfolio Gallery
                'site_id' => 4,
                'name' => 'Featured Work',
                'content' => json_encode([
                    'en' => [
                        'title' => 'Featured Creative Projects',
                        'subtitle' => 'Explore our award-winning design work and creative solutions',
                        'projects' => [
                            [
                                'title' => 'FinTech Mobile App',
                                'category' => 'Mobile Design',
                                'image' => 'https://images.unsplash.com/photo-1512941937669-90a1b58e7e9c?w=600&h=400&fit=crop',
                                'description' => 'Modern fintech app with intuitive UX design and seamless user experience',
                                'client' => 'PayTech Solutions',
                                'year' => '2024'
                            ],
                            [
                                'title' => 'Luxury Brand Website',
                                'category' => 'Web Design',
                                'image' => 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=600&h=400&fit=crop',
                                'description' => 'Elegant e-commerce website for luxury fashion brand',
                                'client' => 'Elite Fashion',
                                'year' => '2024'
                            ],
                            [
                                'title' => 'Corporate Rebrand',
                                'category' => 'Brand Identity',
                                'image' => 'https://images.unsplash.com/photo-1561070791-2526d30994b5?w=600&h=400&fit=crop',
                                'description' => 'Complete brand identity redesign for tech startup',
                                'client' => 'InnovaTech',
                                'year' => '2024'
                            ]
                        ]
                    ]
                ]),
                'status' => true,
                'sort_order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // Saudi Tech Hub Home (Arabic)
            [
                'id' => 9,
                'page_id' => 13,
                'tpl_layouts_id' => 3,
                'site_id' => 5,
                'name' => 'القسم الرئيسي',
                'content' => json_encode([
                    'ar' => [
                        'title' => 'نقود مستقبل التقنية في المملكة',
                        'subtitle' => 'مركز التقنية السعودي الرائد في الابتكار والتحول الرقمي، نبني جسراً نحو مستقبل تقني مشرق.',
                        'cta_text' => 'انضم إلينا',
                        'cta_link' => '/join',
                        'background_image' => 'https://images.unsplash.com/photo-1531973576160-7125cd663d86?w=1920&h=1080&fit=crop'
                    ],
                    'en' => [
                        'title' => 'Leading the Future of Technology in the Kingdom',
                        'subtitle' => 'Saudi Arabia\'s premier technology hub for innovation and digital transformation, building bridges to a bright technological future.',
                        'cta_text' => 'Join Us',
                        'cta_link' => '/join',
                        'background_image' => 'https://images.unsplash.com/photo-1531973576160-7125cd663d86?w=1920&h=1080&fit=crop'
                    ]
                ]),
                'status' => true,
                'sort_order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('tpl_page_sections')->insert($pageSections);

        // 12. Template Site Navigation and Footer Data
        $tplSiteData = [
            [
                'id' => 1,
                'site_id' => 1,
                'nav_data' => json_encode([
                    'en' => [
                        'links' => [
                            ['url' => '/', 'label' => 'Home'],
                            ['url' => '/about', 'label' => 'About Us'],
                            ['url' => '/services', 'label' => 'Services'],
                            ['url' => '/portfolio', 'label' => 'Portfolio'],
                            ['url' => '/contact', 'label' => 'Contact']
                        ]
                    ],
                    'ar' => [
                        'links' => [
                            ['url' => '/', 'label' => 'الرئيسية'],
                            ['url' => '/about', 'label' => 'عنا'],
                            ['url' => '/services', 'label' => 'خدماتنا'],
                            ['url' => '/portfolio', 'label' => 'معرض الأعمال'],
                            ['url' => '/contact', 'label' => 'اتصل بنا']
                        ]
                    ]
                ]),
                'footer_data' => json_encode([
                    'en' => [
                        'company_info' => 'SEOeStore is a leading digital marketing agency in Dubai, UAE, helping businesses grow online since 2016.',
                        'quick_links' => [
                            ['url' => '/about', 'label' => 'About Us'],
                            ['url' => '/services', 'label' => 'Services'],
                            ['url' => '/portfolio', 'label' => 'Portfolio'],
                            ['url' => '/blog', 'label' => 'Blog'],
                            ['url' => '/contact', 'label' => 'Contact']
                        ],
                        'services' => [
                            ['url' => '/seo', 'label' => 'SEO Services'],
                            ['url' => '/ppc', 'label' => 'PPC Advertising'],
                            ['url' => '/social-media', 'label' => 'Social Media'],
                            ['url' => '/web-development', 'label' => 'Web Development'],
                            ['url' => '/email-marketing', 'label' => 'Email Marketing']
                        ],
                        'social_media' => [
                            ['platform' => 'facebook', 'url' => 'https://facebook.com/seoestore'],
                            ['platform' => 'instagram', 'url' => 'https://instagram.com/seoestore'],
                            ['platform' => 'linkedin', 'url' => 'https://linkedin.com/company/seoestore'],
                            ['platform' => 'twitter', 'url' => 'https://twitter.com/seoestore']
                        ],
                        'contact' => [
                            'address' => 'Office 1204, Al Manara Tower, Business Bay, Dubai, UAE',
                            'phone' => '+971 4 567 8901',
                            'email' => 'info@seoestore.com'
                        ]
                    ],
                    'ar' => [
                        'company_info' => 'SEOeStore هي وكالة التسويق الرقمي الرائدة في دبي، الإمارات، تساعد الشركات على النمو عبر الإنترنت منذ 2016.',
                        'quick_links' => [
                            ['url' => '/about', 'label' => 'عنا'],
                            ['url' => '/services', 'label' => 'خدماتنا'],
                            ['url' => '/portfolio', 'label' => 'معرض الأعمال'],
                            ['url' => '/blog', 'label' => 'المدونة'],
                            ['url' => '/contact', 'label' => 'اتصل بنا']
                        ],
                        'services' => [
                            ['url' => '/seo', 'label' => 'خدمات SEO'],
                            ['url' => '/ppc', 'label' => 'إعلانات PPC'],
                            ['url' => '/social-media', 'label' => 'وسائل التواصل'],
                            ['url' => '/web-development', 'label' => 'تطوير المواقع'],
                            ['url' => '/email-marketing', 'label' => 'التسويق بالبريد']
                        ],
                        'social_media' => [
                            ['platform' => 'facebook', 'url' => 'https://facebook.com/seoestore'],
                            ['platform' => 'instagram', 'url' => 'https://instagram.com/seoestore'],
                            ['platform' => 'linkedin', 'url' => 'https://linkedin.com/company/seoestore'],
                            ['platform' => 'twitter', 'url' => 'https://twitter.com/seoestore']
                        ],
                        'contact' => [
                            'address' => 'مكتب 1204، برج المنارة، الخليج التجاري، دبي، الإمارات',
                            'phone' => '+971 4 567 8901',
                            'email' => 'info@seoestore.com'
                        ]
                    ]
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'site_id' => 2,
                'nav_data' => json_encode([
                    'en' => [
                        'links' => [
                            ['url' => '/', 'label' => 'Home'],
                            ['url' => '/solutions', 'label' => 'Solutions'],
                            ['url' => '/case-studies', 'label' => 'Case Studies'],
                            ['url' => '/about', 'label' => 'About'],
                            ['url' => '/contact', 'label' => 'Contact']
                        ]
                    ]
                ]),
                'footer_data' => json_encode([
                    'en' => [
                        'company_info' => 'TechCorp Solutions provides enterprise technology consulting and custom software development services worldwide.',
                        'quick_links' => [
                            ['url' => '/solutions', 'label' => 'Solutions'],
                            ['url' => '/case-studies', 'label' => 'Case Studies'],
                            ['url' => '/careers', 'label' => 'Careers'],
                            ['url' => '/contact', 'label' => 'Contact']
                        ],
                        'contact' => [
                            'address' => '123 Tech Avenue, New York, NY 10001',
                            'phone' => '+1 (555) 123-4567',
                            'email' => 'info@techcorp.com'
                        ]
                    ]
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'site_id' => 3,
                'nav_data' => json_encode([
                    'ar' => [
                        'links' => [
                            ['url' => '/', 'label' => 'الرئيسية'],
                            ['url' => '/services', 'label' => 'خدماتنا'],
                            ['url' => '/portfolio', 'label' => 'معرض الأعمال'],
                            ['url' => '/about', 'label' => 'عنا'],
                            ['url' => '/contact', 'label' => 'اتصل بنا']
                        ]
                    ],
                    'en' => [
                        'links' => [
                            ['url' => '/', 'label' => 'Home'],
                            ['url' => '/services', 'label' => 'Services'],
                            ['url' => '/portfolio', 'label' => 'Portfolio'],
                            ['url' => '/about', 'label' => 'About'],
                            ['url' => '/contact', 'label' => 'Contact']
                        ]
                    ]
                ]),
                'footer_data' => json_encode([
                    'ar' => [
                        'company_info' => 'Digital Media UAE وكالة إعلام رقمي إبداعية متخصصة في العلامات التجارية والتسويق الرقمي.',
                        'contact' => [
                            'address' => 'مكتب 456، مركز دبي المالي، دبي، الإمارات',
                            'phone' => '+971 4 123 4567',
                            'email' => 'info@digitalmedia.ae'
                        ]
                    ]
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'site_id' => 4,
                'nav_data' => json_encode([
                    'en' => [
                        'links' => [
                            ['url' => '/', 'label' => 'Home'],
                            ['url' => '/portfolio', 'label' => 'Portfolio'],
                            ['url' => '/services', 'label' => 'Services'],
                            ['url' => '/about', 'label' => 'About'],
                            ['url' => '/contact', 'label' => 'Contact']
                        ]
                    ]
                ]),
                'footer_data' => json_encode([
                    'en' => [
                        'company_info' => 'Creative Studio is an award-winning design agency creating exceptional digital experiences.',
                        'contact' => [
                            'address' => '789 Creative Lane, Los Angeles, CA 90210',
                            'phone' => '+1 (555) 987-6543',
                            'email' => 'hello@creativestudio.com'
                        ]
                    ]
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 5,
                'site_id' => 5,
                'nav_data' => json_encode([
                    'ar' => [
                        'links' => [
                            ['url' => '/', 'label' => 'الرئيسية'],
                            ['url' => '/innovation', 'label' => 'الابتكار'],
                            ['url' => '/programs', 'label' => 'البرامج'],
                            ['url' => '/about', 'label' => 'عنا'],
                            ['url' => '/contact', 'label' => 'اتصل بنا']
                        ]
                    ],
                    'en' => [
                        'links' => [
                            ['url' => '/', 'label' => 'Home'],
                            ['url' => '/innovation', 'label' => 'Innovation'],
                            ['url' => '/programs', 'label' => 'Programs'],
                            ['url' => '/about', 'label' => 'About'],
                            ['url' => '/contact', 'label' => 'Contact']
                        ]
                    ]
                ]),
                'footer_data' => json_encode([
                    'ar' => [
                        'company_info' => 'Saudi Tech Hub مركز التقنية السعودي الرائد في الابتكار والتحول الرقمي.',
                        'contact' => [
                            'address' => 'مركز الملك عبدالله للتقنية، الرياض، المملكة العربية السعودية',
                            'phone' => '+966 11 456 7890',
                            'email' => 'info@sauditech.sa'
                        ]
                    ]
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('tpl_site')->insert($tplSiteData);

        // 13. Site Image Media - Sample media records
        $siteImageMedia = [
            [
                'id' => 1,
                'site_id' => 1,
                'section_id' => 1, // Hero section
                'max_files' => 5,
                'allowed_types' => json_encode(['image/jpeg', 'image/png', 'image/webp']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'site_id' => 1,
                'section_id' => 2, // Services section
                'max_files' => 10,
                'allowed_types' => json_encode(['image/jpeg', 'image/png', 'image/svg+xml']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'site_id' => 1,
                'section_id' => 3, // About section
                'max_files' => 3,
                'allowed_types' => json_encode(['image/jpeg', 'image/png']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'site_id' => 2,
                'section_id' => 5, // TechCorp Hero
                'max_files' => 5,
                'allowed_types' => json_encode(['image/jpeg', 'image/png', 'image/webp']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 5,
                'site_id' => 3,
                'section_id' => 7, // Digital Media UAE
                'max_files' => 8,
                'allowed_types' => json_encode(['image/jpeg', 'image/png', 'image/webp']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 6,
                'site_id' => 4,
                'section_id' => 8, // Creative Studio Portfolio
                'max_files' => 15,
                'allowed_types' => json_encode(['image/jpeg', 'image/png', 'image/webp']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 7,
                'site_id' => 5,
                'section_id' => 9, // Saudi Tech Hub
                'max_files' => 10,
                'allowed_types' => json_encode(['image/jpeg', 'image/png', 'image/webp']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('site_img_media')->insert($siteImageMedia);

        echo "Real data seeder completed successfully!\n";
        echo "Added:\n";
        echo "- 5 Users with real names and emails\n";
        echo "- 5 Business sites with authentic content\n";
        echo "- 5 Theme categories (business, portfolio, ecommerce, seo-services, restaurant)\n";
        echo "- 6 Theme pages with real business themes\n";
        echo "- 9 Template layouts (headers, sections, footers)\n";
        echo "- 14 Pages with multilingual content\n";
        echo "- 9 Page sections with real business content\n";
        echo "- All with real images from Unsplash\n";
        echo "- Navigation and footer data\n";
        echo "- Media management setup\n\n";
        echo "Websites created:\n";
        echo "1. SEOeStore Digital Agency (Arabic/English)\n";
        echo "2. TechCorp Solutions (English)\n";
        echo "3. Digital Media UAE (Arabic/English)\n";
        echo "4. Creative Studio Portfolio (English)\n";
        echo "5. Saudi Tech Hub (Arabic/English)\n";
    }
}
