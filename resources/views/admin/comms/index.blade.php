@extends('layouts.admin')

@section('title', 'الإشعارات والبريد')
@section('heading', 'الإشعارات والبريد')
@section('subheading', 'قنوات التواصل مع المستخدمين')

@section('content')
    <div class="grid gap-4 lg:grid-cols-2">
        <section class="rounded-2xl border border-[var(--color-line)] bg-white p-6">
            <h2 class="text-base font-semibold text-slate-900">البريد الإلكتروني</h2>
            <p class="mt-2 text-sm leading-6 text-slate-600">يتم الإرسال عبر إعدادات MAIL_* في الخادم. الوضع الحالي على الإنتاج يعتمد على `.env`.</p>
            <ul class="mt-4 space-y-2 text-sm text-slate-700">
                <li class="rounded-xl bg-slate-50 px-3 py-2">إشعارات قرار الالتحاق</li>
                <li class="rounded-xl bg-slate-50 px-3 py-2">الإعلانات والتنبيهات</li>
                <li class="rounded-xl bg-slate-50 px-3 py-2">اكتمال التقارير</li>
            </ul>
        </section>
        <section class="rounded-2xl border border-[var(--color-line)] bg-white p-6">
            <h2 class="text-base font-semibold text-slate-900">داخل المنصة وتليجرام</h2>
            <p class="mt-2 text-sm leading-6 text-slate-600">الإشعارات داخل التطبيق محفوظة دائماً. قناة تليجرام متاحة كتكامل مرحلي عبر الطابور.</p>
            <a href="{{ route('admin.ops.index') }}" class="mt-4 inline-flex text-sm font-medium text-teal-700 hover:underline">مراجعة حالة الطابور</a>
        </section>
    </div>
@endsection
