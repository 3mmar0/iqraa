@extends('layouts.student')

@section('title', 'موادي')

@section('content')
    <h1 class="mb-6 text-2xl font-bold">موادي</h1>

    @if ($courses->isEmpty())
        <div class="rounded-xl border border-dashed border-slate-300 bg-white p-8 text-center text-slate-600">
            لا توجد مواد مشتركة بعد.
            <a href="{{ route('student.course-requests.index') }}" class="mt-2 block text-teal-700 hover:underline">اطلب مقرراً</a>
        </div>
    @else
        <div class="grid gap-4 md:grid-cols-2">
            @foreach ($courses as $course)
                <article class="rounded-xl border border-slate-200 bg-white p-4">
                    <h2 class="font-semibold">{{ $course->title }}</h2>
                    <p class="text-sm text-slate-600">{{ $course->instructor?->name }}</p>
                    <p class="mt-2 text-sm">{{ $course->completed_lessons_count }}/{{ $course->lessons_count }} دروس</p>
                    <div class="mt-2 h-2 overflow-hidden rounded bg-slate-100">
                        <div class="h-full bg-teal-600" style="width: {{ $course->progress_percent }}%"></div>
                    </div>
                    <a href="{{ route('student.courses.show', $course) }}" class="mt-3 inline-block text-sm text-teal-700 hover:underline">دخول</a>
                </article>
            @endforeach
        </div>
    @endif
@endsection
