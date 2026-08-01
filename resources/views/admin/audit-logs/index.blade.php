@extends('layouts.admin')

@section('title', 'سجل التدقيق')
@section('heading', 'سجل التدقيق')
@section('subheading', 'تتبع الإجراءات الحساسة على المنصة')

@section('content')
    <div class="overflow-hidden rounded-2xl border border-[var(--color-line)] bg-white">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-50 text-xs font-semibold text-slate-500">
                    <tr>
                        <th class="px-4 py-3 text-right">الوقت</th>
                        <th class="px-4 py-3 text-right">الفاعل</th>
                        <th class="px-4 py-3 text-right">الإجراء</th>
                        <th class="px-4 py-3 text-right">الهدف</th>
                        <th class="px-4 py-3 text-right">IP</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($logs as $log)
                        <tr class="hover:bg-slate-50/70">
                            <td class="whitespace-nowrap px-4 py-3 text-slate-600">{{ $log->created_at?->format('Y-m-d H:i') }}</td>
                            <td class="px-4 py-3">{{ $log->actor?->name ?? 'نظام' }}</td>
                            <td class="px-4 py-3 font-medium text-slate-900">{{ $log->action }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $log->target_type }} #{{ $log->target_id }}</td>
                            <td class="px-4 py-3 text-slate-500">{{ $log->ip }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-12 text-center text-slate-500">لا توجد سجلات بعد.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($logs->hasPages())
            <div class="border-t border-slate-100 px-4 py-3">{{ $logs->links() }}</div>
        @endif
    </div>
@endsection
