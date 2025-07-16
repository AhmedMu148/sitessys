<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\UserTemplate;
use Illuminate\Support\Facades\Hash;

class DefaultDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Only create default templates - users are handled by SingleSiteSeeder
        $this->createDefaultTemplates();
    }

    /**
     * Create default templates
     */
    private function createDefaultTemplates()
    {
        // Business Template
        UserTemplate::firstOrCreate(
            ['name' => 'Business Template'],
            [
                'user_id' => null, // Global template
                'description' => 'Professional business website template',
                'html_content' => $this->getBusinessTemplateHtml(),
                'css_content' => $this->getBusinessTemplateCss(),
                'is_active' => false,
                'is_default' => true,
            ]
        );

        // Portfolio Template
        UserTemplate::firstOrCreate(
            ['name' => 'Portfolio Template'],
            [
                'user_id' => null, // Global template
                'description' => 'Creative portfolio website template',
                'html_content' => $this->getPortfolioTemplateHtml(),
                'css_content' => $this->getPortfolioTemplateCss(),
                'is_active' => false,
                'is_default' => true,
            ]
        );

        // Blog Template
        UserTemplate::firstOrCreate(
            ['name' => 'Blog Template'],
            [
                'user_id' => null, // Global template
                'description' => 'Clean blog website template',
                'html_content' => $this->getBlogTemplateHtml(),
                'css_content' => $this->getBlogTemplateCss(),
                'is_active' => false,
                'is_default' => true,
            ]
        );
    }

    private function getBusinessTemplateHtml()
    {
        return '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{site_title}} - Professional Services</title>
    <meta name="description" content="{{site_description}}">
</head>
<body>
    <header class="header">
        <nav class="navbar">
            <div class="container">
                <div class="nav-brand">
                    <img src="{{site_logo}}" alt="{{site_title}}" class="logo">
                    <h1>{{site_title}}</h1>
                </div>
                <ul class="nav-menu">
                    <li><a href="#home">Home</a></li>
                    <li><a href="#services">Services</a></li>
                    <li><a href="#about">About</a></li>
                    <li><a href="#contact">Contact</a></li>
                </ul>
            </div>
        </nav>
    </header>

    <main>
        <section id="home" class="hero">
            <div class="container">
                <div class="hero-content">
                    <h2>Welcome to {{site_title}}</h2>
                    <p class="hero-subtitle">{{site_description}}</p>
                    <div class="hero-buttons">
                        <a href="#services" class="btn btn-primary">Our Services</a>
                        <a href="#contact" class="btn btn-secondary">Get in Touch</a>
                    </div>
                </div>
            </div>
        </section>

        <section id="services" class="services">
            <div class="container">
                <h2>Our Services</h2>
                <div class="services-grid">
                    <div class="service-card">
                        <h3>Consulting</h3>
                        <p>Expert advice for your business needs</p>
                    </div>
                    <div class="service-card">
                        <h3>Development</h3>
                        <p>Custom solutions tailored to you</p>
                    </div>
                    <div class="service-card">
                        <h3>Support</h3>
                        <p>24/7 support when you need it</p>
                    </div>
                </div>
            </div>
        </section>

        <section id="about" class="about">
            <div class="container">
                <div class="about-content">
                    <h2>About Us</h2>
                    <p>We are a professional team dedicated to delivering exceptional results for our clients.</p>
                </div>
            </div>
        </section>

        <section id="contact" class="contact">
            <div class="container">
                <h2>Contact Us</h2>
                <div class="contact-info">
                    <div class="contact-item">
                        <strong>Email:</strong> {{contact_email}}
                    </div>
                    <div class="contact-item">
                        <strong>Phone:</strong> {{contact_phone}}
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer class="footer">
        <div class="container">
            <p>&copy; 2025 {{site_title}}. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>';
    }

    private function getBusinessTemplateCss()
    {
        return '* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
    line-height: 1.6;
    color: #333;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

.header {
    background: #fff;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    position: sticky;
    top: 0;
    z-index: 100;
}

.navbar {
    padding: 1rem 0;
}

.navbar .container {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.nav-brand {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.logo {
    height: 40px;
}

.nav-brand h1 {
    color: #2c3e50;
    font-size: 1.5rem;
}

.nav-menu {
    display: flex;
    list-style: none;
    gap: 2rem;
}

.nav-menu a {
    text-decoration: none;
    color: #333;
    font-weight: 500;
    transition: color 0.3s;
}

.nav-menu a:hover {
    color: #3498db;
}

.hero {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 5rem 0;
    text-align: center;
}

.hero-content h2 {
    font-size: 3rem;
    margin-bottom: 1rem;
}

.hero-subtitle {
    font-size: 1.2rem;
    margin-bottom: 2rem;
    opacity: 0.9;
}

.hero-buttons {
    display: flex;
    gap: 1rem;
    justify-content: center;
}

.btn {
    display: inline-block;
    padding: 12px 30px;
    text-decoration: none;
    border-radius: 5px;
    font-weight: 600;
    transition: all 0.3s;
}

.btn-primary {
    background: #3498db;
    color: white;
}

.btn-primary:hover {
    background: #2980b9;
}

.btn-secondary {
    background: transparent;
    color: white;
    border: 2px solid white;
}

.btn-secondary:hover {
    background: white;
    color: #333;
}

.services {
    padding: 4rem 0;
    background: #f8f9fa;
}

.services h2 {
    text-align: center;
    margin-bottom: 3rem;
    font-size: 2.5rem;
    color: #2c3e50;
}

.services-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
}

.service-card {
    background: white;
    padding: 2rem;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    text-align: center;
    transition: transform 0.3s;
}

.service-card:hover {
    transform: translateY(-5px);
}

.service-card h3 {
    color: #2c3e50;
    margin-bottom: 1rem;
}

.about {
    padding: 4rem 0;
}

.about h2 {
    text-align: center;
    margin-bottom: 2rem;
    font-size: 2.5rem;
    color: #2c3e50;
}

.about-content {
    text-align: center;
    max-width: 800px;
    margin: 0 auto;
}

.contact {
    padding: 4rem 0;
    background: #f8f9fa;
}

.contact h2 {
    text-align: center;
    margin-bottom: 3rem;
    font-size: 2.5rem;
    color: #2c3e50;
}

.contact-info {
    display: flex;
    justify-content: center;
    gap: 3rem;
}

.contact-item {
    text-align: center;
}

.footer {
    background: #2c3e50;
    color: white;
    text-align: center;
    padding: 2rem 0;
}

@media (max-width: 768px) {
    .hero-buttons {
        flex-direction: column;
        align-items: center;
    }
    
    .contact-info {
        flex-direction: column;
        gap: 1rem;
    }
    
    .nav-menu {
        flex-direction: column;
        gap: 1rem;
    }
}';
    }

    private function getPortfolioTemplateHtml()
    {
        return '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{site_title}} - Creative Portfolio</title>
    <meta name="description" content="{{site_description}}">
</head>
<body>
    <header class="header">
        <nav class="navbar">
            <div class="container">
                <div class="nav-brand">
                    <h1>{{site_title}}</h1>
                </div>
                <ul class="nav-menu">
                    <li><a href="#home">Home</a></li>
                    <li><a href="#portfolio">Portfolio</a></li>
                    <li><a href="#about">About</a></li>
                    <li><a href="#contact">Contact</a></li>
                </ul>
            </div>
        </nav>
    </header>

    <main>
        <section id="home" class="hero">
            <div class="container">
                <div class="hero-content">
                    <h2>Creative Design & Development</h2>
                    <p>{{site_description}}</p>
                    <a href="#portfolio" class="btn">View My Work</a>
                </div>
            </div>
        </section>

        <section id="portfolio" class="portfolio">
            <div class="container">
                <h2>My Portfolio</h2>
                <div class="portfolio-grid">
                    <div class="portfolio-item">
                        <img src="/images/portfolio-1.jpg" alt="Project 1">
                        <div class="portfolio-overlay">
                            <h3>Project Title</h3>
                            <p>Brief description</p>
                        </div>
                    </div>
                    <div class="portfolio-item">
                        <img src="/images/portfolio-2.jpg" alt="Project 2">
                        <div class="portfolio-overlay">
                            <h3>Project Title</h3>
                            <p>Brief description</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="about" class="about">
            <div class="container">
                <h2>About Me</h2>
                <p>I am a passionate designer and developer with years of experience creating amazing digital experiences.</p>
            </div>
        </section>

        <section id="contact" class="contact">
            <div class="container">
                <h2>Get In Touch</h2>
                <p>Email: {{contact_email}}</p>
                <p>Phone: {{contact_phone}}</p>
            </div>
        </section>
    </main>

    <footer class="footer">
        <div class="container">
            <p>&copy; 2025 {{site_title}}. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>';
    }

    private function getPortfolioTemplateCss()
    {
        return '* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: "Helvetica Neue", Arial, sans-serif;
    line-height: 1.6;
    color: #333;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

.header {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    position: fixed;
    top: 0;
    width: 100%;
    z-index: 100;
}

.navbar {
    padding: 1rem 0;
}

.navbar .container {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.nav-brand h1 {
    color: #222;
    font-size: 1.5rem;
    font-weight: 300;
}

.nav-menu {
    display: flex;
    list-style: none;
    gap: 2rem;
}

.nav-menu a {
    text-decoration: none;
    color: #333;
    font-weight: 300;
    transition: color 0.3s;
}

.nav-menu a:hover {
    color: #ff6b6b;
}

.hero {
    background: linear-gradient(45deg, #ff6b6b, #4ecdc4);
    color: white;
    padding: 8rem 0 4rem;
    text-align: center;
    min-height: 100vh;
    display: flex;
    align-items: center;
}

.hero-content h2 {
    font-size: 3.5rem;
    margin-bottom: 1rem;
    font-weight: 300;
}

.hero-content p {
    font-size: 1.2rem;
    margin-bottom: 2rem;
    opacity: 0.9;
}

.btn {
    display: inline-block;
    background: rgba(255, 255, 255, 0.2);
    color: white;
    padding: 15px 30px;
    text-decoration: none;
    border-radius: 50px;
    border: 2px solid white;
    transition: all 0.3s;
}

.btn:hover {
    background: white;
    color: #333;
}

.portfolio {
    padding: 4rem 0;
}

.portfolio h2 {
    text-align: center;
    margin-bottom: 3rem;
    font-size: 2.5rem;
    font-weight: 300;
    color: #222;
}

.portfolio-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    gap: 2rem;
}

.portfolio-item {
    position: relative;
    overflow: hidden;
    border-radius: 10px;
    height: 300px;
}

.portfolio-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s;
}

.portfolio-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.8);
    color: white;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    opacity: 0;
    transition: opacity 0.3s;
}

