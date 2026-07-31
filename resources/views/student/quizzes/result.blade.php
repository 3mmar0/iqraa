@extends('layouts.app')

@section('title', 'نتيجة الاختبار')

@section('content')
    <h1 class="mb-4 text-2xl font-bold">{{ $attempt->quiz->title }}</h1>

    @if ($attempt->status === 'in_progress')
        <form method="POST" action="{{ route('student.quizzes.submit', $attempt) }}" class="space-y-6">
            @csrf
            @foreach ($attempt->quiz->questions as $question)
                <fieldset class="rounded-xl border border-slate-200 bg-white p-4">
                    <legend class="font-medium">{{ $question->body }}</legend>
                    <div class="mt-3 space-y-2">
                        @foreach ($question->options as $option)
                            <label class="flex items-center gap-2 text-sm">
                                <input type="radio" name="answers[{{ $question->id }}]" value="{{ $option->id }}" required>
                                {{ $option->body }}
                            </label>
                        @endforeach
                    </div>
                </fieldset>
            @endforeach
            <button class="rounded-lg bg-teal-700 px-4 py-2 text-white">إرسال الإجابات</button>
        </form>
    @else
        <p class="mb-4 text-lg">الدرجة: <strong>{{ $attempt->score }}%</strong></p>
        <ul class="space-y-3">
            @foreach ($attempt->quiz->questions as $question)
                @php $answer = $attempt->answers->firstWhere('question_id', $question->id); @endphp
                <li class="rounded-xl border border-slate-200 bg-white p-4 text-sm">
                    <p class="font-medium">{{ $question->body }}</p>
                    <p class="mt-1">نتيجتك: {{ $answer?->is_correct ? 'صحيحة' : 'غير صحيحة' }}</p>
                    @if ($attempt->quiz->show_correct_answers)
                        <p class="text-teal-800">الإجابة الصحيحة:
                            {{ $question->options->where('is_correct', true)->pluck('body')->join('، ') }}
                        </p>
                    @endif
                </li>
            @endforeach
        </ul>
    @endif
@endsection
