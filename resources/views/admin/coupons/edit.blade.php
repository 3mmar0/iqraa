@extends('layouts.admin')

@section('title', 'تعديل كوبون')
@section('heading', 'تعديل كوبون')
@section('subheading', $coupon->code)

@section('content')
    <form method="POST" action="{{ route('admin.coupons.update', $coupon) }}" class="mb-6 max-w-2xl rounded-2xl border border-[var(--color-line)] bg-white p-6">
        @csrf
        @method('PUT')
        @include('admin.coupons._form', ['coupon' => $coupon])
        <div class="mt-6 flex gap-2">
            <button class="rounded-xl bg-teal-700 px-4 py-2.5 text-sm text-white">تحديث</button>
            <a href="{{ route('admin.coupons.index') }}" class="rounded-xl border px-4 py-2.5 text-sm">رجوع</a>
        </div>
    </form>

    <div class="grid max-w-2xl gap-4 sm:grid-cols-2">
        <form method="POST" action="{{ route('admin.coupons.limitUsage', $coupon) }}" class="rounded-2xl border bg-white p-4">
            @csrf
            <label class="mb-2 block text-sm font-medium">تحديث حد الاستخدام</label>
            <input type="number" name="usage_limit" min="1" value="{{ $coupon->usage_limit }}" class="mb-2 w-full rounded-xl border px-3 py-2 text-sm" required>
            <button class="rounded-xl border px-3 py-2 text-sm">تحديث الحد</button>
        </form>
        <form method="POST" action="{{ route('admin.coupons.assignCourse', $coupon) }}" class="rounded-2xl border bg-white p-4">
            @csrf
            <label class="mb-2 block text-sm font-medium">ربط بمقرر (سجل تدقيق فقط)</label>
            <input type="number" name="course_id" placeholder="معرف المقرر" class="mb-2 w-full rounded-xl border px-3 py-2 text-sm" required>
            <button class="rounded-xl border px-3 py-2 text-sm">ربط</button>
        </form>
    </div>
@endsection
