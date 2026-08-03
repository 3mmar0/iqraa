@extends('layouts.app')

@section('title', 'كيف تعمل المنصة')

@section('content')
    <section class="relative isolate overflow-hidden border-b border-[var(--color-line)]">
        <div class="absolute inset-0 bg-[var(--color-primary-light)]/70"></div>
        <div class="relative mx-auto max-w-6xl px-4 py-16 sm:px-6 sm:py-20">
            <h1 class="site-brand text-4xl font-bold tracking-tight text-[var(--color-ink)] sm:text-5xl md:text-6xl">كيف تعمل المنصة</h1>
            <p class="mt-4 max-w-2xl text-base leading-relaxed text-[var(--color-text-secondary)] sm:text-lg">من أول زيارة حتى إتمام المقرر — هذا هو المسار الذي تسلكه على {{ config('app.name') }}.</p>
        </div>
    </section>

    <section class="bg-white">
        <div class="mx-auto max-w-6xl px-4 py-16 sm:px-6 sm:py-20">
            <ol class="grid gap-5 lg:grid-cols-2">
                @foreach ([
                    ['title' => 'اكتشف المقررات', 'text' => 'تصفّح الكتالوج العام دون تسجيل، أو سجّل لتحتفظ بطلباتك وتقدّمك.'],
                    ['title' => 'أرسل طلب التحاق', 'text' => 'اختر المقرر وأرسل طلباً. يراجع موظف مخوّل الطلب ويوافق أو يرفض مع ملاحظة عند الحاجة.'],
                    ['title' => 'ادرس من لوحتك', 'text' => 'بعد الموافقة يظهر المقرر في لوحة الطالب: دروس، وسائط، اختبارات، وتقدّم.'],
                    ['title' => 'تابع وتنبّه', 'text' => 'الإشعارات والتقويم يبقيانك على اطلاع بالمواعيد والمستجدات.'],
                    ['title' => 'اطلب المساعدة', 'text' => 'إن تعثّرت، راسل الدعم من داخل المنصة أو عبر صفحة التواصل.'],
                ] as $i => $step)
                    <li class="flex gap-4 rounded-2xl border border-[var(--color-line)] bg-[var(--color-sand)]/70 p-6 transition hover:border-[var(--color-primary)]/30 hover:bg-white">
                        <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-[var(--color-primary-light)] text-sm font-bold text-[var(--color-primary-hover)]">{{ $i + 1 }}</span>
                        <div>
                            <h2 class="text-xl font-semibold text-[var(--color-ink)]">{{ $step['title'] }}</h2>
                            <p class="mt-2 text-sm leading-relaxed text-[var(--color-text-secondary)] sm:text-base">{{ $step['text'] }}</p>
                        </div>
                    </li>
                @endforeach
            </ol>

            <div class="mt-14 flex flex-wrap gap-3">
                <a href="{{ route('public.courses.index') }}" class="rounded-2xl bg-[var(--color-primary)] px-5 py-3 text-sm font-semibold text-white hover:bg-[var(--color-primary-hover)]">تصفّح المقررات</a>
                <a href="{{ route('register') }}" class="rounded-2xl border border-[var(--color-secondary)]/40 bg-white px-5 py-3 text-sm font-medium text-[var(--color-secondary)] hover:bg-[var(--color-secondary-light)]">إنشاء حساب</a>
            </div>
        </div>
    </section>
@endsection
