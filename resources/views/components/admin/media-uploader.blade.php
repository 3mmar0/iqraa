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
])

<div
    {{ $attributes->class(['space-y-3 rounded-xl border border-slate-200 bg-slate-50/80 p-4']) }}
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
            <label class="mb-1 block text-xs text-slate-500">الدرس</label>
            <select x-model="lessonId" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm" :disabled="uploading">
                @foreach ($lessons as $lesson)
                    <option value="{{ $lesson->id }}">{{ $lesson->position }}. {{ $lesson->title }}</option>
                @endforeach
            </select>
        </div>
    @endif

    <div class="grid gap-3 {{ $showTypeSelect ? 'sm:grid-cols-2' : '' }}">
        <div class="{{ $showTypeSelect ? '' : '' }}">
            <label class="mb-1 block text-xs text-slate-500">الملف</label>
            <input
                type="file"
                x-ref="fileInput"
                accept="{{ $accept }}"
                class="block w-full text-sm"
                @change="onPick($event)"
                :disabled="uploading"
            >
        </div>
        @if ($showTypeSelect)
            <div>
                <label class="mb-1 block text-xs text-slate-500">النوع</label>
                <select x-model="type" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm" :disabled="uploading">
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
            <span x-text="file.name"></span>
            · <span x-text="formatBytes(file.size)"></span>
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
        <p class="mt-1 text-xs text-slate-500" x-show="file">
            <span x-text="file ? formatBytes(Math.round((progress/100) * file.size)) : ''"></span>
            /
            <span x-text="file ? formatBytes(file.size) : ''"></span>
        </p>
    </div>

    <p class="text-sm text-rose-700" x-show="error" x-text="error" x-cloak></p>
    <p class="text-sm text-emerald-700" x-show="message && ! uploading && ! error" x-text="message" x-cloak></p>

    <div class="flex flex-wrap gap-2">
        <button
            type="button"
            class="rounded-xl bg-[var(--color-primary)] px-4 py-2.5 text-sm font-semibold text-white hover:bg-[var(--color-primary-hover)] disabled:opacity-50"
            @click="startUpload()"
            :disabled="uploading || ! file"
        >
            <span x-show="! uploading">{{ $buttonLabel }}</span>
            <span x-show="uploading" x-cloak>جارٍ الرفع…</span>
        </button>
        <button
            type="button"
            class="rounded-xl border px-4 py-2.5 text-sm disabled:opacity-50"
            @click="cancel()"
            x-show="uploading"
            x-cloak
        >إلغاء</button>
    </div>
</div>
