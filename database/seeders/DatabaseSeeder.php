<?php

namespace Database\Seeders;

use App\Models\{Category, CryptoPayment, GameKey, Order, OrderItem, Product, User};
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Admin User
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            ['name' => 'Store Administrator', 'password' => bcrypt('password')]
        );

        $demoUser = User::firstOrCreate(
            ['email' => 'gamer@example.com'],
            ['name' => 'Alex Vance', 'password' => bcrypt('password')]
        );

        // 2. Categories
        $actionCat = Category::firstOrCreate(['slug' => 'action-adventure'], ['name' => 'Action & Adventure', 'active' => true]);
        $openWorldCat = Category::firstOrCreate(['slug' => 'open-world'], ['name' => 'Open World & Crime', 'active' => true]);
        $rockstarCat = Category::firstOrCreate(['slug' => 'rockstar-games'], ['name' => 'Rockstar Games Collection', 'active' => true]);

        // 3. Products
        $productsData = [
            [
                'category_id' => $openWorldCat->id,
                'slug' => 'gta-6-standard-edition',
                'name' => 'Grand Theft Auto VI — Standard Edition',
                'description' => "Welcome to Leonida and the neon-soaked streets of Vice City. Grand Theft Auto VI is the biggest, most immersive evolution of the Grand Theft Auto series yet.\n\nKey Highlights:\n• Complete Base Game for PC (Rockstar Games Launcher & Steam)\n• Full Vice City & Leonida State Open World Map\n• Dual Protagonists: Lucia and Jason story campaign\n• Next-generation physics, Ray Tracing, and dynamic AI world\n• Instant Key Delivery & 100% Guaranteed Activation",
                'price' => 69.99,
                'currency' => 'USD',
                'platform' => 'PC',
                'region' => 'Global',
                'edition' => 'Standard Edition',
                'image' => 'gta-6.svg',
                'active' => true,
            ],
            [
                'category_id' => $openWorldCat->id,
                'slug' => 'gta-6-deluxe-edition',
                'name' => 'Grand Theft Auto VI — Vice City Deluxe Edition',
                'description' => "Dominate Vice City in style with the Deluxe Edition. Includes exclusive bonus cars, high-roller outfits, and starter capital for Vice City Online.\n\nDeluxe Content Includes:\n• Full GTA VI PC Game Key\n• 10 Exclusive Luxury Supercars & Armored Vehicles\n• Vice City Online Starter Pack (1,000,000 GTA$ Bonus Cash)\n• Lucia 'Vice Neon' & Jason 'Heist Specialist' Outfit Packs\n• Custom Weapon Skins & Neon Underglow Kit",
                'price' => 89.99,
                'currency' => 'USD',
                'platform' => 'PC',
                'region' => 'Global',
                'edition' => 'Deluxe Edition',
                'image' => 'gta-6.svg',
                'active' => true,
            ],
            [
                'category_id' => $openWorldCat->id,
                'slug' => 'gta-6-collectors-edition',
                'name' => 'Grand Theft Auto VI — Collector\'s Ultimate Gold Edition',
                'description' => "The definitive VIP package for the ultimate GTA fan. Experience Vice City with 3-day Early Access and maximum perks.\n\nUltimate Collector Perks:\n• Full GTA VI PC Digital Key with 3-Day Early Access\n• 5,000,000 GTA$ High-Roller Cash Card for Vice Online\n• Digital Deluxe Soundtrack & 120-Page Digital Artbook\n• Luxury Oceanfront Penthouse in Vice City Online\n• All Deluxe vehicle packs + Custom Gold Plated Weapon Pack",
                'price' => 119.99,
                'currency' => 'USD',
                'platform' => 'PC',
                'region' => 'Global',
                'edition' => 'Ultimate Gold',
                'image' => 'gta-6.svg',
                'active' => true,
            ],
            [
                'category_id' => $actionCat->id,
                'slug' => 'gta-6-playstation-5',
                'name' => 'Grand Theft Auto VI — PlayStation 5 (PSN Key)',
                'description' => "Harness the power of the PlayStation 5 with DualSense haptic feedback, adaptive triggers, 3D Spatial Audio, and 4K 60FPS Performance Mode.\n\nFeatures:\n• PS5 Digital Activation Code (Redeemable on PlayStation Store)\n• DualSense Dynamic Trigger Resistance & Haptics\n• High-Speed SSD Fast Loading across Leonida",
                'price' => 69.99,
                'currency' => 'USD',
                'platform' => 'PlayStation',
                'region' => 'Global',
                'edition' => 'PS5 Next-Gen',
                'image' => 'gta-6.svg',
                'active' => true,
            ],
            [
                'category_id' => $actionCat->id,
                'slug' => 'gta-6-xbox-series-x',
                'name' => 'Grand Theft Auto VI — Xbox Series X|S Key',
                'description' => "Experience next-gen criminal empire building on Xbox Series X|S. Optimized for Quick Resume and 120Hz variable refresh rate.\n\nFeatures:\n• Xbox Digital Key (Redeemable on Microsoft Xbox Store)\n• 4K Ultra HD & Dolby Atmos Support\n• Xbox Smart Delivery Guaranteed",
                'price' => 69.99,
                'currency' => 'USD',
                'platform' => 'Xbox',
                'region' => 'Global',
                'edition' => 'Xbox Series X|S',
                'image' => 'gta-6.svg',
                'active' => true,
            ],
            [
                'category_id' => $rockstarCat->id,
                'slug' => 'red-dead-redemption-2-ultimate',
                'name' => 'Red Dead Redemption 2 — Ultimate Edition',
                'description' => "Winner of over 175 Game of the Year Awards. Follow Arthur Morgan and the Van der Linde gang across the rugged heartland of America.\n\nIncludes:\n• Full Story Mode + Red Dead Online\n• Bank Robbery Mission & Gang Hideout\n• Dappled Black Thoroughbred Horse & Talisman Perks",
                'price' => 39.99,
                'currency' => 'USD',
                'platform' => 'PC',
                'region' => 'Global',
                'edition' => 'Ultimate Edition',
                'image' => 'placeholder.svg',
                'active' => true,
            ],
            [
                'category_id' => $actionCat->id,
                'slug' => 'cyberpunk-2077-phantom-liberty',
                'name' => 'Cyberpunk 2077: Ultimate Phantom Liberty Bundle',
                'description' => "Dive into Night City and Dogtown with the complete spy-thriller expansion starring Idris Elba. Featuring Ray Tracing Overdrive Mode.\n\nIncludes:\n• Cyberpunk 2077 Base Game + Phantom Liberty DLC\n• Relic Skill Tree & Vehicle Combat Perks",
                'price' => 49.99,
                'currency' => 'USD',
                'platform' => 'PC',
                'region' => 'Global',
                'edition' => 'Complete Bundle',
                'image' => 'placeholder.svg',
                'active' => true,
            ],
        ];

        foreach ($productsData as $data) {
            $product = Product::updateOrCreate(['slug' => $data['slug']], $data);

            // 4. Seed available encrypted digital keys for each product
            $existingKeys = $product->keys()->count();
            if ($existingKeys < 8) {
                for ($i = 1; $i <= (10 - $existingKeys); $i++) {
                    $prefix = strtoupper(substr($product->slug, 0, 4));
                    $keyString = "{$prefix}-" . strtoupper(Str::random(4)) . '-' . strtoupper(Str::random(4)) . '-' . strtoupper(Str::random(4));
                    $product->keys()->create([
                        'key' => $keyString,
                        'status' => 'available',
                    ]);
                }
            }
        }

        // 5. Seed sample orders and crypto payments for admin financial metrics
        if (Order::count() < 4) {
            $gtaStandard = Product::where('slug', 'gta-6-standard-edition')->first();
            $gtaDeluxe = Product::where('slug', 'gta-6-deluxe-edition')->first();
            $gtaGold = Product::where('slug', 'gta-6-collectors-edition')->first();

            // Sample Order 1: Bitcoin payment
            $order1 = Order::create([
                'user_id' => $demoUser->id,
                'order_number' => 'DG-BTC-' . strtoupper(Str::random(6)),
                'customer_name' => 'Michael De Santa',
                'customer_email' => 'michael@los-santos.test',
                'subtotal' => 119.99,
                'discount' => 0,
                'total' => 119.99,
                'currency' => 'USD',
                'status' => 'completed',
                'payment_reference' => 'crypto_btc_' . Str::lower(Str::random(12)),
                'paid_at' => now()->subHours(3),
            ]);
            $order1->items()->create([
                'product_id' => $gtaGold->id,
                'product_name' => $gtaGold->name,
                'unit_price' => $gtaGold->price,
                'quantity' => 1,
            ]);
            // Allocate key
            $key1 = $gtaGold->keys()->where('status', 'available')->first();
            if ($key1) {
                $key1->update(['status' => 'sold', 'order_id' => $order1->id, 'sold_at' => now()->subHours(3)]);
            }
            // Crypto Payment record
            CryptoPayment::create([
                'order_id' => $order1->id,
                'charge_id' => $order1->payment_reference,
                'currency_code' => 'BTC',
                'amount' => 0.001751,
                'usd_amount' => 119.99,
                'wallet_address' => 'bc1q' . Str::lower(Str::random(38)),
                'status' => 'confirmed',
                'confirmations' => 3,
                'transaction_hash' => '0x' . Str::random(64),
                'confirmed_at' => now()->subHours(3),
            ]);

            // Sample Order 2: USDT payment
            $order2 = Order::create([
                'user_id' => $demoUser->id,
                'order_number' => 'DG-USDT-' . strtoupper(Str::random(6)),
                'customer_name' => 'Franklin Clinton',
                'customer_email' => 'franklin@f-agency.test',
                'subtotal' => 89.99,
                'discount' => 0,
                'total' => 89.99,
                'currency' => 'USD',
                'status' => 'completed',
                'payment_reference' => 'crypto_usdt_' . Str::lower(Str::random(12)),
                'paid_at' => now()->subHours(6),
            ]);
            $order2->items()->create([
                'product_id' => $gtaDeluxe->id,
                'product_name' => $gtaDeluxe->name,
                'unit_price' => $gtaDeluxe->price,
                'quantity' => 1,
            ]);
            $key2 = $gtaDeluxe->keys()->where('status', 'available')->first();
            if ($key2) {
                $key2->update(['status' => 'sold', 'order_id' => $order2->id, 'sold_at' => now()->subHours(6)]);
            }
            CryptoPayment::create([
                'order_id' => $order2->id,
                'charge_id' => $order2->payment_reference,
                'currency_code' => 'USDT',
                'amount' => 89.99,
                'usd_amount' => 89.99,
                'wallet_address' => 'T' . Str::random(33),
                'status' => 'confirmed',
                'confirmations' => 3,
                'transaction_hash' => '0x' . Str::random(64),
                'confirmed_at' => now()->subHours(6),
            ]);

            // Sample Order 3: Card payment
            $order3 = Order::create([
                'user_id' => $admin->id,
                'order_number' => 'DG-CARD-' . strtoupper(Str::random(6)),
                'customer_name' => 'Trevor Philips',
                'customer_email' => 'trevor@tp-industries.test',
                'subtotal' => 69.99,
                'discount' => 0,
                'total' => 69.99,
                'currency' => 'USD',
                'status' => 'completed',
                'payment_reference' => 'pi_' . Str::random(24),
                'paid_at' => now()->subHours(12),
            ]);
            $order3->items()->create([
                'product_id' => $gtaStandard->id,
                'product_name' => $gtaStandard->name,
                'unit_price' => $gtaStandard->price,
                'quantity' => 1,
            ]);
            $key3 = $gtaStandard->keys()->where('status', 'available')->first();
            if ($key3) {
                $key3->update(['status' => 'sold', 'order_id' => $order3->id, 'sold_at' => now()->subHours(12)]);
            }
        }
    }
}
