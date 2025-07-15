@extends('layouts.auth')

@section('title', 'Register')

@section('content')
<div class="card">
    <div class="card-body">
        <div class="m-sm-3">
            <form method="POST" action="{{ route('register.post') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input class="form-control form-control-lg @error('name') is-invalid @enderror" 
                           type="text" name="name" placeholder="Enter your name" 
                           value="{{ old('name') }}" required />
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input class="form-control form-control-lg @error('email') is-invalid @enderror" 
                           type="email" name="email" placeholder="Enter your email" 
                           value="{{ old('email') }}" required />
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Subdomain (Optional)</label>
                    <div class="input-group">
                        <input class="form-control form-control-lg @error('subdomain') is-invalid @enderror" 
                               type="text" name="subdomain" placeholder="yoursite" 
                               value="{{ old('subdomain') }}" />
                        <span class="input-group-text">.{{ config('app.main_domain', 'example.com') }}</span>
                    </div>
                    @error('subdomain')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted">Your website will be accessible at this subdomain</small>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Custom Domain (Optional)</label>
                    <input class="form-control form-control-lg @error('domain') is-invalid @enderror" 
                           type="text" name="domain" placeholder="yourdomain.com" 
                           value="{{ old('domain') }}" />
                    @error('domain')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted">Use your own domain name</small>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input class="form-control form-control-lg @error('password') is-invalid @enderror" 
                           type="password" name="password" placeholder="Enter your password" required />
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Confirm Password</label>
                    <input class="form-control form-control-lg" 
                           type="password" name="password_confirmation" placeholder="Confirm your password" required />
                </div>
                
                <div class="d-grid gap-2 mt-3">
                    <button type="submit" class="btn btn-lg btn-primary">Sign up</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="text-center mb-3">
    Already have an account? <a href="{{ route('login') }}">Sign in</a>
</div>
@endsection
