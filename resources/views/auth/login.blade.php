@extends('layouts.guest')

@section('title', 'تسجيل الدخول')

@section('content')
    <h1 class="mb-4 text-xl font-semibold">تسجيل الدخول</h1>
    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf
        <div>
            <label class="mb-1 block text-sm" for="email">البريد الإلكتروني</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                   class="w-full rounded-lg border border-slate-300 px-3 py-2">
        </div>
        <div>
            <label class="mb-1 block text-sm" for="password">كلمة المرور</label>
            <input id="password" type="password" name="password" required
                   class="w-full rounded-lg border border-slate-300 px-3 py-2">
        </div>
        <label class="flex items-center gap-2 text-sm">
            <input type="checkbox" name="remember">
            تذكرني
        </label>
        <button type="submit" class="w-full rounded-lg bg-teal-700 px-4 py-2 font-medium text-white hover:bg-teal-800">
            دخول
        </button>
    </form>
    <p class="mt-4 text-center text-sm text-slate-600">
        <a href="{{ route('password.request') }}" class="text-teal-700 hover:underline">نسيت كلمة المرور؟</a>
    </p>
    <p class="mt-2 text-center text-sm text-slate-600">
        ليس لديك حساب؟
        <a href="{{ route('register') }}" class="text-teal-700 hover:underline">إنشاء حساب</a>
    </p>
@endsection
