@extends('layouts.app')

@section('title', 'من نحن')

@section('content')
    <section class="border-b border-[var(--color-line)] bg-[var(--color-primary-light)]/60">
        <div class="mx-auto max-w-6xl px-4 py-16 sm:px-6 sm:py-20">
            <p class="site-brand text-4xl font-bold tracking-tight text-[var(--color-ink)] sm:text-5xl md:text-6xl">{{ config('app.name') }}</p>
            <h1 class="mt-4 max-w-2xl text-2xl font-semibold leading-relaxed text-[var(--color-ink)] sm:text-3xl">منصة تعلم تضع الطمأنينة قبل الضجيج.</h1>
            <p class="mt-4 max-w-2xl text-base leading-relaxed text-[var(--color-text-secondary)] sm:text-lg">نؤمن أن التعلّم الجاد يحتاج مساراً واضحاً، ومتابعة صادقة، وواجهة لا تُشتت. صممنا المنصة للطالب العربي: مقررات، دروس، تقدم، ودعم — في تجربة واحدة مترابطة.</p>
        </div>
    </section>

    <section class="bg-white">
        <div class="mx-auto max-w-6xl px-4 py-12 sm:px-6">
            <dl class="grid gap-4 rounded-2xl border border-[var(--color-line)] bg-[var(--color-sand)]/80 p-6 sm:grid-cols-3 sm:p-8">
                <div>
                    <dt class="text-sm text-[var(--color-text-secondary)]">مقرر منشور</dt>
                    <dd class="mt-1 text-2xl font-bold tabular-nums text-[var(--color-primary)]">{{ number_format($stats['courses']) }}</dd>
                </div>
                <div>
                    <dt class="text-sm text-[var(--color-text-secondary)]">محاضر</dt>
                    <dd class="mt-1 text-2xl font-bold tabular-nums text-[var(--color-secondary)]">{{ number_format($stats['instructors']) }}</dd>
                </div>
                <div>
                    <dt class="text-sm text-[var(--color-text-secondary)]">طالب مسجّل</dt>
                    <dd class="mt-1 text-2xl font-bold tabular-nums text-[var(--color-ink)]">{{ number_format($stats['students']) }}</dd>
                </div>
            </dl>
        </div>
    </section>

    <section class="border-y border-[var(--color-line)] bg-[var(--color-sand)]">
        <div class="mx-auto grid max-w-6xl gap-10 px-4 py-16 sm:px-6 lg:grid-cols-2 sm:py-20">
            <div class="rounded-2xl border border-[var(--color-line)] bg-white p-6 sm:p-8">
                <h2 class="text-xl font-bold text-[var(--color-ink)]">رسالتنا</h2>
                <p class="mt-3 leading-relaxed text-[var(--color-text-secondary)]">تيسير الوصول إلى تعليم منظّم، مع احترام وقت المتعلم ومنح المحاضر أدوات واضحة للمتابعة والتقييم.</p>
            </div>
            <div class="rounded-2xl border border-[var(--color-line)] bg-white p-6 sm:p-8">
                <h2 class="text-xl font-bold text-[var(--color-ink)]">ما يميزنا</h2>
                <p class="mt-3 leading-relaxed text-[var(--color-text-secondary)]">التحاق بمراجعة بشرية، لوحات متعددة الأدوار، وتجربة عربية كاملة من الصفحة الأولى حتى آخر درس.</p>
            </div>
        </div>
    </section>

    <section class="bg-white">
        <div class="mx-auto max-w-6xl px-4 py-14 sm:px-6">
            <a href="{{ route('public.contact') }}" class="inline-flex rounded-2xl bg-[var(--color-primary)] px-5 py-3 text-sm font-semibold text-white hover:bg-[var(--color-primary-hover)]">تواصل مع الفريق</a>
        </div>
    </section>
@endsection
