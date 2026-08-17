@extends('layouts.app')

@section('title', 'سياسة الخصوصية')

@section('content')
    <x-public-page-hero title="سياسة الخصوصية" />

    <article class="academy-section bg-[var(--color-surface)]">
        <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
            <p class="text-sm text-[var(--color-muted)]">آخر تحديث: {{ now()->translatedFormat('F Y') }}</p>
            <div class="mt-8 space-y-6 leading-relaxed text-[var(--color-text-secondary)]">
                <p>تجمع منصة {{ config('app.name') }} البيانات اللازمة لتقديم الخدمة التعليمية: الاسم، البريد، وبيانات الحساب والتقدم داخل المقررات.</p>
                <h2 class="academy-display text-xl font-bold text-[var(--color-text)]">ما نجمعه</h2>
                <p>معلومات التسجيل، طلبات الالتحاق، نشاط التعلم، ورسائل التواصل التي ترسلها عبر النماذج أو الدعم.</p>
                <h2 class="academy-display text-xl font-bold text-[var(--color-text)]">كيف نستخدمها</h2>
                <p>لتشغيل الحسابات، تمكين الوصول للمقررات، تحسين التجربة، والرد على الاستفسارات. لا نبيع بياناتك الشخصية.</p>
                <h2 class="academy-display text-xl font-bold text-[var(--color-text)]">الحماية والوصول</h2>
                <p>نقيّد الوصول للموظفين المخوّلين وفق الأدوار والصلاحيات. يمكنك طلب تصحيح بياناتك عبر صفحة التواصل أو من داخل حسابك.</p>
                <p>للاستفسارات المتعلقة بالخصوصية: <a href="{{ route('public.contact') }}" class="font-bold text-[var(--color-primary)] hover:underline">تواصل معنا</a>.</p>
            </div>
        </div>
    </article>
@endsection
