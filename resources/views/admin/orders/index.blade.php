@extends('layouts.admin')

@section('title', 'Orders')

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
    }
    
    [data-bs-theme="dark"] .table thead th {
        background-color: #212529 !important;
        color: #ffffff !important;
    }

    [data-bs-theme="dark"] .table tbody td {
        border-color: var(--border-color);
    }
    
    [data-bs-theme="dark"] .table-hover tbody tr:hover {
        background-color: rgba(255, 255, 255, 0.05);
    }
    
    .table-responsive {
        overflow-x: auto;
        border-radius: 8px;
    }
    
    /* Status badges */
    .badge {
        font-weight: 600;
        padding: 6px 10px;
        border-radius: 6px;
    }
    
    /* Filter form */
    .filter-controls {
        gap: 10px;
    }
    
    .filter-controls .form-select,
    .filter-controls .form-control {
        border-radius: 8px;
    }
    
    /* Card header adjustments */
    [data-bs-theme="dark"] .card .text-muted {
        color: rgba(255, 255, 255, 0.6) !important;
    }
    
    @media (max-width: 768px) {
        .filter-controls {
            flex-direction: column;
            align-items: stretch !important;
        }
        
        .filter-controls > * {
            margin-bottom: 10px;
            width: 100%;
        }
    }
</style>
@endsection

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header py-3">
        <div class="row w-100 align-items-center">
            <div class="col-md-6 mb-3 mb-md-0">
                <h5 class="mb-0 d-flex align-items-center">
                    <i class="fas fa-shopping-bag text-primary me-2"></i>
                    Orders Management
                </h5>
                <p class="text-muted mb-0 mt-1 small">
                    <i class="fas fa-info-circle me-1"></i>
                    Manage and track all customer orders
                </p>
            </div>
            <div class="col-md-6">
                <form action="{{ route('admin.orders.index') }}" method="GET" class="d-flex filter-controls">
                    <select name="status" class="form-select" id="status-filter">
                        <option value="">All Statuses</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                        <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Shipped</option>
                        <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Search orders..." value="{{ request('search') }}">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Customer</th>
                        <th>Date</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Payment</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td>
                                <a href="{{ route('admin.orders.show', $order) }}" class="fw-bold text-decoration-none">
                                    #{{ $order->id }}
                                </a>
                            </td>
                            <td>{{ $order->user->name }}</td>
                            <td>{{ $order->created_at->format('M d, Y') }}</td>
                            <td>${{ number_format($order->total_amount, 2) }}</td>
                            <td>
                                @if($order->status == 'pending')
                                    <span class="badge bg-warning">Pending</span>
                                @elseif($order->status == 'processing')
                                    <span class="badge bg-info">Processing</span>
                                @elseif($order->status == 'shipped')
                                    <span class="badge bg-primary">Shipped</span>
                                @elseif($order->status == 'delivered')
                                    <span class="badge bg-success">Delivered</span>
                                @elseif($order->status == 'cancelled')
                                    <span class="badge bg-danger">Cancelled</span>
                                @endif
                            </td>
                            <td>
                                @if($order->payment_status == 'paid')
                                    <span class="badge bg-success">Paid</span>
                                @elseif($order->payment_status == 'pending')
                                    <span class="badge bg-warning">Pending</span>
                                @elseif($order->payment_status == 'failed')
                                    <span class="badge bg-danger">Failed</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" 
                                           data-bs-toggle="modal" 
                                           data-bs-target="#updateStatusModal{{ $order->id }}">
                                        <i class="fas fa-sync-alt"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#invoiceModal{{ $order->id }}">
                                        <i class="fas fa-file-invoice"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <div class="d-flex flex-column align-items-center">
                                    <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                                    <h5>No orders found</h5>
                                    @if(request('search') || request('status'))
                                        <p class="text-muted">Try changing your search criteria</p>
                                        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-primary mt-2">
                                            <i class="fas fa-redo me-1"></i> Clear Filters
                                        </a>
                                    @else
                                        <p class="text-muted">Orders will appear here when customers make purchases</p>
                                    @endif
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
    {{ $orders->appends(request()->query())->links() }}