.portfolio-item:hover .portfolio-overlay {
    opacity: 1;
}

.portfolio-item:hover img {
    transform: scale(1.1);
}

.about {
    padding: 4rem 0;
    background: #f8f9fa;
    text-align: center;
}

.about h2 {
    margin-bottom: 2rem;
    font-size: 2.5rem;
    font-weight: 300;
    color: #222;
}

.contact {
    padding: 4rem 0;
    text-align: center;
}

.contact h2 {
    margin-bottom: 2rem;
    font-size: 2.5rem;
    font-weight: 300;
    color: #222;
}

.footer {
    background: #222;
    color: white;
    text-align: center;
    padding: 2rem 0;
}';
    }

    private function getBlogTemplateHtml()
    {
        return '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{site_title}} - Blog</title>
    <meta name="description" content="{{site_description}}">
</head>
<body>
    <header class="header">
        <div class="container">
            <nav class="navbar">
                <h1>{{site_title}}</h1>
                <ul class="nav-menu">
                    <li><a href="#home">Home</a></li>
                    <li><a href="#blog">Blog</a></li>
                    <li><a href="#about">About</a></li>
                    <li><a href="#contact">Contact</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <section id="home" class="hero">
            <div class="container">
                <h2>{{site_title}}</h2>
                <p>{{site_description}}</p>
            </div>
        </section>

        <section id="blog" class="blog">
            <div class="container">
                <h2>Latest Posts</h2>
                <div class="blog-grid">
                    <article class="blog-post">
                        <h3>Sample Blog Post</h3>
                        <p class="post-meta">January 15, 2025</p>
                        <p>This is a sample blog post content...</p>
                        <a href="#" class="read-more">Read More</a>
                    </article>
                    <article class="blog-post">
                        <h3>Another Blog Post</h3>
                        <p class="post-meta">January 10, 2025</p>
                        <p>This is another sample blog post content...</p>
                        <a href="#" class="read-more">Read More</a>
                    </article>
                </div>
            </div>
        </section>

        <section id="about" class="about">
            <div class="container">
                <h2>About This Blog</h2>
                <p>Welcome to my blog where I share thoughts, insights, and experiences.</p>
            </div>
        </section>

        <section id="contact" class="contact">
            <div class="container">
                <h2>Contact</h2>
                <p>Email: {{contact_email}}</p>
                <p>Phone: {{contact_phone}}</p>
            </div>
        </section>
    </main>

    <footer class="footer">
        <div class="container">
            <p>&copy; 2025 {{site_title}}. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>';
    }

    private function getBlogTemplateCss()
    {
        return '* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: Georgia, serif;
    line-height: 1.6;
    color: #333;
}

