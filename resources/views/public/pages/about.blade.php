@extends('layouts.app')

@section('title', 'من نحن')

@section('content')
    <x-public-page-hero
        title="من نحن"
        lead="منصة تعلم تضع الطمأنينة قبل الضجيج — مسار واضح، ومتابعة صادقة، وواجهة لا تُشتت."
        :dark="true"
    />

    <section class="academy-section bg-[var(--color-surface)]">
        <div class="mx-auto max-w-[90rem] px-4 sm:px-6 lg:px-8">
            <dl class="grid gap-4 rounded-xl border border-[var(--color-line)] bg-[var(--color-sand)]/80 p-6 sm:grid-cols-3 sm:p-8">
                <div>
                    <dt class="text-sm text-[var(--color-text-secondary)]">مقرر منشور</dt>
                    <dd class="mt-1 text-2xl font-bold tabular-nums text-[var(--color-primary)]">{{ number_format($stats['courses']) }}</dd>
                </div>
                <div>
                    <dt class="text-sm text-[var(--color-text-secondary)]">محاضر</dt>
                    <dd class="mt-1 text-2xl font-bold tabular-nums text-[var(--color-secondary-hover)]">{{ number_format($stats['instructors']) }}</dd>
                </div>
                <div>
                    <dt class="text-sm text-[var(--color-text-secondary)]">طالب مسجّل</dt>
                    <dd class="mt-1 text-2xl font-bold tabular-nums text-[var(--color-text)]">{{ number_format($stats['students']) }}</dd>
                </div>
            </dl>
        </div>
    </section>

    <section class="academy-section border-y border-[var(--color-line)] bg-[var(--color-sand)]">
        <div class="mx-auto grid max-w-[90rem] gap-8 px-4 sm:px-6 lg:grid-cols-2 lg:px-8">
            <div class="rounded-xl border border-[var(--color-line)] bg-[var(--color-surface)] p-6 sm:p-8">
                <h2 class="academy-display text-xl font-bold text-[var(--color-text)]">رسالتنا</h2>
                <p class="mt-3 leading-relaxed text-[var(--color-text-secondary)]">تيسير الوصول إلى تعليم منظّم، مع احترام وقت المتعلم ومنح المحاضر أدوات واضحة للمتابعة والتقييم.</p>
            </div>
            <div class="rounded-xl border border-[var(--color-line)] bg-[var(--color-surface)] p-6 sm:p-8">
                <h2 class="academy-display text-xl font-bold text-[var(--color-text)]">ما يميزنا</h2>
                <p class="mt-3 leading-relaxed text-[var(--color-text-secondary)]">التحاق بمراجعة بشرية، لوحات متعددة الأدوار، وتجربة عربية كاملة من الصفحة الأولى حتى آخر درس.</p>
            </div>
        </div>
    </section>

    <section class="academy-section bg-[var(--color-surface)]">
        <div class="mx-auto max-w-[90rem] px-4 sm:px-6 lg:px-8">
            <a href="{{ route('public.contact') }}" class="academy-btn-primary">تواصل مع الفريق</a>
        </div>
    </section>
@endsection
