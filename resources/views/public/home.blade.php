@extends('layouts.app')

@section('title', 'الرئيسية')

@section('content')
    {{-- Catalog-forward home: brand band + course proof in first viewport --}}
    <section class="border-b border-[var(--color-line)] bg-white">
        <div class="mx-auto max-w-6xl px-4 pb-6 pt-6 sm:px-6 sm:pb-8 sm:pt-8">
            <p class="site-brand home-rise text-3xl font-bold tracking-tight text-[var(--color-ink)] sm:text-4xl md:text-5xl">{{ config('app.name') }}</p>
            <h1 class="home-rise-delay mt-3 max-w-2xl text-lg font-semibold leading-relaxed text-[var(--color-ink)] sm:text-xl">تعلّم بطمأنينة، وتقدّم بخطوات واضحة.</h1>
            <p class="home-rise-delay-2 mt-2 max-w-xl text-sm leading-relaxed text-[var(--color-text-secondary)] sm:text-base">منصة عربية للمقررات والدروس والمتابعة — ابدأ من الكتالوج أو أنشئ حسابك اليوم.</p>
            <div class="home-rise-delay-2 mt-5 flex flex-wrap gap-3">
                <a href="{{ route('public.courses.index') }}" class="inline-flex items-center justify-center rounded-xl bg-[var(--color-primary)] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[var(--color-primary-hover)] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[var(--color-primary)]">تصفّح المقررات</a>
                @guest
                    <a href="{{ route('register') }}" class="inline-flex items-center justify-center rounded-xl border border-[var(--color-secondary)]/40 bg-white px-5 py-3 text-sm font-medium text-[var(--color-secondary)] transition hover:bg-[var(--color-secondary-light)] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[var(--color-secondary)]">إنشاء حساب</a>
                @else
                    <a href="{{ route('dashboard.redirect') }}" class="inline-flex items-center justify-center rounded-xl border border-[var(--color-secondary)]/40 bg-white px-5 py-3 text-sm font-medium text-[var(--color-secondary)] transition hover:bg-[var(--color-secondary-light)] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[var(--color-secondary)]">الذهاب إلى لوحتي</a>
                @endguest
            </div>
        </div>
    </section>

    <section class="bg-[var(--color-sand)]" id="courses">
        <div class="mx-auto max-w-6xl px-4 py-8 sm:px-6 sm:py-10">
            <div class="mb-6 flex flex-wrap items-end justify-between gap-3">
                <div>
                    <h2 class="text-xl font-bold tracking-tight text-[var(--color-ink)] sm:text-2xl">المقررات المتاحة</h2>
                    <p class="mt-1 text-sm text-[var(--color-text-secondary)]">{{ $courseCount }} مقرر منشور حالياً على المنصة.</p>
                </div>
                <a href="{{ route('public.courses.index') }}" class="text-sm font-semibold text-[var(--color-secondary)] hover:underline">عرض الكل</a>
            </div>

            @if ($courses->isEmpty())
                <p class="mb-5 text-sm text-[var(--color-text-secondary)]">لا توجد مقررات منشورة بعد — الشكل أدناه يوضح كيف سيظهر الكتالوج عند النشر.</p>
                <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3" aria-hidden="true">
                    @foreach ([1, 2, 3] as $slot)
                        <div class="overflow-hidden rounded-xl border border-dashed border-[var(--color-line)] bg-white/70">
                            <div class="aspect-[16/10] bg-[var(--color-line)]/60">
                                <img src="{{ asset('images/home/course-cover-'.(($slot % 2) + 1).'.webp') }}" alt="" class="h-full w-full object-cover opacity-50" width="640" height="400">
                            </div>
                            <div class="space-y-2 p-5">
                                <div class="h-4 w-3/4 rounded bg-[var(--color-line)]"></div>
                                <div class="h-3 w-1/3 rounded bg-[var(--color-line)]"></div>
                                <div class="h-3 w-full rounded bg-[var(--color-line)]"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <p class="mt-6 text-sm">
                    <a href="{{ route('register') }}" class="font-semibold text-[var(--color-secondary)] hover:underline">أنشئ حساباً</a>
                    <span class="text-[var(--color-text-secondary)]"> للبقاء على اطلاع عند نشر المقررات.</span>
                </p>
            @else
                <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($courses as $index => $course)
                        @php
                            $cover = $course->image_path
                                ? asset('storage/'.$course->image_path)
                                : asset('images/home/course-cover-'.(($index % 2) + 1).'.webp');
                        @endphp
                        <a href="{{ route('public.courses.show', $course) }}" class="group flex flex-col overflow-hidden rounded-2xl border border-[var(--color-line)] bg-white shadow-[0_12px_28px_-20px_rgba(47,58,69,0.28)] transition hover:-translate-y-0.5 hover:border-[var(--color-primary)]/35 hover:bg-[var(--color-primary-light)]/40 hover:shadow-[0_18px_40px_-22px_rgba(47,58,69,0.35)] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[var(--color-primary)]">
                            <div class="aspect-[16/10] overflow-hidden bg-[var(--color-ink)]">
                                <img src="{{ $cover }}" alt="" class="h-full w-full object-cover transition duration-500 group-hover:scale-[1.03]" width="640" height="400" loading="{{ $index < 3 ? 'eager' : 'lazy' }}">
                            </div>
                            <div class="flex flex-1 flex-col p-5">
                                <h3 class="text-lg font-semibold text-[var(--color-ink)] group-hover:text-[var(--color-primary)]">{{ $course->title }}</h3>
                                <p class="mt-1 text-sm text-[var(--color-muted)]">{{ $course->instructor?->name ?? 'محاضر المنصة' }}</p>
                                <p class="mt-3 line-clamp-2 flex-1 text-sm leading-relaxed text-[var(--color-text-secondary)]">{{ $course->description ?: 'تعرّف على محتوى المقرر وابدأ طلب الالتحاق.' }}</p>
                                <p class="mt-4 text-xs font-semibold text-[var(--color-secondary)]">{{ $course->lessons_count }} درس · عرض التفاصيل</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    <section class="border-y border-[var(--color-line)] bg-white">
        <div class="mx-auto max-w-6xl px-4 py-16 sm:px-6">
            <h2 class="text-2xl font-bold tracking-tight text-[var(--color-ink)] sm:text-3xl">مسارك على المنصة</h2>
            <p class="mt-3 max-w-2xl text-[var(--color-text-secondary)]">أربع خطوات بسيطة من الاكتشاف حتى التعلم المستمر.</p>
            <ol class="mt-12 grid gap-10 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ([
                    ['title' => 'أنشئ حسابك', 'text' => 'تسجيل سريع بالعربية وبدء فوري.'],
                    ['title' => 'اختر مقرراً', 'text' => 'تصفّح الكتالوج واقرأ تفاصيل كل مقرر.'],
                    ['title' => 'اطلب الالتحاق', 'text' => 'يرسل النظام طلبك لمراجعة الفريق.'],
                    ['title' => 'تعلّم وتقدّم', 'text' => 'دروس، اختبارات، ومتابعة في لوحتك.'],
                ] as $i => $step)
                    <li class="relative">
                        @if ($i < 3)
                            <span class="pointer-events-none absolute left-0 top-3 hidden h-px w-[calc(100%+2.5rem)] bg-[var(--color-line)] lg:block" aria-hidden="true"></span>
                        @endif
                        <p class="relative text-sm font-semibold tabular-nums text-[var(--color-accent)]">الخطوة {{ $i + 1 }}</p>
                        <h3 class="relative mt-2 text-lg font-semibold text-[var(--color-ink)]">{{ $step['title'] }}</h3>
                        <p class="relative mt-2 text-sm leading-relaxed text-[var(--color-text-secondary)]">{{ $step['text'] }}</p>
                    </li>
                @endforeach
            </ol>
            <a href="{{ route('public.how-it-works') }}" class="mt-10 inline-block text-sm font-semibold text-[var(--color-secondary)] hover:underline">تفاصيل أكثر عن آلية العمل</a>
        </div>
    </section>

    <section class="border-y border-[var(--color-line)] bg-white">
        <div class="mx-auto max-w-6xl px-4 py-14 sm:px-6">
            <div class="grid gap-10 lg:grid-cols-[1.2fr_0.8fr] lg:items-start">
                <div>
                    <h2 class="text-2xl font-bold tracking-tight text-[var(--color-ink)] sm:text-3xl">صُممت للطالب العربي</h2>
                    <p class="mt-4 max-w-xl leading-relaxed text-[var(--color-text-secondary)]">واجهة من اليمين لليسار، محتوى مرتب، وإشعارات تُبقيك على المسار دون تشتيت. المحاضر يتابع، والدعم قريب عند الحاجة.</p>
                    <ul class="mt-8 space-y-4 text-sm text-[var(--color-ink)]">
                        <li class="flex gap-3"><span class="mt-2 h-1.5 w-1.5 shrink-0 rounded-full bg-[var(--color-primary)]" aria-hidden="true"></span>دروس مرتبة وتقدّم واضح</li>
                        <li class="flex gap-3"><span class="mt-2 h-1.5 w-1.5 shrink-0 rounded-full bg-[var(--color-primary)]" aria-hidden="true"></span>طلبات التحاق بمراجعة بشرية</li>
                        <li class="flex gap-3"><span class="mt-2 h-1.5 w-1.5 shrink-0 rounded-full bg-[var(--color-primary)]" aria-hidden="true"></span>تقويم وإشعارات في مكان واحد</li>
                    </ul>
                </div>
                <div class="border-t border-[var(--color-line)] pt-6 lg:border-t-0 lg:border-r lg:pr-8 lg:pt-0">
                    <p class="site-brand text-2xl font-bold text-[var(--color-ink)] sm:text-3xl">طمأنينة التعلم</p>
                    <p class="mt-3 max-w-sm leading-relaxed text-[var(--color-text-secondary)]">لا اندفاع ولا فوضى — مسار تعليمي هادئ يساعدك على الاستمرار حتى النهاية.</p>
                    <a href="{{ route('public.about') }}" class="mt-6 inline-block text-sm font-semibold text-[var(--color-secondary)] hover:underline">اعرف المزيد عنا</a>
                </div>
            </div>
        </div>
    </section>

    @if ($instructors->isNotEmpty())
        <section class="border-y border-[var(--color-line)] bg-white">
            <div class="mx-auto max-w-6xl px-4 py-16 sm:px-6">
                <div class="mb-8 flex flex-wrap items-end justify-between gap-3">
                    <div>
                        <h2 class="text-2xl font-bold tracking-tight text-[var(--color-ink)] sm:text-3xl">المحاضرون</h2>
                        <p class="mt-2 text-[var(--color-text-secondary)]">تعرّف على من يقدّم المحتوى على المنصة.</p>
                    </div>
                    <a href="{{ route('public.instructors') }}" class="text-sm font-semibold text-[var(--color-secondary)] hover:underline">كل المحاضرين</a>
                </div>
                <ul class="divide-y divide-[var(--color-line)] border-y border-[var(--color-line)]">
                    @foreach ($instructors as $instructor)
                        <li class="flex flex-wrap items-baseline justify-between gap-2 py-5">
                            <div>
                                <p class="text-lg font-semibold text-[var(--color-ink)]">{{ $instructor->name }}</p>
                                <p class="mt-1 text-sm text-[var(--color-text-secondary)]">{{ $instructor->university ?: 'محاضر في المنصة' }}</p>
                            </div>
                            <p class="text-sm font-medium text-[var(--color-accent)]">{{ $instructor->published_courses_count }} مقرر منشور</p>
                        </li>
                    @endforeach
                </ul>
            </div>
        </section>
    @endif

    <section class="bg-[var(--color-sand)]">
        <div class="mx-auto max-w-6xl px-4 py-16 sm:px-6">
            <h2 class="text-2xl font-bold tracking-tight text-[var(--color-ink)] sm:text-3xl">أسئلة متكررة</h2>
            <p class="mt-3 text-[var(--color-text-secondary)]">إجابات سريعة قبل أن تبدأ.</p>
            <div class="mt-8 divide-y divide-[var(--color-line)] border-y border-[var(--color-line)] bg-white" x-data="{ open: 0 }">
                @foreach ($faqs as $i => $faq)
                    <div class="px-1 sm:px-2">
                        <button type="button" class="flex w-full items-center justify-between gap-4 py-4 text-right focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[var(--color-primary)]" @click="open = open === {{ $i }} ? -1 : {{ $i }}" :aria-expanded="(open === {{ $i }}).toString()">
                            <span class="font-medium text-[var(--color-ink)]">{{ $faq->title }}</span>
                            <span class="text-lg text-[var(--color-primary)]" aria-hidden="true" x-text="open === {{ $i }} ? '−' : '+'"></span>
                        </button>
                        <div x-show="open === {{ $i }}" class="pb-4 text-sm leading-relaxed text-[var(--color-text-secondary)]" style="display: none;">
                            {{ $faq->body }}
                        </div>
                    </div>
                @endforeach
            </div>
            <a href="{{ route('public.faq') }}" class="mt-6 inline-block text-sm font-semibold text-[var(--color-secondary)] hover:underline">عرض كل الأسئلة</a>
        </div>
    </section>

    <section class="bg-[var(--color-ink)] text-white">
        <div class="mx-auto flex max-w-6xl flex-col items-start justify-between gap-8 px-4 py-16 sm:px-6 md:flex-row md:items-center">
            <div>
                <h2 class="site-brand text-3xl font-bold text-white sm:text-4xl">جاهز للبداية؟</h2>
                <p class="mt-3 max-w-lg text-white/65">انضم الآن وتصفّح المقررات، أو راسلنا إن كان لديك سؤال قبل التسجيل.</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('register') }}" class="inline-flex rounded-xl bg-[var(--color-primary)] px-5 py-3 text-sm font-semibold text-white hover:bg-[var(--color-primary-hover)]">إنشاء حساب</a>
                <a href="{{ route('public.contact') }}" class="inline-flex rounded-xl border border-white/25 bg-transparent px-5 py-3 text-sm font-medium text-white hover:bg-white/10">تواصل معنا</a>
            </div>
        </div>
    </section>
@endsection
