@extends('layouts.student')

@section('title', 'التقدم')
@section('heading', 'تقدمي الدراسي')
@section('subheading', 'إنجاز الدروس والاختبارات لكل مقرر')

@section('content')
    <div class="mx-auto max-w-5xl">
        @if ($enrollments->isEmpty())
            <div class="rounded-2xl border border-dashed border-[var(--color-line)] bg-white px-6 py-14 text-center">
                <h2 class="text-lg font-semibold text-[var(--color-ink)]">لا بيانات تقدم بعد</h2>
                <p class="mx-auto mt-2 max-w-md text-sm text-[var(--color-text-secondary)]">
                    سجّل في مقرر وابدأ الدروس لتظهر هنا نسب الإنجاز.
                </p>
                <a href="{{ route('student.course-requests.index') }}"
                   class="mt-6 inline-flex rounded-xl bg-[var(--color-primary)] px-5 py-2.5 text-sm font-semibold text-white hover:bg-[var(--color-primary-hover)]">
                    طلب مقرر
                </a>
            </div>
        @else
            <ul class="divide-y divide-[var(--color-line)] overflow-hidden rounded-2xl border border-[var(--color-line)] bg-white shadow-[0_12px_32px_-24px_rgba(15,23,42,0.35)]">
                @foreach ($enrollments as $row)
                    <li class="px-5 py-5 sm:px-6">
                        <div class="flex flex-wrap items-start justify-between gap-3">
                            <div class="min-w-0">
                                <a href="{{ route('student.courses.show', $row['course']) }}"
                                   class="font-semibold text-[var(--color-ink)] hover:text-[var(--color-primary-hover)]">
                                    {{ $row['course']->title }}
                                </a>
                                <p class="mt-1 text-sm text-[var(--color-text-secondary)]">
                                    {{ $row['completed'] }}/{{ $row['total'] }} درس
                                    · {{ $row['quizzes'] }} اختبار مُسلَّم
                                    @if ($row['hours'])
                                        · {{ $row['hours'] }} ساعة
                                    @endif
                                </p>
                            </div>
                            <span class="rounded-lg bg-[var(--color-primary-light)] px-2.5 py-1 text-sm font-semibold tabular-nums text-[var(--color-primary-hover)]">
                                {{ $row['percent'] }}%
                            </span>
                        </div>
                        <div class="mt-3 h-1.5 overflow-hidden rounded-full bg-[var(--color-line)]">
                            <div class="h-full rounded-full bg-[var(--color-primary)]" style="width: {{ $row['percent'] }}%"></div>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
@endsection
