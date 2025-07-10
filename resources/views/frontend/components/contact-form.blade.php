@php
    $title = $data['title'] ?? 'Contact Us';
    $subtitle = $data['subtitle'] ?? "We'd love to hear from you. Send us a message and we'll respond as soon as possible.";
    $contact_info = $data['contact_info'] ?? [
        'address' => '123 Business Street, Suite 100, City, ST 12345',
        'email' => 'contact@example.com',
        'phone' => '+1 (234) 567-8900'
    ];
@endphp

<section class="contact-form-section py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">{{ $title }}</h2>
            <p class="lead text-muted">{{ $subtitle }}</p>
        </div>
        
        <div class="row g-5">
            <div class="col-lg-5">
                <div class="contact-info">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <h4 class="mb-4">Get in Touch</h4>
                            
                            <div class="d-flex mb-4">
                                <div class="flex-shrink-0">
                                    <i data-feather="map-pin" class="text-primary me-3" style="width: 24px; height: 24px;"></i>
                                </div>
                                <div class="flex-grow-1 ms-2">
                                    <h6 class="mb-1">Address</h6>
                                    <p class="text-muted mb-0">{{ $contact_info['address'] ?? '123 Business Street, Suite 100, City, ST 12345' }}</p>
                                </div>
                            </div>
                            
                            <div class="d-flex mb-4">
                                <div class="flex-shrink-0">
                                    <i data-feather="mail" class="text-primary me-3" style="width: 24px; height: 24px;"></i>
                                </div>
                                <div class="flex-grow-1 ms-2">
                                    <h6 class="mb-1">Email</h6>
                                    <p class="text-muted mb-0">{{ $contact_info['email'] ?? 'contact@example.com' }}</p>
                                </div>
                            </div>
                            
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <i data-feather="phone" class="text-primary me-3" style="width: 24px; height: 24px;"></i>
                                </div>
                                <div class="flex-grow-1 ms-2">
                                    <h6 class="mb-1">Phone</h6>
                                    <p class="text-muted mb-0">{{ $contact_info['phone'] ?? '+1 (234) 567-8900' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <form>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label">Your Name</label>
                                    <input type="text" class="form-control" id="name" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="email" class="form-label">Your Email</label>
                                    <input type="email" class="form-control" id="email" required>
                                </div>
                                <div class="col-12">
                                    <label for="subject" class="form-label">Subject</label>
                                    <input type="text" class="form-control" id="subject" required>
                                </div>
                                <div class="col-12">
                                    <label for="message" class="form-label">Message</label>
                                    <textarea class="form-control" id="message" rows="5" required></textarea>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary btn-lg">Send Message</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
