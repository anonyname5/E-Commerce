@extends('layouts.app')

@section('title', 'Payment')

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('cart.index') }}">Cart</a></li>
                <li class="breadcrumb-item"><a href="{{ route('cart.checkout') }}">Checkout</a></li>
                <li class="breadcrumb-item active" aria-current="page">Payment</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header text-white" style="background-color: #0d9488;">
                <h5 class="mb-0"><i class="fas fa-credit-card me-2"></i>Payment Method</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('payments.process', $order) }}" method="POST" id="payment-form">
                    @csrf
                    
                    <div class="mb-4">
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="radio" name="payment_method" id="credit_card" value="credit_card" checked>
                            <label class="form-check-label" for="credit_card">
                                <i class="far fa-credit-card me-2"></i>Credit or Debit Card
                            </label>
                        </div>
                        
                        <div class="card-details ms-4 ps-2 border-start">
                            <div class="mb-3">
                                <label for="card_number" class="form-label">Card Number</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="card_number" placeholder="1234 5678 9012 3456" maxlength="19">
                                    <span class="input-group-text">
                                        <i class="fab fa-cc-visa me-1"></i>
                                        <i class="fab fa-cc-mastercard me-1"></i>
                                        <i class="fab fa-cc-amex"></i>
                                    </span>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="expiry_date" class="form-label">Expiry Date</label>
                                    <input type="text" class="form-control" id="expiry_date" placeholder="MM/YY">
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="cvv" class="form-label">CVV Code</label>
                                    <input type="text" class="form-control" id="cvv" placeholder="123" maxlength="4">
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="name_on_card" class="form-label">Name on Card</label>
                                <input type="text" class="form-control" id="name_on_card" placeholder="John Doe">
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="mb-4">
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="radio" name="payment_method" id="paypal" value="paypal">
                            <label class="form-check-label" for="paypal">
                                <i class="fab fa-paypal me-2"></i>PayPal
                            </label>
                        </div>
                        
                        <div class="paypal-details ms-4 ps-2 border-start d-none">
                            <p class="text-muted">You will be redirected to PayPal to complete your payment.</p>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="mb-4">
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="radio" name="payment_method" id="bank_transfer" value="bank_transfer">
                            <label class="form-check-label" for="bank_transfer">
                                <i class="fas fa-university me-2"></i>Bank Transfer
                            </label>
                        </div>
                        
                        <div class="bank-details ms-4 ps-2 border-start d-none">
                            <p class="text-muted">Please use the following details to make your bank transfer:</p>
                            <p>
                                <strong>Bank:</strong> ABC Bank<br>
                                <strong>Account Name:</strong> E-Commerce Store<br>
                                <strong>Account Number:</strong> 1234567890<br>
                                <strong>Sort Code:</strong> 12-34-56<br>
                                <strong>Reference:</strong> Order #{{ $order->id }}
                            </p>
                        </div>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="fas fa-lock me-2"></i> Complete Payment
                        </button>
                        <p class="text-center text-muted small mt-2">
                            <i class="fas fa-shield-alt me-1"></i> Your payment information is secure and encrypted
                        </p>
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
                <p><strong>Order #{{ $order->id }}</strong></p>
                
                <ul class="list-group list-group-flush mb-3">
                    @foreach($order->items as $item)
                        <li class="list-group-item px-0">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <span class="badge bg-primary me-2">{{ $item->quantity }}x</span>
                                    {{ $item->product->name }}
                                </div>
                                <span>${{ number_format($item->price * $item->quantity, 2) }}</span>
                            </div>
                        </li>
                    @endforeach
                </ul>
                
                <hr>
                
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between px-0">
                        <span>Subtotal</span>
                        <span>${{ number_format($order->total_amount, 2) }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between px-0">
                        <span>Shipping</span>
                        <span>Free</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between px-0">
                        <span>Tax</span>
                        <span>${{ number_format($order->total_amount * 0.07, 2) }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between px-0 fw-bold">
                        <span>Total</span>
                        <span>${{ number_format($order->total_amount + ($order->total_amount * 0.07), 2) }}</span>
                    </li>
                </ul>
            </div>
        </div>
        
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h5 class="mb-3">Shipping Address</h5>
                <address class="text-muted">
                    {{ $order->shipping_address }}
                </address>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
    document.querySelectorAll('input[name="payment_method"]').forEach(function(radio) {
        radio.addEventListener('change', function() {
            // Hide all detail sections
            document.querySelector('.card-details').classList.add('d-none');
            document.querySelector('.paypal-details').classList.add('d-none');
            document.querySelector('.bank-details').classList.add('d-none');
            
            // Show the selected section
            if (this.value === 'credit_card') {
                document.querySelector('.card-details').classList.remove('d-none');
            } else if (this.value === 'paypal') {
                document.querySelector('.paypal-details').classList.remove('d-none');
            } else if (this.value === 'bank_transfer') {
                document.querySelector('.bank-details').classList.remove('d-none');
            }
        });
    });
    
    // Format credit card number with spaces
    document.getElementById('card_number').addEventListener('input', function(e) {
        const value = e.target.value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
        const matches = value.match(/\d{4,16}/g);
        const match = matches && matches[0] || '';
        const parts = [];
        
        for (let i = 0; i < match.length; i += 4) {
            parts.push(match.substring(i, i + 4));
        }
        
        if (parts.length) {
            e.target.value = parts.join(' ');
        } else {
            e.target.value = value;
        }
    });
    
    // Format expiry date with slash
    document.getElementById('expiry_date').addEventListener('input', function(e) {
        const value = e.target.value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
        
        if (value.length > 2) {
            e.target.value = value.substring(0, 2) + '/' + value.substring(2, 4);
        } else {
            e.target.value = value;
        }
    });
</script>
@endsection
@endsection 