{{-- Call-to-Action Section with Gradient Background --}}
<section class="cta-section position-relative overflow-hidden py-5" 
         style="background: {{ $config['gradient_background'] ?? 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)' }};">
    
    {{-- Background Elements --}}
    <div class="cta-background position-absolute w-100 h-100 top-0 start-0">
        {{-- Animated Background Shapes --}}
        @if($config['show_background_shapes'] ?? true)
            <div class="bg-shape shape-1 position-absolute rounded-circle opacity-10"></div>
            <div class="bg-shape shape-2 position-absolute rounded-circle opacity-5"></div>
            <div class="bg-shape shape-3 position-absolute opacity-15"></div>
            <div class="bg-shape shape-4 position-absolute opacity-10"></div>
        @endif
        
        {{-- Overlay --}}
        <div class="overlay position-absolute w-100 h-100 top-0 start-0" 
             style="background: rgba(0,0,0,{{ $config['overlay_opacity'] ?? '0.2' }});"></div>
    </div>
    
    <div class="container position-relative">
        <div class="row align-items-center min-vh-50">
            
            {{-- Content Column --}}
            <div class="col-lg-{{ $config['layout'] === 'centered' ? '8 mx-auto text-center' : '8' }}">
                
                {{-- Pre-title Badge (Optional) --}}
                @if(!empty($config['pre_title']))
                    <div class="pre-title mb-3" data-aos="fade-up">
                        <span class="badge bg-white bg-opacity-25 text-white px-3 py-2 rounded-pill fw-semibold">
                            {{ $config['pre_title'] }}
                        </span>
                    </div>
                @endif
                
                {{-- Main Headline --}}
                <h2 class="cta-headline display-4 fw-bold text-white mb-4" 
                    data-aos="fade-up" 
                    data-aos-delay="100">
                    {!! $config['headline'] ?? 'Ready to Get Started?' !!}
                </h2>
                
                {{-- Subheadline --}}
                @if(!empty($config['subheadline']))
                    <p class="cta-subheadline lead text-white text-opacity-90 mb-4" 
                       data-aos="fade-up" 
                       data-aos-delay="200"
                       style="font-size: {{ $config['subheadline_size'] ?? '1.25rem' }};">
                        {{ $config['subheadline'] }}
                    </p>
                @endif
                
                {{-- Features List (Optional) --}}
                @if(!empty($config['features']) && ($config['show_features'] ?? false))
                    <div class="cta-features mb-4" data-aos="fade-up" data-aos-delay="300">
                        <div class="row g-3 {{ $config['layout'] === 'centered' ? 'justify-content-center' : '' }}">
                            @foreach($config['features'] as $index => $feature)
                                <div class="col-md-{{ $config['layout'] === 'centered' ? '4' : '6' }}">
                                    <div class="feature-item d-flex align-items-center text-white">
                                        @if(!empty($feature['icon']))
                                            <i class="{{ $feature['icon'] }} text-white me-3 fs-5"></i>
                                        @else
                                            <i class="fas fa-check-circle text-white me-3"></i>
                                        @endif
                                        <span class="fw-semibold">{{ $feature['text'] ?? $feature }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
                
                {{-- Action Buttons --}}
                <div class="cta-actions d-flex flex-wrap gap-3 {{ $config['layout'] === 'centered' ? 'justify-content-center' : '' }}" 
                     data-aos="fade-up" 
                     data-aos-delay="{{ !empty($config['features']) ? '400' : '300' }}">
                    
                    {{-- Primary Button --}}
                    <a href="{{ $config['primary_button']['url'] ?? '#' }}" 
                       class="btn btn-{{ $config['primary_button']['style'] ?? 'light' }} btn-lg px-4 py-3 fw-semibold cta-btn-primary"
                       @if(!empty($config['primary_button']['target'])) target="{{ $config['primary_button']['target'] }}" @endif>
                        {{ $config['primary_button']['text'] ?? 'Get Started Now' }}
                        @if(!empty($config['primary_button']['icon']))
                            <i class="{{ $config['primary_button']['icon'] }} ms-2"></i>
                        @endif
                    </a>
                    
                    {{-- Secondary Button (Optional) --}}
                    @if(!empty($config['secondary_button']))
                        <a href="{{ $config['secondary_button']['url'] ?? '#' }}" 
                           class="btn btn-{{ $config['secondary_button']['style'] ?? 'outline-light' }} btn-lg px-4 py-3 fw-semibold cta-btn-secondary"
                           @if(!empty($config['secondary_button']['target'])) target="{{ $config['secondary_button']['target'] }}" @endif>
                            {{ $config['secondary_button']['text'] ?? 'Learn More' }}
                            @if(!empty($config['secondary_button']['icon']))
                                <i class="{{ $config['secondary_button']['icon'] }} ms-2"></i>
                            @endif
                        </a>
                    @endif
                    
                    {{-- Video/Demo Button (Optional) --}}
                    @if(!empty($config['video_button']))
                        <button type="button" 
                                class="btn btn-outline-light btn-lg px-4 py-3 fw-semibold cta-btn-video" 
                                data-bs-toggle="modal" 
                                data-bs-target="#videoModal">
                            <i class="fas fa-play-circle me-2"></i>
                            {{ $config['video_button']['text'] ?? 'Watch Demo' }}
                        </button>
                    @endif
                </div>
                
                {{-- Trust Indicators (Optional) --}}
                @if(!empty($config['trust_indicators']) && ($config['show_trust_indicators'] ?? false))
                    <div class="trust-indicators mt-5" data-aos="fade-up" data-aos-delay="500">
                        <div class="row g-4 align-items-center {{ $config['layout'] === 'centered' ? 'justify-content-center' : '' }}">
                            @foreach($config['trust_indicators'] as $indicator)
                                <div class="col-md-{{ $config['layout'] === 'centered' ? '3' : '4' }} col-6">
                                    <div class="trust-item text-center text-white text-opacity-75">
                                        @if(!empty($indicator['icon']))
                                            <i class="{{ $indicator['icon'] }} fs-2 mb-2"></i>
                                        @endif
                                        @if(!empty($indicator['number']))
                                            <div class="trust-number h4 fw-bold text-white mb-1">
                                                {{ $indicator['number'] }}
                                            </div>
                                        @endif
                                        <div class="trust-text small">
                                            {{ $indicator['text'] ?? $indicator }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
                
                {{-- Urgency/Scarcity Element (Optional) --}}
                @if(!empty($config['urgency']) && ($config['show_urgency'] ?? false))
                    <div class="urgency-element mt-4 p-3 bg-warning bg-opacity-25 rounded text-center" 
                         data-aos="fade-up" 
                         data-aos-delay="600">
                        <div class="d-flex align-items-center justify-content-center text-white">
                            <i class="fas fa-clock me-2"></i>
                            <span class="fw-semibold">{{ $config['urgency']['text'] ?? 'Limited Time Offer!' }}</span>
                            @if(!empty($config['urgency']['countdown']))
                                <span class="countdown ms-3 fw-bold" data-countdown="{{ $config['urgency']['countdown'] }}"></span>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
            
            {{-- Visual Element Column (Optional) --}}
            @if(!empty($config['visual_element']) && $config['layout'] !== 'centered')
                <div class="col-lg-4" data-aos="fade-left" data-aos-delay="400">
                    <div class="cta-visual position-relative">
                        @if($config['visual_element']['type'] === 'image')
                            <img src="{{ $config['visual_element']['src'] ?? '/img/cta-visual.png' }}" 
                                 alt="{{ $config['visual_element']['alt'] ?? 'Visual' }}" 
                                 class="img-fluid rounded shadow-lg">
                        @elseif($config['visual_element']['type'] === 'video')
                            <div class="video-container rounded overflow-hidden shadow-lg">
                                <video autoplay muted loop class="w-100">
                                    <source src="{{ $config['visual_element']['src'] }}" type="video/mp4">
                                </video>
                            </div>
                        @elseif($config['visual_element']['type'] === 'stats')
                            <div class="stats-container bg-white bg-opacity-15 p-4 rounded shadow-lg">
                                @foreach($config['visual_element']['stats'] as $stat)
                                    <div class="stat-item text-center mb-3">
                                        <div class="stat-number display-5 fw-bold text-white">
                                            {{ $stat['number'] }}
                                        </div>
                                        <div class="stat-label text-white text-opacity-75">
                                            {{ $stat['label'] }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
    
    {{-- Scroll Down Arrow (Optional) --}}
    @if($config['show_scroll_arrow'] ?? false)
        <div class="scroll-indicator position-absolute bottom-0 start-50 translate-middle-x pb-4">
            <a href="#next-section" class="text-white text-decoration-none">
                <i class="fas fa-chevron-down fs-4 bounce-animation"></i>
            </a>
        </div>
    @endif
</section>

{{-- Video Modal (If video button is enabled) --}}
@if(!empty($config['video_button']))
    <div class="modal fade" id="videoModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content bg-dark border-0">
                <div class="modal-header border-0">
                    <h5 class="modal-title text-white">{{ $config['video_button']['modal_title'] ?? 'Product Demo' }}</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-0">
                    <div class="ratio ratio-16x9">
                        @if(!empty($config['video_button']['embed_code']))
                            {!! $config['video_button']['embed_code'] !!}
                        @else
                            <iframe src="{{ $config['video_button']['video_url'] ?? '' }}" 
                                    title="Video Demo" 
                                    allowfullscreen>
                            </iframe>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

{{-- Inline Styles --}}
<style>
.cta-section {
    min-height: {{ $config['min_height'] ?? '60vh' }};
    position: relative;
}

/* Background Shapes Animation */
.bg-shape {
    pointer-events: none;
}

.shape-1 {
    width: 200px;
    height: 200px;
    background: rgba(255,255,255,0.1);
    top: 10%;
    right: 10%;
    animation: float 6s ease-in-out infinite;
}

.shape-2 {
    width: 150px;
    height: 150px;
    background: rgba(255,255,255,0.05);
    top: 60%;
    left: 5%;
    animation: float 8s ease-in-out infinite reverse;
}

.shape-3 {
    width: 300px;
    height: 100px;
    background: rgba(255,255,255,0.03);
    clip-path: polygon(50% 0%, 0% 100%, 100% 100%);
    top: 30%;
    left: 20%;
    animation: rotate 10s linear infinite;
}

.shape-4 {
    width: 120px;
    height: 120px;
    background: rgba(255,255,255,0.08);
    border-radius: 30%;
    bottom: 20%;
    right: 30%;
    animation: pulse 4s ease-in-out infinite;
}

@keyframes float {
    0%, 100% {
        transform: translateY(0px) rotate(0deg);
    }
    50% {
        transform: translateY(-20px) rotate(10deg);
    }
}

@keyframes rotate {
    0% {
        transform: rotate(0deg);
    }
    100% {
        transform: rotate(360deg);
    }
}

@keyframes pulse {
    0%, 100% {
        transform: scale(1);
        opacity: 0.1;
    }
    50% {
        transform: scale(1.1);
        opacity: 0.2;
    }
}

/* Typography */
.cta-headline {
    text-shadow: 0 2px 4px rgba(0,0,0,0.3);
    line-height: 1.2;
}

.cta-subheadline {
    text-shadow: 0 1px 2px rgba(0,0,0,0.2);
    max-width: 600px;
    margin-left: auto;
    margin-right: auto;
}

/* Button Styles */
.cta-btn-primary {
    transition: all 0.3s ease;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-radius: 50px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    position: relative;
    overflow: hidden;
}

.cta-btn-primary::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    transition: left 0.5s;
}

.cta-btn-primary:hover::before {
    left: 100%;
}

.cta-btn-primary:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.3);
}

