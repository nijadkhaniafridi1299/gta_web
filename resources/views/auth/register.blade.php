@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-md"><div class="mb-8 text-center"><p class="eyebrow">Your game library</p><h1 class="mt-2 text-4xl font-black tracking-[-.04em] text-white">Create your account.</h1></div><div class="card rounded-2xl p-6 sm:p-8"><form method="POST" action="{{ route('register') }}" class="space-y-5">@csrf
        <label class="block"><span class="mb-2 block text-sm font-semibold text-slate-200">Name</span><input name="name" required autocomplete="name" placeholder="Your name" class="field"></label>
        <label class="block"><span class="mb-2 block text-sm font-semibold text-slate-200">Email address</span><input type="email" name="email" required autocomplete="email" placeholder="you@example.com" class="field"></label>
        <label class="block"><span class="mb-2 block text-sm font-semibold text-slate-200">Password</span><input type="password" name="password" required autocomplete="new-password" placeholder="At least 8 characters" class="field"></label>
        <label class="block"><span class="mb-2 block text-sm font-semibold text-slate-200">Confirm password</span><input type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Repeat your password" class="field"></label>
        <button class="button-primary w-full !py-4">Create account <span aria-hidden="true">&rarr;</span></button>
    </form><p class="mt-6 text-center text-sm text-slate-400">Already have an account? <a href="{{ route('login') }}" class="font-bold text-violet-300 hover:text-violet-200">Log in</a></p></div></div>
@endsection
