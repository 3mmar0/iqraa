@php
    $panel = $coursePanel ?? 'admin';
    $statusLabels = ['draft' => 'مسودة', 'published' => 'منشور', 'archived' => 'مؤرشف', 'hidden' => 'مخفي'];
    $introExisting = $course->intro_video_path ? [
        'original_name' => $course->intro_video_original_name,
        'size' => $course->intro_video_size,
        'mime' => $course->intro_video_mime,
        'stream_url' => route($panel.'.courses.intro-video.stream', $course),
    ] : null;
    $introUploadBase = url('/'.$panel.'/courses/'.$course->id.'/intro-video/uploads');
@endphp

<div class="grid gap-6 lg:grid-cols-3">
    <div class="lg:col-span-2 space-y-6">
        @if ($course->image_path)
            <img src="{{ asset('storage/'.$course->image_path) }}" alt="" class="h-48 w-full rounded-2xl object-cover shadow-sm">
        @endif
        <div>
            <h3 class="mb-2 text-sm font-semibold text-slate-900">نبذة عن المقرر</h3>
            <p class="text-sm leading-7 text-slate-600 whitespace-pre-line">{{ $course->description ?: 'لا يوجد وصف.' }}</p>
        </div>
        <dl class="grid gap-3 sm:grid-cols-2">
            @foreach ([
                ['المحاضر', $course->instructor?->name ?? '—'],
                ['التصنيف', $course->category?->name ?? '—'],
                ['السنة الدراسية', $course->academicYear?->name ?? '—'],
                ['الفصل', $course->semester?->name ?? '—'],
                ['السعر', $course->price !== null ? number_format((float) $course->price, 2).' ر.س' : '—'],
                ['الحالة', $statusLabels[$course->status] ?? $course->status],
                ['الساعات', $course->hours ?? '—'],
            ] as [$label, $value])
                <div class="rounded-xl border border-slate-100 bg-slate-50/70 px-4 py-3">
                    <dt class="text-xs font-medium text-slate-500">{{ $label }}</dt>
                    <dd class="mt-1 text-sm font-semibold text-slate-900">{{ $value }}</dd>
                </div>
            @endforeach
        </dl>

        <section
            class="rounded-2xl border border-[var(--color-line)] bg-gradient-to-b from-white to-slate-50/80 p-5"
            x-data="courseIntroUpload({
                courseId: {{ $course->id }},
                csrf: @js(csrf_token()),
                existing: @js($introExisting),
                urls: {
                    init: @js(route($panel.'.courses.intro-video.init', $course)),
                    status: (id) => @js($introUploadBase).concat('/', id),
                    chunk: (id) => @js($introUploadBase).concat('/', id, '/chunk'),
                    complete: (id) => @js($introUploadBase).concat('/', id, '/complete'),
                    destroy: @js(route($panel.'.courses.intro-video.destroy', $course)),
                }
            })"
        >
            <div class="mb-4 flex flex-wrap items-start justify-between gap-3">
                <div>
                    <h3 class="font-semibold text-slate-900">الفيديو التوضيحي للمقرر</h3>
                    <p class="mt-1 text-xs text-slate-500">رفع مجزّأ مع إمكانية الاستئناف إذا انقطع الاتصال. الحد الأقصى 2 جيجابايت.</p>
                </div>
            </div>

            <template x-if="existing">
                <div class="mb-4 space-y-3 rounded-xl bg-slate-50 p-4">
                    <p class="text-sm font-medium text-slate-800" x-text="existing.original_name"></p>
                    <p class="text-xs text-slate-500" x-text="formatBytes(existing.size)"></p>
                    <video
                        class="max-h-64 w-full rounded-xl bg-black"
                        controls
                        preload="metadata"
                        :src="existing.stream_url"
                    ></video>
                    <button type="button" @click="deleteVideo()" class="admin-btn admin-btn-danger admin-btn-sm">حذف الفيديو</button>
                </div>
            </template>

            <template x-if="resumable && ! uploading">
                <div class="mb-4 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-950">
                    <p class="font-medium">يوجد رفع غير مكتمل: <span x-text="resumable.originalName"></span></p>
                    <p class="mt-1 text-xs">اختر نفس الملف ثم اضغط «استئناف الرفع».</p>
                    <div class="mt-3 flex flex-wrap gap-2">
                        <button type="button" @click="startUpload({ resume: true })" class="rounded-lg bg-amber-900 px-3 py-1.5 text-xs font-semibold text-white">استئناف الرفع</button>
                        <button type="button" @click="clearResume()" class="rounded-lg border border-amber-300 px-3 py-1.5 text-xs">تجاهل</button>
                    </div>
                </div>
            </template>

            <div class="space-y-3">
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
                        accept="video/*,.mp4,.webm,.mov,.mkv,.m4v"
                        class="sr-only"
                        x-ref="fileInput"
                        @change="onFilePicked($event)"
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
                            <p class="admin-dropzone-title">اسحب فيديو المقدمة هنا أو انقر للاختيار</p>
                            <p class="admin-dropzone-sub">MP4 / WEBM / MOV — حتى 2 جيجابايت</p>
                        </div>
                    </template>

                    <template x-if="file">
                        <div class="admin-dropzone-file" @click.stop>
                            <div class="admin-dropzone-file-icon" aria-hidden="true">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5l4.72-4.72a.75.75 0 011.28.53v11.38a.75.75 0 01-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 002.25-2.25v-9a2.25 2.25 0 00-2.25-2.25h-9A2.25 2.25 0 002.25 7.5v9a2.25 2.25 0 002.25 2.25z"/></svg>
                            </div>
                            <div class="min-w-0 flex-1 text-right">
                                <p class="truncate text-sm font-semibold text-slate-900" x-text="file.name"></p>
                                <p class="mt-0.5 text-xs text-slate-500" x-text="formatBytes(file.size)"></p>
                            </div>
                            <button type="button" class="admin-btn admin-btn-ghost admin-btn-sm shrink-0" @click="clearFile()" :disabled="uploading">تغيير</button>
                        </div>
                    </template>
                </div>

                <div class="flex flex-wrap gap-2">
                    <button
                        type="button"
                        class="admin-btn admin-btn-primary disabled:opacity-50"
                        @click="startUpload()"
                        :disabled="uploading || ! file"
                    >
                        <span x-show="! uploading">بدء الرفع</span>
                        <span x-show="uploading && ! assembling" x-cloak>جارٍ الرفع…</span>
                        <span x-show="assembling" x-cloak>تجميع الملف…</span>
                    </button>
                    <button
                        type="button"
                        class="admin-btn admin-btn-ghost disabled:opacity-50"
                        @click="pause()"
                        x-show="uploading && ! assembling"
                        x-cloak
                    >إيقاف مؤقت</button>
                </div>
            </div>

            <div class="mt-4" x-show="uploading || progress > 0" x-cloak>
                <div class="mb-1 flex justify-between text-xs text-slate-600">
                    <span x-text="message || 'الرفع'"></span>
                    <span><span x-text="progress"></span>%</span>
                </div>
                <div class="h-2.5 overflow-hidden rounded-full bg-slate-200">
                    <div
                        class="h-full rounded-full bg-[var(--color-primary)] transition-all duration-300"
                        :style="`width: ${progress}%`"
                    ></div>
                </div>
                <p class="mt-1 text-xs text-slate-500">
                    <span x-text="formatBytes(uploadedBytes)"></span>
                    /
                    <span x-text="formatBytes(totalBytes)"></span>
                </p>
            </div>

            <p class="mt-3 text-sm text-rose-700" x-show="error" x-text="error" x-cloak></p>
            <p class="mt-3 text-sm text-emerald-700" x-show="message && ! uploading && ! error" x-text="message" x-cloak></p>
        </section>
    </div>
    <div class="space-y-4">
        <div class="grid grid-cols-2 gap-3">
            <x-admin.kpi-card label="الدروس" :value="$course->lessons_count" />
            <x-admin.kpi-card label="الطلاب" :value="$course->enrollments_count" />
        </div>
        @if (($coursePanel ?? 'admin') === 'admin' && Route::has('admin.courses.assign-teacher'))
            <form method="POST" action="{{ route('admin.courses.assign-teacher', $course) }}" class="rounded-2xl border border-slate-200 bg-white p-4">
                @csrf
                <p class="mb-2 text-sm font-semibold text-slate-900">تعيين محاضر</p>
                <select name="instructor_user_id" class="admin-input mb-3">
                    @foreach (\App\Models\User::whereHas('roles', fn ($q) => $q->whereIn('slug', ['instructor', 'super_admin']))->orderBy('name')->get() as $instructor)
                        <option value="{{ $instructor->id }}" @selected($course->instructor_user_id === $instructor->id)>{{ $instructor->name }}</option>
                    @endforeach
                </select>
                <button class="admin-btn admin-btn-primary admin-btn-sm">حفظ</button>
            </form>
        @endif
    </div>
</div>
