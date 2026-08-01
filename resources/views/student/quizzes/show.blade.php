@extends('layouts.student')

@section('title', $quiz->title)

@section('heading')
    {{ $quiz->title }}
@endsection

@section('subheading')
    استعد قبل البدء — المدة والأسئلة أدناه
@endsection

@section('header-actions')
    @if ($quiz->course_id)
        <a href="{{ route('student.courses.show', $quiz->course_id) }}"
           class="rounded-xl border border-[var(--color-line)] bg-white px-3.5 py-2 text-sm font-medium text-[var(--color-text-secondary)] hover:border-[var(--color-primary)] hover:text-[var(--color-primary)]">
            المقرر
        </a>
    @endif
@endsection

@section('content')
    <div class="mx-auto max-w-lg">
        <section class="rounded-2xl border border-[var(--color-line)] bg-white p-6 shadow-[0_12px_32px_-24px_rgba(15,23,42,0.35)] sm:p-8">
            <dl class="grid grid-cols-2 gap-4 text-sm">
                <div class="rounded-xl bg-[var(--color-sand)] px-4 py-3">
                    <dt class="text-[var(--color-text-secondary)]">المدة</dt>
                    <dd class="mt-1 text-lg font-semibold tabular-nums text-[var(--color-ink)]">{{ $quiz->duration_minutes ?? '—' }} <span class="text-sm font-medium">دقيقة</span></dd>
                </div>
                <div class="rounded-xl bg-[var(--color-sand)] px-4 py-3">
                    <dt class="text-[var(--color-text-secondary)]">الأسئلة</dt>
                    <dd class="mt-1 text-lg font-semibold tabular-nums text-[var(--color-ink)]">{{ $quiz->questions_count }}</dd>
                </div>
            </dl>
            <p class="mt-5 text-sm leading-relaxed text-[var(--color-text-secondary)]">
                عند البدء تُفتح محاولة جديدة. أجب عن كل الأسئلة ثم أرسل قبل انتهاء الوقت إن وُجد.
            </p>
            <form method="POST" action="{{ route('student.quizzes.start', $quiz) }}" class="mt-6">
                @csrf
                <button type="submit" class="w-full rounded-xl bg-[var(--color-primary)] px-5 py-3 text-sm font-semibold text-white hover:bg-[var(--color-primary-hover)]">
                    بدء الاختبار
                </button>
            </form>
        </section>
    </div>
@endsection
