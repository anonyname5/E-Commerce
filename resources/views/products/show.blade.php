@extends('layouts.app')

@section('title', $product->name)

@push('styles')
<style>
.product-container {
    max-width: 1200px;
    margin: 0 auto;
}

.product-image-container {
    background: #929292;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    border-radius: 1rem;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
}

.product-image-container:hover {
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
}

.carousel-item img {
    width: 100%;
    height: 450px;
    object-fit: contain;
}

.carousel-control-prev,
.carousel-control-next {
    width: 10%;
    opacity: 0.7;
}

.carousel-control-prev-icon,
.carousel-control-next-icon {
    background-color: rgba(0, 0, 0, 0.2);
    border-radius: 50%;
    padding: 1.5rem;
    width: 1.2rem;
    height: 1.2rem;
    background-size: 1.2rem;
    transition: all 0.2s ease;
}

.carousel-control-prev-icon:hover,
.carousel-control-next-icon:hover {
    background-color: rgba(0, 0, 0, 0.4);
}

.breadcrumb {
    background: transparent;
    padding: 0.5rem 0;
    margin-bottom: 2rem;
    font-size: 0.9rem;
}

.breadcrumb-item a {
    color: #0d9488;
    text-decoration: none;
    transition: color 0.2s ease;
}

.breadcrumb-item a:hover {
    color: #0f766e;
    text-decoration: underline;
}

.breadcrumb-item.active {
    color: #6b7280;
}

.product-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 1rem;
    color: #1e293b;
    line-height: 1.2;
}

.product-price {
    font-size: 2.5rem;
    font-weight: 700;
    color: #0d9488;
    margin-bottom: 1.5rem;
}

.product-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 0.75rem;
    margin: 1.5rem 0;
}

.badge {
    padding: 0.6rem 1.2rem;
    font-weight: 500;
    border-radius: 0.5rem;
    font-size: 0.9rem;
    letter-spacing: 0.02em;
    display: inline-flex;
    align-items: center;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.badge i {
    margin-right: 0.5rem;
}

.badge.bg-warning {
    background-color: #fbbf24 !important;
    color: #7c2d12;
}

.badge.bg-success {
    background-color: #10b981 !important;
}

.badge.bg-danger {
    background-color: #ef4444 !important;
}

.badge.bg-secondary {
    background-color: #6b7280 !important;
}

.product-description {
    margin: 2rem 0;
    padding: 2rem;
    background: #adadad;
    border-radius: 1rem;
    color: #334155;
    line-height: 1.8;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.03);
}

.product-description h5 {
    color: #0f172a;
    font-weight: 600;
    margin-bottom: 1rem;
    font-size: 1.2rem;
}

.product-description p {
    font-size: 1.05rem;
}

.product-actions {
    margin-top: 2rem;
    display: flex;
    gap: 1.5rem;
}

.btn {
    padding: 0.75rem 1.5rem;
    font-size: 1rem;
    border-radius: 0.5rem;
    font-weight: 600;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
}

