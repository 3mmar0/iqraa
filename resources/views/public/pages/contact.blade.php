@extends('layouts.app')

@section('title', 'تواصل معنا')

@section('content')
    <section class="border-b border-[var(--color-line)] bg-white">
        <div class="mx-auto max-w-6xl px-4 py-16 sm:px-6">
            <h1 class="site-brand text-4xl font-bold text-[var(--color-ink)] sm:text-5xl">تواصل معنا</h1>
            <p class="mt-4 max-w-2xl text-slate-600">سؤال عن مقرر، اقتراح، أو مساعدة قبل التسجيل — اترك رسالتك وسنعود إليك.</p>
        </div>
    </section>

    <section class="mx-auto max-w-xl px-4 py-16 sm:px-6">
        <form method="POST" action="{{ route('public.contact.store') }}" class="space-y-5 rounded-2xl border border-[var(--color-line)] bg-white p-6 sm:p-8">
            @csrf
            <div>
                <label for="name" class="mb-1 block text-sm font-medium text-slate-700">الاسم</label>
                <input id="name" name="name" value="{{ old('name') }}" required class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20">
                @error('name')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="email" class="mb-1 block text-sm font-medium text-slate-700">البريد</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20">
                @error('email')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="phone" class="mb-1 block text-sm font-medium text-slate-700">الهاتف <span class="text-slate-400">(اختياري)</span></label>
                <input id="phone" name="phone" value="{{ old('phone') }}" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20">
            </div>
            <div>
                <label for="message" class="mb-1 block text-sm font-medium text-slate-700">الرسالة</label>
                <textarea id="message" name="message" rows="5" required class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20">{{ old('message') }}</textarea>
                @error('message')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
            </div>
            <button type="submit" class="w-full rounded-xl bg-teal-700 px-4 py-3 text-sm font-semibold text-white hover:bg-teal-800">إرسال الرسالة</button>
        </form>
    </section>
@endsection
