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
        $pathPercent = $total > 0 ? (int) round((count($completedLessonIds) / $total) * 100) : 0;
        $resumeAt = (int) ($progressRow?->last_position_seconds ?? 0);
    @endphp

    <div class="mx-auto max-w-6xl space-y-8">
        <div class="grid gap-8 lg:grid-cols-[minmax(0,1.65fr)_minmax(16rem,0.85fr)]">
            <div class="space-y-8">
                {{-- 1. Main video --}}
                <section class="student-home-rise overflow-hidden rounded-2xl border border-[var(--color-line)] bg-white shadow-[0_18px_40px_-28px_rgba(47,58,69,0.4)]">
                    <span class="block h-1 bg-[var(--color-accent)]" aria-hidden="true"></span>
                    <div class="px-5 py-5 sm:px-6 sm:py-6">
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
                            <p class="mt-2 text-sm text-[var(--color-text-secondary)]">{{ $lesson->description }}</p>
                        @endif
                    </div>

                    @if ($mainVideo)
                        <div
                            class="border-t border-[var(--color-line)] bg-[var(--color-ink)] px-4 py-4 sm:px-6"
                            x-data="lessonPlayer({
                                progressUrl: @js(route('student.lessons.progress', $lesson)),
                                csrf: @js(csrf_token()),
                                startAt: {{ $resumeAt }},
                                alreadyComplete: @js((bool) $progressRow?->watchCompleted()),
                            })"
                        >
                            <video
                                x-ref="player"
                                class="aspect-video w-full rounded-xl bg-black"
                                controls
                                playsinline
                                preload="metadata"
                                src="{{ route('student.media.show', $mainVideo) }}"
                                @timeupdate="onTimeUpdate()"
                                @ended="onEnded()"
                                @loadedmetadata="onLoaded()"
                            ></video>
                            <div class="mt-3 flex flex-wrap items-center justify-between gap-3">
                                <p class="text-xs text-white/65">{{ $mainVideo->original_name ?? 'فيديو الدرس' }}</p>
                                <div class="flex flex-wrap gap-2">
                                    <button type="button" @click="markComplete()"
                                            class="rounded-xl bg-white/10 px-3 py-1.5 text-xs font-medium text-white hover:bg-white/15"
                                            x-text="videoComplete ? 'تم تسجيل إكمال المشاهدة' : 'تعليم الفيديو كمُشاهد'"></button>
                                    <a href="{{ route('student.media.show', $mainVideo) }}"
                                       class="rounded-xl bg-[var(--color-primary)] px-3 py-1.5 text-xs font-semibold text-white hover:bg-[var(--color-primary-hover)]">تنزيل / فتح</a>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="border-t border-[var(--color-line)] px-5 py-8 text-center sm:px-6">
                            <p class="text-sm text-[var(--color-text-secondary)]">لا يوجد فيديو رئيسي لهذا الدرس — تابع الشرح والمواد أدناه.</p>
                        </div>
                    @endif
                </section>

                {{-- 2. Rich text --}}
                <section>
                    <div class="mb-4">
                        <h2 class="text-xl font-bold tracking-tight text-[var(--color-ink)]">شرح الدرس</h2>
                        <p class="mt-1 text-sm text-[var(--color-text-secondary)]">ما يحتاج الطالب فهمه مع هذا الدرس.</p>
                    </div>
                    @if ($contentHtml)
                        <div class="lesson-content prose-lesson rounded-2xl border border-[var(--color-line)] bg-white px-5 py-6 text-[var(--color-ink)] shadow-[0_14px_36px_-26px_rgba(47,58,69,0.35)] sm:px-7 sm:py-7">
                            {!! $contentHtml !!}
                        </div>
                    @else
                        <div class="rounded-2xl border border-dashed border-[var(--color-line)] bg-white px-5 py-10 text-center">
                            <p class="text-sm text-[var(--color-text-secondary)]">لا يوجد شرح نصي إضافي لهذا الدرس.</p>
                        </div>
                    @endif
                </section>

                {{-- 3. Secondary materials --}}
                <section>
                    <div class="mb-4">
                        <h2 class="text-xl font-bold tracking-tight text-[var(--color-ink)]">مواد إضافية</h2>
                        <p class="mt-1 text-sm text-[var(--color-text-secondary)]">ملفات PDF ومرفقات يمكنك فتحها مع الدرس.</p>
                    </div>
                    @if ($files->isEmpty())
                        <div class="rounded-2xl border border-dashed border-[var(--color-line)] bg-white px-5 py-8 text-center">
                            <p class="text-sm text-[var(--color-text-secondary)]">لا مرفقات إضافية.</p>
                        </div>
                    @else
                        <ul class="divide-y divide-[var(--color-line)] overflow-hidden rounded-2xl border border-[var(--color-line)] bg-white shadow-[0_14px_36px_-26px_rgba(47,58,69,0.35)]">
                            @foreach ($files as $asset)
                                @php
                                    $typeLabel = match ($asset->type) {
                                        'video' => 'فيديو',
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

                {{-- Exam gate --}}
                @if ($quiz)
                    <section class="rounded-2xl border border-[var(--color-line)] px-5 py-6 sm:px-6 {{ $examUnlocked ? 'bg-[var(--color-secondary-light)]/55' : 'bg-[var(--color-sand)]' }}">
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <h2 class="text-lg font-bold text-[var(--color-ink)]">اختبار الدرس</h2>
                                <p class="mt-1 text-sm text-[var(--color-text-secondary)]">{{ $quiz->title }}</p>
                                @if (! $examUnlocked)
                                    <p class="mt-2 text-sm font-medium text-[var(--color-secondary-hover)]">
                                        @if ($mainVideo)
                                            أكمل مشاهدة الفيديو أولاً قبل بدء الاختبار.
                                        @else
                                            علّم الدرس كمكتمل لفتح الاختبار.
                                        @endif
                                    </p>
                                @elseif ($latestAttempt)
                                    <p class="mt-2 text-sm text-[var(--color-text-secondary)]">آخر نتيجة: {{ number_format((float) $latestAttempt->score, 0) }}%</p>
                                @endif
                            </div>
                            <div class="flex flex-wrap gap-2">
                                @if ($examUnlocked)
                                    <a href="{{ route('student.quizzes.show', $quiz) }}"
                                       class="inline-flex rounded-2xl bg-[var(--color-secondary)] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[var(--color-secondary-hover)]">
                                        {{ $latestAttempt ? 'إعادة الاختبار' : 'بدء الاختبار' }}
                                    </a>
                                    @if ($latestAttempt)
                                        <a href="{{ route('student.quizzes.result', $latestAttempt) }}"
                                           class="inline-flex rounded-2xl border border-[var(--color-secondary)]/40 bg-white px-5 py-3 text-sm font-semibold text-[var(--color-secondary-hover)]">
                                            عرض النتيجة
                                        </a>
                                    @endif
                                @else
                                    <span class="inline-flex rounded-2xl border border-[var(--color-line)] bg-white px-5 py-3 text-sm font-medium text-[var(--color-muted)]">مقفل</span>
                                @endif
                            </div>
                        </div>
                    </section>
                @endif

                @if ($assignments->isNotEmpty())
                    <section>
                        <h2 class="mb-4 text-xl font-bold tracking-tight text-[var(--color-ink)]">واجبات الدرس</h2>
                        <ul class="divide-y divide-[var(--color-line)] overflow-hidden rounded-2xl border border-[var(--color-line)] bg-white">
                            @foreach ($assignments as $assignment)
                                <li class="px-5 py-4 sm:px-6">
                                    <p class="font-semibold text-[var(--color-ink)]">{{ $assignment->title }}</p>
                                    @if ($assignment->due_at)
                                        <p class="mt-1 text-xs text-[var(--color-muted)]">التسليم: {{ $assignment->due_at->timezone(config('app.timezone'))->format('Y/m/d H:i') }}</p>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </section>
                @endif

                <section class="rounded-2xl border border-[var(--color-line)] bg-white px-5 py-6 shadow-[0_14px_36px_-26px_rgba(47,58,69,0.3)] sm:px-6">
                    <div class="flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h2 class="text-lg font-bold text-[var(--color-ink)]">هل انتهيت من الدرس؟</h2>
                            <p class="mt-1 text-sm text-[var(--color-text-secondary)]">
                                @if ($isCompleted)
                                    هذا الدرس معلَّم كمكتمل.
                                @else
                                    بعد مشاهدة الفيديو ومراجعة الشرح، علّمه كمكتمل ليُحدَّث تقدّمك.
                                @endif
                            </p>
                        </div>
                        <div class="flex flex-wrap items-center gap-3">
                            @if ($isCompleted)
                                <span class="inline-flex rounded-2xl bg-[var(--color-primary-light)] px-4 py-3 text-sm font-semibold text-[var(--color-primary-hover)]">مكتمل</span>
                            @else
                                <form method="POST" action="{{ route('student.lessons.complete', $lesson) }}">
                                    @csrf
                                    <button type="submit" class="rounded-2xl bg-[var(--color-primary)] px-5 py-3 text-sm font-semibold text-white hover:bg-[var(--color-primary-hover)]">تعليم كمكتمل</button>
                                </form>
                            @endif
                            @if ($next)
                                <a href="{{ route('student.lessons.show', $next) }}" class="rounded-2xl border border-[var(--color-secondary)]/40 bg-[var(--color-secondary-light)] px-5 py-3 text-sm font-semibold text-[var(--color-secondary-hover)]">الدرس التالي</a>
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

            <aside class="space-y-6 lg:sticky lg:top-24 lg:self-start">
                <section class="overflow-hidden rounded-2xl border border-[var(--color-line)] bg-white shadow-[0_14px_36px_-26px_rgba(47,58,69,0.35)]">
                    <div class="border-b border-[var(--color-line)] bg-[var(--color-sand)] px-5 py-4">
                        <h2 class="font-bold text-[var(--color-ink)]">مسار المقرر</h2>
                        <p class="mt-1 text-xs text-[var(--color-text-secondary)]">{{ count($completedLessonIds) }}/{{ $total }} مكتمل · {{ $pathPercent }}%</p>
                        <div class="mt-3 h-1.5 overflow-hidden rounded-full bg-white">
                            <div class="h-full rounded-full bg-[var(--color-primary)]" style="width: {{ $pathPercent }}%"></div>
                        </div>
                    </div>
                    <ol class="max-h-[28rem] divide-y divide-[var(--color-line)] overflow-y-auto">
                        @foreach ($siblings as $i => $sibling)
                            @php
                                $done = in_array($sibling->id, $completedLessonIds, true);
                                $current = $sibling->id === $lesson->id;
                            @endphp
                            <li>
                                <a href="{{ route('student.lessons.show', $sibling) }}"
                                   @class(['flex items-center gap-3 px-4 py-3 text-sm transition', 'bg-[var(--color-primary-light)]/60' => $current, 'hover:bg-[var(--color-sand)]' => ! $current])>
                                    <span @class(['flex h-8 w-8 shrink-0 items-center justify-center rounded-xl text-xs font-semibold tabular-nums', 'bg-[var(--color-primary)] text-white' => $done || $current, 'bg-[var(--color-sand)] text-[var(--color-text-secondary)]' => ! $done && ! $current])>{{ $i + 1 }}</span>
                                    <span class="min-w-0 flex-1 truncate font-medium {{ $current ? 'text-[var(--color-primary-hover)]' : 'text-[var(--color-ink)]' }}">{{ $sibling->title }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ol>
                </section>
            </aside>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('lessonPlayer', (cfg) => ({
                progressUrl: cfg.progressUrl,
                csrf: cfg.csrf,
                startAt: cfg.startAt || 0,
                videoComplete: !!cfg.alreadyComplete,
                lastSent: 0,
                onLoaded() {
                    const p = this.$refs.player;
                    if (p && this.startAt > 0 && this.startAt < (p.duration || Infinity)) {
                        p.currentTime = this.startAt;
                    }
                },
                onTimeUpdate() {
                    const p = this.$refs.player;
                    if (!p || !p.duration) return;
                    const t = Math.floor(p.currentTime);
                    if (t - this.lastSent < 5) return;
                    this.lastSent = t;
                    const ratio = p.currentTime / p.duration;
                    this.sendProgress(t, ratio >= 0.9);
                },
                onEnded() {
                    this.sendProgress(Math.floor(this.$refs.player?.currentTime || 0), true);
                },
                markComplete() {
                    this.sendProgress(Math.floor(this.$refs.player?.currentTime || 0), true);
                },
                sendProgress(position, completed) {
                    if (completed) this.videoComplete = true;
                    fetch(this.progressUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': this.csrf,
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        body: JSON.stringify({ position_seconds: position, completed: !!completed }),
                    }).then(r => r.json()).then(data => {
                        if (data.video_completed) this.videoComplete = true;
                        if (data.exam_unlocked) window.location.reload();
                    }).catch(() => {});
                },
            }));
        });
    </script>
    <style>
        .lesson-content h2, .lesson-content h3, .lesson-content h4 { font-weight: 700; margin: 1rem 0 0.5rem; color: var(--color-ink); }
        .lesson-content p { margin: 0.65rem 0; line-height: 1.75; color: var(--color-text-secondary); }
        .lesson-content ul, .lesson-content ol { margin: 0.65rem 1.25rem; line-height: 1.7; color: var(--color-text-secondary); }
        .lesson-content a { color: var(--color-secondary); text-decoration: underline; }
        .lesson-content strong, .lesson-content b { color: var(--color-ink); }
    </style>
    @endpush
@endsection
