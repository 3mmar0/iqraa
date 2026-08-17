@extends('layouts.app')

@section('title', 'الرئيسية')

@section('content')
    {{-- Academy intro hero — flush under header, full-bleed atmosphere --}}
    <section class="academy-hero academy-hero-home">
        <div class="academy-hero-bg" aria-hidden="true">
            <img
                src="{{ asset('images/home/reading-room-hero.webp') }}"
                alt=""
                width="1920"
                height="1080"
                fetchpriority="high"
                decoding="async"
            >
        </div>
        <div class="academy-hero-overlay" aria-hidden="true"></div>
        <div class="academy-hero-glow" aria-hidden="true"></div>

        <div class="academy-hero-inner academy-rise">
            <x-brand-logo size="hero" />
            <h1 class="academy-display mt-6 max-w-3xl text-3xl font-bold leading-tight text-white sm:text-4xl md:text-[2.75rem] md:leading-[1.2] lg:text-5xl">تعلّم بطمأنينة، وتقدّم بخطوات واضحة.</h1>
            <p class="mt-4 max-w-xl text-base leading-relaxed text-white/75 sm:text-lg">منصة عربية للمقررات والدروس والمتابعة — ابدأ من الكتالوج أو أنشئ حسابك اليوم.</p>
            <div class="mt-8 flex flex-wrap gap-3">
                <a href="{{ route('public.courses.index') }}" class="academy-btn-primary">تصفّح المقررات</a>
                @guest
                    <a href="{{ route('register') }}" class="academy-btn-secondary !border-white/35 !text-white hover:!bg-white/10">إنشاء حساب</a>
                @else
                    <a href="{{ route('dashboard.redirect') }}" class="academy-btn-secondary !border-white/35 !text-white hover:!bg-white/10">الذهاب إلى لوحتي</a>
                @endguest
            </div>
        </div>
    </section>

    {{-- Program tracks --}}
    @if ($categories->isNotEmpty())
        <section class="academy-section border-b border-[var(--color-line)] bg-[var(--color-sand)]" id="programs">
            <div class="mx-auto max-w-[90rem] px-4 sm:px-6 lg:px-8">
                <div class="mb-10 max-w-2xl">
                    <h2 class="academy-display text-2xl font-bold text-[var(--color-text)] sm:text-3xl md:text-4xl">برامج الأكاديمية</h2>
                    <p class="mt-3 text-[var(--color-text-secondary)]">اختر مساراً واستكشف المقررات ضمن تصنيفه.</p>
                </div>
                <div class="grid gap-6 sm:grid-cols-2">
                    @foreach ($categories as $index => $category)
                        <x-program-track :category="$category" :index="$index" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Available courses --}}
    <section class="academy-section bg-[var(--color-surface)]" id="courses">
        <div class="mx-auto max-w-[90rem] px-4 sm:px-6 lg:px-8">
            <div class="mb-10 flex flex-wrap items-end justify-between gap-4">
                <div class="max-w-xl">
                    <h2 class="academy-display text-2xl font-bold text-[var(--color-text)] sm:text-3xl md:text-4xl">المقررات المتاحة</h2>
                    <p class="mt-3 text-[var(--color-text-secondary)]">{{ $courseCount }} مقرر منشور — اختر عنواناً واقرأ التفاصيل قبل طلب الالتحاق.</p>
                </div>
                <a href="{{ route('public.courses.index') }}" class="text-sm font-bold text-[var(--color-primary)] hover:text-[var(--color-secondary-hover)]">عرض كل الدورات ←</a>
            </div>

            @if ($courses->isEmpty())
                <p class="mb-6 text-sm text-[var(--color-text-secondary)]">لا توجد مقررات منشورة بعد.</p>
                @guest
                    <p class="text-sm">
                        <a href="{{ route('register') }}" class="font-bold text-[var(--color-primary)] hover:underline">أنشئ حساباً</a>
                        <span class="text-[var(--color-text-secondary)]"> للبقاء على اطلاع عند نشر المقررات.</span>
                    </p>
                @endguest
            @else
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($courses as $index => $course)
                        <x-course-card :course="$course" :index="$index" :eager="$index < 3" />
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    {{-- Learning path --}}
    <section class="academy-section border-y border-[var(--color-line)] bg-[var(--color-sand)]">
        <div class="mx-auto max-w-[90rem] px-4 sm:px-6 lg:px-8">
            <h2 class="academy-display text-2xl font-bold text-[var(--color-text)] sm:text-3xl">مسارك داخل الأكاديمية</h2>
            <p class="mt-3 max-w-2xl text-[var(--color-text-secondary)]">أربع محطات مرتبة — من أول زيارة حتى المتابعة اليومية في لوحتك.</p>
            <ol class="mt-10 grid gap-4 sm:grid-cols-2">
                @foreach ([
                    ['title' => 'أنشئ حسابك', 'text' => 'تسجيل سريع بالعربية وبدء فوري دون تعقيد.'],
                    ['title' => 'اختر مقرراً', 'text' => 'تصفّح الكتالوج واقرأ وصف كل مقرر ودروسه.'],
                    ['title' => 'اطلب الالتحاق', 'text' => 'يرسل النظام طلبك لمراجعة بشرية من الفريق.'],
                    ['title' => 'تعلّم وتقدّم', 'text' => 'دروس، اختبارات، ومتابعة هادئة في لوحتك.'],
                ] as $i => $step)
                    <li class="flex gap-4 rounded-xl border border-[var(--color-line)] bg-[var(--color-surface)] p-5 sm:p-6">
                        <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-[var(--color-primary-light)] text-sm font-bold tabular-nums text-[var(--color-secondary-hover)]">{{ $i + 1 }}</span>
                        <div>
                            <h3 class="text-lg font-bold text-[var(--color-text)]">{{ $step['title'] }}</h3>
                            <p class="mt-2 text-sm leading-relaxed text-[var(--color-text-secondary)]">{{ $step['text'] }}</p>
                        </div>
                    </li>
                @endforeach
            </ol>
            <a href="{{ route('public.how-it-works') }}" class="mt-8 inline-flex text-sm font-bold text-[var(--color-primary)] hover:underline">تفاصيل أكثر عن آلية العمل</a>
        </div>
    </section>

    @if ($instructors->isNotEmpty())
        <section class="academy-section bg-[var(--color-surface)]">
            <div class="mx-auto max-w-[90rem] px-4 sm:px-6 lg:px-8">
                <div class="mb-10 flex flex-wrap items-end justify-between gap-4">
                    <div>
                        <h2 class="academy-display text-2xl font-bold text-[var(--color-text)] sm:text-3xl">هيئة التدريس</h2>
                        <p class="mt-3 text-[var(--color-text-secondary)]">تعرّف على من يقدّم المحتوى على المنصة.</p>
                    </div>
                    <a href="{{ route('public.instructors') }}" class="text-sm font-bold text-[var(--color-primary)] hover:underline">كل المحاضرين ←</a>
                </div>
                <ul class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($instructors as $instructor)
                        <li class="rounded-xl border border-[var(--color-line)] bg-[var(--color-sand)]/50 px-5 py-6 transition hover:border-[var(--color-primary)]/40">
                            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-[var(--color-primary-light)] text-sm font-bold text-[var(--color-secondary-hover)]">
                                {{ mb_substr($instructor->name, 0, 1) }}
                            </div>
                            <p class="mt-4 text-lg font-bold text-[var(--color-text)]">{{ $instructor->name }}</p>
                            <p class="mt-1 text-sm text-[var(--color-text-secondary)]">{{ $instructor->university ?: 'محاضر في المنصة' }}</p>
                            <p class="mt-4 text-sm font-medium text-[var(--color-primary)]">{{ $instructor->published_courses_count }} مقرر منشور</p>
                        </li>
                    @endforeach
                </ul>
            </div>
        </section>
    @endif

    {{-- FAQ --}}
    <section class="academy-section border-t border-[var(--color-line)] bg-[var(--color-sand)]">
        <div class="mx-auto max-w-[90rem] px-4 sm:px-6 lg:px-8">
            <div class="grid gap-10 lg:grid-cols-[0.9fr_1.1fr] lg:items-start">
                <div>
                    <h2 class="academy-display text-2xl font-bold text-[var(--color-text)] sm:text-3xl">أسئلة قبل أن تبدأ</h2>
                    <p class="mt-3 text-[var(--color-text-secondary)]">إجابات سريعة حول التسجيل والالتحاق والمحتوى العربي.</p>
                    <a href="{{ route('public.faq') }}" class="mt-6 inline-flex text-sm font-bold text-[var(--color-primary)] hover:underline">عرض كل الأسئلة</a>
                </div>
                <div class="divide-y divide-[var(--color-line)] overflow-hidden rounded-xl border border-[var(--color-line)] bg-[var(--color-surface)]" x-data="{ open: 0 }">
                    @foreach ($faqs as $i => $faq)
                        <div class="px-4 sm:px-5">
                            <button type="button" class="flex w-full items-center justify-between gap-4 py-4 text-right focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[var(--color-primary)]" @click="open = open === {{ $i }} ? -1 : {{ $i }}" :aria-expanded="(open === {{ $i }}).toString()">
                                <span class="font-medium text-[var(--color-text)]">{{ $faq->title }}</span>
                                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-[var(--color-primary-light)] text-[var(--color-secondary-hover)]" aria-hidden="true" x-text="open === {{ $i }} ? '−' : '+'"></span>
                            </button>
                            <div x-show="open === {{ $i }}" class="pb-4 text-sm leading-relaxed text-[var(--color-text-secondary)]" style="display: none;">
                                {{ $faq->body }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- Closing CTA --}}
    <section class="academy-hero">
        <div class="relative mx-auto flex max-w-[90rem] flex-col items-start justify-between gap-8 px-4 py-16 sm:px-6 sm:py-20 md:flex-row md:items-center lg:px-8">
            <div>
                <h2 class="academy-display text-3xl font-bold text-white sm:text-4xl">جاهز للبدء؟</h2>
                <p class="mt-4 max-w-lg text-base text-white/70">انضم الآن وتصفّح المقررات، أو راسلنا إن كان لديك سؤال قبل التسجيل.</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('register') }}" class="academy-btn-primary">إنشاء حساب</a>
                <a href="{{ route('public.contact') }}" class="academy-btn-secondary !text-white !border-white/30 hover:!bg-white/10">تواصل معنا</a>
            </div>
        </div>
    </section>
@endsection
