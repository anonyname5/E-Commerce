<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('user');
        
        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        
        // Search by order ID or customer name
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                               ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }
        
        // Get orders with pagination
        $orders = $query->latest()->paginate(15);
        
        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load('items.product', 'payment');
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
            'tracking_number' => 'nullable|string',
        ]);

        $oldStatus = $order->status;
        $order->update($validated);
        
        // Create a record in order history
        $order->history()->create([
            'type' => 'status',
            'message' => 'Order status changed from ' . ucfirst($oldStatus) . ' to ' . ucfirst($order->status),
            'note' => $request->note ?? null,
            'user_id' => auth()->id(),
        ]);
        
        return redirect()->route('admin.orders.show', $order)
            ->with('success', 'Order status updated successfully');
    }
    
    /**
     * Add an administrative note to an order
     */
    public function addNote(Request $request, Order $order)
    {
        $validated = $request->validate([
            'note' => 'required|string|max:1000',
        ]);
        
        // Create a record in order history
        $order->history()->create([
            'type' => 'note',
            'message' => 'Admin note added',
            'note' => $validated['note'],
            'user_id' => auth()->id(),
        ]);
        
        return redirect()->route('admin.orders.show', $order)
            ->with('success', 'Note added successfully');
    }
    
    // Customer-facing order history
    public function myOrders()
    {
        $orders = auth()->user()->orders()->latest()->paginate(10);
        return view('orders.index', compact('orders'));
    }
    
    // Customer can view a single order
    public function trackOrder(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }
        
        $order->load('items.product', 'payment');
        return view('orders.track', compact('order'));
    }

    /**
     * Generate a PDF invoice for an order
     */
    public function generateInvoice(Order $order)
    {
        $order->load('items.product', 'user');
        
        // Set up PDF generation
        $data = [
            'order' => $order,
            'company' => [
                'name' => config('app.name'),
                'address' => '123 E-Commerce St.',
                'city' => 'New York',
                'state' => 'NY',
                'zip' => '10001',
                'email' => 'support@example.com'
            ]
        ];
        
        // Generate PDF options
        $pdfOptions = [
            'paper' => 'a4',
            'orientation' => 'portrait',
            'title' => "Invoice #{$order->id}",
            'author' => config('app.name'),
        ];
        
        // Use barryvdh/laravel-dompdf to generate the PDF
        $pdf = Pdf::loadView('admin.orders.invoice', $data)->setOptions($pdfOptions);
        return $pdf->download("invoice-{$order->id}.pdf");
        
        // Fallback approach commented out - no longer needed
        // return response($html)
        //     ->header('Content-Type', 'application/pdf')
        //     ->header('Content-Disposition', "attachment; filename=\"invoice-{$order->id}.pdf\"");
    }
}
