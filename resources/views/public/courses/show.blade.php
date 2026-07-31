@extends('layouts.app')
@section('title', $course->title)
@section('content')
    <h1 class="mb-2 text-2xl font-bold text-teal-900">{{ $course->title }}</h1>
    <p class="mb-4 text-slate-600">{{ $course->instructor?->name }}</p>
    <p class="mb-6 whitespace-pre-line">{{ $course->description }}</p>
    <p class="mb-6 text-sm text-slate-500">{{ $course->lessons->count() }} دروس</p>

    @auth
        @if (\Illuminate\Support\Facades\Route::has('student.course-requests.index'))
            <a href="{{ route('student.course-requests.index', ['course_id' => $course->id]) }}"
               class="inline-block rounded bg-teal-700 px-5 py-2.5 text-white">طلب الانضمام</a>
        @endif
    @else
        <a href="{{ route('login') }}" class="inline-block rounded bg-teal-700 px-5 py-2.5 text-white">طلب الانضمام</a>
        <p class="mt-2 text-sm text-slate-500">سجّل الدخول لإرسال طلب الانضمام.</p>
    @endauth
@endsection