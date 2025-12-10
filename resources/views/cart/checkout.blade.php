@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('cart.index') }}">Cart</a></li>
                <li class="breadcrumb-item active" aria-current="page">Checkout</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header text-white" style="background-color: #0d9488;">
                <h5 class="mb-0"><i class="fas fa-shipping-fast me-2"></i>Shipping Information</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('cart.place-order') }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="first_name" class="form-label">First Name</label>
                            <input type="text" class="form-control @error('first_name') is-invalid @enderror" id="first_name" name="first_name" value="{{ auth()->user()->name ?? old('first_name') }}" required>
                            @error('first_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="last_name" class="form-label">Last Name</label>
                            <input type="text" class="form-control @error('last_name') is-invalid @enderror" id="last_name" name="last_name" value="{{ old('last_name') }}" required>
                            @error('last_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ auth()->user()->email ?? old('email') }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="tel" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}" required>
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="address" class="form-label">Street Address</label>
                        <input type="text" class="form-control @error('address') is-invalid @enderror" id="address" name="address" value="{{ old('address') }}" required>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="city" class="form-label">City</label>
                            <input type="text" class="form-control @error('city') is-invalid @enderror" id="city" name="city" value="{{ old('city') }}" required>
                            @error('city')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="state" class="form-label">State/Province</label>
                            <input type="text" class="form-control @error('state') is-invalid @enderror" id="state" name="state" value="{{ old('state') }}" required>
                            @error('state')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="postal_code" class="form-label">ZIP/Postal Code</label>
                            <input type="text" class="form-control @error('postal_code') is-invalid @enderror" id="postal_code" name="postal_code" value="{{ old('postal_code') }}" required>
                            @error('postal_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="country" class="form-label">Country</label>
                        <select class="form-select @error('country') is-invalid @enderror" id="country" name="country" required>
                            <option value="">Select Country</option>
                            <option value="USA" {{ old('country') == 'USA' ? 'selected' : '' }}>United States</option>
                            <option value="CAN" {{ old('country') == 'CAN' ? 'selected' : '' }}>Canada</option>
                            <option value="MEX" {{ old('country') == 'MEX' ? 'selected' : '' }}>Mexico</option>
                            <option value="GBR" {{ old('country') == 'GBR' ? 'selected' : '' }}>United Kingdom</option>
                            <option value="GBR" {{ old('country') == 'GBR' ? 'selected' : '' }}>Malaysia
                            </option>
                        </select>
                        @error('country')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <input type="hidden" name="shipping_address" id="shipping_address" value="">
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg" onclick="combineAddress()">
                            <i class="fas fa-credit-card me-2"></i> Place Order
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header text-white" style="background-color: #0d9488;">
                <h5 class="mb-0"><i class="fas fa-shopping-basket me-2"></i>Order Summary</h5>
            </div>
            <div class="card-body">
                <?php
                    $total = 0;
                    $cartItems = session('cart', []);
                    foreach ($cartItems as $id => $details) {
                        $product = App\Models\Product::find($id);
                        if ($product) {
                            $total += $product->price * $details['quantity'];
                        }
                    }
                ?>
                
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
            </div>
        </div>
        
        </div>
        
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h5 class="mb-3">Need Help?</h5>
                <p class="text-muted mb-0">
                    <i class="fas fa-phone-alt me-2"></i> Call us at: 1-800-123-4567<br>
                    <i class="fas fa-envelope me-2"></i> Email: support@ecommerce.com
                </p>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
    function combineAddress() {
        const address = document.getElementById('address').value;
        const city = document.getElementById('city').value;
        const state = document.getElementById('state').value;
        const postalCode = document.getElementById('postal_code').value;
        const country = document.getElementById('country').value;
        
        const fullAddress = `${address}, ${city}, ${state} ${postalCode}, ${country}`;
        document.getElementById('shipping_address').value = fullAddress;
    }
</script>
@endsection
@endsection 