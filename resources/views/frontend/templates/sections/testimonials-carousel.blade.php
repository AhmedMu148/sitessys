{{-- Testimonials Carousel Section --}}
<section class="testimonials-section py-5" 
         style="background: {{ $config['background_gradient'] ?? 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)' }};">
    
    <div class="container">
        {{-- Section Header --}}
        <div class="row text-center mb-5">
            <div class="col-lg-8 mx-auto">
                <h2 class="section-title display-5 fw-bold text-white mb-3">
                    {{ $config['section_title'] ?? 'What Our Clients Say' }}
                </h2>
                <p class="section-subtitle lead text-white opacity-75 mb-0">
                    {{ $config['section_subtitle'] ?? 'Real feedback from real customers who trust our services' }}
                </p>
            </div>
        </div>
        
        {{-- Testimonials Carousel --}}
        <div id="testimonialsCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="{{ $config['autoplay_interval'] ?? 5000 }}">
            
            {{-- Carousel Indicators --}}
            @if(count($config['testimonials'] ?? []) > 1)
                <div class="carousel-indicators">
                    @foreach($config['testimonials'] ?? [] as $index => $testimonial)
                        <button type="button" 
                                data-bs-target="#testimonialsCarousel" 
                                data-bs-slide-to="{{ $index }}" 
                                class="{{ $index === 0 ? 'active' : '' }}"
                                aria-current="{{ $index === 0 ? 'true' : 'false' }}"
                                aria-label="Slide {{ $index + 1 }}">
                        </button>
                    @endforeach
                </div>
            @endif
            
            {{-- Carousel Inner --}}
            <div class="carousel-inner">
                @foreach($config['testimonials'] ?? [] as $index => $testimonial)
                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                        <div class="row justify-content-center">
                            <div class="col-lg-8 col-xl-7">
                                <div class="testimonial-card bg-white rounded-4 p-5 shadow-lg text-center position-relative">
                                    
                                    {{-- Quote Icon --}}
                                    <div class="quote-icon mb-4">
                                        <i class="fas fa-quote-left fs-1 opacity-25"
                                           style="color: {{ $config['quote_color'] ?? '#667eea' }};"></i>
                                    </div>
                                    
                                    {{-- Testimonial Content --}}
                                    <blockquote class="testimonial-quote mb-4 fs-5 text-muted fst-italic" 
                                                style="line-height: 1.8; font-weight: 400;">
                                        "{{ $testimonial['quote'] ?? 'Amazing service and great results! Highly recommended for anyone looking for professional solutions.' }}"
                                    </blockquote>
                                    
                                    {{-- Rating (if provided) --}}
                                    @if(!empty($testimonial['rating']))
                                        <div class="testimonial-rating mb-4">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star {{ $i <= ($testimonial['rating'] ?? 5) ? 'text-warning' : 'text-muted' }}" 
                                                   style="font-size: 1.25rem;"></i>
                                            @endfor
                                        </div>
                                    @endif
                                    
                                    {{-- Author Information --}}
                                    <div class="testimonial-author">
                                        {{-- Avatar --}}
                                        @if(!empty($testimonial['avatar']))
                                            <img src="{{ $testimonial['avatar'] }}" 
                                                 alt="{{ $testimonial['name'] ?? 'Client' }}" 
                                                 class="testimonial-avatar rounded-circle mb-3 border border-3"
                                                 style="width: 80px; height: 80px; object-fit: cover; border-color: {{ $config['avatar_border_color'] ?? '#667eea' }} !important;"
                                                 loading="lazy">
                                        @else
                                            <div class="testimonial-avatar-placeholder rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center"
                                                 style="width: 80px; height: 80px; background: {{ $config['avatar_bg'] ?? 'linear-gradient(135deg, #667eea, #764ba2)' }};">
                                                <i class="fas fa-user text-white fs-3"></i>
                                            </div>
                                        @endif
                                        
                                        {{-- Name and Title --}}
                                        <h5 class="author-name fw-bold mb-1 text-dark">
                                            {{ $testimonial['name'] ?? 'Client Name' }}
                                        </h5>
                                        
                                        {{-- Position and Company --}}
                                        <p class="author-details text-muted mb-0">
                                            <span class="position">{{ $testimonial['position'] ?? 'Position' }}</span>
                                            @if(!empty($testimonial['company']))
                                                <span class="company">at <strong>{{ $testimonial['company'] }}</strong></span>
                                            @endif
                                        </p>
                                        
                                        {{-- Optional Social Proof --}}
                                        @if(!empty($testimonial['social_proof']))
                                            <p class="social-proof text-muted small mt-2 mb-0">
                                                <i class="fas fa-check-circle text-success me-1"></i>
                                                {{ $testimonial['social_proof'] }}
                                            </p>
                                        @endif
                                    </div>
                                    
                                    {{-- Optional Company Logo --}}
                                    @if(!empty($testimonial['company_logo']))
                                        <div class="company-logo mt-4">
                                            <img src="{{ $testimonial['company_logo'] }}" 
                                                 alt="{{ $testimonial['company'] ?? 'Company' }}" 
                                                 style="height: 40px; opacity: 0.7; filter: grayscale(100%);"
                                                 loading="lazy">
                                        </div>
                                    @endif
                                    
                                    {{-- Decorative Elements --}}
                                    <div class="testimonial-decoration position-absolute top-0 start-0 w-100 h-100 pointer-events-none">
                                        <div class="decoration-dot decoration-dot-1"></div>
                                        <div class="decoration-dot decoration-dot-2"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            {{-- Carousel Controls --}}
            @if(count($config['testimonials'] ?? []) > 1)
                <button class="carousel-control-prev" type="button" data-bs-target="#testimonialsCarousel" data-bs-slide="prev">
                    <div class="carousel-control-icon carousel-control-prev-icon bg-white text-dark rounded-circle d-flex align-items-center justify-content-center"
                         style="width: 50px; height: 50px; position: static;">
                        <i class="fas fa-chevron-left"></i>
                    </div>
                    <span class="visually-hidden">Previous</span>
                </button>
                
                <button class="carousel-control-next" type="button" data-bs-target="#testimonialsCarousel" data-bs-slide="next">
                    <div class="carousel-control-icon carousel-control-next-icon bg-white text-dark rounded-circle d-flex align-items-center justify-content-center"
                         style="width: 50px; height: 50px; position: static;">
                        <i class="fas fa-chevron-right"></i>
                    </div>
                    <span class="visually-hidden">Next</span>
                </button>
            @endif
        </div>
        
        {{-- Optional Stats Section --}}
        @if($config['show_stats'] ?? false)
            <div class="testimonials-stats mt-5">
                <div class="row text-center text-white">
                    @foreach($config['stats'] ?? [] as $stat)
                        <div class="col-md-3 col-6">
                            <div class="stat-item">
                                <div class="stat-number fs-1 fw-bold mb-2">{{ $stat['number'] ?? '0' }}</div>
                                <div class="stat-label opacity-75">{{ $stat['label'] ?? 'Stat' }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</section>

{{-- Inline Styles --}}
<style>
.testimonials-section {
    position: relative;
    overflow: hidden;
}

.testimonials-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Ccircle cx='30' cy='30' r='4'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    z-index: 1;
}

