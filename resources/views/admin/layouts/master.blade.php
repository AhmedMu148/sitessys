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

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    
    @yield('css')
</head>

<body>
    <div class="wrapper">
        <nav id="sidebar" class="sidebar js-sidebar">
            <div class="sidebar-content js-simplebar">
                <a class="sidebar-brand" href="{{ route('admin.dashboard') }}">
                    <span class="align-middle">Template System</span>
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

                    <li class="sidebar-item {{ request()->routeIs('admin.sites.*') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('admin.sites.index') }}">
                            <i class="align-middle" data-feather="globe"></i> <span class="align-middle">Sites</span>
                        </a>
                    </li>

                    <li class="sidebar-item {{ request()->routeIs('admin.settings.*') || request()->routeIs('admin.pages.*') || request()->routeIs('admin.layouts.*') || request()->routeIs('admin.headers-footers.*') || request()->routeIs('admin.colors.*') ? 'active' : '' }}">
                        <a class="sidebar-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#settings-collapse" aria-expanded="{{ request()->routeIs('admin.settings.*') || request()->routeIs('admin.pages.*') || request()->routeIs('admin.layouts.*') || request()->routeIs('admin.headers-footers.*') || request()->routeIs('admin.colors.*') ? 'true' : 'false' }}">
                            <i class="align-middle" data-feather="settings"></i> <span class="align-middle">Settings</span>
                            <i class="align-middle ms-auto" data-feather="chevron-down"></i>
                        </a>
                        <div class="collapse {{ request()->routeIs('admin.settings.*') || request()->routeIs('admin.pages.*') || request()->routeIs('admin.layouts.*') || request()->routeIs('admin.headers-footers.*') || request()->routeIs('admin.colors.*') ? 'show' : '' }}" id="settings-collapse">
                            <ul class="sidebar-nav-sub">
                                <li class="sidebar-item {{ request()->routeIs('admin.tpl.*') || request()->routeIs('admin.pages.*') || request()->routeIs('admin.headers-footers.*') || request()->routeIs('admin.colors.*') ? 'active' : '' }}">
                                    <a class="sidebar-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#tpl-collapse" aria-expanded="{{ request()->routeIs('admin.tpl.*') || request()->routeIs('admin.pages.*') || request()->routeIs('admin.headers-footers.*') || request()->routeIs('admin.colors.*') ? 'true' : 'false' }}">
                                        <i class="align-middle" data-feather="layers"></i> <span class="align-middle">TPL</span>
                                        <i class="align-middle ms-auto" data-feather="chevron-down"></i>
                                    </a>
                                    <div class="collapse {{ request()->routeIs('admin.tpl.*') || request()->routeIs('admin.pages.*') || request()->routeIs('admin.headers-footers.*') || request()->routeIs('admin.colors.*') ? 'show' : '' }}" id="tpl-collapse">
                                        <ul class="sidebar-nav-sub">
                                            <li class="sidebar-item {{ request()->routeIs('admin.pages.*') ? 'active' : '' }}">
                                                <a class="sidebar-link" href="{{ route('admin.pages.index') }}">
                                                    <i class="align-middle" data-feather="file-text"></i> Pages
                                                </a>
                                            </li>
                                            <li class="sidebar-item {{ request()->routeIs('admin.headers-footers.*') ? 'active' : '' }}">
                                                <a class="sidebar-link" href="{{ route('admin.headers-footers.index') }}">
                                                    <i class="align-middle" data-feather="columns"></i> Layout tabs
                                                </a>
                                            </li>
                                            <li class="sidebar-item {{ request()->routeIs('admin.colors.*') ? 'active' : '' }}">
                                                <a class="sidebar-link" href="{{ route('admin.colors.index') }}">
                                                    <i class="align-middle" data-feather="palette"></i> Color palette
                                                </a>
                                            </li>
                                        </ul>
                                    </div>

                                     <li class="sidebar-item {{ request()->routeIs('admin.content.*') ? 'active' : '' }}">
                                                <a class="sidebar-link" href="{{ route('admin.content.index') }}">
                                                    <i class="align-middle" data-feather="columns"></i> Edit Content
                                                </a>
                                            </li>
                                </li>
                            </ul>
                        </div>

                    <li class="sidebar-item {{ request()->routeIs('admin.templates.*') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('admin.templates.index') }}">
                            <i class="align-middle" data-feather="layers"></i> <span class="align-middle">Templates</span>
                        </a>
                    </li>

                    <li class="sidebar-header">
                        System
                    </li>

                    <li class="sidebar-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('admin.users.index') }}">
                            <i class="align-middle" data-feather="users"></i> <span class="align-middle">Users</span>
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
                                    <span class="indicator" id="alertsIndicator"><span id="alertsIndicatorCount">3</span></span>
                                </div>
                            </a>
                            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end py-0" aria-labelledby="alertsDropdown">
                                <div class="dropdown-menu-header" id="alertsDropdownHeader">
                                    3 New Notifications
                                </div>
                                <div class="list-group" id="alertsList">
                                    <!-- notifications will be injected here by JS -->
                                </div>
                                <div class="dropdown-menu-footer">
                                    <a href="#" class="text-muted" id="alertsShowAll">Show all notifications</a>
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
                            <a class="nav-icon dropdown-toggle d-inline-block d-sm-none" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="align-middle" data-feather="settings"></i>
                            </a>

                            <a class="nav-link dropdown-toggle d-none d-sm-inline-block" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <img src="{{ asset('img/avatars/avatar.jpg') }}" class="avatar img-fluid rounded me-1" alt="{{ auth()->user()->name ?? 'Admin User' }}" /> 
                                <span class="text-dark">{{ auth()->user()->name ?? 'Admin User' }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="#"><i class="align-middle me-1" data-feather="user"></i> Profile</a></li>
                                <li><a class="dropdown-item" href="#"><i class="align-middle me-1" data-feather="pie-chart"></i> Analytics</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="#"><i class="align-middle me-1" data-feather="settings"></i> Settings & Privacy</a></li>
                                <li><a class="dropdown-item" href="#"><i class="align-middle me-1" data-feather="help-circle"></i> Help Center</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}" class="d-inline w-100">
                                        @csrf
                                        <button type="submit" class="dropdown-item border-0 bg-transparent w-100 text-start">
                                            <i class="align-middle me-1" data-feather="log-out"></i> Log out
                                        </button>
                                    </form>
                                </li>
                            </ul>
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

    <!-- jQuery (must be loaded first) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Feather Icons -->
    <script src="https://unpkg.com/feather-icons"></script>
    <script>
        // Initialize everything after DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Feather icons
            feather.replace();
            
            // Force initialize all dropdowns
            const dropdowns = document.querySelectorAll('[data-bs-toggle="dropdown"]');
            dropdowns.forEach(function(dropdown) {
                new bootstrap.Dropdown(dropdown);
            });
            
            console.log('Initialized', dropdowns.length, 'dropdowns');
            console.log('jQuery loaded:', typeof $ !== 'undefined');

            // Sidebar toggle behavior
            const sidebar = document.querySelector('#sidebar');
            const toggleBtn = document.querySelector('.js-sidebar-toggle');
            if (sidebar && toggleBtn) {
                // Restore previous state
                try {
                    const saved = localStorage.getItem('sps_sidebar_collapsed');
                    if (saved === '1') sidebar.classList.add('collapsed');
                } catch (e) { /* ignore */ }

                toggleBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    sidebar.classList.toggle('collapsed');
                    // Persist state
                    try {
                        localStorage.setItem('sps_sidebar_collapsed', sidebar.classList.contains('collapsed') ? '1' : '0');
                    } catch (e) { /* ignore */ }
                });
            } else {
                console.warn('Sidebar toggle not initialized: element not found');
            }
        });
        
        // Additional click handler as fallback
        document.addEventListener('click', function(e) {
            if (e.target.matches('[data-bs-toggle="dropdown"]') || e.target.closest('[data-bs-toggle="dropdown"]')) {
                console.log('Dropdown clicked');
            }
        });
    </script>
    <script>
        // Simulated notifications (client-only, stored in sessionStorage)
        (function() {
            const STORAGE_KEY = 'sps_notifications_v1';

            // Default notifications to show on first load
            const defaultNotifications = [
                { id: 'n1', title: 'Page updated', text: 'Home page has been updated successfully.', icon: 'check-circle', time: '2m', read: false },
                { id: 'n2', title: 'Layout modified', text: 'Header layout has been changed.', icon: 'alert-triangle', time: '1h', read: false },
                { id: 'n3', title: 'New user registered', text: 'A new user has joined the system.', icon: 'user-plus', time: '3h', read: false }
            ];

            function loadNotifications() {
                try {
                    const raw = sessionStorage.getItem(STORAGE_KEY);
                    if (!raw) {
                        sessionStorage.setItem(STORAGE_KEY, JSON.stringify(defaultNotifications));
                        return defaultNotifications.slice();
                    }
                    return JSON.parse(raw) || [];
                } catch (e) {
                    console.error('Failed to load notifications', e);
                    return defaultNotifications.slice();
                }
            }

            function saveNotifications(list) {
                try {
                    sessionStorage.setItem(STORAGE_KEY, JSON.stringify(list));
                } catch (e) { console.error('Failed to save notifications', e); }
            }

            function renderNotifications() {
                const list = loadNotifications();
                const container = document.getElementById('alertsList');
                const header = document.getElementById('alertsDropdownHeader');
                const indicatorCount = document.getElementById('alertsIndicatorCount');
                if (!container || !header || !indicatorCount) return;

                container.innerHTML = '';
                const unread = list.filter(n => !n.read).length;
                indicatorCount.textContent = unread;
                header.textContent = unread + ' New Notifications';

                if (list.length === 0) {
                    container.innerHTML = '<div class="p-3 text-center text-muted">No notifications</div>';
                    return;
                }

                list.forEach(n => {
                    const a = document.createElement('a');
                    a.href = '#';
                    a.className = 'list-group-item list-group-item-action' + (n.read ? '' : ' bg-light');
                    a.dataset.id = n.id;

                    a.innerHTML = `
                        <div class="row g-0 align-items-center">
                            <div class="col-2">
                                <i class="text-${n.read ? 'muted' : 'primary'}" data-feather="${n.icon}"></i>
                            </div>
                            <div class="col-10">
                                <div class="text-dark">${escapeHtml(n.title)}</div>
                                <div class="text-muted small mt-1">${escapeHtml(n.text)}</div>
                                <div class="text-muted small mt-1">${escapeHtml(n.time)}</div>
                            </div>
                        </div>`;

                    a.addEventListener('click', function(ev) {
                        ev.preventDefault();
                        markAsRead(n.id);
                    });

                    container.appendChild(a);
                });

                // Re-run feather replace to render icons in injected nodes
                try { feather.replace(); } catch (e) { /* ignore */ }
            }

            function markAsRead(id) {
                const list = loadNotifications();
                const idx = list.findIndex(x => x.id === id);
                if (idx === -1) return;
                list[idx].read = true;
                saveNotifications(list);
                renderNotifications();
            }

            function escapeHtml(s) {
                return String(s)
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#039;');
            }

            // initialize on page load
            document.addEventListener('DOMContentLoaded', function() { renderNotifications(); });

            // Expose for debugging in console
            window.__sps_notifications = {
                load: loadNotifications,
                save: saveNotifications,
                render: renderNotifications,
                markAsRead: markAsRead
            };
        })();
    </script>
    @yield('js')
    @stack('scripts')
</body>

</html>
