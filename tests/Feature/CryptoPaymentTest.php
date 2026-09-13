<?php

namespace Tests\Feature;

use App\Models\{Category, CryptoPayment, GameKey, Order, Product, User};
use App\Services\CryptoPaymentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CryptoPaymentTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Product $product;
    protected GameKey $gameKey;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::create([
            'email' => 'player@example.com',
            'name' => 'Gamer One',
            'password' => 'password',
        ]);

        $category = Category::create([
            'name' => 'Action',
            'slug' => 'action',
            'active' => true,
        ]);

        $this->product = Product::create([
            'category_id' => $category->id,
            'name' => 'Grand Theft Auto VI',
            'slug' => 'gta-6',
            'description' => 'Official GTA 6 digital game key with instant delivery.',
            'price' => 69.99,
            'currency' => 'USD',
            'platform' => 'PC',
            'region' => 'Global',
            'edition' => 'Standard Edition',
            'active' => true,
        ]);

        $this->gameKey = GameKey::create([
            'product_id' => $this->product->id,
            'key' => 'GTA6-TEST-KEY1-AAAA',
            'status' => 'available',
        ]);
    }

    public function test_crypto_charge_can_be_initiated(): void
    {
        $order = Order::create([
            'user_id' => $this->user->id,
            'order_number' => 'DG-TEST-12345',
            'customer_name' => $this->user->name,
            'customer_email' => $this->user->email,
            'subtotal' => 69.99,
            'discount' => 0,
            'total' => 69.99,
            'currency' => 'USD',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->user)
            ->post(route('crypto.pay'), [
                'order_id' => $order->id,
                'coin' => 'BTC',
            ]);

        $response->assertRedirect();
        
        $this->assertDatabaseHas('crypto_payments', [
            'order_id' => $order->id,
            'currency_code' => 'BTC',
            'usd_amount' => 69.99,
            'status' => 'pending',
        ]);
    }

    public function test_crypto_pending_terminal_displays_qr_and_deposit_info(): void
    {
        $order = Order::create([
            'user_id' => $this->user->id,
            'order_number' => 'DG-TEST-QR',
            'customer_name' => $this->user->name,
            'customer_email' => $this->user->email,
            'subtotal' => 69.99,
            'discount' => 0,
            'total' => 69.99,
            'currency' => 'USD',
            'status' => 'pending',
        ]);

        $service = app(CryptoPaymentService::class);
        $charge = $service->createCharge($order, 'ETH');

        $response = $this->actingAs($this->user)
            ->get(route('crypto.pending', ['charge_id' => $charge['charge_id']]));

        $response->assertOk()
            ->assertSee('Cryptocurrency Payment Terminal')
            ->assertSee('ETH')
            ->assertSee($charge['wallet_address'])
            ->assertSee('Simulate Instant Blockchain Confirmation');
    }

    public function test_crypto_instant_simulation_fulfills_order_and_dispatches_keys(): void
    {
        $order = Order::create([
            'user_id' => $this->user->id,
            'order_number' => 'DG-TEST-SIM',
            'customer_name' => $this->user->name,
            'customer_email' => $this->user->email,
            'subtotal' => 69.99,
            'discount' => 0,
            'total' => 69.99,
            'currency' => 'USD',
            'status' => 'pending',
        ]);

        $order->items()->create([
            'product_id' => $this->product->id,
            'product_name' => $this->product->name,
            'unit_price' => $this->product->price,
            'quantity' => 1,
        ]);

        $service = app(CryptoPaymentService::class);
        $charge = $service->createCharge($order, 'USDT');

        $response = $this->actingAs($this->user)
            ->post(route('crypto.simulate', ['charge_id' => $charge['charge_id']]));

        $response->assertRedirect(route('orders.show', $order));

        $order->refresh();
        $this->assertEquals('completed', $order->status);
        $this->assertNotNull($order->paid_at);

        $this->gameKey->refresh();
        $this->assertEquals('sold', $this->gameKey->status);
        $this->assertEquals($order->id, $this->gameKey->order_id);

        $cryptoPayment = CryptoPayment::where('charge_id', $charge['charge_id'])->first();
        $this->assertEquals('confirmed', $cryptoPayment->status);
        $this->assertEquals(3, $cryptoPayment->confirmations);
        $this->assertNotNull($cryptoPayment->transaction_hash);
    }

    public function test_crypto_status_ajax_endpoint_returns_json(): void
    {
        $order = Order::create([
            'user_id' => $this->user->id,
            'order_number' => 'DG-TEST-AJAX',
            'customer_name' => $this->user->name,
            'customer_email' => $this->user->email,
            'subtotal' => 69.99,
            'discount' => 0,
            'total' => 69.99,
            'currency' => 'USD',
            'status' => 'pending',
        ]);

        $service = app(CryptoPaymentService::class);
        $charge = $service->createCharge($order, 'SOL');

        $response = $this->getJson(route('crypto.status', ['charge_id' => $charge['charge_id']]));

        $response->assertOk()
            ->assertJson([
                'status' => 'pending',
                'confirmations' => 0,
                'is_confirmed' => false,
            ]);
    }
}
