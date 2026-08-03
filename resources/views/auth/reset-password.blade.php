@extends('layouts.guest')

@section('title', 'تعيين كلمة مرور جديدة')

@section('content')
    <h1 class="text-2xl font-bold tracking-tight text-[var(--color-ink)]">كلمة مرور جديدة</h1>
    <p class="mt-2 text-sm leading-relaxed text-[var(--color-text-secondary)]">اختر كلمة مرور قوية لحسابك.</p>

    <form method="POST" action="{{ route('password.store') }}" class="mt-6 space-y-5">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <div>
            <label class="guest-label" for="email">البريد الإلكتروني</label>
            <input id="email" type="email" name="email" value="{{ old('email', $email) }}" required
                   autocomplete="email"
                   aria-invalid="{{ $errors->has('email') ? 'true' : 'false' }}"
                   class="guest-input">
            @error('email')
                <p class="mt-1.5 text-sm text-[var(--color-danger)]">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label class="guest-label" for="password">كلمة المرور</label>
            <input id="password" type="password" name="password" required autofocus
                   autocomplete="new-password"
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
        <button type="submit" class="guest-submit">حفظ</button>
    </form>
@endsection
