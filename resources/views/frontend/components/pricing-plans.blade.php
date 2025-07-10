@php
    $title = $data['title'] ?? 'Our Pricing Plans';
    $subtitle = $data['subtitle'] ?? 'Choose the perfect plan for your needs';
    $plans = $data['plans'] ?? [
        [
            'name' => 'Basic',
            'price' => '$29',
            'period' => 'per month',
            'description' => 'Perfect for small businesses',
            'features' => [
                'Up to 5 users',
                '10GB storage',
                'Basic support',
                'Email notifications',
                'API access'
            ],
            'button_text' => 'Get Started',
            'button_url' => '/contact?plan=basic',
            'is_popular' => false
        ],
        [
            'name' => 'Professional',
            'price' => '$99',
            'period' => 'per month',
            'description' => 'Ideal for growing companies',
            'features' => [
                'Up to 20 users',
                '50GB storage',
                'Priority support',
                'Advanced analytics',
                'Custom integrations'
            ],
            'button_text' => 'Get Started',
            'button_url' => '/contact?plan=pro',
            'is_popular' => true
        ],
        [
            'name' => 'Enterprise',
            'price' => '$299',
            'period' => 'per month',
            'description' => 'For large organizations',
            'features' => [
                'Unlimited users',
                'Unlimited storage',
                '24/7 support',
                'Advanced security',
                'Custom solutions'
            ],
            'button_text' => 'Contact Us',
            'button_url' => '/contact?plan=enterprise',
            'is_popular' => false
        ]
    ];
@endphp

<section class="pricing-section py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">{{ $title }}</h2>
            <p class="lead text-muted">{{ $subtitle }}</p>
        </div>
        
        <div class="row g-4 align-items-center">
            @foreach($plans as $plan)
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm {{ ($plan['popular'] ?? false) ? 'popular' : '' }}">
                        @if($plan['popular'] ?? false)
                            <div class="card-status-top bg-primary"></div>
                        @endif
                        <div class="card-body p-4">
                            @if($plan['popular'] ?? false)
                                <span class="badge bg-primary position-absolute top-0 end-0 mt-3 me-3">Popular</span>
                            @endif
                            
                            <div class="text-center mb-4">
                                <h4 class="mb-3">{{ $plan['name'] }}</h4>
                                <div class="pricing-value">
                                    <h2 class="display-4 mb-0 fw-bold">{{ $plan['price'] }}</h2>
                                    <span class="text-muted">{{ $plan['period'] }}</span>
                                </div>
                                @if(isset($plan['description']))
                                    <p class="text-muted mt-2">{{ $plan['description'] }}</p>
                                @endif
                            </div>
                            
                            <ul class="list-unstyled mb-4">
                                @foreach($plan['features'] as $feature)
                                    <li class="mb-2">
                                        <i data-feather="check" class="text-success me-2" style="width: 16px; height: 16px;"></i>
                                        {{ $feature }}
                                    </li>
                                @endforeach
                            </ul>
                            
                            <div class="text-center">
                                <a href="{{ $plan['button_url'] ?? $plan['cta']['url'] ?? '#' }}" class="btn {{ ($plan['popular'] ?? false) ? 'btn-primary' : 'btn-outline-primary' }} btn-lg w-100">
                                    {{ $plan['button_text'] ?? $plan['cta']['text'] ?? 'Choose Plan' }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<style>
    .pricing-section .card.popular {
        transform: scale(1.05);
    }
    
    .pricing-section .card-status-top {
        position: absolute;
        top: 0;
        right: 0;
        left: 0;
        height: 3px;
    }
</style>
