@extends('layouts.admin')

@section('title', 'Add New Product')

@section('actions')
<a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
    <i class="fas fa-arrow-left me-1"></i> Back to Products
</a>
@endsection

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="row">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label for="name" class="form-label">Product Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="slug" class="form-label">Slug <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('slug') is-invalid @enderror" id="slug" name="slug" value="{{ old('slug') }}" required>
                        <small class="text-muted">The slug is used in the URL. It should contain only letters, numbers, and hyphens.</small>
                        @error('slug')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="5" required>{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="price" class="form-label">Price ($) <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" min="0" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price') }}" required>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="compare_price" class="form-label">Compare at Price ($)</label>
                                <input type="number" step="0.01" min="0" class="form-control @error('compare_price') is-invalid @enderror" id="compare_price" name="compare_price" value="{{ old('compare_price') }}">
                                <small class="text-muted">Leave blank if the product is not on sale</small>
                                @error('compare_price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="sku" class="form-label">SKU</label>
                                <input type="text" class="form-control @error('sku') is-invalid @enderror" id="sku" name="sku" value="{{ old('sku') }}">
                                @error('sku')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="stock" class="form-label">Stock Quantity <span class="text-danger">*</span></label>
                                <input type="number" min="0" class="form-control @error('stock') is-invalid @enderror" id="stock" name="stock" value="{{ old('stock', 0) }}" required>
                                @error('stock')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="category_id" class="form-label">Category <span class="text-danger">*</span></label>
                        <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="images" class="form-label">Product Images</label>
                        <input type="file" class="form-control @error('images.*') is-invalid @enderror" id="images" name="images[]" accept="image/*" multiple>
                        <small class="text-muted">You can select multiple images. The first image will be set as the primary image.</small>
                        <small class="d-block text-muted">Recommended size: 800x800 pixels</small>
                        <div id="preview-container" class="mt-2 d-flex flex-wrap gap-2"></div>
                        @error('images.*')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_featured">Featured Product</label>
                        </div>
                        <small class="text-muted">Featured products appear on the homepage</small>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', 1) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Active</label>
                        </div>
                        <small class="text-muted">Inactive products are not visible to customers</small>
                    </div>
                </div>
            </div>
            
            <hr>
            
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i> Save Product
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Auto-generate slug from name
    document.getElementById('name').addEventListener('input', function() {
        const name = this.value;
        const slug = name.toLowerCase()
            .replace(/[^\w\s-]/g, '')
            .replace(/[\s_-]+/g, '-')
            .replace(/^-+|-+$/g, '');
        document.getElementById('slug').value = slug;
    });
    
    // Image preview functionality
    document.getElementById('images').addEventListener('change', function() {
        const previewContainer = document.getElementById('preview-container');
        previewContainer.innerHTML = '';
        
        if (this.files) {
            Array.from(this.files).forEach((file, index) => {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    const previewDiv = document.createElement('div');
                    previewDiv.className = 'position-relative';
                    
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'img-thumbnail';
                    img.style.width = '100px';
                    img.style.height = '100px';
                    img.style.objectFit = 'cover';
                    
                    const badge = document.createElement('span');
                    badge.className = index === 0 ? 'position-absolute top-0 start-0 badge bg-primary' : 'position-absolute top-0 start-0 badge bg-secondary';
                    badge.textContent = index === 0 ? 'Primary' : `#${index + 1}`;
                    
                    previewDiv.appendChild(img);
                    previewDiv.appendChild(badge);
                    previewContainer.appendChild(previewDiv);
                };
                
                reader.readAsDataURL(file);
            });
        }
    });
    
    // Add form submission debugging
    document.querySelector('form').addEventListener('submit', function(e) {
        // Log form data for debugging
        console.log('Form is being submitted');
        
        // Create a copy of the form data for logging only
        const formData = new FormData(this);
        
        // Check if files are in the FormData
        console.log('Files count:', formData.getAll('images[]').length);
        
        // Log all form data keys
        const formDataKeys = [];
        for (const key of formData.keys()) {
            formDataKeys.push(key);
        }
        console.log('Form data keys:', formDataKeys);
        
        // Do not prevent default form submission
    });
</script>
@endsection 