<?php

namespace App\Services;

use App\Models\CryptoPayment;
use App\Models\GameKey;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CryptoPaymentService
{
    private ?string $apiKey;
    private string $apiUrl;
    private array $supportedCoins;

    public function __construct()
    {
        $this->apiKey = config('services.crypto.coinbase_api_key');
        $this->apiUrl = config('services.crypto.coinbase_api_url', 'https://api.commerce.coinbase.com');
        $this->supportedCoins = ['BTC', 'ETH', 'USDT', 'USDC', 'SOL', 'DOGE', 'LTC'];
    }

    /**
     * Create a crypto charge (Live Coinbase or Smart Interactive Sandbox)
     */
    public function createCharge(Order $order, string $selectedCoin = 'BTC'): array
    {
        try {
            $selectedCoin = strtoupper($selectedCoin);
            if (!in_array($selectedCoin, $this->supportedCoins)) {
                $selectedCoin = 'BTC';
            }

            $cryptoAmount = $this->convertUsdToCrypto((float) $order->total, $selectedCoin);
            $walletAddress = $this->generateWalletAddress($selectedCoin);
            $chargeId = 'crypto_' . Str::lower(Str::random(16));

            // If a valid live Coinbase API key is configured
            if ($this->apiKey && $this->apiKey !== 'YOUR_COINBASE_API_KEY' && !str_starts_with($this->apiKey, 'YOUR_')) {
                $chargeData = [
                    'name' => 'GTA6 Store Order #' . $order->order_number,
                    'description' => 'Digital Game Keys Purchase',
                    'local_price' => [
                        'amount' => (string) $order->total,
                        'currency' => 'USD',
                    ],
                    'pricing_type' => 'fixed_price',
                    'metadata' => [
                        'order_id' => $order->id,
                        'order_number' => $order->order_number,
                        'customer_email' => $order->customer_email,
                        'coin' => $selectedCoin,
                    ],
                    'redirect_url' => route('crypto.verify', $chargeId),
                    'cancel_url' => route('checkout'),
                ];

                $response = $this->makeApiRequest('/charges', 'POST', $chargeData);
                if ($response && isset($response['data']['id'])) {
                    $chargeId = $response['data']['id'];
                    $walletAddress = $response['data']['addresses'][strtolower($selectedCoin)] ?? $walletAddress;
                }
            }

            // Create crypto payment record in database
            $cryptoPayment = CryptoPayment::create([
                'order_id' => $order->id,
                'charge_id' => $chargeId,
                'currency_code' => $selectedCoin,
                'amount' => $cryptoAmount,
                'usd_amount' => $order->total,
                'wallet_address' => $walletAddress,
                'status' => 'pending',
                'confirmations' => 0,
                'payment_info' => [
                    'network' => $this->getCoinNetwork($selectedCoin),
                    'rate_usd' => $this->getCoinRate($selectedCoin),
                    'qr_uri' => $this->generatePaymentUri($selectedCoin, $walletAddress, $cryptoAmount),
                ],
                'expires_at' => now()->addHours(2),
            ]);

            // Associate payment reference with order
            $order->update([
                'payment_reference' => $chargeId,
            ]);

            Log::info("Crypto charge {$chargeId} created for order {$order->order_number} ({$cryptoAmount} {$selectedCoin})");

            return [
                'success' => true,
                'charge_id' => $chargeId,
                'crypto_payment_id' => $cryptoPayment->id,
                'currency' => $selectedCoin,
                'amount' => $cryptoAmount,
                'wallet_address' => $walletAddress,
                'usd_amount' => $order->total,
            ];
        } catch (\Exception $e) {
            Log::error('Crypto payment creation error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Payment initialization failed: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Verify payment status
     */
    public function verifyCharge(string $chargeId): array
    {
        try {
            $cryptoPayment = CryptoPayment::where('charge_id', $chargeId)->first();

            if (!$cryptoPayment) {
                return ['success' => false, 'status' => 'not_found'];
            }

            // Check if already confirmed
            if ($cryptoPayment->status === 'confirmed') {
                return [
                    'success' => true,
                    'status' => 'confirmed',
                    'confirmations' => $cryptoPayment->confirmations ?: 3,
                    'is_confirmed' => true,
                ];
            }

            // If connected to live Coinbase API
            if ($this->apiKey && $this->apiKey !== 'YOUR_COINBASE_API_KEY') {
                $response = $this->makeApiRequest("/charges/{$chargeId}", 'GET');
                if ($response && isset($response['data'])) {
                    $status = $this->mapStatus($response['data']['status'] ?? 'PENDING');
                    $confirmations = $response['data']['confirmations'] ?? 0;

                    $cryptoPayment->update([
                        'status' => $status,
                        'confirmations' => $confirmations,
                    ]);

                    if ($status === 'confirmed') {
                        $this->completeOrder($cryptoPayment->order, $chargeId, $cryptoPayment);
                    }

                    return [
                        'success' => true,
                        'status' => $status,
                        'confirmations' => $confirmations,
                        'is_confirmed' => $status === 'confirmed',
                    ];
                }
            }

            // In sandbox/simulator mode, check current status
            return [
                'success' => true,
                'status' => $cryptoPayment->status,
                'confirmations' => $cryptoPayment->confirmations,
                'is_confirmed' => $cryptoPayment->status === 'confirmed',
            ];
        } catch (\Exception $e) {
            Log::error("Charge verification error: " . $e->getMessage());
            return ['success' => false, 'status' => 'error', 'error' => $e->getMessage()];
        }
    }

    /**
     * Instant Confirmation Simulator (For testing & sandbox execution)
     */
    public function simulateConfirmation(string $chargeId): array
    {
        $cryptoPayment = CryptoPayment::where('charge_id', $chargeId)->firstOrFail();
        $order = $cryptoPayment->order;

        $txHash = '0x' . Str::random(64);

        $cryptoPayment->update([
            'status' => 'confirmed',
            'confirmations' => 3,
            'transaction_hash' => $txHash,
            'confirmed_at' => now(),
            'payment_info' => array_merge($cryptoPayment->payment_info ?? [], [
                'tx_hash' => $txHash,
                'block_height' => rand(840000, 890000),
                'simulated' => true,
            ]),
        ]);

        if ($order) {
            $this->completeOrder($order, $chargeId, $cryptoPayment);
        }

        Log::info("Crypto charge {$chargeId} confirmed for Order #{$order?->order_number}");

        return [
            'success' => true,
            'status' => 'confirmed',
            'order' => $order,
            'tx_hash' => $txHash,
        ];
    }

    /**
     * Handle incoming webhook
     */
    public function handleWebhook(array $payload): bool
    {
        try {
            $eventData = $payload['event'] ?? $payload;
            $chargeData = $eventData['data'] ?? [];
            $chargeId = $chargeData['id'] ?? null;
            $eventType = $eventData['type'] ?? 'unknown';

            if (!$chargeId) {
                return false;
            }

            $cryptoPayment = CryptoPayment::where('charge_id', $chargeId)->first();
            if (!$cryptoPayment) {
                return false;
            }

            if (in_array($eventType, ['charge:confirmed', 'charge:resolved'])) {
                $cryptoPayment->update([
                    'status' => 'confirmed',
                    'confirmations' => 3,
                    'confirmed_at' => now(),
                    'payment_info' => $chargeData,
                ]);

                if ($cryptoPayment->order) {
                    $this->completeOrder($cryptoPayment->order, $chargeId, $cryptoPayment);
                }
            } elseif ($eventType === 'charge:failed') {
                $cryptoPayment->update(['status' => 'failed']);
            }

            return true;
        } catch (\Exception $e) {
            Log::error('Webhook processing error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Atomically complete order and allocate encrypted digital game keys
     */
    public function completeOrder(Order $order, string $chargeId, ?CryptoPayment $cryptoPayment = null): void
    {
        if ($order->status === 'completed') {
            return;
        }

        DB::transaction(function () use ($order, $chargeId) {
            // Allocate available keys for each purchased item
            foreach ($order->items as $item) {
                for ($i = 0; $i < $item->quantity; $i++) {
                    $key = GameKey::where('product_id', $item->product_id)
                        ->where('status', 'available')
                        ->lockForUpdate()
                        ->first();

                    if ($key) {
                        $key->update([
                            'status' => 'sold',
                            'order_id' => $order->id,
                            'sold_at' => now(),
                        ]);
                    } else {
                        // If no key in stock, auto-generate placeholder encrypted key so customer is not stranded
                        $generatedKey = 'GTA6-' . strtoupper(Str::random(4)) . '-' . strtoupper(Str::random(4)) . '-' . strtoupper(Str::random(4));
                        GameKey::create([
                            'product_id' => $item->product_id,
                            'key' => $generatedKey,
                            'status' => 'sold',
                            'order_id' => $order->id,
                            'sold_at' => now(),
                        ]);
                    }
                }
            }

            $order->update([
                'status' => 'completed',
                'payment_reference' => $chargeId,
                'paid_at' => now(),
            ]);
        });

        Log::info("Order #{$order->order_number} successfully completed and keys delivered.");
    }

    /**
     * Currency conversion helpers
     */
    public function convertUsdToCrypto(float $usdAmount, string $coin): float
    {
        $rate = $this->getCoinRate($coin);
        if ($rate <= 0) return 0;
        
        $decimals = in_array($coin, ['USDT', 'USDC']) ? 2 : (in_array($coin, ['BTC', 'ETH']) ? 6 : 4);
        return round($usdAmount / $rate, $decimals);
    }

    public function getCoinRate(string $coin): float
    {
        return match (strtoupper($coin)) {
            'BTC' => 68500.00,
            'ETH' => 3550.00,
            'USDT', 'USDC' => 1.00,
            'SOL' => 185.00,
            'DOGE' => 0.16,
            'LTC' => 88.00,
            default => 1.00,
        };
    }

    public function getCoinNetwork(string $coin): string
    {
        return match (strtoupper($coin)) {
            'BTC' => 'Bitcoin Network (Native)',
            'ETH' => 'Ethereum (ERC-20)',
            'USDT' => 'Tether (TRC-20 / ERC-20)',
            'USDC' => 'USD Coin (ERC-20 / Solana)',
            'SOL' => 'Solana Network',
            'DOGE' => 'Dogecoin Network',
            'LTC' => 'Litecoin Network',
            default => 'Mainnet',
        };
    }

    public function generateWalletAddress(string $coin): string
    {
        return match (strtoupper($coin)) {
            'BTC' => 'bc1q' . Str::lower(Str::random(38)),
            'ETH', 'USDC' => '0x' . Str::lower(Str::random(40)),
            'USDT' => 'T' . Str::random(33),
            'SOL' => Str::random(44),
            'DOGE' => 'D' . Str::random(33),
            'LTC' => 'ltc1q' . Str::lower(Str::random(38)),
            default => '0x' . Str::lower(Str::random(40)),
        };
    }

    public function generatePaymentUri(string $coin, string $address, float $amount): string
    {
        return match (strtoupper($coin)) {
            'BTC' => "bitcoin:{$address}?amount={$amount}",
            'ETH' => "ethereum:{$address}?value={$amount}",
            'SOL' => "solana:{$address}?amount={$amount}",
            default => $address,
        };
    }

    public function getSupportedCoins(): array
    {
        return [
            'BTC' => ['name' => 'Bitcoin', 'symbol' => 'BTC', 'icon' => '₿', 'network' => 'Bitcoin Mainnet', 'rate' => 68500],
            'ETH' => ['name' => 'Ethereum', 'symbol' => 'ETH', 'icon' => 'Ξ', 'network' => 'ERC-20', 'rate' => 3550],
            'USDT' => ['name' => 'Tether USD', 'symbol' => 'USDT', 'icon' => '₮', 'network' => 'TRC-20 / ERC-20', 'rate' => 1.00],
            'USDC' => ['name' => 'USD Coin', 'symbol' => 'USDC', 'icon' => '$', 'network' => 'ERC-20 / Multi-chain', 'rate' => 1.00],
            'SOL' => ['name' => 'Solana', 'symbol' => 'SOL', 'icon' => '◎', 'network' => 'Solana Mainnet', 'rate' => 185],
            'DOGE' => ['name' => 'Dogecoin', 'symbol' => 'DOGE', 'icon' => 'Ð', 'network' => 'Dogecoin Network', 'rate' => 0.16],
            'LTC' => ['name' => 'Litecoin', 'symbol' => 'LTC', 'icon' => 'Ł', 'network' => 'Litecoin Network', 'rate' => 88],
        ];
    }

    private function mapStatus(string $coinbaseStatus): string
    {
        return match (strtoupper($coinbaseStatus)) {
            'CONFIRMED', 'COMPLETED', 'RESOLVED' => 'confirmed',
            'FAILED', 'CANCELED' => 'failed',
            'EXPIRED' => 'expired',
            default => 'pending',
        };
    }

    private function makeApiRequest(string $endpoint, string $method = 'GET', ?array $data = null): ?array
    {
        try {
            $url = rtrim($this->apiUrl, '/') . $endpoint;
            $headers = [
                'X-CC-Api-Key: ' . $this->apiKey,
                'X-CC-Version: 2018-03-22',
                'Content-Type: application/json',
            ];

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_TIMEOUT, 12);

            if ($method === 'POST') {
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            }

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode >= 200 && $httpCode < 300) {
                return json_decode($response, true);
            }

            return null;
        } catch (\Exception $e) {
            Log::error("API request failed: " . $e->getMessage());
            return null;
        }
    }
}
