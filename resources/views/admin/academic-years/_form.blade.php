@php $year = $year ?? null; @endphp
<div class="grid gap-4 sm:grid-cols-2">
    <div class="sm:col-span-2">
        <label class="mb-1 block text-xs font-medium text-slate-500" for="name">اسم السنة</label>
        <input id="name" name="name" value="{{ old('name', $year?->name) }}" required class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
    </div>
    <div>
        <label class="mb-1 block text-xs font-medium text-slate-500" for="starts_on">تاريخ البداية</label>
        <input id="starts_on" type="date" name="starts_on" value="{{ old('starts_on', $year?->starts_on?->format('Y-m-d')) }}" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
    </div>
    <div>
        <label class="mb-1 block text-xs font-medium text-slate-500" for="ends_on">تاريخ النهاية</label>
        <input id="ends_on" type="date" name="ends_on" value="{{ old('ends_on', $year?->ends_on?->format('Y-m-d')) }}" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
    </div>
    <div class="flex items-end">
        <label class="flex items-center gap-2 text-sm">
            <input type="checkbox" name="is_current" value="1" @checked(old('is_current', $year?->is_current))>
            السنة الحالية
        </label>
    </div>
</div>
