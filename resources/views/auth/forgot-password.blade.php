@extends('layouts.guest')

@section('title', 'استعادة كلمة المرور')

@section('content')
    <h1 class="text-2xl font-bold tracking-tight text-[var(--color-ink)]">نسيت كلمة المرور؟</h1>
    <p class="mt-2 text-sm leading-relaxed text-[var(--color-text-secondary)]">أدخل بريدك وسنرسل رابط الاستعادة.</p>

    <form method="POST" action="{{ route('password.email') }}" class="mt-6 space-y-5">
        @csrf
        <div>
            <label class="guest-label" for="email">البريد الإلكتروني</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                   autocomplete="email"
                   aria-invalid="{{ $errors->has('email') ? 'true' : 'false' }}"
                   class="guest-input">
            @error('email')
                <p class="mt-1.5 text-sm text-[var(--color-danger)]">{{ $message }}</p>
            @enderror
        </div>
        <button type="submit" class="guest-submit">إرسال الرابط</button>
    </form>

    <p class="mt-6 text-center text-sm text-[var(--color-text-secondary)]">
        <a href="{{ route('login') }}" class="font-medium text-[var(--color-secondary)] hover:text-[var(--color-secondary-hover)] hover:underline">العودة لتسجيل الدخول</a>
    </p>
@endsection
