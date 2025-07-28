{{-- Hero Section with Video Background --}}
<section class="hero-video-bg position-relative overflow-hidden d-flex align-items-center" 
         style="min-height: 100vh; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
    
    {{-- Background Video --}}
    @if(!empty($config['video_url']))
        <video autoplay muted loop playsinline class="position-absolute w-100 h-100" 
               style="object-fit: cover; z-index: 1; top: 0; left: 0;">
            <source src="{{ $config['video_url'] }}" type="video/mp4">
            Your browser does not support the video tag.
        </video>
    @endif
    
    {{-- Overlay --}}
    <div class="hero-overlay position-absolute w-100 h-100" 
         style="background: rgba(0,0,0,{{ $config['overlay_opacity'] ?? '0.5' }}); z-index: 2; top: 0; left: 0;"></div>
    
    {{-- Content --}}
    <div class="container position-relative" style="z-index: 3;">
        <div class="row justify-content-center">
            <div class="col-lg-10 text-{{ $config['text_alignment'] ?? 'center' }}">
                
                {{-- Main Title --}}
                <h1 class="hero-title display-1 fw-bold text-white mb-4 animate__animated animate__fadeInUp">
                    {{ $config['hero_title'] ?? 'Welcome to Our Amazing Service' }}
                </h1>
                
                {{-- Subtitle --}}
                <p class="hero-subtitle lead text-white mb-5 animate__animated animate__fadeInUp animate__delay-1s" 
                   style="font-size: 1.25rem; line-height: 1.6; max-width: 600px; margin-left: auto; margin-right: auto;">
                    {{ $config['hero_subtitle'] ?? 'We provide innovative solutions to help your business grow and succeed in the digital world with cutting-edge technology and expert support.' }}
                </p>
                
                {{-- Action Buttons --}}
                <div class="hero-buttons d-flex flex-wrap gap-3 justify-content-{{ $config['text_alignment'] ?? 'center' }} animate__animated animate__fadeInUp animate__delay-2s">
                    @if(!empty($config['primary_button']['text']))
                        <a href="{{ $config['primary_button']['url'] ?? '#' }}" 
                           class="btn btn-{{ $config['primary_button']['style'] ?? 'primary' }} btn-lg px-5 py-3 fw-semibold hero-btn-primary">
                            {{ $config['primary_button']['text'] }}
                            <i class="fas fa-arrow-right ms-2"></i>
                        </a>
                    @endif
                    
                    @if(!empty($config['secondary_button']['text']))
                        <a href="{{ $config['secondary_button']['url'] ?? '#' }}" 
                           class="btn btn-{{ $config['secondary_button']['style'] ?? 'outline-light' }} btn-lg px-5 py-3 fw-semibold hero-btn-secondary">
                            <i class="fas fa-play-circle me-2"></i>
                            {{ $config['secondary_button']['text'] }}
                        </a>
                    @endif
                </div>
                
                {{-- Optional Features List --}}
                @if(!empty($config['features_list']))
                    <div class="hero-features mt-5 animate__animated animate__fadeInUp animate__delay-3s">
                        <div class="row g-3 justify-content-center">
                            @foreach($config['features_list'] as $feature)
                                <div class="col-auto">
                                    <div class="feature-badge d-flex align-items-center bg-white bg-opacity-10 rounded-pill px-3 py-2">
                                        <i class="{{ $feature['icon'] ?? 'fas fa-check' }} text-white me-2"></i>
                                        <span class="text-white small fw-medium">{{ $feature['text'] }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    {{-- Scroll Indicator --}}
    @if($config['show_scroll_indicator'] ?? true)
        <div class="scroll-indicator position-absolute bottom-0 start-50 translate-middle-x pb-4" style="z-index: 3;">
            <div class="scroll-arrow animate__animated animate__bounce animate__infinite">
                <i class="fas fa-chevron-down text-white fs-3"></i>
            </div>
        </div>
    @endif
    
    {{-- Floating Elements (Optional) --}}
    @if($config['show_floating_elements'] ?? false)
        <div class="floating-elements position-absolute w-100 h-100" style="z-index: 2;">
            <div class="floating-shape floating-shape-1"></div>
            <div class="floating-shape floating-shape-2"></div>
            <div class="floating-shape floating-shape-3"></div>
        </div>
    @endif
</section>

{{-- Inline Styles --}}
<style>
.hero-video-bg {
    position: relative;
    background-attachment: fixed;
}

.hero-title {
    text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
    line-height: 1.1;
}

.hero-subtitle {
    text-shadow: 1px 1px 2px rgba(0,0,0,0.3);
}

.hero-btn-primary,
.hero-btn-secondary {
    transition: all 0.3s ease;
    border-radius: 50px;
    text-transform: uppercase;
    letter-spacing: 1px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}

.hero-btn-primary:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.3);
}

