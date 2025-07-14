@extends('admin.layouts.master')

@section('title', 'Site Configuration')

@section('content')
<div class="container-fluid p-0">
    <div class="mb-3">
        <h1 class="h3 d-inline align-middle">Site Configuration</h1>
        <span class="badge bg-primary ms-2">{{ $site->site_name }}</span>
        <a href="{{ route('admin.site-content.index') }}" class="btn btn-secondary float-end">
            <i data-feather="arrow-left"></i> Back to Dashboard
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Basic Site Information</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.site-content.config.update') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="title" class="form-label">Site Title</label>
                            <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" 
                                   value="{{ old('title', $config->data['title'] ?? $site->site_name) }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Site Description</label>
                            <textarea name="description" id="description" rows="3" class="form-control @error('description') is-invalid @enderror" required>{{ old('description', $config->data['description'] ?? 'Welcome to ' . $site->site_name) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">This will be used for SEO meta description.</div>
                        </div>

                        <div class="mb-3">
                            <label for="keyword" class="form-label">Keywords</label>
                            <input type="text" name="keyword" id="keyword" class="form-control @error('keyword') is-invalid @enderror" 
                                   value="{{ old('keyword', $config->data['keyword'] ?? 'website, business, services') }}" required>
                            @error('keyword')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Separate keywords with commas.</div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="logo" class="form-label">Logo Path</label>
                                    <input type="text" name="logo" id="logo" class="form-control" 
                                           value="{{ old('logo', $config->data['logo'] ?? '/logo.png') }}">
                                    <div class="form-text">Path to your logo file (e.g., /images/logo.png)</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="favicon" class="form-label">Favicon Path</label>
                                    <input type="text" name="favicon" id="favicon" class="form-control" 
                                           value="{{ old('favicon', $config->data['favicon'] ?? '/favicon.ico') }}">
                                    <div class="form-text">Path to your favicon file</div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.site-content.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Update Configuration</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Site Information</h5>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-4">Site Name:</dt>
                        <dd class="col-sm-8">{{ $site->site_name }}</dd>
                        
                        <dt class="col-sm-4">Domain:</dt>
                        <dd class="col-sm-8">{{ $site->domain ?: 'Not set' }}</dd>
                        
                        <dt class="col-sm-4">Status:</dt>
                        <dd class="col-sm-8">
                            <span class="badge bg-{{ $site->status ? 'success' : 'danger' }}">
                                {{ $site->status ? 'Active' : 'Inactive' }}
                            </span>
                        </dd>
                        
                        <dt class="col-sm-4">Created:</dt>
                        <dd class="col-sm-8">{{ $site->created_at->format('M d, Y') }}</dd>
                    </dl>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Preview</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted">Preview how your site configuration will appear:</p>
                    
                    <div class="border rounded p-3 bg-light">
                        <h6 class="mb-1">{{ $config->data['title'] ?? $site->site_name }}</h6>
                        <small class="text-muted">{{ $config->data['description'] ?? 'Welcome to ' . $site->site_name }}</small>
                    </div>
                    
                    <div class="mt-3">
                        <a href="/" target="_blank" class="btn btn-outline-primary w-100">
                            <i data-feather="external-link"></i> View Live Site
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
