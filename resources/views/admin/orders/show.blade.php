@extends('layouts.admin')

@section('title', 'طلب '.$order->number)
@section('heading', 'طلب '.$order->number)
@section('subheading', 'تفاصيل الطلب والإجراءات')

@section('header-actions')
    <a href="{{ route('admin.orders.invoice', $order) }}" target="_blank" class="rounded-xl border bg-white px-4 py-2.5 text-sm">فاتورة</a>
    <a href="{{ route('admin.orders.index') }}" class="rounded-xl border bg-white px-4 py-2.5 text-sm">رجوع</a>
@endsection

@section('content')
    <div class="mb-6 grid gap-4 lg:grid-cols-3">
        <div class="rounded-2xl border border-[var(--color-line)] bg-white p-5 lg:col-span-2">
            <dl class="grid gap-3 sm:grid-cols-2 text-sm">
                <div><dt class="text-slate-500">الطالب</dt><dd class="font-medium">{{ $order->user?->name }} ({{ $order->user?->email }})</dd></div>
                <div><dt class="text-slate-500">الحالة</dt><dd class="font-medium">{{ $order->status }}</dd></div>
                <div><dt class="text-slate-500">الإجمالي</dt><dd class="font-medium">{{ number_format((float) $order->total, 2) }} {{ $order->currency }}</dd></div>
                <div><dt class="text-slate-500">الكوبون</dt><dd class="font-medium">{{ $order->coupon?->code ?? '—' }}</dd></div>
                <div><dt class="text-slate-500">تاريخ الإنشاء</dt><dd class="font-medium">{{ $order->created_at?->format('Y-m-d H:i') }}</dd></div>
                <div><dt class="text-slate-500">الموافقة</dt><dd class="font-medium">{{ $order->approver?->name ?? '—' }} {{ $order->approved_at ? '· '.$order->approved_at->format('Y-m-d H:i') : '' }}</dd></div>
            </dl>
            @if ($order->note)
                <p class="mt-4 rounded-xl bg-slate-50 p-3 text-sm text-slate-700">{{ $order->note }}</p>
            @endif
        </div>

        <div class="space-y-4">
            @if ($order->status === 'pending')
                <form method="POST" action="{{ route('admin.orders.approve', $order) }}" class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4">
                    @csrf
                    <label class="mb-2 block text-sm font-medium">موافقة</label>
                    <textarea name="note" rows="2" placeholder="ملاحظة اختيارية..." class="mb-2 w-full rounded-xl border px-3 py-2 text-sm"></textarea>
                    <button class="rounded-xl bg-emerald-700 px-4 py-2 text-sm text-white">موافقة</button>
                </form>
                <form method="POST" action="{{ route('admin.orders.reject', $order) }}" class="rounded-2xl border border-rose-200 bg-rose-50 p-4">
                    @csrf
                    <label class="mb-2 block text-sm font-medium">رفض (سبب مطلوب)</label>
                    <textarea name="note" rows="2" required minlength="10" class="mb-2 w-full rounded-xl border px-3 py-2 text-sm"></textarea>
                    <button class="rounded-xl bg-rose-700 px-4 py-2 text-sm text-white">رفض</button>
                </form>
            @endif
            @if (in_array($order->status, ['approved', 'pending'], true))
                <form method="POST" action="{{ route('admin.orders.refund', $order) }}" class="rounded-2xl border border-amber-200 bg-amber-50 p-4">
                    @csrf
                    <label class="mb-2 block text-sm font-medium">استرداد</label>
                    <input type="number" name="amount" step="0.01" max="{{ $order->total }}" placeholder="المبلغ (افتراضي: كامل)" class="mb-2 w-full rounded-xl border px-3 py-2 text-sm">
                    <textarea name="note" rows="2" placeholder="ملاحظة..." class="mb-2 w-full rounded-xl border px-3 py-2 text-sm"></textarea>
                    <button class="rounded-xl bg-amber-700 px-4 py-2 text-sm text-white">استرداد</button>
                </form>
            @endif
        </div>
    </div>

    <section class="rounded-2xl border border-[var(--color-line)] bg-white p-5">
        <h2 class="mb-3 font-semibold">عناصر الطلب</h2>
        <table class="min-w-full text-sm">
            <thead class="text-xs text-slate-500">
                <tr>
                    <th class="py-2 text-right">العنوان</th>
                    <th class="py-2 text-right">المقرر</th>
                    <th class="py-2 text-right">السعر</th>
                    <th class="py-2 text-right">الكمية</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($order->items as $item)
                    <tr>
                        <td class="py-2">{{ $item->title }}</td>
                        <td class="py-2 text-slate-600">{{ $item->course?->title ?? '—' }}</td>
                        <td class="py-2">{{ number_format((float) $item->unit_price, 2) }}</td>
                        <td class="py-2">{{ $item->quantity }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="py-6 text-center text-slate-500">لا عناصر.</td></tr>
                @endforelse
            </tbody>
        </table>
    </section>
@endsection
