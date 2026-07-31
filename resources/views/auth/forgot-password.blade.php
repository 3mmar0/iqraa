@extends('layouts.guest')

@section('title', 'استعادة كلمة المرور')

@section('content')
    <h1 class="mb-4 text-xl font-semibold">نسيت كلمة المرور؟</h1>
    <p class="mb-4 text-sm text-slate-600">أدخل بريدك وسنرسل رابط الاستعادة.</p>
    <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
        @csrf
        <div>
            <label class="mb-1 block text-sm" for="email">البريد الإلكتروني</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required class="w-full rounded-lg border border-slate-300 px-3 py-2">
        </div>
        <button type="submit" class="w-full rounded-lg bg-teal-700 px-4 py-2 text-white">إرسال الرابط</button>
    </form>
@endsection
