@extends('layouts.admin')

@section('title', 'سجلات النظام')
@section('heading', 'سجلات النظام')
@section('subheading', 'مراجعة النشاط والمصادقة والمدفوعات والأخطاء والطابور والبريد والتدقيق')

@section('content')
    <x-admin.tab-nav :tabs="$channels" class="mb-6" />

    <form method="GET" action="{{ route('admin.system-logs.index') }}" class="mb-4">
        <input type="hidden" name="channel" value="{{ $channel }}">
        <x-admin.filter-bar>
            <div class="min-w-[16rem] flex-1">
                <label for="q" class="mb-1 block text-xs font-medium text-slate-500">بحث</label>
                <input type="search" name="q" id="q" value="{{ $search }}" placeholder="حدث، رسالة، IP..."
                    class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm">
            </div>
            <button type="submit" class="rounded-xl bg-teal-600 px-4 py-2 text-sm font-medium text-white hover:bg-teal-700">تطبيق</button>
        </x-admin.filter-bar>
    </form>

    <x-admin.data-table>
        <thead class="bg-slate-50 text-xs font-semibold text-slate-500">
            <tr>
                @if ($logType === 'audit')
                    <th class="px-4 py-3 text-right">الوقت</th>
                    <th class="px-4 py-3 text-right">الفاعل</th>
                    <th class="px-4 py-3 text-right">الإجراء</th>
                    <th class="px-4 py-3 text-right">الهدف</th>
                    <th class="px-4 py-3 text-right">IP</th>
                @elseif ($logType === 'queue')
                    <th class="px-4 py-3 text-right">الوقت</th>
                    <th class="px-4 py-3 text-right">الاتصال</th>
                    <th class="px-4 py-3 text-right">الطابور</th>
                    <th class="px-4 py-3 text-right">UUID</th>
                    <th class="px-4 py-3 text-right">الاستثناء</th>
                @else
                    <th class="px-4 py-3 text-right">الوقت</th>
                    <th class="px-4 py-3 text-right">المستخدم</th>
                    <th class="px-4 py-3 text-right">الحدث</th>
                    <th class="px-4 py-3 text-right">الرسالة</th>
                    <th class="px-4 py-3 text-right">IP</th>
                @endif
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @if ($logType === 'audit')
                @forelse ($logs as $log)
                    <tr class="hover:bg-slate-50/70">
                        <td class="whitespace-nowrap px-4 py-3 text-slate-600">{{ $log->created_at?->format('Y-m-d H:i') }}</td>
                        <td class="px-4 py-3">{{ $log->actor?->name ?? 'نظام' }}</td>
                        <td class="px-4 py-3 font-medium text-slate-900">{{ $log->action }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $log->target_type }} #{{ $log->target_id }}</td>
                        <td class="px-4 py-3 text-slate-500">{{ $log->ip }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-12 text-center text-slate-500">لا توجد سجلات.</td></tr>
                @endforelse
            @elseif ($logType === 'queue')
                @forelse ($logs as $log)
                    <tr class="hover:bg-slate-50/70">
                        <td class="whitespace-nowrap px-4 py-3 text-slate-600">{{ $log->failed_at }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $log->connection }}</td>
                        <td class="px-4 py-3">{{ $log->queue }}</td>
                        <td class="px-4 py-3 font-mono text-xs text-slate-500">{{ Str::limit($log->uuid, 12) }}</td>
                        <td class="px-4 py-3 text-xs text-red-700">{{ Str::limit($log->exception, 80) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-12 text-center text-slate-500">لا توجد وظائف فاشلة.</td></tr>
                @endforelse
            @else
                @forelse ($logs as $log)
                    <tr class="hover:bg-slate-50/70">
                        <td class="whitespace-nowrap px-4 py-3 text-slate-600">{{ $log->created_at?->format('Y-m-d H:i') }}</td>
                        <td class="px-4 py-3">{{ $log->user?->name ?? '—' }}</td>
                        <td class="px-4 py-3 font-medium text-slate-900">{{ $log->event }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ Str::limit($log->message, 80) }}</td>
                        <td class="px-4 py-3 text-slate-500">{{ $log->ip }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-12 text-center text-slate-500">لا توجد سجلات.</td></tr>
                @endforelse
            @endif
        </tbody>
    </x-admin.data-table>

    @if ($logs->hasPages())
        <div class="mt-4">{{ $logs->links() }}</div>
    @endif
@endsection
