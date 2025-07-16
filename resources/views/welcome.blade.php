<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Template Management System</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .hero-section {
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
        }
        .btn-custom {
            border-radius: 50px;
            padding: 12px 30px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        .btn-primary-custom {
            background: linear-gradient(45deg, #667eea, #764ba2);
            border: none;
            color: white;
        }
        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
            color: white;
        }
        .btn-outline-custom {
            border: 2px solid #667eea;
            color: #667eea;
            background: transparent;
        }
        .btn-outline-custom:hover {
            background: #667eea;
            color: white;
            transform: translateY(-2px);
        }
        .btn-success-custom {
            background: linear-gradient(45deg, #48bb78, #38a169);
            border: none;
            color: white;
        }
        .btn-success-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(72, 187, 120, 0.4);
            color: white;
        }
        .navbar-custom {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 20px rgba(0,0,0,0.1);
        }
        .feature-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(45deg, #667eea, #764ba2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
            margin: 0 auto 20px;
        }
        .user-welcome {
            background: linear-gradient(45deg, #48bb78, #38a169);
            color: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 30px;
        }
    </style>
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top navbar-custom">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ url('/') }}">
                <i class="fas fa-layer-group me-2"></i>
                Template Management System
            </a>
            
            <div class="navbar-nav ms-auto">
                @auth
                    @if(auth()->user()->hasAnyRole(['super-admin', 'admin', 'team-member']))
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-primary-custom btn-custom me-2">
                            <i class="fas fa-tachometer-alt me-1"></i> Admin Panel
                        </a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-custom btn-custom">
                            <i class="fas fa-sign-out-alt me-1"></i> Logout
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-success-custom btn-custom me-2">
                        <i class="fas fa-user me-1"></i> User Login
                    </a>
                    <a href="{{ route('admin.login') }}" class="btn btn-primary-custom btn-custom me-2">
                        <i class="fas fa-shield-alt me-1"></i> Admin Login
                    </a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn btn-outline-custom btn-custom">
                            <i class="fas fa-user-plus me-1"></i> Register
                        </a>
                    @endif
                @endauth
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-body p-5 text-center">
                            @auth
                                <!-- Welcome message for authenticated users -->
                                <div class="user-welcome">
                                    <h2 class="mb-2">
                                        <i class="fas fa-hand-wave me-2"></i>
                                        Welcome back, {{ auth()->user()->name }}!
                                    </h2>
                                    <p class="mb-0">
                                        You're logged in as: <strong>{{ ucfirst(auth()->user()->role) }}</strong>
                                    </p>
                                </div>

                                <div class="mb-4">
                                    <div class="feature-icon">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                    <h3 class="fw-bold text-dark">You're all set!</h3>
                                    <p class="text-muted">
                                        Your account is active and ready to use. 
                                        @if(auth()->user()->hasAnyRole(['super-admin', 'admin', 'team-member']))
                                            Access the admin panel to manage templates, pages, and content.
                                        @else
                                            Explore our platform features and start creating.
                                        @endif
                                    </p>
                                </div>

                                <div class="d-grid gap-3 d-md-flex justify-content-md-center">
                                    @if(auth()->user()->hasAnyRole(['super-admin', 'admin', 'team-member']))
                                        <a href="{{ route('admin.dashboard') }}" class="btn btn-primary-custom btn-custom btn-lg">
                                            <i class="fas fa-tachometer-alt me-2"></i>
                                            Go to Admin Dashboard
                                        </a>
                                    @endif
                                    
                                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-custom btn-custom btn-lg">
                                            <i class="fas fa-sign-out-alt me-2"></i>
                                            Logout
                                        </button>
                                    </form>
                                </div>
                            @else
                                <!-- Welcome message for guests -->
                                <div class="mb-4">
                                    <div class="feature-icon">
                                        <i class="fas fa-layer-group"></i>
                                    </div>
                                    <h1 class="display-5 fw-bold text-dark mb-3">
                                        Template Management System
                                    </h1>
                                    <p class="lead text-muted mb-4">
                                        A powerful platform for managing website templates, layouts, and content. 
                                        Create beautiful, responsive designs with our intuitive admin interface.
                                    </p>
                                </div>

                                <div class="row g-4 mb-5">
                                    <div class="col-md-4">
                                        <div class="text-center">
                                            <i class="fas fa-palette text-primary fs-2 mb-3"></i>
                                            <h5 class="fw-semibold">Design Templates</h5>
                                            <p class="text-muted small">Create and customize beautiful layouts</p>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="text-center">
                                            <i class="fas fa-cogs text-success fs-2 mb-3"></i>
                                            <h5 class="fw-semibold">Easy Management</h5>
                                            <p class="text-muted small">Intuitive admin panel for content control</p>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="text-center">
                                            <i class="fas fa-mobile-alt text-warning fs-2 mb-3"></i>
                                            <h5 class="fw-semibold">Responsive Design</h5>
                                            <p class="text-muted small">Mobile-first approach for all devices</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-grid gap-3 d-md-flex justify-content-md-center">
                                    <a href="{{ route('register') }}" class="btn btn-success-custom btn-custom btn-lg">
                                        <i class="fas fa-user-plus me-2"></i>
                                        Get Started - Register
                                    </a>
                                    <a href="{{ route('login') }}" class="btn btn-outline-custom btn-custom btn-lg">
                                        <i class="fas fa-sign-in-alt me-2"></i>
                                        Already have an account?
                                    </a>
                                </div>

                                <div class="mt-4 pt-4 border-top">
                                    <p class="text-muted small mb-2">
                                        <i class="fas fa-shield-alt me-1"></i>
                                        Administrator? 
                                    </p>
                                    <a href="{{ route('admin.login') }}" class="btn btn-primary-custom btn-custom">
                                        <i class="fas fa-key me-2"></i>
                                        Admin Login
                                    </a>
                                </div>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
