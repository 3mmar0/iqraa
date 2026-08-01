@extends('layouts.instructor')

@section('title', 'مقرراتي')

@section('content')
    <div class="mb-6 flex items-center justify-between gap-4">
        <h1 class="text-2xl font-bold text-teal-900">مقرراتي</h1>
        @if (\Illuminate\Support\Facades\Route::has('instructor.courses.create'))
            <a href="{{ route('instructor.courses.create') }}" class="rounded bg-teal-700 px-4 py-2 text-sm text-white">مقرر جديد</a>
        @endif
    </div>

    @if ($courses->isEmpty())
        <x-empty-state message="لا توجد مقررات بعد." />
    @else
        <ul class="space-y-3">
            @foreach ($courses as $course)
                <li class="rounded-xl border border-slate-200 bg-white p-4">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <h2 class="font-semibold">{{ $course->title }}</h2>
                            <p class="text-sm text-slate-600">{{ $course->status }} · {{ $course->lessons_count }} دروس · {{ $course->enrollments_count }} تسجيلات</p>
                        </div>
                        @if (\Illuminate\Support\Facades\Route::has('instructor.courses.show'))
                            <a href="{{ route('instructor.courses.show', $course) }}" class="text-sm text-teal-700 hover:underline">عرض</a>
                        @endif
                    </div>
                </li>
            @endforeach
        </ul>
    @endif
@endsection