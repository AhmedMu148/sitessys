@extends('admin.layouts.master')

@section('title', 'Dashboard')

@section('css')
<style>
    :root {
        --bs-primary-rgb: 13, 110, 253;
        --bs-success-rgb: 25, 135, 84;
        --bs-warning-rgb: 255, 193, 7;
        --bs-info-rgb: 13, 202, 240;

        --theme-bg: #f8f9fa;
        --theme-text: #212529;
        --card-bg: #ffffff;
        --card-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.05);
        --card-shadow-hover: 0 1rem 2rem rgba(0, 0, 0, 0.1);
        --card-border: none;
        --card-border-radius: .75rem;
        --card-header-border: #dee2e6;
        --muted-text: #6c757d;
        --table-hover-bg: rgba(0,0,0,0.075);
        --transition-speed: 0.2s;
    }

    :root[data-theme="dark"] {
        --theme-bg: #1c2128;
        --theme-text: #e6edf3;
        --card-bg: #22272e;
        --card-shadow: 0 0 0 1px #333a42;
        --card-shadow-hover: 0 0 0 1px #4b545f, 0 1rem 2rem rgba(0,0,0,0.2);
        --card-border: 1px solid #333a42;
        --card-header-border: #333a42;
        --muted-text: #848d97;
        --table-hover-bg: rgba(255,255,255,0.075);
    }

    body {
        background-color: var(--theme-bg);
        color: var(--theme-text);
        transition: background-color var(--transition-speed) ease, color var(--transition-speed) ease;
    }

    .dashboard-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        gap: 1rem;
    }

    .dashboard-header .title {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--theme-text);
    }

    .dashboard-header .subtitle {
        color: var(--muted-text);
        font-size: 1rem;
    }

    .card {
        background-color: var(--card-bg);
        color: var(--theme-text);
        box-shadow: var(--card-shadow);
        border-radius: var(--card-border-radius);
        border: var(--card-border);
        transition: transform var(--transition-speed) ease, box-shadow var(--transition-speed) ease;
    }

    .card:hover {
        transform: translateY(-3px);
        box-shadow: var(--card-shadow-hover);
    }

    .card-header {
        background: transparent;
        border-bottom: 1px solid var(--card-header-border);
        padding: 1rem 1.5rem;
    }

    .card-title {
        font-weight: 600;
        font-size: 1.1rem;
        margin-bottom: 0;
        color: var(--theme-text);
    }

    .stat-card .card-body {
        display: flex;
        align-items: center;
        padding: 1.5rem;
    }

    .stat-card-icon {
        width: 52px;
        height: 52px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        color: #fff;
        margin-right: 1rem;
    }

    .stat-card-icon i {
        width: 24px;
        height: 24px;
    }

    .stat-card-content .stat-label {
        font-size: 0.875rem;
        color: var(--muted-text);
        text-transform: uppercase;
        letter-spacing: .5px;
    }

    .stat-card-content .stat-value {
        font-size: 2rem;
        font-weight: 700;
        color: var(--theme-text);
    }

    .stat-card-content .stat-meta {
        font-size: 0.8rem;
        color: var(--muted-text);
    }

    .activity-item {
        display: flex;
        align-items: center;
        padding: 0.75rem 0;
        border-bottom: 1px solid var(--card-header-border);
    }
    .activity-item:last-child {
        border-bottom: none;
    }

    .activity-icon {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background-color: var(--theme-bg);
        margin-right: 1rem;
    }

    .chart-lg {
        height: 320px !important;
    }

    .table {
        color: var(--theme-text);
    }
    .table th {
        font-weight: 600;
        color: var(--muted-text);
    }
    .table-hover > tbody > tr:hover {
        --bs-table-hover-bg: var(--table-hover-bg);
        color: var(--theme-text);
    }
    .table a {
        color: var(--theme-text);
        font-weight: 500;
    }

    .theme-switcher {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    #theme-toggle {
        background: none;
        border: 1px solid var(--card-header-border);
        border-radius: 50%;
        width: 40px;
        height: 40px;
        color: var(--muted-text);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: background-color var(--transition-speed) ease, color var(--transition-speed) ease;
    }
    #theme-toggle:hover {
        background-color: var(--theme-bg);
        color: var(--theme-text);
    }
    #theme-toggle .feather-sun { display: none; }
    #theme-toggle .feather-moon { display: block; }
    [data-theme="dark"] #theme-toggle .feather-sun { display: block; }
    [data-theme="dark"] #theme-toggle .feather-moon { display: none; }
