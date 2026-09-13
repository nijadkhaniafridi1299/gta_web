@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-4">
    <!-- Breadcrumb -->
    <nav class="text-sm text-slate-400 mb-6 flex items-center gap-2">
        <a href="{{ route('home') }}" class="hover:text-white transition">Home</a>
        <span>/</span>
        <a href="{{ route('checkout') }}" class="hover:text-white transition">Checkout</a>
        <span>/</span>
        <span class="text-purple-400 font-medium">Crypto Terminal</span>
    </nav>

    <!-- Header Status Banner -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-purple-900/60 via-slate-900/90 to-violet-950/70 border border-purple-500/40 p-6 sm:p-8 mb-8 shadow-2xl backdrop-blur-xl">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-purple-600 to-pink-500 flex items-center justify-center text-3xl shadow-lg shadow-purple-500/30 animate-pulse">
                    ⚡
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-500/20 text-amber-300 border border-amber-500/30 animate-pulse">
                            <span class="w-2 h-2 rounded-full bg-amber-400 mr-1.5 animate-ping"></span>
                            AWAITING BLOCKCHAIN DEPOSIT
                        </span>
                        <span class="text-xs text-slate-400">Order #{{ $order->order_number ?? 'N/A' }}</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black text-white mt-1">Cryptocurrency Payment Terminal</h1>
                </div>
            </div>

            <!-- Countdown Timer -->
            <div class="bg-slate-950/80 border border-slate-700/60 rounded-xl px-5 py-3 text-right">
                <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Rate Locked For</p>
                <p id="countdown-timer" class="text-2xl font-mono font-black text-purple-300">59:59</p>
            </div>
        </div>
    </div>

    <!-- MAIN TERMINAL GRID -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- LEFT: QR Code & Wallet Address Box (7 cols) -->
        <div class="lg:col-span-7 space-y-6">
            <div class="rounded-3xl bg-slate-900/70 border border-purple-500/20 p-6 sm:p-8 shadow-xl backdrop-blur-md">
                
                <!-- Coin Header -->
                <div class="flex items-center justify-between pb-6 border-b border-slate-800">
                    <div class="flex items-center gap-3">
                        <div class="w-11 h-11 rounded-full bg-purple-500/20 border border-purple-500/40 flex items-center justify-center text-xl font-bold text-white">
                            {{ $supportedCoins[$payment->currency_code]['icon'] ?? '🪙' }}
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-white">{{ $supportedCoins[$payment->currency_code]['name'] ?? $payment->currency_code }}</h2>
                            <p class="text-xs text-purple-300">{{ $coinNetwork }}</p>
                        </div>
                    </div>

                    <div class="text-right">
                        <p class="text-xs text-slate-400 font-medium">Send Exact Amount</p>
                        <p class="text-xl sm:text-2xl font-black font-mono text-emerald-400" id="crypto-amount-text">{{ $payment->amount }} {{ $payment->currency_code }}</p>
                        <p class="text-xs text-slate-500">≈ ${{ number_format($payment->usd_amount, 2) }} USD</p>
                    </div>
                </div>

                <!-- QR Code Display -->
                <div class="py-6 flex flex-col items-center justify-center">
                    <div class="p-4 bg-white rounded-2xl shadow-2xl border-4 border-purple-500/30 transition transform hover:scale-105 duration-300">
                        @php
                            $qrData = $payment->payment_info['qr_uri'] ?? $payment->wallet_address;
                            $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=220x220&margin=10&data=' . urlencode($qrData);
                        @endphp
                        <img src="{{ $qrUrl }}" alt="Crypto QR Code" class="w-48 h-48 sm:w-52 sm:h-52 object-contain" />
                    </div>
                    <p class="text-xs text-slate-400 mt-3 text-center">
                        📲 Scan with any <strong>{{ $payment->currency_code }}</strong> wallet (Trust Wallet, Binance, MetaMask, Phantom, Exodus, etc.)
                    </p>
                </div>

                <!-- Copy Address Box -->
                <div class="space-y-3">
                    <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider">Deposit {{ $payment->currency_code }} Address</label>
                    <div class="flex items-center gap-2 bg-slate-950 border border-slate-700/70 rounded-xl p-2.5">
                        <input 
                            id="wallet-address" 
                            type="text" 
                            readonly 
                            value="{{ $payment->wallet_address }}"
                            class="flex-1 bg-transparent font-mono text-xs sm:text-sm text-purple-200 select-all outline-none px-2">
                        <button 
                            id="copy-btn"
                            type="button" 
                            onclick="copyAddress()" 
                            class="px-4 py-2 bg-purple-600 hover:bg-purple-500 active:bg-purple-700 text-white text-xs font-bold rounded-lg transition-all flex items-center gap-1.5 shadow-md shadow-purple-600/30 whitespace-nowrap">
                            <span id="copy-icon">📋</span>
                            <span id="copy-label">Copy</span>
                        </button>
                    </div>
                </div>

                <!-- Copy Amount Box -->
                <div class="mt-4 space-y-2">
                    <div class="flex items-center justify-between text-xs text-slate-400">
                        <span>Amount to Send:</span>
                        <button type="button" onclick="copyAmount()" class="text-purple-400 hover:text-purple-300 underline font-mono font-semibold">Copy Exact Amount</button>
                    </div>
                </div>

                <!-- Blockchain Status Steps -->
                <div class="mt-8 pt-6 border-t border-slate-800 space-y-4">
                    <p class="text-xs font-bold text-slate-300 uppercase tracking-wider flex items-center justify-between">
                        <span>Live Confirmation Status</span>
                        <span id="confirmations-badge" class="text-purple-400 font-mono">0 / 3 Confirmations</span>
                    </p>
                    
                    <div class="grid grid-cols-3 gap-2">
                        <div id="step-1" class="rounded-xl p-3 bg-purple-950/40 border border-purple-500/40 text-center transition-all">
                            <p class="text-xs font-bold text-purple-300">1. Sent</p>
                            <p class="text-[10px] text-slate-400 mt-0.5">MemPool</p>
                        </div>
                        <div id="step-2" class="rounded-xl p-3 bg-slate-950/50 border border-slate-800 text-center transition-all">
                            <p class="text-xs font-bold text-slate-400" id="step-2-text">2. Validating</p>
                            <p class="text-[10px] text-slate-500 mt-0.5">1-2 Blocks</p>
                        </div>
                        <div id="step-3" class="rounded-xl p-3 bg-slate-950/50 border border-slate-800 text-center transition-all">
                            <p class="text-xs font-bold text-slate-400" id="step-3-text">3. Complete</p>
                            <p class="text-[10px] text-slate-500 mt-0.5">Keys Delivered</p>
                        </div>
                    </div>
                </div>

                <!-- SIMULATE INSTANT TEST PAYMENT BUTTON -->
                <div class="mt-8 pt-6 border-t border-purple-500/20">
                    <form method="POST" action="{{ route('crypto.simulate', $payment->charge_id) }}" id="simulate-form">
                        @csrf
                        <button 
                            type="submit" 
                            id="simulate-btn"
                            class="w-full py-3.5 px-6 rounded-xl bg-gradient-to-r from-emerald-500 via-teal-500 to-cyan-500 hover:from-emerald-400 hover:to-cyan-400 text-slate-950 font-black text-sm transition-all shadow-lg shadow-emerald-500/25 hover:shadow-emerald-500/40 hover:-translate-y-0.5 flex items-center justify-center gap-2">
                            <span>⚡</span>
                            <span>Simulate Instant Blockchain Confirmation (Demo Mode)</span>
                        </button>
                    </form>
                    <p class="text-[11px] text-slate-400 text-center mt-2">
                        💡 Test without real funds: click above to immediately verify deposit and unlock game keys.
                    </p>
                </div>

            </div>
        </div>

        <!-- RIGHT: Order Summary & Security Guarantees (5 cols) -->
        <div class="lg:col-span-5 space-y-6">
            
            <!-- Order Summary Card -->
            <div class="rounded-3xl bg-slate-900/70 border border-slate-700/50 p-6 shadow-xl backdrop-blur-md">
                <h3 class="text-base font-bold text-white mb-4 pb-3 border-b border-slate-800 flex items-center justify-between">
                    <span>Order Summary</span>
                    <span class="text-xs font-mono text-purple-400">#{{ $order->order_number }}</span>
                </h3>

                <div class="space-y-3 mb-6">
                    @foreach($order->items as $item)
                        <div class="flex justify-between items-start text-sm">
                            <div class="pr-2">
                                <p class="font-semibold text-white leading-snug">{{ $item->product_name }}</p>
                                <p class="text-xs text-slate-400">Qty: {{ $item->quantity }} • Platform: {{ $item->product->platform ?? 'PC' }}</p>
                            </div>
                            <span class="font-mono font-bold text-white">${{ number_format($item->unit_price * $item->quantity, 2) }}</span>
                        </div>
                    @endforeach
                </div>

                <div class="pt-4 border-t border-slate-800 space-y-2 text-sm">
                    <div class="flex justify-between text-slate-300">
                        <span>Subtotal</span>
                        <span class="font-mono">${{ number_format($order->subtotal, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-slate-300">
                        <span>Network Fee</span>
                        <span class="text-emerald-400 font-semibold">$0.00 (Merchant Covers)</span>
                    </div>
                    <div class="flex justify-between text-base font-bold text-white pt-2 border-t border-slate-800">
                        <span>Total Due</span>
                        <span class="text-xl font-mono text-purple-300">${{ number_format($order->total, 2) }} USD</span>
                    </div>
                </div>

                <div class="mt-6 pt-4 border-t border-slate-800/80">
                    <a href="{{ route('crypto.verify', $payment->charge_id) }}" class="block w-full text-center py-3 bg-slate-800 hover:bg-slate-700 text-white font-semibold text-xs rounded-xl transition border border-slate-700">
                        🔄 Refresh Blockchain Status
                    </a>
                </div>
            </div>

            <!-- Instant Delivery & Guarantees -->
            <div class="rounded-3xl bg-gradient-to-br from-purple-950/40 to-slate-900/60 border border-purple-500/30 p-6 space-y-4 shadow-xl">
                <h4 class="text-sm font-bold text-white flex items-center gap-2">
                    <span class="text-purple-400">🛡️</span>
                    <span>Automated Smart Delivery</span>
                </h4>
                
                <ul class="text-xs text-slate-300 space-y-3">
                    <li class="flex items-start gap-2.5">
                        <span class="text-emerald-400 font-bold">✓</span>
                        <span><strong>Instant Digital Key Dispatch:</strong> Once blockchain transaction is confirmed, your encrypted game keys appear automatically on screen.</span>
                    </li>
                    <li class="flex items-start gap-2.5">
                        <span class="text-emerald-400 font-bold">✓</span>
                        <span><strong>100% Legit Official Keys:</strong> Guaranteed activation on Steam, PlayStation, Xbox & Rockstar Launcher.</span>
                    </li>
                    <li class="flex items-start gap-2.5">
                        <span class="text-emerald-400 font-bold">✓</span>
                        <span><strong>24/7 Automated Engine:</strong> No waiting for manual review. Payments process 24/7 around the globe.</span>
                    </li>
                </ul>
            </div>

            <!-- Need Help -->
            <div class="rounded-2xl bg-slate-950/60 border border-slate-800 p-4 text-center text-xs text-slate-400">
                <p>Have questions about crypto payments? <a href="{{ route('help') }}" class="text-purple-400 hover:underline">Read Payment Guide</a> or <a href="{{ route('contact') }}" class="text-purple-400 hover:underline">Contact Support</a>.</p>
            </div>

        </div>
    </div>
</div>

<script>
    // Copy Address Handler
    function copyAddress() {
        const addr = document.getElementById('wallet-address').value;
        navigator.clipboard.writeText(addr).then(() => {
            const label = document.getElementById('copy-label');
            const icon = document.getElementById('copy-icon');
            label.textContent = 'Copied!';
            icon.textContent = '✓';
            document.getElementById('copy-btn').classList.remove('bg-purple-600');
            document.getElementById('copy-btn').classList.add('bg-emerald-600');

            setTimeout(() => {
                label.textContent = 'Copy';
                icon.textContent = '📋';
                document.getElementById('copy-btn').classList.remove('bg-emerald-600');
                document.getElementById('copy-btn').classList.add('bg-purple-600');
            }, 2000);
        });
    }

    // Copy Amount Handler
    function copyAmount() {
        const amount = '{{ $payment->amount }}';
        navigator.clipboard.writeText(amount).then(() => {
            alert('Copied crypto amount: ' + amount + ' {{ $payment->currency_code }}');
        });
    }

    // Live Countdown Timer (60 minutes)
    let timeLeft = 3599;
    const timerElement = document.getElementById('countdown-timer');
    const timerInterval = setInterval(() => {
        const minutes = Math.floor(timeLeft / 60);
        const seconds = timeLeft % 60;
        timerElement.textContent = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
        if (timeLeft <= 0) {
            clearInterval(timerInterval);
            timerElement.textContent = "EXPIRED";
        }
        timeLeft--;
    }, 1000);

    // Live AJAX Status Polling
    const chargeId = '{{ $payment->charge_id }}';
    const statusUrl = '{{ route("crypto.status") }}?charge_id=' + chargeId;

    let pollInterval = setInterval(async () => {
        try {
            const res = await fetch(statusUrl);
            const data = await res.json();

            if (data.confirmations > 0) {
                document.getElementById('confirmations-badge').textContent = data.confirmations + ' / 3 Confirmations';
                document.getElementById('step-2').classList.add('bg-purple-950/40', 'border-purple-500/40');
                document.getElementById('step-2-text').classList.add('text-purple-300');
            }

            if (data.is_confirmed && data.redirect_url) {
                clearInterval(pollInterval);
                document.getElementById('step-3').classList.add('bg-emerald-950/50', 'border-emerald-500/60');
                document.getElementById('step-3-text').classList.add('text-emerald-300');
                
                // Show celebration and redirect
                setTimeout(() => {
                    window.location.href = data.redirect_url;
                }, 800);
            }
        } catch (e) {
            console.error('Error polling status:', e);
        }
    }, 3500);
</script>
@endsection