.btn-add-cart {
    background-color: #2563eb;  /* Bright blue */
    color: white;
    border: none;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.btn-add-cart:hover {
    background-color: #1d4ed8;  /* Darker blue on hover */
}

.btn-buy-now {
    background-color: #dc2626;  /* Bright red */
    color: white;
    border: none;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.btn-buy-now:hover {
    background-color: #b91c1c;  /* Darker red on hover */
}

/* For related products buttons */
.btn-primary {
    background-color: #2563eb;  /* Match with add to cart */
    border-color: #2563eb;
    color: white;
}

.btn-primary:hover {
    background-color: #1d4ed8;
    border-color: #1d4ed8;
}

/* Related products section */
.related-products-section {
    margin-top: 4rem;
    padding-top: 2rem;
    border-top: 1px solid #e2e8f0;
}

.related-products-section h3 {
    font-size: 1.8rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 2rem;
}

.related-product-card {
    border: none;
    border-radius: 1rem;
    overflow: hidden;
    transition: all 0.3s ease;
    height: 100%;
    box-shadow: 0 10px 15px rgba(0, 0, 0, 0.05);
}

.related-product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
}

.card-img-top {
    height: 220px;
    object-fit: contain;
    padding: 1.5rem;
    background-color: #b6b6b6;
}

.card-body {
    padding: 1.5rem;
}

.card-title {
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 0.75rem;
    color: #1e293b;
}

.card-text {
    color: #64748b;
    font-size: 0.95rem;
    margin-bottom: 1.5rem;
}

.card-price {
    font-size: 1.3rem;
    font-weight: 700;
    color: #0d9488;
}

.btn-sm {
    padding: 0.5rem 1rem;
    font-size: 0.85rem;
}
</style>
@endpush

@section('content')
<div class="container product-container py-5">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('products.catalog') }}">Products</a></li>
            @if($product->category->parent)
                <li class="breadcrumb-item"><a href="{{ route('shop.category', $product->category->parent) }}">{{ $product->category->parent->name }}</a></li>
            @endif
            <li class="breadcrumb-item"><a href="{{ route('shop.category', $product->category) }}">{{ $product->category->name }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
        </ol>
    </nav>

    <div class="row g-5">
        <!-- Product Image -->
        <div class="col-lg-6">
            <div class="product-image-container">
                <div id="productCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        @if($product->images->count() > 0)
                            @foreach($product->images as $key => $image)
                                <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                                    <img src="{{ asset('storage/' . $image->image) }}" 
                                         class="d-block w-100" 
                                         alt="{{ $product->name }} - Image {{ $key + 1 }}">
                                </div>
                            @endforeach
                        @else
                            <div class="carousel-item active">
                                <div class="text-center p-5">
                                    <i class="fas fa-image fa-4x text-muted"></i>
                                </div>
                            </div>
                        @endif
                    </div>

                    @if($product->images->count() > 1)
                        <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Product Details -->
        <div class="col-lg-6">
            <div class="d-flex align-items-center gap-2 mb-3">
                @if($product->is_featured)
                    <div class="badge bg-warning">
                        <i class="fas fa-star"></i> Featured
                    </div>
                @endif
                
                @if($product->stock > 0)
                    <div class="badge bg-success">
                        <i class="fas fa-check-circle"></i> In Stock
                    </div>
                @else
                    <div class="badge bg-danger">
                        <i class="fas fa-times-circle"></i> Out of Stock
                    </div>
                @endif

                <div class="badge bg-secondary">
                    <i class="fas fa-tag"></i> {{ $product->category->name }}
                </div>
            </div>

            <h1 class="product-title">{{ $product->name }}</h1>
            <h2 class="product-price">${{ number_format($product->price, 2) }}</h2>   

            <div class="product-meta">
                <div class="meta-item">
                    <i class="fas fa-tag"></i> 
                    <span class="meta-label">Category:</span> 
                    @if($product->category->parent)
                        <a href="{{ route('shop.category', $product->category->parent) }}">{{ $product->category->parent->name }}</a> &gt; 
                    @endif
                    <a href="{{ route('shop.category', $product->category) }}">{{ $product->category->name }}</a>
                </div>
            </div>

            <div class="product-description">
                <h5>
                    <i class="fas fa-info-circle me-2"></i>Product Description
                </h5>
                <p class="mb-0">{{ $product->description }}</p>
            </div>
            
            @if($product->stock > 0)
                <div class="product-actions">
                    <form action="{{ route('cart.add') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit" class="btn btn-add-cart">
                            <i class="fas fa-cart-plus"></i>
                            <span>Add to Cart</span>
                        </button>
                    </form>

                    <form action="{{ route('checkout.buy-now') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit" class="btn btn-buy-now">
                            <i class="fas fa-bolt"></i>
                            <span>Buy Now</span>
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>

    @if($relatedProducts->count() > 0)
        <div class="related-products-section">
            <h3>Related Products</h3>
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
                @foreach($relatedProducts as $relatedProduct)
                    <div class="col">
                        <div class="card related-product-card h-100">
                            @if($relatedProduct->primaryImage)
                                <img src="{{ asset('storage/' . $relatedProduct->primaryImage->image) }}" 
                                     class="card-img-top" 
                                     alt="{{ $relatedProduct->name }}">
                            @else
                                <div class="bg-light text-center p-4">
                                    <i class="fas fa-image fa-3x text-muted"></i>
                                </div>
                            @endif
                            <div class="card-body">
                                <h5 class="card-title">{{ $relatedProduct->name }}</h5>
                                <p class="card-text">{{ Str::limit($relatedProduct->description, 80) }}</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="card-price">${{ number_format($relatedProduct->price, 2) }}</span>
                                    @if($relatedProduct->stock > 0)
                                        <form action="{{ route('cart.add') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $relatedProduct->id }}">
                                            <input type="hidden" name="quantity" value="1">
                                            <button type="submit" class="btn btn-primary btn-sm">
                                                <i class="fas fa-cart-plus"></i> Add
                                            </button>
                                        </form>
                                    @else
                                        <span class="badge bg-danger">Out of Stock</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection 