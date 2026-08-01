@extends('layouts.admin')

@section('title', 'تعديل الدرس')
@section('heading', 'تعديل الدرس')
@section('subheading', $lesson->title)

@section('content')
    <form method="POST" action="{{ route('admin.lessons.update', $lesson) }}" class="mx-auto max-w-2xl space-y-4 rounded-2xl border border-[var(--color-line)] bg-white p-6">
        @csrf
        @method('PUT')
        @include('admin.lessons._form', ['lesson' => $lesson, 'selectedCourseId' => $lesson->course_id])
        <div class="flex gap-2">
            <button class="rounded-xl bg-teal-700 px-4 py-2.5 text-sm font-semibold text-white">حفظ</button>
            <a href="{{ route('admin.lessons.index', ['course_id' => $lesson->course_id]) }}" class="rounded-xl border px-4 py-2.5 text-sm">رجوع</a>
        </div>
    </form>
@endsection
