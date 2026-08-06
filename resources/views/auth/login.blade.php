@extends('layouts.guest')

@section('title', 'تسجيل الدخول')

@section('content')
    <header class="guest-head">
        <h1 class="guest-title">مرحباً بعودتك</h1>
        <p class="guest-lead">ادخل بحسابك لمتابعة مقرراتك وتقدّمك بهدوء.</p>
    </header>

    <form method="POST" action="{{ route('login') }}" class="guest-form" x-data="{ showPassword: false }">
        @csrf
        <div class="guest-field">
            <label class="guest-label" for="email">البريد الإلكتروني</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                   autocomplete="username"
                   placeholder="name@example.com"
                   aria-invalid="{{ $errors->has('email') ? 'true' : 'false' }}"
                   class="guest-input">
            @error('email')
                <p class="guest-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="guest-field">
            <label class="guest-label" for="password">كلمة المرور</label>
            <div class="guest-input-wrap">
                <input id="password" :type="showPassword ? 'text' : 'password'" name="password" required
                       autocomplete="current-password"
                       placeholder="••••••••"
                       aria-invalid="{{ $errors->has('password') ? 'true' : 'false' }}"
                       class="guest-input guest-input-has-action">
                <button type="button" class="guest-input-action" @click="showPassword = !showPassword"
                        :aria-label="showPassword ? 'إخفاء كلمة المرور' : 'إظهار كلمة المرور'">
                    <svg x-show="!showPassword" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.5 12s3.5-6.5 9.5-6.5S21.5 12 21.5 12s-3.5 6.5-9.5 6.5S2.5 12 2.5 12z"/>
                        <circle cx="12" cy="12" r="2.75"/>
                    </svg>
                    <svg x-show="showPassword" x-cloak class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 3l18 18M9.9 9.9A3 3 0 0012 15a3 3 0 002.1-.9M6.1 6.2C4.2 7.5 2.8 9.4 2.5 12c0 0 3.5 6.5 9.5 6.5 1.7 0 3.2-.4 4.5-1M14.1 9.1C15.3 9.8 16 11 16 12.3"/>
                    </svg>
                </button>
            </div>
            @error('password')
                <p class="guest-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="guest-row">
            <label class="guest-check">
                <input type="checkbox" name="remember" class="guest-check-input">
                <span>تذكرني</span>
            </label>
            <a href="{{ route('password.request') }}" class="guest-text-link">نسيت كلمة المرور؟</a>
        </div>

        <button type="submit" class="guest-submit">دخول إلى حسابي</button>
    </form>

    <p class="guest-switch">
        ليس لديك حساب؟
        <a href="{{ route('register') }}" class="guest-text-link">إنشاء حساب</a>
    </p>
@endsection
