@extends('admin.layouts.master')

@section('title', 'Headers & Footers Management')

@section('content')
<div class="container-fluid p-0">
    {{-- Page Header --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3 mb-0"><strong>Headers & Footers</strong> Management</h1>
                <div>
                    <a href="{{ route('admin.headers-footers.create', ['type' => 'header']) }}" class="btn btn-primary me-2">
                        <i class="align-middle" data-feather="plus"></i>
                        Create Header
                    </a>
                    <a href="{{ route('admin.headers-footers.create', ['type' => 'footer']) }}" class="btn btn-success">
                        <i class="align-middle" data-feather="plus"></i>
                        Create Footer
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        {{-- Headers Section --}}
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="align-middle" data-feather="navigation"></i>
                        Headers ({{ $headers->count() }})
                    </h5>
                </div>
                <div class="card-body">
                    @if($headers->count() > 0)
                        @foreach($headers as $header)
                        <div class="border rounded p-3 mb-3 {{ $site->active_header_id == $header->id ? 'border-primary bg-light' : '' }}">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <h6 class="fw-bold mb-1">
                                        {{ $header->name }}
                                        @if($site->active_header_id == $header->id)
                                            <span class="badge bg-primary ms-2">Active</span>
                                        @endif
                                    </h6>
                                    @if($header->description)
                                        <p class="text-muted mb-2 small">{{ $header->description }}</p>
                                    @endif
                                    <small class="text-muted">
                                        Created: {{ $header->created_at->format('M d, Y') }}
                                    </small>
                                </div>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        Actions
                                    </button>
                                    <ul class="dropdown-menu">
                                        @if($site->active_header_id != $header->id)
                                        <li>
                                            <form action="{{ route('admin.headers-footers.activate', $header->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="dropdown-item">
                                                    <i class="align-middle" data-feather="check"></i>
                                                    Set as Active
                                                </button>
                                            </form>
                                        </li>
                                        @endif
                                        <li>
                                            <a class="dropdown-item" href="{{ route('admin.headers-footers.edit', $header->id) }}">
                                                <i class="align-middle" data-feather="edit-2"></i>
                                                Edit
                                            </a>
                                        </li>
                                        @if($site->active_header_id != $header->id)
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form action="{{ route('admin.headers-footers.destroy', $header->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Are you sure you want to delete this header?')">
                                                    <i class="align-middle" data-feather="trash-2"></i>
                                                    Delete
                                                </button>
                                            </form>
                                        </li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="text-center py-4">
                            <i class="align-middle" data-feather="navigation" style="font-size: 48px; color: #ccc;"></i>
                            <p class="text-muted mt-2">No headers created yet.</p>
                            <a href="{{ route('admin.headers.create') }}" class="btn btn-primary">
                                Create Your First Header
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Footers Section --}}
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="align-middle" data-feather="layers"></i>
                        Footers ({{ $footers->count() }})
                    </h5>
                </div>
                <div class="card-body">
                    @if($footers->count() > 0)
                        @foreach($footers as $footer)
                        <div class="border rounded p-3 mb-3 {{ $site->active_footer_id == $footer->id ? 'border-success bg-light' : '' }}">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <h6 class="fw-bold mb-1">
                                        {{ $footer->name }}
                                        @if($site->active_footer_id == $footer->id)
                                            <span class="badge bg-success ms-2">Active</span>
                                        @endif
                                    </h6>
                                    @if($footer->description)
                                        <p class="text-muted mb-2 small">{{ $footer->description }}</p>
                                    @endif
                                    <small class="text-muted">
                                        Created: {{ $footer->created_at->format('M d, Y') }}
                                    </small>
                                </div>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        Actions
                                    </button>
                                    <ul class="dropdown-menu">
                                        @if($site->active_footer_id != $footer->id)
                                        <li>
                                            <form action="{{ route('admin.headers-footers.activate', $footer->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="dropdown-item">
                                                    <i class="align-middle" data-feather="check"></i>
                                                    Set as Active
                                                </button>
                                            </form>
                                        </li>
                                        @endif
                                        <li>
                                            <a class="dropdown-item" href="{{ route('admin.headers-footers.edit', $footer->id) }}">
                                                <i class="align-middle" data-feather="edit-2"></i>
                                                Edit
                                            </a>
                                        </li>
                                        @if($site->active_footer_id != $footer->id)
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form action="{{ route('admin.headers-footers.destroy', $footer->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Are you sure you want to delete this footer?')">
                                                    <i class="align-middle" data-feather="trash-2"></i>
                                                    Delete
                                                </button>
                                            </form>
                                        </li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="text-center py-4">
                            <i class="align-middle" data-feather="layers" style="font-size: 48px; color: #ccc;"></i>
                            <p class="text-muted mt-2">No footers created yet.</p>
                            <a href="{{ route('admin.headers-footers.create', ['type' => 'footer']) }}" class="btn btn-success">
                                Create Your First Footer
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Auto-hide success alerts
    setTimeout(function() {
        $('.alert-success').fadeOut();
    }, 5000);
</script>
@endpush
