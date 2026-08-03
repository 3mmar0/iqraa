@extends('layouts.app')

@section('title', 'تواصل معنا')

@section('content')
    <section class="border-b border-[var(--color-line)] bg-[var(--color-primary-light)]/55">
        <div class="mx-auto max-w-6xl px-4 py-16 sm:px-6 sm:py-20">
            <h1 class="site-brand text-4xl font-bold tracking-tight text-[var(--color-ink)] sm:text-5xl md:text-6xl">تواصل معنا</h1>
            <p class="mt-4 max-w-2xl text-base text-[var(--color-text-secondary)] sm:text-lg">سؤال عن مقرر، اقتراح، أو مساعدة قبل التسجيل — اترك رسالتك وسنعود إليك.</p>
        </div>
    </section>

    <section class="bg-[var(--color-sand)]">
        <div class="mx-auto max-w-xl px-4 py-16 sm:px-6 sm:py-20">
            <form method="POST" action="{{ route('public.contact.store') }}" class="space-y-5 rounded-2xl border border-[var(--color-line)] bg-white p-6 shadow-[0_16px_36px_-24px_rgba(47,58,69,0.3)] sm:p-8">
                @csrf
                <div>
                    <label for="name" class="mb-1.5 block text-sm font-medium text-[var(--color-ink)]">الاسم</label>
                    <input id="name" name="name" value="{{ old('name') }}" required class="w-full rounded-2xl border border-[var(--color-line)] px-3.5 py-2.5 text-sm text-[var(--color-ink)] focus:border-[var(--color-primary)] focus:outline-none focus:ring-2 focus:ring-[var(--color-primary)]/20">
                    @error('name')<p class="mt-1 text-xs text-[var(--color-danger)]">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="email" class="mb-1.5 block text-sm font-medium text-[var(--color-ink)]">البريد</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required class="w-full rounded-2xl border border-[var(--color-line)] px-3.5 py-2.5 text-sm text-[var(--color-ink)] focus:border-[var(--color-primary)] focus:outline-none focus:ring-2 focus:ring-[var(--color-primary)]/20">
                    @error('email')<p class="mt-1 text-xs text-[var(--color-danger)]">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="phone" class="mb-1.5 block text-sm font-medium text-[var(--color-ink)]">الهاتف <span class="text-[var(--color-muted)]">(اختياري)</span></label>
                    <input id="phone" name="phone" value="{{ old('phone') }}" class="w-full rounded-2xl border border-[var(--color-line)] px-3.5 py-2.5 text-sm text-[var(--color-ink)] focus:border-[var(--color-primary)] focus:outline-none focus:ring-2 focus:ring-[var(--color-primary)]/20">
                </div>
                <div>
                    <label for="message" class="mb-1.5 block text-sm font-medium text-[var(--color-ink)]">الرسالة</label>
                    <textarea id="message" name="message" rows="5" required class="w-full rounded-2xl border border-[var(--color-line)] px-3.5 py-2.5 text-sm text-[var(--color-ink)] focus:border-[var(--color-primary)] focus:outline-none focus:ring-2 focus:ring-[var(--color-primary)]/20">{{ old('message') }}</textarea>
                    @error('message')<p class="mt-1 text-xs text-[var(--color-danger)]">{{ $message }}</p>@enderror
                </div>
                <button type="submit" class="w-full rounded-2xl bg-[var(--color-primary)] px-4 py-3 text-sm font-semibold text-white hover:bg-[var(--color-primary-hover)]">إرسال الرسالة</button>
            </form>
        </div>
    </section>
@endsection
