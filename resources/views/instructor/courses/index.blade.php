@extends('layouts.instructor')

@section('title', 'مقرراتي')
@section('heading', 'مقرراتي')
@section('subheading', 'إدارة محتوى مقرراتك ودروسها واختباراتها')

@section('header-actions')
    <a href="{{ route('instructor.courses.create') }}" class="inline-flex items-center gap-2 rounded-2xl bg-[var(--color-primary)] px-4 py-2.5 text-sm font-semibold text-white hover:bg-[var(--color-primary-hover)]">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
        مقرر جديد
    </a>
@endsection

@section('content')
    <div class="mx-auto max-w-6xl">
        @if ($courses->isEmpty())
            <div class="rounded-2xl border border-dashed border-[var(--color-line)] bg-white px-6 py-14 text-center">
                <p class="text-lg font-bold text-[var(--color-ink)]">لا توجد مقررات بعد</p>
                <p class="mx-auto mt-2 max-w-md text-sm text-[var(--color-text-secondary)]">أنشئ مقررك الأول أو انتظر تعيين الإدارة لك كمعلّم على مقرر قائم.</p>
                <a href="{{ route('instructor.courses.create') }}" class="mt-6 inline-flex rounded-xl bg-[var(--color-primary)] px-5 py-2.5 text-sm font-semibold text-white">إنشاء مقرر</a>
            </div>
        @else
            <ul class="grid gap-4 sm:grid-cols-2">
                @foreach ($courses as $course)
                    @php
                        $cover = $course->image_path
                            ? asset('storage/'.$course->image_path)
                            : asset('images/home/course-cover-'.(($loop->index % 2) + 1).'.webp');
                    @endphp
                    <li>
                        <a href="{{ route('instructor.courses.show', $course) }}"
                           class="group flex h-full flex-col overflow-hidden rounded-2xl border border-[var(--color-line)] bg-white shadow-[0_14px_36px_-26px_rgba(47,58,69,0.4)] transition hover:-translate-y-0.5 hover:border-[var(--color-primary)]/40">
                            <div class="relative h-36 overflow-hidden">
                                <img src="{{ $cover }}" alt="" class="h-full w-full object-cover transition duration-500 group-hover:scale-[1.03]">
                                <div class="absolute inset-0 bg-gradient-to-t from-[var(--color-ink)]/55 to-transparent"></div>
                                <div class="absolute bottom-3 right-3 left-3 flex items-center justify-between gap-2">
                                    <x-admin.status-badge :status="$course->status" />
                                    <span class="rounded-lg bg-white/90 px-2 py-0.5 text-xs font-semibold text-[var(--color-ink)]">{{ $course->enrollments_count }} طالب</span>
                                </div>
                            </div>
                            <div class="flex flex-1 flex-col p-5">
                                <h2 class="text-lg font-bold text-[var(--color-ink)] group-hover:text-[var(--color-primary-hover)]">{{ $course->title }}</h2>
                                <p class="mt-2 text-sm text-[var(--color-text-secondary)]">
                                    {{ $course->lessons_count }} درس · {{ $course->quizzes_count ?? 0 }} اختبار
                                </p>
                                <span class="mt-auto pt-4 text-sm font-semibold text-[var(--color-secondary)]">فتح المقرر ←</span>
                            </div>
                        </a>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
@endsection
