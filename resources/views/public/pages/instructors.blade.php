@extends('layouts.app')

@section('title', 'المحاضرون')

@section('content')
    <section class="border-b border-[var(--color-line)] bg-white">
        <div class="mx-auto max-w-6xl px-4 py-16 sm:px-6">
            <h1 class="site-brand text-4xl font-bold text-[var(--color-ink)] sm:text-5xl">المحاضرون</h1>
            <p class="mt-4 max-w-2xl text-slate-600">من يقود المقررات على المنصة ويرافق الطلاب في رحلتهم.</p>
        </div>
    </section>

    <section class="mx-auto max-w-6xl px-4 py-16 sm:px-6">
        @if ($instructors->isEmpty())
            <x-empty-state message="لا محاضرين معروضين حالياً." />
        @else
            <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($instructors as $instructor)
                    <article class="rounded-2xl border border-[var(--color-line)] bg-white p-6">
                        <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-[var(--color-primary-hover)] text-lg font-bold text-white">
                            {{ mb_substr($instructor->name, 0, 1) }}
                        </div>
                        <h2 class="mt-4 text-lg font-semibold text-slate-900">{{ $instructor->name }}</h2>
                        <p class="mt-1 text-sm text-slate-500">{{ $instructor->university ?: 'محاضر في المنصة' }}</p>
                        <p class="mt-4 text-sm text-[var(--color-primary-hover)]">{{ $instructor->published_courses_count }} مقرر منشور</p>
                        @if ($instructor->published_courses_count > 0)
                            <a href="{{ route('public.courses.index') }}" class="mt-3 inline-block text-sm font-medium text-[var(--color-primary-hover)] hover:underline">عرض المقررات</a>
                        @endif
                    </article>
                @endforeach
            </div>
        @endif
    </section>
@endsection
