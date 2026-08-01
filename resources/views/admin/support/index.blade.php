@extends('layouts.admin')

@section('title', 'الدعم الفني')
@section('heading', 'نظرة عامة — الدعم')
@section('subheading', 'ملخص التذاكر المفتوحة وروابط لوحة الدعم')

@section('content')
    <div class="mb-6">
        <a href="{{ route('support.home') }}" class="inline-flex rounded-xl bg-teal-600 px-4 py-2 text-sm font-medium text-white hover:bg-teal-700">
            فتح لوحة الدعم
        </a>
    </div>

    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <x-admin.kpi-card label="إجمالي التذاكر" :value="number_format($stats['total'])" :href="route('support.tickets.index')" hint="كل التذاكر" />
        <x-admin.kpi-card label="تذاكر مفتوحة" :value="number_format($stats['open'])" :href="route('support.tickets.index')" hint="المعالجة" />
        <x-admin.kpi-card label="مغلقة" :value="number_format($stats['closed'])" />
        <x-admin.kpi-card label="غير مُسندة" :value="number_format($stats['unassigned'])" />
    </div>

    <section class="mt-8 rounded-2xl border border-[var(--color-line)] bg-white p-5">
        <div class="mb-4 flex items-center justify-between">
            <h2 class="text-base font-semibold text-slate-900">أحدث التذاكر</h2>
            <a href="{{ route('support.tickets.index') }}" class="text-sm font-medium text-teal-700 hover:underline">عرض الكل</a>
        </div>
        <div class="divide-y divide-slate-100">
            @forelse ($recentTickets as $ticket)
                <div class="flex items-center justify-between gap-3 py-3">
                    <div class="min-w-0">
                        <p class="truncate font-medium text-slate-900">{{ $ticket->subject }}</p>
                        <p class="truncate text-sm text-slate-500">{{ $ticket->student?->name ?? '—' }} · {{ $ticket->status }}</p>
                    </div>
                    <a href="{{ route('support.tickets.show', $ticket) }}" class="shrink-0 rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-medium text-slate-700 hover:bg-slate-50">عرض</a>
                </div>
            @empty
                <p class="py-6 text-sm text-slate-500">لا توجد تذاكر بعد.</p>
            @endforelse
        </div>
    </section>
@endsection
