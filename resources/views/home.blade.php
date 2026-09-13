@extends('layouts.app')

@section('title', 'Buy GTA 6 Key & PC CD Keys | Instant Delivery & Crypto | GTA 6 Vault')
@section('meta_description', 'Official Grand Theft Auto VI (GTA 6) CD keys and digital PC, PS5 & Xbox game keys with 0-5s instant automated delivery. Buy with Bitcoin, USDT, Solana, or Credit Cards at best prices.')
@section('meta_keywords', 'Buy GTA 6, GTA 6 PC key, Grand Theft Auto VI CD key, GTA 6 digital key, buy games with bitcoin, cheap GTA 6 key, instant crypto game store, Vice City PC key')

@section('schema')
@php
    $homeWebSiteSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'WebSite',
        'name' => 'GTA 6 Digital Key Store',
        'url' => route('home'),
        'description' => 'Authorized digital game store for GTA VI and AAA game keys with crypto and card checkout.',
        'potentialAction' => [
            '@type' => 'SearchAction',
            'target' => route('products.index') . '?q={search_term_string}',
            'query-input' => 'required name=search_term_string',
        ],
    ];

    $homeFaqSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'FAQPage',
        'mainEntity' => [
            [
                '@type' => 'Question',
                'name' => 'Where can I buy GTA 6 PC digital keys with instant delivery?',
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => 'You can purchase official GTA 6 Standard, Deluxe, and Collector\'s Edition keys directly on GTA 6 Vault with instant 0-5 second key delivery upon payment.',
                ],
            ],
            [
                '@type' => 'Question',
                'name' => 'Can I buy GTA 6 with Bitcoin, USDT, or cryptocurrency?',
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => 'Yes! We offer a built-in crypto payment terminal supporting Bitcoin (BTC), Tether (USDT), Ethereum (ETH), and Solana (SOL) alongside standard Credit and Debit card checkout.',
                ],
            ],
        ],
    ];
