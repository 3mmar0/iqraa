@php $q = $quiz; @endphp
<div>
    <label class="admin-label" for="title">العنوان</label>
    <input id="title" name="title" value="{{ old('title', $q?->title) }}" required class="admin-input">
</div>
<div class="grid gap-4 sm:grid-cols-2">
    <div>
        <label class="admin-label" for="course_id">المقرر</label>
        <select id="course_id" name="course_id" class="admin-input">
            <option value="">—</option>
            @foreach ($courses as $course)
                <option value="{{ $course->id }}" @selected((string) old('course_id', $q?->course_id) === (string) $course->id)>{{ $course->title }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="admin-label" for="duration_minutes">المدة (دقائق)</label>
        <input id="duration_minutes" type="number" min="1" name="duration_minutes" value="{{ old('duration_minutes', $q?->duration_minutes) }}" class="admin-input">
    </div>
    <div>
        <label class="admin-label" for="status">الحالة</label>
        <select id="status" name="status" class="admin-input">
            <option value="draft" @selected(old('status', $q?->status ?? 'draft') === 'draft')>مسودة</option>
            <option value="published" @selected(old('status', $q?->status) === 'published')>منشور</option>
        </select>
    </div>
</div>
<label class="flex items-center gap-2.5 rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-3 text-sm text-slate-700">
    <input id="show_correct_answers" type="checkbox" name="show_correct_answers" value="1" @checked(old('show_correct_answers', $q?->show_correct_answers)) class="rounded border-slate-300 text-[var(--color-primary)] focus:ring-[var(--color-primary)]">
    إظهار الإجابات الصحيحة بعد التسليم
</label>
