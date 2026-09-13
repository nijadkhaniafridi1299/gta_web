@extends('layouts.app')

@section('content')
<!-- SUCCESS BANNER -->
<div class="rounded-3xl bg-gradient-to-r from-emerald-950/80 via-slate-900/90 to-purple-950/80 border border-emerald-500/40 p-6 sm:p-8 mb-8 shadow-2xl backdrop-blur-xl">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-emerald-500/20 border border-emerald-500/40 flex items-center justify-center text-3xl text-emerald-400 shadow-lg shadow-emerald-500/20">
                ✓
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                        PAYMENT CONFIRMED
                    </span>
                    <span class="text-xs text-slate-400">Order #{{ $order->order_number }}</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-black text-white mt-1">Your Digital Game Keys Are Ready!</h1>
            </div>
        </div>

        <button onclick="window.print()" class="px-5 py-2.5 bg-slate-800 hover:bg-slate-700 text-white text-xs font-bold rounded-xl transition border border-slate-700 flex items-center gap-2">
            <span>🖨️</span>
            <span>Print Receipt</span>
        </button>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
    <!-- MAIN CONTENT (8 cols) -->
    <div class="lg:col-span-8 space-y-8">
        
        <!-- DIGITAL KEYS DELIVERY TERMINAL -->
        <div class="rounded-3xl bg-gradient-to-br from-purple-950/40 via-slate-900/90 to-slate-950 border border-purple-500/30 p-6 sm:p-8 shadow-2xl backdrop-blur-md">
            <div class="flex items-center justify-between pb-6 border-b border-slate-800">
                <div>
                    <h2 class="text-xl font-black text-white flex items-center gap-2">
                        <span>🎮 Allocated Digital Keys</span>
                    </h2>
                    <p class="text-xs text-purple-300 mt-0.5">Encrypted key securely stored in your account</p>
                </div>
                <span class="text-xs font-mono font-bold px-3 py-1 bg-purple-500/20 text-purple-300 rounded-full border border-purple-500/30">
                    {{ $order->keys->count() }} {{ Str::plural('Key', $order->keys->count()) }}
                </span>
            </div>

            @if($order->keys->count() > 0)
                <div class="space-y-6 mt-6">
                    @foreach($order->keys as $index => $key)
                        <div class="rounded-2xl bg-slate-950/80 border border-purple-500/30 p-6 space-y-4 shadow-lg">
                            <div class="flex flex-wrap items-center justify-between gap-2">
                                <div>
                                    <h3 class="font-bold text-white text-base">{{ $key->product->name ?? 'Digital Game Key' }}</h3>
                                    <p class="text-xs text-slate-400">Platform: <strong class="text-purple-300">{{ $key->product->platform ?? 'PC' }}</strong> • Region: <strong class="text-emerald-400">{{ $key->product->region ?? 'Global' }}</strong></p>
                                </div>
                                <span class="px-2.5 py-1 bg-emerald-500/20 text-emerald-400 text-xs font-bold rounded-lg border border-emerald-500/30">✓ Active & Unused</span>
                            </div>

                            <!-- Key Box -->
                            <div class="bg-slate-900 border border-slate-800 rounded-xl p-4 flex flex-col sm:flex-row items-center justify-between gap-4">
                                <div class="w-full sm:w-auto">
                                    <p class="text-[10px] uppercase font-bold text-slate-400 tracking-wider mb-1">Activation Code / CD-Key</p>
                                    <div class="font-mono text-lg sm:text-xl font-black text-white tracking-wider select-all" id="key-text-{{ $key->id }}">
                                        {{ $key->key }}
                                    </div>
                                </div>

                                <div class="flex items-center gap-2 w-full sm:w-auto">
                                    <button 
                                        type="button"
                                        onclick="copyKey('key-text-{{ $key->id }}', this)" 
                                        class="flex-1 sm:flex-initial px-4 py-2.5 bg-purple-600 hover:bg-purple-500 text-white font-bold text-xs rounded-lg transition shadow-md shadow-purple-600/30 flex items-center justify-center gap-1.5 whitespace-nowrap">
                                        <span>📋</span>
                                        <span>Copy Code</span>
                                    </button>
                                    <button 
                                        type="button"
                                        onclick="downloadKeyTxt('{{ $key->product->name ?? 'GTA_6' }}', '{{ $key->key }}')" 
                                        class="px-3 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-200 font-bold text-xs rounded-lg transition border border-slate-700 flex items-center justify-center gap-1">
                                        <span>⬇️</span>
                                        <span>Download</span>
                                    </button>
                                </div>
                            </div>

                            <p class="text-[11px] text-slate-400">
                                💡 Redeem this key in <strong>{{ $key->product->drm_client ?? 'Platform Launcher' }}</strong>. Once activated, the game will be permanently linked to your account.
                            </p>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-10 text-slate-400">
                    <p class="text-base font-bold text-white mb-1">📦 Keys are being generated...</p>
                    <p class="text-xs">Please refresh this page in a moment to view your digital codes.</p>
                </div>
            @endif
        </div>

        <!-- ORDER CONFIRMATION DETAILS -->
        <div class="rounded-3xl bg-slate-900/60 border border-slate-800 p-6 sm:p-8 shadow-xl backdrop-blur-md">
            <h2 class="text-lg font-bold text-white mb-6 pb-3 border-b border-slate-800">Order & Payment Verification</h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm mb-6">
                <div>
                    <span class="text-xs text-slate-400 uppercase font-bold tracking-wider">Order Number</span>
                    <p class="font-mono font-bold text-white text-base mt-0.5">{{ $order->order_number }}</p>
                </div>
                <div>
                    <span class="text-xs text-slate-400 uppercase font-bold tracking-wider">Customer</span>
                    <p class="font-bold text-white text-base mt-0.5">{{ $order->customer_name }} ({{ $order->customer_email }})</p>
                </div>
                <div>
                    <span class="text-xs text-slate-400 uppercase font-bold tracking-wider">Payment Method</span>
                    <p class="font-semibold text-purple-300 mt-0.5">{{ $order->payment_method_label }}</p>
                </div>
                <div>
                    <span class="text-xs text-slate-400 uppercase font-bold tracking-wider">Completed At</span>
                    <p class="text-slate-200 mt-0.5">{{ $order->paid_at ? $order->paid_at->format('M d, Y \a\t g:i A') : $order->created_at->format('M d, Y') }}</p>
                </div>
            </div>

            <!-- If Crypto Payment was used, show blockchain transaction proof -->
            @if($crypto = $order->latestCryptoPayment)
                <div class="rounded-2xl bg-slate-950/80 border border-purple-500/20 p-4 space-y-2 mt-4 text-xs font-mono">
                    <p class="font-bold text-purple-300 flex items-center gap-1.5">
                        <span>⚡</span> Blockchain Transaction Receipt
                    </p>
                    <div class="flex justify-between text-slate-400">
                        <span>Currency / Amount:</span>
                        <span class="text-white font-bold">{{ $crypto->amount }} {{ $crypto->currency_code }} (${{ number_format($crypto->usd_amount, 2) }} USD)</span>
                    </div>
                    <div class="flex justify-between text-slate-400">
                        <span>Deposit Address:</span>
                        <span class="text-slate-300 truncate max-w-[200px] sm:max-w-xs">{{ $crypto->wallet_address }}</span>
                    </div>
                    @if($crypto->transaction_hash)
                        <div class="flex justify-between text-slate-400">
                            <span>Transaction Hash:</span>
                            <span class="text-emerald-400 truncate max-w-[200px] sm:max-w-xs">{{ $crypto->transaction_hash }}</span>
                        </div>
                    @endif
                </div>
            @endif

            <!-- Line Items Table -->
            <h3 class="text-sm font-bold text-white mt-6 mb-3">Purchased Items</h3>
            <div class="space-y-2">
                @foreach($order->items as $item)
                    <div class="flex justify-between items-center p-3 rounded-xl bg-slate-950/60 border border-slate-800 text-sm">
                        <div>
                            <p class="font-bold text-white">{{ $item->product_name }}</p>
                            <p class="text-xs text-slate-400">Quantity: {{ $item->quantity }}</p>
                        </div>
                        <p class="font-mono font-bold text-white">${{ number_format($item->unit_price * $item->quantity, 2) }}</p>
                    </div>
                @endforeach
            </div>
        </div>

    </div>

    <!-- RIGHT: Summary Sidebar & Support (4 cols) -->
    <div class="lg:col-span-4 space-y-6">
        
        <!-- Order Summary Card -->
        <div class="rounded-3xl bg-gradient-to-br from-purple-950/50 via-slate-900/90 to-violet-950/50 border border-purple-500/30 p-6 shadow-2xl backdrop-blur-xl space-y-4">
            <h3 class="text-base font-bold text-white pb-3 border-b border-slate-800">Financial Summary</h3>
            
            <div class="space-y-2.5 text-sm">
                <div class="flex justify-between text-slate-300">
                    <span>Subtotal</span>
                    <span class="font-mono">${{ number_format($order->subtotal, 2) }}</span>
                </div>
                @if($order->discount > 0)
                    <div class="flex justify-between text-emerald-400">
                        <span>Discount</span>
                        <span class="font-mono">-${{ number_format($order->discount, 2) }}</span>
                    </div>
                @endif
                <div class="flex justify-between text-slate-300">
                    <span>Digital Fulfillment</span>
                    <span class="text-emerald-400 font-semibold">FREE</span>
                </div>
            </div>

            <div class="pt-3 border-t border-slate-800 flex justify-between items-baseline">
                <span class="text-sm font-bold text-slate-300">Paid Total</span>
                <span class="text-2xl font-black font-mono text-purple-300">${{ number_format($order->total, 2) }} USD</span>
            </div>

            <div class="pt-4 border-t border-slate-800 space-y-2">
                <a href="{{ route('products.index') }}" class="block w-full text-center py-3 bg-purple-600 hover:bg-purple-500 text-white font-bold text-xs rounded-xl transition shadow-lg shadow-purple-600/30">
                    🛍️ Browse More Games
                </a>
                <a href="{{ route('orders') }}" class="block w-full text-center py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-300 font-semibold text-xs rounded-xl transition border border-slate-700">
                    View All Orders
                </a>
            </div>
        </div>

        <!-- 24/7 Support Assistance -->
        <div class="rounded-3xl bg-slate-900/60 border border-slate-800 p-6 space-y-3">
            <h4 class="text-sm font-bold text-white flex items-center gap-2">
                <span>💬</span>
                <span>Need Activation Assistance?</span>
            </h4>
            <p class="text-xs text-slate-400 leading-relaxed">
                If you encounter any issues redeeming your key, our 24/7 automated support team is ready to assist or issue an instant key replacement.
            </p>
            <div class="pt-2 flex gap-2">
                <a href="{{ route('help') }}" class="flex-1 py-2 bg-slate-800 hover:bg-slate-700 text-white text-center text-xs font-semibold rounded-lg transition border border-slate-700">
                    Help Center
                </a>
                <a href="{{ route('contact') }}" class="flex-1 py-2 bg-slate-800 hover:bg-slate-700 text-white text-center text-xs font-semibold rounded-lg transition border border-slate-700">
                    Contact Us
                </a>
            </div>
        </div>

    </div>
