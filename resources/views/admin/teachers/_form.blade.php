@php $teacher = $teacher ?? null; @endphp
<div class="grid gap-4 sm:grid-cols-2">
    <div>
        <label class="mb-1 block text-xs font-medium text-slate-500" for="name">الاسم</label>
        <input id="name" name="name" value="{{ old('name', $teacher?->name) }}" required class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
    </div>
    <div>
        <label class="mb-1 block text-xs font-medium text-slate-500" for="email">البريد</label>
        <input id="email" type="email" name="email" value="{{ old('email', $teacher?->email) }}" required class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
    </div>
    <div>
        <label class="mb-1 block text-xs font-medium text-slate-500" for="phone">الهاتف</label>
        <input id="phone" name="phone" value="{{ old('phone', $teacher?->phone) }}" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
    </div>
    <div>
        <label class="mb-1 block text-xs font-medium text-slate-500" for="status">الحالة</label>
        <select id="status" name="status" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
            @foreach (['invited' => 'مدعو', 'active' => 'نشط', 'disabled' => 'معطل'] as $val => $label)
                <option value="{{ $val }}" @selected(old('status', $teacher?->status ?? 'active') === $val)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div class="sm:col-span-2">
        <label class="mb-1 block text-xs font-medium text-slate-500" for="password">كلمة المرور {{ $teacher ? '(اتركها فارغة للإبقاء)' : '' }}</label>
        <input id="password" type="password" name="password" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
    </div>
</div>
