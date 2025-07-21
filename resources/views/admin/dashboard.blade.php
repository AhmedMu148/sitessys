@extends('admin.layouts.master')

@section('title', 'Dashboard')

@section('content')
    <h1 class="h3 mb-3"><strong>Analytics</strong> Dashboard</h1>

    <div class="row">
        <div class="col-xl-6 col-xxl-5 d-flex">
            <div class="w-100">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col mt-0">
                                        <h5 class="card-title">Total Sites</h5>
                                    </div>
                                    <div class="col-auto">
                                        <div class="stat text-primary">
                                            <i class="align-middle" data-feather="globe"></i>
                                        </div>
                                    </div>
                                </div>
                                <h1 class="mt-1 mb-3">{{ $stats['sites'] ?? 0 }}</h1>
                                <div class="mb-0">
                                    <span class="text-success"> <i class="mdi mdi-arrow-bottom-right"></i> +2.5% </span>
                                    <span class="text-muted">Since last week</span>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col mt-0">
                                        <h5 class="card-title">Total Users</h5>
                                    </div>
                                    <div class="col-auto">
                                        <div class="stat text-primary">
                                            <i class="align-middle" data-feather="users"></i>
                                        </div>
                                    </div>
                                </div>
                                <h1 class="mt-1 mb-3">{{ $stats['users'] ?? 0 }}</h1>
                                <div class="mb-0">
                                    <span class="text-success"> <i class="mdi mdi-arrow-bottom-right"></i> +1.2% </span>
                                    <span class="text-muted">Since last week</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col mt-0">
                                        <h5 class="card-title">Total Designs</h5>
                                    </div>
                                    <div class="col-auto">
                                        <div class="stat text-primary">
                                            <i class="align-middle" data-feather="grid"></i>
                                        </div>
                                    </div>
                                </div>
                                <h1 class="mt-1 mb-3">{{ $stats['designs'] ?? 0 }}</h1>
                                <div class="mb-0">
                                    <span class="text-success"> <i class="mdi mdi-arrow-bottom-right"></i> +3.1% </span>
                                    <span class="text-muted">Since last week</span>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col mt-0">
                                        <h5 class="card-title">Languages</h5>
                                    </div>
                                    <div class="col-auto">
                                        <div class="stat text-primary">
                                            <i class="align-middle" data-feather="globe"></i>
                                        </div>
                                    </div>
                                </div>
                                <h1 class="mt-1 mb-3">{{ $stats['languages'] ?? 0 }}</h1>
                                <div class="mb-0">
                                    <span class="text-muted">Available languages</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-6 col-xxl-7">
            <div class="card flex-fill w-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">Recent Activity</h5>
                </div>
                <div class="card-body py-3">
                    <div class="chart chart-sm">
                        <canvas id="chartjs-dashboard-line"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-md-6 col-xxl-3 d-flex order-2 order-xxl-3">
            <div class="card flex-fill w-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">Recent Pages</h5>
                </div>
                <div class="card-body d-flex">
                    <div class="align-self-center w-100">
                        <div class="py-3">
                            <div class="chart chart-xs">
                                <canvas id="chartjs-dashboard-pie"></canvas>
                            </div>
                        </div>
                        <table class="table mb-0">
                            <tbody>
                                @foreach($recentPages ?? [] as $page)
                                <tr>
                                    <td>{{ $page->name }}</td>
                                    <td class="text-end">{{ $page->updated_at->diffForHumans() }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-12 col-xxl-6 d-flex order-3 order-xxl-2">
            <div class="card flex-fill w-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">Latest Updates</h5>
                </div>
                <div class="card-body px-4">
                    @foreach($recentUpdates ?? [] as $update)
                    <div class="row">
                        <div class="col-auto">
                            <div class="stat text-primary">
                                <i class="align-middle" data-feather="{{ $update['icon'] }}"></i>
                            </div>
                        </div>
                        <div class="col">
                            <h6 class="mb-0">{{ $update['title'] }}</h6>
                            <small class="text-muted">{{ $update['description'] }}</small>
                        </div>
                        <div class="col-auto text-end">
                            <small class="text-muted">{{ $update['time'] }}</small>
                        </div>
                    </div>
                    @if(!$loop->last)
                    <hr>
                    @endif
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xxl-3 d-flex order-1 order-xxl-1">
            <div class="card flex-fill">
                <div class="card-header">
                    <h5 class="card-title mb-0">Quick Actions</h5>
                </div>
                <div class="card-body d-flex">
                    <div class="align-self-center w-100">
                        <div class="d-grid gap-2">
                            <a href="{{ route('admin.pages.create') }}" class="btn btn-primary">
                                <i class="align-middle" data-feather="plus"></i> New Page
                            </a>
                            <a href="{{ route('admin.layouts.create') }}" class="btn btn-outline-primary">
                                <i class="align-middle" data-feather="layout"></i> New Layout
                            </a>
                            <a href="{{ route('admin.templates.index') }}" class="btn btn-outline-primary">
                                <i class="align-middle" data-feather="grid"></i> Templates
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-lg-8 col-xxl-9 d-flex">
            <div class="card flex-fill">
                <div class="card-header">
                    <h5 class="card-title mb-0">System Status</h5>
                </div>
                <table class="table table-hover my-0">
                    <thead>
                        <tr>
                            <th>Component</th>
                            <th class="d-none d-xl-table-cell">Status</th>
                            <th class="d-none d-xl-table-cell">Last Updated</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Frontend Template</td>
                            <td class="d-none d-xl-table-cell"><span class="badge bg-success">Active</span></td>
                            <td class="d-none d-xl-table-cell">{{ now()->diffForHumans() }}</td>
                            <td><a href="/" target="_blank" class="btn btn-sm btn-outline-primary">View</a></td>
                        </tr>
                        <tr>
                            <td>Database</td>
                            <td class="d-none d-xl-table-cell"><span class="badge bg-success">Connected</span></td>
                            <td class="d-none d-xl-table-cell">{{ now()->diffForHumans() }}</td>
                            <td><button class="btn btn-sm btn-outline-secondary">Check</button></td>
                        </tr>
                        <tr>
                            <td>Cache System</td>
                            <td class="d-none d-xl-table-cell"><span class="badge bg-success">Enabled</span></td>
                            <td class="d-none d-xl-table-cell">{{ now()->diffForHumans() }}</td>
                            <td><button class="btn btn-sm btn-outline-warning">Clear</button></td>
                        </tr>
                        <tr>
                            <td>Custom CSS/JS</td>
                            <td class="d-none d-xl-table-cell"><span class="badge bg-success">Active</span></td>
                            <td class="d-none d-xl-table-cell">{{ now()->diffForHumans() }}</td>
                            <td><button class="btn btn-sm btn-outline-primary">Edit</button></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-12 col-lg-4 col-xxl-3 d-flex">
            <div class="card flex-fill w-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">System Info</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col mt-0">
                            <h6 class="card-subtitle">Laravel Version</h6>
                            <p class="mb-2">{{ app()->version() }}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mt-0">
                            <h6 class="card-subtitle">PHP Version</h6>
                            <p class="mb-2">{{ phpversion() }}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mt-0">
                            <h6 class="card-subtitle">Environment</h6>
                            <p class="mb-2">{{ app()->environment() }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var ctx = document.getElementById("chartjs-dashboard-line").getContext("2d");
        var gradient = ctx.createLinearGradient(0, 0, 0, 225);
        gradient.addColorStop(0, "rgba(215, 227, 244, 1)");
        gradient.addColorStop(1, "rgba(215, 227, 244, 0)");
        
        new Chart(document.getElementById("chartjs-dashboard-line"), {
            type: "line",
            data: {
                labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                datasets: [{
                    label: "Activity",
                    fill: true,
                    backgroundColor: gradient,
                    borderColor: window.theme.primary,
                    data: [2115, 1562, 1584, 1892, 1587, 1923, 2566, 2448, 2805, 3438, 2917, 3327]
                }]
            },
            options: {
                maintainAspectRatio: false,
                legend: {
                    display: false
                },
                tooltips: {
                    intersect: false
                },
                hover: {
                    intersect: true
                },
                plugins: {
                    filler: {
                        propagate: false
                    }
                },
                scales: {
                    xAxes: [{
                        reverse: true,
                        gridLines: {
                            color: "rgba(0,0,0,0.0)"
                        }
                    }],
                    yAxes: [{
                        ticks: {
                            stepSize: 1000
                        },
                        display: true,
                        borderDash: [3, 3],
                        gridLines: {
                            color: "rgba(0,0,0,0.0)"
                        }
                    }]
                }
            }
        });
    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        new Chart(document.getElementById("chartjs-dashboard-pie"), {
            type: "pie",
            data: {
                labels: ["Pages", "Layouts", "Designs"],
                datasets: [{
                    data: [{{ $stats['pages'] ?? 0 }}, {{ $stats['layouts'] ?? 0 }}, {{ $stats['designs'] ?? 0 }}],
                    backgroundColor: [
                        window.theme.primary,
                        window.theme.warning,
                        window.theme.danger
                    ],
                    borderWidth: 5
                }]
            },
            options: {
                responsive: !window.MSInputMethodContext,
                maintainAspectRatio: false,
                legend: {
                    display: false
                },
                cutoutPercentage: 75
            }
        });
    });
</script>
@endsection
