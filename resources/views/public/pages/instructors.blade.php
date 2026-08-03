@extends('layouts.app')

@section('title', 'المحاضرون')

@section('content')
    <section class="border-b border-[var(--color-line)] bg-[var(--color-sand)]">
        <div class="mx-auto max-w-6xl px-4 py-16 sm:px-6 sm:py-20">
            <h1 class="site-brand text-4xl font-bold tracking-tight text-[var(--color-ink)] sm:text-5xl md:text-6xl">المحاضرون</h1>
            <p class="mt-4 max-w-2xl text-base text-[var(--color-text-secondary)] sm:text-lg">من يقود المقررات على المنصة ويرافق الطلاب في رحلتهم.</p>
        </div>
    </section>

    <section class="bg-white">
        <div class="mx-auto max-w-6xl px-4 py-16 sm:px-6 sm:py-20">
            @if ($instructors->isEmpty())
                <x-empty-state message="لا محاضرين معروضين حالياً." />
            @else
                <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($instructors as $instructor)
                        <article class="rounded-2xl border border-[var(--color-line)] bg-[var(--color-sand)]/50 p-6 transition hover:border-[var(--color-secondary)]/35 hover:bg-white">
                            <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-[var(--color-secondary-light)] text-lg font-bold text-[var(--color-secondary-hover)]">
                                {{ mb_substr($instructor->name, 0, 1) }}
                            </div>
                            <h2 class="mt-4 text-lg font-semibold text-[var(--color-ink)]">{{ $instructor->name }}</h2>
                            <p class="mt-1 text-sm text-[var(--color-text-secondary)]">{{ $instructor->university ?: 'محاضر في المنصة' }}</p>
                            <p class="mt-4 text-sm font-medium text-[var(--color-accent)]">{{ $instructor->published_courses_count }} مقرر منشور</p>
                            @if ($instructor->published_courses_count > 0)
                                <a href="{{ route('public.courses.index') }}" class="mt-3 inline-block text-sm font-semibold text-[var(--color-secondary)] hover:underline">عرض المقررات</a>
                            @endif
                        </article>
                    @endforeach
                </div>
            @endif
        </div>
    </section>
@endsection
