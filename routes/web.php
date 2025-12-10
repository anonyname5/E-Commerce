<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\CheckoutController;

// Public routes
Route::get('/', [ProductController::class, 'catalog'])->name('home');
Route::get('/products', [ProductController::class, 'catalog'])->name('products.catalog');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
Route::get('/category/{category:slug}', [ProductController::class, 'byCategory'])->name('shop.category');

// Cart routes (protected by auth)
Route::middleware('auth')->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::get('/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
    Route::post('/place-order', [CartController::class, 'placeOrder'])->name('cart.place-order');
    
    // Checkout Routes
    Route::post('/checkout/buy-now', [CheckoutController::class, 'buyNow'])->name('checkout.buy-now');
});

// Authentication Routes
Route::middleware('guest')->group(function() {
    // Login and registration routes
    Route::get('/login', [\App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [\App\Http\Controllers\Auth\LoginController::class, 'login']);
    Route::get('/register', [\App\Http\Controllers\Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [\App\Http\Controllers\Auth\RegisterController::class, 'register']);
    Route::get('/password/reset', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/password/email', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/password/reset/{token}', [\App\Http\Controllers\Auth\ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/password/reset', [\App\Http\Controllers\Auth\ResetPasswordController::class, 'reset'])->name('password.update');
});

// Logout route (needs auth)
Route::post('/logout', [\App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Email verification routes
Route::middleware('auth')->group(function() {
    Route::get('/email/verify', [\App\Http\Controllers\Auth\VerificationController::class, 'show'])->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', [\App\Http\Controllers\Auth\VerificationController::class, 'verify'])->name('verification.verify');
    Route::post('/email/resend', [\App\Http\Controllers\Auth\VerificationController::class, 'resend'])->name('verification.resend');
});

// Customer routes (require authentication)
Route::middleware('auth')->group(function () {
    Route::get('/orders', [OrderController::class, 'myOrders'])->name('orders.my');
    Route::get('/orders/{order}/track', [OrderController::class, 'trackOrder'])->name('orders.track');
    
    Route::get('/payment/{order}/checkout', [PaymentController::class, 'checkout'])->name('payments.checkout');
    Route::post('/payment/{order}/process', [PaymentController::class, 'process'])->name('payments.process');
});

// Admin routes
Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])
    ->middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])
    ->name('admin.dashboard');

// Rest of admin routes
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])
    ->group(function () {
        // Dashboard is defined above independently
        
        Route::resource('products', ProductController::class);
        Route::delete('/products/images/{id}', [ProductController::class, 'deleteImage'])->name('products.delete-image');
        Route::patch('/products/images/{id}/primary', [ProductController::class, 'setPrimaryImage'])->name('products.set-primary-image');
        
        Route::resource('categories', CategoryController::class);
        
        Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
        Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.update-status');
        Route::post('/orders/{order}/add-note', [OrderController::class, 'addNote'])->name('orders.add-note');
        Route::get('/orders/{order}/invoice', [OrderController::class, 'generateInvoice'])->name('orders.invoice');
    });

// Test Upload Routes
Route::get('/test-upload', [App\Http\Controllers\TestUploadController::class, 'showForm'])->name('test.upload.form');
Route::post('/test-upload', [App\Http\Controllers\TestUploadController::class, 'handleUpload'])->name('test.upload.handle');
