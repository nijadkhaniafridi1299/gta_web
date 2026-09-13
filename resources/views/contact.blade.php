@extends('layouts.app')

@section('title', 'Contact Us & 24/7 Customer Support — GTA 6 Vault')
@section('meta_description', 'Need help with your GTA 6 key order or crypto payment? Contact our 24/7 customer support desk. Fast resolution for activations, keys, and payments.')
@section('meta_keywords', 'contact GTA 6 store, game key support, crypto payment help, digital store support desk')

@section('schema')
@php
    $contactSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'ContactPage',
        'name' => 'Contact GTA 6 Digital Key Store',
        'description' => '24/7 Customer Support and Inquiries Desk',
        'url' => route('contact'),
    ];

    $contactBreadcrumbs = [
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
                'name' => 'Contact Us',
                'item' => route('contact'),
            ],
        ],
    ];
@endphp
<script type="application/ld+json">
{!! json_encode($contactSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
</script>
<script type="application/ld+json">
{!! json_encode($contactBreadcrumbs, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
</script>
@endsection

@section('content')
<div class="max-w-2xl mx-auto pb-16">
    <div class="text-center mb-8 space-y-2">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-purple-500/10 border border-purple-500/20 text-purple-300 text-xs font-bold uppercase tracking-wider">
            <span>24/7 Support Desk</span>
        </div>
        <h1 class="text-3xl sm:text-4xl font-black text-white tracking-tight">Get in Touch</h1>
        <p class="text-slate-400 text-sm max-w-md mx-auto">
            Have a question about your order, digital key activation, or crypto payment? We are here to help.
        </p>
    </div>

    @if(session('success'))
        <div class="mb-6 rounded-2xl bg-emerald-950/80 border border-emerald-500/30 p-4 text-emerald-300 text-sm font-semibold flex items-center gap-3">
            <span class="w-6 h-6 rounded-full bg-emerald-500/20 flex items-center justify-center font-bold">✓</span>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <div class="rounded-3xl bg-slate-900/70 border border-slate-800 p-8 sm:p-10 shadow-2xl backdrop-blur-md">
        <form method="POST" action="{{ route('contact') }}" class="space-y-5">
            @csrf
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Your Full Name</label>
                <input name="name" required placeholder="John Doe" class="field" value="{{ old('name', auth()->user()->name ?? '') }}" />
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Email Address</label>
                <input name="email" type="email" required placeholder="gamer@example.com" class="field" value="{{ old('email', auth()->user()->email ?? '') }}" />
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Message / Order Inquiry</label>
                <textarea name="message" rows="5" required placeholder="Please provide your order ID (if applicable) and details of your request..." class="field">{{ old('message') }}</textarea>
            </div>

            <button type="submit" class="button-primary w-full !py-3.5 !rounded-xl text-base font-bold shadow-xl shadow-purple-500/20 hover:scale-[1.01] transition">
                <span>Send Message</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" />
                </svg>
            </button>
        </form>

        <div class="mt-8 pt-6 border-t border-slate-800 grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs text-slate-400 text-center sm:text-left">
            <div>
                <strong class="text-white block mb-0.5">Average Response Time</strong>
                <span>Under 15 minutes for order issues</span>
            </div>
            <div>
                <strong class="text-white block mb-0.5">Automated Fulfillment</strong>
                <span>Keys available 24/7 instantly</span>
            </div>
        </div>
    </div>
</div>
@endsection