.testimonials-section .container {
    position: relative;
    z-index: 2;
}

.section-title {
    text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
}

.testimonial-card {
    transition: all 0.3s ease;
    border: none;
    backdrop-filter: blur(10px);
    position: relative;
    overflow: hidden;
}

.testimonial-card::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: conic-gradient(from 0deg, transparent, rgba(102, 126, 234, 0.1), transparent);
    animation: rotate 10s linear infinite;
    z-index: -1;
}

@keyframes rotate {
    100% {
        transform: rotate(360deg);
    }
}

.quote-icon {
    position: relative;
}

.testimonial-quote {
    position: relative;
    z-index: 2;
    font-family: 'Georgia', serif;
}

.testimonial-avatar {
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.testimonial-avatar:hover,
.testimonial-avatar-placeholder:hover {
    transform: scale(1.1);
    box-shadow: 0 8px 25px rgba(0,0,0,0.2);
}

.author-name {
    font-size: 1.25rem;
}

.author-details {
    font-size: 0.95rem;
}

.testimonial-rating {
    filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));
}

.company-logo img {
    transition: all 0.3s ease;
}

.testimonial-card:hover .company-logo img {
    filter: grayscale(0%) !important;
    opacity: 1 !important;
}

/* Carousel Controls Styling */
.carousel-control-prev,
.carousel-control-next {
    width: auto;
    top: 50%;
    transform: translateY(-50%);
}

