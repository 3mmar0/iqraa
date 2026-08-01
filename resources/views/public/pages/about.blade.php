@extends('layouts.app')

@section('title', 'من نحن')

@section('content')
    <section class="border-b border-[var(--color-line)] bg-white">
        <div class="mx-auto max-w-6xl px-4 py-16 sm:px-6">
            <p class="site-brand text-4xl font-bold text-[var(--color-ink)] sm:text-5xl">{{ config('app.name') }}</p>
            <h1 class="mt-4 max-w-2xl text-2xl font-semibold text-slate-800 sm:text-3xl">منصة تعلم تضع الطمأنينة قبل الضجيج.</h1>
            <p class="mt-4 max-w-2xl text-slate-600 leading-relaxed">نؤمن أن التعلّم الجاد يحتاج مساراً واضحاً، ومتابعة صادقة، وواجهة لا تُشتت. صممنا المنصة للطالب العربي: مقررات، دروس، تقدم، ودعم — في تجربة واحدة مترابطة.</p>
        </div>
    </section>

    <section class="mx-auto max-w-6xl px-4 py-16 sm:px-6">
        <div class="grid gap-6 sm:grid-cols-3">
            <div class="rounded-2xl border border-[var(--color-line)] bg-white px-5 py-6">
                <p class="text-3xl font-bold text-teal-800">{{ number_format($stats['courses']) }}</p>
                <p class="mt-1 text-sm text-slate-600">مقرر منشور</p>
            </div>
            <div class="rounded-2xl border border-[var(--color-line)] bg-white px-5 py-6">
                <p class="text-3xl font-bold text-teal-800">{{ number_format($stats['instructors']) }}</p>
                <p class="mt-1 text-sm text-slate-600">محاضر</p>
            </div>
            <div class="rounded-2xl border border-[var(--color-line)] bg-white px-5 py-6">
                <p class="text-3xl font-bold text-teal-800">{{ number_format($stats['students']) }}</p>
                <p class="mt-1 text-sm text-slate-600">طالب مسجّل</p>
            </div>
        </div>
    </section>

    <section class="border-y border-[var(--color-line)] bg-white">
        <div class="mx-auto grid max-w-6xl gap-10 px-4 py-16 sm:px-6 lg:grid-cols-2">
            <div>
                <h2 class="text-xl font-bold text-[var(--color-ink)]">رسالتنا</h2>
                <p class="mt-3 text-slate-600 leading-relaxed">تيسير الوصول إلى تعليم منظّم، مع احترام وقت المتعلم ومنح المحاضر أدوات واضحة للمتابعة والتقييم.</p>
            </div>
            <div>
                <h2 class="text-xl font-bold text-[var(--color-ink)]">ما يميزنا</h2>
                <p class="mt-3 text-slate-600 leading-relaxed">التحاق بمراجعة بشرية، لوحات متعددة الأدوار، وتجربة عربية كاملة من الصفحة الأولى حتى آخر درس.</p>
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-6xl px-4 py-16 sm:px-6">
        <a href="{{ route('public.contact') }}" class="inline-flex rounded-xl bg-teal-700 px-5 py-3 text-sm font-semibold text-white hover:bg-teal-800">تواصل مع الفريق</a>
    </section>
@endsection
