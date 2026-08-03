@extends('layouts.guest')

@section('title', 'إنشاء حساب')

@section('content')
    <h1 class="text-2xl font-bold tracking-tight text-[var(--color-ink)]">إنشاء حساب طالب</h1>
    <p class="mt-2 text-sm leading-relaxed text-[var(--color-text-secondary)]">أنشئ حسابك ثم اطلب الالتحاق بالمقررات المناسبة لك.</p>

    <form method="POST" action="{{ route('register') }}" class="mt-6 space-y-4">
        @csrf
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

    <p class="mt-6 text-center text-sm text-[var(--color-text-secondary)]">
        لديك حساب؟
        <a href="{{ route('login') }}" class="font-medium text-[var(--color-secondary)] hover:text-[var(--color-secondary-hover)] hover:underline">تسجيل الدخول</a>
    </p>
@endsection
