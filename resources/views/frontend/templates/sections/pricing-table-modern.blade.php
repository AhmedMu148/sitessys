{{-- Modern Pricing Table Section --}}
<section class="pricing-section py-5" 
         style="background-color: {{ $config['background_color'] ?? '#ffffff' }};">
    
    <div class="container">
        {{-- Section Header --}}
        <div class="row text-center mb-5">
            <div class="col-lg-8 mx-auto">
                <h2 class="section-title display-5 fw-bold mb-3" 
                    style="color: {{ $config['title_color'] ?? '#333333' }};">
                    {{ $config['section_title'] ?? 'Choose Your Plan' }}
                </h2>
                <p class="section-subtitle lead text-muted mb-4">
                    {{ $config['section_subtitle'] ?? 'Flexible pricing options designed to grow with your business needs' }}
                </p>
                
                {{-- Billing Toggle (Optional) --}}
                @if($config['show_billing_toggle'] ?? false)
                    <div class="billing-toggle d-inline-flex align-items-center bg-light rounded-pill p-1 mb-4">
                        <button class="btn btn-sm toggle-monthly active" data-billing="monthly">
                            Monthly
                        </button>
                        <button class="btn btn-sm toggle-yearly" data-billing="yearly">
                            Yearly 
                            @if(!empty($config['yearly_discount']))
                                <span class="badge bg-success ms-2">{{ $config['yearly_discount'] }}% OFF</span>
                            @endif
                        </button>
                    </div>
                @endif
            </div>
        </div>
        
        {{-- Pricing Cards --}}
        <div class="row g-4 justify-content-center">
            @foreach($config['pricing_plans'] ?? [] as $index => $plan)
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                    <div class="pricing-card h-100 position-relative {{ ($plan['featured'] ?? false) ? 'featured-plan' : '' }}">
                        
                        {{-- Featured Badge --}}
                        @if($plan['featured'] ?? false)
                            <div class="featured-badge position-absolute top-0 start-50 translate-middle">
                                <span class="badge bg-{{ $plan['badge_color'] ?? 'primary' }} rounded-pill px-3 py-2 fw-semibold">
                                    {{ $plan['badge_text'] ?? 'Most Popular' }}
                                </span>
                            </div>
                        @endif
                        
                        <div class="card h-100 border-0 shadow-sm {{ ($plan['featured'] ?? false) ? 'border-primary shadow-lg' : '' }}">
                            
                            {{-- Card Header --}}
                            <div class="card-header text-center py-4 border-0 {{ ($plan['featured'] ?? false) ? 'bg-primary text-white' : 'bg-light' }}">
                                @if(!empty($plan['icon']))
                                    <div class="plan-icon mb-3">
                                        <i class="{{ $plan['icon'] }} fs-2 {{ ($plan['featured'] ?? false) ? 'text-white' : 'text-primary' }}"></i>
                                    </div>
                                @endif
                                <h4 class="plan-name fw-bold mb-0">
                                    {{ $plan['name'] ?? 'Plan Name' }}
                                </h4>
                                @if(!empty($plan['subtitle']))
                                    <p class="plan-subtitle small mb-0 {{ ($plan['featured'] ?? false) ? 'text-white-50' : 'text-muted' }}">
                                        {{ $plan['subtitle'] }}
                                    </p>
                                @endif
                            </div>
                            
                            {{-- Card Body --}}
                            <div class="card-body text-center p-4 d-flex flex-column">
                                
                                {{-- Price Display --}}
                                <div class="price-display mb-4 pb-3 border-bottom">
                                    @if(!empty($plan['original_price']) && $plan['original_price'] !== $plan['price'])
                                        <div class="original-price text-muted text-decoration-line-through small mb-1">
                                            ${{ $plan['original_price'] }}{{ $plan['period'] ?? '/month' }}
                                        </div>
                                    @endif
                                    
                                    <div class="current-price">
                                        <span class="price-currency text-muted fs-5">$</span>
                                        <span class="price-amount display-4 fw-bold text-{{ ($plan['featured'] ?? false) ? 'primary' : 'dark' }}"
                                              data-monthly="{{ $plan['price'] ?? '0' }}"
                                              data-yearly="{{ $plan['yearly_price'] ?? ($plan['price'] ?? '0') }}">
                                            {{ $plan['price'] ?? '0' }}
                                        </span>
                                        <span class="price-period text-muted">{{ $plan['period'] ?? '/month' }}</span>
                                    </div>
                                    
                                    @if(!empty($plan['price_note']))
                                        <div class="price-note small text-muted mt-2">
                                            {{ $plan['price_note'] }}
                                        </div>
                                    @endif
                                </div>
                                
                                {{-- Plan Description --}}
                                @if(!empty($plan['description']))
                                    <p class="plan-description text-muted mb-4">
                                        {{ $plan['description'] }}
                                    </p>
                                @endif
                                
                                {{-- Features List --}}
                                <ul class="features-list list-unstyled mb-4 flex-grow-1">
                                    @foreach($plan['features'] ?? [] as $feature)
                                        <li class="feature-item mb-3 d-flex align-items-start">
                                            @if(is_array($feature))
                                                <i class="fas fa-{{ $feature['available'] ?? true ? 'check text-success' : 'times text-muted' }} me-3 mt-1 flex-shrink-0"></i>
                                                <span class="{{ ($feature['available'] ?? true) ? '' : 'text-muted' }}">
                                                    {{ $feature['text'] ?? $feature }}
                                                </span>
                                            @else
                                                <i class="fas fa-check text-success me-3 mt-1 flex-shrink-0"></i>
                                                <span>{{ $feature }}</span>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                                
                                {{-- Usage Limits (Optional) --}}
                                @if(!empty($plan['limits']))
                                    <div class="usage-limits mb-4 p-3 bg-light rounded">
                                        <h6 class="small fw-bold text-muted mb-2">INCLUDES:</h6>
                                        @foreach($plan['limits'] as $limit)
                                            <div class="limit-item small text-muted mb-1">
                                                <strong>{{ $limit['value'] }}</strong> {{ $limit['label'] }}
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                                
                                {{-- CTA Button --}}
                                <div class="plan-cta mt-auto">
                                    <a href="{{ $plan['button_url'] ?? '#' }}" 
                                       class="btn btn-{{ ($plan['featured'] ?? false) ? 'primary' : 'outline-primary' }} btn-lg w-100 fw-semibold">
                                        {{ $plan['button_text'] ?? 'Get Started' }}
                                        @if(!empty($plan['button_icon']))
                                            <i class="{{ $plan['button_icon'] }} ms-2"></i>
                                        @endif
                                    </a>
                                    
                                    @if(!empty($plan['secondary_button']))
                                        <a href="{{ $plan['secondary_button']['url'] ?? '#' }}" 
                                           class="btn btn-link btn-sm mt-2 text-decoration-none">
                                            {{ $plan['secondary_button']['text'] ?? 'Learn More' }}
                                        </a>
                                    @endif
                                </div>
                                
                                {{-- Guarantee Badge (Optional) --}}
                                @if(!empty($plan['guarantee']))
                                    <div class="guarantee-badge mt-3">
                                        <small class="text-muted">
                                            <i class="fas fa-shield-alt text-success me-1"></i>
                                            {{ $plan['guarantee'] }}
                                        </small>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        {{-- Additional Information --}}
        @if(!empty($config['additional_info']))
            <div class="row mt-5">
                <div class="col-lg-8 mx-auto text-center">
                    <div class="additional-info p-4 bg-light rounded-3">
                        <h5 class="fw-bold mb-3">{{ $config['additional_info']['title'] ?? 'Need Help Choosing?' }}</h5>
                        <p class="text-muted mb-3">{{ $config['additional_info']['description'] ?? 'Our team is here to help you find the perfect plan.' }}</p>
                        @if(!empty($config['additional_info']['button_url']))
                            <a href="{{ $config['additional_info']['button_url'] }}" class="btn btn-outline-primary">
                                {{ $config['additional_info']['button_text'] ?? 'Contact Sales' }}
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @endif
        
        {{-- FAQ Section (Optional) --}}
        @if($config['show_faq'] ?? false)
            <div class="row mt-5">
                <div class="col-lg-8 mx-auto">
                    <h3 class="text-center fw-bold mb-4">Frequently Asked Questions</h3>
                    <div class="accordion" id="pricingFAQ">
                        @foreach($config['faq_items'] ?? [] as $index => $faq)
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="faq{{ $index }}">
                                    <button class="accordion-button {{ $index === 0 ? '' : 'collapsed' }}" 
                                            type="button" 
                                            data-bs-toggle="collapse" 
                                            data-bs-target="#faqCollapse{{ $index }}">
                                        {{ $faq['question'] ?? 'FAQ Question' }}
                                    </button>
                                </h2>
                                <div id="faqCollapse{{ $index }}" 
                                     class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}" 
                                     data-bs-parent="#pricingFAQ">
                                    <div class="accordion-body">
                                        {{ $faq['answer'] ?? 'FAQ Answer' }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>
</section>

{{-- Inline Styles --}}
<style>
.pricing-section {
    position: relative;
}

.section-title {
    position: relative;
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

.billing-toggle {
    background: #f8f9fa !important;
    padding: 4px;
}

.billing-toggle .btn {
    border: none;
    background: transparent;
    color: #6c757d;
    transition: all 0.3s ease;
    border-radius: 20px;
    padding: 0.5rem 1.5rem;
}

.billing-toggle .btn.active {
    background: white;
    color: #495057;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.pricing-card {
    transition: all 0.3s ease;
}

.pricing-card.featured-plan {
    transform: scale(1.05);
    z-index: 10;
}

.pricing-card .card {
    transition: all 0.3s ease;
    overflow: hidden;
}

.pricing-card:hover .card {
    transform: translateY(-10px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.15) !important;
}

.featured-badge {
    z-index: 20;
}

.plan-icon {
    transition: transform 0.3s ease;
}

.pricing-card:hover .plan-icon {
    transform: scale(1.1);
}

.price-display {
    position: relative;
}

.price-amount {
    transition: all 0.3s ease;
    display: inline-block;
}

.features-list {
    text-align: left;
}

.feature-item {
    transition: all 0.3s ease;
    padding: 0.25rem 0;
}

.feature-item:hover {
    background-color: rgba(0,0,0,0.02);
    border-radius: 4px;
    padding-left: 0.5rem;
    padding-right: 0.5rem;
}

.usage-limits {
    border-left: 3px solid {{ $config['accent_color'] ?? '#667eea' }};
}

.plan-cta .btn {
    transition: all 0.3s ease;
    border-radius: 8px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.plan-cta .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.guarantee-badge {
    opacity: 0.8;
}

.additional-info {
    border: 2px dashed #dee2e6;
    transition: all 0.3s ease;
}

.additional-info:hover {
    border-color: {{ $config['accent_color'] ?? '#667eea' }};
    background-color: #f8f9ff !important;
}

/* Responsive Design */
@media (max-width: 992px) {
    .pricing-card.featured-plan {
        transform: none;
        margin-top: 2rem;
        margin-bottom: 2rem;
    }
}

@media (max-width: 768px) {
    .section-title {
        font-size: 2rem !important;
    }
    
    .price-amount {
        font-size: 2.5rem !important;
    }
    
    .billing-toggle {
        flex-direction: column;
        width: 200px;
    }
    
    .billing-toggle .btn {
        width: 100%;
        margin: 2px 0;
    }
}

@media (max-width: 576px) {
    .pricing-card {
        margin-bottom: 2rem;
    }
    
    .card-body {
        padding: 1.5rem !important;
    }
    
    .price-amount {
        font-size: 2rem !important;
    }
}

/* Animation classes */
.animate-price {
    animation: priceChange 0.3s ease-in-out;
}

@keyframes priceChange {
    0%, 100% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.1);
    }
}

/* Dark mode support */
@media (prefers-color-scheme: dark) {
    .pricing-card .card {
        background-color: #2d3748 !important;
        border-color: rgba(255,255,255,0.1) !important;
    }
    
    .card-header.bg-light {
        background-color: #4a5568 !important;
        color: #ffffff !important;
    }
    
    .text-muted {
        color: #a0aec0 !important;
    }
    
    .additional-info {
        background-color: #2d3748 !important;
        border-color: rgba(255,255,255,0.1) !important;
    }
}

/* High contrast mode */
@media (prefers-contrast: high) {
    .pricing-card .card {
        border: 2px solid #000 !important;
    }
    
    .btn {
        border: 2px solid currentColor !important;
    }
}

/* Reduced motion */
@media (prefers-reduced-motion: reduce) {
    .pricing-card,
    .pricing-card .card,
    .plan-icon,
    .feature-item,
    .plan-cta .btn {
        transition: none !important;
    }
    
    .pricing-card:hover .card {
        transform: none !important;
    }
}
</style>

{{-- JavaScript for Enhanced Functionality --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Billing toggle functionality
    const billingToggles = document.querySelectorAll('.billing-toggle .btn');
    const priceAmounts = document.querySelectorAll('.price-amount');
    
    billingToggles.forEach(toggle => {
        toggle.addEventListener('click', function() {
            // Update active state
            billingToggles.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            
            const billingType = this.dataset.billing;
            
            // Update prices
            priceAmounts.forEach(price => {
                price.classList.add('animate-price');
                
                setTimeout(() => {
                    if (billingType === 'yearly') {
                        price.textContent = price.dataset.yearly || price.dataset.monthly;
                    } else {
                        price.textContent = price.dataset.monthly;
                    }
                    
                    // Update period text
                    const periodElement = price.nextElementSibling;
                    if (periodElement && periodElement.classList.contains('price-period')) {
                        periodElement.textContent = billingType === 'yearly' ? '/year' : '/month';
                    }
                }, 150);
                
                setTimeout(() => {
                    price.classList.remove('animate-price');
                }, 300);
            });
            
            console.log(`Billing changed to: ${billingType}`);
        });
    });
    
    // Plan selection tracking
    const planButtons = document.querySelectorAll('.plan-cta .btn:not(.btn-link)');
    planButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            const planCard = this.closest('.pricing-card');
            const planName = planCard.querySelector('.plan-name').textContent;
            const planPrice = planCard.querySelector('.price-amount').textContent;
            
            console.log(`Plan selected: ${planName} - $${planPrice}`);
            
            // Add analytics tracking here if needed
            // gtag('event', 'plan_selection', {
            //     plan_name: planName,
            //     plan_price: planPrice
            // });
        });
    });
    
    // Intersection Observer for animations
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);
    
    // Apply animation to pricing cards
    document.querySelectorAll('.pricing-card').forEach((card, index) => {
        if (!card.classList.contains('featured-plan')) {
            card.style.opacity = '0';
            card.style.transform = 'translateY(30px)';
            card.style.transition = `all 0.6s ease ${index * 0.1}s`;
        }
        observer.observe(card);
    });
    
    // Keyboard navigation for cards
    document.querySelectorAll('.pricing-card').forEach(card => {
        card.setAttribute('tabindex', '0');
        
        card.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                const button = this.querySelector('.plan-cta .btn:not(.btn-link)');
                if (button) {
                    e.preventDefault();
                    button.click();
                }
            }
        });
        
        card.addEventListener('focus', function() {
            this.style.outline = '2px solid #667eea';
            this.style.outlineOffset = '2px';
        });
        
        card.addEventListener('blur', function() {
            this.style.outline = 'none';
        });
    });
    
    // Auto-adjust card heights for consistency
    function adjustCardHeights() {
        const cards = document.querySelectorAll('.pricing-card .card');
        let maxHeight = 0;
        
        // Reset heights
        cards.forEach(card => {
            card.style.height = 'auto';
        });
        
        // Find max height
        cards.forEach(card => {
            const cardHeight = card.offsetHeight;
            maxHeight = Math.max(maxHeight, cardHeight);
        });
        
        // Apply max height to all cards
        cards.forEach(card => {
            card.style.height = maxHeight + 'px';
        });
    }
    
    // Adjust heights on load and resize
    window.addEventListener('load', adjustCardHeights);
    window.addEventListener('resize', debounce(adjustCardHeights, 250));
    
    // Debounce function for performance
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
    
    // Add tooltips for feature items (optional)
    const featureItems = document.querySelectorAll('.feature-item');
    featureItems.forEach(item => {
        const text = item.textContent.trim();
        if (text.length > 50) {
            item.setAttribute('title', text);
        }
    });
});
</script>
