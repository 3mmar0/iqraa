@extends('layouts.app')

@section('title', 'الرئيسية')

@section('content')
    {{-- Reading Room + official palette: atmosphere hero, then deeper catalog path --}}
    <section class="relative isolate min-h-[88vh] overflow-hidden text-[var(--color-ink)]">
        <img
            src="{{ asset('images/home/reading-room-hero.webp') }}"
            alt=""
            class="absolute inset-0 h-full w-full object-cover"
            width="1920"
            height="1080"
            fetchpriority="high"
        >
        <div class="absolute inset-0 bg-[var(--color-sand)]/78"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-[var(--color-sand)] via-[var(--color-sand)]/55 to-transparent"></div>
        <div class="relative mx-auto flex min-h-[88vh] max-w-6xl flex-col justify-end px-4 pb-14 pt-28 sm:px-6 sm:pb-20">
            <div class="home-rise">
                <x-brand-logo size="hero" />
            </div>
            <h1 class="home-rise-delay mt-4 max-w-2xl text-xl font-semibold leading-relaxed text-[var(--color-ink)] sm:text-2xl md:text-3xl">تعلّم بطمأنينة، وتقدّم بخطوات واضحة.</h1>
            <p class="home-rise-delay-2 mt-3 max-w-xl text-base leading-relaxed text-[var(--color-text-secondary)] sm:text-lg">منصة عربية للمقررات والدروس والمتابعة — ابدأ من الكتالوج أو أنشئ حسابك اليوم.</p>
            <div class="home-rise-delay-2 mt-8 flex flex-wrap gap-3">
                <a href="{{ route('public.courses.index') }}" class="inline-flex items-center justify-center rounded-2xl bg-[var(--color-primary)] px-6 py-3.5 text-sm font-semibold text-white shadow-[0_14px_28px_-18px_rgba(42,157,143,0.7)] transition hover:bg-[var(--color-primary-hover)] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[var(--color-primary)]">تصفّح المقررات</a>
                @guest
                    <a href="{{ route('register') }}" class="inline-flex items-center justify-center rounded-2xl border border-[var(--color-secondary)]/45 bg-white/90 px-6 py-3.5 text-sm font-medium text-[var(--color-secondary)] backdrop-blur-sm transition hover:bg-[var(--color-secondary-light)] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[var(--color-secondary)]">إنشاء حساب</a>
                @else
                    <a href="{{ route('dashboard.redirect') }}" class="inline-flex items-center justify-center rounded-2xl border border-[var(--color-secondary)]/45 bg-white/90 px-6 py-3.5 text-sm font-medium text-[var(--color-secondary)] backdrop-blur-sm transition hover:bg-[var(--color-secondary-light)] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[var(--color-secondary)]">الذهاب إلى لوحتي</a>
                @endguest
            </div>
        </div>
    </section>

    <section class="relative border-b border-[var(--color-line)] bg-[var(--color-sand)]" id="courses">
        <div class="pointer-events-none absolute inset-0 opacity-40" style="background-image: url('{{ asset('images/home/reading-room-wash.webp') }}'); background-size: cover;"></div>
        <div class="relative mx-auto max-w-6xl px-4 py-16 sm:px-6 sm:py-20">
            <div class="mb-10 flex flex-wrap items-end justify-between gap-4">
                <div class="max-w-xl">
                    <h2 class="text-2xl font-bold tracking-tight text-[var(--color-ink)] sm:text-3xl md:text-4xl">رفّ المقررات</h2>
                    <p class="mt-3 text-[var(--color-text-secondary)]">{{ $courseCount }} مقرر منشور — اختر عنواناً واقرأ التفاصيل قبل طلب الالتحاق.</p>
                </div>
                <a href="{{ route('public.courses.index') }}" class="text-sm font-semibold text-[var(--color-secondary)] hover:underline">عرض الكتالوج كاملاً</a>
            </div>

            @if ($courses->isEmpty())
                <p class="mb-6 text-sm text-[var(--color-text-secondary)]">لا توجد مقررات منشورة بعد — الشكل أدناه يوضح كيف سيظهر الرف عند النشر.</p>
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3" aria-hidden="true">
                    @foreach ([1, 2, 3] as $slot)
                        <div class="overflow-hidden rounded-2xl border border-dashed border-[var(--color-line)] bg-white/80">
                            <div class="aspect-[16/10] bg-[var(--color-primary-light)]">
                                <img src="{{ asset('images/home/course-cover-'.(($slot % 2) + 1).'.webp') }}" alt="" class="h-full w-full object-cover opacity-45" width="640" height="400">
                            </div>
                            <div class="space-y-2 p-5">
                                <div class="h-4 w-3/4 rounded-full bg-[var(--color-line)]"></div>
                                <div class="h-3 w-1/3 rounded-full bg-[var(--color-line)]"></div>
                                <div class="h-3 w-full rounded-full bg-[var(--color-line)]"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <p class="mt-8 text-sm">
                    <a href="{{ route('register') }}" class="font-semibold text-[var(--color-secondary)] hover:underline">أنشئ حساباً</a>
                    <span class="text-[var(--color-text-secondary)]"> للبقاء على اطلاع عند نشر المقررات.</span>
                </p>
            @else
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($courses as $index => $course)
                        @php
                            $cover = $course->image_path
                                ? asset('storage/'.$course->image_path)
                                : asset('images/home/course-cover-'.(($index % 2) + 1).'.webp');
                        @endphp
                        <a href="{{ route('public.courses.show', $course) }}" class="group flex flex-col overflow-hidden rounded-2xl border border-[var(--color-line)] bg-white shadow-[0_16px_36px_-24px_rgba(47,58,69,0.4)] transition duration-300 hover:-translate-y-1 hover:border-[var(--color-primary)]/40 hover:shadow-[0_22px_44px_-22px_rgba(42,157,143,0.35)] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[var(--color-primary)]">
                            <div class="relative aspect-[16/10] overflow-hidden bg-[var(--color-ink)]">
                                <img src="{{ $cover }}" alt="" class="h-full w-full object-cover transition duration-500 group-hover:scale-[1.04]" width="640" height="400" loading="{{ $index < 3 ? 'eager' : 'lazy' }}">
                                <span class="absolute bottom-3 right-3 rounded-full bg-white/95 px-3 py-1 text-xs font-semibold text-[var(--color-primary)] shadow-sm">{{ $course->lessons_count }} درس</span>
                            </div>
                            <div class="flex flex-1 flex-col border-t-4 border-[var(--color-accent)]/70 p-5">
                                <h3 class="text-lg font-semibold text-[var(--color-ink)] transition group-hover:text-[var(--color-primary)]">{{ $course->title }}</h3>
                                <p class="mt-1 text-sm text-[var(--color-muted)]">{{ $course->instructor?->name ?? 'محاضر المنصة' }}</p>
                                <p class="mt-3 line-clamp-2 flex-1 text-sm leading-relaxed text-[var(--color-text-secondary)]">{{ $course->description ?: 'تعرّف على محتوى المقرر وابدأ طلب الالتحاق.' }}</p>
                                <p class="mt-4 text-xs font-semibold text-[var(--color-secondary)]">عرض التفاصيل وطلب الالتحاق</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    <section class="bg-white">
        <div class="mx-auto max-w-6xl px-4 py-16 sm:px-6 sm:py-20">
            <h2 class="text-2xl font-bold tracking-tight text-[var(--color-ink)] sm:text-3xl md:text-4xl">مسارك داخل القاعة</h2>
            <p class="mt-3 max-w-2xl text-[var(--color-text-secondary)]">أربع محطات مرتبة — من أول زيارة حتى المتابعة اليومية في لوحتك.</p>
            <ol class="mt-12 grid gap-5 sm:grid-cols-2">
                @foreach ([
                    ['title' => 'أنشئ حسابك', 'text' => 'تسجيل سريع بالعربية وبدء فوري دون تعقيد.', 'tone' => 'bg-[var(--color-primary-light)] text-[var(--color-primary-hover)]'],
                    ['title' => 'اختر مقرراً', 'text' => 'تصفّح الرف واقرأ وصف كل مقرر ودروسه.', 'tone' => 'bg-[var(--color-secondary-light)] text-[var(--color-secondary-hover)]'],
                    ['title' => 'اطلب الالتحاق', 'text' => 'يرسل النظام طلبك لمراجعة بشرية من الفريق.', 'tone' => 'bg-[var(--color-accent-light)] text-[var(--color-ink)]'],
                    ['title' => 'تعلّم وتقدّم', 'text' => 'دروس، اختبارات، ومتابعة هادئة في لوحتك.', 'tone' => 'bg-[var(--color-sand)] text-[var(--color-ink)]'],
                ] as $i => $step)
                    <li class="flex gap-4 rounded-2xl border border-[var(--color-line)] bg-[var(--color-sand)]/60 p-5 transition hover:border-[var(--color-primary)]/30 hover:bg-white sm:p-6">
                        <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl text-sm font-bold tabular-nums {{ $step['tone'] }}">{{ $i + 1 }}</span>
                        <div>
                            <h3 class="text-lg font-semibold text-[var(--color-ink)]">{{ $step['title'] }}</h3>
                            <p class="mt-2 text-sm leading-relaxed text-[var(--color-text-secondary)]">{{ $step['text'] }}</p>
                        </div>
                    </li>
                @endforeach
            </ol>
            <a href="{{ route('public.how-it-works') }}" class="mt-10 inline-flex text-sm font-semibold text-[var(--color-secondary)] hover:underline">تفاصيل أكثر عن آلية العمل</a>
        </div>
    </section>

    <section class="relative overflow-hidden border-y border-[var(--color-line)] bg-[var(--color-primary-light)]/55">
        <div class="mx-auto grid max-w-6xl gap-12 px-4 py-16 sm:px-6 sm:py-20 lg:grid-cols-2 lg:items-center">
            <div>
                <h2 class="text-2xl font-bold tracking-tight text-[var(--color-ink)] sm:text-3xl md:text-4xl">صُممت للطالب العربي</h2>
                <p class="mt-4 max-w-xl text-base leading-relaxed text-[var(--color-text-secondary)] sm:text-lg">واجهة من اليمين لليسار، محتوى مرتب، ومسار التحاق بمراجعة بشرية — دون اندفاع أو فوضى.</p>
                <ul class="mt-8 space-y-4">
                    @foreach ([
                        'دروس مرتبة وتقدّم واضح في لوحة واحدة',
                        'طلبات التحاق تُراجع قبل تفعيل الوصول',
                        'تقويم وإشعارات تُبقيك على المسار',
                    ] as $item)
                        <li class="flex items-start gap-3 rounded-2xl bg-white/80 px-4 py-3 text-sm text-[var(--color-ink)] shadow-[0_10px_24px_-20px_rgba(47,58,69,0.35)]">
                            <span class="mt-1.5 h-2 w-2 shrink-0 rounded-full bg-[var(--color-primary)]" aria-hidden="true"></span>
                            <span>{{ $item }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="rounded-[1.75rem] bg-[var(--color-ink)] px-8 py-10 text-white sm:px-10 sm:py-12">
                <p class="text-sm font-semibold text-[var(--color-accent)]">وعد المنصة</p>
                <p class="site-brand mt-3 text-3xl font-bold leading-snug sm:text-4xl">طمأنينة التعلم</p>
                <p class="mt-4 max-w-md text-base leading-relaxed text-white/70">لا اندفاع ولا فوضى — مسار تعليمي هادئ يساعدك على الاستمرار حتى النهاية.</p>
                <a href="{{ route('public.about') }}" class="mt-8 inline-flex rounded-2xl bg-[var(--color-primary)] px-5 py-3 text-sm font-semibold text-white hover:bg-[var(--color-primary-hover)]">اعرف المزيد عنا</a>
            </div>
        </div>
    </section>

    <section class="bg-white">
        <div class="mx-auto max-w-6xl px-4 py-16 sm:px-6 sm:py-20">
            <h2 class="text-2xl font-bold tracking-tight text-[var(--color-ink)] sm:text-3xl md:text-4xl">ماذا تجد بعد الموافقة؟</h2>
            <p class="mt-3 max-w-2xl text-[var(--color-text-secondary)]">بعد قبول طلب الالتحاق يظهر المقرر في لوحتك مباشرة — هذه محطات المتابعة اليومية.</p>
            <div class="mt-12 grid gap-8 lg:grid-cols-3">
                @foreach ([
                    ['title' => 'مسار الدروس', 'text' => 'دروس متسلسلة يمكنك فتحها وفق تقدّمك، مع وضوح ما أُنجز وما تبقّى.'],
                    ['title' => 'اختبارات هادئة', 'text' => 'تقييمات مرتبطة بالمقرر تساعدك على التأكد من الفهم دون ضجيج.'],
                    ['title' => 'متابعة ودعم', 'text' => 'المحاضر والفريق قريبان عبر الإشعارات والطلبات عند الحاجة.'],
                ] as $block)
                    <article class="border-t-2 border-[var(--color-primary)] pt-6">
                        <h3 class="text-lg font-semibold text-[var(--color-ink)]">{{ $block['title'] }}</h3>
                        <p class="mt-3 text-sm leading-relaxed text-[var(--color-text-secondary)]">{{ $block['text'] }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    @if ($instructors->isNotEmpty())
        <section class="border-y border-[var(--color-line)] bg-[var(--color-sand)]">
            <div class="mx-auto max-w-6xl px-4 py-16 sm:px-6 sm:py-20">
                <div class="mb-10 flex flex-wrap items-end justify-between gap-4">
                    <div>
                        <h2 class="text-2xl font-bold tracking-tight text-[var(--color-ink)] sm:text-3xl md:text-4xl">المحاضرون</h2>
                        <p class="mt-3 text-[var(--color-text-secondary)]">تعرّف على من يقدّم المحتوى على المنصة.</p>
                    </div>
                    <a href="{{ route('public.instructors') }}" class="text-sm font-semibold text-[var(--color-secondary)] hover:underline">كل المحاضرين</a>
                </div>
                <ul class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($instructors as $instructor)
                        <li class="rounded-2xl border border-[var(--color-line)] bg-white px-5 py-6 transition hover:border-[var(--color-secondary)]/35">
                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[var(--color-secondary-light)] text-sm font-bold text-[var(--color-secondary-hover)]">
                                {{ mb_substr($instructor->name, 0, 1) }}
                            </div>
                            <p class="mt-4 text-lg font-semibold text-[var(--color-ink)]">{{ $instructor->name }}</p>
                            <p class="mt-1 text-sm text-[var(--color-text-secondary)]">{{ $instructor->university ?: 'محاضر في المنصة' }}</p>
                            <p class="mt-4 text-sm font-medium text-[var(--color-accent)]">{{ $instructor->published_courses_count }} مقرر منشور</p>
                        </li>
                    @endforeach
                </ul>
            </div>
        </section>
    @endif

    <section class="bg-white">
        <div class="mx-auto max-w-6xl px-4 py-16 sm:px-6 sm:py-20">
            <div class="grid gap-10 lg:grid-cols-[0.9fr_1.1fr] lg:items-start">
                <div>
                    <h2 class="text-2xl font-bold tracking-tight text-[var(--color-ink)] sm:text-3xl md:text-4xl">أسئلة قبل أن تبدأ</h2>
                    <p class="mt-3 text-[var(--color-text-secondary)]">إجابات سريعة حول التسجيل والالتحاق والمحتوى العربي.</p>
                    <a href="{{ route('public.faq') }}" class="mt-6 inline-flex text-sm font-semibold text-[var(--color-secondary)] hover:underline">عرض كل الأسئلة</a>
                </div>
                <div class="divide-y divide-[var(--color-line)] overflow-hidden rounded-2xl border border-[var(--color-line)] bg-[var(--color-sand)]/40" x-data="{ open: 0 }">
                    @foreach ($faqs as $i => $faq)
                        <div class="bg-white/80 px-4 sm:px-5">
                            <button type="button" class="flex w-full items-center justify-between gap-4 py-4 text-right focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[var(--color-primary)]" @click="open = open === {{ $i }} ? -1 : {{ $i }}" :aria-expanded="(open === {{ $i }}).toString()">
                                <span class="font-medium text-[var(--color-ink)]">{{ $faq->title }}</span>
                                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-[var(--color-primary-light)] text-[var(--color-primary)]" aria-hidden="true" x-text="open === {{ $i }} ? '−' : '+'"></span>
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

    <section class="bg-[var(--color-ink)] text-white">
        <div class="mx-auto flex max-w-6xl flex-col items-start justify-between gap-8 px-4 py-16 sm:px-6 sm:py-20 md:flex-row md:items-center">
            <div>
                <h2 class="site-brand text-3xl font-bold sm:text-4xl md:text-5xl">جاهز للدخول إلى القاعة؟</h2>
                <p class="mt-4 max-w-lg text-base text-white/70">انضم الآن وتصفّح المقررات، أو راسلنا إن كان لديك سؤال قبل التسجيل.</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('register') }}" class="inline-flex rounded-2xl bg-[var(--color-primary)] px-6 py-3.5 text-sm font-semibold text-white hover:bg-[var(--color-primary-hover)]">إنشاء حساب</a>
                <a href="{{ route('public.contact') }}" class="inline-flex rounded-2xl border border-white/25 px-6 py-3.5 text-sm font-medium text-white hover:bg-white/10">تواصل معنا</a>
            </div>
        </div>
    </section>
@endsection
