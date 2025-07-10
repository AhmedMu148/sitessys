@php
    $title = $data['title'] ?? 'Ready to Get Started?';
    $subtitle = $data['subtitle'] ?? 'Join thousands of satisfied customers who are already using our services';
    $primary_button = $data['primary_button'] ?? ['text' => 'Get Started', 'url' => '/contact'];
    $secondary_button = $data['secondary_button'] ?? ['text' => 'Learn More', 'url' => '/about'];
    $background = $data['background'] ?? 'primary';
@endphp

<section class="cta-section py-5 bg-{{ $background }}">
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-lg-8">
                <h2 class="text-white fw-bold mb-3">{{ $title }}</h2>
                <p class="lead text-white-50 mb-4">{{ $subtitle }}</p>
                <div class="d-flex justify-content-center gap-3">
                    <a href="{{ $primary_button['url'] ?? '/contact' }}" class="btn btn-light btn-lg px-4">
                        {{ $primary_button['text'] ?? 'Get Started' }}
                    </a>
                    @if(isset($secondary_button['url'], $secondary_button['text']))
                        <a href="{{ $secondary_button['url'] }}" class="btn btn-outline-light btn-lg px-4">
                            {{ $secondary_button['text'] }}
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
