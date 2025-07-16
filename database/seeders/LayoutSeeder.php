<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TplLayoutType;
use App\Models\TplLayout;

class LayoutSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Creating layout types...');
        
        // Create layout types with firstOrCreate to avoid duplicates
        $types = [
            'nav' => TplLayoutType::firstOrCreate(
                ['name' => 'nav'],
                [
                    'description' => 'Navigation section',
                    'status' => true
                ]
            ),
            'section' => TplLayoutType::firstOrCreate(
                ['name' => 'section'],
                [
                    'description' => 'Content section',
                    'status' => true
                ]
            ),
            'footer' => TplLayoutType::firstOrCreate(
                ['name' => 'footer'],
                [
                    'description' => 'Footer section',
                    'status' => true
                ]
            )
        ];

        $this->command->info('Creating default layouts...');

        // Create modern navigation layout
        TplLayout::firstOrCreate(
            [
                'type_id' => $types['nav']->id,
                'name' => 'Modern Navigation',
                'user_id' => null,
                'site_id' => null
            ],
            [
                'data' => $this->getNavigationContent(),
                'status' => true
            ]
        );

        // Create classic navigation layout
        TplLayout::firstOrCreate(
            [
                'type_id' => $types['nav']->id,
                'name' => 'Classic Navigation',
                'user_id' => null,
                'site_id' => null
            ],
            [
                'data' => $this->getClassicNavContent(),
                'status' => true
            ]
        );

        // Create section layouts
        $sections = [
            'Hero Section' => $this->getHeroContent(),
            'Features Grid' => $this->getFeaturesContent(),
            'About Section' => $this->getAboutContent(),
            'Services Grid' => $this->getServicesContent(),
            'Team Members' => $this->getTeamContent(),
            'Testimonials' => $this->getTestimonialsContent(),
            'Blog Grid' => $this->getBlogGridContent(),
            'Contact Form' => $this->getContactContent(),
            'Newsletter Form' => $this->getNewsletterContent(),
            'Quote Form' => $this->getQuoteContent(),
            'CTA Section' => $this->getCtaContent(),
            'Pricing Plans' => $this->getPricingContent()
        ];

        foreach ($sections as $name => $html_template) {
            TplLayout::firstOrCreate(
                [
                    'type_id' => $types['section']->id,
                    'name' => $name,
                    'user_id' => null,
                    'site_id' => null
                ],
                [
                    'data' => $html_template,
                    'status' => true
                ]
            );
        }

        // Create modern footer layout
        TplLayout::firstOrCreate(
            [
                'type_id' => $types['footer']->id,
                'name' => 'Modern Footer',
                'user_id' => null,
                'site_id' => null
            ],
            [
                'data' => $this->getFooterContent(),
                'status' => true
            ]
        );

        // Create simple footer layout
        TplLayout::firstOrCreate(
            [
                'type_id' => $types['footer']->id,
                'name' => 'Simple Footer',
                'user_id' => null,
                'site_id' => null
            ],
            [
                'data' => $this->getSimpleFooterContent(),
                'status' => true
            ]
        );

        $this->command->info('✅ Layout types and layouts created successfully!');
    }

    private function getNavigationContent(): string
    {
        return '<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm fixed-top" data-bs-theme="light">
    <div class="container">
        <a class="navbar-brand fw-bold" href="/">
            <img src="{{ asset("img/logo.svg") }}" alt="{{ $site->name }}" height="32">
            {{ $site->name }}
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain" aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarMain">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link {{ Request::is("/") ? "active" : "" }}" href="/">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::is("about*") ? "active" : "" }}" href="/about">About</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::is("services*") ? "active" : "" }}" href="/services">Services</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::is("portfolio*") ? "active" : "" }}" href="/portfolio">Portfolio</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::is("blog*") ? "active" : "" }}" href="/blog">Blog</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::is("contact*") ? "active" : "" }}" href="/contact">Contact</a>
                </li>
                <li class="nav-item ms-lg-3">
                    <a class="btn btn-primary" href="/contact">Get Started</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<div class="mb-5 pb-3"><!-- Spacer for fixed navbar --></div>';
    }

    private function getHeroContent(): string
    {
        return '<section class="hero-section bg-light py-5">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4">Transform Your Business with Innovation</h1>
                <p class="lead mb-4 text-muted">Join thousands of satisfied customers who are already using our services to grow their business and stay ahead of the competition.</p>
                <div class="d-flex gap-3">
                    <a href="/contact" class="btn btn-primary btn-lg px-4">Get Started</a>
                    <a href="/services" class="btn btn-outline-primary btn-lg px-4">Our Services</a>
                </div>
                <div class="mt-5 d-flex gap-4">
                    <div>
                        <h3 class="fw-bold text-primary mb-1">500+</h3>
                        <p class="text-muted mb-0">Happy Clients</p>
                    </div>
                    <div>
                        <h3 class="fw-bold text-primary mb-1">150+</h3>
                        <p class="text-muted mb-0">Projects Done</p>
                    </div>
                    <div>
                        <h3 class="fw-bold text-primary mb-1">15+</h3>
                        <p class="text-muted mb-0">Years Experience</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <img src="{{ asset("img/hero-illustration.svg") }}" alt="Hero Illustration" class="img-fluid">
            </div>
        </div>
    </div>
</section>';
    }

    private function getFeaturesContent(): string
    {
        return '<section class="features-section py-5">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col-lg-6 mx-auto">
                <h2 class="fw-bold mb-3">Why Choose {{ $site->name }}?</h2>
                <p class="text-muted mb-0">We combine innovative technology with exceptional service to deliver solutions that drive your business forward.</p>
            </div>
        </div>
        <div class="row g-4">
            <div class="col-lg-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body p-4 text-center">
                        <div class="icon-box bg-primary bg-opacity-10 text-primary rounded-3 p-3 d-inline-block mb-3">
                            <i data-feather="trending-up" width="24" height="24"></i>
                        </div>
                        <h4 class="mb-3">Proven Results</h4>
                        <p class="text-muted mb-0">Our solutions have helped businesses increase efficiency and revenue through digital transformation.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body p-4 text-center">
                        <div class="icon-box bg-success bg-opacity-10 text-success rounded-3 p-3 d-inline-block mb-3">
                            <i data-feather="users" width="24" height="24"></i>
                        </div>
                        <h4 class="mb-3">Expert Team</h4>
                        <p class="text-muted mb-0">Our certified professionals bring years of experience in delivering cutting-edge solutions.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body p-4 text-center">
                        <div class="icon-box bg-info bg-opacity-10 text-info rounded-3 p-3 d-inline-block mb-3">
                            <i data-feather="clock" width="24" height="24"></i>
                        </div>
                        <h4 class="mb-3">24/7 Support</h4>
                        <p class="text-muted mb-0">Round-the-clock technical support to ensure your systems run smoothly at all times.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>';
    }

    private function getFooterContent(): string
    {
        return '<footer class="footer bg-dark text-light py-5 mt-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4">
                <h5 class="text-white mb-4">About {{ $site->name }}</h5>
                <p class="text-muted mb-4">Leading the digital transformation with innovative solutions and exceptional service. We help businesses of all sizes harness the power of technology to grow and succeed.</p>
                <div class="social-links">
                    @php $socialLinks = json_decode($siteConfigs->where("key", "social_links")->first()->value ?? "{}"); @endphp
                    <a href="{{ $socialLinks->twitter ?? "#" }}" class="text-white me-3"><i data-feather="twitter"></i></a>
                    <a href="{{ $socialLinks->facebook ?? "#" }}" class="text-white me-3"><i data-feather="facebook"></i></a>
                    <a href="{{ $socialLinks->linkedin ?? "#" }}" class="text-white me-3"><i data-feather="linkedin"></i></a>
                    <a href="{{ $socialLinks->github ?? "#" }}" class="text-white"><i data-feather="github"></i></a>
                </div>
            </div>
            <div class="col-lg-2 col-md-6">
                <h5 class="text-white mb-4">Quick Links</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="/" class="text-muted text-decoration-none">Home</a></li>
                    <li class="mb-2"><a href="/about" class="text-muted text-decoration-none">About</a></li>
                    <li class="mb-2"><a href="/services" class="text-muted text-decoration-none">Services</a></li>
                    <li class="mb-2"><a href="/portfolio" class="text-muted text-decoration-none">Portfolio</a></li>
                    <li class="mb-2"><a href="/blog" class="text-muted text-decoration-none">Blog</a></li>
                    <li class="mb-2"><a href="/contact" class="text-muted text-decoration-none">Contact</a></li>
                </ul>
            </div>
            <div class="col-lg-2 col-md-6">
                <h5 class="text-white mb-4">Resources</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="/privacy-policy" class="text-muted text-decoration-none">Privacy Policy</a></li>
                    <li class="mb-2"><a href="/terms-of-service" class="text-muted text-decoration-none">Terms of Service</a></li>
                    <li class="mb-2"><a href="/cookie-policy" class="text-muted text-decoration-none">Cookie Policy</a></li>
                    <li class="mb-2"><a href="/faqs" class="text-muted text-decoration-none">FAQs</a></li>
                    <li class="mb-2"><a href="/documentation" class="text-muted text-decoration-none">Documentation</a></li>
                </ul>
            </div>
            <div class="col-lg-4">
                <h5 class="text-white mb-4">Contact Us</h5>
                @php $contactInfo = json_decode($siteConfigs->where("key", "contact_info")->first()->value ?? "{}"); @endphp
                <ul class="list-unstyled">
                    <li class="d-flex mb-3">
                        <i data-feather="mail" class="text-primary me-2"></i>
                        <a href="mailto:{{ $contactInfo->email ?? "info@techcorp.com" }}" class="text-muted text-decoration-none">{{ $contactInfo->email ?? "info@techcorp.com" }}</a>
                    </li>
                    <li class="d-flex mb-3">
                        <i data-feather="phone" class="text-primary me-2"></i>
                        <a href="tel:{{ $contactInfo->phone ?? "+1 (555) 123-4567" }}" class="text-muted text-decoration-none">{{ $contactInfo->phone ?? "+1 (555) 123-4567" }}</a>
                    </li>
                    <li class="d-flex">
                        <i data-feather="map-pin" class="text-primary me-2"></i>
                        <span class="text-muted">{{ $contactInfo->address ?? "123 Innovation Drive, Tech City, TC 12345" }}</span>
                    </li>
                </ul>
            </div>
        </div>
        <hr class="my-5 border-light">
        <div class="row">
            <div class="col-md-6 text-center text-md-start">
                <p class="mb-0 text-muted">&copy; {{ date("Y") }} {{ $site->name }}. All rights reserved.</p>
            </div>
            <div class="col-md-6 text-center text-md-end">
                <p class="mb-0 text-muted">Designed with <i data-feather="heart" class="text-danger mx-1" style="width: 16px; height: 16px;"></i> by {{ $site->name }}</p>
            </div>
        </div>
    </div>
</footer>';
    }

    private function getAboutContent(): string
    {
        return '<section class="about-section py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h2 class="mb-4">About Us</h2>
                <p class="lead mb-4">We are a team of passionate professionals committed to delivering innovative solutions that drive success for our clients.</p>
                <p class="mb-4">With years of experience in the industry, we specialize in providing top-notch services that cater to the unique needs of each business.</p>
                <div class="d-flex gap-3">
                    <a href="/about#mission" class="btn btn-primary btn-lg px-4">Our Mission</a>
                    <a href="/about#team" class="btn btn-outline-primary btn-lg px-4">Meet the Team</a>
                </div>
            </div>
            <div class="col-lg-6">
                <img src="{{ asset("img/about-illustration.svg") }}" alt="About Illustration" class="img-fluid">
            </div>
        </div>
    </div>
</section>';
    }

    private function getServicesContent(): string
    {
        return '<section class="services-section py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2>Our Services</h2>
                <p class="lead">What we can do for you</p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="service-icon bg-primary text-white rounded p-2 me-3">
                                <i data-feather="code"></i>
                            </div>
                            <h5 class="card-title mb-0">Web Development</h5>
                        </div>
                        <p class="card-text">Building responsive and scalable websites that deliver seamless user experiences.</p>
                        <a href="/services/web-development" class="btn btn-sm btn-outline-primary">Learn More</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="service-icon bg-success text-white rounded p-2 me-3">
                                <i data-feather="smartphone"></i>
                            </div>
                            <h5 class="card-title mb-0">Mobile Apps</h5>
                        </div>
                        <p class="card-text">Creating intuitive and high-performance mobile applications for iOS and Android.</p>
                        <a href="/services/mobile-apps" class="btn btn-sm btn-outline-primary">Learn More</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="service-icon bg-info text-white rounded p-2 me-3">
                                <i data-feather="cloud"></i>
                            </div>
                            <h5 class="card-title mb-0">Cloud Solutions</h5>
                        </div>
                        <p class="card-text">Providing secure and scalable cloud computing solutions to enhance business agility.</p>
                        <a href="/services/cloud-solutions" class="btn btn-sm btn-outline-primary">Learn More</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="service-icon bg-warning text-white rounded p-2 me-3">
                                <i data-feather="shield"></i>
                            </div>
                            <h5 class="card-title mb-0">Cybersecurity</h5>
                        </div>
                        <p class="card-text">Protecting your business from cyber threats with robust security measures and protocols.</p>
                        <a href="/services/cybersecurity" class="btn btn-sm btn-outline-primary">Learn More</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="service-icon bg-danger text-white rounded p-2 me-3">
                                <i data-feather="bar-chart-2"></i>
                            </div>
                            <h5 class="card-title mb-0">Digital Marketing</h5>
                        </div>
                        <p class="card-text">Boosting your online presence and engagement through effective digital marketing strategies.</p>
                        <a href="/services/digital-marketing" class="btn btn-sm btn-outline-primary">Learn More</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="service-icon bg-secondary text-white rounded p-2 me-3">
                                <i data-feather="layers"></i>
                            </div>
                            <h5 class="card-title mb-0">IT Consulting</h5>
                        </div>
                        <p class="card-text">Offering expert advice and solutions to optimize your IT infrastructure and operations.</p>
                        <a href="/services/it-consulting" class="btn btn-sm btn-outline-primary">Learn More</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>';
    }

    private function getTeamContent(): string
    {
        return '<section class="team-section py-5">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col-lg-6 mx-auto">
                <h2 class="fw-bold mb-3">Meet Our Team</h2>
                <p class="text-muted mb-0">A dedicated team of professionals committed to delivering the best results for our clients.</p>
            </div>
        </div>
        <div class="row g-4">
            <div class="col-lg-3 col-md-6">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center">
                        <div class="team-member-avatar mb-3">
                            <img src="{{ asset("img/team/member1.jpg") }}" alt="Member 1" class="rounded-circle" style="width: 100px; height: 100px; object-fit: cover;">
                        </div>
                        <h5 class="fw-bold mb-0">John Doe</h5>
                        <p class="text-muted">CEO & Founder</p>
                        <div class="social-links mt-3">
                            <a href="#" class="text-muted me-2"><i data-feather="twitter"></i></a>
                            <a href="#" class="text-muted me-2"><i data-feather="linkedin"></i></a>
                            <a href="#" class="text-muted"><i data-feather="github"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center">
                        <div class="team-member-avatar mb-3">
                            <img src="{{ asset("img/team/member2.jpg") }}" alt="Member 2" class="rounded-circle" style="width: 100px; height: 100px; object-fit: cover;">
                        </div>
                        <h5 class="fw-bold mb-0">Jane Smith</h5>
                        <p class="text-muted">CTO & Co-Founder</p>
                        <div class="social-links mt-3">
                            <a href="#" class="text-muted me-2"><i data-feather="twitter"></i></a>
                            <a href="#" class="text-muted me-2"><i data-feather="linkedin"></i></a>
                            <a href="#" class="text-muted"><i data-feather="github"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center">
                        <div class="team-member-avatar mb-3">
                            <img src="{{ asset("img/team/member3.jpg") }}" alt="Member 3" class="rounded-circle" style="width: 100px; height: 100px; object-fit: cover;">
                        </div>
                        <h5 class="fw-bold mb-0">Alice Johnson</h5>
                        <p class="text-muted">Head of Marketing</p>
                        <div class="social-links mt-3">
                            <a href="#" class="text-muted me-2"><i data-feather="twitter"></i></a>
                            <a href="#" class="text-muted me-2"><i data-feather="linkedin"></i></a>
                            <a href="#" class="text-muted"><i data-feather="github"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center">
                        <div class="team-member-avatar mb-3">
                            <img src="{{ asset("img/team/member4.jpg") }}" alt="Member 4" class="rounded-circle" style="width: 100px; height: 100px; object-fit: cover;">
                        </div>
                        <h5 class="fw-bold mb-0">Bob Brown</h5>
                        <p class="text-muted">Lead Developer</p>
                        <div class="social-links mt-3">
                            <a href="#" class="text-muted me-2"><i data-feather="twitter"></i></a>
                            <a href="#" class="text-muted me-2"><i data-feather="linkedin"></i></a>
                            <a href="#" class="text-muted"><i data-feather="github"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>';
    }

    private function getTestimonialsContent(): string
    {
        return '<section class="testimonials-section py-5 py-lg-7 {{ isset($data["background"]) ? $data["background"] : "bg-light" }}">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 mx-auto text-center mb-5">
                @if(isset($data["badge"]))
                    <div class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill mb-3">{{ $data["badge"] }}</div>
                @endif
                <h2 class="display-5 fw-bold mb-3">{{ $data["title"] ?? "Testimonials" }}</h2>
                <p class="lead text-muted">{{ $data["subtitle"] ?? "What our clients say" }}</p>
            </div>
        </div>
        
        @if(isset($data["style"]) && $data["style"] == "carousel")
            <div class="position-relative testimonial-carousel">
                <div class="swiper-container">
                    <div class="swiper-wrapper pb-5">
                        @foreach($data["testimonials"] ?? [] as $testimonial)
                            <div class="swiper-slide">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-body p-4 p-lg-5">
                                        <div class="d-flex mb-4">
                                            <div class="testimonial-quote text-primary opacity-25 me-3">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-message-circle"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>
                                            </div>
                                            <div class="testimonial-stars">
                                                @for($i = 0; $i < ($testimonial["rating"] ?? 5); $i++)
                                                    <i data-feather="star" class="text-warning" style="width: 16px; height: 16px;"></i>
                                                @endfor
                                            </div>
                                        </div>
                                        
                                        <p class="card-text lead mb-4">"{{ $testimonial["quote"] }}"</p>
                                        
                                        <div class="d-flex align-items-center">
                                            @if(isset($testimonial["avatar"]))
                                                <img src="{{ $testimonial["avatar"] }}" alt="{{ $testimonial["name"] }}" class="rounded-circle me-3" width="60" height="60" style="object-fit: cover; border: 3px solid var(--bs-white); box-shadow: 0 3px 10px rgba(0,0,0,0.1);">
                                            @else
                                                <div class="avatar-placeholder bg-primary text-white rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px; border: 3px solid var(--bs-white); box-shadow: 0 3px 10px rgba(0,0,0,0.1);">
                                                    {{ substr($testimonial["name"], 0, 1) }}
                                                </div>
                                            @endif
                                            <div>
                                                <h5 class="mb-1 fw-bold">{{ $testimonial["name"] }}</h5>
                                                <p class="text-muted mb-0">{{ $testimonial["position"] }}</p>
                                                @if(isset($testimonial["company"]))
                                                    <small class="text-primary">{{ $testimonial["company"] }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="swiper-pagination"></div>
                </div>
                <div class="swiper-button-prev"></div>
                <div class="swiper-button-next"></div>
            </div>
        @else
            <div class="row g-4">
                @foreach($data["testimonials"] ?? [] as $index => $testimonial)
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100 border-0 shadow-sm rounded-lg">
                            <div class="card-body p-4">
                                <div class="d-flex mb-3">
                                    <div class="testimonial-quote text-primary opacity-25 me-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-message-circle"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>
                                    </div>
                                    <div class="testimonial-stars">
                                        @for($i = 0; $i < ($testimonial["rating"] ?? 5); $i++)
                                            <i data-feather="star" class="text-warning" style="width: 16px; height: 16px;"></i>
                                        @endfor
                                    </div>
                                </div>
                                
                                <p class="card-text mb-4">"{{ $testimonial["quote"] }}"</p>
                                
                                <div class="d-flex align-items-center mt-4">
                                    @if(isset($testimonial["avatar"]))
                                        <img src="{{ $testimonial["avatar"] }}" alt="{{ $testimonial["name"] }}" class="rounded-circle me-3" width="50" height="50" style="object-fit: cover; border: 2px solid var(--bs-white); box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                                    @else
                                        <div class="avatar-placeholder bg-primary text-white rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; border: 2px solid var(--bs-white); box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                                            {{ substr($testimonial["name"], 0, 1) }}
                                        </div>
                                    @endif
                                    <div>
                                        <h6 class="mb-0 fw-bold">{{ $testimonial["name"] }}</h6>
                                        <small class="text-muted">{{ $testimonial["position"] }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
        
        @if(isset($data["brands"]))
            <div class="mt-5 pt-4 border-top">
                <div class="row align-items-center">
                    <div class="col-lg-3 mb-4 mb-lg-0">
                        <h5 class="mb-0">{{ $data["brands_title"] ?? "Trusted by:" }}</h5>
                    </div>
                    <div class="col-lg-9">
                        <div class="row align-items-center justify-content-center g-4">
                            @foreach($data["brands"] as $brand)
                                <div class="col-4 col-md-2 text-center">
                                    <img src="{{ $brand["logo"] }}" alt="{{ $brand["name"] }}" class="img-fluid opacity-75 transition-all" style="max-height: 35px; filter: grayscale(100%);">
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</section>';
    }

    private function getBlogGridContent(): string
    {
        return '<section class="blog-section py-5 py-lg-7 {{ isset($data["background"]) ? $data["background"] : "" }}">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 mx-auto text-center mb-5">
                @if(isset($data["badge"]))
                    <div class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill mb-3">{{ $data["badge"] }}</div>
                @endif
                <h2 class="display-5 fw-bold mb-3">{{ $data["title"] ?? "Latest Blog Posts" }}</h2>
                <p class="lead text-muted">{{ $data["subtitle"] ?? "Stay updated with our latest insights and news" }}</p>
            </div>
        </div>
        
        <div class="row g-4">
            @foreach($data["posts"] ?? [] as $post)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card blog-card border-0 shadow-sm h-100">
                        <div class="position-relative">
                            <img src="{{ $post["image"] ?? "https://source.unsplash.com/random/600x400/?blog" }}" class="card-img-top" alt="{{ $post["title"] }}">
                            @if(isset($post["category"]))
                                <div class="position-absolute top-0 end-0 m-3">
                                    <span class="badge bg-{{ isset($post["category_bg"]) ? $post["category_bg"] : "primary" }} px-3 py-2 rounded-pill">{{ $post["category"] }}</span>
                                </div>
                            @endif
                        </div>
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                @if(isset($post["author_avatar"]))
                                    <img src="{{ $post["author_avatar"] }}" alt="{{ $post["author"] ?? "Author" }}" class="rounded-circle me-2" width="30" height="30">
                                @else
                                    <div class="avatar-placeholder bg-secondary text-white rounded-circle me-2 d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;">
                                        {{ substr($post["author"] ?? "A", 0, 1) }}
                                    </div>
                                @endif
                                <span class="text-muted small me-3">{{ $post["author"] ?? "Admin" }}</span>
                                <span class="text-muted small"><i data-feather="calendar" class="me-1" style="width: 14px; height: 14px;"></i> {{ $post["date"] ?? date("M d, Y") }}</span>
                            </div>
                            
                            <h5 class="card-title fw-bold mb-3">
                                <a href="{{ $post["url"] ?? "#" }}" class="text-decoration-none text-dark stretched-link">{{ $post["title"] }}</a>
                            </h5>
                            
                            <p class="card-text text-muted mb-3">{{ $post["excerpt"] }}</p>
                            
                            <div class="d-flex align-items-center">
                                <span class="text-muted small me-3"><i data-feather="eye" style="width: 14px; height: 14px;" class="me-1"></i> {{ $post["views"] ?? "124" }}</span>
                                <span class="text-muted small"><i data-feather="message-square" style="width: 14px; height: 14px;" class="me-1"></i> {{ $post["comments"] ?? "8" }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        @if(isset($data["action"]))
            <div class="text-center mt-5">
                <a href="{{ $data["action"]["url"] ?? "/blog" }}" class="btn btn-{{ isset($data["action"]["btn_style"]) ? $data["action"]["btn_style"] : "outline-primary" }} {{ isset($data["action"]["btn_size"]) ? $data["action"]["btn_size"] : "btn-lg" }} px-4">
                    {{ $data["action"]["text"] ?? "View All Posts" }}
                    <i data-feather="arrow-right" class="ms-2" style="width: 16px; height: 16px;"></i>
                </a>
            </div>
        @endif
    </div>
</section>';
    }

    private function getContactContent(): string
    {
        return '<section class="contact-section py-5 py-lg-7 {{ isset($data["background"]) ? $data["background"] : "bg-light" }}" id="contact">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 mx-auto text-center mb-5">
                @if(isset($data["badge"]))
                    <div class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill mb-3">{{ $data["badge"] }}</div>
                @endif
                <h2 class="display-5 fw-bold mb-3">{{ $data["title"] ?? "Contact Us" }}</h2>
                <p class="lead text-muted">{{ $data["subtitle"] ?? "Get in touch with us" }}</p>
            </div>
        </div>
        
        <div class="row g-4">
            <div class="col-lg-5 mb-4 mb-lg-0">
                <div class="card h-100 border-0 shadow-sm rounded-lg">
                    @if(isset($data["contact_bg"]))
                        <div class="card-body p-4 p-lg-5 position-relative overflow-hidden" style="background: linear-gradient(135deg, var(--bs-primary) 0%, #4e73df 100%); color: white;">
                            <div class="position-absolute top-0 end-0 opacity-10">
                                <svg width="200" height="200" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                                    <path fill="#FFFFFF" d="M42.7,-73.4C55.9,-67.5,67.4,-57.4,75.9,-44.4C84.5,-31.5,90,-15.7,89.5,-0.3C89,15.1,82.5,30.2,73.7,43.1C64.9,56.1,53.8,66.8,40.9,74.1C28,81.3,13.5,84.9,-0.6,85.9C-14.7,86.9,-29.4,85.2,-41.7,78.5C-53.9,71.7,-63.7,60,-72.3,46.7C-81,33.5,-88.4,18.7,-88.3,0C-88.2,-18.7,-80.4,-37.5,-68.8,-52.2C-57.2,-66.8,-41.9,-77.4,-26.3,-81.2C-10.8,-85,-5.4,-82,-0.2,-81.7C5,-81.4,10,-81.6,15.9,-77.1C21.7,-72.6,29.5,-79.3,42.7,-73.4Z" transform="translate(100 100)" />
                                </svg>
                            </div>
                    @else
                        <div class="card-body p-4 p-lg-5">
                    @endif
                        <h4 class="fw-bold mb-4 {{ isset($data["contact_bg"]) ? "text-white" : "" }}">{{ $data["contact_info_title"] ?? "Contact Information" }}</h4>
                        <ul class="list-unstyled mb-5">
                            <li class="mb-4 d-flex">
                                <div class="contact-icon {{ isset($data["contact_bg"]) ? "bg-white text-primary" : "bg-primary text-white" }} rounded-circle p-2 me-3">
                                    <i data-feather="map-pin"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1 {{ isset($data["contact_bg"]) ? "text-white" : "" }}">Address</h6>
                                    <p class="{{ isset($data["contact_bg"]) ? "text-white-50" : "text-muted" }} mb-0">{{ $data["address"] ?? "123 Street, City, Country" }}</p>
                                </div>
                            </li>
                            <li class="mb-4 d-flex">
                                <div class="contact-icon {{ isset($data["contact_bg"]) ? "bg-white text-primary" : "bg-primary text-white" }} rounded-circle p-2 me-3">
                                    <i data-feather="phone"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1 {{ isset($data["contact_bg"]) ? "text-white" : "" }}">Phone</h6>
                                    <p class="{{ isset($data["contact_bg"]) ? "text-white-50" : "text-muted" }} mb-0">{{ $data["phone"] ?? "+1 234 567 8900" }}</p>
                                </div>
                            </li>
                            <li class="mb-4 d-flex">
                                <div class="contact-icon {{ isset($data["contact_bg"]) ? "bg-white text-primary" : "bg-primary text-white" }} rounded-circle p-2 me-3">
                                    <i data-feather="mail"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1 {{ isset($data["contact_bg"]) ? "text-white" : "" }}">Email</h6>
                                    <p class="{{ isset($data["contact_bg"]) ? "text-white-50" : "text-muted" }} mb-0">{{ $data["email"] ?? "info@example.com" }}</p>
                                </div>
                            </li>
                            @if(isset($data["hours"]))
                            <li class="mb-4 d-flex">
                                <div class="contact-icon {{ isset($data["contact_bg"]) ? "bg-white text-primary" : "bg-primary text-white" }} rounded-circle p-2 me-3">
                                    <i data-feather="clock"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1 {{ isset($data["contact_bg"]) ? "text-white" : "" }}">Working Hours</h6>
                                    <p class="{{ isset($data["contact_bg"]) ? "text-white-50" : "text-muted" }} mb-0">{{ $data["hours"] }}</p>
                                </div>
                            </li>
                            @endif
                        </ul>
                        
                        <h6 class="fw-bold mb-3 {{ isset($data["contact_bg"]) ? "text-white" : "" }}">{{ $data["social_title"] ?? "Follow Us" }}</h6>
                        <div class="social-icons">
                            @foreach($data["social_links"] ?? [] as $link)
                                <a href="{{ $link["url"] }}" class="me-2 btn btn-sm {{ isset($data["contact_bg"]) ? "btn-light text-primary" : "btn-outline-primary" }} rounded-circle">
                                    <i data-feather="{{ $link["icon"] }}"></i>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm rounded-lg">
                    <div class="card-body p-4 p-lg-5">
                        @if(isset($data["form_title"]))
                            <h4 class="fw-bold mb-4">{{ $data["form_title"] }}</h4>
                        @endif
                        
                        <form>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="name" placeholder="{{ $data["name_placeholder"] ?? "Your Name" }}" required>
                                        <label for="name">{{ $data["name_label"] ?? "Name" }}</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="email" class="form-control" id="email" placeholder="{{ $data["email_placeholder"] ?? "Your Email" }}" required>
                                        <label for="email">{{ $data["email_label"] ?? "Email" }}</label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="subject" placeholder="{{ $data["subject_placeholder"] ?? "Subject" }}" required>
                                <label for="subject">{{ $data["subject_label"] ?? "Subject" }}</label>
                            </div>
                            
                            <div class="form-floating mb-4">
                                <textarea class="form-control" id="message" style="height: 150px" placeholder="{{ $data["message_placeholder"] ?? "Your Message" }}" required></textarea>
                                <label for="message">{{ $data["message_label"] ?? "Message" }}</label>
                            </div>
                            
                            @if(isset($data["gdpr_text"]))
                                <div class="form-check mb-4">
                                    <input class="form-check-input" type="checkbox" id="gdpr" required>
                                    <label class="form-check-label text-muted" for="gdpr">{{ $data["gdpr_text"] }}</label>
                                </div>
                            @endif
                            
                            <button type="submit" class="btn btn-primary btn-lg px-4">
                                {{ $data["button_text"] ?? "Send Message" }}
                                <i data-feather="send" class="ms-2" style="width: 18px; height: 18px;"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        @if(isset($data["map"]) && $data["map"])
            <div class="mt-5">
                <div class="card border-0 shadow-sm overflow-hidden">
                    <div class="card-body p-0">
                        <div class="ratio ratio-21x9">
                            <iframe src="{{ $data["map_url"] ?? "https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3152.9459046076396!2d-122.4194!3d37.7749!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x80859a6d00690021%3A0x4a501367f076adff!2sSan%20Francisco%2C%20CA%2C%20USA!5e0!3m2!1sen!2sus!4v1637500505644!5m2!1sen!2sus" }}" 
                                    style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</section>';
    }

    private function getCtaContent(): string
    {
        return '<section class="cta-section py-5 py-lg-7 {{ isset($data["background"]) ? $data["background"] : "bg-primary text-white" }}">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card border-0 shadow-lg overflow-hidden">
                    <div class="row g-0">
                        @if(isset($data["style"]) && $data["style"] == "image")
                            <div class="col-md-6 position-relative d-none d-md-block">
                                <div class="h-100" style="background: url(\'{{ $data["image"] ?? "https://source.unsplash.com/random/600x600/?business" }}\') center/cover no-repeat;"></div>
                                @if(isset($data["image_overlay"]) && $data["image_overlay"])
                                    <div class="position-absolute top-0 start-0 w-100 h-100 bg-primary bg-opacity-50"></div>
                                @endif
                                @if(isset($data["image_badge"]))
                                    <div class="position-absolute top-0 end-0 m-4">
                                        <span class="badge bg-primary p-3 rounded-pill">{{ $data["image_badge"] }}</span>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-6">
                        @else
                            <div class="col-12">
                        @endif
                                <div class="card-body p-4 p-lg-5 {{ isset($data["content_bg"]) ? $data["content_bg"] : "bg-white" }}">
                                    @if(isset($data["badge"]))
                                        <div class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill mb-3">{{ $data["badge"] }}</div>
                                    @endif
                                    <h2 class="card-title fw-bold mb-3 {{ isset($data["title_color"]) ? $data["title_color"] : "" }}">{{ $data["title"] ?? "Ready to Get Started?" }}</h2>
                                    <p class="card-text mb-4 {{ isset($data["content_color"]) ? $data["content_color"] : "text-muted" }}">{{ $data["content"] ?? "Join thousands of satisfied customers who are already using our services to grow their business." }}</p>
                                    
                                    <div class="d-flex flex-column flex-sm-row gap-3">
                                        <a href="{{ $data["primary_button_url"] ?? "#" }}" class="btn btn-primary px-4 py-2">
                                            {{ $data["primary_button_text"] ?? "Get Started" }}
                                            @if(isset($data["primary_button_icon"]))
                                                <i data-feather="{{ $data["primary_button_icon"] }}" class="ms-2" style="width: 16px; height: 16px;"></i>
                                            @endif
                                        </a>
                                        @if(isset($data["secondary_button_text"]))
                                            <a href="{{ $data["secondary_button_url"] ?? "#" }}" class="btn btn-outline-primary px-4 py-2">
                                                {{ $data["secondary_button_text"] }}
                                                @if(isset($data["secondary_button_icon"]))
                                                    <i data-feather="{{ $data["secondary_button_icon"] }}" class="ms-2" style="width: 16px; height: 16px;"></i>
                                                @endif
                                            </a>
                                        @endif
                                    </div>
                                    
                                    @if(isset($data["note"]))
                                        <p class="small mt-3 mb-0 {{ isset($data["content_color"]) ? $data["content_color"] : "text-muted" }}">{{ $data["note"] }}</p>
                                    @endif
                                    
                                    @if(isset($data["features"]))
                                        <div class="row mt-4">
                                            @foreach($data["features"] as $feature)
                                                <div class="col-sm-6 mb-2">
                                                    <div class="d-flex align-items-center">
                                                        <i data-feather="check-circle" class="text-success me-2" style="width: 16px; height: 16px;"></i>
                                                        <span class="small">{{ $feature }}</span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>';
    }

    private function getPricingContent(): string
    {
        return '<section class="pricing-section py-5 py-lg-7 {{ isset($data["background"]) ? $data["background"] : "" }}">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 mx-auto text-center mb-5">
                @if(isset($data["badge"]))
                    <div class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill mb-3">{{ $data["badge"] }}</div>
                @endif
                <h2 class="display-5 fw-bold mb-3">{{ $data["title"] ?? "Pricing Plans" }}</h2>
                <p class="lead text-muted">{{ $data["subtitle"] ?? "Choose the plan that fits your needs" }}</p>
            </div>
        </div>
        
        @if(isset($data["toggle_billing"]) && $data["toggle_billing"])
            <div class="row">
                <div class="col-lg-8 mx-auto text-center mb-5">
                    <div class="d-inline-flex align-items-center bg-light p-1 rounded">
                        <button type="button" class="btn {{ isset($data["active_billing"]) && $data["active_billing"] == "monthly" ? "btn-primary" : "btn-light" }} px-4">Monthly</button>
                        <button type="button" class="btn {{ isset($data["active_billing"]) && $data["active_billing"] == "annually" ? "btn-primary" : "btn-light" }} px-4">Annually</button>
                    </div>
                    @if(isset($data["billing_note"]))
                        <p class="text-muted small mt-2">{{ $data["billing_note"] }}</p>
                    @endif
                </div>
            </div>
        @endif
        
        <div class="row g-4 justify-content-center">
            @foreach($data["plans"] ?? [] as $index => $plan)
                <div class="col-md-6 col-lg-4">
                    <div class="card pricing-card h-100 border-0 shadow-sm {{ isset($plan["featured"]) && $plan["featured"] ? "border border-primary border-2" : "" }} rounded-lg overflow-hidden">
                        @if(isset($plan["featured"]) && $plan["featured"])
                            <div class="card-badge bg-primary text-white py-1 px-3 position-absolute top-0 end-0 rounded-bottom-start fw-bold">
                                {{ $plan["featured_label"] ?? "Popular" }}
                            </div>
                        @endif
                        
                        <div class="card-header bg-{{ isset($plan["header_bg"]) ? $plan["header_bg"] : (isset($plan["featured"]) && $plan["featured"] ? "primary text-white" : "transparent") }} text-center border-0 pt-4 pb-0">
                            @if(isset($plan["icon"]))
                                <div class="pricing-icon bg-{{ isset($plan["featured"]) && $plan["featured"] ? "white text-primary" : "light text-primary" }} rounded-circle mx-auto mb-3" style="width: 60px; height: 60px;">
                                    <i data-feather="{{ $plan["icon"] }}" style="width: 24px; height: 24px;"></i>
                                </div>
                            @endif
                            <h4 class="fw-bold mb-0">{{ $plan["name"] }}</h4>
                            <p class="text-{{ isset($plan["featured"]) && $plan["featured"] ? "white-50" : "muted" }} mb-4">{{ $plan["description"] }}</p>
                        </div>
                        
                        <div class="card-body p-4">
                            <div class="pricing-price text-center mb-4">
                                <span class="pricing-currency">{{ $plan["currency"] ?? "$" }}</span>
                                <span class="display-4 fw-bold">{{ $plan["price"] }}</span>
                                <span class="text-muted">{{ $plan["period"] ?? "/month" }}</span>
                            </div>
                            
                            <ul class="pricing-features list-unstyled mb-4">
                                @foreach($plan["features"] as $feature)
                                    <li class="d-flex align-items-center mb-3">
                                        <div class="flex-shrink-0 me-2 text-success">
                                            <i data-feather="check-circle" style="width: 18px; height: 18px;"></i>
                                        </div>
                                        <span>{{ $feature }}</span>
                                    </li>
                                @endforeach
                                
                                @foreach($plan["nonfeatures"] ?? [] as $nonfeature)
                                    <li class="d-flex align-items-center mb-3 text-muted">
                                        <div class="flex-shrink-0 me-2 text-muted">
                                            <i data-feather="x-circle" style="width: 18px; height: 18px;"></i>
                                        </div>
                                        <span>{{ $nonfeature }}</span>
                                    </li>
                                @endforeach
                            </div>
                        </div>
                        
                        <div class="card-footer bg-transparent border-0 p-4 pt-0 text-center">
                            <a href="{{ $plan["button_url"] ?? "#" }}" class="btn {{ isset($plan["featured"]) && $plan["featured"] ? "btn-primary" : "btn-outline-primary" }} btn-lg w-100">
                                {{ $plan["button_text"] ?? "Get Started" }}
                            </a>
                            @if(isset($plan["note"]))
                                <p class="small text-muted mt-3 mb-0">{{ $plan["note"] }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        @if(isset($data["guarantee"]))
            <div class="row mt-5">
                <div class="col-lg-8 mx-auto text-center">
                    <div class="d-flex justify-content-center align-items-center">
                        <i data-feather="shield" class="text-primary me-2"></i>
                        <p class="mb-0">{{ $data["guarantee"] }}</p>
                    </div>
                </div>
            </div>
        @endif
        
        @if(isset($data["faq_title"]))
            <div class="row mt-5 pt-5 border-top">
                <div class="col-lg-8 mx-auto text-center mb-4">
                    <h3 class="fw-bold">{{ $data["faq_title"] }}</h3>
                    @if(isset($data["faq_subtitle"]))
                        <p class="text-muted">{{ $data["faq_subtitle"] }}</p>
                    @endif
                </div>
                
                <div class="col-lg-8 mx-auto">
                    <div class="accordion" id="pricingFAQ">
                        @foreach($data["faqs"] ?? [] as $index => $faq)
                            <div class="accordion-item border-0 shadow-sm mb-3 rounded">
                                <h3 class="accordion-header" id="heading{{ $index }}">
                                    <button class="accordion-button {{ $index > 0 ? "collapsed" : "" }} bg-white" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $index }}" aria-expanded="{{ $index == 0 ? "true" : "false" }}" aria-controls="collapse{{ $index }}">
                                        {{ $faq["question"] }}
                                    </button>
                                </h3>
                                <div id="collapse{{ $index }}" class="accordion-collapse collapse {{ $index == 0 ? "show" : "" }}" aria-labelledby="heading{{ $index }}" data-bs-parent="#pricingFAQ">
                                    <div class="accordion-body">
                                        {{ $faq["answer"] }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>
</section>';
    }

    private function getNewsletterContent(): string
    {
        return '@include("frontend.components.newsletter-form", ["data" => $data])';
    }

    private function getQuoteContent(): string
    {
        return '@include("frontend.components.quote-form", ["data" => $data])';
    }

    private function getClassicNavContent(): string
    {
        return '<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="/">
            {{ $site->name ?? "Your Site" }}
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="#home">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="#about">About</a></li>
                <li class="nav-item"><a class="nav-link" href="#services">Services</a></li>
                <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
            </ul>
        </div>
    </div>
</nav>';
    }

    private function getSimpleFooterContent(): string
    {
        return '<footer class="bg-light py-4 text-center">
    <div class="container">
        <p class="mb-0">&copy; 2025 {{ $site->name ?? "Your Site" }}. All rights reserved.</p>
    </div>
</footer>';
    }
}
