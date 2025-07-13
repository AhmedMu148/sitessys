<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Site;
use App\Models\SiteConfig;
use App\Models\SiteSocial;
use App\Models\SiteContact;
use App\Models\SiteSeoInt;
use App\Models\TplLang;
use App\Models\TplLayoutType;
use App\Models\TplLayout;
use App\Models\TplSection;
use App\Models\TplPage;
use App\Models\TplSite;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create languages
        $english = TplLang::firstOrCreate(
            ['code' => 'en'],
            [
                'name' => 'English',
                'dir' => 'ltr',
                'status' => true
            ]
        );

        $arabic = TplLang::firstOrCreate(
            ['code' => 'ar'],
            [
                'name' => 'Arabic',
                'dir' => 'rtl',
                'status' => true
            ]
        );

        // Create layout types
        $navType = TplLayoutType::firstOrCreate(
            ['name' => 'nav'],
            [
                'description' => 'Navigation section',
                'status' => true
            ]
        );

        $sectionType = TplLayoutType::firstOrCreate(
            ['name' => 'section'],
            [
                'description' => 'Content section',
                'status' => true
            ]
        );

        $footerType = TplLayoutType::firstOrCreate(
            ['name' => 'footer'],
            [
                'description' => 'Footer section',
                'status' => true
            ]
        );

        // Create layout templates (8 total: 1 nav + 6 section + 1 footer)
        $navLayout = TplLayout::create([
            'type_id' => $navType->id,
            'data' => '<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
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

        // Create 6 section layouts
        $layouts = [
            'hero' => '<section class="hero-section bg-primary text-white py-5">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-lg-6">
                            <h1 class="display-4 fw-bold">{{ $data["title"] ?? "Welcome" }}</h1>
                            <p class="lead">{{ $data["subtitle"] ?? "Subtitle" }}</p>
                            <a href="{{ $data["button_url"] ?? "#" }}" class="btn btn-light btn-lg">{{ $data["button_text"] ?? "Get Started" }}</a>
                        </div>
                        <div class="col-lg-6">
                            <img src="{{ $data["image"] ?? "/placeholder.jpg" }}" alt="Hero" class="img-fluid">
                        </div>
                    </div>
                </div>
            </section>',
            'features' => '<section class="features-section py-5">
                <div class="container">
                    <div class="row">
                        <div class="col-12 text-center mb-5">
                            <h2>{{ $data["title"] ?? "Features" }}</h2>
                            <p>{{ $data["subtitle"] ?? "What we offer" }}</p>
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
            'about' => '<section class="about-section py-5">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-lg-6">
                            <img src="{{ $data["image"] ?? "/placeholder.jpg" }}" alt="About" class="img-fluid">
                        </div>
                        <div class="col-lg-6">
                            <h2>{{ $data["title"] ?? "About Us" }}</h2>
                            <p>{{ $data["content"] ?? "About content here" }}</p>
                        </div>
                    </div>
                </div>
            </section>',
            'services' => '<section class="services-section py-5 bg-light">
                <div class="container">
                    <div class="row">
                        <div class="col-12 text-center mb-5">
                            <h2>{{ $data["title"] ?? "Our Services" }}</h2>
                        </div>
                    </div>
                    <div class="row">
                        @foreach($data["services"] ?? [] as $service)
                            <div class="col-md-3 mb-4">
                                <div class="service-item text-center">
                                    <i class="{{ $service["icon"] ?? "fas fa-cog" }} fa-3x text-primary mb-3"></i>
                                    <h5>{{ $service["title"] }}</h5>
                                    <p>{{ $service["description"] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>',
            'contact' => '<section class="contact-section py-5">
                <div class="container">
                    <div class="row">
                        <div class="col-12 text-center mb-5">
                            <h2>{{ $data["title"] ?? "Contact Us" }}</h2>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-8 mx-auto">
                            <form>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <input type="text" class="form-control" placeholder="Name" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <input type="email" class="form-control" placeholder="Email" required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <textarea class="form-control" rows="5" placeholder="Message" required></textarea>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary">Send Message</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </section>',
            'testimonials' => '<section class="testimonials-section py-5 bg-light">
                <div class="container">
                    <div class="row">
                        <div class="col-12 text-center mb-5">
                            <h2>{{ $data["title"] ?? "Testimonials" }}</h2>
                        </div>
                    </div>
                    <div class="row">
                        @foreach($data["testimonials"] ?? [] as $testimonial)
                            <div class="col-md-4 mb-4">
                                <div class="testimonial-item text-center">
                                    <p>"{{ $testimonial["content"] }}"</p>
                                    <h6>- {{ $testimonial["author"] }}</h6>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>'
        ];

        $sectionLayouts = [];
        foreach ($layouts as $name => $html) {
            $sectionLayouts[] = TplLayout::create([
                'type_id' => $sectionType->id,
                'data' => $html,
                'status' => true
            ]);
        }

        $footerLayout = TplLayout::create([
            'type_id' => $footerType->id,
            'data' => '<footer class="bg-dark text-white py-4">
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
    }

    /**
     * Create a new site setup with all required data
     */
    public function createSiteSetup($userName = 'Admin User', $userEmail = 'admin@example.com', $siteName = 'My Site', $domain = 'localhost')
    {
        // 1. Create user
        $user = User::create([
            'name' => $userName,
            'email' => $userEmail,
            'password' => bcrypt('password'),
            'role' => 'admin'
        ]);

        // 2. Create site
        $site = Site::create([
            'user_id' => $user->id,
            'site_name' => $siteName,
            'domain' => $domain,
            'status' => true
        ]);

        // 3. Create site config
        SiteConfig::create([
            'site_id' => $site->id,
            'data' => [
                'logo' => '/logo.png',
                'favicon' => '/favicon.ico',
                'title' => $siteName,
                'keyword' => 'website, business, services',
                'description' => 'Welcome to our amazing website'
            ],
            'lang_id' => '1,2' // English and Arabic
        ]);

        // Create social data
        SiteSocial::create([
            'site_id' => $site->id,
            'data' => [
                'facebook' => 'https://facebook.com',
                'twitter' => 'https://twitter.com',
                'instagram' => 'https://instagram.com',
                'linkedin' => 'https://linkedin.com'
            ]
        ]);

        // Create contact data
        SiteContact::create([
            'site_id' => $site->id,
            'data' => [
                'email' => 'contact@example.com',
                'phone' => '+1234567890',
                'address' => '123 Main St, City, Country'
            ]
        ]);

        // Create SEO integration
        SiteSeoInt::create([
            'site_id' => $site->id,
            'int_name' => 'ses',
            'data' => [
                'username' => 'username',
                'api_key' => 'api_key_here',
                'email' => 'seo@example.com',
                'balance' => '100',
                'api_link' => 'https://api.ses.com'
            ],
            'status' => true
        ]);

        // 4. Create 60 sections (30 for each language: 6 pages × 5 sections)
        $pages = ['home', 'about', 'services', 'portfolio', 'blog', 'contact'];
        $sectionData = [
            'en' => [
                'title' => 'Section Title',
                'content' => 'Section content in English',
                'button_text' => 'Learn More'
            ],
            'ar' => [
                'title' => 'عنوان القسم',
                'content' => 'محتوى القسم باللغة العربية',
                'button_text' => 'اعرف المزيد'
            ]
        ];

        for ($i = 1; $i <= 60; $i++) {
            TplSection::create([
                'site_id' => $site->id,
                'data' => $sectionData
            ]);
        }

        // 5. Create 6 pages
        $pageLinks = [
            'home' => '/',
            'about' => '/about',
            'services' => '/services',
            'portfolio' => '/portfolio',
            'blog' => '/blog',
            'contact' => '/contact'
        ];

        $createdPages = [];
        foreach ($pageLinks as $name => $link) {
            $sectionIds = [];
            for ($i = 0; $i < 5; $i++) {
                $sectionIds[] = rand(1, 60);
            }
            
            $createdPages[] = TplPage::create([
                'site_id' => $site->id,
                'name' => ucfirst($name),
                'link' => $link,
                'section_id' => implode(',', $sectionIds)
            ]);
        }

        // 6. Create tpl_site configuration
        TplSite::create([
            'site_id' => $site->id,
            'nav' => 1, // First layout (nav)
            'pages' => array_slice(array_column($createdPages, 'id'), 0, 4), // First 4 pages
            'footer' => 8 // Last layout (footer)
        ]);

        return $site;
    }
}