.cta-btn-secondary {
    transition: all 0.3s ease;
    border-radius: 50px;
    backdrop-filter: blur(10px);
    border-width: 2px;
}

.cta-btn-secondary:hover {
    background-color: rgba(255,255,255,0.2);
    transform: translateY(-2px);
}

.cta-btn-video {
    transition: all 0.3s ease;
    border-radius: 50px;
    backdrop-filter: blur(10px);
}

.cta-btn-video:hover {
    background-color: rgba(255,255,255,0.1);
    transform: scale(1.05);
}

/* Feature Items */
.feature-item {
    transition: all 0.3s ease;
    padding: 0.5rem;
    border-radius: 8px;
}

.feature-item:hover {
    background-color: rgba(255,255,255,0.1);
    transform: translateX(5px);
}

/* Trust Indicators */
.trust-item {
    transition: all 0.3s ease;
    padding: 1rem;
    border-radius: 8px;
}

.trust-item:hover {
    background-color: rgba(255,255,255,0.1);
    transform: translateY(-5px);
}

.trust-number {
    background: linear-gradient(45deg, #fff, #f0f0f0);
    -webkit-background-clip: text;
    background-clip: text;
    -webkit-text-fill-color: transparent;
}

/* Urgency Element */
.urgency-element {
    border: 2px solid rgba(255,193,7,0.3);
    backdrop-filter: blur(10px);
    animation: urgencyPulse 2s infinite;
}

@keyframes urgencyPulse {
    0%, 100% {
        border-color: rgba(255,193,7,0.3);
        box-shadow: 0 0 0 0 rgba(255,193,7,0.4);
    }
    50% {
        border-color: rgba(255,193,7,0.6);
        box-shadow: 0 0 0 10px rgba(255,193,7,0);
    }
}

/* Countdown Timer */
.countdown {
    font-family: 'Courier New', monospace;
    background: rgba(255,255,255,0.2);
    padding: 0.25rem 0.75rem;
    border-radius: 4px;
    backdrop-filter: blur(5px);
}

/* Scroll Indicator */
.scroll-indicator {
    z-index: 10;
}

.bounce-animation {
    animation: bounce 2s infinite;
}

@keyframes bounce {
    0%, 20%, 50%, 80%, 100% {
        transform: translateY(0);
    }
    40% {
        transform: translateY(-10px);
    }
    60% {
        transform: translateY(-5px);
    }
}

/* Visual Elements */
.cta-visual img,
.video-container {
    transition: all 0.3s ease;
}

.cta-visual:hover img,
.cta-visual:hover .video-container {
    transform: translateY(-10px) scale(1.02);
}

.stats-container {
    backdrop-filter: blur(15px);
    border: 1px solid rgba(255,255,255,0.2);
}

/* Responsive Design */
@media (max-width: 992px) {
    .cta-headline {
        font-size: 2.5rem !important;
    }
    
    .cta-actions {
        justify-content: center !important;
    }
    
    .trust-indicators .col-md-3,
    .trust-indicators .col-md-4 {
        margin-bottom: 1rem;
    }
}

@media (max-width: 768px) {
    .cta-section {
        padding: 3rem 0 !important;
    }
    
    .cta-headline {
        font-size: 2rem !important;
    }
    
    .cta-subheadline {
        font-size: 1.1rem !important;
    }
    
    .btn-lg {
        padding: 0.75rem 2rem !important;
        font-size: 1rem !important;
    }
    
    .cta-actions {
        flex-direction: column;
        align-items: stretch;
    }
    
    .cta-features .col-md-4,
    .cta-features .col-md-6 {
        margin-bottom: 1rem;
    }
    
    .bg-shape {
        display: none;
    }
}

@media (max-width: 576px) {
    .container {
        padding: 0 1rem;
    }
    
    .cta-headline {
        font-size: 1.75rem !important;
    }
    
    .trust-indicators {
        margin-top: 2rem !important;
    }
    
    .urgency-element {
        margin-top: 2rem !important;
        padding: 1rem !important;
    }
}

/* High contrast mode */
@media (prefers-contrast: high) {
    .cta-section {
        background: #000 !important;
    }
    
    .text-white {
        color: #fff !important;
    }
    
    .btn {
        border: 3px solid currentColor !important;
    }
}

/* Reduced motion */
@media (prefers-reduced-motion: reduce) {
    .bg-shape,
    .bounce-animation,
    .urgency-element {
        animation: none !important;
    }
    
    .cta-btn-primary,
    .cta-btn-secondary,
    .feature-item,
    .trust-item {
        transition: none !important;
    }
}

/* Print styles */
@media print {
    .cta-section {
        background: #f8f9fa !important;
        color: #000 !important;
    }
    
    .bg-shape,
    .scroll-indicator {
        display: none !important;
    }
}
</style>

{{-- JavaScript for Enhanced Functionality --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Countdown Timer functionality
    const countdownElements = document.querySelectorAll('[data-countdown]');
    
    countdownElements.forEach(element => {
        const targetDate = new Date(element.dataset.countdown).getTime();
        
        function updateCountdown() {
            const now = new Date().getTime();
            const distance = targetDate - now;
            
            if (distance > 0) {
                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                
                element.innerHTML = `${days}d ${hours}h ${minutes}m ${seconds}s`;
            } else {
                element.innerHTML = 'EXPIRED';
                element.closest('.urgency-element').style.display = 'none';
            }
        }
        
        updateCountdown();
        setInterval(updateCountdown, 1000);
    });
    
    // Button click tracking
    const ctaButtons = document.querySelectorAll('.cta-btn-primary, .cta-btn-secondary');
    ctaButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            const buttonText = this.textContent.trim();
            const buttonType = this.classList.contains('cta-btn-primary') ? 'primary' : 'secondary';
            
            console.log(`CTA button clicked: ${buttonType} - ${buttonText}`);
            
            // Add analytics tracking here if needed
            // gtag('event', 'cta_click', {
            //     button_type: buttonType,
            //     button_text: buttonText
            // });
        });
    });
    
    // Video modal handling
    const videoModal = document.getElementById('videoModal');
    if (videoModal) {
        videoModal.addEventListener('hidden.bs.modal', function() {
            // Pause video when modal is closed
            const iframe = this.querySelector('iframe');
            if (iframe) {
                const src = iframe.src;
                iframe.src = '';
                iframe.src = src;
            }
            
            const video = this.querySelector('video');
            if (video) {
                video.pause();
                video.currentTime = 0;
            }
        });
    }
    
    // Parallax effect for background shapes
    function parallaxEffect() {
        const scrolled = window.pageYOffset;
        const parallaxElements = document.querySelectorAll('.bg-shape');
        const speed = 0.5;
        
        parallaxElements.forEach((element, index) => {
            const yPos = -(scrolled * speed * (index + 1) * 0.3);
            element.style.transform = `translate3d(0, ${yPos}px, 0)`;
        });
    }
    
    // Apply parallax on scroll (only if not reduced motion)
    if (!window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        window.addEventListener('scroll', throttle(parallaxEffect, 10));
    }
    
    // Intersection Observer for animations
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate');
                
                // Trigger number counting animation for trust indicators
                const numbers = entry.target.querySelectorAll('.trust-number, .stat-number');
                numbers.forEach(numberElement => {
                    animateNumber(numberElement);
                });
            }
        });
    }, observerOptions);
    
    // Observe CTA section for animations
    const ctaSection = document.querySelector('.cta-section');
    if (ctaSection) {
        observer.observe(ctaSection);
    }
    
    // Number counting animation
    function animateNumber(element) {
        const finalNumber = element.textContent.replace(/[^\d.-]/g, '');
        const duration = 2000;
        const start = performance.now();
        const initialValue = 0;
        
        function updateNumber(currentTime) {
            const elapsed = currentTime - start;
            const progress = Math.min(elapsed / duration, 1);
            
            const currentNumber = Math.floor(initialValue + (finalNumber - initialValue) * easeOutCubic(progress));
            element.textContent = element.textContent.replace(/\d+/, currentNumber);
            
            if (progress < 1) {
                requestAnimationFrame(updateNumber);
            }
        }
        
        requestAnimationFrame(updateNumber);
    }
    
    // Easing function
    function easeOutCubic(t) {
        return 1 - Math.pow(1 - t, 3);
    }
    
    // Throttle function for performance
    function throttle(func, limit) {
        let inThrottle;
        return function() {
            const args = arguments;
            const context = this;
            if (!inThrottle) {
                func.apply(context, args);
                inThrottle = true;
                setTimeout(() => inThrottle = false, limit);
            }
        }
    }
    
    // Smooth scroll for scroll indicator
    const scrollIndicator = document.querySelector('.scroll-indicator a');
    if (scrollIndicator) {
        scrollIndicator.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');
            const targetElement = document.querySelector(targetId);
            
            if (targetElement) {
                targetElement.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    }
    
    // Keyboard navigation enhancements
    document.addEventListener('keydown', function(e) {
        // ESC key to close video modal
        if (e.key === 'Escape' && videoModal && videoModal.classList.contains('show')) {
            const modal = bootstrap.Modal.getInstance(videoModal);
            if (modal) {
                modal.hide();
            }
        }
    });
    
    // Touch gestures for mobile
    let touchStartX = 0;
    let touchStartY = 0;
    
    document.addEventListener('touchstart', function(e) {
        touchStartX = e.touches[0].clientX;
        touchStartY = e.touches[0].clientY;
    });
    
    document.addEventListener('touchend', function(e) {
        if (!touchStartX || !touchStartY) return;
        
        const touchEndX = e.changedTouches[0].clientX;
        const touchEndY = e.changedTouches[0].clientY;
        
        const diffX = touchStartX - touchEndX;
        const diffY = touchStartY - touchEndY;
        
        // Horizontal swipe detection (for future carousel features)
        if (Math.abs(diffX) > Math.abs(diffY) && Math.abs(diffX) > 50) {
            if (diffX > 0) {
                console.log('Swipe left detected');
            } else {
                console.log('Swipe right detected');
            }
        }
        
        touchStartX = 0;
        touchStartY = 0;
    });
    
    // Resize handler for responsive adjustments
    window.addEventListener('resize', debounce(function() {
        // Recalculate any size-dependent features
        console.log('Window resized, adjusting CTA section');
    }, 250));
    
    // Debounce function
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
});
</script>
