{{-- Modern Navigation 2024 Template --}}
<nav class="navbar navbar-expand-lg navbar-modern" 
     style="background: linear-gradient(135deg, {{ $config['gradient_start'] ?? '#667eea' }}, {{ $config['gradient_end'] ?? '#764ba2' }});">
    <div class="container">
        <!-- Brand/Logo -->
        <a class="navbar-brand d-flex align-items-center" href="/">
            @if(!empty($config['logo_url']))
                <img src="{{ $config['logo_url'] }}" alt="{{ $config['brand_text'] ?? 'Logo' }}" height="40" class="me-2">
            @endif
            <span class="fw-bold" style="color: {{ $config['text_color'] ?? '#ffffff' }};">
                {{ $config['brand_text'] ?? 'Your Brand' }}
            </span>
        </a>
        
        <!-- Mobile Toggle Button -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" 
                aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <!-- Navigation Content -->
        <div class="collapse navbar-collapse" id="navbarContent">
            <!-- Main Navigation -->
            <ul class="navbar-nav me-auto">
                @foreach($config['menu_items'] ?? [] as $item)
                    <li class="nav-item">
                        <a class="nav-link fw-medium" 
                           href="{{ $item['url'] }}" 
                           target="{{ $item['target'] ?? '_self' }}"
                           style="color: {{ $config['text_color'] ?? '#ffffff' }};">
                            {{ $item['label'] }}
                        </a>
                    </li>
                @endforeach
            </ul>
            
            <!-- Right Side Actions -->
            <div class="d-flex align-items-center">
                {{-- Search Box --}}
                @if($config['show_search'] ?? true)
                    <form class="d-flex me-3" role="search">
                        <input class="form-control form-control-sm" type="search" 
                               placeholder="{{ $config['search_placeholder'] ?? 'Search...' }}" 
                               style="border-radius: 20px; width: 200px;">
                        <button class="btn btn-outline-light btn-sm ms-2" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                @endif
                
                {{-- CTA Button --}}
                @if(($config['cta_button']['show'] ?? true) && !empty($config['cta_button']['text']))
                    <a href="{{ $config['cta_button']['url'] ?? '/contact' }}" 
                       class="btn {{ $config['cta_button']['style'] ?? 'btn-light' }} btn-sm rounded-pill px-3 fw-medium">
                        {{ $config['cta_button']['text'] ?? 'Get Started' }}
                    </a>
                @endif
            </div>
        </div>
    </div>
</nav>

{{-- Inline Styles --}}
<style>
.navbar-modern {
    backdrop-filter: blur(10px);
    transition: all 0.3s ease;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
}

.navbar-modern.scrolled {
    background: rgba(255, 255, 255, 0.95) !important;
    backdrop-filter: blur(20px);
    box-shadow: 0 2px 20px rgba(0, 0, 0, 0.15);
}

.navbar-modern.scrolled .nav-link,
.navbar-modern.scrolled .navbar-brand {
    color: #333 !important;
}

.navbar-nav .nav-link {
    transition: all 0.3s ease;
    position: relative;
    margin: 0 0.5rem;
}

.navbar-nav .nav-link:hover {
    transform: translateY(-1px);
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.navbar-nav .nav-link::after {
    content: '';
    position: absolute;
    width: 0;
    height: 2px;
    bottom: -5px;
    left: 50%;
    background-color: currentColor;
    transition: all 0.3s ease;
    transform: translateX(-50%);
}

.navbar-nav .nav-link:hover::after {
    width: 100%;
}

.navbar-toggler {
    border: none;
    padding: 0.25rem 0.5rem;
}

.navbar-toggler:focus {
    box-shadow: none;
}

@media (max-width: 991.98px) {
    .navbar-collapse {
        background: rgba(0, 0, 0, 0.9);
        margin-top: 1rem;
        border-radius: 10px;
        padding: 1rem;
    }
    
    .navbar-nav .nav-link {
        padding: 0.75rem 1rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    .navbar-nav .nav-link:last-child {
        border-bottom: none;
    }
}
</style>

{{-- JavaScript for Scroll Effect --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const navbar = document.querySelector('.navbar-modern');
    
    if (navbar && {{ $config['sticky_navbar'] ?? 'true' ? 'true' : 'false' }}) {
        // Make navbar sticky
        navbar.style.position = 'fixed';
        navbar.style.top = '0';
        navbar.style.width = '100%';
        navbar.style.zIndex = '1030';
        
        // Add scroll effect
        window.addEventListener('scroll', function() {
            if (window.scrollY > 100) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });
        
        // Add padding to body to compensate for fixed navbar
        document.body.style.paddingTop = navbar.offsetHeight + 'px';
    }
});
</script>
