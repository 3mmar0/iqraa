@extends('layouts.admin')

@section('title', 'المالية')
@section('heading', 'نظرة عامة — المالية')
@section('subheading', 'ملخص سريع مع روابط إلى لوحة المالية')

@section('content')
    <div class="mb-6">
        <a href="{{ route('finance.home') }}" class="inline-flex rounded-xl bg-teal-600 px-4 py-2 text-sm font-medium text-white hover:bg-teal-700">
            فتح لوحة المالية
        </a>
    </div>

    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
        <x-admin.kpi-card label="إجمالي الطلبات" :value="number_format($stats['orders'])" :href="route('finance.home')" hint="عرض التفاصيل" />
        <x-admin.kpi-card label="طلبات معلّقة" :value="number_format($stats['pending_orders'])" />
        <x-admin.kpi-card label="المعاملات" :value="number_format($stats['transactions'])" :href="route('finance.transactions.index')" hint="المعاملات" />
        <x-admin.kpi-card label="إجمالي المدفوع" :value="number_format($stats['paid_total'], 2)" />
        <x-admin.kpi-card label="الاشتراكات" :value="number_format($stats['subscriptions'])" :href="route('finance.subscriptions.index')" hint="الاشتراكات" />
        <x-admin.kpi-card label="المبالغ المستردة" :value="number_format($stats['refunds'])" :href="route('finance.refunds.index')" hint="الاسترداد" />
    </div>

    <section class="mt-8 rounded-2xl border border-[var(--color-line)] bg-white p-6">
        <h2 class="text-base font-semibold text-slate-900">اختصارات</h2>
        <div class="mt-4 grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
            <a href="{{ route('finance.revenue.index') }}" class="rounded-xl bg-[var(--color-sand)] px-4 py-3 text-sm font-medium text-slate-800 ring-1 ring-[var(--color-line)] hover:bg-teal-50">الإيرادات</a>
            <a href="{{ route('finance.expenses.index') }}" class="rounded-xl bg-[var(--color-sand)] px-4 py-3 text-sm font-medium text-slate-800 ring-1 ring-[var(--color-line)] hover:bg-teal-50">المصروفات</a>
            <a href="{{ route('finance.profit.index') }}" class="rounded-xl bg-[var(--color-sand)] px-4 py-3 text-sm font-medium text-slate-800 ring-1 ring-[var(--color-line)] hover:bg-teal-50">الأرباح</a>
            <a href="{{ route('finance.forecast.index') }}" class="rounded-xl bg-[var(--color-sand)] px-4 py-3 text-sm font-medium text-slate-800 ring-1 ring-[var(--color-line)] hover:bg-teal-50">التوقعات</a>
        </div>
    </section>
@endsection
