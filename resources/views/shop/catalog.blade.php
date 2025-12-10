@extends('layouts.app')

@section('title', 'Products Catalog')

@section('content')
<div class="row">
    <!-- Sidebar (Categories) -->
    <div class="col-lg-3 mb-4">
        <div class="filter-section">
            <h5>Categories</h5>
            <ul class="category-list">
                <li>
                    <a href="{{ route('products.catalog') }}" 
                       class="category-link {{ !isset($category) && !request('category') ? 'active' : '' }}">
                        <i class="fas fa-th-large me-2"></i>
                        All Products
                    </a>
                </li>
                
                @php
                    // Get parent categories only
                    $parentCategories = $categories->whereNull('parent_id');
                @endphp
                
                @foreach($parentCategories as $parentCat)
                    <li class="parent-category-item">
                        <a href="{{ route('shop.category', $parentCat) }}" 
                           class="category-link {{ isset($category) && ($category->id === $parentCat->id || (isset($category->parent_id) && $category->parent_id === $parentCat->id)) ? 'active' : '' }}">
                            <i class="fas fa-folder me-2"></i>
                            {{ $parentCat->name }}
                            
                            @php
                                // Get subcategories
                                $subCategories = $categories->where('parent_id', $parentCat->id);
                            @endphp
                            
                            @if($subCategories->count() > 0)
                                <i class="fas fa-chevron-right submenu-arrow"></i>
                            @endif
                        </a>
                        
                        @if($subCategories->count() > 0)
                            <ul class="subcategory-list {{ isset($category) && ($category->id === $parentCat->id || (isset($category->parent_id) && $category->parent_id === $parentCat->id)) ? 'show' : '' }}">
                                @foreach($subCategories as $subCat)
                                    <li>
                                        <a href="{{ route('shop.category', $subCat) }}" 
                                           class="category-link subcategory-link {{ isset($category) && $category->id === $subCat->id ? 'active' : '' }}">
                                            <i class="fas fa-tag me-2"></i>
                                            {{ $subCat->name }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
        
        <div class="filter-section">
            <h5>Filter</h5>
            <form action="{{ route('products.catalog') }}" method="GET" class="filter-form">
                @if(request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif
                
                <div class="mb-3">
                    <label for="search" class="form-label">Search</label>
                    <div class="search-input-group">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" class="form-control" id="search" name="search" 
                               value="{{ request('search') }}" placeholder="Product name...">
                    </div>
                </div>
                
                <div class="filter-actions">
                    <button type="submit" class="btn btn-filter">
                        <i class="fas fa-filter me-1"></i> Apply
                    </button>
                    <a href="{{ route('products.catalog') }}" class="btn btn-clear">
                        <i class="fas fa-times me-1"></i> Clear
                    </a>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Products Grid -->
    <div class="col-lg-9">
        <!-- Heading and Sort -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="h3 mb-0">
                @if(isset($category))
                    @if($category->parent)
                        <a href="{{ route('shop.category', $category->parent) }}" class="text-muted">
                            {{ $category->parent->name }}
                        </a> &gt; 
                    @endif
                    {{ $category->name }}
                @elseif(request('category'))
                    {{ $categories->where('id', request('category'))->first()->name }}
                @else
                    All Products
                @endif
                
                @if(request('search'))
                    <small class="text-muted">search: "{{ request('search') }}"</small>
                @endif
            </h2>
            
            <div class="d-flex align-items-center">
                <span class="text-muted">{{ $products->total() }} products</span>
            </div>
        </div>
        
        @if(isset($category) && $category->children->count() > 0)
            <div class="subcategories-bar mb-4">
                <span class="subcategories-label">Subcategories:</span>
                @foreach($category->children as $subCategory)
                    <a href="{{ route('shop.category', $subCategory) }}" class="subcategory-pill">
                        {{ $subCategory->name }}
                    </a>
                @endforeach
            </div>
        @endif
        
        @if($products->isEmpty())
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i> No products found matching your criteria.
            </div>
        @else
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                @foreach($products as $product)
                    <div class="col">
                        <div class="product-card">
                            @if($product->is_new)
                                <span class="product-badge badge-new">New</span>
                            @endif
                            @if($product->compare_price)
                                <span class="product-badge badge-sale">Sale</span>
                            @endif
                            
                            <div class="card-img-wrapper">
                                @if($product->images->count() > 0)
                                    <img src="{{ asset('storage/' . ($product->images->where('is_primary', true)->first()->image ?? $product->images->first()->image)) }}" 
                                         class="card-img-top" alt="{{ $product->name }}">
                                @elseif($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" 
                                         class="card-img-top" alt="{{ $product->name }}">
                                @else
                                    <div class="bg-light text-center h-100 d-flex align-items-center justify-content-center">
                                        <i class="fas fa-image fa-3x text-muted"></i>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="card-body">
                                <h3 class="product-title">{{ $product->name }}</h3>
                                
                                <div class="product-category-path">
                                    @if($product->category->parent)
                                        <a href="{{ route('shop.category', $product->category->parent) }}" class="parent-category">
                                            {{ $product->category->parent->name }}
                                        </a> &gt; 
                                    @endif
                                    <a href="{{ route('shop.category', $product->category) }}" class="category">
                                        {{ $product->category->name }}
                                    </a>
                                </div>
                                
                                <span class="product-price">${{ number_format($product->price, 2) }}</span>
                                
                                <div class="btn-group">
                                    <a href="{{ route('products.show', $product) }}" class="btn btn-view">
                                        View Details
                                    </a>
                                    <form action="{{ route('cart.add') }}" method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <button type="submit" class="btn btn-add-cart" title="Add to Cart">
                                            <i class="fas fa-shopping-cart"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="mt-4">
                {{ $products->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Category List Styles */
    .filter-section {
        background-color: #1e293b;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 20px;
        color: #f8fafc;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }
    
    .filter-section h5 {
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 15px;
        color: #f8fafc;
        padding-bottom: 10px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    .category-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .category-link {
        display: flex;
        align-items: center;
        padding: 10px 15px;
        color: #cbd5e1;
        text-decoration: none;
        border-radius: 8px;
        transition: all 0.2s;
        position: relative;
    }
    
    .category-link:hover {
        background-color: rgba(255, 255, 255, 0.05);
        color: #ffffff;
    }
    
    .category-link.active {
        background-color: rgba(99, 102, 241, 0.15);
        color: #818cf8;
        font-weight: 500;
    }
    
    .parent-category-item {
        position: relative;
        margin-bottom: 2px;
    }
    
    .submenu-arrow {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 0.8rem;
        transition: transform 0.3s ease;
        color: #64748b;
    }
    
    .parent-category-item:hover .submenu-arrow,
    .category-link.active .submenu-arrow {
        color: #818cf8;
    }
    
    .parent-category-item .category-link.active + .subcategory-list .submenu-arrow,
    .parent-category-item .subcategory-list.show + .submenu-arrow {
        transform: translateY(-50%) rotate(90deg);
    }
    
    /* Subcategory List Styles */
    .subcategory-list {
        list-style: none;
        padding-left: 0;
        margin: 0;
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s ease;
    }
    
    .subcategory-list.show {
        max-height: 500px;
        margin-top: 4px;
    }
    
    .subcategory-link {
        font-size: 0.9rem;
        padding: 8px 15px 8px 35px;
        color: #94a3b8;
    }
    
    .subcategory-link.active {
        background-color: rgba(99, 102, 241, 0.15);
        color: #818cf8;
    }
    
    /* Subcategories Bar Styles */
    .subcategories-bar {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 10px;
        background-color: #f8f9fa;
        padding: 10px 15px;
        border-radius: 4px;
    }
    
    .subcategories-label {
        font-weight: 500;
        color: #6c757d;
        margin-right: 5px;
    }
    
    .subcategory-pill {
        display: inline-block;
        padding: 5px 12px;
        background-color: #e9ecef;
        border-radius: 20px;
        color: #495057;
        font-size: 0.85rem;
        transition: all 0.2s;
        text-decoration: none;
    }
    
    .subcategory-pill:hover {
        background-color: #0d6efd;
        color: white;
    }
    
    /* Product Category Path Styles */
    .product-category-path {
        font-size: 0.8rem;
        color: #6c757d;
        margin-bottom: 5px;
    }
    
    .product-category-path a {
        color: #6c757d;
        text-decoration: none;
    }
    
    /* Add JavaScript to handle the toggling of subcategories */
    @if(!request()->ajax())
    document.addEventListener('DOMContentLoaded', function() {
        const parentCategories = document.querySelectorAll('.parent-category-item > .category-link');
        
        parentCategories.forEach(category => {
            category.addEventListener('click', function(event) {
                if (this.querySelector('.submenu-arrow')) {
                    event.preventDefault();
                    const submenu = this.nextElementSibling;
                    if (submenu && submenu.classList.contains('subcategory-list')) {
                        submenu.classList.toggle('show');
                        this.classList.toggle('active');
                    }
                }
            });
        });
    });
    @endif
    
    /* Filter Form Styles */
    .filter-form .form-label {
        color: #94a3b8;
        font-weight: 500;
        font-size: 0.9rem;
        margin-bottom: 8px;
    }
    
    .search-input-group {
        position: relative;
    }
    
    .search-icon {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #64748b;
        z-index: 2;
    }
    
    .filter-form .form-control {
        background-color: #0f172a;
        border: 1px solid #334155;
        color: #e2e8f0;
        border-radius: 8px;
        padding: 10px 10px 10px 35px;
        font-size: 0.95rem;
        transition: all 0.2s;
    }
    
    .filter-form .form-control:focus {
        background-color: #1e293b;
        border-color: #4f46e5;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2);
    }
    
    .filter-form .form-control::placeholder {
        color: #64748b;
    }
    
    .filter-actions {
        display: flex;
        gap: 10px;
        margin-top: 20px;
    }
    
    .btn-filter {
        background-color: #4f46e5;
        color: white;
        border: none;
        padding: 8px 16px;
        border-radius: 8px;
        font-weight: 500;
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }
    
    .btn-filter:hover {
        background-color: #4338ca;
        transform: translateY(-1px);
        color: white;
    }
    
    .btn-clear {
        background-color: transparent;
        color: #94a3b8;
        border: 1px solid #334155;
        padding: 8px 16px;
        border-radius: 8px;
        font-weight: 500;
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        transition: all 0.2s;
    }
    
    .btn-clear:hover {
        background-color: rgba(255, 255, 255, 0.05);
        color: #e2e8f0;
    }
</style>
@endpush 