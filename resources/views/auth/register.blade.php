@extends('layouts.guest')

@section('title', 'إنشاء حساب')

@section('content')
    <h1 class="mb-4 text-xl font-semibold">إنشاء حساب طالب</h1>
    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf
        <div>
            <label class="mb-1 block text-sm" for="name">الاسم</label>
            <input id="name" name="name" value="{{ old('name') }}" required class="w-full rounded-lg border border-slate-300 px-3 py-2">
        </div>
        <div>
            <label class="mb-1 block text-sm" for="email">البريد الإلكتروني</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required class="w-full rounded-lg border border-slate-300 px-3 py-2">
        </div>
        <div>
            <label class="mb-1 block text-sm" for="phone">رقم الهاتف</label>
            <input id="phone" name="phone" value="{{ old('phone') }}" class="w-full rounded-lg border border-slate-300 px-3 py-2">
        </div>
        <div>
            <label class="mb-1 block text-sm" for="university">الجامعة</label>
            <input id="university" name="university" value="{{ old('university') }}" class="w-full rounded-lg border border-slate-300 px-3 py-2">
        </div>
        <div>
            <label class="mb-1 block text-sm" for="password">كلمة المرور</label>
            <input id="password" type="password" name="password" required class="w-full rounded-lg border border-slate-300 px-3 py-2">
        </div>
        <div>
            <label class="mb-1 block text-sm" for="password_confirmation">تأكيد كلمة المرور</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required class="w-full rounded-lg border border-slate-300 px-3 py-2">
        </div>
        <button type="submit" class="w-full rounded-lg bg-[var(--color-primary)] px-4 py-2 font-medium text-white hover:bg-[var(--color-primary-hover)]">
            تسجيل
        </button>
    </form>
@endsection
