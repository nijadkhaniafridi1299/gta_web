@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-md"><div class="mb-8 text-center"><p class="eyebrow">Welcome back</p><h1 class="mt-2 text-4xl font-black tracking-[-.04em] text-white">Sign in to play.</h1></div><div class="card rounded-2xl p-6 sm:p-8"><form method="POST" action="{{ route('login') }}" class="space-y-5">@csrf
        <label class="block"><span class="mb-2 block text-sm font-semibold text-slate-200">Email address</span><input type="email" name="email" required autocomplete="email" placeholder="you@example.com" class="field"></label>
        <label class="block"><span class="mb-2 block text-sm font-semibold text-slate-200">Password</span><input type="password" name="password" required autocomplete="current-password" placeholder="Your password" class="field"></label>
        <label class="flex items-center gap-2 text-sm text-slate-400"><input type="checkbox" name="remember" class="rounded border-white/20 bg-transparent text-violet-500"> Remember me on this device</label>
        <button class="button-primary w-full !py-4">Log in <span aria-hidden="true">&rarr;</span></button>
    </form><p class="mt-6 text-center text-sm text-slate-400">New here? <a href="{{ route('register') }}" class="font-bold text-violet-300 hover:text-violet-200">Create an account</a></p></div></div>
@endsection