@endphp
<script type="application/ld+json">
{!! json_encode($homeWebSiteSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
</script>
<script type="application/ld+json">
{!! json_encode($homeFaqSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
</script>
@endsection

@section('content')
<!-- ===== HERO SECTION ===== -->
<section class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-purple-950 via-slate-900 to-slate-950 px-6 py-14 sm:px-10 sm:py-20 lg:px-16 border border-purple-500/30 shadow-2xl">
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -left-20 -top-20 h-96 w-96 rounded-full bg-purple-600/20 blur-3xl"></div>
        <div class="absolute right-0 top-1/3 h-96 w-96 rounded-full bg-pink-600/15 blur-3xl"></div>
    </div>
    
    <div class="relative z-10 max-w-4xl">
        <div class="inline-flex items-center gap-2 rounded-full bg-purple-500/10 px-4 py-1.5 text-xs font-bold text-purple-300 border border-purple-500/30 backdrop-blur-md mb-6">
            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
            <span>OFFICIAL GTA VI & AAA DIGITAL GAME KEYS</span>
        </div>
        
        <h1 class="text-4xl sm:text-6xl lg:text-7xl font-black leading-none tracking-tight text-white">
            Welcome to Vice City.
            <span class="block mt-2 bg-gradient-to-r from-purple-400 via-pink-400 to-amber-300 bg-clip-text text-transparent">Instant Digital Delivery.</span>
        </h1>
        
        <p class="mt-6 text-base sm:text-lg text-slate-300 max-w-2xl leading-relaxed">
            Get official authorized digital keys for <strong>Grand Theft Auto VI</strong> and top titles. Pay instantly with <strong>Bitcoin, Ethereum, USDT, Solana, Credit Cards or PayPal</strong> and receive your key immediately.
        </p>
        
        <!-- CTA Buttons -->
        <div class="mt-8 flex flex-wrap gap-4">
            <a href="{{ route('products.index') }}" class="inline-flex items-center justify-center px-8 py-4 bg-gradient-to-r from-purple-500 via-violet-600 to-pink-500 hover:from-purple-600 hover:to-pink-600 text-white font-black text-sm rounded-xl transition-all shadow-lg shadow-purple-500/30 hover:shadow-purple-500/50 hover:-translate-y-0.5">
                Explore Game Editions <span class="ml-2">→</span>
            </a>
            <a href="{{ route('help') }}" class="inline-flex items-center justify-center px-7 py-4 bg-slate-900/80 hover:bg-slate-800 text-white font-bold text-sm rounded-xl border border-slate-700 transition-all">
                ⚡ Instant Crypto Guide
            </a>
        </div>
        
        <!-- Trust Indicators -->
        <div class="mt-12 grid grid-cols-1 sm:grid-cols-3 gap-6 pt-8 border-t border-slate-800">
            <div class="flex items-center gap-3">
                <div class="flex-shrink-0 w-10 h-10 rounded-xl bg-emerald-500/20 flex items-center justify-center text-emerald-400 text-lg">
                    ⚡
                </div>
                <div>
                    <p class="text-sm font-bold text-white">Instant Key Dispatch</p>
                    <p class="text-xs text-slate-400">Keys in 0-5 seconds</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <div class="flex-shrink-0 w-10 h-10 rounded-xl bg-purple-500/20 flex items-center justify-center text-purple-300 text-lg">
                    🪙
                </div>
                <div>
                    <p class="text-sm font-bold text-white">Crypto & Fiat Accepted</p>
                    <p class="text-xs text-slate-400">BTC, ETH, USDT, SOL & Cards</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <div class="flex-shrink-0 w-10 h-10 rounded-xl bg-amber-500/20 flex items-center justify-center text-amber-300 text-lg">
                    🛡️
                </div>
                <div>
                    <p class="text-sm font-bold text-white">100% Authorized</p>
                    <p class="text-xs text-slate-400">Guaranteed activation</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ===== CRYPTO MARQUEE BAR ===== -->
<div class="mt-8 rounded-2xl bg-gradient-to-r from-purple-950/60 via-slate-900/90 to-purple-950/60 border border-purple-500/20 p-4 shadow-lg backdrop-blur-md">
    <div class="flex flex-wrap items-center justify-between gap-4 text-xs font-mono">
        <div class="flex items-center gap-2 text-purple-300 font-bold uppercase tracking-wider">
            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
            <span>Accepted Currencies:</span>
        </div>
        <div class="flex flex-wrap items-center gap-4 text-slate-300 font-semibold">
            <span class="flex items-center gap-1.5"><strong class="text-amber-400">₿</strong> Bitcoin</span>
            <span class="flex items-center gap-1.5"><strong class="text-blue-400">Ξ</strong> Ethereum</span>
            <span class="flex items-center gap-1.5"><strong class="text-emerald-400">₮</strong> USDT (TRC20/ERC20)</span>
            <span class="flex items-center gap-1.5"><strong class="text-purple-400">◎</strong> Solana</span>
            <span class="flex items-center gap-1.5"><strong class="text-amber-300">Ð</strong> Dogecoin</span>
            <span class="flex items-center gap-1.5"><strong class="text-cyan-400">💳</strong> Visa / Mastercard</span>
            <span class="flex items-center gap-1.5"><strong class="text-blue-300">🅿️</strong> PayPal</span>
        </div>
    </div>
</div>

<!-- ===== FEATURED GTA 6 EDITIONS & PRODUCTS ===== -->
@php
    $featured = (isset($featuredProducts) && $featuredProducts->isNotEmpty()) ? $featuredProducts : \App\Models\Product::where('active', true)->get();
@endphp
@if($featured && $featured->count())
<section class="mt-16">
    <div class="flex items-center justify-between mb-8">
        <div>
            <p class="text-xs font-bold uppercase tracking-widest text-purple-400">OFFICIAL EDITIONS</p>
            <h2 class="mt-1 text-3xl font-black text-white">Popular Titles & GTA VI Catalog</h2>
        </div>
        <a href="{{ route('products.index') }}" class="text-purple-400 hover:text-purple-300 font-bold text-sm flex items-center gap-1 transition">
            View Full Vault <span>→</span>
        </a>
    </div>
    
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @foreach($featured as $product)
            @include('components.product-card', ['product' => $product])
        @endforeach
    </div>
</section>
@endif

<!-- ===== WHY CHOOSE OUR DIGITAL STORE ===== -->
<section class="mt-20 rounded-3xl bg-slate-900/60 border border-slate-800 p-8 sm:p-12 shadow-2xl backdrop-blur-md">
    <div class="max-w-3xl mx-auto text-center mb-12">
        <p class="text-xs font-bold uppercase tracking-widest text-purple-400">THE PREMIUM ADVANTAGE</p>
        <h2 class="mt-2 text-3xl sm:text-4xl font-black text-white">Automated Digital Key Marketplace</h2>
        <p class="mt-3 text-sm text-slate-400">Built from the ground up for instantaneous fulfillment, absolute security, and frictionless cryptocurrency checkouts.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="rounded-2xl bg-slate-950/70 border border-slate-800 p-6 space-y-3">
            <div class="w-12 h-12 rounded-xl bg-purple-500/20 border border-purple-500/40 flex items-center justify-center text-2xl text-purple-300">
                ⚡
            </div>
            <h3 class="text-lg font-bold text-white">Zero Wait Time</h3>
            <p class="text-xs text-slate-400 leading-relaxed">No manual approvals. Our automated fulfillment engine instantly decrypts and binds keys to your order upon payment confirmation.</p>
        </div>

        <div class="rounded-2xl bg-slate-950/70 border border-slate-800 p-6 space-y-3">
            <div class="w-12 h-12 rounded-xl bg-emerald-500/20 border border-emerald-500/40 flex items-center justify-center text-2xl text-emerald-300">
                🔒
            </div>
            <h3 class="text-lg font-bold text-white">Cryptographic Encryption</h3>
            <p class="text-xs text-slate-400 leading-relaxed">Every digital key is encrypted at rest using industry standard AES-256-CBC, ensuring only you have access to your purchased activation codes.</p>
        </div>

        <div class="rounded-2xl bg-slate-950/70 border border-slate-800 p-6 space-y-3">
            <div class="w-12 h-12 rounded-xl bg-cyan-500/20 border border-cyan-500/40 flex items-center justify-center text-2xl text-cyan-300">
                💎
            </div>
            <h3 class="text-lg font-bold text-white">Native Web3 & Crypto Terminal</h3>
            <p class="text-xs text-slate-400 leading-relaxed">Pay effortlessly with Bitcoin, Ethereum, USDT, USDC or Solana with real-time QR codes and live automated blockchain confirmation polling.</p>
        </div>
    </div>
</section>

<!-- ===== HOMEPAGE SEO FAQ SECTION ===== -->
<section class="mt-20 space-y-6">
    <div class="text-center max-w-2xl mx-auto mb-8">
        <p class="text-xs font-bold uppercase tracking-widest text-purple-400">COMMONLY ASKED QUESTIONS</p>
        <h2 class="mt-1 text-3xl font-black text-white">Everything About GTA VI Game Keys</h2>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div class="rounded-2xl bg-slate-900/60 border border-slate-800 p-6 space-y-2">
            <h3 class="font-bold text-white text-base">How do I receive my GTA 6 PC key?</h3>
            <p class="text-xs text-slate-400 leading-relaxed">Keys are instantly generated and shown in your account dashboard and sent via email immediately upon checkout confirmation.</p>
        </div>
        <div class="rounded-2xl bg-slate-900/60 border border-slate-800 p-6 space-y-2">
            <h3 class="font-bold text-white text-base">Can I pay with Bitcoin, USDT or Crypto?</h3>
            <p class="text-xs text-slate-400 leading-relaxed">Yes, we support native crypto checkout for BTC, ETH, USDT, USDC, and SOL with live blockchain confirmation tracking.</p>
        </div>
        <div class="rounded-2xl bg-slate-900/60 border border-slate-800 p-6 space-y-2">
            <h3 class="font-bold text-white text-base">Are these keys region-locked?</h3>
            <p class="text-xs text-slate-400 leading-relaxed">All products clearly specify their activation region (e.g. Global, North America, Europe). Global keys work anywhere in the world.</p>
        </div>
        <div class="rounded-2xl bg-slate-900/60 border border-slate-800 p-6 space-y-2">
            <h3 class="font-bold text-white text-base">Where do I redeem my game key?</h3>
            <p class="text-xs text-slate-400 leading-relaxed">Redeem directly on the official platform indicated on the product page (Rockstar Games Launcher, Steam, PlayStation Network, Xbox Store).</p>
        </div>
    </div>
</section>
@endsection
