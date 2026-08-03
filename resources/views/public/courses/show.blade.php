@extends('layouts.app')
@section('title', $course->title)
@section('content')
    <section class="border-b border-[var(--color-line)] bg-[var(--color-sand)]">
        <div class="mx-auto max-w-3xl px-4 py-12 sm:px-6 sm:py-16">
            <a href="{{ route('public.courses.index') }}" class="text-sm font-semibold text-[var(--color-secondary)] hover:underline">← العودة للكتالوج</a>
            <h1 class="mt-5 site-brand text-3xl font-bold tracking-tight text-[var(--color-ink)] sm:text-4xl md:text-5xl">{{ $course->title }}</h1>
            <p class="mt-3 text-[var(--color-text-secondary)]">{{ $course->instructor?->name }}</p>
            <p class="mt-2 text-sm font-medium text-[var(--color-accent)]">{{ $course->lessons->count() }} دروس</p>
        </div>
    </section>
    <section class="bg-white">
        <div class="mx-auto max-w-3xl px-4 py-12 sm:px-6">
            <p class="whitespace-pre-line leading-relaxed text-[var(--color-text-secondary)]">{{ $course->description }}</p>

            @auth
                @if (\Illuminate\Support\Facades\Route::has('student.course-requests.index'))
                    <a href="{{ route('student.course-requests.index', ['course_id' => $course->id]) }}"
                       class="mt-8 inline-flex rounded-2xl bg-[var(--color-primary)] px-5 py-3 text-sm font-semibold text-white hover:bg-[var(--color-primary-hover)]">طلب الانضمام</a>
                @endif
            @else
                <a href="{{ route('login') }}" class="mt-8 inline-flex rounded-2xl bg-[var(--color-primary)] px-5 py-3 text-sm font-semibold text-white hover:bg-[var(--color-primary-hover)]">طلب الانضمام</a>
                <p class="mt-3 text-sm text-[var(--color-muted)]">سجّل الدخول لإرسال طلب الانضمام.</p>
            @endauth
        </div>
    </section>
@endsection
