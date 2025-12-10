<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Str;

class PaymentService
{
    public function processPayment(Order $order, $paymentMethod)
    {
        // This is a dummy payment processor
        // In a real app, you would integrate with a payment gateway like Stripe or PayPal
        
        $transactionId = 'TXN-' . Str::uuid();
        
        // Simulate payment processing
        $isSuccessful = rand(0, 10) > 2; // 80% success rate
        
        $payment = Payment::create([
            'order_id' => $order->id,
            'amount' => $order->total_amount,
            'payment_method' => $paymentMethod,
            'transaction_id' => $transactionId,
            'status' => $isSuccessful ? 'completed' : 'failed',
        ]);
        
        if ($isSuccessful) {
            $order->update(['status' => 'processing']);
        }
        
        return $payment;
    }
} 