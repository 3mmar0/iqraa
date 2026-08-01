@extends('layouts.guest')

@section('title', 'تسجيل الدخول')

@section('content')
    <h1 class="mb-4 text-xl font-semibold text-[var(--color-ink)]">تسجيل الدخول</h1>
    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf
        <div>
            <label class="mb-1 block text-sm" for="email">البريد الإلكتروني</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                   class="w-full rounded-lg border border-[var(--color-line)] px-3 py-2 focus:border-[var(--color-primary)] focus:outline-none focus:ring-2 focus:ring-[var(--color-primary)]/20">
        </div>
        <div>
            <label class="mb-1 block text-sm" for="password">كلمة المرور</label>
            <input id="password" type="password" name="password" required
                   class="w-full rounded-lg border border-[var(--color-line)] px-3 py-2 focus:border-[var(--color-primary)] focus:outline-none focus:ring-2 focus:ring-[var(--color-primary)]/20">
        </div>
        <label class="flex items-center gap-2 text-sm">
            <input type="checkbox" name="remember" class="rounded border-[var(--color-line)] text-[var(--color-primary)]">
            تذكرني
        </label>
        <button type="submit" class="btn-primary w-full rounded-lg px-4 py-2 font-medium">
            دخول
        </button>
    </form>
    <p class="mt-4 text-center text-sm text-[var(--color-text-secondary)]">
        <a href="{{ route('password.request') }}" class="text-[var(--color-primary)] hover:underline">نسيت كلمة المرور؟</a>
    </p>
    <p class="mt-2 text-center text-sm text-[var(--color-text-secondary)]">
        ليس لديك حساب؟
        <a href="{{ route('register') }}" class="text-[var(--color-secondary)] hover:underline">إنشاء حساب</a>
    </p>
@endsection
