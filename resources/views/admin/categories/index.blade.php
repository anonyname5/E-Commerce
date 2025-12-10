@extends('layouts.admin')

@section('title', 'Categories')

@php
use Illuminate\Support\Str;
@endphp

@section('styles')
<style>
    /* Table header styles */
    .table thead th {
        background-color: #343a40 !important;
        color: #ffffff !important;
        border-bottom: none;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 12px;
        letter-spacing: 0.5px;
        padding: 12px 16px;
        transition: background-color 0.3s ease;
    }
    
    [data-bs-theme="dark"] .table thead th {
        background-color: #212529 !important;
    }
    
    [data-bs-theme="dark"] .table tbody td {
        border-color: var(--border-color);
    }
    
    [data-bs-theme="dark"] .table-hover tbody tr:hover {
        background-color: rgba(255, 255, 255, 0.05);
    }
    
    /* Category actions button styling */
    .category-actions {
        position: relative;
        z-index: 1;
        transition: all 0.3s ease;
    }
    
    /* Subcategory indentation */
    .subcategory-row td:not(:first-child) {
        padding-left: 30px;
    }
    
    .subcategory-indicator {
        margin-right: 5px;
        color: var(--text-muted);
    }
    
    /* Image placeholder for dark mode */
    [data-bs-theme="dark"] .bg-light {
        background-color: #2d3748 !important;
    }
    
    [data-bs-theme="dark"] .img-thumbnail {
        background-color: #2d3748;
        border-color: var(--border-color);
    }
    
    .table-responsive {
        overflow-x: auto;
        border-radius: 8px;
    }
    
    @media (max-width: 768px) {
        .category-actions {
            width: 100%;
            margin-bottom: 15px;
        }
    }
    
    /* Empty state styling */
    .empty-state-icon {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background-color: var(--border-light);
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }
    
    [data-bs-theme="dark"] .empty-state-icon {
        background-color: rgba(255, 255, 255, 0.05);
    }
</style>
@endsection


