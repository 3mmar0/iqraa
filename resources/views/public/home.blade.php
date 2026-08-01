@extends('layouts.app')

@section('title', 'الرئيسية')

@section('content')
    <section class="relative overflow-hidden bg-[var(--color-ink)] text-white">
        <div class="pointer-events-none absolute inset-0 opacity-40" style="background:
            radial-gradient(ellipse 70% 50% at 20% 20%, rgba(20,184,166,0.35), transparent 55%),
            radial-gradient(ellipse 50% 60% at 90% 80%, rgba(15,118,110,0.45), transparent 50%),
            linear-gradient(160deg, #0c1f1c 0%, #163530 55%, #0f766e 120%);"></div>
        <div class="relative mx-auto flex min-h-[78vh] max-w-6xl flex-col justify-end px-4 pb-16 pt-24 sm:px-6 sm:pb-20">
            <p class="site-brand anim-rise mb-4 text-4xl font-bold text-teal-200 sm:text-6xl md:text-7xl">{{ config('app.name') }}</p>
            <h1 class="anim-rise-delay max-w-2xl text-2xl font-semibold leading-relaxed text-white/95 sm:text-3xl">تعلّم بطمأنينة، وتقدّم بخطوات واضحة.</h1>
            <p class="anim-rise-delay-2 mt-4 max-w-xl text-base text-teal-100/75 sm:text-lg">منصة عربية للمقررات والدروس والمتابعة — ابدأ من الكتالوج أو أنشئ حسابك اليوم.</p>
            <div class="anim-rise-delay-2 mt-8 flex flex-wrap gap-3">
                <a href="{{ route('public.courses.index') }}" class="rounded-xl bg-teal-400 px-5 py-3 text-sm font-semibold text-[var(--color-ink)] hover:bg-teal-300">تصفّح المقررات</a>
                @guest
                    <a href="{{ route('register') }}" class="rounded-xl border border-white/25 bg-white/5 px-5 py-3 text-sm font-medium text-white hover:bg-white/10">إنشاء حساب</a>
                    <a href="{{ route('login') }}" class="rounded-xl px-5 py-3 text-sm font-medium text-teal-100/80 hover:text-white">لدي حساب</a>
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
            <a href="{{ route('public.courses.index') }}" class="text-sm font-medium text-teal-800 hover:underline">عرض الكل</a>
        </div>

        @if ($courses->isEmpty())
            <div class="rounded-2xl border border-dashed border-[var(--color-line)] bg-white px-6 py-14 text-center text-slate-500">
                لا توجد مقررات منشورة بعد. عُد قريباً أو سجّل للبقاء على اطلاع.
            </div>
        @else
            <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($courses as $course)
                    <a href="{{ route('public.courses.show', $course) }}" class="group block overflow-hidden rounded-2xl border border-[var(--color-line)] bg-white transition hover:-translate-y-0.5 hover:border-teal-300 hover:shadow-[0_16px_40px_-24px_rgba(12,31,28,0.45)]">
                        <div class="h-32 bg-gradient-to-br from-teal-800 via-teal-700 to-[var(--color-ink)] transition group-hover:brightness-110"></div>
                        <div class="p-5">
                            <h3 class="text-lg font-semibold text-slate-900 group-hover:text-teal-900">{{ $course->title }}</h3>
                            <p class="mt-1 text-sm text-slate-500">{{ $course->instructor?->name ?? 'محاضر المنصة' }}</p>
                            <p class="mt-3 line-clamp-2 text-sm text-slate-600">{{ $course->description ?: 'تعرّف على محتوى المقرر وابدأ طلب الالتحاق.' }}</p>
                            <p class="mt-4 text-xs font-medium text-teal-800">{{ $course->lessons_count }} درس · عرض التفاصيل</p>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </section>

    <section class="border-y border-[var(--color-line)] bg-white">
        <div class="mx-auto grid max-w-6xl gap-10 px-4 py-16 sm:px-6 lg:grid-cols-3">
            <div>
                <h2 class="text-lg font-semibold text-[var(--color-ink)]">كيف تبدأ؟</h2>
                <p class="mt-2 text-sm leading-relaxed text-slate-600">أنشئ حساباً، تصفّح المقررات المنشورة، ثم أرسل طلب التحاق ليراجعه الفريق.</p>
            </div>
            <div>
                <h2 class="text-lg font-semibold text-[var(--color-ink)]">محتوى منظّم</h2>
                <p class="mt-2 text-sm leading-relaxed text-slate-600">دروس، تقدّم، اختبارات وإشعارات في لوحة واحدة للطالب.</p>
            </div>
            <div>
                <h2 class="text-lg font-semibold text-[var(--color-ink)]">فريق يدعمك</h2>
                <p class="mt-2 text-sm leading-relaxed text-slate-600">محاضرون ودعم ومتابعة حتى تكتمل رحلتك التعليمية بثقة.</p>
            </div>
        </div>
    </section>
@endsection
