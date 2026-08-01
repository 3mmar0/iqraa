@php
    $c = $course;
@endphp
<div>
    <label class="mb-1 block text-sm font-medium" for="title">العنوان</label>
    <input id="title" name="title" value="{{ old('title', $c?->title) }}" required class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
    @error('title')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
</div>
<div>
    <label class="mb-1 block text-sm font-medium" for="description">الوصف</label>
    <textarea id="description" name="description" rows="4" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">{{ old('description', $c?->description) }}</textarea>
</div>
<div class="grid gap-4 sm:grid-cols-2">
    <div>
        <label class="mb-1 block text-sm font-medium" for="instructor_user_id">المحاضر</label>
        <select id="instructor_user_id" name="instructor_user_id" required class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
            <option value="">اختر محاضراً</option>
            @foreach ($instructors as $instructor)
                <option value="{{ $instructor->id }}" @selected((string) old('instructor_user_id', $c?->instructor_user_id) === (string) $instructor->id)>{{ $instructor->name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="mb-1 block text-sm font-medium" for="category_id">التصنيف</label>
        <select id="category_id" name="category_id" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
            <option value="">بدون تصنيف</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" @selected((string) old('category_id', $c?->category_id) === (string) $category->id)>{{ $category->name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="mb-1 block text-sm font-medium" for="academic_year_id">السنة الدراسية</label>
        <select id="academic_year_id" name="academic_year_id" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
            <option value="">—</option>
            @foreach ($academicYears as $year)
                <option value="{{ $year->id }}" @selected((string) old('academic_year_id', $c?->academic_year_id) === (string) $year->id)>{{ $year->name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="mb-1 block text-sm font-medium" for="semester_id">الفصل الدراسي</label>
        <select id="semester_id" name="semester_id" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
            <option value="">—</option>
            @foreach ($semesters as $semester)
                <option value="{{ $semester->id }}" @selected((string) old('semester_id', $c?->semester_id) === (string) $semester->id)>{{ $semester->name }} @if($semester->academicYear) ({{ $semester->academicYear->name }}) @endif</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="mb-1 block text-sm font-medium" for="price">السعر (ر.س)</label>
        <input id="price" type="number" step="0.01" min="0" name="price" value="{{ old('price', $c?->price) }}" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
    </div>
    <div>
        <label class="mb-1 block text-sm font-medium" for="status">الحالة</label>
        <select id="status" name="status" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
            <option value="draft" @selected(old('status', $c?->status ?? 'draft') === 'draft')>مسودة</option>
            <option value="published" @selected(old('status', $c?->status) === 'published')>منشور</option>
            <option value="hidden" @selected(old('status', $c?->status) === 'hidden')>مخفي</option>
            <option value="archived" @selected(old('status', $c?->status) === 'archived')>مؤرشف</option>
        </select>
    </div>
    <div>
        <label class="mb-1 block text-sm font-medium" for="hours">الساعات</label>
        <input id="hours" type="number" step="0.5" name="hours" value="{{ old('hours', $c?->hours) }}" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
    </div>
    <div>
        <label class="mb-1 block text-sm font-medium" for="term_label">الفصل / الترم (نص)</label>
        <input id="term_label" name="term_label" value="{{ old('term_label', $c?->term_label) }}" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
    </div>
</div>
<div>
    <label class="mb-1 block text-sm font-medium" for="schedule_text">الجدول</label>
    <input id="schedule_text" name="schedule_text" value="{{ old('schedule_text', $c?->schedule_text) }}" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
</div>
