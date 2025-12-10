<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Get sales data for the last 7 days
        $salesData = Order::where('created_at', '>=', Carbon::now()->subDays(7))
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_amount) as total_sales')
            )
            ->groupBy('date')
            ->get();
        
        // Format for Chart.js
        $dates = [];
        $sales = [];
        
        foreach ($salesData as $data) {
            $dates[] = Carbon::parse($data->date)->format('M d');
            $sales[] = $data->total_sales;
        }
        
        // Top selling products
        $topProducts = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->select('products.name', DB::raw('SUM(order_items.quantity) as total_sold'))
            ->groupBy('products.id', 'products.name')
            ->orderBy('total_sold', 'desc')
            ->limit(5)
            ->get();
        
        // Order status distribution
        $orderStatuses = Order::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get();
        
        // Get recent orders
        $recentOrders = Order::with('user')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        
        // Summary stats
        $totalSales = Order::where('status', '!=', 'cancelled')->sum('total_amount');
        $totalOrders = Order::count();
        $totalCustomers = User::where('is_admin', false)->count();
        $totalProducts = Product::count();
        
        return view('admin.dashboard', compact(
            'dates',
            'sales',
            'topProducts',
            'orderStatuses',
            'totalSales',
            'totalOrders',
            'totalCustomers',
            'totalProducts',
            'recentOrders'
        ));
    }
} 