.hero-btn-secondary:hover {
    transform: translateY(-3px);
    background-color: rgba(255,255,255,0.9);
    color: #333;
}

.scroll-indicator {
    cursor: pointer;
    animation: bounce 2s infinite;
}

.scroll-indicator:hover {
    transform: translateX(-50%) scale(1.1);
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

.feature-badge {
    backdrop-filter: blur(10px);
    transition: all 0.3s ease;
}

.feature-badge:hover {
    background-color: rgba(255,255,255,0.2) !important;
    transform: translateY(-2px);
}

/* Floating Elements Animation */
.floating-elements {
    pointer-events: none;
}

.floating-shape {
    position: absolute;
    background: rgba(255,255,255,0.1);
    border-radius: 50%;
    animation: float 6s ease-in-out infinite;
}

.floating-shape-1 {
    width: 100px;
    height: 100px;
    top: 20%;
    left: 10%;
    animation-delay: 0s;
}

.floating-shape-2 {
    width: 60px;
    height: 60px;
    top: 60%;
    right: 15%;
    animation-delay: 2s;
}

.floating-shape-3 {
    width: 80px;
    height: 80px;
    bottom: 30%;
    left: 20%;
    animation-delay: 4s;
}

@keyframes float {
    0%, 100% {
        transform: translateY(0px) rotate(0deg);
        opacity: 0.7;
    }
    50% {
        transform: translateY(-20px) rotate(180deg);
        opacity: 1;
    }
}

/* Responsive Design */
@media (max-width: 768px) {
    .hero-title {
        font-size: 2.5rem !important;
    }
    
    .hero-subtitle {
        font-size: 1.1rem !important;
    }
    
    .hero-buttons {
        flex-direction: column;
        align-items: center;
    }
    
    .hero-btn-primary,
    .hero-btn-secondary {
        width: 100%;
        max-width: 300px;
    }
    
    .hero-features .row {
        text-align: center;
    }
    
    .feature-badge {
        margin-bottom: 0.5rem;
    }
}

@media (max-width: 576px) {
    .hero-video-bg {
        min-height: 90vh;
    }
    
    .hero-title {
        font-size: 2rem !important;
    }
    
    .hero-subtitle {
        font-size: 1rem !important;
    }
}

/* Accessibility */
@media (prefers-reduced-motion: reduce) {
    .animate__animated {
        animation: none !important;
    }
    
    .floating-shape {
        animation: none !important;
    }
    
    .scroll-indicator {
        animation: none !important;
    }
}
</style>

{{-- JavaScript for Interactions --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Scroll indicator click handler
    const scrollIndicator = document.querySelector('.scroll-indicator');
    if (scrollIndicator) {
        scrollIndicator.addEventListener('click', function() {
            const nextSection = document.querySelector('.hero-video-bg').nextElementSibling;
            if (nextSection) {
                nextSection.scrollIntoView({ 
                    behavior: 'smooth',
                    block: 'start'
                });
            } else {
                window.scrollTo({
                    top: window.innerHeight,
                    behavior: 'smooth'
                });
            }
        });
    }
    
    // Parallax effect for video (if enabled)
    if (window.innerWidth > 768) {
        const video = document.querySelector('.hero-video-bg video');
        const heroSection = document.querySelector('.hero-video-bg');
        
        if (video && heroSection) {
            window.addEventListener('scroll', function() {
                const scrolled = window.pageYOffset;
                const rate = scrolled * -0.5;
                
                if (scrolled < heroSection.offsetHeight) {
                    video.style.transform = `translateY(${rate}px)`;
                }
            });
        }
    }
    
    // Auto-hide scroll indicator when scrolling starts
    let scrollTimeout;
    window.addEventListener('scroll', function() {
        const scrollIndicator = document.querySelector('.scroll-indicator');
        if (scrollIndicator && window.pageYOffset > 100) {
            scrollIndicator.style.opacity = '0';
            scrollIndicator.style.visibility = 'hidden';
        } else if (scrollIndicator && window.pageYOffset <= 100) {
            scrollIndicator.style.opacity = '1';
            scrollIndicator.style.visibility = 'visible';
        }
    });
    
    // Video error handling
    const video = document.querySelector('.hero-video-bg video');
    if (video) {
        video.addEventListener('error', function() {
            console.warn('Hero video failed to load, falling back to gradient background');
            this.style.display = 'none';
        });
    }
});
</script>
