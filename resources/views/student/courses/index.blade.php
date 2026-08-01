@extends('layouts.student')

@section('title', 'مقرراتي')
@section('heading', 'مقرراتي')
@section('subheading', 'المواد المسجّل فيها وتقدّمك في كل مقرر')

@section('header-actions')
    <a href="{{ route('student.course-requests.index') }}"
       class="rounded-xl bg-[var(--color-primary)] px-3.5 py-2 text-sm font-semibold text-white hover:bg-[var(--color-primary-hover)]">
        طلب مقرر
    </a>
@endsection

@section('content')
    <div class="mx-auto max-w-5xl">
        @if ($courses->isEmpty())
            <div class="rounded-2xl border border-dashed border-[var(--color-line)] bg-white px-6 py-14 text-center">
                <h2 class="text-lg font-semibold text-[var(--color-ink)]">لا مقررات بعد</h2>
                <p class="mx-auto mt-2 max-w-md text-sm text-[var(--color-text-secondary)]">
                    اطلب الالتحاق بمقرر منشور ليظهر هنا مع شريط تقدّمك.
                </p>
                <a href="{{ route('student.course-requests.index') }}"
                   class="mt-6 inline-flex rounded-xl bg-[var(--color-primary)] px-5 py-2.5 text-sm font-semibold text-white hover:bg-[var(--color-primary-hover)]">
                    طلب مقرر
                </a>
            </div>
        @else
            <ul class="divide-y divide-[var(--color-line)] overflow-hidden rounded-2xl border border-[var(--color-line)] bg-white shadow-[0_12px_32px_-24px_rgba(15,23,42,0.35)]">
                @foreach ($courses as $course)
                    <li>
                        <a href="{{ route('student.courses.show', $course) }}"
                           class="group flex flex-col gap-4 px-5 py-5 transition hover:bg-[var(--color-sand)] sm:flex-row sm:items-center sm:justify-between sm:px-6">
                            <div class="min-w-0 flex-1">
                                <p class="truncate text-base font-semibold text-[var(--color-ink)] group-hover:text-[var(--color-primary-hover)]">{{ $course->title }}</p>
                                <p class="mt-1 text-sm text-[var(--color-text-secondary)]">
                                    {{ $course->instructor?->name ?? 'محاضر المنصة' }}
                                    · {{ $course->completed_lessons_count }}/{{ $course->lessons_count }} درس مكتمل
                                </p>
                                <div class="mt-3 h-1.5 max-w-md overflow-hidden rounded-full bg-[var(--color-line)]">
                                    <div class="h-full rounded-full bg-[var(--color-primary)]" style="width: {{ $course->progress_percent }}%"></div>
                                </div>
                            </div>
                            <div class="flex shrink-0 items-center gap-4">
                                <span class="rounded-lg bg-[var(--color-primary-light)] px-2.5 py-1 text-sm font-semibold tabular-nums text-[var(--color-primary-hover)]">
                                    {{ $course->progress_percent }}%
                                </span>
                                <span class="text-sm font-medium text-[var(--color-primary)]">فتح المقرر</span>
                            </div>
                        </a>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
@endsection
