<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TplLayout;

class FutureTemplatesSeeder extends Seeder
{
    /**
     * Example: How to add new templates in the future
     */
    public function run(): void
    {
        echo "🎨 Creating future templates...\n";
        
        $this->createNewHeaderTemplate();
        $this->createNewSectionTemplate();
        $this->createNewFooterTemplate();
        
        echo "✅ Future templates created successfully!\n";
    }

    /**
     * Example: Create a new header template
     */
    private function createNewHeaderTemplate()
    {
        TplLayout::updateOrCreate(
            ['tpl_id' => 'corporate-header-2025'],
            [
                'tpl_id' => 'corporate-header-2025',
                'layout_type' => 'header',
                'name' => 'Corporate Header 2025',
                'description' => 'Professional corporate navigation with dark theme',
                'preview_image' => '/img/templates/corporate-header-preview.jpg',
                'path' => 'frontend.templates.headers.corporate-header',
                'content' => $this->getCorporateHeaderContent(),
                'configurable_fields' => [
                    'company_name' => [
                        'type' => 'text', 
                        'default' => 'Your Company', 
                        'label' => 'Company Name'
                    ],
                    'logo_url' => [
                        'type' => 'url', 
                        'default' => '', 
                        'label' => 'Logo URL'
                    ],
                    'background_color' => [
                        'type' => 'color', 
                        'default' => '#1a1a1a', 
                        'label' => 'Background Color'
                    ],
                    'text_color' => [
                        'type' => 'color', 
                        'default' => '#ffffff', 
                        'label' => 'Text Color'
                    ],
                    'show_phone' => [
                        'type' => 'boolean', 
                        'default' => true, 
                        'label' => 'Show Phone Number'
                    ],
                    'phone_number' => [
                        'type' => 'text', 
                        'default' => '+1-555-0123', 
                        'label' => 'Phone Number'
                    ],
                    'menu_items' => [
                        'type' => 'array',
                        'default' => [
                            ['label' => 'Home', 'url' => '/', 'target' => '_self'],
                            ['label' => 'About', 'url' => '/about', 'target' => '_self'],
                            ['label' => 'Services', 'url' => '/services', 'target' => '_self'],
                            ['label' => 'Contact', 'url' => '/contact', 'target' => '_self']
                        ],
                        'label' => 'Navigation Menu Items'
                    ]
                ],
                'default_config' => [
                    'company_name' => 'Your Company',
                    'background_color' => '#1a1a1a',
                    'text_color' => '#ffffff',
                    'show_phone' => true,
                    'phone_number' => '+1-555-0123',
                    'menu_items' => [
                        ['label' => 'Home', 'url' => '/', 'target' => '_self'],
                        ['label' => 'About', 'url' => '/about', 'target' => '_self'],
                        ['label' => 'Services', 'url' => '/services', 'target' => '_self'],
                        ['label' => 'Contact', 'url' => '/contact', 'target' => '_self']
                    ]
                ],
                'status' => true,
                'sort_order' => 10
            ]
        );
        
        echo "  ✓ Corporate Header 2025\n";
    }

    /**
     * Example: Create a new section template
     */
    private function createNewSectionTemplate()
    {
        TplLayout::updateOrCreate(
            ['tpl_id' => 'stats-counter-modern'],
            [
                'tpl_id' => 'stats-counter-modern',
                'layout_type' => 'section',
                'name' => 'Modern Stats Counter',
                'description' => 'Animated statistics counter with icons and descriptions',
                'preview_image' => '/img/templates/stats-counter-preview.jpg',
                'path' => 'frontend.templates.sections.stats-counter-modern',
                'content' => $this->getStatsCounterContent(),
                'configurable_fields' => [
                    'section_title' => [
                        'type' => 'text', 
                        'default' => 'Our Achievements', 
                        'label' => 'Section Title'
                    ],
                    'background_color' => [
                        'type' => 'color', 
                        'default' => '#f8f9fa', 
                        'label' => 'Background Color'
                    ],
                    'stats' => [
                        'type' => 'array',
                        'default' => [
                            [
                                'number' => '100+',
                                'label' => 'Happy Clients',
                                'icon' => 'fas fa-users',
                                'description' => 'Satisfied customers worldwide'
                            ],
                            [
                                'number' => '250+',
                                'label' => 'Projects Done',
                                'icon' => 'fas fa-project-diagram',
                                'description' => 'Successful projects completed'
                            ],
                            [
                                'number' => '50+',
                                'label' => 'Team Members',
                                'icon' => 'fas fa-user-tie',
                                'description' => 'Professional experts'
                            ],
                            [
                                'number' => '5+',
                                'label' => 'Years Experience',
                                'icon' => 'fas fa-calendar-alt',
                                'description' => 'Years in business'
                            ]
                        ],
                        'label' => 'Statistics Data'
                    ]
                ],
                'default_config' => [
                    'section_title' => 'Our Achievements',
                    'background_color' => '#f8f9fa',
                    'stats' => [
                        [
                            'number' => '100+',
                            'label' => 'Happy Clients',
                            'icon' => 'fas fa-users',
                            'description' => 'Satisfied customers worldwide'
                        ],
                        [
                            'number' => '250+',
                            'label' => 'Projects Done',
                            'icon' => 'fas fa-project-diagram',
                            'description' => 'Successful projects completed'
                        ],
                        [
                            'number' => '50+',
                            'label' => 'Team Members',
                            'icon' => 'fas fa-user-tie',
                            'description' => 'Professional experts'
                        ],
                        [
                            'number' => '5+',
                            'label' => 'Years Experience',
                            'icon' => 'fas fa-calendar-alt',
                            'description' => 'Years in business'
                        ]
                    ]
                ],
                'status' => true,
                'sort_order' => 10
            ]
        );
        
        echo "  ✓ Modern Stats Counter\n";
    }

