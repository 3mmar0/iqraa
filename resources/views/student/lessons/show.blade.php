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
                            class="border-t border-[var(--color-line)] bg-[var(--color-ink)]"
                            x-data="lessonPlayer({
                                progressUrl: @js(route('student.lessons.progress', $lesson)),
                                csrf: @js(csrf_token()),
                                startAt: {{ $resumeAt }},
                                alreadyComplete: @js((bool) $progressRow?->watchCompleted()),
                                src: @js(route('student.media.show', $mainVideo)),
                                title: @js($mainVideo->original_name ?? 'فيديو الدرس'),
                            })"
                        >
                            <div
                                x-ref="shell"
                                class="group relative aspect-video w-full overflow-hidden bg-black select-none"
                                @mousemove="bumpControls()"
                                @touchstart.passive="bumpControls()"
                                @contextmenu.prevent
                            >
                                <video
                                    x-ref="player"
                                    class="h-full w-full object-contain"
                                    playsinline
                                    preload="metadata"
                                    controlslist="nodownload noremoteplayback"
                                    disablepictureinpicture
                                    :src="src"
                                    @loadedmetadata="onLoaded()"
                                    @timeupdate="onTimeUpdate()"
                                    @ended="onEnded()"
                                    x-on:error="onError()"
                                    @click="togglePlay()"
                                ></video>

                                {{-- Big play affordance when paused --}}
                                <button
                                    type="button"
                                    class="absolute inset-0 flex items-center justify-center transition"
                                    x-show="!playing && !error"
                                    x-cloak
                                    @click="togglePlay()"
                                    aria-label="تشغيل"
                                >
                                    <span class="flex h-16 w-16 items-center justify-center rounded-full bg-[var(--color-primary)] text-white shadow-lg ring-4 ring-white/20">
                                        <svg class="h-7 w-7 ms-0.5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M8 5.14v13.72L19 12 8 5.14z"/></svg>
                                    </span>
                                </button>

                                <div
                                    x-show="error"
                                    x-cloak
                                    class="absolute inset-0 flex items-center justify-center bg-black/80 px-6 text-center text-sm text-white"
                                    x-text="error"
                                ></div>

                                {{-- Custom controls --}}
                                <div
                                    class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/85 via-black/45 to-transparent px-3 pb-3 pt-10 transition-opacity"
                                    :class="showControls || !playing ? 'opacity-100' : 'opacity-0 pointer-events-none'"
                                >
                                    <div
                                        class="mb-2 h-1.5 cursor-pointer overflow-hidden rounded-full bg-white/25"
                                        @mousedown="startSeek()"
                                        @mouseup="endSeek($event)"
                                        @click="seekTo($event)"
                                        role="slider"
                                        :aria-valuenow="Math.floor(current)"
                                        :aria-valuemax="Math.floor(duration)"
                                        aria-label="تقدم الفيديو"
                                    >
                                        <div class="relative h-full w-full">
                                            <div class="absolute inset-y-0 right-0 rounded-full bg-white/30" :style="`width: ${bufferedPct}%`"></div>
                                            <div class="absolute inset-y-0 right-0 rounded-full bg-[var(--color-primary)]" :style="`width: ${progressPct}%`"></div>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2 text-white">
                                        <button type="button" class="rounded-lg p-2 hover:bg-white/10" @click="togglePlay()" :aria-label="playing ? 'إيقاف' : 'تشغيل'">
                                            <svg x-show="!playing" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5.14v13.72L19 12 8 5.14z"/></svg>
                                            <svg x-show="playing" x-cloak class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M6 5h4v14H6V5zm8 0h4v14h-4V5z"/></svg>
                                        </button>
                                        <button type="button" class="rounded-lg p-2 hover:bg-white/10" @click="toggleMute()" :aria-label="muted ? 'إلغاء كتم الصوت' : 'كتم الصوت'">
                                            <svg x-show="!muted" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5 6 9H3v6h3l5 4V5zm7.07 2.93a6 6 0 0 1 0 8.14M17 9.17a3.5 3.5 0 0 1 0 5.66"/></svg>
                                            <svg x-show="muted" x-cloak class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5 6 9H3v6h3l5 4V5zM22 9l-6 6m0-6 6 6"/></svg>
                                        </button>
                                        <span class="ms-1 text-xs tabular-nums text-white/80" x-text="`${formatTime(current)} / ${formatTime(duration)}`"></span>
                                        <span class="mx-2 truncate text-xs text-white/55" x-text="title"></span>
                                        <button type="button" class="ms-auto rounded-lg p-2 hover:bg-white/10" @click="toggleFullscreen()" aria-label="ملء الشاشة">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 3H5a2 2 0 0 0-2 2v3m18 0V5a2 2 0 0 0-2-2h-3m0 18h3a2 2 0 0 0 2-2v-3M3 16v3a2 2 0 0 0 2 2h3"/></svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="flex flex-wrap items-center justify-between gap-3 px-4 py-3 sm:px-6">
                                <p class="text-xs text-white/65">المشاهدة داخل المنصة فقط — التنزيل غير متاح</p>
                                <button type="button" @click="markComplete()"
                                        class="rounded-xl bg-white/10 px-3 py-1.5 text-xs font-medium text-white hover:bg-white/15"
                                        x-text="videoComplete ? 'تم تسجيل إكمال المشاهدة' : 'تعليم الفيديو كمُشاهد'"></button>
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

                {{-- Extra videos (stream only) --}}
                @if ($secondaryVideos->isNotEmpty())
                    <section>
                        <div class="mb-4">
                            <h2 class="text-xl font-bold tracking-tight text-[var(--color-ink)]">فيديوهات إضافية</h2>
                            <p class="mt-1 text-sm text-[var(--color-text-secondary)]">تُشغَّل داخل الصفحة ولا يمكن تنزيلها.</p>
                        </div>
                        <div class="space-y-4">
                            @foreach ($secondaryVideos as $extraVideo)
                                <div class="overflow-hidden rounded-2xl border border-[var(--color-line)] bg-[var(--color-ink)] shadow-[0_14px_36px_-26px_rgba(47,58,69,0.35)]"
                                     x-data="lessonPlayer({
                                        src: @js(route('student.media.show', $extraVideo)),
                                        title: @js($extraVideo->original_name ?? 'فيديو'),
                                        csrf: @js(csrf_token()),
                                     })">
                                    <div x-ref="shell" class="relative aspect-video w-full bg-black select-none" @mousemove="bumpControls()" @contextmenu.prevent>
                                        <video
                                            x-ref="player"
                                            class="h-full w-full object-contain"
                                            playsinline
                                            preload="metadata"
                                            controlslist="nodownload noremoteplayback"
                                            disablepictureinpicture
                                            :src="src"
                                            @loadedmetadata="onLoaded()"
                                            @timeupdate="onTimeUpdate()"
                                            @ended="onEnded()"
                                            x-on:error="onError()"
                                            @click="togglePlay()"
                                        ></video>
                                        <button type="button" class="absolute inset-0 flex items-center justify-center" x-show="!playing && !error" x-cloak @click="togglePlay()" aria-label="تشغيل">
                                            <span class="flex h-14 w-14 items-center justify-center rounded-full bg-[var(--color-primary)] text-white shadow-lg">
                                                <svg class="h-6 w-6 ms-0.5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5.14v13.72L19 12 8 5.14z"/></svg>
                                            </span>
                                        </button>
                                        <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/80 to-transparent px-3 pb-3 pt-8" :class="showControls || !playing ? 'opacity-100' : 'opacity-0'">
                                            <div class="mb-2 h-1.5 cursor-pointer overflow-hidden rounded-full bg-white/25" @click="seekTo($event)">
                                                <div class="h-full rounded-full bg-[var(--color-primary)]" :style="`width: ${progressPct}%`"></div>
                                            </div>
                                            <div class="flex items-center gap-2 text-white">
                                                <button type="button" class="rounded-lg p-1.5 hover:bg-white/10" @click="togglePlay()">
                                                    <svg x-show="!playing" class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5.14v13.72L19 12 8 5.14z"/></svg>
                                                    <svg x-show="playing" x-cloak class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M6 5h4v14H6V5zm8 0h4v14h-4V5z"/></svg>
                                                </button>
                                                <span class="text-xs tabular-nums text-white/80" x-text="`${formatTime(current)} / ${formatTime(duration)}`"></span>
                                                <span class="truncate text-xs text-white/55" x-text="title"></span>
                                                <button type="button" class="ms-auto rounded-lg p-1.5 hover:bg-white/10" @click="toggleFullscreen()" aria-label="ملء الشاشة">
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 3H5a2 2 0 0 0-2 2v3m18 0V5a2 2 0 0 0-2-2h-3m0 18h3a2 2 0 0 0 2-2v-3M3 16v3a2 2 0 0 0 2 2h3"/></svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endif

                {{-- 3. Secondary materials (non-video) --}}
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
                                        'pdf' => 'PDF',
                                        'attachment' => 'مرفق',
                                        default => $asset->type,
                                    };
                                @endphp
                                <li>
                                    <a href="{{ route('student.media.show', ['asset' => $asset, 'download' => 1]) }}"
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
    <style>
        .lesson-content h2, .lesson-content h3, .lesson-content h4 { font-weight: 700; margin: 1rem 0 0.5rem; color: var(--color-ink); }
        .lesson-content p { margin: 0.65rem 0; line-height: 1.75; color: var(--color-text-secondary); }
        .lesson-content ul, .lesson-content ol { margin: 0.65rem 1.25rem; line-height: 1.7; color: var(--color-text-secondary); }
        .lesson-content a { color: var(--color-secondary); text-decoration: underline; }
        .lesson-content strong, .lesson-content b { color: var(--color-ink); }
        video::-webkit-media-controls-download-button { display: none !important; }
        video::-internal-media-controls-download-button { display: none !important; }
    </style>
    @endpush
@endsection
