@extends('layouts.auth')

@section('title', 'Admin Login')

@section('content')
<div class="card">
    <div class="card-body">
        <div class="m-sm-3">
            <div class="text-center mb-4">
                <h2 class="text-primary"><i class="fas fa-shield-alt"></i> Admin Access</h2>
                <p class="text-muted">Administrative Panel Login</p>
            </div>
            
            <form method="POST" action="{{ route('admin.login.post') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Admin Email</label>
                    <input class="form-control form-control-lg @error('email') is-invalid @enderror" 
                           type="email" name="email" placeholder="Enter your admin email" 
                           value="{{ old('email') }}" required />
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input class="form-control form-control-lg @error('password') is-invalid @enderror" 
                           type="password" name="password" placeholder="Enter your password" required />
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" name="remember" id="remember">
                    <label class="form-check-label" for="remember">
                        Keep me signed in
                    </label>
                </div>
                
                <div class="d-grid gap-2 mt-3">
                    <button type="submit" class="btn btn-lg btn-primary">
                        <i class="fas fa-sign-in-alt me-2"></i>Access Admin Panel
                    </button>
                </div>
            </form>
            
            <div class="text-center mt-3">
                <small class="text-muted">
                    <i class="fas fa-info-circle"></i> 
                    Admin access is restricted to authorized personnel only
                </small>
            </div>
        </div>
    </div>
</div>

<div class="text-center mb-3">
    <a href="{{ route('login') }}" class="text-muted">
        <i class="fas fa-user"></i> Regular User Login
    </a>
</div>
@endsection