.carousel-control-prev {
    left: -80px;
}

.carousel-control-next {
    right: -80px;
}

.carousel-control-icon {
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.carousel-control-icon:hover {
    transform: scale(1.1);
    box-shadow: 0 8px 25px rgba(0,0,0,0.2);
}

/* Carousel Indicators */
.carousel-indicators {
    bottom: -60px;
}

.carousel-indicators button {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    margin: 0 5px;
    background-color: rgba(255,255,255,0.5);
    border: 2px solid transparent;
    transition: all 0.3s ease;
}

.carousel-indicators button.active {
    background-color: white;
    transform: scale(1.2);
}

/* Decorative Elements */
.decoration-dot {
    position: absolute;
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea, #764ba2);
    opacity: 0.3;
}

.decoration-dot-1 {
    top: 20px;
    right: 20px;
    animation: float 3s ease-in-out infinite;
}

.decoration-dot-2 {
    bottom: 20px;
    left: 20px;
    animation: float 3s ease-in-out infinite reverse;
}

@keyframes float {
    0%, 100% {
        transform: translateY(0px);
    }
    50% {
        transform: translateY(-10px);
    }
}

/* Stats Section */
.testimonials-stats .stat-item {
    padding: 1rem;
}

.stat-number {
    text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
    background: linear-gradient(135deg, #ffffff, rgba(255,255,255,0.8));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

/* Responsive Design */
@media (max-width: 992px) {
    .carousel-control-prev {
        left: -60px;
    }
    
    .carousel-control-next {
        right: -60px;
    }
}

@media (max-width: 768px) {
    .carousel-control-prev,
    .carousel-control-next {
        display: none;
    }
    
    .testimonial-card {
        margin-bottom: 2rem;
    }
    
    .section-title {
        font-size: 2rem !important;
    }
    
    .testimonial-quote {
        font-size: 1.1rem !important;
    }
    
    .carousel-indicators {
        position: static;
        margin-top: 2rem;
    }
}

@media (max-width: 576px) {
    .testimonial-card {
        padding: 2rem !important;
    }
    
    .testimonial-avatar,
    .testimonial-avatar-placeholder {
        width: 60px !important;
        height: 60px !important;
    }
    
    .quote-icon i {
        font-size: 2rem !important;
    }
}

/* Dark mode support */
@media (prefers-color-scheme: dark) {
    .testimonial-card {
        background-color: rgba(45, 55, 72, 0.9) !important;
        color: #ffffff !important;
    }
    
    .author-name {
        color: #ffffff !important;
    }
    
    .testimonial-quote {
        color: #e2e8f0 !important;
    }
}

/* Animation for carousel transition */
.carousel-item {
    transition: transform 0.6s ease-in-out;
}

.carousel-item-next,
.carousel-item-prev,
.carousel-item.active {
    transform: translateX(0);
}

.carousel-item-start {
    transform: translateX(-100%);
}

.carousel-item-end {
    transform: translateX(100%);
}

/* Accessibility improvements */
.carousel-control-prev:focus,
.carousel-control-next:focus {
    outline: 2px solid #ffffff;
    outline-offset: 2px;
}

.carousel-indicators button:focus {
    outline: 2px solid #ffffff;
    outline-offset: 2px;
}

/* Reduced motion support */
@media (prefers-reduced-motion: reduce) {
    .testimonial-card::before {
        animation: none !important;
    }
    
    .decoration-dot-1,
    .decoration-dot-2 {
        animation: none !important;
    }
    
    .carousel-item {
        transition: none !important;
    }
}
</style>

{{-- JavaScript for Enhanced Functionality --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const carousel = document.getElementById('testimonialsCarousel');
    
    if (carousel) {
        // Initialize Bootstrap carousel
        const testimonialCarousel = new bootstrap.Carousel(carousel, {
            interval: {{ $config['autoplay_interval'] ?? 5000 }},
            wrap: true,
            keyboard: true,
            pause: 'hover',
            ride: 'carousel'
        });
        
        // Pause on focus for accessibility
        carousel.addEventListener('focusin', function() {
            testimonialCarousel.pause();
        });
        
        carousel.addEventListener('focusout', function() {
            testimonialCarousel.cycle();
        });
        
        // Track carousel events
        carousel.addEventListener('slide.bs.carousel', function(event) {
            const currentSlide = event.to;
            console.log(`Testimonial slide changed to: ${currentSlide + 1}`);
            
            // Add analytics tracking here if needed
            // gtag('event', 'testimonial_view', { slide_number: currentSlide + 1 });
        });
        
        // Add swipe support for mobile devices
        let startX = 0;
        let endX = 0;
        
        carousel.addEventListener('touchstart', function(e) {
            startX = e.touches[0].clientX;
        });
        
        carousel.addEventListener('touchend', function(e) {
            endX = e.changedTouches[0].clientX;
            handleSwipe();
        });
        
        function handleSwipe() {
            const threshold = 50;
            const diff = startX - endX;
            
            if (Math.abs(diff) > threshold) {
                if (diff > 0) {
                    // Swipe left - next slide
                    testimonialCarousel.next();
                } else {
                    // Swipe right - previous slide
                    testimonialCarousel.prev();
                }
            }
        }
        
        // Lazy loading for testimonial avatars
        const avatarImages = carousel.querySelectorAll('.testimonial-avatar');
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    if (img.dataset.src) {
                        img.src = img.dataset.src;
                        img.removeAttribute('data-src');
                        observer.unobserve(img);
                    }
                }
            });
        });
        
        avatarImages.forEach(img => {
            if (img.dataset.src) {
                imageObserver.observe(img);
            }
        });
        
        // Auto-adjust height for consistent card sizes
        function adjustCardHeights() {
            const cards = carousel.querySelectorAll('.testimonial-card');
            let maxHeight = 0;
            
            cards.forEach(card => {
                card.style.height = 'auto';
                const cardHeight = card.offsetHeight;
                maxHeight = Math.max(maxHeight, cardHeight);
            });
            
            cards.forEach(card => {
                card.style.height = maxHeight + 'px';
            });
        }
        
        // Adjust heights on load and resize
        window.addEventListener('load', adjustCardHeights);
        window.addEventListener('resize', adjustCardHeights);
        
        // Add keyboard navigation
        document.addEventListener('keydown', function(e) {
            if (document.activeElement.closest('.testimonials-section')) {
                if (e.key === 'ArrowLeft') {
                    e.preventDefault();
                    testimonialCarousel.prev();
                } else if (e.key === 'ArrowRight') {
                    e.preventDefault();
                    testimonialCarousel.next();
                }
            }
        });
    }
    
    // Animate stats numbers if visible
    const statsSection = document.querySelector('.testimonials-stats');
    if (statsSection) {
        const statsObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    animateStats();
                    statsObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.5 });
        
        statsObserver.observe(statsSection);
    }
    
    function animateStats() {
        const statNumbers = document.querySelectorAll('.stat-number');
        
        statNumbers.forEach(stat => {
            const target = parseInt(stat.textContent.replace(/\D/g, ''));
            let current = 0;
            const increment = target / 100;
            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    current = target;
                    clearInterval(timer);
                }
                stat.textContent = Math.floor(current).toLocaleString();
            }, 20);
        });
    }
});
</script>
