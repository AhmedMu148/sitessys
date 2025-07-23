{{-- Modern Features Grid Section --}}
<section class="features-section py-5" 
         style="background-color: {{ $config['background_color'] ?? '#f8f9fa' }};">
    
    <div class="container">
        {{-- Section Header --}}
        <div class="row text-center mb-5">
            <div class="col-lg-8 mx-auto">
                <h2 class="section-title display-5 fw-bold mb-3 text-{{ $config['title_color'] ?? 'dark' }}">
                    {{ $config['section_title'] ?? 'Our Amazing Features' }}
                </h2>
                <p class="section-subtitle lead text-muted mb-0">
                    {{ $config['section_subtitle'] ?? 'Discover what makes us different and why thousands of customers trust us' }}
                </p>
                @if(!empty($config['section_description']))
                    <p class="section-description mt-3 text-muted">
                        {{ $config['section_description'] }}
                    </p>
                @endif
            </div>
        </div>
        
        {{-- Features Grid --}}
        <div class="row g-4">
            @foreach($config['features'] ?? [] as $index => $feature)
                <div class="col-lg-{{ 12 / ($config['columns'] ?? 3) }} col-md-6" 
                     data-aos="fade-up" 
                     data-aos-delay="{{ $index * 100 }}">
                    
                    <div class="feature-card h-100 p-4 bg-white rounded-3 shadow-sm hover-lift border-0">
                        {{-- Feature Icon --}}
                        <div class="feature-icon mb-4 text-center">
                            @if(!empty($feature['icon']))
                                <div class="icon-wrapper d-inline-flex align-items-center justify-content-center rounded-circle mb-3"
                                     style="width: {{ $config['icon_size'] ?? '80' }}px; 
                                            height: {{ $config['icon_size'] ?? '80' }}px; 
                                            background: {{ $config['icon_background'] ?? 'linear-gradient(135deg, #667eea, #764ba2)' }};">
                                    <i class="{{ $feature['icon'] }} text-white"
                                       style="font-size: {{ ($config['icon_size'] ?? 80) * 0.4 }}px;"></i>
                                </div>
                            @endif
                            
                            @if(!empty($feature['image']))
                                <img src="{{ $feature['image'] }}" 
                                     alt="{{ $feature['title'] ?? 'Feature' }}"
                                     class="feature-image mb-3"
                                     style="width: {{ $config['icon_size'] ?? '80' }}px; 
                                            height: {{ $config['icon_size'] ?? '80' }}px; 
                                            object-fit: cover; 
                                            border-radius: 50%;">
                            @endif
                        </div>
                        
                        {{-- Feature Content --}}
                        <div class="feature-content text-center">
                            <h4 class="feature-title fw-bold mb-3 text-{{ $config['title_color'] ?? 'dark' }}">
                                {{ $feature['title'] ?? 'Feature Title' }}
                            </h4>
                            
                            <p class="feature-description text-muted mb-3" style="line-height: 1.6;">
                                {{ $feature['description'] ?? 'Feature description goes here. This explains the benefit and value this feature provides to users.' }}
                            </p>
                            
                            {{-- Optional Feature Metrics --}}
                            @if(!empty($feature['metric_value']))
                                <div class="feature-metric mb-3">
                                    <span class="metric-value fw-bold text-primary fs-4">
                                        {{ $feature['metric_value'] }}
                                    </span>
                                    @if(!empty($feature['metric_label']))
                                        <span class="metric-label text-muted small d-block">
                                            {{ $feature['metric_label'] }}
                                        </span>
                                    @endif
                                </div>
                            @endif
                            
                            {{-- Optional Learn More Link --}}
                            @if(!empty($feature['link_url']))
                                <a href="{{ $feature['link_url'] }}" 
                                   class="btn btn-outline-primary btn-sm mt-2 feature-link">
                                    {{ $feature['link_text'] ?? 'Learn More' }}
                                    <i class="fas fa-arrow-right ms-1"></i>
                                </a>
                            @endif
                        </div>
                        
                        {{-- Optional Badge --}}
                        @if(!empty($feature['badge_text']))
                            <div class="feature-badge position-absolute top-0 end-0 m-3">
                                <span class="badge bg-{{ $feature['badge_color'] ?? 'primary' }} rounded-pill">
                                    {{ $feature['badge_text'] }}
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
        
        {{-- Optional Call-to-Action --}}
        @if($config['show_cta'] ?? false)
            <div class="row mt-5">
                <div class="col-lg-8 mx-auto text-center">
                    <div class="features-cta p-4 rounded-3" 
                         style="background: {{ $config['cta_background'] ?? 'linear-gradient(135deg, #667eea, #764ba2)' }};">
                        <h3 class="text-white fw-bold mb-3">
                            {{ $config['cta_title'] ?? 'Ready to get started?' }}
                        </h3>
                        <p class="text-white opacity-75 mb-4">
                            {{ $config['cta_subtitle'] ?? 'Join thousands of satisfied customers today' }}
                        </p>
                        <a href="{{ $config['cta_url'] ?? '/contact' }}" 
                           class="btn btn-light btn-lg px-4 fw-semibold">
                            {{ $config['cta_button_text'] ?? 'Get Started Now' }}
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</section>

{{-- Inline Styles --}}
<style>
.features-section {
    position: relative;
}

.section-title {
    position: relative;
    margin-bottom: 1rem;
}

