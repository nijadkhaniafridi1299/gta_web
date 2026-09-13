<?php

namespace App\Http\Controllers;

use App\Models\{Order, OrderItem, Product, GameKey};
use App\Services\PaymentService;
use App\Services\CryptoPaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    protected PaymentService $paymentService;
    protected CryptoPaymentService $cryptoService;

    public function __construct(PaymentService $paymentService, CryptoPaymentService $cryptoService)
    {
        $this->paymentService = $paymentService;
        $this->cryptoService = $cryptoService;
    }

    /**
     * Show checkout form
     */
    public function form()
    {
        $cart = session('cart', []);
        
        if (!count($cart)) {
            return redirect('/cart')->with('error', 'Your cart is empty.');
        }

        $supportedCoins = $this->cryptoService->getSupportedCoins();

        return view('checkout.index', [
            'cart' => $cart,
            'supportedCoins' => $supportedCoins,
        ]);
    }

    /**
     * Process payment and create order
     */
    public function pay(Request $r)
    {
        $data = $r->validate([
            'name' => 'required|max:100',
            'email' => 'required|email',
            'address' => 'nullable|max:255',
            'city' => 'nullable|max:100',
            'state' => 'nullable|max:100',
            'postal_code' => 'nullable|max:20',
            'payment_method' => 'nullable|in:card,paypal,crypto',
            'payment_method_id' => 'nullable|string', // Stripe payment method
            'paypal_order_id' => 'nullable|string', // PayPal order ID
            'crypto_coin' => 'nullable|string',
        ]);

        $cart = session('cart', []);
        if (!$cart) {
            return redirect('/cart')->with('error', 'Your cart is empty.');
        }

        // Determine payment method
        $paymentMethod = $data['payment_method'] ?? ($data['payment_method_id'] ? 'card' : ($data['paypal_order_id'] ? 'paypal' : 'crypto'));

        if (($paymentMethod === 'card' || $paymentMethod === 'paypal') && !empty($data['crypto_coin'])) {
            $paymentMethod = 'crypto';
        }

        if (!$paymentMethod) {
            return back()->with('error', 'No payment method selected.');
        }

        try {
            $order = DB::transaction(function () use ($data, $cart, $paymentMethod) {
                // Calculate totals
                $subtotal = array_sum(array_map(fn($x) => $x['price'] * $x['quantity'], $cart));
                
                // Create order
                $order = Order::create([
                    'user_id' => auth()->id(),
                    'order_number' => 'DG-' . strtoupper(Str::random(10)),
                    'customer_name' => $data['name'],
                    'customer_email' => $data['email'],
                    'subtotal' => $subtotal,
                    'discount' => 0,
                    'total' => $subtotal,
                    'currency' => 'USD',
                    'status' => 'pending', // Will be updated after payment
                    'payment_reference' => null, // Will be set after payment
                ]);

                // Reserve inventory (but don't allocate yet)
                foreach ($cart as $row) {
                    $product = Product::lockForUpdate()->findOrFail($row['id']);
                    
                    // Check if enough keys available
                    $availableKeys = GameKey::where('product_id', $product->id)
                        ->where('status', 'available')
                        ->count();
                    
                    if ($availableKeys < $row['quantity']) {
                        throw new \RuntimeException('Not enough stock for ' . $product->name);
                    }

                    $order->items()->create([
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'unit_price' => $product->price,
                        'quantity' => $row['quantity'],
                    ]);
                }

                return $order;
            });

            // If crypto payment selected
            if ($paymentMethod === 'crypto') {
                $coin = $data['crypto_coin'] ?? 'BTC';
                $cryptoResult = $this->cryptoService->createCharge($order, $coin);

                if (!$cryptoResult['success']) {
                    $order->update(['status' => 'failed']);
                    return back()->with('error', $cryptoResult['error'] ?? 'Could not initialize cryptocurrency payment.');
                }

                // Clear cart session
                session()->forget('cart');

                return redirect()->route('crypto.pending', ['charge_id' => $cryptoResult['charge_id']]);
            }

            // Process payment based on method
            $paymentSuccessful = false;

            if ($paymentMethod === 'card' && ($data['payment_method_id'] ?? null)) {
                $paymentSuccessful = $this->paymentService->processStripePayment(
                    $order,
                    $data['payment_method_id']
                );
            } elseif ($paymentMethod === 'paypal' && ($data['paypal_order_id'] ?? null)) {
                $verifyResult = $this->paymentService->verifyPayPalPayment($data['paypal_order_id']);
                if ($verifyResult['success']) {
                    $order->update([
                        'payment_reference' => $data['paypal_order_id'],
                        'status' => 'completed',
                        'paid_at' => now(),
                    ]);
                    $paymentSuccessful = true;
                }
            }

            if (!$paymentSuccessful) {
                $order->update(['status' => 'failed']);
                return back()->with('error', 'Payment processing failed. Please try again.');
            }

            // Allocate digital keys to the order
            DB::transaction(function () use ($order, $cart) {
                foreach ($cart as $row) {
                    $product = Product::findOrFail($row['id']);
                    
                    for ($i = 0; $i < $row['quantity']; $i++) {
                        $key = GameKey::where('product_id', $product->id)
                            ->where('status', 'available')
                            ->lockForUpdate()
                            ->first();
                        
                        if (!$key) {
                            $key = GameKey::create([
                                'product_id' => $product->id,
                                'key' => 'GTA6-' . strtoupper(Str::random(4)) . '-' . strtoupper(Str::random(4)) . '-' . strtoupper(Str::random(4)),
                                'status' => 'available',
                            ]);
                        }

                        $key->update([
                            'status' => 'sold',
                            'order_id' => $order->id,
                            'sold_at' => now(),
                        ]);
                    }
                }

                $order->update(['status' => 'completed']);
            });

            // Clear session
            session()->forget('cart');

            return redirect()->route('orders.show', $order)
                ->with('success', 'Payment successful! Your digital keys are ready to download.');

        } catch (\RuntimeException $e) {
            // Handle inventory errors
            if (isset($order)) {
                $order->update(['status' => 'failed']);
            }
            return back()->with('error', 'Error: ' . $e->getMessage());
        } catch (\Exception $e) {
            // Handle other errors
            if (isset($order)) {
                $order->update(['status' => 'failed']);
            }
            logger()->error('Checkout error', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
            ]);
            return back()->with('error', 'An error occurred during checkout. Please try again.');
        }
    }
}
