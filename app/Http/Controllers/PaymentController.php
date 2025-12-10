<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\PaymentService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    protected $paymentService;
    
    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }
    
    public function checkout(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }
        
        // If already paid
        if ($order->payment && $order->payment->status === 'completed') {
            return redirect()->route('orders.track', $order)
                ->with('info', 'This order has already been paid');
        }
        
        return view('payments.checkout', compact('order'));
    }
    
    public function process(Request $request, Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }
        
        $validated = $request->validate([
            'payment_method' => 'required|in:credit_card,paypal,bank_transfer',
            // For a real payment processor, you'd need more fields here
        ]);
        
        $payment = $this->paymentService->processPayment($order, $validated['payment_method']);
        
        if ($payment->status === 'completed') {
            return redirect()->route('orders.track', $order)
                ->with('success', 'Payment successful! Your order is being processed.');
        } else {
            return redirect()->route('payments.checkout', $order)
                ->with('error', 'Payment failed. Please try again.');
        }
    }
}
