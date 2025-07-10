<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Site;
use App\Models\TplPage;
use App\Models\TplLayout;
use App\Models\TplLayoutType;
use App\Models\TplDesign;

class DesignSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $site = Site::where('status', true)->first();
        if (!$site) {
            return;
        }
        
        $pages = TplPage::where('site_id', $site->id)->get();
        if ($pages->isEmpty()) {
            return;
        }
        
        // Get layout types
        $navType = TplLayoutType::where('name', 'nav')->first();
        $sectionType = TplLayoutType::where('name', 'section')->first();
        $footerType = TplLayoutType::where('name', 'footer')->first();
        
        if (!$navType || !$sectionType || !$footerType) {
            return;
        }
        
        // Get layouts with their types
        $modernHeader = TplLayout::with('type')->where('name', 'Modern Header')->first();
        $modernFooter = TplLayout::with('type')->where('name', 'Modern Footer')->first();
        $modernHero = TplLayout::with('type')->where('name', 'modern-hero')->first();
        $featuresGrid = TplLayout::with('type')->where('name', 'features-grid')->first();
        $aboutSection = TplLayout::with('type')->where('name', 'about-section')->first();
        $servicesGrid = TplLayout::with('type')->where('name', 'services-grid')->first();
        $teamMembers = TplLayout::with('type')->where('name', 'team-members')->first();
        $testimonials = TplLayout::with('type')->where('name', 'testimonials')->first();
        $blogGrid = TplLayout::with('type')->where('name', 'Blog Grid')->first();
        $contactForm = TplLayout::with('type')->where('name', 'Contact Form')->first();
        $newsletterForm = TplLayout::with('type')->where('name', 'Newsletter Form')->first();
        $quoteForm = TplLayout::with('type')->where('name', 'Quote Form')->first();
        $ctaSection = TplLayout::with('type')->where('name', 'CTA Section')->first();
        $pricingPlans = TplLayout::with('type')->where('name', 'Pricing Plans')->first();
        
        if (!$modernHeader || !$modernFooter) {
            return;
        }
        
        // Create header and footer designs for all pages
        foreach ($pages as $page) {
            // Add header
            TplDesign::create([
                'site_id' => $site->id,
                'page_id' => $page->id,
                'layout_id' => $modernHeader->id,
                'layout_type_id' => $navType->id,
                'lang_code' => 'en',
                'sort_order' => 1,
                'status' => true
            ]);
            
            // Add footer
            TplDesign::create([
                'site_id' => $site->id,
                'page_id' => $page->id,
                'layout_id' => $modernFooter->id,
                'layout_type_id' => $footerType->id,
                'lang_code' => 'en',
                'sort_order' => 999,
                'status' => true
            ]);
            
            // Add page-specific sections
            switch ($page->slug) {
                case 'home':
                    $this->createHomePageDesigns($site, $page, [
                        'hero' => $modernHero,
                        'features' => $featuresGrid,
                        'about' => $aboutSection,
                        'services' => $servicesGrid,
                        'testimonials' => $testimonials,
                        'newsletter' => $newsletterForm,
                        'cta' => $ctaSection
                    ], $sectionType);
                    break;
                    
                case 'about':
                    $this->createAboutPageDesigns($site, $page, [
                        'hero' => $modernHero,
                        'about' => $aboutSection,
                        'team' => $teamMembers,
                        'features' => $featuresGrid,
                        'testimonials' => $testimonials,
                        'cta' => $ctaSection
                    ], $sectionType);
                    break;
                    
                case 'services':
                    $this->createServicesPageDesigns($site, $page, [
                        'hero' => $modernHero,
                        'services' => $servicesGrid,
                        'features' => $featuresGrid,
                        'testimonials' => $testimonials,
                        'pricing' => $pricingPlans,
                        'quote' => $quoteForm,
                        'cta' => $ctaSection
                    ], $sectionType);
                    break;
                    
                case 'portfolio':
                    $this->createPortfolioPageDesigns($site, $page, [
                        'hero' => $modernHero,
                        'grid' => $servicesGrid,
                        'about' => $aboutSection,
                        'testimonials' => $testimonials,
                        'cta' => $ctaSection
                    ], $sectionType);
                    break;
                    
                case 'pricing':
                    $this->createPricingPageDesigns($site, $page, [
                        'hero' => $modernHero,
                        'pricing' => $pricingPlans,
                        'features' => $featuresGrid,
                        'cta' => $ctaSection
                    ], $sectionType);
                    break;
                    
                case 'blog':
                    $this->createBlogPageDesigns($site, $page, [
                        'hero' => $modernHero,
                        'blog' => $blogGrid,
                        'features' => $featuresGrid,
                        'testimonials' => $testimonials,
                        'newsletter' => $newsletterForm,
                        'cta' => $ctaSection
                    ], $sectionType);
                    break;
                    
                case 'contact':
                    $this->createContactPageDesigns($site, $page, [
                        'hero' => $modernHero,
                        'contact' => $contactForm,
                        'features' => $featuresGrid,
                        'testimonials' => $testimonials,
                        'about' => $aboutSection,
                        'cta' => $ctaSection
                    ], $sectionType);
                    break;
            }
        }
    }
    
    private function createHomePageDesigns($site, $page, $layouts, $sectionType)
    {
        $order = 2;
        
        if ($layouts['hero']) {
            TplDesign::create([
                'site_id' => $site->id,
                'page_id' => $page->id,
                'layout_id' => $layouts['hero']->id,
                'layout_type_id' => $sectionType->id,
                'lang_code' => 'en',
                'sort_order' => $order++,
                'status' => true,
                'data' => json_encode([
                    'title' => 'Transform Your Business with Innovation',
                    'subtitle' => 'Join thousands of satisfied customers who are already using our services to grow their business and stay ahead of the competition.',
                    'primary_button' => ['text' => 'Get Started', 'url' => '/contact'],
                    'secondary_button' => ['text' => 'Our Services', 'url' => '/services'],
                    'stats' => [
                        ['number' => '500+', 'label' => 'Happy Clients'],
                        ['number' => '150+', 'label' => 'Projects Done'],
                        ['number' => '15+', 'label' => 'Years Experience']
                    ]
                ])
            ]);
        }
        
        if ($layouts['features']) {
            TplDesign::create([
                'site_id' => $site->id,
                'page_id' => $page->id,
                'layout_id' => $layouts['features']->id,
                'layout_type_id' => $sectionType->id,
                'lang_code' => 'en',
                'sort_order' => $order++,
                'status' => true,
                'data' => json_encode([
                    'title' => 'Why Choose Us?',
                    'subtitle' => 'We combine innovative technology with exceptional service to deliver solutions that drive your business forward.',
                    'features' => [
                        [
                            'icon' => 'trending-up',
                            'title' => 'Proven Results',
                            'description' => 'Our solutions have helped businesses increase efficiency and revenue through digital transformation.'
                        ],
                        [
                            'icon' => 'users',
                            'title' => 'Expert Team',
                            'description' => 'Our certified professionals bring years of experience in delivering cutting-edge solutions.'
                        ],
                        [
                            'icon' => 'clock',
                            'title' => '24/7 Support',
                            'description' => 'Round-the-clock technical support to ensure your systems run smoothly at all times.'
                        ]
                    ]
                ])
            ]);
        }
        
        if ($layouts['about']) {
            TplDesign::create([
                'site_id' => $site->id,
                'page_id' => $page->id,
                'layout_id' => $layouts['about']->id,
                'layout_type_id' => $sectionType->id,
                'lang_code' => 'en',
                'sort_order' => $order++,
                'status' => true,
                'data' => json_encode([
                    'title' => 'About Us',
                    'subtitle' => 'Our Story',
                    'content' => 'We are a team of passionate professionals committed to delivering innovative solutions that drive success for our clients.',
                    'image' => '/img/about-illustration.svg'
                ])
            ]);
        }
        
        if ($layouts['services']) {
            TplDesign::create([
                'site_id' => $site->id,
                'page_id' => $page->id,
                'layout_id' => $layouts['services']->id,
                'layout_type_id' => $sectionType->id,
                'lang_code' => 'en',
                'sort_order' => $order++,
                'status' => true,
                'data' => json_encode([
                    'title' => 'Our Services',
                    'subtitle' => 'Comprehensive solutions to power your business growth',
                    'services' => [
                        [
                            'title' => 'Custom Software Development',
                            'description' => 'Bespoke software solutions designed specifically for your business requirements, from concept to deployment.',
                            'icon' => 'code',
                            'url' => '/services/software-development'
                        ],
                        [
                            'title' => 'Web Application Development',
                            'description' => 'Responsive, scalable web applications that enhance user experience and drive business growth.',
                            'icon' => 'globe',
                            'url' => '/services/web-development'
                        ],
                        [
                            'title' => 'Mobile App Development',
                            'description' => 'Native and cross-platform mobile applications for iOS and Android that engage users and extend your reach.',
                            'icon' => 'smartphone',
                            'url' => '/services/mobile-development'
                        ],
                        [
                            'title' => 'UI/UX Design',
                            'description' => 'User-centered design services that create intuitive, engaging interfaces and exceptional user experiences.',
                            'icon' => 'layout',
                            'url' => '/services/ui-ux-design'
                        ],
                        [
                            'title' => 'Cloud Services',
                            'description' => 'Cloud migration, management, and optimization services to enhance scalability, security, and cost-efficiency.',
                            'icon' => 'cloud',
                            'url' => '/services/cloud-services'
                        ],
                        [
                            'title' => 'IT Consulting',
                            'description' => 'Expert guidance on technology strategy, digital transformation, and IT infrastructure optimization.',
                            'icon' => 'briefcase',
                            'url' => '/services/it-consulting'
                        ]
                    ]
                ])
            ]);
        }
        
        if ($layouts['testimonials']) {
            TplDesign::create([
                'site_id' => $site->id,
                'page_id' => $page->id,
                'layout_id' => $layouts['testimonials']->id,
                'layout_type_id' => $sectionType->id,
                'lang_code' => 'en',
                'sort_order' => $order++,
                'status' => true,
                'data' => json_encode([
                    'title' => 'Client Testimonials',
                    'subtitle' => 'See what our clients have to say about our services',
                    'testimonials' => [
                        [
                            'name' => 'John Smith',
                            'position' => 'CEO, Tech Innovations',
                            'rating' => 5,
                            'quote' => 'TechCorp has transformed our business with their innovative solutions. Their team was professional, responsive, and delivered beyond expectations.',
                            'avatar' => 'https://source.unsplash.com/random/100x100/?man'
                        ],
                        [
                            'name' => 'Sarah Johnson',
                            'position' => 'Marketing Director, Growth Co.',
                            'rating' => 5,
                            'quote' => 'Working with TechCorp was a game-changer for our marketing efforts. Their solutions helped us increase leads by 150% in just 3 months.',
                            'avatar' => 'https://source.unsplash.com/random/100x100/?woman'
                        ],
                        [
                            'name' => 'David Wilson',
                            'position' => 'CTO, Enterprise Solutions',
                            'rating' => 4,
                            'quote' => 'The technical expertise at TechCorp is impressive. They solved problems that other vendors couldn\'t even understand.',
                            'avatar' => 'https://source.unsplash.com/random/100x100/?person'
                        ]
                    ]
                ])
            ]);
        }
        
        if ($layouts['newsletter']) {
            TplDesign::create([
                'site_id' => $site->id,
                'page_id' => $page->id,
                'layout_id' => $layouts['newsletter']->id,
                'layout_type_id' => $sectionType->id,
                'lang_code' => 'en',
                'sort_order' => $order++,
                'status' => true,
                'data' => json_encode([
                    'title' => 'Stay Updated',
                    'subtitle' => 'Subscribe to our newsletter and get the latest updates on industry trends and our services.',
                    'background' => 'primary'
                ])
            ]);
        }
        
        if ($layouts['cta']) {
            TplDesign::create([
                'site_id' => $site->id,
                'page_id' => $page->id,
                'layout_id' => $layouts['cta']->id,
                'layout_type_id' => $sectionType->id,
                'lang_code' => 'en',
                'sort_order' => $order++,
                'status' => true,
                'data' => json_encode([
                    'title' => 'Ready to Transform Your Business?',
                    'content' => 'Join thousands of satisfied customers who are already using our services to grow their business and stay ahead of the competition.',
                    'primary_button' => ['text' => 'Get Started Now', 'url' => '/contact'],
                    'secondary_button' => ['text' => 'Learn More', 'url' => '/services'],
                    'image' => 'https://source.unsplash.com/random/800x800/?business,technology',
                    'features' => [
                        'Free 14-day trial',
                        'No credit card required',
                        'Cancel anytime',
                        '24/7 customer support'
                    ],
                    'note' => '* No credit card required for trial. Cancel anytime.'
                ])
            ]);
        }
    }
    
    private function createAboutPageDesigns($site, $page, $layouts, $sectionType)
    {
        $order = 2;
        
        if ($layouts['hero']) {
            TplDesign::create([
                'site_id' => $site->id,
                'page_id' => $page->id,
                'layout_id' => $layouts['hero']->id,
                'layout_type_id' => $sectionType->id,
                'lang_code' => 'en',
                'sort_order' => $order++,
                'status' => true,
                'data' => json_encode([
                    'title' => 'About Our Company',
                    'subtitle' => 'Learn more about our mission, values, and the team behind our success',
                    'primary_button' => ['text' => 'Join Our Team', 'url' => '/careers'],
                    'secondary_button' => ['text' => 'Our Services', 'url' => '/services']
                ])
            ]);
        }
        
        if ($layouts['about']) {
            TplDesign::create([
                'site_id' => $site->id,
                'page_id' => $page->id,
                'layout_id' => $layouts['about']->id,
                'layout_type_id' => $sectionType->id,
                'lang_code' => 'en',
                'sort_order' => $order++,
                'status' => true,
                'data' => json_encode([
                    'title' => 'About Us',
                    'subtitle' => 'Our Story',
                    'content' => 'We are a team of passionate professionals committed to delivering innovative solutions that drive success for our clients.',
                    'image' => '/img/about-illustration.svg'
                ])
            ]);
        }
        
        if ($layouts['team']) {
            TplDesign::create([
                'site_id' => $site->id,
                'page_id' => $page->id,
                'layout_id' => $layouts['team']->id,
                'layout_type_id' => $sectionType->id,
                'lang_code' => 'en',
                'sort_order' => $order++,
                'status' => true,
                'data' => json_encode([
                    'title' => 'Meet Our Team',
                    'subtitle' => 'The visionaries and experts driving our company forward',
                    'members' => [
                        [
                            'name' => 'Jane Doe',
                            'position' => 'CEO & Founder',
                            'description' => '15+ years of experience in technology leadership and innovation. Former VP of Technology at Fortune 500 company.',
                            'image' => 'https://source.unsplash.com/random/100x100/?person',
                            'social_links' => [
                                ['platform' => 'LinkedIn', 'url' => '#'],
                                ['platform' => 'Twitter', 'url' => '#']
                            ]
                        ],
                        [
                            'name' => 'John Smith',
                            'position' => 'CTO',
                            'description' => 'Expert in emerging technologies and software architecture.',
                            'image' => 'https://source.unsplash.com/random/100x100/?person',
                            'social_links' => [
                                ['platform' => 'LinkedIn', 'url' => '#']
                            ]
                        ],
                        [
                            'name' => 'Emily Johnson',
                            'position' => 'Head of Client Services',
                            'description' => 'Ensuring exceptional customer experiences.',
                            'image' => 'https://source.unsplash.com/random/100x100/?person',
                            'social_links' => []
                        ]
                    ]
                ])
            ]);
        }
        
        if ($layouts['testimonials']) {
            TplDesign::create([
                'site_id' => $site->id,
                'page_id' => $page->id,
                'layout_id' => $layouts['testimonials']->id,
                'layout_type_id' => $sectionType->id,
                'lang_code' => 'en',
                'sort_order' => $order++,
                'status' => true,
                'data' => json_encode([
                    'title' => 'Client Testimonials',
                    'subtitle' => 'See what our clients have to say about our services',
                    'testimonials' => [
                        [
                            'name' => 'John Smith',
                            'position' => 'CEO, Tech Innovations',
                            'rating' => 5,
                            'quote' => 'TechCorp has transformed our business with their innovative solutions. Their team was professional, responsive, and delivered beyond expectations.',
                            'avatar' => 'https://source.unsplash.com/random/100x100/?man'
                        ],
                        [
                            'name' => 'Sarah Johnson',
                            'position' => 'Marketing Director, Growth Co.',
                            'rating' => 5,
                            'quote' => 'Working with TechCorp was a game-changer for our marketing efforts. Their solutions helped us increase leads by 150% in just 3 months.',
                            'avatar' => 'https://source.unsplash.com/random/100x100/?woman'
                        ],
                        [
                            'name' => 'David Wilson',
                            'position' => 'CTO, Enterprise Solutions',
                            'rating' => 4,
                            'quote' => 'The technical expertise at TechCorp is impressive. They solved problems that other vendors couldn\'t even understand.',
                            'avatar' => 'https://source.unsplash.com/random/100x100/?person'
                        ]
                    ]
                ])
            ]);
        }
    }
    
    private function createServicesPageDesigns($site, $page, $layouts, $sectionType)
    {
        $order = 2;
        
        if ($layouts['hero']) {
            TplDesign::create([
                'site_id' => $site->id,
                'page_id' => $page->id,
                'layout_id' => $layouts['hero']->id,
                'layout_type_id' => $sectionType->id,
                'lang_code' => 'en',
                'sort_order' => $order++,
                'status' => true,
                'data' => json_encode([
                    'title' => 'Our Services',
                    'subtitle' => 'Discover how we can help transform your business',
                    'primary_button' => ['text' => 'Get Started', 'url' => '/contact'],
                    'secondary_button' => ['text' => 'Learn More', 'url' => '#services'],
                    'stats' => [
                        ['number' => '500+', 'label' => 'Projects Delivered'],
                        ['number' => '98%', 'label' => 'Client Satisfaction'],
                        ['number' => '24/7', 'label' => 'Support Available']
                    ]
                ])
            ]);
        }

        if ($layouts['services']) {
            TplDesign::create([
                'site_id' => $site->id,
                'page_id' => $page->id,
                'layout_id' => $layouts['services']->id,
                'layout_type_id' => $sectionType->id,
                'lang_code' => 'en',
                'sort_order' => $order++,
                'status' => true,
                'data' => json_encode([
                    'title' => 'Our Core Services',
                    'subtitle' => 'Comprehensive solutions tailored to your needs',
                    'services' => [
                        [
                            'icon' => 'trending-up',
                            'title' => 'Business Strategy',
                            'description' => 'Strategic planning and execution to drive growth.',
                            'url' => '/services/strategy'
                        ],
                        [
                            'icon' => 'shield',
                            'title' => 'Cybersecurity',
                            'description' => 'Protect your business with advanced security solutions.',
                            'url' => '/services/security'
                        ],
                        [
                            'icon' => 'cloud',
                            'title' => 'Cloud Solutions',
                            'description' => 'Scalable cloud infrastructure for modern businesses.',
                            'url' => '/services/cloud'
                        ]
                    ]
                ])
            ]);
        }

        if ($layouts['features']) {
            TplDesign::create([
                'site_id' => $site->id,
                'page_id' => $page->id,
                'layout_id' => $layouts['features']->id,
                'layout_type_id' => $sectionType->id,
                'lang_code' => 'en',
                'sort_order' => $order++,
                'status' => true,
                'data' => json_encode([
                    'title' => 'Why Choose Us',
                    'subtitle' => 'Benefits of working with our team',
                    'features' => [
                        [
                            'icon' => 'clock',
                            'title' => '24/7 Support',
                            'description' => 'Round-the-clock support to ensure your business runs smoothly.'
                        ],
                        [
                            'icon' => 'users',
                            'title' => 'Expert Team',
                            'description' => 'Skilled professionals with years of industry experience.'
                        ],
                        [
                            'icon' => 'check-circle',
                            'title' => 'Proven Results',
                            'description' => 'Track record of successful project deliveries.'
                        ]
                    ]
                ])
            ]);
        }

        if ($layouts['pricing']) {
            TplDesign::create([
                'site_id' => $site->id,
                'page_id' => $page->id,
                'layout_id' => $layouts['pricing']->id,
                'layout_type_id' => $sectionType->id,
                'lang_code' => 'en',
                'sort_order' => $order++,
                'status' => true,
                'data' => json_encode([
                    'title' => 'Transparent Pricing',
                    'subtitle' => 'Choose the plan that fits your needs',
                    'plans' => [
                        [
                            'name' => 'Starter',
                            'price' => '$99',
                            'period' => 'month',
                            'features' => [
                                'Basic Support',
                                'Up to 5 Users',
                                '10GB Storage',
                                'Basic Analytics'
                            ],
                            'cta' => ['text' => 'Get Started', 'url' => '/contact?plan=starter']
                        ],
                        [
                            'name' => 'Professional',
                            'price' => '$199',
                            'period' => 'month',
                            'popular' => true,
                            'features' => [
                                'Priority Support',
                                'Up to 20 Users',
                                '50GB Storage',
                                'Advanced Analytics',
                                'Custom Reports'
                            ],
                            'cta' => ['text' => 'Get Started', 'url' => '/contact?plan=pro']
                        ],
                        [
                            'name' => 'Enterprise',
                            'price' => '$499',
                            'period' => 'month',
                            'features' => [
                                '24/7 Support',
                                'Unlimited Users',
                                'Unlimited Storage',
                                'Custom Solutions',
                                'Dedicated Manager'
                            ],
                            'cta' => ['text' => 'Contact Us', 'url' => '/contact?plan=enterprise']
                        ]
                    ]
                ])
            ]);
        }

        if ($layouts['testimonials']) {
            TplDesign::create([
                'site_id' => $site->id,
                'page_id' => $page->id,
                'layout_id' => $layouts['testimonials']->id,
                'layout_type_id' => $sectionType->id,
                'lang_code' => 'en',
                'sort_order' => $order++,
                'status' => true,
                'data' => json_encode([
                    'title' => 'Client Testimonials',
                    'subtitle' => 'What our clients say about our services',
                    'testimonials' => [
                        [
                            'content' => 'The team delivered exceptional results. Our efficiency improved by 50% after implementing their solutions.',
                            'author' => 'John Smith',
                            'position' => 'CTO',
                            'company' => 'Tech Corp',
                            'image' => 'https://source.unsplash.com/random/100x100/?man,suit'
                        ],
                        [
                            'content' => 'Outstanding service and support. They truly understand our business needs and deliver accordingly.',
                            'author' => 'Sarah Johnson',
                            'position' => 'CEO',
                            'company' => 'Digital Solutions',
                            'image' => 'https://source.unsplash.com/random/100x100/?woman,business'
                        ],
                        [
                            'content' => 'Their expertise in cloud solutions helped us scale our operations seamlessly.',
                            'author' => 'Mike Chen',
                            'position' => 'Operations Director',
                            'company' => 'Growth Inc',
                            'image' => 'https://source.unsplash.com/random/100x100/?man,professional'
                        ]
                    ]
                ])
            ]);
        }

        if ($layouts['quote']) {
            TplDesign::create([
                'site_id' => $site->id,
                'page_id' => $page->id,
                'layout_id' => $layouts['quote']->id,
                'layout_type_id' => $sectionType->id,
                'lang_code' => 'en',
                'sort_order' => $order++,
                'status' => true,
                'data' => json_encode([
                    'title' => 'Get a Custom Quote',
                    'subtitle' => 'Tell us about your project and we\'ll provide a detailed proposal tailored to your needs.',
                    'services' => [
                        'Web Development',
                        'Mobile App Development',
                        'UI/UX Design',
                        'E-commerce Solutions',
                        'Digital Marketing',
                        'Cloud Services',
                        'IT Consulting',
                        'Custom Software'
                    ]
                ])
            ]);
        }

        if ($layouts['cta']) {
            TplDesign::create([
                'site_id' => $site->id,
                'page_id' => $page->id,
                'layout_id' => $layouts['cta']->id,
                'layout_type_id' => $sectionType->id,
                'lang_code' => 'en',
                'sort_order' => $order++,
                'status' => true,
                'data' => json_encode([
                    'title' => 'Ready to Transform Your Business?',
                    'subtitle' => 'Get started with our services today and see the difference we can make.',
                    'primary_button' => ['text' => 'Get Started', 'url' => '/contact'],
                    'secondary_button' => ['text' => 'Learn More', 'url' => '/about'],
                    'background' => 'primary'
                ])
            ]);
        }
    }
    
    private function createPortfolioPageDesigns($site, $page, $layouts, $sectionType)
    {
        $order = 2;
        
        if ($layouts['hero']) {
            TplDesign::create([
                'site_id' => $site->id,
                'page_id' => $page->id,
                'layout_id' => $layouts['hero']->id,
                'layout_type_id' => $sectionType->id,
                'lang_code' => 'en',
                'sort_order' => $order++,
                'status' => true,
                'data' => json_encode([
                    'title' => 'Our Work',
                    'subtitle' => 'Discover how we have helped businesses succeed',
                    'button_text' => 'Get In Touch',
                    'button_url' => '/contact'
                ])
            ]);
        }
        
        if ($layouts['grid']) {
            TplDesign::create([
                'site_id' => $site->id,
                'page_id' => $page->id,
                'layout_id' => $layouts['grid']->id,
                'layout_type_id' => $sectionType->id,
                'lang_code' => 'en',
                'sort_order' => $order++,
                'status' => true,
                'data' => json_encode([
                    'title' => 'Featured Projects',
                    'subtitle' => 'A selection of our successful projects',
                    'projects' => [
                        [
                            'title' => 'Project Alpha',
                            'description' => 'A comprehensive digital transformation for a leading retail brand.',
                            'image' => 'https://source.unsplash.com/random/600x400/?retail',
                            'url' => '/portfolio/project-alpha'
                        ],
                        [
                            'title' => 'Project Beta',
                            'description' => 'Development of a scalable e-commerce platform using cutting-edge technology.',
                            'image' => 'https://source.unsplash.com/random/600x400/?ecommerce',
                            'url' => '/portfolio/project-beta'
                        ],
                        [
                            'title' => 'Project Gamma',
                            'description' => 'Implementation of a robust cloud infrastructure for a global enterprise.',
                            'image' => 'https://source.unsplash.com/random/600x400/?cloud',
                            'url' => '/portfolio/project-gamma'
                        ]
                    ],
                    'action' => [
                        'text' => 'View All Projects',
                        'url' => '/portfolio',
                        'btn_style' => 'outline-primary',
                        'btn_size' => 'btn-lg'
                    ]
                ])
            ]);
        }
        
        if ($layouts['testimonials']) {
            TplDesign::create([
                'site_id' => $site->id,
                'page_id' => $page->id,
                'layout_id' => $layouts['testimonials']->id,
                'layout_type_id' => $sectionType->id,
                'lang_code' => 'en',
                'sort_order' => $order++,
                'status' => true,
                'data' => json_encode([
                    'title' => 'Client Testimonials',
                    'subtitle' => 'See what our clients have to say about our services',
                    'testimonials' => [
                        [
                            'name' => 'John Smith',
                            'position' => 'CEO, Tech Innovations',
                            'rating' => 5,
                            'quote' => 'TechCorp has transformed our business with their innovative solutions. Their team was professional, responsive, and delivered beyond expectations.',
                            'avatar' => 'https://source.unsplash.com/random/100x100/?man'
                        ],
                        [
                            'name' => 'Sarah Johnson',
                            'position' => 'Marketing Director, Growth Co.',
                            'rating' => 5,
                            'quote' => 'Working with TechCorp was a game-changer for our marketing efforts. Their solutions helped us increase leads by 150% in just 3 months.',
                            'avatar' => 'https://source.unsplash.com/random/100x100/?woman'
                        ],
                        [
                            'name' => 'David Wilson',
                            'position' => 'CTO, Enterprise Solutions',
                            'rating' => 4,
                            'quote' => 'The technical expertise at TechCorp is impressive. They solved problems that other vendors couldn\'t even understand.',
                            'avatar' => 'https://source.unsplash.com/random/100x100/?person'
                        ]
                    ]
                ])
            ]);
        }
    }
    
    private function createPricingPageDesigns($site, $page, $layouts, $sectionType)
    {
        $order = 2;
        
        if ($layouts['hero']) {
            TplDesign::create([
                'site_id' => $site->id,
                'page_id' => $page->id,
                'layout_id' => $layouts['hero']->id,
                'layout_type_id' => $sectionType->id,
                'lang_code' => 'en',
                'sort_order' => $order++,
                'status' => true,
                'data' => json_encode([
                    'title' => 'Our Pricing Plans',
                    'subtitle' => 'Transparent pricing for businesses of all sizes',
                    'button_text' => 'Contact Us',
                    'button_url' => '/contact',
                    'secondary_button_text' => 'Learn More',
                    'secondary_button_url' => '/about'
                ])
            ]);
        }
        
        if ($layouts['pricing']) {
            TplDesign::create([
                'site_id' => $site->id,
                'page_id' => $page->id,
                'layout_id' => $layouts['pricing']->id,
                'layout_type_id' => $sectionType->id,
                'lang_code' => 'en',
                'sort_order' => $order++,
                'status' => true,
                'data' => json_encode([
                    'title' => 'Choose Your Plan',
                    'subtitle' => 'Select the plan that best fits your needs',
                    'plans' => [
                        [
                            'name' => 'Basic',
                            'description' => 'For small businesses',
                            'price' => '99',
                            'period' => 'month',
                            'features' => [
                                '5 Projects',
                                'Up to 10 Users',
                                '20GB Storage',
                                'Basic Support',
                                'Email Notifications'
                            ],
                            'button_text' => 'Choose Plan',
                            'button_url' => '/contact?plan=basic'
                        ],
                        [
                            'name' => 'Professional',
                            'description' => 'For growing teams',
                            'price' => '199',
                            'period' => 'month',
                            'featured' => true,
                            'features' => [
                                '15 Projects',
                                'Up to 50 Users',
                                '100GB Storage',
                                'Priority Support',
                                'Advanced Analytics',
                                'Email and SMS Notifications',
                                'API Access'
                            ],
                            'button_text' => 'Choose Plan',
                            'button_url' => '/contact?plan=professional'
                        ],
                        [
                            'name' => 'Enterprise',
                            'description' => 'For large organizations',
                            'price' => '399',
                            'period' => 'month',
                            'features' => [
                                'Unlimited Projects',
                                'Unlimited Users',
                                '500GB Storage',
                                '24/7 Priority Support',
                                'Custom Analytics',
                                'All Notification Methods',
                                'API Access',
                                'Custom Integration',
                                'Dedicated Account Manager'
                            ],
                            'button_text' => 'Choose Plan',
                            'button_url' => '/contact?plan=enterprise'
                        ]
                    ]
                ])
            ]);
        }
    }
    
    private function createBlogPageDesigns($site, $page, $layouts, $sectionType)
    {
        $order = 2;
        
        if ($layouts['hero']) {
            TplDesign::create([
                'site_id' => $site->id,
                'page_id' => $page->id,
                'layout_id' => $layouts['hero']->id,
                'layout_type_id' => $sectionType->id,
                'lang_code' => 'en',
                'sort_order' => $order++,
                'status' => true,
                'data' => json_encode([
                    'title' => 'Latest Insights',
                    'subtitle' => 'Stay updated with our latest news and articles',
                    'button_text' => 'Subscribe to Newsletter',
                    'button_url' => '/contact'
                ])
            ]);
        }
        
        if ($layouts['blog']) {
            TplDesign::create([
                'site_id' => $site->id,
                'page_id' => $page->id,
                'layout_id' => $layouts['blog']->id,
                'layout_type_id' => $sectionType->id,
                'lang_code' => 'en',
                'sort_order' => $order++,
                'status' => true,
                'data' => json_encode([
                    'title' => 'From Our Blog',
                    'subtitle' => 'Latest articles and insights from our team',
                    'posts' => [
                        [
                            'title' => 'How AI is Transforming Business Operations in 2023',
                            'excerpt' => 'Discover how artificial intelligence is revolutionizing business processes and creating new opportunities for growth and efficiency.',
                            'author' => 'Sarah Johnson',
                            'date' => date('M d, Y', strtotime('-2 days')),
                            'image' => 'https://source.unsplash.com/random/600x400/?ai,technology',
                            'url' => '/blog/ai-transforming-business-operations',
                            'category' => 'Technology',
                            'category_bg' => 'primary',
                            'views' => '1.2K',
                            'comments' => '24'
                        ],
                        [
                            'title' => '10 Cybersecurity Practices Every Business Should Implement',
                            'excerpt' => 'With cyber threats on the rise, learn the essential security measures your organization needs to protect sensitive data and maintain customer trust.',
                            'author' => 'Michael Chang',
                            'date' => date('M d, Y', strtotime('-5 days')),
                            'image' => 'https://source.unsplash.com/random/600x400/?cybersecurity,security',
                            'url' => '/blog/essential-cybersecurity-practices',
                            'category' => 'Security',
                            'category_bg' => 'danger',
                            'views' => '843',
                            'comments' => '17'
                        ],
                        [
                            'title' => 'The Future of Remote Work: Tools and Best Practices',
                            'excerpt' => 'Remote work is here to stay. Explore the latest tools, strategies, and best practices for maintaining productivity and team cohesion in a distributed workplace.',
                            'author' => 'Emily Rodriguez',
                            'date' => date('M d, Y', strtotime('-7 days')),
                            'image' => 'https://source.unsplash.com/random/600x400/?remote,work',
                            'url' => '/blog/future-of-remote-work',
                            'category' => 'Workplace',
                            'category_bg' => 'success',
                            'views' => '756',
                            'comments' => '12'
                        ]
                    ],
                    'action' => [
                        'text' => 'View All Articles',
                        'url' => '/blog',
                        'btn_style' => 'outline-primary',
                        'btn_size' => 'btn-lg'
                    ]
                ])
            ]);
        }
        
        if ($layouts['newsletter']) {
            TplDesign::create([
                'site_id' => $site->id,
                'page_id' => $page->id,
                'layout_id' => $layouts['newsletter']->id,
                'layout_type_id' => $sectionType->id,
                'lang_code' => 'en',
                'sort_order' => $order++,
                'status' => true,
                'data' => json_encode([
                    'title' => 'Subscribe to Our Blog',
                    'subtitle' => 'Get the latest articles and insights delivered straight to your inbox.',
                    'background' => 'dark'
                ])
            ]);
        }
    }
    
    private function createContactPageDesigns($site, $page, $layouts, $sectionType)
    {
        $order = 2;
        
        if ($layouts['hero']) {
            TplDesign::create([
                'site_id' => $site->id,
                'page_id' => $page->id,
                'layout_id' => $layouts['hero']->id,
                'layout_type_id' => $sectionType->id,
                'lang_code' => 'en',
                'sort_order' => $order++,
                'status' => true,
                'data' => json_encode([
                    'title' => 'Get in Touch',
                    'subtitle' => 'We\'d love to hear from you. Send us a message and we\'ll respond as soon as possible.',
                    'primary_button' => ['text' => 'Contact Support', 'url' => '#contact-form'],
                    'secondary_button' => ['text' => 'Visit FAQ', 'url' => '/faq']
                ])
            ]);
        }

        if ($layouts['contact']) {
            TplDesign::create([
                'site_id' => $site->id,
                'page_id' => $page->id,
                'layout_id' => $layouts['contact']->id,
                'layout_type_id' => $sectionType->id,
                'lang_code' => 'en',
                'sort_order' => $order++,
                'status' => true,
                'data' => json_encode([
                    'title' => 'Contact Us',
                    'subtitle' => 'Send us a message and we\'ll get back to you as soon as possible',
                    'contact_info' => [
                        'address' => '123 Business Street, Suite 100, New York, NY 10001',
                        'email' => 'contact@example.com',
                        'phone' => '+1 (234) 567-8900'
                    ],
                    'form' => [
                        'success_message' => 'Thank you for your message. We\'ll get back to you soon!',
                        'error_message' => 'Sorry, there was an error sending your message. Please try again.',
                        'submit_button' => 'Send Message'
                    ]
                ])
            ]);
        }

        if ($layouts['features']) {
            TplDesign::create([
                'site_id' => $site->id,
                'page_id' => $page->id,
                'layout_id' => $layouts['features']->id,
                'layout_type_id' => $sectionType->id,
                'lang_code' => 'en',
                'sort_order' => $order++,
                'status' => true,
                'data' => json_encode([
                    'title' => 'Why Contact Us',
                    'subtitle' => 'Benefits of getting in touch with our team',
                    'features' => [
                        [
                            'icon' => 'clock',
                            'title' => 'Fast Response',
                            'description' => 'We respond to all inquiries within 24 hours.'
                        ],
                        [
                            'icon' => 'users',
                            'title' => 'Dedicated Support',
                            'description' => 'Get personalized attention from our expert team.'
                        ],
                        [
                            'icon' => 'life-buoy',
                            'title' => 'Expert Help',
                            'description' => 'Access to skilled professionals who understand your needs.'
                        ]
                    ]
                ])
            ]);
        }

        if ($layouts['testimonials']) {
            TplDesign::create([
                'site_id' => $site->id,
                'page_id' => $page->id,
                'layout_id' => $layouts['testimonials']->id,
                'layout_type_id' => $sectionType->id,
                'lang_code' => 'en',
                'sort_order' => $order++,
                'status' => true,
                'data' => json_encode([
                    'title' => 'Customer Stories',
                    'subtitle' => 'See what our clients say about our support',
                    'testimonials' => [
                        [
                            'content' => 'Exceptional customer service! They responded quickly and resolved our issues efficiently.',
                            'author' => 'Emily Brown',
                            'position' => 'Product Manager',
                            'company' => 'Innovation Labs',
                            'image' => 'https://source.unsplash.com/random/100x100/?woman,professional'
                        ],
                        [
                            'content' => 'Their support team is amazing. Always helpful and proactive in solving problems.',
                            'author' => 'David Wilson',
                            'position' => 'IT Director',
                            'company' => 'Tech Solutions',
                            'image' => 'https://source.unsplash.com/random/100x100/?man,business'
                        ],
                        [
                            'content' => 'Great communication and follow-up. They really care about their clients.',
                            'author' => 'Lisa Chen',
                            'position' => 'Operations Manager',
                            'company' => 'Global Systems',
                            'image' => 'https://source.unsplash.com/random/100x100/?woman,business'
                        ]
                    ]
                ])
            ]);
        }

        if ($layouts['about']) {
            TplDesign::create([
                'site_id' => $site->id,
                'page_id' => $page->id,
                'layout_id' => $layouts['about']->id,
                'layout_type_id' => $sectionType->id,
                'lang_code' => 'en',
                'sort_order' => $order++,
                'status' => true,
                'data' => json_encode([
                    'title' => 'About Our Support',
                    'subtitle' => 'Learn more about our commitment to helping you succeed',
                    'content' => 'We pride ourselves on providing exceptional customer support. Our team is dedicated to understanding your unique needs and providing solutions that drive your business forward.',
                    'image' => 'https://source.unsplash.com/random/600x400/?team,office',
                    'stats' => [
                        ['number' => '24/7', 'label' => 'Support Available'],
                        ['number' => '2min', 'label' => 'Avg Response Time'],
                        ['number' => '99%', 'label' => 'Customer Satisfaction']
                    ]
                ])
            ]);
        }

        if ($layouts['cta']) {
            TplDesign::create([
                'site_id' => $site->id,
                'page_id' => $page->id,
                'layout_id' => $layouts['cta']->id,
                'layout_type_id' => $sectionType->id,
                'lang_code' => 'en',
                'sort_order' => $order++,
                'status' => true,
                'data' => json_encode([
                    'title' => 'Ready to Get Started?',
                    'subtitle' => 'Contact us today and let\'s discuss how we can help your business grow',
                    'primary_button' => ['text' => 'Schedule a Call', 'url' => '/contact?type=call'],
                    'secondary_button' => ['text' => 'Get Quote', 'url' => '/contact?type=quote'],
                    'background' => 'primary'
                ])
            ]);
        }
    }
}