</div>

<!-- Invoice Modals -->
@foreach($orders as $order)
    <div class="modal fade" id="invoiceModal{{ $order->id }}" tabindex="-1" aria-labelledby="invoiceModalLabel{{ $order->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="invoiceModalLabel{{ $order->id }}">Invoice #{{ $order->id }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex justify-content-between mb-4">
                        <div>
                            <h4 class="mb-1">INVOICE</h4>
                            <div>Order #{{ $order->id }}</div>
                            <div>Date: {{ $order->created_at->format('M d, Y') }}</div>
                        </div>
                        <div class="text-end">
                            <h5 class="mb-1">{{ config('app.name') }}</h5>
                            <div>123 E-Commerce St.</div>
                            <div>New York, NY 10001</div>
                            <div>support@example.com</div>
                        </div>
                    </div>
                    
                    <!-- Customer Info -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="fw-bold">Bill To:</h6>
                            <div>{{ $order->billing_name ?? $order->user->name }}</div>
                            <div>{{ $order->billing_address ?? 'No address provided' }}</div>
                            @if(isset($order->billing_city))
                                <div>{{ $order->billing_city }}, {{ $order->billing_state }} {{ $order->billing_zip }}</div>
                                <div>{{ $order->billing_country }}</div>
                            @endif
                            <div>{{ $order->user->email }}</div>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-bold">Ship To:</h6>
                            <div>{{ $order->shipping_name ?? $order->user->name }}</div>
                            <div>{{ $order->shipping_address ?? 'No address provided' }}</div>
                            @if(isset($order->shipping_city))
                                <div>{{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_zip }}</div>
                                <div>{{ $order->shipping_country }}</div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Order Items -->
                    <h6 class="fw-bold mb-3">Order Items</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                    <tr>
                                        <td>{{ $item->product ? $item->product->name : 'Product Unavailable' }}</td>
                                        <td>${{ number_format($item->price, 2) }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td class="text-end">${{ number_format($item->price * $item->quantity, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end fw-bold">Subtotal:</td>
                                    <td class="text-end">${{ number_format($order->subtotal, 2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end fw-bold">Shipping:</td>
                                    <td class="text-end">${{ number_format($order->shipping_cost, 2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end fw-bold">Tax:</td>
                                    <td class="text-end">${{ number_format($order->tax, 2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end fw-bold">Total:</td>
                                    <td class="text-end fw-bold">${{ number_format($order->total_amount, 2) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="fw-bold">Payment Information:</h6>
                            <div>Method: {{ ucfirst($order->payment_method) }}</div>
                            <div>Status: {{ ucfirst($order->payment_status) }}</div>
                            @if($order->transaction_id)
                                <div>Transaction ID: {{ $order->transaction_id }}</div>
                            @endif
                        </div>
                        <div class="col-md-6 text-end">
                            <h6 class="fw-bold">Thank You for Your Business!</h6>
                            <div class="text-muted">If you have any questions about this invoice, please contact our customer support.</div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <a href="{{ route('admin.orders.invoice', $order) }}" class="btn btn-primary">
                        <i class="fas fa-download me-1"></i> Download PDF
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Update Status Modal -->
    <div class="modal fade" id="updateStatusModal{{ $order->id }}" tabindex="-1" aria-labelledby="updateStatusModalLabel{{ $order->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('admin.orders.update-status', $order) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="modal-header">
                        <h5 class="modal-title" id="updateStatusModalLabel{{ $order->id }}">Update Order #{{ $order->id }} Status</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="status{{ $order->id }}" class="form-label">Status</label>
                            <select class="form-select" id="status{{ $order->id }}" name="status" required>
                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                                <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                                <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="tracking_number{{ $order->id }}" class="form-label">Tracking Number (optional)</label>
                            <input type="text" class="form-control" id="tracking_number{{ $order->id }}" name="tracking_number" value="{{ $order->tracking_number }}">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Status</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-submit the form when status filter changes
        const statusFilter = document.getElementById('status-filter');
        if (statusFilter) {
            statusFilter.addEventListener('change', function() {
                this.form.submit();
            });
        }
    });
</script>
@endsection 