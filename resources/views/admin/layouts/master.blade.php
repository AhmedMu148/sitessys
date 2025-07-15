<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Admin Dashboard - Template Management System">
    <meta name="author" content="AdminKit">
    <meta name="keywords" content="adminkit, bootstrap, bootstrap 5, admin, dashboard, template, responsive, css, sass, html, theme, front-end, ui kit, web">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="shortcut icon" href="{{ asset('img/icons/icon-48x48.png') }}" />

    <title>@yield('title', 'Admin Dashboard') - Template Management System</title>

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    
    @yield('css')
</head>

<body>
    <div class="wrapper">
        <nav id="sidebar" class="sidebar js-sidebar">
            <div class="sidebar-content js-simplebar">
                <a class="sidebar-brand" href="{{ route('admin.site-content.index') }}">
                    <span class="align-middle">AdminKit</span>
                </a>

                <ul class="sidebar-nav">
                    <li class="sidebar-header">
                        Main
                    </li>

                    <li class="sidebar-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('admin.dashboard') }}">
                            <i class="align-middle" data-feather="sliders"></i> <span class="align-middle">Dashboard</span>
                        </a>
                    </li>

                    <li class="sidebar-header">
                        Content Management
                                        </li>
                                <li class="sidebar-item {{ request()->routeIs('admin.layouts.*') ? 'active' : '' }} has-dropdown">
                                    <a class="sidebar-link" data-bs-toggle="collapse" href="#layoutsDropdown" role="button" aria-expanded="false" aria-controls="layoutsDropdown">
                                        <i class="align-middle" data-feather="layout"></i> 
                                        <span class="align-middle">Layouts</span> 
                                        <i class="feather-icon ms-2" data-feather="chevron-down"></i>
                                    </a>
                                   <div class="collapse" id="layoutsDropdown">
    <ul class="sidebar-nav ms-3">
        <!-- Nav Layout -->
        <li class="sidebar-item {{ request()->get('type') === 'nav' ? 'active' : '' }}">
            <a class="sidebar-link" href="{{ route('admin.layouts.index', ['type' => 'nav']) }}">
                <i class="align-middle" data-feather="menu"></i>
                <span class="align-middle">Nav</span>
            </a>
        </li>
        <!-- Body (Section) Layout -->
        <li class="sidebar-item {{ request()->get('type') === 'section' ? 'active' : '' }}">
            <a class="sidebar-link" href="{{ route('admin.layouts.index', ['type' => 'section']) }}">
                <i class="align-middle" data-feather="box"></i>
                <span class="align-middle">Body</span>
            </a>
        </li>
        <!-- Footer Layout -->
        <li class="sidebar-item {{ request()->get('type') === 'footer' ? 'active' : '' }}">
            <a class="sidebar-link" href="{{ route('admin.layouts.index', ['type' => 'footer']) }}">
                <i class="align-middle" data-feather="archive"></i>
                <span class="align-middle">Footer</span>
            </a>
        </li>
    </ul>
