@php $semester = $semester ?? null; @endphp
<div class="grid gap-4 sm:grid-cols-2">
    <div class="sm:col-span-2">
        <label class="mb-1 block text-xs font-medium text-slate-500" for="academic_year_id">السنة الدراسية</label>
        <select id="academic_year_id" name="academic_year_id" required class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
            @foreach ($years as $year)
                <option value="{{ $year->id }}" @selected(old('academic_year_id', $semester?->academic_year_id ?? $selectedYearId ?? null) == $year->id)>{{ $year->name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="mb-1 block text-xs font-medium text-slate-500" for="name">اسم الفصل</label>
        <input id="name" name="name" value="{{ old('name', $semester?->name) }}" required class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
    </div>
    <div>
        <label class="mb-1 block text-xs font-medium text-slate-500" for="term_number">رقم الترم</label>
        <input id="term_number" type="number" name="term_number" min="1" max="12" value="{{ old('term_number', $semester?->term_number) }}" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
    </div>
    <div>
        <label class="mb-1 block text-xs font-medium text-slate-500" for="starts_on">البداية</label>
        <input id="starts_on" type="date" name="starts_on" value="{{ old('starts_on', $semester?->starts_on?->format('Y-m-d')) }}" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
    </div>
    <div>
        <label class="mb-1 block text-xs font-medium text-slate-500" for="ends_on">النهاية</label>
        <input id="ends_on" type="date" name="ends_on" value="{{ old('ends_on', $semester?->ends_on?->format('Y-m-d')) }}" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
    </div>
    <div class="flex items-end">
        <label class="flex items-center gap-2 text-sm">
            <input type="checkbox" name="is_current" value="1" @checked(old('is_current', $semester?->is_current))>
            الفصل الحالي
        </label>
    </div>
</div>
