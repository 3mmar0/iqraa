@extends('layouts.guest')

@section('title', 'تسجيل الدخول')

@section('content')
    <h1 class="text-2xl font-bold tracking-tight text-[var(--color-ink)]">تسجيل الدخول</h1>
    <p class="mt-2 text-sm leading-relaxed text-[var(--color-text-secondary)]">ادخل بحسابك لمتابعة مقرراتك وتقدّمك.</p>

    <form method="POST" action="{{ route('login') }}" class="mt-6 space-y-5">
        @csrf
        <div>
            <label class="guest-label" for="email">البريد الإلكتروني</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                   autocomplete="username"
                   aria-invalid="{{ $errors->has('email') ? 'true' : 'false' }}"
                   class="guest-input">
            @error('email')
                <p class="mt-1.5 text-sm text-[var(--color-danger)]">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label class="guest-label" for="password">كلمة المرور</label>
            <input id="password" type="password" name="password" required
                   autocomplete="current-password"
                   aria-invalid="{{ $errors->has('password') ? 'true' : 'false' }}"
                   class="guest-input">
            @error('password')
                <p class="mt-1.5 text-sm text-[var(--color-danger)]">{{ $message }}</p>
            @enderror
        </div>
        <div class="flex flex-wrap items-center justify-between gap-3">
            <label class="flex min-h-11 cursor-pointer items-center gap-2.5 text-sm text-[var(--color-text-secondary)]">
                <input type="checkbox" name="remember"
                       class="size-4 rounded border-[var(--color-line)] text-[var(--color-primary)] focus:ring-[var(--color-primary)]/25">
                تذكرني
            </label>
            <a href="{{ route('password.request') }}"
               class="text-sm font-medium text-[var(--color-secondary)] hover:text-[var(--color-secondary-hover)] hover:underline">
                نسيت كلمة المرور؟
            </a>
        </div>
        <button type="submit" class="guest-submit">
            دخول
        </button>
    </form>

    <p class="mt-6 text-center text-sm text-[var(--color-text-secondary)]">
        ليس لديك حساب؟
        <a href="{{ route('register') }}" class="font-medium text-[var(--color-secondary)] hover:text-[var(--color-secondary-hover)] hover:underline">إنشاء حساب</a>
    </p>
@endsection
