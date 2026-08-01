@extends('layouts.admin')

@section('title', 'الأمان والنسخ')
@section('heading', 'الأمان والنسخ الاحتياطي')
@section('subheading', 'حماية المنصة واستمرارية الخدمة')

@section('content')
    <div class="grid gap-4 lg:grid-cols-2">
        <section class="rounded-2xl border border-[var(--color-line)] bg-white p-6">
            <h2 class="text-base font-semibold text-slate-900">الأمان</h2>
            <ul class="mt-4 space-y-3 text-sm text-slate-700">
                <li class="rounded-xl bg-emerald-50 px-4 py-3 text-emerald-900">HTTPS مفعّل عبر Let's Encrypt</li>
                <li class="rounded-xl bg-emerald-50 px-4 py-3 text-emerald-900">CSRF وحماية الجلسات في Laravel</li>
                <li class="rounded-xl bg-emerald-50 px-4 py-3 text-emerald-900">تقييد معدل طلبات الـ API</li>
                <li class="rounded-xl bg-slate-50 px-4 py-3">رؤوس أمان HTTP مفعّلة عبر الوسيط</li>
            </ul>
        </section>
        <section class="rounded-2xl border border-[var(--color-line)] bg-white p-6">
            <h2 class="text-base font-semibold text-slate-900">النسخ الاحتياطي</h2>
            <p class="mt-2 text-sm leading-6 text-slate-600">النسخ تتم عبر سكربتات الخادم المشتركة. راجع مجلد النسخ على السيرفر أو لوحة Deploy عند الحاجة لاستعادة.</p>
            <a href="{{ route('admin.audit-logs.index') }}" class="mt-4 inline-flex text-sm font-medium text-teal-700 hover:underline">مراجعة سجل التدقيق</a>
        </section>
    </div>
@endsection
