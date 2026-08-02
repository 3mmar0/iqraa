@props([
    'uploadUrl' => null,
    'lessonBaseUrl' => null,
    'lessonId' => null,
    'defaultType' => '',
    'showTypeSelect' => true,
    'accept' => '*/*',
    'reloadOnSuccess' => true,
    'buttonLabel' => 'رفع',
    'showLessonSelect' => false,
    'lessons' => [],
    'hint' => 'اسحب الملف هنا أو انقر للاختيار',
])

<div
    {{ $attributes->class(['admin-uploader space-y-4']) }}
    x-data="mediaUploader({
        csrf: @js(csrf_token()),
        uploadUrl: @js($uploadUrl),
        lessonBaseUrl: @js($lessonBaseUrl),
        lessonId: @js($lessonId ? (string) $lessonId : null),
        defaultType: @js($defaultType),
        showTypeSelect: @js((bool) $showTypeSelect),
        reloadOnSuccess: @js((bool) $reloadOnSuccess),
    })"
>
    @if ($showLessonSelect && count($lessons))
        <div>
            <label class="admin-label">الدرس</label>
            <select x-model="lessonId" class="admin-input" :disabled="uploading">
                @foreach ($lessons as $lesson)
                    <option value="{{ $lesson->id }}">{{ $lesson->position }}. {{ $lesson->title }}</option>
                @endforeach
            </select>
        </div>
    @endif

    <div class="grid gap-4 {{ $showTypeSelect ? 'lg:grid-cols-[1fr_12rem]' : '' }}">
        <div
            class="admin-dropzone"
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
            <input
                type="file"
                x-ref="fileInput"
                accept="{{ $accept }}"
                class="sr-only"
                @change="onPick($event)"
                :disabled="uploading"
                @click.stop
            >

            <template x-if="! file">
                <div class="admin-dropzone-empty">
                    <span class="admin-dropzone-icon" aria-hidden="true">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/>
                        </svg>
                    </span>
                    <p class="admin-dropzone-title">{{ $hint }}</p>
                    <p class="admin-dropzone-sub">أو استخدم زر الاختيار من جهازك</p>
                </div>
            </template>

            <template x-if="file">
                <div class="admin-dropzone-file" @click.stop>
                    <div class="admin-dropzone-file-icon" aria-hidden="true">
                        <template x-if="previewKind === 'video'">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5l4.72-4.72a.75.75 0 011.28.53v11.38a.75.75 0 01-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 002.25-2.25v-9a2.25 2.25 0 00-2.25-2.25h-9A2.25 2.25 0 002.25 7.5v9a2.25 2.25 0 002.25 2.25z"/></svg>
                        </template>
                        <template x-if="previewKind === 'image'">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0022.5 18.75V5.25A2.25 2.25 0 0020.25 3H3.75A2.25 2.25 0 001.5 5.25v13.5A2.25 2.25 0 003.75 21z"/></svg>
                        </template>
                        <template x-if="previewKind === 'pdf' || previewKind === 'file'">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                        </template>
                    </div>
                    <div class="min-w-0 flex-1 text-right">
                        <p class="truncate text-sm font-semibold text-slate-900" x-text="file.name"></p>
                        <p class="mt-0.5 text-xs text-slate-500">
                            <span x-text="formatBytes(file.size)"></span>
                            <span x-show="previewKind"> · </span>
                            <span x-text="previewKind === 'video' ? 'فيديو' : (previewKind === 'image' ? 'صورة' : (previewKind === 'pdf' ? 'PDF' : 'ملف'))"></span>
                        </p>
                    </div>
                    <button
                        type="button"
                        class="admin-btn admin-btn-ghost admin-btn-sm shrink-0"
                        @click="clearFile()"
                        :disabled="uploading"
                    >تغيير</button>
                </div>
            </template>
        </div>

        @if ($showTypeSelect)
            <div>
                <label class="admin-label">النوع</label>
                <select x-model="type" class="admin-input" :disabled="uploading">
                    <option value="">تلقائي</option>
                    <option value="video">فيديو</option>
                    <option value="pdf">PDF</option>
                    <option value="image">صورة</option>
                    <option value="attachment">مرفق</option>
                    <option value="file">ملف</option>
                </select>
            </div>
        @endif
    </div>

    <template x-if="previewKind === 'video' && previewUrl">
        <video class="max-h-56 w-full rounded-2xl bg-black shadow-sm" controls preload="metadata" :src="previewUrl"></video>
    </template>
    <template x-if="previewKind === 'image' && previewUrl">
        <img :src="previewUrl" alt="معاينة" class="max-h-56 w-full rounded-2xl object-contain bg-white shadow-sm ring-1 ring-slate-200">
    </template>
    <template x-if="previewKind === 'pdf' && previewUrl">
        <iframe :src="previewUrl" class="h-56 w-full rounded-2xl border-0 bg-white shadow-sm ring-1 ring-slate-200" title="معاينة PDF"></iframe>
    </template>

    <div x-show="uploading || progress > 0" x-cloak class="admin-upload-progress">
        <div class="mb-1.5 flex justify-between text-xs font-medium text-slate-600">
            <span x-text="message || 'الرفع'"></span>
            <span><span x-text="progress"></span>%</span>
        </div>
        <div class="h-2.5 overflow-hidden rounded-full bg-slate-200">
            <div class="h-full rounded-full bg-[var(--color-primary)] transition-all duration-200" :style="`width: ${progress}%`"></div>
        </div>
        <p class="mt-1.5 text-xs text-slate-500" x-show="file">
            <span x-text="file ? formatBytes(Math.round((progress/100) * file.size)) : ''"></span>
            /
            <span x-text="file ? formatBytes(file.size) : ''"></span>
        </p>
    </div>

    <p class="rounded-xl bg-rose-50 px-3 py-2 text-sm text-rose-700" x-show="error" x-text="error" x-cloak></p>
    <p class="rounded-xl bg-emerald-50 px-3 py-2 text-sm text-emerald-800" x-show="message && ! uploading && ! error" x-text="message" x-cloak></p>

    <div class="flex flex-wrap gap-2">
        <button
            type="button"
            class="admin-btn admin-btn-primary disabled:opacity-50"
            @click="startUpload()"
            :disabled="uploading || ! file"
        >
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/>
            </svg>
            <span x-show="! uploading">{{ $buttonLabel }}</span>
            <span x-show="uploading" x-cloak>جارٍ الرفع…</span>
        </button>
        <button
            type="button"
            class="admin-btn admin-btn-ghost disabled:opacity-50"
            @click="cancel()"
            x-show="uploading"
            x-cloak
        >إلغاء</button>
    </div>
</div>
