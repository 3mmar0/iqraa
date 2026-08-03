@extends('layouts.student')

@section('title', $course->title)

@section('heading')
    {{ $course->title }}
@endsection

@section('subheading')
    {{ $course->instructor?->name ?? 'محاضر المنصة' }}
    @if ($course->hours)
        · {{ $course->hours }} ساعة
    @endif
@endsection

@section('header-actions')
    <a href="{{ route('student.courses.index') }}"
       class="rounded-2xl border border-[var(--color-line)] bg-white px-3.5 py-2 text-sm font-medium text-[var(--color-text-secondary)] transition hover:border-[var(--color-primary)] hover:text-[var(--color-primary)]">
        كل المقررات
    </a>
    @if ($continueLesson)
        <a href="{{ route('student.lessons.show', $continueLesson) }}"
           class="rounded-2xl bg-[var(--color-primary)] px-3.5 py-2 text-sm font-semibold text-white shadow-[0_14px_28px_-18px_rgba(42,157,143,0.55)] transition hover:bg-[var(--color-primary-hover)]">
            متابعة التعلم
        </a>
    @endif
@endsection

@section('content')
    @php
        $cover = $course->image_path
            ? asset('storage/'.$course->image_path)
            : asset('images/home/course-cover-1.webp');
    @endphp

    <div class="mx-auto max-w-6xl space-y-10">
        {{-- Course overview --}}
        <section class="student-home-rise overflow-hidden rounded-2xl border border-[var(--color-line)] bg-white shadow-[0_18px_40px_-28px_rgba(47,58,69,0.4)]">
            <span class="block h-1 bg-[var(--color-accent)]" aria-hidden="true"></span>
            <div class="grid gap-0 lg:grid-cols-[minmax(0,1.1fr)_minmax(0,1.4fr)]">
                <div class="relative min-h-44 overflow-hidden bg-[var(--color-primary-light)] lg:min-h-full">
                    <img src="{{ $cover }}" alt="" class="absolute inset-0 h-full w-full object-cover" width="800" height="500">
                    <div class="absolute inset-0 bg-gradient-to-t from-[var(--color-ink)]/55 via-[var(--color-ink)]/10 to-transparent lg:bg-gradient-to-l"></div>
                </div>
                <div class="flex flex-col justify-center p-6 sm:p-8">
                    <h2 class="text-xl font-bold tracking-tight text-[var(--color-ink)] sm:text-2xl">عن المقرر</h2>
                    @if ($course->description)
                        <p class="mt-3 max-w-2xl text-sm leading-relaxed text-[var(--color-text-secondary)]">{{ $course->description }}</p>
                    @else
                        <p class="mt-3 text-sm text-[var(--color-text-secondary)]">مسار دروس واختبارات مرتّب — تابع تقدّمك من القائمة أدناه.</p>
                    @endif

                    <div class="mt-6 rounded-2xl bg-[var(--color-primary-light)]/70 px-4 py-4 sm:px-5">
                        <div class="flex flex-wrap items-end justify-between gap-3">
                            <div>
                                <p class="text-sm font-medium text-[var(--color-ink)]">تقدّمك في المقرر</p>
                                <p class="mt-1 text-sm text-[var(--color-text-secondary)]">
                                    {{ $completedCount }}/{{ $lessonsCount }} درس مكتمل
                                    @if ($course->schedule_text)
                                        · {{ $course->schedule_text }}
                                    @endif
                                </p>
                            </div>
                            <span class="rounded-lg bg-white px-2.5 py-1 text-sm font-semibold tabular-nums text-[var(--color-primary-hover)]">{{ $progressPercent }}%</span>
                        </div>
                        <div class="mt-3 h-2 overflow-hidden rounded-full bg-white/80">
                            <div class="h-full rounded-full bg-[var(--color-primary)] transition-all" style="width: {{ $progressPercent }}%"></div>
                        </div>
                    </div>

                    @if ($continueLesson)
                        <div class="mt-6 flex flex-wrap items-center gap-3">
                            <a href="{{ route('student.lessons.show', $continueLesson) }}"
                               class="inline-flex items-center justify-center rounded-2xl bg-[var(--color-primary)] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[var(--color-primary-hover)]">
                                @if ($progressPercent >= 100)
                                    مراجعة الدروس
                                @elseif ($completedCount === 0)
                                    ابدأ الدرس الأول
                                @else
                                    متابعة: {{ \Illuminate\Support\Str::limit($continueLesson->title, 28) }}
                                @endif
                            </a>
                            <a href="{{ route('student.progress') }}"
                               class="text-sm font-semibold text-[var(--color-secondary)] hover:underline">عرض كل تقدّمي</a>
                        </div>
                    @endif
                </div>
            </div>
        </section>

        {{-- Lessons path --}}
        <section class="student-home-rise-delay">
            <div class="mb-5 flex flex-wrap items-end justify-between gap-3">
                <div>
                    <h2 class="text-xl font-bold tracking-tight text-[var(--color-ink)]">مسار الدروس</h2>
                    <p class="mt-1 text-sm text-[var(--color-text-secondary)]">افتح الدرس التالي بالترتيب — المكتمل يظهر بوضوح.</p>
                </div>
                <p class="text-sm tabular-nums text-[var(--color-text-secondary)]">{{ $lessonsCount }} درس</p>
            </div>

            @if ($course->lessons->isEmpty())
                <div class="rounded-2xl border border-dashed border-[var(--color-line)] bg-white px-5 py-12 text-center">
                    <p class="text-sm text-[var(--color-text-secondary)]">لا دروس منشورة في هذا المقرر بعد.</p>
                </div>
            @else
                <ol class="divide-y divide-[var(--color-line)] overflow-hidden rounded-2xl border border-[var(--color-line)] bg-white shadow-[0_14px_36px_-26px_rgba(47,58,69,0.35)]">
                    @foreach ($course->lessons as $i => $lesson)
                        @php
                            $done = in_array($lesson->id, $completedLessonIds, true);
                            $isNext = $continueLesson && $continueLesson->id === $lesson->id && ! $done;
                        @endphp
                        <li>
                            <a href="{{ route('student.lessons.show', $lesson) }}"
                               @class([
                                   'group flex items-center gap-4 px-5 py-4 transition sm:px-6',
                                   'bg-[var(--color-primary-light)]/45 hover:bg-[var(--color-primary-light)]/70' => $isNext,
                                   'hover:bg-[var(--color-sand)]' => ! $isNext,
                               ])>
                                <span @class([
                                    'flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl text-sm font-semibold tabular-nums',
                                    'bg-[var(--color-primary)] text-white' => $done,
                                    'bg-[var(--color-primary)] text-white ring-4 ring-[var(--color-primary)]/20' => $isNext,
                                    'bg-[var(--color-sand)] text-[var(--color-text-secondary)]' => ! $done && ! $isNext,
                                ])>{{ $i + 1 }}</span>
                                <span class="min-w-0 flex-1">
                                    <span class="block truncate font-semibold text-[var(--color-ink)] group-hover:text-[var(--color-primary-hover)]">{{ $lesson->title }}</span>
                                    @if ($done)
                                        <span class="mt-0.5 block text-xs font-medium text-[var(--color-primary-hover)]">مكتمل</span>
                                    @elseif ($isNext)
                                        <span class="mt-0.5 block text-xs font-medium text-[var(--color-secondary-hover)]">الدرس التالي</span>
                                    @elseif ($lesson->description)
                                        <span class="mt-0.5 block truncate text-xs text-[var(--color-muted)]">{{ $lesson->description }}</span>
                                    @endif
                                </span>
                                <span class="shrink-0 text-sm font-medium text-[var(--color-primary)]">
                                    {{ $done ? 'مراجعة' : 'فتح' }}
                                </span>
                            </a>
                        </li>
                    @endforeach
                </ol>
            @endif
        </section>

        {{-- Quizzes + assignments --}}
        <div class="grid gap-8 lg:grid-cols-2">
            <section>
                <div class="mb-4 flex flex-wrap items-end justify-between gap-3">
                    <div>
                        <h2 class="text-xl font-bold tracking-tight text-[var(--color-ink)]">الاختبارات</h2>
                        <p class="mt-1 text-sm text-[var(--color-text-secondary)]">قيّم فهمك بعد الدروس.</p>
                    </div>
                </div>
                @if ($course->quizzes->isEmpty())
                    <div class="rounded-2xl border border-dashed border-[var(--color-line)] bg-white px-5 py-10 text-center">
                        <p class="text-sm text-[var(--color-text-secondary)]">لا اختبارات بعد في هذا المقرر.</p>
                    </div>
                @else
                    <ul class="divide-y divide-[var(--color-line)] overflow-hidden rounded-2xl border border-[var(--color-line)] bg-white shadow-[0_14px_36px_-26px_rgba(47,58,69,0.35)]">
                        @foreach ($course->quizzes as $quiz)
                            @php $attempt = $quizAttempts->get($quiz->id); @endphp
                            <li>
                                <a href="{{ route('student.quizzes.show', $quiz) }}"
                                   class="group flex items-center justify-between gap-3 px-5 py-4 transition hover:bg-[var(--color-sand)] sm:px-6">
                                    <span class="min-w-0">
                                        <span class="block font-semibold text-[var(--color-ink)] group-hover:text-[var(--color-primary-hover)]">{{ $quiz->title }}</span>
                                        <span class="mt-0.5 block text-xs text-[var(--color-text-secondary)]">
                                            @if ($quiz->duration_minutes)
                                                {{ $quiz->duration_minutes }} دقيقة
                                            @else
                                                بدون حد زمني محدد
                                            @endif
                                            @if ($attempt)
                                                · آخر نتيجة {{ number_format((float) $attempt->score, 0) }}%
                                            @endif
                                        </span>
                                    </span>
                                    <span class="shrink-0 text-sm font-medium text-[var(--color-secondary)]">
                                        {{ $attempt ? 'إعادة' : 'بدء' }}
                                    </span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </section>

            <section>
                <div class="mb-4 flex flex-wrap items-end justify-between gap-3">
                    <div>
                        <h2 class="text-xl font-bold tracking-tight text-[var(--color-ink)]">الواجبات</h2>
                        <p class="mt-1 text-sm text-[var(--color-text-secondary)]">مواعيد التسليم المرتبطة بالمقرر.</p>
                    </div>
                </div>
                @if ($assignments->isEmpty())
                    <div class="rounded-2xl border border-dashed border-[var(--color-line)] bg-white px-5 py-10 text-center">
                        <p class="text-sm text-[var(--color-text-secondary)]">لا واجبات معلنة حالياً.</p>
                    </div>
                @else
                    <ul class="divide-y divide-[var(--color-line)] overflow-hidden rounded-2xl border border-[var(--color-line)] bg-white shadow-[0_14px_36px_-26px_rgba(47,58,69,0.35)]">
                        @foreach ($assignments as $assignment)
                            <li class="px-5 py-4 sm:px-6">
                                <p class="font-semibold text-[var(--color-ink)]">{{ $assignment->title }}</p>
                                @if ($assignment->description)
                                    <p class="mt-1 line-clamp-2 text-sm text-[var(--color-text-secondary)]">{{ $assignment->description }}</p>
                                @endif
                                <p class="mt-2 text-xs text-[var(--color-muted)]">
                                    @if ($assignment->due_at)
                                        التسليم: {{ $assignment->due_at->timezone(config('app.timezone'))->format('Y/m/d H:i') }}
                                    @else
                                        بدون موعد تسليم محدد
                                    @endif
                                </p>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </section>
        </div>

        {{-- Upcoming course events --}}
        <section>
            <div class="mb-4 flex flex-wrap items-end justify-between gap-3">
                <div>
                    <h2 class="text-xl font-bold tracking-tight text-[var(--color-ink)]">مواعيد هذا المقرر</h2>
                    <p class="mt-1 text-sm text-[var(--color-text-secondary)]">المحاضرات والجلسات القادمة المرتبطة به.</p>
                </div>
                <a href="{{ route('student.calendar') }}" class="text-sm font-semibold text-[var(--color-secondary)] hover:underline">التقويم الكامل</a>
            </div>
            @if ($upcomingEvents->isEmpty())
                <div class="rounded-2xl border border-dashed border-[var(--color-line)] bg-white px-5 py-10 text-center">
                    <p class="text-sm text-[var(--color-text-secondary)]">لا مواعيد قريبة لهذا المقرر.</p>
                </div>
            @else
                <ol class="divide-y divide-[var(--color-line)] overflow-hidden rounded-2xl border border-[var(--color-line)] bg-white shadow-[0_14px_36px_-26px_rgba(47,58,69,0.35)]">
                    @foreach ($upcomingEvents as $event)
                        <li class="px-5 py-4 sm:px-6">
                            <div class="flex flex-wrap items-baseline justify-between gap-2">
                                <p class="font-semibold text-[var(--color-ink)]">{{ $event->title }}</p>
                                @if ($event->type)
                                    <span class="rounded-md bg-[var(--color-sand)] px-2 py-0.5 text-xs font-medium text-[var(--color-text-secondary)]">{{ $event->type }}</span>
                                @endif
                            </div>
                            <p class="mt-1 text-sm text-[var(--color-text-secondary)]">
                                {{ optional($event->starts_at)?->timezone(config('app.timezone'))->format('Y/m/d H:i') }}
                                @if ($event->ends_at)
                                    — {{ $event->ends_at->timezone(config('app.timezone'))->format('H:i') }}
                                @endif
                            </p>
                        </li>
                    @endforeach
                </ol>
            @endif
        </section>

        {{-- Course meta / help --}}
        <section class="rounded-2xl border border-[var(--color-line)] bg-white px-6 py-7 sm:px-8">
            <div class="flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-lg font-bold text-[var(--color-ink)]">المحاضر والمساعدة</h2>
                    <p class="mt-1 text-sm text-[var(--color-text-secondary)]">
                        {{ $course->instructor?->name ?? 'محاضر المنصة' }}
                        @if ($course->term_label)
                            · {{ $course->term_label }}
                        @endif
                    </p>
                    <p class="mt-2 max-w-xl text-sm leading-relaxed text-[var(--color-text-secondary)]">
                        إن واجهت مشكلة في الوصول أو المحتوى، تواصل مع الدعم وسنساعدك على المتابعة بطمأنينة.
                    </p>
                </div>
                <a href="{{ route('student.support.index') }}"
                   class="inline-flex shrink-0 items-center justify-center rounded-2xl border border-[var(--color-secondary)]/45 bg-[var(--color-secondary-light)] px-5 py-3 text-sm font-semibold text-[var(--color-secondary-hover)] transition hover:bg-white">
                    طلب مساعدة
                </a>
            </div>
        </section>
    </div>
@endsection
