<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = Session::get('cart', []);
        $total = 0;
        $products = [];
        
        foreach ($cartItems as $id => $details) {
            $product = Product::with('primaryImage')->find($id);
            if ($product) {
                $products[] = [
                    'product' => $product,
                    'quantity' => $details['quantity']
                ];
                $total += $product->price * $details['quantity'];
            }
        }
        
        return view('cart.index', compact('products', 'total'));
    }
    
    public function add(Request $request)
    {
        $product = Product::findOrFail($request->product_id);
        $cart = Session::get('cart', []);
        
        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] += $request->quantity ?? 1;
        } else {
            $cart[$product->id] = [
                'quantity' => $request->quantity ?? 1
            ];
        }
        
        Session::put('cart', $cart);
        
        return redirect()->back()->with('success', 'Product added to cart!');
    }
    
    public function update(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $cart = Session::get('cart', []);
        $product = Product::findOrFail($request->product_id);
        
        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] = min($request->quantity, $product->stock);
            Session::put('cart', $cart);
        }
        
        return redirect()->route('cart.index')->with('success', 'Cart updated!');
    }
    
    public function remove($id)
    {
        $cart = Session::get('cart', []);
        
        if (isset($cart[$id])) {
            unset($cart[$id]);
            Session::put('cart', $cart);
        }
        
        return redirect()->route('cart.index')->with('success', 'Item removed from cart!');
    }
    
    public function checkout(Request $request)
    {
        $cart = [];
        $products = [];
        $isBuyNow = $request->query('buy_now', false);
        $total = 0;
        
        if ($isBuyNow) {
            $cartItems = Session::get('buy_now_cart', []);
            if (empty($cartItems)) {
                return redirect()->route('products.catalog')->with('error', 'No items found for checkout.');
            }
        } else {
            $cartItems = Session::get('cart', []);
            if (empty($cartItems)) {
                return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
            }
        }

        // Process cart items to include product details
        foreach ($cartItems as $id => $details) {
            $product = Product::find($id);
            if ($product) {
                $products[] = [
                    'product' => $product,
                    'quantity' => $details['quantity']
                ];
                $total += $product->price * $details['quantity'];
            }
        }

        return view('cart.checkout', compact('products', 'total', 'isBuyNow'));
    }
    
    public function placeOrder(Request $request)
    {
        $cartItems = Session::get('cart', []);
        
        if (empty($cartItems)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty');
        }
        
        $validated = $request->validate([
            'shipping_address' => 'required|string|max:255',
        ]);
        
        $totalAmount = 0;
        
        // Calculate total
        foreach ($cartItems as $id => $details) {
            $product = Product::find($id);
            if ($product) {
                $totalAmount += $product->price * $details['quantity'];
            }
        }
        
        // Create order
        $order = Order::create([
            'user_id' => auth()->id(),
            'total_amount' => $totalAmount,
            'status' => 'pending',
            'shipping_address' => $validated['shipping_address'],
            'tracking_number' => 'TRK-' . strtoupper(substr(uniqid(), -8)),
        ]);
        
        // Create order items
        foreach ($cartItems as $id => $details) {
            $product = Product::find($id);
            if ($product) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $details['quantity'],
                    'price' => $product->price,
                ]);
                
                // Update stock
                $product->decrement('stock', $details['quantity']);
            }
        }
        
        // Clear cart
        Session::forget('cart');
        
        return redirect()->route('payments.checkout', $order);
    }
}
