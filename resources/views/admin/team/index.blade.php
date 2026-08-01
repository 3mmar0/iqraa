@extends('layouts.admin')

@section('title', 'فريق العمل')
@section('heading', 'نظرة عامة — فريق العمل')
@section('subheading', 'ملخص المهام والاجتماعات مع روابط لوحة الفريق')

@section('content')
    <div class="mb-6">
        <a href="{{ route('team.home') }}" class="inline-flex rounded-xl bg-[var(--color-primary)] px-4 py-2 text-sm font-medium text-white hover:bg-[var(--color-primary-hover)]">
            فتح لوحة الفريق
        </a>
    </div>

    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <x-admin.kpi-card label="المهام" :value="number_format($stats['tasks'])" :href="route('team.tasks.index')" hint="كل المهام" />
        <x-admin.kpi-card label="مهام مفتوحة" :value="number_format($stats['open_tasks'])" :href="route('team.tasks.index')" hint="المتابعة" />
        <x-admin.kpi-card label="الاجتماعات" :value="number_format($stats['meetings'])" :href="route('team.meetings.index')" hint="الاجتماعات" />
        <x-admin.kpi-card label="قادمة" :value="number_format($stats['upcoming_meetings'])" />
    </div>

    <section class="mt-8 rounded-2xl border border-[var(--color-line)] bg-white p-6">
        <h2 class="text-base font-semibold text-slate-900">اختصارات</h2>
        <div class="mt-4 grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
            <a href="{{ route('team.announcements.index') }}" class="rounded-xl bg-[var(--color-sand)] px-4 py-3 text-sm font-medium text-slate-800 ring-1 ring-[var(--color-line)] hover:bg-[var(--color-primary-light)]">الإعلانات</a>
            <a href="{{ route('team.goals.index') }}" class="rounded-xl bg-[var(--color-sand)] px-4 py-3 text-sm font-medium text-slate-800 ring-1 ring-[var(--color-line)] hover:bg-[var(--color-primary-light)]">الأهداف</a>
            <a href="{{ route('team.attendance.index') }}" class="rounded-xl bg-[var(--color-sand)] px-4 py-3 text-sm font-medium text-slate-800 ring-1 ring-[var(--color-line)] hover:bg-[var(--color-primary-light)]">الحضور</a>
            <a href="{{ route('team.reports.index') }}" class="rounded-xl bg-[var(--color-sand)] px-4 py-3 text-sm font-medium text-slate-800 ring-1 ring-[var(--color-line)] hover:bg-[var(--color-primary-light)]">التقارير</a>
        </div>
    </section>
@endsection
