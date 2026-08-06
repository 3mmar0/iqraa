@extends('layouts.instructor')

@section('title', 'تعديل المقرر')
@section('heading', 'تعديل المقرر')
@section('subheading', $course->title)

@section('content')
    <x-admin.form-shell>
        <x-slot:header>
            <p class="text-sm font-semibold text-slate-800">تعديل بيانات المقرر</p>
            <p class="mt-0.5 text-xs text-slate-500">{{ $course->title }}</p>
        </x-slot:header>
        <form method="POST" action="{{ route('instructor.courses.update', $course) }}" class="space-y-5">
            @csrf
            @method('PUT')
            @include('admin.courses._form', ['course' => $course, 'coursePanel' => 'instructor', 'lockInstructor' => true])
            <div class="flex flex-wrap gap-2 border-t border-slate-100 pt-5">
                <button type="submit" class="admin-btn admin-btn-primary">حفظ التعديلات</button>
                <a href="{{ route('instructor.courses.show', $course) }}" class="admin-btn admin-btn-ghost">رجوع</a>
            </div>
        </form>
    </x-admin.form-shell>
@endsection
