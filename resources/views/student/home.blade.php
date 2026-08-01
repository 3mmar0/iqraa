@extends('layouts.student')

@section('title', 'لوحة الطالب')

@section('heading')
    مرحباً، {{ $user->name }}
@endsection

@section('subheading')
    {{ $termLabel }}
@endsection

@section('header-actions')
    <a href="{{ route('student.course-requests.index') }}"
       class="rounded-xl border border-[var(--color-line)] bg-white px-3.5 py-2 text-sm font-medium text-[var(--color-text-secondary)] hover:border-[var(--color-primary)] hover:text-[var(--color-primary)]">
        طلب مقرر
    </a>
    <a href="{{ route('student.courses.index') }}"
       class="rounded-xl bg-[var(--color-primary)] px-3.5 py-2 text-sm font-semibold text-white hover:bg-[var(--color-primary-hover)]">
        مقرراتي
    </a>
@endsection

@section('content')
    <div class="mx-auto max-w-5xl space-y-8">
        {{-- Continue learning: primary focus --}}
        @if ($lastProgress?->lesson)
            <section class="overflow-hidden rounded-2xl bg-[var(--color-ink)] text-white shadow-[0_20px_50px_-28px_rgba(15,23,42,0.55)]">
                <div class="relative px-6 py-7 sm:px-8 sm:py-8">
                    <div class="pointer-events-none absolute inset-0 opacity-50" style="background:
                        radial-gradient(ellipse 55% 80% at 100% 0%, rgba(15,118,110,0.55), transparent 55%),
                        radial-gradient(ellipse 40% 60% at 0% 100%, rgba(79,70,229,0.35), transparent 50%);"></div>
                    <div class="relative flex flex-col gap-6 sm:flex-row sm:items-end sm:justify-between">
                        <div class="min-w-0">
                            <p class="text-sm font-medium text-white/65">تابع من حيث توقفت</p>
                            <h2 class="mt-2 text-xl font-semibold leading-snug sm:text-2xl">{{ $lastProgress->lesson->title }}</h2>
                            @if ($lastProgress->lesson->course)
                                <p class="mt-2 text-sm text-white/55">{{ $lastProgress->lesson->course->title }}</p>
                            @endif
                        </div>
                        <a href="{{ route('student.lessons.show', $lastProgress->lesson) }}"
                           class="inline-flex shrink-0 items-center justify-center rounded-xl bg-white px-5 py-3 text-sm font-semibold text-[var(--color-primary)] transition hover:bg-[var(--color-primary-light)]">
                            متابعة الدرس
                        </a>
                    </div>
                </div>
            </section>
        @elseif ($courses->isEmpty())
            <section class="rounded-2xl border border-dashed border-[var(--color-line)] bg-white px-6 py-12 text-center">
                <h2 class="text-lg font-semibold text-[var(--color-ink)]">ابدأ مسارك التعليمي</h2>
                <p class="mx-auto mt-2 max-w-md text-sm leading-relaxed text-[var(--color-text-secondary)]">
                    لم تُسجَّل في أي مقرر بعد. تصفّح المقررات المنشورة وأرسل طلب التحاق — سنراجع طلبك ونفعّل وصولك.
                </p>
                <div class="mt-6 flex flex-wrap items-center justify-center gap-3">
                    <a href="{{ route('public.courses.index') }}"
                       class="rounded-xl bg-[var(--color-primary)] px-5 py-2.5 text-sm font-semibold text-white hover:bg-[var(--color-primary-hover)]">تصفّح المقررات</a>
                    <a href="{{ route('student.course-requests.index') }}"
                       class="rounded-xl border border-[var(--color-line)] px-5 py-2.5 text-sm font-medium text-[var(--color-text-secondary)] hover:border-[var(--color-primary)] hover:text-[var(--color-primary)]">طلبات الالتحاق</a>
                </div>
            </section>
        @endif

        {{-- Courses --}}
        @if ($courses->isNotEmpty())
            <section>
                <div class="mb-4 flex flex-wrap items-end justify-between gap-3">
                    <div>
                        <h2 class="text-lg font-semibold text-[var(--color-ink)]">مقرراتك النشطة</h2>
                        <p class="mt-1 text-sm text-[var(--color-text-secondary)]">
                            {{ $courses->count() }} مقرر
                            @if ($overallPercent > 0)
                                · متوسط الإنجاز {{ $overallPercent }}%
                            @endif
                        </p>
                    </div>
                    <a href="{{ route('student.progress') }}" class="text-sm font-medium text-[var(--color-primary)] hover:underline">عرض التقدم</a>
                </div>

                <ul class="divide-y divide-[var(--color-line)] overflow-hidden rounded-2xl border border-[var(--color-line)] bg-white shadow-[0_12px_32px_-24px_rgba(15,23,42,0.35)]">
                    @foreach ($courses as $course)
                        <li>
                            <a href="{{ route('student.courses.show', $course) }}"
                               class="group flex flex-col gap-4 px-5 py-5 transition hover:bg-[var(--color-sand)] sm:flex-row sm:items-center sm:justify-between sm:px-6">
                                <div class="min-w-0 flex-1">
                                    <p class="truncate font-semibold text-[var(--color-ink)] group-hover:text-[var(--color-primary-hover)]">{{ $course->title }}</p>
                                    <p class="mt-1 text-sm text-[var(--color-text-secondary)]">
                                        {{ $course->instructor?->name ?? 'محاضر المنصة' }}
                                        · {{ $course->completed_lessons_count }}/{{ $course->lessons_count }} درس
                                    </p>
                                    <div class="mt-3 h-1.5 max-w-md overflow-hidden rounded-full bg-[var(--color-line)]">
                                        <div class="h-full rounded-full bg-[var(--color-primary)] transition-all" style="width: {{ $course->progress_percent }}%"></div>
                                    </div>
                                </div>
                                <div class="flex shrink-0 items-center gap-3">
                                    <span class="text-sm font-semibold tabular-nums text-[var(--color-primary-hover)]">{{ $course->progress_percent }}%</span>
                                    <span class="text-sm font-medium text-[var(--color-primary)] opacity-0 transition group-hover:opacity-100 sm:opacity-100">دخول</span>
                                </div>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </section>
        @endif

        {{-- Quiet utility strip --}}
        <nav class="flex flex-wrap gap-x-6 gap-y-2 border-t border-[var(--color-line)] pt-6 text-sm" aria-label="اختصارات">
            <a href="{{ route('student.calendar') }}" class="font-medium text-[var(--color-text-secondary)] hover:text-[var(--color-primary)]">التقويم</a>
            <a href="{{ route('student.notifications') }}" class="font-medium text-[var(--color-text-secondary)] hover:text-[var(--color-primary)]">الإشعارات</a>
            <a href="{{ route('student.support.index') }}" class="font-medium text-[var(--color-text-secondary)] hover:text-[var(--color-primary)]">الدعم</a>
            @if ($pendingRequests > 0)
                <a href="{{ route('student.course-requests.index') }}" class="font-medium text-[var(--color-secondary)] hover:underline">
                    {{ $pendingRequests }} طلب التحاق قيد المراجعة
                </a>
            @endif
        </nav>
    </div>
@endsection
