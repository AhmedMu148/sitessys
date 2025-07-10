@php
    $title = $data['title'] ?? 'What Our Clients Say';
    $subtitle = $data['subtitle'] ?? 'Read testimonials from our satisfied customers';
    $testimonials = $data['testimonials'] ?? [
        [
            'content' => 'Working with this team has been an absolute pleasure. Their expertise and dedication to our project was evident throughout the entire process.',
            'author' => 'Sarah Johnson',
            'position' => 'Marketing Director',
            'company' => 'Tech Solutions Inc.',
            'image' => 'https://source.unsplash.com/random/100x100/?portrait,woman'
        ],
        [
            'content' => 'The level of professionalism and technical expertise demonstrated by the team is outstanding. They delivered beyond our expectations.',
            'author' => 'Michael Chen',
            'position' => 'CTO',
            'company' => 'Innovation Labs',
            'image' => 'https://source.unsplash.com/random/100x100/?portrait,man'
        ],
        [
            'content' => 'Their attention to detail and commitment to quality is remarkable. Would highly recommend their services to anyone.',
            'author' => 'Emily Rodriguez',
            'position' => 'Product Manager',
            'company' => 'Digital Ventures',
            'image' => 'https://source.unsplash.com/random/100x100/?portrait,professional'
        ]
    ];
@endphp

<section class="testimonials-section py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">{{ $title }}</h2>
            <p class="lead text-muted">{{ $subtitle }}</p>
        </div>
        
        <div class="row g-4">
            @foreach($testimonials as $testimonial)
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <i data-feather="quote" class="text-primary" style="width: 32px; height: 32px;"></i>
                            </div>                            <p class="card-text mb-4">{{ $testimonial['content'] ?? 'Working with this team has been incredible.' }}</p>
                            <div class="d-flex align-items-center">
                                <img src="{{ $testimonial['image'] ?? 'https://via.placeholder.com/48x48' }}" 
                                     alt="{{ $testimonial['author'] ?? 'Client' }}"
                                     class="rounded-circle me-3" style="width: 48px; height: 48px; object-fit: cover;">
                                <div>
                                    <h6 class="mb-1">{{ $testimonial['author'] ?? 'Happy Client' }}</h6>
                                    <p class="text-muted mb-0 small">
                                        {{ $testimonial['position'] ?? 'Customer' }}{{ isset($testimonial['company']) ? ', ' . $testimonial['company'] : '' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
