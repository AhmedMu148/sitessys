<!-- Landing Page Footer -->
<footer class="footer-section bg-dark text-white py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 mb-4">
                <h5 class="fw-bold mb-3">{{ $data['brand'] ?? 'MyCompany' }}</h5>
                <p class="text-muted mb-3">{{ $data['description'] ?? 'We are committed to providing exceptional service and innovative solutions for our clients.' }}</p>
                
                <div class="d-flex gap-3">
                    @foreach($data['social_links'] ?? [
                        ['name' => 'Facebook', 'url' => '#', 'icon' => 'facebook'],
                        ['name' => 'Twitter', 'url' => '#', 'icon' => 'twitter'],
                        ['name' => 'LinkedIn', 'url' => '#', 'icon' => 'linkedin'],
                        ['name' => 'Instagram', 'url' => '#', 'icon' => 'instagram']
                    ] as $social)
                        <a href="{{ $social['url'] }}" class="text-muted" target="_blank">
                            <i class="align-middle" data-feather="{{ $social['icon'] }}"></i>
                        </a>
                    @endforeach
                </div>
            </div>
            
            <div class="col-lg-2 col-md-6 mb-4">
                <h6 class="fw-bold mb-3">Quick Links</h6>
                <ul class="list-unstyled">
                    @foreach($data['quick_links'] ?? [
                        ['title' => 'Home', 'url' => '/'],
                        ['title' => 'About', 'url' => '/about'],
                        ['title' => 'Services', 'url' => '/services'],
                        ['title' => 'Contact', 'url' => '/contact']
                    ] as $link)
                        <li class="mb-2">
                            <a href="{{ $link['url'] }}" class="text-muted text-decoration-none">
                                {{ $link['title'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-4">
                <h6 class="fw-bold mb-3">Contact Info</h6>
                <div class="contact-info">
                    <p class="text-muted mb-2">
                        <i class="align-middle me-2" data-feather="mail"></i>
                        {{ $data['email'] ?? 'info@company.com' }}
                    </p>
                    <p class="text-muted mb-2">
                        <i class="align-middle me-2" data-feather="phone"></i>
                        {{ $data['phone'] ?? '+1 (555) 123-4567' }}
                    </p>
                    <p class="text-muted mb-0">
                        <i class="align-middle me-2" data-feather="map-pin"></i>
                        {{ $data['address'] ?? '123 Business St, City, State 12345' }}
                    </p>
                </div>
            </div>
            
            <div class="col-lg-3 mb-4">
                <h6 class="fw-bold mb-3">Newsletter</h6>
                <p class="text-muted mb-3">Subscribe to our newsletter for updates and news.</p>
                <div class="input-group">
                    <input type="email" class="form-control" placeholder="Enter your email">
                    <button class="btn btn-primary" type="button">
                        <i class="align-middle" data-feather="send"></i>
                    </button>
                </div>
            </div>
        </div>
        
        <hr class="my-4">
        
        <div class="row align-items-center">
            <div class="col-md-6">
                <p class="mb-0 text-muted">
                    &copy; {{ date('Y') }} {{ $data['brand'] ?? 'MyCompany' }}. All rights reserved.
                </p>
            </div>
            <div class="col-md-6 text-md-end">
                <div class="d-flex justify-content-md-end gap-3">
                    @foreach($data['legal_links'] ?? [
                        ['title' => 'Privacy Policy', 'url' => '/privacy'],
                        ['title' => 'Terms of Service', 'url' => '/terms'],
                        ['title' => 'Cookie Policy', 'url' => '/cookies']
                    ] as $link)
                        <a href="{{ $link['url'] }}" class="text-muted text-decoration-none">
                            {{ $link['title'] }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</footer>
