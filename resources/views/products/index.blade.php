@extends('layouts.app')

@section('title', 'Browse All Game Keys & GTA VI Digital Editions | GTA 6 Vault')
@section('meta_description', 'Explore our live catalog of authorized Grand Theft Auto VI (GTA 6) digital keys, PC CD keys, PS5, Xbox Series X|S, and top AAA games. Instant code delivery with crypto.')
@section('meta_keywords', 'GTA 6 digital key catalog, buy GTA 6 PC key, Grand Theft Auto VI all editions, digital game keys store, cheap CD keys, crypto game shop')

@section('schema')
@php
    $itemList = [];
    foreach ($products as $index => $prod) {
        $itemList[] = [
            '@type' => 'ListItem',
            'position' => $index + 1,
            'url' => route('products.show', $prod->slug),
            'name' => $prod->name,
        ];
    }

    $collectionSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'CollectionPage',
        'name' => 'GTA 6 Digital Game Key Catalog',
        'description' => 'Browse official digital keys for GTA VI and popular AAA gaming titles with instant delivery.',
        'url' => route('products.index'),
        'mainEntity' => [
            '@type' => 'ItemList',
            'itemListElement' => $itemList,
        ],
    ];

    $catalogBreadcrumbs = [
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
        ],
    ];
@endphp
<script type="application/ld+json">
{!! json_encode($collectionSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
</script>
<script type="application/ld+json">
{!! json_encode($catalogBreadcrumbs, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
</script>
@endsection

@section('content')
    <section class="products-page">
        <div class="products-page__heading">
            <p class="eyebrow">Browse the vault</p>
            <h1>All <span>Products.</span></h1>
            <p>Explore every active game key in our catalogue. Filter the live inventory to find exactly what you want.</p>
        </div>

        <form method="GET" action="{{ route('products.index') }}" class="product-filters card">
            <div class="product-filters__controls">
                <label>
                    <span>Category</span>
                    <select name="category" class="field">
                        <option value="">All categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->slug }}" @selected(request('category') === $category->slug)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </label>
                <label>
                    <span>Price range</span>
                    <select name="price" class="field">
                        <option value="all">All prices</option>
                        <option value="under-25" @selected(request('price') === 'under-25')>Under $25</option>
                        <option value="25-50" @selected(request('price') === '25-50')>$25 - $50</option>
                        <option value="50-100" @selected(request('price') === '50-100')>$50 - $100</option>
                        <option value="over-100" @selected(request('price') === 'over-100')>Over $100</option>
                    </select>
                </label>
                <label>
                    <span>Sort by</span>
                    <select name="sort" class="field">
                        <option value="newest" @selected(request('sort', 'newest') === 'newest')>Newest</option>
                        <option value="price-low" @selected(request('sort') === 'price-low')>Price: low to high</option>
                        <option value="price-high" @selected(request('sort') === 'price-high')>Price: high to low</option>
                        <option value="name" @selected(request('sort') === 'name')>Name: A to Z</option>
                    </select>
                </label>
            </div>
            <div class="product-filters__search">
                <label class="relative block">
                    <span class="sr-only">Search products</span>
                    <svg class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-slate-500" width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="11" cy="11" r="6.5"/><path d="m16 16 4 4" stroke-linecap="round"/></svg>
                    <input name="q" value="{{ request('q') }}" placeholder="Search products..." class="field !py-3.5 !pl-11" />
                </label>
                <button class="button-primary">Apply filters</button>
                @if(request()->hasAny(['q', 'category', 'price', 'sort']))
                    <a href="{{ route('products.index') }}" class="button-secondary">Clear</a>
                @endif
            </div>
        </form>

        <div class="products-page__results">
            <p><strong>{{ $products->total() }}</strong> {{ $products->total() === 1 ? 'product' : 'products' }} found</p>
        </div>

        @if($products->count())
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                @foreach($products as $product)
                    @include('components.product-card', ['product' => $product])
                @endforeach
            </div>
            <div class="mt-10">{{ $products->links() }}</div>
        @else
            <div class="card rounded-2xl px-6 py-16 text-center">
                <p class="text-xl font-bold">No products match those filters.</p>
                <p class="mt-2 text-sm text-slate-400">Adjust the search or clear the filters to see the full catalogue.</p>
                <a class="button-primary mt-6" href="{{ route('products.index') }}">View all products</a>
            </div>
        @endif
    </section>
@endsection
