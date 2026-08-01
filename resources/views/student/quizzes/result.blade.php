@extends('layouts.student')

@section('title', $attempt->status === 'in_progress' ? 'أداء الاختبار' : 'نتيجة الاختبار')

@section('heading')
    {{ $attempt->quiz->title }}
@endsection

@section('subheading')
    @if ($attempt->status === 'in_progress')
        أجب عن الأسئلة ثم أرسل المحاولة
    @else
        نتيجة محاولتك
    @endif
@endsection

@section('content')
    <div class="mx-auto max-w-3xl space-y-6">
        @if ($attempt->status === 'in_progress')
            <form method="POST" action="{{ route('student.quizzes.submit', $attempt) }}" class="space-y-5">
                @csrf
                @foreach ($attempt->quiz->questions as $qi => $question)
                    <fieldset class="rounded-2xl border border-[var(--color-line)] bg-white p-5 shadow-[0_12px_32px_-24px_rgba(15,23,42,0.35)] sm:p-6">
                        <legend class="px-1 text-base font-semibold text-[var(--color-ink)]">
                            <span class="ml-2 inline-flex h-7 w-7 items-center justify-center rounded-lg bg-[var(--color-primary-light)] text-xs font-bold text-[var(--color-primary-hover)]">{{ $qi + 1 }}</span>
                            {{ $question->body }}
                        </legend>
                        <div class="mt-4 space-y-2">
                            @foreach ($question->options as $option)
                                <label class="flex cursor-pointer items-start gap-3 rounded-xl border border-[var(--color-line)] px-4 py-3 text-sm transition hover:border-[var(--color-primary)] hover:bg-[var(--color-sand)] has-[:checked]:border-[var(--color-primary)] has-[:checked]:bg-[var(--color-primary-light)]/50">
                                    <input type="radio" name="answers[{{ $question->id }}]" value="{{ $option->id }}" required
                                           class="mt-0.5 text-[var(--color-primary)] focus:ring-[var(--color-primary)]">
                                    <span class="text-[var(--color-ink)]">{{ $option->body }}</span>
                                </label>
                            @endforeach
                        </div>
                    </fieldset>
                @endforeach
                <button type="submit" class="rounded-xl bg-[var(--color-primary)] px-6 py-3 text-sm font-semibold text-white hover:bg-[var(--color-primary-hover)]">
                    إرسال الإجابات
                </button>
            </form>
        @else
            <div class="rounded-2xl border border-[var(--color-line)] bg-white p-6 shadow-[0_12px_32px_-24px_rgba(15,23,42,0.35)] sm:flex sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-[var(--color-text-secondary)]">درجتك النهائية</p>
                    <p class="mt-1 text-3xl font-bold tabular-nums text-[var(--color-ink)]">{{ $attempt->score }}%</p>
                </div>
                @if ($attempt->quiz->course_id)
                    <a href="{{ route('student.courses.show', $attempt->quiz->course_id) }}"
                       class="mt-4 inline-flex rounded-xl border border-[var(--color-line)] px-4 py-2.5 text-sm font-medium text-[var(--color-text-secondary)] hover:border-[var(--color-primary)] hover:text-[var(--color-primary)] sm:mt-0">
                        العودة للمقرر
                    </a>
                @endif
            </div>

            <ul class="space-y-3">
                @foreach ($attempt->quiz->questions as $qi => $question)
                    @php $answer = $attempt->answers->firstWhere('question_id', $question->id); @endphp
                    <li class="rounded-2xl border border-[var(--color-line)] bg-white p-5 shadow-[0_12px_32px_-24px_rgba(15,23,42,0.35)]">
                        <p class="font-medium text-[var(--color-ink)]">
                            <span class="ml-2 text-xs font-bold text-[var(--color-muted)]">{{ $qi + 1 }}.</span>
                            {{ $question->body }}
                        </p>
                        <p @class([
                            'mt-2 text-sm font-semibold',
                            'text-[var(--color-success)]' => $answer?->is_correct,
                            'text-[var(--color-danger)]' => ! $answer?->is_correct,
                        ])>
                            {{ $answer?->is_correct ? 'إجابة صحيحة' : 'إجابة غير صحيحة' }}
                        </p>
                        @if ($attempt->quiz->show_correct_answers)
                            <p class="mt-1 text-sm text-[var(--color-text-secondary)]">
                                الإجابة الصحيحة:
                                <span class="font-medium text-[var(--color-primary-hover)]">
                                    {{ $question->options->where('is_correct', true)->pluck('body')->join('، ') }}
                                </span>
                            </p>
                        @endif
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
@endsection