</style>
@endsection

@section('content')
    <div class="dashboard-header">
        <div>
            <h1 class="title">Analytics Dashboard</h1>
            <p class="subtitle">An overview of your sites, pages, and recent activities.</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            @if(\Illuminate\Support\Facades\Route::has('admin.sites.index'))
                <a href="{{ route('admin.sites.index') }}" class="btn btn-primary d-none d-sm-flex">
                    <i class="align-middle me-1" data-feather="grid"></i> Manage Sites
                </a>
            @endif
            <div class="theme-switcher">
                <button id="theme-toggle" title="Toggle theme">
                    <i class="align-middle" data-feather="moon"></i>
                    <i class="align-middle" data-feather="sun"></i>
                </button>
            </div>
        </div>
    </div>

    @php
    $statsCards = [
        ['route' => 'admin.sites.index', 'color' => 'primary', 'icon' => 'globe', 'label' => 'Total Sites', 'value' => $stats['sites'] ?? 0, 'meta' => 'Updated: ' . now()->toDateString()],
        ['route' => 'admin.users.index', 'color' => 'success', 'icon' => 'users', 'label' => 'Total Users', 'value' => $stats['users'] ?? 0, 'meta' => 'Active users and admins'],
        ['route' => 'admin.templates.index', 'color' => 'warning', 'icon' => 'grid', 'label' => 'Designs', 'value' => $stats['designs'] ?? 0, 'meta' => 'Available theme categories'],
        ['route' => 'admin.layouts.index', 'color' => 'info', 'icon' => 'layout', 'label' => 'Languages', 'value' => $stats['languages'] ?? 0, 'meta' => 'Available languages'],
    ];
    @endphp

    <div class="row">
        @foreach($statsCards as $stat)
        <div class="col-md-6 col-xl-3 mb-4">
            @php $hasRoute = \Illuminate\Support\Facades\Route::has($stat['route']); @endphp
            <div class="card stat-card h-100">
                @if($hasRoute)
                <a href="{{ route($stat['route']) }}" class="text-decoration-none stretched-link" aria-label="{{ $stat['label'] }}"></a>
                @endif
                <div class="card-body">
                    <div class="stat-card-icon bg-{{ $stat['color'] }}">
                        <i data-feather="{{ $stat['icon'] }}"></i>
                    </div>
                    <div class="stat-card-content">
                        <div class="stat-label">{{ $stat['label'] }}</div>
                        <div class="stat-value">{{ $stat['value'] }}</div>
                        <div class="stat-meta">{{ $stat['meta'] }}</div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title">Activity This Year</h5>
                </div>
                <div class="card-body">
                    <div class="chart-lg">
                        <canvas id="chartjs-dashboard-line"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title">Content Overview</h5>
                </div>
                <div class="card-body d-flex align-items-center justify-content-center">
                    <div class="w-100" style="max-height: 320px;">
                        <canvas id="chartjs-dashboard-pie"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title">Recent Pages</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Page</th>
                                <th class="d-none d-md-table-cell">Site</th>
                                <th class="text-end">Updated</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentPagesSimple ?? [] as $page)
                            <tr>
                                <td>
                                    @if(!empty($page['url']))
                                        <a href="{{ $page['url'] }}" class="text-decoration-none">{{ $page['name'] }}</a>
                                    @else
                                        {{ $page['name'] }}
                                    @endif
                                </td>
                                <td class="d-none d-md-table-cell">{{ $page['site'] }}</td>
                                <td class="text-end">{{ $page['updated_human'] }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center py-4">No recent pages found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title">Recent Activity</h5>
                </div>
                <div class="card-body">
                    @forelse($recentUpdates as $update)
                    <div class="activity-item">
                        <div class="activity-icon">
                            <i class="align-middle" data-feather="{{ $update['icon'] }}"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="fw-bold">{{ $update['title'] }}</div>
                            <div class="small text-muted">{{ $update['description'] }}</div>
                        </div>
                        <div class="text-end small text-muted ms-2">{{ $update['time'] }}</div>
                    </div>
                    @empty
                    <div class="text-center py-4">No recent activity.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
             <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">System Information</h5>
                </div>
                <div class="card-body small text-muted d-flex flex-wrap gap-3">
                    <div>Laravel Version: <strong class="text-dark">{{ app()->version() }}</strong></div>
                    <div>PHP Version: <strong class="text-dark">{{ phpversion() }}</strong></div>
                    <div>Environment: <strong class="text-dark">{{ app()->environment() }}</strong></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const themeToggle = document.getElementById('theme-toggle');
        const doc = document.documentElement;

        const applyTheme = (theme) => {
            doc.setAttribute('data-theme', theme);
            localStorage.setItem('dashboard-theme', theme);
            updateCharts(theme);
        };

        const toggleTheme = () => {
            const currentTheme = doc.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
            applyTheme(currentTheme);
        };

        themeToggle.addEventListener('click', toggleTheme);

        const savedTheme = localStorage.getItem('dashboard-theme') || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
        applyTheme(savedTheme);

        let lineChart, pieChart;

        function updateCharts(theme) {
            const isDark = theme === 'dark';
            const gridColor = isDark ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)';
            const textColor = isDark ? '#e6edf3' : '#495057';

            // Line chart
            if (window.lineChart) window.lineChart.destroy();
            const ctxLine = document.getElementById("chartjs-dashboard-line").getContext("2d");
            const gradient = ctxLine.createLinearGradient(0, 0, 0, 225);
            gradient.addColorStop(0, "rgba(var(--bs-primary-rgb), 0.3)");
            gradient.addColorStop(1, "rgba(var(--bs-primary-rgb), 0)");

            window.lineChart = new Chart(ctxLine, {
                type: "line",
                data: {
                    labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                    datasets: [{
                        label: "Pages Created",
                        fill: true,
                        backgroundColor: gradient,
                        borderColor: `rgb(var(--bs-primary-rgb))`,
                        data: [{{ isset($monthlyActivity) ? implode(',', $monthlyActivity) : '0,0,0,0,0,0,0,0,0,0,0,0' }}],
                        tension: 0.4
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    responsive: true,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { stepSize: 1, color: textColor },
                            grid: { color: gridColor }
                        },
                        x: {
                            ticks: { color: textColor },
                            grid: { color: gridColor }
                        }
                    }
                }
            });

            // Pie chart
            if (window.pieChart) window.pieChart.destroy();
            window.pieChart = new Chart(document.getElementById("chartjs-dashboard-pie"), {
                type: "doughnut",
                data: {
                    labels: ["Pages", "Layouts", "Designs"],
                    datasets: [{
                        data: [{{ $stats['pages'] ?? 0 }}, {{ $stats['layouts'] ?? 0 }}, {{ $stats['designs'] ?? 0 }}],
                        backgroundColor: [
                            `rgb(var(--bs-primary-rgb))`,
                            `rgb(var(--bs-warning-rgb))`,
                            `rgb(var(--bs-success-rgb))`
                        ],
                        borderWidth: 2,
                        borderColor: isDark ? '#22272e' : '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { color: textColor }
                        }
                    }
                }
            });
        }
    });
</script>
@endsection
