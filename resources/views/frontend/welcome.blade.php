<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome - Laravel Multi-Tenant System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .feature-box {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
            transition: transform 0.3s ease;
        }
        .feature-box:hover {
            transform: translateY(-5px);
        }
        .feature-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">
                <i class="fas fa-cube me-2"></i>
                Laravel Multi-Tenant
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="/login">
                    <i class="fas fa-sign-in-alt me-1"></i>
                    Login
                </a>
                <a class="nav-link" href="/admin">
                    <i class="fas fa-cog me-1"></i>
                    Admin
                </a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section text-white">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold mb-4">
                        Welcome to Laravel Multi-Tenant System
                    </h1>
                    <p class="lead mb-4">
                        A powerful Laravel application with multi-tenant support, template management, 
                        and role-based authentication. Perfect for managing multiple websites from a single platform.
                    </p>
                    <div class="d-flex gap-3">
                        <a href="/login" class="btn btn-light btn-lg">
                            <i class="fas fa-sign-in-alt me-2"></i>
                            Get Started
                        </a>
                        <a href="/admin" class="btn btn-outline-light btn-lg">
                            <i class="fas fa-cog me-2"></i>
                            Admin Panel
                        </a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="text-center">
                        <i class="fas fa-rocket fa-10x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center mb-5">
                    <h2 class="display-5 fw-bold">System Features</h2>
                    <p class="lead">Everything you need for a modern multi-tenant application</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="feature-box text-center">
                        <div class="feature-icon">
                            <i class="fas fa-users fa-2x text-white"></i>
                        </div>
                        <h4>Multi-Tenant</h4>
                        <p>Support for multiple tenants with domain and subdomain routing. Each tenant can have their own customized website.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-box text-center">
                        <div class="feature-icon">
                            <i class="fas fa-shield-alt fa-2x text-white"></i>
                        </div>
                        <h4>Laravel Sanctum</h4>
                        <p>Secure authentication with Laravel Sanctum for both web and API access. Token-based authentication for maximum security.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-box text-center">
                        <div class="feature-icon">
                            <i class="fas fa-user-shield fa-2x text-white"></i>
                        </div>
                        <h4>Role-Based Access</h4>
                        <p>Spatie Laravel-Permission integration with granular role and permission management for different user types.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-box text-center">
                        <div class="feature-icon">
                            <i class="fas fa-paint-brush fa-2x text-white"></i>
                        </div>
                        <h4>Template Management</h4>
                        <p>Powerful template management system with CRUD operations, activation, cloning, and preview capabilities.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-box text-center">
                        <div class="feature-icon">
                            <i class="fas fa-tachometer-alt fa-2x text-white"></i>
                        </div>
                        <h4>AdminLTE Dashboard</h4>
                        <p>Modern and responsive admin dashboard built with AdminLTE 3 for easy content and system management.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-box text-center">
                        <div class="feature-icon">
                            <i class="fas fa-api fa-2x text-white"></i>
                        </div>
                        <h4>RESTful API</h4>
                        <p>Complete API endpoints with authentication, rate limiting, and comprehensive error handling for integrations.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Quick Start Section -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center">
                    <h2 class="display-5 fw-bold mb-4">Quick Start</h2>
                    <p class="lead mb-4">Get started with these test accounts to explore the system</p>
                    
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="card border-primary">
                                <div class="card-body">
                                    <h5 class="card-title text-primary">
                                        <i class="fas fa-user-shield me-2"></i>
                                        Super Admin
                                    </h5>
                                    <p class="card-text">
                                        <strong>Email:</strong> admin@example.com<br>
                                        <strong>Password:</strong> password
                                    </p>
                                    <small class="text-muted">Full system access and management</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <div class="card border-success">
                                <div class="card-body">
                                    <h5 class="card-title text-success">
                                        <i class="fas fa-user-cog me-2"></i>
                                        Demo Admin
                                    </h5>
                                    <p class="card-text">
                                        <strong>Email:</strong> demo@example.com<br>
                                        <strong>Password:</strong> password
                                    </p>
                                    <small class="text-muted">Admin dashboard and template management</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <a href="/login" class="btn btn-primary btn-lg me-3">
                            <i class="fas fa-sign-in-alt me-2"></i>
                            Login Now
                        </a>
                        <a href="/admin" class="btn btn-outline-primary btn-lg">
                            <i class="fas fa-external-link-alt me-2"></i>
                            Go to Admin
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4">
        <div class="container text-center">
            <p class="mb-0">
                &copy; {{ date('Y') }} Laravel Multi-Tenant System. 
                Built with <i class="fas fa-heart text-danger"></i> using Laravel {{ app()->version() }}
            </p>
            <small class="text-muted">
                <i class="fas fa-server me-1"></i>
                Environment: {{ app()->environment() }} | 
                <i class="fas fa-database me-1"></i>
                Database: Connected
            </small>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
