@if($layoutName == 'hero')
    <!-- Hero Section -->
    <section class="hero-section bg-primary text-white py-5 mb-5">
        <div class="container">
            <div class="row align-items-center min-vh-75">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold mb-4">{{ $data['title'] ?? 'Welcome to Our Company' }}</h1>
                    <p class="lead mb-4">{{ $data['subtitle'] ?? 'We provide innovative solutions that help your business grow and succeed in the digital world.' }}</p>
                    <div class="d-flex gap-3">
                        <a href="{{ $data['button_url'] ?? '#services' }}" class="btn btn-light btn-lg">
                            {{ $data['button_text'] ?? 'Get Started' }}
                        </a>
                        <a href="{{ $data['secondary_button_url'] ?? '#about' }}" class="btn btn-outline-light btn-lg">
                            {{ $data['secondary_button_text'] ?? 'Learn More' }}
                        </a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="text-center">
                        <img src="{{ $data['image'] ?? 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNTAwIiBoZWlnaHQ9IjMwMCIgdmlld0JveD0iMCAwIDUwMCAzMDAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxyZWN0IHdpZHRoPSI1MDAiIGhlaWdodD0iMzAwIiBmaWxsPSIjRjhGOUZBIi8+CjxwYXRoIGQ9Ik0yNTAgMTUwTDMwMCAxMDBIMjAwTDI1MCAxNTBaTTI1MCAxNTBMMjAwIDIwMEgzMDBMMjUwIDE1MFoiIGZpbGw9IiM2Qzc1N0QiLz4KPHRLEHT4dGV4dCB4PSI1MCUiIHk9IjUwJSIgZG9taW5hbnQtYmFzZWxpbmU9Im1pZGRsZSIgdGV4dC1hbmNob3I9Im1pZGRsZSIgZmlsbD0iIzZDNzU3RCIgZm9udC1mYW1pbHk9IkFyaWFsLCBzYW5zLXNlcmlmIiBmb250LXNpemU9IjE4Ij5IZXJvIEltYWdlPC90ZXh0Pgo8L3N2Zz4=' }}" 
                             alt="Hero Image" class="img-fluid rounded shadow">
                    </div>
                </div>
            </div>
        </div>
    </section>