@section('content')
<div class="card border-0 shadow-sm dashboard-card">
    <div class="card-header py-3">
        <div class="row w-100 align-items-center">
            <div class="col-md-6 mb-3 mb-md-0">
                <h5 class="mb-0 d-flex align-items-center">
                    <i class="fas fa-tags text-primary me-2"></i>
                    Categories Management
                </h5>
                <p class="text-muted mb-0 mt-1 small">
                    <i class="fas fa-info-circle me-1"></i>
                    Manage your product categories and subcategories
                </p>
            </div>
            <div class="col-md-6 text-md-end">
                <button type="button" class="btn btn-primary category-actions" data-bs-toggle="modal" data-bs-target="#createCategoryModal">
                    <i class="fas fa-plus me-1"></i> Add New Category
                </button>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th width="80">Image</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Slug</th>
                        <th>Status</th>
                        <th>Products</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($parentCategories as $parentCategory)
                        <tr>
                            <td>
                                @if($parentCategory->image)
                                    <img src="{{ asset('storage/' . $parentCategory->image) }}" alt="{{ $parentCategory->name }}" class="img-thumbnail" width="50">
                                @else
                                    <div class="d-flex align-items-center justify-content-center rounded" style="width: 50px; height: 50px; background-color: var(--border-light)">
                                        <i class="fas fa-folder text-muted"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <strong>{{ $parentCategory->name }}</strong>
                                @if($parentCategory->children->count() > 0)
                                    <span class="badge bg-info ms-2">{{ $parentCategory->children->count() }} subcategories</span>
                                @endif
                            </td>
                            <td>{{ Str::limit($parentCategory->description, 50) }}</td>
                            <td>{{ $parentCategory->slug }}</td>
                            <td>
                                @if($parentCategory->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-danger">Inactive</span>
                                @endif
                            </td>
                            <td>
                                {{ $parentCategory->products->count() }}
                            </td>
                            <td>
                                <div class="d-flex">
                                    <a href="{{ route('admin.categories.edit', $parentCategory->id) }}" class="btn btn-sm btn-outline-primary me-1">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="{{ route('shop.category', ['category' => $parentCategory->slug]) }}" class="btn btn-sm btn-outline-info me-1" target="_blank">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <form action="{{ route('admin.categories.destroy', $parentCategory->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this category?');">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        
                        {{-- Display subcategories --}}
                        @foreach($parentCategory->children as $childCategory)
                            <tr class="subcategory-row">
                                <td>
                                    @if($childCategory->image)
                                        <img src="{{ asset('storage/' . $childCategory->image) }}" alt="{{ $childCategory->name }}" class="img-thumbnail" width="50">
                                    @else
                                        <div class="d-flex align-items-center justify-content-center rounded" style="width: 50px; height: 50px; background-color: var(--border-light)">
                                            <i class="fas fa-tag text-muted"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <i class="fas fa-level-down-alt fa-rotate-90 subcategory-indicator"></i>
                                    {{ $childCategory->name }}
                                </td>
                                <td>{{ Str::limit($childCategory->description, 50) }}</td>
                                <td>{{ $childCategory->slug }}</td>
                                <td>
                                    @if($childCategory->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    {{ $childCategory->products->count() }}
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <a href="{{ route('admin.categories.edit', $childCategory->id) }}" class="btn btn-sm btn-outline-primary me-1">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{ route('shop.category', ['category' => $childCategory->slug]) }}" class="btn btn-sm btn-outline-info me-1" target="_blank">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <form action="{{ route('admin.categories.destroy', $childCategory->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this category?');">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="d-flex flex-column align-items-center p-4">
                                    <div class="empty-state-icon mb-3">
                                        <i class="fas fa-folder-open fa-3x text-muted"></i>
                                    </div>
                                    <h5>No Categories Found</h5>
                                    <p class="text-muted mb-3">Get started by adding your first product category</p>
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createCategoryModal">
                                        <i class="fas fa-plus me-1"></i> Add New Category
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-4">
    {{ $parentCategories->links() }}
</div>

<!-- Create Category Modal -->
<div class="modal fade" id="createCategoryModal" tabindex="-1" aria-labelledby="createCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="createCategoryModalLabel">
                        <i class="fas fa-plus-circle text-primary me-2"></i>
                        Add New Category
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Category Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" required>
                        <small class="text-muted">The slug will be automatically generated from the name.</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="parent_id" class="form-label">Parent Category</label>
                        <select class="form-select" id="parent_id" name="parent_id">
                            <option value="">None (Top Level Category)</option>
                            @foreach($allCategories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                        <small class="text-muted">Leave empty to create a top-level category</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="image" class="form-label">Category Image</label>
                        <input type="file" class="form-control" id="image" name="image" accept="image/*" onchange="previewImage(this, 'create_image_preview')">
                        <div class="mt-2" id="create_image_preview_container" style="display: none;">
                            <p class="text-muted mb-1">Image Preview:</p>
                            <img id="create_image_preview" src="" alt="Category image preview" class="img-thumbnail" style="max-width: 150px; max-height: 150px;">
                        </div>
                    </div>
                    
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" checked>
                        <label class="form-check-label" for="is_active">Active</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Save Category
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Category Modal -->
<!-- Modal code removed as we're now using a dedicated edit page -->
@endsection

@section('scripts')
<script>
    // Image preview function
    function previewImage(input, previewId) {
        const preview = document.getElementById(previewId);
        const previewContainer = document.getElementById(previewId + '_container');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                preview.src = e.target.result;
                previewContainer.style.display = 'block';
            }
            
            reader.readAsDataURL(input.files[0]);
        } else {
            previewContainer.style.display = 'none';
        }
    }
    
    // Modal JavaScript removed as we're now using a dedicated edit page
</script>
@endsection 