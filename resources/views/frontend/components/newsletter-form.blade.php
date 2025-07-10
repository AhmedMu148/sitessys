@php
    $title = $data['title'] ?? 'Stay Updated';
    $subtitle = $data['subtitle'] ?? 'Subscribe to our newsletter and never miss our latest updates.';
    $background = $data['background'] ?? 'primary';
@endphp

<section class="newsletter-section py-5 bg-{{ $background }} text-white">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <h3 class="fw-bold mb-2">{{ $title }}</h3>
                <p class="mb-0 opacity-75">{{ $subtitle }}</p>
            </div>
            <div class="col-lg-6">
                <div class="row g-2">
                    <div class="col-md-8">
                        <input type="email" class="form-control form-control-lg" placeholder="Enter your email address" required>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-light btn-lg w-100">Subscribe</button>
                    </div>
                </div>
                <small class="text-white-50 mt-2 d-block">We respect your privacy. Unsubscribe at any time.</small>
            </div>
        </div>
    </div>
</section>
