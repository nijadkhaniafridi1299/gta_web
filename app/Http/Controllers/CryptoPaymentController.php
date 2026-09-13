<?php

namespace App\Http\Controllers;

use App\Models\CryptoPayment;
use App\Models\Order;
use App\Services\CryptoPaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CryptoPaymentController extends Controller
{
    protected CryptoPaymentService $cryptoService;

    public function __construct(CryptoPaymentService $cryptoService)
    {
        $this->cryptoService = $cryptoService;
    }

    /**
     * Initiate crypto payment from checkout or existing order
     */
    public function pay(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'coin' => 'nullable|string',
        ]);

        $order = Order::findOrFail($request->order_id);

        if (auth()->check() && $order->user_id !== auth()->id()) {
            return back()->with('error', 'Unauthorized access to this order.');
        }

        $coin = strtoupper($request->input('coin', 'BTC'));
        $result = $this->cryptoService->createCharge($order, $coin);

        if (!$result['success']) {
            return back()->with('error', $result['error'] ?? 'Crypto payment initialization failed.');
        }

        session(['crypto_charge_id' => $result['charge_id']]);

        return redirect()->route('crypto.pending', ['charge_id' => $result['charge_id']]);
    }

    /**
     * Show pending cryptocurrency payment terminal
     */
    public function pending(string $charge_id)
    {
        $cryptoPayment = CryptoPayment::with(['order.items.product'])
            ->where('charge_id', $charge_id)
            ->firstOrFail();

        // If already confirmed, redirect straight to order
        if ($cryptoPayment->status === 'confirmed' && $cryptoPayment->order) {
            return redirect()->route('orders.show', $cryptoPayment->order)
                ->with('success', 'Crypto payment confirmed! Your game keys are ready.');
        }

        $supportedCoins = $this->cryptoService->getSupportedCoins();
        $coinRate = $this->cryptoService->getCoinRate($cryptoPayment->currency_code);
        $coinNetwork = $this->cryptoService->getCoinNetwork($cryptoPayment->currency_code);

        return view('crypto.pending', [
            'payment' => $cryptoPayment,
            'order' => $cryptoPayment->order,
            'supportedCoins' => $supportedCoins,
            'coinRate' => $coinRate,
            'coinNetwork' => $coinNetwork,
        ]);
    }

    /**
     * Verify payment status and redirect
     */
    public function verify(string $charge_id)
    {
        $result = $this->cryptoService->verifyCharge($charge_id);
        $cryptoPayment = CryptoPayment::where('charge_id', $charge_id)->firstOrFail();

        if ($result['is_confirmed'] ?? false) {
            session()->forget('crypto_charge_id');
            return redirect()->route('orders.show', $cryptoPayment->order)
                ->with('success', '🎉 Cryptocurrency payment verified successfully! Your digital keys have been delivered.');
        }

        return redirect()->route('crypto.pending', ['charge_id' => $charge_id])
            ->with('info', 'Blockchain confirmation in progress. Current status: ' . ucfirst($result['status'] ?? 'Pending'));
    }

    /**
     * Real-time AJAX payment status checker
     */
    public function checkStatus(Request $request)
    {
        $chargeId = $request->input('charge_id');
        $cryptoPayment = CryptoPayment::where('charge_id', $chargeId)->first();

        if (!$cryptoPayment) {
            return response()->json(['status' => 'error', 'message' => 'Payment not found'], 404);
        }

        $result = $this->cryptoService->verifyCharge($chargeId);

        return response()->json([
            'status' => $cryptoPayment->status,
            'confirmations' => $cryptoPayment->confirmations,
            'is_confirmed' => $cryptoPayment->status === 'confirmed',
            'redirect_url' => $cryptoPayment->order ? route('orders.show', $cryptoPayment->order) : null,
        ]);
    }

    /**
     * Simulate Instant Blockchain Confirmation (For testing & sandbox execution)
     */
    public function simulatePayment(string $charge_id, Request $request)
    {
        $result = $this->cryptoService->simulateConfirmation($charge_id);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'status' => 'confirmed',
                'redirect_url' => route('orders.show', $result['order']),
            ]);
        }

        return redirect()->route('orders.show', $result['order'])
            ->with('success', '⚡ Blockchain transaction simulated & confirmed! Game keys unlocked.');
    }

    /**
     * Handle webhook from Coinbase Commerce
     */
    public function webhook(Request $request)
    {
        $payload = $request->json()->all();
        Log::info('Crypto webhook received', ['payload' => $payload]);

        $success = $this->cryptoService->handleWebhook($payload);
        return response()->json(['received' => $success], $success ? 200 : 400);
    }
}
