@extends('layouts.app')

@section('title', "Buy {$product->name} ({$product->platform}) | Instant Digital Key Delivery")
@section('meta_description', "Buy {$product->name} for {$product->platform}. Instant key activation code delivery, 100% genuine guaranteed. Crypto checkout with Bitcoin, USDT, Cards from \${$product->price}.")
@section('meta_keywords', "Buy {$product->name}, {$product->name} key, {$product->slug}, {$product->platform} game key, buy {$product->name} with bitcoin, cheap {$product->name} cd key")
@section('og_image', asset('images/products/'.$product->slug.'.svg'))
@section('og_type', 'product')

@section('schema')
@php
    $brandName = (str_contains(strtolower($product->name), 'grand theft auto') || str_contains(strtolower($product->name), 'red dead')) 
        ? 'Rockstar Games' 
        : (str_contains(strtolower($product->name), 'cyberpunk') ? 'CD PROJEKT RED' : 'Official Publisher');

    $productSchema = [
        '@context' => 'https://schema.org/',
        '@type' => 'Product',
        'name' => $product->name,
        'image' => [
            asset('images/products/'.$product->slug.'.svg')
        ],
        'description' => Str::limit(strip_tags($product->description), 280),
        'sku' => $product->slug,
        'mpn' => 'KEY-' . $product->id,
        'brand' => [
            '@type' => 'Brand',
            'name' => $brandName,
        ],
        'offers' => [
            '@type' => 'Offer',
            'url' => route('products.show', $product->slug),
            'priceCurrency' => 'USD',
            'price' => number_format($product->price, 2, '.', ''),
            'priceValidUntil' => date('Y-12-31', strtotime('+1 year')),
            'itemCondition' => 'https://schema.org/NewCondition',
            'availability' => 'https://schema.org/InStock',
            'seller' => [
                '@type' => 'Organization',
                'name' => config('app.name', 'GTA 6 Digital Key Store'),
            ],
        ],
        'aggregateRating' => [
            '@type' => 'AggregateRating',
            'ratingValue' => '4.9',
            'reviewCount' => '148',
        ],
    ];

    $breadcrumbSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => [
            [
                '@type' => 'ListItem',
                'position' => 1,
                'name' => 'Home',
                'item' => route('home'),
            ],
            [
                '@type' => 'ListItem',
                'position' => 2,
                'name' => 'Catalog',
                'item' => route('products.index'),
            ],
            [
                '@type' => 'ListItem',
                'position' => 3,
                'name' => $product->name,
                'item' => route('products.show', $product->slug),
            ],
        ],
    ];
