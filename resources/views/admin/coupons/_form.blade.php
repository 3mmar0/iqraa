@php $coupon = $coupon ?? null; @endphp
<div class="grid gap-4 sm:grid-cols-2">
    <div>
        <label class="mb-1 block text-xs font-medium text-slate-500" for="code">رمز الكوبون</label>
        <input id="code" name="code" value="{{ old('code', $coupon?->code) }}" required minlength="6" maxlength="20" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm font-mono uppercase">
        @error('code')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="mb-1 block text-xs font-medium text-slate-500" for="discount_type">نوع الخصم</label>
        <select id="discount_type" name="discount_type" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
            <option value="percent" @selected(old('discount_type', $coupon?->discount_type) === 'percent')>نسبة مئوية</option>
            <option value="fixed" @selected(old('discount_type', $coupon?->discount_type) === 'fixed')>مبلغ ثابت</option>
        </select>
    </div>
    <div>
        <label class="mb-1 block text-xs font-medium text-slate-500" for="discount_value">قيمة الخصم</label>
        <input id="discount_value" type="number" step="0.01" name="discount_value" value="{{ old('discount_value', $coupon?->discount_value) }}" required class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
    </div>
    <div>
        <label class="mb-1 block text-xs font-medium text-slate-500" for="usage_limit">حد الاستخدام</label>
        <input id="usage_limit" type="number" name="usage_limit" value="{{ old('usage_limit', $coupon?->usage_limit) }}" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm" placeholder="غير محدود">
    </div>
    <div>
        <label class="mb-1 block text-xs font-medium text-slate-500" for="expires_at">تاريخ الانتهاء</label>
        <input id="expires_at" type="datetime-local" name="expires_at" value="{{ old('expires_at', $coupon?->expires_at?->format('Y-m-d\TH:i')) }}" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
    </div>
    <div class="flex items-end">
        <label class="flex items-center gap-2 text-sm">
            <input type="checkbox" name="active" value="1" @checked(old('active', $coupon?->active ?? true))>
            نشط
        </label>
    </div>
</div>
