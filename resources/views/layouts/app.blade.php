<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=5">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	
	{{-- Primary Page Title & SEO Meta Tags --}}
	<title>@yield('title', $title ?? 'Buy GTA 6 Key & Digital Games | Instant Key Delivery | GTA 6 Vault')</title>
	
	<meta name="description" content="@yield('meta_description', $metaDescription ?? 'Buy Grand Theft Auto VI (GTA 6) CD keys and digital PC, PS5 & Xbox game keys with instant delivery. 100% genuine keys, automated fulfillment, and crypto checkout (Bitcoin, USDT, Solana, Cards).')">
	<meta name="keywords" content="@yield('meta_keywords', $metaKeywords ?? 'Buy GTA 6, GTA 6 PC Key, Grand Theft Auto VI CD Key, GTA 6 Pre Order, Buy Games with Bitcoin, Crypto Game Key Store, Instant Game Key Delivery, Cheap GTA 6 Key, Vice City PC Key')">
	<meta name="author" content="GTA 6 Digital Key Store">
	<meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
	<meta name="googlebot" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
	<meta name="bingbot" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
	<meta name="rating" content="general">
	<meta name="theme-color" content="#0b0910">
	<meta name="color-scheme" content="dark">
	
	{{-- Canonical Link --}}
	<link rel="canonical" href="@yield('canonical', url()->current())">
	<link rel="alternate" hreflang="en" href="{{ url()->current() }}">
	<link rel="alternate" hreflang="es" href="{{ url()->current() }}">
	<link rel="alternate" hreflang="x-default" href="{{ url()->current() }}">

	{{-- OpenGraph / Facebook Meta Tags --}}
	<meta property="og:site_name" content="{{ config('app.name', 'GTA 6 Digital Key Vault') }}">
	<meta property="og:type" content="@yield('og_type', 'website')">
	<meta property="og:url" content="{{ url()->current() }}">
	<meta property="og:title" content="@yield('title', $title ?? 'Buy GTA 6 Key & Digital Games | Instant Delivery & Crypto')">
	<meta property="og:description" content="@yield('meta_description', $metaDescription ?? 'Instant digital delivery for GTA 6 and top AAA games. Pay securely with Bitcoin, USDT, Solana, or Credit Cards.')">
	<meta property="og:image" content="@yield('og_image', asset('images/products/gta-6-standard-edition.svg'))">
	<meta property="og:image:alt" content="GTA 6 Digital Game Key Store">
	<meta property="og:locale" content="{{ str_replace('_', '-', app()->getLocale()) === 'es' ? 'es_ES' : 'en_US' }}">

	{{-- Twitter Card Meta Tags --}}
	<meta name="twitter:card" content="summary_large_image">
	<meta name="twitter:title" content="@yield('title', $title ?? 'Buy GTA 6 Key & Digital Games | Instant Delivery')">
	<meta name="twitter:description" content="@yield('meta_description', $metaDescription ?? 'Instant digital key delivery for GTA 6. Pay with Crypto or Cards.')">
	<meta name="twitter:image" content="@yield('og_image', asset('images/products/gta-6-standard-edition.svg'))">

	{{-- Dynamic asset loading from manifest --}}
	@php
		$manifest = [];
		if (file_exists(public_path('build/manifest.json'))) {
			$manifest = json_decode(file_get_contents(public_path('build/manifest.json')), true);
		}
		$cssFile = $manifest['resources/css/app.css']['file'] ?? 'assets/app-aCB4clyB.css';
		$jsFile = $manifest['resources/js/app.js']['file'] ?? 'assets/app-l0sNRNKZ.js';
	@endphp
	<link rel="stylesheet" href="{{ asset('build/' . $cssFile) }}">
	<script defer src="{{ asset('build/' . $jsFile) }}"></script>
	<meta name="csrf-token" content="{{ csrf_token() }}">

	{{-- JSON-LD Schema: Organization & WebSite --}}
	@php
		$globalSchema = [
			'@context' => 'https://schema.org',
			'@graph' => [
				[
					'@type' => 'Organization',
					'@id' => route('home') . '#organization',
					'name' => config('app.name', 'GTA 6 Digital Key Store'),
					'url' => route('home'),
					'logo' => [
						'@type' => 'ImageObject',
						'url' => asset('images/logo.svg'),
						'caption' => config('app.name', 'GTA 6 Digital Key Store'),
					],
					'description' => 'Authorized digital key vendor providing instant activation keys for Grand Theft Auto VI and top AAA video games with cryptocurrency and fiat payment options.',
				],
				[
					'@type' => 'WebSite',
					'@id' => route('home') . '#website',
					'url' => route('home'),
					'name' => config('app.name', 'GTA 6 Digital Key Store'),
					'description' => 'Official GTA VI and AAA Digital Game Keys with Instant Automated Fulfillment',
					'publisher' => [
						'@id' => route('home') . '#organization',
					],
					'potentialAction' => [
						'@type' => 'SearchAction',
						'target' => route('products.index') . '?q={search_term_string}',
						'query-input' => 'required name=search_term_string',
					],
				],
			],
		];
	@endphp
	<script type="application/ld+json">
	{!! json_encode($globalSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
	</script>

	{{-- Page-Specific Structured Data Injection --}}
	@yield('schema')

	<style>/* keep headings readable on small screens */
		@media (max-width:640px){h1{font-size:1.5rem}}</style>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen antialiased">
	<div class="site-ambient" aria-hidden="true"></div>
	<header class="border-b border-slate-800 sticky top-0 z-50 bg-slate-950/80 backdrop-blur-sm glass">
		<div class="container flex min-h-20 items-center justify-between">
			<a href="{{ route('home') }}" class="flex items-center gap-3">
				<img src="{{ asset('images/logo.svg') }}" alt="logo" style="height:40px" />
			</a>
			<nav class="hidden sm:flex items-center gap-6 ml-6">
				<a href="{{ route('home') }}" class="text-sm">Home</a>
				<a href="{{ route('products.index') }}" class="text-sm">Products</a>
				<a href="{{ route('contact') }}" class="text-sm">Contact</a>
				<a href="{{ route('help') }}" class="text-sm">Help</a>
			</nav>

			@php
				$cartCount = array_sum(array_column(session('cart', []), 'quantity'));
				if ($cartCount === 0 && count(session('cart', [])) > 0) {
					$cartCount = count(session('cart', []));
				}
			@endphp

			<div class="hidden sm:flex items-center gap-4">
				{{-- Professional Desktop Cart Button --}}
				<a href="{{ route('cart') }}" 
				   class="relative group inline-flex items-center gap-2.5 px-3.5 py-2 rounded-xl bg-slate-900/90 hover:bg-slate-800/90 border border-slate-800 hover:border-purple-500/50 text-slate-200 hover:text-white transition-all duration-200 shadow-sm hover:shadow-purple-500/10"
				   aria-label="{{ __('messages.cart') }} ({{ $cartCount }} items)">
					<div class="relative flex items-center justify-center">
						<svg class="w-5 h-5 text-purple-400 group-hover:text-purple-300 transition-colors" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
							<path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.7 2.84-7.242A1.125 1.125 0 0020.25 6H5.106m2.394 8.25l-.8-4.25m0 0h14.7M7.5 20.25a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zm12 0a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z" />
						</svg>
						@if($cartCount > 0)
							<span class="absolute -top-2.5 -right-2.5 flex h-4 min-w-4 px-1 items-center justify-center rounded-full bg-gradient-to-r from-purple-500 to-pink-500 text-[10px] font-extrabold text-white shadow-md shadow-purple-500/40 ring-2 ring-slate-950">
								{{ $cartCount > 99 ? '99+' : $cartCount }}
							</span>
						@endif
					</div>
					<span class="text-xs font-bold tracking-wide uppercase group-hover:text-purple-200">{{ __('messages.cart') }}</span>
					@if($cartCount > 0)
						<span class="text-xs font-semibold px-1.5 py-0.5 rounded-md bg-purple-950/80 text-purple-300 border border-purple-800/40 font-mono">
							{{ $cartCount }}
						</span>
					@endif
				</a>

				@auth
					<a href="{{ route('orders') }}" class="text-sm">Orders</a>
					<span class="text-slate-400 text-sm">{{ auth()->user()->name }}</span>
					<form method="POST" action="{{ route('logout') }}">@csrf<button class="ml-2 text-sm underline">Logout</button></form>
				@else
					<a href="{{ route('login') }}" class="text-sm">{{ __('messages.login') }}</a>
					<a href="{{ route('register') }}" class="text-sm">{{ __('messages.register') }}</a>
				@endauth

				<div class="ml-4">
					<select onchange="location = this.value" class="bg-slate-900 border border-slate-700 rounded px-2 py-1 text-sm">
						<option value="{{ route('lang.switch', 'en') }}" @if(app()->getLocale()==='en') selected @endif>English</option>
						<option value="{{ route('lang.switch', 'es') }}" @if(app()->getLocale()==='es') selected @endif>Español</option>
					</select>
				</div>
			</div>

			<div class="sm:hidden flex items-center gap-2">
				{{-- Mobile Cart Quick Button --}}
				<a href="{{ route('cart') }}" class="relative p-2 rounded-xl bg-slate-900/90 border border-slate-800 text-slate-200 hover:text-white flex items-center justify-center" aria-label="{{ __('messages.cart') }}">
					<svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.7 2.84-7.242A1.125 1.125 0 0020.25 6H5.106m2.394 8.25l-.8-4.25m0 0h14.7M7.5 20.25a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zm12 0a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z" />
					</svg>
					@if($cartCount > 0)
						<span class="absolute -top-1.5 -right-1.5 flex h-4 min-w-4 px-1 items-center justify-center rounded-full bg-gradient-to-r from-purple-500 to-pink-500 text-[10px] font-bold text-white shadow-sm ring-2 ring-slate-950">
							{{ $cartCount > 99 ? '99+' : $cartCount }}
						</span>
					@endif
				</a>
				<button id="mobile-sidebar-toggle" class="text-slate-200" aria-label="Open categories">☰</button>
				<button id="mobile-toggle" class="text-slate-200" aria-label="Open menu">☰</button>
			</div>
		</div>
		<div id="mobile-menu" class="hidden sm:hidden border-t border-slate-800">
			<div class="container py-3 flex flex-col gap-3">
				<a href="{{ route('home') }}" class="font-medium">Home</a>
				<a href="{{ route('products.index') }}" class="font-medium">Products</a>
				<a href="{{ route('contact') }}" class="font-medium">Contact</a>
				<a href="{{ route('help') }}" class="font-medium">Help</a>

				<div class="border-t border-slate-800 pt-3">
					{{-- Mobile Menu Cart Item --}}
					<a href="{{ route('cart') }}" class="flex items-center justify-between p-2.5 rounded-xl bg-slate-900/90 border border-slate-800 text-slate-200 font-medium mb-3">
						<span class="flex items-center gap-2.5">
							<svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.7 2.84-7.242A1.125 1.125 0 0020.25 6H5.106m2.394 8.25l-.8-4.25m0 0h14.7M7.5 20.25a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zm12 0a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z" />
							</svg>
							<span>{{ __('messages.cart') }}</span>
						</span>
						<span class="px-2 py-0.5 rounded-full text-xs font-bold {{ $cartCount > 0 ? 'bg-purple-600 text-white shadow-sm' : 'bg-slate-800 text-slate-400' }}">
							{{ $cartCount }}
						</span>
					</a>

					@auth
						<a href="{{ route('orders') }}">Orders</a>
						<form method="POST" action="{{ route('logout') }}">@csrf<button class="text-left">Logout</button></form>
					@else
						<a href="{{ route('login') }}">{{ __('messages.login') }}</a>
						<a href="{{ route('register') }}">{{ __('messages.register') }}</a>
					@endauth
				</div>

				<div class="pt-2">
					<a href="{{ route('lang.switch','en') }}" class="mr-2">English</a>
					<a href="{{ route('lang.switch','es') }}">Español</a>
				</div>
			</div>
		</div>

		<div id="mobile-sidebar" class="hidden fixed inset-0 z-40">
			<div class="absolute inset-0 bg-black/40" id="mobile-sidebar-backdrop"></div>
			<div class="absolute left-0 top-0 bottom-0 w-64 bg-slate-900 p-4 overflow-auto">
				<button id="mobile-sidebar-close" class="mb-4">Close</button>
				<h3 class="font-semibold">Categories</h3>
				<ul class="mt-2">
					@foreach(\App\Models\Category::where('active',true)->get() as $c)
						<li class="mt-2"><a href="?category={{ $c->slug }}">{{ $c->name }}</a></li>
					@endforeach
				</ul>
			</div>
		</div>
	</header>

	<main class="container py-8">
		@if(session('success'))
			<div class="mb-5 rounded bg-emerald-900/50 p-3">{{ session('success') }}</div>
		@endif
		@if(session('error'))
			<div class="mb-5 rounded bg-red-900/50 p-3">{{ session('error') }}</div>
		@endif
		@if(isset($errors) && $errors->any())
			<div class="mb-5 rounded bg-red-900/50 p-3">{{ $errors->first() }}</div>
		@endif

		{{ $slot ?? '' }}
		@yield('content')
	</main>

	<footer class="border-t border-slate-800 mt-12">
		<div class="container py-6 text-slate-400 text-sm">{{ __('messages.footer_note') }}</div>
	</footer>

	<script>
		document.getElementById('mobile-toggle')?.addEventListener('click', function(){
			const m = document.getElementById('mobile-menu');
			if (m) m.classList.toggle('hidden');
		});

		document.getElementById('mobile-sidebar-toggle')?.addEventListener('click', function(){
			const s = document.getElementById('mobile-sidebar');
			if (s) s.classList.remove('hidden');
		});

		document.getElementById('mobile-sidebar-close')?.addEventListener('click', function(){
			const s = document.getElementById('mobile-sidebar');
			if (s) s.classList.add('hidden');
		});

		document.getElementById('mobile-sidebar-backdrop')?.addEventListener('click', function(){
			const s = document.getElementById('mobile-sidebar');
			if (s) s.classList.add('hidden');
		});
	</script>
</body>
</html>
