@extends('layouts.app')

@section('title', 'Help Center & FAQ — GTA 6 Key Activation & Crypto Support')
@section('meta_description', 'Everything you need to know about buying Grand Theft Auto VI (GTA 6) digital keys, crypto payments, instant activation on Rockstar Games Launcher/Steam/PS5, and 24/7 support.')
@section('meta_keywords', 'GTA 6 key activation guide, how to redeem GTA 6 key, buy games with bitcoin guide, instant digital key FAQ, Rockstar key redemption')

@section('schema')
@php
    $helpFaqSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'FAQPage',
        'mainEntity' => [
            [
                '@type' => 'Question',
                'name' => 'How quickly do I receive my GTA 6 game key after payment?',
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => 'Your GTA 6 activation key is dispatched instantaneously (within 0 to 5 seconds) after payment confirmation. The key appears directly on your screen on your orders page and is also sent immediately to your registered email address.',
                ],
            ],
            [
                '@type' => 'Question',
                'name' => 'Which cryptocurrencies are supported for purchasing game keys?',
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => 'We accept Bitcoin (BTC), Ethereum (ETH), Tether (USDT TRC20/ERC20), USD Coin (USDC), Solana (SOL), and Dogecoin (DOGE), alongside traditional Credit and Debit cards with zero extra checkout fees.',
                ],
            ],
            [
                '@type' => 'Question',
                'name' => 'How do I redeem my Grand Theft Auto VI PC key on Rockstar Games Launcher?',
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => "1. Download and open the Rockstar Games Launcher.\n2. Sign in with your Rockstar Games Social Club account.\n3. Click your profile avatar in the top-right corner and select 'Redeem Code'.\n4. Paste the digital key from your GTA 6 Vault order and click 'Check' to bind the game to your account permanently.",
                ],
            ],
            [
                '@type' => 'Question',
                'name' => 'Are your digital game keys genuine and authorized?',
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => 'Yes, 100% of our activation keys are genuine, sourced through authorized distributor channels, and guaranteed for immediate, lifelong activation in your chosen region without regional VPN workarounds.',
                ],
            ],
            [
                '@type' => 'Question',
                'name' => 'What should I do if a cryptocurrency transaction is delayed on the blockchain?',
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => 'Our payment terminal checks network confirmations in real time. For fast transactions, we recommend USDT (TRC20) or Solana (SOL) which confirm in under 30 seconds. If a Bitcoin or Ethereum transaction is waiting on miner confirmations, the terminal will automatically detect and fulfill the order as soon as network confirmations complete.',
                ],
            ],
            [
                '@type' => 'Question',
                'name' => 'Can I get a refund if I purchased the wrong platform edition?',
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => 'Because digital game keys are sensitive cryptographic credentials, we can offer refunds or replacements for keys that have not yet been decrypted/revealed or in case of verified activation issues. Please reach out via our 24/7 Contact page with your order ID.',
                ],
            ],
        ],
    ];

    $helpBreadcrumbs = [
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
                'name' => 'Help & Support',
                'item' => route('help'),
            ],
        ],
    ];
