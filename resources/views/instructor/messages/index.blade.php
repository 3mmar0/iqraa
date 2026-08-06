@extends('layouts.instructor')

@section('title', 'الرسائل')
@section('heading', 'الرسائل')
@section('subheading', 'التواصل مع طلاب مقرراتك')

@section('content')
    <div class="mx-auto max-w-3xl rounded-2xl border border-[var(--color-line)] bg-white p-8 text-center shadow-[0_14px_36px_-26px_rgba(47,58,69,0.4)]">
        <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-[var(--color-secondary-light)] text-[var(--color-secondary-hover)]">
            <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg>
        </div>
        <h2 class="text-xl font-bold text-[var(--color-ink)]">صندوق الرسائل قيد التجهيز</h2>
        <p class="mx-auto mt-2 max-w-md text-sm leading-relaxed text-[var(--color-text-secondary)]">
            قريباً ستتمكن من مراسلة طلاب مقرراتك من هنا. حالياً استخدم الإعلانات لإبلاغ المجموعات.
        </p>
        <a href="{{ route('instructor.announcements.index') }}" class="mt-6 inline-flex rounded-xl bg-[var(--color-primary)] px-5 py-2.5 text-sm font-semibold text-white hover:bg-[var(--color-primary-hover)]">الذهاب للإعلانات</a>
    </div>
@endsection
