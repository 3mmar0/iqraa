@php $a = $assignment; @endphp
<div>
    <label class="mb-1 block text-sm font-medium" for="title">العنوان</label>
    <input id="title" name="title" value="{{ old('title', $a?->title) }}" required class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
</div>
<div>
    <label class="mb-1 block text-sm font-medium" for="description">الوصف</label>
    <textarea id="description" name="description" rows="4" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">{{ old('description', $a?->description) }}</textarea>
</div>
<div class="grid gap-4 sm:grid-cols-2">
    <div>
        <label class="mb-1 block text-sm font-medium" for="course_id">المقرر</label>
        <select id="course_id" name="course_id" required class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
            <option value="">اختر مقرراً</option>
            @foreach ($courses as $course)
                <option value="{{ $course->id }}" @selected((string) old('course_id', $a?->course_id) === (string) $course->id)>{{ $course->title }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="mb-1 block text-sm font-medium" for="lesson_id">الدرس (اختياري)</label>
        <select id="lesson_id" name="lesson_id" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
            <option value="">—</option>
            @foreach ($lessons as $lesson)
                <option value="{{ $lesson->id }}" @selected((string) old('lesson_id', $a?->lesson_id) === (string) $lesson->id)>{{ $lesson->title }} @if($lesson->course) ({{ $lesson->course->title }}) @endif</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="mb-1 block text-sm font-medium" for="due_at">موعد التسليم</label>
        <input id="due_at" type="datetime-local" name="due_at" value="{{ old('due_at', $a?->due_at?->format('Y-m-d\TH:i')) }}" required class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
    </div>
    <div>
        <label class="mb-1 block text-sm font-medium" for="status">الحالة</label>
        <select id="status" name="status" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
            <option value="draft" @selected(old('status', $a?->status ?? 'draft') === 'draft')>مسودة</option>
            <option value="published" @selected(old('status', $a?->status) === 'published')>منشور</option>
            <option value="archived" @selected(old('status', $a?->status) === 'archived')>مؤرشف</option>
        </select>
    </div>
</div>
