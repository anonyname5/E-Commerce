@extends('layouts.app')

@section('title', $product->name)

@section('styles')
<style>
    /* Carousel control styling */
    .carousel-control-prev,
    .carousel-control-next {
        width: 5%;
        background-color: rgba(0, 0, 0, 0.2);
        border-radius: 50%;
        width: 40px;
        height: 40px;
        top: 50%;
        transform: translateY(-50%);
    }
    
    .carousel-control-prev {
        left: 10px;
    }
    
    .carousel-control-next {
        right: 10px;
    }
    
    .carousel-control-prev-icon,
    .carousel-control-next-icon {
        background-color: #424242;
        border-radius: 50%;
        padding: 20px;
        background-size: 50%;
    }
</style>
@endsection

@section('content')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('products.catalog') }}">Products</a></li>
        <li class="breadcrumb-item"><a href="{{ route('products.catalog', ['category' => $product->category_id]) }}">{{ $product->category->name }}</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
    </ol>
</nav>

<div class="row">
    <!-- Product Image -->
    <div class="col-md-5 mb-4">
        <div class="card border-0 shadow-sm">
            @if($product->images->count() > 0)
                <div id="productImageCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-indicators">
                        @foreach($product->images as $index => $image)
                            <button type="button" data-bs-target="#productImageCarousel" data-bs-slide-to="{{ $index }}" class="{{ $index === 0 ? 'active' : '' }}" aria-current="{{ $index === 0 ? 'true' : 'false' }}" aria-label="Slide {{ $index + 1 }}"></button>
                        @endforeach
                    </div>
                    <div class="carousel-inner">
                        @foreach($product->images as $index => $image)
                            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                <img src="{{ asset('storage/' . $image->image) }}" class="d-block w-100 img-fluid" alt="{{ $product->name }}" style="height: 400px; object-fit: contain;">
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
                    <div class="d-flex flex-wrap justify-content-center mt-2 px-2 pb-2 gap-2">
                        @foreach($product->images as $index => $image)
                            <div data-bs-target="#productImageCarousel" data-bs-slide-to="{{ $index }}" class="{{ $index === 0 ? 'active' : '' }}" style="cursor: pointer;">
                                <img src="{{ asset('storage/' . $image->image) }}" alt="Thumbnail" class="img-thumbnail" style="width: 60px; height: 60px; object-fit: cover;">
                            </div>
                        @endforeach
                    </div>
                @endif
            @elseif($product->image)
                <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top img-fluid" alt="{{ $product->name }}">
            @else
                <div class="bg-light text-center py-5" style="height: 350px">
                    <i class="fas fa-image fa-5x text-muted mt-5"></i>
                </div>
            @endif
        </div>
    </div>
    
    <!-- Product Details -->
    <div class="col-md-7">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h1 class="h2 mb-2">{{ $product->name }}</h1>
                <p class="text-muted mb-3">
                    Category: <a href="{{ route('products.catalog', ['category' => $product->category_id]) }}">{{ $product->category->name }}</a>
                </p>
                
                <div class="mb-3">
                    <span class="h3 text-primary">${{ number_format($product->price, 2) }}</span>
                    
                    <span class="ms-3 badge {{ $product->stock > 0 ? 'bg-success' : 'bg-danger' }}">
                        {{ $product->stock > 0 ? 'In Stock' : 'Out of Stock' }}
                    </span>
                    
                    @if($product->stock > 0 && $product->stock < 5)
                        <span class="ms-2 text-danger">Only {{ $product->stock }} left!</span>
                    @endif
                </div>
                
                <hr>
                
                <div class="mb-4">
                    <h5>Description</h5>
                    <p>{{ $product->description }}</p>
                </div>
                
                @if($product->stock > 0)
                    <form action="{{ route('cart.add') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="quantity" class="form-label">Quantity</label>
                                <input type="number" class="form-control" id="quantity" name="quantity" min="1" max="{{ $product->stock }}" value="1">
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-cart-plus me-2"></i> Add to Cart
                        </button>
                    </form>
                @else
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i> This product is currently out of stock.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Related Products -->
@if($relatedProducts->count() > 0)
    <div class="mt-5">
        <h3 class="mb-4">Related Products</h3>
        
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
            @foreach($relatedProducts as $related)
                <div class="col">
                    <div class="card h-100 product-card border-0 shadow-sm">
                        @if($related->images->count() > 0)
                            <img src="{{ asset('storage/' . $related->images->where('is_primary', true)->first()->image ?? $related->images->first()->image) }}" class="card-img-top" alt="{{ $related->name }}" style="height: 150px; object-fit: cover;">
                        @elseif($related->image)
                            <img src="{{ asset('storage/' . $related->image) }}" class="card-img-top" alt="{{ $related->name }}" style="height: 150px; object-fit: cover;">
                        @else
                            <div class="bg-light text-center py-4">
                                <i class="fas fa-image fa-3x text-muted"></i>
                            </div>
                        @endif
                        
                        <div class="card-body">
                            <h5 class="card-title">{{ $related->name }}</h5>
                            <p class="card-text text-muted">{{ Str::limit($related->description, 50) }}</p>
                        </div>
                        
                        <div class="card-footer bg-transparent border-top-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="h5 mb-0 text-primary">${{ number_format($related->price, 2) }}</span>
                                
                                <a href="{{ route('products.show', $related) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye me-1"></i> View
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif
@endsection 