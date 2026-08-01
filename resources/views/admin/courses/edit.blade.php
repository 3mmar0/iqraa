@extends('layouts.admin')

@section('title', 'تعديل المقرر')
@section('heading', 'تعديل المقرر')
@section('subheading', $course->title)

@section('content')
    <form method="POST" action="{{ route('admin.courses.update', $course) }}" class="mx-auto max-w-3xl space-y-5 rounded-2xl border border-[var(--color-line)] bg-white p-6">
        @csrf
        @method('PUT')
        @include('admin.courses._form', ['course' => $course])
        <div class="flex gap-2">
            <button class="rounded-xl bg-[var(--color-primary)] px-4 py-2.5 text-sm font-semibold text-white">حفظ التعديلات</button>
            <a href="{{ route('admin.courses.show', $course) }}" class="rounded-xl border px-4 py-2.5 text-sm">رجوع</a>
        </div>
    </form>
@endsection
