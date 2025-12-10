<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CheckoutController extends Controller
{
    public function buyNow(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $product = Product::findOrFail($request->product_id);
        
        // Check if product is in stock
        if ($product->stock < $request->quantity) {
            return back()->with('error', 'Sorry, the product is out of stock or the requested quantity is not available.');
        }

        // Create a cart session specifically for buy now
        $buyNowCart = [
            [
                'product' => $product,
                'quantity' => $request->quantity
            ]
        ];
        
        Session::put('buy_now_cart', $buyNowCart);
        
        return redirect()->route('cart.checkout', ['buy_now' => true]);
    }
} 