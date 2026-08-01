@extends('layouts.admin')

@section('title', 'التسويق')
@section('heading', 'نظرة عامة — التسويق')
@section('subheading', 'ملخص الحملات والكوبونات والعملاء المحتملين')

@section('content')
    <div class="mb-6">
        <a href="{{ route('marketing.home') }}" class="inline-flex rounded-xl bg-teal-600 px-4 py-2 text-sm font-medium text-white hover:bg-teal-700">
            فتح لوحة التسويق
        </a>
    </div>

    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
        <x-admin.kpi-card label="الحملات" :value="number_format($stats['campaigns'])" :href="route('marketing.campaigns.index')" hint="إدارة الحملات" />
        <x-admin.kpi-card label="حملات نشطة" :value="number_format($stats['active_campaigns'])" />
        <x-admin.kpi-card label="كوبونات الخصم" :value="number_format($stats['coupons'])" :href="route('marketing.coupons.index')" hint="الكوبونات" />
        <x-admin.kpi-card label="كوبونات فعّالة" :value="number_format($stats['active_coupons'])" />
        <x-admin.kpi-card label="العملاء المحتملون" :value="number_format($stats['leads'])" :href="route('marketing.leads.index')" hint="العملاء المحتملون" />
        <x-admin.kpi-card label="الإحالات" :value="number_format($stats['referrals'])" :href="route('marketing.referrals.index')" hint="الإحالات" />
    </div>

    <section class="mt-8 rounded-2xl border border-[var(--color-line)] bg-white p-6">
        <h2 class="text-base font-semibold text-slate-900">اختصارات</h2>
        <div class="mt-4 grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
            <a href="{{ route('marketing.analytics.index') }}" class="rounded-xl bg-[var(--color-sand)] px-4 py-3 text-sm font-medium text-slate-800 ring-1 ring-[var(--color-line)] hover:bg-teal-50">التحليلات</a>
            <a href="{{ route('marketing.conversions.index') }}" class="rounded-xl bg-[var(--color-sand)] px-4 py-3 text-sm font-medium text-slate-800 ring-1 ring-[var(--color-line)] hover:bg-teal-50">التحويلات</a>
            <a href="{{ route('marketing.ambassadors.index') }}" class="rounded-xl bg-[var(--color-sand)] px-4 py-3 text-sm font-medium text-slate-800 ring-1 ring-[var(--color-line)] hover:bg-teal-50">السفراء</a>
            <a href="{{ route('admin.coupons.index') }}" class="rounded-xl bg-[var(--color-sand)] px-4 py-3 text-sm font-medium text-slate-800 ring-1 ring-[var(--color-line)] hover:bg-teal-50">كوبونات الإدارة</a>
        </div>
    </section>
@endsection
