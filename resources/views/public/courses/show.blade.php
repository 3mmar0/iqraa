@extends('layouts.app')
@section('title', $course->title)
@section('content')
    <div class="mx-auto max-w-3xl px-4 py-10 sm:px-6">
        <a href="{{ route('public.courses.index') }}" class="text-sm font-medium text-teal-800 hover:underline">← العودة للكتالوج</a>
        <h1 class="mt-4 site-brand text-3xl font-bold text-[var(--color-ink)] sm:text-4xl">{{ $course->title }}</h1>
        <p class="mt-2 text-slate-600">{{ $course->instructor?->name }}</p>
        <p class="mt-6 whitespace-pre-line text-slate-700 leading-relaxed">{{ $course->description }}</p>
        <p class="mt-6 text-sm text-slate-500">{{ $course->lessons->count() }} دروس</p>

        @auth
            @if (\Illuminate\Support\Facades\Route::has('student.course-requests.index'))
                <a href="{{ route('student.course-requests.index', ['course_id' => $course->id]) }}"
                   class="mt-6 inline-block rounded-xl bg-teal-700 px-5 py-2.5 font-medium text-white hover:bg-teal-800">طلب الانضمام</a>
            @endif
        @else
            <a href="{{ route('login') }}" class="mt-6 inline-block rounded-xl bg-teal-700 px-5 py-2.5 font-medium text-white hover:bg-teal-800">طلب الانضمام</a>
            <p class="mt-2 text-sm text-slate-500">سجّل الدخول لإرسال طلب الانضمام.</p>
        @endauth
    </div>
@endsection
