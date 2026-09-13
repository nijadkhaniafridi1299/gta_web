@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto pb-12">
    @php
        $cartItems = $cart ?? [];
        $totalItems = array_sum(array_column($cartItems, 'quantity'));
        $totalPrice = 0;
        foreach ($cartItems as $item) {
            $totalPrice += $item['price'] * $item['quantity'];
        }
    @endphp

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-8 pb-4 border-b border-slate-800/80">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-purple-500/10 border border-purple-500/20 text-purple-300 text-xs font-semibold mb-2">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.7 2.84-7.242A1.125 1.125 0 0020.25 6H5.106m2.394 8.25l-.8-4.25m0 0h14.7M7.5 20.25a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zm12 0a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z" />
                </svg>
                <span>SHOPPING CART</span>
            </div>
            <h1 class="text-3xl sm:text-4xl font-black text-white tracking-tight flex items-center gap-3">
                <span>Your Cart</span>
                @if(!empty($cartItems))
                    <span class="text-base font-bold px-3 py-1 rounded-xl bg-slate-800 text-slate-300 border border-slate-700/60 font-mono">
                        {{ $totalItems }} {{ Str::plural('item', $totalItems) }}
                    </span>
                @endif
            </h1>
        </div>

        @if(!empty($cartItems))
            <form method="POST" action="{{ route('cart.clear') }}" onsubmit="return confirm('Are you sure you want to clear your cart?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-xs font-semibold text-slate-400 hover:text-red-400 transition-colors flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-slate-900 border border-slate-800 hover:border-red-900/50">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                    </svg>
                    <span>Clear Cart</span>
                </button>
            </form>
        @endif
    </div>

    @if(empty($cartItems))
        <!-- Empty Cart State -->
        <div class="relative overflow-hidden rounded-3xl bg-slate-900/40 border border-slate-800/80 p-12 sm:p-20 text-center backdrop-blur-sm">
            <div class="site-ambient" aria-hidden="true"></div>
            
            <div class="w-20 h-20 mx-auto mb-6 rounded-2xl bg-gradient-to-br from-purple-500/20 to-pink-500/10 border border-purple-500/30 flex items-center justify-center text-purple-400 shadow-xl shadow-purple-500/10">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.7 2.84-7.242A1.125 1.125 0 0020.25 6H5.106m2.394 8.25l-.8-4.25m0 0h14.7M7.5 20.25a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zm12 0a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z" />
                </svg>
            </div>
            
            <h2 class="text-2xl sm:text-3xl font-black text-white mb-3">Your cart is empty</h2>
            <p class="text-slate-400 mb-8 max-w-md mx-auto text-base">
                Looks like you haven't added any game keys yet. Explore our high-speed digital catalog and grab the best gaming deals!
            </p>
            
            <a href="{{ route('products.index') }}" class="button-primary inline-flex items-center gap-2 !px-8 !py-3.5 !text-base font-bold shadow-xl shadow-purple-500/30 hover:scale-105 transition-all">
                <span>Browse Games Catalog</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                </svg>
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- CART ITEMS LIST -->
            <div class="lg:col-span-2 space-y-4">
                @foreach($cartItems as $productId => $item)
                    @php 
                        $lineTotal = $item['price'] * $item['quantity'];
                        $slug = $item['slug'] ?? Str::slug($item['name'] ?? 'game');
                    @endphp
                    <div class="rounded-2xl bg-slate-900/60 border border-slate-800 hover:border-purple-500/40 transition-all p-5 sm:p-6 backdrop-blur-sm shadow-md">
                        <div class="flex flex-col sm:flex-row gap-5 items-start sm:items-center">
                            
                            <!-- Game Image / Thumbnail -->
                            <a href="{{ route('products.show', $slug) }}" class="w-full sm:w-28 h-28 rounded-xl overflow-hidden bg-slate-950 border border-slate-800 flex-shrink-0 relative group">
                                <img src="{{ asset('images/products/'.$slug.'.svg') }}" 
                                     onerror="this.onerror=null;this.src='{{ asset('images/products/placeholder.svg') }}'" 
                                     alt="{{ $item['name'] }}" 
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" />
                                <span class="absolute top-2 left-2 bg-purple-600/90 text-white text-[10px] font-black px-1.5 py-0.5 rounded backdrop-blur-md">
                                    {{ $item['platform'] ?? 'PC' }}
                                </span>
                            </a>

                            <!-- Item Info -->
                            <div class="flex-1 min-w-0">
                                <div class="flex flex-wrap items-center gap-2 mb-1.5">
                                    <span class="inline-flex items-center px-2 py-0.5 text-[11px] font-bold bg-slate-800 text-slate-300 rounded border border-slate-700/60">
                                        {{ $item['region'] ?? 'Global' }}
                                    </span>
                                    @if(!empty($item['edition']))
                                        <span class="inline-flex items-center px-2 py-0.5 text-[11px] font-bold bg-purple-950/80 text-purple-300 rounded border border-purple-800/40">
                                            {{ $item['edition'] }}
                                        </span>
                                    @endif
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 text-[11px] font-bold bg-emerald-950/60 text-emerald-300 rounded border border-emerald-800/40">
                                        ⚡ Instant Key
                                    </span>
                                </div>

                                <a href="{{ route('products.show', $slug) }}" class="text-lg font-bold text-white hover:text-purple-300 transition-colors block truncate">
                                    {{ $item['name'] }}
                                </a>

                                <div class="mt-2 flex items-center gap-3 text-xs text-slate-400">
                                    <span>Unit price: <strong class="text-slate-200 font-mono">${{ number_format($item['price'], 2) }}</strong></span>
                                </div>
                            </div>

                            <!-- Stepper & Price & Delete -->
                            <div class="flex items-center justify-between sm:flex-col sm:items-end gap-4 w-full sm:w-auto border-t sm:border-t-0 pt-4 sm:pt-0 border-slate-800">
                                
                                <!-- Subtotal for this item -->
                                <div class="text-left sm:text-right">
                                    <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Total</p>
                                    <p class="text-xl font-black font-mono text-white">${{ number_format($lineTotal, 2) }}</p>
                                </div>

                                <!-- Actions: Stepper + Remove -->
                                <div class="flex items-center gap-3">
                                    <!-- Quantity Stepper Form -->
                                    <div class="inline-flex items-center rounded-xl bg-slate-950 border border-slate-800 p-1">
                                        <!-- Decrease -->
                                        <form method="POST" action="{{ route('cart.update', $productId) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="action" value="decrease">
                                            <button type="submit" 
                                                    class="w-7 h-7 flex items-center justify-center rounded-lg bg-slate-900 hover:bg-slate-800 text-slate-300 hover:text-white transition disabled:opacity-40" 
                                                    aria-label="Decrease quantity">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15" />
                                                </svg>
                                            </button>
                                        </form>

                                        <!-- Quantity Display -->
                                        <span class="w-8 text-center text-xs font-black font-mono text-white">
                                            {{ $item['quantity'] }}
                                        </span>

                                        <!-- Increase -->
                                        <form method="POST" action="{{ route('cart.update', $productId) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="action" value="increase">
                                            <button type="submit" 
                                                    class="w-7 h-7 flex items-center justify-center rounded-lg bg-slate-900 hover:bg-slate-800 text-slate-300 hover:text-white transition disabled:opacity-40"
                                                    aria-label="Increase quantity">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>

                                    <!-- Remove Button -->
                                    <form method="POST" action="{{ route('cart.remove', $productId) }}" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="p-2 rounded-xl bg-red-950/40 hover:bg-red-900/60 border border-red-900/40 text-red-400 hover:text-red-200 transition"
                                                title="Remove item"
                                                aria-label="Remove {{ $item['name'] }} from cart">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>

                        </div>
                    </div>
                @endforeach

                <!-- Continue Shopping Navigation -->
                <div class="pt-4 flex items-center justify-between">
                    <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 text-sm font-bold text-purple-400 hover:text-purple-300 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                        </svg>
                        <span>Continue Shopping</span>
                    </a>
                </div>
            </div>

            <!-- ORDER SUMMARY SIDEBAR -->
            <div class="lg:col-span-1">
                <div class="rounded-3xl bg-gradient-to-b from-slate-900/90 to-purple-950/30 border border-purple-500/20 p-6 sm:p-7 sticky top-28 backdrop-blur-md shadow-2xl shadow-purple-950/20 space-y-6">
                    <div>
                        <h2 class="text-xl font-black text-white tracking-tight flex items-center gap-2">
                            <span>Order Summary</span>
                        </h2>
                        <p class="text-xs text-slate-400 mt-1">Review your total and proceed to checkout</p>
                    </div>

                    <!-- Summary Price Rows -->
                    <div class="space-y-3 pb-5 border-b border-slate-800 text-sm">
                        <div class="flex justify-between items-center text-slate-300">
                            <span>Items Subtotal ({{ $totalItems }})</span>
                            <span class="font-mono font-bold text-white">${{ number_format($totalPrice, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center text-slate-300">
                            <span>Digital Delivery</span>
                            <span class="text-emerald-400 font-bold font-mono">FREE (Instant)</span>
                        </div>
                        <div class="flex justify-between items-center text-slate-300">
                            <span>Taxes & Handling</span>
                            <span class="text-slate-400 font-mono">$0.00</span>
                        </div>
                    </div>

                    <!-- Total Row -->
                    <div>
                        <div class="flex justify-between items-baseline mb-1">
                            <span class="text-slate-200 font-bold">Total Amount</span>
                            <span class="text-3xl font-black font-mono text-transparent bg-clip-text bg-gradient-to-r from-white via-purple-100 to-pink-300">
                                ${{ number_format($totalPrice, 2) }}
                            </span>
                        </div>
                        <p class="text-[11px] text-slate-400">Equivalent in BTC / USDT calculated on checkout</p>
                    </div>

                    <!-- Checkout Button -->
                    <a href="{{ route('checkout') }}" 
                       class="button-primary w-full !py-4 !rounded-xl !text-base font-black shadow-xl shadow-purple-600/30 hover:shadow-purple-600/50 hover:scale-[1.02] active:scale-[0.98] transition-all flex items-center justify-center gap-2">
                        <span>Proceed to Checkout</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                        </svg>
                    </a>

                    <!-- Security & Trust Badges -->
                    <div class="pt-2 space-y-2.5 text-xs text-slate-400">
                        <div class="flex items-center gap-2 text-slate-300">
                            <span class="w-5 h-5 rounded-full bg-emerald-500/10 text-emerald-400 flex items-center justify-center font-bold text-xs flex-shrink-0">✓</span>
                            <span>Instant delivery to your account & email</span>
                        </div>
                        <div class="flex items-center gap-2 text-slate-300">
                            <span class="w-5 h-5 rounded-full bg-purple-500/10 text-purple-400 flex items-center justify-center font-bold text-xs flex-shrink-0">✓</span>
                            <span>Pay with Bitcoin, USDT, or Credit Card</span>
                        </div>
                        <div class="flex items-center gap-2 text-slate-300">
                            <span class="w-5 h-5 rounded-full bg-cyan-500/10 text-cyan-400 flex items-center justify-center font-bold text-xs flex-shrink-0">✓</span>
                            <span>100% Genuine & authorized game keys</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
