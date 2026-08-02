@php
    $statusLabels = ['draft' => 'مسودة', 'published' => 'منشور', 'scheduled' => 'مجدول', 'archived' => 'مؤرشف'];
    $lessonsPayload = $course->lessons->sortBy('position')->values()->map(fn ($l) => [
        'id' => $l->id,
        'title' => $l->title,
        'description' => $l->description ?? '',
        'position' => $l->position,
        'status' => $l->status,
        'is_locked' => (bool) $l->is_locked,
        'media_count' => $l->mediaAssets->count(),
        'update_url' => route('admin.lessons.update', $l),
        'destroy_url' => route('admin.lessons.destroy', $l),
        'lock_url' => route('admin.lessons.lock', $l),
        'unlock_url' => route('admin.lessons.unlock', $l),
        'show_url' => route('admin.lessons.show', $l),
        'media_store_url' => route('admin.lessons.media.store', $l),
        'media' => $l->mediaAssets->map(fn ($a) => [
            'id' => $a->id,
            'name' => $a->original_name ?? basename($a->path),
            'type' => $a->type,
            'mime' => $a->mime,
            'preview_url' => route('admin.lessons.media.show', [$l, $a]),
            'destroy_url' => route('admin.lessons.media.destroy', [$l, $a]),
            'kind' => match (true) {
                $a->type === 'video' || str_starts_with((string) $a->mime, 'video/') => 'video',
                $a->type === 'image' || str_starts_with((string) $a->mime, 'image/') => 'image',
                $a->type === 'pdf' || $a->mime === 'application/pdf' => 'pdf',
                default => 'file',
            },
        ])->values(),
    ]);
@endphp

<div
    class="space-y-4"
    x-data="{
        modal: null,
        mediaLessonId: null,
        editing: null,
        openCreate() { this.editing = null; this.modal = 'form'; this.mediaLessonId = null; },
        openEdit(lesson) { this.editing = lesson; this.modal = 'form'; this.mediaLessonId = null; },
        openMedia(lesson) { this.mediaLessonId = lesson.id; this.modal = 'media'; this.editing = lesson; },
        close() { this.modal = null; this.editing = null; this.mediaLessonId = null; },
        lessons: @js($lessonsPayload),
        statusLabels: @js($statusLabels),
        mediaLesson() { return this.lessons.find(l => l.id === this.mediaLessonId) || null; }
    }"
    @keydown.escape.window="close()"
