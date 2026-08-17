@extends('layouts.app')

@section('title', 'كيف تعمل المنصة')

@section('content')
    <x-public-page-hero
        title="كيف تعمل المنصة"
        :lead="'من أول زيارة حتى إتمام المقرر — هذا هو المسار الذي تسلكه على '.config('app.name').'.'"
    />

    <section class="academy-section bg-[var(--color-surface)]">
        <div class="mx-auto max-w-[90rem] px-4 sm:px-6 lg:px-8">
            <ol class="grid gap-4 lg:grid-cols-2">
                @foreach ([
                    ['title' => 'اكتشف المقررات', 'text' => 'تصفّح الكتالوج العام دون تسجيل، أو سجّل لتحتفظ بطلباتك وتقدّمك.'],
                    ['title' => 'أرسل طلب التحاق', 'text' => 'اختر المقرر وأرسل طلباً. يراجع موظف مخوّل الطلب ويوافق أو يرفض مع ملاحظة عند الحاجة.'],
                    ['title' => 'ادرس من لوحتك', 'text' => 'بعد الموافقة يظهر المقرر في لوحة الطالب: دروس، وسائط، اختبارات، وتقدّم.'],
                    ['title' => 'تابع وتنبّه', 'text' => 'الإشعارات والتقويم يبقيانك على اطلاع بالمواعيد والمستجدات.'],
                    ['title' => 'اطلب المساعدة', 'text' => 'إن تعثّرت، راسل الدعم من داخل المنصة أو عبر صفحة التواصل.'],
                ] as $i => $step)
                    <li class="flex gap-4 rounded-xl border border-[var(--color-line)] bg-[var(--color-sand)]/70 p-6 transition hover:border-[var(--color-primary)]/40 hover:bg-[var(--color-surface)]">
                        <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-[var(--color-primary-light)] text-sm font-bold text-[var(--color-secondary-hover)]">{{ $i + 1 }}</span>
                        <div>
                            <h2 class="text-lg font-bold text-[var(--color-text)]">{{ $step['title'] }}</h2>
                            <p class="mt-2 text-sm leading-relaxed text-[var(--color-text-secondary)]">{{ $step['text'] }}</p>
                        </div>
                    </li>
                @endforeach
            </ol>

            <div class="mt-12 flex flex-wrap gap-3">
                <a href="{{ route('public.courses.index') }}" class="academy-btn-primary">تصفّح المقررات</a>
                <a href="{{ route('register') }}" class="academy-btn-secondary">إنشاء حساب</a>
            </div>
        </div>
    </section>
@endsection
