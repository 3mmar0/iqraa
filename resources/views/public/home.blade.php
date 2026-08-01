@extends('layouts.app')

@section('title', 'الرئيسية')

@section('content')
    <section class="relative overflow-hidden text-white">
        <div class="pointer-events-none absolute inset-0 hero-gradient"></div>
        <div class="pointer-events-none absolute inset-0 opacity-40 hero-wash" style="background:
            radial-gradient(ellipse 70% 50% at 20% 20%, rgba(20,184,166,0.45), transparent 55%),
            radial-gradient(ellipse 50% 60% at 90% 80%, rgba(79,70,229,0.4), transparent 50%);"></div>
        <div class="relative mx-auto flex min-h-[78vh] max-w-6xl flex-col justify-end px-4 pb-16 pt-24 sm:px-6 sm:pb-20">
            <p class="site-brand anim-rise mb-4 text-4xl font-bold text-teal-100 sm:text-6xl md:text-7xl">{{ config('app.name') }}</p>
            <h1 class="anim-rise-delay max-w-2xl text-2xl font-semibold leading-relaxed text-white/95 sm:text-3xl">تعلّم بطمأنينة، وتقدّم بخطوات واضحة.</h1>
            <p class="anim-rise-delay-2 mt-4 max-w-xl text-base text-teal-50/80 sm:text-lg">منصة عربية للمقررات والدروس والمتابعة — ابدأ من الكتالوج أو أنشئ حسابك اليوم.</p>
            <div class="anim-rise-delay-2 mt-8 flex flex-wrap gap-3">
                <a href="{{ route('public.courses.index') }}" class="rounded-xl bg-white px-5 py-3 text-sm font-semibold text-[var(--color-primary)] hover:bg-[var(--color-primary-light)]">تصفّح المقررات</a>
                @guest
                    <a href="{{ route('register') }}" class="rounded-xl border border-white/25 bg-white/5 px-5 py-3 text-sm font-medium text-white hover:bg-white/10">إنشاء حساب</a>
                @else
                    <a href="{{ route('dashboard.redirect') }}" class="rounded-xl border border-white/25 bg-white/5 px-5 py-3 text-sm font-medium text-white hover:bg-white/10">الذهاب إلى لوحتي</a>
                @endguest
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-6xl px-4 py-16 sm:px-6">
        <div class="mb-8 flex flex-wrap items-end justify-between gap-3">
            <div>
                <h2 class="text-2xl font-bold text-[var(--color-ink)]">المقررات المتاحة</h2>
                <p class="mt-1 text-slate-600">{{ $courseCount }} مقرر منشور حالياً على المنصة.</p>
            </div>
            <a href="{{ route('public.courses.index') }}" class="text-sm font-medium text-[var(--color-primary-hover)] hover:underline">عرض الكل</a>
        </div>

        @if ($courses->isEmpty())
            <div class="rounded-2xl border border-dashed border-[var(--color-line)] bg-white px-6 py-14 text-center text-slate-500">
                لا توجد مقررات منشورة بعد. عُد قريباً أو سجّل للبقاء على اطلاع.
            </div>
        @else
            <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($courses as $course)
                    <a href="{{ route('public.courses.show', $course) }}" class="group block overflow-hidden rounded-2xl border border-[var(--color-line)] bg-white transition hover:-translate-y-0.5 hover:border-[var(--color-primary)] hover:shadow-[0_16px_40px_-24px_rgba(12,31,28,0.45)]">
                        <div class="h-32 bg-gradient-to-br from-[var(--color-primary-hover)] via-[var(--color-primary)] to-[var(--color-ink)] transition group-hover:brightness-110"></div>
                        <div class="p-5">
                            <h3 class="text-lg font-semibold text-slate-900 group-hover:text-[var(--color-ink)]">{{ $course->title }}</h3>
                            <p class="mt-1 text-sm text-slate-500">{{ $course->instructor?->name ?? 'محاضر المنصة' }}</p>
                            <p class="mt-3 line-clamp-2 text-sm text-slate-600">{{ $course->description ?: 'تعرّف على محتوى المقرر وابدأ طلب الالتحاق.' }}</p>
                            <p class="mt-4 text-xs font-medium text-[var(--color-primary-hover)]">{{ $course->lessons_count }} درس · عرض التفاصيل</p>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </section>

    <section class="border-y border-[var(--color-line)] bg-white">
        <div class="mx-auto max-w-6xl px-4 py-16 sm:px-6">
            <h2 class="text-2xl font-bold text-[var(--color-ink)]">مسارك على المنصة</h2>
            <p class="mt-2 max-w-2xl text-slate-600">أربع خطوات بسيطة من الاكتشاف حتى التعلم المستمر.</p>
            <ol class="mt-10 grid gap-8 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ([
                    ['title' => 'أنشئ حسابك', 'text' => 'تسجيل سريع بالعربية وبدء فوري.'],
                    ['title' => 'اختر مقرراً', 'text' => 'تصفّح الكتالوج واقرأ تفاصيل كل مقرر.'],
                    ['title' => 'اطلب الالتحاق', 'text' => 'يرسل النظام طلبك لمراجعة الفريق.'],
                    ['title' => 'تعلّم وتقدّم', 'text' => 'دروس، اختبارات، ومتابعة في لوحتك.'],
                ] as $i => $step)
                    <li>
                        <p class="site-brand text-3xl font-bold text-[var(--color-primary)]/80">{{ str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT) }}</p>
                        <h3 class="mt-3 text-lg font-semibold text-slate-900">{{ $step['title'] }}</h3>
                        <p class="mt-2 text-sm leading-relaxed text-slate-600">{{ $step['text'] }}</p>
                    </li>
                @endforeach
            </ol>
            <a href="{{ route('public.how-it-works') }}" class="mt-10 inline-block text-sm font-medium text-[var(--color-primary-hover)] hover:underline">تفاصيل أكثر عن آلية العمل</a>
        </div>
    </section>

    <section class="mx-auto max-w-6xl px-4 py-16 sm:px-6">
        <div class="grid gap-12 lg:grid-cols-2 lg:items-center">
            <div>
                <h2 class="text-2xl font-bold text-[var(--color-ink)]">صُممت للطالب العربي</h2>
                <p class="mt-3 text-slate-600 leading-relaxed">واجهة من اليمين لليسار، محتوى مرتب، وإشعارات تُبقيك على المسار دون تشتيت. المحاضر يتابع، والدعم قريب عند الحاجة.</p>
                <ul class="mt-6 space-y-3 text-sm text-slate-700">
                    <li class="flex gap-3"><span class="mt-1 h-1.5 w-1.5 shrink-0 rounded-full bg-[var(--color-primary)]"></span>دروس مرتبة وتقدّم واضح</li>
                    <li class="flex gap-3"><span class="mt-1 h-1.5 w-1.5 shrink-0 rounded-full bg-[var(--color-primary)]"></span>طلبات التحاق بمراجعة بشرية</li>
                    <li class="flex gap-3"><span class="mt-1 h-1.5 w-1.5 shrink-0 rounded-full bg-[var(--color-primary)]"></span>تقويم وإشعارات في مكان واحد</li>
                </ul>
            </div>
            <div class="relative overflow-hidden rounded-3xl bg-[var(--color-ink)] px-8 py-12 text-white">
                <div class="pointer-events-none absolute inset-0 opacity-40" style="background: radial-gradient(circle at 80% 20%, rgba(45,212,191,0.35), transparent 45%);"></div>
                <p class="relative site-brand text-3xl font-bold text-teal-200">طمأنينة التعلم</p>
                <p class="relative mt-4 max-w-sm text-teal-100/75 leading-relaxed">لا اندفاع ولا فوضى — مسار تعليمي هادئ يساعدك على الاستمرار حتى النهاية.</p>
                <a href="{{ route('public.about') }}" class="relative mt-8 inline-block text-sm font-medium text-teal-300 hover:text-teal-200">اعرف المزيد عنا</a>
            </div>
        </div>
    </section>

    @if ($instructors->isNotEmpty())
        <section class="border-y border-[var(--color-line)] bg-white">
            <div class="mx-auto max-w-6xl px-4 py-16 sm:px-6">
                <div class="mb-8 flex flex-wrap items-end justify-between gap-3">
                    <div>
                        <h2 class="text-2xl font-bold text-[var(--color-ink)]">المحاضرون</h2>
                        <p class="mt-1 text-slate-600">تعرّف على من يقدّم المحتوى على المنصة.</p>
                    </div>
                    <a href="{{ route('public.instructors') }}" class="text-sm font-medium text-[var(--color-primary-hover)] hover:underline">كل المحاضرين</a>
                </div>
                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($instructors as $instructor)
                        <div class="rounded-2xl border border-[var(--color-line)] bg-[var(--color-sand)]/60 px-5 py-5">
                            <p class="text-lg font-semibold text-slate-900">{{ $instructor->name }}</p>
                            <p class="mt-1 text-sm text-slate-500">{{ $instructor->university ?: 'محاضر في المنصة' }}</p>
                            <p class="mt-3 text-xs font-medium text-[var(--color-primary-hover)]">{{ $instructor->published_courses_count }} مقرر منشور</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <section class="mx-auto max-w-6xl px-4 py-16 sm:px-6">
        <h2 class="text-2xl font-bold text-[var(--color-ink)]">أسئلة متكررة</h2>
        <p class="mt-2 text-slate-600">إجابات سريعة قبل أن تبدأ.</p>
        <div class="mt-8 divide-y divide-[var(--color-line)] border-y border-[var(--color-line)]" x-data="{ open: 0 }">
            @foreach ($faqs as $i => $faq)
                <div>
                    <button type="button" class="flex w-full items-center justify-between gap-4 py-4 text-right" @click="open = open === {{ $i }} ? -1 : {{ $i }}">
                        <span class="font-medium text-slate-900">{{ $faq->title }}</span>
                        <span class="text-[var(--color-primary)]" x-text="open === {{ $i }} ? '−' : '+'"></span>
                    </button>
                    <div x-show="open === {{ $i }}" class="pb-4 text-sm leading-relaxed text-slate-600" style="display: none;">
                        {{ $faq->body }}
                    </div>
                </div>
            @endforeach
        </div>
        <a href="{{ route('public.faq') }}" class="mt-6 inline-block text-sm font-medium text-[var(--color-primary-hover)] hover:underline">عرض كل الأسئلة</a>
    </section>

    <section class="bg-[var(--color-ink)] text-white">
        <div class="mx-auto flex max-w-6xl flex-col items-start justify-between gap-6 px-4 py-16 sm:px-6 md:flex-row md:items-center">
            <div>
                <h2 class="site-brand text-3xl font-bold text-teal-200 sm:text-4xl">جاهز للبداية؟</h2>
                <p class="mt-2 max-w-lg text-teal-100/70">انضم الآن وتصفّح المقررات، أو راسلنا إن كان لديك سؤال قبل التسجيل.</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('register') }}" class="rounded-xl bg-[var(--color-accent)] px-5 py-3 text-sm font-semibold text-[var(--color-ink)] hover:brightness-95">إنشاء حساب</a>
                <a href="{{ route('public.contact') }}" class="rounded-xl border border-white/20 px-5 py-3 text-sm font-medium text-white hover:bg-white/10">تواصل معنا</a>
            </div>
        </div>
    </section>
@endsection
