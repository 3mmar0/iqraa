@extends('layouts.admin')

@section('title', 'المدفوعات')
@section('heading', 'المدفوعات والمعاملات')
@section('subheading', 'متابعة المعاملات المالية على المنصة')

@section('header-actions')
    <a href="{{ route('admin.payments.export', request()->query()) }}" class="rounded-xl border bg-white px-4 py-2.5 text-sm">تصدير CSV</a>
@endsection

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

    <form method="GET" class="mb-5 grid gap-3 rounded-2xl border border-[var(--color-line)] bg-white p-4 sm:grid-cols-6">
        <div class="sm:col-span-2">
            <input type="search" name="q" value="{{ request('q') }}" placeholder="مرجع أو مستخدم..." class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
        </div>
        <div>
            <select name="status" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
                <option value="">كل الحالات</option>
                @foreach (['pending', 'paid', 'failed', 'refunded'] as $st)
                    <option value="{{ $st }}" @selected(request('status') === $st)>{{ $st }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <select name="type" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
                <option value="">كل الأنواع</option>
                @foreach (['payment', 'refund', 'subscription'] as $tp)
                    <option value="{{ $tp }}" @selected(request('type') === $tp)>{{ $tp }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <input type="date" name="from" value="{{ request('from') }}" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
        </div>
        <div>
            <input type="date" name="to" value="{{ request('to') }}" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
        </div>
        <div class="sm:col-span-6 flex flex-wrap items-center gap-2">
            <label class="flex items-center gap-2 text-sm">
                <input type="checkbox" name="pending_verification" value="1" @checked(request()->boolean('pending_verification'))>
                بانتظار التحقق فقط
            </label>
            <button class="rounded-xl bg-slate-900 px-4 py-2.5 text-sm text-white">تصفية</button>
            <a href="{{ route('admin.payments.index') }}" class="rounded-xl border px-4 py-2.5 text-sm">مسح</a>
        </div>
    </form>

    <details class="mb-5 rounded-2xl border bg-white p-4">
        <summary class="cursor-pointer text-sm font-medium">تسجيل دفعة يدوية</summary>
        <form method="POST" action="{{ route('admin.payments.store') }}" class="mt-4 grid gap-3 sm:grid-cols-3">
            @csrf
            <select name="user_id" required class="rounded-xl border px-3 py-2 text-sm sm:col-span-2">
                <option value="">الطالب...</option>
                @foreach ($students as $student)
                    <option value="{{ $student->id }}">{{ $student->name }} ({{ $student->email }})</option>
                @endforeach
            </select>
            <input type="number" step="0.01" name="amount" required placeholder="المبلغ" class="rounded-xl border px-3 py-2 text-sm">
            <select name="type" class="rounded-xl border px-3 py-2 text-sm">
                <option value="payment">دفع</option>
                <option value="subscription">اشتراك</option>
            </select>
            <input name="reference" placeholder="مرجع (اختياري)" class="rounded-xl border px-3 py-2 text-sm">
            <textarea name="note" rows="1" placeholder="ملاحظة" class="rounded-xl border px-3 py-2 text-sm sm:col-span-2"></textarea>
            <button class="rounded-xl bg-teal-700 px-4 py-2 text-sm text-white">تسجيل</button>
        </form>
    </details>

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
                    <th class="px-4 py-3 text-right">إجراءات</th>
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
                        <td class="px-4 py-3">
                            <div class="flex flex-wrap gap-1">
                                @if ($payment->status === 'pending')
                                    <form method="POST" action="{{ route('admin.payments.verify', $payment) }}" class="inline">@csrf<button class="rounded-lg border px-2 py-1 text-xs">تحقق</button></form>
                                    <form method="POST" action="{{ route('admin.payments.approve', $payment) }}" class="inline">@csrf<button class="rounded-lg border px-2 py-1 text-xs">موافقة</button></form>
                                    <form method="POST" action="{{ route('admin.payments.reject', $payment) }}" class="inline">@csrf<button class="rounded-lg border px-2 py-1 text-xs">رفض</button></form>
                                @endif
                                @if ($payment->status === 'paid')
                                    <form method="POST" action="{{ route('admin.payments.refund', $payment) }}" class="inline" onsubmit="return confirm('استرداد كامل؟');">
                                        @csrf
                                        <input type="hidden" name="amount" value="{{ $payment->amount }}">
                                        <button class="rounded-lg border px-2 py-1 text-xs">استرداد</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="px-4 py-10 text-center text-slate-500">لا توجد معاملات بعد.</td></tr>
                @endforelse
            </tbody>
        </table>
        @if ($payments->hasPages())
            <div class="border-t px-4 py-3">{{ $payments->links() }}</div>
        @endif
    </div>
@endsection
