@extends('layouts.admin')

@section('title', 'Dashboard')

@section('styles')
<style>
    /* Dashboard-specific styles */
    .dashboard-card {
        transition: all 0.3s ease;
    }
    
    /* Dashboard table styles */
    .dashboard-table thead th {
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
    
    [data-bs-theme="dark"] .dashboard-table thead th {
        background-color: #212529 !important;
    }
    
    [data-bs-theme="dark"] .dashboard-table tbody td {
        border-color: var(--border-color);
    }
    
    /* Stats card icon containers */
    .stats-icon-container {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }
    
    [data-bs-theme="dark"] .bg-opacity-10 {
        opacity: 0.2;
    }
    
    /* Chart customization for dark mode */
    [data-bs-theme="dark"] canvas {
        background-color: transparent !important;
    }
</style>
@endsection

@section('content')
<div class="row">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm mb-4 dashboard-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon-container bg-primary bg-opacity-10 me-3">
                        <i class="fas fa-shopping-bag text-primary fa-2x"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">Total Orders</h6>
                        <h3 class="mb-0">{{ $totalOrders }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card border-0 shadow-sm mb-4 dashboard-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon-container bg-success bg-opacity-10 me-3">
                        <i class="fas fa-dollar-sign text-success fa-2x"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">Total Sales</h6>
                        <h3 class="mb-0">${{ number_format($totalSales, 2) }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card border-0 shadow-sm mb-4 dashboard-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon-container bg-info bg-opacity-10 me-3">
                        <i class="fas fa-users text-info fa-2x"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">Total Customers</h6>
                        <h3 class="mb-0">{{ $totalCustomers }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card border-0 shadow-sm mb-4 dashboard-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon-container bg-warning bg-opacity-10 me-3">
                        <i class="fas fa-box text-warning fa-2x"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">Total Products</h6>
                        <h3 class="mb-0">{{ $totalProducts }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm mb-4 dashboard-card">
            <div class="card-header">
                <h5 class="mb-0">Sales Overview</h5>
            </div>
            <div class="card-body">
                <canvas id="salesChart" height="300"></canvas>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card border-0 shadow-sm mb-4 dashboard-card">
            <div class="card-header">
                <h5 class="mb-0">Order Status</h5>
            </div>
            <div class="card-body">
                <canvas id="orderStatusChart" height="300"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm mb-4 dashboard-card">
            <div class="card-header">
                <h5 class="mb-0">Top Selling Products</h5>
            </div>
            <div class="card-body">
                <canvas id="topProductsChart" height="300"></canvas>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card border-0 shadow-sm mb-4 dashboard-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Recent Orders</h5>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover dashboard-table mb-0">
                        <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Customer</th>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentOrders as $order)
                                <tr>
                                    <td>
                                        <a href="{{ route('admin.orders.show', $order) }}" class="text-decoration-none">
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
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        try {
            // Sales Chart
            const salesCtx = document.getElementById('salesChart');
            if (salesCtx) {
                const salesChart = new Chart(salesCtx.getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: @json($dates),
                        datasets: [{
                            label: 'Sales ($)',
                            data: @json($sales),
                            borderColor: 'rgba(75, 192, 192, 1)',
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            tension: 0.1,
                            fill: true
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'top',
                            },
                            tooltip: {
                                enabled: true
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
                console.log('Sales chart initialized successfully');
            } else {
                console.error('Sales chart canvas not found');
            }
            
            // Order Status Chart
            const statusCtx = document.getElementById('orderStatusChart');
            if (statusCtx) {
                const statusChart = new Chart(statusCtx.getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        labels: @json($orderStatuses->pluck('status')),
                        datasets: [{
                            data: @json($orderStatuses->pluck('total')),
                            backgroundColor: [
                                'rgba(255, 193, 7, 0.8)',  // Pending (warning)
                                'rgba(13, 202, 240, 0.8)', // Processing (info)
                                'rgba(13, 110, 253, 0.8)', // Shipped (primary)
                                'rgba(25, 135, 84, 0.8)',  // Delivered (success)
                                'rgba(220, 53, 69, 0.8)'   // Cancelled (danger)
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'right',
                            }
                        }
                    }
                });
                console.log('Order status chart initialized successfully');
            } else {
                console.error('Order status chart canvas not found');
            }
            
            // Top Products Chart
            const productsCtx = document.getElementById('topProductsChart');
            if (productsCtx) {
                const productsChart = new Chart(productsCtx.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: @json($topProducts->pluck('name')),
                        datasets: [{
                            label: 'Units Sold',
                            data: @json($topProducts->pluck('total_sold')),
                            backgroundColor: 'rgba(13, 110, 253, 0.7)',
                            borderColor: 'rgba(13, 110, 253, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    precision: 0
                                }
                            }
                        }
                    }
                });
                console.log('Top products chart initialized successfully');
            } else {
                console.error('Top products chart canvas not found');
            }
        } catch (error) {
            console.error('Error initializing charts:', error);
        }
    });
</script>
@endsection 