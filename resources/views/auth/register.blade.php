@extends('layouts.guest')

@section('title', 'إنشاء حساب')

@section('content')
    @php
        $accountType = old('account_type', 'student');
    @endphp

    <div
        x-data="{ accountType: @js($accountType) }"
        class="space-y-4"
    >
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-[var(--color-ink)]"
                x-text="accountType === 'instructor' ? 'إنشاء حساب محاضر' : 'إنشاء حساب طالب'">
                {{ $accountType === 'instructor' ? 'إنشاء حساب محاضر' : 'إنشاء حساب طالب' }}
            </h1>
            <p class="mt-2 text-sm leading-relaxed text-[var(--color-text-secondary)]"
               x-text="accountType === 'instructor'
                    ? 'أنشئ حساب المحاضر للوصول إلى لوحة التدريس وإدارة مقرراتك.'
                    : 'أنشئ حسابك ثم اطلب الالتحاق بالمقررات المناسبة لك.'">
                {{ $accountType === 'instructor'
                    ? 'أنشئ حساب المحاضر للوصول إلى لوحة التدريس وإدارة مقرراتك.'
                    : 'أنشئ حسابك ثم اطلب الالتحاق بالمقررات المناسبة لك.' }}
            </p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            <fieldset>
                <legend class="guest-label mb-2">نوع الحساب</legend>
                <div class="grid grid-cols-2 gap-2" role="radiogroup" aria-label="نوع الحساب">
                    <label class="flex cursor-pointer items-center justify-center gap-2 rounded-xl border px-3 py-2.5 text-sm font-medium transition"
                           :class="accountType === 'student'
                                ? 'border-[var(--color-primary)] bg-[var(--color-primary-light)] text-[var(--color-primary-hover)]'
                                : 'border-[var(--color-border)] bg-white text-[var(--color-ink)]'">
                        <input type="radio" name="account_type" value="student" class="sr-only"
                               x-model="accountType"
                               @checked($accountType === 'student')>
                        طالب
                    </label>
                    <label class="flex cursor-pointer items-center justify-center gap-2 rounded-xl border px-3 py-2.5 text-sm font-medium transition"
                           :class="accountType === 'instructor'
                                ? 'border-[var(--color-primary)] bg-[var(--color-primary-light)] text-[var(--color-primary-hover)]'
                                : 'border-[var(--color-border)] bg-white text-[var(--color-ink)]'">
                        <input type="radio" name="account_type" value="instructor" class="sr-only"
                               x-model="accountType"
                               @checked($accountType === 'instructor')>
                        محاضر
                    </label>
                </div>
                @error('account_type')
                    <p class="mt-1.5 text-sm text-[var(--color-danger)]">{{ $message }}</p>
                @enderror
            </fieldset>

            <div>
                <label class="guest-label" for="name">الاسم</label>
                <input id="name" name="name" value="{{ old('name') }}" required autocomplete="name"
                       aria-invalid="{{ $errors->has('name') ? 'true' : 'false' }}"
                       class="guest-input">
                @error('name')
                    <p class="mt-1.5 text-sm text-[var(--color-danger)]">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="guest-label" for="email">البريد الإلكتروني</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email"
                       aria-invalid="{{ $errors->has('email') ? 'true' : 'false' }}"
                       class="guest-input">
                @error('email')
                    <p class="mt-1.5 text-sm text-[var(--color-danger)]">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="guest-label" for="phone">رقم الهاتف</label>
                <input id="phone" name="phone" value="{{ old('phone') }}" autocomplete="tel"
                       aria-invalid="{{ $errors->has('phone') ? 'true' : 'false' }}"
                       class="guest-input">
                @error('phone')
                    <p class="mt-1.5 text-sm text-[var(--color-danger)]">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="guest-label" for="university">الجامعة</label>
                <input id="university" name="university" value="{{ old('university') }}"
                       aria-invalid="{{ $errors->has('university') ? 'true' : 'false' }}"
                       class="guest-input">
                <p class="mt-1.5 text-xs text-[var(--color-text-secondary)]"
                   x-show="accountType === 'student'"
                   x-cloak>
                    اختياري — يساعد في توجيه المقررات المناسبة لك.
                </p>
                @error('university')
                    <p class="mt-1.5 text-sm text-[var(--color-danger)]">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="guest-label" for="password">كلمة المرور</label>
                <input id="password" type="password" name="password" required autocomplete="new-password"
                       aria-invalid="{{ $errors->has('password') ? 'true' : 'false' }}"
                       class="guest-input">
                @error('password')
                    <p class="mt-1.5 text-sm text-[var(--color-danger)]">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="guest-label" for="password_confirmation">تأكيد كلمة المرور</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required
                       autocomplete="new-password"
                       class="guest-input">
            </div>
            <button type="submit" class="guest-submit mt-1">
                تسجيل
            </button>
        </form>
    </div>

    <p class="mt-6 text-center text-sm text-[var(--color-text-secondary)]">
        لديك حساب؟
        <a href="{{ route('login') }}" class="font-medium text-[var(--color-secondary)] hover:text-[var(--color-secondary-hover)] hover:underline">تسجيل الدخول</a>
    </p>
@endsection
