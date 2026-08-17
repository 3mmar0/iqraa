@extends('layouts.app')

@section('title', 'تواصل معنا')

@section('content')
    <x-public-page-hero
        title="تواصل معنا"
        lead="سؤال عن مقرر، اقتراح، أو مساعدة قبل التسجيل — اترك رسالتك وسنعود إليك."
        :dark="true"
    />

    <section class="academy-section bg-[var(--color-sand)]">
        <div class="mx-auto max-w-xl px-4 sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('public.contact.store') }}" class="space-y-5 rounded-xl border border-[var(--color-line)] bg-[var(--color-surface)] p-6 shadow-[0_16px_36px_-24px_rgba(22,26,30,0.25)] sm:p-8">
                @csrf
                <div>
                    <label for="name" class="mb-1.5 block text-sm font-bold text-[var(--color-text)]">الاسم</label>
                    <input id="name" name="name" value="{{ old('name') }}" required class="w-full rounded-lg border border-[var(--color-line)] bg-[var(--color-sand)] px-3.5 py-2.5 text-sm focus:border-[var(--color-primary)] focus:outline-none focus:ring-2 focus:ring-[var(--color-primary)]/20">
                    @error('name')<p class="mt-1 text-xs text-[var(--color-danger)]">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="email" class="mb-1.5 block text-sm font-bold text-[var(--color-text)]">البريد</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required class="w-full rounded-lg border border-[var(--color-line)] bg-[var(--color-sand)] px-3.5 py-2.5 text-sm focus:border-[var(--color-primary)] focus:outline-none focus:ring-2 focus:ring-[var(--color-primary)]/20">
                    @error('email')<p class="mt-1 text-xs text-[var(--color-danger)]">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="phone" class="mb-1.5 block text-sm font-bold text-[var(--color-text)]">الهاتف <span class="text-[var(--color-muted)]">(اختياري)</span></label>
                    <input id="phone" name="phone" value="{{ old('phone') }}" class="w-full rounded-lg border border-[var(--color-line)] bg-[var(--color-sand)] px-3.5 py-2.5 text-sm focus:border-[var(--color-primary)] focus:outline-none focus:ring-2 focus:ring-[var(--color-primary)]/20">
                </div>
                <div>
                    <label for="message" class="mb-1.5 block text-sm font-bold text-[var(--color-text)]">الرسالة</label>
                    <textarea id="message" name="message" rows="5" required class="w-full rounded-lg border border-[var(--color-line)] bg-[var(--color-sand)] px-3.5 py-2.5 text-sm focus:border-[var(--color-primary)] focus:outline-none focus:ring-2 focus:ring-[var(--color-primary)]/20">{{ old('message') }}</textarea>
                    @error('message')<p class="mt-1 text-xs text-[var(--color-danger)]">{{ $message }}</p>@enderror
                </div>
                <button type="submit" class="academy-btn-primary w-full">إرسال الرسالة</button>
            </form>
        </div>
    </section>
@endsection
