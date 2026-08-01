@extends('layouts.admin')

@section('title', 'كوبونات الخصم')
@section('heading', 'كوبونات الخصم')
@section('subheading', 'إنشاء وإدارة أكواد الخصم')

@section('header-actions')
    <a href="{{ route('admin.coupons.create') }}" class="rounded-xl bg-[var(--color-primary)] px-4 py-2.5 text-sm font-semibold text-white">كوبون جديد</a>
@endsection

@section('content')
    <form method="GET" class="mb-5 grid gap-3 rounded-2xl border border-[var(--color-line)] bg-white p-4 sm:grid-cols-4">
        <div class="sm:col-span-2">
            <input type="search" name="q" value="{{ request('q') }}" placeholder="بحث بالرمز..." class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
        </div>
        <div>
            <select name="active" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
                <option value="">كل الحالات</option>
                <option value="1" @selected(request('active') === '1')>نشط</option>
                <option value="0" @selected(request('active') === '0')>موقوف</option>
            </select>
        </div>
        <div>
            <select name="discount_type" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
                <option value="">كل الأنواع</option>
                <option value="percent" @selected(request('discount_type') === 'percent')>نسبة</option>
                <option value="fixed" @selected(request('discount_type') === 'fixed')>ثابت</option>
            </select>
        </div>
        <div class="sm:col-span-4 flex gap-2">
            <button class="rounded-xl bg-slate-900 px-4 py-2.5 text-sm text-white">تصفية</button>
            <a href="{{ route('admin.coupons.index') }}" class="rounded-xl border px-4 py-2.5 text-sm">مسح</a>
        </div>
    </form>

    <details class="mb-5 rounded-2xl border border-[var(--color-line)] bg-white p-4">
        <summary class="cursor-pointer text-sm font-medium">توليد دفعة كوبونات</summary>
        <form method="POST" action="{{ route('admin.coupons.generate') }}" class="mt-4 grid gap-3 sm:grid-cols-4">
            @csrf
            <input type="number" name="count" min="1" max="50" value="5" placeholder="العدد" class="rounded-xl border px-3 py-2 text-sm" required>
            <select name="discount_type" class="rounded-xl border px-3 py-2 text-sm">
                <option value="percent">نسبة</option>
                <option value="fixed">ثابت</option>
            </select>
            <input type="number" step="0.01" name="discount_value" placeholder="القيمة" class="rounded-xl border px-3 py-2 text-sm" required>
            <button class="rounded-xl bg-[var(--color-primary)] px-4 py-2 text-sm text-white">توليد</button>
        </form>
    </details>

    <div class="overflow-hidden rounded-2xl border border-[var(--color-line)] bg-white">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-50 text-xs font-semibold text-slate-500">
                <tr>
                    <th class="px-4 py-3 text-right">الرمز</th>
                    <th class="px-4 py-3 text-right">الخصم</th>
                    <th class="px-4 py-3 text-right">الاستخدام</th>
                    <th class="px-4 py-3 text-right">الانتهاء</th>
                    <th class="px-4 py-3 text-right">الحالة</th>
                    <th class="px-4 py-3 text-right">إجراءات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($coupons as $coupon)
                    <tr>
                        <td class="px-4 py-3 font-mono text-xs">{{ $coupon->code }}</td>
                        <td class="px-4 py-3">
                            {{ $coupon->discount_type === 'percent' ? $coupon->discount_value.'%' : number_format((float) $coupon->discount_value, 2) }}
                        </td>
                        <td class="px-4 py-3">{{ $coupon->used_count }}{{ $coupon->usage_limit ? '/'.$coupon->usage_limit : '' }}</td>
                        <td class="px-4 py-3 text-slate-500">{{ $coupon->expires_at?->format('Y-m-d') ?? '—' }}</td>
                        <td class="px-4 py-3">
                            <span class="rounded-full px-2.5 py-1 text-xs {{ $coupon->active ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100' }}">
                                {{ $coupon->active ? 'نشط' : 'موقوف' }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex flex-wrap gap-1">
                                <a href="{{ route('admin.coupons.edit', $coupon) }}" class="rounded-lg border px-2 py-1 text-xs">تعديل</a>
                                @if ($coupon->active)
                                    <form method="POST" action="{{ route('admin.coupons.deactivate', $coupon) }}" class="inline">@csrf<button class="rounded-lg border px-2 py-1 text-xs">إيقاف</button></form>
                                @else
                                    <form method="POST" action="{{ route('admin.coupons.activate', $coupon) }}" class="inline">@csrf<button class="rounded-lg border px-2 py-1 text-xs">تفعيل</button></form>
                                @endif
                                <form method="POST" action="{{ route('admin.coupons.duplicate', $coupon) }}" class="inline">@csrf<button class="rounded-lg border px-2 py-1 text-xs">نسخ</button></form>
                                <form method="POST" action="{{ route('admin.coupons.destroy', $coupon) }}" class="inline" onsubmit="return confirm('حذف؟');">@csrf @method('DELETE')<button class="rounded-lg border border-rose-200 px-2 py-1 text-xs text-rose-700">حذف</button></form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-10 text-center text-slate-500">لا كوبونات.</td></tr>
                @endforelse
            </tbody>
        </table>
        @if ($coupons->hasPages())
            <div class="border-t px-4 py-3">{{ $coupons->links() }}</div>
        @endif
    </div>
@endsection
