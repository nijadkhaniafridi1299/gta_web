<?php

namespace Tests\Feature;

use App\Models\{Category, GameKey, Order, Product, User};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreAndAdminTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $customer;
    protected Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'email' => 'admin@example.com',
            'name' => 'Admin User',
            'password' => bcrypt('password'),
        ]);

        $this->customer = User::create([
            'email' => 'customer@example.com',
            'name' => 'John Customer',
            'password' => bcrypt('password'),
        ]);

        $category = Category::create([
            'name' => 'Open World',
            'slug' => 'open-world',
            'active' => true,
        ]);

        $this->product = Product::create([
            'category_id' => $category->id,
            'name' => 'Grand Theft Auto VI — Standard Edition',
            'slug' => 'gta-6-standard-edition',
            'description' => 'Welcome to Vice City. Instant digital key delivery.',
            'price' => 69.99,
            'currency' => 'USD',
            'platform' => 'PC',
            'region' => 'Global',
            'edition' => 'Standard Edition',
            'active' => true,
        ]);

        GameKey::create([
            'product_id' => $this->product->id,
            'key' => 'GTA6-AUTO-KEY1-9999',
            'status' => 'available',
        ]);
    }

    public function test_product_detail_page_loads_with_specs_and_crypto_badges(): void
    {
        $response = $this->get(route('products.show', $this->product->slug));

        $response->assertOk()
            ->assertSee('Grand Theft Auto VI — Standard Edition')
            ->assertSee('$69.99')
            ->assertSee('PC System Requirements')
            ->assertSee('Instant Key Delivery')
            ->assertSee('How to Redeem');
    }

    public function test_checkout_form_displays_crypto_tab(): void
    {
        // Add item to cart session
        $response = $this->actingAs($this->customer)
            ->withSession([
                'cart' => [
                    $this->product->id => [
                        'id' => $this->product->id,
                        'name' => $this->product->name,
                        'price' => $this->product->price,
                        'quantity' => 1,
                    ],
                ],
            ])
            ->get(route('checkout'));

        $response->assertOk()
            ->assertSee('Crypto (BTC / USDT)')
            ->assertSee('Choose Your Cryptocurrency')
            ->assertSee('Bitcoin')
            ->assertSee('Tether USDT')
            ->assertSee('Proceed with Crypto Payment');
    }

    public function test_checkout_submission_with_crypto_creates_order_and_redirects(): void
    {
        $response = $this->actingAs($this->customer)
            ->withSession([
                'cart' => [
                    $this->product->id => [
                        'id' => $this->product->id,
                        'name' => $this->product->name,
                        'price' => $this->product->price,
                        'quantity' => 1,
                    ],
                ],
            ])
            ->post(route('checkout.pay'), [
                'name' => 'John Customer',
                'email' => 'customer@example.com',
                'payment_method' => 'crypto',
                'crypto_coin' => 'BTC',
            ]);

        $response->assertRedirect();
        
        $this->assertDatabaseHas('orders', [
            'customer_email' => 'customer@example.com',
            'total' => 69.99,
        ]);

        $this->assertDatabaseHas('crypto_payments', [
            'currency_code' => 'BTC',
            'usd_amount' => 69.99,
            'status' => 'pending',
        ]);
    }

    public function test_admin_can_access_admin_dashboard(): void
    {
        $response = $this->actingAs($this->admin)
            ->get('/admin');

        $response->assertOk();
    }
}
