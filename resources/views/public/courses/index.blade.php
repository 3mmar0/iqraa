@extends('layouts.app')
@section('title', 'كتالوج المقررات')
@section('content')
    <section class="border-b border-[var(--color-line)] bg-[var(--color-sand)]">
        <div class="mx-auto max-w-6xl px-4 py-14 sm:px-6 sm:py-16">
            <h1 class="site-brand text-4xl font-bold tracking-tight text-[var(--color-ink)] sm:text-5xl">رفّ المقررات</h1>
            <p class="mt-3 max-w-2xl text-[var(--color-text-secondary)]">استعرض المقررات المنشورة واطلب الالتحاق بعد تسجيل الدخول.</p>
        </div>
    </section>
    <section class="bg-white">
        <div class="mx-auto max-w-6xl px-4 py-12 sm:px-6 sm:py-16">
            @if ($courses->isEmpty())
                <x-empty-state message="لا مقررات منشورة حالياً." />
            @else
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($courses as $index => $course)
                        @php
                            $cover = $course->image_path
                                ? asset('storage/'.$course->image_path)
                                : asset('images/home/course-cover-'.(($index % 2) + 1).'.webp');
                        @endphp
                        <a href="{{ route('public.courses.show', $course) }}" class="group flex flex-col overflow-hidden rounded-2xl border border-[var(--color-line)] bg-white shadow-[0_16px_36px_-24px_rgba(47,58,69,0.35)] transition hover:-translate-y-1 hover:border-[var(--color-primary)]/40 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[var(--color-primary)]">
                            <div class="aspect-[16/10] overflow-hidden bg-[var(--color-ink)]">
                                <img src="{{ $cover }}" alt="" class="h-full w-full object-cover transition duration-500 group-hover:scale-[1.03]" width="640" height="400" loading="lazy">
                            </div>
                            <div class="flex flex-1 flex-col border-t-4 border-[var(--color-accent)]/70 p-5">
                                <h2 class="text-lg font-semibold text-[var(--color-ink)] group-hover:text-[var(--color-primary)]">{{ $course->title }}</h2>
                                <p class="mt-1 text-sm text-[var(--color-muted)]">{{ $course->instructor?->name }}</p>
                                <p class="mt-3 line-clamp-2 flex-1 text-sm text-[var(--color-text-secondary)]">{{ $course->description }}</p>
                                <span class="mt-4 text-sm font-semibold text-[var(--color-secondary)]">التفاصيل</span>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </section>
@endsection
