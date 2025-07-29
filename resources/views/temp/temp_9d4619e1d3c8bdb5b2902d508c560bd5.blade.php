<section class="section-testimonials py-5" data-variant="3">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="display-5 fw-bold mb-3">{{ $config['title'] ?? 'Section Title' }}</h2>
                <p class="lead">{{ $config['subtitle'] ?? 'Section subtitle goes here' }}</p>
            </div>
        </div>
        
        @if($variant == 1)
            <!-- Hero Video Content -->
            <div class="hero-content text-center">
                <h1 class="display-4 fw-bold mb-4">{{ $config['hero_title'] ?? 'Welcome to Our Site' }}</h1>
                <p class="lead mb-4">{{ $config['hero_description'] ?? 'Your success story starts here' }}</p>
                <div class="hero-buttons">
                    <a href="{{ $config['cta_url'] ?? '#' }}" class="btn btn-primary btn-lg me-3">
                        {{ $config['cta_text'] ?? 'Get Started' }}
                    </a>
                    <a href="{{ $config['secondary_url'] ?? '#' }}" class="btn btn-outline-primary btn-lg">
                        {{ $config['secondary_text'] ?? 'Learn More' }}
                    </a>
                </div>
            </div>
        @endif
        
        @if($variant == 2)
            <!-- Services Grid -->
            <div class="row g-4">
                @foreach($config['services'] ?? [] as $service)
                    <div class="col-lg-4 col-md-6">
                        <div class="service-card h-100 p-4 text-center">
                            <div class="service-icon mb-3">
                                <i class="{{ $service['icon'] ?? 'fas fa-star' }} fs-1"></i>
                            </div>
                            <h4>{{ $service['title'] ?? 'Service Title' }}</h4>
                            <p>{{ $service['description'] ?? 'Service description' }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
        
        @if($variant == 5)
            <!-- Contact Form -->
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <form class="contact-form">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <input type="text" class="form-control" placeholder="Your Name" required>
                            </div>
                            <div class="col-md-6">
                                <input type="email" class="form-control" placeholder="Your Email" required>
                            </div>
                            <div class="col-12">
                                <input type="text" class="form-control" placeholder="Subject" required>
                            </div>
                            <div class="col-12">
                                <textarea class="form-control" rows="5" placeholder="Your Message" required></textarea>
                            </div>
                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-primary btn-lg">Send Message</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    </div>
</section>