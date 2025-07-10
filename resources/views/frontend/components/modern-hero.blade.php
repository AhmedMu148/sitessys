@php
    $title = $data['title'] ?? 'Welcome to ' . config('app.name');
    $subtitle = $data['subtitle'] ?? 'Discover amazing features and possibilities';
    $primaryButton = $data['primary_button'] ?? ['text' => 'Get Started', 'url' => '#'];
    $secondaryButton = $data['secondary_button'] ?? ['text' => 'Learn More', 'url' => '#'];
    $stats = $data['stats'] ?? [];
@endphp

<section class="hero-section bg-light py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-3">{{ $title }}</h1>
                <p class="lead mb-4">{{ $subtitle }}</p>
                <div class="d-flex gap-3">
                    <a href="{{ $primaryButton['url'] }}" class="btn btn-primary btn-lg">{{ $primaryButton['text'] }}</a>
                    <a href="{{ $secondaryButton['url'] }}" class="btn btn-outline-secondary btn-lg">{{ $secondaryButton['text'] }}</a>
                </div>
                
                @if(count($stats) > 0)
                <div class="row g-4 mt-5">
                    @foreach($stats as $stat)
                        <div class="col">
                            <div class="text-center">
                                <h3 class="fw-bold text-primary mb-2">{{ $stat['number'] }}</h3>
                                <p class="text-muted mb-0">{{ $stat['label'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
                @endif
            </div>
            <div class="col-lg-6">
                <img src="{{ asset('img/hero-image.jpg') }}" alt="Hero Image" class="img-fluid rounded-3 shadow-lg">
            </div>
        </div>
    </div>
</section>
