@extends('layouts.app')

@section('title', 'Shopping Cart')

@section('content')
<div class="cart-page-wrapper">
    <div class="container py-5">
        <div class="row">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header text-white" style="background-color: #0d9488;">
                        <h5 class="mb-0"><i class="fas fa-shopping-cart me-2"></i>Your Shopping Cart</h5>
                    </div>
                    <div class="card-body">
                        @if(empty($products) || count($products) === 0)
                            <div class="text-center py-5">
                                <i class="fas fa-shopping-cart fa-4x text-muted mb-3"></i>
                                <h3>Your cart is empty</h3>
                                <p class="text-muted">Looks like you haven't added any products to your cart yet.</p>
                                <a href="{{ route('products.catalog') }}" class="btn btn-primary mt-3">
                                    <i class="fas fa-shopping-bag me-2"></i> Continue Shopping
                                </a>
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table align-middle">
                                    <thead>
                                        <tr>
                                            <th width="100">Product</th>
                                            <th>Name</th>
                                            <th>Price</th>
                                            <th width="120">Quantity</th>
                                            <th class="text-end">Subtotal</th>
                                            <th width="50"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($products as $item)
                                            @if(isset($item['product']) && $item['product'])
                                                <tr>
                                                    <td>
                                                        @if(isset($item['product']->primaryImage) && $item['product']->primaryImage)
                                                            <img src="{{ asset('storage/' . $item['product']->primaryImage->image) }}" 
                                                                 alt="{{ $item['product']->name }}" 
                                                                 class="img-thumbnail" 
                                                                 width="80">
                                                        @else
                                                            <div class="bg-light text-center" style="width: 80px; height: 80px">
                                                                <i class="fas fa-image text-muted" style="line-height: 80px"></i>
                                                            </div>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('products.show', $item['product']) }}" class="text-decoration-none">
                                                            {{ $item['product']->name }}
                                                        </a>
                                                    </td>
                                                    <td>${{ number_format($item['product']->price, 2) }}</td>
                                                    <td>
                                                        <form action="{{ route('cart.update') }}" method="POST" class="d-inline">
                                                            @csrf
                                                            <input type="hidden" name="product_id" value="{{ $item['product']->id }}">
                                                            <input type="number" name="quantity" value="{{ $item['quantity'] }}" 
                                                                   min="1" max="{{ $item['product']->stock }}" 
                                                                   class="form-control form-control-sm d-inline-block" 
                                                                   style="width: 80px"
                                                                   onchange="this.form.submit()">
                                                        </form>
                                                    </td>
                                                    <td class="text-end">${{ number_format($item['product']->price * $item['quantity'], 2) }}</td>
                                                    <td>
                                                        <form action="{{ route('cart.remove', $item['product']->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to remove {{ $item['product']->name }} from your cart?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                                    data-product-id="{{ $item['product']->id }}"
                                                                    data-product-name="{{ $item['product']->name }}">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="d-flex justify-content-between mt-4">
                                <a href="{{ route('products.catalog') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-arrow-left me-2"></i> Continue Shopping
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 mt-4 mt-lg-0">
                <div class="card border-0 shadow-sm">
                    <div class="card-header text-white" style="background-color: #0d9488;">
                        <h5 class="mb-0"><i class="fas fa-calculator me-2"></i>Order Summary</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between px-0">
                                <span>Subtotal</span>
                                <span>${{ number_format($total, 2) }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between px-0">
                                <span>Shipping</span>
                                <span>Free</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between px-0">
                                <span>Tax</span>
                                <span>${{ number_format($total * 0.07, 2) }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between px-0 fw-bold">
                                <span>Total</span>
                                <span>${{ number_format($total + ($total * 0.07), 2) }}</span>
                            </li>
                        </ul>
                        
                        @if(!empty($products))
                            <div class="mt-4">
                                <a href="{{ route('cart.checkout') }}" class="btn btn-success w-100 btn-lg">
                                    <i class="fas fa-credit-card me-2"></i> Proceed to Checkout
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="deleteModalLabel">Remove Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <i class="fas fa-exclamation-circle text-warning fa-3x mb-3"></i>
                <p class="mb-0">Are you sure you want to remove <strong id="productName"></strong> from your cart?</p>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Remove Item</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.cart-page-wrapper {
    min-height: calc(100vh - 300px);
    padding-bottom: 4rem;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize the modal
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    
    // Add click event listener to delete buttons
    document.querySelectorAll('.btn-outline-danger').forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.getAttribute('data-product-id');
            const productName = this.getAttribute('data-product-name');
            
            // Set the product name in the modal
            document.getElementById('productName').textContent = productName;
            
            // Set the form action
            const form = document.getElementById('deleteForm');
            form.action = `/cart/remove/${productId}`;
            
            // Show the modal
            deleteModal.show();
        });
    });
});
</script>
@endpush 