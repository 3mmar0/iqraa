@extends('layouts.student')

@section('title', 'لوحة الطالب')

@section('heading')
    مرحباً، {{ $user->name }}
@endsection

@section('subheading')
    {{ $termLabel }} — مسارك التعليمي في مكان واحد
@endsection

@section('header-actions')
    <a href="{{ route('student.course-requests.index') }}"
       class="rounded-2xl border border-[var(--color-line)] bg-white px-3.5 py-2 text-sm font-medium text-[var(--color-text-secondary)] transition hover:border-[var(--color-primary)] hover:text-[var(--color-primary)]">
        طلب مقرر
    </a>
    <a href="{{ route('student.courses.index') }}"
       class="rounded-2xl bg-[var(--color-primary)] px-3.5 py-2 text-sm font-semibold text-white shadow-[0_14px_28px_-18px_rgba(42,157,143,0.55)] transition hover:bg-[var(--color-primary-hover)]">
        مقرراتي
    </a>
@endsection

@section('content')
    <div class="mx-auto max-w-6xl space-y-10">
        {{-- Continue learning --}}
        @if ($lastProgress?->lesson)
            <section class="student-home-rise overflow-hidden rounded-2xl bg-[var(--color-ink)] text-white shadow-[0_22px_48px_-28px_rgba(47,58,69,0.55)]">
                <div class="relative px-6 py-8 sm:px-8 sm:py-9">
                    <div class="pointer-events-none absolute inset-0" style="background:
                        radial-gradient(ellipse 60% 80% at 100% 0%, color-mix(in srgb, var(--color-primary) 55%, transparent), transparent 58%),
                        radial-gradient(ellipse 45% 55% at 0% 100%, color-mix(in srgb, var(--color-secondary) 40%, transparent), transparent 52%);"></div>
                    <div class="relative flex flex-col gap-6 sm:flex-row sm:items-end sm:justify-between">
                        <div class="min-w-0">
                            <h2 class="text-xl font-bold leading-snug sm:text-2xl">{{ $lastProgress->lesson->title }}</h2>
                            @if ($lastProgress->lesson->course)
                                <p class="mt-2 text-sm text-white/65">{{ $lastProgress->lesson->course->title }}</p>
                            @endif
                            <p class="mt-3 max-w-xl text-sm leading-relaxed text-white/55">تابع من حيث توقفت — خطوة هادئة تعيدك إلى الدرس مباشرة.</p>
                        </div>
                        <a href="{{ route('student.lessons.show', $lastProgress->lesson) }}"
                           class="inline-flex shrink-0 items-center justify-center rounded-2xl bg-white px-6 py-3.5 text-sm font-semibold text-[var(--color-primary)] transition hover:bg-[var(--color-primary-light)]">
                            متابعة الدرس
                        </a>
                    </div>
                </div>
            </section>
        @elseif ($courses->isEmpty())
            <section class="student-home-rise overflow-hidden rounded-2xl border border-[var(--color-line)] bg-white shadow-[0_18px_40px_-28px_rgba(47,58,69,0.35)]">
                <div class="relative px-6 py-12 text-center sm:px-10">
                    <div class="pointer-events-none absolute inset-0 opacity-40" style="background-image: url('{{ asset('images/home/reading-room-wash.webp') }}'); background-size: cover;"></div>
                    <div class="relative">
                        <h2 class="text-xl font-bold text-[var(--color-ink)] sm:text-2xl">ابدأ مسارك التعليمي</h2>
                        <p class="mx-auto mt-3 max-w-lg text-sm leading-relaxed text-[var(--color-text-secondary)]">
                            لم تُسجَّل في أي مقرر بعد. تصفّح المقررات المنشورة وأرسل طلب التحاق — نراجع طلبك ثم نفعّل وصولك.
                        </p>
                        <div class="mt-7 flex flex-wrap items-center justify-center gap-3">
                            <a href="{{ route('public.courses.index') }}"
                               class="rounded-2xl bg-[var(--color-primary)] px-5 py-3 text-sm font-semibold text-white hover:bg-[var(--color-primary-hover)]">تصفّح المقررات</a>
                            <a href="{{ route('student.course-requests.index') }}"
                               class="rounded-2xl border border-[var(--color-secondary)]/40 bg-white px-5 py-3 text-sm font-medium text-[var(--color-secondary)] hover:bg-[var(--color-secondary-light)]">طلبات الالتحاق</a>
                        </div>
                    </div>
                </div>
            </section>
        @endif

        {{-- Learning snapshot (single summary band, not metric-card grid) --}}
        @if ($courses->isNotEmpty() || $completedLessons > 0 || $submittedQuizzes > 0)
            <section class="student-home-rise-delay rounded-2xl border border-[var(--color-line)] bg-[var(--color-primary-light)]/55 px-5 py-5 sm:px-7">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="text-lg font-bold text-[var(--color-ink)]">ملخص تقدّمك</h2>
                        <p class="mt-1 text-sm text-[var(--color-text-secondary)]">
                            {{ $courses->count() }} مقرر نشط
                            @if ($overallPercent > 0)
                                · متوسط الإنجاز {{ $overallPercent }}%
                            @endif
                            · {{ $completedLessons }} درس مكتمل
                            · {{ $submittedQuizzes }} اختبار مُسلَّم
                        </p>
                    </div>
                    <a href="{{ route('student.progress') }}"
                       class="inline-flex shrink-0 text-sm font-semibold text-[var(--color-secondary)] hover:underline">عرض التقدم التفصيلي</a>
                </div>
            </section>
        @endif

        {{-- Active courses shelf --}}
        @if ($courses->isNotEmpty())
            <section>
                <div class="mb-5 flex flex-wrap items-end justify-between gap-3">
                    <div>
                        <h2 class="text-xl font-bold tracking-tight text-[var(--color-ink)]">مقرراتك النشطة</h2>
                        <p class="mt-1 text-sm text-[var(--color-text-secondary)]">افتح المقرر لمتابعة الدروس والاختبارات.</p>
                    </div>
                    <a href="{{ route('student.courses.index') }}" class="text-sm font-semibold text-[var(--color-secondary)] hover:underline">كل المقررات</a>
                </div>

                <ul class="space-y-3">
                    @foreach ($courses as $course)
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
                                    <img src="{{ $cover }}" alt="" class="h-20 w-full rounded-xl object-cover sm:h-16 sm:w-28" width="112" height="64">
                                    <div class="min-w-0 flex-1">
                                        <p class="truncate text-base font-semibold text-[var(--color-ink)] group-hover:text-[var(--color-primary-hover)]">{{ $course->title }}</p>
                                        <p class="mt-1 text-sm text-[var(--color-text-secondary)]">
                                            {{ $course->instructor?->name ?? 'محاضر المنصة' }}
                                            · {{ $course->completed_lessons_count }}/{{ $course->lessons_count }} درس
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
        @endif

        {{-- Pending enrollment requests --}}
        @if ($pendingRequests->isNotEmpty())
            <section>
                <div class="mb-4 flex flex-wrap items-end justify-between gap-3">
                    <div>
                        <h2 class="text-xl font-bold tracking-tight text-[var(--color-ink)]">طلبات قيد المراجعة</h2>
                        <p class="mt-1 text-sm text-[var(--color-text-secondary)]">نراجع طلبات الالتحاق قبل تفعيل الوصول.</p>
                    </div>
                    <a href="{{ route('student.course-requests.index') }}" class="text-sm font-semibold text-[var(--color-secondary)] hover:underline">إدارة الطلبات</a>
                </div>
                <ul class="divide-y divide-[var(--color-line)] overflow-hidden rounded-2xl border border-[var(--color-line)] bg-white">
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

        {{-- Upcoming schedule + notifications --}}
        <div class="grid gap-8 lg:grid-cols-2">
            <section>
                <div class="mb-4 flex flex-wrap items-end justify-between gap-3">
                    <div>
                        <h2 class="text-xl font-bold tracking-tight text-[var(--color-ink)]">المواعيد القادمة</h2>
                        <p class="mt-1 text-sm text-[var(--color-text-secondary)]">محاضرات ومواعيد مرتبطة بمقرراتك.</p>
                    </div>
                    <a href="{{ route('student.calendar') }}" class="text-sm font-semibold text-[var(--color-secondary)] hover:underline">التقويم</a>
                </div>
                @if ($upcomingEvents->isEmpty())
                    <div class="rounded-2xl border border-dashed border-[var(--color-line)] bg-white px-5 py-10 text-center">
                        <p class="text-sm text-[var(--color-text-secondary)]">لا مواعيد قريبة حالياً.</p>
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
                                @if ($event->course)
                                    <p class="mt-1 text-xs text-[var(--color-muted)]">{{ $event->course->title }}</p>
                                @endif
                            </li>
                        @endforeach
                    </ol>
                @endif
            </section>

            <section>
                <div class="mb-4 flex flex-wrap items-end justify-between gap-3">
                    <div>
                        <h2 class="text-xl font-bold tracking-tight text-[var(--color-ink)]">الإشعارات</h2>
                        <p class="mt-1 text-sm text-[var(--color-text-secondary)]">
                            @if ($unreadNotifications > 0)
                                {{ $unreadNotifications }} غير مقروء
                            @else
                                آخر التنبيهات لحسابك
                            @endif
                        </p>
                    </div>
                    <a href="{{ route('student.notifications') }}" class="text-sm font-semibold text-[var(--color-secondary)] hover:underline">الكل</a>
                </div>
                @if ($notifications->isEmpty())
                    <div class="rounded-2xl border border-dashed border-[var(--color-line)] bg-white px-5 py-10 text-center">
                        <p class="text-sm text-[var(--color-text-secondary)]">لا إشعارات بعد.</p>
                    </div>
                @else
                    <ul class="divide-y divide-[var(--color-line)] overflow-hidden rounded-2xl border border-[var(--color-line)] bg-white shadow-[0_14px_36px_-26px_rgba(47,58,69,0.35)]">
                        @foreach ($notifications as $notification)
                            @php
                                $data = $notification->data ?? [];
                                $title = $data['title'] ?? $data['subject'] ?? 'إشعار';
                                $body = $data['body'] ?? $data['message'] ?? null;
                                $unread = is_null($notification->read_at);
                            @endphp
                            <li @class([
                                'px-5 py-4 sm:px-6',
                                'bg-[var(--color-primary-light)]/40' => $unread,
                            ])>
                                <div class="flex flex-wrap items-start justify-between gap-2">
                                    <p class="font-medium text-[var(--color-ink)]">{{ $title }}</p>
                                    <time class="text-xs text-[var(--color-muted)]">{{ $notification->created_at?->diffForHumans() }}</time>
                                </div>
                                @if ($body)
                                    <p class="mt-1 line-clamp-2 text-sm text-[var(--color-text-secondary)]">{{ $body }}</p>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @endif
            </section>
        </div>

        {{-- Achievements --}}
        <section>
            <div class="mb-4 flex flex-wrap items-end justify-between gap-3">
                <div>
                    <h2 class="text-xl font-bold tracking-tight text-[var(--color-ink)]">إنجازاتك الأخيرة</h2>
                    <p class="mt-1 text-sm text-[var(--color-text-secondary)]">شارات حصلت عليها من تقدّمك في المقررات.</p>
                </div>
                <a href="{{ route('student.achievements') }}" class="text-sm font-semibold text-[var(--color-secondary)] hover:underline">كل الإنجازات</a>
            </div>
            @if ($recentAchievements->isEmpty())
                <div class="rounded-2xl border border-dashed border-[var(--color-line)] bg-white px-5 py-10 text-center">
                    <p class="text-sm text-[var(--color-text-secondary)]">أكمل الدروس والاختبارات لتظهر الشارات هنا.</p>
                </div>
            @else
                <ul class="divide-y divide-[var(--color-line)] overflow-hidden rounded-2xl border border-[var(--color-line)] bg-white shadow-[0_14px_36px_-26px_rgba(47,58,69,0.35)]">
                    @foreach ($recentAchievements as $achievement)
                        <li class="flex flex-wrap items-center justify-between gap-3 px-5 py-4 sm:px-6">
                            <div class="min-w-0">
                                <p class="font-semibold text-[var(--color-ink)]">{{ $achievement->title }}</p>
                                @if ($achievement->description)
                                    <p class="mt-0.5 text-sm text-[var(--color-text-secondary)]">{{ $achievement->description }}</p>
                                @endif
                            </div>
                            @if ($achievement->pivot?->created_at)
                                <time class="text-xs text-[var(--color-muted)]">{{ $achievement->pivot->created_at->diffForHumans() }}</time>
                            @endif
                        </li>
                    @endforeach
                </ul>
            @endif
        </section>

        {{-- Discover more courses --}}
        @if ($discoverCourses->isNotEmpty())
            <section>
                <div class="mb-5 flex flex-wrap items-end justify-between gap-3">
                    <div>
                        <h2 class="text-xl font-bold tracking-tight text-[var(--color-ink)]">مقررات يمكنك استكشافها</h2>
                        <p class="mt-1 text-sm text-[var(--color-text-secondary)]">من الكتالوج المنشور — اطلب الالتحاق إن ناسبك المسار.</p>
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
                                </div>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </section>
        @endif

        {{-- Support path --}}
        <section class="rounded-2xl border border-[var(--color-line)] bg-white px-6 py-7 sm:px-8">
            <div class="flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-lg font-bold text-[var(--color-ink)]">تحتاج مساعدة؟</h2>
                    <p class="mt-1 max-w-xl text-sm leading-relaxed text-[var(--color-text-secondary)]">
                        فريق الدعم جاهز لأسئلة الالتحاق، الوصول للمقررات، أو أي عائق في مسارك.
                    </p>
                </div>
                <a href="{{ route('student.support.index') }}"
                   class="inline-flex shrink-0 items-center justify-center rounded-2xl border border-[var(--color-secondary)]/45 bg-[var(--color-secondary-light)] px-5 py-3 text-sm font-semibold text-[var(--color-secondary-hover)] transition hover:bg-white">
                    التواصل مع الدعم
                </a>
            </div>
        </section>
    </div>
@endsection
