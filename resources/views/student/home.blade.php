@extends('layouts.student')

@section('title', 'لوحة الطالب')

@section('content')
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold">مرحباً، {{ $user->name }}</h1>
            <p class="text-sm text-slate-600">{{ $termLabel }}</p>
        </div>
        <div class="flex gap-2 text-sm">
            <a href="{{ route('student.courses.index') }}" class="rounded-lg bg-[var(--color-primary)] px-3 py-2 text-white">موادي</a>
            <a href="{{ route('student.course-requests.index') }}" class="rounded-lg border border-[var(--color-primary)] px-3 py-2 text-[var(--color-primary-hover)]">طلب مقرر</a>
        </div>
    </div>

    <div class="grid gap-4 md:grid-cols-2">
        <section class="rounded-xl border border-slate-200 bg-white p-4">
            <h2 class="mb-2 font-semibold">آخر درس</h2>
            @if ($lastProgress?->lesson)
                <p>{{ $lastProgress->lesson->title }}</p>
                <a class="mt-2 inline-block text-[var(--color-primary)] hover:underline" href="{{ route('student.lessons.show', $lastProgress->lesson) }}">متابعة</a>
            @else
                <p class="text-sm text-slate-500">لا يوجد درس بعد.</p>
            @endif
        </section>

        <section class="rounded-xl border border-slate-200 bg-white p-4">
            <h2 class="mb-2 font-semibold">المواد المشتركة</h2>
            <ul class="space-y-2 text-sm">
                @forelse ($enrollments as $enrollment)
                    <li>
                        <a class="text-[var(--color-primary)] hover:underline" href="{{ route('student.courses.show', $enrollment->course) }}">
                            {{ $enrollment->course->title }}
                        </a>
                    </li>
                @empty
                    <li class="text-slate-500">لا توجد مواد. اطلب الانضمام لمقرر منشور.</li>
                @endforelse
            </ul>
        </section>
    </div>
@endsection
