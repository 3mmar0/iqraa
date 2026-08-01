@extends('layouts.guest')

@section('title', 'تعيين كلمة مرور جديدة')

@section('content')
    <h1 class="mb-4 text-xl font-semibold">كلمة مرور جديدة</h1>
    <form method="POST" action="{{ route('password.store') }}" class="space-y-4">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <div>
            <label class="mb-1 block text-sm" for="email">البريد الإلكتروني</label>
            <input id="email" type="email" name="email" value="{{ old('email', $email) }}" required class="w-full rounded-lg border border-slate-300 px-3 py-2">
        </div>
        <div>
            <label class="mb-1 block text-sm" for="password">كلمة المرور</label>
            <input id="password" type="password" name="password" required class="w-full rounded-lg border border-slate-300 px-3 py-2">
        </div>
        <div>
            <label class="mb-1 block text-sm" for="password_confirmation">تأكيد كلمة المرور</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required class="w-full rounded-lg border border-slate-300 px-3 py-2">
        </div>
        <button type="submit" class="w-full rounded-lg bg-[var(--color-primary)] px-4 py-2 text-white">حفظ</button>
    </form>
@endsection
