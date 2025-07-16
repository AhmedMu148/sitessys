<?php

namespace App\Services;

use App\Models\User;
use App\Models\Site;
use App\Models\UserTemplate;
use App\Models\PageSection;
use App\Models\SiteConfig;
use App\Models\TplSite;
use App\Models\TplPage;
use App\Factories\TemplateFactory;
use Illuminate\Support\Facades\DB;
use App\Models\TplLayout;
use App\Models\TplLayoutType;
use Illuminate\Support\Facades\Log;

class TemplateCloneService
{
    /**
     * Clone the default template for a new user
     */
    public function cloneDefaultTemplateForUser(User $user): bool
    {
        try {
            DB::beginTransaction();

            // 1. Create a default site for the user
            $site = $this->createDefaultSite($user);

            // 2. Get template data from factory
            $templateData = TemplateFactory::createBasicWebsiteData($user, $site);

            // 3. Clone the master template with personalized data
            $template = $this->cloneMasterTemplate($user, $templateData['template']);

            // 4. Clone site-specific layouts (headers, footers, sections)
            $layouts = $this->cloneSiteLayouts($user, $site);

            // 5. Get available section layouts (created in step 4)
            $sectionLayouts = $this->getSectionLayouts($site, $layouts);

            // 6. Clone template pages and create initial page sections
            $pages = $this->cloneTemplatePages($site, $layouts, $templateData['pages']);

            // 7. Create site configuration
            $this->createSiteConfiguration($site, $template, $templateData['template']);

            // 8. Link template to site
            $this->linkTemplateToSite($site, $template, $pages);

            // 9. Set default active header and footer
            $this->setDefaultActiveLayouts($site, $layouts);

            DB::commit();
            
            Log::info("Template cloned successfully for user: {$user->email}");
            return true;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Failed to clone template for user {$user->email}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Create a default site for the user
     */
    private function createDefaultSite(User $user): Site
    {
        return Site::create([
            'user_id' => $user->id,
            'site_name' => $user->name . "'s Site",
            'domain' => null,
            'status' => true
        ]);
    }

    /**
     * Clone the master template for the user with personalized data
     */
    private function cloneMasterTemplate(User $user, array $templateData): UserTemplate
    {
        $masterTemplate = $this->getMasterTemplate();

        return UserTemplate::create([
            'user_id' => $user->id,
            'name' => $templateData['name'],
            'description' => $templateData['description'],
            'html_content' => $masterTemplate['html_content'],
            'css_content' => $masterTemplate['css_content'],
            'js_content' => $masterTemplate['js_content'],
            'config' => $templateData['config'],
            'is_active' => true,
            'is_default' => true,
        ]);
    }

    /**
     * Clone site-specific layouts (headers, footers, sections) for the user
     */
    private function cloneSiteLayouts(User $user, Site $site): array
    {
        $layouts = [];
        
        // Get layout types
        $navType = TplLayoutType::where('name', 'nav')->first();
        $footerType = TplLayoutType::where('name', 'footer')->first();
        $sectionType = TplLayoutType::where('name', 'section')->first();
        
        // Create default header layouts
        $defaultHeader = TplLayout::create([
            'user_id' => $user->id,
            'site_id' => $site->id,
            'type_id' => $navType->id,
            'name' => 'Default Header',
            'description' => 'Default navigation header for your website',
            'data' => $this->getDefaultHeaderContent($site),
            'status' => true,
            'is_active' => true,
            'sort_order' => 1
        ]);
        
        // Create modern header layout
        $modernHeader = TplLayout::create([
            'user_id' => $user->id,
            'site_id' => $site->id,
            'type_id' => $navType->id,
            'name' => 'Modern Header',
            'description' => 'Modern navigation header with gradient background',
            'data' => $this->getModernHeaderContent($site),
            'status' => true,
            'is_active' => false,
            'sort_order' => 2
        ]);
        
        // Create default footer layouts
        $defaultFooter = TplLayout::create([
            'user_id' => $user->id,
            'site_id' => $site->id,
            'type_id' => $footerType->id,
            'name' => 'Default Footer',
            'description' => 'Default footer with contact information',
            'data' => $this->getDefaultFooterContent($site),
            'status' => true,
            'is_active' => true,
            'sort_order' => 1
        ]);
        
        // Create simple footer layout
        $simpleFooter = TplLayout::create([
            'user_id' => $user->id,
            'site_id' => $site->id,
            'type_id' => $footerType->id,
            'name' => 'Simple Footer',
            'description' => 'Minimal footer with basic links',
            'data' => $this->getSimpleFooterContent($site),
            'status' => true,
            'is_active' => false,
            'sort_order' => 2
        ]);
        
        // Create section layouts
        $sectionLayouts = $this->createDefaultSectionLayouts($user, $site, $sectionType);
        
        $layouts = array_merge(
            [$defaultHeader, $modernHeader, $defaultFooter, $simpleFooter],
            $sectionLayouts
        );
        
        return $layouts;
    }
    
    /**
     * Create default section layouts for the site
     */
    private function createDefaultSectionLayouts(User $user, Site $site, TplLayoutType $sectionType): array
    {
        $sections = [
            [
                'name' => 'Hero Section',
                'description' => 'Main hero banner with call-to-action',
                'data' => $this->getHeroSectionContent($site)
            ],
            [
                'name' => 'About Section',
                'description' => 'About us content section',
                'data' => $this->getAboutSectionContent($site)
            ],
            [
                'name' => 'Services Section',
                'description' => 'Services showcase grid',
                'data' => $this->getServicesSectionContent($site)
            ],
            [
                'name' => 'Features Section',
                'description' => 'Key features highlight',
                'data' => $this->getFeaturesSectionContent($site)
            ],
            [
                'name' => 'Testimonials Section',
                'description' => 'Customer testimonials carousel',
                'data' => $this->getTestimonialsSectionContent($site)
            ],
            [
                'name' => 'Contact Section',
                'description' => 'Contact form and information',
                'data' => $this->getContactSectionContent($site)
            ]
        ];
        
        $layouts = [];
        foreach ($sections as $index => $section) {
            $layouts[] = TplLayout::create([
                'user_id' => $user->id,
                'site_id' => $site->id,
                'type_id' => $sectionType->id,
                'name' => $section['name'],
                'description' => $section['description'],
                'data' => $section['data'],
                'status' => true,
                'is_active' => true,
                'sort_order' => $index + 1
            ]);
        }
        
        return $layouts;
    }
    
    /**
     * Get section layouts for creating page sections
     */
    private function getSectionLayouts(Site $site, array $layouts): array
    {
        return collect($layouts)->filter(function($layout) {
            $typeName = is_array($layout) && isset($layout['type']) 
                ? (is_array($layout['type']) ? $layout['type']['name'] : $layout['type']->name)
                : (isset($layout->type) ? $layout->type->name : null);
            return $typeName === 'section';
        })->values()->toArray();
    }

    /**
     * Clone template sections for the site with personalized content
     * Now creates PageSections that belong to specific pages instead of standalone sections
     */
    private function cloneTemplateSections(Site $site, array $sectionsData): array
    {
        // This method is now handled by creating PageSections for each page
        // We'll return layout IDs that can be used for creating page sections
        $sectionLayouts = TplLayout::where('site_id', $site->id)
            ->whereHas('type', function($q) {
                $q->where('name', 'section');
            })
            ->get();
            
        return $sectionLayouts->toArray();
    }

    /**
     * Clone template pages for the site and create initial page sections
     */
    private function cloneTemplatePages(Site $site, array $layouts, array $pagesData): array
    {
        $clonedPages = [];
        $sectionLayouts = collect($layouts)->filter(function($layout) {
            return is_array($layout) && isset($layout['type']) && 
                   (is_array($layout['type']) ? $layout['type']['name'] : $layout['type']->name) === 'section';
        });

        foreach ($pagesData as $pageData) {
            // Create the page using new enhanced structure
            $page = TplPage::create([
                'site_id' => $site->id,
                'name' => $pageData['name'],
                'slug' => $pageData['slug'],
                'link' => '/' . ($pageData['slug'] === 'home' ? '' : $pageData['slug']),
                'description' => 'Learn more about ' . strtolower($pageData['name']) . ' at ' . $site->site_name,
                'meta_data' => json_encode([
                    'title' => $pageData['name'] . ' - ' . $site->site_name,
                    'description' => 'Learn more about ' . strtolower($pageData['name']) . ' at ' . $site->site_name,
                    'keywords' => strtolower($pageData['name']) . ', ' . strtolower($site->site_name)
                ]),
                'is_active' => true,
                'show_in_nav' => $pageData['slug'] !== 'home', // Home doesn't need to show in nav
                'sort_order' => count($clonedPages) + 1,
                'section_id' => '' // Will be populated when sections are created
            ]);

            // Create initial page sections for this page
            $this->createInitialPageSections($page, $sectionLayouts, $pageData['slug']);

            $clonedPages[] = $page;
        }

        return $clonedPages;
    }
    
    /**
     * Create initial page sections for a page based on page type
     */
    private function createInitialPageSections(TplPage $page, $sectionLayouts, string $pageSlug): void
    {
        $sectionMapping = [
            'home' => ['Hero Section', 'About Section', 'Services Section'],
            'about' => ['About Section', 'Testimonials Section'],
            'services' => ['Services Section', 'Features Section'],
            'contact' => ['Contact Section']
        ];
        
        $sectionsToCreate = $sectionMapping[$pageSlug] ?? ['Hero Section'];
        
        // Get section layouts for this site
        $availableLayouts = TplLayout::where('site_id', $page->site_id)
            ->whereHas('type', function($q) {
                $q->where('name', 'section');
            })
            ->get();
        
        foreach ($sectionsToCreate as $index => $sectionName) {
            $layout = $availableLayouts->where('name', $sectionName)->first();
            
            if ($layout) {
                PageSection::create([
                    'page_id' => $page->id,
                    'layout_id' => $layout->id,
                    'site_id' => $page->site_id,
                    'name' => $sectionName,
                    'content_data' => json_encode([
                        'title' => 'Welcome to ' . $sectionName,
                        'content' => 'This is an auto-generated section. You can customize it in the admin panel.'
                    ]),
                    'settings' => json_encode(['background' => 'default']),
                    'is_active' => true,
                    'sort_order' => $index + 1
                ]);
            }
        }
    }

    /**
     * Create site configuration with personalized data
     */
    private function createSiteConfiguration(Site $site, UserTemplate $template, array $templateData): void
    {
        $variables = $templateData['config']['variables'] ?? [];
        
        SiteConfig::create([
            'site_id' => $site->id,
            'data' => [
                'logo' => '/img/logo.png',
                'favicon' => '/img/favicon.ico',
                'title' => $variables['site_title'] ?? $site->site_name,
                'keyword' => 'website, business, personal, portfolio',
                'description' => $variables['site_description'] ?? 'Welcome to my professional website'
            ],
            'lang_id' => '1,2' // English and Arabic
        ]);
    }

    /**
     * Link template to site with proper configuration
     * This is now simplified since we use active_header_id and active_footer_id directly
     */
    private function linkTemplateToSite(Site $site, UserTemplate $template, array $pages): void
    {
        // Create TplSite configuration if needed for legacy compatibility
        TplSite::create([
            'site_id' => $site->id,
            'nav' => 1, // Legacy - now using site->active_header_id 
            'pages' => collect($pages)->pluck('id')->take(4)->toArray(),
            'footer' => 1 // Legacy - now using site->active_footer_id
        ]);
    }

    /**
     * Set default active header and footer for the site
     */
    private function setDefaultActiveLayouts(Site $site, array $layouts): void
    {
        $defaultHeader = null;
        $defaultFooter = null;
        
        foreach ($layouts as $layout) {
            if ($layout->type->name === 'nav' && $layout->name === 'Default Header') {
                $defaultHeader = $layout;
            }
            if ($layout->type->name === 'footer' && $layout->name === 'Default Footer') {
                $defaultFooter = $layout;
            }
        }
        
        if ($defaultHeader && $defaultFooter) {
            $site->update([
                'active_header_id' => $defaultHeader->id,
                'active_footer_id' => $defaultFooter->id
            ]);
        }
    }

    /**
     * Get the master template structure
     */
    private function getMasterTemplate(): array
    {
        // Try to get the master template from database first
        $masterTemplate = UserTemplate::where('user_id', null)
            ->where('is_default', true)
            ->where('name', 'Master Default Template')
            ->first();

        if ($masterTemplate) {
            return [
                'html_content' => $masterTemplate->html_content,
                'css_content' => $masterTemplate->css_content,
                'js_content' => $masterTemplate->js_content,
                'config' => $masterTemplate->config ?: $this->getDefaultConfig()
            ];
        }

        // Fallback to built-in template if no master template found
        return [
            'html_content' => $this->getMasterHtmlContent(),
            'css_content' => $this->getMasterCssContent(),
            'js_content' => $this->getMasterJsContent(),
            'config' => $this->getDefaultConfig()
        ];
    }

    /**
     * Get default template configuration
     */
    private function getDefaultConfig(): array
    {
        return [
            'variables' => [
                'site_title' => '{{site_title}}',
                'site_description' => '{{site_description}}',
                'contact_email' => '{{contact_email}}',
                'contact_phone' => '{{contact_phone}}',
                'company_address' => '{{company_address}}',
                'social_facebook' => '{{social_facebook}}',
                'social_twitter' => '{{social_twitter}}',
                'social_instagram' => '{{social_instagram}}',
                'social_linkedin' => '{{social_linkedin}}'
            ],
            'features' => [
                'responsive',
                'multi_language',
                'contact_form',
                'social_links',
                'seo_optimized'
            ],
            'customizable_sections' => [
                'hero',
                'about',
                'services',
                'portfolio',
                'testimonials',
                'contact'
            ]
        ];
    }

    /**
     * Get master sections structure
     */
    private function getMasterSections(): array
    {
        return [
            [
                'name' => 'Hero Section',
                'content' => [
                    'en' => [
                        'title' => 'Welcome to My Website',
                        'subtitle' => 'Professional services and solutions',
                        'description' => 'Discover what we can do for you with our professional services and innovative solutions.',
                        'button_text' => 'Get Started',
                        'button_link' => '#about',
                        'background_image' => '/img/hero-bg.jpg'
                    ],
                    'ar' => [
                        'title' => 'مرحباً بكم في موقعي',
                        'subtitle' => 'خدمات وحلول احترافية',
                        'description' => 'اكتشف ما يمكننا فعله لك من خلال خدماتنا المهنية والحلول المبتكرة.',
                        'button_text' => 'ابدأ الآن',
                        'button_link' => '#about',
                        'background_image' => '/img/hero-bg.jpg'
                    ]
                ]
            ],
            [
                'name' => 'About Section',
                'content' => [
                    'en' => [
                        'title' => 'About Us',
                        'subtitle' => 'Who we are and what we do',
                        'description' => 'We are a team of professionals dedicated to providing excellent services and creating value for our clients.',
                        'features' => [
                            'Professional Team',
                            'Quality Service',
                            'Customer Satisfaction',
                            'Innovation'
                        ],
                        'image' => '/img/about-us.jpg'
                    ],
                    'ar' => [
                        'title' => 'من نحن',
                        'subtitle' => 'من نحن وماذا نفعل',
                        'description' => 'نحن فريق من المحترفين المكرسين لتقديم خدمات ممتازة وخلق قيمة لعملائنا.',
                        'features' => [
                            'فريق محترف',
                            'خدمة عالية الجودة',
                            'رضا العملاء',
                            'الابتكار'
                        ],
                        'image' => '/img/about-us.jpg'
                    ]
                ]
            ],
            [
                'name' => 'Services Section',
                'content' => [
                    'en' => [
                        'title' => 'Our Services',
                        'subtitle' => 'What we offer',
                        'description' => 'Comprehensive services tailored to meet your specific needs.',
                        'services' => [
                            [
                                'title' => 'Web Development',
                                'description' => 'Custom web solutions for your business',
                                'icon' => 'fas fa-code',
                                'image' => '/img/service-web.jpg'
                            ],
                            [
                                'title' => 'Mobile Apps',
                                'description' => 'Native and cross-platform mobile applications',
                                'icon' => 'fas fa-mobile-alt',
                                'image' => '/img/service-mobile.jpg'
                            ],
                            [
                                'title' => 'Consulting',
                                'description' => 'Strategic business and technology consulting',
                                'icon' => 'fas fa-chart-line',
                                'image' => '/img/service-consulting.jpg'
                            ]
                        ]
                    ],
                    'ar' => [
                        'title' => 'خدماتنا',
                        'subtitle' => 'ما نقدمه',
                        'description' => 'خدمات شاملة مصممة لتلبية احتياجاتك الخاصة.',
                        'services' => [
                            [
                                'title' => 'تطوير المواقع',
                                'description' => 'حلول ويب مخصصة لأعمالك',
                                'icon' => 'fas fa-code',
                                'image' => '/img/service-web.jpg'
                            ],
                            [
                                'title' => 'تطبيقات الهاتف',
                                'description' => 'تطبيقات هاتف أصلية ومتعددة المنصات',
                                'icon' => 'fas fa-mobile-alt',
                                'image' => '/img/service-mobile.jpg'
                            ],
                            [
                                'title' => 'الاستشارات',
                                'description' => 'استشارات أعمال وتكنولوجيا استراتيجية',
                                'icon' => 'fas fa-chart-line',
                                'image' => '/img/service-consulting.jpg'
                            ]
                        ]
                    ]
                ]
            ],
            [
                'name' => 'Portfolio Section',
                'content' => [
                    'en' => [
                        'title' => 'Our Work',
                        'subtitle' => 'Recent projects',
                        'description' => 'Take a look at some of our recent work and achievements.',
                        'projects' => [
                            [
                                'title' => 'E-commerce Platform',
                                'description' => 'Modern online shopping experience',
                                'image' => '/img/project-1.jpg',
                                'category' => 'Web Development'
                            ],
                            [
                                'title' => 'Mobile Banking App',
                                'description' => 'Secure and user-friendly banking',
                                'image' => '/img/project-2.jpg',
                                'category' => 'Mobile App'
                            ],
                            [
                                'title' => 'Corporate Website',
                                'description' => 'Professional business presence',
                                'image' => '/img/project-3.jpg',
                                'category' => 'Web Design'
                            ]
                        ]
                    ],
                    'ar' => [
                        'title' => 'أعمالنا',
                        'subtitle' => 'المشاريع الحديثة',
                        'description' => 'ألق نظرة على بعض أعمالنا وإنجازاتنا الحديثة.',
                        'projects' => [
                            [
                                'title' => 'منصة التجارة الإلكترونية',
                                'description' => 'تجربة تسوق عبر الإنترنت حديثة',
                                'image' => '/img/project-1.jpg',
                                'category' => 'تطوير الويب'
                            ],
                            [
                                'title' => 'تطبيق البنك المحمول',
                                'description' => 'خدمات مصرفية آمنة وسهلة الاستخدام',
                                'image' => '/img/project-2.jpg',
                                'category' => 'تطبيق الهاتف'
                            ],
                            [
                                'title' => 'موقع الشركة',
                                'description' => 'حضور تجاري احترافي',
                                'image' => '/img/project-3.jpg',
                                'category' => 'تصميم الويب'
                            ]
                        ]
                    ]
                ]
            ],
            [
                'name' => 'Testimonials Section',
                'content' => [
                    'en' => [
                        'title' => 'What Our Clients Say',
                        'subtitle' => 'Client testimonials',
                        'description' => 'Hear what our satisfied clients have to say about working with us.',
                        'testimonials' => [
                            [
                                'name' => 'John Smith',
                                'position' => 'CEO, Tech Corp',
                                'content' => 'Excellent service and outstanding results. Highly recommended!',
                                'rating' => 5,
                                'avatar' => '/img/client-1.jpg'
                            ],
                            [
                                'name' => 'Sarah Johnson',
                                'position' => 'Marketing Director',
                                'content' => 'Professional team with creative solutions. Great experience!',
                                'rating' => 5,
                                'avatar' => '/img/client-2.jpg'
                            ],
                            [
                                'name' => 'Mike Davis',
                                'position' => 'Business Owner',
                                'content' => 'They delivered exactly what we needed, on time and budget.',
                                'rating' => 5,
                                'avatar' => '/img/client-3.jpg'
                            ]
                        ]
                    ],
                    'ar' => [
                        'title' => 'ماذا يقول عملاؤنا',
                        'subtitle' => 'شهادات العملاء',
                        'description' => 'اسمع ما يقوله عملاؤنا الراضون عن العمل معنا.',
                        'testimonials' => [
                            [
                                'name' => 'أحمد محمد',
                                'position' => 'الرئيس التنفيذي، شركة التقنية',
                                'content' => 'خدمة ممتازة ونتائج استثنائية. أنصح بشدة!',
                                'rating' => 5,
                                'avatar' => '/img/client-1.jpg'
                            ],
                            [
                                'name' => 'فاطمة أحمد',
                                'position' => 'مدير التسويق',
                                'content' => 'فريق محترف مع حلول إبداعية. تجربة رائعة!',
                                'rating' => 5,
                                'avatar' => '/img/client-2.jpg'
                            ],
                            [
                                'name' => 'محمد علي',
                                'position' => 'صاحب أعمال',
                                'content' => 'قدموا بالضبط ما نحتاجه، في الوقت المحدد والميزانية.',
                                'rating' => 5,
                                'avatar' => '/img/client-3.jpg'
                            ]
                        ]
                    ]
                ]
            ],
            [
                'name' => 'Contact Section',
                'content' => [
                    'en' => [
                        'title' => 'Get In Touch',
                        'subtitle' => 'Contact us today',
                        'description' => 'Ready to start your project? Get in touch with us today!',
                        'contact_info' => [
                            'email' => 'contact@example.com',
                            'phone' => '+1 (555) 123-4567',
                            'address' => '123 Business St, City, State 12345',
                            'hours' => 'Mon - Fri: 9:00 AM - 6:00 PM'
                        ],
                        'form_fields' => [
                            'name' => 'Your Name',
                            'email' => 'Your Email',
                            'subject' => 'Subject',
                            'message' => 'Your Message'
                        ]
                    ],
                    'ar' => [
                        'title' => 'تواصل معنا',
                        'subtitle' => 'اتصل بنا اليوم',
                        'description' => 'مستعد لبدء مشروعك؟ تواصل معنا اليوم!',
                        'contact_info' => [
                            'email' => 'contact@example.com',
                            'phone' => '+1 (555) 123-4567',
                            'address' => '123 شارع الأعمال، المدينة، الولاية 12345',
                            'hours' => 'الإثنين - الجمعة: 9:00 ص - 6:00 م'
                        ],
                        'form_fields' => [
                            'name' => 'اسمك',
                            'email' => 'بريدك الإلكتروني',
                            'subject' => 'الموضوع',
                            'message' => 'رسالتك'
                        ]
                    ]
                ]
            ]
        ];
    }

    /**
     * Get master pages structure
     */
    private function getMasterPages(): array
    {
        return [
            [
                'name' => 'Home',
                'slug' => 'home',
            ],
            [
                'name' => 'About',
                'slug' => 'about',
            ],
            [
                'name' => 'Services',
                'slug' => 'services',
            ],
            [
                'name' => 'Contact',
                'slug' => 'contact',
            ]
        ];
    }



    /**
     * Get master HTML content
     */
    private function getMasterHtmlContent(): string
    {
        $filePath = resource_path('templates/master/template.html');
        
        if (file_exists($filePath)) {
            return file_get_contents($filePath);
        }
        
        // Return fallback HTML if file doesn't exist
        return $this->getFallbackHtmlContent();
    }

    /**
     * Get master CSS content
     */
    private function getMasterCssContent(): string
    {
        $filePath = resource_path('templates/master/template.css');
        
        if (file_exists($filePath)) {
            return file_get_contents($filePath);
        }
        
        // Return fallback CSS if file doesn't exist
        return $this->getFallbackCssContent();
    }

    /**
     * Get master JS content
     */
    private function getMasterJsContent(): string
    {
        $filePath = resource_path('templates/master/template.js');
        
        if (file_exists($filePath)) {
            return file_get_contents($filePath);
        }
        
        // Return fallback JS if file doesn't exist
        return $this->getFallbackJsContent();
    }

    /**
     * Fallback HTML content
     */
    private function getFallbackHtmlContent(): string
    {
        return '<!DOCTYPE html>
<html lang="{{language}}" dir="{{direction}}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{site_title}}</title>
    <meta name="description" content="{{site_description}}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="/">{{site_title}}</a>
            {{navigation_menu}}
        </div>
    </nav>
    
    <main>
        {{page_sections}}
    </main>
    
    <footer class="bg-dark text-white py-4">
        <div class="container text-center">
            <p>&copy; 2025 {{site_title}}. All rights reserved.</p>
        </div>
    </footer>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>';
    }

    /**
     * Fallback CSS content
     */
    private function getFallbackCssContent(): string
    {
        return 'body { font-family: Arial, sans-serif; } .hero-section { background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%); color: white; padding: 4rem 0; } .section { padding: 3rem 0; }';
    }

    /**
     * Fallback JS content
     */
    private function getFallbackJsContent(): string
    {
        return 'document.addEventListener("DOMContentLoaded", function() { console.log("Template loaded"); });';
    }

    /**
     * Get default header content
     */
    private function getDefaultHeaderContent(Site $site): string
    {
        return '<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="/">
            ' . $site->site_name . '
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarMain">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="/">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="/about">About</a></li>
                <li class="nav-item"><a class="nav-link" href="/services">Services</a></li>
                <li class="nav-item"><a class="nav-link" href="/contact">Contact</a></li>
            </ul>
        </div>
    </div>
</nav>';
    }
    
    /**
     * Get modern header content
     */
    private function getModernHeaderContent(Site $site): string
    {
        return '<nav class="navbar navbar-expand-lg navbar-dark" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
    <div class="container">
        <a class="navbar-brand fw-bold" href="/">
            ' . $site->site_name . '
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarModern">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarModern">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="/">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="/about">About</a></li>
                <li class="nav-item"><a class="nav-link" href="/services">Services</a></li>
                <li class="nav-item"><a class="nav-link" href="/contact">Contact</a></li>
                <li class="nav-item ms-2">
                    <a class="btn btn-outline-light btn-sm" href="/contact">Get Started</a>
                </li>
            </ul>
        </div>
    </div>
</nav>';
    }
    
    /**
     * Get default footer content
     */
    private function getDefaultFooterContent(Site $site): string
    {
        return '<footer class="bg-dark text-white py-5">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h5>' . $site->site_name . '</h5>
                <p class="text-light">Your trusted partner for innovative solutions.</p>
            </div>
            <div class="col-md-3">
                <h6>Quick Links</h6>
                <ul class="list-unstyled">
                    <li><a href="/" class="text-light text-decoration-none">Home</a></li>
                    <li><a href="/about" class="text-light text-decoration-none">About</a></li>
                    <li><a href="/services" class="text-light text-decoration-none">Services</a></li>
                    <li><a href="/contact" class="text-light text-decoration-none">Contact</a></li>
                </ul>
            </div>
            <div class="col-md-3">
                <h6>Contact Info</h6>
                <p class="text-light mb-1">📧 info@' . strtolower(str_replace(' ', '', $site->site_name)) . '.com</p>
                <p class="text-light mb-1">📞 +1 (555) 123-4567</p>
            </div>
        </div>
        <hr class="my-4">
        <div class="text-center">
            <p class="mb-0">&copy; ' . date('Y') . ' ' . $site->site_name . '. All rights reserved.</p>
        </div>
    </div>
</footer>';
    }
    
    /**
     * Get simple footer content
     */
    private function getSimpleFooterContent(Site $site): string
    {
        return '<footer class="bg-light py-4">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <p class="mb-0">&copy; ' . date('Y') . ' ' . $site->site_name . '. All rights reserved.</p>
            </div>
            <div class="col-md-6 text-md-end">
                <a href="/privacy" class="text-muted text-decoration-none me-3">Privacy</a>
                <a href="/terms" class="text-muted text-decoration-none">Terms</a>
            </div>
        </div>
    </div>
</footer>';
    }
    
    /**
     * Get hero section content
     */
    private function getHeroSectionContent(Site $site): string
    {
        return '<section class="hero-section bg-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4">Welcome to ' . $site->site_name . '</h1>
                <p class="lead mb-4">We provide innovative solutions to help your business grow and succeed in today\'s competitive market.</p>
                <div class="d-flex gap-3">
                    <a href="/services" class="btn btn-light btn-lg">Our Services</a>
                    <a href="/contact" class="btn btn-outline-light btn-lg">Get Started</a>
                </div>
            </div>
            <div class="col-lg-6">
                <img src="https://via.placeholder.com/600x400/007bff/ffffff?text=Hero+Image" class="img-fluid rounded" alt="Hero Image">
            </div>
        </div>
    </div>
</section>';
    }
    
    /**
     * Get about section content
     */
    private function getAboutSectionContent(Site $site): string
    {
        return '<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <img src="https://via.placeholder.com/500x400/f8f9fa/6c757d?text=About+Us" class="img-fluid rounded" alt="About Us">
            </div>
            <div class="col-lg-6">
                <h2 class="mb-4">About ' . $site->site_name . '</h2>
                <p class="mb-4">We are a dedicated team of professionals committed to delivering exceptional results for our clients. With years of experience in the industry, we have the expertise and knowledge to help you achieve your goals.</p>
                <div class="row">
                    <div class="col-sm-6 mb-3">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            <span>Expert Team</span>
                        </div>
                    </div>
                    <div class="col-sm-6 mb-3">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            <span>Quality Service</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>';
    }
    
    /**
     * Get services section content
     */
    private function getServicesSectionContent(Site $site): string
    {
        return '<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Our Services</h2>
            <p class="lead text-muted">We offer a comprehensive range of services to meet your needs</p>
        </div>
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card h-100 text-center">
                    <div class="card-body">
                        <i class="fas fa-cog fa-3x text-primary mb-3"></i>
                        <h5 class="card-title">Consulting</h5>
                        <p class="card-text">Strategic consulting to help your business grow and optimize operations.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card h-100 text-center">
                    <div class="card-body">
                        <i class="fas fa-laptop-code fa-3x text-primary mb-3"></i>
                        <h5 class="card-title">Development</h5>
                        <p class="card-text">Custom software development solutions tailored to your requirements.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card h-100 text-center">
                    <div class="card-body">
                        <i class="fas fa-chart-line fa-3x text-primary mb-3"></i>
                        <h5 class="card-title">Analytics</h5>
                        <p class="card-text">Data analysis and insights to drive informed business decisions.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>';
    }
    
    /**
     * Get features section content
     */
    private function getFeaturesSectionContent(Site $site): string
    {
        return '<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Why Choose Us</h2>
            <p class="lead text-muted">Here\'s what sets us apart from the competition</p>
        </div>
        <div class="row">
            <div class="col-lg-4 text-center mb-4">
                <div class="feature-icon bg-primary text-white rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                    <i class="fas fa-rocket fa-2x"></i>
                </div>
                <h5>Fast & Reliable</h5>
                <p class="text-muted">Quick turnaround times without compromising on quality.</p>
            </div>
            <div class="col-lg-4 text-center mb-4">
                <div class="feature-icon bg-success text-white rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                    <i class="fas fa-shield-alt fa-2x"></i>
                </div>
                <h5>Secure & Safe</h5>
                <p class="text-muted">Your data and privacy are our top priorities.</p>
            </div>
            <div class="col-lg-4 text-center mb-4">
                <div class="feature-icon bg-info text-white rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                    <i class="fas fa-headset fa-2x"></i>
                </div>
                <h5>24/7 Support</h5>
                <p class="text-muted">Round-the-clock support whenever you need assistance.</p>
            </div>
        </div>
    </div>
</section>';
    }
    
    /**
     * Get testimonials section content
     */
    private function getTestimonialsSectionContent(Site $site): string
    {
        return '<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">What Our Clients Say</h2>
            <p class="lead text-muted">Don\'t just take our word for it</p>
        </div>
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <img src="https://via.placeholder.com/80x80/dee2e6/6c757d?text=JD" class="rounded-circle mb-3" alt="Client">
                        <p class="card-text">"Excellent service and professional team. Highly recommended!"</p>
                        <h6 class="fw-bold">John Doe</h6>
                        <small class="text-muted">CEO, Example Corp</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <img src="https://via.placeholder.com/80x80/dee2e6/6c757d?text=JS" class="rounded-circle mb-3" alt="Client">
                        <p class="card-text">"They delivered exactly what we needed, on time and within budget."</p>
                        <h6 class="fw-bold">Jane Smith</h6>
                        <small class="text-muted">Marketing Director</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <img src="https://via.placeholder.com/80x80/dee2e6/6c757d?text=MW" class="rounded-circle mb-3" alt="Client">
                        <p class="card-text">"Outstanding results and great communication throughout the project."</p>
                        <h6 class="fw-bold">Mike Wilson</h6>
                        <small class="text-muted">Project Manager</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>';
    }
    
    /**
     * Get contact section content
     */
    private function getContactSectionContent(Site $site): string
    {
        return '<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Get In Touch</h2>
            <p class="lead text-muted">Ready to start your project? Contact us today!</p>
        </div>
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <form>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <input type="text" class="form-control" placeholder="Your Name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <input type="email" class="form-control" placeholder="Your Email" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <input type="text" class="form-control" placeholder="Subject" required>
                    </div>
                    <div class="mb-3">
                        <textarea class="form-control" rows="5" placeholder="Your Message" required></textarea>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary btn-lg">Send Message</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>';
    }
}
