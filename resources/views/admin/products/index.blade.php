@extends('layouts.admin')

@section('title', 'Products')

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
    
    /* Product actions button styling */
    .product-actions {
        position: relative;
        z-index: 1;
        transition: all 0.3s ease;
    }
    
    /* Product image styling */
    .product-image-container {
        position: relative;
        width: 60px;
        height: 60px;
        border-radius: 8px;
        overflow: hidden;
        transition: all 0.3s ease;
    }
    
    .product-image-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border: 1px solid var(--border-color);
        border-radius: 4px;
        transition: all 0.3s ease;
    }
    
    /* Empty product image placeholder */
    .product-image-placeholder {
        width: 60px;
        height: 60px;
        background-color: var(--border-light);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }
    
    [data-bs-theme="dark"] .product-image-placeholder {
        background-color: rgba(255, 255, 255, 0.05);
    }
    
    /* Table responsive styling */
    .table-responsive {
        overflow-x: auto;
        border-radius: 8px;
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
    
    @media (max-width: 768px) {
        .product-actions {
            width: 100%;
            margin-bottom: 15px;
        }
    }
</style>
@endsection

@section('content')
<div class="card border-0 shadow-sm dashboard-card">
    <div class="card-header py-3">
        <div class="row w-100 align-items-center">
            <div class="col-md-6 mb-3 mb-md-0">
                <h5 class="mb-0 d-flex align-items-center">
                    <i class="fas fa-box text-primary me-2"></i>
                    Products Management
                </h5>
                <p class="text-muted mb-0 mt-1 small">
                    <i class="fas fa-info-circle me-1"></i>
                    Manage your store's product inventory
                </p>
            </div>
            <div class="col-md-6 text-md-end">
                <a href="{{ route('admin.products.create') }}" class="btn btn-primary product-actions">
                    <i class="fas fa-plus me-1"></i>Add New Product 
                </a>
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
                        <th>Category</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Status</th>
                        <th width="150">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        <tr>
                            <td>
                                @if($product->primaryImage)
                                    <div class="product-image-container">
                                        <img src="{{ asset('storage/' . $product->primaryImage->image) }}" alt="{{ $product->name }}">
                                    </div>
                                @elseif($product->images->count() > 0)
                                    <div class="product-image-container">
                                        <img src="{{ asset('storage/' . $product->images->first()->image) }}" alt="{{ $product->name }}">
                                    </div>
                                @else
                                    <div class="product-image-placeholder">
                                        <i class="fas fa-image text-muted"></i>
                                    </div>
                                @endif
                            </td>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->category->name }}</td>
                            <td>${{ number_format($product->price, 2) }}</td>
                            <td>
                                @if($product->stock > 10)
                                    <span class="text-success">{{ $product->stock }}</span>
                                @elseif($product->stock > 0)
                                    <span class="text-warning">{{ $product->stock }}</span>
                                @else
                                    <span class="text-danger">Out of stock</span>
                                @endif
                            </td>
                            <td>
                                @if($product->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-danger">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-outline-info" target="_blank">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this product?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="d-flex flex-column align-items-center p-4">
                                    <div class="empty-state-icon mb-3">
                                        <i class="fas fa-box-open fa-3x text-muted"></i>
                                    </div>
                                    <h5>No Products Found</h5>
                                    <p class="text-muted mb-3">Get started by adding your first product</p>
                                    <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus me-1"></i> Add New Product
                                    </a>
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
    {{ $products->links() }}
</div>
@endsection 