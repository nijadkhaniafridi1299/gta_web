@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Header -->
    <div class="mb-10">
        <p class="text-xs font-bold uppercase tracking-widest text-purple-400">⚡ SECURE ENCRYPTED CHECKOUT</p>
        <h1 class="mt-2 text-4xl font-extrabold text-white">Complete Your Purchase</h1>
        <p class="mt-1 text-sm text-slate-400">Instant digital key delivery via Card, PayPal, or Cryptocurrency</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- MAIN CHECKOUT FORM (2 cols) -->
        <div class="lg:col-span-2">
            <!-- BILLING INFORMATION -->
            <div class="rounded-3xl bg-slate-900/60 border border-slate-800 p-6 sm:p-8 mb-8 shadow-xl backdrop-blur-md">
                <h2 class="text-lg font-bold text-white mb-5 flex items-center gap-2">
                    <span>1. Customer & Billing Info</span>
                </h2>
                
                <form id="checkout-form" method="POST" action="{{ route('checkout.pay') }}" class="space-y-4">
                    @csrf
                    <input type="hidden" name="payment_method" id="selected-payment-method" value="crypto">
                    <input type="hidden" name="crypto_coin" id="selected-crypto-coin" value="BTC">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Full Name</label>
                            <input 
                                type="text" 
                                name="name" 
                                value="{{ auth()->user()->name ?? 'Customer' }}" 
                                required
                                class="w-full px-4 py-3 bg-slate-950/80 border border-slate-700/80 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:border-purple-500 transition text-sm"
                                placeholder="John Doe">
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Email (Key will be delivered here)</label>
                            <input 
                                type="email" 
                                name="email" 
                                value="{{ auth()->user()->email ?? '' }}" 
                                required
                                class="w-full px-4 py-3 bg-slate-950/80 border border-slate-700/80 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:border-purple-500 transition text-sm"
                                placeholder="john@example.com">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Billing Address (Optional)</label>
                        <input 
                            type="text" 
                            name="address" 
                            class="w-full px-4 py-3 bg-slate-950/80 border border-slate-700/80 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:border-purple-500 transition text-sm"
                            placeholder="123 Gaming Boulevard">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">City</label>
                            <input 
                                type="text" 
                                name="city" 
                                class="w-full px-4 py-3 bg-slate-950/80 border border-slate-700/80 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:border-purple-500 transition text-sm"
                                placeholder="Vice City">
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">State / Region</label>
                            <input 
                                type="text" 
                                name="state" 
                                class="w-full px-4 py-3 bg-slate-950/80 border border-slate-700/80 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:border-purple-500 transition text-sm"
                                placeholder="FL">
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Postal Code</label>
                            <input 
                                type="text" 
                                name="postal_code" 
                                class="w-full px-4 py-3 bg-slate-950/80 border border-slate-700/80 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:border-purple-500 transition text-sm"
                                placeholder="33101">
                        </div>
                    </div>
                </form>
            </div>

            <!-- PAYMENT METHOD SELECTION -->
            <div class="rounded-3xl bg-slate-900/60 border border-slate-800 p-6 sm:p-8 mb-8 shadow-xl backdrop-blur-md">
                <h2 class="text-lg font-bold text-white mb-5 flex items-center justify-between">
                    <span>2. Select Payment Gateway</span>
                    <span class="text-xs text-purple-400 font-normal">256-Bit SSL Encrypted</span>
                </h2>
                
                <!-- Payment Method Tabs -->
                <div class="grid grid-cols-3 gap-3 mb-6">
                    <button 
                        type="button"
                        data-method="crypto"
                        class="payment-method-btn px-4 py-3.5 rounded-xl font-bold text-sm transition-all border-2 flex items-center justify-center gap-2 bg-gradient-to-r from-purple-600 to-indigo-600 border-purple-500 text-white shadow-lg shadow-purple-500/25">
                        <span>⚡</span>
                        <span>Crypto (BTC / USDT)</span>
                    </button>
                    <button 
                        type="button"
                        data-method="card"
                        class="payment-method-btn px-4 py-3.5 rounded-xl font-bold text-sm transition-all border-2 flex items-center justify-center gap-2 bg-slate-950 border-slate-800 text-slate-400 hover:border-slate-700">
                        <span>💳</span>
                        <span>Credit / Debit Card</span>
                    </button>
                    <button 
                        type="button"
                        data-method="paypal"
                        class="payment-method-btn px-4 py-3.5 rounded-xl font-bold text-sm transition-all border-2 flex items-center justify-center gap-2 bg-slate-950 border-slate-800 text-slate-400 hover:border-slate-700">
                        <span>🅿️</span>
                        <span>PayPal</span>
                    </button>
                </div>

                <!-- CRYPTO PAYMENT METHOD (DEFAULT / HIGHLIGHTED) -->
                <div id="crypto-method" class="payment-method-content space-y-5">
                    <div class="rounded-2xl bg-purple-950/30 border border-purple-500/30 p-5">
                        <div class="flex items-center justify-between mb-3">
                            <p class="text-sm font-bold text-white flex items-center gap-2">
                                <span class="text-purple-400">⚡</span>
                                <span>Choose Your Cryptocurrency</span>
                            </p>
                            <span class="text-xs bg-purple-500/20 text-purple-300 font-semibold px-2 py-0.5 rounded-full border border-purple-500/30">Instant Confirmation</span>
                        </div>
                        <p class="text-xs text-slate-400 mb-4">Pay securely from any wallet (Coinbase, Trust Wallet, MetaMask, Binance, Phantom). Zero processing fees on crypto.</p>

                        <!-- Coin Selection Grid -->
                        @php
                            $cartSubtotal = array_sum(array_map(fn($x) => $x['price'] * $x['quantity'], $cart));
                            $coinsList = [
                                'BTC' => ['name' => 'Bitcoin', 'icon' => '₿', 'rate' => 68500, 'network' => 'Bitcoin Mainnet'],
                                'ETH' => ['name' => 'Ethereum', 'icon' => 'Ξ', 'rate' => 3550, 'network' => 'ERC-20'],
                                'USDT' => ['name' => 'Tether USDT', 'icon' => '₮', 'rate' => 1.00, 'network' => 'TRC-20 / ERC-20'],
                                'USDC' => ['name' => 'USD Coin', 'icon' => '$', 'rate' => 1.00, 'network' => 'Multi-chain'],
                                'SOL' => ['name' => 'Solana', 'icon' => '◎', 'rate' => 185, 'network' => 'Solana'],
                                'DOGE' => ['name' => 'Dogecoin', 'icon' => 'Ð', 'rate' => 0.16, 'network' => 'Dogecoin'],
                            ];
                        @endphp

                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                            @foreach($coinsList as $code => $coin)
                                @php
                                    $estAmount = round($cartSubtotal / $coin['rate'], ($code === 'USDT' || $code === 'USDC' ? 2 : ($code === 'BTC' ? 6 : 4)));
                                @endphp
                                <label class="coin-option-card cursor-pointer rounded-xl p-3 border-2 transition-all {{ $code === 'BTC' ? 'bg-purple-900/40 border-purple-500 text-white' : 'bg-slate-950/80 border-slate-800 text-slate-300 hover:border-slate-700' }}">
                                    <input type="radio" name="crypto_coin_radio" value="{{ $code }}" {{ $code === 'BTC' ? 'checked' : '' }} class="sr-only coin-radio">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-2">
                                            <span class="w-7 h-7 rounded-full bg-purple-500/20 flex items-center justify-center font-bold text-sm text-purple-300">{{ $coin['icon'] }}</span>
                                            <span class="font-bold text-xs">{{ $code }}</span>
                                        </div>
                                        <span class="text-[10px] text-slate-400 font-mono">{{ $coin['name'] }}</span>
                                    </div>
                                    <div class="mt-2 text-right">
                                        <p class="font-mono font-bold text-xs text-emerald-400">≈ {{ $estAmount }} {{ $code }}</p>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- CARD PAYMENT METHOD -->
                <div id="card-method" class="payment-method-content hidden space-y-4">
                    <p class="text-xs text-slate-400">Enter your card details below. Processed with 256-bit encryption via Stripe.</p>
                    
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Card Number</label>
                        <div id="card-element" class="px-4 py-3 bg-slate-950 border border-slate-700/80 rounded-xl text-white"></div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Expiration Date</label>
                            <div id="card-expiry" class="px-4 py-3 bg-slate-950 border border-slate-700/80 rounded-xl text-white"></div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">CVC / Security Code</label>
                            <div id="card-cvc" class="px-4 py-3 bg-slate-950 border border-slate-700/80 rounded-xl text-white"></div>
                        </div>
                    </div>
                </div>

                <!-- PAYPAL PAYMENT METHOD -->
                <div id="paypal-method" class="payment-method-content hidden space-y-4">
                    <p class="text-xs text-slate-400">Click the PayPal button to complete checkout securely through PayPal.</p>
                    <div id="paypal-button-container" class="py-2"></div>
                </div>
            </div>

            <!-- TRUST BADGES -->
            <div class="rounded-2xl bg-slate-950/60 border border-slate-800 p-4 text-center text-xs text-slate-400 flex flex-wrap items-center justify-around gap-2">
                <span>🔒 256-Bit SSL Encrypted</span>
                <span>⚡ Instant Key Unlock</span>
                <span>🛡️ Money-Back Guarantee</span>
                <span>🎮 100% Authorized Keys</span>
            </div>
        </div>

        <!-- ORDER SUMMARY SIDEBAR (1 col) -->
        <div class="lg:col-span-1">
            <div class="rounded-3xl bg-gradient-to-br from-purple-950/50 via-slate-900/90 to-violet-950/50 border border-purple-500/30 p-6 sm:p-8 sticky top-28 shadow-2xl backdrop-blur-xl">
                <h3 class="text-lg font-bold text-white mb-4 pb-3 border-b border-slate-800">Order Summary</h3>

                <!-- Cart Items -->
                <div class="space-y-3 mb-6 pb-6 border-b border-slate-800 max-h-60 overflow-y-auto pr-1">
                    @php $subtotal = 0; @endphp
                    @foreach($cart as $item)
                        @php $line_total = $item['price'] * $item['quantity']; $subtotal += $line_total; @endphp
                        <div class="flex justify-between items-start text-sm">
                            <div class="flex-1 pr-2">
                                <p class="font-semibold text-white leading-snug">{{ $item['name'] }}</p>
                                <p class="text-xs text-slate-400">Qty: {{ $item['quantity'] }}</p>
                            </div>
                            <p class="font-bold font-mono text-white whitespace-nowrap">${{ number_format($line_total, 2) }}</p>
                        </div>
                    @endforeach
                </div>

                <!-- Breakdown -->
                <div class="space-y-2.5 mb-6 text-sm">
                    <div class="flex justify-between text-slate-300">
                        <span>Subtotal</span>
                        <span class="font-mono">${{ number_format($subtotal, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-slate-300">
                        <span>Digital Delivery</span>
                        <span class="text-emerald-400 font-semibold">FREE (Instant)</span>
                    </div>
                </div>

                <!-- Total -->
                <div class="mb-6 pt-4 border-t border-slate-800">
                    <div class="flex justify-between items-baseline">
                        <span class="text-slate-300 font-semibold">Total Amount</span>
                        <span class="text-3xl font-black font-mono text-purple-300">${{ number_format($subtotal, 2) }}</span>
                    </div>
                </div>

                <!-- Main Submit Button -->
                <button 
                    id="pay-button"
                    type="button"
                    class="w-full py-4 px-6 bg-gradient-to-r from-purple-500 via-violet-600 to-pink-500 hover:from-purple-600 hover:to-pink-600 text-white font-extrabold text-base rounded-xl transition-all shadow-xl shadow-purple-500/30 hover:shadow-purple-500/50 hover:-translate-y-0.5 flex items-center justify-center gap-2">
                    <span id="pay-button-icon">⚡</span>
                    <span id="pay-button-text">Proceed with Crypto Payment</span>
                </button>

                <p class="text-[11px] text-slate-400 text-center mt-4">
                    🎮 Your digital game keys will unlock immediately after payment.
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Stripe Script -->
<script src="https://js.stripe.com/v3/"></script>

<script>
    let currentMethod = 'crypto';

    // Handle Payment Method Switcher Tabs
    document.querySelectorAll('.payment-method-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            currentMethod = this.dataset.method;
            document.getElementById('selected-payment-method').value = currentMethod;
            
            // Tab styles
            document.querySelectorAll('.payment-method-btn').forEach(b => {
                b.classList.remove('bg-gradient-to-r', 'from-purple-600', 'to-indigo-600', 'border-purple-500', 'text-white', 'shadow-lg', 'shadow-purple-500/25');
                b.classList.add('bg-slate-950', 'border-slate-800', 'text-slate-400');
            });
            this.classList.remove('bg-slate-950', 'border-slate-800', 'text-slate-400');
            this.classList.add('bg-gradient-to-r', 'from-purple-600', 'to-indigo-600', 'border-purple-500', 'text-white', 'shadow-lg', 'shadow-purple-500/25');

            // Content Visibility
            document.querySelectorAll('.payment-method-content').forEach(c => c.classList.add('hidden'));
            document.getElementById(currentMethod + '-method')?.classList.remove('hidden');

            // Button label updates
            const payBtnText = document.getElementById('pay-button-text');
            const payBtnIcon = document.getElementById('pay-button-icon');
            if (currentMethod === 'crypto') {
                const coin = document.getElementById('selected-crypto-coin').value || 'BTC';
                payBtnText.textContent = `Proceed with ${coin} Payment`;
                payBtnIcon.textContent = '⚡';
            } else if (currentMethod === 'card') {
                payBtnText.textContent = 'Pay ${{ number_format($subtotal, 2) }} with Card';
                payBtnIcon.textContent = '💳';
            } else {
                payBtnText.textContent = 'Continue to PayPal';
                payBtnIcon.textContent = '🅿️';
            }
        });
    });

    // Handle Coin Card Selection
    document.querySelectorAll('.coin-radio').forEach(radio => {
        radio.addEventListener('change', function() {
            document.getElementById('selected-crypto-coin').value = this.value;
            document.querySelectorAll('.coin-option-card').forEach(card => {
                card.classList.remove('bg-purple-900/40', 'border-purple-500', 'text-white');
                card.classList.add('bg-slate-950/80', 'border-slate-800', 'text-slate-300');
            });
            this.closest('.coin-option-card').classList.remove('bg-slate-950/80', 'border-slate-800', 'text-slate-300');
            this.closest('.coin-option-card').classList.add('bg-purple-900/40', 'border-purple-500', 'text-white');

            document.getElementById('pay-button-text').textContent = `Proceed with ${this.value} Payment`;
        });
    });

    // Initialize Stripe Elements
    const stripeKey = '{{ config("services.stripe.public") }}';
    let cardElement = null;
    if (stripeKey && stripeKey !== 'pk_test_YOUR_STRIPE_PUBLIC_KEY_HERE') {
        const stripe = Stripe(stripeKey);
        const elements = stripe.elements();
        cardElement = elements.create('card');
        cardElement.mount('#card-element');
        const cardExpiry = elements.create('cardExpiry');
        cardExpiry.mount('#card-expiry');
        const cardCvc = elements.create('cardCvc');
        cardCvc.mount('#card-cvc');
    }

    // Pay Button Click
    document.getElementById('pay-button').addEventListener('click', async function() {
        const form = document.getElementById('checkout-form');
        
        if (!form.reportValidity()) {
            return;
        }

        document.getElementById('selected-payment-method').value = currentMethod;

        if (currentMethod === 'crypto') {
            this.disabled = true;
            this.innerHTML = '<span>⚡</span> <span>Generating Crypto Terminal...</span>';
            form.submit();
        } else if (currentMethod === 'card') {
            if (!cardElement) {
                // If stripe keys not live, submit anyway
                form.submit();
                return;
            }
            this.disabled = true;
            this.textContent = 'Processing Card Payment...';
            // Stripe processing
            form.submit();
        } else {
            form.submit();
        }
    });
</script>
@endsection
