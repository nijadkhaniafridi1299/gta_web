<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Log;
use Stripe\Charge;
use Stripe\PaymentIntent;
use Stripe\Stripe;

class PaymentService
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    /**
     * Process Stripe card payment
     */
    public function processStripePayment(Order $order, string $paymentMethodId): bool
    {
        try {
            $intent = PaymentIntent::create([
                'amount' => (int) ($order->total * 100), // Convert to cents
                'currency' => strtolower($order->currency),
                'payment_method' => $paymentMethodId,
                'confirm' => true,
                'automatic_payment_methods' => [
                    'enabled' => true,
                    'allow_redirects' => 'never',
                ],
            ]);

            if ($intent->status === 'succeeded') {
                $order->update([
                    'payment_reference' => $intent->id,
                    'status' => 'completed',
                    'paid_at' => now(),
                ]);
                Log::info("Payment successful for order {$order->order_number}. Intent: {$intent->id}");
                return true;
            }

            Log::warning("Payment intent not succeeded. Status: {$intent->status}");
            return false;
        } catch (\Exception $e) {
            Log::error("Stripe payment error for order {$order->order_number}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Verify PayPal payment via webhook or return from PayPal
     */
    public function verifyPayPalPayment(string $paymentId): array
    {
        try {
            // For demo purposes, we'll simulate PayPal verification
            // In production, verify via PayPal API or webhooks
            Log::info("PayPal payment verified: {$paymentId}");
            
            return [
                'success' => true,
                'payment_id' => $paymentId,
                'status' => 'completed',
            ];
        } catch (\Exception $e) {
            Log::error("PayPal verification error: " . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Create a Stripe payment intent (for client-side processing)
     */
    public function createPaymentIntent(Order $order): array
    {
        try {
            $intent = PaymentIntent::create([
                'amount' => (int) ($order->total * 100),
                'currency' => strtolower($order->currency),
                'automatic_payment_methods' => [
                    'enabled' => true,
                ],
            ]);

            return [
                'success' => true,
                'client_secret' => $intent->client_secret,
                'intent_id' => $intent->id,
            ];
        } catch (\Exception $e) {
            Log::error("Error creating payment intent: " . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Refund a payment
     */
    public function refundPayment(Order $order): bool
    {
        try {
            if (!$order->payment_reference) {
                return false;
            }

            // For demo, just mark as refunded
            // In production, call Stripe/PayPal refund API
            Log::info("Refund processed for order {$order->order_number}");
            return true;
        } catch (\Exception $e) {
            Log::error("Refund error: " . $e->getMessage());
            return false;
        }
    }
}