>
    <div class="flex flex-wrap items-center justify-between gap-3">
        <h3 class="font-semibold text-slate-900">الدروس (<span x-text="lessons.length">{{ $course->lessons->count() }}</span>)</h3>
        <button type="button" @click="openCreate()" class="rounded-xl bg-[var(--color-primary)] px-4 py-2.5 text-sm font-semibold text-white hover:bg-[var(--color-primary-hover)]">
            إضافة درس
        </button>
    </div>

    <div class="space-y-3">
        <template x-for="lesson in lessons" :key="lesson.id">
            <div class="rounded-xl border border-slate-200 p-4">
                <div class="flex flex-wrap items-start justify-between gap-3">
                    <div>
                        <p class="font-medium text-slate-900">
                            <span class="text-slate-400" x-text="lesson.position + '.'"></span>
                            <span x-text="lesson.title"></span>
                        </p>
                        <p class="mt-1 text-xs text-slate-500">
                            <span x-text="statusLabels[lesson.status] || lesson.status"></span>
                            · <span x-text="lesson.media_count"></span> ملفات
                            <span x-show="lesson.is_locked" class="text-amber-700"> · مقفل</span>
                        </p>
                    </div>
                    <div class="flex flex-wrap gap-1.5">
                        <button type="button" @click="openEdit(lesson)" class="rounded-lg border border-slate-200 px-2.5 py-1.5 text-xs text-slate-700 hover:bg-slate-50">تعديل</button>
                        <button type="button" @click="openMedia(lesson)" class="rounded-lg border border-slate-200 px-2.5 py-1.5 text-xs text-slate-700 hover:bg-slate-50">وسائط</button>
                        <a :href="lesson.show_url" class="rounded-lg border border-slate-200 px-2.5 py-1.5 text-xs text-slate-700 hover:bg-slate-50">تفاصيل</a>
                        <form method="POST" :action="lesson.is_locked ? lesson.unlock_url : lesson.lock_url">
                            @csrf
                            <button type="submit" class="rounded-lg border px-2.5 py-1.5 text-xs"
                                    :class="lesson.is_locked ? 'border-emerald-200 bg-emerald-50 text-emerald-800' : 'border-amber-200 bg-amber-50 text-amber-900'"
                                    x-text="lesson.is_locked ? 'فتح' : 'قفل'"></button>
                        </form>
                        <form method="POST" :action="lesson.destroy_url" onsubmit="return confirm('حذف الدرس؟');">
                            @csrf
                            @method('DELETE')
                            @include('admin.courses._return_fields', ['course' => $course, 'tab' => 'lessons'])
                            <button type="submit" class="rounded-lg border border-rose-200 bg-rose-50 px-2.5 py-1.5 text-xs text-rose-800">حذف</button>
                        </form>
                    </div>
                </div>
            </div>
        </template>

        <p x-show="lessons.length === 0" class="rounded-xl border border-dashed border-slate-200 py-10 text-center text-sm text-slate-500">
            لا دروس بعد — اضغط «إضافة درس» للبدء.
        </p>
    </div>

    {{-- Create / Edit modal --}}
    <div
        x-show="modal === 'form'"
        x-cloak
        class="fixed inset-0 z-[80] flex items-end justify-center bg-black/45 p-4 sm:items-center"
        @click.self="close()"
    >
        <div
            class="max-h-[90vh] w-full max-w-lg overflow-y-auto rounded-2xl bg-white p-5 shadow-xl"
            @click.stop
            x-transition
        >
            <div class="mb-4 flex items-center justify-between gap-3">
                <h3 class="text-base font-semibold text-slate-900" x-text="editing ? 'تعديل الدرس' : 'إضافة درس'"></h3>
                <button type="button" @click="close()" class="rounded-lg p-2 text-slate-500 hover:bg-slate-100" aria-label="إغلاق">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form
                method="POST"
                :action="editing ? editing.update_url : '{{ route('admin.lessons.store') }}'"
                class="grid gap-3 sm:grid-cols-2"
                :key="editing ? ('edit-' + editing.id) : 'create'"
            >
                @csrf
                <template x-if="editing">
                    <input type="hidden" name="_method" value="PUT">
                </template>
                @include('admin.courses._return_fields', ['course' => $course, 'tab' => 'lessons'])
                <input type="hidden" name="course_id" value="{{ $course->id }}">

                <div class="sm:col-span-2">
                    <label class="mb-1 block text-xs text-slate-500">العنوان</label>
                    <input name="title" required class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm" placeholder="عنوان الدرس"
                           :value="editing?.title || ''">
                </div>
                <div class="sm:col-span-2">
                    <label class="mb-1 block text-xs text-slate-500">الوصف</label>
                    <textarea name="description" rows="3" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm"
                              x-effect="$el.value = editing?.description || ''"></textarea>
                </div>
                <div x-show="!!editing" x-cloak>
                    <label class="mb-1 block text-xs text-slate-500">الترتيب</label>
                    <input type="number" min="1" name="position" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm"
                           :value="editing?.position || ''"
                           :disabled="!editing">
                </div>
                <div :class="editing ? '' : 'sm:col-span-2'">
                    <label class="mb-1 block text-xs text-slate-500">الحالة</label>
                    <select name="status" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm"
                            x-effect="$el.value = editing?.status || 'draft'">
                        @foreach ($statusLabels as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-center gap-2 sm:col-span-2">
                    <input id="lesson_modal_locked" type="checkbox" name="is_locked" value="1" class="rounded border-slate-300"
                           :checked="!!editing?.is_locked">
                    <label for="lesson_modal_locked" class="text-sm">قفل الدرس</label>
                </div>
                <div class="sm:col-span-2 flex flex-wrap gap-2 pt-2">
                    <button type="submit" class="rounded-xl bg-[var(--color-primary)] px-4 py-2.5 text-sm font-semibold text-white"
                            x-text="editing ? 'حفظ التعديلات' : 'إضافة الدرس'"></button>
                    <button type="button" @click="close()" class="rounded-xl border px-4 py-2.5 text-sm">إلغاء</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Media modal --}}
    <div
        x-show="modal === 'media' && mediaLesson()"
        x-cloak
        class="fixed inset-0 z-[80] flex items-end justify-center bg-black/45 p-4 sm:items-center"
        @click.self="close()"
    >
        <div class="max-h-[90vh] w-full max-w-2xl overflow-y-auto rounded-2xl bg-white p-5 shadow-xl" @click.stop>
            <div class="mb-4 flex items-center justify-between gap-3">
                <div>
                    <h3 class="text-base font-semibold text-slate-900">وسائط الدرس</h3>
                    <p class="text-xs text-slate-500" x-text="mediaLesson()?.title"></p>
                </div>
                <button type="button" @click="close()" class="rounded-lg p-2 text-slate-500 hover:bg-slate-100" aria-label="إغلاق">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <template x-if="mediaLesson()">
                <div>
                    <template x-for="lesson in [mediaLesson()]" :key="'upload-'+lesson.id">
                        <div
                            class="mb-4 space-y-3 rounded-xl border border-slate-200 bg-slate-50/80 p-4"
                            x-data="mediaUploader({
                                csrf: @js(csrf_token()),
                                uploadUrl: lesson.media_store_url,
                                reloadOnSuccess: true,
                                showTypeSelect: true,
                            })"
                        >
                            <div class="grid gap-3 sm:grid-cols-2">
                                <div>
                                    <label class="mb-1 block text-xs text-slate-500">الملف</label>
                                    <input type="file" x-ref="fileInput" class="block w-full text-sm" @change="onPick($event)" :disabled="uploading">
                                </div>
                                <div>
                                    <label class="mb-1 block text-xs text-slate-500">النوع</label>
                                    <select x-model="type" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm" :disabled="uploading">
                                        <option value="">تلقائي</option>
                                        <option value="video">فيديو</option>
                                        <option value="pdf">PDF</option>
                                        <option value="image">صورة</option>
                                        <option value="attachment">مرفق</option>
                                    </select>
                                </div>
                            </div>

                            <template x-if="previewKind === 'video' && previewUrl">
                                <video class="max-h-56 w-full rounded-xl bg-black" controls preload="metadata" :src="previewUrl"></video>
                            </template>
                            <template x-if="previewKind === 'image' && previewUrl">
                                <img :src="previewUrl" alt="معاينة" class="max-h-56 w-full rounded-xl object-contain bg-white">
                            </template>
                            <template x-if="previewKind === 'pdf' && previewUrl">
                                <iframe :src="previewUrl" class="h-56 w-full rounded-xl border-0 bg-white" title="معاينة PDF"></iframe>
                            </template>
                            <template x-if="previewKind === 'file' && file">
                                <div class="rounded-xl border border-dashed border-slate-300 bg-white px-4 py-3 text-sm text-slate-600">
                                    <span x-text="file.name"></span> · <span x-text="formatBytes(file.size)"></span>
                                </div>
                            </template>

                            <div x-show="uploading || progress > 0" x-cloak>
                                <div class="mb-1 flex justify-between text-xs text-slate-600">
                                    <span x-text="message || 'الرفع'"></span>
                                    <span><span x-text="progress"></span>%</span>
                                </div>
                                <div class="h-2.5 overflow-hidden rounded-full bg-slate-200">
                                    <div class="h-full rounded-full bg-[var(--color-primary)] transition-all duration-200" :style="`width: ${progress}%`"></div>
                                </div>
                            </div>
                            <p class="text-sm text-rose-700" x-show="error" x-text="error" x-cloak></p>

                            <div class="flex flex-wrap gap-2">
                                <button type="button" class="rounded-xl bg-slate-900 px-4 py-2 text-sm text-white disabled:opacity-50" @click="startUpload()" :disabled="uploading || ! file">
                                    <span x-show="! uploading">رفع</span>
                                    <span x-show="uploading" x-cloak>جارٍ الرفع…</span>
                                </button>
                                <button type="button" class="rounded-xl border px-4 py-2 text-sm" @click="cancel()" x-show="uploading" x-cloak>إلغاء</button>
                            </div>
                        </div>
                    </template>

                    <ul class="space-y-4 text-sm">
                        <template x-for="asset in mediaLesson().media" :key="asset.id">
                            <li class="rounded-xl border border-slate-200 p-3">
                                <div class="mb-2 flex items-center justify-between gap-3">
                                    <div class="min-w-0">
                                        <p class="truncate font-medium" x-text="asset.name"></p>
                                        <p class="text-xs text-slate-400" x-text="asset.type"></p>
                                    </div>
                                    <form method="POST" :action="asset.destroy_url" onsubmit="return confirm('حذف الملف؟');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs text-rose-700 hover:underline">حذف</button>
                                    </form>
                                </div>

                                <template x-if="asset.kind === 'video'">
                                    <video class="max-h-56 w-full rounded-lg bg-black" controls preload="metadata" :src="asset.preview_url"></video>
                                </template>
                                <template x-if="asset.kind === 'image'">
                                    <a :href="asset.preview_url" target="_blank" rel="noopener">
                                        <img :src="asset.preview_url" :alt="asset.name" class="max-h-56 w-full rounded-lg object-contain bg-white">
                                    </a>
                                </template>
                                <template x-if="asset.kind === 'pdf'">
                                    <div>
                                        <iframe :src="asset.preview_url" class="h-56 w-full rounded-lg border-0 bg-white" :title="asset.name"></iframe>
                                        <a :href="asset.preview_url" target="_blank" rel="noopener" class="mt-2 inline-block text-xs text-[var(--color-primary)] hover:underline">فتح PDF</a>
                                    </div>
                                </template>
                                <template x-if="asset.kind === 'file'">
                                    <a :href="asset.preview_url" target="_blank" rel="noopener" class="inline-flex rounded-lg border px-3 py-1.5 text-xs font-medium text-slate-700 hover:bg-slate-50">فتح / تنزيل</a>
                                </template>
                            </li>
                        </template>
                    </ul>
                    <p x-show="! mediaLesson().media.length" class="py-4 text-center text-sm text-slate-500">لا وسائط لهذا الدرس.</p>
                </div>
            </template>
        </div>
    </div>
</div>
