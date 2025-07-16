<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UserTemplate;
use App\Models\User;

class DefaultTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a master template that will be used as default for new users
        $defaultTemplate = UserTemplate::updateOrCreate(
            ['user_id' => null, 'name' => 'Master Default Template'],
            [
                'user_id' => null, // Master template belongs to no specific user
                'name' => 'Master Default Template',
                'description' => 'Default template used for new user registrations',
                'html_content' => $this->getDefaultHtmlContent(),
                'css_content' => $this->getDefaultCssContent(),
                'js_content' => $this->getDefaultJsContent(),
                'config' => [
                    'template_type' => 'business',
                    'color_scheme' => 'modern',
                    'layout' => 'responsive',
                    'features' => [
                        'multi_language',
                        'responsive_design',
                        'contact_form',
                        'social_links',
                        'seo_optimized'
                    ]
                ],
                'is_active' => false,
                'is_default' => true, // Mark as master default template
                'preview_image' => 'templates/default-preview.jpg'
            ]
        );

        $this->command->info('✅ Default master template created: ' . $defaultTemplate->name);
    }

    private function getDefaultHtmlContent(): string
    {
        return '<!DOCTYPE html>
<html lang="{{lang_code}}" dir="{{lang_direction}}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{site_title}}</title>
    <meta name="description" content="{{site_description}}">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm fixed-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="/">
                {{site_title}}
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="#home">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="#about">About</a></li>
                    <li class="nav-item"><a class="nav-link" href="#services">Services</a></li>
                    <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="hero-section bg-primary text-white py-5 mt-5">
        <div class="container">
            <div class="row align-items-center min-vh-75">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold mb-4">{{hero_title}}</h1>
                    <p class="lead mb-4">{{hero_description}}</p>
                    <a href="#contact" class="btn btn-light btn-lg">{{hero_button_text}}</a>
                </div>
                <div class="col-lg-6">
                    <img src="{{hero_image}}" alt="Hero Image" class="img-fluid rounded">
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <h2 class="mb-4">{{about_title}}</h2>
                    <p class="mb-4">{{about_description}}</p>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-check text-primary me-2"></i> {{about_feature_1}}</li>
                        <li><i class="fas fa-check text-primary me-2"></i> {{about_feature_2}}</li>
                        <li><i class="fas fa-check text-primary me-2"></i> {{about_feature_3}}</li>
                    </ul>
                </div>
                <div class="col-lg-6">
                    <img src="{{about_image}}" alt="About Image" class="img-fluid rounded">
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2>{{services_title}}</h2>
                <p class="lead">{{services_description}}</p>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card h-100 text-center border-0 shadow-sm">
                        <div class="card-body">
                            <i class="fas fa-laptop-code fa-3x text-primary mb-3"></i>
                            <h5 class="card-title">{{service_1_title}}</h5>
                            <p class="card-text">{{service_1_description}}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 text-center border-0 shadow-sm">
                        <div class="card-body">
                            <i class="fas fa-mobile-alt fa-3x text-primary mb-3"></i>
                            <h5 class="card-title">{{service_2_title}}</h5>
                            <p class="card-text">{{service_2_description}}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 text-center border-0 shadow-sm">
                        <div class="card-body">
                            <i class="fas fa-chart-line fa-3x text-primary mb-3"></i>
                            <h5 class="card-title">{{service_3_title}}</h5>
                            <p class="card-text">{{service_3_description}}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2>{{contact_title}}</h2>
                <p class="lead">{{contact_description}}</p>
            </div>
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <form class="contact-form">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <input type="text" class="form-control" placeholder="Your Name" required>
                            </div>
                            <div class="col-md-6">
                                <input type="email" class="form-control" placeholder="Your Email" required>
                            </div>
                            <div class="col-12">
                                <input type="text" class="form-control" placeholder="Subject" required>
                            </div>
                            <div class="col-12">
                                <textarea class="form-control" rows="5" placeholder="Your Message" required></textarea>
                            </div>
                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-primary btn-lg">Send Message</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="mb-0">&copy; 2025 {{site_title}}. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <div class="social-links">
                        <a href="{{social_facebook}}" class="text-white me-3"><i class="fab fa-facebook"></i></a>
                        <a href="{{social_twitter}}" class="text-white me-3"><i class="fab fa-twitter"></i></a>
                        <a href="{{social_linkedin}}" class="text-white me-3"><i class="fab fa-linkedin"></i></a>
                        <a href="{{social_instagram}}" class="text-white"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>';
    }

    private function getDefaultCssContent(): string
    {
        return ':root {
    --primary-color: #4361ee;
    --secondary-color: #3f4e66;
    --success-color: #2ec971;
    --danger-color: #ef476f;
    --info-color: #4cc9f0;
    --warning-color: #ffd166;
    --light-color: #f8f9fa;
    --dark-color: #212529;
}