@endphp
<script type="application/ld+json">
{!! json_encode($productSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
</script>
<script type="application/ld+json">
{!! json_encode($breadcrumbSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
</script>
@endsection

@section('content')
<!-- Breadcrumb -->
<nav class="text-sm text-slate-400 mb-8 flex items-center gap-2">
    <a href="{{ route('home') }}" class="hover:text-white transition">Home</a>
    <span>/</span>
    <a href="{{ route('products.index') }}" class="hover:text-white transition">Catalog</a>
    <span>/</span>
    <span class="text-purple-400 font-medium truncate">{{ $product->name }}</span>
</nav>

<div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
    <!-- LEFT: Media, Description, Specs & Activation Guide (8 cols) -->
    <div class="lg:col-span-8 space-y-8">
        
        <!-- Product Poster / Hero Media -->
        <div class="relative rounded-3xl overflow-hidden bg-gradient-to-br from-slate-900 via-purple-950/40 to-slate-950 border border-purple-500/30 shadow-2xl">
            <div class="h-[340px] sm:h-[460px] relative flex items-center justify-center overflow-hidden">
                <img 
                    src="{{ asset('images/products/'.$product->slug.'.svg') }}" 
                    alt="{{ $product->name }}"
                    class="w-full h-full object-cover transition-transform duration-700 hover:scale-105"
                    onerror="this.onerror=null;this.src='{{ asset('images/products/placeholder.svg') }}'">
                
                <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/20 to-transparent"></div>

                <!-- Floating Badges on Poster -->
                <div class="absolute top-4 left-4 flex flex-wrap gap-2">
                    <span class="px-3 py-1 bg-purple-600/90 text-white text-xs font-black rounded-full shadow-lg backdrop-blur-md">
                        🎮 {{ $product->platform }}
                    </span>
                    <span class="px-3 py-1 bg-slate-900/90 text-emerald-400 text-xs font-bold rounded-full border border-emerald-500/30 backdrop-blur-md flex items-center gap-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                        ⚡ Instant Key Delivery
                    </span>
                </div>

                <div class="absolute bottom-4 left-4 right-4 flex items-end justify-between">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-widest text-purple-300">{{ $product->edition ?? 'Standard' }}</p>
                        <h1 class="text-2xl sm:text-4xl font-black text-white drop-shadow-md leading-tight">{{ $product->name }}</h1>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edition Selector Matrix (If GTA 6) -->
        @if(str_contains(strtolower($product->slug), 'gta-6') || str_contains(strtolower($product->name), 'grand theft auto'))
        <div class="rounded-3xl bg-slate-900/60 border border-slate-800 p-6 shadow-xl backdrop-blur-md">
            <h3 class="text-base font-bold text-white mb-4 flex items-center justify-between">
                <span>Select Edition</span>
                <span class="text-xs text-purple-400 font-semibold">Includes Official Digital Key</span>
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                @php
                    $editions = [
                        ['slug' => 'gta-6-standard-edition', 'name' => 'Standard Edition', 'price' => 69.99, 'tag' => 'Base Game', 'perks' => 'Full Base Campaign + Map'],
                        ['slug' => 'gta-6-deluxe-edition', 'name' => 'Vice City Deluxe', 'price' => 89.99, 'tag' => 'Popular ★', 'perks' => '10 Supercars + 1M GTA$ Bonus'],
                        ['slug' => 'gta-6-collectors-edition', 'name' => 'Collector\'s Gold', 'price' => 119.99, 'tag' => 'VIP Ultimate', 'perks' => '3-Day Early Access + 5M GTA$'],
                    ];
                @endphp

                @foreach($editions as $ed)
                    @php
                        $isSelected = ($product->slug === $ed['slug']) || (str_contains($product->slug, 'standard') && str_contains($ed['slug'], 'standard'));
                    @endphp
                    <a href="{{ route('products.show', $ed['slug']) }}" class="block rounded-2xl p-4 border-2 transition-all {{ $isSelected ? 'bg-purple-900/40 border-purple-500 shadow-lg shadow-purple-500/20' : 'bg-slate-950/80 border-slate-800 hover:border-slate-700' }}">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded {{ $isSelected ? 'bg-purple-500 text-white' : 'bg-slate-800 text-slate-400' }}">{{ $ed['tag'] }}</span>
                            <span class="text-base font-black font-mono text-white">${{ number_format($ed['price'], 2) }}</span>
                        </div>
                        <p class="text-sm font-bold text-white">{{ $ed['name'] }}</p>
                        <p class="text-xs text-slate-400 mt-1">{{ $ed['perks'] }}</p>
                    </a>
                @endforeach
            </div>
        </div>
        @endif

        <!-- About Game & Description -->
        <div class="rounded-3xl bg-slate-900/60 border border-slate-800 p-6 sm:p-8 shadow-xl backdrop-blur-md space-y-6">
            <h2 class="text-xl font-bold text-white flex items-center gap-2">
                <span>📖 About This Game</span>
            </h2>
            
            <div class="text-slate-300 text-sm leading-relaxed space-y-4 whitespace-pre-line">
                {{ $product->description }}
            </div>

            <!-- Features Highlights Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-4 border-t border-slate-800">
                <div class="rounded-2xl bg-slate-950/70 border border-slate-800/80 p-4">
                    <p class="text-sm font-bold text-purple-300 mb-1 flex items-center gap-1.5">
                        <span>⚡</span> Instant Digital Dispatch
                    </p>
                    <p class="text-xs text-slate-400">Encrypted game key is unlocked on your receipt immediately after payment confirmation.</p>
                </div>
                <div class="rounded-2xl bg-slate-950/70 border border-slate-800/80 p-4">
                    <p class="text-sm font-bold text-emerald-400 mb-1 flex items-center gap-1.5">
                        <span>🛡️</span> 100% Authorized & Region Free
                    </p>
                    <p class="text-xs text-slate-400">Global license key with zero regional lockouts. Lifetime ownership on your personal account.</p>
                </div>
                <div class="rounded-2xl bg-slate-950/70 border border-slate-800/80 p-4">
                    <p class="text-sm font-bold text-cyan-400 mb-1 flex items-center gap-1.5">
                        <span>🪙</span> Multi-Crypto & Fiat Accepted
                    </p>
                    <p class="text-xs text-slate-400">Checkout smoothly using Bitcoin, Ethereum, USDT, Solana, Credit Cards or PayPal.</p>
                </div>
                <div class="rounded-2xl bg-slate-950/70 border border-slate-800/80 p-4">
                    <p class="text-sm font-bold text-amber-400 mb-1 flex items-center gap-1.5">
                        <span>💬</span> 24/7 Key Support
                    </p>
                    <p class="text-xs text-slate-400">Automated key replacement guarantee with dedicated 24/7 client resolution support.</p>
                </div>
            </div>
        </div>

        <!-- System Requirements (For PC Games) -->
        @if(strtolower($product->platform) === 'pc')
        <div class="rounded-3xl bg-slate-900/60 border border-slate-800 p-6 sm:p-8 shadow-xl backdrop-blur-md">
            <h3 class="text-lg font-bold text-white mb-6 flex items-center gap-2">
                <span>💻 PC System Requirements (Grand Theft Auto VI)</span>
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Minimum Specs -->
                <div class="rounded-2xl bg-slate-950/80 border border-slate-800 p-5 space-y-3">
                    <span class="inline-block px-2.5 py-1 bg-slate-800 text-slate-300 text-xs font-bold rounded-lg uppercase">Minimum (1080p / 30fps)</span>
                    <ul class="text-xs text-slate-300 space-y-2 font-mono">
                        <li><strong class="text-slate-400">OS:</strong> Windows 10/11 64-bit</li>
                        <li><strong class="text-slate-400">Processor:</strong> Intel Core i5-10600K / AMD Ryzen 5 5600X</li>
                        <li><strong class="text-slate-400">Memory:</strong> 16 GB RAM</li>
                        <li><strong class="text-slate-400">Graphics:</strong> NVIDIA GeForce RTX 2070 (8GB) / Radeon RX 6700 XT</li>
                        <li><strong class="text-slate-400">DirectX:</strong> Version 12 Ultimate</li>
                        <li><strong class="text-slate-400">Storage:</strong> 150 GB NVMe SSD space required</li>
                    </ul>
                </div>

                <!-- Recommended Specs -->
                <div class="rounded-2xl bg-purple-950/20 border border-purple-500/30 p-5 space-y-3">
                    <span class="inline-block px-2.5 py-1 bg-purple-600 text-white text-xs font-bold rounded-lg uppercase">Recommended (1440p/4K Ray Tracing)</span>
                    <ul class="text-xs text-slate-200 space-y-2 font-mono">
                        <li><strong class="text-purple-300">OS:</strong> Windows 11 64-bit (Latest Build)</li>
                        <li><strong class="text-purple-300">Processor:</strong> Intel Core i7-13700K / AMD Ryzen 7 7800X3D</li>
                        <li><strong class="text-purple-300">Memory:</strong> 32 GB High-Speed DDR5 RAM</li>
                        <li><strong class="text-purple-300">Graphics:</strong> NVIDIA GeForce RTX 4070 Ti / 4080 (16GB)</li>
                        <li><strong class="text-purple-300">DirectX:</strong> Version 12 Ultimate (Full DXR Ray Tracing)</li>
                        <li><strong class="text-purple-300">Storage:</strong> 150 GB PCIe Gen4 M.2 SSD</li>
                    </ul>
                </div>
            </div>
        </div>
        @endif

        <!-- Step-by-Step Key Activation Guide -->
        <div class="rounded-3xl bg-slate-900/60 border border-slate-800 p-6 sm:p-8 shadow-xl backdrop-blur-md">
            <h3 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
                <span>🔑 How to Redeem & Activate Your Key</span>
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="rounded-2xl bg-slate-950/70 border border-slate-800 p-4">
                    <div class="w-8 h-8 rounded-full bg-purple-600 text-white font-black text-sm flex items-center justify-center mb-3">1</div>
                    <p class="font-bold text-white text-sm">Copy Key</p>
                    <p class="text-xs text-slate-400 mt-1">Receive your encrypted code instantly upon payment and click "Copy Key".</p>
                </div>
                <div class="rounded-2xl bg-slate-950/70 border border-slate-800 p-4">
                    <div class="w-8 h-8 rounded-full bg-purple-600 text-white font-black text-sm flex items-center justify-center mb-3">2</div>
                    <p class="font-bold text-white text-sm">Open Platform</p>
                    <p class="text-xs text-slate-400 mt-1">Open {{ $product->drm_client }}, click "Redeem Code" or "Activate Product".</p>
                </div>
                <div class="rounded-2xl bg-slate-950/70 border border-slate-800 p-4">
                    <div class="w-8 h-8 rounded-full bg-emerald-500 text-white font-black text-sm flex items-center justify-center mb-3">3</div>
                    <p class="font-bold text-white text-sm">Download & Play</p>
                    <p class="text-xs text-slate-400 mt-1">The game will permanently bind to your library. Pre-load and start playing!</p>
                </div>
            </div>
        </div>

    </div>

    <!-- RIGHT: Buy Box & Checkout Sidebar (4 cols) -->
    <div class="lg:col-span-4">
        <div class="rounded-3xl bg-gradient-to-br from-purple-950/60 via-slate-900/90 to-violet-950/60 border border-purple-500/40 p-6 sm:p-8 sticky top-28 shadow-2xl backdrop-blur-xl space-y-6">
            
            <!-- Price Box -->
            <div class="pb-6 border-b border-slate-800">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Digital Key Price</span>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-pink-500/20 text-pink-300 border border-pink-500/30">-{{ $product->discount_percent }}% OFF</span>
                </div>
                <div class="flex items-baseline gap-3">
                    <span class="text-4xl sm:text-5xl font-black font-mono text-white">${{ number_format($product->price, 2) }}</span>
                    <span class="text-lg font-mono text-slate-500 line-through">${{ number_format($product->original_price, 2) }}</span>
                </div>
                <p class="text-xs text-slate-400 mt-2">One-time purchase • Lifetime ownership</p>
            </div>

            <!-- Stock & Availability Indicator -->
            <div class="rounded-2xl bg-slate-950/80 border border-slate-800 p-4 space-y-2">
                <div class="flex items-center justify-between text-xs">
                    <span class="text-slate-400 font-semibold">Inventory Status:</span>
                    @if($product->is_in_stock)
                        <span class="text-emerald-400 font-bold flex items-center gap-1">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                            In Stock ({{ $product->available_keys_count }} Available)
                        </span>
                    @else
                        <span class="text-amber-400 font-bold">Back-Order Automated Dispatch</span>
                    @endif
                </div>
                <div class="flex items-center justify-between text-xs">
                    <span class="text-slate-400 font-semibold">Delivery Time:</span>
                    <span class="text-purple-300 font-bold">Instant (0-5 Seconds)</span>
                </div>
                <div class="flex items-center justify-between text-xs">
                    <span class="text-slate-400 font-semibold">Activation:</span>
                    <span class="text-white font-bold">{{ $product->region }} (Worldwide)</span>
                </div>
            </div>

            <div class="rounded-2xl bg-slate-950/80 border border-slate-800 p-4 space-y-3">
                <div class="flex items-center justify-between">
                    <p class="text-[11px] font-bold uppercase tracking-wider text-purple-300">AI Product Assistant</p>
                    <span class="inline-flex items-center gap-1 rounded-full border border-emerald-500/30 bg-emerald-500/10 px-2 py-0.5 text-[10px] font-bold text-emerald-300">
                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span>
                        Online
                    </span>
                </div>

                <div id="ai-chat-box" class="min-h-[120px] rounded-xl border border-slate-700 bg-slate-900/80 p-3 text-sm text-slate-200">
                    <div class="mb-2 rounded-lg border border-emerald-500/20 bg-emerald-500/10 p-2 text-emerald-100">
                        Hi! Ask me if this product is available or in stock.
                    </div>
                </div>

                <div class="flex gap-2">
                    <input
                        id="ai-product-question"
                        type="text"
                        value="Is this product available?"
                        class="w-full rounded-xl border border-slate-700 bg-slate-900/80 px-3 py-2 text-sm text-slate-100 placeholder:text-slate-500 focus:border-purple-500 focus:outline-none"
                        placeholder="Ask about availability..."
                    >
                    <button id="ai-product-check" type="button" class="rounded-xl bg-gradient-to-r from-emerald-500 to-cyan-500 px-4 py-2 text-sm font-bold text-white shadow-lg shadow-emerald-500/20 transition hover:brightness-110">
                        Send
                    </button>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="space-y-3">
                <form method="POST" action="{{ route('cart.add', $product) }}">
                    @csrf
                    <button type="submit" class="w-full py-4 px-6 bg-gradient-to-r from-purple-500 via-violet-600 to-pink-500 hover:from-purple-600 hover:to-pink-600 text-white font-black text-base rounded-xl transition-all shadow-xl shadow-purple-500/30 hover:shadow-purple-500/50 hover:-translate-y-0.5 flex items-center justify-center gap-2.5">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.7 2.84-7.242A1.125 1.125 0 0020.25 6H5.106m2.394 8.25l-.8-4.25m0 0h14.7M7.5 20.25a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zm12 0a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z" />
                        </svg>
                        <span>Add to Cart</span>
                    </button>
                </form>

                <form method="POST" action="{{ route('cart.add', $product) }}">
                    @csrf
                    <button type="submit" class="w-full py-3.5 px-6 bg-slate-800/90 hover:bg-slate-700 text-white font-bold text-sm rounded-xl transition border border-slate-700 flex items-center justify-center gap-2">
                        <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M13 2L3 14h7v8l10-12h-7z" />
                        </svg>
                        <span>Instant Buy / Crypto Checkout</span>
                    </button>
                </form>
            </div>

            <!-- Accepted Payment Gateways -->
            <div class="pt-6 border-t border-slate-800 space-y-3">
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400 text-center">Accepted Payment Methods</p>
                <div class="flex flex-wrap items-center justify-center gap-2 text-xs text-slate-300 font-mono">
                    <span class="px-2 py-1 bg-slate-950 rounded border border-slate-800">₿ Bitcoin</span>
                    <span class="px-2 py-1 bg-slate-950 rounded border border-slate-800">Ξ Ethereum</span>
                    <span class="px-2 py-1 bg-slate-950 rounded border border-slate-800">₮ USDT</span>
                    <span class="px-2 py-1 bg-slate-950 rounded border border-slate-800">◎ Solana</span>
                    <span class="px-2 py-1 bg-slate-950 rounded border border-slate-800">💳 Cards</span>
                    <span class="px-2 py-1 bg-slate-950 rounded border border-slate-800">🅿️ PayPal</span>
                </div>
            </div>

            <!-- Customer Trust Badge -->
            <div class="text-center pt-2">
                <p class="text-xs text-amber-300 font-bold">⭐⭐⭐⭐⭐ 4.9 / 5.0 (2,840+ Reviews)</p>
                <p class="text-[11px] text-slate-500 mt-1">Verified Digital Key Reseller Guaranteed</p>

            </div>
        </div>
    </div>
</div>

<script>
    const productName = @json($product->name);
    const productPlatform = @json($product->platform);
    const productPrice = @json(number_format($product->price, 2));
    const productIsAvailable = {{ $product->is_in_stock ? 'true' : 'false' }};
    const availableCount = {{ $product->available_keys_count }};
    const questionInput = document.getElementById('ai-product-question');
    const aiChatBox = document.getElementById('ai-chat-box');
    const askButton = document.getElementById('ai-product-check');

    function addChatMessage(text, isUser = false) {
        const message = document.createElement('div');
        message.className = 'mb-2 rounded-lg p-2 text-sm ' + (isUser
            ? 'bg-slate-700 text-white ml-auto max-w-[85%]'
            : 'border ' + (productIsAvailable ? 'border-emerald-500/20 bg-emerald-500/10 text-emerald-100' : 'border-amber-500/20 bg-amber-500/10 text-amber-100'));
        message.textContent = text;
        aiChatBox.appendChild(message);
        aiChatBox.scrollTop = aiChatBox.scrollHeight;
    }

    function answerAvailabilityQuestion() {
        const question = (questionInput?.value || '').trim();

        if (!question) {
            addChatMessage('Please ask me something about this product.', false);
            return;
        }

        addChatMessage(question, true);

        const q = question.toLowerCase();
        const isAvailability = /(available|stock|in stock|have this|do you have|can i buy|buy now|sell this|inventory|ready to order|out of stock|sold out)/i.test(q);
        const isPrice = /(price|cost|how much|cheap|expensive|amount)/i.test(q);
        const isPlatform = /(platform|pc|playstation|xbox|console|steam|launcher)/i.test(q);
        const isEdition = /(edition|version|standard|deluxe|gold|collector|vip)/i.test(q);
        const isDelivery = /(delivery|download|instant|key|activate|how long|redeem)/i.test(q);

        if (isAvailability) {
            addChatMessage(productIsAvailable
                ? 'Yes — this product is currently available and ready to order.'
                : 'No — this product is currently out of stock. Please check back later or try another edition.', false);
            return;
        }

        if (isPrice) {
            addChatMessage('This product is priced at $' + productPrice + ' and includes instant digital delivery.', false);
            return;
        }

        if (isPlatform) {
            addChatMessage('This product is for ' + productPlatform + ' and is ready for digital activation.', false);
            return;
        }

        if (isEdition) {
            addChatMessage('This is the ' + productName + ' edition. It includes the standard digital key and instant access.', false);
            return;
        }

        if (isDelivery) {
            addChatMessage('Your key is delivered instantly after payment and is ready for activation right away.', false);
            return;
        }

        addChatMessage('This product is ' + productName + '. It is ' + (productIsAvailable ? 'available now' : 'currently out of stock') + ' and priced at $' + productPrice + '.', false);
    }

    askButton?.addEventListener('click', answerAvailabilityQuestion);
    questionInput?.addEventListener('keydown', function (event) {
        if (event.key === 'Enter') {
            answerAvailabilityQuestion();
        }
    });
</script>

            </div>

        </div>
    </div>
</div>
@endsection
