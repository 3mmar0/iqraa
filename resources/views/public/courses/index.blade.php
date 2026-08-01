@extends('layouts.app')
@section('title', 'كتالوج المقررات')
@section('content')
    <div class="mx-auto max-w-6xl px-4 py-10 sm:px-6">
        <div class="mb-8">
            <h1 class="site-brand text-3xl font-bold text-[var(--color-ink)] sm:text-4xl">كتالوج المقررات</h1>
            <p class="mt-2 text-slate-600">استعرض المقررات المنشورة واطلب الالتحاق بعد تسجيل الدخول.</p>
        </div>
        @if ($courses->isEmpty())
            <x-empty-state message="لا مقررات منشورة حالياً." />
        @else
            <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($courses as $course)
                    <a href="{{ route('public.courses.show', $course) }}" class="group block overflow-hidden rounded-2xl border border-[var(--color-line)] bg-white transition hover:-translate-y-0.5 hover:border-[var(--color-primary)] hover:shadow-[0_16px_40px_-24px_rgba(12,31,28,0.45)]">
                        <div class="h-28 bg-gradient-to-br from-[var(--color-primary-hover)] via-[var(--color-primary)] to-[var(--color-ink)]"></div>
                        <div class="p-5">
                            <h2 class="text-lg font-semibold text-slate-900">{{ $course->title }}</h2>
                            <p class="mt-1 text-sm text-slate-500">{{ $course->instructor?->name }}</p>
                            <p class="mt-3 line-clamp-2 text-sm text-slate-600">{{ $course->description }}</p>
                            <span class="mt-4 inline-block text-sm font-medium text-[var(--color-primary-hover)] group-hover:underline">التفاصيل</span>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
@endsection
