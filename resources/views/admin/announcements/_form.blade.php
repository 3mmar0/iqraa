@php $announcement = $announcement ?? null; @endphp
<div class="grid gap-4">
    <div>
        <label class="mb-1 block text-xs font-medium text-slate-500" for="title">العنوان</label>
        <input id="title" name="title" value="{{ old('title', $announcement?->title) }}" required class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
    </div>
    <div>
        <label class="mb-1 block text-xs font-medium text-slate-500" for="body">المحتوى</label>
        <textarea id="body" name="body" rows="6" required class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">{{ old('body', $announcement?->body) }}</textarea>
    </div>
    <div>
        <label class="mb-1 block text-xs font-medium text-slate-500" for="course_id">المقرر (اختياري — جمهور المقرر)</label>
        <select id="course_id" name="course_id" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
            <option value="">كل المنصة</option>
            @foreach ($courses as $course)
                <option value="{{ $course->id }}" @selected(old('course_id', $announcement?->course_id) == $course->id)>{{ $course->title }}</option>
            @endforeach
        </select>
    </div>
</div>
