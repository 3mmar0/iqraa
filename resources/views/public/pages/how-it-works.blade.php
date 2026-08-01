@extends('layouts.app')

@section('title', 'كيف تعمل المنصة')

@section('content')
    <section class="border-b border-[var(--color-line)] bg-white">
        <div class="mx-auto max-w-6xl px-4 py-16 sm:px-6">
            <h1 class="site-brand text-4xl font-bold text-[var(--color-ink)] sm:text-5xl">كيف تعمل المنصة</h1>
            <p class="mt-4 max-w-2xl text-slate-600 leading-relaxed">من أول زيارة حتى إتمام المقرر — هذا هو المسار الذي تسلكه على {{ config('app.name') }}.</p>
        </div>
    </section>

    <section class="mx-auto max-w-3xl px-4 py-16 sm:px-6">
        <ol class="space-y-12">
            @foreach ([
                ['title' => 'اكتشف المقررات', 'text' => 'تصفّح الكتالوج العام دون تسجيل، أو سجّل لتحتفظ بطلباتك وتقدّمك.'],
                ['title' => 'أرسل طلب التحاق', 'text' => 'اختر المقرر وأرسل طلباً. يراجع موظف مخوّل الطلب ويوافق أو يرفض مع ملاحظة عند الحاجة.'],
                ['title' => 'ادرس من لوحتك', 'text' => 'بعد الموافقة يظهر المقرر في لوحة الطالب: دروس، وسائط، اختبارات، وتقدّم.'],
                ['title' => 'تابع وتنبّه', 'text' => 'الإشعارات والتقويم يبقيانك على اطلاع بالمواعيد والمستجدات.'],
                ['title' => 'اطلب المساعدة', 'text' => 'إن تعثّرت، راسل الدعم من داخل المنصة أو عبر صفحة التواصل.'],
            ] as $i => $step)
                <li class="grid gap-3 sm:grid-cols-[4rem_1fr] sm:gap-6">
                    <span class="site-brand text-3xl font-bold text-[var(--color-primary)]/70">{{ str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT) }}</span>
                    <div>
                        <h2 class="text-xl font-semibold text-slate-900">{{ $step['title'] }}</h2>
                        <p class="mt-2 text-slate-600 leading-relaxed">{{ $step['text'] }}</p>
                    </div>
                </li>
            @endforeach
        </ol>

        <div class="mt-14 flex flex-wrap gap-3">
            <a href="{{ route('public.courses.index') }}" class="rounded-xl bg-[var(--color-primary)] px-5 py-3 text-sm font-semibold text-white hover:bg-[var(--color-primary-hover)]">تصفّح المقررات</a>
            <a href="{{ route('register') }}" class="rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-medium text-slate-800 hover:bg-slate-50">إنشاء حساب</a>
        </div>
    </section>
@endsection
