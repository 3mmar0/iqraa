@php $l = $lesson; @endphp
<div>
    <label class="admin-label" for="course_id">المقرر</label>
    <select id="course_id" name="course_id" required class="admin-input">
        <option value="">اختر مقرراً</option>
        @foreach ($courses as $course)
            <option value="{{ $course->id }}" @selected((string) old('course_id', $selectedCourseId ?? $l?->course_id) === (string) $course->id)>{{ $course->title }}</option>
        @endforeach
    </select>
</div>
<div>
    <label class="admin-label" for="title">العنوان</label>
    <input id="title" name="title" value="{{ old('title', $l?->title) }}" required class="admin-input">
</div>
<div>
    <label class="admin-label" for="description">وصف مختصر</label>
    <textarea id="description" name="description" rows="2" class="admin-input" placeholder="يظهر في القوائم والمسار">{{ old('description', $l?->description) }}</textarea>
</div>
<div>
    <label class="admin-label" for="content_html">شرح الدرس (نص منسّق)</label>
    <textarea id="content_html" name="content_html" rows="8" class="admin-input font-sans" placeholder="اشرح الدرس للطالب: عناوين، قوائم، روابط…">{{ old('content_html', $l?->content_html) }}</textarea>
    <p class="mt-1 text-xs text-slate-500">يُعرض للطالب بعد الفيديو الرئيسي. يُسمح بعناصر HTML بسيطة فقط.</p>
</div>
@if ($l)
    <div>
        <label class="admin-label" for="main_media_asset_id">الفيديو الرئيسي</label>
        <select id="main_media_asset_id" name="main_media_asset_id" class="admin-input">
            <option value="">بدون فيديو رئيسي</option>
            @foreach ($l->mediaAssets->where('type', 'video') as $video)
                <option value="{{ $video->id }}" @selected((string) old('main_media_asset_id', $l->main_media_asset_id) === (string) $video->id)>
                    {{ $video->original_name ?? basename($video->path) }}
                </option>
            @endforeach
        </select>
        <p class="mt-1 text-xs text-slate-500">ارفع فيديو من تبويب الوسائط ثم اختره هنا. أول فيديو يُرفع يُعيَّن تلقائياً إن لم يوجد رئيسي.</p>
        @if (! $l->main_media_asset_id)
            <p class="mt-1 text-xs font-medium text-amber-700">لا يوجد فيديو رئيسي بعد — مسار الطالب سيكون ناقصاً حتى ترفعه.</p>
        @endif
    </div>
@else
    <p class="rounded-xl border border-dashed border-slate-200 bg-slate-50 px-3.5 py-3 text-xs text-slate-600">
        بعد إنشاء الدرس، ارفع الفيديو الرئيسي من صفحة الدرس (قسم الفيديو / الوسائط).
    </p>
@endif
<div class="grid gap-4 sm:grid-cols-2">
    <div>
        <label class="admin-label" for="position">الترتيب</label>
        <input id="position" type="number" min="1" name="position" value="{{ old('position', $l?->position) }}" class="admin-input">
    </div>
    <div>
        <label class="admin-label" for="status">الحالة</label>
        <select id="status" name="status" class="admin-input">
            <option value="draft" @selected(old('status', $l?->status ?? 'draft') === 'draft')>مسودة</option>
            <option value="published" @selected(old('status', $l?->status) === 'published')>منشور</option>
            <option value="scheduled" @selected(old('status', $l?->status) === 'scheduled')>مجدول</option>
            <option value="archived" @selected(old('status', $l?->status) === 'archived')>مؤرشف</option>
        </select>
    </div>
    <div>
        <label class="admin-label" for="published_at">تاريخ النشر</label>
        <input id="published_at" type="datetime-local" name="published_at" value="{{ old('published_at', $l?->published_at?->format('Y-m-d\TH:i')) }}" class="admin-input">
    </div>
    <div>
        <label class="admin-label" for="quiz_id">اختبار ما بعد المشاهدة</label>
        <select id="quiz_id" name="quiz_id" class="admin-input">
            <option value="">بدون اختبار</option>
            @foreach ($quizzes ?? [] as $quiz)
                <option value="{{ $quiz->id }}" @selected((string) old('quiz_id', $l?->quiz_id) === (string) $quiz->id)>
                    {{ $quiz->title }}
                    @if (($quiz->status ?? null) && $quiz->status !== 'published')
                        ({{ $quiz->status === 'draft' ? 'مسودة' : $quiz->status }})
                    @endif
                </option>
            @endforeach
        </select>
        <p class="mt-1 text-xs text-slate-500">يظهر للطالب بعد إكمال مشاهدة الفيديو الرئيسي.</p>
        @php
            $linked = collect($quizzes ?? [])->firstWhere('id', (int) old('quiz_id', $l?->quiz_id));
        @endphp
        @if ($linked && ($linked->status ?? null) === 'draft')
            <p class="mt-1 text-xs font-medium text-amber-700">الاختبار المختار مسودة — لن يظهر للطالب حتى يُنشر.</p>
        @endif
    </div>
</div>
<label class="flex items-center gap-2.5 rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-3 text-sm text-slate-700">
    <input id="is_locked" type="checkbox" name="is_locked" value="1" @checked(old('is_locked', $l?->is_locked)) class="rounded border-slate-300 text-[var(--color-primary)] focus:ring-[var(--color-primary)]">
    قفل الدرس (غير مرئي للطلاب)
</label>
