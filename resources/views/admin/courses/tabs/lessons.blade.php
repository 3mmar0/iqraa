@php
    $statusLabels = ['draft' => 'مسودة', 'published' => 'منشور', 'scheduled' => 'مجدول', 'archived' => 'مؤرشف'];
    $lessonsPayload = $course->lessons->sortBy('position')->values()->map(fn ($l) => [
        'id' => $l->id,
        'title' => $l->title,
        'description' => $l->description ?? '',
        'content_html' => $l->content_html ?? '',
        'position' => $l->position,
        'status' => $l->status,
        'is_locked' => (bool) $l->is_locked,
        'main_media_asset_id' => $l->main_media_asset_id,
        'quiz_id' => $l->quiz_id,
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
        <h3 class="text-base font-semibold text-slate-900">الدروس (<span x-text="lessons.length">{{ $course->lessons->count() }}</span>)</h3>
        <button type="button" @click="openCreate()" class="admin-btn admin-btn-primary">
            إضافة درس
        </button>
    </div>

    <div class="space-y-3">
        <template x-for="lesson in lessons" :key="lesson.id">
            <div class="rounded-2xl border border-slate-200 bg-gradient-to-l from-slate-50/80 to-white p-4 transition hover:border-[var(--color-primary)]/30 hover:shadow-sm">
                <div class="flex flex-wrap items-start justify-between gap-3">
                    <div class="flex items-start gap-3">
                        <div class="admin-entity-thumb !h-10 !w-10 text-[0.7rem]" x-text="lesson.position"></div>
                        <div>
                            <p class="font-semibold text-slate-900" x-text="lesson.title"></p>
                            <p class="mt-1 text-xs text-slate-500">
                                <span x-text="statusLabels[lesson.status] || lesson.status"></span>
                                · <span x-text="lesson.media_count"></span> ملفات
                                <span x-show="lesson.is_locked" class="text-amber-700"> · مقفل</span>
                            </p>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-1.5">
                        <button type="button" @click="openEdit(lesson)" class="admin-btn admin-btn-ghost admin-btn-sm">تعديل</button>
                        <button type="button" @click="openMedia(lesson)" class="admin-btn admin-btn-ghost admin-btn-sm">وسائط</button>
                        <a :href="lesson.show_url" class="admin-btn admin-btn-ghost admin-btn-sm">تفاصيل</a>
                        <form method="POST" :action="lesson.is_locked ? lesson.unlock_url : lesson.lock_url">
                            @csrf
                            <button type="submit" class="admin-btn admin-btn-sm"
                                    :class="lesson.is_locked ? 'border border-emerald-200 bg-emerald-50 text-emerald-800' : 'border border-amber-200 bg-amber-50 text-amber-900'"
                                    x-text="lesson.is_locked ? 'فتح' : 'قفل'"></button>
                        </form>
                        <form method="POST" :action="lesson.destroy_url" onsubmit="return confirm('حذف الدرس؟');">
                            @csrf
                            @method('DELETE')
                            @include('admin.courses._return_fields', ['course' => $course, 'tab' => 'lessons'])
                            <button type="submit" class="admin-btn admin-btn-danger admin-btn-sm">حذف</button>
                        </form>
                    </div>
                </div>
            </div>
        </template>

        <p x-show="lessons.length === 0" class="rounded-2xl border border-dashed border-slate-200 bg-slate-50/50 py-12 text-center text-sm text-slate-500">
            لا دروس بعد — اضغط «إضافة درس» للبدء.
        </p>
    </div>

    {{-- Create / Edit modal --}}
    <x-admin.modal show="modal === 'form'">
        <x-slot:header>
            <h3 class="text-base font-semibold text-slate-900" x-text="editing ? 'تعديل الدرس' : 'إضافة درس'"></h3>
            <p class="mt-0.5 text-xs text-slate-500">العنوان، الشرح، وحالة النشر — الفيديو الرئيسي واختبار ما بعد المشاهدة من صفحة تعديل الدرس</p>
        </x-slot:header>

        <form
            method="POST"
            :action="editing ? editing.update_url : '{{ route('admin.lessons.store') }}'"
            class="grid gap-4 sm:grid-cols-2"
            :key="editing ? ('edit-' + editing.id) : 'create'"
        >
            @csrf
            <template x-if="editing">
                <input type="hidden" name="_method" value="PUT">
            </template>
            @include('admin.courses._return_fields', ['course' => $course, 'tab' => 'lessons'])
            <input type="hidden" name="course_id" value="{{ $course->id }}">

            <div class="sm:col-span-2">
                <label class="admin-label">العنوان</label>
                <input name="title" required class="admin-input" placeholder="عنوان الدرس" :value="editing?.title || ''">
            </div>
            <div class="sm:col-span-2">
                <label class="admin-label">وصف مختصر</label>
                <textarea name="description" rows="2" class="admin-input" x-effect="$el.value = editing?.description || ''"></textarea>
            </div>
            <div class="sm:col-span-2">
                <label class="admin-label">شرح الدرس (نص منسّق)</label>
                <textarea name="content_html" rows="5" class="admin-input" placeholder="شرح للطالب يظهر بعد الفيديو" x-effect="$el.value = editing?.content_html || ''"></textarea>
            </div>
            <div x-show="!!editing" x-cloak>
                <label class="admin-label">الترتيب</label>
                <input type="number" min="1" name="position" class="admin-input" :value="editing?.position || ''" :disabled="!editing">
            </div>
            <div :class="editing ? '' : 'sm:col-span-2'">
                <label class="admin-label">الحالة</label>
                <select name="status" class="admin-input" x-effect="$el.value = editing?.status || 'draft'">
                    @foreach ($statusLabels as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <label class="flex items-center gap-2.5 rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-3 text-sm text-slate-700 sm:col-span-2">
                <input id="lesson_modal_locked" type="checkbox" name="is_locked" value="1" class="rounded border-slate-300 text-[var(--color-primary)] focus:ring-[var(--color-primary)]" :checked="!!editing?.is_locked">
                قفل الدرس
            </label>
            <p class="sm:col-span-2 text-xs text-slate-500" x-show="!!editing" x-cloak>
                <a :href="editing?.show_url" class="font-medium text-[var(--color-primary)] hover:underline">فتح صفحة الدرس</a>
                لتعيين الفيديو الرئيسي واختبار ما بعد المشاهدة ورفع الوسائط.
            </p>
            <div class="sm:col-span-2 flex flex-wrap gap-2 border-t border-slate-100 pt-4">
                <button type="submit" class="admin-btn admin-btn-primary" x-text="editing ? 'حفظ التعديلات' : 'إضافة الدرس'"></button>
                <button type="button" @click="close()" class="admin-btn admin-btn-ghost">إلغاء</button>
            </div>
        </form>
    </x-admin.modal>

    {{-- Media modal --}}
    <x-admin.modal show="modal === 'media' && mediaLesson()" max-width="max-w-2xl">
        <x-slot:header>
            <h3 class="text-base font-semibold text-slate-900">وسائط الدرس</h3>
            <p class="mt-0.5 text-xs text-slate-500" x-text="mediaLesson()?.title"></p>
        </x-slot:header>

        <template x-if="mediaLesson()">
            <div>
                <template x-for="lesson in [mediaLesson()]" :key="'upload-'+lesson.id">
                    <div
                        class="mb-4 space-y-3 rounded-2xl border border-slate-200 bg-slate-50/80 p-4"
                        x-data="mediaUploader({
                            csrf: @js(csrf_token()),
                            uploadUrl: lesson.media_store_url,
                            reloadOnSuccess: true,
                            showTypeSelect: true,
                        })"
                    >
                        <div class="grid gap-3 sm:grid-cols-2">
                            <div
                                class="admin-dropzone sm:col-span-2"
                                :class="{
                                    'is-dragging': dragging,
                                    'is-filled': !! file,
                                    'is-disabled': uploading,
                                }"
                                @dragenter.prevent="if (! uploading) dragging = true"
                                @dragover.prevent="if (! uploading) dragging = true"
                                @dragleave.prevent="dragging = false"
                                @drop.prevent="onDrop($event)"
                                @click="if (! uploading) $refs.fileInput.click()"
                                role="button"
                                tabindex="0"
                                @keydown.enter.prevent="if (! uploading) $refs.fileInput.click()"
                                @keydown.space.prevent="if (! uploading) $refs.fileInput.click()"
                            >
                                <input type="file" x-ref="fileInput" class="sr-only" @change="onPick($event)" :disabled="uploading" @click.stop>

                                <template x-if="! file">
                                    <div class="admin-dropzone-empty">
                                        <span class="admin-dropzone-icon" aria-hidden="true">
                                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/>
                                            </svg>
                                        </span>
                                        <p class="admin-dropzone-title">اسحب الملف هنا أو انقر للاختيار</p>
                                        <p class="admin-dropzone-sub">فيديو، صورة، PDF أو مرفق</p>
                                    </div>
                                </template>

                                <template x-if="file">
                                    <div class="admin-dropzone-file" @click.stop>
                                        <div class="admin-dropzone-file-icon" aria-hidden="true">
                                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                                        </div>
                                        <div class="min-w-0 flex-1 text-right">
                                            <p class="truncate text-sm font-semibold text-slate-900" x-text="file.name"></p>
                                            <p class="mt-0.5 text-xs text-slate-500" x-text="formatBytes(file.size)"></p>
                                        </div>
                                        <button type="button" class="admin-btn admin-btn-ghost admin-btn-sm" @click="clearFile()" :disabled="uploading">تغيير</button>
                                    </div>
                                </template>
                            </div>
                            <div class="sm:col-span-2">
                                <label class="admin-label">النوع</label>
                                <select x-model="type" class="admin-input" :disabled="uploading">
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
                            <button type="button" class="admin-btn admin-btn-dark disabled:opacity-50" @click="startUpload()" :disabled="uploading || ! file">
                                <span x-show="! uploading">رفع</span>
                                <span x-show="uploading" x-cloak>جارٍ الرفع…</span>
                            </button>
                            <button type="button" class="admin-btn admin-btn-ghost" @click="cancel()" x-show="uploading" x-cloak>إلغاء</button>
                        </div>
                    </div>
                </template>

                <ul class="space-y-4 text-sm">
                    <template x-for="asset in mediaLesson().media" :key="asset.id">
                        <li class="rounded-2xl border border-slate-200 p-3">
                            <div class="mb-2 flex items-center justify-between gap-3">
                                <div class="min-w-0">
                                    <p class="truncate font-semibold" x-text="asset.name"></p>
                                    <p class="text-xs text-slate-400" x-text="asset.type"></p>
                                </div>
                                <form method="POST" :action="asset.destroy_url" onsubmit="return confirm('حذف الملف؟');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="admin-btn admin-btn-danger admin-btn-sm">حذف</button>
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
                                <a :href="asset.preview_url" target="_blank" rel="noopener" class="admin-btn admin-btn-ghost admin-btn-sm">فتح / تنزيل</a>
                            </template>
                        </li>
                    </template>
                </ul>
                <p x-show="! mediaLesson().media.length" class="rounded-2xl border border-dashed border-slate-200 py-8 text-center text-sm text-slate-500">لا وسائط لهذا الدرس.</p>
            </div>
        </template>
    </x-admin.modal>
</div>
