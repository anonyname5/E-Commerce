@extends('layouts.admin')

@section('title', 'Edit Category')

@section('actions')
<a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
    <i class="fas fa-arrow-left me-1"></i> Back to Categories
</a>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="card-title">Edit Category: {{ $category->name }}</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="mb-3">
                <label for="name" class="form-label">Category Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $category->name) }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="text-muted">The slug will be automatically generated from the name.</small>
            </div>
            
            <div class="mb-3">
                <label for="parent_id" class="form-label">Parent Category</label>
                <select class="form-select @error('parent_id') is-invalid @enderror" id="parent_id" name="parent_id">
                    <option value="">None (Top Level Category)</option>
                    @foreach($categories as $cat)
                        @if($cat->id != $category->id)
                            <option value="{{ $cat->id }}" {{ old('parent_id', $category->parent_id) == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endif
                    @endforeach
                </select>
                @error('parent_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="text-muted">Leave empty to make this a top-level category</small>
            </div>
            
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description', $category->description) }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="image" class="form-label">Category Image</label>
                <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*">
                @error('image')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                
                @if($category->image)
                    <div class="mt-2">
                        <p class="text-muted mb-1">Current Image:</p>
                        <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" class="img-thumbnail" style="max-width: 200px; max-height: 200px;">
                    </div>
                @endif
                <small class="text-muted d-block mt-2">Leave empty to keep the current image</small>
            </div>
            
            <div class="form-check form-switch mb-3">
                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                <label class="form-check-label" for="is_active">Active</label>
            </div>
            
            <div class="d-flex justify-content-end">
                <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary me-2">Cancel</a>
                <button type="submit" class="btn btn-primary">Update Category</button>
            </div>
        </form>
    </div>
</div>
@endsection 