    /**
     * Example: Create a new footer template
     */
    private function createNewFooterTemplate()
    {
        TplLayout::updateOrCreate(
            ['tpl_id' => 'minimal-footer-2025'],
            [
                'tpl_id' => 'minimal-footer-2025',
                'layout_type' => 'footer',
                'name' => 'Minimal Footer 2025',
                'description' => 'Clean minimal footer with essential links only',
                'preview_image' => '/img/templates/minimal-footer-preview.jpg',
                'path' => 'frontend.templates.footers.minimal-footer',
                'content' => $this->getMinimalFooterContent(),
                'configurable_fields' => [
                    'company_name' => [
                        'type' => 'text', 
                        'default' => 'Your Company', 
                        'label' => 'Company Name'
                    ],
                    'copyright_text' => [
                        'type' => 'text', 
                        'default' => 'All rights reserved.', 
                        'label' => 'Copyright Text'
                    ],
                    'background_color' => [
                        'type' => 'color', 
                        'default' => '#ffffff', 
                        'label' => 'Background Color'
                    ],
                    'text_color' => [
                        'type' => 'color', 
                        'default' => '#666666', 
                        'label' => 'Text Color'
                    ],
                    'social_links' => [
                        'type' => 'array',
                        'default' => [
                            ['platform' => 'facebook', 'url' => '#', 'icon' => 'fab fa-facebook-f'],
                            ['platform' => 'twitter', 'url' => '#', 'icon' => 'fab fa-twitter'],
                            ['platform' => 'linkedin', 'url' => '#', 'icon' => 'fab fa-linkedin-in']
                        ],
                        'label' => 'Social Media Links'
                    ]
                ],
                'default_config' => [
                    'company_name' => 'Your Company',
                    'copyright_text' => 'All rights reserved.',
                    'background_color' => '#ffffff',
                    'text_color' => '#666666',
                    'social_links' => [
                        ['platform' => 'facebook', 'url' => '#', 'icon' => 'fab fa-facebook-f'],
                        ['platform' => 'twitter', 'url' => '#', 'icon' => 'fab fa-twitter'],
                        ['platform' => 'linkedin', 'url' => '#', 'icon' => 'fab fa-linkedin-in']
                    ]
                ],
                'status' => true,
                'sort_order' => 10
            ]
        );
        
        echo "  ✓ Minimal Footer 2025\n";
    }

    /**
     * Get corporate header template content
     */
    private function getCorporateHeaderContent()
    {
        return [
            'html' => '<!-- Corporate Header Template Content -->',
            'css' => '/* Corporate Header Styles */',
            'js' => '/* Corporate Header JavaScript */'
        ];
    }

    /**
     * Get stats counter template content
     */
    private function getStatsCounterContent()
    {
        return [
            'html' => '<!-- Stats Counter Template Content -->',
            'css' => '/* Stats Counter Styles */',
            'js' => '/* Stats Counter JavaScript */'
        ];
    }

    /**
     * Get minimal footer template content
     */
    private function getMinimalFooterContent()
    {
        return [
            'html' => '<!-- Minimal Footer Template Content -->',
            'css' => '/* Minimal Footer Styles */',
            'js' => '/* Minimal Footer JavaScript */'
        ];
    }
}