@endphp
<script type="application/ld+json">
{!! json_encode($helpFaqSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
</script>
<script type="application/ld+json">
{!! json_encode($helpBreadcrumbs, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
</script>
@endsection

@section('content')
<div class="max-w-4xl mx-auto space-y-10 pb-16">
    <!-- Header -->
    <div class="text-center space-y-3">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-purple-500/10 border border-purple-500/20 text-purple-300 text-xs font-bold uppercase tracking-wider">
            <span>24/7 Support & Knowledge Base</span>
        </div>
        <h1 class="text-3xl sm:text-5xl font-black text-white tracking-tight">Help Center & Activation FAQ</h1>
        <p class="text-slate-400 text-sm sm:text-base max-w-2xl mx-auto">
            Everything you need to know about instant digital delivery, cryptocurrency checkout, and redeeming your game keys.
        </p>
    </div>

    <!-- Quick Support Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        <div class="rounded-2xl bg-slate-900/60 border border-slate-800 p-6 space-y-2 backdrop-blur-sm">
            <div class="w-10 h-10 rounded-xl bg-purple-500/20 text-purple-400 flex items-center justify-center font-bold text-lg">⚡</div>
            <h3 class="text-base font-bold text-white">Instant Fulfillment</h3>
            <p class="text-xs text-slate-400 leading-relaxed">Keys are decrypted and delivered to your screen & email in 0-5 seconds after payment.</p>
        </div>
        <div class="rounded-2xl bg-slate-900/60 border border-slate-800 p-6 space-y-2 backdrop-blur-sm">
            <div class="w-10 h-10 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center font-bold text-lg">🪙</div>
            <h3 class="text-base font-bold text-white">Web3 & Crypto Help</h3>
            <p class="text-xs text-slate-400 leading-relaxed">Support for BTC, USDT, SOL, ETH with real-time on-chain confirmation tracking.</p>
        </div>
        <div class="rounded-2xl bg-slate-900/60 border border-slate-800 p-6 space-y-2 backdrop-blur-sm">
            <div class="w-10 h-10 rounded-xl bg-cyan-500/20 text-cyan-400 flex items-center justify-center font-bold text-lg">🛡️</div>
            <h3 class="text-base font-bold text-white">100% Genuine Keys</h3>
            <p class="text-xs text-slate-400 leading-relaxed">All digital codes are official publisher releases with guaranteed lifetime activation.</p>
        </div>
    </div>

    <!-- FAQ Accordion List -->
    <div class="space-y-4">
        <h2 class="text-2xl font-black text-white tracking-tight mb-6">Frequently Asked Questions</h2>

        <!-- Q1 -->
        <div class="rounded-2xl bg-slate-900/70 border border-slate-800 p-6 space-y-2">
            <h3 class="text-base font-bold text-white flex items-center gap-2">
                <span class="text-purple-400 font-mono">01.</span>
                <span>How quickly do I receive my GTA 6 game key after payment?</span>
            </h3>
            <p class="text-sm text-slate-300 leading-relaxed pl-7">
                Your GTA 6 activation key is dispatched instantaneously (within 0 to 5 seconds) after payment confirmation. The key appears directly on your screen on your orders page and is also sent immediately to your registered email address.
            </p>
        </div>

        <!-- Q2 -->
        <div class="rounded-2xl bg-slate-900/70 border border-slate-800 p-6 space-y-2">
            <h3 class="text-base font-bold text-white flex items-center gap-2">
                <span class="text-purple-400 font-mono">02.</span>
                <span>Which cryptocurrencies and payment options are supported?</span>
            </h3>
            <p class="text-sm text-slate-300 leading-relaxed pl-7">
                We accept Bitcoin (BTC), Ethereum (ETH), Tether (USDT TRC20/ERC20), USD Coin (USDC), Solana (SOL), and Dogecoin (DOGE), alongside traditional Credit and Debit cards with zero extra checkout fees.
            </p>
        </div>

        <!-- Q3 -->
        <div class="rounded-2xl bg-slate-900/70 border border-slate-800 p-6 space-y-2">
            <h3 class="text-base font-bold text-white flex items-center gap-2">
                <span class="text-purple-400 font-mono">03.</span>
                <span>How do I redeem my Grand Theft Auto VI PC key?</span>
            </h3>
            <div class="text-sm text-slate-300 leading-relaxed pl-7 space-y-1">
                <p>1. Open the <strong>Rockstar Games Launcher</strong> or Steam client.</p>
                <p>2. Sign in with your official account.</p>
                <p>3. Select <strong>'Redeem Code'</strong> from the account menu.</p>
                <p>4. Paste your purchased 16/25 digit digital activation key to bind the game permanently to your library.</p>
            </div>
        </div>

        <!-- Q4 -->
        <div class="rounded-2xl bg-slate-900/70 border border-slate-800 p-6 space-y-2">
            <h3 class="text-base font-bold text-white flex items-center gap-2">
                <span class="text-purple-400 font-mono">04.</span>
                <span>Are your digital game keys genuine and authorized?</span>
            </h3>
            <p class="text-sm text-slate-300 leading-relaxed pl-7">
                Yes, 100% of our activation keys are genuine, sourced through authorized distributor channels, and guaranteed for immediate, lifelong activation in your chosen region without regional VPN workarounds.
            </p>
        </div>

        <!-- Q5 -->
        <div class="rounded-2xl bg-slate-900/70 border border-slate-800 p-6 space-y-2">
            <h3 class="text-base font-bold text-white flex items-center gap-2">
                <span class="text-purple-400 font-mono">05.</span>
                <span>What should I do if a cryptocurrency payment is pending?</span>
            </h3>
            <p class="text-sm text-slate-300 leading-relaxed pl-7">
                Our payment terminal checks network confirmations in real time. For ultra-fast transactions, we recommend USDT (TRC20) or Solana (SOL). If a Bitcoin transaction is waiting on blockchain confirmations, our engine polls the ledger and releases your key as soon as confirmations complete.
            </p>
        </div>

        <!-- Q6 -->
        <div class="rounded-2xl bg-slate-900/70 border border-slate-800 p-6 space-y-2">
            <h3 class="text-base font-bold text-white flex items-center gap-2">
                <span class="text-purple-400 font-mono">06.</span>
                <span>How does the Money-Back & Replacement Guarantee work?</span>
            </h3>
            <p class="text-sm text-slate-300 leading-relaxed pl-7">
                All unrevealed keys and verified activation errors are eligible for immediate replacement or full refund within 14 days of purchase. Contact our team anytime through our support desk.
            </p>
        </div>
    </div>

    <!-- Need More Help Banner -->
    <div class="rounded-3xl bg-gradient-to-r from-purple-900/40 via-slate-900 to-pink-900/30 border border-purple-500/30 p-8 text-center space-y-4">
        <h3 class="text-xl font-bold text-white">Still have questions or need assistance with your order?</h3>
        <p class="text-slate-400 text-sm max-w-xl mx-auto">Our dedicated customer support team is available 24/7 to help resolve order inquiries, key activations, and payment confirmations.</p>
        <a href="{{ route('contact') }}" class="button-primary inline-flex items-center gap-2 !px-8 !py-3.5 !rounded-xl font-bold">
            <span>Contact Support Desk</span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
            </svg>
        </a>
    </div>
</div>
@endsection
