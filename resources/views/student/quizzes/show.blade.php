@extends('layouts.student')

@section('title', $quiz->title)

@section('content')
    <h1 class="mb-2 text-2xl font-bold">{{ $quiz->title }}</h1>
    <p class="mb-4 text-sm text-slate-600">المدة: {{ $quiz->duration_minutes }} دقيقة · الأسئلة: {{ $quiz->questions_count }}</p>
    <form method="POST" action="{{ route('student.quizzes.start', $quiz) }}">
        @csrf
        <button class="rounded-lg bg-[var(--color-primary)] px-4 py-2 text-white">بدء الاختبار</button>
    </form>
@endsection
