@extends('layouts.app')

@section('title', 'سياسة الخصوصية')

@section('content')
    <article class="mx-auto max-w-3xl px-4 py-16 sm:px-6">
        <h1 class="site-brand text-4xl font-bold text-[var(--color-ink)]">سياسة الخصوصية</h1>
        <p class="mt-2 text-sm text-slate-500">آخر تحديث: {{ now()->translatedFormat('F Y') }}</p>
        <div class="prose-slate mt-10 space-y-6 text-slate-700 leading-relaxed">
            <p>تجمع منصة {{ config('app.name') }} البيانات اللازمة لتقديم الخدمة التعليمية: الاسم، البريد، وبيانات الحساب والتقدم داخل المقررات.</p>
            <h2 class="text-xl font-semibold text-slate-900">ما نجمعه</h2>
            <p>معلومات التسجيل، طلبات الالتحاق، نشاط التعلم، ورسائل التواصل التي ترسلها عبر النماذج أو الدعم.</p>
            <h2 class="text-xl font-semibold text-slate-900">كيف نستخدمها</h2>
            <p>لتشغيل الحسابات، تمكين الوصول للمقررات، تحسين التجربة، والرد على الاستفسارات. لا نبيع بياناتك الشخصية.</p>
            <h2 class="text-xl font-semibold text-slate-900">الحماية والوصول</h2>
            <p>نقيّد الوصول للموظفين المخوّلين وفق الأدوار والصلاحيات. يمكنك طلب تصحيح بياناتك عبر صفحة التواصل أو من داخل حسابك.</p>
            <p>للاستفسارات المتعلقة بالخصوصية: <a href="{{ route('public.contact') }}" class="text-teal-800 hover:underline">تواصل معنا</a>.</p>
        </div>
    </article>
@endsection
