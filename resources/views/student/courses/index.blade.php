@extends('layouts.student')

@section('title', 'مقرراتي')
@section('heading', 'مقرراتي')
@section('subheading', 'المواد المسجّل فيها وتقدّمك في كل مقرر')

@section('header-actions')
    <a href="{{ route('public.courses.index') }}"
       class="rounded-2xl border border-[var(--color-line)] bg-white px-3.5 py-2 text-sm font-medium text-[var(--color-text-secondary)] transition hover:border-[var(--color-primary)] hover:text-[var(--color-primary)]">
        الكتالوج
    </a>
    <a href="{{ route('student.course-requests.index') }}"
       class="rounded-2xl bg-[var(--color-primary)] px-3.5 py-2 text-sm font-semibold text-white shadow-[0_14px_28px_-18px_rgba(42,157,143,0.55)] transition hover:bg-[var(--color-primary-hover)]">
        طلب مقرر
    </a>
@endsection

@section('content')
    <div class="mx-auto max-w-6xl space-y-10">
        @if ($courses->isEmpty())
            <section class="student-home-rise overflow-hidden rounded-2xl border border-[var(--color-line)] bg-white shadow-[0_18px_40px_-28px_rgba(47,58,69,0.35)]">
                <div class="relative px-6 py-14 text-center sm:px-10">
                    <div class="pointer-events-none absolute inset-0 opacity-40" style="background-image: url('{{ asset('images/home/reading-room-wash.webp') }}'); background-size: cover;"></div>
                    <div class="relative">
                        <h2 class="text-xl font-bold text-[var(--color-ink)] sm:text-2xl">لا مقررات بعد</h2>
                        <p class="mx-auto mt-3 max-w-lg text-sm leading-relaxed text-[var(--color-text-secondary)]">
                            اطلب الالتحاق بمقرر منشور ليظهر هنا مع شريط تقدّمك. الالتحاق يُراجع قبل تفعيل الوصول.
                        </p>
                        <div class="mt-7 flex flex-wrap items-center justify-center gap-3">
                            <a href="{{ route('student.course-requests.index') }}"
                               class="rounded-2xl bg-[var(--color-primary)] px-5 py-3 text-sm font-semibold text-white hover:bg-[var(--color-primary-hover)]">طلب مقرر</a>
                            <a href="{{ route('public.courses.index') }}"
                               class="rounded-2xl border border-[var(--color-secondary)]/40 bg-white px-5 py-3 text-sm font-medium text-[var(--color-secondary)] hover:bg-[var(--color-secondary-light)]">تصفّح الكتالوج</a>
                        </div>
                    </div>
                </div>
            </section>
        @else
            <section class="student-home-rise rounded-2xl border border-[var(--color-line)] bg-[var(--color-primary-light)]/55 px-5 py-5 sm:px-7">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="text-lg font-bold text-[var(--color-ink)]">رفّ مقرراتك</h2>
                        <p class="mt-1 text-sm text-[var(--color-text-secondary)]">
                            {{ $courses->count() }} مقرر نشط
                            @if ($overallPercent > 0)
                                · متوسط الإنجاز {{ $overallPercent }}%
                            @endif
                            @if ($inProgress->isNotEmpty())
                                · {{ $inProgress->count() }} قيد التقدّم
                            @endif
                            @if ($completedCourses->isNotEmpty())
                                · {{ $completedCourses->count() }} مكتمل
                            @endif
                        </p>
                    </div>
                    <a href="{{ route('student.progress') }}"
                       class="inline-flex shrink-0 text-sm font-semibold text-[var(--color-secondary)] hover:underline">عرض التقدم التفصيلي</a>
                </div>
            </section>

            @php
                $sections = [
                    ['items' => $inProgress, 'title' => 'قيد التقدّم', 'hint' => 'واصل من حيث توقفت في هذه المقررات.'],
                    ['items' => $notStarted, 'title' => 'لم تبدأ بعد', 'hint' => 'افتح المقرر وابدأ الدرس الأول.'],
                    ['items' => $completedCourses, 'title' => 'مكتملة', 'hint' => 'يمكنك مراجعة الدروس والاختبارات في أي وقت.'],
                ];
            @endphp

            @foreach ($sections as $section)
                @continue($section['items']->isEmpty())
                <section @class(['student-home-rise-delay' => $loop->first])>
                    <div class="mb-5 flex flex-wrap items-end justify-between gap-3">
                        <div>
                            <h2 class="text-xl font-bold tracking-tight text-[var(--color-ink)]">{{ $section['title'] }}</h2>
                            <p class="mt-1 text-sm text-[var(--color-text-secondary)]">{{ $section['hint'] }}</p>
                        </div>
                        <p class="text-sm tabular-nums text-[var(--color-text-secondary)]">{{ $section['items']->count() }}</p>
                    </div>

                    <ul class="space-y-3">
                        @foreach ($section['items'] as $course)
                            @php
                                $cover = $course->image_path
                                    ? asset('storage/'.$course->image_path)
                                    : asset('images/home/course-cover-'.(($loop->index % 2) + 1).'.webp');
                            @endphp
                            <li>
                                <a href="{{ route('student.courses.show', $course) }}"
                                   class="group block overflow-hidden rounded-2xl border border-[var(--color-line)] bg-white shadow-[0_14px_36px_-26px_rgba(47,58,69,0.4)] transition hover:border-[var(--color-primary)]/35 hover:shadow-[0_18px_40px_-24px_rgba(42,157,143,0.35)]">
                                    <span class="block h-1 bg-[var(--color-accent)]" aria-hidden="true"></span>
                                    <div class="flex min-w-0 flex-col gap-4 p-4 sm:flex-row sm:items-center sm:gap-5 sm:p-5">
                                        <img src="{{ $cover }}" alt="" class="h-24 w-full rounded-xl object-cover sm:h-20 sm:w-32" width="128" height="80">
                                        <div class="min-w-0 flex-1">
                                            <p class="truncate text-base font-semibold text-[var(--color-ink)] group-hover:text-[var(--color-primary-hover)]">{{ $course->title }}</p>
                                            <p class="mt-1 text-sm text-[var(--color-text-secondary)]">
                                                {{ $course->instructor?->name ?? 'محاضر المنصة' }}
                                                · {{ $course->completed_lessons_count }}/{{ $course->lessons_count }} درس
                                                @if ($course->term_label)
                                                    · {{ $course->term_label }}
                                                @endif
                                            </p>
                                            <div class="mt-3 h-1.5 max-w-md overflow-hidden rounded-full bg-[var(--color-line)]">
                                                <div class="h-full rounded-full bg-[var(--color-primary)] transition-all" style="width: {{ $course->progress_percent }}%"></div>
                                            </div>
                                        </div>
                                        <div class="flex shrink-0 items-center gap-3 sm:flex-col sm:items-end sm:gap-1">
                                            <span class="rounded-lg bg-[var(--color-primary-light)] px-2.5 py-1 text-sm font-semibold tabular-nums text-[var(--color-primary-hover)]">{{ $course->progress_percent }}%</span>
                                            <span class="text-sm font-medium text-[var(--color-primary)]">فتح المقرر</span>
                                        </div>
                                    </div>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </section>
            @endforeach
        @endif

        @if ($pendingRequests->isNotEmpty())
            <section>
                <div class="mb-4 flex flex-wrap items-end justify-between gap-3">
                    <div>
                        <h2 class="text-xl font-bold tracking-tight text-[var(--color-ink)]">طلبات قيد المراجعة</h2>
                        <p class="mt-1 text-sm text-[var(--color-text-secondary)]">ستظهر المقررات هنا بعد الموافقة على الالتحاق.</p>
                    </div>
                    <a href="{{ route('student.course-requests.index') }}" class="text-sm font-semibold text-[var(--color-secondary)] hover:underline">إدارة الطلبات</a>
                </div>
                <ul class="divide-y divide-[var(--color-line)] overflow-hidden rounded-2xl border border-[var(--color-line)] bg-white shadow-[0_14px_36px_-26px_rgba(47,58,69,0.35)]">
                    @foreach ($pendingRequests as $request)
                        <li class="flex flex-wrap items-center justify-between gap-3 px-5 py-4 sm:px-6">
                            <div class="min-w-0">
                                <p class="font-semibold text-[var(--color-ink)]">{{ $request->course?->title ?? 'مقرر' }}</p>
                                <p class="mt-0.5 text-sm text-[var(--color-text-secondary)]">أُرسل {{ $request->created_at?->diffForHumans() }}</p>
                            </div>
                            <span class="rounded-lg bg-[var(--color-secondary-light)] px-2.5 py-1 text-xs font-semibold text-[var(--color-secondary-hover)]">قيد المراجعة</span>
                        </li>
                    @endforeach
                </ul>
            </section>
        @endif

        @if ($discoverCourses->isNotEmpty())
            <section>
                <div class="mb-5 flex flex-wrap items-end justify-between gap-3">
                    <div>
                        <h2 class="text-xl font-bold tracking-tight text-[var(--color-ink)]">أضف إلى مسارك</h2>
                        <p class="mt-1 text-sm text-[var(--color-text-secondary)]">مقررات منشورة يمكنك طلب الالتحاق بها.</p>
                    </div>
                    <a href="{{ route('public.courses.index') }}" class="text-sm font-semibold text-[var(--color-secondary)] hover:underline">عرض الكتالوج</a>
                </div>
                <ul class="grid gap-4 sm:grid-cols-3">
                    @foreach ($discoverCourses as $course)
                        @php
                            $cover = $course->image_path
                                ? asset('storage/'.$course->image_path)
                                : asset('images/home/course-cover-'.(($loop->index % 2) + 1).'.webp');
                        @endphp
                        <li>
                            <a href="{{ route('public.courses.show', $course) }}"
                               class="group block overflow-hidden rounded-2xl border border-[var(--color-line)] bg-white shadow-[0_14px_36px_-26px_rgba(47,58,69,0.35)] transition hover:border-[var(--color-primary)]/35">
                                <span class="block h-1 bg-[var(--color-accent)]" aria-hidden="true"></span>
                                <img src="{{ $cover }}" alt="" class="aspect-[16/10] w-full object-cover" width="640" height="400">
                                <div class="p-4">
                                    <p class="line-clamp-2 font-semibold text-[var(--color-ink)] group-hover:text-[var(--color-primary-hover)]">{{ $course->title }}</p>
                                    <p class="mt-1 text-sm text-[var(--color-text-secondary)]">{{ $course->instructor?->name ?? 'محاضر المنصة' }}</p>
                                    <span class="mt-3 inline-block text-sm font-medium text-[var(--color-primary)]">عرض التفاصيل</span>
                                </div>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </section>
        @endif
    </div>
@endsection
