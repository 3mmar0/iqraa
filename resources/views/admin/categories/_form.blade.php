@php $cat = $category; @endphp
<div>
    <label class="admin-label" for="name">الاسم</label>
    <input id="name" name="name" value="{{ old('name', $cat?->name) }}" required class="admin-input">
    @error('name')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
</div>
<div>
    <label class="admin-label" for="slug">الرابط (slug)</label>
    <input id="slug" name="slug" value="{{ old('slug', $cat?->slug) }}" class="admin-input" dir="ltr">
</div>
<div>
    <label class="admin-label" for="description">الوصف</label>
    <textarea id="description" name="description" rows="3" class="admin-input">{{ old('description', $cat?->description) }}</textarea>
</div>
<div class="grid gap-4 sm:grid-cols-2">
    <div>
        <label class="admin-label" for="position">الترتيب</label>
        <input id="position" type="number" min="0" name="position" value="{{ old('position', $cat?->position ?? 0) }}" class="admin-input">
    </div>
    <div>
        <label class="admin-label" for="status">الحالة</label>
        <select id="status" name="status" class="admin-input">
            <option value="active" @selected(old('status', $cat?->status ?? 'active') === 'active')>نشط</option>
            <option value="archived" @selected(old('status', $cat?->status) === 'archived')>مؤرشف</option>
        </select>
    </div>
</div>
