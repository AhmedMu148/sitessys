@extends('layouts.auth')

@section('title', 'Login')

@section('content')
<div class="card">
    <div class="card-body">
        <div class="m-sm-3">
            <form method="POST" action="{{ route('login.post') }}">
                @csrf
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
                
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" name="remember" id="remember">
                    <label class="form-check-label" for="remember">
                        Remember me
                    </label>
                </div>
                
                <div class="d-grid gap-2 mt-3">
                    <button type="submit" class="btn btn-lg btn-primary">Sign in</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="text-center mb-3">
    Don't have an account? <a href="{{ route('register') }}">Sign up</a>
</div>
@endsection
