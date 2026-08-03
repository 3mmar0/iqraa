@extends('layouts.app')

@section('title', $course->title)

@section('content')
    @php
        $cover = $course->image_path
            ? asset('storage/'.$course->image_path)
            : asset('images/home/course-cover-1.webp');
        $lessonCount = $course->lessons->count();
        $enrollUrl = auth()->check() && \Illuminate\Support\Facades\Route::has('student.course-requests.index')
            ? route('student.course-requests.index', ['course_id' => $course->id])
            : route('login');
    @endphp

    <section class="border-b border-[var(--color-line)] bg-white">
        <div class="mx-auto w-full max-w-[90rem] px-4 py-4 sm:px-6 lg:px-8">
            <a href="{{ route('public.courses.index') }}" class="text-base font-semibold text-[var(--color-secondary)] hover:underline">← العودة لرف المقررات</a>
        </div>
    </section>

    <section class="bg-[var(--color-sand)]">
        <div class="mx-auto grid w-full max-w-[90rem] gap-8 px-4 py-8 sm:px-6 lg:grid-cols-[minmax(0,1fr)_22rem] lg:gap-10 lg:px-8 lg:py-12 xl:grid-cols-[minmax(0,1fr)_24rem]">
            <div class="min-w-0 space-y-8">
                <div class="flex flex-col gap-5 rounded-2xl border border-[var(--color-line)] bg-white p-5 sm:flex-row sm:items-start sm:gap-6 sm:p-7">
                    <div class="relative h-[180px] w-[300px] max-w-full shrink-0 self-start overflow-hidden rounded-xl bg-[var(--color-ink)]" style="width: 300px; max-width: 100%; height: 180px;">
                        <img src="{{ $cover }}" alt="" class="h-full w-full object-cover" width="300" height="180" fetchpriority="high">
                        @if ($course->category)
                            <span class="absolute bottom-2 right-2 max-w-[90%] truncate rounded-full bg-white/95 px-2.5 py-1 text-xs font-semibold text-[var(--color-primary)]">{{ $course->category->name }}</span>
                        @endif
                    </div>
                    <div class="min-w-0 flex-1 sm:border-r-4 sm:border-[var(--color-accent)] sm:pr-6">
                        <h1 class="site-brand text-3xl font-bold leading-snug tracking-tight text-[var(--color-ink)] sm:text-4xl lg:text-[2.75rem] lg:leading-tight">{{ $course->title }}</h1>

                        <div class="mt-5 flex items-center gap-3.5">
                            <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-[var(--color-secondary-light)] text-base font-bold text-[var(--color-secondary-hover)]">
                                {{ mb_substr($course->instructor?->name ?? 'م', 0, 1) }}
                            </span>
                            <div>
                                <p class="text-base font-semibold text-[var(--color-ink)] sm:text-lg">{{ $course->instructor?->name ?? 'محاضر المنصة' }}</p>
                                <p class="mt-0.5 text-sm text-[var(--color-muted)]">محاضر المقرر</p>
                            </div>
                        </div>

                        <dl class="mt-6 grid gap-3 sm:grid-cols-3">
                            <div class="rounded-xl bg-[var(--color-primary-light)] px-4 py-3">
                                <dt class="text-xs font-medium text-[var(--color-text-secondary)]">عدد الدروس</dt>
                                <dd class="mt-1 text-xl font-bold tabular-nums text-[var(--color-primary-hover)]">{{ $lessonCount }}</dd>
                            </div>
                            <div class="rounded-xl bg-[var(--color-secondary-light)] px-4 py-3">
                                <dt class="text-xs font-medium text-[var(--color-text-secondary)]">الساعات</dt>
                                <dd class="mt-1 text-xl font-bold tabular-nums text-[var(--color-secondary-hover)]">{{ $course->hours ?: '—' }}</dd>
                            </div>
                            <div class="rounded-xl bg-[var(--color-accent-light)] px-4 py-3">
                                <dt class="text-xs font-medium text-[var(--color-text-secondary)]">الترم</dt>
                                <dd class="mt-1 text-base font-bold leading-snug text-[var(--color-ink)]">{{ $course->term_label ?: 'مفتوح' }}</dd>
                            </div>
                        </dl>

                        @if ($course->schedule_text)
                            <p class="mt-5 rounded-xl border border-[var(--color-line)] bg-[var(--color-sand)]/80 px-4 py-3 text-base leading-relaxed text-[var(--color-text-secondary)]">
                                <span class="font-semibold text-[var(--color-ink)]">الجدول:</span>
                                {{ $course->schedule_text }}
                            </p>
                        @endif
                    </div>
                </div>

                <div class="grid gap-6 lg:grid-cols-5">
                    <div class="rounded-2xl border border-[var(--color-line)] bg-white p-6 sm:p-8 lg:col-span-3">
                        <h2 class="text-2xl font-bold tracking-tight text-[var(--color-ink)] sm:text-3xl">عن المقرر</h2>
                        <div class="mt-5 max-w-prose whitespace-pre-line text-base leading-8 text-[var(--color-text-secondary)] sm:text-lg sm:leading-9">
                            {{ $course->description ?: 'سيُضاف وصف تفصيلي لهذا المقرر قريباً.' }}
                        </div>
                    </div>
                    <div class="rounded-2xl bg-[var(--color-primary-light)] p-6 sm:p-8 lg:col-span-2">
                        <h3 class="text-xl font-bold text-[var(--color-ink)] sm:text-2xl">ماذا بعد الموافقة؟</h3>
                        <ol class="mt-5 space-y-4 text-base leading-7 text-[var(--color-text-secondary)]">
                            <li class="flex gap-3">
                                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-white text-sm font-bold text-[var(--color-primary-hover)]">1</span>
                                <span class="pt-0.5">يظهر المقرر في لوحتك فوراً</span>
                            </li>
                            <li class="flex gap-3">
                                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-white text-sm font-bold text-[var(--color-primary-hover)]">2</span>
                                <span class="pt-0.5">تفتح الدروس بالترتيب المحدد</span>
                            </li>
                            <li class="flex gap-3">
                                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-white text-sm font-bold text-[var(--color-primary-hover)]">3</span>
                                <span class="pt-0.5">تتابع الاختبارات وتقدّمك بوضوح</span>
                            </li>
                        </ol>
                        <a href="{{ route('public.how-it-works') }}" class="mt-6 inline-flex text-base font-semibold text-[var(--color-secondary)] hover:underline">كيف تعمل المنصة</a>
                    </div>
                </div>

                <div class="rounded-2xl border border-[var(--color-line)] bg-white p-6 sm:p-8">
                    <div class="mb-6 flex flex-wrap items-end justify-between gap-3">
                        <div>
                            <h2 class="text-2xl font-bold tracking-tight text-[var(--color-ink)] sm:text-3xl">مخطط الدروس</h2>
                            <p class="mt-2 text-base text-[var(--color-text-secondary)]">عناوين الدروس المنشورة — المحتوى الكامل يُفتح بعد الموافقة.</p>
                        </div>
                        <p class="rounded-full bg-[var(--color-accent-light)] px-3.5 py-1.5 text-sm font-bold text-[var(--color-ink)]">{{ $lessonCount }} درس</p>
                    </div>

                    @if ($course->lessons->isEmpty())
                        <p class="rounded-xl border border-dashed border-[var(--color-line)] px-5 py-10 text-center text-base text-[var(--color-text-secondary)]">لم تُنشر دروس لهذا المقرر بعد.</p>
                    @else
                        <ol class="divide-y divide-[var(--color-line)] overflow-hidden rounded-2xl border border-[var(--color-line)]">
                            @foreach ($course->lessons as $index => $lesson)
                                <li class="flex gap-4 bg-white px-4 py-4 transition hover:bg-[var(--color-sand)]/60 sm:gap-5 sm:px-5 sm:py-5">
                                    <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-[var(--color-primary-light)] text-sm font-bold tabular-nums text-[var(--color-primary-hover)]">{{ $index + 1 }}</span>
                                    <div class="min-w-0 flex-1">
                                        <h3 class="text-base font-semibold text-[var(--color-ink)] sm:text-lg">{{ $lesson->title }}</h3>
                                        @if ($lesson->description)
                                            <p class="mt-1.5 line-clamp-2 text-sm leading-6 text-[var(--color-text-secondary)] sm:text-base sm:leading-7">{{ $lesson->description }}</p>
                                        @endif
                                    </div>
                                    <span class="hidden shrink-0 self-center text-sm font-medium text-[var(--color-muted)] sm:inline">بعد الموافقة</span>
                                </li>
                            @endforeach
                        </ol>
                    @endif
                </div>
            </div>

            <aside class="lg:sticky lg:top-20 lg:self-start">
                <div class="rounded-2xl border border-[var(--color-line)] bg-white p-6 sm:p-7">
                    <p class="text-base font-bold text-[var(--color-primary)]">طلب الالتحاق</p>
                    <p class="mt-3 text-base leading-7 text-[var(--color-text-secondary)]">يراجع الفريق طلبك قبل تفعيل الوصول إلى الدروس والاختبارات.</p>

                    @if ($course->price !== null && (float) $course->price > 0)
                        <p class="mt-5 text-3xl font-bold tabular-nums text-[var(--color-ink)]">{{ number_format((float) $course->price, 2) }} <span class="text-base font-medium text-[var(--color-muted)]">ر.س</span></p>
                    @else
                        <p class="mt-5 text-xl font-bold leading-snug text-[var(--color-ink)]">التحاق بمراجعة الطلب</p>
                    @endif

                    <a href="{{ $enrollUrl }}" class="mt-6 flex w-full items-center justify-center rounded-xl bg-[var(--color-primary)] px-5 py-3.5 text-base font-semibold text-white hover:bg-[var(--color-primary-hover)]">
                        طلب الانضمام
                    </a>
                    @guest
                        <p class="mt-3 text-center text-sm text-[var(--color-muted)]">ستُوجَّه لتسجيل الدخول أولاً إن لم يكن لديك حساب.</p>
                    @endguest

                    <ul class="mt-6 space-y-3 border-t border-[var(--color-line)] pt-5 text-base leading-7 text-[var(--color-text-secondary)]">
                        <li class="flex gap-2.5"><span class="mt-2.5 h-1.5 w-1.5 shrink-0 rounded-full bg-[var(--color-accent)]" aria-hidden="true"></span>محتوى عربي بالكامل</li>
                        <li class="flex gap-2.5"><span class="mt-2.5 h-1.5 w-1.5 shrink-0 rounded-full bg-[var(--color-accent)]" aria-hidden="true"></span>{{ $lessonCount }} درساً مرتباً</li>
                        <li class="flex gap-2.5"><span class="mt-2.5 h-1.5 w-1.5 shrink-0 rounded-full bg-[var(--color-accent)]" aria-hidden="true"></span>موافقة بشرية قبل الوصول</li>
                    </ul>
                </div>
            </aside>
        </div>
    </section>

    <section class="bg-[var(--color-ink)] text-white">
        <div class="mx-auto flex w-full max-w-[90rem] flex-col items-start justify-between gap-5 px-4 py-12 sm:px-6 md:flex-row md:items-center lg:px-8">
            <div>
                <h2 class="site-brand text-3xl font-bold sm:text-4xl">جاهز لطلب الالتحاق؟</h2>
                <p class="mt-3 max-w-lg text-base leading-7 text-white/70">أرسل الطلب الآن — يراجعه الفريق قبل فتح الدروس.</p>
            </div>
            <a href="{{ $enrollUrl }}" class="inline-flex rounded-xl bg-[var(--color-primary)] px-6 py-3.5 text-base font-semibold text-white hover:bg-[var(--color-primary-hover)]">طلب الانضمام</a>
        </div>
    </section>
@endsection
