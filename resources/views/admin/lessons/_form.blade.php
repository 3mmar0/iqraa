@php $l = $lesson; @endphp
<div>
    <label class="mb-1 block text-sm font-medium" for="course_id">المقرر</label>
    <select id="course_id" name="course_id" required class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
        <option value="">اختر مقرراً</option>
        @foreach ($courses as $course)
            <option value="{{ $course->id }}" @selected((string) old('course_id', $selectedCourseId ?? $l?->course_id) === (string) $course->id)>{{ $course->title }}</option>
        @endforeach
    </select>
</div>
<div>
    <label class="mb-1 block text-sm font-medium" for="title">العنوان</label>
    <input id="title" name="title" value="{{ old('title', $l?->title) }}" required class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
</div>
<div>
    <label class="mb-1 block text-sm font-medium" for="description">الوصف</label>
    <textarea id="description" name="description" rows="4" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">{{ old('description', $l?->description) }}</textarea>
</div>
<div class="grid gap-4 sm:grid-cols-2">
    <div>
        <label class="mb-1 block text-sm font-medium" for="position">الترتيب</label>
        <input id="position" type="number" min="1" name="position" value="{{ old('position', $l?->position) }}" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
    </div>
    <div>
        <label class="mb-1 block text-sm font-medium" for="status">الحالة</label>
        <select id="status" name="status" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
            <option value="draft" @selected(old('status', $l?->status ?? 'draft') === 'draft')>مسودة</option>
            <option value="published" @selected(old('status', $l?->status) === 'published')>منشور</option>
            <option value="scheduled" @selected(old('status', $l?->status) === 'scheduled')>مجدول</option>
            <option value="archived" @selected(old('status', $l?->status) === 'archived')>مؤرشف</option>
        </select>
    </div>
    <div>
        <label class="mb-1 block text-sm font-medium" for="published_at">تاريخ النشر</label>
        <input id="published_at" type="datetime-local" name="published_at" value="{{ old('published_at', $l?->published_at?->format('Y-m-d\TH:i')) }}" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
    </div>
    <div>
        <label class="mb-1 block text-sm font-medium" for="quiz_id">الاختبار المرتبط</label>
        <select id="quiz_id" name="quiz_id" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
            <option value="">بدون اختبار</option>
            @foreach ($quizzes ?? [] as $quiz)
                <option value="{{ $quiz->id }}" @selected((string) old('quiz_id', $l?->quiz_id) === (string) $quiz->id)>{{ $quiz->title }}</option>
            @endforeach
        </select>
    </div>
</div>
<div class="flex items-center gap-2">
    <input id="is_locked" type="checkbox" name="is_locked" value="1" @checked(old('is_locked', $l?->is_locked)) class="rounded border-slate-300">
    <label for="is_locked" class="text-sm">قفل الدرس (غير مرئي للطلاب)</label>
</div>
