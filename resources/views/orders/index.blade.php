@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-10">
        <p class="text-sm font-semibold text-purple-400">YOUR LIBRARY</p>
        <h1 class="mt-2 text-4xl font-bold text-white">My Orders & Keys</h1>
        <p class="mt-2 text-slate-400">View all your purchases and digital keys in one place</p>
    </div>

    @if($orders->count() > 0)
        <!-- Orders Grid -->
        <div class="space-y-4">
            @foreach($orders as $order)
                <a href="{{ route('orders.show', $order) }}" class="group block rounded-2xl bg-slate-800/40 border border-slate-700/50 hover:border-purple-500/50 transition-all hover:shadow-lg hover:shadow-purple-500/10 p-6">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <!-- Order Info -->
                        <div class="flex-1">
                            <div class="flex items-center gap-4 mb-2">
                                <h3 class="font-bold text-white">{{ $order->order_number }}</h3>
                                <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full
                                    @if($order->status === 'completed')
                                        bg-green-900/50 text-green-300
                                    @elseif($order->status === 'pending')
                                        bg-amber-900/50 text-amber-300
                                    @elseif($order->status === 'failed')
                                        bg-red-900/50 text-red-300
                                    @else
                                        bg-slate-700/50 text-slate-300
                                    @endif
                                ">
                                    @if($order->status === 'completed')
                                        ✓ Completed
                                    @else
                                        {{ ucfirst($order->status) }}
                                    @endif
                                </span>
                            </div>
                            
                            <p class="text-slate-400 text-sm mb-3">
                                {{ $order->created_at->format('F d, Y \a\t g:i A') }} • {{ $order->items->count() }} item{{ $order->items->count() !== 1 ? 's' : '' }}
                            </p>

                            <!-- Items Preview -->
                            <div class="flex flex-wrap gap-2">
                                @foreach($order->items->take(3) as $item)
                                    <span class="inline-block px-2 py-1 text-xs bg-slate-700/50 text-slate-300 rounded">{{ $item->product_name }}</span>
                                @endforeach
                                @if($order->items->count() > 3)
                                    <span class="inline-block px-2 py-1 text-xs bg-slate-700/50 text-slate-300 rounded">+{{ $order->items->count() - 3 }} more</span>
                                @endif
                            </div>
                        </div>

                        <!-- Total & Arrow -->
                        <div class="flex items-center justify-between md:flex-col md:items-end gap-4">
                            <div>
                                <p class="text-xs text-slate-400 mb-1">Total</p>
                                <p class="text-2xl font-bold text-white">${{ number_format($order->total, 2) }}</p>
                            </div>
                            <span class="text-slate-500 group-hover:text-purple-400 transition-colors text-xl">→</span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($orders->hasPages())
            <div class="mt-10">
                {{ $orders->links('pagination::tailwind') }}
            </div>
        @endif
    @else
        <!-- Empty State -->
        <div class="rounded-2xl bg-slate-800/40 border border-slate-700/50 p-12 text-center">
            <div class="text-5xl mb-4">📦</div>
            <h2 class="text-2xl font-bold text-white mb-2">No Orders Yet</h2>
            <p class="text-slate-400 mb-6 max-w-md mx-auto">
                You haven't made any purchases yet. Browse our collection and grab some amazing games!
            </p>
            <a href="{{ route('products.index') }}" class="inline-block px-8 py-3 bg-gradient-to-r from-purple-500 to-violet-600 hover:from-purple-600 hover:to-violet-700 text-white font-bold rounded-lg transition-all hover:shadow-lg hover:shadow-purple-500/50">
                Start Shopping
            </a>
        </div>
    @endif
</div>

@endsection

