<!-- Landing Page Navigation -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold text-primary" href="/">
            {{ $data['brand'] ?? 'MyCompany' }}
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                @foreach($data['menu_items'] ?? [
                    ['title' => 'Home', 'url' => '/'],
                    ['title' => 'About', 'url' => '/about'],
                    ['title' => 'Services', 'url' => '/services'],
                    ['title' => 'Contact', 'url' => '/contact']
                ] as $item)
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is(ltrim($item['url'], '/')) || (request()->is('/') && $item['url'] == '/') ? 'active' : '' }}" 
                           href="{{ $item['url'] }}">
                            {{ $item['title'] }}
                        </a>
                    </li>
                @endforeach
                
                <li class="nav-item ms-3">
                    <a href="{{ $data['cta_url'] ?? '#contact' }}" class="btn btn-primary">
                        {{ $data['cta_text'] ?? 'Get Started' }}
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

        <div class="sidebar-cta">
            <div class="sidebar-cta-content">
                <strong class="d-inline-block mb-2">{{ $data['cta_title'] ?? 'Upgrade to Pro' }}</strong>
                <div class="mb-3 text-sm">
                    {{ $data['cta_text'] ?? 'Are you looking for more components? Check out our premium version.' }}
                </div>
                <div class="d-grid">
                    <a href="{{ $data['cta_url'] ?? '#' }}" class="btn btn-primary">
                        {{ $data['cta_button'] ?? 'Upgrade to Pro' }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</nav>
