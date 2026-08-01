@extends('layouts.admin')

@section('title', 'المدفوعات')
@section('heading', 'المدفوعات والمعاملات')
@section('subheading', 'متابعة المعاملات المالية على المنصة')

@section('content')
    <div class="mb-6 grid gap-4 sm:grid-cols-3">
        <div class="rounded-2xl border border-[var(--color-line)] bg-white p-5">
            <p class="text-sm text-slate-500">عدد المعاملات</p>
            <p class="mt-1 text-2xl font-bold">{{ number_format($totals['count']) }}</p>
        </div>
        <div class="rounded-2xl border border-[var(--color-line)] bg-white p-5">
            <p class="text-sm text-slate-500">إجمالي المدفوع</p>
            <p class="mt-1 text-2xl font-bold">{{ number_format($totals['paid'], 2) }}</p>
        </div>
        <div class="rounded-2xl border border-[var(--color-line)] bg-white p-5">
            <p class="text-sm text-slate-500">قيد الانتظار</p>
            <p class="mt-1 text-2xl font-bold">{{ number_format($totals['pending']) }}</p>
        </div>
    </div>

    <form method="GET" class="mb-5 grid gap-3 rounded-2xl border border-[var(--color-line)] bg-white p-4 sm:grid-cols-4">
        <div class="sm:col-span-2">
            <input type="search" name="q" value="{{ request('q') }}" placeholder="مرجع أو مستخدم..." class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
        </div>
        <div>
            <select name="status" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
                <option value="">كل الحالات</option>
                <option value="pending" @selected(request('status') === 'pending')>قيد الانتظار</option>
                <option value="paid" @selected(request('status') === 'paid')>مدفوع</option>
                <option value="failed" @selected(request('status') === 'failed')>فشل</option>
                <option value="refunded" @selected(request('status') === 'refunded')>مسترد</option>
            </select>
        </div>
        <div>
            <select name="type" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
                <option value="">كل الأنواع</option>
                <option value="payment" @selected(request('type') === 'payment')>دفع</option>
                <option value="refund" @selected(request('type') === 'refund')>استرداد</option>
                <option value="subscription" @selected(request('type') === 'subscription')>اشتراك</option>
            </select>
        </div>
        <div class="sm:col-span-4 flex gap-2">
            <button class="rounded-xl bg-slate-900 px-4 py-2.5 text-sm text-white">تصفية</button>
            <a href="{{ route('admin.payments.index') }}" class="rounded-xl border px-4 py-2.5 text-sm">مسح</a>
        </div>
    </form>

    <div class="overflow-hidden rounded-2xl border border-[var(--color-line)] bg-white">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-50 text-xs font-semibold text-slate-500">
                <tr>
                    <th class="px-4 py-3 text-right">المرجع</th>
                    <th class="px-4 py-3 text-right">المستخدم</th>
                    <th class="px-4 py-3 text-right">المبلغ</th>
                    <th class="px-4 py-3 text-right">النوع</th>
                    <th class="px-4 py-3 text-right">الحالة</th>
                    <th class="px-4 py-3 text-right">التاريخ</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($payments as $payment)
                    <tr>
                        <td class="px-4 py-3 font-mono text-xs">{{ $payment->reference ?: '#'.$payment->id }}</td>
                        <td class="px-4 py-3">
                            <div>{{ $payment->user?->name ?? '—' }}</div>
                            <div class="text-xs text-slate-500">{{ $payment->user?->email }}</div>
                        </td>
                        <td class="px-4 py-3 font-medium">{{ number_format((float) $payment->amount, 2) }} {{ $payment->currency }}</td>
                        <td class="px-4 py-3">{{ $payment->type }}</td>
                        <td class="px-4 py-3">{{ $payment->status }}</td>
                        <td class="px-4 py-3 text-slate-500">{{ $payment->created_at?->format('Y-m-d H:i') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-10 text-center text-slate-500">لا توجد معاملات بعد.</td></tr>
                @endforelse
            </tbody>
        </table>
        @if ($payments->hasPages())
            <div class="border-t px-4 py-3">{{ $payments->links() }}</div>
        @endif
    </div>
@endsection
