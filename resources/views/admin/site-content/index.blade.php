@extends('admin.layouts.master')

@section('title', 'Site Content Dashboard')

@section('content')
<div class="container-fluid p-0">
    <div class="mb-3">
        <h1 class="h3 d-inline align-middle">Site Content Dashboard</h1>
        <span class="badge bg-primary ms-2">{{ $site->site_name }}</span>
        <a href="/" target="_blank" class="btn btn-outline-primary float-end">
            <i data-feather="external-link"></i> View Frontend
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-sm-6">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col mt-0">
                            <h5 class="card-title">Pages</h5>
                        </div>
                        <div class="col-auto">
                            <div class="stat text-primary">
                                <i class="align-middle" data-feather="file-text"></i>
                            </div>
                        </div>
                    </div>
                    <h1 class="mt-1 mb-3">{{ $stats['pages'] }}</h1>
                    <div class="mb-0">
                        <a href="{{ route('admin.site-content.pages') }}" class="text-primary">Manage Pages</a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-sm-6">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col mt-0">
                            <h5 class="card-title">Sections</h5>
                        </div>
                        <div class="col-auto">
                            <div class="stat text-success">
                                <i class="align-middle" data-feather="grid"></i>
                            </div>
                        </div>
                    </div>
                    <h1 class="mt-1 mb-3">{{ $stats['sections'] }}</h1>
                    <div class="mb-0">
                        <a href="{{ route('admin.site-content.sections') }}" class="text-success">Manage Sections</a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-sm-6">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col mt-0">
                            <h5 class="card-title">Configuration</h5>
                        </div>
                        <div class="col-auto">
                            <div class="stat text-warning">
                                <i class="align-middle" data-feather="settings"></i>
                            </div>
                        </div>
                    </div>
                    <h1 class="mt-1 mb-3">{{ $stats['config_items'] }}</h1>
                    <div class="mb-0">
                        <a href="{{ route('admin.site-content.config') }}" class="text-warning">Site Settings</a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-sm-6">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col mt-0">
                            <h5 class="card-title">Site Status</h5>
                        </div>
                        <div class="col-auto">
                            <div class="stat text-{{ $site->status ? 'success' : 'danger' }}">
                                <i class="align-middle" data-feather="globe"></i>
                            </div>
                        </div>
                    </div>
                    <h1 class="mt-1 mb-3">
                        <span class="badge bg-{{ $site->status ? 'success' : 'danger' }}">
                            {{ $site->status ? 'Active' : 'Inactive' }}
                        </span>
                    </h1>
                    <div class="mb-0">
                        <span class="text-muted">Domain: {{ $site->domain ?: 'Not set' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <a href="{{ route('admin.site-content.pages') }}" class="btn btn-primary w-100 mb-2">
                                <i data-feather="plus"></i> Add New Page
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('admin.site-content.sections') }}" class="btn btn-success w-100 mb-2">
                                <i data-feather="grid"></i> Manage Sections
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('admin.site-content.config') }}" class="btn btn-warning w-100 mb-2">
                                <i data-feather="settings"></i> Site Settings
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="/" target="_blank" class="btn btn-outline-primary w-100 mb-2">
                                <i data-feather="external-link"></i> Preview Site
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Pages -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Recent Pages</h5>
                </div>
                <div class="card-body">
                    @if($recentPages->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Page Name</th>
                                        <th>Link</th>
                                        <th>Sections</th>
                                        <th>Last Updated</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentPages as $page)
                                        <tr>
                                            <td>{{ $page->name }}</td>
                                            <td><code>{{ $page->link }}</code></td>
                                            <td>{{ count(explode(',', $page->section_id)) }} sections</td>
                                            <td>{{ $page->updated_at->format('M d, Y H:i') }}</td>
                                            <td>
                                                <a href="{{ $page->link === '/' ? '/' : $page->link }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                    <i data-feather="external-link"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i data-feather="file-text" class="text-muted" style="width: 48px; height: 48px;"></i>
                            <h5 class="mt-3">No pages yet</h5>
                            <p class="text-muted">Start building your site by creating pages and sections.</p>
                            <a href="{{ route('admin.site-content.pages') }}" class="btn btn-primary">
                                <i data-feather="plus"></i> Create First Page
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
