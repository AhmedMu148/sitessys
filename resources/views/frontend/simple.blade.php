<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ ucfirst($slug) }} - {{ $site->site_name ?? 'Site' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .hero-section {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            min-height: 60vh;
            display: flex;
            align-items: center;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand fw-bold" href="/">
                <i class="fas fa-globe me-2"></i>
                {{ $site->site_name ?? 'My Site' }}
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="/">Home</a>
                <a class="nav-link" href="/about">About</a>
                <a class="nav-link" href="/contact">Contact</a>
                @auth
                    <a class="nav-link" href="/admin">Admin</a>
                @else
                    <a class="nav-link" href="/login">Login</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section text-white">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center">
                    <h1 class="display-4 fw-bold mb-4">
                        @if($slug === 'home')
                            Welcome to {{ $site->site_name ?? 'My Site' }}
                        @else
                            {{ ucfirst($slug) }}
                        @endif
                    </h1>
                    <p class="lead mb-4">
                        @if($slug === 'home')
                            This site is powered by our Laravel Multi-Tenant system. 
                            The content for this page is being dynamically generated.
                        @else
                            You've accessed the {{ $slug }} page. This is a placeholder 
                            until custom content is configured in the admin panel.
                        @endif
                    </p>
                    @if($slug === 'home')
                        <a href="/admin" class="btn btn-light btn-lg">
                            <i class="fas fa-cog me-2"></i>
                            Configure Site
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- Content Section -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    @if($slug === 'home')
                        <h2 class="mb-4">Site Information</h2>
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <i class="fas fa-info-circle text-primary me-2"></i>
                                    Site Details
                                </h5>
                                <ul class="list-unstyled">
                                    <li><strong>Site Name:</strong> {{ $site->site_name ?? 'Not configured' }}</li>
                                    <li><strong>Status:</strong> 
                                        @if($site->status ?? false)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-warning">Inactive</span>
                                        @endif
                                    </li>
                                    <li><strong>Created:</strong> {{ $site->created_at ? $site->created_at->format('M d, Y') : 'N/A' }}</li>
                                </ul>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-4">
                                <div class="card border-primary">
                                    <div class="card-body text-center">
                                        <i class="fas fa-paint-brush fa-3x text-primary mb-3"></i>
                                        <h5>Templates</h5>
                                        <p class="card-text">Customize your site appearance with our template system.</p>
                                        <a href="/admin/templates" class="btn btn-outline-primary">Manage Templates</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card border-success">
                                    <div class="card-body text-center">
                                        <i class="fas fa-file-alt fa-3x text-success mb-3"></i>
                                        <h5>Pages</h5>
                                        <p class="card-text">Create and manage your website pages and content.</p>
                                        <a href="/admin/pages" class="btn btn-outline-success">Manage Pages</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card border-info">
                                    <div class="card-body text-center">
                                        <i class="fas fa-cog fa-3x text-info mb-3"></i>
                                        <h5>Settings</h5>
                                        <p class="card-text">Configure your site settings and preferences.</p>
                                        <a href="/admin/config" class="btn btn-outline-info">Site Settings</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <h2 class="mb-4">{{ ucfirst($slug) }} Page</h2>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Page Configuration Needed:</strong> 
                            This page ({{ $slug }}) doesn't have custom content configured yet. 
                            Please visit the <a href="/admin" class="alert-link">admin panel</a> to set up content for this page.
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">What you can do:</h5>
                                <ul>
                                    <li>Create custom content for this page in the admin panel</li>
                                    <li>Add sections, layouts, and designs</li>
                                    <li>Configure page-specific settings</li>
                                    <li>Set up navigation and menus</li>
                                </ul>
                                <div class="mt-3">
                                    <a href="/admin/pages" class="btn btn-primary">
                                        <i class="fas fa-plus me-2"></i>
                                        Create Page Content
                                    </a>
                                    <a href="/" class="btn btn-outline-secondary ms-2">
                                        <i class="fas fa-home me-2"></i>
                                        Back to Home
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4">
        <div class="container text-center">
            <p class="mb-0">
                &copy; {{ date('Y') }} {{ $site->site_name ?? 'My Site' }}. 
                Powered by Laravel Multi-Tenant System.
            </p>
            <small class="text-muted">
                <i class="fas fa-globe me-1"></i>
                Multi-Tenant Platform | 
                <i class="fas fa-cog me-1"></i>
                <a href="/admin" class="text-light">Admin Panel</a>
            </small>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
