@extends('admin.layouts.master')

@section('page_title', 'Edit Layout')

@section('page_content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Edit Layout</h3>
    </div>
    <form action="{{ route('admin.layouts.update', $layout) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="card-body">
            <div class="form-group">
                <label for="type_id">Layout Type</label>
                <select name="type_id" id="type_id" class="form-control @error('type_id') is-invalid @enderror">
                    <option value="">Select Layout Type</option>
                    @foreach($types as $type)
                        <option value="{{ $type->id }}" {{ ($layout->type_id == $type->id) ? 'selected' : '' }}>
                            {{ ucfirst($type->name) }}
                        </option>
                    @endforeach
                </select>
                @error('type_id')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" 
                       value="{{ old('name', $layout->name) }}" required>
                @error('name')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="preview_image">Preview Image</label>
                @if($layout->preview_image)
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $layout->preview_image) }}" alt="Current Preview" class="img-thumbnail" style="max-width: 200px;">
                    </div>
                @endif
                <input type="file" name="preview_image" id="preview_image" 
                       class="form-control @error('preview_image') is-invalid @enderror" 
                       accept="image/*">
                @error('preview_image')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="html_template">HTML Template</label>
                <textarea name="html_template" id="html_template" rows="15" 
                          class="form-control @error('html_template') is-invalid @enderror" 
                          required>{{ old('html_template', $layout->html_template) }}</textarea>
                @error('html_template')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
                <small class="form-text text-muted">
                    Use <code>{{ '{{ $data["key"] }}' }}</code> for dynamic content.
                </small>
            </div>
            
            <div class="form-group">
                <div class="custom-control custom-switch">
                    <input type="checkbox" name="status" class="custom-control-input" 
                           id="status" {{ old('status', $layout->status) ? 'checked' : '' }}>
                    <label class="custom-control-label" for="status">Active</label>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Update Layout</button>
            <a href="{{ route('admin.layouts.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection

@section('page_js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add syntax highlighting or code editor here if needed
    const textarea = document.getElementById('html_template');
    textarea.style.fontFamily = 'monospace';
});
</script>
@endsection
