@extends('layouts.student')

@section('title', $course->title)

@section('heading')
    {{ $course->title }}
@endsection

@section('subheading')
    {{ $course->instructor?->name ?? 'محاضر المنصة' }}
    @if ($course->hours)
        · {{ $course->hours }} ساعة
    @endif
@endsection

@section('header-actions')
    <a href="{{ route('student.courses.index') }}"
       class="rounded-xl border border-[var(--color-line)] bg-white px-3.5 py-2 text-sm font-medium text-[var(--color-text-secondary)] hover:border-[var(--color-primary)] hover:text-[var(--color-primary)]">
        كل المقررات
    </a>
@endsection

@section('content')
    <div class="mx-auto max-w-5xl space-y-8">
        <section class="rounded-2xl border border-[var(--color-line)] bg-white p-6 shadow-[0_12px_32px_-24px_rgba(15,23,42,0.35)] sm:p-7">
            @if ($course->description)
                <p class="max-w-3xl text-sm leading-relaxed text-[var(--color-text-secondary)]">{{ $course->description }}</p>
            @endif
            <div class="mt-5 flex flex-wrap items-end justify-between gap-4">
                <div>
                    <p class="text-sm text-[var(--color-text-secondary)]">
                        التقدم · {{ $completedCount }}/{{ $lessonsCount }} درس
                        @if ($course->schedule_text)
                            · {{ $course->schedule_text }}
                        @endif
                    </p>
                    <div class="mt-2 h-1.5 w-48 overflow-hidden rounded-full bg-[var(--color-line)] sm:w-64">
                        <div class="h-full rounded-full bg-[var(--color-primary)]" style="width: {{ $progressPercent }}%"></div>
                    </div>
                </div>
                <span class="rounded-lg bg-[var(--color-primary-light)] px-2.5 py-1 text-sm font-semibold tabular-nums text-[var(--color-primary-hover)]">{{ $progressPercent }}%</span>
            </div>
        </section>

        <section>
            <h2 class="mb-3 text-lg font-semibold text-[var(--color-ink)]">الدروس</h2>
            @if ($course->lessons->isEmpty())
                <div class="rounded-2xl border border-dashed border-[var(--color-line)] bg-white px-5 py-10 text-center text-sm text-[var(--color-text-secondary)]">
                    لا دروس منشورة في هذا المقرر بعد.
                </div>
            @else
                <ol class="divide-y divide-[var(--color-line)] overflow-hidden rounded-2xl border border-[var(--color-line)] bg-white shadow-[0_12px_32px_-24px_rgba(15,23,42,0.35)]">
                    @foreach ($course->lessons as $i => $lesson)
                        @php $done = in_array($lesson->id, $completedLessonIds, true); @endphp
                        <li>
                            <a href="{{ route('student.lessons.show', $lesson) }}"
                               class="group flex items-center gap-4 px-5 py-4 transition hover:bg-[var(--color-sand)] sm:px-6">
                                <span @class([
                                    'flex h-9 w-9 shrink-0 items-center justify-center rounded-xl text-sm font-semibold tabular-nums',
                                    'bg-[var(--color-primary-light)] text-[var(--color-primary-hover)]' => $done,
                                    'bg-[var(--color-sand)] text-[var(--color-text-secondary)]' => ! $done,
                                ])>{{ $i + 1 }}</span>
                                <span class="min-w-0 flex-1">
                                    <span class="block truncate font-medium text-[var(--color-ink)] group-hover:text-[var(--color-primary-hover)]">{{ $lesson->title }}</span>
                                    @if ($done)
                                        <span class="mt-0.5 block text-xs text-[var(--color-success)]">مكتمل</span>
                                    @endif
                                </span>
                                <span class="shrink-0 text-sm font-medium text-[var(--color-primary)]">فتح</span>
                            </a>
                        </li>
                    @endforeach
                </ol>
            @endif
        </section>

        <section>
            <h2 class="mb-3 text-lg font-semibold text-[var(--color-ink)]">الاختبارات</h2>
            @if ($course->quizzes->isEmpty())
                <div class="rounded-2xl border border-dashed border-[var(--color-line)] bg-white px-5 py-8 text-center text-sm text-[var(--color-text-secondary)]">
                    لا اختبارات بعد في هذا المقرر.
                </div>
            @else
                <ul class="divide-y divide-[var(--color-line)] overflow-hidden rounded-2xl border border-[var(--color-line)] bg-white shadow-[0_12px_32px_-24px_rgba(15,23,42,0.35)]">
                    @foreach ($course->quizzes as $quiz)
                        <li>
                            <a href="{{ route('student.quizzes.show', $quiz) }}"
                               class="group flex items-center justify-between gap-3 px-5 py-4 transition hover:bg-[var(--color-sand)] sm:px-6">
                                <span class="font-medium text-[var(--color-ink)] group-hover:text-[var(--color-primary-hover)]">{{ $quiz->title }}</span>
                                <span class="text-sm font-medium text-[var(--color-secondary)]">بدء</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </section>
    </div>
@endsection
