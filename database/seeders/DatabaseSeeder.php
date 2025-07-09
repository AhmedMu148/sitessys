<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TplLayoutType;
use App\Models\TplLayout;
use App\Models\Site;
use App\Models\TplPage;
use App\Models\TplDesign;
use App\Models\TplLang;
use App\Models\SiteConfig;
use App\Models\TplColorPalette;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create languages
        $english = TplLang::create([
            'name' => 'English',
            'code' => 'en',
            'dir' => 'ltr',
            'status' => true
        ]);
        
        $arabic = TplLang::create([
            'name' => 'Arabic',
            'code' => 'ar',
            'dir' => 'rtl',
            'status' => true
        ]);
        
        // Create layout types
        $navType = TplLayoutType::create([
            'name' => 'nav',
            'description' => 'Navigation section',
            'status' => true
        ]);
        
        $sectionType = TplLayoutType::create([
            'name' => 'section',
            'description' => 'Content section',
            'status' => true
        ]);
        
        $footerType = TplLayoutType::create([
            'name' => 'footer',
            'description' => 'Footer section',
            'status' => true
        ]);
        
        // Create default layouts
        $navLayout = TplLayout::create([
            'type_id' => $navType->id,
            'name' => 'default-nav',
            'html_template' => '<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
                <div class="container">
                    <a class="navbar-brand" href="/">{{ $data["brand"] ?? "My Site" }}</a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav ms-auto">
                            @foreach($data["menu_items"] ?? [] as $item)
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ $item["url"] }}">{{ $item["title"] }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </nav>',
            'status' => true
        ]);
        
        $heroLayout = TplLayout::create([
            'type_id' => $sectionType->id,
            'name' => 'hero',
            'html_template' => '<section class="hero-section bg-primary text-white py-5">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-lg-6">
                            <h1 class="display-4 fw-bold">{{ $data["title"] ?? "Welcome to Our Site" }}</h1>
                            <p class="lead">{{ $data["subtitle"] ?? "Your success starts here" }}</p>
                            <a href="{{ $data["button_url"] ?? "#" }}" class="btn btn-light btn-lg">{{ $data["button_text"] ?? "Get Started" }}</a>
                        </div>
                        <div class="col-lg-6">
                            <img src="{{ $data["image"] ?? "/placeholder.jpg" }}" alt="Hero Image" class="img-fluid">
                        </div>
                    </div>
                </div>
            </section>',
            'status' => true
        ]);
        
        $featuresLayout = TplLayout::create([
            'type_id' => $sectionType->id,
            'name' => 'features',
            'html_template' => '<section class="features-section py-5">
                <div class="container">
                    <div class="row">
                        <div class="col-12 text-center mb-5">
                            <h2>{{ $data["title"] ?? "Our Features" }}</h2>
                            <p>{{ $data["subtitle"] ?? "What makes us special" }}</p>
                        </div>
                    </div>
                    <div class="row">
                        @foreach($data["features"] ?? [] as $feature)
                            <div class="col-md-4 mb-4">
                                <div class="card h-100">
                                    <div class="card-body text-center">
                                        <i class="{{ $feature["icon"] ?? "fas fa-star" }} fa-3x text-primary mb-3"></i>
                                        <h5 class="card-title">{{ $feature["title"] }}</h5>
                                        <p class="card-text">{{ $feature["description"] }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>',
            'status' => true
        ]);
        
        $contactLayout = TplLayout::create([
            'type_id' => $sectionType->id,
            'name' => 'contact',
            'html_template' => '<section class="contact-section py-5 bg-light">
                <div class="container">
                    <div class="row">
                        <div class="col-12 text-center mb-5">
                            <h2>{{ $data["title"] ?? "Contact Us" }}</h2>
                            <p>{{ $data["subtitle"] ?? "Get in touch with us" }}</p>
                        </div>
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
                                    <button type="submit" class="btn btn-primary">{{ $data["button_text"] ?? "Send Message" }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </section>',
            'status' => true
        ]);
        
        $footerLayout = TplLayout::create([
            'type_id' => $footerType->id,
            'name' => 'default-footer',
            'html_template' => '<footer class="bg-dark text-white py-4">
                <div class="container">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>{{ $data["brand"] ?? "My Site" }}</h5>
                            <p>{{ $data["description"] ?? "Building amazing experiences" }}</p>
                        </div>
                        <div class="col-md-6">
                            <h5>Quick Links</h5>
                            <ul class="list-unstyled">
                                @foreach($data["links"] ?? [] as $link)
                                    <li><a href="{{ $link["url"] }}" class="text-white-50">{{ $link["title"] }}</a></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-12 text-center">
                            <p>&copy; {{ date("Y") }} {{ $data["brand"] ?? "My Site" }}. All rights reserved.</p>
                        </div>
                    </div>
                </div>
            </footer>',
            'status' => true
        ]);
        
        // Create default site
        $site = Site::create([
            'name' => 'My Site',
            'domain' => 'localhost',
            'status' => true
        ]);
        
        // Create site configuration
        SiteConfig::create([
            'site_id' => $site->id,
            'lang_id' => $english->id,
            'direction' => 'ltr',
            'is_default' => true
        ]);
        
        // Create default color palette
        TplColorPalette::create([
            'site_id' => $site->id,
            'name' => 'Primary',
            'color_code' => '#0d6efd',
            'is_primary' => true,
            'status' => true
        ]);
        
        TplColorPalette::create([
            'site_id' => $site->id,
            'name' => 'Secondary',
            'color_code' => '#6c757d',
            'is_primary' => false,
            'status' => true
        ]);
        
        // Create home page
        $homePage = TplPage::create([
            'site_id' => $site->id,
            'name' => 'Home',
            'slug' => 'home',
            'sort_order' => 1,
            'status' => true
        ]);
        
        // Create about page
        $aboutPage = TplPage::create([
            'site_id' => $site->id,
            'name' => 'About',
            'slug' => 'about',
            'sort_order' => 2,
            'status' => true
        ]);
        
        // Create contact page
        $contactPage = TplPage::create([
            'site_id' => $site->id,
            'name' => 'Contact',
            'slug' => 'contact',
            'sort_order' => 3,
            'status' => true
        ]);
        
        // Create designs for home page
        TplDesign::create([
            'site_id' => $site->id,
            'page_id' => $homePage->id,
            'layout_id' => $navLayout->id,
            'layout_type_id' => $navType->id,
            'lang_code' => 'en',
            'sort_order' => 1,
            'data' => [
                'brand' => 'TechCorp',
                'menu_items' => [
                    ['title' => 'Home', 'url' => '/'],
                    ['title' => 'About', 'url' => '/about'],
                    ['title' => 'Services', 'url' => '/services'],
                    ['title' => 'Contact', 'url' => '/contact']
                ],
                'cta_text' => 'Get Started',
                'cta_url' => '/contact'
            ],
            'status' => true
        ]);
        
        TplDesign::create([
            'site_id' => $site->id,
            'page_id' => $homePage->id,
            'layout_id' => $heroLayout->id,
            'layout_type_id' => $sectionType->id,
            'lang_code' => 'en',
            'sort_order' => 2,
            'data' => [
                'title' => 'Transform Your Business with Innovation',
                'subtitle' => 'We provide cutting-edge solutions that help companies grow, scale, and succeed in the digital age.',
                'button_text' => 'Start Your Journey',
                'button_url' => '/contact',
                'secondary_button_text' => 'Learn More',
                'secondary_button_url' => '/about',
                'image' => 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNTAwIiBoZWlnaHQ9IjMwMCIgdmlld0JveD0iMCAwIDUwMCAzMDAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxyZWN0IHdpZHRoPSI1MDAiIGhlaWdodD0iMzAwIiBmaWxsPSIjRjhGOUZBIi8+CjxjaXJjbGUgY3g9IjI1MCIgY3k9IjE1MCIgcj0iNjAiIGZpbGw9IiMwZDZlZmQiLz4KPHRLEHT4dGV4dCB4PSI1MCUiIHk9IjcwJSIgZG9taW5hbnQtYmFzZWxpbmU9Im1pZGRsZSIgdGV4dC1hbmNob3I9Im1pZGRsZSIgZmlsbD0iIzZDNzU3RCIgZm9udC1mYW1pbHk9IkFyaWFsLCBzYW5zLXNlcmlmIiBmb250LXNpemU9IjE4Ij5JTk5PVkFUSU9OPC90ZXh0Pgo8L3N2Zz4='
            ],
            'status' => true
        ]);
        
        TplDesign::create([
            'site_id' => $site->id,
            'page_id' => $homePage->id,
            'layout_id' => $featuresLayout->id,
            'layout_type_id' => $sectionType->id,
            'lang_code' => 'en',
            'sort_order' => 3,
            'data' => [
                'title' => 'Why Choose TechCorp?',
                'subtitle' => 'We deliver exceptional results through innovation, expertise, and dedication.',
                'features' => [
                    [
                        'title' => 'Expert Team',
                        'description' => 'Our skilled professionals bring years of experience and cutting-edge knowledge to every project.',
                        'icon' => 'users'
                    ],
                    [
                        'title' => 'Innovation First',
                        'description' => 'We stay ahead of the curve with the latest technologies and industry best practices.',
                        'icon' => 'zap'
                    ],
                    [
                        'title' => 'Proven Results',
                        'description' => 'Our track record speaks for itself - we deliver measurable results for our clients.',
                        'icon' => 'trending-up'
                    ],
                    [
                        'title' => 'Customer Focus',
                        'description' => 'Your success is our priority. We work closely with you to achieve your goals.',
                        'icon' => 'heart'
                    ],
                    [
                        'title' => 'Scalable Solutions',
                        'description' => 'Our solutions grow with your business, ensuring long-term success and adaptability.',
                        'icon' => 'layers'
                    ],
                    [
                        'title' => '24/7 Support',
                        'description' => 'Round-the-clock support ensures your business never misses a beat.',
                        'icon' => 'headphones'
                    ]
                ]
            ],
            'status' => true
        ]);
        
        TplDesign::create([
            'site_id' => $site->id,
            'page_id' => $homePage->id,
            'layout_id' => $footerLayout->id,
            'layout_type_id' => $footerType->id,
            'lang_code' => 'en',
            'sort_order' => 4,
            'data' => [
                'brand' => 'TechCorp',
                'description' => 'Leading the digital transformation with innovative solutions and exceptional service.',
                'email' => 'info@techcorp.com',
                'phone' => '+1 (555) 123-4567',
                'address' => '123 Innovation Drive, Tech City, TC 12345',
                'social_links' => [
                    ['name' => 'Facebook', 'url' => 'https://facebook.com/techcorp', 'icon' => 'facebook'],
                    ['name' => 'Twitter', 'url' => 'https://twitter.com/techcorp', 'icon' => 'twitter'],
                    ['name' => 'LinkedIn', 'url' => 'https://linkedin.com/company/techcorp', 'icon' => 'linkedin'],
                    ['name' => 'Instagram', 'url' => 'https://instagram.com/techcorp', 'icon' => 'instagram']
                ],
                'quick_links' => [
                    ['title' => 'Home', 'url' => '/'],
                    ['title' => 'About', 'url' => '/about'],
                    ['title' => 'Services', 'url' => '/services'],
                    ['title' => 'Contact', 'url' => '/contact']
                ],
                'legal_links' => [
                    ['title' => 'Privacy Policy', 'url' => '/privacy'],
                    ['title' => 'Terms of Service', 'url' => '/terms'],
                    ['title' => 'Cookie Policy', 'url' => '/cookies']
                ]
            ],
            'status' => true
        ]);
        
        // Create designs for about page
        TplDesign::create([
            'site_id' => $site->id,
            'page_id' => $aboutPage->id,
            'layout_id' => $navLayout->id,
            'layout_type_id' => $navType->id,
            'lang_code' => 'en',
            'sort_order' => 1,
            'data' => [
                'brand' => 'My Site',
                'menu_items' => [
                    ['title' => 'Home', 'url' => '/'],
                    ['title' => 'About', 'url' => '/about'],
                    ['title' => 'Contact', 'url' => '/contact']
                ]
            ],
            'status' => true
        ]);
        
        TplDesign::create([
            'site_id' => $site->id,
            'page_id' => $aboutPage->id,
            'layout_id' => $heroLayout->id,
            'layout_type_id' => $sectionType->id,
            'lang_code' => 'en',
            'sort_order' => 2,
            'data' => [
                'title' => 'About Us',
                'subtitle' => 'Learn more about our story and mission',
                'button_text' => 'Contact Us',
                'button_url' => '/contact',
                'image' => '/placeholder.jpg'
            ],
            'status' => true
        ]);
        
        TplDesign::create([
            'site_id' => $site->id,
            'page_id' => $aboutPage->id,
            'layout_id' => $footerLayout->id,
            'layout_type_id' => $footerType->id,
            'lang_code' => 'en',
            'sort_order' => 3,
            'data' => [
                'brand' => 'My Site',
                'description' => 'Building amazing experiences for our users',
                'links' => [
                    ['title' => 'Privacy Policy', 'url' => '/privacy'],
                    ['title' => 'Terms of Service', 'url' => '/terms'],
                    ['title' => 'Support', 'url' => '/support']
                ]
            ],
            'status' => true
        ]);
        
        // Create designs for contact page
        TplDesign::create([
            'site_id' => $site->id,
            'page_id' => $contactPage->id,
            'layout_id' => $navLayout->id,
            'layout_type_id' => $navType->id,
            'lang_code' => 'en',
            'sort_order' => 1,
            'data' => [
                'brand' => 'My Site',
                'menu_items' => [
                    ['title' => 'Home', 'url' => '/'],
                    ['title' => 'About', 'url' => '/about'],
                    ['title' => 'Contact', 'url' => '/contact']
                ]
            ],
            'status' => true
        ]);
        
        TplDesign::create([
            'site_id' => $site->id,
            'page_id' => $contactPage->id,
            'layout_id' => $contactLayout->id,
            'layout_type_id' => $sectionType->id,
            'lang_code' => 'en',
            'sort_order' => 2,
            'data' => [
                'title' => 'Contact Us',
                'subtitle' => 'Get in touch with our team',
                'button_text' => 'Send Message'
            ],
            'status' => true
        ]);
        
        TplDesign::create([
            'site_id' => $site->id,
            'page_id' => $contactPage->id,
            'layout_id' => $footerLayout->id,
            'layout_type_id' => $footerType->id,
            'lang_code' => 'en',
            'sort_order' => 3,
            'data' => [
                'brand' => 'My Site',
                'description' => 'Building amazing experiences for our users',
                'links' => [
                    ['title' => 'Privacy Policy', 'url' => '/privacy'],
                    ['title' => 'Terms of Service', 'url' => '/terms'],
                    ['title' => 'Support', 'url' => '/support']
                ]
            ],
            'status' => true
        ]);
    }
}
