@extends('layouts.app')

@section('title', 'المحاضرون')

@section('content')
    <x-public-page-hero
        title="هيئة التدريس"
        lead="من يقود المقررات على المنصة ويرافق الطلاب في رحلتهم."
    />

    <section class="academy-section bg-[var(--color-surface)]">
        <div class="mx-auto max-w-[90rem] px-4 sm:px-6 lg:px-8">
            @if ($instructors->isEmpty())
                <x-empty-state message="لا محاضرين معروضين حالياً." />
            @else
                <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($instructors as $instructor)
                        <article class="rounded-xl border border-[var(--color-line)] bg-[var(--color-sand)]/50 p-6 transition hover:border-[var(--color-primary)]/40">
                            <div class="flex h-14 w-14 items-center justify-center rounded-full bg-[var(--color-primary-light)] text-lg font-bold text-[var(--color-secondary-hover)]">
                                {{ mb_substr($instructor->name, 0, 1) }}
                            </div>
                            <h2 class="mt-4 text-lg font-bold text-[var(--color-text)]">{{ $instructor->name }}</h2>
                            <p class="mt-1 text-sm text-[var(--color-text-secondary)]">{{ $instructor->university ?: 'محاضر في المنصة' }}</p>
                            <p class="mt-4 text-sm font-medium text-[var(--color-primary)]">{{ $instructor->published_courses_count }} مقرر منشور</p>
                            @if ($instructor->published_courses_count > 0)
                                <a href="{{ route('public.courses.index') }}" class="mt-3 inline-block text-sm font-bold text-[var(--color-primary)] hover:underline">عرض المقررات</a>
                            @endif
                        </article>
                    @endforeach
                </div>
            @endif
        </div>
    </section>
@endsection
