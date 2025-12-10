@extends('layouts.app')

@section('title', 'Track Order #' . $order->id)

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('orders.my') }}">My Orders</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Track Order #{{ $order->id }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Order Header with Summary -->
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex flex-wrap justify-content-between align-items-center">
                        <div class="mb-3 mb-md-0">
                            <h2 class="mb-1">Order #{{ $order->id }}</h2>
                            <p class="text-muted mb-0">Placed on {{ $order->created_at->format('F j, Y, g:i a') }}</p>
                        </div>
                        <div>
                            @if($order->status == 'pending')
                                <span class="badge bg-warning fs-6 px-3 py-2">Pending</span>
                            @elseif($order->status == 'processing')
                                <span class="badge bg-info fs-6 px-3 py-2">Processing</span>
                            @elseif($order->status == 'shipped')
                                <span class="badge bg-primary fs-6 px-3 py-2">Shipped</span>
                            @elseif($order->status == 'delivered')
                                <span class="badge bg-success fs-6 px-3 py-2">Delivered</span>
                            @elseif($order->status == 'cancelled')
                                <span class="badge bg-danger fs-6 px-3 py-2">Cancelled</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <!-- Order Summary - Moved to top right -->
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header text-white" style="background-color: #0d9488;">
                    <h5 class="mb-0"><i class="fas fa-calculator me-2"></i>Order Summary</h5>
                </div>
                <div class="card-body p-4">
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
        </div>
    </div>

    <div class="row">
        <!-- Left Column -->
        <div class="col-lg-8">
            <!-- Order Timeline -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header text-white" style="background-color: #0d9488;">
                    <h5 class="mb-0"><i class="fas fa-truck me-2"></i>Order Status Timeline</h5>
                </div>
                <div class="card-body p-4">
                    <div class="position-relative pb-3">
                        <div class="position-absolute" style="top: 0; bottom: 0; left: 20px; border-left: 2px dashed #dee2e6;"></div>
                        
                        <!-- Pending Stage -->
                        <div class="d-flex mb-4">
                            <div class="position-relative">
                                <div class="rounded-circle bg-{{ in_array($order->status, ['pending', 'processing', 'shipped', 'delivered']) ? 'success' : 'light' }} text-{{ in_array($order->status, ['pending', 'processing', 'shipped', 'delivered']) ? 'white' : 'muted' }} d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; z-index: 1;">
                                    <i class="fas fa-clipboard-check"></i>
                                </div>
                            </div>
                            <div class="ms-3">
                                <h5 class="mb-1">Order Placed</h5>
                                <p class="text-muted mb-0">{{ $order->created_at->format('F j, Y, g:i a') }}</p>
                                <p>Your order has been placed successfully.</p>
                            </div>
                        </div>
                        
                        <!-- Processing Stage -->
                        <div class="d-flex mb-4">
                            <div class="position-relative">
                                <div class="rounded-circle bg-{{ in_array($order->status, ['processing', 'shipped', 'delivered']) ? 'success' : 'light' }} text-{{ in_array($order->status, ['processing', 'shipped', 'delivered']) ? 'white' : 'muted' }} d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; z-index: 1;">
                                    <i class="fas fa-cog"></i>
                                </div>
                            </div>
                            <div class="ms-3">
                                <h5 class="mb-1">Processing</h5>
                                @if(in_array($order->status, ['processing', 'shipped', 'delivered']))
                                    <p class="text-muted mb-0">{{ $order->updated_at->format('F j, Y, g:i a') }}</p>
                                    <p>Your order is being processed and prepared for shipping.</p>
                                @else
                                    <p>We'll notify you when your order starts processing.</p>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Shipped Stage -->
                        <div class="d-flex mb-4">
                            <div class="position-relative">
                                <div class="rounded-circle bg-{{ in_array($order->status, ['shipped', 'delivered']) ? 'success' : 'light' }} text-{{ in_array($order->status, ['shipped', 'delivered']) ? 'white' : 'muted' }} d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; z-index: 1;">
                                    <i class="fas fa-shipping-fast"></i>
                                </div>
                            </div>
                            <div class="ms-3">
                                <h5 class="mb-1">Shipped</h5>
                                @if(in_array($order->status, ['shipped', 'delivered']))
                                    <p class="text-muted mb-0">{{ $order->updated_at->format('F j, Y, g:i a') }}</p>
                                    <p>
                                        Your order has been shipped!
                                        @if($order->tracking_number)
                                            <br>Tracking Number: <strong>{{ $order->tracking_number }}</strong>
                                        @endif
                                    </p>
                                @else
                                    <p>We'll notify you when your order ships.</p>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Delivered Stage -->
                        <div class="d-flex">
                            <div class="position-relative">
                                <div class="rounded-circle bg-{{ $order->status == 'delivered' ? 'success' : 'light' }} text-{{ $order->status == 'delivered' ? 'white' : 'muted' }} d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; z-index: 1;">
                                    <i class="fas fa-check"></i>
                                </div>
                            </div>
                            <div class="ms-3">
                                <h5 class="mb-1">Delivered</h5>
                                @if($order->status == 'delivered')
                                    <p class="text-muted mb-0">{{ $order->updated_at->format('F j, Y, g:i a') }}</p>
                                    <p>Your order has been delivered. Enjoy your purchase!</p>
                                @else
                                    <p>Your order will be delivered soon.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Order Items -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header text-white" style="background-color: #0d9488;">
                    <h5 class="mb-0"><i class="fas fa-box me-2"></i>Order Items</h5>
                </div>
                <div class="card-body p-4">
                    @foreach($order->items as $item)
                        <div class="row mb-4 {{ !$loop->last ? 'pb-4 border-bottom' : '' }}">
                            <div class="col-md-2 col-sm-3 mb-3 mb-sm-0">
                                @if($item->product->image)
                                    <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}" class="img-thumbnail">
                                @else
                                    <div class="bg-light text-center p-3">
                                        <i class="fas fa-image text-muted"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-10 col-sm-9">
                                <div class="d-flex justify-content-between align-items-start">
                                    <h5 class="mb-2">{{ $item->product->name }}</h5>
                                    <span class="text-primary fw-bold">${{ number_format($item->price, 2) }}</span>
                                </div>
                                <p class="text-muted mb-2">{{ Str::limit($item->product->description, 100) }}</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge bg-secondary">Qty: {{ $item->quantity }}</span>
                                    <span class="fw-bold">Subtotal: ${{ number_format($item->price * $item->quantity, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        
        <!-- Right Column -->
        <div class="col-lg-4">
            <!-- Shipping Info -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header text-white" style="background-color: #0d9488;">
                    <h5 class="mb-0"><i class="fas fa-map-marker-alt me-2"></i>Shipping Information</h5>
                </div>
                <div class="card-body p-4">
                    <address>
                        <strong>{{ auth()->user()->name }}</strong><br>
                        {{ $order->shipping_address }}
                    </address>
                    
                    @if($order->tracking_number)
                        <div class="mt-3 p-3 bg-light rounded">
                            <strong>Tracking Number:</strong><br>
                            <span class="text-primary">{{ $order->tracking_number }}</span>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Payment Info -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header text-white" style="background-color: #0d9488;">
                    <h5 class="mb-0"><i class="fas fa-credit-card me-2"></i>Payment Information</h5>
                </div>
                <div class="card-body p-4">
                    @if($order->payment)
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between px-0">
                                <span>Payment Method</span>
                                <span>
                                    @if($order->payment->payment_method == 'credit_card')
                                        <i class="far fa-credit-card me-1"></i> Credit Card
                                    @elseif($order->payment->payment_method == 'paypal')
                                        <i class="fab fa-paypal me-1"></i> PayPal
                                    @elseif($order->payment->payment_method == 'bank_transfer')
                                        <i class="fas fa-university me-1"></i> Bank Transfer
                                    @endif
                                </span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between px-0">
                                <span>Status</span>
                                <span>
                                    @if($order->payment->status == 'completed')
                                        <span class="badge bg-success">Completed</span>
                                    @elseif($order->payment->status == 'pending')
                                        <span class="badge bg-warning">Pending</span>
                                    @elseif($order->payment->status == 'failed')
                                        <span class="badge bg-danger">Failed</span>
                                    @endif
                                </span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between px-0">
                                <span>Transaction ID</span>
                                <span class="text-muted">{{ $order->payment->transaction_id }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between px-0">
                                <span>Date</span>
                                <span class="text-muted">{{ $order->payment->created_at->format('M d, Y') }}</span>
                            </li>
                        </ul>
                    @else
                        <div class="alert alert-warning mb-0">
                            <i class="fas fa-exclamation-triangle me-2"></i> No payment information available.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <!-- Footer Space -->
    <div class="my-5"></div>
</div>
@endsection 