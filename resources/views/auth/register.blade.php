@extends('layouts.auth')

@section('title', 'Register')

@section('content')
<div class="card">
    <div class="card-body">
        <div class="m-sm-3">
            <div class="text-center mb-4">
                <h2 class="text-info"><i class="fas fa-user-plus"></i> Create Account</h2>
                <p class="text-muted">Join our platform</p>
            </div>
            
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
                    <button type="submit" class="btn btn-lg btn-info">
                        <i class="fas fa-user-plus me-2"></i>Create Account
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="text-center mb-3">
    Already have an account? <a href="{{ route('login') }}" class="text-success">Sign in</a>
    <br>
    <small class="text-muted mt-2 d-block">
        <a href="{{ route('admin.login') }}" class="text-primary">
            <i class="fas fa-shield-alt"></i> Admin Login
        </a>
    </small>
</div>
@endsection
