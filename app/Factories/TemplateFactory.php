<?php

namespace App\Factories;

use App\Models\User;
use App\Models\Site;
use App\Models\UserTemplate;

class TemplateFactory
{
    /**
     * Create a basic website template configuration
     */
    public static function createBasicWebsiteData(User $user, Site $site): array
    {
        return [
            'template' => [
                'name' => 'My Website Template',
                'description' => 'Professional website template with customizable sections',
                'config' => [
                    'variables' => [
                        'site_title' => $site->site_name,
                        'site_description' => 'Welcome to my professional website',
                        'contact_email' => $user->email,
                        'contact_phone' => $user->phone ?? '+1-555-0123',
                        'company_address' => '123 Business St, City, State 12345',
                        'social_facebook' => 'https://facebook.com/' . ($user->subdomain ?? 'company'),
                        'social_twitter' => 'https://twitter.com/' . ($user->subdomain ?? 'company'),
                        'social_instagram' => 'https://instagram.com/' . ($user->subdomain ?? 'company'),
                        'social_linkedin' => 'https://linkedin.com/company/' . ($user->subdomain ?? 'company')
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
                ]
            ],
            'sections' => self::getDefaultSections($user, $site),
            'pages' => self::getDefaultPages()
        ];
    }

    /**
     * Get default sections for a new site
     */
    private static function getDefaultSections(User $user, Site $site): array
    {
        $userName = $user->name;
        $siteName = $site->site_name;
        $userEmail = $user->email;
        $userPhone = $user->phone ?? '+1-555-0123';

        return [
            [
                'name' => 'Hero Section',
                'content' => [
                    'en' => [
                        'title' => "Welcome to {$siteName}",
                        'subtitle' => 'Professional services and solutions',
                        'description' => "Discover what we can do for you with our professional services and innovative solutions.",
                        'button_text' => 'Get Started',
                        'button_link' => '#about',
                        'background_image' => '/img/hero-bg.jpg'
                    ],
                    'ar' => [
                        'title' => "مرحباً بكم في {$siteName}",
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
                        'description' => "We are a team of professionals dedicated to providing excellent services and creating value for our clients. {$userName} leads our experienced team.",
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
                        'description' => "نحن فريق من المحترفين المكرسين لتقديم خدمات ممتازة وخلق قيمة لعملائنا. {$userName} يقود فريقنا ذو الخبرة.",
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
                                'title' => 'Consulting',
                                'description' => 'Strategic business and technology consulting',
                                'icon' => 'fas fa-chart-line',
                                'image' => '/img/service-consulting.jpg'
                            ],
                            [
                                'title' => 'Support',
                                'description' => '24/7 customer support and maintenance',
                                'icon' => 'fas fa-headset',
                                'image' => '/img/service-support.jpg'
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
                                'title' => 'الاستشارات',
                                'description' => 'استشارات أعمال وتكنولوجيا استراتيجية',
                                'icon' => 'fas fa-chart-line',
                                'image' => '/img/service-consulting.jpg'
                            ],
                            [
                                'title' => 'الدعم',
                                'description' => 'دعم العملاء والصيانة على مدار الساعة',
                                'icon' => 'fas fa-headset',
                                'image' => '/img/service-support.jpg'
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
                                'title' => 'Corporate Website',
                                'description' => 'Professional business presence',
                                'image' => '/img/project-2.jpg',
                                'category' => 'Web Design'
                            ],
                            [
                                'title' => 'Mobile Application',
                                'description' => 'User-friendly mobile solution',
                                'image' => '/img/project-3.jpg',
                                'category' => 'Mobile App'
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
                                'title' => 'موقع الشركة',
                                'description' => 'حضور تجاري احترافي',
                                'image' => '/img/project-2.jpg',
                                'category' => 'تصميم الويب'
                            ],
                            [
                                'title' => 'تطبيق الهاتف المحمول',
                                'description' => 'حل محمول سهل الاستخدام',
                                'image' => '/img/project-3.jpg',
                                'category' => 'تطبيق الهاتف'
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
                            'email' => $userEmail,
                            'phone' => $userPhone,
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
                            'email' => $userEmail,
                            'phone' => $userPhone,
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
     * Get default pages structure
     */
    private static function getDefaultPages(): array
    {
        return [
            [
                'name' => 'Home',
                'slug' => 'home',
                'link' => '/',
                'sections' => ['hero', 'about', 'services']
            ],
            [
                'name' => 'About',
                'slug' => 'about',
                'link' => '/about',
                'sections' => ['about', 'testimonials']
            ],
            [
                'name' => 'Services',
                'slug' => 'services',
                'link' => '/services',
                'sections' => ['services', 'portfolio']
            ],
            [
                'name' => 'Portfolio',
                'slug' => 'portfolio',
                'link' => '/portfolio',
                'sections' => ['portfolio', 'testimonials']
            ],
            [
                'name' => 'Contact',
                'slug' => 'contact',
                'link' => '/contact',
                'sections' => ['contact']
            ]
        ];
    }
}
