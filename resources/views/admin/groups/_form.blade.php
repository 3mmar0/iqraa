@php $group = $group ?? null; @endphp
<div class="grid gap-4 sm:grid-cols-2">
    <div class="sm:col-span-2">
        <label class="mb-1 block text-xs font-medium text-slate-500" for="name">اسم المجموعة</label>
        <input id="name" name="name" value="{{ old('name', $group?->name) }}" required class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
    </div>
    <div>
        <label class="mb-1 block text-xs font-medium text-slate-500" for="academic_year_id">السنة الدراسية</label>
        <select id="academic_year_id" name="academic_year_id" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
            <option value="">—</option>
            @foreach ($years as $year)
                <option value="{{ $year->id }}" @selected(old('academic_year_id', $group?->academic_year_id) == $year->id)>{{ $year->name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="mb-1 block text-xs font-medium text-slate-500" for="semester_id">الفصل</label>
        <select id="semester_id" name="semester_id" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
            <option value="">—</option>
            @foreach ($semesters as $semester)
                <option value="{{ $semester->id }}" @selected(old('semester_id', $group?->semester_id) == $semester->id)>{{ $semester->academicYear?->name }} · {{ $semester->name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="mb-1 block text-xs font-medium text-slate-500" for="status">الحالة</label>
        <select id="status" name="status" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
            @foreach (['active' => 'نشطة', 'inactive' => 'غير نشطة', 'archived' => 'مؤرشفة'] as $val => $label)
                <option value="{{ $val }}" @selected(old('status', $group?->status ?? 'active') === $val)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
</div>
