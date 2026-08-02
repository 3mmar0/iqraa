@extends('layouts.admin')

@section('title', 'تعديل الدرس')
@section('heading', 'تعديل الدرس')
@section('subheading', $lesson->title)

@section('content')
    <x-admin.form-shell max-width="max-w-2xl">
        <x-slot:header>
            <p class="text-sm font-semibold text-slate-800">تعديل الدرس</p>
            <p class="mt-0.5 text-xs text-slate-500">{{ $lesson->title }}</p>
        </x-slot:header>
        <form method="POST" action="{{ route('admin.lessons.update', $lesson) }}" class="space-y-5">
            @csrf
            @method('PUT')
            @include('admin.lessons._form', ['lesson' => $lesson, 'selectedCourseId' => $lesson->course_id])
            <div class="flex flex-wrap gap-2 border-t border-slate-100 pt-5">
                <button class="admin-btn admin-btn-primary">حفظ</button>
                <a href="{{ route('admin.lessons.index', ['course_id' => $lesson->course_id]) }}" class="admin-btn admin-btn-ghost">رجوع</a>
            </div>
        </form>
    </x-admin.form-shell>
@endsection
