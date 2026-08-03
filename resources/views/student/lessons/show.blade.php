@extends('layouts.student')

@section('title', $lesson->title)

@section('heading')
    {{ $lesson->title }}
@endsection

@section('subheading')
    {{ $lesson->course?->title }}
    @if ($position && $total)
        · الدرس {{ $position }} من {{ $total }}
    @endif
@endsection

@section('header-actions')
    @if ($lesson->course)
        <a href="{{ route('student.courses.show', $lesson->course) }}"
           class="rounded-2xl border border-[var(--color-line)] bg-white px-3.5 py-2 text-sm font-medium text-[var(--color-text-secondary)] transition hover:border-[var(--color-primary)] hover:text-[var(--color-primary)]">
            المقرر
        </a>
    @endif
    @if ($next)
        <a href="{{ route('student.lessons.show', $next) }}"
           class="rounded-2xl bg-[var(--color-primary)] px-3.5 py-2 text-sm font-semibold text-white shadow-[0_14px_28px_-18px_rgba(42,157,143,0.55)] transition hover:bg-[var(--color-primary-hover)]">
            الدرس التالي
        </a>
    @endif
@endsection

@section('content')
    @php
        $cover = $lesson->course?->image_path
            ? asset('storage/'.$lesson->course->image_path)
            : asset('images/home/course-cover-1.webp');
        $pathPercent = $total > 0 ? (int) round((count($completedLessonIds) / $total) * 100) : 0;
    @endphp

    <div class="mx-auto max-w-6xl space-y-8">
        <div class="grid gap-8 lg:grid-cols-[minmax(0,1.65fr)_minmax(16rem,0.85fr)]">
            <div class="space-y-8">
                {{-- Lesson intro --}}
                <section class="student-home-rise overflow-hidden rounded-2xl border border-[var(--color-line)] bg-white shadow-[0_18px_40px_-28px_rgba(47,58,69,0.4)]">
                    <span class="block h-1 bg-[var(--color-accent)]" aria-hidden="true"></span>
                    <div class="relative overflow-hidden">
                        <div class="absolute inset-0 opacity-30" style="background-image: url('{{ $cover }}'); background-size: cover; background-position: center;"></div>
                        <div class="absolute inset-0 bg-gradient-to-t from-white via-white/90 to-white/70"></div>
                        <div class="relative px-6 py-7 sm:px-8 sm:py-8">
                            <div class="flex flex-wrap items-center gap-2">
                                @if ($isCompleted)
                                    <span class="rounded-lg bg-[var(--color-primary-light)] px-2.5 py-1 text-xs font-semibold text-[var(--color-primary-hover)]">مكتمل</span>
                                @else
                                    <span class="rounded-lg bg-[var(--color-secondary-light)] px-2.5 py-1 text-xs font-semibold text-[var(--color-secondary-hover)]">قيد الدراسة</span>
                                @endif
                                @if ($position && $total)
                                    <span class="text-xs font-medium tabular-nums text-[var(--color-text-secondary)]">الدرس {{ $position }} / {{ $total }}</span>
                                @endif
                            </div>
                            <h2 class="mt-3 text-xl font-bold tracking-tight text-[var(--color-ink)] sm:text-2xl">{{ $lesson->title }}</h2>
                            @if ($lesson->description)
                                <p class="mt-3 max-w-2xl text-sm leading-relaxed text-[var(--color-text-secondary)]">{{ $lesson->description }}</p>
                            @else
                                <p class="mt-3 max-w-2xl text-sm leading-relaxed text-[var(--color-text-secondary)]">
                                    راجع المواد أدناه، ثم علّم الدرس كمكتمل عندما تنتهي.
                                </p>
                            @endif
                            @if ($lesson->course?->instructor)
                                <p class="mt-4 text-sm text-[var(--color-muted)]">المحاضر: {{ $lesson->course->instructor->name }}</p>
                            @endif
                        </div>
                    </div>
                </section>

                {{-- Videos first --}}
                @if ($videos->isNotEmpty())
                    <section class="student-home-rise-delay">
                        <div class="mb-4">
                            <h2 class="text-xl font-bold tracking-tight text-[var(--color-ink)]">الفيديو</h2>
                            <p class="mt-1 text-sm text-[var(--color-text-secondary)]">شغّل محتوى الدرس المرئي من هنا.</p>
                        </div>
                        <ul class="space-y-3">
                            @foreach ($videos as $asset)
                                <li>
                                    <a href="{{ route('student.media.show', $asset) }}"
                                       class="group flex items-center gap-4 overflow-hidden rounded-2xl border border-[var(--color-line)] bg-[var(--color-ink)] p-4 text-white shadow-[0_14px_36px_-26px_rgba(47,58,69,0.45)] transition hover:border-[var(--color-primary)] sm:p-5">
                                        <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-[var(--color-primary)] text-white">
                                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M8 5.14v13.72L19 12 8 5.14z"/></svg>
                                        </span>
                                        <span class="min-w-0 flex-1">
                                            <span class="block truncate font-semibold">{{ $asset->original_name ?? basename($asset->path) }}</span>
                                            <span class="mt-0.5 block text-xs text-white/60">فيديو · فتح للتشغيل</span>
                                        </span>
                                        <span class="shrink-0 text-sm font-medium text-[var(--color-primary-light)]">تشغيل</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </section>
                @endif

                {{-- Files / attachments --}}
                <section>
                    <div class="mb-4">
                        <h2 class="text-xl font-bold tracking-tight text-[var(--color-ink)]">مواد الدرس</h2>
                        <p class="mt-1 text-sm text-[var(--color-text-secondary)]">ملفات PDF والمرفقات المرتبطة بهذا الدرس.</p>
                    </div>
                    @if ($files->isEmpty() && $videos->isEmpty())
                        <div class="rounded-2xl border border-dashed border-[var(--color-line)] bg-white px-5 py-12 text-center">
                            <p class="text-sm text-[var(--color-text-secondary)]">لا توجد ملفات مرفقة لهذا الدرس بعد.</p>
                        </div>
                    @elseif ($files->isEmpty())
                        <div class="rounded-2xl border border-dashed border-[var(--color-line)] bg-white px-5 py-8 text-center">
                            <p class="text-sm text-[var(--color-text-secondary)]">لا مرفقات إضافية — الفيديو أعلاه هو محتوى الدرس.</p>
                        </div>
                    @else
                        <ul class="divide-y divide-[var(--color-line)] overflow-hidden rounded-2xl border border-[var(--color-line)] bg-white shadow-[0_14px_36px_-26px_rgba(47,58,69,0.35)]">
                            @foreach ($files as $asset)
                                @php
                                    $typeLabel = match ($asset->type) {
                                        'pdf' => 'PDF',
                                        'attachment' => 'مرفق',
                                        default => $asset->type,
                                    };
                                @endphp
                                <li>
                                    <a href="{{ route('student.media.show', $asset) }}"
                                       class="group flex items-center justify-between gap-3 px-5 py-4 transition hover:bg-[var(--color-sand)] sm:px-6">
                                        <span class="min-w-0">
                                            <span class="block truncate font-semibold text-[var(--color-ink)] group-hover:text-[var(--color-primary-hover)]">{{ $asset->original_name ?? basename($asset->path) }}</span>
                                            <span class="mt-0.5 block text-xs text-[var(--color-text-secondary)]">{{ $typeLabel }}</span>
                                        </span>
                                        <span class="shrink-0 text-sm font-medium text-[var(--color-primary)]">فتح</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </section>

                {{-- Linked quiz --}}
                @if ($lesson->quiz && $lesson->quiz->status === 'published')
                    <section class="rounded-2xl border border-[var(--color-line)] bg-[var(--color-secondary-light)]/50 px-5 py-6 sm:px-6">
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <h2 class="text-lg font-bold text-[var(--color-ink)]">اختبار مرتبط بهذا الدرس</h2>
                                <p class="mt-1 text-sm text-[var(--color-text-secondary)]">{{ $lesson->quiz->title }}</p>
                            </div>
                            <a href="{{ route('student.quizzes.show', $lesson->quiz) }}"
                               class="inline-flex shrink-0 items-center justify-center rounded-2xl bg-[var(--color-secondary)] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[var(--color-secondary-hover)]">
                                بدء الاختبار
                            </a>
                        </div>
                    </section>
                @endif

                {{-- Assignments for this lesson --}}
                @if ($assignments->isNotEmpty())
                    <section>
                        <div class="mb-4">
                            <h2 class="text-xl font-bold tracking-tight text-[var(--color-ink)]">واجبات الدرس</h2>
                            <p class="mt-1 text-sm text-[var(--color-text-secondary)]">مواعيد التسليم المرتبطة بهذا الدرس.</p>
                        </div>
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
                    </section>
                @endif

                {{-- Complete + nav --}}
                <section class="rounded-2xl border border-[var(--color-line)] bg-white px-5 py-6 shadow-[0_14px_36px_-26px_rgba(47,58,69,0.3)] sm:px-6">
                    <div class="flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h2 class="text-lg font-bold text-[var(--color-ink)]">هل انتهيت من الدرس؟</h2>
                            <p class="mt-1 text-sm text-[var(--color-text-secondary)]">
                                @if ($isCompleted)
                                    هذا الدرس معلَّم كمكتمل — يمكنك مراجعته أو الانتقال للتالي.
                                @else
                                    بعد مراجعة المواد، علّمه كمكتمل ليُحدَّث تقدّمك.
                                @endif
                            </p>
                        </div>
                        <div class="flex flex-wrap items-center gap-3">
                            @if ($isCompleted)
                                <span class="inline-flex items-center rounded-2xl bg-[var(--color-primary-light)] px-4 py-3 text-sm font-semibold text-[var(--color-primary-hover)]">
                                    مكتمل
                                </span>
                            @else
                                <form method="POST" action="{{ route('student.lessons.complete', $lesson) }}">
                                    @csrf
                                    <button type="submit" class="rounded-2xl bg-[var(--color-primary)] px-5 py-3 text-sm font-semibold text-white shadow-[0_14px_28px_-18px_rgba(42,157,143,0.55)] transition hover:bg-[var(--color-primary-hover)]">
                                        تعليم كمكتمل
                                    </button>
                                </form>
                            @endif
                            @if ($next)
                                <a href="{{ route('student.lessons.show', $next) }}"
                                   class="rounded-2xl border border-[var(--color-secondary)]/40 bg-[var(--color-secondary-light)] px-5 py-3 text-sm font-semibold text-[var(--color-secondary-hover)] transition hover:bg-white">
                                    الدرس التالي
                                </a>
                            @elseif ($lesson->course)
                                <a href="{{ route('student.courses.show', $lesson->course) }}"
                                   class="rounded-2xl border border-[var(--color-secondary)]/40 bg-[var(--color-secondary-light)] px-5 py-3 text-sm font-semibold text-[var(--color-secondary-hover)] transition hover:bg-white">
                                    العودة للمقرر
                                </a>
                            @endif
                        </div>
                    </div>

                    <div class="mt-5 flex flex-wrap gap-4 border-t border-[var(--color-line)] pt-5 text-sm">
                        @if ($previous)
                            <a class="font-medium text-[var(--color-text-secondary)] hover:text-[var(--color-primary)]" href="{{ route('student.lessons.show', $previous) }}">← الدرس السابق</a>
                        @endif
                        @if ($lesson->course)
                            <a class="font-medium text-[var(--color-secondary)] hover:underline" href="{{ route('student.courses.show', $lesson->course) }}">كل دروس المقرر</a>
                        @endif
                    </div>
                </section>
            </div>

            {{-- Path sidebar --}}
            <aside class="space-y-6 lg:sticky lg:top-24 lg:self-start">
                <section class="overflow-hidden rounded-2xl border border-[var(--color-line)] bg-white shadow-[0_14px_36px_-26px_rgba(47,58,69,0.35)]">
                    <div class="border-b border-[var(--color-line)] bg-[var(--color-sand)] px-5 py-4">
                        <h2 class="font-bold text-[var(--color-ink)]">مسار المقرر</h2>
                        <p class="mt-1 text-xs text-[var(--color-text-secondary)]">
                            {{ count($completedLessonIds) }}/{{ $total }} مكتمل · {{ $pathPercent }}%
                        </p>
                        <div class="mt-3 h-1.5 overflow-hidden rounded-full bg-white">
                            <div class="h-full rounded-full bg-[var(--color-primary)]" style="width: {{ $pathPercent }}%"></div>
                        </div>
                    </div>
                    @if ($siblings->isEmpty())
                        <p class="px-5 py-6 text-sm text-[var(--color-text-secondary)]">لا دروس أخرى.</p>
                    @else
                        <ol class="max-h-[28rem] divide-y divide-[var(--color-line)] overflow-y-auto">
                            @foreach ($siblings as $i => $sibling)
                                @php
                                    $done = in_array($sibling->id, $completedLessonIds, true);
                                    $current = $sibling->id === $lesson->id;
                                @endphp
                                <li>
                                    <a href="{{ route('student.lessons.show', $sibling) }}"
                                       @class([
                                           'flex items-center gap-3 px-4 py-3 text-sm transition',
                                           'bg-[var(--color-primary-light)]/60' => $current,
                                           'hover:bg-[var(--color-sand)]' => ! $current,
                                       ])>
                                        <span @class([
                                            'flex h-8 w-8 shrink-0 items-center justify-center rounded-xl text-xs font-semibold tabular-nums',
                                            'bg-[var(--color-primary)] text-white' => $done || $current,
                                            'bg-[var(--color-sand)] text-[var(--color-text-secondary)]' => ! $done && ! $current,
                                        ])>{{ $i + 1 }}</span>
                                        <span class="min-w-0 flex-1">
                                            <span @class([
                                                'block truncate font-medium',
                                                'text-[var(--color-primary-hover)]' => $current,
                                                'text-[var(--color-ink)]' => ! $current,
                                            ])>{{ $sibling->title }}</span>
                                            @if ($current)
                                                <span class="text-xs text-[var(--color-secondary-hover)]">الدرس الحالي</span>
                                            @elseif ($done)
                                                <span class="text-xs text-[var(--color-primary-hover)]">مكتمل</span>
                                            @endif
                                        </span>
                                    </a>
                                </li>
                            @endforeach
                        </ol>
                    @endif
                </section>

                @if ($lesson->course)
                    <a href="{{ route('student.courses.show', $lesson->course) }}"
                       class="block rounded-2xl border border-[var(--color-line)] bg-white px-5 py-4 text-sm shadow-[0_14px_36px_-26px_rgba(47,58,69,0.3)] transition hover:border-[var(--color-primary)]/35">
                        <span class="font-semibold text-[var(--color-ink)]">{{ $lesson->course->title }}</span>
                        <span class="mt-1 block text-[var(--color-secondary)]">العودة لصفحة المقرر ←</span>
                    </a>
                @endif
            </aside>
        </div>
    </div>
@endsection
