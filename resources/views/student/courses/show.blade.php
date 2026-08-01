@extends('layouts.student')

@section('title', $course->title)

@section('content')
    <h1 class="mb-2 text-2xl font-bold">{{ $course->title }}</h1>
    <p class="mb-6 text-slate-600">{{ $course->description }}</p>
    <p class="mb-4 text-sm">الجدول: {{ $course->schedule_text ?? '—' }} · الساعات: {{ $course->hours }}</p>

    <h2 class="mb-2 font-semibold">الدروس</h2>
    <ul class="mb-6 space-y-2">
        @foreach ($course->lessons as $lesson)
            <li>
                <a class="text-teal-700 hover:underline" href="{{ route('student.lessons.show', $lesson) }}">{{ $lesson->title }}</a>
            </li>
        @endforeach
    </ul>

    <h2 class="mb-2 font-semibold">الاختبارات</h2>
    <ul class="space-y-2">
        @forelse ($course->quizzes as $quiz)
            <li>
                <a class="text-teal-700 hover:underline" href="{{ route('student.quizzes.show', $quiz) }}">{{ $quiz->title }}</a>
            </li>
        @empty
            <li class="text-sm text-slate-500">لا اختبارات بعد.</li>
        @endforelse
    </ul>
@endsection