</div>

                                </li>




                    <li class="sidebar-item {{ request()->routeIs('admin.pages.*') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('admin.pages.index') }}">
                            <i class="align-middle" data-feather="file-text"></i> <span class="align-middle">Pages</span>
                        </a>
                    </li>

                    <li class="sidebar-item {{ request()->routeIs('admin.headers-footers.*') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('admin.headers-footers.index') }}">
                            <i class="align-middle" data-feather="layout"></i> <span class="align-middle">Headers & Footers</span>
                        </a>
                    </li>

                    <li class="sidebar-header">
                        Design & Layout
                    </li>

                    <li class="sidebar-item {{ request()->routeIs('admin.layouts.*') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('admin.layouts.index') }}">
                            <i class="align-middle" data-feather="layers"></i> <span class="align-middle">Layout Templates</span>
                        </a>
                    </li>

                    <li class="sidebar-item {{ request()->routeIs('admin.color-palette.*') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('admin.color-palette.index') }}">
                            <i class="align-middle" data-feather="palette"></i> <span class="align-middle">Color Palette</span>
                        </a>
                    </li>

                    <li class="sidebar-item {{ request()->routeIs('admin.custom-css.*') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('admin.custom-css.index') }}">
                            <i class="align-middle" data-feather="code"></i> <span class="align-middle">Custom CSS</span>
                        </a>
                    </li>

                    <li class="sidebar-item {{ request()->routeIs('admin.custom-scripts.*') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('admin.custom-scripts.index') }}">
                            <i class="align-middle" data-feather="terminal"></i> <span class="align-middle">Custom Scripts</span>
                        </a>
                    </li>

                    <li class="sidebar-header">
                        System
                    </li>

                    <li class="sidebar-item {{ request()->routeIs('admin.languages.*') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('admin.languages.index') }}">
                            <i class="align-middle" data-feather="globe"></i> <span class="align-middle">Languages</span>
                        </a>
                    </li>
                </ul>

                <div class="sidebar-cta">
                    <div class="sidebar-cta-content">
                        <strong class="d-inline-block mb-2">Template Manager</strong>
                        <div class="mb-3 text-sm">
                            Manage your dynamic website templates with ease.
                        </div>
                        <div class="d-grid">
                            <a href="/" target="_blank" class="btn btn-primary">View Frontend</a>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <div class="main">
            <nav class="navbar navbar-expand navbar-light navbar-bg">
                <a class="sidebar-toggle js-sidebar-toggle">
                    <i class="hamburger align-self-center"></i>
                </a>

                <div class="navbar-collapse collapse">
                    <ul class="navbar-nav navbar-align">
                        <li class="nav-item dropdown">
                            <a class="nav-icon dropdown-toggle" href="#" id="alertsDropdown" data-bs-toggle="dropdown">
                                <div class="position-relative">
                                    <i class="align-middle" data-feather="bell"></i>
                                    <span class="indicator">3</span>
                                </div>
                            </a>
                            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end py-0" aria-labelledby="alertsDropdown">
                                <div class="dropdown-menu-header">
                                    3 New Notifications
                                </div>
                                <div class="list-group">
                                    <a href="#" class="list-group-item">
                                        <div class="row g-0 align-items-center">
                                            <div class="col-2">
                                                <i class="text-success" data-feather="check-circle"></i>
                                            </div>
                                            <div class="col-10">
                                                <div class="text-dark">Page updated</div>
                                                <div class="text-muted small mt-1">Home page has been updated successfully.</div>
                                                <div class="text-muted small mt-1">2m ago</div>
                                            </div>
                                        </div>
                                    </a>
                                    <a href="#" class="list-group-item">
                                        <div class="row g-0 align-items-center">
                                            <div class="col-2">
                                                <i class="text-warning" data-feather="alert-triangle"></i>
                                            </div>
                                            <div class="col-10">
                                                <div class="text-dark">Layout modified</div>
                                                <div class="text-muted small mt-1">Header layout has been changed.</div>
                                                <div class="text-muted small mt-1">1h ago</div>
                                            </div>
                                        </div>
                                    </a>
                                    <a href="#" class="list-group-item">
                                        <div class="row g-0 align-items-center">
                                            <div class="col-2">
                                                <i class="text-primary" data-feather="user-plus"></i>
                                            </div>
                                            <div class="col-10">
                                                <div class="text-dark">New user registered</div>
                                                <div class="text-muted small mt-1">A new user has joined the system.</div>
                                                <div class="text-muted small mt-1">3h ago</div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="dropdown-menu-footer">
                                    <a href="#" class="text-muted">Show all notifications</a>
                                </div>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-icon dropdown-toggle" href="#" id="messagesDropdown" data-bs-toggle="dropdown">
                                <div class="position-relative">
                                    <i class="align-middle" data-feather="message-square"></i>
                                </div>
                            </a>
                            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end py-0" aria-labelledby="messagesDropdown">
                                <div class="dropdown-menu-header">
                                    <div class="position-relative">
                                        System Messages
                                    </div>
                                </div>
                                <div class="list-group">
                                    <a href="#" class="list-group-item">
                                        <div class="row g-0 align-items-center">
                                            <div class="col-2">
                                                <i class="text-success" data-feather="info"></i>
                                            </div>
                                            <div class="col-10 ps-2">
                                                <div class="text-dark">System Status</div>
                                                <div class="text-muted small mt-1">All systems operational.</div>
                                                <div class="text-muted small mt-1">Now</div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="dropdown-menu-footer">
                                    <a href="#" class="text-muted">Show all messages</a>
                                </div>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-icon dropdown-toggle d-inline-block d-sm-none" href="#" data-bs-toggle="dropdown">
                                <i class="align-middle" data-feather="settings"></i>
                            </a>

                            <a class="nav-link dropdown-toggle d-none d-sm-inline-block" href="#" data-bs-toggle="dropdown">
                                <img src="{{ asset('img/avatars/avatar.jpg') }}" class="avatar img-fluid rounded me-1" alt="Admin User" /> <span class="text-dark">Admin User</span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="#"><i class="align-middle me-1" data-feather="user"></i> Profile</a>
                                <a class="dropdown-item" href="#"><i class="align-middle me-1" data-feather="pie-chart"></i> Analytics</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#"><i class="align-middle me-1" data-feather="settings"></i> Settings & Privacy</a>
                                <a class="dropdown-item" href="#"><i class="align-middle me-1" data-feather="help-circle"></i> Help Center</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#">Log out</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>

            <main class="content">
                <div class="container-fluid p-0">
                    @yield('content')
                </div>
            </main>

            <footer class="footer">
                <div class="container-fluid">
                    <div class="row text-muted">
                        <div class="col-6 text-start">
                            <p class="mb-0">
                                <a class="text-muted" href="#" target="_blank"><strong>Template Management System</strong></a> &copy; {{ date('Y') }}
                            </p>
                        </div>
                        <div class="col-6 text-end">
                            <ul class="list-inline">
                                <li class="list-inline-item">
                                    <a class="text-muted" href="#" target="_blank">Support</a>
                                </li>
                                <li class="list-inline-item">
                                    <a class="text-muted" href="#" target="_blank">Help Center</a>
                                </li>
                                <li class="list-inline-item">
                                    <a class="text-muted" href="#" target="_blank">Privacy</a>
                                </li>
                                <li class="list-inline-item">
                                    <a class="text-muted" href="#" target="_blank">Terms</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <script src="{{ asset('js/app.js') }}"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Feather Icons -->
    <script src="https://unpkg.com/feather-icons"></script>
    <script>
        // Initialize Feather icons
        document.addEventListener('DOMContentLoaded', function() {
            feather.replace();
        });
    </script>
    @yield('js')
    @stack('scripts')
</body>

</html>
