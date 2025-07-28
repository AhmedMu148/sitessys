{{-- Comprehensive Footer 2024 Template --}}
<footer class="footer-comprehensive" 
        style="background-color: {{ $config['background_color'] ?? '#1f2937' }}; 
               color: {{ $config['text_color'] ?? '#ffffff' }};">
    <div class="container py-5">
        <div class="row g-4">
            {{-- Company Info Column --}}
            <div class="col-lg-4 col-md-6">
                <div class="footer-brand mb-3">
                    @if(!empty($config['logo_url']))
                        <img src="{{ $config['logo_url'] }}" 
                             alt="{{ $config['company_name'] ?? 'Company Logo' }}" 
                             height="40" class="mb-3">
                    @else
                        <h5 class="fw-bold mb-3" style="color: {{ $config['text_color'] ?? '#ffffff' }};">
                            {{ $config['company_name'] ?? 'Your Company' }}
                        </h5>
                    @endif
                </div>
                
                <p class="mb-3" style="opacity: 0.8;">
                    {{ $config['company_description'] ?? 'We are a leading company providing innovative solutions to help your business grow and succeed in the digital world.' }}
                </p>
                
                {{-- Contact Information --}}
                <div class="contact-info">
                    @if(!empty($config['contact_info']['email']))
                        <p class="mb-2" style="opacity: 0.9;">
                            <i class="fas fa-envelope me-2"></i>
                            <a href="mailto:{{ $config['contact_info']['email'] }}" 
                               style="color: {{ $config['text_color'] ?? '#ffffff' }}; text-decoration: none;">
                                {{ $config['contact_info']['email'] }}
                            </a>
                        </p>
                    @endif
                    
                    @if(!empty($config['contact_info']['phone']))
                        <p class="mb-2" style="opacity: 0.9;">
                            <i class="fas fa-phone me-2"></i>
                            <a href="tel:{{ $config['contact_info']['phone'] }}" 
                               style="color: {{ $config['text_color'] ?? '#ffffff' }}; text-decoration: none;">
                                {{ $config['contact_info']['phone'] }}
                            </a>
                        </p>
                    @endif
                    
                    @if(!empty($config['contact_info']['address']))
                        <p class="mb-2" style="opacity: 0.9;">
                            <i class="fas fa-map-marker-alt me-2"></i>
                            {{ $config['contact_info']['address'] }}
                        </p>
                    @endif
                    
                    @if(!empty($config['contact_info']['business_hours']))
                        <p class="mb-2" style="opacity: 0.9;">
                            <i class="fas fa-clock me-2"></i>
                            {{ $config['contact_info']['business_hours'] }}
                        </p>
                    @endif
                </div>
            </div>
            
            {{-- Quick Links Column --}}
            <div class="col-lg-2 col-md-6">
                <h6 class="fw-bold mb-3" style="color: {{ $config['text_color'] ?? '#ffffff' }};">
                    Quick Links
                </h6>
                <ul class="list-unstyled">
                    @foreach($config['quick_links'] ?? [] as $link)
                        <li class="mb-2">
                            <a href="{{ $link['url'] }}" 
                               class="footer-link text-decoration-none" 
                               style="color: {{ $config['text_color'] ?? '#ffffff' }}; opacity: 0.8;">
                                {{ $link['label'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
            
            {{-- Services Column --}}
            <div class="col-lg-2 col-md-6">
                <h6 class="fw-bold mb-3" style="color: {{ $config['text_color'] ?? '#ffffff' }};">
                    Services
                </h6>
                <ul class="list-unstyled">
                    @foreach($config['services_links'] ?? [] as $service)
                        <li class="mb-2">
                            <a href="{{ $service['url'] }}" 
                               class="footer-link text-decoration-none" 
                               style="color: {{ $config['text_color'] ?? '#ffffff' }}; opacity: 0.8;">
                                {{ $service['label'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
            
            {{-- Newsletter & Social Column --}}
            <div class="col-lg-4 col-md-6">
                {{-- Newsletter Section --}}
                @if($config['newsletter']['enabled'] ?? true)
                    <div class="newsletter-section mb-4">
                        <h6 class="fw-bold mb-3" style="color: {{ $config['text_color'] ?? '#ffffff' }};">
                            {{ $config['newsletter']['title'] ?? 'Stay Updated' }}
                        </h6>
                        <p class="mb-3" style="opacity: 0.8;">
                            {{ $config['newsletter']['description'] ?? 'Subscribe to our newsletter for the latest updates and offers.' }}
                        </p>
                        <form class="newsletter-form" action="/newsletter/subscribe" method="POST">
                            @csrf
                            <div class="input-group mb-3">
                                <input type="email" 
                                       class="form-control" 
                                       name="email"
                                       placeholder="{{ $config['newsletter']['placeholder'] ?? 'Enter your email address' }}"
                                       required>
                                <button class="btn btn-primary" type="submit">
                                    {{ $config['newsletter']['button_text'] ?? 'Subscribe' }}
                                </button>
                            </div>
                        </form>
                    </div>
                @endif
                
                {{-- Social Links Section --}}
                <div class="social-links">
                    <h6 class="fw-bold mb-3" style="color: {{ $config['text_color'] ?? '#ffffff' }};">
                        Follow Us
                    </h6>
                    <div class="d-flex gap-3 flex-wrap">
                        @foreach($config['social_links'] ?? [] as $social)
                            <a href="{{ $social['url'] }}" 
                               target="_blank" 
                               class="social-link d-flex align-items-center justify-content-center"
                               style="width: 45px; height: 45px; 
                                      background-color: rgba(255,255,255,0.1); 
                                      border-radius: 50%; 
                                      color: {{ $config['text_color'] ?? '#ffffff' }}; 
                                      text-decoration: none;
                                      transition: all 0.3s ease;"
                               title="{{ $social['platform'] ?? '' }}">
                                <i class="{{ $social['icon'] }}"></i>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        
        {{-- Footer Bottom / Copyright --}}
        <hr class="my-4" style="border-color: rgba(255,255,255,0.2);">
        <div class="row align-items-center">
            <div class="col-md-6">
                <p class="mb-0" style="opacity: 0.8;">
                    {{ $config['copyright_text'] ?? '© 2024 Your Company. All rights reserved.' }}
                </p>
            </div>
            <div class="col-md-6 text-md-end">
                <a href="/privacy-policy" 
                   class="footer-link text-decoration-none me-3" 
                   style="color: {{ $config['text_color'] ?? '#ffffff' }}; opacity: 0.8;">
                    Privacy Policy
                </a>
                <a href="/terms-of-service" 
                   class="footer-link text-decoration-none me-3" 
                   style="color: {{ $config['text_color'] ?? '#ffffff' }}; opacity: 0.8;">
                    Terms of Service
                </a>
                <a href="/sitemap" 
                   class="footer-link text-decoration-none" 
                   style="color: {{ $config['text_color'] ?? '#ffffff' }}; opacity: 0.8;">
                    Sitemap
                </a>
            </div>
        </div>
    </div>
</footer>

{{-- Inline Styles --}}
<style>
.footer-comprehensive {
    margin-top: auto;
    position: relative;
}

.footer-comprehensive::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 1px;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
}

.footer-link {
    transition: all 0.3s ease;
    position: relative;
}

.footer-link:hover {
    opacity: 1 !important;
    transform: translateX(5px);
}

.social-link:hover {
    background-color: rgba(255,255,255,0.2) !important;
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}

.newsletter-form .form-control {
    border-radius: 5px 0 0 5px;
    border: 1px solid rgba(255,255,255,0.2);
    background-color: rgba(255,255,255,0.1);
    color: {{ $config['text_color'] ?? '#ffffff' }};
}

.newsletter-form .form-control::placeholder {
    color: rgba(255,255,255,0.6);
}

.newsletter-form .form-control:focus {
    background-color: rgba(255,255,255,0.15);
    border-color: rgba(255,255,255,0.4);
    box-shadow: none;
    color: {{ $config['text_color'] ?? '#ffffff' }};
}

.newsletter-form .btn {
    border-radius: 0 5px 5px 0;
    border: 1px solid #007bff;
}

.contact-info i {
    width: 18px;
    text-align: center;
    opacity: 0.7;
}

@media (max-width: 768px) {
    .footer-comprehensive .col-md-6.text-md-end {
        text-align: center !important;
        margin-top: 1rem;
    }
    
    .footer-link {
        display: inline-block;
        margin-bottom: 0.5rem;
    }
    
    .social-links .d-flex {
        justify-content: center;
    }
}

/* Animation for newsletter success */
.newsletter-success {
    padding: 0.75rem;
    background-color: rgba(40, 167, 69, 0.2);
    border: 1px solid rgba(40, 167, 69, 0.3);
    border-radius: 5px;
    margin-top: 0.5rem;
    animation: slideIn 0.3s ease;
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>

{{-- JavaScript for Newsletter --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const newsletterForm = document.querySelector('.newsletter-form');
    
    if (newsletterForm) {
        newsletterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const emailInput = this.querySelector('input[type="email"]');
            const email = emailInput.value.trim();
            
            if (email && validateEmail(email)) {
                // Simulate newsletter subscription
                const button = this.querySelector('button[type="submit"]');
                const originalText = button.textContent;
                
                button.textContent = 'Subscribing...';
                button.disabled = true;
                
                // Simulate API call
                setTimeout(() => {
                    // Show success message
                    showNewsletterSuccess('Thank you for subscribing!');
                    emailInput.value = '';
                    button.textContent = originalText;
                    button.disabled = false;
                }, 1500);
            } else {
                showNewsletterError('Please enter a valid email address.');
            }
        });
    }
    
    function validateEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }
    
    function showNewsletterSuccess(message) {
        const form = document.querySelector('.newsletter-form');
        const existingMessage = form.querySelector('.newsletter-message');
        if (existingMessage) existingMessage.remove();
        
        const successDiv = document.createElement('div');
        successDiv.className = 'newsletter-message newsletter-success text-success';
        successDiv.innerHTML = '<i class="fas fa-check-circle me-2"></i>' + message;
        form.appendChild(successDiv);
        
        setTimeout(() => successDiv.remove(), 5000);
    }
    
    function showNewsletterError(message) {
        const form = document.querySelector('.newsletter-form');
        const existingMessage = form.querySelector('.newsletter-message');
        if (existingMessage) existingMessage.remove();
        
        const errorDiv = document.createElement('div');
        errorDiv.className = 'newsletter-message text-danger';
        errorDiv.innerHTML = '<i class="fas fa-exclamation-circle me-2"></i>' + message;
        form.appendChild(errorDiv);
        
        setTimeout(() => errorDiv.remove(), 5000);
    }
});
</script>