.container {
    max-width: 800px;
    margin: 0 auto;
    padding: 0 20px;
}

.header {
    background: #fff;
    border-bottom: 1px solid #eee;
    padding: 1rem 0;
}

.navbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.navbar h1 {
    color: #333;
    font-size: 1.8rem;
    font-weight: normal;
}

.nav-menu {
    display: flex;
    list-style: none;
    gap: 2rem;
}

.nav-menu a {
    text-decoration: none;
    color: #333;
    transition: color 0.3s;
}

.nav-menu a:hover {
    color: #0066cc;
}

.hero {
    padding: 3rem 0;
    text-align: center;
    background: #f8f9fa;
}

.hero h2 {
    font-size: 2.5rem;
    margin-bottom: 1rem;
    color: #333;
}

.blog {
    padding: 3rem 0;
}

.blog h2 {
    margin-bottom: 2rem;
    color: #333;
    border-bottom: 2px solid #0066cc;
    padding-bottom: 0.5rem;
}

.blog-grid {
    display: flex;
    flex-direction: column;
    gap: 2rem;
}

.blog-post {
    border: 1px solid #eee;
    padding: 2rem;
    border-radius: 5px;
}

.blog-post h3 {
    margin-bottom: 0.5rem;
    color: #333;
}

.post-meta {
    color: #666;
    font-size: 0.9rem;
    margin-bottom: 1rem;
}

.read-more {
    color: #0066cc;
    text-decoration: none;
    font-weight: bold;
}

.read-more:hover {
    text-decoration: underline;
}

.about, .contact {
    padding: 3rem 0;
    text-align: center;
}

.about {
    background: #f8f9fa;
}

.about h2, .contact h2 {
    margin-bottom: 1rem;
    color: #333;
}

.footer {
    background: #333;
    color: white;
    text-align: center;
    padding: 2rem 0;
}';
    }
}
