@extends('layouts.admin')

@section('title', 'تعديل الاختبار')
@section('heading', 'تعديل الاختبار')
@section('subheading', $quiz->title)

@section('content')
    <form method="POST" action="{{ route('admin.quizzes.update', $quiz) }}" class="mx-auto max-w-2xl space-y-4 rounded-2xl border border-[var(--color-line)] bg-white p-6">
        @csrf
        @method('PUT')
        @include('admin.quizzes._form', ['quiz' => $quiz])
        <div class="flex gap-2">
            <button class="rounded-xl bg-[var(--color-primary)] px-4 py-2.5 text-sm font-semibold text-white">حفظ</button>
            <a href="{{ route('admin.quizzes.show', $quiz) }}" class="rounded-xl border px-4 py-2.5 text-sm">رجوع</a>
        </div>
    </form>
@endsection
