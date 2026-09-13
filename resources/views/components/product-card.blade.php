<article class="product-card flex flex-col group rounded-2xl overflow-hidden bg-slate-900/60 border border-slate-800 hover:border-purple-500/50 transition-all duration-300 hover:shadow-xl hover:shadow-purple-500/10">
    <a href="{{ route('products.show', $product) }}" class="product-card__image relative overflow-hidden block">
        <img src="{{ asset('images/products/'.$product->slug.'.svg') }}" onerror="this.onerror=null;this.src='{{ asset('images/products/placeholder.svg') }}'" alt="{{ $product->name }}" class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-500" />
        <span class="product-card__tag absolute top-3 left-3 bg-purple-600/90 text-white text-[11px] font-black px-2.5 py-0.5 rounded-full backdrop-blur-md shadow-md">{{ $product->platform }}</span>
        <span class="absolute top-3 right-3 bg-slate-950/80 text-emerald-400 text-[10px] font-bold px-2 py-0.5 rounded-full border border-emerald-500/30 backdrop-blur-md">⚡ Instant</span>
    </a>

    <div class="flex flex-1 flex-col p-5">
        <div class="flex items-center justify-between text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">
            <span>{{ $product->region }}</span>
            @if($product->edition)
                <span class="text-purple-400">{{ $product->edition }}</span>
            @endif
        </div>
        <a href="{{ route('products.show', $product) }}" class="mt-1 text-base font-bold leading-snug text-white transition hover:text-purple-300 line-clamp-2">{{ $product->name }}</a>
        <p class="text-slate-400 text-xs mt-1">{{ $product->platform }} • {{ $product->drm_client ?? 'Digital Key' }}</p>
        
        <div class="mt-4 pt-3 border-t border-slate-800/80 flex items-center justify-between gap-3">
            <div>
                <div class="flex items-center gap-1.5">
                    <span class="text-xl font-black font-mono text-white">${{ number_format($product->price, 2) }}</span>
                    <span class="text-xs font-mono text-slate-500 line-through">${{ number_format($product->original_price, 2) }}</span>
                </div>
                <span class="block text-[10px] text-emerald-400 font-semibold mt-0.5">⚡ Crypto & Cards</span>
            </div>
            <form method="POST" action="{{ route('cart.add', $product) }}">
                @csrf
                <button class="button-primary !rounded-xl !px-3.5 !py-2.5 text-xs font-bold shadow-md shadow-purple-600/20 flex items-center gap-1.5 hover:shadow-purple-500/30" aria-label="Add {{ $product->name }} to cart">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.7 2.84-7.242A1.125 1.125 0 0020.25 6H5.106m2.394 8.25l-.8-4.25m0 0h14.7M7.5 20.25a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zm12 0a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z" />
                    </svg>
                    <span>Add</span>
                </button>
            </form>
        </div>
    </div>
</article>