@elseif($layoutName == 'features')
    <!-- Features Section -->
    <section class="features-section py-5">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center mb-5">
                    <h2 class="display-6 fw-bold">{{ $data['title'] ?? 'Our Features' }}</h2>
                    <p class="lead text-muted">{{ $data['subtitle'] ?? 'Discover what makes us different' }}</p>
                </div>
            </div>
            <div class="row g-4">
                @foreach($data['features'] ?? [
                    ['title' => 'Professional Design', 'description' => 'Modern and clean design that represents your brand perfectly', 'icon' => 'palette'],
                    ['title' => 'Responsive Layout', 'description' => 'Works seamlessly across all devices and screen sizes', 'icon' => 'smartphone'],
                    ['title' => 'Fast Performance', 'description' => 'Optimized for speed and performance to deliver the best user experience', 'icon' => 'zap'],
                    ['title' => 'SEO Optimized', 'description' => 'Built with SEO best practices to help your site rank higher', 'icon' => 'search'],
                    ['title' => '24/7 Support', 'description' => 'Round-the-clock support to help you whenever you need assistance', 'icon' => 'headphones'],
                    ['title' => 'Secure & Reliable', 'description' => 'Enterprise-grade security and 99.9% uptime guarantee', 'icon' => 'shield']
                ] as $feature)
                    <div class="col-lg-4 col-md-6">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body text-center p-4">
                                <div class="feature-icon mb-3">
                                    <i class="align-middle text-primary" data-feather="{{ $feature['icon'] }}" style="width: 48px; height: 48px;"></i>
                                </div>
                                <h5 class="card-title mb-3">{{ $feature['title'] }}</h5>
                                <p class="card-text text-muted">{{ $feature['description'] }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

@elseif($layoutName == 'about')
    <!-- About Section -->
    <section class="about-section py-5 bg-light">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h2 class="display-6 fw-bold mb-4">{{ $data['title'] ?? 'About Our Company' }}</h2>
                    <p class="lead mb-4">{{ $data['subtitle'] ?? 'We are passionate about delivering exceptional results for our clients.' }}</p>
                    <p class="mb-4">{{ $data['description'] ?? 'With years of experience in the industry, we have helped hundreds of businesses achieve their goals through innovative solutions and dedicated support.' }}</p>
                    
                    <div class="row g-4 mb-4">
                        @foreach($data['stats'] ?? [
                            ['number' => '500+', 'label' => 'Projects Completed'],
                            ['number' => '150+', 'label' => 'Happy Clients'],
                            ['number' => '5+', 'label' => 'Years Experience']
                        ] as $stat)
                            <div class="col-sm-4">
                                <div class="text-center">
                                    <h3 class="text-primary fw-bold">{{ $stat['number'] }}</h3>
                                    <p class="text-muted mb-0">{{ $stat['label'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <a href="{{ $data['button_url'] ?? '#contact' }}" class="btn btn-primary btn-lg">
                        {{ $data['button_text'] ?? 'Learn More' }}
                    </a>
                </div>
                <div class="col-lg-6">
                    <div class="text-center">
                        <img src="{{ $data['image'] ?? 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNTAwIiBoZWlnaHQ9IjQwMCIgdmlld0JveD0iMCAwIDUwMCA0MDAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxyZWN0IHdpZHRoPSI1MDAiIGhlaWdodD0iNDAwIiBmaWxsPSIjRjhGOUZBIi8+CjxjaXJjbGUgY3g9IjI1MCIgY3k9IjIwMCIgcj0iNjAiIGZpbGw9IiM2Qzc1N0QiLz4KPHRLEHT4dGV4dCB4PSI1MCUiIHk9IjcwJSIgZG9taW5hbnQtYmFzZWxpbmU9Im1pZGRsZSIgdGV4dC1hbmNob3I9Im1pZGRsZSIgZmlsbD0iIzZDNzU3RCIgZm9udC1mYW1pbHk9IkFyaWFsLCBzYW5zLXNlcmlmIiBmb250LXNpemU9IjE4Ij5BYm91dCBJbWFnZTwvdGV4dD4KPC9zdmc+' }}" 
                             alt="About Us" class="img-fluid rounded shadow">
                    </div>
                </div>
            </div>
        </div>
    </section>

@elseif($layoutName == 'dashboard')
    <div class="row">
        <div class="col-12 col-md-6 col-xxl-3 d-flex order-2 order-xxl-3">
            <div class="card flex-fill w-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">{{ $data['calendar_title'] ?? 'Calendar' }}</h5>
                </div>
                <div class="card-body d-flex">
                    <div class="align-self-center w-100">
                        <div class="chart">
                            <div id="datetimepicker-dashboard"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-12 col-xxl-6 d-flex order-3 order-xxl-2">
            <div class="card flex-fill w-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">{{ $data['world_title'] ?? 'Real-Time' }}</h5>
                </div>
                <div class="card-body px-4">
                    <div id="world_map" style="height:350px;"></div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xxl-3 d-flex order-1 order-xxl-1">
            <div class="card flex-fill">
                <div class="card-header">
                    <h5 class="card-title mb-0">{{ $data['monthly_title'] ?? 'Monthly Sales' }}</h5>
                </div>
                <div class="card-body d-flex">
                    <div class="align-self-center w-100">
                        <div class="py-3">
                            <div class="chart chart-xs">
                                <canvas id="chartjs-dashboard-bar"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@elseif($layoutName == 'content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">{{ $data['title'] ?? 'Content Section' }}</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @if(isset($data['content']))
                            <div class="col-lg-8">
                                <div class="content-area">
                                    {!! $data['content'] !!}
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="sidebar-content">
                                    @if(isset($data['sidebar']))
                                        {!! $data['sidebar'] !!}
                                    @else
                                        <div class="card">
                                            <div class="card-header">
                                                <h6 class="card-title mb-0">Quick Info</h6>
                                            </div>
                                            <div class="card-body">
                                                <p class="text-muted">Additional information goes here.</p>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @else
                            <div class="col-12">
                                <p class="text-muted">{{ $data['description'] ?? 'No content available.' }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@elseif($layoutName == 'contact')
    <!-- Contact Section -->
    <section class="contact-section py-5 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center mb-5">
                    <h2 class="display-6 fw-bold">{{ $data['title'] ?? 'Get In Touch' }}</h2>
                    <p class="lead text-muted">{{ $data['subtitle'] ?? 'We\'d love to hear from you. Send us a message and we\'ll respond as soon as possible.' }}</p>
                </div>
            </div>
            
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="card shadow-sm border-0">
                        <div class="card-body p-5">
                            <form>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="name" class="form-label">Full Name</label>
                                        <input type="text" class="form-control form-control-lg" id="name" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="email" class="form-label">Email Address</label>
                                        <input type="email" class="form-control form-control-lg" id="email" required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="subject" class="form-label">Subject</label>
                                    <input type="text" class="form-control form-control-lg" id="subject" required>
                                </div>
                                <div class="mb-4">
                                    <label for="message" class="form-label">Message</label>
                                    <textarea class="form-control form-control-lg" id="message" rows="5" required></textarea>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary btn-lg px-5">
                                        {{ $data['button_text'] ?? 'Send Message' }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row mt-5">
                <div class="col-md-4 text-center mb-4">
                    <div class="contact-info">
                        <div class="contact-icon mb-3">
                            <i class="align-middle text-primary" data-feather="mail" style="width: 48px; height: 48px;"></i>
                        </div>
                        <h5 class="mb-2">Email Us</h5>
                        <p class="text-muted">{{ $data['email'] ?? 'info@company.com' }}</p>
                    </div>
                </div>
                <div class="col-md-4 text-center mb-4">
                    <div class="contact-info">
                        <div class="contact-icon mb-3">
                            <i class="align-middle text-primary" data-feather="phone" style="width: 48px; height: 48px;"></i>
                        </div>
                        <h5 class="mb-2">Call Us</h5>
                        <p class="text-muted">{{ $data['phone'] ?? '+1 (555) 123-4567' }}</p>
                    </div>
                </div>
                <div class="col-md-4 text-center mb-4">
                    <div class="contact-info">
                        <div class="contact-icon mb-3">
                            <i class="align-middle text-primary" data-feather="map-pin" style="width: 48px; height: 48px;"></i>
                        </div>
                        <h5 class="mb-2">Visit Us</h5>
                        <p class="text-muted">{{ $data['address'] ?? '123 Business Street, City, State 12345' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

@else
    <!-- Default section template -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ $data['title'] ?? 'Section Title' }}</h5>
                    <p class="card-text">{{ $data['content'] ?? 'Section content goes here.' }}</p>
                </div>
            </div>
        </div>
    </div>
@endif
