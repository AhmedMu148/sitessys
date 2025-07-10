@php
    $title = $data['title'] ?? 'Get a Quote';
    $subtitle = $data['subtitle'] ?? 'Tell us about your project and we\'ll provide a detailed quote.';
    $services = $data['services'] ?? [
        'Web Development',
        'Mobile App Development',
        'UI/UX Design',
        'Digital Marketing',
        'E-commerce Solutions',
        'Custom Software'
    ];
@endphp

<section class="quote-form-section py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">{{ $title }}</h2>
            <p class="lead text-muted">{{ $subtitle }}</p>
        </div>
        
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card border-0 shadow">
                    <div class="card-body p-5">
                        <form>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="quote-name" class="form-label">Full Name *</label>
                                    <input type="text" class="form-control" id="quote-name" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="quote-email" class="form-label">Email Address *</label>
                                    <input type="email" class="form-control" id="quote-email" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="quote-company" class="form-label">Company Name</label>
                                    <input type="text" class="form-control" id="quote-company">
                                </div>
                                <div class="col-md-6">
                                    <label for="quote-phone" class="form-label">Phone Number</label>
                                    <input type="tel" class="form-control" id="quote-phone">
                                </div>
                                <div class="col-12">
                                    <label for="quote-service" class="form-label">Service Interested In *</label>
                                    <select class="form-select" id="quote-service" required>
                                        <option value="">Select a service</option>
                                        @foreach($services as $service)
                                            <option value="{{ $service }}">{{ $service }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="quote-budget" class="form-label">Budget Range</label>
                                    <select class="form-select" id="quote-budget">
                                        <option value="">Select budget range</option>
                                        <option value="under-5k">Under $5,000</option>
                                        <option value="5k-10k">$5,000 - $10,000</option>
                                        <option value="10k-25k">$10,000 - $25,000</option>
                                        <option value="25k-50k">$25,000 - $50,000</option>
                                        <option value="over-50k">Over $50,000</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="quote-timeline" class="form-label">Project Timeline</label>
                                    <select class="form-select" id="quote-timeline">
                                        <option value="">Select timeline</option>
                                        <option value="asap">ASAP</option>
                                        <option value="1-3-months">1-3 months</option>
                                        <option value="3-6-months">3-6 months</option>
                                        <option value="6-12-months">6-12 months</option>
                                        <option value="flexible">Flexible</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label for="quote-description" class="form-label">Project Description *</label>
                                    <textarea class="form-control" id="quote-description" rows="5" placeholder="Please describe your project requirements, goals, and any specific features you need." required></textarea>
                                </div>
                                <div class="col-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="quote-terms" required>
                                        <label class="form-check-label" for="quote-terms">
                                            I agree to the <a href="/terms" target="_blank">Terms of Service</a> and <a href="/privacy" target="_blank">Privacy Policy</a>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary btn-lg">Get Quote</button>
                                    <small class="text-muted ms-3">We'll respond within 24 hours</small>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
