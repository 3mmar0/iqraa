@extends('layouts.admin')

@section('title', 'الطلبات')
@section('heading', 'الطلبات')
@section('subheading', 'إدارة طلبات الشراء والموافقة والاسترداد')

@section('header-actions')
    <a href="{{ route('admin.orders.export', request()->query()) }}" class="rounded-xl border bg-white px-4 py-2.5 text-sm">تصدير CSV</a>
@endsection

@section('content')
    <form method="GET" class="mb-5 grid gap-3 rounded-2xl border border-[var(--color-line)] bg-white p-4 sm:grid-cols-5">
        <div class="sm:col-span-2">
            <label class="mb-1 block text-xs font-medium text-slate-500" for="q">بحث</label>
            <input id="q" type="search" name="q" value="{{ request('q') }}" placeholder="رقم الطلب أو الطالب..." class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
        </div>
        <div>
            <label class="mb-1 block text-xs font-medium text-slate-500" for="status">الحالة</label>
            <select id="status" name="status" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
                <option value="">الكل</option>
                @foreach (['pending' => 'قيد الانتظار', 'approved' => 'موافق', 'rejected' => 'مرفوض', 'refunded' => 'مسترد'] as $val => $label)
                    <option value="{{ $val }}" @selected(request('status') === $val)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="mb-1 block text-xs font-medium text-slate-500" for="from">من</label>
            <input id="from" type="date" name="from" value="{{ request('from') }}" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
        </div>
        <div>
            <label class="mb-1 block text-xs font-medium text-slate-500" for="to">إلى</label>
            <input id="to" type="date" name="to" value="{{ request('to') }}" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
        </div>
        <div class="sm:col-span-5 flex gap-2">
            <button class="rounded-xl bg-slate-900 px-4 py-2.5 text-sm text-white">تصفية</button>
            <a href="{{ route('admin.orders.index') }}" class="rounded-xl border border-slate-200 px-4 py-2.5 text-sm">مسح</a>
        </div>
    </form>

    <div class="overflow-hidden rounded-2xl border border-[var(--color-line)] bg-white">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-50 text-xs font-semibold text-slate-500">
                <tr>
                    <th class="px-4 py-3 text-right">رقم الطلب</th>
                    <th class="px-4 py-3 text-right">الطالب</th>
                    <th class="px-4 py-3 text-right">العناصر</th>
                    <th class="px-4 py-3 text-right">الإجمالي</th>
                    <th class="px-4 py-3 text-right">الحالة</th>
                    <th class="px-4 py-3 text-right">التاريخ</th>
                    <th class="px-4 py-3 text-right">إجراءات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($orders as $order)
                    <tr>
                        <td class="px-4 py-3 font-mono text-xs">{{ $order->number }}</td>
                        <td class="px-4 py-3">
                            <div>{{ $order->user?->name ?? '—' }}</div>
                            <div class="text-xs text-slate-500">{{ $order->user?->email }}</div>
                        </td>
                        <td class="px-4 py-3">{{ $order->items_count }}</td>
                        <td class="px-4 py-3 font-medium">{{ number_format((float) $order->total, 2) }} {{ $order->currency }}</td>
                        <td class="px-4 py-3">
                            @php
                                $labels = ['pending' => 'قيد الانتظار', 'approved' => 'موافق', 'rejected' => 'مرفوض', 'refunded' => 'مسترد'];
                            @endphp
                            <span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs">{{ $labels[$order->status] ?? $order->status }}</span>
                        </td>
                        <td class="px-4 py-3 text-slate-500">{{ $order->created_at?->format('Y-m-d H:i') }}</td>
                        <td class="px-4 py-3">
                            <a href="{{ route('admin.orders.show', $order) }}" class="rounded-lg border px-3 py-1.5 text-xs">عرض</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="px-4 py-10 text-center text-slate-500">لا توجد طلبات.</td></tr>
                @endforelse
            </tbody>
        </table>
        @if ($orders->hasPages())
            <div class="border-t px-4 py-3">{{ $orders->links() }}</div>
        @endif
    </div>
@endsection
