@extends('admin.layouts.master')

@section('title', 'Sections Management')

@section('content')
<div class="container-fluid p-0">
    <div class="mb-3">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 d-inline align-middle">Sections Management</h1>
                <span class="badge bg-primary ms-2">{{ $site->site_name }}</span>
            </div>
            <a href="{{ route('admin.site-content.index') }}" class="btn btn-secondary">
                <i data-feather="arrow-left"></i> Back to Dashboard
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">All Sections</h5>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSectionModal">
                            <i data-feather="plus"></i> Add New Section
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    @if($sections->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Section Name</th>
                                        <th>Position</th>
                                        <th>English Content</th>
                                        <th>Arabic Content</th>
                                        <th>Last Updated</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($sections as $section)
                                        @php
                                            try {
                                                $contentData = json_decode($section->content, true);
                                                $enData = $contentData['en'] ?? [];
                                                $arData = $contentData['ar'] ?? [];
                                            } catch (Exception $e) {
                                                // Fallback for old content format
                                                $enData = ['title' => $section->name, 'content' => $section->content];
                                                $arData = ['title' => $section->name, 'content' => $section->content];
                                            }
                                        @endphp
                                        <tr>
                                            <td><span class="badge bg-primary">{{ $section->id }}</span></td>
                                            <td>
                                                <strong>{{ $section->name }}</strong>
                                                <br>
                                                <small class="text-muted">Position: {{ $section->position }}</small>
                                            </td>
                                            <td><span class="badge bg-secondary">{{ $section->position }}</span></td>
                                            <td>
                                                <div class="mb-1">
                                                    <strong class="text-primary">{{ $enData['title'] ?? 'No title' }}</strong>
                                                </div>
                                                <div class="text-muted small">
                                                    {{ Str::limit($enData['content'] ?? 'No content', 50) }}
                                                </div>
                                                @if(!empty($enData['button_text']))
                                                    <div class="mt-1">
                                                        <span class="badge bg-info">{{ $enData['button_text'] }}</span>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="mb-1">
                                                    <strong class="text-success">{{ $arData['title'] ?? 'No title' }}</strong>
                                                </div>
                                                <div class="text-muted small">
                                                    {{ Str::limit($arData['content'] ?? 'No content', 50) }}
                                                </div>
                                                @if(!empty($arData['button_text']))
                                                    <div class="mt-1">
                                                        <span class="badge bg-info">{{ $arData['button_text'] }}</span>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                <small class="text-muted">{{ $section->updated_at->format('M d, Y') }}</small>
                                                <br>
                                                <small class="text-muted">{{ $section->updated_at->format('H:i') }}</small>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <button class="btn btn-sm btn-outline-secondary" title="Edit"
                                                            onclick="editSection({{ $section->id }})">
                                                        <i data-feather="edit"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-danger" title="Delete"
                                                            onclick="deleteSection({{ $section->id }})">
                                                        <i data-feather="trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-center">
                            {{ $sections->links() }}
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i data-feather="grid" class="text-muted" style="width: 48px; height: 48px;"></i>
                            <h5 class="mt-3">No sections found</h5>
                            <p class="text-muted">Sections are the building blocks of your pages. Create your first section to get started.</p>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSectionModal">
                                <i data-feather="plus"></i> Create First Section
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Section Modal -->
<div class="modal fade" id="addSectionModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Section</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addSectionForm">
                    @csrf
                    
                    <!-- English Content -->
                    <div class="mb-4">
                        <h6 class="text-primary">English Content</h6>
                        <div class="mb-3">
                            <label for="en_title" class="form-label">Title</label>
                            <input type="text" class="form-control" id="en_title" name="en_title" required>
                        </div>
                        <div class="mb-3">
                            <label for="en_content" class="form-label">Content</label>
                            <textarea class="form-control" id="en_content" name="en_content" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="en_button_text" class="form-label">Button Text (Optional)</label>
                            <input type="text" class="form-control" id="en_button_text" name="en_button_text">
                        </div>
                    </div>
                    
                    <!-- Arabic Content -->
                    <div class="mb-4">
                        <h6 class="text-success">Arabic Content</h6>
                        <div class="mb-3">
                            <label for="ar_title" class="form-label">Title</label>
                            <input type="text" class="form-control" id="ar_title" name="ar_title" required>
                        </div>
                        <div class="mb-3">
                            <label for="ar_content" class="form-label">Content</label>
                            <textarea class="form-control" id="ar_content" name="ar_content" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="ar_button_text" class="form-label">Button Text (Optional)</label>
                            <input type="text" class="form-control" id="ar_button_text" name="ar_button_text">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="saveSection()">Save Section</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function saveSection() {
    // For now, show a message that this feature will be implemented
    alert('Section creation feature will be implemented next. This is the admin interface structure.');
}

function editSection(id) {
    alert('Edit section feature will be implemented. Section ID: ' + id);
}

function deleteSection(id) {
    if (confirm('Are you sure you want to delete this section?')) {
        alert('Delete section feature will be implemented. Section ID: ' + id);
    }
}
</script>
@endpush
