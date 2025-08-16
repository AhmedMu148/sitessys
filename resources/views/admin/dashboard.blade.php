@extends('admin.layouts.master')

@section('title', 'Dashboard')

@section('css')
<style>
    /* Slight elevation for dashboard cards to match AdminKit feel */
    .card.h-100, .card.flex-fill { box-shadow: 0 6px 18px rgba(20,30,50,0.05); border-radius: .5rem; }

    /* Stat card icon sizes and alignment */
    .card .bg-primary, .card .bg-success, .card .bg-warning, .card .bg-info { width:48px; height:48px; }
    .card .bg-primary i, .card .bg-success i, .card .bg-warning i, .card .bg-info i { width:22px; height:22px; }

    /* Improve spacing inside stat card values */
    .card .h4 { font-size: 1.5rem; font-weight: 600; }

    /* Recent activity visual tweaks */
    .card-body .bg-light { background: #f8f9fb; }
    .card-body .fw-semibold { font-weight:600; }

    /* Table row spacing */
    table.table tbody tr td { padding-top: 0.9rem; padding-bottom: 0.9rem; }

    /* Chart heights */
    .chart-lg canvas { height: 260px !important; }
    .chart canvas { max-height: 260px; }

    /* Buttons in quick actions — more consistent look */
    .btn-outline-primary { border-color: rgba(13,110,253,0.12); }

    /* Subtle hover on recent activity rows */
    .card-body > .d-flex:hover { background: rgba(0,0,0,0.02); border-radius: .35rem; }

    /* Page header */
    .dashboard-header { display:flex; align-items:center; justify-content:space-between; gap:1rem; }
    .dashboard-header .title { font-size:1.25rem; font-weight:700; letter-spacing: -0.02em; }
    .dashboard-header .subtitle { color: #6c757d; font-size:0.95rem; }

    /* Card headers style */
    .card-header { background: transparent; border-bottom: none; padding-bottom: .75rem; }
    .card-title { font-weight:600; font-size:1rem; }

    /* Make stat cards feel lighter */
    .card.h-100 { padding: .6rem; }

    /* Make links on cards obvious */
    .text-reset:hover { text-decoration: none; }
    a.text-decoration-none:hover { transform: translateY(-2px); transition: all .12s ease; }

    /* Table header subtle background */
    table.table thead th { background: transparent; color: #495057; font-weight:600; }

    /* Responsive tweaks */
    @media (max-width: 768px) {
        .dashboard-header { flex-direction:column; align-items:flex-start; }
    }
</style>
@endsection

@section('content')
    <div class="dashboard-header mb-3">
        <div>
            <div class="title">Analytics Dashboard</div>
            <div class="subtitle">Overview of sites, pages and recent activity</div>
        </div>
        <div>
            {{-- Removed create dialog button as requested; keep Manage Sites only if route exists --}}
            @if(
                \Illuminate\Support\Facades\Route::has('admin.sites.index')
            )
            <a href="{{ route('admin.sites.index') }}" class="btn btn-outline-secondary btn-sm ms-2">
                Manage Sites
            </a>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="row gx-3">
                <div class="col-sm-6 col-lg-3 mb-3">
                    @if(\Illuminate\Support\Facades\Route::has('admin.sites.index'))
                    <a href="{{ route('admin.sites.index') }}" class="text-decoration-none text-reset">
                        <div class="card h-100">
                            <div class="card-body d-flex align-items-center">
                    @else
                        <div class="card h-100">
                            <div class="card-body d-flex align-items-center">
                    @endif
                            <div class="me-3">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width:48px;height:48px;">
                                    <i data-feather="globe"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <div class="text-muted small">Total Sites</div>
                                <div class="h4 mb-0">{{ $stats['sites'] ?? 0 }}</div>
                                <div class="small text-muted">Updated: {{ now()->toDateString() }}</div>
                                    </div>
                                </div>
                            @if(\Illuminate\Support\Facades\Route::has('admin.sites.index'))
                            </a>
                            @endif
                            </div>
                </div>

                <div class="col-sm-6 col-lg-3 mb-3">
                    @if(\Illuminate\Support\Facades\Route::has('admin.users.index'))
                    <a href="{{ route('admin.users.index') }}" class="text-decoration-none text-reset">
                        <div class="card h-100">
                            <div class="card-body d-flex align-items-center">
                    @else
                        <div class="card h-100">
                            <div class="card-body d-flex align-items-center">
                    @endif
                            <div class="me-3">
                                <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center" style="width:48px;height:48px;">
                                    <i data-feather="users"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <div class="text-muted small">Total Users</div>
                                <div class="h4 mb-0">{{ $stats['users'] ?? 0 }}</div>
                                <div class="small text-muted">Active users and admins</div>
                                    </div>
                                </div>
                            @if(\Illuminate\Support\Facades\Route::has('admin.users.index'))
                            </a>
                            @endif
                            </div>
                </div>

                <div class="col-sm-6 col-lg-3 mb-3">
                    @if(\Illuminate\Support\Facades\Route::has('admin.templates.index'))
                    <a href="{{ route('admin.templates.index') }}" class="text-decoration-none text-reset">
                        <div class="card h-100">
                            <div class="card-body d-flex align-items-center">
                    @else
                        <div class="card h-100">
                            <div class="card-body d-flex align-items-center">
                    @endif
                            <div class="me-3">
                                <div class="bg-warning text-white rounded-circle d-flex align-items-center justify-content-center" style="width:48px;height:48px;">
                                    <i data-feather="grid"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <div class="text-muted small">Designs</div>
                                <div class="h4 mb-0">{{ $stats['designs'] ?? 0 }}</div>
                                <div class="small text-muted">Theme categories</div>
                                    </div>
                                </div>
                            @if(\Illuminate\Support\Facades\Route::has('admin.templates.index'))
                            </a>
                            @endif
                            </div>
                </div>

                <div class="col-sm-6 col-lg-3 mb-3">
                    @if(\Illuminate\Support\Facades\Route::has('admin.layouts.index'))
                    <a href="{{ route('admin.layouts.index') }}" class="text-decoration-none text-reset">
                        <div class="card h-100">
                            <div class="card-body d-flex align-items-center">
                    @else
                        <div class="card h-100">
                            <div class="card-body d-flex align-items-center">
                    @endif
                            <div class="me-3">
                                <div class="bg-info text-white rounded-circle d-flex align-items-center justify-content-center" style="width:48px;height:48px;">
                                    <i data-feather="globe"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <div class="text-muted small">Languages</div>
                                <div class="h4 mb-0">{{ $stats['languages'] ?? 0 }}</div>
                                <div class="small text-muted">Available languages</div>
                                    </div>
                                </div>
                            @if(\Illuminate\Support\Facades\Route::has('admin.layouts.index'))
                            </a>
                            @endif
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
                    @foreach($recentUpdates as $update)
                    @if(!empty($update['url']))
                    <a href="{{ $update['url'] }}" class="d-block text-reset text-decoration-none">
                    <div class="d-flex mb-3 align-items-center">
                    @else
                    <div class="d-flex mb-3 align-items-center">
                    @endif
                        <div class="me-3">
                            <div class="bg-light rounded-circle d-flex align-items-center justify-content-center" style="width:40px;height:40px;">
                                <i class="align-middle" data-feather="{{ $update['icon'] }}"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <div class="fw-semibold">{{ $update['title'] }}</div>
                            <div class="small text-muted">{{ $update['description'] }}</div>
                        </div>
                        <div class="text-end small text-muted">{{ $update['time'] }}</div>
                    </div>
                    @if(!empty($update['url']))
                    </a>
                    @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="row">
    <div class="col-12 col-md-8 col-xxl-8 d-flex order-2 order-xxl-3">
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
                            <thead>
                                <tr>
                                    <th>Page</th>
                                    <th class="d-none d-md-table-cell">Site</th>
                                    <th class="text-end">Updated</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentPagesSimple ?? [] as $page)
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
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
    </div>
    </div>

    <div class="row">
        <div class="col-12 col-lg-9 d-flex">
            <div class="card flex-fill">
                <div class="card-header">
                    <h5 class="card-title mb-0">Activity This Year</h5>
                </div>
                <div class="card-body">
                    <div class="chart chart-lg">
                        <canvas id="chartjs-dashboard-line"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-3 d-flex">
            <div class="card flex-fill w-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">System</h5>
                </div>
                <div class="card-body small text-muted">
                    <div>Laravel: <strong class="text-dark">{{ app()->version() }}</strong></div>
                    <div>PHP: <strong class="text-dark">{{ phpversion() }}</strong></div>
                    <div>Env: <strong class="text-dark">{{ app()->environment() }}</strong></div>
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

        new Chart(ctx, {
            type: "line",
            data: {
                labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                datasets: [{
                    label: "Pages Created",
                    fill: true,
                    backgroundColor: gradient,
                    borderColor: window.theme.primary,
                    data: [{{ isset($monthlyActivity) ? implode(',', $monthlyActivity) : '0,0,0,0,0,0,0,0,0,0,0,0' }}]
                }]
            },
            options: {
                maintainAspectRatio: false,
                plugins: {
                    filler: { propagate: false }
                },
                scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
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
                    backgroundColor: [ window.theme.primary, window.theme.warning, window.theme.danger ],
                    borderWidth: 2
                }]
            },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } } }
        });
    });
</script>
@endsection
