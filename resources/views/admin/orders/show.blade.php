@extends('layouts.admin')

@section('title', 'Order #' . $order->id)

@section('actions')
<div class="btn-group" role="group">
    <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i> Back to Orders
    </a>
    <a href="#order-status-section" class="btn btn-primary scroll-to">
        <i class="fas fa-sync-alt me-1"></i> Update Status
    </a>
    <a href="{{ route('admin.orders.invoice', $order) }}" class="btn btn-info">
        <i class="fas fa-file-invoice me-1"></i> Download Invoice
    </a>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8">
        <!-- Order Items -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="card-title mb-0">Order Items</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="80">Image</th>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                                <tr>
                                    <td>
                                        @if($item->product && $item->product->image)
                                            <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}" class="img-thumbnail" width="50">
                                        @else
                                            <div class="bg-light text-center" style="width: 50px; height: 50px">
                                                <i class="fas fa-image text-muted" style="line-height: 50px"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        @if($item->product)
                                            <a href="{{ route('admin.products.show', $item->product) }}" class="text-decoration-none">
                                                {{ $item->product->name }}
                                            </a>
                                        @else
                                            {{ $item->product_name ?? 'Product Unavailable' }}
                                        @endif
                                    </td>
                                    <td>${{ number_format($item->price, 2) }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td class="text-end">${{ number_format($item->price * $item->quantity, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <td colspan="4" class="text-end fw-bold">Subtotal:</td>
                                <td class="text-end">${{ number_format($order->subtotal, 2) }}</td>
                            </tr>
                            <tr>
                                <td colspan="4" class="text-end fw-bold">Shipping:</td>
                                <td class="text-end">${{ number_format($order->shipping_cost, 2) }}</td>
                            </tr>
                            <tr>
                                <td colspan="4" class="text-end fw-bold">Tax:</td>
                                <td class="text-end">${{ number_format($order->tax, 2) }}</td>
                            </tr>
                            <tr>
                                <td colspan="4" class="text-end fw-bold">Total:</td>
                                <td class="text-end fw-bold">${{ number_format($order->total_amount, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Order Timeline -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Order Timeline</h5>
                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addNoteModal">
                    <i class="fas fa-plus me-1"></i> Add Note
                </button>
            </div>
            <div class="card-body">
                <div class="timeline">
                    @foreach($order->history()->orderBy('created_at', 'desc')->get() as $history)
                        <div class="timeline-item">
                            <div class="timeline-marker 
                                @if($history->type == 'status') bg-primary
                                @elseif($history->type == 'payment') bg-success
                                @elseif($history->type == 'note') bg-info
                                @else bg-secondary @endif
                            "></div>
                            <div class="timeline-content">
                                <div class="d-flex justify-content-between">
                                    <h6 class="mb-1">{{ $history->message }}</h6>
                                    <small class="text-muted">{{ $history->created_at->format('M d, Y h:i A') }}</small>
                                </div>
                                @if($history->note)
                                    <p class="mb-0 text-muted">{{ $history->note }}</p>
                                @endif
                                @if($history->user)
                                    <small class="text-muted">By: {{ $history->user->name }}</small>
                                @endif
                            </div>
                        </div>
                    @endforeach
                    <div class="timeline-item">
                        <div class="timeline-marker bg-success"></div>
                        <div class="timeline-content">
                            <div class="d-flex justify-content-between">
                                <h6 class="mb-1">Order Created</h6>
                                <small class="text-muted">{{ $order->created_at->format('M d, Y h:i A') }}</small>
                            </div>
                            <small class="text-muted">By: {{ $order->user->name }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <!-- Order Status -->
        <div class="card border-0 shadow-sm mb-4" id="order-status-section">
            <div class="card-header bg-white py-3">
                <h5 class="card-title mb-0">Order Status</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.orders.update-status', $order) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                            <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                            <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="payment_status" class="form-label">Payment Status</label>
                        <select class="form-select @error('payment_status') is-invalid @enderror" id="payment_status" name="payment_status" required>
                            <option value="pending" {{ $order->payment_status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="failed" {{ $order->payment_status == 'failed' ? 'selected' : '' }}>Failed</option>
                        </select>
                        @error('payment_status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="tracking_number" class="form-label">Tracking Number</label>
                        <input type="text" class="form-control @error('tracking_number') is-invalid @enderror" id="tracking_number" name="tracking_number" value="{{ old('tracking_number', $order->tracking_number) }}">
                        @error('tracking_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="note" class="form-label">Status Note (Optional)</label>
                        <textarea class="form-control @error('note') is-invalid @enderror" id="note" name="note" rows="3">{{ old('note') }}</textarea>
                        <small class="text-muted">This note will be added to the order history</small>
                        @error('note')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Update Status
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Customer Information -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="card-title mb-0">Customer Information</h5>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="flex-shrink-0">
                        <div class="avatar-circle">
                            <span class="initials">{{ substr($order->user->name, 0, 1) }}</span>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="mb-0">{{ $order->user->name }}</h6>
                        <a href="mailto:{{ $order->user->email }}" class="text-decoration-none">{{ $order->user->email }}</a>
                    </div>
                    <div>
                        <a href="mailto:{{ $order->user->email }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-envelope me-1"></i> Contact
                        </a>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-6">
                        <div class="mb-1 text-muted">Customer Since</div>
                        <div>{{ $order->user->created_at->format('M d, Y') }}</div>
                    </div>
                    <div class="col-6">
                        <div class="mb-1 text-muted">Total Orders</div>
                        <div>{{ $order->user->orders->count() }}</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Shipping Information -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="card-title mb-0">Shipping Information</h5>
            </div>
            <div class="card-body">
                <address class="mb-0">
                    <strong>{{ $order->shipping_name }}</strong><br>
                    {{ $order->shipping_address }}<br>
                    {{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_zip }}<br>
                    {{ $order->shipping_country }}<br>
                    @if($order->shipping_phone)
                        <abbr title="Phone">P:</abbr> {{ $order->shipping_phone }}
                    @endif
                </address>
            </div>
        </div>
        
        <!-- Billing Information -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="card-title mb-0">Billing Information</h5>
            </div>
            <div class="card-body">
                <address class="mb-3">
                    <strong>{{ $order->billing_name }}</strong><br>
                    {{ $order->billing_address }}<br>
                    {{ $order->billing_city }}, {{ $order->billing_state }} {{ $order->billing_zip }}<br>
                    {{ $order->billing_country }}<br>
                    @if($order->billing_phone)
                        <abbr title="Phone">P:</abbr> {{ $order->billing_phone }}
                    @endif
                </address>
                
                <div class="mb-1 text-muted">Payment Method</div>
                <div>{{ ucfirst($order->payment_method) }}</div>
                
                @if($order->transaction_id)
                    <div class="mt-2">
                        <div class="mb-1 text-muted">Transaction ID</div>
                        <div>{{ $order->transaction_id }}</div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Add Note Modal -->
<div class="modal fade" id="addNoteModal" tabindex="-1" aria-labelledby="addNoteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.orders.add-note', $order) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addNoteModalLabel">Add Order Note</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="admin_note" class="form-label">Note</label>
                        <textarea class="form-control" id="admin_note" name="note" rows="4" required></textarea>
                        <small class="text-muted">This note will be visible only to administrators</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Note</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .timeline {
        position: relative;
        padding-left: 1.5rem;
    }
    
    .timeline:before {
        content: '';
        position: absolute;
        left: 0.5rem;
        top: 0;
        bottom: 0;
        width: 2px;
        background-color: #e9ecef;
    }
    
    .timeline-item {
        position: relative;
        padding-bottom: 1.5rem;
    }
    
    .timeline-marker {
        position: absolute;
        left: -1.5rem;
        width: 1rem;
        height: 1rem;
        border-radius: 50%;
    }
    
    .timeline-content {
        padding-left: 0.5rem;
    }
    
    .avatar-circle {
        width: 40px;
        height: 40px;
        background-color: #6c757d;
        text-align: center;
        border-radius: 50%;
        -webkit-border-radius: 50%;
        -moz-border-radius: 50%;
    }
    
    .initials {
        position: relative;
        top: 10px;
        font-size: 20px;
        line-height: 20px;
        color: #fff;
        font-weight: bold;
    }
    
    .timeline-item {
        position: relative;
        padding-left: 40px;
        margin-bottom: 20px;
    }
    .timeline-item:before {
        content: '';
        position: absolute;
        top: 0;
        left: 9px;
        height: 100%;
        width: 2px;
        background-color: #e0e0e0;
    }
    .timeline-item:last-child:before {
        height: 20px;
    }
    .timeline-icon {
        position: absolute;
        left: 0;
        top: 0;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #fff;
        border: 2px solid #ccc;
    }
    .timeline-content {
        padding: 10px 15px;
        border-radius: 4px;
        background-color: #f8f9fa;
        border-left: 3px solid #ccc;
    }
    .highlight-section {
        animation: highlight 2s ease-in-out;
    }
    
    @keyframes highlight {
        0% { box-shadow: 0 0 0 0 rgba(13, 110, 253, 0.2); }
        70% { box-shadow: 0 0 0 10px rgba(13, 110, 253, 0); }
        100% { box-shadow: 0 0 0 0 rgba(13, 110, 253, 0); }
    }
    .timeline-date {
        font-size: 0.85rem;
        color: #6c757d;
        font-weight: bold;
    }
</style>
@endsection

@section('scripts')
<script>
    // Smooth scroll to the order status section when the button is clicked
    document.querySelector('.scroll-to').addEventListener('click', function(e) {
        e.preventDefault();
        const targetId = this.getAttribute('href');
        const targetElement = document.querySelector(targetId);
        
        if (targetElement) {
            targetElement.scrollIntoView({ 
                behavior: 'smooth',
                block: 'start' 
            });
            
            // Add a highlight effect
            targetElement.classList.add('highlight-section');
            setTimeout(() => {
                targetElement.classList.remove('highlight-section');
            }, 2000);
        }
    });
</script>
@endsection 