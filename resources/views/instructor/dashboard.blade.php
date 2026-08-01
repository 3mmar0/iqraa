@extends('layouts.instructor')

@section('title', $title ?? 'لوحة المحاضر')

@section('content')
    <h1 class="mb-2 text-2xl font-bold text-teal-900">لوحة المحاضر</h1>
    <p class="mb-6 text-slate-600">مرحباً بك في لوحة المحاضر — نظرة سريعة على مقرراتك وطلابك.</p>

    <div class="mb-8 grid gap-4 sm:grid-cols-2">
        <div class="rounded-xl border border-slate-200 bg-white p-5">
            <p class="text-sm text-slate-500">المقررات</p>
            <p class="text-3xl font-semibold text-teal-800">{{ $coursesCount ?? 0 }}</p>
        </div>
        <div class="rounded-xl border border-slate-200 bg-white p-5">
            <p class="text-sm text-slate-500">الطلاب المسجّلون</p>
            <p class="text-3xl font-semibold text-teal-800">{{ $studentsCount ?? 0 }}</p>
        </div>
    </div>

    <div class="flex flex-wrap gap-3 text-sm">
        @if (\Illuminate\Support\Facades\Route::has('instructor.courses.index'))
            <a href="{{ route('instructor.courses.index') }}" class="rounded bg-teal-700 px-4 py-2 text-white">مقرراتي</a>
        @endif
        @if (\Illuminate\Support\Facades\Route::has('instructor.students.index'))
            <a href="{{ route('instructor.students.index') }}" class="rounded border border-teal-700 px-4 py-2 text-teal-800">قائمة الطلاب</a>
        @endif
    </div>
@endsection