@extends('admin.layouts.master')

@section('title', 'Settings')

@section('content')
    <div class="container-fluid p-0">
        <div class="mb-3">
            <h1 class="h3 d-inline align-middle">Settings</h1>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">General Settings</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.settings.update') }}">
                            @csrf
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Site Name</label>
                                        <input type="text" class="form-control @error('site_name') is-invalid @enderror" 
                                               name="site_name" value="{{ old('site_name', $site->site_name) }}" required>
                                        @error('site_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Site URL</label>
                                        <input type="url" class="form-control @error('url') is-invalid @enderror" 
                                               name="url" value="{{ old('url', $site->url) }}">
                                        @error('url')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Site Description</label>
                                        <textarea class="form-control" name="data[site_description]" rows="3">{{ old('data.site_description', $config->data['site_description'] ?? '') }}</textarea>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Contact Email</label>
                                        <input type="email" class="form-control" name="data[contact_email]" 
                                               value="{{ old('data.contact_email', $config->data['contact_email'] ?? '') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Contact Phone</label>
                                        <input type="text" class="form-control" name="data[contact_phone]" 
                                               value="{{ old('data.contact_phone', $config->data['contact_phone'] ?? '') }}">
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Timezone</label>
                                        <select class="form-control" name="settings[timezone]">
                                            <option value="UTC" {{ ($config->settings['timezone'] ?? 'UTC') == 'UTC' ? 'selected' : '' }}>UTC</option>
                                            <option value="America/New_York" {{ ($config->settings['timezone'] ?? '') == 'America/New_York' ? 'selected' : '' }}>New York</option>
                                            <option value="Europe/London" {{ ($config->settings['timezone'] ?? '') == 'Europe/London' ? 'selected' : '' }}>London</option>
                                            <option value="Asia/Dubai" {{ ($config->settings['timezone'] ?? '') == 'Asia/Dubai' ? 'selected' : '' }}>Dubai</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <h6>Social Media Links</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Facebook</label>
                                        <input type="url" class="form-control" name="data[social_facebook]" 
                                               value="{{ old('data.social_facebook', $config->data['social_facebook'] ?? '') }}">
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Twitter</label>
                                        <input type="url" class="form-control" name="data[social_twitter]" 
                                               value="{{ old('data.social_twitter', $config->data['social_twitter'] ?? '') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Instagram</label>
                                        <input type="url" class="form-control" name="data[social_instagram]" 
                                               value="{{ old('data.social_instagram', $config->data['social_instagram'] ?? '') }}">
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">LinkedIn</label>
                                        <input type="url" class="form-control" name="data[social_linkedin]" 
                                               value="{{ old('data.social_linkedin', $config->data['social_linkedin'] ?? '') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <button type="submit" class="btn btn-primary">
                                    <i data-feather="save"></i> Save Settings
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Available Languages</h5>
                    </div>
                    <div class="card-body">
                        @if($languages->count() > 0)
                            <div class="row">
                                @foreach($languages as $language)
                                    <div class="col-md-3 mb-3">
                                        <div class="card border">
                                            <div class="card-body text-center">
                                                <h6>{{ $language->name }}</h6>
                                                <span class="badge bg-primary">{{ strtoupper($language->code) }}</span>
                                                <span class="badge bg-secondary">{{ strtoupper($language->dir) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted">No languages configured.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
