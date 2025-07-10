@extends('admin.layouts.master')

@section('content')
<div class="container-fluid p-0">
    <h1 class="h3 mb-3">Consistency Management</h1>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible" role="alert">
        <div class="alert-message">
            {{ session('success') }}
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Navigation Consistency</h5>
                </div>
                <div class="card-body">
                    <div class="alert {{ $navConsistent ? 'alert-success' : 'alert-warning' }}">
                        <div class="alert-message">
                            @if($navConsistent)
                                <strong>Great!</strong> Navigation is consistent across all pages.
                            @else
                                <strong>Warning!</strong> Navigation is not consistent across all pages.
                            @endif
                        </div>
                    </div>

                    <form action="{{ route('admin.consistency.navbar') }}" method="POST" class="mb-4">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Brand Name</label>
                            <input type="text" class="form-control" name="brand" value="TechCorp" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">CTA Button Text</label>
                            <input type="text" class="form-control" name="cta_text" value="Get Started" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">CTA Button URL</label>
                            <input type="text" class="form-control" name="cta_url" value="#contact" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Update Navigation Across All Pages</button>
                    </form>

                    <a href="#" onclick="event.preventDefault(); document.getElementById('quickFix').submit();" 
                       class="btn btn-success">Quick Fix Now</a>
                    <form id="quickFix" action="{{ route('admin.consistency.navbar') }}" method="POST" style="display: none;">
                        @csrf
                        <input type="hidden" name="brand" value="TechCorp">
                        <input type="hidden" name="cta_text" value="Get Started">
                        <input type="hidden" name="cta_url" value="#contact">
                    </form>
                </div>
            </div>
        </div>

        <div class="col-12 mt-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Footer Consistency</h5>
                </div>
                <div class="card-body">
                    <div class="alert {{ $footerConsistent ? 'alert-success' : 'alert-warning' }}">
                        <div class="alert-message">
                            @if($footerConsistent)
                                <strong>Great!</strong> Footer is consistent across all pages.
                            @else
                                <strong>Warning!</strong> Footer is not consistent across all pages.
                            @endif
                        </div>
                    </div>

                    <form action="{{ route('admin.consistency.footer') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Brand Name</label>
                            <input type="text" class="form-control" name="brand" value="TechCorp" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" rows="3" required>We are a leading technology company providing innovative solutions.</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" value="info@techcorp.com" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Phone</label>
                            <input type="text" class="form-control" name="phone" value="+1 (123) 456-7890" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Address</label>
                            <textarea class="form-control" name="address" rows="2" required>123 Tech Street, Silicon Valley, CA 94043</textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Update Footer Across All Pages</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
