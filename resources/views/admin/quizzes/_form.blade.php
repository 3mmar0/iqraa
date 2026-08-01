@php $q = $quiz; @endphp
<div>
    <label class="mb-1 block text-sm font-medium" for="title">العنوان</label>
    <input id="title" name="title" value="{{ old('title', $q?->title) }}" required class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
</div>
<div class="grid gap-4 sm:grid-cols-2">
    <div>
        <label class="mb-1 block text-sm font-medium" for="course_id">المقرر</label>
        <select id="course_id" name="course_id" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
            <option value="">—</option>
            @foreach ($courses as $course)
                <option value="{{ $course->id }}" @selected((string) old('course_id', $q?->course_id) === (string) $course->id)>{{ $course->title }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="mb-1 block text-sm font-medium" for="duration_minutes">المدة (دقائق)</label>
        <input id="duration_minutes" type="number" min="1" name="duration_minutes" value="{{ old('duration_minutes', $q?->duration_minutes) }}" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
    </div>
    <div>
        <label class="mb-1 block text-sm font-medium" for="status">الحالة</label>
        <select id="status" name="status" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
            <option value="draft" @selected(old('status', $q?->status ?? 'draft') === 'draft')>مسودة</option>
            <option value="published" @selected(old('status', $q?->status) === 'published')>منشور</option>
        </select>
    </div>
</div>
<div class="flex items-center gap-2">
    <input id="show_correct_answers" type="checkbox" name="show_correct_answers" value="1" @checked(old('show_correct_answers', $q?->show_correct_answers)) class="rounded border-slate-300">
    <label for="show_correct_answers" class="text-sm">إظهار الإجابات الصحيحة بعد التسليم</label>
</div>
