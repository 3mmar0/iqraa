@extends('layouts.instructor')

@section('title', 'لوحة المحاضر')
@section('heading', 'مرحباً، '.$user->name)
@section('subheading', 'مساحة التدريس — مقرراتك وطلابك ومتابعاتك في مكان واحد')

@section('header-actions')
    <a href="{{ route('instructor.courses.create') }}"
       class="rounded-2xl border border-[var(--color-line)] bg-white px-3.5 py-2 text-sm font-medium text-[var(--color-text-secondary)] transition hover:border-[var(--color-primary)] hover:text-[var(--color-primary)]">
        مقرر جديد
    </a>
    <a href="{{ route('instructor.courses.index') }}"
       class="rounded-2xl bg-[var(--color-primary)] px-3.5 py-2 text-sm font-semibold text-white shadow-[0_14px_28px_-18px_rgba(42,157,143,0.55)] transition hover:bg-[var(--color-primary-hover)]">
        مقرراتي
    </a>
@endsection

@section('content')
    <div class="mx-auto max-w-6xl space-y-8">
        {{-- Hero --}}
        <section class="overflow-hidden rounded-2xl bg-[var(--color-ink)] text-white shadow-[0_22px_48px_-28px_rgba(47,58,69,0.55)]">
            <div class="relative px-6 py-8 sm:px-8 sm:py-9">
                <div class="pointer-events-none absolute inset-0" style="background:
                    radial-gradient(ellipse 60% 80% at 100% 0%, color-mix(in srgb, var(--color-primary) 55%, transparent), transparent 58%),
                    radial-gradient(ellipse 45% 55% at 0% 100%, color-mix(in srgb, var(--color-secondary) 40%, transparent), transparent 52%);"></div>
                <div class="relative flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                    <div class="min-w-0 max-w-2xl">
                        <h2 class="text-xl font-bold leading-snug sm:text-2xl">جاهز للتدريس اليوم؟</h2>
                        <p class="mt-3 text-sm leading-relaxed text-white/65">
                            لديك {{ $stats['courses'] }} مقرر · {{ $stats['students'] }} طالب · {{ $stats['lessons'] }} درس.
                            @if ($stats['pending_submissions'] > 0)
                                وهناك {{ $stats['pending_submissions'] }} تسليم بانتظار مراجعتك.
                            @else
                                لا تسليمات معلّقة حالياً.
                            @endif
                        </p>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('instructor.students.index') }}" class="rounded-2xl bg-white/10 px-4 py-2.5 text-sm font-medium text-white ring-1 ring-white/15 transition hover:bg-white/15">الطلاب</a>
                        <a href="{{ route('instructor.assignments.index') }}" class="rounded-2xl bg-white px-4 py-2.5 text-sm font-semibold text-[var(--color-primary)] transition hover:bg-[var(--color-primary-light)]">الواجبات</a>
                    </div>
                </div>
            </div>
        </section>

        {{-- Snapshot --}}
        <section class="rounded-2xl border border-[var(--color-line)] bg-[var(--color-primary-light)]/55 px-5 py-5 sm:px-7">
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <div>
                    <p class="text-xs font-medium text-[var(--color-text-secondary)]">المقررات</p>
                    <p class="mt-1 text-2xl font-bold text-[var(--color-ink)]">{{ $stats['courses'] }}</p>
                    <p class="mt-0.5 text-xs text-[var(--color-text-secondary)]">{{ $stats['published'] }} منشور</p>
                </div>
                <div>
                    <p class="text-xs font-medium text-[var(--color-text-secondary)]">الطلاب</p>
                    <p class="mt-1 text-2xl font-bold text-[var(--color-ink)]">{{ $stats['students'] }}</p>
                    <p class="mt-0.5 text-xs text-[var(--color-text-secondary)]">التحاقات نشطة</p>
                </div>
                <div>
                    <p class="text-xs font-medium text-[var(--color-text-secondary)]">المحتوى</p>
                    <p class="mt-1 text-2xl font-bold text-[var(--color-ink)]">{{ $stats['lessons'] }}</p>
                    <p class="mt-0.5 text-xs text-[var(--color-text-secondary)]">{{ $stats['quizzes'] }} اختبار</p>
                </div>
                <div>
                    <p class="text-xs font-medium text-[var(--color-text-secondary)]">بانتظارك</p>
                    <p class="mt-1 text-2xl font-bold text-[var(--color-ink)]">{{ $stats['pending_submissions'] }}</p>
                    <p class="mt-0.5 text-xs text-[var(--color-text-secondary)]">تسليم واجب</p>
                </div>
            </div>
        </section>

        {{-- Quick stations --}}
        <section>
            <div class="mb-4">
                <h2 class="text-xl font-bold tracking-tight text-[var(--color-ink)]">محطات سريعة</h2>
                <p class="mt-1 text-sm text-[var(--color-text-secondary)]">اختصارات لأهم أعمال التدريس.</p>
            </div>
            <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ([
                    ['label' => 'المقررات', 'hint' => 'إدارة المحتوى', 'route' => 'instructor.courses.index', 'tone' => 'primary'],
                    ['label' => 'الطلاب', 'hint' => 'قائمة الملتحقين', 'route' => 'instructor.students.index', 'tone' => 'secondary'],
                    ['label' => 'الإعلانات', 'hint' => 'بلّغ طلابك', 'route' => 'instructor.announcements.index', 'tone' => 'sage'],
                    ['label' => 'الجلسات', 'hint' => 'جدولة مباشرة', 'route' => 'instructor.live-sessions.index', 'tone' => 'ink'],
                ] as $station)
                    <a href="{{ route($station['route']) }}"
                       class="group rounded-2xl border border-[var(--color-line)] bg-white p-4 shadow-[0_12px_28px_-22px_rgba(47,58,69,0.4)] transition hover:-translate-y-0.5 hover:border-[var(--color-primary)]/40">
                        <span class="mb-3 inline-flex h-10 w-10 items-center justify-center rounded-xl
                            {{ $station['tone'] === 'primary' ? 'bg-[var(--color-primary-light)] text-[var(--color-primary-hover)]' : '' }}
                            {{ $station['tone'] === 'secondary' ? 'bg-[var(--color-secondary-light)] text-[var(--color-secondary-hover)]' : '' }}
                            {{ $station['tone'] === 'sage' ? 'bg-[var(--color-accent-light)] text-[var(--color-accent)]' : '' }}
                            {{ $station['tone'] === 'ink' ? 'bg-slate-100 text-slate-700' : '' }}">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
                        </span>
                        <p class="font-bold text-[var(--color-ink)] group-hover:text-[var(--color-primary-hover)]">{{ $station['label'] }}</p>
                        <p class="mt-0.5 text-xs text-[var(--color-text-secondary)]">{{ $station['hint'] }}</p>
                    </a>
                @endforeach
            </div>
        </section>

        <div class="grid gap-8 lg:grid-cols-[minmax(0,1.4fr)_minmax(0,1fr)]">
            {{-- Courses --}}
            <section>
                <div class="mb-4 flex flex-wrap items-end justify-between gap-3">
                    <div>
                        <h2 class="text-xl font-bold tracking-tight text-[var(--color-ink)]">مقرراتك</h2>
                        <p class="mt-1 text-sm text-[var(--color-text-secondary)]">أحدث المقررات المعيّنة لك.</p>
                    </div>
                    <a href="{{ route('instructor.courses.index') }}" class="text-sm font-semibold text-[var(--color-secondary)] hover:underline">عرض الكل</a>
                </div>

                @if ($courses->isEmpty())
                    <div class="rounded-2xl border border-dashed border-[var(--color-line)] bg-white px-5 py-10 text-center">
                        <p class="font-semibold text-[var(--color-ink)]">لا مقررات بعد</p>
                        <p class="mt-1 text-sm text-[var(--color-text-secondary)]">أنشئ مقرراً أو اطلب من الإدارة تعيين مقررات لك.</p>
                        <a href="{{ route('instructor.courses.create') }}" class="mt-4 inline-flex rounded-xl bg-[var(--color-primary)] px-4 py-2.5 text-sm font-semibold text-white">إنشاء مقرر</a>
                    </div>
                @else
                    <ul class="space-y-3">
                        @foreach ($courses->take(5) as $course)
                            @php
                                $cover = $course->image_path
                                    ? asset('storage/'.$course->image_path)
                                    : asset('images/home/course-cover-'.(($loop->index % 2) + 1).'.webp');
                            @endphp
                            <li>
                                <a href="{{ route('instructor.courses.show', $course) }}"
                                   class="group flex flex-col gap-4 overflow-hidden rounded-2xl border border-[var(--color-line)] bg-white p-4 shadow-[0_14px_36px_-26px_rgba(47,58,69,0.4)] transition hover:border-[var(--color-primary)]/35 sm:flex-row sm:items-center sm:p-5">
                                    <span class="block h-1 w-full rounded-full bg-[var(--color-accent)] sm:hidden" aria-hidden="true"></span>
                                    <img src="{{ $cover }}" alt="" class="h-20 w-full rounded-xl object-cover sm:h-16 sm:w-28" width="112" height="64">
                                    <div class="min-w-0 flex-1">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <p class="truncate text-base font-semibold text-[var(--color-ink)] group-hover:text-[var(--color-primary-hover)]">{{ $course->title }}</p>
                                            <x-admin.status-badge :status="$course->status" />
                                        </div>
                                        <p class="mt-1 text-sm text-[var(--color-text-secondary)]">
                                            {{ $course->lessons_count }} درس · {{ $course->enrollments_count }} طالب · {{ $course->quizzes_count }} اختبار
                                        </p>
                                    </div>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </section>

            {{-- Side columns --}}
            <div class="space-y-6">
                <section class="rounded-2xl border border-[var(--color-line)] bg-white p-5">
                    <div class="mb-3 flex items-center justify-between gap-2">
                        <h2 class="font-bold text-[var(--color-ink)]">جلسات قادمة</h2>
                        <a href="{{ route('instructor.live-sessions.index') }}" class="text-xs font-semibold text-[var(--color-secondary)] hover:underline">الكل</a>
                    </div>
                    @forelse ($upcomingSessions as $session)
                        <div @class(['border-t border-slate-100 pt-3 mt-3' => ! $loop->first])>
                            <p class="text-sm font-semibold text-[var(--color-ink)]">{{ $session->title }}</p>
                            <p class="mt-0.5 text-xs text-slate-500">{{ $session->course?->title }} · {{ $session->starts_at?->translatedFormat('d M — H:i') }}</p>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500">لا جلسات مجدولة قريباً.</p>
                    @endforelse
                </section>

                <section class="rounded-2xl border border-[var(--color-line)] bg-white p-5">
                    <div class="mb-3 flex items-center justify-between gap-2">
                        <h2 class="font-bold text-[var(--color-ink)]">واجبات قريبة الاستحقاق</h2>
                        <a href="{{ route('instructor.assignments.index') }}" class="text-xs font-semibold text-[var(--color-secondary)] hover:underline">الكل</a>
                    </div>
                    @forelse ($dueSoonAssignments as $assignment)
                        <div @class(['border-t border-slate-100 pt-3 mt-3' => ! $loop->first])>
                            <p class="text-sm font-semibold text-[var(--color-ink)]">{{ $assignment->title }}</p>
                            <p class="mt-0.5 text-xs text-slate-500">{{ $assignment->course?->title }} · يستحق {{ $assignment->due_at?->diffForHumans() }} · {{ $assignment->submissions_count }} تسليم</p>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500">لا مواعيد استحقاق قريبة.</p>
                    @endforelse
                </section>

                <section class="rounded-2xl border border-[var(--color-line)] bg-white p-5">
                    <div class="mb-3 flex items-center justify-between gap-2">
                        <h2 class="font-bold text-[var(--color-ink)]">أحدث الالتحاقات</h2>
                        <a href="{{ route('instructor.students.index') }}" class="text-xs font-semibold text-[var(--color-secondary)] hover:underline">الطلاب</a>
                    </div>
                    @forelse ($recentEnrollments as $enrollment)
                        <div class="flex items-center gap-3 {{ $loop->first ? '' : 'mt-3 border-t border-slate-100 pt-3' }}">
                            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-[var(--color-secondary-light)] text-xs font-bold text-[var(--color-secondary-hover)]">
                                {{ mb_substr($enrollment->user?->name ?? '?', 0, 1) }}
                            </span>
                            <div class="min-w-0">
                                <p class="truncate text-sm font-semibold text-[var(--color-ink)]">{{ $enrollment->user?->name }}</p>
                                <p class="truncate text-xs text-slate-500">{{ $enrollment->course?->title }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500">لا التحاقات حديثة.</p>
                    @endforelse
                </section>

                <section class="rounded-2xl border border-[var(--color-line)] bg-white p-5">
                    <div class="mb-3 flex items-center justify-between gap-2">
                        <h2 class="font-bold text-[var(--color-ink)]">آخر الإعلانات</h2>
                        <a href="{{ route('instructor.announcements.index') }}" class="text-xs font-semibold text-[var(--color-secondary)] hover:underline">الكل</a>
                    </div>
                    @forelse ($recentAnnouncements as $item)
                        <div @class(['border-t border-slate-100 pt-3 mt-3' => ! $loop->first])>
                            <p class="text-sm font-semibold text-[var(--color-ink)]">{{ $item->title }}</p>
                            <p class="mt-0.5 text-xs text-slate-500">{{ $item->course?->title }} · {{ $item->published_at?->diffForHumans() ?? $item->created_at?->diffForHumans() }}</p>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500">لم تنشر إعلانات بعد.</p>
                    @endforelse
                </section>
            </div>
        </div>
    </div>
@endsection
