@extends('admin.layouts.master')

@section('title', 'Layouts Management')

@section('content')
    {{-- Page header + "Create New" button --}}
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h1 class="h3 mb-0"><strong>Layouts</strong> Management</h1>
            <a href="{{ route('admin.layouts.create') }}" class="btn btn-primary">
                <i class="align-middle" data-feather="plus"></i>
                Create New Layout
            </a>
        </div>
    </div>

    {{-- Traditional table of layouts --}}
    <div class="row mb-5">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        All Layouts
                        @if($typeFilter)
                            &nbsp; &rsaquo; <small>{{ ucfirst($typeFilter) }} Only</small>
                        @endif
                    </h5>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($layouts->count())
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Type</th>
                                        <th>Preview</th>
                                        <th>Status</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($layouts as $layout)
                                        <tr>
                                            {{-- ID --}}
                                            <td>{{ $layout->id }}</td>

                                            {{-- Name & description snippet --}}
                                            <td>
                                                <strong>{{ $layout->name }}</strong>
                                                @if($layout->description)
                                                    <br>
                                                    <small class="text-muted">
                                                        {{ Str::limit($layout->description, 50) }}
                                                    </small>
                                                @endif
                                            </td>

                                            {{-- Type badge --}}
                                            <td>
                                                @php
                                                    $typeName = $layout->type->name;
                                                    $badge = $typeName === 'nav'
                                                        ? 'primary'
                                                        : ($typeName === 'section' ? 'success' : 'info');
                                                @endphp
                                                <span class="badge bg-{{ $badge }}">
                                                    {{ ucfirst($typeName) }}
                                                </span>
                                            </td>

                                            {{-- Preview image or placeholder --}}
                                            <td>
                                                @if($layout->preview_image)
                                                    <img src="{{ asset('storage/'.$layout->preview_image) }}"
                                                         class="img-thumbnail"
                                                         style="max-width: 80px;"
                                                         alt="Preview">
                                                @else
                                                    <div class="text-center"
                                                         style="width:80px;height:50px;
                                                                background:#f8f9fa;
                                                                border:1px solid #dee2e6;
                                                                display:flex;
                                                                align-items:center;
                                                                justify-content:center;
                                                                border-radius:4px;">
                                                        <i class="align-middle text-muted"
                                                           data-feather="image"></i>
                                                    </div>
                                                @endif
                                            </td>

                                            {{-- Status badge --}}
                                            <td>
                                                <span class="badge bg-{{ $layout->status ? 'success' : 'secondary' }}">
                                                    {{ $layout->status ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>

                                            {{-- Creation date --}}
                                            <td>{{ $layout->created_at->format('M d, Y') }}</td>

                                            {{-- Action buttons --}}
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('admin.layouts.show', $layout) }}"
                                                       class="btn btn-sm btn-outline-info">
                                                        <i class="align-middle" data-feather="eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.layouts.edit', $layout) }}"
                                                       class="btn btn-sm btn-outline-primary">
                                                        <i class="align-middle" data-feather="edit"></i>
                                                    </a>
                                                    <form action="{{ route('admin.layouts.destroy', $layout) }}"
                                                          method="POST"
                                                          onsubmit="return confirm('Are you sure you want to delete this layout?');"
                                                          style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                                class="btn btn-sm btn-outline-danger">
                                                            <i class="align-middle" data-feather="trash-2"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- Pagination links (keep ?type=xxx if set) --}}
                        <div class="d-flex justify-content-center mt-3">
                            {{ $layouts->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="align-middle" data-feather="layout"
                               style="width:64px; height:64px; color:#6c757d;"></i>
                            <h4 class="mt-3">No Layouts Found</h4>
                            <p class="text-muted">Start by creating your first layout.</p>
                            <a href="{{ route('admin.layouts.create') }}"
                               class="btn btn-primary">
                                <i class="align-middle" data-feather="plus"></i>
                                Create First Layout
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Grid overview below the table --}}
  @if($layouts->count())
    <div class="row">
        <div class="col-12 mb-3">
            <h5>Layouts Overview</h5>
        </div>

        @foreach($layouts as $layout)
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    {{-- 1. الصورة تملأ عرض الكارد من فوق بدون إطار --}}
                    @if($layout->preview_image)
                        <img src="{{ asset('storage/'.$layout->preview_image) }}"
                             class="card-img-top mb-3"
                             style="border: none; border-radius: 0;"
                             alt="Preview of {{ $layout->name }}">
                    @else
                        <div class="bg-light mb-3"
                             style="width:100%; height:150px;
                                    display:flex; align-items:center; justify-content:center;
                                    border:none; border-radius:0;">
                            <i class="align-middle text-muted"
                               data-feather="layout"
                               style="width:48px; height:48px;"></i>
                        </div>
                    @endif

                    <div class="card-body d-flex flex-column">
                        {{-- 2. العنوان + الحالة --}}
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h5 class="card-title mb-0">{{ $layout->name }}</h5>
                            <span class="badge bg-{{ $layout->status ? 'success' : 'secondary' }}">
                                {{ $layout->status ? 'Active' : 'Inactive' }}
                            </span>
                        </div>

                        {{-- 3. Type badge --}}
                        <p class="mb-2">
                            @php
                                $t = $layout->type->name;
                                $c = $t === 'nav' ? 'primary'
                                   : ($t === 'section' ? 'success' : 'info');
                            @endphp
                            <span class="badge bg-{{ $c }}">{{ ucfirst($t) }}</span>
                        </p>

                        {{-- 4. الوصف --}}
                        <p class="text-muted mb-4 flex-grow-1">
                            {{ Str::limit($layout->description ?: 'No description provided.', 80) }}
                        </p>

                        {{-- 5. Dropdown actions في الأسفل --}}
                        <div class="mt-auto">
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle"
                                        type="button"
                                        id="actions{{ $layout->id }}"
                                        data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                    Actions
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="actions{{ $layout->id }}">
                                    <li>
                                        <a class="dropdown-item"
                                           href="{{ route('admin.layouts.show', $layout) }}">
                                            <i class="align-middle me-1" data-feather="eye"></i>
                                            View
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item"
                                           href="{{ route('admin.layouts.edit', $layout) }}">
                                            <i class="align-middle me-1" data-feather="edit"></i>
                                            Edit
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form action="{{ route('admin.layouts.destroy', $layout) }}"
                                              method="POST"
                                              onsubmit="return confirm('هل أنت متأكد من حذف هذا Layout؟');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="dropdown-item text-danger" type="submit">
                                                <i class="align-middle me-1" data-feather="trash-2"></i>
                                                Delete
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        {{-- Pagination for grid view --}}
        <div class="col-12 d-flex justify-content-center">
            {{ $layouts->links() }}
        </div>
    </div>
@endif



@endsection
