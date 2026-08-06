@extends('layouts.guest')

@section('title', 'استعادة كلمة المرور')

@section('content')
    <header class="guest-head">
        <h1 class="guest-title">استعادة كلمة المرور</h1>
        <p class="guest-lead">أدخل بريدك وسنرسل رابطاً آمناً لتعيين كلمة مرور جديدة.</p>
    </header>

    <form method="POST" action="{{ route('password.email') }}" class="guest-form">
        @csrf
        <div class="guest-field">
            <label class="guest-label" for="email">البريد الإلكتروني</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                   autocomplete="email"
                   placeholder="name@example.com"
                   aria-invalid="{{ $errors->has('email') ? 'true' : 'false' }}"
                   class="guest-input">
            @error('email')
                <p class="guest-error">{{ $message }}</p>
            @enderror
        </div>
        <button type="submit" class="guest-submit">إرسال رابط الاستعادة</button>
    </form>

    <p class="guest-switch">
        <a href="{{ route('login') }}" class="guest-text-link">العودة لتسجيل الدخول</a>
    </p>
@endsection
