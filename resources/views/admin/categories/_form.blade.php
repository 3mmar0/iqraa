@php $cat = $category; @endphp
<div>
    <label class="mb-1 block text-sm font-medium" for="name">الاسم</label>
    <input id="name" name="name" value="{{ old('name', $cat?->name) }}" required class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
    @error('name')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
</div>
<div>
    <label class="mb-1 block text-sm font-medium" for="slug">الرابط (slug)</label>
    <input id="slug" name="slug" value="{{ old('slug', $cat?->slug) }}" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm" dir="ltr">
</div>
<div>
    <label class="mb-1 block text-sm font-medium" for="description">الوصف</label>
    <textarea id="description" name="description" rows="3" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">{{ old('description', $cat?->description) }}</textarea>
</div>
<div class="grid gap-4 sm:grid-cols-2">
    <div>
        <label class="mb-1 block text-sm font-medium" for="position">الترتيب</label>
        <input id="position" type="number" min="0" name="position" value="{{ old('position', $cat?->position ?? 0) }}" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
    </div>
    <div>
        <label class="mb-1 block text-sm font-medium" for="status">الحالة</label>
        <select id="status" name="status" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
            <option value="active" @selected(old('status', $cat?->status ?? 'active') === 'active')>نشط</option>
            <option value="archived" @selected(old('status', $cat?->status) === 'archived')>مؤرشف</option>
        </select>
    </div>
</div>
