@php
    $statusLabels = ['draft' => 'مسودة', 'published' => 'منشور', 'scheduled' => 'مجدول', 'archived' => 'مؤرشف'];
@endphp

<div class="space-y-6">
    <section class="rounded-xl border border-slate-200 bg-slate-50/70 p-4">
        <h3 class="mb-3 text-sm font-semibold text-slate-900">إضافة درس</h3>
        <form method="POST" action="{{ route('admin.lessons.store') }}" class="grid gap-3 sm:grid-cols-2">
            @csrf
            @include('admin.courses._return_fields', ['course' => $course, 'tab' => 'lessons'])
            <input type="hidden" name="course_id" value="{{ $course->id }}">
            <div class="sm:col-span-2">
                <label class="mb-1 block text-xs text-slate-500" for="lesson_title">العنوان</label>
                <input id="lesson_title" name="title" required class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm" placeholder="عنوان الدرس">
            </div>
            <div class="sm:col-span-2">
                <label class="mb-1 block text-xs text-slate-500" for="lesson_description">الوصف (اختياري)</label>
                <textarea id="lesson_description" name="description" rows="2" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm"></textarea>
            </div>
            <div>
                <label class="mb-1 block text-xs text-slate-500" for="lesson_status">الحالة</label>
                <select id="lesson_status" name="status" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm">
                    <option value="draft">مسودة</option>
                    <option value="published">منشور</option>
                    <option value="scheduled">مجدول</option>
                </select>
            </div>
            <div class="flex items-end">
                <label class="flex items-center gap-2 text-sm text-slate-700">
                    <input type="checkbox" name="is_locked" value="1" class="rounded border-slate-300">
                    قفل الدرس
                </label>
            </div>
            <div class="sm:col-span-2">
                <button type="submit" class="rounded-xl bg-[var(--color-primary)] px-4 py-2.5 text-sm font-semibold text-white hover:bg-[var(--color-primary-hover)]">إضافة الدرس</button>
            </div>
        </form>
    </section>

    <section>
        <h3 class="mb-3 font-semibold text-slate-900">الدروس ({{ $course->lessons->count() }})</h3>
        <div class="space-y-3">
            @forelse ($course->lessons->sortBy('position') as $lesson)
                <div class="rounded-xl border border-slate-200 p-4" x-data="{ editing: false, media: false }">
                    <div class="flex flex-wrap items-start justify-between gap-3">
                        <div>
                            <p class="font-medium text-slate-900">
                                <span class="text-slate-400">{{ $lesson->position }}.</span>
                                {{ $lesson->title }}
                            </p>
                            <p class="mt-1 text-xs text-slate-500">
                                {{ $statusLabels[$lesson->status] ?? $lesson->status }}
                                · {{ $lesson->mediaAssets->count() }} ملفات
                                @if ($lesson->is_locked)
                                    · <span class="text-amber-700">مقفل</span>
                                @endif
                            </p>
                        </div>
                        <div class="flex flex-wrap gap-1.5">
                            <button type="button" @click="editing = !editing; media = false" class="rounded-lg border border-slate-200 px-2.5 py-1.5 text-xs text-slate-700 hover:bg-slate-50">تعديل</button>
                            <button type="button" @click="media = !media; editing = false" class="rounded-lg border border-slate-200 px-2.5 py-1.5 text-xs text-slate-700 hover:bg-slate-50">وسائط</button>
                            <a href="{{ route('admin.lessons.show', $lesson) }}" class="rounded-lg border border-slate-200 px-2.5 py-1.5 text-xs text-slate-700 hover:bg-slate-50">تفاصيل</a>
                            @if ($lesson->is_locked)
                                <form method="POST" action="{{ route('admin.lessons.unlock', $lesson) }}">@csrf<button class="rounded-lg border border-emerald-200 bg-emerald-50 px-2.5 py-1.5 text-xs text-emerald-800">فتح</button></form>
                            @else
                                <form method="POST" action="{{ route('admin.lessons.lock', $lesson) }}">@csrf<button class="rounded-lg border border-amber-200 bg-amber-50 px-2.5 py-1.5 text-xs text-amber-900">قفل</button></form>
                            @endif
                            <form method="POST" action="{{ route('admin.lessons.destroy', $lesson) }}" onsubmit="return confirm('حذف الدرس؟');">
                                @csrf
                                @method('DELETE')
                                @include('admin.courses._return_fields', ['course' => $course, 'tab' => 'lessons'])
                                <button class="rounded-lg border border-rose-200 bg-rose-50 px-2.5 py-1.5 text-xs text-rose-800">حذف</button>
                            </form>
                        </div>
                    </div>

                    <div x-show="editing" x-cloak class="mt-4 border-t border-slate-100 pt-4">
                        <form method="POST" action="{{ route('admin.lessons.update', $lesson) }}" class="grid gap-3 sm:grid-cols-2">
                            @csrf
                            @method('PUT')
                            @include('admin.courses._return_fields', ['course' => $course, 'tab' => 'lessons'])
                            <input type="hidden" name="course_id" value="{{ $course->id }}">
                            <div class="sm:col-span-2">
                                <label class="mb-1 block text-xs text-slate-500">العنوان</label>
                                <input name="title" value="{{ $lesson->title }}" required class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm">
                            </div>
                            <div class="sm:col-span-2">
                                <label class="mb-1 block text-xs text-slate-500">الوصف</label>
                                <textarea name="description" rows="2" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm">{{ $lesson->description }}</textarea>
                            </div>
                            <div>
                                <label class="mb-1 block text-xs text-slate-500">الترتيب</label>
                                <input type="number" min="1" name="position" value="{{ $lesson->position }}" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm">
                            </div>
                            <div>
                                <label class="mb-1 block text-xs text-slate-500">الحالة</label>
                                <select name="status" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm">
                                    @foreach ($statusLabels as $value => $label)
                                        <option value="{{ $value }}" @selected($lesson->status === $value)>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex items-center gap-2 sm:col-span-2">
                                <input id="locked_{{ $lesson->id }}" type="checkbox" name="is_locked" value="1" @checked($lesson->is_locked) class="rounded border-slate-300">
                                <label for="locked_{{ $lesson->id }}" class="text-sm">قفل الدرس</label>
                            </div>
                            <div class="sm:col-span-2 flex gap-2">
                                <button type="submit" class="rounded-xl bg-[var(--color-primary)] px-4 py-2 text-sm font-semibold text-white">حفظ</button>
                                <button type="button" @click="editing = false" class="rounded-xl border px-4 py-2 text-sm">إلغاء</button>
                            </div>
                        </form>
                    </div>

                    <div x-show="media" x-cloak class="mt-4 border-t border-slate-100 pt-4">
                        <form method="POST" action="{{ route('admin.lessons.media.store', $lesson) }}" enctype="multipart/form-data" class="mb-4 flex flex-wrap items-end gap-2">
                            @csrf
                            <div>
                                <label class="mb-1 block text-xs text-slate-500">رفع ملف / فيديو</label>
                                <input type="file" name="file" required class="block w-full text-sm">
                            </div>
                            <div>
                                <label class="mb-1 block text-xs text-slate-500">النوع</label>
                                <select name="type" class="rounded-xl border border-slate-200 px-3 py-2 text-sm">
                                    <option value="">تلقائي</option>
                                    <option value="video">فيديو</option>
                                    <option value="pdf">PDF</option>
                                    <option value="attachment">مرفق</option>
                                    <option value="image">صورة</option>
                                </select>
                            </div>
                            <button type="submit" class="rounded-xl bg-slate-900 px-4 py-2 text-sm text-white">رفع</button>
                        </form>
                        <ul class="divide-y divide-slate-100 text-sm">
                            @forelse ($lesson->mediaAssets as $asset)
                                <li class="flex items-center justify-between gap-3 py-2">
                                    <span class="min-w-0 truncate">{{ $asset->original_name ?? basename($asset->path) }} <span class="text-xs text-slate-400">({{ $asset->type }})</span></span>
                                    <form method="POST" action="{{ route('admin.lessons.media.destroy', [$lesson, $asset]) }}" onsubmit="return confirm('حذف الملف؟');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-xs text-rose-700 hover:underline">حذف</button>
                                    </form>
                                </li>
                            @empty
                                <li class="py-3 text-slate-500">لا وسائط لهذا الدرس.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            @empty
                <p class="rounded-xl border border-dashed border-slate-200 py-10 text-center text-sm text-slate-500">لا دروس بعد — أضف أول درس من النموذج أعلاه.</p>
            @endforelse
        </div>
    </section>
</div>