body {
    font-family: "Inter", sans-serif;
    line-height: 1.6;
    color: var(--dark-color);
}

.navbar-brand {
    color: var(--primary-color) !important;
}

.bg-primary {
    background-color: var(--primary-color) !important;
}

.btn-primary {
    background-color: var(--primary-color);
    border-color: var(--primary-color);
}

.btn-primary:hover {
    background-color: #3448d1;
    border-color: #3448d1;
}

.text-primary {
    color: var(--primary-color) !important;
}

.hero-section {
    background: linear-gradient(135deg, var(--primary-color) 0%, #7209b7 100%);
}

.min-vh-75 {
    min-height: 75vh;
}

.card {
    transition: transform 0.3s ease;
}

.card:hover {
    transform: translateY(-5px);
}

.social-links a {
    transition: opacity 0.3s ease;
}

.social-links a:hover {
    opacity: 0.7;
}

.contact-form {
    background: white;
    padding: 2rem;
    border-radius: 10px;
    box-shadow: 0 0 30px rgba(0,0,0,0.1);
}

/* Smooth scrolling */
html {
    scroll-behavior: smooth;
}

/* Section padding */
section {
    padding-top: 5rem;
    padding-bottom: 5rem;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .display-4 {
        font-size: 2.5rem;
    }
    
    .hero-section {
        text-align: center;
    }
    
    .min-vh-75 {
        min-height: auto;
        padding: 3rem 0;
    }
}';
    }

    private function getDefaultJsContent(): string
    {
        return '// Smooth scrolling for navigation links
document.querySelectorAll(\'a[href^="#"]\').forEach(anchor => {
    anchor.addEventListener("click", function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute("href"));
        if (target) {
            target.scrollIntoView({
                behavior: "smooth",
                block: "start"
            });
        }
    });
});

// Contact form handling
document.querySelector(".contact-form").addEventListener("submit", function(e) {
    e.preventDefault();
    
    // Get form data
    const formData = new FormData(this);
    
    // Show loading state
    const submitBtn = this.querySelector(\'button[type="submit"]\');
    const originalText = submitBtn.textContent;
    submitBtn.textContent = "Sending...";
    submitBtn.disabled = true;
    
    // Simulate form submission (replace with actual AJAX call)
    setTimeout(() => {
        alert("Thank you for your message! We will get back to you soon.");
        this.reset();
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
    }, 2000);
});

// Navbar background change on scroll
window.addEventListener("scroll", function() {
    const navbar = document.querySelector(".navbar");
    if (window.scrollY > 50) {
        navbar.classList.add("bg-white", "shadow");
    } else {
        navbar.classList.remove("shadow");
    }
});

// Animation on scroll
const observerOptions = {
    threshold: 0.1,
    rootMargin: "0px 0px -50px 0px"
};

const observer = new IntersectionObserver(function(entries) {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.opacity = "1";
            entry.target.style.transform = "translateY(0)";
        }
    });
}, observerOptions);

// Observe all cards and sections
document.querySelectorAll(".card, section").forEach(el => {
    el.style.opacity = "0";
    el.style.transform = "translateY(20px)";
    el.style.transition = "opacity 0.6s ease, transform 0.6s ease";
    observer.observe(el);
});';
    }
}
