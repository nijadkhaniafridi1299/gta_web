<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    StoreController,
    CartController,
    CheckoutController,
    AuthController,
    OrderController,
    CryptoPaymentController
};
use Illuminate\Http\Request;

// Locale switcher
Route::get('lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'es'])) {
        session(['locale' => $locale]);
    }
    return redirect()->back();
})->name('lang.switch');

// Contact and help pages
Route::get('/contact', function () { return view('contact'); })->name('contact');
Route::post('/contact', function (Request $request) {
    $request->validate(['name' => 'required', 'email' => 'required|email', 'message' => 'required']);
    logger()->info('Contact form', $request->only('name', 'email', 'message'));
    return redirect()->route('contact')->with('success', 'Thanks — your message was sent.');
});

Route::get('/help', function () { return view('help'); })->name('help');

// Store & Products
Route::get('/', [StoreController::class, 'home'])->name('home');
Route::get('/products', [StoreController::class, 'index'])->name('products.index');
Route::get('/products/{product:slug}', [StoreController::class, 'show'])->name('products.show');
Route::get('/sitemap.xml', [StoreController::class, 'sitemap'])->name('sitemap');

// Shopping Cart
Route::get('/cart', [CartController::class, 'index'])->name('cart');
Route::post('/cart/{product}/add', [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/{product}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{product}', [CartController::class, 'remove'])->name('cart.remove');
Route::delete('/cart', [CartController::class, 'clear'])->name('cart.clear');

// Authentication
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'registerForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Orders & Checkout (Authenticated)
Route::middleware('auth')->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'form'])->name('checkout');
    Route::post('/checkout', [CheckoutController::class, 'pay'])->name('checkout.pay');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
});

// Cryptocurrency Payments
Route::post('/crypto/pay', [CryptoPaymentController::class, 'pay'])->name('crypto.pay');
Route::get('/crypto/pending/{charge_id}', [CryptoPaymentController::class, 'pending'])->name('crypto.pending');
Route::get('/crypto/verify/{charge_id}', [CryptoPaymentController::class, 'verify'])->name('crypto.verify');
Route::get('/crypto/status', [CryptoPaymentController::class, 'checkStatus'])->name('crypto.status');
Route::post('/crypto/simulate/{charge_id}', [CryptoPaymentController::class, 'simulatePayment'])->name('crypto.simulate');
Route::post('/crypto/webhook', [CryptoPaymentController::class, 'webhook'])->name('crypto.webhook');
