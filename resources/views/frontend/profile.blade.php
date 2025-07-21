@extends('frontend.layouts.app')

@section('content')
<div class="container my-5">
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="avatar bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                            <i class="fas fa-user fa-2x"></i>
                        </div>
                        <h5 class="mt-3 mb-1">{{ Auth::user()->name }}</h5>
                        <p class="text-muted small">{{ Auth::user()->email }}</p>
                    </div>
                    
                    <nav class="nav flex-column">
                        <a class="nav-link active" href="#profile">
                            <i class="fas fa-user me-2"></i>Profile
                        </a>
                        <a class="nav-link" href="#settings">
                            <i class="fas fa-cog me-2"></i>Settings
                        </a>
                        <a class="nav-link" href="#security">
                            <i class="fas fa-shield-alt me-2"></i>Security
                        </a>
                        <a class="nav-link text-danger" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                        </a>
                    </nav>
                    
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Profile Information</h5>
                </div>
                <div class="card-body">
                    <form>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Full Name</label>
                                <input type="text" class="form-control" value="{{ Auth::user()->name }}" readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" value="{{ Auth::user()->email }}" readonly>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Member Since</label>
                                <input type="text" class="form-control" value="{{ Auth::user()->created_at->format('M d, Y') }}" readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Status</label>
                                <input type="text" class="form-control" value="Active" readonly>
                            </div>
                        </div>
                        
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            This is a demo profile page. Contact administrator to update your information.
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
