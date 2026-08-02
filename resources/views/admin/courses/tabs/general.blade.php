@php
    $statusLabels = ['draft' => 'مسودة', 'published' => 'منشور', 'archived' => 'مؤرشف', 'hidden' => 'مخفي'];
    $introExisting = $course->intro_video_path ? [
        'original_name' => $course->intro_video_original_name,
        'size' => $course->intro_video_size,
        'mime' => $course->intro_video_mime,
        'stream_url' => route('admin.courses.intro-video.stream', $course),
    ] : null;
@endphp

<div class="grid gap-6 lg:grid-cols-3">
    <div class="lg:col-span-2 space-y-6">
        @if ($course->image_path)
            <img src="{{ asset('storage/'.$course->image_path) }}" alt="" class="h-40 w-full rounded-xl object-cover">
        @endif
        <p class="text-sm text-slate-600 whitespace-pre-line">{{ $course->description ?: 'لا يوجد وصف.' }}</p>
        <dl class="grid gap-3 sm:grid-cols-2 text-sm">
            <div><dt class="text-slate-500">المحاضر</dt><dd class="font-medium">{{ $course->instructor?->name ?? '—' }}</dd></div>
            <div><dt class="text-slate-500">التصنيف</dt><dd class="font-medium">{{ $course->category?->name ?? '—' }}</dd></div>
            <div><dt class="text-slate-500">السنة الدراسية</dt><dd class="font-medium">{{ $course->academicYear?->name ?? '—' }}</dd></div>
            <div><dt class="text-slate-500">الفصل</dt><dd class="font-medium">{{ $course->semester?->name ?? '—' }}</dd></div>
            <div><dt class="text-slate-500">السعر</dt><dd class="font-medium">{{ $course->price !== null ? number_format((float) $course->price, 2).' ر.س' : '—' }}</dd></div>
            <div><dt class="text-slate-500">الحالة</dt><dd class="font-medium">{{ $statusLabels[$course->status] ?? $course->status }}</dd></div>
            <div><dt class="text-slate-500">الساعات</dt><dd class="font-medium">{{ $course->hours ?? '—' }}</dd></div>
        </dl>

        <section
            class="rounded-2xl border border-[var(--color-line)] p-5"
            x-data="courseIntroUpload({
                courseId: {{ $course->id }},
                csrf: @js(csrf_token()),
                existing: @js($introExisting),
                urls: {
                    init: @js(route('admin.courses.intro-video.init', $course)),
                    status: (id) => @js(url('/admin/courses/'.$course->id.'/intro-video/uploads')).concat('/', id),
                    chunk: (id) => @js(url('/admin/courses/'.$course->id.'/intro-video/uploads')).concat('/', id, '/chunk'),
                    complete: (id) => @js(url('/admin/courses/'.$course->id.'/intro-video/uploads')).concat('/', id, '/complete'),
                    destroy: @js(route('admin.courses.intro-video.destroy', $course)),
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
                    <button type="button" @click="deleteVideo()" class="rounded-lg border border-rose-200 bg-rose-50 px-3 py-1.5 text-xs text-rose-800">حذف الفيديو</button>
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
                <input
                    type="file"
                    accept="video/*,.mp4,.webm,.mov,.mkv,.m4v"
                    class="block w-full text-sm"
                    x-ref="fileInput"
                    @change="onFilePicked($event)"
                    :disabled="uploading"
                >
                <div class="flex flex-wrap gap-2">
                    <button
                        type="button"
                        class="rounded-xl bg-[var(--color-primary)] px-4 py-2.5 text-sm font-semibold text-white hover:bg-[var(--color-primary-hover)] disabled:opacity-50"
                        @click="startUpload()"
                        :disabled="uploading || ! file"
                    >
                        <span x-show="! uploading">بدء الرفع</span>
                        <span x-show="uploading && ! assembling" x-cloak>جارٍ الرفع…</span>
                        <span x-show="assembling" x-cloak>تجميع الملف…</span>
                    </button>
                    <button
                        type="button"
                        class="rounded-xl border px-4 py-2.5 text-sm disabled:opacity-50"
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
        @if (Route::has('admin.courses.assign-teacher'))
            <form method="POST" action="{{ route('admin.courses.assign-teacher', $course) }}" class="rounded-xl border p-4">
                @csrf
                <p class="mb-2 text-sm font-medium">تعيين محاضر</p>
                <select name="instructor_user_id" class="mb-2 w-full rounded-lg border px-3 py-2 text-sm">
                    @foreach (\App\Models\User::whereHas('roles', fn ($q) => $q->whereIn('slug', ['instructor', 'super_admin']))->orderBy('name')->get() as $instructor)
                        <option value="{{ $instructor->id }}" @selected($course->instructor_user_id === $instructor->id)>{{ $instructor->name }}</option>
                    @endforeach
                </select>
                <button class="rounded-lg bg-[var(--color-primary)] px-3 py-1.5 text-xs text-white">حفظ</button>
            </form>
        @endif
    </div>
</div>
