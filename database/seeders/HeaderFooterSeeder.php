<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TplLayout;
use App\Models\Site;

class HeaderFooterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Simple Header
        $simpleHeader = TplLayout::create([
            'tpl_id' => 'simple-header',
            'layout_type' => 'header',
            'name' => 'Simple Header',
            'description' => 'A clean and simple navigation header',
            'preview_image' => '/img/templates/simple-header.png',
            'path' => 'frontend.templates.headers.simple',
            'content' => [
                'html' => '<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="/">{{site_name}}</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="/">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="/about">About</a></li>
                <li class="nav-item"><a class="nav-link" href="/services">Services</a></li>
                <li class="nav-item"><a class="nav-link" href="/contact">Contact</a></li>
            </ul>
        </div>
    </div>
</nav>',
                'css' => '.navbar { transition: all 0.3s ease; }',
                'js' => ''
            ],
            'default_config' => [
                'site_name' => 'My Company',
                'background_color' => '#ffffff',
                'text_color' => '#333333'
            ],
            'configurable_fields' => [
                'site_name' => [
                    'type' => 'text',
                    'label' => 'Site Name',
                    'default' => 'My Company'
                ],
                'background_color' => [
                    'type' => 'color',
                    'label' => 'Background Color',
                    'default' => '#ffffff'
                ]
            ],
            'status' => true,
            'sort_order' => 1
        ]);

        // Create Modern Header
        $modernHeader = TplLayout::create([
            'tpl_id' => 'modern-header',
            'layout_type' => 'header',
            'name' => 'Modern Header',
            'description' => 'A modern header with gradient background',
            'preview_image' => '/img/templates/modern-header.png',
            'path' => 'frontend.templates.headers.modern',
            'content' => [
                'html' => '<nav class="navbar navbar-expand-lg navbar-dark bg-gradient">
    <div class="container">
        <a class="navbar-brand fw-bold" href="/">{{site_name}}</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="/">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="/about">About</a></li>
                <li class="nav-item"><a class="nav-link" href="/services">Services</a></li>
                <li class="nav-item"><a class="nav-link" href="/contact">Contact</a></li>
            </ul>
            <div class="ms-3">
                <a href="/contact" class="btn btn-outline-light">Get Started</a>
            </div>
        </div>
    </div>
</nav>',
                'css' => '.bg-gradient { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }',
                'js' => ''
            ],
            'default_config' => [
                'site_name' => 'Modern Company'
            ],
            'configurable_fields' => [
                'site_name' => [
                    'type' => 'text',
                    'label' => 'Site Name',
                    'default' => 'Modern Company'
                ]
            ],
            'status' => true,
            'sort_order' => 2
        ]);

        // Create Simple Footer
        $simpleFooter = TplLayout::create([
            'tpl_id' => 'simple-footer',
            'layout_type' => 'footer',
            'name' => 'Simple Footer',
            'description' => 'A clean and simple footer',
            'preview_image' => '/img/templates/simple-footer.png',
            'path' => 'frontend.templates.footers.simple',
            'content' => [
                'html' => '<footer class="bg-dark text-white py-4">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h5>{{site_name}}</h5>
                <p>{{description}}</p>
            </div>
            <div class="col-md-6 text-end">
                <p>&copy; {{year}} {{site_name}}. All rights reserved.</p>
            </div>
        </div>
    </div>
</footer>',
                'css' => '',
                'js' => ''
            ],
            'default_config' => [
                'site_name' => 'My Company',
                'description' => 'We provide excellent services.',
                'year' => date('Y')
            ],
            'configurable_fields' => [
                'site_name' => [
                    'type' => 'text',
                    'label' => 'Site Name',
                    'default' => 'My Company'
                ],
                'description' => [
                    'type' => 'textarea',
                    'label' => 'Description',
                    'default' => 'We provide excellent services.'
                ]
            ],
            'status' => true,
            'sort_order' => 1
        ]);

        // Create Corporate Footer
        $corporateFooter = TplLayout::create([
            'tpl_id' => 'corporate-footer',
            'layout_type' => 'footer',
            'name' => 'Corporate Footer',
            'description' => 'A comprehensive corporate footer with multiple sections',
            'preview_image' => '/img/templates/corporate-footer.png',
            'path' => 'frontend.templates.footers.corporate',
            'content' => [
                'html' => '<footer class="bg-dark text-white py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 mb-4">
                <h5>{{site_name}}</h5>
                <p>{{description}}</p>
                <div class="social-links">
                    <a href="{{facebook_url}}" class="text-white me-3"><i class="fab fa-facebook"></i></a>
                    <a href="{{twitter_url}}" class="text-white me-3"><i class="fab fa-twitter"></i></a>
                    <a href="{{linkedin_url}}" class="text-white me-3"><i class="fab fa-linkedin"></i></a>
                </div>
            </div>
            <div class="col-lg-2 mb-4">
                <h6>Company</h6>
                <ul class="list-unstyled">
                    <li><a href="/about" class="text-light">About</a></li>
                    <li><a href="/services" class="text-light">Services</a></li>
                    <li><a href="/portfolio" class="text-light">Portfolio</a></li>
                </ul>
            </div>
            <div class="col-lg-3 mb-4">
                <h6>Contact</h6>
                <p>{{address}}</p>
                <p>Email: {{email}}</p>
                <p>Phone: {{phone}}</p>
            </div>
            <div class="col-lg-3 mb-4">
                <h6>Newsletter</h6>
                <p>Subscribe to our newsletter for updates</p>
                <div class="input-group">
                    <input type="email" class="form-control" placeholder="Your email">
                    <button class="btn btn-primary">Subscribe</button>
                </div>
            </div>
        </div>
        <hr class="my-4">
        <div class="row">
            <div class="col-12 text-center">
                <p>&copy; {{year}} {{site_name}}. All rights reserved.</p>
            </div>
        </div>
    </div>
</footer>',
                'css' => '.social-links a:hover { opacity: 0.8; }',
                'js' => ''
            ],
            'default_config' => [
                'site_name' => 'Corporate Company',
                'description' => 'Leading provider of business solutions.',
                'year' => date('Y'),
                'facebook_url' => '#',
                'twitter_url' => '#',
                'linkedin_url' => '#',
                'address' => '123 Business St, City, State 12345',
                'email' => 'info@company.com',
                'phone' => '+1 (555) 123-4567'
            ],
            'configurable_fields' => [
                'site_name' => [
                    'type' => 'text',
                    'label' => 'Company Name',
                    'default' => 'Corporate Company'
                ],
                'description' => [
                    'type' => 'textarea',
                    'label' => 'Company Description',
                    'default' => 'Leading provider of business solutions.'
                ],
                'email' => [
                    'type' => 'email',
                    'label' => 'Contact Email',
                    'default' => 'info@company.com'
                ],
                'phone' => [
                    'type' => 'text',
                    'label' => 'Phone Number',
                    'default' => '+1 (555) 123-4567'
                ],
                'address' => [
                    'type' => 'textarea',
                    'label' => 'Address',
                    'default' => '123 Business St, City, State 12345'
                ]
            ],
            'status' => true,
            'sort_order' => 2
        ]);

        // Update the first site to use these headers and footers
        $firstSite = Site::first();
        if ($firstSite) {
            $firstSite->update([
                'active_header_id' => $simpleHeader->id,
                'active_footer_id' => $simpleFooter->id
            ]);
        }

        $this->command->info('Header and Footer templates created successfully!');
    }
}
