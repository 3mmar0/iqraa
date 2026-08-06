@extends('layouts.guest')

@section('title', 'إنشاء حساب')

@section('content')
    @php
        $accountType = old('account_type', 'student');
    @endphp

    <div x-data="{ accountType: @js($accountType), showPassword: false, showConfirm: false }">
        <header class="guest-head">
            <h1 class="guest-title"
                x-text="accountType === 'instructor' ? 'إنشاء حساب محاضر' : 'إنشاء حساب طالب'">
                {{ $accountType === 'instructor' ? 'إنشاء حساب محاضر' : 'إنشاء حساب طالب' }}
            </h1>
            <p class="guest-lead"
               x-text="accountType === 'instructor'
                    ? 'انضم كمعلّم للوصول إلى لوحة التدريس وإدارة مقرراتك.'
                    : 'أنشئ حسابك ثم اطلب الالتحاق بالمقررات المناسبة لك.'">
                {{ $accountType === 'instructor'
                    ? 'انضم كمعلّم للوصول إلى لوحة التدريس وإدارة مقرراتك.'
                    : 'أنشئ حسابك ثم اطلب الالتحاق بالمقررات المناسبة لك.' }}
            </p>
        </header>

        <form method="POST" action="{{ route('register') }}" class="guest-form">
            @csrf

            <fieldset class="guest-field">
                <legend class="guest-label">نوع الحساب</legend>
                <div class="guest-segment" role="radiogroup" aria-label="نوع الحساب">
                    <label class="guest-segment-option"
                           :class="{ 'is-active': accountType === 'student' }">
                        <input type="radio" name="account_type" value="student" class="sr-only"
                               x-model="accountType"
                               @checked($accountType === 'student')>
                        <span class="guest-segment-icon" aria-hidden="true">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5zm0 0v6m0-6l6.2-3.4"/>
                            </svg>
                        </span>
                        <span class="guest-segment-text">
                            <span class="guest-segment-title">طالب</span>
                            <span class="guest-segment-hint">تعلّم ومتابعة</span>
                        </span>
                    </label>
                    <label class="guest-segment-option"
                           :class="{ 'is-active': accountType === 'instructor' }">
                        <input type="radio" name="account_type" value="instructor" class="sr-only"
                               x-model="accountType"
                               @checked($accountType === 'instructor')>
                        <span class="guest-segment-icon" aria-hidden="true">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 19h16"/>
                            </svg>
                        </span>
                        <span class="guest-segment-text">
                            <span class="guest-segment-title">محاضر</span>
                            <span class="guest-segment-hint">تدريس وإدارة</span>
                        </span>
                    </label>
                </div>
                @error('account_type')
                    <p class="guest-error">{{ $message }}</p>
                @enderror
            </fieldset>

            <div class="guest-field">
                <label class="guest-label" for="name">الاسم</label>
                <input id="name" name="name" value="{{ old('name') }}" required autocomplete="name"
                       placeholder="الاسم الكامل"
                       aria-invalid="{{ $errors->has('name') ? 'true' : 'false' }}"
                       class="guest-input">
                @error('name')
                    <p class="guest-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="guest-field">
                <label class="guest-label" for="email">البريد الإلكتروني</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email"
                       placeholder="name@example.com"
                       aria-invalid="{{ $errors->has('email') ? 'true' : 'false' }}"
                       class="guest-input">
                @error('email')
                    <p class="guest-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="guest-grid-2">
                <div class="guest-field">
                    <label class="guest-label" for="phone">رقم الهاتف</label>
                    <input id="phone" name="phone" value="{{ old('phone') }}" autocomplete="tel"
                           placeholder="اختياري"
                           aria-invalid="{{ $errors->has('phone') ? 'true' : 'false' }}"
                           class="guest-input">
                    @error('phone')
                        <p class="guest-error">{{ $message }}</p>
                    @enderror
                </div>
                <div class="guest-field">
                    <label class="guest-label" for="university">الجامعة</label>
                    <input id="university" name="university" value="{{ old('university') }}"
                           placeholder="اختياري"
                           aria-invalid="{{ $errors->has('university') ? 'true' : 'false' }}"
                           class="guest-input">
                    <p class="guest-hint" x-show="accountType === 'student'" x-cloak>
                        يساعد في توجيه المقررات المناسبة لك.
                    </p>
                    @error('university')
                        <p class="guest-error">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="guest-field">
                <label class="guest-label" for="password">كلمة المرور</label>
                <div class="guest-input-wrap">
                    <input id="password" :type="showPassword ? 'text' : 'password'" name="password" required
                           autocomplete="new-password"
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

            <div class="guest-field">
                <label class="guest-label" for="password_confirmation">تأكيد كلمة المرور</label>
                <div class="guest-input-wrap">
                    <input id="password_confirmation" :type="showConfirm ? 'text' : 'password'" name="password_confirmation" required
                           autocomplete="new-password"
                           placeholder="••••••••"
                           class="guest-input guest-input-has-action">
                    <button type="button" class="guest-input-action" @click="showConfirm = !showConfirm"
                            :aria-label="showConfirm ? 'إخفاء التأكيد' : 'إظهار التأكيد'">
                        <svg x-show="!showConfirm" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.5 12s3.5-6.5 9.5-6.5S21.5 12 21.5 12s-3.5 6.5-9.5 6.5S2.5 12 2.5 12z"/>
                            <circle cx="12" cy="12" r="2.75"/>
                        </svg>
                        <svg x-show="showConfirm" x-cloak class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 3l18 18M9.9 9.9A3 3 0 0012 15a3 3 0 002.1-.9M6.1 6.2C4.2 7.5 2.8 9.4 2.5 12c0 0 3.5 6.5 9.5 6.5 1.7 0 3.2-.4 4.5-1M14.1 9.1C15.3 9.8 16 11 16 12.3"/>
                        </svg>
                    </button>
                </div>
            </div>

            <button type="submit" class="guest-submit">إنشاء الحساب</button>
        </form>

        <p class="guest-switch">
            لديك حساب؟
            <a href="{{ route('login') }}" class="guest-text-link">تسجيل الدخول</a>
        </p>
    </div>
@endsection
