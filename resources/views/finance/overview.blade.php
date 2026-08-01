@extends('layouts.finance')
@section('title', $title ?? 'نظرة عامة')
@section('content')
    <h1 class="mb-6 text-2xl font-bold text-[var(--color-ink)]">نظرة عامة مالية</h1>
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div class="rounded-xl border border-slate-200 bg-white p-4"><p class="text-sm text-slate-500">المعاملات</p><p class="text-2xl font-semibold">{{ $transactionsCount }}</p></div>
        <div class="rounded-xl border border-slate-200 bg-white p-4"><p class="text-sm text-slate-500">الاستردادات</p><p class="text-2xl font-semibold">{{ $refundsCount }}</p></div>
        <div class="rounded-xl border border-slate-200 bg-white p-4"><p class="text-sm text-slate-500">المصروفات</p><p class="text-2xl font-semibold">{{ $expensesCount }}</p></div>
        <div class="rounded-xl border border-slate-200 bg-white p-4"><p class="text-sm text-slate-500">اشتراكات نشطة</p><p class="text-2xl font-semibold">{{ $subscriptionsCount }}</p></div>
    </div>
@endsection