</div>

<script>
function copyKey(elementId, btn) {
    const text = document.getElementById(elementId).textContent.trim();
    navigator.clipboard.writeText(text).then(() => {
        const originalHtml = btn.innerHTML;
        btn.innerHTML = '<span>✓</span> <span>Copied!</span>';
        btn.classList.remove('bg-purple-600');
        btn.classList.add('bg-emerald-600');

        setTimeout(() => {
            btn.innerHTML = originalHtml;
            btn.classList.remove('bg-emerald-600');
            btn.classList.add('bg-purple-600');
        }, 2000);
    });
}

function downloadKeyTxt(productName, key) {
    const content = `================================================
GTA6 GAME KEY STORE - OFFICIAL RECEIPT & KEY
================================================
Order Number: {{ $order->order_number }}
Product: ${productName}
Digital Key: ${key}
Date: {{ now()->toFormattedDateString() }}

ACTIVATION INSTRUCTIONS:
1. Launch Rockstar Games Launcher, Steam, PSN or Xbox Store.
2. Navigate to 'Activate Product' or 'Redeem Code'.
3. Enter the digital key above.
4. Enjoy your game!
================================================`.trim();

    const blob = new Blob([content], { type: 'text/plain;charset=utf-8' });
    const link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = `${productName.replace(/[^a-zA-Z0-9]/g, '_')}_GameKey.txt`;
    link.click();
}
</script>
@endsection
