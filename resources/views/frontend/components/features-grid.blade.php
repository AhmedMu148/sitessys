@php
    $title = $data['title'] ?? 'Our Features';
    $subtitle = $data['subtitle'] ?? 'Discover what makes us different';
    $features = $data['features'] ?? [
        [
            'icon' => 'trending-up',
            'title' => 'Easy to Use',
            'description' => 'Our intuitive interface makes it simple to get started.'
        ],
        [
            'icon' => 'feather-shield',
            'title' => 'Secure & Reliable',
            'description' => 'Your data is safe and protected with us.'
        ],
        [
            'icon' => 'feather-clock',
            'title' => '24/7 Support',
            'description' => 'Our team is always here to help you succeed.'
        ]
    ];
@endphp

<section class="features-section py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">{{ $title }}</h2>
            <p class="lead text-muted">{{ $subtitle }}</p>
        </div>
        <div class="row g-4">
            @foreach($features as $feature)
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon mb-3">
                                <i data-feather="{{ $feature['icon'] ?? 'check-circle' }}" class="text-primary" style="width: 36px; height: 36px;"></i>
                            </div>
                            <h4 class="card-title mb-3">{{ $feature['title'] ?? 'Feature Title' }}</h4>
                            <p class="card-text text-muted">{{ $feature['description'] ?? 'Feature description.' }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