.section-title::after {
    content: '';
    position: absolute;
    bottom: -10px;
    left: 50%;
    transform: translateX(-50%);
    width: 60px;
    height: 3px;
    background: {{ $config['accent_color'] ?? 'linear-gradient(135deg, #667eea, #764ba2)' }};
    border-radius: 2px;
}

.feature-card {
    transition: all 0.3s ease;
    border: 1px solid rgba(0,0,0,0.05);
    position: relative;
    overflow: hidden;
}

.feature-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
    transition: left 0.5s ease;
}

.hover-lift:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.1) !important;
}

.hover-lift:hover::before {
    left: 100%;
}

.feature-icon .icon-wrapper {
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.feature-icon .icon-wrapper::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    background: rgba(255,255,255,0.3);
    border-radius: 50%;
    transition: all 0.3s ease;
    transform: translate(-50%, -50%);
}

.feature-card:hover .icon-wrapper {
    transform: scale(1.1) rotate(5deg);
}

.feature-card:hover .icon-wrapper::before {
    width: 100%;
    height: 100%;
}

.feature-image {
    transition: all 0.3s ease;
    border: 3px solid transparent;
    background-clip: padding-box;
}

.feature-card:hover .feature-image {
    transform: scale(1.1);
    border-color: {{ $config['accent_color'] ?? '#667eea' }};
}

.feature-title {
    transition: color 0.3s ease;
}

.feature-card:hover .feature-title {
    color: {{ $config['hover_color'] ?? '#667eea' }} !important;
}

.feature-link {
    transition: all 0.3s ease;
    border-radius: 25px;
    padding: 0.5rem 1.5rem;
}

.feature-link:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}

.features-cta {
    transition: all 0.3s ease;
}

.features-cta:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.2);
}

.metric-value {
    display: block;
    line-height: 1;
}

.feature-badge {
    z-index: 10;
}

/* Animation Classes */
.animate-on-scroll {
    opacity: 0;
    transform: translateY(30px);
    transition: all 0.6s ease;
}

.animate-on-scroll.animated {
    opacity: 1;
    transform: translateY(0);
}

/* Responsive Design */
@media (max-width: 768px) {
    .feature-card {
        margin-bottom: 2rem;
    }
    
    .section-title {
        font-size: 2rem !important;
    }
    
    .features-section .col-lg-4:nth-child(2n) .feature-card {
        margin-top: 0;
    }
}

@media (max-width: 576px) {
    .feature-icon .icon-wrapper {
        width: 60px !important;
        height: 60px !important;
    }
    
    .feature-icon .icon-wrapper i {
        font-size: 24px !important;
    }
    
    .section-title {
        font-size: 1.75rem !important;
    }
}

/* Dark mode support */
@media (prefers-color-scheme: dark) {
    .feature-card {
        background-color: #2d3748 !important;
        border-color: rgba(255,255,255,0.1) !important;
    }
    
    .feature-title {
        color: #ffffff !important;
    }
    
    .feature-description {
        color: #a0aec0 !important;
    }
}

/* High contrast mode support */
@media (prefers-contrast: high) {
    .feature-card {
        border: 2px solid #000 !important;
    }
    
    .feature-link {
        border: 2px solid currentColor !important;
    }
}
</style>

{{-- JavaScript for Animation and Interactions --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Intersection Observer for scroll animations
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
                entry.target.classList.add('animated');
            }
        });
    }, observerOptions);
    
    // Apply animation to feature cards
    document.querySelectorAll('.feature-card').forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(30px)';
        card.style.transition = `all 0.6s ease ${index * 0.1}s`;
        card.classList.add('animate-on-scroll');
        observer.observe(card);
    });
    
    // Add hover effects for feature cards
    document.querySelectorAll('.feature-card').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.zIndex = '10';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.zIndex = '1';
        });
    });
    
    // Feature link click tracking (optional)
    document.querySelectorAll('.feature-link').forEach(link => {
        link.addEventListener('click', function(e) {
            const featureTitle = this.closest('.feature-card').querySelector('.feature-title').textContent;
            console.log(`Feature link clicked: ${featureTitle}`);
            
            // Add analytics tracking here if needed
            // gtag('event', 'feature_click', { feature_name: featureTitle });
        });
    });
    
    // Keyboard navigation support
    document.querySelectorAll('.feature-card').forEach(card => {
        card.setAttribute('tabindex', '0');
        
        card.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                const link = this.querySelector('.feature-link');
                if (link) {
                    e.preventDefault();
                    link.click();
                }
            }
        });
    });
    
    // Dynamic column adjustment based on screen size
    function adjustColumns() {
        const container = document.querySelector('.features-section .row.g-4');
        const cards = container.querySelectorAll('.col-lg-4, .col-lg-3, .col-lg-6');
        const screenWidth = window.innerWidth;
        
        cards.forEach(card => {
            if (screenWidth < 768) {
                card.className = 'col-12';
            } else if (screenWidth < 992) {
                card.className = 'col-md-6';
            } else {
                const columns = {{ $config['columns'] ?? 3 }};
                card.className = `col-lg-${12 / columns} col-md-6`;
            }
        });
    }
    
    // Initial column adjustment
    adjustColumns();
    
    // Adjust columns on window resize
    window.addEventListener('resize', adjustColumns);
    
    // Preload feature images for better performance
    document.querySelectorAll('.feature-image').forEach(img => {
        const src = img.src;
        if (src) {
            const preloader = new Image();
            preloader.src = src;
        }
    });
});
</script>
