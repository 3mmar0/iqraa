@php
    $videos = $course->lessons->flatMap(
        fn ($l) => $l->mediaAssets->where('type', 'video')->map(fn ($a) => ['asset' => $a, 'lesson' => $l])
    );
@endphp

<div class="space-y-6">
    <section class="rounded-2xl border border-slate-200 bg-gradient-to-l from-slate-50/80 to-white p-5">
        <h3 class="mb-3 text-sm font-semibold text-slate-900">رفع فيديو</h3>
        @if ($course->lessons->isEmpty())
            <p class="rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">أضف درساً أولاً من تبويب الدروس ثم ارفع الفيديو عليه.</p>
        @else
            <x-admin.media-uploader
                :lesson-base-url="url('/admin/lessons')"
                :lesson-id="$course->lessons->first()->id"
                :show-lesson-select="true"
                :lessons="$course->lessons"
                default-type="video"
                :show-type-select="false"
                accept="video/*,.mp4,.webm,.mov,.mkv,.m4v"
                button-label="رفع الفيديو"
                hint="اسحب الفيديو هنا أو انقر للاختيار"
            />
        @endif
    </section>

    <section>
        <h3 class="mb-4 font-semibold text-slate-900">الفيديوهات ({{ $videos->count() }})</h3>
        <div class="space-y-4">
            @forelse ($videos as $row)
                <article class="rounded-2xl border border-slate-200 p-4 transition hover:border-[var(--color-primary)]/25">
                    <div class="mb-3 flex flex-wrap items-start justify-between gap-2">
                        <div>
                            <p class="font-semibold text-slate-900">{{ $row['asset']->original_name ?? basename($row['asset']->path) }}</p>
                            <p class="text-xs text-slate-500">
                                {{ $row['lesson']->title }}
                                · {{ number_format(($row['asset']->size ?? 0) / 1048576, 1) }} MB
                            </p>
                        </div>
                        <form method="POST" action="{{ route('admin.lessons.media.destroy', [$row['lesson'], $row['asset']]) }}" onsubmit="return confirm('حذف الفيديو؟');">
                            @csrf
                            @method('DELETE')
                            <button class="admin-btn admin-btn-danger admin-btn-sm">حذف</button>
                        </form>
                    </div>
                    <x-admin.media-preview :asset="$row['asset']" />
                </article>
            @empty
                <x-admin.empty-state title="لا فيديوهات بعد" description="ارفع فيديوهات الدروس من النموذج أعلاه." />
            @endforelse
        </div>
    </section>
</div>
