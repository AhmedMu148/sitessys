@extends('admin.layouts.master')

@section('title', 'Pages Management')

@section('content')
<div class="container-fluid p-0">
    <div class="mb-3">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 d-inline align-middle">Pages Management</h1>
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
                        <h5 class="card-title mb-0">All Pages</h5>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPageModal">
                            <i data-feather="plus"></i> Add New Page
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    @if($pages->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Page Name</th>
                                        <th>Link/URL</th>
                                        <th>Sections</th>
                                        <th>Last Updated</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pages as $page)
                                        <tr>
                                            <td>{{ $page->id }}</td>
                                            <td>{{ $page->name }}</td>
                                            <td><code>{{ $page->link }}</code></td>
                                            <td>
                                                <span class="badge bg-info">
                                                    {{ count(explode(',', $page->section_id)) }} sections
                                                </span>
                                            </td>
                                            <td>{{ $page->updated_at->format('M d, Y H:i') }}</td>
                                            <td>
                                                <a href="{{ $page->link === '/' ? '/' : $page->link }}" target="_blank" 
                                                   class="btn btn-sm btn-outline-primary" title="Preview">
                                                    <i data-feather="external-link"></i>
                                                </a>
                                                <button class="btn btn-sm btn-outline-secondary" title="Edit"
                                                        onclick="editPage({{ $page->id }}, '{{ $page->name }}', '{{ $page->link }}', '{{ $page->section_id }}')">
                                                    <i data-feather="edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-danger" title="Delete"
                                                        onclick="deletePage({{ $page->id }}, '{{ $page->name }}')">
                                                    <i data-feather="trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-center">
                            {{ $pages->links() }}
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i data-feather="file-text" class="text-muted" style="width: 48px; height: 48px;"></i>
                            <h5 class="mt-3">No pages found</h5>
                            <p class="text-muted">Start building your site by creating your first page.</p>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPageModal">
                                <i data-feather="plus"></i> Create First Page
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Page Modal -->
<div class="modal fade" id="addPageModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Page</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addPageForm">
                    @csrf
                    <div class="mb-3">
                        <label for="page_name" class="form-label">Page Name</label>
                        <input type="text" class="form-control" id="page_name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="page_link" class="form-label">Page Link/URL</label>
                        <input type="text" class="form-control" id="page_link" name="link" 
                               placeholder="/about, /contact, etc." required>
                        <div class="form-text">Start with / (e.g., /about, /contact)</div>
                    </div>
                    <div class="mb-3">
                        <label for="section_ids" class="form-label">Section IDs</label>
                        <input type="text" class="form-control" id="section_ids" name="section_id" 
                               placeholder="1,2,3" required>
                        <div class="form-text">Comma-separated list of section IDs to include in this page</div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="savePage()">Save Page</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function savePage() {
    // For now, show a message that this feature will be implemented
    alert('Page creation feature will be implemented next. This is the admin interface structure.');
}

function editPage(id, name, link, sectionId) {
    alert('Edit page feature will be implemented. Page: ' + name);
}

function deletePage(id, name) {
    if (confirm('Are you sure you want to delete the page "' + name + '"?')) {
        alert('Delete page feature will be implemented. Page: ' + name);
    }
}
</script>
@endpush
