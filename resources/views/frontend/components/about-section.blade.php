@php
    $title = $data['title'] ?? 'About Us';
    $subtitle = $data['subtitle'] ?? 'Learn more about our story and mission';
    $content = $data['content'] ?? 'We are passionate about delivering exceptional value to our customers.';
    $image = $data['image'] ?? 'img/about.jpg';
    $stats = $data['stats'] ?? [
        ['number' => '10+', 'label' => 'Years Experience'],
        ['number' => '500+', 'label' => 'Happy Clients'],
        ['number' => '1000+', 'label' => 'Projects Completed']
    ];
@endphp

<section class="about-section py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <img src="{{ asset($image) }}" alt="About Us" class="img-fluid rounded-3 shadow">
            </div>
            <div class="col-lg-6">
                <h2 class="fw-bold mb-3">{{ $title }}</h2>
                <p class="lead text-muted mb-4">{{ $subtitle }}</p>
                <p class="mb-4">{{ $content }}</p>
                <div class="row g-4">
                    @foreach($stats as $stat)
                        <div class="col-sm-4">
                            <div class="text-center">
                <h3 class="fw-bold text-primary mb-2">{{ $stat['number'] ?? '0' }}</h3>
                <p class="text-muted mb-0">{{ $stat['label'] ?? 'Stat' }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
