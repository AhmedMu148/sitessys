<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TplLang;
use App\Models\ThemeCategory;
use App\Models\ThemePage;
use App\Models\TplLayout;

class EnhancedTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seed languages
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

        // Seed theme categories
        $categories = [
            ['name' => 'Business', 'description' => 'Professional business templates', 'icon' => 'briefcase', 'sort_order' => 1],
            ['name' => 'Portfolio', 'description' => 'Creative portfolio templates', 'icon' => 'folder', 'sort_order' => 2],
            ['name' => 'Ecommerce', 'description' => 'Online store templates', 'icon' => 'shopping-cart', 'sort_order' => 3],
            ['name' => 'SEO Services', 'description' => 'SEO and digital marketing templates', 'icon' => 'search', 'sort_order' => 4],
        ];

        foreach ($categories as $category) {
            ThemeCategory::updateOrCreate(
                ['name' => $category['name']],
                $category
            );
        }

        // Seed theme pages
        $themePages = [
            // Business themes
            [
                'category' => 'Business',
                'theme_id' => 'business-corporate',
                'name' => 'Corporate Business',
                'description' => 'Clean and professional corporate template',
                'path' => 'themes/business/corporate',
                'css_variables' => [
                    'primary_color' => '#2563eb',
                    'secondary_color' => '#64748b',
                    'accent_color' => '#f59e0b'
                ],
                'sort_order' => 1
            ],
            [
                'category' => 'Business',
                'theme_id' => 'business-startup',
                'name' => 'Startup Business',
                'description' => 'Modern startup-focused template',
                'path' => 'themes/business/startup',
                'css_variables' => [
                    'primary_color' => '#7c3aed',
                    'secondary_color' => '#6b7280',
                    'accent_color' => '#10b981'
                ],
                'sort_order' => 2
            ],
            // SEO Services themes
            [
                'category' => 'SEO Services',
                'theme_id' => 'seo-services',
                'name' => 'SEO Services',
                'description' => 'Professional SEO services template',
                'path' => 'themes/seo/services',
                'css_variables' => [
                    'primary_color' => '#059669',
                    'secondary_color' => '#374151',
                    'accent_color' => '#f59e0b'
                ],
                'sort_order' => 1
            ],
            [
                'category' => 'SEO Services',
                'theme_id' => 'seo-agency',
                'name' => 'SEO Agency',
                'description' => 'Digital marketing agency template',
                'path' => 'themes/seo/agency',
                'css_variables' => [
                    'primary_color' => '#dc2626',
                    'secondary_color' => '#4b5563',
                    'accent_color' => '#3b82f6'
                ],
                'sort_order' => 2
            ],
            // Portfolio themes
            [
                'category' => 'Portfolio',
                'theme_id' => 'portfolio-creative',
                'name' => 'Creative Portfolio',
                'description' => 'Modern creative portfolio template',
                'path' => 'themes/portfolio/creative',
                'css_variables' => [
                    'primary_color' => '#8b5cf6',
                    'secondary_color' => '#6b7280',
                    'accent_color' => '#f59e0b'
                ],
                'sort_order' => 1
            ],
            // Ecommerce themes
            [
                'category' => 'Ecommerce',
                'theme_id' => 'ecommerce-store',
                'name' => 'Online Store',
                'description' => 'Complete ecommerce store template',
                'path' => 'themes/ecommerce/store',
                'css_variables' => [
                    'primary_color' => '#059669',
                    'secondary_color' => '#374151',
                    'accent_color' => '#dc2626'
                ],
                'sort_order' => 1
            ],
        ];

        foreach ($themePages as $themePage) {
            $category = ThemeCategory::where('name', $themePage['category'])->first();
            if ($category) {
                ThemePage::updateOrCreate(
                    ['theme_id' => $themePage['theme_id']],
                    array_merge($themePage, ['category_id' => $category->id])
                );
            }
        }

        // Seed layout templates
        $layouts = [
            // Header templates
            [
                'tpl_id' => 'seo-header',
                'layout_type' => 'header',
                'name' => 'SEO Header',
                'description' => 'Professional SEO services header',
                'path' => 'layouts/headers/seo',
                'sort_order' => 1
            ],
            [
                'tpl_id' => 'corporate-header',
                'layout_type' => 'header',
                'name' => 'Corporate Header',
                'description' => 'Corporate business header',
                'path' => 'layouts/headers/corporate',
                'sort_order' => 2
            ],
            [
                'tpl_id' => 'creative-header',
                'layout_type' => 'header',
                'name' => 'Creative Header',
                'description' => 'Creative portfolio header',
                'path' => 'layouts/headers/creative',
                'sort_order' => 3
            ],
            [
                'tpl_id' => 'ecommerce-header',
                'layout_type' => 'header',
                'name' => 'Ecommerce Header',
                'description' => 'Online store header',
                'path' => 'layouts/headers/ecommerce',
                'sort_order' => 4
            ],
            [
                'tpl_id' => 'minimal-header',
                'layout_type' => 'header',
                'name' => 'Minimal Header',
                'description' => 'Clean minimal header',
                'path' => 'layouts/headers/minimal',
                'sort_order' => 5
            ],
            
            // Footer templates
            [
                'tpl_id' => 'seo-footer',
                'layout_type' => 'footer',
                'name' => 'SEO Footer',
                'description' => 'Professional SEO services footer',
                'path' => 'layouts/footers/seo',
                'sort_order' => 1
            ],
            [
                'tpl_id' => 'corporate-footer',
                'layout_type' => 'footer',
                'name' => 'Corporate Footer',
                'description' => 'Corporate business footer',
                'path' => 'layouts/footers/corporate',
                'sort_order' => 2
            ],
            [
                'tpl_id' => 'creative-footer',
                'layout_type' => 'footer',
                'name' => 'Creative Footer',
                'description' => 'Creative portfolio footer',
                'path' => 'layouts/footers/creative',
                'sort_order' => 3
            ],
            [
                'tpl_id' => 'ecommerce-footer',
                'layout_type' => 'footer',
                'name' => 'Ecommerce Footer',
                'description' => 'Online store footer',
                'path' => 'layouts/footers/ecommerce',
                'sort_order' => 4
            ],
            [
                'tpl_id' => 'minimal-footer',
                'layout_type' => 'footer',
                'name' => 'Minimal Footer',
                'description' => 'Clean minimal footer',
                'path' => 'layouts/footers/minimal',
                'sort_order' => 5
            ],
            
            // Section templates
            [
                'tpl_id' => 'hero-section',
                'layout_type' => 'section',
                'name' => 'Hero Section',
                'description' => 'Main hero banner section',
                'path' => 'layouts/sections/hero',
                'configurable_fields' => [
                    'title' => 'text',
                    'subtitle' => 'text',
                    'button_text' => 'text',
                    'button_url' => 'url',
                    'background_image' => 'image'
                ],
                'sort_order' => 1
            ],
            [
                'tpl_id' => 'about-section',
                'layout_type' => 'section',
                'name' => 'About Section',
                'description' => 'About us content section',
                'path' => 'layouts/sections/about',
                'configurable_fields' => [
                    'title' => 'text',
                    'content' => 'textarea',
                    'image' => 'image'
                ],
                'sort_order' => 2
            ],
            [
                'tpl_id' => 'services-section',
                'layout_type' => 'section',
                'name' => 'Services Section',
                'description' => 'Services showcase section',
                'path' => 'layouts/sections/services',
                'configurable_fields' => [
                    'title' => 'text',
                    'services' => 'repeater'
                ],
                'sort_order' => 3
            ],
            [
                'tpl_id' => 'testimonials-section',
                'layout_type' => 'section',
                'name' => 'Testimonials Section',
                'description' => 'Customer testimonials section',
                'path' => 'layouts/sections/testimonials',
                'configurable_fields' => [
                    'title' => 'text',
                    'testimonials' => 'repeater'
                ],
                'sort_order' => 4
            ],
            [
                'tpl_id' => 'contact-section',
                'layout_type' => 'section',
                'name' => 'Contact Section',
                'description' => 'Contact form and details section',
                'path' => 'layouts/sections/contact',
                'configurable_fields' => [
                    'title' => 'text',
                    'email' => 'email',
                    'phone' => 'text',
                    'address' => 'textarea'
                ],
                'sort_order' => 5
            ],
            [
                'tpl_id' => 'portfolio-section',
                'layout_type' => 'section',
                'name' => 'Portfolio Section',
                'description' => 'Portfolio gallery section',
                'path' => 'layouts/sections/portfolio',
                'configurable_fields' => [
                    'title' => 'text',
                    'projects' => 'repeater'
                ],
                'sort_order' => 6
            ],
            [
                'tpl_id' => 'team-section',
                'layout_type' => 'section',
                'name' => 'Team Section',
                'description' => 'Team members section',
                'path' => 'layouts/sections/team',
                'configurable_fields' => [
                    'title' => 'text',
                    'members' => 'repeater'
                ],
                'sort_order' => 7
            ],
            [
                'tpl_id' => 'pricing-section',
                'layout_type' => 'section',
                'name' => 'Pricing Section',
                'description' => 'Pricing plans section',
                'path' => 'layouts/sections/pricing',
                'configurable_fields' => [
                    'title' => 'text',
                    'plans' => 'repeater'
                ],
                'sort_order' => 8
            ],
            [
                'tpl_id' => 'faq-section',
                'layout_type' => 'section',
                'name' => 'FAQ Section',
                'description' => 'Frequently asked questions section',
                'path' => 'layouts/sections/faq',
                'configurable_fields' => [
                    'title' => 'text',
                    'faqs' => 'repeater'
                ],
                'sort_order' => 9
            ],
            [
                'tpl_id' => 'blog-section',
                'layout_type' => 'section',
                'name' => 'Blog Section',
                'description' => 'Latest blog posts section',
                'path' => 'layouts/sections/blog',
                'configurable_fields' => [
                    'title' => 'text',
                    'posts_count' => 'number'
                ],
                'sort_order' => 10
            ],
            [
                'tpl_id' => 'cta-section',
                'layout_type' => 'section',
                'name' => 'Call to Action Section',
                'description' => 'Call to action banner section',
                'path' => 'layouts/sections/cta',
                'configurable_fields' => [
                    'title' => 'text',
                    'description' => 'textarea',
                    'button_text' => 'text',
                    'button_url' => 'url'
                ],
                'sort_order' => 11
            ],
            [
                'tpl_id' => 'features-section',
                'layout_type' => 'section',
                'name' => 'Features Section',
                'description' => 'Product features section',
                'path' => 'layouts/sections/features',
                'configurable_fields' => [
                    'title' => 'text',
                    'features' => 'repeater'
                ],
                'sort_order' => 12
            ],
            [
                'tpl_id' => 'stats-section',
                'layout_type' => 'section',
                'name' => 'Statistics Section',
                'description' => 'Statistics and numbers section',
                'path' => 'layouts/sections/stats',
                'configurable_fields' => [
                    'title' => 'text',
                    'stats' => 'repeater'
                ],
                'sort_order' => 13
            ],
            [
                'tpl_id' => 'gallery-section',
                'layout_type' => 'section',
                'name' => 'Gallery Section',
                'description' => 'Image gallery section',
                'path' => 'layouts/sections/gallery',
                'configurable_fields' => [
                    'title' => 'text',
                    'images' => 'gallery'
                ],
                'sort_order' => 14
            ],
            [
                'tpl_id' => 'newsletter-section',
                'layout_type' => 'section',
                'name' => 'Newsletter Section',
                'description' => 'Newsletter signup section',
                'path' => 'layouts/sections/newsletter',
                'configurable_fields' => [
                    'title' => 'text',
                    'description' => 'textarea',
                    'placeholder' => 'text'
                ],
                'sort_order' => 15
            ],
            [
                'tpl_id' => 'video-section',
                'layout_type' => 'section',
                'name' => 'Video Section',
                'description' => 'Video showcase section',
                'path' => 'layouts/sections/video',
                'configurable_fields' => [
                    'title' => 'text',
                    'video_url' => 'url',
                    'poster_image' => 'image'
                ],
                'sort_order' => 16
            ],
            [
                'tpl_id' => 'countdown-section',
                'layout_type' => 'section',
                'name' => 'Countdown Section',
                'description' => 'Event countdown section',
                'path' => 'layouts/sections/countdown',
                'configurable_fields' => [
                    'title' => 'text',
                    'target_date' => 'datetime'
                ],
                'sort_order' => 17
            ],
            [
                'tpl_id' => 'brands-section',
                'layout_type' => 'section',
                'name' => 'Brands Section',
                'description' => 'Partner brands showcase section',
                'path' => 'layouts/sections/brands',
                'configurable_fields' => [
                    'title' => 'text',
                    'brands' => 'repeater'
                ],
                'sort_order' => 18
            ],
            [
                'tpl_id' => 'timeline-section',
                'layout_type' => 'section',
                'name' => 'Timeline Section',
                'description' => 'Timeline or process section',
                'path' => 'layouts/sections/timeline',
                'configurable_fields' => [
                    'title' => 'text',
                    'steps' => 'repeater'
                ],
                'sort_order' => 19
            ],
            [
                'tpl_id' => 'comparison-section',
                'layout_type' => 'section',
                'name' => 'Comparison Section',
                'description' => 'Feature comparison section',
                'path' => 'layouts/sections/comparison',
                'configurable_fields' => [
                    'title' => 'text',
                    'comparison_table' => 'table'
                ],
                'sort_order' => 20
            ],
        ];

        foreach ($layouts as $layout) {
            TplLayout::updateOrCreate(
                ['tpl_id' => $layout['tpl_id']],
                $layout
            );
        }
    }
}
