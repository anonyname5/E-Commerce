@extends('layouts.admin')

@section('title', $product->name)

@section('actions')
<div class="btn-group" role="group">
    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i> Back to Products
    </a>
    <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-primary">
        <i class="fas fa-edit me-1"></i> Edit Product
    </a>
    <a href="{{ route('products.show', $product) }}" class="btn btn-info" target="_blank">
        <i class="fas fa-external-link-alt me-1"></i> View on Site
    </a>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-4 mb-4 mb-md-0">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
                @if($product->images->count() > 0)
                    <div id="productImageCarousel" class="carousel slide mb-3" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            @foreach($product->images as $index => $image)
                                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                    <img src="{{ asset('storage/' . $image->image) }}" alt="{{ $product->name }}" class="d-block w-100" style="max-height: 300px; object-fit: contain;">
                                    @if($image->is_primary)
                                        <div class="text-center mt-1">
                                            <span class="badge bg-primary">Primary Image</span>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                        @if($product->images->count() > 1)
                            <button class="carousel-control-prev" type="button" data-bs-target="#productImageCarousel" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#productImageCarousel" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        @endif
                    </div>
                    
                    @if($product->images->count() > 1)
                        <div class="d-flex flex-wrap justify-content-center gap-2 mb-3">
                            @foreach($product->images as $index => $image)
                                <div class="cursor-pointer" data-bs-target="#productImageCarousel" data-bs-slide-to="{{ $index }}">
                                    <img src="{{ asset('storage/' . $image->image) }}" alt="Thumbnail" class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover; cursor: pointer;">
                                </div>
                            @endforeach
                        </div>
                    @endif
                @else
                    <div class="bg-light d-flex align-items-center justify-content-center mb-3" style="height: 300px">
                        <i class="fas fa-image fa-3x text-muted"></i>
                    </div>
                @endif
                
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">Status</h5>
                        @if($product->is_active)
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-danger">Inactive</span>
                        @endif
                    </div>
                    
                    <div>
                        <h5 class="mb-0">Featured</h5>
                        @if($product->is_featured)
                            <span class="badge bg-primary">Yes</span>
                        @else
                            <span class="badge bg-secondary">No</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="card-title mb-0">Product Information</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-3 fw-bold">Product Name</div>
                    <div class="col-md-9">{{ $product->name }}</div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-3 fw-bold">Slug</div>
                    <div class="col-md-9">{{ $product->slug }}</div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-3 fw-bold">Category</div>
                    <div class="col-md-9">{{ $product->category->name }}</div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-3 fw-bold">SKU</div>
                    <div class="col-md-9">{{ $product->sku ?: 'N/A' }}</div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-3 fw-bold">Price</div>
                    <div class="col-md-9">${{ number_format($product->price, 2) }}</div>
                </div>
                
                @if($product->compare_price)
                <div class="row mb-3">
                    <div class="col-md-3 fw-bold">Compare at Price</div>
                    <div class="col-md-9">${{ number_format($product->compare_price, 2) }}</div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-3 fw-bold">Discount</div>
                    <div class="col-md-9">
                        @php
                            $discountPercentage = round((($product->compare_price - $product->price) / $product->compare_price) * 100);
                        @endphp
                        <span class="badge bg-danger">{{ $discountPercentage }}% OFF</span>
                    </div>
                </div>
                @endif
                
                <div class="row mb-3">
                    <div class="col-md-3 fw-bold">Stock</div>
                    <div class="col-md-9">
                        @if($product->stock > 10)
                            <span class="text-success">{{ $product->stock }} in stock</span>
                        @elseif($product->stock > 0)
                            <span class="text-warning">{{ $product->stock }} in stock (Low)</span>
                        @else
                            <span class="text-danger">Out of stock</span>
                        @endif
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-3 fw-bold">Created At</div>
                    <div class="col-md-9">{{ $product->created_at->format('M d, Y h:i A') }}</div>
                </div>
                
                <div class="row">
                    <div class="col-md-3 fw-bold">Last Updated</div>
                    <div class="col-md-9">{{ $product->updated_at->format('M d, Y h:i A') }}</div>
                </div>
            </div>
        </div>
        
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="card-title mb-0">Product Description</h5>
            </div>
            <div class="card-body">
                <div class="product-description">
                    {{ $product->description }}
                </div>
            </div>
        </div>
    </div>
</div>

<div class="mt-4 d-flex justify-content-between">
    <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this product? This action cannot be undone.');">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger">
            <i class="fas fa-trash me-1"></i> Delete Product
        </button>
    </form>
    
    <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-primary">
        <i class="fas fa-edit me-1"></i> Edit Product
    </a>
</div>
@endsection 